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
        'description',
        'price',
        'original_price',
        'brand',
        'compatibility',
        'warranty',
        'is_active',
        'is_featured',
        'average_rating',
        'rating_count',
        'has_discount',
        'discount_percentage',
        'is_bestseller',
        'stock_quantity',
        'stock_status',
        'material',
        'weight',
        'dimensions',
        'color_options',
        'installation_service_available',
        'installation_fee',
        'warranty_months',
        'slug',
        'code',
        'sku',
        'short_description',
        'category',
        'subcategory',
        'compatible_car_brands',
        'compatible_car_models',
        'compatible_car_years',
        'cost_price',
        'wholesale_price',
        'is_on_sale',
        'sale_price',
        'sale_start_date',
        'sale_end_date',
        'min_stock_level',
        'max_stock_level',
        'track_quantity',
        'allow_backorder',
        'backorder_quantity',
        'gallery',
        'video_url',
        'manual_pdf_path',
        'specifications',
        'features',
        'installation_instructions',
        'warranty_info',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_popular',
        'is_new',
        'sort_order',
        'is_visible',
        'status',
        'view_count',
        'purchase_count',
        'is_new_arrival',
        
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'average_rating' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'has_discount' => 'boolean',
        'is_bestseller' => 'boolean',
        'is_available' => 'boolean',
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

    public function getStockStatusAttribute()
    {
        return $this->is_available ? 'Còn hàng' : 'Hết hàng';
    }

    public function getDiscountAmountAttribute()
    {
        if ($this->has_discount && $this->discount_percentage > 0) {
            return $this->price * ($this->discount_percentage / 100);
        }
        return 0;
    }

    public function getFinalPriceAttribute()
    {
        if ($this->has_discount && $this->discount_percentage > 0) {
            return $this->price * (1 - $this->discount_percentage / 100);
        }
        return $this->price;
    }
    
    /**
     * Tự động tính toán is_available dựa trên stock_quantity
     */
    public function getIsAvailableAttribute()
    {
        // Nếu có trường is_available trong database, ưu tiên sử dụng
        if (isset($this->attributes['is_available'])) {
            return (bool) $this->attributes['is_available'];
        }
        
        // Tự động tính toán dựa trên stock_quantity
        return ($this->stock_quantity ?? 0) > 0;
    }
}
