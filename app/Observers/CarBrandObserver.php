<?php

namespace App\Observers;

use App\Models\CarBrand;
use Illuminate\Support\Facades\Log;

class CarBrandObserver
{
    /**
     * Handle the CarBrand "created" event.
     */
    public function created(CarBrand $carBrand): void
    {
        // Không cần làm gì khi tạo mới vì chưa có models
    }

    /**
     * Handle the CarBrand "updated" event.
     */
    public function updated(CarBrand $carBrand): void
    {
        // Cập nhật thống kê khi brand được cập nhật
        $this->updateBrandStatistics($carBrand);
    }

    /**
     * Handle the CarBrand "deleted" event.
     */
    public function deleted(CarBrand $carBrand): void
    {
        // Không cần làm gì khi xóa
    }

    /**
     * Handle the CarBrand "restored" event.
     */
    public function restored(CarBrand $carBrand): void
    {
        // Cập nhật thống kê khi brand được khôi phục
        $this->updateBrandStatistics($carBrand);
    }

    /**
     * Handle the CarBrand "force deleted" event.
     */
    public function forceDeleted(CarBrand $carBrand): void
    {
        // Không cần làm gì khi force delete
    }

    /**
     * Cập nhật thống kê cho brand
     */
    private function updateBrandStatistics(CarBrand $brand)
    {
        try {
            $totalModels = $brand->carModels()
                ->where('is_active', 1)
                ->whereHas('carVariants', function($q){ $q->where('is_active', 1); })
                ->count();

            $totalVariants = $brand->carModels()
                ->where('is_active', 1)
                ->whereHas('carVariants', function($q){ $q->where('is_active', 1); })
                ->withCount(['carVariants' => function($q){ $q->where('is_active', 1); }])
                ->get()->sum('car_variants_count');
            
            $brand->updateQuietly([
                'total_models' => $totalModels,
                'total_variants' => $totalVariants
            ]);
        } catch (\Exception $e) {
            // Log lỗi nhưng không làm crash ứng dụng
            Log::error('Error updating brand statistics: ' . $e->getMessage());
        }
    }
}
