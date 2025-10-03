<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarVariant extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Force Eloquent to use the fully-qualified class name as morph type
     * so it matches existing rows where reviewable_type = 'App\\Models\\CarVariant'.
     */
    protected $morphClass = \App\Models\CarVariant::class;

    protected $fillable = [
        'car_model_id',
        'name',
        'slug',
        'sku',
        'description',
        'short_description',
        'base_price',
        'current_price',
        'is_on_sale',
        'color_inventory',
        'is_active',
        'sort_order',
        'is_featured',
        'is_available',
        'is_new_arrival',
        'is_bestseller',
        'meta_title',
        'meta_description',
        'keywords',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'current_price' => 'decimal:2',
        'color_inventory' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_on_sale' => 'boolean',
        'is_available' => 'boolean',
        'is_new_arrival' => 'boolean',
        'is_bestseller' => 'boolean',
        'sort_order' => 'integer',
    ];
    
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        // When soft deleting CarVariant, also delete related records
        static::deleting(function ($carVariant) {
            // Only delete related records if this is a soft delete
            if (!$carVariant->isForceDeleting()) {
                // Hard delete related records to avoid constraint violations
                // since most related models don't use SoftDeletes
                $carVariant->specifications()->forceDelete();
                $carVariant->featuresRelation()->forceDelete();
                
                // For models that might use SoftDeletes, check first
                if (method_exists($carVariant->colors()->getModel(), 'bootSoftDeletes')) {
                    $carVariant->colors()->delete();
                } else {
                    $carVariant->colors()->forceDelete();
                }
                
                if (method_exists($carVariant->images()->getModel(), 'bootSoftDeletes')) {
                    $carVariant->images()->delete();
                } else {
                    $carVariant->images()->forceDelete();
                }
            }
        });
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }

    // Featured scope
    public function scopeFeatured($query)
    {
        return $query->where('is_active', 1)
            ->where('is_featured', 1)
            ->with(['carModel.carBrand', 'images'])
            ->orderByDesc('created_at');
    }

    public function colors()
    {
        return $this->hasMany(CarVariantColor::class);
    }

    /**
     * Đồng bộ tồn kho biến thể = tổng tồn kho các màu đang active.
     */
    // Removed stock_quantity recalculation since column no longer exists

    public function images()
    {
        return $this->hasMany(CarVariantImage::class);
    }

    // Đã loại bỏ quan hệ inventory vì không còn module kho

    public function specifications()
    {
        return $this->hasMany(CarVariantSpecification::class);
    }

    public function featuresRelation()
    {
        return $this->hasMany(CarVariantFeature::class, 'car_variant_id');
    }
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
    // Effective availability derived from color_inventory if present
    public function getEffectiveAvailableQuantityAttribute(): int
    {
        $inventory = $this->color_inventory ?? [];
        if (is_array($inventory)) {
            $sum = 0;
            foreach ($inventory as $colorId => $data) {
                $sum += (int) ($data['available'] ?? $data['quantity'] ?? 0);
            }
            return $sum;
        }
        return 0;
    }

    public function getSpecValue(string $name, ?string $category = null): ?string
    {
        $specs = $this->relationLoaded('specifications') ? $this->specifications : $this->specifications()->get();
        $record = $specs->first(function ($spec) use ($name, $category) {
            if ($category !== null && $spec->category !== $category) {
                return false;
            }
            return strtolower($spec->spec_name) === strtolower($name);
        });
        return $record->spec_value ?? null;
    }

    public function getFuelTypeAttribute(): ?string
    {
        return $this->getSpecValue('fuel_type', 'engine') ?? $this->getSpecValue('fuel_type');
    }
    public function getTransmissionAttribute(): ?string
    {
        return $this->getSpecValue('transmission', 'transmission') ?? $this->getSpecValue('transmission');
    }
    public function getSeatingCapacityAttribute(): ?int
    {
        $v = $this->getSpecValue('seating_capacity');
        return $v !== null ? (int) $v : null;
    }
    public function getPowerOutputAttribute(): ?string
    {
        return $this->getSpecValue('power_output', 'performance') ?? $this->getSpecValue('power_output');
    }
    public function getDrivetrainAttribute(): ?string
    {
        return $this->getSpecValue('drivetrain');
    }
    public function getEmissionStandardAttribute(): ?string
    {
        return $this->getSpecValue('emission_standard', 'emissions') ?? $this->getSpecValue('emission_standard');
    }
    public function getCo2EmissionAttribute(): ?string
    {
        return $this->getSpecValue('co2_emission', 'emissions') ?? $this->getSpecValue('co2_emission');
    }
    public function getFormattedPriceAttribute()
    {
        return number_format($this->current_price, 0, ',', '.') . ' VNĐ';
    }

    public function getFormattedOriginalPriceAttribute()
    {
        return number_format($this->base_price, 0, ',', '.') . ' VNĐ';
    }

    public function getMonthlyPaymentAttribute()
    {
        $monthlyPayment = $this->current_price / 60; // 60 tháng
        return 'Trả góp từ ' . number_format($monthlyPayment, 0, ',', '.') . ' VNĐ/tháng';
    }

    public function getImageUrlAttribute()
    {
        $image = $this->images()->first();
        if ($image && $image->image_url) {
            return $image->image_url;
        }
        $label = $this->name ?: ($this->carModel->name ?? 'Sản phẩm');
        return 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode($label);
    }

    public function getDiscountAmountAttribute()
    {
        if ($this->is_on_sale && $this->current_price < $this->base_price) {
            return $this->base_price - $this->current_price;
        }
        return 0;
    }

    public function getFinalPriceAttribute()
    {
        return $this->current_price;  // Giá cuối = giá bán hiện tại
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

    public function getPriceWithColorAdjustment(?int $colorId): float
    {
        $base = (float) $this->current_price;
        if ($colorId) {
            $color = $this->colors()->where('id', $colorId)->first();
            if ($color && $color->is_active) {
                $base += (float) ($color->price_adjustment ?? 0);
            }
        }
        return $base;
    }

    protected static function booted(): void
    {
        static::creating(function (CarVariant $variant) {
            if (empty($variant->slug) && !empty($variant->name)) {
                $variant->slug = static::generateUniqueSlug($variant->name);
            }
        });
        static::updating(function (CarVariant $variant) {
            if ($variant->isDirty('name') && empty($variant->slug)) {
                $variant->slug = static::generateUniqueSlug($variant->name, $variant->id);
            }
        });
    }

    protected static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = \Illuminate\Support\Str::slug($name);
        $slug = $base;
        $i = 2;
        while (static::query()
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()
        ) {
            $slug = $base . '-' . $i;
            $i++;
        }
        return $slug;
    }

    // Thông tin bảo hành
    public function getWarrantyInfoAttribute()
    {
        $years = $this->getSpecValue('warranty_years', 'warranty');
        $km = $this->getSpecValue('warranty_km', 'warranty');
        $info = [];
        if ($years) {
            $info[] = $years . ' năm';
        }
        if ($km) {
            $info[] = number_format((int) $km, 0, ',', '.') . ' km';
        }
        return implode(' / ', $info) ?: '3 năm / 100.000 km';
    }

    public function getWarrantyDisplayAttribute()
    {
        $details = $this->getSpecValue('warranty_details', 'warranty');
        return $this->warranty_info . ($details ? ' - ' . $details : '');
    }

    // Thông tin tài chính
    // Các accessor tài chính chi tiết được loại bỏ cùng với cột

    // Thông tin ắc quy
    public function getBatteryInfoAttribute()
    {
        $capacity = $this->getSpecValue('battery_capacity', 'battery') ?? $this->getSpecValue('battery_capacity');
        if (!$capacity) {
            return null;
        }

        $range = $this->getSpecValue('battery_range', 'battery') ?? $this->getSpecValue('battery_range');
        $time = $this->getSpecValue('charging_time', 'battery') ?? $this->getSpecValue('charging_time');
        $info = $capacity . ' kWh';
        if ($range) {
            $info .= ' - ' . $range . ' km';
        }
        if ($time) {
            $info .= ' (Sạc: ' . $time . 'h)';
        }
        return $info;
    }

    // Thông tin an toàn
    public function getSafetyFeaturesListAttribute()
    {
        $features = [];

        if ($this->has_abs) $features[] = 'ABS';
        if ($this->has_esp) $features[] = 'ESP';
        if ($this->has_traction_control) $features[] = 'Kiểm soát lực kéo';
        if ($this->has_lane_assist) $features[] = 'Hỗ trợ làn đường';
        if ($this->has_adaptive_cruise) $features[] = 'Cruise control thích ứng';
        if ($this->has_parking_sensors) $features[] = 'Cảm biến đỗ xe';
        if ($this->has_rear_camera) $features[] = 'Camera lùi';
        if ($this->has_360_camera) $features[] = 'Camera 360°';
        if ($this->airbag_count) $features[] = $this->airbag_count . ' túi khí';

        return implode(', ', $features) ?: 'ABS, EBD, ESP, 6 túi khí';
    }

    // Thông tin tiện nghi
    public function getComfortFeaturesListAttribute()
    {
        $features = [];

        if ($this->has_auto_climate) $features[] = 'Điều hòa tự động';
        if ($this->has_heated_seats) $features[] = 'Ghế sưởi';
        if ($this->has_ventilated_seats) $features[] = 'Ghế thông gió';
        if ($this->has_memory_seats) $features[] = 'Ghế nhớ vị trí';
        if ($this->has_power_seats) $features[] = 'Ghế điện';
        if ($this->has_sunroof) $features[] = 'Cửa sổ trời';
        if ($this->has_led_headlights) $features[] = 'Đèn LED';
        if ($this->has_xenon_headlights) $features[] = 'Đèn Xenon';
        if ($this->has_auto_headlights) $features[] = 'Đèn tự động';
        if ($this->has_rain_sensor) $features[] = 'Cảm biến mưa';
        if ($this->has_light_sensor) $features[] = 'Cảm biến ánh sáng';

        return implode(', ', $features) ?: 'Điều hòa tự động, GPS, Camera lùi';
    }

    // Thông tin công nghệ
    public function getTechnologyFeaturesListAttribute()
    {
        $features = [];

        if ($this->has_navigation) $features[] = 'GPS';
        if ($this->has_bluetooth) $features[] = 'Bluetooth';
        if ($this->has_apple_carplay) $features[] = 'Apple CarPlay';
        if ($this->has_android_auto) $features[] = 'Android Auto';
        if ($this->has_wireless_charging) $features[] = 'Sạc không dây';
        if ($this->has_usb_ports) $features[] = 'Cổng USB';
        if ($this->has_keyless_entry) $features[] = 'Khóa thông minh';
        if ($this->has_push_start) $features[] = 'Nút khởi động';
        if ($this->has_remote_start) $features[] = 'Khởi động từ xa';

        return implode(', ', $features) ?: 'GPS, Bluetooth, USB';
    }

    // Thông tin bảo dưỡng
    public function getFormattedMaintenanceCostAttribute()
    {
        $cost = $this->getSpecValue('maintenance_cost', 'maintenance');
        if ($cost) {
            return number_format((float) $cost, 0, ',', '.') . ' VNĐ';
        }
        return 'Liên hệ để biết thêm';
    }

    // Thông tin khí thải
    public function getEmissionInfoAttribute()
    {
        $info = [];
        $std = $this->emission_standard;
        $co2 = $this->co2_emission;
        if ($std) {
            $info[] = $std;
        }
        if ($co2) {
            $info[] = $co2 . ' g/km';
        }
        return implode(' - ', $info) ?: 'Euro 6';
    }
}
