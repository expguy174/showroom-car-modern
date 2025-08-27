<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModelImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_model_id',
        'image_url',
        'alt_text',
        'title',
        'description',
        'image_type',
        'is_main',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function carModel()
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }

    public function getImageUrlAttribute()
    {
        if ($this->attributes['image_url'] ?? null) {
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
