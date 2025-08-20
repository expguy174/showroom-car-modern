<?php

namespace App\Helpers;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CartHelper
{
    /**
     * Get cart count for current user
     */
    public static function getCartCount()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $cacheKey = "cart_count_user_{$userId}";
            
            return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($userId) {
                return CartItem::where('user_id', $userId)->sum('quantity');
            });
        } else {
            $sessionId = session()->getId();
            $cacheKey = "cart_count_session_{$sessionId}";
            
            return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($sessionId) {
                return CartItem::where('session_id', $sessionId)->sum('quantity');
            });
        }
    }

    /**
     * Clear cart count cache for current user
     */
    public static function clearCartCountCache()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            Cache::forget("cart_count_user_{$userId}");
        } else {
            $sessionId = session()->getId();
            Cache::forget("cart_count_session_{$sessionId}");
        }
    }

    /**
     * Get cart items for current user
     */
    public static function getCartItems()
    {
        if (Auth::check()) {
            return CartItem::where('user_id', Auth::id())
                ->with(['item', 'color'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $sessionId = session()->getId();
            return CartItem::where('session_id', $sessionId)
                ->with(['item', 'color'])
                ->orderBy('created_at', 'desc')
                ->get();
        }
    }

    /**
     * Add item to cart and return updated count
     */
    public static function addToCart($itemType, $itemId, $quantity = 1, $colorId = null)
    {
        $userId = Auth::id();
        $sessionId = session()->getId();

        // Check if item already exists in cart
        $existingItem = CartItem::where('item_type', $itemType)
            ->where('item_id', $itemId)
            ->where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->first();

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $quantity
            ]);
        } else {
            CartItem::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'item_type' => $itemType,
                'item_id' => $itemId,
                'quantity' => $quantity,
                'color_id' => $colorId
            ]);
        }

        self::clearCartCountCache();
        
        return [
            'success' => true,
            'message' => $existingItem ? 'Đã cập nhật số lượng trong giỏ hàng' : 'Đã thêm vào giỏ hàng thành công',
            'cart_count' => self::getCartCount()
        ];
    }

    /**
     * Remove item from cart and return updated count
     */
    public static function removeFromCart($cartItemId)
    {
        $cartItem = CartItem::find($cartItemId);
        
        if (!$cartItem) {
            return [
                'success' => false,
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng!'
            ];
        }

        // Check if user owns this cart item
        if (Auth::check()) {
            if ($cartItem->user_id !== Auth::id()) {
                return [
                    'success' => false,
                    'message' => 'Bạn không có quyền xóa sản phẩm này!'
                ];
            }
        } else {
            if ($cartItem->session_id !== session()->getId()) {
                return [
                    'success' => false,
                    'message' => 'Bạn không có quyền xóa sản phẩm này!'
                ];
            }
        }

        $cartItem->delete();
        self::clearCartCountCache();
        
        return [
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng!',
            'cart_count' => self::getCartCount()
        ];
    }

    /**
     * Update cart item quantity and return updated count
     */
    public static function updateCartItem($cartItemId, $quantity, $colorId = null)
    {
        $cartItem = CartItem::find($cartItemId);
        
        if (!$cartItem) {
            return [
                'success' => false,
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng!'
            ];
        }

        // Check if user owns this cart item
        if (Auth::check()) {
            if ($cartItem->user_id !== Auth::id()) {
                return [
                    'success' => false,
                    'message' => 'Bạn không có quyền cập nhật sản phẩm này!'
                ];
            }
        } else {
            if ($cartItem->session_id !== session()->getId()) {
                return [
                    'success' => false,
                    'message' => 'Bạn không có quyền cập nhật sản phẩm này!'
                ];
            }
        }

        $updateData = ['quantity' => $quantity];
        if ($colorId !== null) {
            $updateData['color_id'] = $colorId;
        }

        $cartItem->update($updateData);
        self::clearCartCountCache();
        
        return [
            'success' => true,
            'message' => 'Cập nhật thành công!',
            'cart_count' => self::getCartCount(),
            'item_total' => $cartItem->item->price * $quantity,
            'quantity' => $quantity,
            'color_id' => $cartItem->color_id
        ];
    }

    /**
     * Clear all cart items and return updated count
     */
    public static function clearCart()
    {
        $userId = Auth::id();
        $sessionId = session()->getId();
        
        CartItem::where(function ($q) use ($userId, $sessionId) {
            if ($userId) {
                $q->where('user_id', $userId);
            } else {
                $q->where('session_id', $sessionId);
            }
        })->delete();

        self::clearCartCountCache();
        
        return [
            'success' => true,
            'message' => 'Đã xóa toàn bộ giỏ hàng!',
            'cart_count' => 0
        ];
    }

    /**
     * Get cart total price
     */
    public static function getCartTotal()
    {
        $cartItems = self::getCartItems();
        $total = 0;
        
        foreach ($cartItems as $item) {
            if ($item->item) {
                $unitPrice = $item->item->price ?? 0;
                if ($item->item_type === 'car_variant' && method_exists($item->item, 'getPriceWithColorAdjustment')) {
                    $unitPrice = $item->item->getPriceWithColorAdjustment($item->color_id);
                }
                $total += $unitPrice * $item->quantity;
            }
        }
        
        return $total;
    }
}
