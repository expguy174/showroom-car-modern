<?php

namespace App\Application\Orders\UseCases;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderLog;
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
                'payment_fee' => $payload['payment_fee'] ?? 0,
                'grand_total' => $payload['grand_total'] ?? ($orderTotal + ($payload['tax_total'] ?? 0) + ($payload['shipping_fee'] ?? 0) + ($payload['payment_fee'] ?? 0) - ($payload['discount_total'] ?? 0)),
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
                'promotion_id' => $payload['promotion_id'] ?? null,
                'shipping_method' => $payload['shipping_method'] ?? 'standard',
                'tax_rate' => $payload['tax_rate'] ?? 0.10,
            ]);

            // Reserve stock for each item (increase reserved, decrease available automatically)
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
                        // Reserve color-specific stock from color_inventory JSON
                        $inventory = $variant->color_inventory ?? [];
                        if (is_array($inventory) && isset($inventory[$colorId])) {
                            $quantity = (int) ($inventory[$colorId]['quantity'] ?? 0);
                            $reserved = (int) ($inventory[$colorId]['reserved'] ?? 0);
                            $available = $quantity - $reserved;
                            
                            if ($available < $qty) {
                                throw new \RuntimeException('Insufficient color stock');
                            }
                            
                            // Increase reserved (available auto-decreases since available = quantity - reserved)
                            $inventory[$colorId]['reserved'] = $reserved + $qty;
                            $inventory[$colorId]['available'] = max(0, $available - $qty);
                            $variant->color_inventory = $inventory;
                            $variant->save();
                            
                            Log::info('Stock reserved for car variant color', [
                                'variant_id' => $variant->id,
                                'color_id' => $colorId,
                                'quantity_reserved' => $qty,
                                'old_reserved' => $reserved,
                                'new_reserved' => $reserved + $qty,
                                'available_after' => $available - $qty
                            ]);
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
                        // Reserve from total variant stock (distribute across colors)
                        $inventory = $variant->color_inventory ?? [];
                        if (is_array($inventory) && !empty($inventory)) {
                            $totalAvailable = 0;
                            foreach ($inventory as $colorData) {
                                $q = (int) ($colorData['quantity'] ?? 0);
                                $r = (int) ($colorData['reserved'] ?? 0);
                                $totalAvailable += ($q - $r);
                            }
                            if ($totalAvailable < $qty) {
                                throw new \RuntimeException('Insufficient variant stock');
                            }
                            
                            // Distribute the quantity across available colors
                            $remaining = $qty;
                            foreach ($inventory as $cId => $colorData) {
                                if ($remaining <= 0) break;
                                $q = (int) ($colorData['quantity'] ?? 0);
                                $r = (int) ($colorData['reserved'] ?? 0);
                                $avail = $q - $r;
                                $toReserve = min($remaining, $avail);
                                if ($toReserve > 0) {
                                    $inventory[$cId]['reserved'] = $r + $toReserve;
                                    $inventory[$cId]['available'] = max(0, $avail - $toReserve);
                                    $remaining -= $toReserve;
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
                } elseif ($itemData['item_type'] === 'accessory') {
                    /** @var Accessory $accessory */
                    $accessory = Accessory::lockForUpdate()->find($itemData['item_id']);
                    if (!$accessory) {
                        throw new \RuntimeException('Accessory not found for stock update');
                    }
                    
                    $qty = (int) $itemData['quantity'];
                    $currentStock = (int) ($accessory->stock_quantity ?? 0);
                    
                    // Validate stock availability
                    if ($currentStock < $qty) {
                        throw new \RuntimeException("Không đủ hàng cho phụ kiện: {$accessory->name}. Còn lại: {$currentStock}, yêu cầu: {$qty}");
                    }
                    
                    // Decrease stock
                    $newStock = max(0, $currentStock - $qty);
                    $accessory->stock_quantity = $newStock;
                    
                    // Auto-update stock_status based on remaining quantity
                    if ($newStock === 0) {
                        $accessory->stock_status = 'out_of_stock';
                    } elseif ($newStock <= 5) {
                        $accessory->stock_status = 'low_stock';
                    } else {
                        $accessory->stock_status = 'in_stock';
                    }
                    
                    $accessory->save();
                    
                    // Log stock change for tracking
                    Log::info('Accessory stock decreased', [
                        'accessory_id' => $accessory->id,
                        'accessory_name' => $accessory->name,
                        'order_id' => $order->id ?? null,
                        'quantity_sold' => $qty,
                        'old_stock' => $currentStock,
                        'new_stock' => $newStock,
                        'new_status' => $accessory->stock_status
                    ]);
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
                // Dispatch domain event for side-effects (email/notifications) - this creates "order_created" log first
                event(new OrderCreated($order));
            } catch (\Throwable $e) {
                Log::warning('Failed to dispatch OrderCreated event', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }

            // Auto-create installment schedule for finance orders AFTER order created log
            if ($order->finance_option_id && $order->tenure_months && $order->monthly_payment_amount) {
                try {
                    $this->createInstallmentSchedule($order);
                } catch (\Throwable $e) {
                    Log::warning('Failed to create installment schedule', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                    ]);
                }
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

    /**
     * Create installment schedule for finance orders
     */
    private function createInstallmentSchedule($order): void
    {
        if (!$order->finance_option_id || !$order->tenure_months || !$order->monthly_payment_amount) {
            return;
        }

        $tenureMonths = $order->tenure_months;
        $monthlyAmount = $order->monthly_payment_amount;

        for ($i = 1; $i <= $tenureMonths; $i++) {
            \App\Models\Installment::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'finance_option_id' => $order->finance_option_id,
                'installment_number' => $i,
                'amount' => $monthlyAmount,
                'due_date' => now()->addMonths($i)->startOfMonth(),
                'status' => 'pending',
            ]);
        }

        // Log installment creation
        \App\Models\OrderLog::create([
            'order_id' => $order->id,
            'user_id' => \Illuminate\Support\Facades\Auth::id() ?? $order->user_id,
            'action' => 'installments_created',
            'message' => 'Tạo lịch trả góp tự động',
            'details' => [
                'tenure_months' => $tenureMonths,
                'monthly_amount' => $monthlyAmount,
                'total_installments' => $tenureMonths,
                'finance_option_id' => $order->finance_option_id,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}


