<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\CarVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BrandController extends Controller
{
    /**
     * Display a listing of all car brands
     */
    public function index()
    {
        try {
            $brands = CarBrand::withCount(['carModels' => function($q) {
                $q->where('is_active', 1)
                  ->whereHas('carVariants', function($q2){ $q2->where('is_active', 1); });
            }])
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();

            // Group brands by first letter for alphabetical navigation
            $groupedBrands = $brands->groupBy(function ($brand) {
                return strtoupper(substr($brand->name, 0, 1));
            });

            return view('user.car-brands.index', compact('brands', 'groupedBrands'));
        } catch (\Exception $e) {
            Log::error('Error in BrandController@index: ' . $e->getMessage());
            return view('user.car-brands.index', [
                'brands' => collect(),
                'groupedBrands' => collect()
            ])->with('error', 'Có lỗi xảy ra khi tải danh sách hãng xe.');
        }
    }

    /**
     * Display the specified car brand with its models
     */
    public function show($id)
    {
        try {
            // Validate ID parameter
            if (!is_numeric($id) || $id <= 0) {
                Log::warning('Invalid brand ID provided: ' . $id);
                abort(404, 'ID hãng xe không hợp lệ.');
            }
            
            Log::info('Looking for brand with ID: ' . $id);
            $brand = CarBrand::where('is_active', 1)->findOrFail($id);
            Log::info('Found brand: ' . $brand->name);
            
            // Đếm tổng số dòng xe của hãng (bao gồm cả model chưa có phiên bản)
            $totalModelsCount = $brand->carModels()->count();

            // Lấy danh sách models đang hoạt động và có ít nhất 1 variant đang hoạt động (kèm eager load variants active)
            $models = $brand->carModels()
                ->where('is_active', 1)
                ->whereHas('carVariants', function($q){ $q->where('is_active', 1); })
                ->with(['carVariants' => function ($q) {
                    $q->where('is_active', 1)
                      ->with(['images', 'reviews', 'carModel.carBrand']);
                }])
                ->get();

            // Get brand statistics (dựa trên danh sách models đã lọc)
            $stats = [
                'total_models' => $models->count(),
                'total_variants' => $models->sum(function ($model) {
                    return $model->carVariants ? $model->carVariants->count() : 0;
                }),
                'price_range' => $this->getPriceRange($id),
                'fuel_types' => $this->getFuelTypes($id),
            ];

            // Get featured variants from this brand
            $featuredVariants = CarVariant::with(['carModel.carBrand', 'images'])
                ->whereHas('carModel', function ($query) use ($id) {
                    $query->where('car_brand_id', $id);
                })
                ->where('is_featured', 1)
                ->where('is_active', 1)
                ->take(6)
                ->get();

            // Paginated variants of this brand for full grid
            $brandVariants = CarVariant::with(['carModel.carBrand', 'images'])
                ->whereHas('carModel', function ($query) use ($id) {
                    $query->where('car_brand_id', $id);
                })
                ->where('is_active', 1)
                ->orderBy('name')
                ->paginate(12);

            return view('user.car-brands.show', compact('brand', 'featuredVariants', 'models', 'stats', 'brandVariants', 'totalModelsCount'));
        } catch (\Exception $e) {
            Log::error('Error in BrandController@show: ' . $e->getMessage());
            abort(404, 'Không tìm thấy hãng xe này.');
        }
    }

    /**
     * Get price range for a brand
     */
    private function getPriceRange($brandId)
    {
        try {
            $variants = CarVariant::whereHas('carModel', function ($query) use ($brandId) {
                $query->where('car_brand_id', $brandId);
            })->where('is_active', 1);

            $minPrice = $variants->min('price');
            $maxPrice = $variants->max('price');

            return [
                'min' => $minPrice ?? 0,
                'max' => $maxPrice ?? 0,
                'formatted' => [
                    'min' => $minPrice ? number_format($minPrice) . ' VNĐ' : 'Liên hệ',
                    'max' => $maxPrice ? number_format($maxPrice) . ' VNĐ' : 'Liên hệ'
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error getting price range for brand ' . $brandId . ': ' . $e->getMessage());
            return [
                'min' => 0,
                'max' => 0,
                'formatted' => [
                    'min' => 'Liên hệ',
                    'max' => 'Liên hệ'
                ]
            ];
        }
    }

    /**
     * Get fuel types available for a brand
     */
    private function getFuelTypes($brandId)
    {
        try {
            $variantIds = CarVariant::whereHas('carModel', function ($query) use ($brandId) {
                $query->where('car_brand_id', $brandId);
            })
            ->where('is_active', 1)
            ->pluck('id');

            if ($variantIds->isEmpty()) {
                return collect();
            }

            return \App\Models\CarSpecification::where('spec_name', 'fuel_type')
                ->whereIn('car_variant_id', $variantIds)
                ->distinct()
                ->pluck('spec_value')
                ->values();
        } catch (\Exception $e) {
            Log::error('Error getting fuel types for brand ' . $brandId . ': ' . $e->getMessage());
            return collect();
        }
    }
}
