<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WishlistItem;
use App\Models\CarVariant;
use App\Models\Accessory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist
     */
    public function index(Request $request)
    {
        $type = $request->get('type'); // car_variant|accessory|null
        $q = trim((string) $request->get('q', ''));
        $sort = $request->get('sort', 'newest'); // newest|oldest
        // Fixed page size as requested
        $perPage = 8;

        // Use helper to support both logged-in and session-based wishlists
        $allItems = \App\Helpers\WishlistHelper::getWishlistItems();

        // Filter by type
        if (in_array($type, ['car_variant', 'accessory'])) {
            $allItems = $allItems->where('item_type', $type)->values();
        }

        // Ensure related item is loaded for filtering and rendering
        $allItems->each(function($item) {
            if (!$item->item) { return; }
            if ($item->item_type === 'car_variant' || $item->item instanceof \App\Models\CarVariant) {
                $item->item->load(['images', 'carModel.carBrand']);
                $item->item->loadCount(['approvedReviews as approved_reviews_count']);
                $item->item->loadAvg('approvedReviews as approved_reviews_avg', 'rating');
            }
            if ($item->item_type === 'accessory' || $item->item instanceof \App\Models\Accessory) {
                $item->item->loadCount(['approvedReviews as approved_reviews_count']);
                $item->item->loadAvg('approvedReviews as approved_reviews_avg', 'rating');
            }
        });

        // Search by name
        if ($q !== '') {
            $needle = mb_strtolower($q);
            $allItems = $allItems->filter(function($w) use ($needle) {
                $name = mb_strtolower((string) optional($w->item)->name);
                return $name !== '' && mb_strpos($name, $needle) !== false;
            })->values();
        }

        // Sort by created_at
        $allItems = $allItems->sortBy(function($w) {
            return $w->created_at ? $w->created_at->timestamp : 0;
        }, SORT_REGULAR, $sort !== 'oldest')->values();

        // Paginate collection
        $page = max(1, (int) $request->get('page', 1));
        $total = $allItems->count();
        $results = $allItems->slice(($page - 1) * $perPage, $perPage)->values();
        $wishlistItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('user.wishlist.index', compact('wishlistItems', 'type', 'q', 'sort', 'perPage'));
    }

    /**
     * Add item to wishlist
     */
    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'item_type' => 'required|in:car_variant,accessory',
                'item_id' => 'required|integer|min:1'
            ]);

            // Use WishlistHelper to add item
            $result = \App\Helpers\WishlistHelper::addToWishlist(
                $validated['item_type'], 
                $validated['item_id']
            );

            return response()->json($result);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error adding item to wishlist: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm vào danh sách yêu thích!'
            ], 500);
        }
    }

    /**
     * Remove item from wishlist
     */
    public function remove(Request $request)
    {
        try {
            $validated = $request->validate([
                'item_type' => 'required|in:car_variant,accessory',
                'item_id' => 'required|integer'
            ]);

            // Use WishlistHelper to remove item
            $result = \App\Helpers\WishlistHelper::removeFromWishlist(
                $validated['item_type'], 
                $validated['item_id']
            );

            return response()->json($result);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error removing item from wishlist: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa khỏi danh sách yêu thích!'
            ], 500);
        }
    }

    /**
     * Clear all wishlist items
     */
    public function clear(Request $request)
    {
        try {
            $result = \App\Helpers\WishlistHelper::clearWishlist();

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error clearing wishlist: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa danh sách yêu thích!'
            ], 500);
        }
    }

    /**
     * Check if item is in wishlist
     */
    public function check(Request $request)
    {
        try {
            $validated = $request->validate([
                'item_type' => 'required|in:car_variant,accessory',
                'item_id' => 'required|integer'
            ]);

            $exists = \App\Helpers\WishlistHelper::isInWishlist(
                $validated['item_type'], 
                $validated['item_id']
            );

            return response()->json([
                'success' => true,
                'in_wishlist' => $exists
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error checking wishlist item: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi kiểm tra danh sách yêu thích!'
            ], 500);
        }
    }

    /**
     * Get wishlist count
     */
    public function getCount()
    {
        try {
            $count = \App\Helpers\WishlistHelper::getWishlistCount();

            return response()->json([
                'success' => true,
                'wishlist_count' => $count
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting wishlist count: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy số lượng danh sách yêu thích!'
            ], 500);
        }
    }

    /**
     * Get all wishlist items
     */
    public function getItems()
    {
        try {
            $items = \App\Helpers\WishlistHelper::getWishlistItems();

            return response()->json([
                'success' => true,
                'wishlist_items' => $items,
                'wishlist_count' => count($items)
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting wishlist items: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách yêu thích!'
            ], 500);
        }
    }

    /**
     * Check multiple items at once
     */
    public function checkBulk(Request $request)
    {
        try {
            $validated = $request->validate([
                'item_type' => 'required|in:car_variant,accessory',
                'item_ids' => 'required|array',
                'item_ids.*' => 'integer'
            ]);

            $existingIds = [];
            $itemType = $validated['item_type'];
            $itemIds = $validated['item_ids'];

            // Check each item individually using WishlistHelper
            foreach ($itemIds as $itemId) {
                if (\App\Helpers\WishlistHelper::isInWishlist($itemType, $itemId)) {
                    $existingIds[] = $itemId;
                }
            }

            return response()->json([
                'success' => true,
                'existing_ids' => $existingIds,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error checking bulk wishlist items: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi kiểm tra danh sách yêu thích!'
            ], 500);
        }
    }

    /**
     * Migrate session wishlist to database after user login
     */
    public function migrateSessionWishlist()
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Người dùng chưa đăng nhập!'
                ]);
            }

            $wishlistData = session()->get('wishlist', []);
            $migratedCount = 0;

            foreach ($wishlistData as $itemKey => $itemData) {
                $itemType = $itemData['item_type'];
                $itemId = $itemData['item_id'];

                // Check if item already exists in database
                if (!\App\Helpers\WishlistHelper::isInWishlist($itemType, $itemId)) {
                    // Add to database
                    $result = \App\Helpers\WishlistHelper::addToWishlist($itemType, $itemId);
                    if ($result['success']) {
                        $migratedCount++;
                    }
                }
            }

            // Clear session wishlist after migration
            session()->forget('wishlist');

            return response()->json([
                'success' => true,
                'message' => "Đã di chuyển {$migratedCount} sản phẩm vào danh sách yêu thích!",
                'migrated_count' => $migratedCount
            ]);
        } catch (\Exception $e) {
            Log::error('Error migrating session wishlist: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi di chuyển danh sách yêu thích!'
            ], 500);
        }
    }
}
