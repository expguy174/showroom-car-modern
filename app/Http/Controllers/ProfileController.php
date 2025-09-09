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
use App\Models\UserProfile as CustomerProfile;
use App\Models\Order;
use App\Models\TestDrive;

class ProfileController extends Controller
{
    /**
     * Account Hub - two tabs: account + customer profile
     */
    public function index(Request $request): View
    {
        $user = $request->user()->loadMissing(['userProfile', 'addresses' => function($q){ $q->orderByDesc('is_default'); }]);
        $orders = $user->orders()->with(['items', 'items.color', 'paymentMethod'])->orderByDesc('created_at')->get();

        // Load customer profile + aggregates same as user.customer-profiles.index
        $customerProfile = CustomerProfile::where('user_id', $user->id)
            ->with(['user'])
            ->first();

        $recentOrders = Order::where('user_id', $user->id)
            ->with(['items.item'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $testDrives = TestDrive::where('user_id', $user->id)
            ->with(['carVariant.carModel.carBrand'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('user.profile.index', [
            'user' => $user,
            'orders' => $orders,
            'customerProfile' => $customerProfile,
            'recentOrders' => $recentOrders,
            'testDrives' => $testDrives,
        ]);
    }
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

        // Handle avatar upload (store on user_profiles.avatar_path)
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $file = $request->file('avatar');
            $path = $file->store('avatars', 'public');
            // Optionally delete old file if exists and stored locally (from profile)
            try {
                $oldAvatar = optional($user->userProfile)->avatar_path;
                if ($oldAvatar && !str_starts_with($oldAvatar, 'http')) {
                    @unlink(storage_path('app/public/'.$oldAvatar));
                }
            } catch (\Throwable $e) {}
            // Ensure profile exists and update avatar_path
            $user->userProfile()->updateOrCreate([], ['avatar_path' => $path]);
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
            $avatarPath = optional($user->userProfile)->avatar_path;
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

    // --- Granular AJAX updates ---
    public function updateGeneral(Request $request): JsonResponse
    {
        $user = $request->user()->loadMissing('userProfile');
        $data = $request->validate([
            'name' => ['nullable','string','max:255'],
            'birth_date' => ['nullable','date'],
            'gender' => ['nullable','in:male,female,other'],
            'purchase_purpose' => ['nullable','string','max:255'],
            'budget_min' => ['nullable','numeric'],
            'budget_max' => ['nullable','numeric'],
            'employee_salary' => ['nullable','numeric'],
            'employee_skills' => ['nullable','string'],
        ]);

        $profile = $user->userProfile()->firstOrCreate([]);
        $profile->fill(collect($data)->only([
            'name','birth_date','gender','purchase_purpose','budget_min','budget_max','employee_salary','employee_skills'
        ])->toArray());
        $profile->save();

        return response()->json([
            'success' => true,
            'data' => [
                'name' => $profile->name,
                'birth_date' => optional($profile->birth_date)->format('Y-m-d'),
                'gender' => $profile->gender,
                'purchase_purpose' => $profile->purchase_purpose,
                'budget_min' => $profile->budget_min,
                'budget_max' => $profile->budget_max,
                'employee_salary' => $profile->employee_salary,
                'employee_skills' => $profile->employee_skills,
            ]
        ]);
    }

    public function updateLicense(Request $request): JsonResponse
    {
        $user = $request->user()->loadMissing('userProfile');
        $data = $request->validate([
            'driver_license_number' => ['nullable','string','max:255'],
            'driver_license_issue_date' => ['nullable','date'],
            'driver_license_expiry_date' => ['nullable','date'],
            'driver_license_class' => ['nullable','string','max:255'],
            'driving_experience_years' => ['nullable','integer','min:0'],
        ]);

        $profile = $user->userProfile()->firstOrCreate([]);
        $profile->fill(collect($data)->only([
            'driver_license_number','driver_license_issue_date','driver_license_expiry_date','driver_license_class','driving_experience_years'
        ])->toArray());
        $profile->save();

        return response()->json([
            'success' => true,
            'data' => [
                'driver_license_number' => $profile->driver_license_number,
                'driver_license_issue_date' => optional($profile->driver_license_issue_date)->format('Y-m-d'),
                'driver_license_expiry_date' => optional($profile->driver_license_expiry_date)->format('Y-m-d'),
                'driver_license_class' => $profile->driver_license_class,
                'driving_experience_years' => $profile->driving_experience_years,
            ]
        ]);
    }

    public function updatePreferences(Request $request): JsonResponse
    {
        $user = $request->user()->loadMissing('userProfile');
        $data = $request->validate([
            'preferred_car_types' => ['nullable'],
            'preferred_brands' => ['nullable'],
            'preferred_colors' => ['nullable'],
            'budget_min' => ['nullable','numeric'],
            'budget_max' => ['nullable','numeric'],
        ]);

        // Normalize arrays (can come as JSON string or array of strings)
        $normalize = function($value) {
            if (is_array($value)) return array_values($value);
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) return array_values($decoded);
            }
            return null;
        };

        $profile = $user->userProfile()->firstOrCreate([]);
        $profile->preferred_car_types = $normalize($data['preferred_car_types'] ?? null);
        $profile->preferred_brands = $normalize($data['preferred_brands'] ?? null);
        $profile->preferred_colors = $normalize($data['preferred_colors'] ?? null);
        if (array_key_exists('budget_min', $data)) $profile->budget_min = $data['budget_min'];
        if (array_key_exists('budget_max', $data)) $profile->budget_max = $data['budget_max'];
        $profile->save();

        return response()->json([
            'success' => true,
            'data' => [
                'preferred_car_types' => $profile->preferred_car_types,
                'preferred_brands' => $profile->preferred_brands,
                'preferred_colors' => $profile->preferred_colors,
                'budget_min' => $profile->budget_min,
                'budget_max' => $profile->budget_max,
            ]
        ]);
    }

    public function updateAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => ['required','image','max:2048'],
        ]);
        $user = $request->user()->loadMissing('userProfile');

        $file = $request->file('avatar');
        $path = $file->store('avatars', 'public');

        // Delete old if local and exists
        try {
            $old = optional($user->userProfile)->avatar_path;
            if ($old && !str_starts_with($old, 'http')) {
                @unlink(storage_path('app/public/'.$old));
            }
        } catch (\Throwable $e) {}

        $user->userProfile()->updateOrCreate([], ['avatar_path' => $path]);

        return response()->json([
            'success' => true,
            'data' => [
                'avatar_url' => asset('storage/'.$path),
            ]
        ]);
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
