<?php

namespace App\Helpers;

use App\Models\WishlistItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class WishlistHelper
{
    /**
     * Check if an item is in user's wishlist
     */
    public static function isInWishlist($itemType = null, $itemId = null, $productId = null)
    {
        // Backward compatibility for old product_id parameter
        if ($productId !== null) {
            $itemType = 'product';
            $itemId = $productId;
        }
        
        if (Auth::check()) {
            $itemTypeClass = self::getItemTypeClass($itemType);
            return WishlistItem::where('user_id', Auth::id())
                ->where('item_type', $itemTypeClass)
                ->where('item_id', $itemId)
                ->exists();
        } else {
            $wishlistData = session()->get('wishlist', []);
            $itemKey = $itemType . '_' . $itemId;
            return isset($wishlistData[$itemKey]);
        }
    }

    /**
     * Get wishlist count for current user
     */
    public static function getWishlistCount()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $cacheKey = "wishlist_count_user_{$userId}";
            return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($userId) {
                return WishlistItem::where('user_id', $userId)->count();
            });
        }
        $sessionId = session()->getId();
        $cacheKey = "wishlist_count_session_{$sessionId}";
        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($sessionId) {
            if (Schema::hasTable('wishlist_items') && Schema::hasColumn('wishlist_items', 'session_id')) {
                try {
                    return WishlistItem::where('session_id', $sessionId)->count();
                } catch (\Throwable $e) {
                    // fallback to session storage
                }
            }
            $wishlistData = session()->get('wishlist', []);
            return count($wishlistData);
        });
    }

    /**
     * Clear wishlist count cache for current user
     */
    public static function clearWishlistCountCache()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            Cache::forget("wishlist_count_user_{$userId}");
            return;
        }
        $sessionId = session()->getId();
        Cache::forget("wishlist_count_session_{$sessionId}");
    }

    /**
     * Get all wishlist items for current user
     */
    public static function getWishlistItems()
    {
        if (Auth::check()) {
            $items = WishlistItem::where('user_id', Auth::id())
                ->with(['item'])
                ->orderBy('created_at', 'desc')
                ->get();
            return $items->map(function($item) {
                $itemType = $item->item_type === \App\Models\CarVariant::class ? 'car_variant' : 'accessory';
                return (object) [
                    'id' => $item->id,
                    'item' => $item->item,
                    'item_type' => $itemType,
                    'item_id' => $item->item_id,
                    'created_at' => $item->created_at
                ];
            });
        }
        // Guest
        if (Schema::hasTable('wishlist_items') && Schema::hasColumn('wishlist_items', 'session_id')) {
            try {
                $items = WishlistItem::where('session_id', session()->getId())
                    ->with(['item'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                return $items->map(function($item) {
                    $itemType = $item->item_type === \App\Models\CarVariant::class ? 'car_variant' : 'accessory';
                    return (object) [
                        'id' => $item->id,
                        'item' => $item->item,
                        'item_type' => $itemType,
                        'item_id' => $item->item_id,
                        'created_at' => $item->created_at
                    ];
                });
            } catch (\Throwable $e) {
                // fall through to session fallback
            }
        }
        // Fallback to session storage
        $wishlistData = session()->get('wishlist', []);
        $items = collect();
        if (!empty($wishlistData)) {
            foreach ($wishlistData as $itemKey => $itemData) {
                $itemType = $itemData['item_type'];
                $itemId = $itemData['item_id'];
                $item = null;
                switch ($itemType) {
                    case 'car_variant':
                        $item = \App\Models\CarVariant::with('images')->find($itemId);
                        break;
                    case 'accessory':
                        $item = \App\Models\Accessory::find($itemId);
                        break;
                }
                if ($item) {
                    $items->push((object) [
                        'id' => 'session_' . $itemKey,
                        'item' => $item,
                        'item_type' => $itemType,
                        'item_id' => $itemId,
                        'created_at' => $itemData['added_at'] ?? now()
                    ]);
                }
            }
        }
        return $items;
    }

    /**
     * Add item to wishlist and return updated count
     */
    public static function addToWishlist($itemType, $itemId)
    {
        // Validate item exists first
        $item = self::validateItem($itemType, $itemId);
        if (!$item) {
            return [
                'success' => false,
                'message' => 'Sản phẩm không tồn tại!',
                'wishlist_count' => self::getWishlistCount()
            ];
        }

        $itemTypeClass = self::getItemTypeClass($itemType);
        if (Auth::check()) {
            $userId = Auth::id();
            $wishlistItem = WishlistItem::firstOrCreate(
                ['user_id' => $userId, 'item_type' => $itemTypeClass, 'item_id' => $itemId],
                ['is_active' => true]
            );
            self::clearWishlistCountCache();
            return [
                'success' => true,
                'message' => $wishlistItem->wasRecentlyCreated ? 'Đã thêm vào danh sách yêu thích!' : 'Sản phẩm đã có trong danh sách yêu thích!',
                'wishlist_count' => self::getWishlistCount()
            ];
        }
        $sessionId = session()->getId();
        if (Schema::hasTable('wishlist_items') && Schema::hasColumn('wishlist_items', 'session_id')) {
            try {
                $wishlistItem = WishlistItem::firstOrCreate(
                    ['session_id' => $sessionId, 'item_type' => $itemTypeClass, 'item_id' => $itemId],
                    ['is_active' => true]
                );
                self::clearWishlistCountCache();
                return [
                    'success' => true,
                    'message' => $wishlistItem->wasRecentlyCreated ? 'Đã thêm vào danh sách yêu thích!' : 'Sản phẩm đã có trong danh sách yêu thích!',
                    'wishlist_count' => self::getWishlistCount()
                ];
            } catch (\Throwable $e) {
                // fallback to session storage
            }
        }
        // Fallback to session storage if DB path unavailable
        $wishlistData = session()->get('wishlist', []);
        $itemKey = $itemType . '_' . $itemId;
        if (isset($wishlistData[$itemKey])) {
            return [
                'success' => true,
                'message' => 'Sản phẩm đã có trong danh sách yêu thích!',
                'wishlist_count' => count($wishlistData)
            ];
        }
        $wishlistData[$itemKey] = [
            'item_type' => $itemType,
            'item_id' => $itemId,
            'added_at' => now()
        ];
        session()->put('wishlist', $wishlistData);
        self::clearWishlistCountCache();
        return [
            'success' => true,
            'message' => 'Đã thêm vào danh sách yêu thích!',
            'wishlist_count' => count($wishlistData)
        ];
    }

    /**
     * Remove item from wishlist and return updated count
     */
    public static function removeFromWishlist($itemType, $itemId)
    {
        $itemTypeClass = self::getItemTypeClass($itemType);
        if (Auth::check()) {
            $userId = Auth::id();
            $deleted = WishlistItem::where('user_id', $userId)
                ->where('item_type', $itemTypeClass)
                ->where('item_id', $itemId)
                ->delete();
            self::clearWishlistCountCache();
            return [
                'success' => true,
                'message' => $deleted > 0 ? 'Đã xóa khỏi danh sách yêu thích!' : 'Sản phẩm không có trong danh sách yêu thích!',
                'wishlist_count' => self::getWishlistCount()
            ];
        }
        $sessionId = session()->getId();
        if (Schema::hasTable('wishlist_items') && Schema::hasColumn('wishlist_items', 'session_id')) {
            try {
                $deleted = WishlistItem::where('session_id', $sessionId)
                    ->where('item_type', $itemTypeClass)
                    ->where('item_id', $itemId)
                    ->delete();
                self::clearWishlistCountCache();
                return [
                    'success' => true,
                    'message' => $deleted > 0 ? 'Đã xóa khỏi danh sách yêu thích!' : 'Sản phẩm không có trong danh sách yêu thích!',
                    'wishlist_count' => self::getWishlistCount()
                ];
            } catch (\Throwable $e) {
                // fallback to session storage
            }
        }
        // Fallback to session storage
        $wishlistData = session()->get('wishlist', []);
        $itemKey = $itemType . '_' . $itemId;
        if (isset($wishlistData[$itemKey])) {
            unset($wishlistData[$itemKey]);
            session()->put('wishlist', $wishlistData);
            self::clearWishlistCountCache();
            return [
                'success' => true,
                'message' => 'Đã xóa khỏi danh sách yêu thích!',
                'wishlist_count' => count($wishlistData)
            ];
        }
        return [
            'success' => false,
            'message' => 'Sản phẩm không có trong danh sách yêu thích!',
            'wishlist_count' => count($wishlistData)
        ];
    }

    /**
     * Clear all wishlist items
     */
    public static function clearWishlist()
    {
        if (Auth::check()) {
            WishlistItem::where('user_id', Auth::id())->delete();
        } else {
            if (Schema::hasTable('wishlist_items') && Schema::hasColumn('wishlist_items', 'session_id')) {
                try { WishlistItem::where('session_id', session()->getId())->delete(); }
                catch (\Throwable $e) { /* ignore and fallback below */ }
            }
            session()->forget('wishlist');
        }
        
        self::clearWishlistCountCache();
        
        return [
            'success' => true,
            'message' => 'Đã xóa tất cả sản phẩm khỏi danh sách yêu thích!',
            'wishlist_count' => 0
        ];
    }

    /**
     * Get item type class from string
     */
    private static function getItemTypeClass($itemType)
    {
        switch ($itemType) {
            case 'car_variant':
                return \App\Models\CarVariant::class;
            case 'accessory':
                return \App\Models\Accessory::class;
            default:
                return $itemType; // Assume it's already a class name
        }
    }

    /**
     * Validate that item exists
     */
    private static function validateItem($itemType, $itemId)
    {
        switch ($itemType) {
            case 'car_variant':
                return \App\Models\CarVariant::find($itemId);
            case 'accessory':
                return \App\Models\Accessory::find($itemId);
            default:
                return null;
        }
    }
} 