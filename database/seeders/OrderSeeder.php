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
                
                // Pick tenure within the finance option's min/max range
                $minTenure = (int) $financeOption->min_tenure;
                $maxTenure = (int) $financeOption->max_tenure;
                
                // Common tenure options: 6, 12, 18, 24, 36, 48, 60, 72
                $commonTenures = [6, 12, 18, 24, 36, 48, 60, 72];
                $validTenures = array_filter($commonTenures, function($t) use ($minTenure, $maxTenure) {
                    return $t >= $minTenure && $t <= $maxTenure;
                });
                
                // If no common tenure fits, pick a random value in range
                if (empty($validTenures)) {
                    $tenureMonths = rand($minTenure, $maxTenure);
                } else {
                    $tenureMonths = $validTenures[array_rand($validTenures)];
                }
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
        
        // Create specific example orders for testing tenure variations
        $this->createExampleInstallmentOrders($users, $variants, $paymentMethods, $financeOptions);
    }
    
    /**
     * Create example orders with specific tenure configurations
     */
    private function createExampleInstallmentOrders($users, $variants, $paymentMethods, $financeOptions)
    {
        if ($users->isEmpty() || $variants->isEmpty() || $paymentMethods->isEmpty() || $financeOptions->isEmpty()) {
            return;
        }
        
        // Example 1: Gói 12 tháng (min=6, max=12) với tenure = 6 tháng
        $finance12M = $financeOptions->where('code', 'FIN-12M')->first();
        if ($finance12M) {
            $this->createInstallmentOrder($users->random(), $variants->random(), $paymentMethods->random(), $finance12M, 6, 'ORD-' . date('Ymd') . '-EX01');
        }
        
        // Example 2: Gói 12 tháng với tenure = 12 tháng
        if ($finance12M) {
            $this->createInstallmentOrder($users->random(), $variants->random(), $paymentMethods->random(), $finance12M, 12, 'ORD-' . date('Ymd') . '-EX02');
        }
        
        // Example 3: Gói 36 tháng với tenure = 18 tháng
        $finance36M = $financeOptions->where('code', 'FIN-36M')->first();
        if ($finance36M) {
            $this->createInstallmentOrder($users->random(), $variants->random(), $paymentMethods->random(), $finance36M, 18, 'ORD-' . date('Ymd') . '-EX03');
        }
    }
    
    /**
     * Create a single installment order with specific configuration
     */
    private function createInstallmentOrder($user, $variant, $paymentMethod, $financeOption, $tenureMonths, $orderNumber)
    {
        $userAddress = $user->addresses()->first();
        $shippingFee = 30000;
        
        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $user->id,
            'total_price' => 0,
            'subtotal' => 0,
            'discount_total' => 0,
            'tax_total' => 0,
            'shipping_fee' => $shippingFee,
            'grand_total' => 0,
            'note' => "Đơn hàng mẫu: Gói {$financeOption->name}, trả trong {$tenureMonths} tháng",
            'payment_method_id' => $paymentMethod->id,
            'finance_option_id' => $financeOption->id,
            'tenure_months' => $tenureMonths,
            'payment_status' => 'pending',
            'status' => 'confirmed',
            'billing_address_id' => $userAddress?->id,
            'shipping_address_id' => $userAddress?->id,
            'shipping_method' => 'standard',
            'tax_rate' => 0.1000,
        ]);
        
        // Add order item
        $variantColor = $variant->colors()->where('is_active', true)->inRandomOrder()->first();
        $colorPrice = $variantColor?->price_adjustment ?? 0;
        $finalPrice = $variant->current_price + $colorPrice;
        
        $metadata = [
            'color' => $variantColor ? [
                'id' => $variantColor->id,
                'name' => $variantColor->color_name,
                'hex' => $variantColor->hex_code,
                'price_adjustment' => $colorPrice,
            ] : null,
            'base_price' => $variant->current_price,
            'color_price' => $colorPrice,
            'final_price' => $finalPrice,
        ];
        
        OrderItem::create([
            'order_id' => $order->id,
            'item_type' => 'car_variant',
            'item_id' => $variant->id,
            'color_id' => $variantColor?->id,
            'item_name' => $variant->name . ($variantColor ? ' - ' . $variantColor->color_name : ''),
            'item_sku' => $variant->sku,
            'item_metadata' => json_encode($metadata),
            'quantity' => 1,
            'price' => $finalPrice,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'line_total' => $finalPrice,
        ]);
        
        // Calculate totals
        $subtotal = $finalPrice;
        $taxTotal = round($subtotal * 0.1, 2);
        $totalPrice = $subtotal + $taxTotal + $shippingFee;
        
        // Calculate finance amounts
        $downPaymentPercent = $financeOption->min_down_payment / 100;
        $downPayment = round($totalPrice * $downPaymentPercent, 2);
        $remainingAmount = $totalPrice - $downPayment;
        $monthlyPayment = round($remainingAmount / $tenureMonths, 2);
        
        $order->update([
            'subtotal' => $subtotal,
            'tax_total' => $taxTotal,
            'total_price' => $totalPrice,
            'grand_total' => $totalPrice,
            'down_payment_amount' => $downPayment,
            'monthly_payment_amount' => $monthlyPayment,
        ]);
    }
}


