<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\CarVariant;
use App\Models\Accessory;
use App\Models\WishlistItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class WishlistSystemTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $carVariant;
    protected $accessory;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user
        $this->user = User::factory()->create();
        
        // Create test car variant
        $this->carVariant = CarVariant::factory()->create();
        
        // Create test accessory
        $this->accessory = Accessory::factory()->create();
    }

    /** @test */
    public function guest_can_add_car_variant_to_wishlist()
    {
        $response = $this->postJson('/wishlist/add', [
            'item_type' => 'car_variant',
            'item_id' => $this->carVariant->id
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Đã thêm vào danh sách yêu thích!'
                ]);

        // Check session wishlist
        $this->assertTrue(session()->has('wishlist'));
        $wishlistData = session()->get('wishlist');
        $itemKey = 'car_variant_' . $this->carVariant->id;
        $this->assertArrayHasKey($itemKey, $wishlistData);
    }

    /** @test */
    public function guest_can_add_accessory_to_wishlist()
    {
        $response = $this->postJson('/wishlist/add', [
            'item_type' => 'accessory',
            'item_id' => $this->accessory->id
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Đã thêm vào danh sách yêu thích!'
                ]);

        // Check session wishlist
        $this->assertTrue(session()->has('wishlist'));
        $wishlistData = session()->get('wishlist');
        $itemKey = 'accessory_' . $this->accessory->id;
        $this->assertArrayHasKey($itemKey, $wishlistData);
    }

    /** @test */
    public function authenticated_user_can_add_car_variant_to_wishlist()
    {
        $response = $this->actingAs($this->user)
                        ->postJson('/wishlist/add', [
                            'item_type' => 'car_variant',
                            'item_id' => $this->carVariant->id
                        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Đã thêm vào danh sách yêu thích!'
                ]);

        // Check database
        $this->assertDatabaseHas('wishlist_items', [
            'user_id' => $this->user->id,
            'item_type' => CarVariant::class,
            'item_id' => $this->carVariant->id
        ]);
    }

    /** @test */
    public function cannot_add_duplicate_item_to_wishlist()
    {
        // Add item first time
        $this->actingAs($this->user)
             ->postJson('/wishlist/add', [
                 'item_type' => 'car_variant',
                 'item_id' => $this->carVariant->id
             ]);

        // Try to add same item again
        $response = $this->actingAs($this->user)
                        ->postJson('/wishlist/add', [
                            'item_type' => 'car_variant',
                            'item_id' => $this->carVariant->id
                        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => false,
                    'message' => 'Sản phẩm đã có trong danh sách yêu thích!'
                ]);
    }

    /** @test */
    public function cannot_add_nonexistent_item_to_wishlist()
    {
        $response = $this->actingAs($this->user)
                        ->postJson('/wishlist/add', [
                            'item_type' => 'car_variant',
                            'item_id' => 99999
                        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => false,
                    'message' => 'Sản phẩm không tồn tại!'
                ]);
    }

    /** @test */
    public function can_remove_item_from_wishlist()
    {
        // Add item first
        $this->actingAs($this->user)
             ->postJson('/wishlist/add', [
                 'item_type' => 'car_variant',
                 'item_id' => $this->carVariant->id
             ]);

        // Remove item
        $response = $this->actingAs($this->user)
                        ->postJson('/wishlist/remove', [
                            'item_type' => 'car_variant',
                            'item_id' => $this->carVariant->id
                        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Đã xóa khỏi danh sách yêu thích!'
                ]);

        // Check database
        $this->assertDatabaseMissing('wishlist_items', [
            'user_id' => $this->user->id,
            'item_type' => CarVariant::class,
            'item_id' => $this->carVariant->id
        ]);
    }

    /** @test */
    public function can_check_if_item_is_in_wishlist()
    {
        // Add item first
        $this->actingAs($this->user)
             ->postJson('/wishlist/add', [
                 'item_type' => 'car_variant',
                 'item_id' => $this->carVariant->id
             ]);

        // Check if item is in wishlist
        $response = $this->actingAs($this->user)
                        ->getJson('/wishlist/check?item_type=car_variant&item_id=' . $this->carVariant->id);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'in_wishlist' => true
                ]);
    }

    /** @test */
    public function can_check_bulk_items_in_wishlist()
    {
        // Add items
        $this->actingAs($this->user)
             ->postJson('/wishlist/add', [
                 'item_type' => 'car_variant',
                 'item_id' => $this->carVariant->id
             ]);

        $this->actingAs($this->user)
             ->postJson('/wishlist/add', [
                 'item_type' => 'accessory',
                 'item_id' => $this->accessory->id
             ]);

        // Check bulk
        $response = $this->actingAs($this->user)
                        ->postJson('/wishlist/check-bulk', [
                            'item_type' => 'car_variant',
                            'item_ids' => [$this->carVariant->id, 99999]
                        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'existing_ids' => [$this->carVariant->id]
                ]);
    }

    /** @test */
    public function can_get_wishlist_count()
    {
        // Add items
        $this->actingAs($this->user)
             ->postJson('/wishlist/add', [
                 'item_type' => 'car_variant',
                 'item_id' => $this->carVariant->id
             ]);

        $this->actingAs($this->user)
             ->postJson('/wishlist/add', [
                 'item_type' => 'accessory',
                 'item_id' => $this->accessory->id
             ]);

        // Get count
        $response = $this->actingAs($this->user)
                        ->getJson('/wishlist/count');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'wishlist_count' => 2
                ]);
    }

    /** @test */
    public function can_clear_wishlist()
    {
        // Add items
        $this->actingAs($this->user)
             ->postJson('/wishlist/add', [
                 'item_type' => 'car_variant',
                 'item_id' => $this->carVariant->id
             ]);

        $this->actingAs($this->user)
             ->postJson('/wishlist/add', [
                 'item_type' => 'accessory',
                 'item_id' => $this->accessory->id
             ]);

        // Clear wishlist
        $response = $this->actingAs($this->user)
                        ->postJson('/wishlist/clear');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Đã xóa tất cả sản phẩm khỏi danh sách yêu thích!',
                    'wishlist_count' => 0
                ]);

        // Check database
        $this->assertDatabaseCount('wishlist_items', 0);
    }

    /** @test */
    public function can_migrate_session_wishlist_to_database()
    {
        // Add item to session wishlist as guest
        $this->postJson('/wishlist/add', [
            'item_type' => 'car_variant',
            'item_id' => $this->carVariant->id
        ]);

        // Migrate to database after login
        $response = $this->actingAs($this->user)
                        ->postJson('/wishlist/migrate-session');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'migrated_count' => 1
                ]);

        // Check database
        $this->assertDatabaseHas('wishlist_items', [
            'user_id' => $this->user->id,
            'item_type' => CarVariant::class,
            'item_id' => $this->carVariant->id
        ]);

        // Check session is cleared
        $this->assertFalse(session()->has('wishlist'));
    }

    /** @test */
    public function wishlist_helper_methods_work_correctly()
    {
        $helper = new \App\Helpers\WishlistHelper();

        // Test isInWishlist
        $this->assertFalse(\App\Helpers\WishlistHelper::isInWishlist('car_variant', $this->carVariant->id));

        // Test addToWishlist
        $result = \App\Helpers\WishlistHelper::addToWishlist('car_variant', $this->carVariant->id);
        $this->assertTrue($result['success']);

        // Test isInWishlist after adding
        $this->assertTrue(\App\Helpers\WishlistHelper::isInWishlist('car_variant', $this->carVariant->id));

        // Test getWishlistCount
        $this->assertEquals(1, \App\Helpers\WishlistHelper::getWishlistCount());

        // Test removeFromWishlist
        $result = \App\Helpers\WishlistHelper::removeFromWishlist('car_variant', $this->carVariant->id);
        $this->assertTrue($result['success']);

        // Test isInWishlist after removing
        $this->assertFalse(\App\Helpers\WishlistHelper::isInWishlist('car_variant', $this->carVariant->id));
    }

    /** @test */
    public function validation_works_correctly()
    {
        // Test invalid item_type
        $response = $this->actingAs($this->user)
                        ->postJson('/wishlist/add', [
                            'item_type' => 'invalid_type',
                            'item_id' => $this->carVariant->id
                        ]);

        $response->assertStatus(422);

        // Test missing item_id
        $response = $this->actingAs($this->user)
                        ->postJson('/wishlist/add', [
                            'item_type' => 'car_variant'
                        ]);

        $response->assertStatus(422);

        // Test invalid item_id
        $response = $this->actingAs($this->user)
                        ->postJson('/wishlist/add', [
                            'item_type' => 'car_variant',
                            'item_id' => 'invalid_id'
                        ]);

        $response->assertStatus(422);
    }
}
