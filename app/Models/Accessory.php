<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accessory extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Force Eloquent to use the fully-qualified class name as morph type
     * so it matches existing rows where reviewable_type = 'App\\Models\\Accessory'.
     */
    protected $morphClass = \App\Models\Accessory::class;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'short_description',
        'category',
        'subcategory',
        'compatible_car_brands',
        'compatible_car_models',
        'compatible_car_years',
        'price',
        'original_price',
        'is_on_sale',
        'sale_price',
        'sale_start_date',
        'sale_end_date',
        'stock_quantity',
        'stock_status',
        'gallery',
        'specifications',
        'features',
        'installation_instructions',
        'warranty_info',
        'warranty_months',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_featured',
        'is_bestseller',
        'is_popular',
        'sort_order',
        'is_active',
        'installation_service_available',
        'installation_fee',
        'installation_requirements',
        'installation_time_minutes',
        'warranty_terms',
        'warranty_contact',
        'return_policy',
        'support_contact',
        'return_policy_days',
        'weight',
        'dimensions',
        'material',
        'color_options',
        'is_new_arrival',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_bestseller' => 'boolean',
        'is_on_sale' => 'boolean',
        'installation_service_available' => 'boolean',
        'is_new_arrival' => 'boolean',
        'sale_start_date' => 'date',
        'sale_end_date' => 'date',
        'installation_fee' => 'decimal:2',
        'weight' => 'decimal:2',
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        // JSON columns
        'gallery' => 'array',
        'specifications' => 'array',
        'features' => 'array',
        'color_options' => 'array',
    ];

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function approvedReviews()
    {
        return $this->morphMany(Review::class, 'reviewable')->where('is_approved', true);
    }

            // Polymorphic relationships for cart and orders
    public function orderItems()
    {
        return $this->morphMany(OrderItem::class, 'item', 'item_type', 'item_id');
    }

    public function cartItems()
    {
        return $this->morphMany(CartItem::class, 'item', 'item_type', 'item_id');
    }



    // Accessors
    public function getFormattedPriceAttribute()
    {
        if ($this->has_discount && $this->discount_percentage > 0) {
            $discountedPrice = $this->price * (1 - $this->discount_percentage / 100);
            return number_format($discountedPrice, 0, ',', '.') . ' VNĐ';
        }
        return number_format($this->price, 0, ',', '.') . ' VNĐ';
    }

    public function getFormattedOriginalPriceAttribute()
    {
        return number_format($this->original_price ?? $this->price, 0, ',', '.') . ' VNĐ';
    }

    public function getImageUrlAttribute()
    {
        // Prefer main_image_path then image_path
        $paths = [
            $this->main_image_path ?? null,
            $this->image_path ?? null,
        ];
        foreach ($paths as $p) {
            if (!$p) continue;
            if (filter_var($p, FILTER_VALIDATE_URL)) return $p;
            return asset('storage/' . ltrim($p, '/'));
        }
        // Deterministic accessory photo pool
        $pool = [
            'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1583267746897-8b4fbc1d2a9a?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1517059224940-d4af9eec41e5?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1542367597-8849ebreplb?auto=format&fit=crop&w=1200&q=80',
        ];
        $seed = crc32(($this->category ?? '') . '|' . ($this->name ?? ''));
        return $pool[(int) ($seed % count($pool))];
    }

    // Stock status helper based on stock_quantity
    public function getStockStatusTextAttribute()
    {
        $status = $this->stock_status;
        return match ($status) {
            'in_stock' => 'Còn hàng',
            'low_stock' => 'Sắp hết',
            'out_of_stock' => 'Hết hàng',
            'discontinued' => 'Ngừng kinh doanh',
            default => $status,
        };
    }

    public function getFinalPriceAttribute()
    {
        if ($this->is_on_sale && $this->sale_price !== null) {
            return $this->sale_price;
        }
        return $this->price;
    }
    
    /**
     * Tự động tính toán is_available dựa trên stock_quantity
     */
    // Availability derived from quantity and status
    public function getIsAvailableAttribute()
    {
        if ($this->stock_status === 'out_of_stock' || $this->stock_status === 'discontinued') {
            return false;
        }
        return ($this->stock_quantity ?? 0) > 0;
    }
}
