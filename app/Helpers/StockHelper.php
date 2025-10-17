<?php

namespace App\Helpers;

class StockHelper
{
    /**
     * Get stock badge information based on quantity and type
     * 
     * @param int $stock Current stock quantity
     * @param string $type Product type: 'car_variant' or 'accessory'
     * @return array Badge configuration
     */
    public static function getStockBadge(int $stock, string $type = 'accessory'): array
    {
        // Out of stock
        if ($stock === 0) {
            return [
                'text' => 'Hết hàng',
                'class' => 'bg-red-100 text-red-800 border border-red-200',
                'icon' => '❌',
                'show_quantity' => false,
                'available' => false,
                'urgent' => false
            ];
        }
        
        if ($type === 'car_variant') {
            // Cars: Show specific quantity when ≤ 5
            if ($stock === 1) {
                return [
                    'text' => "Chỉ còn 1 xe cuối cùng!",
                    'class' => 'bg-orange-100 text-orange-800 border border-orange-200',
                    'icon' => '🔥',
                    'show_quantity' => true,
                    'available' => true,
                    'urgent' => true
                ];
            } elseif ($stock === 2) {
                return [
                    'text' => "Chỉ còn 2 xe cuối cùng!",
                    'class' => 'bg-orange-100 text-orange-800 border border-orange-200',
                    'icon' => '🔥',
                    'show_quantity' => true,
                    'available' => true,
                    'urgent' => true
                ];
            } elseif ($stock <= 5) {
                return [
                    'text' => "Còn {$stock} xe",
                    'class' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                    'icon' => '⚠️',
                    'show_quantity' => true,
                    'available' => true,
                    'urgent' => false
                ];
            } else {
                return [
                    'text' => 'Còn hàng',
                    'class' => 'bg-green-100 text-green-800 border border-green-200',
                    'icon' => '✅',
                    'show_quantity' => false,
                    'available' => true,
                    'urgent' => false
                ];
            }
        } else {
            // Accessories: Show specific quantity when ≤ 10
            if ($stock === 1) {
                return [
                    'text' => "Chỉ còn 1 sản phẩm",
                    'class' => 'bg-orange-100 text-orange-800 border border-orange-200',
                    'icon' => '🔥',
                    'show_quantity' => true,
                    'available' => true,
                    'urgent' => true
                ];
            } elseif ($stock <= 3) {
                return [
                    'text' => "Chỉ còn {$stock} sản phẩm",
                    'class' => 'bg-orange-100 text-orange-800 border border-orange-200',
                    'icon' => '⚠️',
                    'show_quantity' => true,
                    'available' => true,
                    'urgent' => true
                ];
            } elseif ($stock <= 10) {
                return [
                    'text' => "Còn {$stock} sản phẩm",
                    'class' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                    'icon' => '⚠️',
                    'show_quantity' => true,
                    'available' => true,
                    'urgent' => false
                ];
            } else {
                return [
                    'text' => 'Còn hàng',
                    'class' => 'bg-green-100 text-green-800 border border-green-200',
                    'icon' => '✅',
                    'show_quantity' => false,
                    'available' => true,
                    'urgent' => false
                ];
            }
        }
    }

    /**
     * Get stock info for car variant color
     * 
     * @param array|null $colorInventory JSON color_inventory from car_variant
     * @param int $colorId Selected color ID
     * @return array Stock information
     */
    public static function getCarColorStock($colorInventory, int $colorId): array
    {
        if (!is_array($colorInventory) || !isset($colorInventory[$colorId])) {
            return [
                'available' => 0,
                'quantity' => 0,
                'reserved' => 0,
                'badge' => self::getStockBadge(0, 'car_variant')
            ];
        }

        $colorData = $colorInventory[$colorId];
        $available = (int) ($colorData['available'] ?? $colorData['quantity'] ?? 0);
        $quantity = (int) ($colorData['quantity'] ?? 0);
        $reserved = (int) ($colorData['reserved'] ?? 0);

        return [
            'available' => $available,
            'quantity' => $quantity,
            'reserved' => $reserved,
            'badge' => self::getStockBadge($available, 'car_variant')
        ];
    }

    /**
     * Get total stock across all colors for a car variant
     * 
     * @param array|null $colorInventory JSON color_inventory from car_variant
     * @return int Total available stock
     */
    public static function getCarTotalStock($colorInventory): int
    {
        if (!is_array($colorInventory) || empty($colorInventory)) {
            return 0;
        }

        $total = 0;
        foreach ($colorInventory as $colorId => $colorData) {
            $available = (int) ($colorData['available'] ?? $colorData['quantity'] ?? 0);
            $total += $available;
        }

        return $total;
    }

    /**
     * Check if quantity is available
     * 
     * @param int $stock Current stock
     * @param int $requested Requested quantity
     * @return bool
     */
    public static function isAvailable(int $stock, int $requested = 1): bool
    {
        return $stock >= $requested;
    }

    /**
     * Get max quantity that can be ordered
     * 
     * @param int $stock Current stock
     * @param int $maxPerOrder Maximum per order limit (default 10)
     * @return int
     */
    public static function getMaxOrderQuantity(int $stock, int $maxPerOrder = 10): int
    {
        return min($stock, $maxPerOrder);
    }
}
