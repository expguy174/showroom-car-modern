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
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required|date_format:H:i',
            'duration_minutes' => 'nullable|integer|min:5',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'special_requirements' => 'nullable|string',
            'has_experience' => 'nullable|boolean',
            'experience_level' => 'nullable|string|max:100',
        ]);

        // Normalize time to HH:MM:SS for TIME column
        $normalizedTime = preg_match('/^\d{2}:\d{2}:\d{2}$/', (string) $request->preferred_time)
            ? $request->preferred_time
            : ($request->preferred_time . ':00');

        $useCase = app(BookTestDrive::class);
        $testDrive = $useCase->handle([
            'user_id' => Auth::id(),
            'car_variant_id' => $request->car_variant_id,
            'preferred_date' => $request->preferred_date,
            'preferred_time' => $normalizedTime,
            'duration_minutes' => $request->duration_minutes,
            'location' => $request->location,
            'notes' => $request->notes,
            'special_requirements' => $request->special_requirements,
            'has_experience' => $request->boolean('has_experience'),
            'experience_level' => $request->experience_level,
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

    public function rate(Request $request, TestDrive $testDrive)
    {
        if ($testDrive->user_id !== Auth::id()) {
            abort(403);
        }

        // Chỉ cho phép đánh giá khi đã hoàn thành
        if ($testDrive->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Bạn chỉ có thể đánh giá sau khi hoàn thành buổi lái thử.'
            ], 422);
        }

        $validated = $request->validate([
            'satisfaction_rating' => 'required|numeric|min:0|max:5',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $testDrive->update([
            'satisfaction_rating' => $validated['satisfaction_rating'],
            'feedback' => $validated['feedback'] ?? $testDrive->feedback,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cảm ơn bạn đã đánh giá buổi lái thử!',
            'test_drive' => $testDrive->fresh(),
        ]);
    }
} 