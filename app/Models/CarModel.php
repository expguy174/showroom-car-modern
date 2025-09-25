<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'car_brand_id',
        'name',
        'slug',
        'description',
        'body_type',
        'segment',
        'fuel_type',
        'production_start_year',
        'production_end_year',
        'generation',
        'meta_title',
        'meta_description',
        'keywords',
        'is_active',
        'is_featured',
        'is_new',
        'is_discontinued',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_discontinued' => 'boolean',
        'production_start_year' => 'integer',
        'production_end_year' => 'integer',
        'sort_order' => 'integer',
    ];

    public function carBrand()
    {
        return $this->belongsTo(CarBrand::class);
    }

    public function variants()
    {
        return $this->hasMany(CarVariant::class);
    }

    /**
     * Backwards-compatible alias: many parts of the app reference `carVariants`.
     */
    public function carVariants()
    {
        return $this->hasMany(CarVariant::class);
    }

    public function images()
    {
        return $this->hasMany(CarModelImage::class);
    }

    protected static function booted(): void
    {
        // Removed recalculation of totals since brand table does not track these numbers

        static::creating(function (CarModel $model) {
            // Only generate slug if not already set by Controller
            if (empty($model->slug) && !empty($model->name)) {
                $model->slug = static::generateUniqueSlug($model->name);
            }
        });

        static::updating(function (CarModel $model) {
            if ($model->isDirty('name') && empty($model->slug)) {
                $model->slug = static::generateUniqueSlug($model->name, $model->id);
            }
        });
    }

    protected static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = \Illuminate\Support\Str::slug($name);
        $slug = $base;
        $i = 2;
        while (static::query()
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }
        return $slug;
    }

    public function getMainImageUrlAttribute()
    {
        if ($this->main_image_path) {
            return asset('storage/' . $this->main_image_path);
        }
        $variantName = $this->name ?? 'Model';
        $encodedName = urlencode($variantName);
        return "https://via.placeholder.com/400x300/4f46e5/ffffff?text={$encodedName}";
    }

    public function getImageUrlAttribute()
    {
        // First check if there's a main image from the images relationship
        $mainImage = $this->images()->where('is_main', true)->first();
        if ($mainImage) {
            return $mainImage->image_url;
        }

        // If no main image, get the first image
        $firstImage = $this->images()->first();
        if ($firstImage) {
            return $firstImage->image_url;
        }
        $label = $this->name ?? 'Sản phẩm';
        return 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode($label);
    }
}
