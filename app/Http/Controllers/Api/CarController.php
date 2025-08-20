<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\CarVariant;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = CarBrand::with(['carModels.carVariants' => function ($query) {
            $query->where('is_active', 1)->with('specifications');
        }]);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $cars = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $cars
        ]);
    }

    public function show($id)
    {
        $car = CarBrand::with(['carModels.carVariants' => function ($query) {
            $query->where('is_active', 1);
        }])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $car
        ]);
    }

    public function models($carId)
    {
        $models = CarModel::with(['carVariants' => function ($query) {
            $query->where('is_active', 1);
        }])
        ->where('car_brand_id', $carId)
        ->where('is_active', 1)
        ->get();

        return response()->json([
            'success' => true,
            'data' => $models
        ]);
    }

    public function variants($modelId)
    {
        $variants = CarVariant::with(['carModel.carBrand', 'colors', 'images', 'specifications', 'options', 'featuresRelation'])
        ->where('car_model_id', $modelId)
        ->where('is_active', 1)
        ->get();

        return response()->json([
            'success' => true,
            'data' => $variants
        ]);
    }

    public function variant($id)
    {
        $variant = CarVariant::with([
            'carModel.carBrand',
            'colors',
            'images',
            'specifications',
            'options',
            'featuresRelation'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $variant
        ]);
    }

    public function accessory($id)
    {
        $accessory = \App\Models\Accessory::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $accessory
        ]);
    }

    public function search(Request $request)
    {
        $query = CarVariant::with(['carModel.carBrand', 'colors', 'images']);

        if ($request->has('brand')) {
            $query->whereHas('carModel.carBrand', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->brand}%");
            });
        }

        if ($request->has('model')) {
            $query->whereHas('carModel', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->model}%");
            });
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('fuel_type')) {
            $query->whereHas('carModels.carVariants.specifications', function ($q) use ($request) {
                $q->where('spec_name', 'fuel_type')->where('spec_value', $request->fuel_type);
            });
        }

        $variants = $query->where('is_active', 1)->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $variants
        ]);
    }
} 