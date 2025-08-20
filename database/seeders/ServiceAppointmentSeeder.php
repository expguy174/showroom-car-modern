<?php

namespace Database\Seeders;

use App\Models\ServiceAppointment;
use App\Models\User;
use App\Models\Service;
use App\Models\Showroom;
use App\Models\CarVariant;
use Illuminate\Database\Seeder;

class ServiceAppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $services = Service::all();
        $showrooms = Showroom::all();
        $carVariants = CarVariant::all();

        $appointments = [
            // Lịch hẹn bảo dưỡng định kỳ
            [
                'appointment_number' => 'SA-2024-001',
                'user_id' => $users->where('email', 'customer1@example.com')->first()->id,
                'showroom_id' => $showrooms->where('code', 'HN_CENTER')->first()->id,
                'car_variant_id' => $carVariants->where('name', 'Vios G')->first()->id,
                'vehicle_registration' => '30A-12345',
                'vehicle_year' => 2022,
                'current_mileage' => 9500,
                'appointment_date' => now()->addDays(3)->toDateString(),
                'appointment_time' => '09:00:00',
                'estimated_duration' => 120,
                'appointment_type' => 'maintenance',
                'requested_services' => 'Bảo dưỡng định kỳ 10.000km',
                'service_description' => 'Bảo dưỡng định kỳ cho xe đã chạy 10.000km',
                'customer_complaints' => 'Xe cần bảo dưỡng định kỳ lần đầu',
                'special_instructions' => 'Khách hàng VIP, cần chăm sóc đặc biệt',
                'status' => 'confirmed',
                'priority' => 'medium',
                'estimated_cost' => 1500000,
                'actual_cost' => null,
                'total_amount' => 1500000,
                'payment_status' => 'pending',
                'payment_method' => null,
                'work_performed' => null,
                'technician_notes' => null,
                'customer_satisfaction' => null,
                'customer_feedback' => null,
                'notes' => 'Lịch hẹn bảo dưỡng định kỳ'
            ],

            // Lịch hẹn thay dầu
            [
                'appointment_number' => 'SA-2024-002',
                'user_id' => $users->where('email', 'customer2@example.com')->first()->id,
                'showroom_id' => $showrooms->where('code', 'HCM_Q1')->first()->id,
                'car_variant_id' => $carVariants->where('name', 'City G')->first()->id,
                'vehicle_registration' => '51A-67890',
                'vehicle_year' => 2021,
                'current_mileage' => 15000,
                'appointment_date' => now()->subDays(2)->toDateString(),
                'appointment_time' => '14:00:00',
                'estimated_duration' => 60,
                'appointment_type' => 'maintenance',
                'requested_services' => 'Thay dầu động cơ',
                'service_description' => 'Thay dầu động cơ và lọc dầu',
                'customer_complaints' => 'Cần thay dầu và lọc dầu',
                'special_instructions' => 'Khách hàng thường xuyên',
                'status' => 'completed',
                'priority' => 'low',
                'estimated_cost' => 800000,
                'actual_cost' => 800000,
                'total_amount' => 800000,
                'payment_status' => 'paid',
                'payment_method' => 'cash',
                'payment_date' => now()->subDays(2)->toDateString(),
                'work_performed' => 'Đã thay dầu và lọc dầu, xe hoạt động tốt',
                'technician_notes' => 'Dịch vụ hoàn thành tốt',
                'customer_satisfaction' => 5.0,
                'customer_feedback' => 'Dịch vụ tốt, nhân viên chuyên nghiệp',
                'notes' => 'Lịch hẹn thay dầu đã hoàn thành'
            ],

            // Lịch hẹn sửa chữa phanh
            [
                'appointment_number' => 'SA-2024-003',
                'user_id' => $users->where('email', 'vip@example.com')->first()->id,
                'showroom_id' => $showrooms->where('code', 'HN_TAYHO')->first()->id,
                'car_variant_id' => $carVariants->where('name', 'C-Class C200')->first()->id,
                'vehicle_registration' => '30A-11111',
                'vehicle_year' => 2023,
                'current_mileage' => 8000,
                'appointment_date' => now()->addDays(5)->toDateString(),
                'appointment_time' => '10:00:00',
                'estimated_duration' => 240,
                'appointment_type' => 'repair',
                'requested_services' => 'Sửa chữa phanh',
                'service_description' => 'Kiểm tra và sửa chữa hệ thống phanh',
                'customer_complaints' => 'Phanh có tiếng kêu lạ, cần kiểm tra',
                'special_instructions' => 'Khách hàng VIP, xe cao cấp',
                'status' => 'scheduled',
                'priority' => 'high',
                'estimated_cost' => 3000000,
                'actual_cost' => null,
                'total_amount' => 3000000,
                'payment_status' => 'pending',
                'payment_method' => null,
                'work_performed' => null,
                'technician_notes' => null,
                'customer_satisfaction' => null,
                'customer_feedback' => null,
                'notes' => 'Lịch hẹn sửa chữa phanh'
            ],

            // Lịch hẹn chẩn đoán
            [
                'appointment_number' => 'SA-2024-004',
                'user_id' => $users->where('email', 'customer1@example.com')->first()->id,
                'showroom_id' => $showrooms->where('code', 'HCM_Q7')->first()->id,
                'car_variant_id' => $carVariants->where('name', 'Ranger XLT')->first()->id,
                'vehicle_registration' => '51A-22222',
                'vehicle_year' => 2020,
                'current_mileage' => 25000,
                'appointment_date' => now()->subDays(10)->toDateString(),
                'appointment_time' => '15:00:00',
                'estimated_duration' => 60,
                'appointment_type' => 'inspection',
                'requested_services' => 'Chẩn đoán lỗi động cơ',
                'service_description' => 'Chẩn đoán lỗi động cơ bằng máy chuyên dụng',
                'customer_complaints' => 'Động cơ có tiếng ồn lạ',
                'special_instructions' => 'Khách hàng thay đổi kế hoạch',
                'status' => 'cancelled',
                'priority' => 'medium',
                'estimated_cost' => 500000,
                'actual_cost' => null,
                'total_amount' => 500000,
                'payment_status' => 'pending',
                'payment_method' => null,
                'work_performed' => null,
                'technician_notes' => null,
                'customer_satisfaction' => null,
                'customer_feedback' => null,
                'notes' => 'Lịch hẹn bị hủy do khách hàng thay đổi kế hoạch'
            ],

            // Lịch hẹn thay lốp
            [
                'appointment_number' => 'SA-2024-005',
                'user_id' => $users->where('email', 'customer2@example.com')->first()->id,
                'showroom_id' => $showrooms->where('code', 'DN_CENTER')->first()->id,
                'car_variant_id' => $carVariants->where('name', 'Innova G')->first()->id,
                'vehicle_registration' => '43A-33333',
                'vehicle_year' => 2021,
                'current_mileage' => 30000,
                'appointment_date' => now()->addDays(1)->toDateString(),
                'appointment_time' => '08:00:00',
                'estimated_duration' => 90,
                'appointment_type' => 'maintenance',
                'requested_services' => 'Thay lốp xe',
                'service_description' => 'Thay lốp xe mới',
                'customer_complaints' => 'Cần thay 4 lốp xe mới',
                'special_instructions' => 'Khách hàng cần lốp chất lượng cao',
                'status' => 'confirmed',
                'priority' => 'medium',
                'estimated_cost' => 2000000,
                'actual_cost' => null,
                'total_amount' => 2000000,
                'payment_status' => 'pending',
                'payment_method' => null,
                'work_performed' => null,
                'technician_notes' => null,
                'customer_satisfaction' => null,
                'customer_feedback' => null,
                'notes' => 'Lịch hẹn thay lốp xe'
            ]
        ];

        foreach ($appointments as $appointment) {
            ServiceAppointment::create($appointment);
        }
    }
}
