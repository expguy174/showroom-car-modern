<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'title',
        'content',
        'image_path',
        'is_published',
        'published_at',
        'is_active',
        'status',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function getImageUrlAttribute()
    {
        $path = $this->image_path ?? null;
        if (!$path) {
            return 'https://images.unsplash.com/photo-1529078155058-5d716f45d604?auto=format&fit=crop&w=1200&q=80';
        }
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        return asset('storage/' . ltrim($path, '/'));
    }
}
