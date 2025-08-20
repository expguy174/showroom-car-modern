<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModelImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_model_id',
        'image_path',
        'image_url',
        'title',
        'caption',
        'description',
        'image_type',
        'is_main',
        'is_active',
        'is_featured',
        'alt_text',
        'sort_order',
        'width',
        'height',
        'file_size',
        'file_format',
        'color_variant',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
    ];

    public function carModel()
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }

    public function getImageUrlAttribute()
    {
        if ($this->attributes['image_url']) {
            // Check if it's an external URL (starts with http)
            if (filter_var($this->attributes['image_url'], FILTER_VALIDATE_URL)) {
                return $this->attributes['image_url'];
            }
            // If it's a local file path, prepend storage path
            return asset('storage/' . $this->attributes['image_url']);
        }
        return 'https://via.placeholder.com/400x300/4f46e5/ffffff?text=Image';
    }
}
