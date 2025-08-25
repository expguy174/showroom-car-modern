<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refund extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_transaction_id',
        'amount',
        'reason',
        'status',
        'processed_at',
        'meta'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'meta' => 'json'
    ];

    public function paymentTransaction()
    {
        return $this->belongsTo(PaymentTransaction::class);
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 0, ',', '.') . ' VNĐ';
    }

    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'refunded' => 'Đã hoàn tiền',
            'failed' => 'Thất bại'
        ];
        return $statuses[$this->status] ?? $this->status;
    }
}
