<?php

namespace App\Application\Orders\UseCases;

use App\Models\Order;
use App\Models\CarVariant;
use App\Models\Accessory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConfirmOrder
{
    /**
     * Confirm order - Convert reserved stock to sold (decrease quantity)
     * Called when payment is completed
     */
    public function handle(Order $order): void
    {
        if ($order->status !== 'pending') {
            throw new \RuntimeException('Only pending orders can be confirmed');
        }

        DB::transaction(function () use ($order) {
            foreach ($order->items as $orderItem) {
                if ($orderItem->item_type === 'car_variant') {
                    $this->confirmCarVariantStock($orderItem);
                } elseif ($orderItem->item_type === 'accessory') {
                    // Accessory already decreased, no action needed
                    Log::info('Accessory stock already decreased', [
                        'order_id' => $order->id,
                        'accessory_id' => $orderItem->item_id,
                        'quantity' => $orderItem->quantity
                    ]);
                }
            }

            // Update order status
            $order->update(['status' => 'confirmed']);

            Log::info('Order confirmed and stock finalized', [
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);
        });
    }

    private function confirmCarVariantStock($orderItem): void
    {
        $variant = CarVariant::lockForUpdate()->find($orderItem->item_id);
        if (!$variant) {
            Log::warning('Variant not found for stock confirmation', [
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
            // Decrease quantity, decrease reserved
            $quantity = (int) ($inventory[$colorId]['quantity'] ?? 0);
            $reserved = (int) ($inventory[$colorId]['reserved'] ?? 0);
            
            $inventory[$colorId]['quantity'] = max(0, $quantity - $qty);
            $inventory[$colorId]['reserved'] = max(0, $reserved - $qty);
            $inventory[$colorId]['available'] = $inventory[$colorId]['quantity'] - $inventory[$colorId]['reserved'];
            
            $variant->color_inventory = $inventory;
            $variant->save();

            Log::info('Car variant stock confirmed', [
                'variant_id' => $variant->id,
                'color_id' => $colorId,
                'quantity_sold' => $qty,
                'old_quantity' => $quantity,
                'new_quantity' => $inventory[$colorId]['quantity'],
                'reserved_released' => $qty
            ]);
        } else {
            // No specific color - distribute across colors
            $remaining = $qty;
            foreach ($inventory as $cId => $colorData) {
                if ($remaining <= 0) break;
                
                $r = (int) ($colorData['reserved'] ?? 0);
                if ($r > 0) {
                    $toConfirm = min($remaining, $r);
                    $q = (int) ($colorData['quantity'] ?? 0);
                    
                    $inventory[$cId]['quantity'] = max(0, $q - $toConfirm);
                    $inventory[$cId]['reserved'] = max(0, $r - $toConfirm);
                    $inventory[$cId]['available'] = $inventory[$cId]['quantity'] - $inventory[$cId]['reserved'];
                    
                    $remaining -= $toConfirm;
                }
            }
            
            $variant->color_inventory = $inventory;
            $variant->save();
        }
    }
}
