<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestDrive extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUSES = ['scheduled', 'confirmed', 'completed', 'cancelled'];
    public const TYPES = ['individual', 'group', 'virtual'];

    protected $fillable = [
        'test_drive_number',
        'user_id',
        'car_variant_id',
        'showroom_id',
        'preferred_date',
        'preferred_time',
        'duration_minutes',
        'location',
        'notes',
        'special_requirements',
        'has_experience',
        'experience_level',
        'status',
        'test_drive_type',
        'confirmed_at',
        'completed_at',
        'cancelled_at',
        'feedback',
        'satisfaction_rating',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'preferred_time' => 'string',
        'duration_minutes' => 'integer',
        'has_experience' => 'boolean',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'satisfaction_rating' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function carVariant()
    {
        return $this->belongsTo(CarVariant::class);
    }

    public function showroom()
    {
        return $this->belongsTo(Showroom::class);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'scheduled' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getStatusTextAttribute()
    {
        $texts = [
            'scheduled' => 'Đã đặt lịch',
            'confirmed' => 'Đã xác nhận',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        return $texts[$this->status] ?? 'Không xác định';
    }

    // Customer info accessors (from user relationship)
    public function getCustomerNameAttribute()
    {
        return $this->user ? $this->user->userProfile->name : 'N/A';
    }

    public function getCustomerEmailAttribute()
    {
        return $this->user ? $this->user->email : 'N/A';
    }

    public function getCustomerPhoneAttribute()
    {
        return $this->user && $this->user->userProfile ? $this->user->userProfile->phone : 'N/A';
    }

    public function getCarFullNameAttribute()
    {
        if (!$this->carVariant) return 'N/A';
        $brand = $this->carVariant->carModel->carBrand->name ?? '';
        $model = $this->carVariant->carModel->name ?? '';
        $variant = $this->carVariant->name ?? '';
        return trim("{$brand} {$model} {$variant}");
    }
} 