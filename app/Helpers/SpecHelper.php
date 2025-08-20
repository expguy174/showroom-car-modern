<?php

namespace App\Helpers;

class SpecHelper
{
    public static function normalizeName(string $name): string
    {
        $n = strtolower(trim($name));
        $n = str_replace(['-', ' '], '_', $n);
        $n = preg_replace('/[^a-z0-9_]/', '', $n) ?? $n;
        return $n;
    }

    public static function labelFor(string $specName): string
    {
        $key = self::normalizeName($specName);
        $map = [
            // Engine & performance
            'engine_size' => 'Động cơ',
            'engine_type' => 'Loại động cơ',
            'engine_displacement' => 'Dung tích xy-lanh',
            'cylinders' => 'Số xy-lanh',
            'turbo' => 'Turbo',
            'supercharger' => 'Siêu nạp',
            'power_output' => 'Công suất',
            'power' => 'Công suất',
            'torque' => 'Mô-men xoắn',
            'transmission' => 'Hộp số',
            'drivetrain' => 'Dẫn động',
            'seating_capacity' => 'Số chỗ',
            '0_60_mph' => '0-60 mph',
            'zero_to_sixty' => '0-60 mph',
            'zero_to_hundred' => '0-100 km/h',
            'top_speed' => 'Tốc độ tối đa',
            'gear_ratios' => 'Tỉ số truyền',
            'fuel_type' => 'Nhiên liệu',
            'consumption_city' => 'Tiêu thụ đô thị',
            'fuel_economy_city' => 'Tiêu thụ đô thị',
            'consumption_hwy' => 'Tiêu thụ đường trường',
            'fuel_economy_highway' => 'Tiêu thụ đường trường',
            'consumption_combined' => 'Tiêu thụ hỗn hợp',
            'fuel_economy_combined' => 'Tiêu thụ hỗn hợp',
            'co2_emission' => 'CO₂',
            'emission_standard' => 'Chuẩn khí thải',
            // Dimensions & capacity
            'length' => 'Dài',
            'width' => 'Rộng',
            'height' => 'Cao',
            'wheelbase' => 'Chiều dài cơ sở',
            'ground_clearance' => 'Khoảng sáng gầm',
            'track_width_front' => 'Vệt bánh trước',
            'track_width_rear' => 'Vệt bánh sau',
            'turning_radius' => 'Bán kính quay vòng',
            'curb_weight' => 'Trọng lượng không tải',
            'gross_weight' => 'Trọng lượng toàn tải',
            'fuel_tank_capacity' => 'Dung tích bình',
            'cargo_volume' => 'Khoang hành lý',
            'trunk_volume' => 'Cốp sau',
            'payload' => 'Tải trọng',
            'towing_capacity' => 'Khả năng kéo',
            // Safety & chassis
            'airbags' => 'Túi khí',
            'airbag_count' => 'Số túi khí',
            'abs' => 'ABS',
            'ebd' => 'EBD',
            'esc' => 'Ổn định điện tử',
            'stability_control' => 'Cân bằng điện tử',
            'traction_control' => 'Kiểm soát lực kéo',
            'hill_start_assist' => 'Hỗ trợ khởi hành ngang dốc',
            'hill_descent_control' => 'Hỗ trợ đổ đèo',
            'lane_assist' => 'Hỗ trợ làn đường',
            'adaptive_cruise' => 'Cruise Control thích ứng',
            'parking_sensors' => 'Cảm biến đỗ xe',
            'rear_camera' => 'Camera lùi',
            'camera_360' => 'Camera 360°',
            'front_brake' => 'Phanh trước',
            'rear_brake' => 'Phanh sau',
            'front_suspension' => 'Treo trước',
            'rear_suspension' => 'Treo sau',
            'steering_type' => 'Trợ lực lái',
            'tire_size' => 'Lốp',
            'wheel_size' => 'Mâm',
            // Lighting & comfort/tech
            'headlight_type' => 'Đèn chiếu sáng',
            'daytime_running_lights' => 'Đèn ban ngày',
            'fog_lights' => 'Đèn sương mù',
            'sunroof' => 'Cửa sổ trời',
            'seat_material' => 'Chất liệu ghế',
            'seat_adjustment' => 'Chỉnh ghế',
            'infotainment_screen_size' => 'Màn hình giải trí',
            'speaker_count' => 'Số loa',
            'bluetooth' => 'Bluetooth',
            'apple_carplay' => 'Apple CarPlay',
            'android_auto' => 'Android Auto',
            // Warranty
            'warranty_years' => 'Bảo hành (năm)',
            'warranty_km' => 'Bảo hành (km)',
            'warranty_details' => 'Chi tiết bảo hành',
            // Generic
            'safety_features' => 'Tính năng an toàn',
            'battery_capacity' => 'Dung lượng pin',
            'battery_range' => 'Quãng đường (WLTP)',
            'charging_time' => 'Thời gian sạc',
        ];
        if (isset($map[$key])) {
            return $map[$key];
        }
        $fallback = ucwords(str_replace('_', ' ', $key));
        return $fallback;
    }

