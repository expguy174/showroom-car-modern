<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use Illuminate\Http\Request;

class AccessoryController extends Controller
{
    public function show($id)
    {
        $accessory = Accessory::with(['reviews.user'])
            ->withCount(['approvedReviews as approved_reviews_count'])
            ->withAvg('approvedReviews as approved_reviews_avg', 'rating')
            ->findOrFail($id);
        
        // Lấy các phụ kiện liên quan (cùng danh mục/phân loại)
        $relatedAccessories = Accessory::where('is_active', true)
            ->withCount(['approvedReviews as approved_reviews_count'])
            ->withAvg('approvedReviews as approved_reviews_avg', 'rating')
            ->where('id', '!=', $accessory->id)
            ->where(function($query) use ($accessory) {
                $query->where('category', $accessory->category)
                      ->orWhere('subcategory', $accessory->subcategory);
            })
            ->inRandomOrder()
            ->limit(4)
            ->get();
        
        return view('user.accessories.show', compact('accessory', 'relatedAccessories'));
    }
} 