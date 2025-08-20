<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CarVariant;
use App\Models\Review;
use Illuminate\Http\Request;

class UserCarVariantController extends Controller
{
    public function show($slugOrId)
    {
        $query = CarVariant::with(['carModel.carBrand', 'images', 'specifications', 'colors', 'featuresRelation', 'options'])
            ->withCount(['approvedReviews as approved_reviews_count'])
            ->withAvg('approvedReviews as approved_reviews_avg', 'rating');
        if (is_numeric($slugOrId)) {
            $variant = $query->findOrFail((int) $slugOrId);
        } else {
            $variant = $query->where('slug', $slugOrId)->firstOrFail();
        }

        // Related variants from the same car model, excluding the current variant
        $relatedVariants = CarVariant::with(['carModel.carBrand', 'images'])
            ->withCount(['approvedReviews as approved_reviews_count'])
            ->withAvg('approvedReviews as approved_reviews_avg', 'rating')
            ->where('car_model_id', $variant->car_model_id)
            ->where('id', '!=', $variant->id)
            ->limit(4)
            ->get();

        if ($relatedVariants->count() < 4) {
            $additionalVariants = CarVariant::with(['carModel.carBrand', 'images'])
                ->withCount(['approvedReviews as approved_reviews_count'])
                ->withAvg('approvedReviews as approved_reviews_avg', 'rating')
                ->where('car_model_id', '!=', $variant->car_model_id)
                ->limit(4 - $relatedVariants->count())
                ->get();

            $relatedVariants = $relatedVariants->merge($additionalVariants);
        }

        // Accurate review stats (approved only)
        $approvedCount = Review::where('reviewable_type', CarVariant::class)
            ->where('reviewable_id', $variant->id)
            ->where('is_approved', true)
            ->count();
        $approvedAvg = (float) (Review::where('reviewable_type', CarVariant::class)
            ->where('reviewable_id', $variant->id)
            ->where('is_approved', true)
            ->avg('rating') ?? 0);

        return view('user.car_variants.show', compact('variant', 'relatedVariants', 'approvedCount', 'approvedAvg'));
    }
}