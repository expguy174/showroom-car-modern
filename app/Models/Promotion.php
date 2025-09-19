<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
        'discount_value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'usage_count',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_count' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function getFormattedValueAttribute()
    {
        switch ($this->type) {
            case 'percentage':
                return $this->discount_value ? number_format($this->discount_value, 0) . '%' : '0%';
            case 'fixed_amount':
                return $this->discount_value ? number_format($this->discount_value, 0, ',', '.') . ' VNĐ' : '0 VNĐ';
            case 'free_shipping':
                return 'Miễn phí ship';
            case 'brand_specific':
                return 'Theo thương hiệu';
            default:
                return 'Liên hệ';
        }
    }

    public function getStatusAttribute()
    {
        $now = now();
        if ($this->start_date && $now < $this->start_date) {
            return 'upcoming';
        } elseif ($this->end_date && $now > $this->end_date) {
            return 'expired';
        } else {
            return 'active';
        }
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            'upcoming' => 'Sắp diễn ra',
            'active' => 'Đang diễn ra',
            'expired' => 'Đã kết thúc',
        ];
        
        return $statuses[$this->status] ?? 'Không xác định';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'upcoming' => 'bg-blue-100 text-blue-800',
            'active' => 'bg-green-100 text-green-800',
            'expired' => 'bg-gray-100 text-gray-800',
        ];
        
        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
} 