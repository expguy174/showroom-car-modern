<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarVariantColor extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_variant_id',
        'color_name',
        'color_code',
        'hex_code',
        'rgb_code',
        'price_adjustment',
        'stock_quantity',
        'is_free',
        'description',
        'material',
        'is_popular',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price_adjustment' => 'decimal:2',
        'is_free' => 'boolean',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'stock_quantity' => 'integer',
        'sort_order' => 'integer',
    ];

    public function carVariant()
    {
        return $this->belongsTo(CarVariant::class, 'car_variant_id');
    }

    public function getImageUrlAttribute()
    {
        // Lấy ảnh chính từ bảng car_variant_images
        $mainImage = $this->carVariant->images()
            ->where('car_variant_color_id', $this->id)
            ->where('image_type', 'color_main')
            ->first();
            
        if ($mainImage && $mainImage->image_url) {
            return $mainImage->image_url;
        }
        
        return 'https://via.placeholder.com/100x100/cccccc/ffffff?text=Color';
    }
}
