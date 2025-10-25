<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dealership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DealershipController extends Controller
{
    public function index(Request $request)
    {
        $query = Dealership::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by active status
        if ($request->filled('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->is_active);
        }

        $dealerships = $query->orderBy('created_at', 'desc')->paginate(15);

        // Stats for cards
        $stats = [
            'total' => Dealership::count(),
            'active' => Dealership::where('is_active', true)->count(),
            'inactive' => Dealership::where('is_active', false)->count(),
        ];

        // Return partial view for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return view('admin.dealerships.partials.table', compact('dealerships'))->render();
        }

        return view('admin.dealerships.index', compact('dealerships', 'stats'));
    }

    public function create()
    {
        return view('admin.dealerships.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:dealerships,code,NULL,id,deleted_at,NULL',
            'description' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'country' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Vui lòng nhập tên đại lý.',
            'name.max' => 'Tên đại lý không được vượt quá 255 ký tự.',
            'code.required' => 'Vui lòng nhập mã đại lý.',
            'code.unique' => 'Mã đại lý này đã tồn tại.',
            'code.max' => 'Mã đại lý không được vượt quá 50 ký tự.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'email.email' => 'Email không đúng định dạng.',
            'address.required' => 'Vui lòng nhập địa chỉ.',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự.',
            'city.required' => 'Vui lòng nhập thành phố.',
            'city.max' => 'Thành phố không được vượt quá 100 ký tự.',
        ]);

        // Check if a soft-deleted dealership with this code exists
        $existingDealership = Dealership::onlyTrashed()
            ->where('code', $validated['code'])
            ->first();

        if ($existingDealership) {
            // Restore and update the soft-deleted dealership
            $existingDealership->restore();
            $existingDealership->update($validated);
            
            $message = "Đã khôi phục và cập nhật đại lý \"{$existingDealership->name}\" thành công!";
        } else {
            // Create new dealership
            Dealership::create($validated);
            $message = 'Đại lý đã được tạo thành công!';
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return redirect()->route('admin.dealerships.index')
            ->with('success', $message);
    }

    public function show(Dealership $dealership)
    {
        $dealership->load('showrooms');
        return view('admin.dealerships.show', compact('dealership'));
    }

    public function edit(Dealership $dealership)
    {
        return view('admin.dealerships.edit', compact('dealership'));
    }

    public function update(Request $request, Dealership $dealership)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:dealerships,code,' . $dealership->id . ',id,deleted_at,NULL',
            'description' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'country' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Vui lòng nhập tên đại lý.',
            'name.max' => 'Tên đại lý không được vượt quá 255 ký tự.',
            'code.required' => 'Vui lòng nhập mã đại lý.',
            'code.unique' => 'Mã đại lý này đã tồn tại.',
            'code.max' => 'Mã đại lý không được vượt quá 50 ký tự.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'email.email' => 'Email không đúng định dạng.',
            'address.required' => 'Vui lòng nhập địa chỉ.',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự.',
            'city.required' => 'Vui lòng nhập thành phố.',
            'city.max' => 'Thành phố không được vượt quá 100 ký tự.',
        ]);

        $dealership->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đại lý đã được cập nhật thành công!'
            ]);
        }

        return redirect()->route('admin.dealerships.index')
            ->with('success', 'Đại lý đã được cập nhật thành công!');
    }

    public function toggleStatus(Dealership $dealership, Request $request)
    {
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $dealership->update([
            'is_active' => $request->is_active
        ]);

        $statusText = $request->is_active ? 'kích hoạt' : 'tạm dừng';

        if ($request->ajax() || $request->wantsJson()) {
            // Get updated stats
            $stats = [
                'total' => Dealership::count(),
                'active' => Dealership::where('is_active', true)->count(),
                'inactive' => Dealership::where('is_active', false)->count(),
            ];
            
            return response()->json([
                'success' => true,
                'message' => "Đã {$statusText} đại lý thành công!",
                'stats' => $stats
            ]);
        }

        return redirect()->route('admin.dealerships.index')
            ->with('success', "Đã {$statusText} đại lý thành công!");
    }

    public function destroy(Dealership $dealership, Request $request)
    {
        try {
            // Check if dealership is currently active
            if ($dealership->is_active) {
                $message = "Không thể xóa đại lý \"{$dealership->name}\" vì đang ở trạng thái HOẠT ĐỘNG. Vui lòng tạm dừng đại lý trước khi xóa.";
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'action_suggestion' => 'deactivate'
                    ], 400);
                }
                return redirect()->route('admin.dealerships.index')->with('error', $message);
            }

            // Check if dealership has showrooms
            $showroomsCount = $dealership->showrooms()->count();
            
            if ($showroomsCount > 0) {
                $message = "KHÔNG THỂ XÓA đại lý \"{$dealership->name}\" vì còn {$showroomsCount} showroom liên quan. " .
                          "Đây là dữ liệu kinh doanh quan trọng! Vui lòng xóa các showroom trước hoặc chỉ 'Tạm dừng' để ngừng sử dụng.";
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'action_suggestion' => 'deactivate',
                        'business_data' => [
                            'showroom_count' => $showroomsCount
                        ]
                    ], 400);
                }

                return redirect()->route('admin.dealerships.index')->with('error', $message);
            }

            // Safe to delete - inactive and no business data
            $dealershipName = $dealership->name;
            $dealershipCode = $dealership->code;
            
            $dealership->delete();

            $message = "Đã xóa đại lý \"{$dealershipName}\" ({$dealershipCode}) thành công!";

            if ($request->ajax() || $request->wantsJson()) {
                // Get updated stats
                $stats = [
                    'total' => Dealership::count(),
                    'active' => Dealership::where('is_active', true)->count(),
                    'inactive' => Dealership::where('is_active', false)->count(),
                ];
                
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'stats' => $stats
                ]);
            }

            return redirect()->route('admin.dealerships.index')->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Error deleting dealership: ' . $e->getMessage());
            
            $message = 'Có lỗi xảy ra khi xóa đại lý!';
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }

            return redirect()->route('admin.dealerships.index')->with('error', $message);
        }
    }
}
