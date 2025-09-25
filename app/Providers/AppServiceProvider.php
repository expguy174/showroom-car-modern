<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\CarBrand;
use App\Models\CartItem;
use App\Models\WishlistItem;
use App\Models\Notification;
use App\Observers\CarBrandObserver;
use App\Models\CarVariantColor;
use App\Observers\CarVariantColorObserver;
use Illuminate\Support\Facades\Artisan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Đăng ký observers
        CarBrand::observe(CarBrandObserver::class);
        CarVariantColor::observe(CarVariantColorObserver::class);

        // Map polymorphic aliases to actual models. Include both FQCN and short aliases
        // so existing rows using either form continue to resolve correctly.
        Relation::morphMap([
            // Prefer FQCN for new writes
            'App\\Models\\CarVariant' => \App\Models\CarVariant::class,
            'App\\Models\\Accessory' => \App\Models\Accessory::class,
            // Backward compatible short aliases
            'car_variant' => \App\Models\CarVariant::class,
            'accessory' => \App\Models\Accessory::class,
            // 'service' => \App\Models\Service::class, // Uncomment if Service model is added
        ]);

        View::composer(['components.nav', 'layouts.app', 'layouts.guest'], function ($view) {
            // Cache brands for nav (skip during unit tests or when schema not ready)
            if (app()->runningUnitTests() || !Schema::hasTable('car_brands')) {
                $navBrands = collect();
            } else {
                // Include logo_path so accessor logo_url can compute correctly; bump cache key
                $navBrands = Cache::remember('nav_brands_active_12_v6', now()->addHours(24), function () {
                    return CarBrand::where('is_active', 1)
                        ->select('id', 'name', 'logo_path', 'slug', 'country', 'sort_order')
                        ->withCount(['carModels' => function($q){
                            // Count all models regardless of active variants to match brand-card and brand pages
                        }])
                        ->orderBy('sort_order')
                        ->orderBy('name')
                        ->take(12)
                        ->get();
                });
            }

            // Counts (guard for tests and missing tables)
            $cartCount = 0;
            $unreadNotifCount = 0;
            $wishlistCount = 0;

            $hasCartItemsTable = Schema::hasTable('cart_items');
            $hasNotificationsTable = Schema::hasTable('notifications');
            $hasWishlistItemsTable = Schema::hasTable('wishlist_items');

            if (Auth::check()) {
                $userId = Auth::id();
                $cacheKey = "nav_counts_user_{$userId}";
                
                // For home page, always get fresh counts (no cache)
                if (request()->routeIs('home')) {
                    // Clear all related cache keys to ensure fresh data
                    Cache::forget($cacheKey);
                    
                    // Also clear any user-related cache if exists
                    if (Auth::check()) {
                        $userId = Auth::id();
                        Cache::forget("nav_counts_user_{$userId}");
                    }
                    

                    if ($hasCartItemsTable) {
                        $cartCount = CartItem::where('user_id', $userId)->sum('quantity');
                    }
                    if ($hasNotificationsTable) {
                        $unreadNotifCount = Notification::where('user_id', $userId)
                            ->where('is_read', false)
                            ->count();
                    }
                    if ($hasWishlistItemsTable) {
                        $wishlistCount = WishlistItem::where('user_id', $userId)->count();
                    }
                } else {
                    // Compute fresh counts every request to avoid header flicker
                    if ($hasCartItemsTable) {
                        $cartCount = CartItem::where('user_id', $userId)->sum('quantity');
                    }
                    if ($hasNotificationsTable) {
                        $unreadNotifCount = Notification::where('user_id', $userId)
                            ->where('is_read', false)
                            ->count();
                    }
                    if ($hasWishlistItemsTable) {
                        $wishlistCount = WishlistItem::where('user_id', $userId)->count();
                    }
                }
            } else {
                $sessionId = session()->getId();
                $cacheKey = "nav_counts_session_{$sessionId}";
                
                // For home page, always get fresh counts (no cache)
                if (request()->routeIs('home')) {
                    // Clear all related cache keys to ensure fresh data
                    Cache::forget($cacheKey);
                    
                    // Also clear any user-related cache if exists
                    if (Auth::check()) {
                        $userId = Auth::id();
                        Cache::forget("nav_counts_user_{$userId}");
                    }
                    
                    $cartCount = 0;
                    $wishlistCount = 0;
                    
                    if ($hasCartItemsTable) {
                        $cartCount = CartItem::where('session_id', $sessionId)->sum('quantity');
                    }
                    // For guest users, get wishlist count from DB by session_id
                    if ($hasWishlistItemsTable) {
                        $wishlistCount = WishlistItem::where('session_id', $sessionId)->count();
                    }
                } else {
                    // Compute fresh counts every request for guests as well
                    if ($hasCartItemsTable) {
                        $cartCount = CartItem::where('session_id', $sessionId)->sum('quantity');
                    }
                    if ($hasWishlistItemsTable) {
                        $wishlistCount = WishlistItem::where('session_id', $sessionId)->count();
                    }
                }
            }

            $view->with([
                'navBrands' => $navBrands,
                'navCartCount' => $cartCount,
                'navWishlistCount' => $wishlistCount,
                'navUnreadNotifCount' => $unreadNotifCount,
            ]);
        });

        // Register console commands (guard missing classes in some environments)
        // if ($this->app->runningInConsole()) {
        //     $commands = [];
        //     if (class_exists(\App\Console\Commands\BrandStatsCommand::class)) {
        //         $commands[] = \App\Console\Commands\BrandStatsCommand::class;
        //     }
        //     if (!empty($commands)) {
        //         $this->commands($commands);
        //     }
        // }
    }
}
