<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ServiceAppointment;
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
        $services = [
            'maintenance' => [
                'title' => 'Bảo dưỡng định kỳ',
                'description' => 'Dịch vụ bảo dưỡng định kỳ theo khuyến nghị của nhà sản xuất',
                'icon' => 'fas fa-tools',
                'features' => [
                    'Thay dầu nhớt và bộ lọc',
                    'Kiểm tra hệ thống phanh',
                    'Kiểm tra hệ thống điện',
                    'Kiểm tra hệ thống làm mát',
                    'Kiểm tra hệ thống treo'
                ],
                'price_range' => '500,000 - 2,000,000 VNĐ',
                'duration' => '2-4 giờ'
            ],
            'repair' => [
                'title' => 'Sửa chữa chuyên nghiệp',
                'description' => 'Sửa chữa các sự cố với đội ngũ kỹ thuật viên chuyên môn cao',
                'icon' => 'fas fa-wrench',
                'features' => [
                    'Chẩn đoán lỗi bằng thiết bị hiện đại',
                    'Sửa chữa động cơ và hộp số',
                    'Sửa chữa hệ thống điện tử',
                    'Sửa chữa hệ thống điều hòa',
                    'Bảo hành sửa chữa'
                ],
                'price_range' => 'Tùy theo mức độ hư hỏng',
                'duration' => '1-3 ngày'
            ],
            'insurance' => [
                'title' => 'Bảo hiểm xe hơi',
                'description' => 'Gói bảo hiểm toàn diện với mức phí hợp lý và quyền lợi tối ưu',
                'icon' => 'fas fa-shield-alt',
                'features' => [
                    'Bảo hiểm bắt buộc trách nhiệm dân sự',
                    'Bảo hiểm tự nguyện toàn diện',
                    'Bảo hiểm người ngồi trên xe',
                    'Bảo hiểm tai nạn lái xe',
                    'Tư vấn và hỗ trợ khiếu nại'
                ],
                'price_range' => 'Từ 500,000 VNĐ/năm',
                'duration' => '1-2 giờ'
            ],
            'finance' => [
                'title' => 'Tài chính linh hoạt',
                'description' => 'Giải pháp tài chính đa dạng với lãi suất cạnh tranh',
                'icon' => 'fas fa-calculator',
                'features' => [
                    'Vay trả góp xe hơi',
                    'Lãi suất cạnh tranh từ 0%',
                    'Thủ tục đơn giản, nhanh chóng',
                    'Hỗ trợ vay lên đến 90% giá trị xe',
                    'Tư vấn tài chính miễn phí'
                ],
                'price_range' => 'Lãi suất từ 0%',
                'duration' => '1-3 ngày'
            ],
            'accessories' => [
                'title' => 'Phụ kiện chính hãng',
                'description' => 'Cung cấp đầy đủ phụ kiện chính hãng với chất lượng cao',
                'icon' => 'fas fa-tools',
                'features' => [
                    'Phụ kiện nội thất cao cấp',
                    'Phụ kiện ngoại thất và bảo vệ',
                    'Phụ kiện công nghệ và giải trí',
                    'Phụ kiện bảo dưỡng và chăm sóc',
                    'Bảo hành chính hãng'
                ],
                'price_range' => 'Từ 100,000 VNĐ',
                'duration' => '30 phút - 2 giờ'
            ],
            'consultation' => [
                'title' => 'Tư vấn chuyên nghiệp',
                'description' => 'Dịch vụ tư vấn chuyên nghiệp về xe hơi và dịch vụ',
                'icon' => 'fas fa-headset',
                'features' => [
                    'Tư vấn chọn xe phù hợp',
                    'Tư vấn bảo dưỡng và sửa chữa',
                    'Tư vấn tài chính và bảo hiểm',
                    'Tư vấn phụ kiện và nâng cấp',
                    'Hỗ trợ 24/7'
                ],
                'price_range' => 'Miễn phí',
                'duration' => '30 phút - 2 giờ'
            ]
        ];

        return view('user.services.index', compact('services'));
    }

    /**
     * Display maintenance services
     */
    public function maintenance()
    {
        $maintenancePackages = [
            'basic' => [
                'name' => 'Gói cơ bản',
                'price' => '500,000',
                'description' => 'Bảo dưỡng cơ bản cho xe mới',
                'services' => [
                    'Thay dầu nhớt động cơ',
                    'Thay bộ lọc dầu',
                    'Thay bộ lọc gió',
                    'Kiểm tra mức nước làm mát',
                    'Kiểm tra áp suất lốp'
                ],
                'suitable_for' => 'Xe dưới 20,000 km',
                'duration' => '2 giờ'
            ],
            'standard' => [
                'name' => 'Gói tiêu chuẩn',
                'price' => '1,200,000',
                'description' => 'Bảo dưỡng tiêu chuẩn toàn diện',
                'services' => [
                    'Tất cả dịch vụ gói cơ bản',
                    'Thay dầu hộp số',
                    'Thay dầu phanh',
                    'Kiểm tra hệ thống phanh',
                    'Kiểm tra hệ thống điện',
                    'Kiểm tra hệ thống treo'
                ],
                'suitable_for' => 'Xe 20,000 - 60,000 km',
                'duration' => '3 giờ'
            ],
            'premium' => [
                'name' => 'Gói cao cấp',
                'price' => '2,000,000',
                'description' => 'Bảo dưỡng cao cấp toàn diện',
                'services' => [
                    'Tất cả dịch vụ gói tiêu chuẩn',
                    'Thay dầu vi sai',
                    'Kiểm tra hệ thống điều hòa',
                    'Kiểm tra hệ thống an toàn',
                    'Kiểm tra hệ thống giải trí',
                    'Làm sạch hệ thống nhiên liệu'
                ],
                'suitable_for' => 'Xe trên 60,000 km',
                'duration' => '4 giờ'
            ]
        ];

        return view('user.services.maintenance', compact('maintenancePackages'));
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
