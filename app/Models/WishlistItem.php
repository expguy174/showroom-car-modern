<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishlistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'item_type',
        'item_id',
        'notes',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->morphTo();
    }

    public function carVariant()
    {
        return $this->belongsTo(CarVariant::class, 'item_id')->where('item_type', CarVariant::class);
    }

    public function accessory()
    {
        return $this->belongsTo(Accessory::class, 'item_id')->where('item_type', Accessory::class);
    }

    public function getItemNameAttribute()
    {
        if ($this->item) {
            return $this->item->name ?? $this->item->title ?? 'Unknown Item';
        }
        return 'Unknown Item';
    }

    public function getItemPriceAttribute()
    {
        if ($this->item) {
            return $this->item->price ?? $this->item->formatted_price ?? 'N/A';
        }
        return 'N/A';
    }

    public function getItemImageAttribute()
    {
        if ($this->item) {
            return $this->item->image_url ?? $this->item->main_image_url ?? null;
        }
        return null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByItemType($query, $itemType)
    {
        return $query->where('item_type', $itemType);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', '>=', 8);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('priority', 'desc')->orderBy('created_at', 'desc');
    }
}
