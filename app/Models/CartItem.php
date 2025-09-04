<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'item_type',
        'item_id',
        'color_id',
        'options_signature',
        'quantity',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
        return $this->item->price ?? 0;
    }
}