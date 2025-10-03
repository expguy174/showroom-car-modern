<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarVariantSpecification extends Model
{
    use HasFactory;

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
        'sort_order'
    ];

    protected $casts = [
        'is_important' => 'boolean',
        'is_highlighted' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Get the car variant that owns the specification
     */
    public function carVariant()
    {
        return $this->belongsTo(CarVariant::class);
    }

    /**
     * Scope for important specifications
     */
    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }

    /**
     * Scope for highlighted specifications
     */
    public function scopeHighlighted($query)
    {
        return $query->where('is_highlighted', true);
    }

    /**
     * Scope for specifications by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get formatted specification value with unit
     */
    public function getFormattedValueAttribute()
    {
        if ($this->unit) {
            return $this->spec_value . ' ' . $this->unit;
        }
        return $this->spec_value;
    }

    /**
     * Get specification categories
     */
    public static function getCategories()
    {
        return [
            'engine' => 'Động cơ',
            'transmission' => 'Hộp số',
            'dimensions' => 'Kích thước',
            'performance' => 'Hiệu suất',
            'safety' => 'An toàn',
            'fuel' => 'Nhiên liệu',
            'suspension' => 'Hệ thống treo',
            'brakes' => 'Phanh',
            'wheels' => 'Bánh xe',
            'interior' => 'Nội thất',
            'exterior' => 'Ngoại thất',
            'technology' => 'Công nghệ',
            'other' => 'Khác'
        ];
    }
}
