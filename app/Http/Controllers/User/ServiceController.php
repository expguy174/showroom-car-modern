<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ServiceAppointment;
use App\Models\Service;
use App\Application\ServiceAppointments\UseCases\BookAppointment as BookServiceAppointment;
use App\Models\CarVariant;
use App\Models\Accessory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    /**
     * Display the main services page
     */
    public function index()
    {
        // Get services from database instead of hard-coded array
        $services = Service::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(function($service) {
                // Map database fields to view format
                $features = [];
                if ($service->requirements) {
                    $features = explode(', ', $service->requirements);
                }
                
                // Determine icon based on service code (since category is mapped to enum)
                $iconMap = [
                    'maintenance' => 'fas fa-tools',
                    'repair' => 'fas fa-wrench', 
                    'insurance' => 'fas fa-shield-alt',
                    'finance' => 'fas fa-calculator',
                    'accessories' => 'fas fa-puzzle-piece',
                    'consultation' => 'fas fa-headset',
                ];
                
                // Use service code for icon mapping instead of category
                $icon = $iconMap[$service->code] ?? ($iconMap[$service->category] ?? 'fas fa-cog');
                
                return [
                    'id' => $service->id,
                    'code' => $service->code,
                    'title' => $service->name,
                    'description' => $service->description,
                    'icon' => $icon,
                    'features' => $features,
                    'price_range' => $service->price > 0 ? number_format($service->price, 0, ',', '.') . ' VNĐ' : 'Miễn phí',
                    'duration' => $this->formatDuration($service->duration_minutes),
                    'category' => $service->category,
                ];
            })
            ->keyBy('code'); // Key by code for backward compatibility

        return view('user.services.index', compact('services'));
    }
    
    /**
     * Format duration from minutes to human readable
     */
    private function formatDuration($minutes)
    {
        if ($minutes < 60) {
            return $minutes . ' phút';
        } elseif ($minutes < 1440) { // Less than 24 hours
            $hours = round($minutes / 60);
            return $hours . ' giờ';
        } else {
            $days = round($minutes / 1440);
            return $days . ' ngày';
        }
    }

    /**
     * Display maintenance services
     */
    public function maintenance()
    {
        // Get maintenance services from database
        $maintenanceServices = Service::where('is_active', true)
            ->where('category', 'maintenance')
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get();

        // Get recent maintenance appointments for user (if logged in)
        $recentAppointments = collect();
        if (auth()->check()) {
            $recentAppointments = ServiceAppointment::where('user_id', auth()->id())
                ->whereHas('service', function($query) {
                    $query->where('category', 'maintenance');
                })
                ->with(['service', 'showroom'])
                ->orderBy('appointment_date', 'desc')
                ->limit(5)
                ->get();
        }

        return view('user.services.maintenance', compact('maintenanceServices', 'recentAppointments'));
    }

    /**
     * Display insurance services
     */
    public function insurance()
    {
        $insurancePackages = [
            'basic' => [
                'name' => 'Bảo hiểm cơ bản',
                'price' => '500,000',
                'description' => 'Bảo hiểm bắt buộc trách nhiệm dân sự',
                'coverage' => [
                    'Bảo hiểm trách nhiệm dân sự bắt buộc',
                    'Bảo hiểm người bị tai nạn',
                    'Bảo hiểm tài sản bị thiệt hại'
                ],
                'benefits' => [
                    'Đúng quy định pháp luật',
                    'Mức phí thấp nhất',
                    'Thủ tục đơn giản'
                ]
            ],
            'comprehensive' => [
                'name' => 'Bảo hiểm toàn diện',
                'price' => '1,500,000',
                'description' => 'Bảo hiểm tự nguyện toàn diện',
                'coverage' => [
                    'Tất cả quyền lợi gói cơ bản',
                    'Bảo hiểm thiệt hại xe cơ giới',
                    'Bảo hiểm trộm cắp xe',
                    'Bảo hiểm cháy nổ xe',
                    'Bảo hiểm người ngồi trên xe'
                ],
                'benefits' => [
                    'Bảo vệ toàn diện cho xe',
                    'Quyền lợi cao nhất',
                    'Hỗ trợ 24/7'
                ]
            ],
            'premium' => [
                'name' => 'Bảo hiểm cao cấp',
                'price' => '2,500,000',
                'description' => 'Gói bảo hiểm cao cấp với nhiều quyền lợi đặc biệt',
                'coverage' => [
                    'Tất cả quyền lợi gói toàn diện',
                    'Bảo hiểm tai nạn lái xe',
                    'Bảo hiểm bảo hành mở rộng',
                    'Bảo hiểm dịch vụ thay thế xe',
                    'Bảo hiểm chi phí y tế'
                ],
                'benefits' => [
                    'Quyền lợi đặc biệt',
                    'Dịch vụ VIP',
                    'Hỗ trợ toàn quốc'
                ]
            ]
        ];

        return view('user.services.insurance', compact('insurancePackages'));
    }

    /**
     * Display finance services
     */
    public function finance()
    {
        $financeOptions = [
            'installment' => [
                'name' => 'Trả góp xe hơi',
                'description' => 'Giải pháp tài chính linh hoạt cho việc mua xe',
                'features' => [
                    'Lãi suất từ 0%',
                    'Hỗ trợ vay lên đến 90% giá trị xe',
                    'Thời hạn vay linh hoạt 12-84 tháng',
                    'Thủ tục đơn giản, nhanh chóng',
                    'Không cần thế chấp tài sản'
                ],
                'requirements' => [
                    'CMND/CCCD còn hiệu lực',
                    'Sổ hộ khẩu/KT3',
                    'Giấy tờ chứng minh thu nhập',
                    'Giấy tờ xe (nếu có)'
                ]
            ],
            'leasing' => [
                'name' => 'Thuê tài chính',
                'description' => 'Giải pháp thuê xe với tùy chọn mua lại',
                'features' => [
                    'Không cần vốn ban đầu lớn',
                    'Chi phí cố định hàng tháng',
                    'Tùy chọn mua lại xe',
                    'Bảo dưỡng và bảo hiểm bao gồm',
                    'Thay xe mới định kỳ'
                ],
                'requirements' => [
                    'Doanh nghiệp có tư cách pháp nhân',
                    'Báo cáo tài chính 2 năm gần nhất',
                    'Kế hoạch kinh doanh',
                    'Tài sản đảm bảo'
                ]
            ]
        ];

        return view('user.services.finance', compact('financeOptions'));
    }

    /**
     * Display repair services
     */
    public function repair()
    {
        $repairServices = [
            'engine' => [
                'name' => 'Sửa chữa động cơ',
                'description' => 'Sửa chữa các vấn đề về động cơ',
                'services' => [
                    'Sửa chữa hệ thống nhiên liệu',
                    'Sửa chữa hệ thống đánh lửa',
                    'Sửa chữa hệ thống làm mát',
                    'Sửa chữa hệ thống bôi trơn',
                    'Sửa chữa hệ thống xả'
                ],
                'price_range' => '500,000 - 5,000,000 VNĐ'
            ],
            'transmission' => [
                'name' => 'Sửa chữa hộp số',
                'description' => 'Sửa chữa các vấn đề về hộp số',
                'services' => [
                    'Sửa chữa hộp số sàn',
                    'Sửa chữa hộp số tự động',
                    'Thay dầu hộp số',
                    'Sửa chữa bộ ly hợp',
                    'Sửa chữa hệ thống truyền động'
                ],
                'price_range' => '1,000,000 - 8,000,000 VNĐ'
            ],
            'electrical' => [
                'name' => 'Sửa chữa hệ thống điện',
                'description' => 'Sửa chữa các vấn đề về điện',
                'services' => [
                    'Sửa chữa hệ thống khởi động',
                    'Sửa chữa hệ thống sạc',
                    'Sửa chữa hệ thống chiếu sáng',
                    'Sửa chữa hệ thống điều khiển',
                    'Sửa chữa hệ thống an toàn'
                ],
                'price_range' => '300,000 - 3,000,000 VNĐ'
            ],
            'brake' => [
                'name' => 'Sửa chữa hệ thống phanh',
                'description' => 'Sửa chữa các vấn đề về phanh',
                'services' => [
                    'Thay má phanh',
                    'Thay đĩa phanh',
                    'Sửa chữa xi lanh phanh',
                    'Sửa chữa hệ thống ABS',
                    'Sửa chữa hệ thống phanh tay'
                ],
                'price_range' => '200,000 - 2,000,000 VNĐ'
            ]
        ];

        return view('user.services.repair', compact('repairServices'));
    }

    /**
     * Book a service appointment
     */
    public function bookAppointment(Request $request)
    {
        $validated = $request->validate([
            'service_type' => 'required|string',
            'car_variant_id' => 'nullable|exists:car_variants,id',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required|string',
            'description' => 'nullable|string|max:1000',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255'
        ]);

        try {
            $appointment = app(BookServiceAppointment::class)->handle([
                'user_id' => Auth::id(),
                'service_type' => $validated['service_type'],
                'car_variant_id' => $validated['car_variant_id'],
                'scheduled_date' => $validated['preferred_date'],
                'scheduled_time' => $validated['preferred_time'],
                'description' => $validated['description'] ?? null,
                'contact_name' => $validated['contact_name'],
                'contact_phone' => $validated['contact_phone'],
                'contact_email' => $validated['contact_email'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đặt lịch hẹn thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.',
                'appointment_id' => $appointment->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đặt lịch hẹn. Vui lòng thử lại.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Get available time slots for a specific date
     */
    public function getAvailableTimeSlots(Request $request)
    {
        $date = $request->get('date');
        $serviceType = $request->get('service_type');

        // Generate available time slots (9:00 AM to 5:00 PM)
        $timeSlots = [];
        for ($hour = 9; $hour <= 17; $hour++) {
            $timeSlots[] = sprintf('%02d:00', $hour);
        }

        // Remove booked time slots
        $bookedSlots = ServiceAppointment::where('appointment_date', $date)
            ->where('appointment_type', $serviceType)
            ->pluck('appointment_time')
            ->toArray();

        $availableSlots = array_diff($timeSlots, $bookedSlots);

        return response()->json([
            'available_slots' => array_values($availableSlots)
        ]);
    }
}
