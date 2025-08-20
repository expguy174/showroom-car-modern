<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarVariantOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_variant_id',
        'option_name',
        'option_code',
        'description',
        'category',
        'availability',
        'type',
        'price',
        'package_price',
        'is_included',
        'image_path',
        'icon_path',
        'is_active',
        'is_popular',
        'is_recommended',
        'sort_order',
        'stock_quantity',
        'specifications',
        'compatibility_notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'package_price' => 'decimal:2',
        'is_included' => 'boolean',
        'is_popular' => 'boolean',
        'is_recommended' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'stock_quantity' => 'integer',
        'specifications' => 'string',
    ];

    public function carVariant()
    {
        return $this->belongsTo(CarVariant::class);
    }

    public function getImageUrlAttribute()
    {
        $value = $this->image_path;
        if (!$value) return null;
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        return asset('storage/' . ltrim($value, '/'));
    }

    public function getIconUrlAttribute()
    {
        $value = $this->icon_path;
        if (!$value) return null;
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        return asset('storage/' . ltrim($value, '/'));
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . ' VNĐ';
    }

    public function getFormattedOriginalPriceAttribute()
    {
        return number_format($this->package_price ?? $this->price, 0, ',', '.') . ' VNĐ';
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->package_price && $this->package_price > $this->price) {
            return round((($this->package_price - $this->price) / $this->package_price) * 100, 1);
        }
        return 0;
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeIncluded($query)
    {
        return $query->where('is_included', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeRecommended($query)
    {
        return $query->where('is_recommended', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('option_name');
    }
}
