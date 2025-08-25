<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CarModel;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class CarModelController extends Controller
{
    public function show($id)
    {
        $carModel = CarModel::with([
            'carBrand',
            'variants.colors',
            'variants.images',
            'variants.specifications',
            'variants.featuresRelation',
            'variants.reviews',
            'images',
        ])->findOrFail($id);

        // Lấy các phiên bản, màu sắc, option, gallery...
        $variants = $carModel->variants;
        $gallery = $carModel->images->pluck('image_url')->filter()->values()->toArray();

        // Tính toán thống kê/phạm vi giá và các thông tin tổng hợp cho trang chi tiết model
        /** @var Collection<int, \App\Models\CarVariant> $variants */
        $activeVariants = $variants->filter(function ($v) {
            return (bool) ($v->is_active ?? true);
        })->values();

        $prices = $activeVariants->map(function ($v) {
            return (float) ($v->final_price ?? 0);
        })->filter(function ($n) {
            return $n > 0;
        })->values();

        $fuelTypes = $activeVariants->pluck('fuel_type')->filter()->map(function ($s) {
            return strtolower((string) $s);
        })->unique()->values();

        $transmissions = $activeVariants->pluck('transmission')->filter()->map(function ($s) {
            return strtolower((string) $s);
        })->unique()->values();

        $seatingCapacities = $activeVariants->pluck('seating_capacity')->filter()->unique()->sort()->values();

        $stats = [
            'total_variants' => $activeVariants->count(),
            'price_range' => [
                'min' => $prices->isNotEmpty() ? $prices->min() : null,
                'max' => $prices->isNotEmpty() ? $prices->max() : null,
            ],
            'average_rating' => $carModel->average_rating ?? ($activeVariants->avg('average_rating') ?: null),
            'rating_count' => $carModel->rating_count ?? ($activeVariants->sum('rating_count') ?: 0),
            'fuel_types' => $fuelTypes,
            'transmissions' => $transmissions,
            'seating_capacities' => $seatingCapacities,
        ];

        // Phiên bản nổi bật (nếu có)
        $featuredVariants = $activeVariants->filter(function ($v) {
            return (bool) ($v->is_featured ?? false);
        })->values();

        // Tổng hợp màu sắc (unique theo tên/hex)
        $aggregatedColors = [];
        foreach ($activeVariants as $v) {
            foreach (($v->colors ?? []) as $c) {
                $name = trim((string) ($c->color_name ?? ''));
                $hex = trim((string) ($c->hex_code ?? $c->color_code ?? ''));
                $key = strtolower($name . '|' . $hex);
                if (!isset($aggregatedColors[$key])) {
                    $aggregatedColors[$key] = [
                        'name' => $name ?: ($hex ?: 'Màu khác'),
                        'hex' => $hex ?: null,
                        'popular' => (bool) ($c->is_popular ?? false),
                        'image_url' => method_exists($c, 'getImageUrlAttribute') ? $c->image_url : ($c->image_url ?? null),
                    ];
                }
            }
        }
        $aggregatedColors = array_values($aggregatedColors);

        // Tổng hợp tính năng theo nhóm (ưu tiên included/active)
        $featuresByCategory = [];
        foreach ($activeVariants as $v) {
            foreach (($v->featuresRelation ?? []) as $f) {
                if (isset($f->is_active) && !$f->is_active) { continue; }
                if (isset($f->is_included) && !$f->is_included) { continue; }
                $cat = strtolower((string) ($f->category ?? 'khac'));
                $name = trim((string) ($f->feature_name ?? ''));
                if ($name === '') { continue; }
                $featuresByCategory[$cat] = $featuresByCategory[$cat] ?? [];
                $featuresByCategory[$cat][$name] = true; // dùng map để unique theo tên
            }
        }
        // convert to array of strings
        foreach ($featuresByCategory as $cat => $set) {
            $featuresByCategory[$cat] = array_values(array_keys($set));
        }

        // Xây bảng so sánh nhanh theo các thông số chính
        $specKeys = [
            // label => [possible spec names (lowercase) to search]
            'Nhiên liệu' => ['fuel_type', 'nhiên liệu'],
            'Hộp số' => ['transmission', 'hộp số'],
            'Dẫn động' => ['drivetrain', 'dẫn động'],
            'Số chỗ' => ['seating_capacity', 'số chỗ'],
            'Động cơ' => ['engine_type', 'loại động cơ', 'engine'],
            'Công suất' => ['power_output', 'công suất tối đa', 'công suất'],
            'Mô-men xoắn' => ['torque', 'mô-men xoắn'],
            'Tiêu thụ nhiên liệu' => ['fuel_consumption', 'tiêu thụ nhiên liệu'],
            'Dài (mm)' => ['length', 'dài'],
            'Rộng (mm)' => ['width', 'rộng'],
            'Cao (mm)' => ['height', 'cao'],
            'Chiều dài cơ sở (mm)' => ['wheelbase', 'chiều dài cơ sở'],
            'Khoảng sáng gầm (mm)' => ['ground clearance', 'khoảng sáng gầm'],
        ];

        $compareMatrix = [];
        foreach ($specKeys as $label => $names) {
            $row = [];
            foreach ($activeVariants as $v) {
                $value = null;
                // ưu tiên accessor sẵn có
                $accessorMap = [
                    'fuel_type' => $v->fuel_type ?? null,
                    'transmission' => $v->transmission ?? null,
                    'drivetrain' => $v->drivetrain ?? null,
                    'seating_capacity' => $v->seating_capacity ?? null,
                    'power_output' => $v->power_output ?? null,
                ];
                foreach ($names as $n) {
                    $nLc = strtolower($n);
                    if (isset($accessorMap[$nLc]) && !empty($accessorMap[$nLc])) {
                        $value = $accessorMap[$nLc];
                        break;
                    }
                    if (method_exists($v, 'getSpecValue')) {
                        $value = $v->getSpecValue($n) ?? $v->getSpecValue($nLc);
                        if (!empty($value)) { break; }
                    }
                }
                $row[] = $value ? (string) $value : '-';
            }
            // Chỉ thêm hàng nếu có ít nhất một giá trị hữu ích
            if (collect($row)->filter(fn($x) => $x !== '-' && $x !== '')->isNotEmpty()) {
                $compareMatrix[$label] = $row;
            }
        }

        // Thông số nổi bật (lấy từ compareMatrix theo thứ tự ưu tiên)
        $highlightOrder = ['Động cơ', 'Công suất', 'Hộp số', 'Dẫn động', 'Tiêu thụ nhiên liệu'];
        $highlightSpecs = [];
        foreach ($highlightOrder as $l) {
            if (isset($compareMatrix[$l])) {
                // Nếu các phiên bản có cùng giá trị, hiển thị một giá trị; ngược lại hiển thị "N phiên bản"
                $vals = array_values(array_unique($compareMatrix[$l]));
                $highlightSpecs[$l] = count($vals) === 1 ? $vals[0] : (count($vals) . ' tuỳ chọn');
            }
        }

        // Các model liên quan cùng hãng
        $relatedModels = CarModel::query()
            ->with(['images'])
            ->where('car_brand_id', $carModel->car_brand_id)
            ->where('id', '!=', $carModel->id)
            ->where(function ($q) {
                $q->whereNull('is_active')->orWhere('is_active', 1);
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(6)
            ->get();

        return view('user.car-models.index', [
            'carModel' => $carModel,
            'variants' => $activeVariants,
            'gallery' => $gallery,
            'stats' => $stats,
            'featuredVariants' => $featuredVariants,
            'relatedModels' => $relatedModels,
            'aggregatedColors' => $aggregatedColors,
            'featuresByCategory' => $featuresByCategory,
            'compareMatrix' => $compareMatrix,
            'highlightSpecs' => $highlightSpecs,
        ]);
    }
}
