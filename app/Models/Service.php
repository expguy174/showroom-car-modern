<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public const CATEGORIES = ['maintenance', 'repair', 'diagnostic', 'cosmetic', 'emergency'];

    protected $fillable = [
        'name',
        'code',
        'description',
        'category',
        'duration_minutes',
        'price',
        'is_active',
        'is_featured',
        'sort_order',
        'compatible_car_brands',
        'compatible_car_models',
        'compatible_car_years',
        'requirements',
        'warranty_months',
        'service_center_required',
        'parts_included',
        'labor_included',
        'oil_change_included',
        'filter_change_included',
        'inspection_included',
        'notes'
    ];

    protected $casts = [
        'compatible_car_brands' => 'array',
        'compatible_car_models' => 'array',
        'compatible_car_years' => 'array',
        'price' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'service_center_required' => 'boolean',
        'parts_included' => 'boolean',
        'labor_included' => 'boolean',
        'oil_change_included' => 'boolean',
        'filter_change_included' => 'boolean',
        'inspection_included' => 'boolean'
    ];

    // Relationships
    public function carBrands()
    {
        return $this->belongsToMany(CarBrand::class, 'service_car_brand');
    }

    public function serviceAppointments()
    {
        return $this->hasMany(ServiceAppointment::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
