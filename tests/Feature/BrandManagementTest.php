<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\CarVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class BrandManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Tạo admin user
        $this->adminUser = User::factory()->create([
            'is_admin' => true
        ]);
    }

    /** @test */
    public function admin_can_view_brands_list()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.cars.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.cars.index');
    }

    /** @test */
    public function admin_can_create_new_brand()
    {
        $brandData = [
            'name' => 'Test Brand',
            'country' => 'Test Country',
            'description' => 'Test Description',
            'founded_year' => 2000,
            'website' => 'https://testbrand.com',
            'phone' => '123456789',
            'email' => 'test@testbrand.com',
            'address' => 'Test Address',
            'meta_title' => 'Test Meta Title',
            'meta_description' => 'Test Meta Description',
            'keywords' => 'test, brand, car',
            'is_active' => true,
            'is_featured' => false,
            'sort_order' => 1,
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.cars.store'), $brandData);

        $response->assertRedirect(route('admin.cars.index'));
        $this->assertDatabaseHas('car_brands', [
            'name' => 'Test Brand',
            'slug' => 'test-brand',
            'country' => 'Test Country',
        ]);
    }

    /** @test */
    public function admin_can_edit_brand()
    {
        $brand = CarBrand::factory()->create([
            'name' => 'Original Name',
            'country' => 'Original Country'
        ]);

        $updatedData = [
            'name' => 'Updated Name',
            'country' => 'Updated Country',
            'description' => 'Updated Description',
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.cars.update', $brand), $updatedData);

        $response->assertRedirect(route('admin.cars.index'));
        $this->assertDatabaseHas('car_brands', [
            'id' => $brand->id,
            'name' => 'Updated Name',
            'slug' => 'updated-name',
            'country' => 'Updated Country',
        ]);
    }

    /** @test */
    public function admin_cannot_delete_brand_with_models()
    {
        $brand = CarBrand::factory()->create();
        $model = CarModel::factory()->create(['car_brand_id' => $brand->id]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.cars.destroy', $brand));

        $response->assertRedirect(route('admin.cars.index'));
        $this->assertDatabaseHas('car_brands', ['id' => $brand->id]);
    }

    /** @test */
    public function admin_can_delete_brand_without_models()
    {
        $brand = CarBrand::factory()->create();

        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.cars.destroy', $brand));

        $response->assertRedirect(route('admin.cars.index'));
        $this->assertSoftDeleted('car_brands', ['id' => $brand->id]);
    }

    /** @test */
    public function brand_statistics_are_updated_correctly()
    {
        $brand = CarBrand::factory()->create();
        
        // Tạo 2 models
        $model1 = CarModel::factory()->create(['car_brand_id' => $brand->id]);
        $model2 = CarModel::factory()->create(['car_brand_id' => $brand->id]);
        
        // Tạo 3 variants cho model1
        CarVariant::factory()->count(3)->create(['car_model_id' => $model1->id]);
        
        // Tạo 2 variants cho model2
        CarVariant::factory()->count(2)->create(['car_model_id' => $model2->id]);

        // Cập nhật thống kê
        $brand->refresh();
        
        $this->assertEquals(2, $brand->total_models);
        $this->assertEquals(5, $brand->total_variants);
    }

    /** @test */
    public function brand_slug_is_generated_automatically()
    {
        $brandData = [
            'name' => 'Test Brand Name',
            'country' => 'Test Country',
        ];

        $this->actingAs($this->adminUser)
            ->post(route('admin.cars.store'), $brandData);

        $this->assertDatabaseHas('car_brands', [
            'name' => 'Test Brand Name',
            'slug' => 'test-brand-name',
        ]);
    }

    /** @test */
    public function brand_name_must_be_unique()
    {
        $existingBrand = CarBrand::factory()->create(['name' => 'Existing Brand']);

        $brandData = [
            'name' => 'Existing Brand', // Trùng tên
            'country' => 'Test Country',
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.cars.store'), $brandData);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseCount('car_brands', 1);
    }
}
