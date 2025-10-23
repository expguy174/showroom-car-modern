<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestDrive;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TestDriveController extends Controller
{
    public function index(Request $request)
    {
        $query = TestDrive::with(['user.userProfile', 'carVariant.carModel.carBrand', 'showroom']);

        // Search via user and car variant relationships
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Search in user info
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('email', 'like', "%{$search}%")
                              ->orWhereHas('userProfile', function($profileQuery) use ($search) {
                                  $profileQuery->where('name', 'like', "%{$search}%");
                              });
                })
                // Search in car variant name
                ->orWhereHas('carVariant', function($variantQuery) use ($search) {
                    $variantQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('preferred_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('preferred_date', '<=', $request->date_to);
        }

        if ($request->filled('showroom_id')) {
            $query->where('showroom_id', $request->showroom_id);
        }

        $testDrives = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Append query parameters to pagination links (exclude ajax and with_stats)
        $testDrives->appends($request->except(['page', 'ajax', 'with_stats']));

        // Calculate stats
        $totalTestDrives = TestDrive::count();
        $pendingTestDrives = TestDrive::where('status', 'scheduled')->count();
        $confirmedTestDrives = TestDrive::where('status', 'confirmed')->count();
        $completedTestDrives = TestDrive::where('status', 'completed')->count();
        $cancelledTestDrives = TestDrive::where('status', 'cancelled')->count();

        // Get showrooms for filter
        $showrooms = \App\Models\Showroom::orderBy('name')->get();

        // AJAX support
        if ($request->ajax() || $request->has('ajax')) {
            // For pagination and regular AJAX - return HTML only
            if (!$request->has('with_stats')) {
                return view('admin.test-drives.partials.table', compact('testDrives'));
            }
            
            // For with_stats requests - return JSON
            $html = view('admin.test-drives.partials.table', compact('testDrives'))->render();
            return response()->json([
                'html' => $html,
                'stats' => [
                    'total' => $totalTestDrives,
                    'pending' => $pendingTestDrives,
                    'confirmed' => $confirmedTestDrives,
                    'completed' => $completedTestDrives,
                    'cancelled' => $cancelledTestDrives
                ]
            ]);
        }

        return view('admin.test-drives.index', compact(
            'testDrives',
            'totalTestDrives',
            'pendingTestDrives',
            'confirmedTestDrives',
            'completedTestDrives',
            'cancelledTestDrives',
            'showrooms'
        ));
    }

    public function show(TestDrive $testDrive)
    {
        $testDrive->load([
            'user.userProfile',
            'user.addresses' => function($query) {
                $query->where('is_default', true);
            },
            'carVariant.carModel.carBrand',
            'showroom'
        ]);
        return view('admin.test-drives.show', compact('testDrive'));
    }

    public function updateStatus(Request $request, TestDrive $testDrive)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', \App\Models\TestDrive::STATUSES)
        ]);

        $oldStatus = $testDrive->status;
        $newStatus = $request->status;
        
        $testDrive->update(['status' => $newStatus]);

        // Send notification to user
        if ($testDrive->user_id && $oldStatus !== $newStatus) {
            $statusLabels = [
                'scheduled' => 'Đã đặt lịch',
                'confirmed' => 'Đã xác nhận',
                'completed' => 'Hoàn thành',
                'cancelled' => 'Đã hủy'
            ];

            $carName = $testDrive->carVariant 
                ? "{$testDrive->carVariant->carModel->carBrand->name} {$testDrive->carVariant->carModel->name}"
                : 'N/A';

            \App\Models\Notification::create([
                'user_id' => $testDrive->user_id,
                'type' => 'test_drive',
                'title' => "Lịch lái thử {$carName}",
                'message' => "Trạng thái lịch lái thử đã cập nhật: {$statusLabels[$newStatus]}. Ngày hẹn: {$testDrive->preferred_date->format('d/m/Y H:i')}",
                'is_read' => false,
            ]);
        }

        return redirect()->back()->with('success', 'Trạng thái đã được cập nhật!');
    }

    public function export(Request $request)
    {
        $query = TestDrive::with(['user.userProfile', 'carVariant.carModel.carBrand', 'showroom']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Search in user info
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('email', 'like', "%{$search}%")
                              ->orWhereHas('userProfile', function($profileQuery) use ($search) {
                                  $profileQuery->where('name', 'like', "%{$search}%");
                              });
                })
                // Search in car variant name
                ->orWhereHas('carVariant', function($variantQuery) use ($search) {
                    $variantQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $testDrives = $query->orderBy('created_at', 'desc')->get();

        $filename = 'test-drives-' . now()->format('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($testDrives) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'ID',
                'Họ tên',
                'Email', 
                'Số điện thoại',
                'Xe',
                'Ngày hẹn',
                'Trạng thái',
                'Ghi chú',
                'Ngày tạo'
            ]);

            // Data
            foreach ($testDrives as $testDrive) {
                $carInfo = '';
                if ($testDrive->carVariant) {
                    $carInfo = $testDrive->carVariant->carModel->carBrand->name . ' ' . 
                              $testDrive->carVariant->carModel->name . ' ' . 
                              $testDrive->carVariant->name;
                }

                $statusLabels = [
                    'pending' => 'Chờ xử lý',
                    'confirmed' => 'Đã xác nhận', 
                    'completed' => 'Hoàn thành',
                    'cancelled' => 'Đã hủy'
                ];

                fputcsv($file, [
                    $testDrive->id,
                    $testDrive->name,
                    $testDrive->email,
                    $testDrive->phone,
                    $carInfo,
                    $testDrive->preferred_date ? \Carbon\Carbon::parse($testDrive->preferred_date)->format('d/m/Y H:i') : '',
                    $statusLabels[$testDrive->status] ?? $testDrive->status,
                    $testDrive->notes ?? '',
                    $testDrive->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function confirm(Request $request, TestDrive $testDrive)
    {
        if ($testDrive->status !== 'scheduled') {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể xác nhận lịch ở trạng thái đã đặt lịch'
            ], 400);
        }

        $testDrive->update([
            'status' => 'confirmed',
            'confirmed_at' => now()
        ]);

        // Send notification
        if ($testDrive->user_id) {
            \App\Models\Notification::create([
                'user_id' => $testDrive->user_id,
                'type' => 'test_drive',
                'title' => 'Lịch lái thử đã được xác nhận',
                'message' => "Lịch lái thử {$testDrive->car_full_name} vào ngày {$testDrive->preferred_date->format('d/m/Y')} đã được xác nhận.",
                'is_read' => false,
            ]);
        }

        // Get updated stats
        $stats = [
            'total' => TestDrive::count(),
            'pending' => TestDrive::where('status', 'scheduled')->count(),
            'confirmed' => TestDrive::where('status', 'confirmed')->count(),
            'completed' => TestDrive::where('status', 'completed')->count(),
            'cancelled' => TestDrive::where('status', 'cancelled')->count()
        ];

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lịch lái thử đã được xác nhận!',
                'stats' => $stats
            ]);
        }

        return redirect()->back()->with('success', 'Lịch lái thử đã được xác nhận!');
    }

    public function complete(Request $request, TestDrive $testDrive)
    {
        if ($testDrive->status !== 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể hoàn thành lịch đã xác nhận'
            ], 400);
        }

        $testDrive->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        // Send notification
        if ($testDrive->user_id) {
            \App\Models\Notification::create([
                'user_id' => $testDrive->user_id,
                'type' => 'test_drive',
                'title' => 'Lịch lái thử đã hoàn thành',
                'message' => "Lịch lái thử {$testDrive->car_full_name} đã hoàn thành. Cảm ơn bạn đã tin tưởng!",
                'is_read' => false,
            ]);
        }

        // Get updated stats
        $stats = [
            'total' => TestDrive::count(),
            'pending' => TestDrive::where('status', 'scheduled')->count(),
            'confirmed' => TestDrive::where('status', 'confirmed')->count(),
            'completed' => TestDrive::where('status', 'completed')->count(),
            'cancelled' => TestDrive::where('status', 'cancelled')->count()
        ];

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lịch lái thử đã hoàn thành!',
                'stats' => $stats
            ]);
        }

        return redirect()->back()->with('success', 'Lịch lái thử đã hoàn thành!');
    }

    public function cancel(Request $request, TestDrive $testDrive)
    {
        if (!in_array($testDrive->status, ['scheduled', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể hủy lịch lái thử này'
            ], 400);
        }

        $testDrive->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);

        // Send notification
        if ($testDrive->user_id) {
            \App\Models\Notification::create([
                'user_id' => $testDrive->user_id,
                'type' => 'test_drive',
                'title' => 'Lịch lái thử đã bị hủy',
                'message' => "Lịch lái thử {$testDrive->car_full_name} vào ngày {$testDrive->preferred_date->format('d/m/Y')} đã bị hủy.",
                'is_read' => false,
            ]);
        }

        // Get updated stats
        $stats = [
            'total' => TestDrive::count(),
            'pending' => TestDrive::where('status', 'scheduled')->count(),
            'confirmed' => TestDrive::where('status', 'confirmed')->count(),
            'completed' => TestDrive::where('status', 'completed')->count(),
            'cancelled' => TestDrive::where('status', 'cancelled')->count()
        ];

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lịch lái thử đã bị hủy!',
                'stats' => $stats
            ]);
        }

        return redirect()->back()->with('success', 'Lịch lái thử đã bị hủy!');
    }

    public function destroy(Request $request, TestDrive $testDrive)
    {
        $testDrive->delete();
        
        // Get updated stats
        $stats = [
            'total' => TestDrive::count(),
            'pending' => TestDrive::where('status', 'scheduled')->count(),
            'confirmed' => TestDrive::where('status', 'confirmed')->count(),
            'completed' => TestDrive::where('status', 'completed')->count(),
            'cancelled' => TestDrive::where('status', 'cancelled')->count()
        ];
        
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa lịch lái thử thành công!',
                'stats' => $stats
            ]);
        }
        
        return redirect()->back()->with('success', 'Đã xóa lịch lái thử thành công!');
    }
} 