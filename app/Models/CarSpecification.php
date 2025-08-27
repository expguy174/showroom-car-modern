<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarSpecification extends Model
{
    use HasFactory;

    protected $table = 'car_variant_specifications';

    protected $fillable = [
        'car_variant_id',
        'category',
        'spec_name',
        'spec_value',
        'unit',
        'description',
        'spec_code',
        'is_important',
        'is_highlighted',
        'sort_order',
    ];

    protected $casts = [
        'is_important' => 'boolean',
        'is_highlighted' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function carVariant()
    {
        return $this->belongsTo(CarVariant::class);
    }

    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }

    public function scopeHighlighted($query)
    {
        return $query->where('is_highlighted', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('spec_name');
    }
}
