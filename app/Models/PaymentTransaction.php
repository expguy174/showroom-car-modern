<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTransaction extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUSES = ['pending', 'processing', 'completed', 'failed', 'cancelled', 'partial', 'refunded'];

    protected $fillable = [
        'order_id',
        'user_id',
        'payment_method_id',
        'transaction_number',
        'amount',
        'currency',
        'status',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 0, ',', '.') . ' ' . $this->currency;
    }

    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'completed' => 'Thành công',
            'failed' => 'Thất bại',
            'cancelled' => 'Đã hủy'
        ];
        return $statuses[$this->status] ?? $this->status;
    }
}
