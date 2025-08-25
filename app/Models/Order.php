<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUSES = ['pending', 'confirmed', 'shipping', 'delivered', 'cancelled'];
    public const PAYMENT_STATUSES = ['pending', 'processing', 'completed', 'failed', 'cancelled', 'partial', 'refunded'];

    protected $fillable = [
        'user_id',
        'total_price',
        'subtotal',
        'discount_total',
        'tax_total',
        'shipping_fee',
        'grand_total',
        'note',
        'payment_method_id',
        'payment_status',
        'transaction_id',
        'paid_at',
        'status',
        'order_number',
        'tracking_number',
        'estimated_delivery',
        'customer_notes',
        'internal_notes',
        'source',
        'ip_address',
        'user_agent',
        'referrer',
        'created_by',
        'updated_by',
        'cancelled_by',
        'cancelled_at',
        'cancellation_reason',
        'sales_person_id',
        'showroom_id',
        'delivery_date',
        'delivery_address',
        'delivery_notes',
        'billing_address_id',
        'shipping_address_id',
        'has_trade_in',
        'trade_in_brand',
        'trade_in_model',
        'trade_in_year',
        'trade_in_value',
        'trade_in_condition',
        'finance_option_id',
        'down_payment_amount',
        'monthly_payment_amount',
        'loan_term_months',
        'interest_rate',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'paid_at' => 'datetime',
        'estimated_delivery' => 'date',
        'cancelled_at' => 'datetime',
        'delivery_date' => 'date',
        'has_trade_in' => 'boolean',
        'trade_in_value' => 'decimal:2',
        'down_payment_amount' => 'decimal:2',
        'monthly_payment_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'trade_in_year' => 'integer',
        'loan_term_months' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // New relationships for payment and analytics
    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    // commissions() removed; not used in basic showroom setup

    public function logs()
    {
        return $this->hasMany(OrderLog::class);
    }

    public function getFormattedTotalPriceAttribute()
    {
        return number_format($this->total_price, 0, ',', '.') . ' VNĐ';
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    // Staff relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function canceller()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function salesPerson()
    {
        return $this->belongsTo(User::class, 'sales_person_id');
    }

    public function showroom()
    {
        return $this->belongsTo(Showroom::class);
    }

    public function financeOption()
    {
        return $this->belongsTo(FinanceOption::class);
    }

    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'pending' => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'shipping' => 'Đang giao',
            'delivered' => 'Đã giao',
            'cancelled' => 'Đã hủy'
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    public function getPaymentStatusDisplayAttribute()
    {
        $statuses = [
            'pending' => 'Chờ thanh toán',
            'processing' => 'Đang xử lý',
            'completed' => 'Đã thanh toán',
            'failed' => 'Thanh toán thất bại',
            'cancelled' => 'Đã hủy',
            'partial' => 'Thanh toán một phần',
            'refunded' => 'Đã hoàn tiền',
        ];
        return $statuses[$this->payment_status] ?? $this->payment_status;
    }

    // deposits removed
}