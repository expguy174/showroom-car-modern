<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    public const TYPES = ['home', 'work', 'billing', 'shipping', 'other'];

    protected $fillable = [
        'user_id',
        'type',
        'contact_name',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'is_default',
        'notes',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


