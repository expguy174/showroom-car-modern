<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    protected $model = PaymentMethod::class;

    public function definition(): array
    {
        $name = $this->faker->randomElement(['Tiền mặt', 'Chuyển khoản', 'VNPAY', 'MoMo']);
        $codeMap = [
            'Tiền mặt' => 'cash',
            'Chuyển khoản' => 'bank_transfer',
            'VNPAY' => 'vnpay',
            'MoMo' => 'momo',
        ];
        $code = $codeMap[$name] ?? strtolower($this->faker->unique()->lexify('pm????'));

        return [
            'name' => $name,
            'code' => $code,
            'provider' => in_array($code, ['vnpay','momo']) ? strtoupper($code) : null,
            'type' => in_array($code, ['vnpay','momo']) ? 'online' : 'offline',
            'is_active' => true,
            'fee_flat' => 0,
            'fee_percent' => 0,
            'config' => null,
            'sort_order' => 0,
            'notes' => null,
        ];
    }
}


