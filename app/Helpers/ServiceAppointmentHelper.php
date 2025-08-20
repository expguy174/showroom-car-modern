<?php

namespace App\Helpers;

use Carbon\CarbonInterface;

class ServiceAppointmentHelper
{
    public static function statusLabel(?string $status): string
    {
        $map = [
            'scheduled'   => 'Đã lên lịch',
            'confirmed'   => 'Đã xác nhận',
            'in_progress' => 'Đang thực hiện',
            'completed'   => 'Hoàn thành',
            'cancelled'   => 'Đã hủy',
            'no_show'     => 'Không đến',
            'rescheduled' => 'Đã dời lịch',
        ];
        $s = (string) $status;
        return $map[$s] ?? ($s !== '' ? ucfirst(str_replace('_',' ', $s)) : '-');
    }

    public static function statusBadgeClass(?string $status): string
    {
        return match ($status) {
            'completed'   => 'bg-green-100 text-green-800',
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            'confirmed'   => 'bg-blue-100 text-blue-800',
            'cancelled'   => 'bg-red-100 text-red-800',
            'rescheduled' => 'bg-purple-100 text-purple-800',
            default       => 'bg-gray-100 text-gray-800',
        };
    }

    public static function typeLabel(?string $type): string
    {
        $map = [
            'maintenance'    => 'Bảo dưỡng',
            'repair'         => 'Sửa chữa',
            'inspection'     => 'Kiểm tra',
            'warranty_work'  => 'Bảo hành',
            'recall_service' => 'Triệu hồi',
            'emergency'      => 'Khẩn cấp',
            'other'          => 'Khác',
        ];
        $t = (string) $type;
        return $map[$t] ?? ($t !== '' ? ucfirst(str_replace('_',' ', $t)) : '-');
    }

    public static function priorityLabel(?string $priority): string
    {
        $map = [
            'low'    => 'Thấp',
            'medium' => 'Trung bình',
            'high'   => 'Cao',
            'urgent' => 'Khẩn',
        ];
        $p = (string) $priority;
        return $map[$p] ?? ($p !== '' ? ucfirst($p) : '-');
    }

    public static function formatDate($date): string
    {
        if ($date instanceof CarbonInterface) return $date->format('d/m/Y');
        try { return $date ? date('d/m/Y', strtotime((string)$date)) : '-'; } catch (\Throwable) { return (string)$date; }
    }

    public static function formatTime(?string $time): string
    {
        return $time ?: '-';
    }

    public static function formatDateTime($date, ?string $time): string
    {
        $d = self::formatDate($date);
        $t = self::formatTime($time);
        return trim($d . ' ' . ($t !== '-' ? ('• ' . $t) : ''));
    }
}


