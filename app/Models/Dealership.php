<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dealership extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'type',
        'description',
        'phone',
        'email',
        'website',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'business_license',
        'tax_code',
        'established_date',
        'owner_name',
        'owner_phone',
        'owner_email',
        'provides_sales',
        'provides_service',
        'provides_parts',
        'provides_finance',
        'provides_insurance',
        'opening_time',
        'closing_time',
        'working_days',
        'special_hours',
        'is_active',
        'is_featured',
        'status',
        'average_rating',
        'rating_count',
        'logo_path',
        'banner_path',
        'gallery',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'established_date' => 'date',
        'opening_time' => 'string',
        'closing_time' => 'string',
        'working_days' => 'json',
        'gallery' => 'json',
        'provides_sales' => 'boolean',
        'provides_service' => 'boolean',
        'provides_parts' => 'boolean',
        'provides_finance' => 'boolean',
        'provides_insurance' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'average_rating' => 'decimal:2'
    ];

    public function showrooms()
    {
        return $this->hasMany(Showroom::class);
    }

    public function getLogoUrlAttribute()
    {
        if ($this->logo_path) {
            return asset('storage/' . $this->logo_path);
        }
        return "https://via.placeholder.com/200x200/4f46e5/ffffff?text=" . urlencode($this->name);
    }

    public function getBannerUrlAttribute()
    {
        if ($this->banner_path) {
            return asset('storage/' . $this->banner_path);
        }
        return "https://via.placeholder.com/800x400/4f46e5/ffffff?text=" . urlencode($this->name);
    }

    public function getWorkingDaysDisplayAttribute()
    {
        if (!$this->working_days) {
            return 'Thứ 2 - Thứ 7';
        }
        
        $days = [
            'monday' => 'Thứ 2',
            'tuesday' => 'Thứ 3', 
            'wednesday' => 'Thứ 4',
            'thursday' => 'Thứ 5',
            'friday' => 'Thứ 6',
            'saturday' => 'Thứ 7',
            'sunday' => 'Chủ nhật'
        ];
        
        $displayDays = [];
        foreach ($this->working_days as $day) {
            if (isset($days[$day])) {
                $displayDays[] = $days[$day];
            }
        }
        
        return implode(', ', $displayDays);
    }

    public function getServicesDisplayAttribute()
    {
        $services = [];
        if ($this->provides_sales) $services[] = 'Bán xe';
        if ($this->provides_service) $services[] = 'Bảo dưỡng';
        if ($this->provides_parts) $services[] = 'Phụ tùng';
        if ($this->provides_finance) $services[] = 'Tài chính';
        if ($this->provides_insurance) $services[] = 'Bảo hiểm';
        
        return implode(', ', $services);
    }
}
