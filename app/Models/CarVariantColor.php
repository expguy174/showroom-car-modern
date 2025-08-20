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
        'image_path',
        'image_url',
        'swatch_image',
        'exterior_image',
        'interior_image',
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

    public function getImageUrlAttribute()
    {
        $value = $this->attributes['image_url'] ?? null;
        if ($value) {
            // Check if it's already a full URL (starts with http or https)
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }
            // Otherwise, assume it's a local file path and prepend the storage path
            return asset('storage/' . $value);
        }
        return 'https://via.placeholder.com/100x100/cccccc/ffffff?text=Color';
    }
}
