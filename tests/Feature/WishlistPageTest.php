<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\CarVariant;
use App\Models\Accessory;
use App\Models\WishlistItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class WishlistPageTest extends TestCase
{
    use RefreshDatabase, WithFaker;

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
    public function authenticated_user_can_view_wishlist_page()
    {
        $this->actingAs($this->user);

        $response = $this->get('/wishlist');

        $response->assertStatus(200);
        $response->assertViewIs('user.wishlist.index');
        $response->assertViewHas('wishlistItems');
    }

    /** @test */
    public function guest_user_can_view_wishlist_page()
    {
        $response = $this->get('/wishlist');

        $response->assertStatus(200);
        $response->assertViewIs('user.wishlist.index');
        $response->assertViewHas('wishlistItems');
    }

    /** @test */
    public function wishlist_page_shows_empty_state_when_no_items()
    {
        $this->actingAs($this->user);

        $response = $this->get('/wishlist');

        $response->assertStatus(200);
        $response->assertSee('Danh sách yêu thích trống');
        $response->assertSee('Khám phá sản phẩm');
    }

    /** @test */
    public function wishlist_page_shows_items_when_user_has_wishlist_items()
    {
        $this->actingAs($this->user);

        // Create wishlist items
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

        $response = $this->get('/wishlist');

        $response->assertStatus(200);
        $response->assertSee($this->carVariant->name);
        $response->assertSee($this->accessory->name);
        $response->assertSee('2 sản phẩm yêu thích');
        $response->assertSee('Xóa tất cả');
    }

    /** @test */
    public function wishlist_page_shows_session_items_for_guest_user()
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

        $response = $this->get('/wishlist');

        $response->assertStatus(200);
        $response->assertSee($this->carVariant->name);
        $response->assertSee($this->accessory->name);
        $response->assertSee('2 sản phẩm yêu thích');
    }

    /** @test */
    public function wishlist_page_has_filter_and_sort_options()
    {
        $this->actingAs($this->user);

        // Create some wishlist items
        WishlistItem::create([
            'user_id' => $this->user->id,
            'item_type' => CarVariant::class,
            'item_id' => $this->carVariant->id,
            'is_active' => true
        ]);

        $response = $this->get('/wishlist');

        $response->assertStatus(200);
        $response->assertSee('Lọc theo:');
        $response->assertSee('Sắp xếp:');
        $response->assertSee('Tất cả');
        $response->assertSee('Xe hơi');
        $response->assertSee('Phụ kiện');
        $response->assertSee('Mới nhất');
        $response->assertSee('Cũ nhất');
        $response->assertSee('Giá thấp → cao');
        $response->assertSee('Giá cao → thấp');
        $response->assertSee('Tên A-Z');
    }

    /** @test */
    public function wishlist_page_has_proper_data_attributes()
    {
        $this->actingAs($this->user);

        // Create wishlist item
        WishlistItem::create([
            'user_id' => $this->user->id,
            'item_type' => CarVariant::class,
            'item_id' => $this->carVariant->id,
            'is_active' => true
        ]);

        $response = $this->get('/wishlist');

        $response->assertStatus(200);
        $response->assertSee('data-type="car_variant"');
        $response->assertSee('data-item-id="' . $this->carVariant->id . '"');
        $response->assertSee('data-price="' . $this->carVariant->price . '"');
        $response->assertSee('data-name="' . strtolower($this->carVariant->name) . '"');
    }

    /** @test */
    public function wishlist_page_has_wishlist_toggle_buttons()
    {
        $this->actingAs($this->user);

        // Create wishlist item
        WishlistItem::create([
            'user_id' => $this->user->id,
            'item_type' => CarVariant::class,
            'item_id' => $this->carVariant->id,
            'is_active' => true
        ]);

        $response = $this->get('/wishlist');

        $response->assertStatus(200);
        $response->assertSee('js-wishlist-toggle');
        $response->assertSee('data-item-type="car_variant"');
        $response->assertSee('data-item-id="' . $this->carVariant->id . '"');
    }

    /** @test */
    public function wishlist_page_has_message_container()
    {
        $response = $this->get('/wishlist');

        $response->assertStatus(200);
        $response->assertSee('id="message-container"');
    }

    /** @test */
    public function wishlist_page_has_empty_state_container()
    {
        $response = $this->get('/wishlist');

        $response->assertStatus(200);
        $response->assertSee('id="empty-state"');
    }

    /** @test */
    public function wishlist_page_has_filter_section()
    {
        $this->actingAs($this->user);

        // Create wishlist item
        WishlistItem::create([
            'user_id' => $this->user->id,
            'item_type' => CarVariant::class,
            'item_id' => $this->carVariant->id,
            'is_active' => true
        ]);

        $response = $this->get('/wishlist');

        $response->assertStatus(200);
        $response->assertSee('id="filter-section"');
        $response->assertSee('id="filter-type"');
        $response->assertSee('id="sort-by"');
    }

    /** @test */
    public function wishlist_page_has_wishlist_grid()
    {
        $this->actingAs($this->user);

        // Create wishlist item
        WishlistItem::create([
            'user_id' => $this->user->id,
            'item_type' => CarVariant::class,
            'item_id' => $this->carVariant->id,
            'is_active' => true
        ]);

        $response = $this->get('/wishlist');

        $response->assertStatus(200);
        $response->assertSee('id="wishlist-grid"');
    }

    /** @test */
    public function wishlist_page_includes_required_scripts()
    {
        $response = $this->get('/wishlist');

        $response->assertStatus(200);
        $response->assertSee('wishlist.js');
    }

    /** @test */
    public function wishlist_page_includes_required_styles()
    {
        $response = $this->get('/wishlist');

        $response->assertStatus(200);
        $response->assertSee('wishlist.css');
    }

    /** @test */
    public function wishlist_page_handles_missing_items_gracefully()
    {
        $this->actingAs($this->user);

        // Create wishlist item with non-existent item
        WishlistItem::create([
            'user_id' => $this->user->id,
            'item_type' => CarVariant::class,
            'item_id' => 99999, // Non-existent ID
            'is_active' => true
        ]);

        $response = $this->get('/wishlist');

        $response->assertStatus(200);
        // Should not crash and should show empty state or other items
        $response->assertViewIs('user.wishlist.index');
    }

    /** @test */
    public function wishlist_page_orders_items_by_created_at_desc()
    {
        $this->actingAs($this->user);

        // Create two car variants
        $carVariant1 = CarVariant::factory()->create(['is_active' => true]);
        $carVariant2 = CarVariant::factory()->create(['is_active' => true]);

        // Create wishlist items with different timestamps
        WishlistItem::create([
            'user_id' => $this->user->id,
            'item_type' => CarVariant::class,
            'item_id' => $carVariant1->id,
            'is_active' => true,
            'created_at' => now()->subDay()
        ]);

        WishlistItem::create([
            'user_id' => $this->user->id,
            'item_type' => CarVariant::class,
            'item_id' => $carVariant2->id,
            'is_active' => true,
            'created_at' => now()
        ]);

        $response = $this->get('/wishlist');

        $response->assertStatus(200);
        
        // Get the view data
        $viewData = $response->viewData('wishlistItems');
        
        // Check that items are ordered by created_at desc
        $this->assertEquals($carVariant2->id, $viewData->first()->item_id);
        $this->assertEquals($carVariant1->id, $viewData->last()->item_id);
    }
}
