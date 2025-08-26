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
                $item->item->load('colors', 'images', 'featuresRelation', 'options');
            }
        }

        return view('cart.index', compact('cartItems'));
    }

    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'item_type' => 'required|in:car_variant,accessory',
                'item_id' => 'required|integer|min:1',
                'quantity' => 'integer|min:1|max:10',
                'color_id' => 'nullable|integer|exists:car_variant_colors,id',
                'feature_ids' => 'nullable|array',
                'feature_ids.*' => 'integer|exists:car_variant_features,id',
                'option_ids' => 'nullable|array',
                'option_ids.*' => 'integer|exists:car_variant_options,id'
            ]);

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

            if (!$item->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm hiện không có sẵn'
                ], 400);
            }

            // Stock guard: for car_variant, check variant or selected color stock
            if ($validated['item_type'] === 'car_variant') {
                // If color selected, prefer color stock; else use variant stock
                if (!is_null($colorId)) {
                    $color = $item->colors()->where('id', $colorId)->first();
                    if (!$color || ($color->stock_quantity ?? 0) <= 0) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Màu đã hết hàng'
                        ], 400);
                    }
                } else {
                    if (($item->stock_quantity ?? 0) <= 0) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Phiên bản đã hết hàng'
                        ], 400);
                    }
                }
            }

            // Use CartHelper to add item
            $result = \App\Helpers\CartHelper::addToCart(
                $validated['item_type'],
                $validated['item_id'],
                $quantity,
                $colorId
            );

            // Persist selected features/options in session keyed by cart_item_id (lightweight without schema changes)
            if (!empty($result['cart_item_id'])) {
                $meta = [
                    'feature_ids' => array_values(array_unique(array_map('intval', $validated['feature_ids'] ?? []))),
                    'option_ids' => array_values(array_unique(array_map('intval', $validated['option_ids'] ?? []))),
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
                'option_ids' => 'nullable|array',
                'option_ids.*' => 'integer|exists:car_variant_options,id'
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
            // Persist selected features/options into session meta for this cart item
            $featureIds = $request->input('feature_ids', null);
            $optionIds = $request->input('option_ids', null);
            if (is_array($featureIds) || is_array($optionIds)) {
                $meta = [
                    'feature_ids' => array_values(array_unique(array_map('intval', $featureIds ?? []))),
                    'option_ids' => array_values(array_unique(array_map('intval', $optionIds ?? []))),
                ];
                session()->put('cart_item_meta.' . $cartItem->id, $meta);
            }

            $cartItem->update($updateData);
            $cartItem->load(['item', 'color']);

            if ($request->ajax()) {
                // Invalidate nav cached counts so first paint is correct
                $this->invalidateNavCountsCache();
                $cartCount = $this->computeCartCount(Auth::id(), session()->getId());
                // Tính lại đơn giá theo màu (nếu là car_variant)
                $unitPrice = 0;
                if ($cartItem->item) {
                    if ($cartItem->item_type === 'car_variant' && method_exists($cartItem->item, 'getPriceWithColorAdjustment')) {
                        $unitPrice = $cartItem->item->getPriceWithColorAdjustment($cartItem->color_id);
                    } else {
                        $unitPrice = $cartItem->item->price ?? 0;
                    }
                    // Include selected add-ons from session meta
                    $meta = session('cart_item_meta.' . $cartItem->id, []);
                    $featIds = collect($meta['feature_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
                    $optIds = collect($meta['option_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
                    $featSum = !empty($featIds) ? (float) CarVariantFeature::whereIn('id', $featIds)->sum(DB::raw('COALESCE(package_price, price, 0)')) : 0;
                    $optSum  = !empty($optIds) ? (float) CarVariantOption::whereIn('id', $optIds)->sum(DB::raw('COALESCE(package_price, price, 0)')) : 0;
                    $unitPrice += ($featSum + $optSum);
                }
                $itemTotal = $unitPrice * $cartItem->quantity;
                $colorName = $cartItem->color ? $cartItem->color->color_name : null;

                return response()->json([
                    'success' => true,
                    'cart_count' => $cartCount,
                    'unit_price' => $unitPrice,
                    'item_total' => $itemTotal,
                    'quantity' => $cartItem->quantity,
                    'color_id' => $cartItem->color_id,
                    'color_name' => $colorName,
                    'message' => $request->has('color_id') ? 'Cập nhật màu sắc thành công!' : 'Cập nhật thành công!',
                    'feature_ids' => $featureIds ?? ($meta['feature_ids'] ?? []),
                    'option_ids' => $optionIds ?? ($meta['option_ids'] ?? []),
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
                $optIds = collect($meta['option_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
                $featSum = !empty($featIds) ? (float) CarVariantFeature::whereIn('id', $featIds)->sum(DB::raw('COALESCE(package_price, price, 0)')) : 0;
                $optSum  = !empty($optIds) ? (float) CarVariantOption::whereIn('id', $optIds)->sum(DB::raw('COALESCE(package_price, price, 0)')) : 0;
                $unit += ($featSum + $optSum);
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
                    ->with('warning', 'Vui lòng thêm và đặt một địa chỉ mặc định trước khi thanh toán.');
            }
        } else {
            return redirect()->route('login')->with('warning', 'Vui lòng đăng nhập để thanh toán.');
        }

        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('id')->get();

        return view('cart.checkout', compact('cartItems', 'total', 'user', 'addresses', 'paymentMethods'));
    }

    public function processCheckout(Request $request)
    {
        try {
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
                'billing_address_id' => 'required|integer|exists:addresses,id',
                'shipping_different' => 'nullable|boolean',
                'shipping_address_id' => 'nullable|integer|exists:addresses,id',
                'shipping_method' => 'nullable|in:standard,express',
                'note' => 'nullable|string|max:1000',
                'payment_method_id' => 'required|exists:payment_methods,id',
                'terms_accepted' => 'required|accepted',
            ], [
                'phone.regex' => 'Số điện thoại không hợp lệ',
                'billing_address_id.required' => 'Vui lòng chọn địa chỉ thanh toán',
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
                if (!$item->item || !$item->item->is_active) {
                    return redirect()->route('user.cart.index')->with('error', 'Một số sản phẩm không còn khả dụng');
                }
                // Guard stock again at checkout
                if ($item->item_type === 'car_variant') {
                    if ($item->color_id) {
                        $color = $item->item->colors()->where('id', $item->color_id)->first();
                        if (!$color || ($color->stock_quantity ?? 0) <= 0) {
                            return redirect()->route('user.cart.index')->with('error', 'Một số màu đã hết hàng');
                        }
                    } else if (($item->item->stock_quantity ?? 0) <= 0) {
                        return redirect()->route('user.cart.index')->with('error', 'Một số phiên bản đã hết hàng');
                    }
                }

                $unitPrice = ($item->item_type === 'car_variant' && method_exists($item->item, 'getPriceWithColorAdjustment'))
                    ? $item->item->getPriceWithColorAdjustment($item->color_id)
                    : ($item->item->price ?? 0);
                // Include selected add-ons
                $meta = session('cart_item_meta.' . $item->id, []);
                $featIds = collect($meta['feature_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
                $optIds = collect($meta['option_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
                $featSum = !empty($featIds) ? (float) CarVariantFeature::whereIn('id', $featIds)->sum(DB::raw('COALESCE(package_price, price, 0)')) : 0;
                $optSum  = !empty($optIds) ? (float) CarVariantOption::whereIn('id', $optIds)->sum(DB::raw('COALESCE(package_price, price, 0)')) : 0;
                $unitPrice += ($featSum + $optSum);
                $itemTotal = $unitPrice * $item->quantity;
                $total += $itemTotal;
                
                $orderItems[] = [
                    'item_type' => $item->item_type,
                    'item_id' => $item->item_id,
                    'color_id' => $item->color_id,
                    'quantity' => $item->quantity,
                    'price' => $unitPrice,
                    'total' => $itemTotal,
                ];
            }

            // Resolve billing & shipping addresses
            $billingAddressText = '';
            $shippingAddressText = '';
            $billingAddressId = null;
            $shippingAddressId = null;

            \Log::info('Starting address resolution:', [
                'billing_address_id' => $validated['billing_address_id'] ?? 'not_set',
                'user_exists' => $user ? 'yes' : 'no'
            ]);

            // Billing from saved address
            if (!empty($validated['billing_address_id']) && $user) {
                \Log::info('Checking user access to billing address:', [
                    'user_id' => $user->id,
                    'billing_address_id' => $validated['billing_address_id']
                ]);
                
                $addr = $user->addresses()->where('id', $validated['billing_address_id'])->first();
                if ($addr) {
                    $billingAddressId = $addr->id;
                    $billingAddressText = $addr->address;
                    \Log::info('Billing address resolved:', ['id' => $billingAddressId, 'text' => $billingAddressText]);
                } else {
                    \Log::error('Billing address not found:', ['id' => $validated['billing_address_id']]);
                    return redirect()->route('user.cart.index')->with('error', 'Địa chỉ thanh toán không tồn tại');
                }
            } else {
                \Log::error('Billing address resolution failed:', [
                    'billing_address_id' => $validated['billing_address_id'] ?? 'not_set',
                    'user_exists' => $user ? 'yes' : 'no',
                    'user_id' => $userId
                ]);
                return redirect()->route('user.cart.index')->with('error', 'Vui lòng chọn địa chỉ thanh toán');
            }

            // Shipping: same as billing by default
            if (!empty($validated['shipping_different'])) {
                if (!empty($validated['shipping_address_id']) && $user) {
                    $saddr = $user->addresses()->where('id', $validated['shipping_address_id'])->first();
                    if ($saddr) {
                        $shippingAddressId = $saddr->id;
                        $shippingAddressText = $saddr->address;
                    }
                }
            } else {
                $shippingAddressId = $billingAddressId;
                $shippingAddressText = $billingAddressText;
            }

            // Shipping/tax totals
            $shippingFee = ($validated['shipping_method'] ?? 'standard') === 'express' ? 50000 : 30000;
            $taxTotal = (int) round($total * 0.1);
            $grandTotal = $total + $shippingFee + $taxTotal;

            $placeOrder = app(PlaceOrder::class);
            $order = $placeOrder->handle([
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
                'grand_total' => $grandTotal,
                'items' => array_map(function ($item) use ($cartItems) {
                    $itemModel = $cartItems->firstWhere(function($cartItem) use ($item) {
                        return $cartItem->item_id == $item['item_id'] && $cartItem->item_type == $item['item_type'];
                    })?->item;
                    return [
                        'item_type' => $item['item_type'],
                        'item_id' => $item['item_id'],
                        'color_id' => $item['color_id'],
                        'quantity' => $item['quantity'],
                        'price' => ($item['price'] ?? ($itemModel->price ?? 0)),
                    ];
                }, $orderItems),
            ]);

            // Notifications are handled by OrderCreated event listener

            $methodCode = optional(\App\Models\PaymentMethod::find($validated['payment_method_id']))->code;
            switch ($methodCode) {
                case 'vnpay':
                    return $this->processVNPayPayment($order);
                case 'momo':
                    return $this->processMoMoPayment($order);
                case 'bank_transfer':
                    return $this->processBankTransfer($order);
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
            ]);
            
            return back()->with('error', 'Có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại.');
        }
    }

    private function processVNPayPayment($order)
    {
        $vnpUrl = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnpReturnUrl = route('payment.vnpay.return');
        $vnpTmnCode = env('VNPAY_TMN_CODE', '');
        $vnpHashSecret = env('VNPAY_HASH_SECRET', '');
        
        $vnpTxnRef = $order->order_number;
        $vnpOrderInfo = "Thanh toan don hang " . $order->order_number;
        $vnpOrderType = "other";
        $vnpAmount = $order->total_price * 100;
        $vnpLocale = 'vn';
        $vnpCurrCode = 'VND';
        
        $vnpParams = array();
        $vnpParams['vnp_Version'] = '2.1.0';
        $vnpParams['vnp_Command'] = 'pay';
        $vnpParams['vnp_TmnCode'] = $vnpTmnCode;
        $vnpParams['vnp_Amount'] = $vnpAmount;
        $vnpParams['vnp_CurrCode'] = $vnpCurrCode;
        $vnpParams['vnp_BankCode'] = '';
        $vnpParams['vnp_TxnRef'] = $vnpTxnRef;
        $vnpParams['vnp_OrderInfo'] = $vnpOrderInfo;
        $vnpParams['vnp_OrderType'] = $vnpOrderType;
        $vnpParams['vnp_Locale'] = $vnpLocale;
        $vnpParams['vnp_ReturnUrl'] = $vnpReturnUrl;
        $vnpParams['vnp_IpAddr'] = request()->ip();
        $vnpParams['vnp_CreateDate'] = date('YmdHis');
        
        ksort($vnpParams);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($vnpParams as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        
        $vnpUrl = $vnpUrl . "?" . $query;
        if (isset($vnpHashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnpHashSecret);
            $vnpUrl .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        
        return redirect($vnpUrl);
    }

    private function processMoMoPayment($order)
    {
        return redirect()->route('payment.momo.process', ['order_id' => $order->id]);
    }

    private function processBankTransfer($order)
    {
        // Giữ trạng thái đơn theo luồng: chỉ cập nhật payment_status qua webhook/ngoại tuyến
        return view('payment.bank-transfer', compact('order'))->with('success', 'Đơn hàng đã được tạo. Vui lòng chuyển khoản theo hướng dẫn.');
    }

    private function processCODPayment($order, $cartItems, $userId, $sessionId)
    {
        CartItem::where(function ($q) use ($userId, $sessionId) {
            if ($userId) $q->where('user_id', $userId);
            else $q->where('session_id', $sessionId);
        })->delete();

        // Email + notifications for COD are already triggered by OrderCreated listener

        return redirect()->route('order.success', ['order' => $order->id])
            ->with('success', 'Đặt hàng thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.');
    }

    public function orderSuccess(Request $request, Order $order)
    {
        if (!Auth::check() || ($order->user_id && $order->user_id !== Auth::id())) {
            abort(403);
        }

        $order->load([
            'items.item',
            'items.color',
            'paymentMethod',
            'billingAddress',
            'shippingAddress',
        ]);

        return view('order.success', [
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