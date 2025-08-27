<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Showroom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'dealership_id',
        'name',
        'code',
        'description',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'postal_code',
        'latitude',
        'longitude',
        'total_area',
        'display_capacity',
        'parking_capacity',
        'has_test_drive_track',
        'has_service_center',
        'has_parts_store',
        'has_cafe',
        'has_wifi',
        'has_playground',
        'opening_time',
        'closing_time',
        'working_days',
        'special_hours',
        'sales_staff_count',
        'service_staff_count',
        'manager_name',
        'manager_phone',
        'manager_email',
        'is_active',
        'is_featured',
        'status',
        'average_rating',
        'rating_count',
        'banner_path',
        'gallery',
        'virtual_tour_url',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'total_area' => 'integer',
        'display_capacity' => 'integer',
        'parking_capacity' => 'integer',
        'sales_staff_count' => 'integer',
        'service_staff_count' => 'integer',
        'has_test_drive_track' => 'boolean',
        'has_service_center' => 'boolean',
        'has_parts_store' => 'boolean',
        'has_cafe' => 'boolean',
        'has_wifi' => 'boolean',
        'has_playground' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'average_rating' => 'decimal:2',
        'working_days' => 'json',
        'gallery' => 'json',
    ];

    public function dealership()
    {
        return $this->belongsTo(Dealership::class);
    }

    public function customerProfiles()
    {
        return $this->hasMany(UserProfile::class, 'preferred_showroom_id');
    }

    public function serviceAppointments()
    {
        return $this->hasMany(ServiceAppointment::class);
    }

    public function testDrives()
    {
        return $this->hasMany(TestDrive::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function contactMessages()
    {
        return $this->hasMany(ContactMessage::class);
    }





    public function getBannerUrlAttribute(): string
    {
        if ($this->banner_path) {
            return asset('storage/' . $this->banner_path);
        }
        $encoded = urlencode($this->name ?? 'Showroom');
        return "https://via.placeholder.com/1200x300/111827/ffffff?text={$encoded}";
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([$this->address, $this->city, $this->state, $this->postal_code]);
        return implode(', ', $parts);
    }
}
