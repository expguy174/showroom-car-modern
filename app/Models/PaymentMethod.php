<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'provider',
        'type',
        'is_active',
        'fee_flat',
        'fee_percent',
        'config',
        'sort_order',
        'notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'fee_flat' => 'decimal:2',
        'fee_percent' => 'decimal:2',
        'config' => 'json'
    ];

    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function getFeeAmountAttribute($amount)
    {
        return $this->fee_flat + ($amount * $this->fee_percent / 100);
    }

    public function getDisplayNameAttribute()
    {
        return $this->name . ' (' . $this->provider . ')';
    }
}