    public static function unitLabel(?string $unit): ?string
    {
        if ($unit === null || $unit === '') return null;
        $u = strtolower(trim($unit));
        $map = [
            'mm' => 'mm',
            'cm' => 'cm',
            'm' => 'm',
            'kg' => 'kg',
            'kw' => 'kW',
            'ps' => 'PS',
            'hp' => 'HP',
            'nm' => 'Nm',
            'km' => 'km',
            'g/km' => 'g/km',
            'l/100km' => 'L/100km',
            'kwh/100km' => 'kWh/100km',
            'kwh' => 'kWh',
            'l' => 'L',
            'h' => 'h',
            'sec' => 'giây',
            'seats' => 'chỗ',
        ];
        return $map[$u] ?? $unit;
    }

    public static function formatValue(?string $value, ?string $unit = null, ?string $specName = null): string
    {
        $v = trim((string)($value ?? ''));
        if ($v === '') return '';
        $key = $specName ? self::normalizeName($specName) : null;
        // Inline replacements/unifications
        $v = preg_replace('/\bsec\b/i', 'giây', $v);
        $v = preg_replace('/kwh\/100km/i', 'kWh/100km', $v);
        $v = preg_replace('/l\/100km/i', 'L/100km', $v);
        if ($key === 'gear_ratios') {
            $v = preg_replace('/\b(\d+)\s*-?speed\b/i', '$1 cấp', $v);
        }
        if ($key === 'engine_size' && stripos($v, 'electric motor') !== false) {
            $v = 'Động cơ điện';
        }
        if ($specName) {
            $v = self::localizeDiscrete($key ?? '', $v);
        }
        // If purely numeric, format with thousands separator
        if (preg_match('/^-?\d+(?:[\.,]\d+)?$/', $v)) {
            $num = str_replace(',', '.', $v);
            if (strpos($num, '.') !== false) {
                $v = number_format((float)$num, 2, ',', '.');
                $v = rtrim(rtrim($v, '0'), ',');
            } else {
                $v = number_format((int)$num, 0, ',', '.');
            }
        }
        $u = self::unitLabel($unit);
        if ($u) {
            $vLower = mb_strtolower($v, 'UTF-8');
            $uLower = mb_strtolower($u, 'UTF-8');
            $knownUnits = ['kw','hp','nm','km/h','l/100km','kwh/100km','giây','h','l','kwh','mm','cm','m','seats','chỗ'];
            $hasAnyUnit = false;
            foreach ($knownUnits as $tok) { if (strpos($vLower, $tok) !== false) { $hasAnyUnit = true; break; } }
            if (!$hasAnyUnit && strpos($vLower, $uLower) === false && preg_match('/\d/', $v)) { $v .= ' ' . $u; }
        }
        return $v;
    }

