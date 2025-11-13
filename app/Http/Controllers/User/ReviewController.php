<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\CarVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'reviewable_type' => 'required|in:App\\Models\\CarVariant,App\\Models\\Accessory',
            'reviewable_id' => 'required|integer',
            'rating' => 'required|integer|between:1,5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|min:10',
        ]);

        $review = Review::create([
            'user_id' => Auth::id(),
            'reviewable_type' => $request->reviewable_type,
            'reviewable_id' => $request->reviewable_id,
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
        ]);

        // Notify admin about new review (needs approval)
        try {
            $reviewable = $review->reviewable;
            \App\Models\Notification::create([
                'user_id' => null,
                'type' => 'system',
                'title' => 'Đánh giá mới cần duyệt',
                'message' => 'Khách hàng ' . (Auth::user()->userProfile->name ?? 'Khách hàng') . ' đã đánh giá ' . ($reviewable->name ?? 'sản phẩm') . ' - ' . $request->rating . ' sao',
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send admin review notification', [
                'review_id' => $review->id,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đánh giá đã được gửi thành công!',
            'review' => $review
        ]);
    }

    public function getReviews(Request $request)
    {
        $request->validate([
            'reviewable_type' => 'required|in:App\\Models\\CarVariant,App\\Models\\Accessory',
            'reviewable_id' => 'required|integer',
        ]);

        $reviews = Review::where('reviewable_type', $request->reviewable_type)
            ->where('reviewable_id', $request->reviewable_id)
            ->where('is_approved', true)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($reviews);
    }

    public function summary(Request $request)
    {
        $request->validate([
            'reviewable_type' => 'required|in:App\\Models\\CarVariant,App\\Models\\Accessory',
            'reviewable_id' => 'required|integer',
        ]);

        $base = Review::where('reviewable_type', $request->reviewable_type)
            ->where('reviewable_id', $request->reviewable_id)
            ->where('is_approved', true);

        $approvedCount = (clone $base)->count();
        $approvedAvg = (float) ((clone $base)->avg('rating') ?? 0);

        $distribution = [];
        for ($star = 1; $star <= 5; $star++) {
            $distribution[$star] = (clone $base)->where('rating', $star)->count();
        }

        return response()->json([
            'success' => true,
            'approved_count' => (int) $approvedCount,
            'approved_avg' => (float) number_format($approvedAvg, 1, '.', ''),
            'distribution' => $distribution,
        ]);
    }
} 