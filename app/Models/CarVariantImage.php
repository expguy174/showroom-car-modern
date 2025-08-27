<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarVariantImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_variant_id',
        'car_variant_color_id',
        'image_url',
        'alt_text',
        'title',
        'description',
        'image_type',
        'angle',
        'is_main',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function variant()
    {
        return $this->belongsTo(CarVariant::class, 'car_variant_id');
    }

    public function color()
    {
        return $this->belongsTo(CarVariantColor::class, 'car_variant_color_id');
    }

    public function getImageUrlAttribute()
    {
        $value = $this->attributes['image_url'] ?? null;
        if ($value) {
            // Check if it's an external URL (starts with http and not example.com)
            if (filter_var($value, FILTER_VALIDATE_URL) && !str_contains($value, 'example.com')) {
                return $value;
            }
            // If it's a local file path, prepend storage path
            if (!str_starts_with($value, 'http')) {
                return asset('storage/' . $value);
            }
            // If it's example.com or invalid URL, return placeholder
            return 'https://via.placeholder.com/400x300/4f46e5/ffffff?text=Image';
        }
        return 'https://via.placeholder.com/400x300/4f46e5/ffffff?text=Image';
    }
}
