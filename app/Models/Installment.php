<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    public const STATUSES = ['pending', 'paid', 'overdue', 'cancelled'];

    protected $fillable = [
        'order_id',
        'user_id',
        'payment_transaction_id',
        'installment_number',
        'amount',
        'due_date',
        'bank_name',
        'interest_rate',
        'tenure_months',
        'down_payment_amount',
        'monthly_payment_amount',
        'schedule',
        'status',
        'paid_at',
        'approved_at',
        'cancelled_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'datetime',
        'interest_rate' => 'decimal:2',
        'down_payment_amount' => 'decimal:2',
        'monthly_payment_amount' => 'decimal:2',
        'installment_number' => 'integer',
        'tenure_months' => 'integer',
        'schedule' => 'json',
        'paid_at' => 'datetime',
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

    public function paymentTransaction()
    {
        return $this->belongsTo(PaymentTransaction::class);
    }

    public function financeOption()
    {
        // Access finance option through order relationship
        return $this->hasOneThrough(
            FinanceOption::class,
            Order::class,
            'id', // Foreign key on orders table
            'id', // Foreign key on finance_options table
            'order_id', // Local key on installments table
            'finance_option_id' // Local key on orders table
        );
    }

    public function getFormattedMonthlyPaymentAttribute()
    {
        return $this->monthly_payment_amount !== null
            ? number_format($this->monthly_payment_amount, 0, ',', '.') . ' VNĐ/tháng'
            : null;
    }

    public function getFormattedDownPaymentAttribute()
    {
        return $this->down_payment_amount !== null
            ? number_format($this->down_payment_amount, 0, ',', '.') . ' VNĐ'
            : null;
    }

    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'pending' => 'Chờ thanh toán',
            'paid' => 'Đã thanh toán',
            'overdue' => 'Quá hạn',
            'cancelled' => 'Đã hủy'
        ];
        return $statuses[$this->status] ?? $this->status;
    }
}
