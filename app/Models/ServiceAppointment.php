<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceAppointment extends Model
{
    use HasFactory;

    public const STATUSES = ['scheduled','confirmed','in_progress','completed','cancelled','no_show','rescheduled'];
    public const PRIORITIES = ['low','medium','high','urgent'];
    // Removed payment constants due to simplified schema

    protected $fillable = [
        'user_id',
        'showroom_id',
        'service_id',
        'car_variant_id',
        'vehicle_registration',
        'current_mileage',
        'appointment_number',
        'appointment_date',
        'appointment_time',
        'requested_services',
        'service_description',
        'status',
        'priority',
        'is_warranty_work',
        'estimated_cost',
        'satisfaction_rating',
        'feedback',
    ];

    protected $casts = [
        'current_mileage' => 'integer',
        'appointment_date' => 'date',
        'appointment_time' => 'string',
        'estimated_cost' => 'decimal:2',
        'is_warranty_work' => 'boolean',
        'satisfaction_rating' => 'integer',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function showroom()
    {
        return $this->belongsTo(Showroom::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function carVariant()
    {
        return $this->belongsTo(CarVariant::class);
    }

    // Removed relationships for fields not in current schema

    public function getStatusDisplayAttribute(): string
    {
        $statuses = [
            'scheduled' => 'Đã lên lịch',
            'confirmed' => 'Đã xác nhận',
            'in_progress' => 'Đang thực hiện',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            'no_show' => 'Không đến',
            'rescheduled' => 'Đã dời lịch',
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    public function getPriorityDisplayAttribute(): string
    {
        $priorities = [
            'low' => 'Thấp',
            'medium' => 'Trung bình',
            'high' => 'Cao',
            'urgent' => 'Khẩn',
        ];
        return $priorities[$this->priority] ?? $this->priority;
    }

    public function getFormattedEstimatedCostAttribute(): ?string
    {
        return $this->estimated_cost !== null
            ? number_format($this->estimated_cost, 0, ',', '.') . ' VNĐ'
            : null;
    }

    // Removed accessors for fields not in current schema
}
