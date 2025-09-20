<?php

namespace App\Http\Controllers\User;

use App\Models\CarModel;
use App\Models\Accessory;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CarVariant;
use App\Models\Showroom;
use App\Models\CarBrand;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class HomeController extends Controller
{
    public function index()
    {
        // Featured brands for homepage (only featured brands)
        $brands = CarBrand::where('is_active', 1)
            ->where('is_featured', 1)
            ->with(['carModels' => function ($q) {
                $q->where('is_active', 1)->select('id', 'car_brand_id', 'name')
                  ->whereHas('carVariants', function($q2){ $q2->where('is_active', 1); });
            }])
            ->withCount(['carModels' => function($q){
                $q->where('is_active', 1)
                  ->whereHas('carVariants', function($q2){ $q2->where('is_active', 1); });
            }])
            ->orderBy('name', 'asc')
            ->take(4)
            ->get();
    
        // Featured variants (cars) with eager loading
        $featuredVariants = CarVariant::where('is_active', 1)
            ->where('is_featured', 1)
            ->with(['carModel.carBrand', 'images'])
            ->withCount(['approvedReviews as approved_reviews_count'])
            ->withAvg('approvedReviews as approved_reviews_avg', 'rating')
            ->orderByDesc('updated_at')
            ->take(8)
            ->get();
            


        // Featured accessories (top 4)
        $featuredAccessories = \App\Models\Accessory::where('is_active', 1)
            ->where('is_featured', 1)
            ->withCount(['approvedReviews as approved_reviews_count'])
            ->withAvg('approvedReviews as approved_reviews_avg', 'rating')
            ->orderByDesc('updated_at')
            ->take(4)
            ->get();
            


        // Latest blogs/news (limit to 3) - Optimized with caching
        $blogs = Cache::remember('home_latest_blogs', now()->addHours(6), function () {
            $allBlogs = Blog::where('status', 'published')
                ->where('is_active', 1)
                ->where('is_published', 1)
                ->with(['admin.userProfile:id,user_id,name'])
                ->orderBy('created_at', 'desc')
                ->get(['id', 'admin_id', 'title', 'content', 'image_path', 'created_at']);
            
            // Ensure only 3 blogs are returned
            return $allBlogs->take(3);
        });

        // Top 3 approved reviews: highest rating first, then newest
        $recentReviews = \App\Models\Review::where('is_approved', true)
            ->with(['user.userProfile:id,user_id,name'])
            ->with('reviewable') // Load reviewable models without specifying relationships
            ->select('id', 'rating', 'title', 'comment', 'user_id', 'reviewable_type', 'reviewable_id', 'created_at')
            ->orderByDesc('rating')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        // Load additional relationships for car variants after getting reviews
        foreach ($recentReviews as $review) {
            if ($review->reviewable_type === CarVariant::class && $review->reviewable) {
                $review->reviewable->load(['carModel.carBrand:id,name', 'images:id,car_variant_id,image_url']);
            }
        }

        // Featured/active showrooms (for contact & map teaser)
        $showrooms = Showroom::where('is_active', 1)
            ->orderBy('name')
            ->take(3)
            ->get(['id','name','phone','email','address','city']);

        // Quick search options removed (fuel types, transmissions)

        // Popular variants for quick test drive form
        $testDriveVariants = CarVariant::where('is_active', 1)
            ->with(['carModel.carBrand:id,name'])
            ->select('id', 'name', 'car_model_id')
            ->orderByDesc('created_at')
            ->take(15)
            ->get();

        // Active promotions (top 3)
        $promotions = \App\Models\Promotion::where('is_active', 1)
            ->orderByDesc('start_date')
            ->take(3)
            ->get();



        return view('user.home', compact(
            'brands',
            'featuredVariants',
            'featuredAccessories',
            'showrooms',
            'blogs',
            'testDriveVariants',
            'recentReviews',
            'promotions'
        ));
    }
}
