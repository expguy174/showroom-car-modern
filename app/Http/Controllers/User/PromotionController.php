<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Promotion;
use App\Models\Order;
use App\Models\CartItem;
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
                ->where('promotion_id', $promotion->id)
                ->count();
        }

        return view('user.promotions.show', compact('promotion', 'userUsageCount'));
    }


    public function validatePromotion(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string',
                'order_total' => 'required|numeric|min:0',
                'shipping_method' => 'nullable|string|in:standard,express'
            ]);

            $code = strtoupper(trim($request->code));
            $orderTotal = (float) $request->order_total;

            Log::info('Validating promotion', [
                'code' => $code,
                'order_total' => $orderTotal,
                'user_id' => Auth::id()
            ]);

            // Find promotion
            $promotion = Promotion::where('code', $code)
                ->where('is_active', true)
                ->first();
                
            Log::info('Promotion found', [
                'promotion_exists' => $promotion ? true : false,
                'promotion_id' => $promotion ? $promotion->id : null,
                'promotion_type' => $promotion ? $promotion->type : null
            ]);

            if (!$promotion) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Mã khuyến mãi không tồn tại hoặc đã hết hạn.'
                ]);
            }

            // Check if promotion is still valid (dates)
            $now = now();
            if ($promotion->start_date && $now < $promotion->start_date) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Mã khuyến mãi chưa có hiệu lực.'
                ]);
            }

            if ($promotion->end_date && $now > $promotion->end_date) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Mã khuyến mãi đã hết hạn.'
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
                    'message' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($promotion->min_order_amount, 0, ',', '.') . ' đồng để sử dụng mã này.'
                ]);
            }

            // Check brand-specific promotions
            if ($promotion->type === 'brand_specific') {
                $cartItems = $this->getCurrentCartItems();
                if (!$this->isPromotionApplicableToCart($promotion, $cartItems)) {
                    $brandName = $this->extractBrandFromPromotion($promotion);
                    return response()->json([
                        'valid' => false,
                        'message' => "Mã khuyến mãi này chỉ áp dụng cho xe {$brandName}. Giỏ hàng của bạn không có sản phẩm phù hợp."
                    ]);
                }
            }

            // Get cart items for accurate calculation
            $cartItems = $this->getCurrentCartItems();
            
            Log::info('Cart items loaded', [
                'cart_items_count' => $cartItems->count(),
                'cart_items' => $cartItems->map(function($item) {
                    return [
                        'id' => $item->id,
                        'item_type' => $item->item_type,
                        'item_id' => $item->item_id,
                        'quantity' => $item->quantity
                    ];
                })
            ]);
            
            // Calculate discount
            $discountAmount = $this->calculateDiscount($promotion, $orderTotal, $cartItems);
            
            Log::info('Discount calculated', [
                'discount_amount' => $discountAmount,
                'promotion_type' => $promotion->type
            ]);

            return response()->json([
                'valid' => true,
                'promotion' => [
                    'id' => $promotion->id,
                    'code' => $promotion->code,
                    'name' => $promotion->name,
                    'description' => $promotion->description,
                    'type' => $promotion->type,
                    'discount_value' => $promotion->discount_value,
                    'max_discount_amount' => $promotion->max_discount_amount,
                    'discount_amount' => $discountAmount,
                ],
                'message' => 'Áp dụng mã khuyến mãi thành công!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Promotion validation error', [
                'code' => $request->code ?? 'N/A',
                'order_total' => $request->order_total ?? 'N/A',
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'valid' => false,
                'message' => 'Có lỗi xảy ra khi kiểm tra mã khuyến mãi: ' . $e->getMessage()
            ], 500);
        }
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

    private function calculateDiscount(Promotion $promotion, float $orderTotal, $cartItems = null): float
    {
        // Enhanced logic to handle different promotion scenarios
        switch ($promotion->type) {
            case 'percentage':
                return $this->calculatePercentageDiscount($promotion, $orderTotal, $cartItems);
                
            case 'fixed_amount':
                return $this->calculateFixedAmountDiscount($promotion, $orderTotal, $cartItems);
                
            case 'free_shipping':
                $shippingMethod = request('shipping_method', 'standard');
                return $this->calculateFreeShippingDiscount($promotion, $shippingMethod);
                
            case 'brand_specific':
                return $this->calculateBrandSpecificDiscount($promotion, $orderTotal, $cartItems);
                
            default:
                return 0;
        }
    }
    
    private function calculatePercentageDiscount(Promotion $promotion, float $orderTotal, $cartItems = null): float
    {
        // Simple percentage discount on entire order
        $discountAmount = $orderTotal * ($promotion->discount_value / 100);
        
        // Apply max discount limit if set
        if ($promotion->max_discount_amount && $promotion->max_discount_amount > 0) {
            $discountAmount = min($discountAmount, $promotion->max_discount_amount);
        }
        
        return min($discountAmount, $orderTotal);
    }
    
    private function calculateFixedAmountDiscount(Promotion $promotion, float $orderTotal, $cartItems = null): float
    {
        // Fixed amount discount (gift vouchers, flat discounts)
        return min($promotion->discount_value, $orderTotal);
    }
    
    private function calculateFreeShippingDiscount(Promotion $promotion, string $shippingMethod = 'standard'): float
    {
        // Define shipping fees
        $shippingFees = [
            'standard' => 30000,
            'express' => 50000
        ];
        
        // Check if promotion is restricted to specific shipping type
        $description = strtolower($promotion->description ?? '');
        
        // If promotion specifies "nhanh/express", only apply to express shipping
        if (str_contains($description, 'nhanh') || str_contains($description, 'express') || str_contains($description, 'fast')) {
            return $shippingMethod === 'express' ? $shippingFees['express'] : 0;
        }
        
        // If promotion specifies "tiêu chuẩn/standard", only apply to standard shipping  
        if (str_contains($description, 'tiêu chuẩn') || str_contains($description, 'standard')) {
            return $shippingMethod === 'standard' ? $shippingFees['standard'] : 0;
        }
        
        // General free shipping - apply to selected method
        return $shippingFees[$shippingMethod] ?? $shippingFees['standard'];
    }
    
    private function calculateBrandSpecificDiscount(Promotion $promotion, float $orderTotal, $cartItems = null): float
    {
        $targetBrand = $this->extractBrandFromPromotion($promotion);
        
        if (!$targetBrand || !$cartItems) {
            // Fallback: apply to entire order (current behavior)
            $discountAmount = $orderTotal * ($promotion->discount_value / 100);
            return min($discountAmount, $orderTotal);
        }
        
        // Calculate discount only for items of specific brand
        $brandSpecificTotal = 0;
        
        foreach ($cartItems as $cartItem) {
            $itemBrand = null;
            $itemPrice = 0;
            
            // Handle different item types
            if ($cartItem->item_type === 'car_variant' && $cartItem->item) {
                // Car variant - get brand from car model (load relationship if needed)
                if (!$cartItem->item->relationLoaded('carModel')) {
                    $cartItem->item->load('carModel.carBrand');
                }
                $carBrand = optional($cartItem->item->carModel)->carBrand;
                $itemBrand = $carBrand ? $carBrand->name : null;
                
                // Calculate car price (base + features + color adjustment)
                if (method_exists($cartItem->item, 'getPriceWithColorAdjustment')) {
                    $itemPrice = (float) $cartItem->item->getPriceWithColorAdjustment($cartItem->color_id);
                } else {
                    $itemPrice = (float) ($cartItem->item->current_price ?? 0);
                }
                
                // Add feature prices if any
                $meta = session('cart_item_meta.' . $cartItem->id, []);
                $featIds = collect($meta['feature_ids'] ?? [])->filter()->map(fn($v) => (int)$v)->unique()->all();
                if (!empty($featIds)) {
                    $featSum = (float) \App\Models\CarVariantFeature::whereIn('id', $featIds)->sum('price');
                    $itemPrice += $featSum;
                }
                
            } elseif ($cartItem->item_type === 'accessory' && $cartItem->item) {
                // Accessory - check compatible_car_brands (JSON field)
                $itemBrand = null;
                
                // Get compatible brands from JSON field
                $compatibleBrands = $cartItem->item->compatible_car_brands ?? null;
                
                if ($compatibleBrands) {
                    // If it's a string, decode JSON
                    if (is_string($compatibleBrands)) {
                        $compatibleBrands = json_decode($compatibleBrands, true) ?? [];
                    }
                    
                    // Check if target brand is in compatible brands
                    if (is_array($compatibleBrands)) {
                        foreach ($compatibleBrands as $brand) {
                            if (strtoupper($brand) === strtoupper($targetBrand)) {
                                $itemBrand = $targetBrand; // Consider it as target brand item
                                break;
                            }
                        }
                    }
                }
                
                $itemPrice = (float) ($cartItem->item->current_price ?? $cartItem->item->base_price ?? 0);
                
            } else {
                // Other item types - skip for now
                continue;
            }
            
            // Check if this item matches the target brand
            if ($itemBrand && strtoupper($itemBrand) === strtoupper($targetBrand)) {
                $brandSpecificTotal += $itemPrice * (int) $cartItem->quantity;
            }
        }
        
        if ($brandSpecificTotal <= 0) {
            return 0; // No items of target brand found
        }
        
        // Apply percentage discount only to brand-specific items
        $discountAmount = $brandSpecificTotal * ($promotion->discount_value / 100);
        
        // Apply max discount limit if set
        if ($promotion->max_discount_amount && $promotion->max_discount_amount > 0) {
            $discountAmount = min($discountAmount, $promotion->max_discount_amount);
        }
        
        return min($discountAmount, $brandSpecificTotal);
    }
    

    /**
     * Check if promotion is brand-specific based on code or name
     */
    private function isBrandSpecificPromotion(Promotion $promotion): bool
    {
        $code = strtoupper($promotion->code);
        $name = strtoupper($promotion->name);
        
        $brands = [
            'TOYOTA', 'HYUNDAI', 'VINFAST', 'HONDA', 'MAZDA', 'KIA', 
            'MITSUBISHI', 'FORD', 'BMW', 'MERCEDES-BENZ', 'MERCEDES', 'NISSAN', 
            'PEUGEOT', 'SUBARU', 'LEXUS', 'AUDI', 'VOLKSWAGEN', 'SUZUKI', 'MG'
        ];
        
        foreach ($brands as $brand) {
            if (str_contains($code, $brand) || str_contains($name, $brand)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get current cart items for the authenticated user
     */
    private function getCurrentCartItems()
    {
        if (!Auth::check()) {
            return collect();
        }

        $cartItems = CartItem::where('user_id', Auth::id())
            ->with(['item'])
            ->get();
            
        // Load specific relationships based on item type
        foreach ($cartItems as $cartItem) {
            if ($cartItem->item_type === 'car_variant' && $cartItem->item) {
                $cartItem->item->load('carModel.carBrand');
            }
            // Note: Accessories use compatible_car_brands JSON field, not relationship
        }
        
        return $cartItems;
    }

    /**
     * Check if promotion is applicable to current cart items
     */
    private function isPromotionApplicableToCart(Promotion $promotion, $cartItems): bool
    {
        if ($cartItems->isEmpty()) {
            return false;
        }

        $promotionBrand = $this->extractBrandFromPromotion($promotion);
        if (!$promotionBrand) {
            return true; // If can't extract brand, allow it
        }

        // Check if any cart item matches the promotion brand
        foreach ($cartItems as $cartItem) {
            $itemBrand = null;
            
            if ($cartItem->item_type === 'car_variant' && $cartItem->item) {
                // Car variant - get brand from car model (load relationship if needed)
                if (!$cartItem->item->relationLoaded('carModel')) {
                    $cartItem->item->load('carModel.carBrand');
                }
                $carBrand = optional($cartItem->item->carModel)->carBrand;
                $itemBrand = $carBrand ? $carBrand->name : null;
                
            } elseif ($cartItem->item_type === 'accessory' && $cartItem->item) {
                // Accessory - check compatible_car_brands (JSON field)
                $itemBrand = null;
                
                // Get compatible brands from JSON field
                $compatibleBrands = $cartItem->item->compatible_car_brands ?? null;
                
                if ($compatibleBrands) {
                    // If it's a string, decode JSON
                    if (is_string($compatibleBrands)) {
                        $compatibleBrands = json_decode($compatibleBrands, true) ?? [];
                    }
                    
                    // Check if promotion brand is in compatible brands
                    if (is_array($compatibleBrands)) {
                        foreach ($compatibleBrands as $brand) {
                            if (strtoupper($brand) === strtoupper($promotionBrand)) {
                                $itemBrand = $promotionBrand;
                                break;
                            }
                        }
                    }
                }
            }
            
            // Check if this item matches the promotion brand
            if ($itemBrand && strtoupper($itemBrand) === strtoupper($promotionBrand)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extract brand name from promotion code or name
     */
    private function extractBrandFromPromotion(Promotion $promotion): ?string
    {
        $code = strtoupper($promotion->code);
        $name = strtoupper($promotion->name);
        
        $brands = [
            'TOYOTA' => 'Toyota',
            'HYUNDAI' => 'Hyundai',
            'VINFAST' => 'VinFast',
            'HONDA' => 'Honda', 
            'MAZDA' => 'Mazda',
            'KIA' => 'Kia',
            'MITSUBISHI' => 'Mitsubishi',
            'FORD' => 'Ford',
            'BMW' => 'BMW',
            'MERCEDES-BENZ' => 'Mercedes-Benz',
            'MERCEDES' => 'Mercedes-Benz',
            'NISSAN' => 'Nissan',
            'PEUGEOT' => 'Peugeot',
            'SUBARU' => 'Subaru',
            'LEXUS' => 'Lexus',
            'AUDI' => 'Audi',
            'VOLKSWAGEN' => 'Volkswagen',
            'SUZUKI' => 'Suzuki',
            'MG' => 'MG'
        ];
        
        foreach ($brands as $brandKey => $brandName) {
            if (str_contains($code, $brandKey) || str_contains($name, $brandKey)) {
                return $brandName;
            }
        }
        
        return null;
    }
}
