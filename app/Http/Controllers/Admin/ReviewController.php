<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user.userProfile', 'reviewable']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('comment', 'like', "%{$search}%")
                  ->orWhereHas('user.userProfile', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('reviewable', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status') && $request->status !== '') {
            $query->where('is_approved', $request->status === 'approved');
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Append query parameters to pagination links
        $reviews->appends($request->except(['page', 'ajax', 'with_stats']));

        // Return partial view for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return view('admin.reviews.partials.table', compact('reviews'))->render();
        }

        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        $review->load(['user.userProfile', 'reviewable']);
        return view('admin.reviews.show', compact('review'));
    }

    public function approve(Review $review, Request $request)
    {
        $review->update(['is_approved' => true]);
        
        if ($request->ajax() || $request->wantsJson()) {
            // Get updated stats
            $stats = [
                'total' => Review::count(),
                'approved' => Review::where('is_approved', true)->count(),
                'pending' => Review::where('is_approved', false)->count()
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Đánh giá đã được phê duyệt!',
                'stats' => $stats,
                'new_status' => true
            ]);
        }
        
        return redirect()->back()->with('success', 'Đánh giá đã được phê duyệt!');
    }

    public function reject(Review $review, Request $request)
    {
        $review->update(['is_approved' => false]);
        
        if ($request->ajax() || $request->wantsJson()) {
            // Get updated stats
            $stats = [
                'total' => Review::count(),
                'approved' => Review::where('is_approved', true)->count(),
                'pending' => Review::where('is_approved', false)->count()
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Đã bỏ duyệt đánh giá!',
                'stats' => $stats,
                'new_status' => false
            ]);
        }
        
        return redirect()->back()->with('success', 'Đánh giá đã bị từ chối!');
    }

    public function destroy(Review $review, Request $request)
    {
        $review->delete();
        
        if ($request->ajax() || $request->wantsJson()) {
            // Get updated stats after deletion
            $stats = [
                'total' => Review::count(),
                'approved' => Review::where('is_approved', true)->count(),
                'pending' => Review::where('is_approved', false)->count()
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa đánh giá thành công!',
                'stats' => $stats
            ]);
        }
        
        return redirect()->back()->with('success', 'Đánh giá đã được xóa!');
    }
} 