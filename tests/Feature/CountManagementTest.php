<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\CarVariant;
use App\Models\Accessory;
use App\Models\CartItem;
use App\Models\WishlistItem;
use App\Helpers\WishlistHelper;
use App\Helpers\CartHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class CountManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $carVariant;
    protected $accessory;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->carVariant = CarVariant::factory()->create(['is_active' => true]);
        $this->accessory = Accessory::factory()->create(['is_active' => true]);
    }

    /** @test */
    public function it_can_get_wishlist_count_for_authenticated_user()
    {
        $this->actingAs($this->user);

        // Create some wishlist items
        WishlistItem::create([
            'user_id' => $this->user->id,
            'item_type' => CarVariant::class,
            'item_id' => $this->carVariant->id,
            'is_active' => true
        ]);

        WishlistItem::create([
            'user_id' => $this->user->id,
            'item_type' => Accessory::class,
            'item_id' => $this->accessory->id,
            'is_active' => true
        ]);

        $count = WishlistHelper::getWishlistCount();
        
        $this->assertEquals(2, $count);
    }

    /** @test */
    public function it_can_get_wishlist_count_for_guest_user()
    {
        // Add items to session wishlist
        session(['wishlist' => [
            'car_variant_' . $this->carVariant->id => [
                'item_type' => 'car_variant',
                'item_id' => $this->carVariant->id,
                'added_at' => now()
            ],
            'accessory_' . $this->accessory->id => [
                'item_type' => 'accessory',
                'item_id' => $this->accessory->id,
                'added_at' => now()
            ]
        ]]);

        $count = WishlistHelper::getWishlistCount();
        
        $this->assertEquals(2, $count);
    }

    /** @test */
    public function it_can_get_cart_count_for_authenticated_user()
    {
        $this->actingAs($this->user);

        // Create some cart items
        CartItem::create([
            'user_id' => $this->user->id,
            'item_type' => 'car_variant',
            'item_id' => $this->carVariant->id,
            'quantity' => 2
        ]);

        CartItem::create([
            'user_id' => $this->user->id,
            'item_type' => 'accessory',
            'item_id' => $this->accessory->id,
            'quantity' => 1
        ]);

        $count = CartHelper::getCartCount();
        
        $this->assertEquals(3, $count); // 2 + 1 = 3
    }

    /** @test */
    public function it_can_get_cart_count_for_guest_user()
    {
        $sessionId = session()->getId();

        // Create some cart items for session
        CartItem::create([
            'session_id' => $sessionId,
            'item_type' => 'car_variant',
            'item_id' => $this->carVariant->id,
            'quantity' => 2
        ]);

        CartItem::create([
            'session_id' => $sessionId,
            'item_type' => 'accessory',
            'item_id' => $this->accessory->id,
            'quantity' => 1
        ]);

        $count = CartHelper::getCartCount();
        
        $this->assertEquals(3, $count);
    }

    /** @test */
    public function it_can_add_item_to_wishlist_and_return_updated_count()
    {
        $this->actingAs($this->user);

        $result = WishlistHelper::addToWishlist('car_variant', $this->carVariant->id);
        
        $this->assertTrue($result['success']);
        $this->assertEquals(1, $result['wishlist_count']);
        $this->assertEquals('Đã thêm vào wishlist!', $result['message']);
    }

    /** @test */
    public function it_can_add_item_to_cart_and_return_updated_count()
    {
        $this->actingAs($this->user);

        $result = CartHelper::addToCart('car_variant', $this->carVariant->id, 2);
        
        $this->assertTrue($result['success']);
        $this->assertEquals(2, $result['cart_count']);
        $this->assertEquals('Đã thêm vào giỏ hàng thành công', $result['message']);
    }

    /** @test */
    public function it_can_remove_item_from_wishlist_and_return_updated_count()
    {
        $this->actingAs($this->user);

        // Add item first
        WishlistHelper::addToWishlist('car_variant', $this->carVariant->id);
        
        // Remove item
        $result = WishlistHelper::removeFromWishlist('car_variant', $this->carVariant->id);
        
        $this->assertTrue($result['success']);
        $this->assertEquals(0, $result['wishlist_count']);
        $this->assertEquals('Đã xóa khỏi wishlist!', $result['message']);
    }

    /** @test */
    public function it_can_remove_item_from_cart_and_return_updated_count()
    {
        $this->actingAs($this->user);

        // Add item first
        $addResult = CartHelper::addToCart('car_variant', $this->carVariant->id, 2);
        $cartItemId = CartItem::where('user_id', $this->user->id)->first()->id;
        
        // Remove item
        $result = CartHelper::removeFromCart($cartItemId);
        
        $this->assertTrue($result['success']);
        $this->assertEquals(0, $result['cart_count']);
        $this->assertEquals('Đã xóa sản phẩm khỏi giỏ hàng!', $result['message']);
    }

    /** @test */
    public function it_can_update_cart_item_quantity()
    {
        $this->actingAs($this->user);

        // Add item first
        CartHelper::addToCart('car_variant', $this->carVariant->id, 1);
        $cartItemId = CartItem::where('user_id', $this->user->id)->first()->id;
        
        // Update quantity
        $result = CartHelper::updateCartItem($cartItemId, 3);
        
        $this->assertTrue($result['success']);
        $this->assertEquals(3, $result['cart_count']);
        $this->assertEquals(3, $result['quantity']);
    }

    /** @test */
    public function it_can_clear_cart()
    {
        $this->actingAs($this->user);

        // Add items first
        CartHelper::addToCart('car_variant', $this->carVariant->id, 2);
        CartHelper::addToCart('accessory', $this->accessory->id, 1);
        
        // Clear cart
        $result = CartHelper::clearCart();
        
        $this->assertTrue($result['success']);
        $this->assertEquals(0, $result['cart_count']);
        $this->assertEquals('Đã xóa toàn bộ giỏ hàng!', $result['message']);
    }

    /** @test */
    public function it_caches_wishlist_count_for_authenticated_user()
    {
        $this->actingAs($this->user);

        // Add item
        WishlistHelper::addToWishlist('car_variant', $this->carVariant->id);
        
        // Get count (should be cached)
        $count1 = WishlistHelper::getWishlistCount();
        
        // Clear cache
        WishlistHelper::clearWishlistCountCache();
        
        // Get count again (should not be cached)
        $count2 = WishlistHelper::getWishlistCount();
        
        $this->assertEquals(1, $count1);
        $this->assertEquals(1, $count2);
    }

    /** @test */
    public function it_caches_cart_count_for_authenticated_user()
    {
        $this->actingAs($this->user);

        // Add item
        CartHelper::addToCart('car_variant', $this->carVariant->id, 2);
        
        // Get count (should be cached)
        $count1 = CartHelper::getCartCount();
        
        // Clear cache
        CartHelper::clearCartCountCache();
        
        // Get count again (should not be cached)
        $count2 = CartHelper::getCartCount();
        
        $this->assertEquals(2, $count1);
        $this->assertEquals(2, $count2);
    }

    /** @test */
    public function it_prevents_duplicate_wishlist_items()
    {
        $this->actingAs($this->user);

        // Add item first time
        $result1 = WishlistHelper::addToWishlist('car_variant', $this->carVariant->id);
        
        // Try to add same item again
        $result2 = WishlistHelper::addToWishlist('car_variant', $this->carVariant->id);
        
        $this->assertTrue($result1['success']);
        $this->assertFalse($result2['success']);
        $this->assertEquals(1, $result1['wishlist_count']);
        $this->assertEquals(1, $result2['wishlist_count']);
        $this->assertEquals('Item đã có trong wishlist!', $result2['message']);
    }

    /** @test */
    public function it_prevents_duplicate_cart_items_and_increments_quantity()
    {
        $this->actingAs($this->user);

        // Add item first time
        $result1 = CartHelper::addToCart('car_variant', $this->carVariant->id, 1);
        
        // Add same item again
        $result2 = CartHelper::addToCart('car_variant', $this->carVariant->id, 2);
        
        $this->assertTrue($result1['success']);
        $this->assertTrue($result2['success']);
        $this->assertEquals(1, $result1['cart_count']);
        $this->assertEquals(3, $result2['cart_count']); // 1 + 2 = 3
        $this->assertEquals('Đã cập nhật số lượng trong giỏ hàng', $result2['message']);
    }

    /** @test */
    public function it_handles_wishlist_api_endpoints()
    {
        $this->actingAs($this->user);

        // Test add endpoint
        $response = $this->postJson('/wishlist/add', [
            'item_type' => 'car_variant',
            'item_id' => $this->carVariant->id
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'wishlist_count' => 1
                ]);

        // Test count endpoint
        $response = $this->getJson('/wishlist/count');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'wishlist_count' => 1
                ]);

        // Test remove endpoint
        $response = $this->postJson('/wishlist/remove', [
            'item_type' => 'car_variant',
            'item_id' => $this->carVariant->id
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'wishlist_count' => 0
                ]);
    }

    /** @test */
    public function it_handles_cart_api_endpoints()
    {
        $this->actingAs($this->user);

        // Test add endpoint
        $response = $this->postJson('/cart/add', [
            'item_type' => 'car_variant',
            'item_id' => $this->carVariant->id,
            'quantity' => 2
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'cart_count' => 2
                ]);

        // Test count endpoint
        $response = $this->getJson('/cart/count');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'cart_count' => 2
                ]);

        // Get cart item ID
        $cartItem = CartItem::where('user_id', $this->user->id)->first();

        // Test remove endpoint
        $response = $this->deleteJson("/cart/remove/{$cartItem->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'cart_count' => 0
                ]);
    }

    /** @test */
    public function it_handles_guest_user_wishlist()
    {
        // Test add to session wishlist
        $response = $this->postJson('/wishlist/add', [
            'item_type' => 'car_variant',
            'item_id' => $this->carVariant->id
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'wishlist_count' => 1
                ]);

        // Verify session data
        $this->assertArrayHasKey('car_variant_' . $this->carVariant->id, session('wishlist'));
    }

    /** @test */
    public function it_handles_guest_user_cart()
    {
        // Test add to session cart
        $response = $this->postJson('/cart/add', [
            'item_type' => 'car_variant',
            'item_id' => $this->carVariant->id,
            'quantity' => 2
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'cart_count' => 2
                ]);

        // Verify cart item exists
        $this->assertDatabaseHas('cart_items', [
            'session_id' => session()->getId(),
            'item_type' => 'car_variant',
            'item_id' => $this->carVariant->id,
            'quantity' => 2
        ]);
    }

    /** @test */
    public function it_can_check_bulk_wishlist_status_for_authenticated_user()
    {
        $this->actingAs($this->user);

        // Create a second car variant for testing
        $carVariant2 = CarVariant::factory()->create(['is_active' => true]);

        // Add one item to wishlist
        WishlistItem::create([
            'user_id' => $this->user->id,
            'item_type' => CarVariant::class,
            'item_id' => $this->carVariant->id,
            'is_active' => true
        ]);

        $response = $this->postJson('/wishlist/check-bulk', [
            'item_type' => 'car_variant',
            'item_ids' => [$this->carVariant->id, $carVariant2->id]
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'existing_ids' => [$this->carVariant->id]
                ]);
    }

    /** @test */
    public function it_can_check_bulk_wishlist_status_for_guest_user()
    {
        // Add items to session wishlist
        session(['wishlist' => [
            'car_variant_' . $this->carVariant->id => [
                'item_type' => 'car_variant',
                'item_id' => $this->carVariant->id,
                'added_at' => now()
            ]
        ]]);

        // Create a second car variant for testing
        $carVariant2 = CarVariant::factory()->create(['is_active' => true]);

        $response = $this->postJson('/wishlist/check-bulk', [
            'item_type' => 'car_variant',
            'item_ids' => [$this->carVariant->id, $carVariant2->id]
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'existing_ids' => [$this->carVariant->id]
                ]);
    }

    /** @test */
    public function it_can_check_bulk_wishlist_status_for_accessories()
    {
        $this->actingAs($this->user);

        // Create a second accessory for testing
        $accessory2 = Accessory::factory()->create(['is_active' => true]);

        // Add one accessory to wishlist
        WishlistItem::create([
            'user_id' => $this->user->id,
            'item_type' => Accessory::class,
            'item_id' => $this->accessory->id,
            'is_active' => true
        ]);

        $response = $this->postJson('/wishlist/check-bulk', [
            'item_type' => 'accessory',
            'item_ids' => [$this->accessory->id, $accessory2->id]
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'existing_ids' => [$this->accessory->id]
                ]);
    }
}
