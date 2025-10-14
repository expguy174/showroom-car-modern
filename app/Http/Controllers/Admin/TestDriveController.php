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
        $query = TestDrive::with(['user', 'carVariant.carModel.carBrand']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $testDrives = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.test-drives.index', compact('testDrives'));
    }

    public function show(TestDrive $testDrive)
    {
        $testDrive->load(['user', 'carVariant.carModel.carBrand']);
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
                'pending' => 'Chờ xử lý',
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
        $query = TestDrive::with(['user', 'carVariant.carModel.carBrand']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
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

    public function confirm(TestDrive $testDrive)
    {
        $testDrive->update(['status' => 'confirmed']);
        return redirect()->back()->with('success', 'Lịch lái thử đã được xác nhận!');
    }

    public function cancel(TestDrive $testDrive)
    {
        $testDrive->update(['status' => 'cancelled']);
        return redirect()->back()->with('success', 'Lịch lái thử đã được hủy!');
    }

    public function destroy(TestDrive $testDrive)
    {
        $testDrive->delete();
        return redirect()->back()->with('success', 'Lịch lái thử đã được xóa!');
    }
} 