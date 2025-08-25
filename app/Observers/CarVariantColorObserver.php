<?php

namespace App\Observers;

use App\Models\CarVariantColor;

class CarVariantColorObserver
{
    public function created(CarVariantColor $color): void
    {
        $color->variant?->recalculateStockQuantity();
    }

    public function updated(CarVariantColor $color): void
    {
        $color->variant?->recalculateStockQuantity();
    }

    public function deleted(CarVariantColor $color): void
    {
        $color->variant?->recalculateStockQuantity();
    }

    public function restored(CarVariantColor $color): void
    {
        $color->variant?->recalculateStockQuantity();
    }
}


