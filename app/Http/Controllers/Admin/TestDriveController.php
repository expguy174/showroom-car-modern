<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestDrive;
use Illuminate\Http\Request;

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

        return view('admin.test_drives.index', compact('testDrives'));
    }

    public function show(TestDrive $testDrive)
    {
        $testDrive->load(['user', 'carVariant.carModel.carBrand']);
        return view('admin.test_drives.show', compact('testDrive'));
    }

    public function updateStatus(Request $request, TestDrive $testDrive)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', \App\Models\TestDrive::STATUSES)
        ]);

        $testDrive->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Trạng thái đã được cập nhật!');
    }

    public function destroy(TestDrive $testDrive)
    {
        $testDrive->delete();
        return redirect()->back()->with('success', 'Lịch lái thử đã được xóa!');
    }
} 