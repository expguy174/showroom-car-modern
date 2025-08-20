<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'bank_name',
        'interest_rate',
        'tenure_months',
        'down_payment_amount',
        'monthly_payment_amount',
        'schedule',
        'status',
        'approved_at',
        'cancelled_at'
    ];

    protected $casts = [
        'interest_rate' => 'decimal:2',
        'down_payment_amount' => 'decimal:2',
        'monthly_payment_amount' => 'decimal:2',
        'tenure_months' => 'integer',
        'schedule' => 'json',
        'approved_at' => 'datetime',
        'cancelled_at' => 'datetime'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedMonthlyPaymentAttribute()
    {
        return number_format($this->monthly_payment_amount, 0, ',', '.') . ' VNĐ/tháng';
    }

    public function getFormattedDownPaymentAttribute()
    {
        return number_format($this->down_payment_amount, 0, ',', '.') . ' VNĐ';
    }

    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'draft' => 'Nháp',
            'active' => 'Hoạt động',
            'completed' => 'Hoàn thành',
            'defaulted' => 'Quá hạn',
            'cancelled' => 'Đã hủy'
        ];
        return $statuses[$this->status] ?? $this->status;
    }
}
