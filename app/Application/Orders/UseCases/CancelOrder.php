<?php

namespace App\Application\Orders\UseCases;

use App\Models\Order;
use App\Models\CarVariant;
use App\Models\Accessory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelOrder
{
    /**
     * Cancel order - Restore reserved stock back to available
     * Called when order is cancelled before payment
     */
    public function handle(Order $order): void
    {
        if (!in_array($order->status, ['pending', 'confirmed'])) {
            throw new \RuntimeException('Only pending or confirmed orders can be cancelled');
        }

        DB::transaction(function () use ($order) {
            foreach ($order->items as $orderItem) {
                if ($orderItem->item_type === 'car_variant') {
                    $this->restoreCarVariantStock($orderItem);
                } elseif ($orderItem->item_type === 'accessory') {
                    $this->restoreAccessoryStock($orderItem);
                }
            }

            // Update order status
            $order->update(['status' => 'cancelled']);

            Log::info('Order cancelled and stock restored', [
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);
        });
    }

    private function restoreCarVariantStock($orderItem): void
    {
        $variant = CarVariant::lockForUpdate()->find($orderItem->item_id);
        if (!$variant) {
            Log::warning('Variant not found for stock restoration', [
                'order_item_id' => $orderItem->id,
                'variant_id' => $orderItem->item_id
            ]);
            return;
        }

        $qty = (int) $orderItem->quantity;
        $colorId = $orderItem->color_id;
        $inventory = $variant->color_inventory ?? [];

        if (!is_array($inventory)) {
            return;
        }

        if ($colorId && isset($inventory[$colorId])) {
            // Decrease reserved, increase available
            $reserved = (int) ($inventory[$colorId]['reserved'] ?? 0);
            $quantity = (int) ($inventory[$colorId]['quantity'] ?? 0);
            
            $inventory[$colorId]['reserved'] = max(0, $reserved - $qty);
            $inventory[$colorId]['available'] = $quantity - $inventory[$colorId]['reserved'];
            
            $variant->color_inventory = $inventory;
            $variant->save();

            Log::info('Car variant stock restored', [
                'variant_id' => $variant->id,
                'color_id' => $colorId,
                'quantity_restored' => $qty,
                'old_reserved' => $reserved,
                'new_reserved' => $inventory[$colorId]['reserved'],
                'new_available' => $inventory[$colorId]['available']
            ]);
        } else {
            // No specific color - restore across colors that have reserved
            $remaining = $qty;
            foreach ($inventory as $cId => $colorData) {
                if ($remaining <= 0) break;
                
                $r = (int) ($colorData['reserved'] ?? 0);
                if ($r > 0) {
                    $toRestore = min($remaining, $r);
                    $q = (int) ($colorData['quantity'] ?? 0);
                    
                    $inventory[$cId]['reserved'] = max(0, $r - $toRestore);
                    $inventory[$cId]['available'] = $q - $inventory[$cId]['reserved'];
                    
                    $remaining -= $toRestore;
                }
            }
            
            $variant->color_inventory = $inventory;
            $variant->save();
        }
    }

    private function restoreAccessoryStock($orderItem): void
    {
        $accessory = Accessory::lockForUpdate()->find($orderItem->item_id);
        if (!$accessory) {
            Log::warning('Accessory not found for stock restoration', [
                'order_item_id' => $orderItem->id,
                'accessory_id' => $orderItem->item_id
            ]);
            return;
        }

        $qty = (int) $orderItem->quantity;
        $currentStock = (int) ($accessory->stock_quantity ?? 0);
        
        // Restore stock
        $newStock = $currentStock + $qty;
        $accessory->stock_quantity = $newStock;
        
        // Update stock status
        if ($newStock === 0) {
            $accessory->stock_status = 'out_of_stock';
        } elseif ($newStock <= 5) {
            $accessory->stock_status = 'low_stock';
        } else {
            $accessory->stock_status = 'in_stock';
        }
        
        $accessory->save();

        Log::info('Accessory stock restored', [
            'accessory_id' => $accessory->id,
            'quantity_restored' => $qty,
            'old_stock' => $currentStock,
            'new_stock' => $newStock,
            'new_status' => $accessory->stock_status
        ]);
    }
}
