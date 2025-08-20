<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarVariant;
use App\Models\Accessory;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\CarSpecification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type');
        $mode = $type === 'accessory' ? 'accessory' : ($type === 'car' ? 'variants' : 'all');

        // Filter sources
        $brands = CarBrand::where('is_active', 1)->orderBy('name')->get();
        $modelsQuery = CarModel::where('is_active', 1);
        if ($request->filled('brand')) {
            $modelsQuery->where('car_brand_id', $request->brand);
        }
        $models = $modelsQuery->orderBy('name')->get();
        // Use direct fields for filter options to ensure availability
        $optionBase = CarVariant::where('is_active', 1);
        $fuelTypes = (clone $optionBase)
            ->select('fuel_type')
            ->whereNotNull('fuel_type')
            ->whereRaw("TRIM(fuel_type) <> ''")
            ->distinct()
            ->orderBy('fuel_type')
            ->pluck('fuel_type')
            ->map(fn($v) => trim((string) $v))
            ->filter()
            ->values();
        if ($fuelTypes->isEmpty()) {
            // Global fallback from all active variants
            $fuelTypes = CarVariant::query()
                ->select('fuel_type')
                ->where('is_active', 1)
                ->whereNotNull('fuel_type')
                ->whereRaw("TRIM(fuel_type) <> ''")
                ->distinct()
                ->orderBy('fuel_type')
                ->pluck('fuel_type')
                ->map(fn($v) => trim((string) $v))
                ->filter()
                ->values();
        }
        if ($fuelTypes->isEmpty()) {
            // Final fallback from specifications if available (rare)
            $fuelTypes = CarSpecification::query()
                ->select('spec_value')
                ->where('spec_name', 'fuel_type')
                ->distinct()
                ->orderBy('spec_value')
                ->pluck('spec_value')
                ->map(fn($v) => trim((string) $v))
                ->filter()
                ->values();
        }
        if ($fuelTypes->isEmpty()) {
            // Static safe fallback to keep filter usable
            $fuelTypes = collect(['Xăng', 'Diesel', 'Điện', 'Hybrid']);
        }

        $transmissions = (clone $optionBase)
            ->select('transmission')
            ->whereNotNull('transmission')
            ->whereRaw("TRIM(transmission) <> ''")
            ->distinct()
            ->orderBy('transmission')
            ->pluck('transmission')
            ->map(fn($v) => trim((string) $v))
            ->filter()
            ->values();
        if ($transmissions->isEmpty()) {
            // Global fallback from all active variants
            $transmissions = CarVariant::query()
                ->select('transmission')
                ->where('is_active', 1)
                ->whereNotNull('transmission')
                ->whereRaw("TRIM(transmission) <> ''")
                ->distinct()
                ->orderBy('transmission')
                ->pluck('transmission')
                ->map(fn($v) => trim((string) $v))
                ->filter()
                ->values();
        }
        if ($transmissions->isEmpty()) {
            // Final fallback from specifications (support Vietnamese spec_name)
            $transmissions = CarSpecification::query()
                ->select('spec_value')
                ->whereIn('spec_name', ['transmission', 'Hộp số'])
                ->distinct()
                ->orderBy('spec_value')
                ->pluck('spec_value')
                ->map(fn($v) => trim((string) $v))
                ->filter()
                ->values();
        }
        if ($transmissions->isEmpty()) {
            // Static safe fallback
            $transmissions = collect(['Số tự động', 'CVT', 'Số sàn']);
        }

        $bodyTypes = CarModel::query()
            ->select('body_type')
            ->where('is_active', 1)
            ->whereNotNull('body_type')
            ->distinct()
            ->orderBy('body_type')
            ->pluck('body_type')
            ->map(fn($v) => trim((string) $v))
            ->filter()
            ->values();

        // Merge with safe defaults and dedupe (case/diacritics-insensitive by simple lowercase key)
        $defaultFuel = collect(['Xăng','Diesel','Điện','Hybrid']);
        $defaultTrans = collect(['Số tự động','CVT','Số sàn']);

        $fuelSet = $fuelTypes->merge($defaultFuel)->filter()->values();
        $transSet = $transmissions->merge($defaultTrans)->filter()->values();

        $dedupe = function($values) {
            $seen = [];
            $out = [];
            foreach ($values as $v) {
                $key = function_exists('mb_strtolower') ? mb_strtolower(trim((string)$v), 'UTF-8') : strtolower(trim((string)$v));
                if ($key === '') continue;
                if (!isset($seen[$key])) { $seen[$key] = true; $out[] = (string)$v; }
            }
            return collect($out);
        };

        $fuelTypes = $dedupe($fuelSet);
        $transmissions = $dedupe($transSet);

        // Build label maps
        $fuelMap = [
            'gasoline' => 'Xăng', 'petrol' => 'Xăng', 'xang' => 'Xăng', 'xăng' => 'Xăng',
            'diesel' => 'Dầu', 'dau' => 'Dầu', 'dầu' => 'Dầu',
            'hybrid' => 'Hybrid', 'plug-in_hybrid' => 'Hybrid sạc ngoài', 'phev' => 'Hybrid sạc ngoài',
            'electric' => 'Điện', 'ev' => 'Điện', 'dien' => 'Điện', 'điện' => 'Điện',
            'hydrogen' => 'Hydro', 'lpg' => 'Gas', 'cng' => 'CNG', 'ethanol' => 'Ethanol',
        ];
        $transMap = [
            'manual' => 'Số sàn', 'so san' => 'Số sàn', 'số sàn' => 'Số sàn',
            'automatic' => 'Số tự động', 'auto' => 'Số tự động', 'so tu dong' => 'Số tự động', 'số tự động' => 'Số tự động',
            'cvt' => 'CVT', 'dct' => 'DCT', 'amt' => 'AMT', 'semi-automatic' => 'Bán tự động', 'sequential' => 'Tuần tự',
            'don toc do' => 'Đơn tốc độ', 'đơn tốc độ' => 'Đơn tốc độ',
        ];
        $bodyMap = [
            'sedan' => 'Sedan', 'suv' => 'SUV', 'hatchback' => 'Hatchback', 'minivan' => 'Minivan', 'mpv' => 'MPV',
            'pickup' => 'Bán tải', 'truck' => 'Bán tải', 'coupe' => 'Coupe', 'convertible' => 'Mui trần',
            'wagon' => 'Wagon', 'crossover' => 'Crossover',
        ];

        $fuelOptions = $fuelTypes->map(function ($v) use ($fuelMap) {
            $k = function_exists('mb_strtolower') ? mb_strtolower(trim((string)$v), 'UTF-8') : strtolower(trim((string)$v));
            $label = $fuelMap[$k] ?? $v;
            return ['value' => (string)$v, 'label' => (string)$label];
        })->sortBy('label', SORT_NATURAL|SORT_FLAG_CASE)->values();
        $transOptions = $transmissions->map(function ($v) use ($transMap) {
            $k = function_exists('mb_strtolower') ? mb_strtolower(trim((string)$v), 'UTF-8') : strtolower(trim((string)$v));
            $label = $transMap[$k] ?? $v;
            return ['value' => (string)$v, 'label' => (string)$label];
        })->sortBy('label', SORT_NATURAL|SORT_FLAG_CASE)->values();
        $bodyOptions = $bodyTypes->map(function ($v) use ($bodyMap) {
            $k = function_exists('mb_strtolower') ? mb_strtolower(trim((string)$v), 'UTF-8') : strtolower(trim((string)$v));
            $label = $bodyMap[$k] ?? $v;
            return ['value' => (string)$v, 'label' => (string)$label];
        })->values();

        // Debug options if requested
        if ($request->get('debug') === 'options') {
            try {
                Log::info('Products filter options', [
                    'fuelOptions' => $fuelOptions,
                    'transOptions' => $transOptions,
                    'bodyOptions' => $bodyOptions,
                    'fuelTypesRaw' => $fuelTypes,
                    'transmissionsRaw' => $transmissions,
                    'bodyTypesRaw' => $bodyTypes,
                ]);
            } catch (\Throwable $e) {
                // ignore logging errors
            }
        }

        $accessoryCategories = Accessory::query()
            ->where('is_active', 1)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        if ($mode === 'accessory') {
            $accQuery = Accessory::query()->where('is_active', 1);

            if ($request->filled('q')) {
                $keyword = trim($request->q);
                $accQuery->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhere('brand', 'like', "%{$keyword}%");
                });
            }

            if ($request->filled('acc_brand')) {
                $accQuery->where('brand', $request->acc_brand);
            }
            if ($request->filled('acc_category')) {
                $accQuery->where('category', $request->acc_category);
            }
            if ($request->filled('stock_status')) {
                $accQuery->where('stock_status', $request->stock_status);
            }

            if ($request->filled('price_quick')) {
                $range = $request->price_quick;
                if (strpos($range, '-') !== false) {
                    [$min, $max] = explode('-', $range);
                    if ($min !== '') $accQuery->where('price', '>=', (int) $min);
                    if ($max !== '') $accQuery->where('price', '<=', (int) $max);
                }
            }
            if ($request->filled('price_min')) {
                $accQuery->where('price', '>=', $request->price_min);
            }
            if ($request->filled('price_max')) {
                $accQuery->where('price', '<=', $request->price_max);
            }

            $sortBy = $request->get('sort', 'name');
            $sortOrder = $request->get('order', 'asc');
            switch ($sortBy) {
                case 'price':
                    $accQuery->orderBy('price', $sortOrder);
                    break;
                case 'name':
                    $accQuery->orderBy('name', $sortOrder);
                    break;
                default:
                    $accQuery->orderBy('name', $sortOrder);
            }

            $accessories = $accQuery
                ->withCount(['reviews as approved_reviews_count' => function($q){ $q->where('is_approved', true); }])
                ->withAvg(['reviews as approved_reviews_avg' => function($q){ $q->where('is_approved', true); }], 'rating')
                ->paginate(12);

            $accBrands = Accessory::where('is_active', 1)
                ->whereNotNull('brand')
                ->orderBy('brand')
                ->distinct()
                ->pluck('brand');

            $stats = [
                'total_variants' => CarVariant::where('is_active', 1)->count(),
                'in_stock' => CarVariant::where('is_active', 1)->where('is_available', 1)->count(),
                'acc_total' => Accessory::where('is_active', 1)->count(),
                'acc_in_stock' => Accessory::where('is_active', 1)->where('stock_status', 'in_stock')->count(),
            ];

            $routeName = 'products.index';

            $view = view('user.products.index', compact(
                'mode', 'accessories', 'accBrands', 'brands', 'models', 'fuelTypes', 'transmissions', 'bodyTypes', 'stats', 'routeName', 'accessoryCategories', 'fuelOptions', 'transOptions', 'bodyOptions'
            ));
            if ($request->ajax()) {
                return $view->render();
            }
            return $view;
        }

        if ($mode === 'all') {
            // Cars (variants)
            $variantQuery = CarVariant::with(['carModel.carBrand', 'images', 'specifications'])
                ->withCount(['approvedReviews as approved_reviews_count'])
                ->withAvg('approvedReviews as approved_reviews_avg', 'rating')
                ->where('is_active', 1);

            if ($request->filled('q')) {
                $keyword = trim($request->q);
                $variantQuery->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhereHas('carModel', function ($mq) use ($keyword) {
                          $mq->where('name', 'like', "%{$keyword}%");
                      })
                      ->orWhereHas('carModel.carBrand', function ($bq) use ($keyword) {
                          $bq->where('name', 'like', "%{$keyword}%");
                      });
                });
            }

            if ($request->filled('brand')) {
                $variantQuery->whereHas('carModel.carBrand', function ($q) use ($request) {
                    $q->where('id', $request->brand);
                });
            }
            if ($request->filled('model')) {
                $variantQuery->where('car_model_id', $request->model);
            }
            if ($request->filled('price_quick')) {
                $range = $request->price_quick;
                if (strpos($range, '-') !== false) {
                    [$min, $max] = explode('-', $range);
                    if ($min !== '') $variantQuery->where('price', '>=', (int) $min);
                    if ($max !== '') $variantQuery->where('price', '<=', (int) $max);
                }
            }
            if ($request->filled('price_min')) {
                $variantQuery->where('price', '>=', $request->price_min);
            }
            if ($request->filled('price_max')) {
                $variantQuery->where('price', '<=', $request->price_max);
            }
            if ($request->filled('fuel_type')) {
                $variantQuery->whereHas('specifications', function ($q) use ($request) {
                    $q->where('spec_name', 'fuel_type')->where('spec_value', $request->fuel_type);
                });
            }
            if ($request->filled('transmission')) {
                $variantQuery->whereHas('specifications', function ($q) use ($request) {
                    $q->where('spec_name', 'transmission')->where('spec_value', $request->transmission);
                });
            }
            if ($request->filled('body_type')) {
                $variantQuery->whereHas('specifications', function ($q) use ($request) {
                    $q->where('spec_name', 'body_type')->where('spec_value', $request->body_type);
                });
            }

            $sortBy = $request->get('sort', 'name');
            $sortOrder = $request->get('order', 'asc');
            switch ($sortBy) {
                case 'price':
                    $variantQuery->orderBy('price', $sortOrder);
                    break;
                case 'name':
                default:
                    $variantQuery->orderBy('name', $sortOrder);
                    break;
            }

            $perPage = 12;
            $variants = $variantQuery->paginate($perPage, ['*'], 'page');

            // Accessories
            $accQuery = Accessory::query()->where('is_active', 1);

            if ($request->filled('q')) {
                $keyword = trim($request->q);
                $accQuery->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhere('brand', 'like', "%{$keyword}%");
                });
            }
            if ($request->filled('acc_brand')) {
                $accQuery->where('brand', $request->acc_brand);
            }
            if ($request->filled('acc_category')) {
                $accQuery->where('category', $request->acc_category);
            }
            if ($request->filled('stock_status')) {
                $accQuery->where('stock_status', $request->stock_status);
            }
            if ($request->filled('price_quick')) {
                $range = $request->price_quick;
                if (strpos($range, '-') !== false) {
                    [$min, $max] = explode('-', $range);
                    if ($min !== '') $accQuery->where('price', '>=', (int) $min);
                    if ($max !== '') $accQuery->where('price', '<=', (int) $max);
                }
            }
            if ($request->filled('price_min')) {
                $accQuery->where('price', '>=', $request->price_min);
            }
            if ($request->filled('price_max')) {
                $accQuery->where('price', '<=', $request->price_max);
            }

            // Reuse sort for accessories (by name/price)
            switch ($sortBy) {
                case 'price':
                    $accQuery->orderBy('price', $sortOrder);
                    break;
                case 'name':
                    $accQuery->orderBy('name', $sortOrder);
                    break;
                default:
                    $accQuery->orderBy('name', $sortOrder);
            }

            // In "all" mode: paginate by car pages only, and show accessories ONLY on the last car page
            $carsTotal = (clone $variantQuery)->toBase()->getCountForPagination();
            $lastPage = max(1, (int) ceil($carsTotal / $perPage));
            $currentPage = max(1, (int) $request->get('page', 1));

            if ($currentPage < $lastPage) {
                $accessories = collect();
            } else {
                // On last car page, compute remaining slots and fill with accessories
                $carsBefore = ($lastPage - 1) * $perPage;
                $carsOnLast = max(0, $carsTotal - $carsBefore);
                $slotsLeft = max(0, $perPage - $carsOnLast);
                $accessories = $slotsLeft > 0 ? (clone $accQuery)
                    ->withCount(['reviews as approved_reviews_count' => function($q){ $q->where('is_approved', true); }])
                    ->withAvg(['reviews as approved_reviews_avg' => function($q){ $q->where('is_approved', true); }], 'rating')
                    ->take($slotsLeft)->get() : collect();
            }

            $stats = [
                'total_variants' => CarVariant::where('is_active', 1)->count(),
                'in_stock' => CarVariant::where('is_active', 1)->where('is_available', 1)->count(),
                'acc_total' => Accessory::where('is_active', 1)->count(),
                'acc_in_stock' => Accessory::where('is_active', 1)->where('stock_status', 'in_stock')->count(),
            ];

            $routeName = 'products.index';

            $view = view('user.products.index', compact(
                'mode', 'variants', 'accessories', 'brands', 'models', 'fuelTypes', 'transmissions', 'bodyTypes', 'stats', 'routeName', 'accessoryCategories', 'fuelOptions', 'transOptions', 'bodyOptions'
            ));
            if ($request->ajax()) {
                return $view->render();
            }
            return $view;
        }

        // Variants mode (cars)
        $query = CarVariant::with(['carModel.carBrand', 'images', 'specifications'])
            ->withCount(['approvedReviews as approved_reviews_count'])
            ->withAvg('approvedReviews as approved_reviews_avg', 'rating')
            ->where('is_active', 1);

        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhereHas('carModel', function ($mq) use ($keyword) {
                      $mq->where('name', 'like', "%{$keyword}%");
                  })
                  ->orWhereHas('carModel.carBrand', function ($bq) use ($keyword) {
                      $bq->where('name', 'like', "%{$keyword}%");
                  });
            });
        }

        if ($request->filled('brand')) {
            $query->whereHas('carModel.carBrand', function ($q) use ($request) {
                $q->where('id', $request->brand);
            });
        }
        if ($request->filled('model')) {
            $query->where('car_model_id', $request->model);
        }
        if ($request->filled('price_quick')) {
            $range = $request->price_quick;
            if (strpos($range, '-') !== false) {
                [$min, $max] = explode('-', $range);
                if ($min !== '') $query->where('price', '>=', (int) $min);
                if ($max !== '') $query->where('price', '<=', (int) $max);
            }
        }
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }
        if ($request->filled('fuel_type')) {
            $val = trim((string) $request->fuel_type);
            $query->where(function($q) use ($val){
                $q->where('fuel_type', $val)
                  ->orWhereHas('specifications', function ($sq) use ($val) {
                      $sq->where('spec_name', 'fuel_type')->where('spec_value', $val);
                  });
            });
        }
        if ($request->filled('transmission')) {
            $val = trim((string) $request->transmission);
            $query->where(function($q) use ($val){
                $q->where('transmission', $val)
                  ->orWhereHas('specifications', function ($sq) use ($val) {
                      $sq->where('spec_name', 'transmission')->where('spec_value', $val);
                  });
            });
        }
        if ($request->filled('body_type')) {
            $query->whereHas('carModel', function ($q) use ($request) {
                $q->where('body_type', $request->body_type);
            });
        }

        $sortBy = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');
        switch ($sortBy) {
            case 'price':
                $query->orderBy('price', $sortOrder);
                break;
            case 'name':
            default:
                $query->orderBy('name', $sortOrder);
                break;
        }

        $variants = $query->paginate(12);

        $stats = [
            'total_variants' => CarVariant::where('is_active', 1)->count(),
            'in_stock' => CarVariant::where('is_active', 1)->where('is_available', 1)->count(),
            'acc_total' => Accessory::where('is_active', 1)->count(),
            'acc_in_stock' => Accessory::where('is_active', 1)->where('stock_status', 'in_stock')->count(),
        ];

        $routeName = 'products.index';

        $view = view('user.products.index', compact(
            'mode', 'variants', 'brands', 'models', 'fuelTypes', 'transmissions', 'bodyTypes', 'stats', 'routeName', 'accessoryCategories', 'fuelOptions', 'transOptions', 'bodyOptions'
        ));
        if ($request->ajax()) {
            return $view->render();
        }
        return $view;
    }
}


