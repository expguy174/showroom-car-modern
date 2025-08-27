<?php

namespace App\Observers;

use App\Models\CarVariantColor;

class CarVariantColorObserver
{
    public function created(CarVariantColor $color): void
    {
        $variant = $color->variant;
        if ($variant && method_exists($variant, 'recalculateStockQuantity')) {
            $variant->recalculateStockQuantity();
        }
    }

    public function updated(CarVariantColor $color): void
    {
        $variant = $color->variant;
        if ($variant && method_exists($variant, 'recalculateStockQuantity')) {
            $variant->recalculateStockQuantity();
        }
    }

    public function deleted(CarVariantColor $color): void
    {
        $variant = $color->variant;
        if ($variant && method_exists($variant, 'recalculateStockQuantity')) {
            $variant->recalculateStockQuantity();
        }
    }

    public function restored(CarVariantColor $color): void
    {
        $variant = $color->variant;
        if ($variant && method_exists($variant, 'recalculateStockQuantity')) {
            $variant->recalculateStockQuantity();
        }
    }
}


