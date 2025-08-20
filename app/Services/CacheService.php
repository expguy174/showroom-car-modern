<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\CarVariant;
use App\Models\Blog;

class CacheService
{
    const CACHE_TTL = 3600; // 1 hour

    public static function getCars()
    {
        return Cache::remember('cars_with_models', self::CACHE_TTL, function () {
            return CarBrand::with(['carModels.carVariants' => function ($query) {
                $query->where('is_active', 1);
            }])->where('is_active', 1)->get();
        });
    }

    public static function getFeaturedCars()
    {
        return Cache::remember('featured_cars', self::CACHE_TTL, function () {
            return CarModel::with(['car.carVariants' => function ($query) {
                $query->where('is_active', 1);
            }])
            ->where('is_featured', 1)
            ->where('is_active', 1)
            ->get();
        });
    }

    public static function getLatestBlogs($limit = 6)
    {
        return Cache::remember("latest_blogs_{$limit}", self::CACHE_TTL, function () use ($limit) {
            return Blog::where('is_active', 1)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    public static function getCarDetails($carId)
    {
        return Cache::remember("car_details_{$carId}", self::CACHE_TTL, function () use ($carId) {
            return CarBrand::with(['carModels.carVariants' => function ($query) {
                $query->where('is_active', 1);
            }])->find($carId);
        });
    }

    public static function getVariantDetails($variantId)
    {
        return Cache::remember("variant_details_{$variantId}", self::CACHE_TTL, function () use ($variantId) {
            return CarVariant::with([
                'carModel.carBrand',
                'colors',
                'images',
                'reviews' => function ($query) {
                    $query->where('is_approved', 1);
                }
            ])->find($variantId);
        });
    }

    public static function clearCarCache()
    {
        Cache::forget('cars_with_models');
        Cache::forget('featured_cars');
    }

    public static function clearBlogCache()
    {
        Cache::forget('latest_blogs_6');
    }

    public static function clearVariantCache($variantId = null)
    {
        if ($variantId) {
            Cache::forget("variant_details_{$variantId}");
        } else {
            // Clear all variant caches (you might want to implement a more sophisticated approach)
            Cache::flush();
        }
    }
} 