<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CarVariant;
use Illuminate\Http\Request;

class CarVariantController extends Controller
{
    public function show($slugOrId)
    {
        $variant = CarVariant::with([
                'carModel.carBrand',
                'images',
                'colors' => function($q){ $q->where('is_active', true)->orderBy('sort_order'); },
                'featuresRelation' => function($q){ $q->where('is_active', true)->orderBy('sort_order'); },
                'specifications' => function($q){ $q->ordered(); },
                'reviews' => function ($q) { $q->where('is_approved', true); }
            ])
            ->when(is_numeric($slugOrId), function ($q) use ($slugOrId) {
                $q->where('id', $slugOrId);
            }, function ($q) use ($slugOrId) {
                $q->where('slug', $slugOrId);
            })
            ->firstOrFail();

        $relatedVariants = CarVariant::where('car_model_id', $variant->car_model_id)
            ->where('id', '!=', $variant->id)
            ->where('is_active', 1)
            ->take(6)
            ->get();

        $approvedCount = $variant->approvedReviews()->count();
        $approvedAvg = $variant->approvedReviews()->avg('rating');

        return view('user.car-variants.show', compact('variant', 'relatedVariants', 'approvedCount', 'approvedAvg'));
    }
}


