<?php

namespace App\Services;

use App\Models\CarVariant;
use App\Models\CarBrand;
use App\Models\CarModel;
use Illuminate\Http\Request;

class SearchService
{
    public static function searchCars(Request $request)
    {
        $query = CarVariant::with([
            'carModel.carBrand',
            'colors',
            'images'
        ]);

        // Brand filter
        if ($request->filled('brand')) {
            $query->whereHas('carModel.carBrand', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->brand . '%');
            });
        }

        // Model filter
        if ($request->filled('model')) {
            $query->whereHas('carModel', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->model . '%');
            });
        }

        // Price range
        if ($request->filled('min_price')) {
            $query->where('current_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('current_price', '<=', $request->max_price);
        }

        // Fuel type
        if ($request->filled('fuel_type')) {
            $query->whereHas('specifications', function ($q) use ($request) {
                $q->where('spec_name', 'fuel_type')->where('spec_value', $request->fuel_type);
            });
        }

        // Transmission
        if ($request->filled('transmission')) {
            $query->whereHas('specifications', function ($q) use ($request) {
                $q->where('spec_name', 'transmission')->where('spec_value', $request->transmission);
            });
        }

        // Body type
        if ($request->filled('body_type')) {
            $query->whereHas('specifications', function ($q) use ($request) {
                $q->where('spec_name', 'body_type')->where('spec_value', $request->body_type);
            });
        }

        // Year range (map sang năm sản xuất của model)
        if ($request->filled('min_year')) {
            $min = (int) $request->min_year;
            $query->whereHas('carModel', function($q) use ($min) {
                $q->where(function($qq) use ($min) {
                    $qq->whereNull('production_start_year')->orWhere('production_start_year', '>=', $min);
                });
            });
        }

        if ($request->filled('max_year')) {
            $max = (int) $request->max_year;
            $query->whereHas('carModel', function($q) use ($max) {
                $q->where(function($qq) use ($max) {
                    $qq->whereNull('production_end_year')->orWhere('production_end_year', '<=', $max);
                });
            });
        }

        // Color
        if ($request->filled('color')) {
            $query->whereHas('colors', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->color . '%');
            });
        }

        // Features moved out of variant text; keep simple keyword match over specs
        if ($request->filled('features')) {
            $features = array_filter(array_map('trim', explode(',', $request->features)));
            foreach ($features as $feature) {
                $query->whereHas('specifications', function ($q) use ($feature) {
                    $q->where('spec_name', 'like', '%' . $feature . '%')
                      ->orWhere('spec_value', 'like', '%' . $feature . '%');
                });
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Active variants only
        $query->where('is_active', 1);

        return $query->paginate($request->get('per_page', 12));
    }

    public static function getSearchFilters()
    {
        return [
            'brands' => CarBrand::where('is_active', 1)->pluck('name')->unique(),
            'fuel_types' => \App\Models\CarSpecification::where('spec_name', 'fuel_type')->distinct()->pluck('spec_value')->filter(),
            'transmissions' => \App\Models\CarSpecification::where('spec_name', 'transmission')->distinct()->pluck('spec_value')->filter(),
            'body_types' => \App\Models\CarSpecification::where('spec_name', 'body_type')->distinct()->pluck('spec_value')->filter(),
            'price_ranges' => [
                ['min' => 0, 'max' => 500000000, 'label' => 'Dưới 500 triệu'],
                ['min' => 500000000, 'max' => 1000000000, 'label' => '500 triệu - 1 tỷ'],
                ['min' => 1000000000, 'max' => 2000000000, 'label' => '1 tỷ - 2 tỷ'],
                ['min' => 2000000000, 'max' => null, 'label' => 'Trên 2 tỷ'],
            ],
            'years' => range(date('Y') - 10, date('Y') + 1),
        ];
    }

    public static function getPopularSearches()
    {
        // This could be implemented with a search log table
        return [
            'BMW X5',
            'Mercedes C-Class',
            'Audi A4',
            'Toyota Camry',
            'Honda CR-V'
        ];
    }

    public static function suggestSearch($query)
    {
        $suggestions = [];

        // Brand suggestions
        $brands = CarBrand::where('name', 'like', '%' . $query . '%')
            ->where('is_active', 1)
            ->pluck('name')
            ->take(5);

        foreach ($brands as $brand) {
            $suggestions[] = [
                'type' => 'brand',
                'text' => $brand,
                'url' => '/search?brand=' . urlencode($brand)
            ];
        }

        // Model suggestions
        $models = CarModel::where('name', 'like', '%' . $query . '%')
            ->where('is_active', 1)
            ->with('carBrand')
            ->get()
            ->take(5);

        foreach ($models as $model) {
            $suggestions[] = [
                'type' => 'model',
                'text' => $model->carBrand->name . ' ' . $model->name,
                'url' => '/search?model=' . urlencode($model->name)
            ];
        }

        return $suggestions;
    }
} 