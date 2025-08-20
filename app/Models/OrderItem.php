<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item_type',
        'item_id',
        'color_id',
        'item_name',
        'item_sku',
        'item_metadata',
        'quantity',
        'price',
        'tax_amount',
        'discount_amount',
        'line_total',
        'notes',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function item()
    {
        return $this->morphTo();
    }

    public function color()
    {
        return $this->belongsTo(CarVariantColor::class, 'color_id');
    }

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'line_total' => 'decimal:2',
        'item_metadata' => 'json',
    ];

    public function getItemNameAttribute()
    {
        if ($this->item) {
            return $this->item->name;
        }
        return 'Sản phẩm không xác định';
    }

    public function getItemPriceAttribute()
    {
        if ($this->item) {
            return $this->item->price ?? $this->price;
        }
        return $this->price;
    }
}