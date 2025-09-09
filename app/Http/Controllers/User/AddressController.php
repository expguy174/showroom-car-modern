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

        // Coalesce contact_name from name for compatibility
        if (!$request->filled('contact_name') && $request->filled('name')) {
            $request->merge(['contact_name' => $request->input('name')]);
        }
        
        $request->validate([
            'contact_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => ['required', 'string', 'max:1000', new UniqueAddress()],
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'type' => 'nullable|in:' . implode(',', \App\Models\Address::TYPES),
            'notes' => 'nullable|string|max:1000',
            'is_default' => 'nullable|boolean',
        ], [
            'contact_name.required' => 'Họ tên liên hệ là bắt buộc.',
            'contact_name.string' => 'Họ tên liên hệ phải là chuỗi ký tự.',
            'contact_name.max' => 'Họ tên liên hệ không được vượt quá :max ký tự.',

            'phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá :max ký tự.',

            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá :max ký tự.',

            'address.required' => 'Địa chỉ không được để trống.',
            'address.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'address.max' => 'Địa chỉ không được vượt quá :max ký tự.',

            'city.required' => 'Tỉnh/Thành phố là bắt buộc.',
            'city.string' => 'Tỉnh/Thành phố phải là chuỗi ký tự.',
            'city.max' => 'Tỉnh/Thành phố không được vượt quá :max ký tự.',

            'state.string' => 'Quận/Huyện phải là chuỗi ký tự.',
            'state.max' => 'Quận/Huyện không được vượt quá :max ký tự.',

            'postal_code.string' => 'Mã bưu chính phải là chuỗi ký tự.',
            'postal_code.max' => 'Mã bưu chính không được vượt quá :max ký tự.',

            'country.string' => 'Quốc gia phải là chuỗi ký tự.',
            'country.max' => 'Quốc gia không được vượt quá :max ký tự.',

            'type.in' => 'Loại địa chỉ không hợp lệ. Vui lòng chọn đúng giá trị.',

            'notes.string' => 'Ghi chú phải là chuỗi ký tự.',
            'notes.max' => 'Ghi chú không được vượt quá :max ký tự.',

            'is_default.boolean' => 'Giá trị đặt làm mặc định không hợp lệ.',
        ], [
            'contact_name' => 'Họ tên liên hệ',
            'phone' => 'Số điện thoại',
            'email' => 'Email',
            'address' => 'Địa chỉ',
            'city' => 'Tỉnh/Thành phố',
            'state' => 'Quận/Huyện',
            'postal_code' => 'Mã bưu chính',
            'country' => 'Quốc gia',
            'type' => 'Loại địa chỉ',
            'notes' => 'Ghi chú',
            'is_default' => 'Đặt làm mặc định',
        ]);

        $address = new Address();
        $address->user_id = $user->id;
        $address->contact_name = $request->contact_name ?: $request->name;
        $address->phone = $request->phone;
        $address->address = $request->address;
        $address->city = $request->city ?: 'Unknown';
        $address->state = $request->state;
        $address->postal_code = $request->postal_code;
        $address->country = $request->country ?: 'Vietnam';
        $address->type = $request->type ?: 'home';
        $address->notes = $request->notes;
        $address->is_default = (bool) $request->boolean('is_default');
        $address->save();

        if ($address->is_default) {
            Address::where('user_id', $user->id)->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm địa chỉ.',
                'address' => [
                    'id' => $address->id,
                    'contact_name' => $address->contact_name,
                    'phone' => $address->phone,
                    'address' => $address->address,
                    'city' => $address->city,
                    'state' => $address->state,
                    'postal_code' => $address->postal_code,
                    'country' => $address->country,
                    'type' => $address->type,
                    'notes' => $address->notes,
                    'is_default' => (bool) $address->is_default,
                    'created_at' => $address->created_at,
                ],
                'urls' => [
                    'set_default' => route('user.addresses.set-default', $address),
                    'destroy' => route('user.addresses.destroy', $address),
                    'update' => route('user.addresses.update', $address),
                ],
            ], 201);
        }

        return redirect()->route('user.addresses.index')->with('success', 'Đã thêm địa chỉ.');
    }

    public function update(Request $request, Address $address)
    {
        abort_unless($address->user_id === Auth::id(), 403);

        // Coalesce contact_name from name for compatibility
        if (!$request->filled('contact_name') && $request->filled('name')) {
            $request->merge(['contact_name' => $request->input('name')]);
        }

        $request->validate([
            'contact_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => ['required', 'string', 'max:1000', new UniqueAddress($address->id)],
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'type' => 'nullable|in:' . implode(',', \App\Models\Address::TYPES),
            'notes' => 'nullable|string|max:1000',
            'is_default' => 'nullable|boolean',
        ], [
            'contact_name.required' => 'Họ tên liên hệ là bắt buộc.',
            'contact_name.string' => 'Họ tên liên hệ phải là chuỗi ký tự.',
            'contact_name.max' => 'Họ tên liên hệ không được vượt quá :max ký tự.',

            'phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá :max ký tự.',

            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá :max ký tự.',

            'address.required' => 'Địa chỉ không được để trống.',
            'address.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'address.max' => 'Địa chỉ không được vượt quá :max ký tự.',

            'city.required' => 'Tỉnh/Thành phố là bắt buộc.',
            'city.string' => 'Tỉnh/Thành phố phải là chuỗi ký tự.',
            'city.max' => 'Tỉnh/Thành phố không được vượt quá :max ký tự.',

            'state.string' => 'Quận/Huyện phải là chuỗi ký tự.',
            'state.max' => 'Quận/Huyện không được vượt quá :max ký tự.',

            'postal_code.string' => 'Mã bưu chính phải là chuỗi ký tự.',
            'postal_code.max' => 'Mã bưu chính không được vượt quá :max ký tự.',

            'country.string' => 'Quốc gia phải là chuỗi ký tự.',
            'country.max' => 'Quốc gia không được vượt quá :max ký tự.',

            'type.in' => 'Loại địa chỉ không hợp lệ. Vui lòng chọn đúng giá trị.',

            'notes.string' => 'Ghi chú phải là chuỗi ký tự.',
            'notes.max' => 'Ghi chú không được vượt quá :max ký tự.',

            'is_default.boolean' => 'Giá trị đặt làm mặc định không hợp lệ.',
        ], [
            'contact_name' => 'Họ tên liên hệ',
            'phone' => 'Số điện thoại',
            'email' => 'Email',
            'address' => 'Địa chỉ',
            'city' => 'Tỉnh/Thành phố',
            'state' => 'Quận/Huyện',
            'postal_code' => 'Mã bưu chính',
            'country' => 'Quốc gia',
            'type' => 'Loại địa chỉ',
            'notes' => 'Ghi chú',
            'is_default' => 'Đặt làm mặc định',
        ]);

        $address->contact_name = $request->contact_name ?: $request->name;
        $address->phone = $request->phone;
        $address->address = $request->address;
        $address->city = $request->city ?: 'Unknown';
        $address->state = $request->state;
        $address->postal_code = $request->postal_code;
        $address->country = $request->country ?: 'Vietnam';
        $address->type = $request->type ?: 'home';
        $address->notes = $request->notes;
        $address->is_default = (bool) $request->boolean('is_default');
        $address->save();

        if ($address->is_default) {
            // Unset default for other addresses of the user
            Address::where('user_id', $address->user_id)->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật địa chỉ.',
                'address' => [
                    'id' => $address->id,
                    'contact_name' => $address->contact_name,
                    'phone' => $address->phone,
                    'address' => $address->address,
                    'city' => $address->city,
                    'state' => $address->state,
                    'postal_code' => $address->postal_code,
                    'country' => $address->country,
                    'type' => $address->type,
                    'notes' => $address->notes,
                    'is_default' => (bool) $address->is_default,
                    'updated_at' => $address->updated_at,
                ],
                'urls' => [
                    'set_default' => route('user.addresses.set-default', $address),
                    'destroy' => route('user.addresses.destroy', $address),
                    'update' => route('user.addresses.update', $address),
                ],
            ]);
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
            if ($user instanceof \App\Models\User) {
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


