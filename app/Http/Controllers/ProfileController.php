<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->loadMissing(['userProfile', 'addresses' => function($q){ $q->orderByDesc('is_default'); }]);
        $orders = $user->orders()->with(['items', 'items.color', 'paymentMethod'])->orderByDesc('created_at')->get();
        return view('profile.edit', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse|JsonResponse
    {
        $user = $request->user()->loadMissing(['userProfile', 'addresses']);

        $data = $request->validated();

        // Sync user core fields
        $user->fill(collect($data)->only(['email'])->toArray());

        // Handle avatar upload
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $file = $request->file('avatar');
            $path = $file->store('avatars', 'public');
            // Optionally delete old file if exists and stored locally
            try {
                if ($user->avatar_path && !str_starts_with($user->avatar_path, 'http')) {
                    @unlink(storage_path('app/public/'.$user->avatar_path));
                }
            } catch (\Throwable $e) {}
            $user->avatar_path = $path;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Update or create profile name
        if (!empty($data['name'])) {
            $user->userProfile()->updateOrCreate([], ['name' => $data['name']]);
        }

        // Update default address phone if provided
        if (!empty($data['phone'])) {
            $defaultAddress = $user->addresses->firstWhere('is_default', true) ?: $user->addresses->first();
            if ($defaultAddress) {
                $defaultAddress->phone = $data['phone'];
                $defaultAddress->save();
            }
        }

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            $avatarPath = $user->avatar_path;
            $avatarUrl = null;
            if ($avatarPath) {
                $avatarUrl = Str::startsWith($avatarPath, ['http://', 'https://']) ? $avatarPath : asset('storage/' . $avatarPath);
            }
            return response()->json([
                'success' => true,
                'status' => 'profile-updated',
                'user' => [
                    'name' => optional($user->userProfile)->name,
                    'email' => $user->email,
                    'phone' => optional($user->addresses->firstWhere('is_default', true) ?: $user->addresses->first())->phone,
                    'gender' => optional($user->userProfile)->gender,
                    'nationality' => null,
                    'date_of_birth' => optional($user->userProfile->birth_date ?? null)?->format('Y-m-d'),
                    'date_of_birth_display' => optional($user->userProfile->birth_date ?? null)?->format('d/m/Y'),
                    'avatar_url' => $avatarUrl,
                ],
            ]);
        }

        return Redirect::route('user.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
