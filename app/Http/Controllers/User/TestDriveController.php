<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TestDrive;
use App\Models\CarVariant;
use App\Models\Showroom;
use App\Application\TestDrives\UseCases\BookTestDrive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestDriveController extends Controller
{
    public function edit(TestDrive $testDrive)
    {
        if ($testDrive->user_id !== Auth::id()) {
            abort(403);
        }
        $variants = CarVariant::with(['carModel.carBrand'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        $showrooms = Showroom::orderBy('name')->get();
        return view('user.test-drives.edit', [
            'testDrive' => $testDrive,
            'variants' => $variants,
            'showrooms' => $showrooms,
        ]);
    }

    public function update(Request $request, TestDrive $testDrive)
    {
        if ($testDrive->user_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($testDrive->status, ['scheduled','confirmed'])) {
            return redirect()->route('test-drives.show', $testDrive)
                ->with('success', 'Đặt lịch lái thử thành công! Chúng tôi sẽ liên hệ xác nhận.');
        }

        $data = $request->validate([
            'car_variant_id' => ['required','exists:car_variants,id'],
            'showroom_id' => ['nullable','exists:showrooms,id'],
            'preferred_date' => ['required','date'],
            'preferred_time' => ['required','date_format:H:i'],
            'duration_minutes' => ['nullable','integer','min:5','max:240'],
            'location' => ['nullable','string','max:255'],
            'notes' => ['nullable','string','max:1000'],
            'special_requirements' => ['nullable','string','max:1000'],
            'has_experience' => ['nullable','boolean'],
            'experience_level' => ['nullable','string','max:100'],
            'test_drive_type' => ['nullable','in:individual,group,virtual'],
        ]);

        $testDrive->car_variant_id = $data['car_variant_id'];
        $testDrive->showroom_id = $data['showroom_id'] ?? null;
        $testDrive->preferred_date = $data['preferred_date'];
        $testDrive->preferred_time = $data['preferred_time'];
        $testDrive->duration_minutes = $data['duration_minutes'] ?? null;
        $testDrive->location = $data['location'] ?? null;
        $testDrive->notes = $data['notes'] ?? null;
        $testDrive->special_requirements = $data['special_requirements'] ?? null;
        $testDrive->has_experience = array_key_exists('has_experience', $data) ? (bool)$data['has_experience'] : $testDrive->has_experience;
        $testDrive->experience_level = $data['experience_level'] ?? null;
        $testDrive->test_drive_type = $data['test_drive_type'] ?? null;
        $testDrive->save();

        // Redirect to detail with both session flash and query params for robust toasts
        return redirect()->route('test-drives.show', [
            'testDrive' => $testDrive,
            'toast' => 'success',
            'msg' => 'Đã cập nhật lịch lái thử',
        ])->with('success', 'Đã cập nhật lịch lái thử');
    }
    public function create()
    {
        $variants = CarVariant::with(['carModel.carBrand'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        $showrooms = Showroom::orderBy('name')->get();
        return view('user.test-drives.create', compact('variants','showrooms'));
    }

    public function store(Request $request)
    {
        // Business constraints - align with Service Appointments
        $openHour = 9;  // 09:00 (same as service appointments)
        $closeHour = 17; // 17:00 (same as service appointments)

        // Sequential validation theo thứ tự: mẫu xe → showroom → ngày → giờ
        $validationRules = [
            // 1. Mẫu xe
            ['car_variant_id', 'required|exists:car_variants,id', 'Vui lòng chọn mẫu xe.'],
            
            // 2. Showroom
            ['showroom_id', 'nullable|exists:showrooms,id', 'Showroom không khả dụng.'],
            
            // 3. Ngày mong muốn
            ['preferred_date', 'required|date|after:today', 'Vui lòng chọn ngày mong muốn.'],
            
            // 4. Giờ mong muốn
            ['preferred_time', 'required|date_format:H:i', 'Vui lòng chọn giờ mong muốn.'],
        ];
        
        $validated = [];
        
        // Validate từng field theo thứ tự với custom messages
        foreach ($validationRules as [$field, $rules, $message]) {
            try {
                $fieldValidation = $request->validate([
                    $field => $rules
                ], [
                    $field . '.required' => $message,
                    $field . '.date' => $field === 'preferred_date' ? 'Ngày mong muốn không hợp lệ.' : $message,
                    $field . '.after' => $field === 'preferred_date' ? 'Ngày mong muốn phải sau hôm nay.' : $message,
                    $field . '.exists' => $field === 'showroom_id' ? 'Showroom không khả dụng.' : 
                                        ($field === 'car_variant_id' ? 'Mẫu xe không khả dụng.' : $message),
                    $field . '.date_format' => $field === 'preferred_time' ? 'Giờ mong muốn không hợp lệ.' : $message,
                ]);
                $validated = array_merge($validated, $fieldValidation);
            } catch (\Illuminate\Validation\ValidationException $e) {
                // Dừng ngay khi gặp error đầu tiên
                return $request->expectsJson()
                    ? response()->json(['success'=>false,'message'=>$message], 422)
                    : back()->withErrors([$field => $message])->withInput();
            }
        }
        
        // Validate additional fields (không dừng khi có lỗi)
        $additionalValidation = $request->validate([
            'duration_minutes' => 'nullable|integer|min:5|max:240',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'special_requirements' => 'nullable|string|max:1000',
            'has_experience' => 'nullable|boolean',
            'experience_level' => 'nullable|string|max:100',
            'test_drive_type' => 'nullable|in:individual,group,virtual',
        ]);
        
        $validated = array_merge($validated, $additionalValidation);

        // Weekend check - align with Service Appointments (no Saturday/Sunday)
        $date = \Carbon\Carbon::parse($validated['preferred_date']);
        if ($date->dayOfWeek === 0 || $date->dayOfWeek === 6) {
            $message = 'Không thể đặt lịch vào cuối tuần. Vui lòng chọn thứ 2 - thứ 6.';
            return $request->expectsJson()
                ? response()->json(['success'=>false,'message'=>$message], 422)
                : back()->withErrors(['preferred_date' => $message])->withInput();
        }

        // Business hours check - align with Service Appointments (09:00-16:59)
        $time = $validated['preferred_time'];
        if (!preg_match('/^([01]?\d|2[0-3]):[0-5]\d$/', $time)) {
            $message = 'Giờ hẹn không hợp lệ (định dạng HH:MM).';
            return $request->expectsJson()
                ? response()->json(['success'=>false,'message'=>$message], 422)
                : back()->withErrors(['preferred_time' => $message])->withInput();
        }
        
        $hour = (int) substr($time, 0, 2);
        if ($hour < $openHour || $hour >= $closeHour) {
            $message = 'Giờ hẹn phải trong khung giờ làm việc (09:00 - 16:59).';
            return $request->expectsJson()
                ? response()->json(['success'=>false,'message'=>$message], 422)
                : back()->withErrors(['preferred_time' => $message])->withInput();
        }

        $useCase = app(BookTestDrive::class);
        $testDrive = $useCase->handle([
            'user_id' => Auth::id(),
            'car_variant_id' => $validated['car_variant_id'],
            'preferred_date' => $validated['preferred_date'],
            'preferred_time' => $time . ':00',
            'duration_minutes' => $validated['duration_minutes'],
            'location' => $validated['location'],
            'notes' => $validated['notes'],
            'special_requirements' => $validated['special_requirements'],
            'has_experience' => $validated['has_experience'] ?? false,
            'experience_level' => $validated['experience_level'],
            'showroom_id' => $validated['showroom_id'],
            'test_drive_type' => $validated['test_drive_type'],
        ]);

        // Notify user about the booking
        try {
            app(\App\Services\NotificationService::class)->send(
                Auth::id(),
                'test_drive',
                'Đặt lịch lái thử thành công',
                'Bạn đã đặt lịch lái thử #' . ($testDrive->test_drive_number ?? $testDrive->id) . '.'
            );
        } catch (\Throwable $e) {}

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đặt lịch lái thử thành công! Chúng tôi sẽ liên hệ sớm nhất.',
                'test_drive' => $testDrive
            ]);
        }
        return redirect()->route('test-drives.index')->with('success', 'Đặt lịch lái thử thành công!');
    }

    public function index()
    {
        $status = request()->string('status')->toString();
        $q = request()->string('q')->toString();

        $baseQuery = TestDrive::where('user_id', Auth::id())
            ->with('carVariant.carModel.carBrand');

        if ($status !== '') {
            $baseQuery->where('status', $status);
        }

        if ($q !== '') {
            $baseQuery->where(function ($sub) use ($q) {
                $sub->where('test_drive_number', 'like', "%{$q}%")
                    ->orWhereHas('carVariant.carModel', function ($cm) use ($q) {
                        $cm->where('name', 'like', "%{$q}%");
                    });
            });
        }

        $testDrives = $baseQuery->orderByDesc('created_at')->paginate(10)->withQueryString();

        // Status counts summary
        $statusCounts = TestDrive::where('user_id', Auth::id())
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Variants to populate dropdown (brand • model • variant)
        $variants = CarVariant::with(['carModel.carBrand'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Showrooms for selection
        $showrooms = Showroom::orderBy('name')->get();

        if (request()->ajax()) {
            return response()->json([
                'html' => view('user.test-drives.partials.list', ['testDrives' => $testDrives])->render(),
                'pagination' => view('components.pagination-modern', ['paginator' => $testDrives->withQueryString()])->render(),
                'summary' => view('user.test-drives.partials.summary', ['paginator' => $testDrives->withQueryString()])->render(),
            ]);
        }

        return view('user.test-drives.index', [
            'testDrives' => $testDrives,
            'variants' => $variants,
            'showrooms' => $showrooms,
            'status' => $status,
            'q' => $q,
            'statusCounts' => $statusCounts,
        ]);
    }

    public function show(TestDrive $testDrive)
    {
        if ($testDrive->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.test-drives.show', compact('testDrive'));
    }

    public function cancel(Request $request, TestDrive $testDrive)
    {
        if ($testDrive->user_id !== Auth::id()) {
            abort(403);
        }
        if (!in_array($testDrive->status, ['scheduled','confirmed'])) {
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'Lịch không thể hủy ở trạng thái hiện tại'], 422)
                : back()->with('error', 'Lịch không thể hủy ở trạng thái hiện tại');
        }
        $testDrive->status = 'cancelled';
        $testDrive->save();
        try {
            app(\App\Services\NotificationService::class)->send(
                $testDrive->user_id,
                'test_drive',
                'Đã hủy lịch lái thử',
                'Bạn đã hủy lịch lái thử #' . ($testDrive->test_drive_number ?? $testDrive->id) . '.'
            );
        } catch (\Throwable $e) {}
        return $request->ajax()
            ? response()->json(['success' => true, 'message' => 'Đã hủy lịch lái thử'])
            : back()->with('success', 'Đã hủy lịch lái thử');
    }

    public function reschedule(Request $request, TestDrive $testDrive)
    {
        if ($testDrive->user_id !== Auth::id()) {
            abort(403);
        }
        if (!in_array($testDrive->status, ['scheduled','confirmed'])) {
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'Lịch không thể đổi giờ ở trạng thái hiện tại'], 422)
                : back()->with('error', 'Lịch không thể đổi giờ ở trạng thái hiện tại');
        }
        $data = $request->validate([
            'preferred_date' => ['required','date'],
            'preferred_time' => ['required','string'],
            'car_variant_id' => ['nullable','exists:car_variants,id'],
            'showroom_id' => ['nullable','exists:showrooms,id'],
            'notes' => ['nullable','string'],
            'duration_minutes' => ['nullable','integer','min:5'],
            'location' => ['nullable','string','max:255'],
            'special_requirements' => ['nullable','string'],
            'has_experience' => ['nullable','boolean'],
            'experience_level' => ['nullable','string','max:100'],
            'test_drive_type' => ['nullable','in:individual,group,virtual'],
        ]);
        $testDrive->preferred_date = $data['preferred_date'];
        $testDrive->preferred_time = $data['preferred_time'];
        if (!empty($data['car_variant_id'])) { $testDrive->car_variant_id = $data['car_variant_id']; }
        if (array_key_exists('showroom_id', $data)) { $testDrive->showroom_id = $data['showroom_id']; }
        if (array_key_exists('notes', $data)) { $testDrive->notes = $data['notes']; }
        if (array_key_exists('duration_minutes', $data)) { $testDrive->duration_minutes = $data['duration_minutes']; }
        if (array_key_exists('location', $data)) { $testDrive->location = $data['location']; }
        if (array_key_exists('special_requirements', $data)) { $testDrive->special_requirements = $data['special_requirements']; }
        if (array_key_exists('has_experience', $data)) { $testDrive->has_experience = (bool)$data['has_experience']; }
        if (array_key_exists('experience_level', $data)) { $testDrive->experience_level = $data['experience_level']; }
        if (array_key_exists('test_drive_type', $data)) { $testDrive->test_drive_type = $data['test_drive_type']; }
        $testDrive->save();
        try {
            app(\App\Services\NotificationService::class)->send(
                $testDrive->user_id,
                'test_drive',
                'Đã đổi lịch lái thử',
                'Lịch lái thử #' . ($testDrive->test_drive_number ?? $testDrive->id) . ' đã được cập nhật.'
            );
        } catch (\Throwable $e) {}
        return $request->ajax()
            ? response()->json(['success' => true, 'message' => 'Đã lưu thay đổi lịch lái thử'])
            : back()->with('success', 'Đã lưu thay đổi lịch lái thử');
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

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'showroom_id' => 'nullable|exists:showrooms,id',
            'date' => 'required|date',
        ]);

        $date = $request->date;
        $showroomId = $request->showroom_id;

        // Get existing test drives for this date (and showroom if specified)
        $query = TestDrive::whereDate('preferred_date', $date)
            ->whereIn('status', ['scheduled', 'confirmed', 'in_progress']);

        if ($showroomId) {
            $query->where('showroom_id', $showroomId);
        }

        $existingTestDrives = $query->get();

        // Get existing service appointments for this date (and showroom if specified)
        $serviceQuery = \App\Models\ServiceAppointment::whereDate('appointment_date', $date)
            ->whereIn('status', ['scheduled', 'confirmed', 'in_progress']);

        if ($showroomId) {
            $serviceQuery->where('showroom_id', $showroomId);
        }

        $existingServices = $serviceQuery->get();

        // Combine all existing times
        $existingTimes = [];
        
        foreach ($existingTestDrives as $td) {
            $existingTimes[] = substr($td->preferred_time, 0, 5); // HH:MM
        }
        
        foreach ($existingServices as $sa) {
            $existingTimes[] = substr($sa->appointment_time, 0, 5); // HH:MM
        }

        $existingTimes = array_unique($existingTimes);
        sort($existingTimes);

        $totalAppointments = count($existingTimes);
        $showroomName = $showroomId ? \App\Models\Showroom::find($showroomId)->name : 'Tất cả showroom';

        return response()->json([
            'success' => true,
            'available_slots' => [[
                'info' => "Lịch hẹn cho ngày " . \Carbon\Carbon::parse($date)->format('d/m/Y') . " tại " . $showroomName,
                'total_appointments' => $totalAppointments,
                'existing_times' => $existingTimes,
            ]]
        ]);
    }
} 