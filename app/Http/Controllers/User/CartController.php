<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use App\Application\Orders\UseCases\PlaceOrder;
use App\Services\EmailService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use App\Models\CarVariant;
use App\Models\Accessory;
use App\Services\NotificationService;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\CarVariantFeature;
use App\Models\CarVariantOption;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::check() ? Auth::user()->id : null;
        $sessionId = session()->getId();

        $cartItems = CartItem::where(function ($q) use ($userId, $sessionId) {
            if ($userId) $q->where('user_id', $userId);
            else $q->where('session_id', $sessionId);
        })->with(['item', 'color'])->get();

        foreach ($cartItems as $item) {
            if ($item->item_type === 'car_variant') {
                $item->item->load('colors', 'images', 'featuresRelation');
            }
        }

        return view('user.cart.index', compact('cartItems'));
    }

    public function add(Request $request)
    {
        try {
            // Basic validation for all items
            $basicValidated = $request->validate([
                'item_type' => 'required|in:car_variant,accessory',
                'item_id' => 'required|integer|min:1',
                'quantity' => 'integer|min:1|max:10',
                'options_signature' => 'nullable|string',
            ]);

            // Additional validation based on item type
            if ($basicValidated['item_type'] === 'car_variant') {
            $validated = $request->validate([
                'item_type' => 'required|in:car_variant,accessory',
                'item_id' => 'required|integer|min:1',
                'quantity' => 'integer|min:1|max:10',
                'color_id' => 'nullable|integer|exists:car_variant_colors,id',
                'feature_ids' => 'nullable|array',
                    'feature_ids.*' => 'integer',
                    'options_signature' => 'nullable|string',
                ]);
            } else {
                // For accessories, only basic fields
                $validated = $basicValidated;
                $validated['color_id'] = null;
                $validated['feature_ids'] = [];
            }

            $quantity = $validated['quantity'] ?? 1;
            $colorId = $validated['color_id'] ?? null;

            $item = null;
            switch ($validated['item_type']) {
                case 'car_variant':
                    $item = CarVariant::find($validated['item_id']);
                    break;
                case 'accessory':
                    $item = Accessory::find($validated['item_id']);
                    break;
            }

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không tồn tại'
                ], 404);
            }

            // Check if item is active (for both car variants and accessories)
            if (isset($item->is_active) && !$item->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm hiện không có sẵn'
                ], 400);
            }

            // Stock guard: prefer per-color control if color selected; otherwise allow add (choose color later)
            if ($validated['item_type'] === 'car_variant') {
                if (!is_null($colorId)) {
                    $color = $item->colors()->where('id', $colorId)->first();
                    if (!$color || !$color->is_active || (isset($color->availability) && strtolower((string)$color->availability) === 'discontinued')) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Màu đã hết hàng'
                        ], 400);
                    }

                    // Read per-color availability from color_inventory JSON
                    $inventory = is_array($item->color_inventory) ? $item->color_inventory : [];
                    $colorInventory = $inventory[$colorId] ?? null;
                    $available = (int) ($colorInventory['available'] ?? $colorInventory['quantity'] ?? 0);

                    // Current quantity in cart for this variant+color
                    $userIdCheck = \Illuminate\Support\Facades\Auth::id();
                    $sessionIdCheck = session()->getId();
                    $currentInCart = \App\Models\CartItem::where('item_type', 'car_variant')
                        ->where('item_id', $item->id)
                        ->where('color_id', $colorId)
                        ->where(function($q) use ($userIdCheck, $sessionIdCheck) {
                            if ($userIdCheck) { $q->where('user_id', $userIdCheck); }
                            else { $q->where('session_id', $sessionIdCheck); }
                        })
                        ->sum('quantity');

                    $requestedTotal = $currentInCart + $quantity;

                    // If inventory entry exists, enforce available >= requested
                    if ($colorInventory !== null && $available < $requestedTotal) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Số lượng màu này không đủ trong kho'
                        ], 400);
                    }
                } else {
                    // No color chosen at add-to-cart time: if variant has inventory data and all colors are zero, block; else allow
                    $hasInventoryData = is_array($item->color_inventory) && count($item->color_inventory) > 0;
                    if ($hasInventoryData && (int) $item->effective_available_quantity <= 0) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Phiên bản đã hết hàng'
                        ], 400);
                    }
                }
            }

            // Use CartHelper to add item
            // Build options signature (sorted unique feature ids) so different selections are separate lines
            $featureIdsForSignature = array_values(array_unique(array_map('intval', $validated['feature_ids'] ?? [])));
            sort($featureIdsForSignature);
            $signature = empty($featureIdsForSignature) ? null : implode('-', $featureIdsForSignature);

            // If options_signature starts with "duplicate_", always create new line
            $forceNewLine = isset($validated['options_signature']) && str_starts_with($validated['options_signature'], 'duplicate_');
            
            $result = \App\Helpers\CartHelper::addToCart(
                $validated['item_type'],
                $validated['item_id'],
                $quantity,
                $colorId,
                $forceNewLine ? $validated['options_signature'] : $signature,
                $forceNewLine // Pass flag to force new line
            );

            // Persist selected features in session keyed by cart_item_id (lightweight without schema changes)
            if (!empty($result['cart_item_id']) && !empty($validated['feature_ids'])) {
                $meta = [
                    'feature_ids' => array_values(array_unique(array_map('intval', $validated['feature_ids']))),
                ];
                session()->put('cart_item_meta.' . $result['cart_item_id'], $meta);
            }

            return response()->json($result);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error adding to cart:', [
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm vào giỏ hàng. Vui lòng thử lại.'
            ], 500);
        }
    }

    public function update(Request $request, CartItem $cartItem)
    {
        Log::info('Cart update request:', $request->all());

        try {
            $request->validate([
                'quantity' => 'nullable|integer|min:1',
                'color_id' => 'nullable|exists:car_variant_colors,id',
                'feature_ids' => 'nullable|array',
                'feature_ids.*' => 'integer|exists:car_variant_features,id',
                
            ]);

            $updateData = [];
            if ($request->has('quantity')) {
                $qty = (int) $request->quantity;
                if ($qty < 1) {
                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Số lượng tối thiểu là 1'
                        ], 422);
                    }
                    return back()->with('error', 'Số lượng tối thiểu là 1');
                }
                $updateData['quantity'] = $qty;
            }
            if ($request->has('color_id')) {
                $updateData['color_id'] = $request->color_id;
            }
            // Persist selected features into session meta for this cart item
            $featureIds = $request->input('feature_ids', null);
            if (is_array($featureIds)) {
                $meta = [
                    'feature_ids' => array_values(array_unique(array_map('intval', $featureIds ?? []))),
                ];
                session()->put('cart_item_meta.' . $cartItem->id, $meta);
            } else {
                // Support single toggle update: feature_id + feature_enabled
                $fid = $request->input('feature_id');
                $fen = $request->boolean('feature_enabled', null);
                if (!is_null($fid) && !is_null($fen)) {
                    $meta = session('cart_item_meta.' . $cartItem->id, []);
                    $ids = collect($meta['feature_ids'] ?? [])->map(fn($v)=> (int)$v)->toArray();
                    $fid = (int) $fid;
                    if ($fen) {
                        $ids[] = $fid;
                    } else {
                        $ids = array_values(array_filter($ids, fn($v) => $v !== $fid));
                    }
                    $meta['feature_ids'] = array_values(array_unique($ids));
                    session()->put('cart_item_meta.' . $cartItem->id, $meta);
                }
            }

            $cartItem->update($updateData);
            $cartItem->load(['item', 'color']);

            if ($request->ajax()) {
                // Invalidate nav cached counts so first paint is correct
                $this->invalidateNavCountsCache();
                $cartCount = $this->computeCartCount(Auth::id(), session()->getId());
                // Chuẩn hoá dữ liệu giá trả về cho FE
                $item = $cartItem->item;
                $currentPrice = 0.0; // giá hiện tại (chưa gồm màu/tuỳ chọn)
                $originalPrice = 0.0; // giá gốc
                $colorPriceAdjustment = 0.0; // phụ phí màu
                $featSum = 0.0; // tổng phụ kiện thêm
                $itemPrice = 0.0; // đơn giá hiển thị (current + color + addons)

                if ($item) {
                    $originalPrice = (float) ($item->base_price ?? ($item->original_price ?? 0));
                    $currentPrice  = (float) ($item->current_price ?? ($item->price ?? 0));

                    if ($item instanceof \App\Models\CarVariant && method_exists($item, 'getPriceWithColorAdjustment')) {
                        $priceWithColor = (float) $item->getPriceWithColorAdjustment($cartItem->color_id);
                        $colorPriceAdjustment = max(0.0, $priceWithColor - $currentPrice);
                    }

                    $meta = session('cart_item_meta.' . $cartItem->id, []);
                    $featIds = collect($meta['feature_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
                    $featSum = 0.0;
                    if (!empty($featIds)) {
                        $features = CarVariantFeature::whereIn('id', $featIds)->get(['price']);
                        foreach ($features as $f) {
                            $featSum += (float) ($f->price ?? 0);
                        }
                    }

                    $itemPrice = max(0.0, $currentPrice + $colorPriceAdjustment + $featSum);
                }

                $itemTotal = $itemPrice * (int) $cartItem->quantity;
                $colorName = $cartItem->color ? $cartItem->color->color_name : null;

                $discountAmount = ($originalPrice > 0 && $currentPrice >= 0 && $currentPrice < $originalPrice)
                    ? ($originalPrice - $currentPrice) : 0.0;
                // Round to whole percent for consistent display (e.g., 5%)
                $discountPercentage = $discountAmount > 0 ? round(($discountAmount / $originalPrice) * 100, 0) : 0.0;
                $hasDiscount = $discountAmount > 0;

                return response()->json([
                    'success' => true,
                    'cart_count' => $cartCount,
                    'item_price' => $itemPrice,
                    'unit_price' => $itemPrice,
                    'item_total' => $itemTotal,
                    'quantity' => $cartItem->quantity,
                    'color_id' => $cartItem->color_id,
                    'color_name' => $colorName,
                    'message' => $request->has('color_id') ? 'Cập nhật màu sắc thành công!' : 'Cập nhật thành công!',
                    'feature_ids' => $featureIds ?? ($meta['feature_ids'] ?? []),
                    // Discount info
                    'discount_percentage' => $discountPercentage,
                    'has_discount' => $hasDiscount,
                    'original_price_before_discount' => $originalPrice,
                    'discount_amount' => $discountAmount,
                    // Price breakdown
                    'current_price' => $currentPrice,
                    'original_base_price' => $originalPrice,
                    'color_price_adjustment' => $colorPriceAdjustment,
                    'addon_sum' => $featSum
                ]);
            }
            return back()->with('success', 'Cập nhật thành công!');
        } catch (\Exception $e) {
            Log::error('Error in cart update:', [
                'error' => $e->getMessage(),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi cập nhật: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Có lỗi xảy ra khi cập nhật: ' . $e->getMessage());
        }
    }

    public function remove(CartItem $cartItem)
    {
        // Use CartHelper to remove item
        $result = \App\Helpers\CartHelper::removeFromCart($cartItem->id);
        // Clear any stored meta for this item
        session()->forget('cart_item_meta.' . $cartItem->id);

        // Bust nav counts cache to avoid stale header counts
        $this->invalidateNavCountsCache();

        if (request()->expectsJson()) {
            return response()->json($result);
        }

        return back()->with('success', $result['message']);
    }

    public function clear(Request $request)
    {
        // Use CartHelper to clear cart
        $result = \App\Helpers\CartHelper::clearCart();
        // Clear meta for all items
        session()->forget('cart_item_meta');

        // Bust nav counts cache to avoid stale header counts
        $this->invalidateNavCountsCache();

        if ($request->ajax()) {
            return response()->json($result);
        }

        return back()->with('success', $result['message']);
    }

    public function getCount(Request $request)
    {
        $userId = Auth::check() ? Auth::id() : null;
        $sessionId = session()->getId();
        $cartCount = $this->computeCartCount($userId, $sessionId);

        return response()->json([
            'success' => true,
            'cart_count' => $cartCount
        ]);
    }

    public function getItems(Request $request)
    {
        $userId = Auth::check() ? Auth::id() : null;
        $sessionId = session()->getId();

        $cartItems = CartItem::where(function ($q) use ($userId, $sessionId) {
            if ($userId) $q->where('user_id', $userId);
            else $q->where('session_id', $sessionId);
        })->with(['item', 'color'])->get();

        // Format items for frontend
        $items = $cartItems->map(function ($cartItem) {
            return [
                'id' => $cartItem->id,
                'item_type' => $cartItem->item_type,
                'item_id' => $cartItem->item_id,
                'quantity' => $cartItem->quantity,
                'color_id' => $cartItem->color_id,
                'color_name' => $cartItem->color ? $cartItem->color->color_name : null,
                'item_name' => $cartItem->item ? $cartItem->item->name : null,
                'item_price' => $cartItem->item ? $cartItem->item->current_price : 0,
                'total_price' => $cartItem->item ? $cartItem->item->current_price * $cartItem->quantity : 0
            ];
        });

        return response()->json([
            'success' => true,
            'cart_items' => $items,
            'cart_count' => $this->computeCartCount($userId, $sessionId)
        ]);
    }

    private function computeCartCount($userId, $sessionId)
    {
        return CartItem::where(function ($q) use ($userId, $sessionId) {
                if ($userId) $q->where('user_id', $userId);
                else $q->where('session_id', $sessionId);
            })->sum('quantity');
    }

    private function invalidateNavCountsCache(): void
    {
        if (Auth::check()) {
            $userId = Auth::id();
            Cache::forget("nav_counts_user_{$userId}");
        } else {
            $sessionId = session()->getId();
            Cache::forget("nav_counts_session_{$sessionId}");
        }
    }

    public function showCheckoutForm(Request $request)
    {
        $user = Auth::user();
        $userId = $user ? $user->id : null;
        $sessionId = session()->getId();

        $cartItems = CartItem::where(function ($q) use ($userId, $sessionId) {
            if ($userId) $q->where('user_id', $userId);
            else $q->where('session_id', $sessionId);
        })->with(['item', 'color'])->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Giỏ hàng trống!');
        }

        // Require color selection for all car variants before proceeding to checkout form
        $missingColor = $cartItems->first(function($ci){
            return $ci->item_type === 'car_variant' && empty($ci->color_id);
        });
        if ($missingColor) {
            return redirect()->route('user.cart.index')->with('warning', 'Vui lòng chọn màu cho tất cả phiên bản trước khi thanh toán.');
        }

        $total = $cartItems->sum(function($ci){
                $model = $ci->item;
                if ($ci->item_type === 'car_variant' && method_exists($model, 'getPriceWithColorAdjustment')) {
                    $unit = $model->getPriceWithColorAdjustment($ci->color_id);
                } else {
                    $unit = $model->price ?? 0;
                }
                // Add selected feature/option surcharges from session meta
                $meta = session('cart_item_meta.' . $ci->id, []);
                $featIds = collect($meta['feature_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
                $featSum = !empty($featIds) ? (float) CarVariantFeature::whereIn('id', $featIds)->sum('price') : 0;
                $unit += $featSum;
                return $unit * $ci->quantity;
            });

        $addresses = collect();
        if ($user) {
            $addresses = $user->addresses()->orderByDesc('is_default')->get();
        }

        // Require default address before checkout
        if ($user) {
            $defaultAddress = $addresses->firstWhere('is_default', true);
            if (!$defaultAddress) {
                return redirect()->route('user.addresses.index')
                    ->with('warning', 'Vui lòng thêm một địa chỉ trước khi thanh toán.');
            }
        } else {
            return redirect()->route('login')->with('warning', 'Vui lòng đăng nhập để thanh toán.');
        }

        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('id')->get();
        $financeOptions = \App\Models\FinanceOption::where('is_active', true)->orderBy('sort_order')->get();

        // Issue one-time checkout token to prevent duplicate submissions/back-resubmit
        $checkoutToken = bin2hex(random_bytes(16));
        session(['checkout_token' => $checkoutToken]);

        return view('user.cart.checkout', compact('cartItems', 'total', 'user', 'addresses', 'paymentMethods', 'financeOptions', 'checkoutToken'));
    }

    public function processCheckout(Request $request)
    {
        try {
            // Idempotency guard: verify one-time checkout token
            $sessionToken = session('checkout_token');
            $postedToken = (string) $request->input('checkout_token');
            if (empty($sessionToken) || !hash_equals($sessionToken, $postedToken)) {
                return redirect()->route('user.cart.index')->with('warning', 'Phiên đặt hàng đã được xử lý hoặc hết hạn. Vui lòng mở lại trang thanh toán.');
            }
            // Invalidate immediately to prevent back-resubmit
            session()->forget('checkout_token');
            $user = Auth::user();
            $userId = $user ? $user->id : null;
            $sessionId = session()->getId();
            
            // Debug: Log request data
            \Log::info('Checkout request data:', $request->all());
            \Log::info('User authentication check:', [
                'user_id' => $userId,
                'user_exists' => $user ? 'yes' : 'no',
                'session_id' => $sessionId,
                'auth_check' => Auth::check()
            ]);

            $cartItems = CartItem::where(function ($q) use ($userId, $sessionId) {
                if ($userId) $q->where('user_id', $userId);
                else $q->where('session_id', $sessionId);
            })->with(['item', 'color'])->get();

            \Log::info('Cart items found:', ['count' => $cartItems->count()]);

            if ($cartItems->isEmpty()) {
                return redirect()->route('user.cart.index')->with('error', 'Giỏ hàng trống!');
            }

            // Normalize possible array inputs from multi-field UIs
            if (is_array($request->input('billing_address'))) {
                $request->merge(['billing_address' => trim(implode(', ', array_filter($request->input('billing_address'))))]);
            }
            if (is_array($request->input('shipping_address'))) {
                $request->merge(['shipping_address' => trim(implode(', ', array_filter($request->input('shipping_address'))))]);
            }

            $validated = $request->validate([
                'phone' => 'required|string|regex:/^[0-9+\-\s()]+$/|min:10|max:15',
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'billing_address_id' => 'nullable|integer|exists:addresses,id',
                'billing_address' => 'nullable|string|max:1000',
                'shipping_different' => 'nullable|boolean',
                'shipping_address_id' => 'nullable|integer|exists:addresses,id',
                'shipping_address' => 'nullable|string|max:1000',
                'shipping_method' => 'nullable|in:standard,express',
                'note' => 'nullable|string|max:1000',
                'payment_method_id' => 'required|exists:payment_methods,id',
                'payment_type' => 'required|in:full,finance',
                'finance_option_id' => 'required_if:payment_type,finance|exists:finance_options,id',
                'down_payment_percent' => 'required_if:payment_type,finance|numeric|min:20|max:80',
                'tenure_months' => 'required_if:payment_type,finance|integer|min:3|max:96',
                'terms_accepted' => 'required|accepted',
            ], [
                'phone.required' => 'Vui lòng nhập số điện thoại.',
                'phone.regex' => 'Số điện thoại không hợp lệ.',
                'phone.min' => 'Số điện thoại phải có ít nhất 10 ký tự.',
                'phone.max' => 'Số điện thoại không được vượt quá 15 ký tự.',
                'payment_method_id.required' => 'Vui lòng chọn phương thức thanh toán',
                'terms_accepted.accepted' => 'Bạn phải đồng ý với điều khoản sử dụng',
            ]);
            
            // Debug: Log validated data
            \Log::info('Validated checkout data:', $validated);

            // If user chooses different shipping address, enforce presence
            if (!empty($validated['shipping_different'])) {
                if (empty($validated['shipping_address']) && empty($validated['shipping_address_id'])) {
                    return back()->withErrors(['shipping_address' => 'Vui lòng nhập hoặc chọn địa chỉ giao hàng'])->withInput();
                }
            }

            $total = 0;
            $orderItems = [];
            
                foreach ($cartItems as $item) {
                if (!$item->item) {
                    return redirect()->route('user.cart.index')->with('error', 'Một số sản phẩm không còn khả dụng');
                }
                // Only enforce is_active when the attribute exists on the model
                if (isset($item->item->is_active) && !$item->item->is_active) {
                    return redirect()->route('user.cart.index')->with('error', 'Một số sản phẩm không còn khả dụng');
                }
                // Guard stock again at checkout
                if ($item->item_type === 'car_variant') {
                    $variant = $item->item;
                    $colorId = $item->color_id;
                    $inventory = is_array($variant->color_inventory) ? $variant->color_inventory : (json_decode($variant->color_inventory ?? 'null', true) ?: null);
                    if ($colorId) {
                        // Ưu tiên kiểm tra theo color_inventory JSON nếu có
                        if (is_array($inventory) && isset($inventory[$colorId])) {
                            $available = (int) ($inventory[$colorId]['available'] ?? $inventory[$colorId]['quantity'] ?? 0);
                            if ($available <= 0) {
                            return redirect()->route('user.cart.index')->with('error', 'Một số màu đã hết hàng');
                        }
                        } else {
                            // Fallback theo trạng thái của màu (availability) nếu được lưu
                            $color = $variant->colors()->where('id', $colorId)->first();
                            if ($color && isset($color->availability) && in_array($color->availability, ['out_of_stock','discontinued'], true)) {
                                return redirect()->route('user.cart.index')->with('error', 'Một số màu đã hết hàng');
                            }
                        }
                    } else {
                        // Không chọn màu: nếu có inventory thì tổng available phải > 0; nếu không, fallback is_available
                        if (is_array($inventory) && !empty($inventory)) {
                            $sumAvailable = 0;
                            foreach ($inventory as $inv) { $sumAvailable += (int) ($inv['available'] ?? $inv['quantity'] ?? 0); }
                            if ($sumAvailable <= 0) {
                        return redirect()->route('user.cart.index')->with('error', 'Một số phiên bản đã hết hàng');
                            }
                        } else if (isset($variant->is_available) && $variant->is_available === false) {
                            return redirect()->route('user.cart.index')->with('error', 'Một số phiên bản đã hết hàng');
                        }
                    }
                } else if ($item->item_type === 'accessory') {
                    $acc = $item->item;
                    if (isset($acc->stock_quantity) && $acc->stock_quantity !== null && (int)$acc->stock_quantity <= 0) {
                        return redirect()->route('user.cart.index')->with('error', 'Một số phụ kiện đã hết hàng');
                    }
                    if (isset($acc->stock_status) && in_array($acc->stock_status, ['out_of_stock','discontinued'], true)) {
                        return redirect()->route('user.cart.index')->with('error', 'Một số phụ kiện đã hết hàng');
                    }
                }

                // Get base price
                $basePrice = $item->item->current_price ?? 0;
                
                // Calculate color price adjustment
                $colorPrice = 0;
                $colorData = null;
                if ($item->item_type === 'car_variant' && $item->color_id) {
                    $unitPrice = $item->item->getPriceWithColorAdjustment($item->color_id);
                    $colorPrice = $unitPrice - $basePrice;
                    
                    // Get color details
                    $color = $item->color;
                    if ($color) {
                        $colorData = [
                            'id' => $color->id,
                            'name' => $color->color_name,
                            'hex' => $color->hex_code,
                            'price_adjustment' => $colorPrice,
                        ];
                    }
                } else {
                    $unitPrice = $basePrice;
                }
                
                // Include selected add-ons with full details
                $meta = session('cart_item_meta.' . $item->id, []);
                $featIds = collect($meta['feature_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
                $optIds = collect($meta['option_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
                
                // Get features with full details
                $featuresArray = [];
                $featSum = 0;
                if (!empty($featIds)) {
                    $features = CarVariantFeature::whereIn('id', $featIds)->get(['id', 'feature_name', 'category', 'price']);
                    foreach ($features as $feat) {
                        $featuresArray[] = [
                            'id' => $feat->id,
                            'name' => $feat->feature_name,
                            'category' => $feat->category,
                            'price' => $feat->price ?? 0,
                        ];
                        $featSum += $feat->price ?? 0;
                    }
                }
                
                $optSum  = !empty($optIds) ? (float) CarVariantOption::whereIn('id', $optIds)->sum(DB::raw('COALESCE(package_price, price, 0)')) : 0;
                $unitPrice += ($featSum + $optSum);
                $itemTotal = $unitPrice * $item->quantity;
                $total += $itemTotal;
                
                // Prepare metadata matching seeder format
                $metadata = [
                    'base_price' => $basePrice,
                    'color_price' => $colorPrice,
                    'features_price' => $featSum,
                    'final_price' => $unitPrice,
                    'color' => $colorData,
                    'features' => $featuresArray,
                    // Legacy fields for backward compatibility
                    'feature_ids' => $featIds,
                    'option_ids' => $optIds,
                    'feature_names' => !empty($featIds) ? collect($featuresArray)->pluck('name')->toArray() : [],
                    'option_names' => !empty($optIds) ? \App\Models\CarVariantOption::whereIn('id', $optIds)->pluck('option_name')->toArray() : [],
                    'feature_total' => $featSum,
                    'option_total' => $optSum,
                ];
                
                $orderItems[] = [
                    'item_type' => $item->item_type,
                    'item_id' => $item->item_id,
                    'color_id' => $item->color_id,
                    'quantity' => $item->quantity,
                    'price' => $unitPrice,
                    'total' => $itemTotal,
                    'item_metadata' => $metadata,
                ];
            }

            // Resolve billing & shipping addresses
            $billingAddressText = '';
            $shippingAddressText = '';
            $billingAddressId = null;
            $shippingAddressId = null;

            \Log::info('Starting address resolution:', [
                'billing_address_id' => $validated['billing_address_id'] ?? 'not_set',
                'billing_address' => $validated['billing_address'] ?? 'not_set',
                'user_exists' => $user ? 'yes' : 'no'
            ]);

            // Billing address resolution
            if (!empty($validated['billing_address_id']) && $user) {
                // Use saved address
                $addr = $user->addresses()->where('id', $validated['billing_address_id'])->first();
                if ($addr) {
                    $billingAddressId = $addr->id;
                    $billingAddressText = $addr->address;
                    \Log::info('Billing address resolved from saved:', ['id' => $billingAddressId, 'text' => $billingAddressText]);
                } else {
                    \Log::error('Billing address not found:', ['id' => $validated['billing_address_id']]);
                    return redirect()->route('user.cart.index')->with('error', 'Địa chỉ thanh toán không tồn tại');
                }
            } elseif (!empty($validated['billing_address']) && $user) {
                // Ensure user has profile
                if (!$user->userProfile) {
                    $user->userProfile()->create([
                        'profile_type' => 'customer',
                        'name' => $validated['name'],
                        'customer_type' => 'new',
                    ]);
                }
                
                // Enforce address cap (6)
                $addrCount = $user->addresses()->count();
                if ($addrCount >= 6) {
                    return back()->with('warning', 'Bạn đã đạt giới hạn 6 địa chỉ. Vui lòng chọn địa chỉ đã lưu hoặc xóa bớt địa chỉ cũ.')->withInput();
                }

                // Create new billing address
                $newAddr = $user->addresses()->create([
                    'type' => 'other', // Let user categorize later, not system-defined
                    'contact_name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'address' => $validated['billing_address'],
                    'city' => null,
                    'state' => null,
                    'country' => 'Vietnam',
                    'is_default' => $addrCount === 0, // First address is default
                ]);
                $billingAddressId = $newAddr->id;
                $billingAddressText = $newAddr->address;
                \Log::info('Billing address created:', ['id' => $billingAddressId, 'text' => $billingAddressText]);
            } else {
                \Log::error('Billing address resolution failed:', [
                    'billing_address_id' => $validated['billing_address_id'] ?? 'not_set',
                    'billing_address' => $validated['billing_address'] ?? 'not_set',
                    'user_exists' => $user ? 'yes' : 'no',
                    'user_id' => $userId
                ]);
                // Stay on checkout and show a toast instead of redirecting to cart
                return back()->with('error', 'Vui lòng chọn hoặc nhập địa chỉ thanh toán')->withInput();
            }

            // Shipping: same as billing by default
            if (!empty($validated['shipping_different'])) {
                if (!empty($validated['shipping_address_id']) && $user) {
                    // Use saved shipping address
                    $saddr = $user->addresses()->where('id', $validated['shipping_address_id'])->first();
                    if ($saddr) {
                        $shippingAddressId = $saddr->id;
                        $shippingAddressText = $saddr->address;
                    }
                } elseif (!empty($validated['shipping_address']) && $user) {
                    // Enforce address cap (6)
                    $addrCount = $user->addresses()->count();
                    if ($addrCount >= 6) {
                        return back()->with('warning', 'Bạn đã đạt giới hạn 6 địa chỉ. Vui lòng chọn địa chỉ đã lưu hoặc xóa bớt địa chỉ cũ.')->withInput();
                    }

                    // Create new shipping address
                    $newSAddr = $user->addresses()->create([
                        'type' => 'other', // Let user categorize later, not system-defined
                        'contact_name' => $validated['name'],
                        'phone' => $validated['phone'],
                        'address' => $validated['shipping_address'],
                        'city' => null,
                        'state' => null,
                        'country' => 'Vietnam',
                        'is_default' => false,
                    ]);
                    $shippingAddressId = $newSAddr->id;
                    $shippingAddressText = $newSAddr->address;
                }
            } else {
                $shippingAddressId = $billingAddressId;
                $shippingAddressText = $billingAddressText;
            }

            // Calculate tax and shipping
            $shippingMethod = $validated['shipping_method'] ?? 'standard';
            $shippingFee = $shippingMethod === 'express' ? 50000 : 30000;
            $taxRate = 0.10; // 10% VAT
            $taxTotal = (int) round($total * $taxRate);
            
            // Handle promotion if provided
            $promotionId = null;
            $discountTotal = 0;
            
            if ($request->promotion_code) {
                $promotion = \App\Models\Promotion::where('code', strtoupper(trim($request->promotion_code)))
                    ->where('is_active', true)
                    ->first();
                    
                if ($promotion) {
                    // Validate promotion
                    $now = now();
                    $isValid = true;
                    
                    // Check dates
                    if ($promotion->start_date && $now < $promotion->start_date) $isValid = false;
                    if ($promotion->end_date && $now > $promotion->end_date) $isValid = false;
                    
                    // Check usage limit
                    if ($promotion->usage_limit && $promotion->usage_count >= $promotion->usage_limit) $isValid = false;
                    
                    // Check minimum order amount
                    if ($promotion->min_order_amount && $total < $promotion->min_order_amount) $isValid = false;
                    
                    if ($isValid) {
                        // Calculate discount using promotion logic
                        $cartItems = \App\Models\CartItem::where('user_id', $userId ?? null)
                            ->orWhere('session_id', $sessionId)
                            ->with(['item'])
                            ->get();
                            
                        // Simple discount calculation based on promotion type
                        switch ($promotion->type) {
                            case 'percentage':
                                $discountTotal = $total * ($promotion->discount_value / 100);
                                if ($promotion->max_discount_amount) {
                                    $discountTotal = min($discountTotal, $promotion->max_discount_amount);
                                }
                                break;
                            case 'fixed_amount':
                                $discountTotal = min($promotion->discount_value, $total);
                                break;
                            case 'free_shipping':
                                $discountTotal = $shippingFee; // Will be subtracted from total
                                break;
                            default:
                                $discountTotal = 0;
                        }
                        
                        // For free shipping, don't limit by total since it affects shipping fee
                        if ($promotion->type !== 'free_shipping') {
                            $discountTotal = min($discountTotal, $total);
                        }
                        $promotionId = $promotion->id;
                    }
                }
            }
            
            $grandTotal = $total + $taxTotal + $shippingFee - $discountTotal;

            $methodCode = optional(\App\Models\PaymentMethod::find($validated['payment_method_id']))->code;
            
            // Store payment method in session for mock gateway
            session(['selected_payment_method' => $methodCode]);
            
            // For VNPay and MoMo, create order after payment success
            if (in_array($methodCode, ['vnpay', 'momo'])) {
                return $this->processOnlinePayment($methodCode, $validated, $orderItems, $cartItems, $userId, $sessionId, $total, $taxTotal, $shippingFee, $grandTotal, $billingAddressId, $shippingAddressId, $billingAddressText, $shippingAddressText, $promotionId, $discountTotal);
            }
            
            // For COD and Bank Transfer, create order immediately
            $placeOrder = app(PlaceOrder::class);
            // Calculate finance data if applicable
            $financeData = [];
            if ($validated['payment_type'] === 'finance' && !empty($validated['finance_option_id'])) {
                $financeOption = \App\Models\FinanceOption::find($validated['finance_option_id']);
                $downPaymentPercent = $validated['down_payment_percent'] ?? 30;
                $tenureMonths = $validated['tenure_months'] ?? 36;
                
                // Finance calculation should be based on product value only (excluding tax, shipping, and discount)
                $financeableAmount = $total - $discountTotal; // Product value after discount
                $downPaymentAmount = round($financeableAmount * ($downPaymentPercent / 100));
                $loanAmount = $financeableAmount - $downPaymentAmount;
                
                // Calculate monthly payment
                $monthlyRate = ($financeOption->interest_rate / 100) / 12;
                $monthlyPayment = $monthlyRate > 0 
                    ? round($loanAmount * ($monthlyRate * pow(1 + $monthlyRate, $tenureMonths)) / (pow(1 + $monthlyRate, $tenureMonths) - 1))
                    : round($loanAmount / $tenureMonths);
                
                $financeData = [
                    'finance_option_id' => $validated['finance_option_id'],
                    'down_payment_amount' => $downPaymentAmount,
                    'tenure_months' => $tenureMonths,
                    'monthly_payment_amount' => $monthlyPayment,
                ];
            }

            $orderData = array_merge([
                'user_id' => $userId,
                'phone' => $validated['phone'],
                'name' => $validated['name'],
                'email' => $validated['email'] ?? $user?->email,
                'address' => $shippingAddressText ?: $billingAddressText,
                'note' => $validated['note'] ?? null,
                'payment_method_id' => $validated['payment_method_id'],
                'billing_address_id' => $billingAddressId,
                'shipping_address_id' => $shippingAddressId,
                'subtotal' => $total,
                'tax_total' => $taxTotal,
                'shipping_fee' => $shippingFee,
                'discount_total' => $discountTotal,
                'grand_total' => $grandTotal,
                'promotion_id' => $promotionId,
                'shipping_method' => $shippingMethod,
                'tax_rate' => $taxRate,
                'items' => array_map(function ($item) use ($cartItems) {
                    $itemModel = $cartItems->firstWhere(function($cartItem) use ($item) {
                        return $cartItem->item_id == $item['item_id'] && $cartItem->item_type == $item['item_type'];
                    })?->item;
                    return [
                        'item_type' => $item['item_type'],
                        'item_id' => $item['item_id'],
                        'color_id' => $item['color_id'],
                        'quantity' => $item['quantity'],
                        'price' => ($item['price'] ?? ($itemModel->current_price ?? 0)),
                        'item_metadata' => $item['item_metadata'] ?? null,
                    ];
                }, $orderItems),
            ], $financeData);

            $order = $placeOrder->handle($orderData);

            // Increment promotion usage count if promotion was applied
            if ($promotionId) {
                \App\Models\Promotion::find($promotionId)->increment('used_count');
            }

            // Create initial payment log for COD and Bank Transfer
            if (in_array($methodCode, ['cod', 'bank_transfer'])) {
                \App\Models\OrderLog::create([
                    'order_id' => $order->id,
                    'user_id' => \Illuminate\Support\Facades\Auth::id() ?? $order->user_id,
                    'action' => 'payment_pending',
                    'message' => $methodCode === 'cod' 
                        ? 'Chờ thanh toán khi nhận hàng (COD)' 
                        : 'Chờ xác nhận chuyển khoản',
                    'details' => [
                        'payment_method' => $methodCode,
                        'payment_status' => 'pending',
                        'amount' => $order->grand_total,
                    ],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }

            // Notifications are handled by OrderCreated event listener

            switch ($methodCode) {
                case 'bank_transfer':
                    return $this->processBankTransfer($order, $cartItems, $userId, $sessionId);
                case 'cod':
                default:
                    return $this->processCODPayment($order, $cartItems, $userId, $sessionId);
            }

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Checkout error:', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return back()->with('error', 'Có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại.')->withInput();
        }
    }

    private function processOnlinePayment($methodCode, $validated, $orderItems, $cartItems, $userId, $sessionId, $total, $taxTotal, $shippingFee, $grandTotal, $billingAddressId, $shippingAddressId, $billingAddressText, $shippingAddressText, $promotionId = null, $discountTotal = 0)
    {
        // Calculate finance data if applicable
        $financeData = [];
        if ($validated['payment_type'] === 'finance' && !empty($validated['finance_option_id'])) {
            $financeOption = \App\Models\FinanceOption::find($validated['finance_option_id']);
            $downPaymentPercent = $validated['down_payment_percent'] ?? 30;
            $tenureMonths = $validated['tenure_months'] ?? 36;
            
            // Finance calculation should be based on product value only (excluding tax, shipping, and discount)
            $financeableAmount = $total - $discountTotal; // Product value after discount
            $downPaymentAmount = round($financeableAmount * ($downPaymentPercent / 100));
            $loanAmount = $financeableAmount - $downPaymentAmount;
            
            // Calculate monthly payment
            $monthlyRate = ($financeOption->interest_rate / 100) / 12;
            $monthlyPayment = $monthlyRate > 0 
                ? round($loanAmount * ($monthlyRate * pow(1 + $monthlyRate, $tenureMonths)) / (pow(1 + $monthlyRate, $tenureMonths) - 1))
                : round($loanAmount / $tenureMonths);
            
            $financeData = [
                'finance_option_id' => $validated['finance_option_id'],
                'down_payment_amount' => $downPaymentAmount,
                'tenure_months' => $tenureMonths,
                'monthly_payment_amount' => $monthlyPayment,
            ];
        }

        // Store order data in session for later creation
        $orderData = array_merge([
            'user_id' => $userId,
            'phone' => $validated['phone'],
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'address' => $shippingAddressText ?: $billingAddressText,
            'note' => $validated['note'] ?? null,
            'payment_method_id' => $validated['payment_method_id'],
            'billing_address_id' => $billingAddressId,
            'shipping_address_id' => $shippingAddressId,
            'subtotal' => $total,
            'tax_total' => $taxTotal,
            'shipping_fee' => $shippingFee,
            'discount_total' => $discountTotal,
            'grand_total' => $grandTotal,
            'promotion_id' => $promotionId,
            'shipping_method' => $validated['shipping_method'] ?? 'standard',
            'tax_rate' => 0.10,
            'items' => array_map(function ($item) use ($cartItems) {
                $itemModel = $cartItems->firstWhere(function($cartItem) use ($item) {
                    return $cartItem->item_id == $item['item_id'] && $cartItem->item_type == $item['item_type'];
                })?->item;
                return [
                    'item_type' => $item['item_type'],
                    'item_id' => $item['item_id'],
                    'color_id' => $item['color_id'],
                    'quantity' => $item['quantity'],
                    'price' => ($item['price'] ?? ($itemModel->current_price ?? 0)),
                    'item_metadata' => $item['item_metadata'] ?? null,
                ];
            }, $orderItems),
        ], $financeData);
        
        session(['pending_order_data' => $orderData]);
        
        $gatewayMode = env('PAYMENT_GATEWAY_MODE', 'sandbox');
        if ($gatewayMode === 'mock') {
            if ($methodCode === 'vnpay' || $methodCode === 'momo') {
                // Create order immediately (mock success)
                $placeOrder = app(PlaceOrder::class);
                $order = $placeOrder->handle($orderData);
                return $this->processMockOnlinePayment($order, $cartItems, $userId, $sessionId, strtoupper($methodCode));
            }
        } else {
            // Use gateway factory for online payments
            $gateway = \App\Services\Payments\PaymentGatewayFactory::make($methodCode);
            return $gateway->createPayment($orderData);
        }
    }


    private function processMoMoPayment($orderData)
    {
        // Store order data in session for MoMo processing
        session(['pending_order_data' => $orderData]);
        
        // Generate temporary order number for MoMo
        $tempOrderNumber = 'TEMP-' . date('Ymd') . '-' . strtoupper(uniqid());
        
        return redirect()->route('payment.momo.process', ['order_id' => $tempOrderNumber]);
    }

    private function processBankTransfer($order, $cartItems, $userId, $sessionId)
    {
        // Clear cart items to prevent duplicate orders
        CartItem::where(function ($q) use ($userId, $sessionId) {
            if ($userId) $q->where('user_id', $userId);
            else $q->where('session_id', $sessionId);
        })->delete();

        // Email + notifications for bank transfer are already triggered by OrderCreated listener

        return redirect()->route('user.order.success', ['order' => $order->id])
            ->with('success', 'Đơn hàng đã được tạo. Vui lòng chuyển khoản theo hướng dẫn.')
            ->with('payment_method', 'bank_transfer');
    }

    private function processCODPayment($order, $cartItems, $userId, $sessionId)
    {
        CartItem::where(function ($q) use ($userId, $sessionId) {
            if ($userId) $q->where('user_id', $userId);
            else $q->where('session_id', $sessionId);
        })->delete();

        // Email + notifications for COD are already triggered by OrderCreated listener

        return redirect()->route('user.order.success', ['order' => $order->id])
            ->with('success', 'Đặt hàng thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.');
    }

    private function processMockOnlinePayment($order, $cartItems, $userId, $sessionId, string $gateway)
    {
        // Clear cart items
        CartItem::where(function ($q) use ($userId, $sessionId) {
            if ($userId) $q->where('user_id', $userId);
            else $q->where('session_id', $sessionId);
        })->delete();

        \Log::info('Mock online payment success', [
            'gateway' => $gateway,
            'order_id' => $order->id,
        ]);

        return redirect()->route('user.order.success', ['order' => $order->id])
            ->with('success', 'Thanh toán (MOCK ' . $gateway . ') thành công!');
    }

    public function orderSuccess(Request $request, Order $order)
    {
        if (!Auth::check() || ($order->user_id && $order->user_id !== Auth::id())) {
            abort(403);
        }
        $order = $order->load([
            'items.item',
            'items.color',
            'paymentMethod',
            'financeOption',
            'billingAddress',
            'shippingAddress',
            'promotion',
        ]);

        return view('user.cart.success', [
            'order' => $order,
        ]);
    }

    private function getCartCount($userId, $sessionId): int
    {
        return CartItem::where(function($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->sum('quantity');
    }
}