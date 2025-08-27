<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceAppointment;
use App\Models\User;
use App\Models\CarVariant;
use App\Models\Showroom;

class ServiceAppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role','user')->get();
        $techs = User::where('role','technician')->get();
        $variants = CarVariant::pluck('id');
        $showrooms = Showroom::pluck('id');
        if ($users->isEmpty() || $variants->isEmpty() || $showrooms->isEmpty()) return;

        $count = 120;
        for ($i = 1; $i <= $count; $i++) {
            $user = $users->random();
            $techId = optional($techs->random())->id;
            $variantId = $variants->random();
            $showroomId = $showrooms->random();
            $date = now()->addDays(rand(1,30));
            $time = str_pad((string) rand(8,16), 2, '0', STR_PAD_LEFT) . ':' . (rand(0,1) ? '00' : '30');
            ServiceAppointment::create([
                'user_id' => $user->id,
                'showroom_id' => $showroomId,
                'assigned_technician_id' => $techId,
                'car_variant_id' => $variantId,
                'vehicle_vin' => null,
                'vehicle_registration' => 'XX-' . rand(10,99) . rand(100,999) . '.' . rand(10,99),
                'vehicle_year' => rand(2015,2024),
                'current_mileage' => rand(5000,90000),
                'appointment_number' => 'SA-' . date('Ymd') . '-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'appointment_date' => $date->toDateString(),
                'appointment_time' => $time,
                'estimated_duration' => [60,90,120][array_rand([60,90,120])],
                'appointment_type' => ['maintenance','repair','inspection'][array_rand(['maintenance','repair','inspection'])],
                'requested_services' => 'Dịch vụ #' . $i,
                'service_description' => null,
                'customer_complaints' => null,
                'special_instructions' => null,
                'status' => ['scheduled','in_progress','completed','cancelled'][array_rand(['scheduled','in_progress','completed','cancelled'])],
                'priority' => ['low','medium','high'][array_rand(['low','medium','high'])],
                'is_warranty_work' => (bool) rand(0,1),
                'warranty_number' => null,
                'warranty_expiry_date' => null,
                'estimated_cost' => rand(300000,3000000),
                'actual_cost' => null,
                'parts_cost' => null,
                'labor_cost' => null,
                'tax_amount' => null,
                'discount_amount' => null,
                'total_amount' => null,
                'payment_status' => ['pending','paid'][array_rand(['pending','paid'])],
                'payment_method' => 'cash',
                'payment_date' => null,
                'actual_start_time' => null,
                'actual_end_time' => null,
                'work_performed' => null,
                'parts_used' => null,
                'technician_notes' => null,
                'quality_check_passed' => (bool) rand(0,1),
                'quality_check_by' => null,
                'quality_check_notes' => null,
                'vehicle_ready' => (bool) rand(0,1),
                'vehicle_ready_time' => null,
                'customer_notified' => (bool) rand(0,1),
                'customer_notified_time' => null,
                'customer_satisfaction' => null,
                'customer_recommend' => (bool) rand(0,1),
                'customer_feedback' => null,
                'notes' => null,
                'documents' => null,
                'tags' => null,
            ]);
        }
    }
}


