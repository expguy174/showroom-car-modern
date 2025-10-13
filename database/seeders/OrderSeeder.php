<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\CarVariant;
use App\Models\Accessory;
use App\Models\PaymentMethod;
use App\Models\FinanceOption;
use App\Models\Promotion;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role','user')->get();
        $variants = CarVariant::all();
        $accessories = Accessory::all();
        $paymentMethods = PaymentMethod::all();
        $financeOptions = FinanceOption::where('is_active', true)->get();
        $promotions = Promotion::where('is_active', true)->get();

        if ($users->isEmpty() || $variants->isEmpty() || $paymentMethods->isEmpty()) return;

        $orderCount = 220;
        for ($i = 1; $i <= $orderCount; $i++) {
            $user = $users->random();
            $pm = $paymentMethods->random();
            
            // Random status
            $statusRand = rand(1,100);
            if($statusRand<=10) $status = 'pending';
            elseif($statusRand<=40) $status = 'confirmed';
            elseif($statusRand<=70) $status = 'shipping';
            elseif($statusRand<=90) $status = 'delivered';
            else $status = 'cancelled';
            
            // Random payment status
            $paymentRand = rand(1,100);
            if($paymentRand<=60) $paymentStatus = 'completed';
            elseif($paymentRand<=90) $paymentStatus = 'pending';
            else $paymentStatus = 'failed';
            
            // Random shipping method
            $shippingMethod = rand(0,1) ? 'standard' : 'express';
            $shippingFee = $shippingMethod == 'standard' ? 30000 : 50000;
            
            // Random finance option (30% trả góp)
            $isInstallment = rand(1,100) <= 30;
            $financeOption = null;
            $downPayment = null;
            $tenureMonths = null;
            $monthlyPayment = null;
            
            if ($isInstallment && $financeOptions->isNotEmpty()) {
                $financeOption = $financeOptions->random();
                $tenureMonths = [12, 24, 36, 48][rand(0,3)];
            }
            
            // Get addresses
            $userAddress = $user->addresses()->first();
            $billingAddressId = $userAddress->id ?? null;
            $shippingAddressId = $userAddress->id ?? null;
            
            // Random promotion (20% có promotion)
            $promotionId = (rand(1,100) <= 20 && $promotions->isNotEmpty()) ? $promotions->random()->id : null;
            
            $order = Order::create([
                'order_number' => 'ORD-' . date('Ymd') . '-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'user_id' => $user->id,
                'total_price' => 0,
                'subtotal' => 0,
                'discount_total' => 0,
                'tax_total' => 0,
                'shipping_fee' => $shippingFee,
                'grand_total' => 0,
                'note' => $isInstallment ? 'Đơn hàng trả góp #' . $i : 'Đơn hàng thanh toán 1 lần #' . $i,
                'payment_method_id' => $pm->id,
                'finance_option_id' => $financeOption?->id,
                'down_payment_amount' => $downPayment,
                'tenure_months' => $tenureMonths,
                'monthly_payment_amount' => $monthlyPayment,
                'payment_status' => $paymentStatus,
                'status' => $status,
                'billing_address_id' => $billingAddressId,
                'shipping_address_id' => $shippingAddressId,
                'transaction_id' => $paymentStatus == 'completed' ? 'TXN-' . strtoupper(uniqid()) : null,
                'paid_at' => $paymentStatus == 'completed' ? Carbon::now()->subDays(rand(1, 30)) : null,
                'tracking_number' => in_array($status, ['shipping', 'delivered']) ? 'TRACK-' . strtoupper(uniqid()) : null,
                'estimated_delivery' => in_array($status, ['confirmed', 'shipping']) ? Carbon::now()->addDays(rand(3, 7)) : null,
                'promotion_id' => $promotionId,
                'shipping_method' => $shippingMethod,
                'tax_rate' => 0.1000,
            ]);

            $numItems = rand(1, 3);
            $subtotal = 0;
            for ($j = 0; $j < $numItems; $j++) {
                if (rand(0,1) || $accessories->isEmpty()) {
                    $variant = $variants->random();
                    
                    // Get random ACTIVE color for variant
                    $variantColor = $variant->colors()
                        ->where('is_active', true)
                        ->inRandomOrder()
                        ->first();
                    $colorId = $variantColor?->id;
                    $colorPrice = $variantColor?->price_adjustment ?? 0;
                    
                    // Get random OPTIONAL features with price > 0 (50% chance to add features for car variants)
                    $selectedFeatures = [];
                    $featuresPrice = 0;
                    if (rand(1,100) <= 50) { // Increased to 50% for better testing
                        $availableFeatures = $variant->featuresRelation()
                            ->where('availability', 'optional')
                            ->where('is_active', true)
                            ->where('price', '>', 0)
                            ->inRandomOrder()
                            ->limit(rand(1,3))
                            ->get();
                        
                        if ($availableFeatures->isNotEmpty()) {
                            foreach ($availableFeatures as $feature) {
                                $selectedFeatures[] = [
                                    'id' => $feature->id,
                                    'name' => $feature->feature_name,
                                    'category' => $feature->category,
                                    'price' => $feature->price,
                                ];
                                $featuresPrice += $feature->price;
                            }
                        }
                    }
                    
                    // Calculate final price
                    $basePrice = $variant->current_price;
                    $finalPrice = $basePrice + $colorPrice + $featuresPrice;
                    
                    // Build metadata
                    $metadata = [
                        'color' => $variantColor ? [
                            'id' => $variantColor->id,
                            'name' => $variantColor->color_name,
                            'hex' => $variantColor->hex_code,
                            'price_adjustment' => $colorPrice,
                        ] : null,
                        'features' => $selectedFeatures,
                        'base_price' => $basePrice,
                        'color_price' => $colorPrice,
                        'features_price' => $featuresPrice,
                        'final_price' => $finalPrice,
                    ];
                    
                    $line = [
                        'item_type' => 'car_variant',
                        'item_id' => $variant->id,
                        'color_id' => $colorId,
                        'item_name' => $variant->name . ($variantColor ? ' - ' . $variantColor->color_name : ''),
                        'item_sku' => $variant->sku,
                        'item_metadata' => json_encode($metadata),
                        'quantity' => 1,
                        'price' => $finalPrice,
                        'tax_amount' => 0,
                        'discount_amount' => 0,
                        'line_total' => $finalPrice,
                    ];
                } else {
                    $acc = $accessories->random();
                    $price = $acc->current_price;
                    $quantity = rand(1,2);
                    
                    $metadata = [
                        'base_price' => $price,
                        'quantity' => $quantity,
                    ];
                    
                    $line = [
                        'item_type' => 'accessory',
                        'item_id' => $acc->id,
                        'color_id' => null,
                        'item_name' => $acc->name,
                        'item_sku' => $acc->sku,
                        'item_metadata' => json_encode($metadata),
                        'quantity' => $quantity,
                        'price' => $price,
                        'tax_amount' => 0,
                        'discount_amount' => 0,
                        'line_total' => $price * $quantity,
                    ];
                }
                $subtotal += $line['line_total'];
                OrderItem::create(array_merge($line, ['order_id' => $order->id]));
            }

            // Calculate totals
            $discountTotal = $promotionId ? round($subtotal * 0.05, 2) : 0; // 5% discount if has promotion
            $taxTotal = round($subtotal * 0.1, 2); // 10% tax
            $totalPrice = $subtotal - $discountTotal + $taxTotal + $shippingFee;
            
            // Calculate finance amounts if installment
            if ($isInstallment && $financeOption) {
                $downPaymentPercent = $financeOption->down_payment_percent / 100;
                $downPayment = round($totalPrice * $downPaymentPercent, 2);
                $remainingAmount = $totalPrice - $downPayment;
                $monthlyPayment = round($remainingAmount / $tenureMonths, 2);
            }
            
            $order->update([
                'subtotal' => $subtotal,
                'discount_total' => $discountTotal,
                'tax_total' => $taxTotal,
                'total_price' => $totalPrice,
                'grand_total' => $totalPrice,
                'down_payment_amount' => $downPayment,
                'monthly_payment_amount' => $monthlyPayment,
            ]);
        }
    }
}