    private static function localizeDiscrete(string $key, string $value): string
    {
        $val = trim($value);
        $lower = strtolower($val);
        if ($key === 'fuel_type') {
            $map = [
                'gasoline' => 'Xăng',
                'petrol' => 'Xăng',
                'diesel' => 'Dầu',
                'hybrid' => 'Hybrid',
                'plug-in_hybrid' => 'Hybrid sạc ngoài',
                'electric' => 'Điện',
                'hydrogen' => 'Hydro',
            ];
            return $map[$lower] ?? $val;
        }
        if ($key === 'transmission') {
            if (preg_match('/\b\d+\s*-?speed\b/i', $val)) return preg_replace('/speed/i', 'cấp', $val);
            if (stripos($val, 'dct') !== false) return 'Ly hợp kép';
            if (stripos($val, 'cvt') !== false) return 'CVT';
            if (stripos($val, 'amt') !== false) return 'Tự động kiểu cơ';
            if (preg_match('/\b(at|automatic)\b/i', $val)) return 'Tự động';
            if (preg_match('/\b(mt|manual)\b/i', $val)) return 'Số sàn';
            return $val;
        }
        if ($key === 'drivetrain') {
            if (preg_match('/awd|4wd|4x4|all-?wheel/i', $val)) return 'Hai cầu';
            if (preg_match('/rwd|rear/i', $val)) return 'Cầu sau';
            if (preg_match('/fwd|front/i', $val)) return 'Cầu trước';
            return $val;
        }
        if ($key === 'airbags') {
            return preg_replace('/airbags?/i', 'túi khí', $val);
        }
        if ($key === 'abs') {
            $map = ['standard' => 'Tiêu chuẩn', 'advanced' => 'Nâng cao', 'premium' => 'Cao cấp'];
            return $map[$lower] ?? 'ABS';
        }
        if ($key === 'stability_control') {
            $map = ['standard' => 'Tiêu chuẩn', 'advanced' => 'Nâng cao', 'premium' => 'Cao cấp'];
            return $map[$lower] ?? 'Cân bằng điện tử';
        }
        return $val;
    }

    public static function groupForCategory(?string $category): ?string
    {
        $c = $category ? strtolower($category) : '';
        $map = [
            'engine' => 'performance',
            'transmission' => 'performance',
            'performance' => 'performance',
            'fuel' => 'performance',
            'emissions' => 'performance',
            'battery' => 'battery',
            'dimensions' => 'dimensions',
            'capacity' => 'capacity',
            'safety' => 'safety',
            'warranty' => 'warranty',
        ];
        if (isset($map[$c])) return $map[$c];
        // Fallback: use normalized category as group key if present
        if ($c !== '') {
            return self::normalizeName($c);
        }
        return null;
    }

    public static function groupOrder(): array
    {
        return ['performance','dimensions','capacity','safety','battery','warranty'];
    }

    public static function groupLabels(): array
    {
        return [
            'performance' => 'Vận hành',
            'capacity' => 'Dung tích',
            'battery' => 'Pin/EV',
            'dimensions' => 'Kích thước',
            'safety' => 'An toàn',
            'warranty' => 'Bảo hành',
        ];
    }

    public static function groupIcons(): array
    {
        return [
            'performance' => 'fas fa-tachometer-alt',
            'capacity' => 'fas fa-box-open',
            'battery' => 'fas fa-battery-full',
            'dimensions' => 'fas fa-ruler-combined',
            'safety' => 'fas fa-shield-alt',
            'warranty' => 'fas fa-medal',
        ];
    }

    public static function labelForCategory(string $categoryKeyOrName): string
    {
        $key = self::normalizeName($categoryKeyOrName);
        $labels = self::groupLabels();
        if (isset($labels[$key])) return $labels[$key];
        // Fallback: humanize
        return ucwords(str_replace('_', ' ', $key));
    }
}


