<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarBrand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'logo_path',
        'country',
        'description',
        'meta_title',
        'meta_description',
        'keywords',
        'founded_year',
        'website',
        'phone',
        'email',
        'address',
        'is_active',
        'is_featured',
        'sort_order',
        'total_models',
        'total_variants',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'founded_year' => 'integer',
        'total_models' => 'integer',
        'total_variants' => 'integer',
        'sort_order' => 'integer',
    ];

    public function carModels()
    {
        return $this->hasMany(CarModel::class);
    }

    public function activeCarModels()
    {
        return $this->hasMany(CarModel::class)->where('is_active', 1);
    }

    public function getLogoUrlAttribute()
    {
        if ($this->logo_path) {
            // Check if it's an external URL (starts with http)
            if (filter_var($this->logo_path, FILTER_VALIDATE_URL)) {
                return $this->logo_path;
            }
            
            // Check if file exists in public directory
            $publicPath = public_path($this->logo_path);
            if (file_exists($publicPath)) {
                return asset($this->logo_path);
            }
            
            // Check if it's in storage
            $storagePath = storage_path('app/public/' . $this->logo_path);
            if (file_exists($storagePath)) {
                return asset('storage/' . $this->logo_path);
            }
        }
        
        // Use specific logos for known brands
        $logos = [
            'Mercedes-Benz' => 'images/logos/mercedes.png',
            'BMW' => 'images/logos/bmw.png',
            'Audi' => 'images/logos/audi.png',
            'Toyota' => 'images/logos/toyota.png',
            'Honda' => 'images/logos/honda.png',
            'Hyundai' => 'images/logos/hyundai.png',
            'Ford' => 'images/logos/ford.png',
            'Volkswagen' => 'images/logos/volkswagen.png',
            'Tesla' => 'images/logos/tesla.png',
            'Lexus' => 'images/logos/lexus.png',
        ];

        // Return specific logo if available
        if (isset($logos[$this->name])) {
            $logoPath = $logos[$this->name];
            $fullPath = public_path($logoPath);
            if (file_exists($fullPath)) {
                return asset($logoPath);
            }
        }

        // Generate placeholder with proper encoding
        $carName = $this->name ?? 'Car';
        $encodedName = str_replace(' ', '+', $carName);

        return "https://placehold.co/200x200/4f46e5/ffffff?text={$encodedName}";
    }
}