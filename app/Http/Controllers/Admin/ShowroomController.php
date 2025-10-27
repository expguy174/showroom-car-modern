<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Showroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShowroomController extends Controller
{
    public function index(Request $request)
    {
        $query = Showroom::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by active status
        if ($request->filled('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->is_active);
        }

        $showrooms = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Append query parameters to pagination links
        $showrooms->appends($request->except(['page', 'ajax', 'with_stats']));

        // Stats for cards
        $stats = [
            'total' => Showroom::count(),
            'active' => Showroom::where('is_active', true)->count(),
            'inactive' => Showroom::where('is_active', false)->count(),
        ];

        // If AJAX request, return partial view
        if ($request->ajax() || $request->wantsJson()) {
            return view('admin.showrooms.partials.table', compact('showrooms'))->render();
        }

        return view('admin.showrooms.index', compact('showrooms', 'stats'));
    }

    public function show(Showroom $showroom)
    {
        $showroom->load(['serviceAppointments' => function($query) {
            $query->latest()->limit(10);
        }]);

        return view('admin.showrooms.show', compact('showroom'));
    }

    public function create()
    {
        return view('admin.showrooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:showrooms,code,NULL,id,deleted_at,NULL',
            'description' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Vui lòng nhập tên showroom.',
            'name.max' => 'Tên showroom không được vượt quá 255 ký tự.',
            'code.required' => 'Vui lòng nhập mã showroom.',
            'code.unique' => 'Mã showroom này đã tồn tại.',
            'code.max' => 'Mã showroom không được vượt quá 50 ký tự.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'email.email' => 'Email không đúng định dạng.',
            'address.required' => 'Vui lòng nhập địa chỉ.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
            'city.required' => 'Vui lòng nhập thành phố.',
            'city.max' => 'Thành phố không được vượt quá 100 ký tự.',
        ]);

        // Set default dealership_id = 1 if not provided
        $validated['dealership_id'] = $request->dealership_id ?? 1;

        // Check if a soft-deleted showroom with this code exists
        $existingShowroom = Showroom::onlyTrashed()
            ->where('code', $validated['code'])
            ->first();

        if ($existingShowroom) {
            // Restore and update the soft-deleted showroom
            $existingShowroom->restore();
            $existingShowroom->update($validated);

            $message = "Đã khôi phục và cập nhật showroom \"{$existingShowroom->name}\" thành công!";
        } else {
            // Create new showroom
            Showroom::create($validated);
            $message = 'Showroom đã được tạo thành công!';
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return redirect()->route('admin.showrooms.index')
            ->with('success', $message);
    }

    public function edit(Showroom $showroom)
    {
        return view('admin.showrooms.edit', compact('showroom'));
    }

    public function update(Request $request, Showroom $showroom)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:showrooms,code,' . $showroom->id . ',id,deleted_at,NULL',
            'description' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Vui lòng nhập tên showroom.',
            'name.max' => 'Tên showroom không được vượt quá 255 ký tự.',
            'code.required' => 'Vui lòng nhập mã showroom.',
            'code.unique' => 'Mã showroom này đã tồn tại.',
            'code.max' => 'Mã showroom không được vượt quá 50 ký tự.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'email.email' => 'Email không đúng định dạng.',
            'address.required' => 'Vui lòng nhập địa chỉ.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
            'city.required' => 'Vui lòng nhập thành phố.',
            'city.max' => 'Thành phố không được vượt quá 100 ký tự.',
        ]);

        $showroom->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Showroom đã được cập nhật thành công!'
            ]);
        }

        return redirect()->route('admin.showrooms.index')
            ->with('success', 'Showroom đã được cập nhật thành công!');
    }

    public function toggleStatus(Request $request, Showroom $showroom)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $showroom->update(['is_active' => $validated['is_active']]);

        // Get updated stats
        $stats = [
            'total' => Showroom::count(),
            'active' => Showroom::where('is_active', true)->count(),
            'inactive' => Showroom::where('is_active', false)->count(),
        ];

        return response()->json([
            'success' => true,
            'message' => $showroom->is_active ? 'Đã kích hoạt showroom' : 'Đã tạm dừng showroom',
            'is_active' => $showroom->is_active,
            'stats' => $stats
        ]);
    }

    public function destroy(Showroom $showroom, Request $request)
    {
        try {
            // Check if showroom is currently active
            if ($showroom->is_active) {
                $message = "Không thể xóa showroom \"{$showroom->name}\" vì đang ở trạng thái HOẠT ĐỘNG. Vui lòng tạm dừng showroom trước khi xóa.";

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'action_suggestion' => 'deactivate'
                    ], 400);
                }
                return redirect()->route('admin.showrooms.index')->with('error', $message);
            }

            // Check for business-critical relationships
            $businessData = [];
            $hasBusinessData = false;

            // Check service appointments
            $appointmentsCount = $showroom->serviceAppointments()->count();
            if ($appointmentsCount > 0) {
                $businessData['appointments'] = $appointmentsCount;
                $hasBusinessData = true;
            }

            // Check test drives
            $testDrivesCount = $showroom->testDrives()->count();
            if ($testDrivesCount > 0) {
                $businessData['test_drives'] = $testDrivesCount;
                $hasBusinessData = true;
            }

            if ($hasBusinessData) {
                $details = [];
                if (isset($businessData['appointments'])) $details[] = "{$businessData['appointments']} lịch hẹn dịch vụ";
                if (isset($businessData['test_drives'])) $details[] = "{$businessData['test_drives']} lịch lái thử";

                $message = "KHÔNG THỂ XÓA showroom \"{$showroom->name}\" vì còn " . implode(', ', $details) . " liên quan. " .
                    "Đây là dữ liệu kinh doanh quan trọng! Vui lòng chỉ 'Tạm dừng' để ngừng sử dụng.";

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'action_suggestion' => 'deactivate',
                        'business_data' => $businessData
                    ], 400);
                }

                return redirect()->route('admin.showrooms.index')->with('error', $message);
            }

            // Safe to delete - inactive and no business data
            $showroomName = $showroom->name;
            $showroomCode = $showroom->code;

            $showroom->delete();

            $message = "Đã xóa showroom \"{$showroomName}\" ({$showroomCode}) thành công!";

            if ($request->ajax() || $request->wantsJson()) {
                // Get updated stats
                $stats = [
                    'total' => Showroom::count(),
                    'active' => Showroom::where('is_active', true)->count(),
                    'inactive' => Showroom::where('is_active', false)->count(),
                ];

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'stats' => $stats
                ]);
            }

            return redirect()->route('admin.showrooms.index')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error deleting showroom: ' . $e->getMessage());

            $message = 'Có lỗi xảy ra khi xóa showroom!';

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }

            return redirect()->route('admin.showrooms.index')->with('error', $message);
        }
    }
}
