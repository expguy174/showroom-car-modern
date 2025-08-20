<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TestDrive;
use App\Models\CarVariant;
use App\Application\TestDrives\UseCases\BookTestDrive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestDriveController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'car_variant_id' => 'required|exists:car_variants,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
            'driver_license' => 'nullable|string|max:20',
            'id_card' => 'nullable|string|max:20',
        ]);

        $useCase = app(BookTestDrive::class);
        $testDrive = $useCase->handle([
            'user_id' => Auth::id(),
            'car_variant_id' => $request->car_variant_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'preferred_date' => $request->preferred_date,
            'preferred_time' => $request->preferred_time,
            'notes' => $request->notes,
            'driver_license' => $request->driver_license,
            'id_card' => $request->id_card,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đặt lịch lái thử thành công! Chúng tôi sẽ liên hệ sớm nhất.',
            'test_drive' => $testDrive
        ]);
    }

    public function index()
    {
        $testDrives = TestDrive::where('user_id', Auth::id())
            ->with('carVariant.carModel.carBrand')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Variants to populate dropdown (brand • model • variant)
        $variants = CarVariant::with(['carModel.carBrand'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('user.test_drives.index', compact('testDrives', 'variants'));
    }

    public function show(TestDrive $testDrive)
    {
        if ($testDrive->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.test_drives.show', compact('testDrive'));
    }
} 