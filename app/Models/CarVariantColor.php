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
        'color_type',
        'availability',
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

    public function variant()
    {
        return $this->belongsTo(CarVariant::class, 'car_variant_id');
    }

    public function images()
    {
        return $this->hasMany(CarVariantImage::class, 'car_variant_color_id');
    }

    public function getImageUrlAttribute()
    {
        $image = $this->images()->where('is_active', true)->orderByDesc('is_main')->orderBy('sort_order')->first();
        if ($image && $image->image_url) {
            return $image->image_url;
        }
        return 'https://via.placeholder.com/100x100/cccccc/ffffff?text=Color';
    }
}
