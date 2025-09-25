<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CartItem;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Capture guest session id before authentication regenerates session
        $oldSessionId = $request->session()->getId();

        $request->authenticate();

        // Merge guest cart -> user cart
        try {
            $userId = Auth::id();
            if ($userId && $oldSessionId) {
                DB::transaction(function () use ($userId, $oldSessionId) {
                    $guestItems = CartItem::where('session_id', $oldSessionId)->get();
                    foreach ($guestItems as $gi) {
                        $existing = CartItem::where('user_id', $userId)
                            ->where('item_type', $gi->item_type)
                            ->where('item_id', $gi->item_id)
                            ->when($gi->color_id, function ($q) use ($gi) { $q->where('color_id', $gi->color_id); }, function ($q) { $q->whereNull('color_id'); })
                            ->first();
                        if ($existing) {
                            $existing->quantity = max(1, (int)$existing->quantity) + max(1, (int)$gi->quantity);
                            $existing->save();
                            $gi->delete();
                        } else {
                            $gi->user_id = $userId;
                            $gi->session_id = null;
                            $gi->save();
                        }
                    }
                });
            }
        } catch (\Throwable $e) {
            Log::warning('Cart merge on login failed', ['error' => $e->getMessage()]);
        }

        // Migrate guest wishlist in DB (by session_id) -> user wishlist
        try {
            if (Auth::check() && $oldSessionId) {
                $guestWishlist = \App\Models\WishlistItem::where('session_id', $oldSessionId)->get();
                foreach ($guestWishlist as $gw) {
                    \App\Helpers\WishlistHelper::addToWishlist(
                        $gw->item_type === \App\Models\CarVariant::class ? 'car_variant' : 'accessory',
                        $gw->item_id
                    );
                    $gw->delete();
                }
                \App\Helpers\WishlistHelper::clearWishlistCountCache();
            }
        } catch (\Throwable $e) {
            Log::warning('Wishlist merge on login failed', ['error' => $e->getMessage()]);
        }

        $request->session()->regenerate();

        // Update last login time
        if (Auth::user()) {
            $user = Auth::user();
            $user->last_login_at = now();
            $user->save();
        }

        // Role-based redirect after login (no session flash messages)
        $user = Auth::user();
        if ($user) {
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended('/admin/dashboard');
                
                case 'manager':
                    return redirect()->intended('/admin/dashboard');
                
                case 'sales_person':
                    return redirect()->intended('/admin/dashboard');
                
                case 'technician':
                    return redirect()->intended('/admin/dashboard');
                
                case 'user':
                default:
                    return redirect()->intended('/dashboard');
            }
        }

        return redirect()->intended('/dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
