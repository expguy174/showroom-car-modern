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
        'base_price',
        'current_price',
        'is_on_sale',
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
        'base_price' => 'decimal:2',
        'current_price' => 'decimal:2',
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
        return number_format($this->current_price, 0, ',', '.') . ' VNĐ';
    }

    public function getFormattedOriginalPriceAttribute()
    {
        return number_format($this->base_price, 0, ',', '.') . ' VNĐ';
    }

    public function getImageUrlAttribute()
    {
        // Prefer main_image_path then image_path, then first gallery image
        $paths = [
            $this->main_image_path ?? null,
            $this->image_path ?? null,
        ];
        if (is_array($this->gallery) && !empty($this->gallery)) {
            $first = $this->gallery[0] ?? null;
            if ($first) {
                $paths[] = $first;
            }
        }
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
        return $this->current_price;  // Giá cuối = giá bán hiện tại
    }

    public function getDiscountAmountAttribute()
    {
        if ($this->is_on_sale && $this->current_price < $this->base_price) {
            return $this->base_price - $this->current_price;
        }
        return 0;
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->is_on_sale && $this->current_price < $this->base_price) {
            return round((($this->base_price - $this->current_price) / $this->base_price) * 100, 1);
        }
        return 0;
    }

    public function getHasDiscountAttribute()
    {
        return $this->is_on_sale && $this->current_price < $this->base_price;
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

    /**
     * Get Vietnamese translation for category
     */
    public static function getCategoryTranslations()
    {
        return [
            // Standard format
            'interior' => 'Nội thất',
            'exterior' => 'Ngoại thất',
            'electronics' => 'Điện tử',
            'engine' => 'Động cơ',
            'wheels' => 'Bánh xe',
            'tires' => 'Lốp xe',
            'lighting' => 'Đèn chiếu sáng',
            'audio' => 'Âm thanh',
            'navigation' => 'Định vị',
            'safety' => 'An toàn',
            'performance' => 'Hiệu suất',
            'maintenance' => 'Bảo dưỡng',
            'tools' => 'Dụng cụ',
            'accessories' => 'Phụ kiện',
            'decoration' => 'Trang trí',
            'comfort' => 'Tiện nghi',
            'protection' => 'Bảo vệ',
            'cleaning' => 'Vệ sinh',
            'storage' => 'Lưu trữ',
            'utility' => 'Tiện ích',
            'car_care' => 'Chăm sóc xe',
            'other' => 'Khác',
            
            // Underscore format (tên_tên)
            'noi_that' => 'Nội thất',
            'ngoai_that' => 'Ngoại thất',
            'dien_tu' => 'Điện tử',
            'dong_co' => 'Động cơ',
            'banh_xe' => 'Bánh xe',
            'lop_xe' => 'Lốp xe',
            'den_chieu_sang' => 'Đèn chiếu sáng',
            'am_thanh' => 'Âm thanh',
            'dinh_vi' => 'Định vị',
            'an_toan' => 'An toàn',
            'hieu_suat' => 'Hiệu suất',
            'bao_duong' => 'Bảo dưỡng',
            'dung_cu' => 'Dụng cụ',
            'phu_kien' => 'Phụ kiện',
            'trang_tri' => 'Trang trí',
            'tien_nghi' => 'Tiện nghi',
            'bao_ve' => 'Bảo vệ',
            've_sinh' => 'Vệ sinh',
            'luu_tru' => 'Lưu trữ',
            'tien_ich' => 'Tiện ích',
            'cham_soc_xe' => 'Chăm sóc xe',
            'khac' => 'Khác',
            
            // Mixed formats
            'car_interior' => 'Nội thất xe',
            'car_exterior' => 'Ngoại thất xe',
            'car_electronics' => 'Điện tử xe',
            'engine_parts' => 'Phụ tùng động cơ',
            'wheel_accessories' => 'Phụ kiện bánh xe',
            'tire_accessories' => 'Phụ kiện lốp xe',
            'lighting_system' => 'Hệ thống chiếu sáng',
            'audio_system' => 'Hệ thống âm thanh',
            'navigation_system' => 'Hệ thống định vị',
            'safety_equipment' => 'Thiết bị an toàn',
            'performance_parts' => 'Phụ tùng hiệu suất',
            'maintenance_tools' => 'Dụng cụ bảo dưỡng',
            'utility_accessories' => 'Phụ kiện tiện ích',
            'decorative_items' => 'Đồ trang trí',
            'comfort_accessories' => 'Phụ kiện tiện nghi',
            'protective_gear' => 'Thiết bị bảo vệ',
            'cleaning_supplies' => 'Vật tư vệ sinh',
            'storage_solutions' => 'Giải pháp lưu trữ',
            'car_care_products' => 'Sản phẩm chăm sóc xe',
            'car_care_tools' => 'Dụng cụ chăm sóc xe',
            
            // Specific accessory types
            'trunk_organizer' => 'Hộp đựng đồ cốp xe',
            'phone_holder' => 'Giá đỡ điện thoại',
            'seat_cover' => 'Bọc ghế',
            'tire_pressure_monitor' => 'Cảm biến áp suất lốp',
            'seat_back_hook' => 'Móc treo sau ghế',
            'sunshade' => 'Tấm che nắng',
            'shampoo' => 'Dầu gội xe',
            'charger' => 'Sạc điện thoại',
            'dash_cam' => 'Camera hành trình',
            'blind_spot_mirror' => 'Gương điểm mù',
            'microfiber_towel' => 'Khăn microfiber'
        ];
    }

    /**
     * Get Vietnamese category name
     */
    public function getVietnameseCategoryAttribute()
    {
        $translations = self::getCategoryTranslations();
        return $translations[$this->category] ?? ucfirst($this->category);
    }

    /**
     * Get Vietnamese subcategory name
     */
    public function getVietnameseSubcategoryAttribute()
    {
        if (!$this->subcategory) return null;
        $translations = self::getCategoryTranslations();
        return $translations[$this->subcategory] ?? ucfirst($this->subcategory);
    }
}
