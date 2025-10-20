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


    public static function formatDate($date): string
    {
        if ($date instanceof CarbonInterface) return $date->format('d/m/Y');
        try { return $date ? date('d/m/Y', strtotime((string)$date)) : '-'; } catch (\Throwable) { return (string)$date; }
    }

    public static function formatTime($time): string
    {
        if (!$time) return '-';
        try {
            // Hỗ trợ cả string HH:MM:SS và Carbon/DateTime
            if ($time instanceof \DateTimeInterface) {
                return $time->format('H:i');
            }
            $s = (string) $time;
            // Nếu đã là HH:MM
            if (preg_match('/^\d{2}:\d{2}$/', $s)) {
                return $s;
            }
            // Nếu HH:MM:SS
            if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $s)) {
                return substr($s, 0, 5);
            }
            // Fallback parse
            return date('H:i', strtotime($s));
        } catch (\Throwable) {
            return (string) $time;
        }
    }

    public static function formatDateTime($date, ?string $time): string
    {
        $d = self::formatDate($date);
        $t = self::formatTime($time);
        return trim($d . ' ' . ($t !== '-' ? ('• ' . $t) : ''));
    }
}


