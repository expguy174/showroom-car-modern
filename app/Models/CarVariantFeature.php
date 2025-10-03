<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarVariantFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_variant_id',
        'feature_name',
        'description',
        'feature_code',
        'category',
        'availability',
        'importance',
        'price',
        'is_included',
        'is_active',
        'is_featured',
        'is_popular',
        'is_recommended',
        'sort_order',
    ];

    protected $casts = [
        'is_included' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function carVariant()
    {
        return $this->belongsTo(CarVariant::class);
    }

    // Removed featurePackages relationship as CarFeaturePackage model doesn't exist

    public function getFormattedPriceAttribute()
    {
        if ($this->price > 0) {
            return number_format($this->price, 0, ',', '.') . ' VNĐ';
        }
        return 'Miễn phí';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeIncluded($query)
    {
        return $query->where('is_included', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByAvailability($query, $availability)
    {
        return $query->where('availability', $availability);
    }

    public function scopeByImportance($query, $importance)
    {
        return $query->where('importance', $importance);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('feature_name');
    }
}
