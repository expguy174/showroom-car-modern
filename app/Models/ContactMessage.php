<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactMessage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'contact_type',
        'showroom_id',
        'name',
        'phone',
        'email',
        'subject',
        'message',
        'topic',
        'status',
        'handled_at',
        'handled_by',
        'source',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'handled_at' => 'datetime',
        'metadata' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function showroom()
    {
        return $this->belongsTo(Showroom::class);
    }

    public function handledBy()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'new' => 'Mới',
            'in_progress' => 'Đang xử lý',
            'resolved' => 'Đã giải quyết',
            'closed' => 'Đã đóng'
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    public function getTopicDisplayAttribute()
    {
        $topics = [
            'sales' => 'Tư vấn mua hàng',
            'service' => 'Dịch vụ',
            'test_drive' => 'Lái thử',
            'warranty' => 'Bảo hành',
            'finance' => 'Tài chính',
            'other' => 'Khác'
        ];
        return $topics[$this->topic] ?? $this->topic;
    }

    public function scopePending($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeByTopic($query, $topic)
    {
        return $query->where('topic', $topic);
    }
}
