<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceAppointment extends Model
{
    use HasFactory;

    public const STATUSES = ['scheduled','confirmed','in_progress','completed','cancelled','no_show','rescheduled'];
    public const APPOINTMENT_TYPES = ['maintenance','repair','inspection','warranty_work','recall_service','emergency','other'];
    public const PRIORITIES = ['low','medium','high','urgent'];
    // Removed payment constants due to simplified schema

    protected $fillable = [
        'user_id',
        'showroom_id',
        'assigned_technician_id',
        'car_variant_id',
        'vehicle_vin',
        'vehicle_registration',
        'vehicle_year',
        'current_mileage',
        'appointment_number',
        'appointment_date',
        'appointment_time',
        'estimated_duration',
        'appointment_type',
        'requested_services',
        'service_description',
        'customer_complaints',
        'special_instructions',
        'status',
        'priority',
        'is_warranty_work',
        'warranty_number',
        'warranty_expiry_date',
        'estimated_cost',
        'actual_cost',
        'parts_cost',
        'labor_cost',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'payment_status',
        'payment_method',
        'payment_date',
        'actual_start_time',
        'actual_end_time',
        'work_performed',
        'parts_used',
        'technician_notes',
        'quality_check_passed',
        'quality_check_by',
        'quality_check_notes',
        'vehicle_ready',
        'vehicle_ready_time',
        'customer_notified',
        'customer_notified_time',
        'customer_satisfaction',
        'customer_feedback',
        'customer_recommend',
        'notes',
        'documents',
        'tags',
        'satisfaction_rating',
        'feedback',
    ];

    protected $casts = [
        'vehicle_year' => 'integer',
        'current_mileage' => 'integer',
        'estimated_duration' => 'integer',
        'appointment_date' => 'date',
        'warranty_expiry_date' => 'date',
        'payment_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'parts_cost' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'customer_satisfaction' => 'decimal:2',
        'is_warranty_work' => 'boolean',
        'quality_check_passed' => 'boolean',
        'vehicle_ready' => 'boolean',
        'customer_notified' => 'boolean',
        'customer_recommend' => 'boolean',
        'documents' => 'json',
        'satisfaction_rating' => 'integer',
        // Time columns are stored as TIME in DB, cast to string (HH:MM:SS)
        'appointment_time' => 'string',
        'actual_start_time' => 'string',
        'actual_end_time' => 'string',
        'vehicle_ready_time' => 'string',
        'customer_notified_time' => 'string',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function showroom()
    {
        return $this->belongsTo(Showroom::class);
    }

    public function carVariant()
    {
        return $this->belongsTo(CarVariant::class);
    }

    public function assignedTechnician()
    {
        return $this->belongsTo(User::class, 'assigned_technician_id');
    }

    public function qualityChecker()
    {
        return $this->belongsTo(User::class, 'quality_check_by');
    }

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

    public function getFormattedActualCostAttribute(): ?string
    {
        return $this->actual_cost !== null
            ? number_format($this->actual_cost, 0, ',', '.') . ' VNĐ'
            : null;
    }

    public function getFormattedTotalAmountAttribute(): ?string
    {
        return $this->total_amount !== null
            ? number_format($this->total_amount, 0, ',', '.') . ' VNĐ'
            : null;
    }
}
