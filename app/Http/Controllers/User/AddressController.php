<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Rules\UniqueAddress;

class AddressController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $addresses = Address::where('user_id', $user->id)->orderByDesc('is_default')->latest()->get();
        return view('user.addresses.index', compact('addresses', 'user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'line1' => ['required', 'string', 'max:255', new UniqueAddress()],
            'line2' => 'nullable|string|max:255',
            'ward' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'type' => 'nullable|in:billing,shipping',
            'is_default' => 'nullable|boolean',
        ], [
            'line1.required' => 'Địa chỉ không được để trống.',
        ]);

        // Build full address from components
        $addressParts = array_filter([
            $request->line1,
            $request->line2,
            $request->ward,
            $request->district,
            $request->province
        ]);
        $fullAddress = implode(', ', $addressParts);

        $address = new Address();
        $address->user_id = $user->id;
        $address->full_name = $request->name;
        $address->phone = $request->phone;
        $address->address = $fullAddress;
        $address->city = $request->province ?: 'Unknown';
        $address->state = $request->district;
        $address->postal_code = null;
        $address->country = 'Vietnam';
        $address->type = $request->type ?: 'home';
        $address->is_default = (bool) $request->boolean('is_default');
        $address->save();

        if ($address->is_default) {
            Address::where('user_id', $user->id)->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        return redirect()->route('user.addresses.index')->with('success', 'Đã thêm địa chỉ.');
    }

    public function update(Request $request, Address $address)
    {
        abort_unless($address->user_id === Auth::id(), 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'line1' => ['required', 'string', 'max:255', new UniqueAddress($address->id)],
            'line2' => 'nullable|string|max:255',
            'ward' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'type' => 'nullable|in:billing,shipping',
            'is_default' => 'nullable|boolean',
        ], [
            'line1.required' => 'Địa chỉ không được để trống.',
        ]);

        // Build full address from components
        $addressParts = array_filter([
            $request->line1,
            $request->line2,
            $request->ward,
            $request->district,
            $request->province
        ]);
        $fullAddress = implode(', ', $addressParts);

        $address->full_name = $request->name;
        $address->phone = $request->phone;
        $address->address = $fullAddress;
        $address->city = $request->province ?: 'Unknown';
        $address->state = $request->district;
        $address->postal_code = null;
        $address->country = 'Vietnam';
        $address->type = $request->type ?: 'home';
        $address->is_default = (bool) $request->boolean('is_default');
        $address->save();

        if ($address->is_default) {
            Address::where('user_id', $address->user_id)->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        return redirect()->route('user.addresses.index')->with('success', 'Đã cập nhật địa chỉ.');
    }

    public function destroy(Address $address)
    {
        abort_unless($address->user_id === Auth::id(), 403);
        $address->delete();
        
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Đã xóa địa chỉ']);
        }
        
        return redirect()->route('user.addresses.index')->with('success', 'Đã xoá địa chỉ.');
    }

    public function setDefault(Address $address)
    {
        abort_unless($address->user_id === Auth::id(), 403);
        Address::where('user_id', $address->user_id)->update(['is_default' => false]);
        $address->update(['is_default' => true]);
        // Sync user's primary address field for legacy displays
        try {
            $user = Auth::user();
            if ($user) {
                $user->address = $address->address;
                $user->save();
            }
        } catch (\Throwable $e) { /* noop */ }
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Đã đặt làm mặc định']);
        }
        return redirect()->route('user.addresses.index')->with('success', 'Đã đặt làm địa chỉ mặc định.');
    }
}


