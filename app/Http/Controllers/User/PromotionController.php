<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Promotion;
use App\Models\Order;
use Carbon\Carbon;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = Promotion::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            });

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search by name or code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $promotions = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('user.promotions.index', compact('promotions'));
    }

    public function show(Promotion $promotion)
    {
        // Check if promotion is active and valid
        if (!$promotion->is_active || 
            ($promotion->start_date && $promotion->start_date > now()) ||
            ($promotion->end_date && $promotion->end_date < now())) {
            abort(404);
        }

        // Get user's usage count for this promotion
        $userUsageCount = 0;
        if (Auth::check()) {
            $userUsageCount = Order::where('user_id', Auth::id())
                ->whereJsonContains('promotion_codes', $promotion->code)
                ->count();
        }

        return view('user.promotions.show', compact('promotion', 'userUsageCount'));
    }

    public function validatePromotion(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'order_total' => 'required|numeric|min:0'
        ]);

        $code = strtoupper(trim($request->code));
        $orderTotal = $request->order_total;

        $promotion = Promotion::where('code', $code)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            })
            ->first();

        if (!$promotion) {
            return response()->json([
                'valid' => false,
                'message' => 'Mã khuyến mãi không tồn tại hoặc đã hết hạn.'
            ]);
        }

        // Check usage limit
        if ($promotion->usage_limit && $promotion->usage_count >= $promotion->usage_limit) {
            return response()->json([
                'valid' => false,
                'message' => 'Mã khuyến mãi đã hết lượt sử dụng.'
            ]);
        }

        // Check minimum order amount
        if ($promotion->min_order_amount && $orderTotal < $promotion->min_order_amount) {
            return response()->json([
                'valid' => false,
                'message' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($promotion->min_order_amount, 0, ',', '.') . ' VND.'
            ]);
        }

        // Calculate discount
        $discountAmount = $this->calculateDiscount($promotion, $orderTotal);

        return response()->json([
            'valid' => true,
            'promotion' => [
                'id' => $promotion->id,
                'code' => $promotion->code,
                'name' => $promotion->name,
                'type' => $promotion->type,
                'discount_value' => $promotion->discount_value,
                'discount_amount' => $discountAmount,
                'description' => $promotion->description
            ],
            'message' => 'Mã khuyến mãi hợp lệ! Bạn được giảm ' . number_format($discountAmount, 0, ',', '.') . ' VND.'
        ]);
    }

    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'order_id' => 'required|exists:orders,id'
        ]);

        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $code = strtoupper(trim($request->code));
        
        $promotion = Promotion::where('code', $code)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            })
            ->first();

        if (!$promotion) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi không tồn tại hoặc đã hết hạn.'
            ]);
        }

        // Apply promotion to order
        $discountAmount = $this->calculateDiscount($promotion, $order->total_amount);
        
        $promotionCodes = $order->promotion_codes ?? [];
        if (!in_array($code, $promotionCodes)) {
            $promotionCodes[] = $code;
        }

        $order->update([
            'promotion_codes' => $promotionCodes,
            'discount_amount' => ($order->discount_amount ?? 0) + $discountAmount,
            'total_amount' => $order->subtotal - (($order->discount_amount ?? 0) + $discountAmount)
        ]);

        // Increment usage count
        $promotion->increment('usage_count');

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã khuyến mãi thành công!',
            'discount_amount' => $discountAmount,
            'new_total' => $order->total_amount
        ]);
    }

    public function myPromotions()
    {
        $usedPromotions = collect();
        
        if (Auth::check()) {
            $orders = Order::where('user_id', Auth::id())
                ->whereNotNull('promotion_codes')
                ->with(['items'])
                ->get();

            $promotionCodes = $orders->pluck('promotion_codes')->flatten()->unique();
            
            $usedPromotions = Promotion::whereIn('code', $promotionCodes)
                ->get()
                ->map(function($promotion) use ($orders) {
                    $promotion->usage_count_by_user = $orders->where('promotion_codes', 'like', '%' . $promotion->code . '%')->count();
                    $promotion->total_saved = $orders->where('promotion_codes', 'like', '%' . $promotion->code . '%')
                        ->sum('discount_amount');
                    return $promotion;
                });
        }

        return view('user.promotions.my-promotions', compact('usedPromotions'));
    }

    private function calculateDiscount(Promotion $promotion, float $orderTotal): float
    {
        switch ($promotion->type) {
            case 'percentage':
                return $orderTotal * ($promotion->discount_value / 100);
            case 'fixed_amount':
                return min($promotion->discount_value, $orderTotal);
            case 'free_shipping':
                // Assuming shipping cost is calculated elsewhere
                return 0; // This would be the shipping cost
            default:
                return 0;
        }
    }
}
