<?php

namespace App\Application\Orders\UseCases;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CarVariant;
use App\Models\Accessory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use App\Events\OrderCreated;

class PlaceOrder
{
    /**
     * Create an order with items in a transaction and dispatch OrderCreated event.
     *
     * Expected $payload shape:
     * - user_id?: int|null
     * - name: string
     * - phone: string
     * - email?: string|null
     * - address?: string|null
     * - note?: string|null
     * - payment_method_id?: int|null
     * - items: array<array{
     *     item_type: 'car_variant'|'accessory',
     *     item_id: int,
     *     quantity: int,
     *     color_id?: int|null,
     *     price?: float|null
     * }>
     */
    public function handle(array $payload): Order
    {
        $items = $payload['items'] ?? [];
        if (empty($items)) {
            throw new \InvalidArgumentException('Order items must not be empty');
        }

        // Contact fields are not stored in orders table per latest schema

        // Fetch models and compute totals
        $resolvedItems = [];
        $orderTotal = 0;

        foreach ($items as $item) {
            $type = Arr::get($item, 'item_type');
            $itemId = Arr::get($item, 'item_id');
            $quantity = max(1, (int) Arr::get($item, 'quantity', 1));
            $colorId = Arr::get($item, 'color_id');

            if (!in_array($type, ['car_variant', 'accessory'], true)) {
                throw new \InvalidArgumentException('Unsupported item_type: ' . (string) $type);
            }

            $model = $type === 'car_variant'
                ? CarVariant::with(['carModel.carBrand'])->findOrFail($itemId)
                : Accessory::findOrFail($itemId);

            if (property_exists($model, 'is_active') && !$model->is_active) {
                throw new \RuntimeException('Item is not available');
            }

            $unitPrice = (float) (Arr::get($item, 'price', null));
            if ($unitPrice === null) {
                if ($type === 'car_variant' && method_exists($model, 'getPriceWithColorAdjustment')) {
                    $unitPrice = (float) $model->getPriceWithColorAdjustment($colorId);
                } else {
                    $unitPrice = (float) ($model->current_price ?? 0);
                }
            }
            $lineTotal = $unitPrice * $quantity;
            $orderTotal += $lineTotal;

            // Use provided metadata or build default metadata
            $itemMetadata = Arr::get($item, 'item_metadata');
            if (empty($itemMetadata)) {
                $itemMetadata = $this->buildItemMetadata($type, $model);
            }

            $resolvedItems[] = [
                'item_type' => $type,
                'item_id' => $model->id,
                'color_id' => $colorId,
                'item_name' => $model->name ?? 'Item',
                'item_sku' => $model->sku ?? null,
                'item_metadata' => $itemMetadata,
                'quantity' => $quantity,
                'price' => $unitPrice,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'line_total' => $lineTotal,
            ];
        }

        return DB::transaction(function () use ($payload, $orderTotal, $resolvedItems) {
            $order = Order::create([
                'user_id' => $payload['user_id'] ?? null,
                'total_price' => $orderTotal,
                'subtotal' => $payload['subtotal'] ?? $orderTotal,
                'discount_total' => $payload['discount_total'] ?? 0,
                'tax_total' => $payload['tax_total'] ?? 0,
                'shipping_fee' => $payload['shipping_fee'] ?? 0,
                'grand_total' => $payload['grand_total'] ?? ($orderTotal + ($payload['tax_total'] ?? 0) + ($payload['shipping_fee'] ?? 0) - ($payload['discount_total'] ?? 0)),
                'note' => $payload['note'] ?? null,
                'payment_method_id' => $payload['payment_method_id'] ?? null,
                'finance_option_id' => $payload['finance_option_id'] ?? null,
                'down_payment_amount' => $payload['down_payment_amount'] ?? null,
                'tenure_months' => $payload['tenure_months'] ?? null,
                'monthly_payment_amount' => $payload['monthly_payment_amount'] ?? null,
                'status' => 'pending',
                'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                'billing_address_id' => $payload['billing_address_id'] ?? null,
                'shipping_address_id' => $payload['shipping_address_id'] ?? null,
            ]);

            // Decrement stock for each item (color stock takes precedence)
            foreach ($resolvedItems as $itemData) {
                if ($itemData['item_type'] === 'car_variant') {
                    /** @var CarVariant $variant */
                    $variant = CarVariant::lockForUpdate()->find($itemData['item_id']);
                    if (!$variant) {
                        throw new \RuntimeException('Variant not found for stock update');
                    }
                    $qty = (int) $itemData['quantity'];
                    $colorId = $itemData['color_id'] ?? null;
                    
                    if ($colorId) {
                        // Check color-specific stock from color_inventory JSON
                        $inventory = $variant->color_inventory ?? [];
                        if (is_array($inventory) && isset($inventory[$colorId])) {
                            $available = (int) ($inventory[$colorId]['available'] ?? $inventory[$colorId]['quantity'] ?? 0);
                            if ($available < $qty) {
                                throw new \RuntimeException('Insufficient color stock');
                            }
                            
                            // Update the color_inventory JSON
                            $inventory[$colorId]['available'] = max(0, $available - $qty);
                            $variant->color_inventory = $inventory;
                            $variant->save();
                        } else {
                            // If no color inventory data, check if color exists and is active
                            $color = $variant->colors()->find($colorId);
                            if (!$color || !$color->is_active) {
                                throw new \RuntimeException('Color not available');
                            }
                            // For colors without inventory data, we'll allow the order but log a warning
                            Log::warning('Color inventory data missing for variant', [
                                'variant_id' => $variant->id,
                                'color_id' => $colorId
                            ]);
                        }
                    } else {
                        // Check total variant stock from color_inventory
                        $inventory = $variant->color_inventory ?? [];
                        if (is_array($inventory) && !empty($inventory)) {
                            $totalAvailable = 0;
                            foreach ($inventory as $colorData) {
                                $totalAvailable += (int) ($colorData['available'] ?? $colorData['quantity'] ?? 0);
                            }
                            if ($totalAvailable < $qty) {
                                throw new \RuntimeException('Insufficient variant stock');
                            }
                            
                            // Distribute the quantity across available colors
                            $remaining = $qty;
                            foreach ($inventory as $colorId => $colorData) {
                                if ($remaining <= 0) break;
                                $available = (int) ($colorData['available'] ?? $colorData['quantity'] ?? 0);
                                $toDeduct = min($remaining, $available);
                                if ($toDeduct > 0) {
                                    $inventory[$colorId]['available'] = max(0, $available - $toDeduct);
                                    $remaining -= $toDeduct;
                                }
                            }
                            $variant->color_inventory = $inventory;
                            $variant->save();
                        } else {
                            // If no inventory data, check variant availability
                            if (!$variant->is_available) {
                                throw new \RuntimeException('Variant not available');
                            }
                            // For variants without inventory data, we'll allow the order but log a warning
                            Log::warning('Variant inventory data missing', [
                                'variant_id' => $variant->id
                            ]);
                        }
                    }
                }
            }

            foreach ($resolvedItems as $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_type' => $itemData['item_type'],
                    'item_id' => $itemData['item_id'],
                    'color_id' => $itemData['color_id'] ?? null,
                    'item_name' => $itemData['item_name'],
                    'item_sku' => $itemData['item_sku'] ?? null,
                    'item_metadata' => json_encode($itemData['item_metadata'] ?? []),
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                    'tax_amount' => $itemData['tax_amount'] ?? 0,
                    'discount_amount' => $itemData['discount_amount'] ?? 0,
                    'line_total' => $itemData['line_total'],
                ]);
            }

            try {
                // Dispatch domain event for side-effects (email/notifications)
                event(new OrderCreated($order));
            } catch (\Throwable $e) {
                Log::warning('Failed to dispatch OrderCreated event', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }

            return $order;
        });
    }

    private function buildItemMetadata(string $type, $model): array
    {
        if ($type === 'car_variant') {
            return [
                'brand' => optional($model->carModel->carBrand ?? null)->name,
                'model' => optional($model->carModel ?? null)->name,
            ];
        }

        return [
            'category' => $model->category ?? null,
            'brand' => $model->brand ?? null,
        ];
    }
}


