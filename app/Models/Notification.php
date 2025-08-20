<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIconAttribute()
    {
        $icons = [
            'order_status' => 'fas fa-shopping-cart',
            'promotion' => 'fas fa-gift',
            'test_drive' => 'fas fa-car',
            'lead' => 'fas fa-file-signature',
            'system' => 'fas fa-info-circle',
            'payment' => 'fas fa-credit-card',
        ];
        
        return $icons[$this->type] ?? 'fas fa-bell';
    }

    public function getColorAttribute()
    {
        $colors = [
            'order_status' => 'text-blue-600',
            'promotion' => 'text-green-600',
            'test_drive' => 'text-purple-600',
            'lead' => 'text-indigo-600',
            'system' => 'text-gray-600',
            'payment' => 'text-orange-600',
        ];
        
        return $colors[$this->type] ?? 'text-gray-600';
    }

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
} 