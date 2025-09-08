<?php

namespace App\Services\Payments;

use App\Services\Payments\Providers\VNPayGateway;
use App\Services\Payments\Providers\MoMoGateway;
use App\Services\Payments\Providers\MockGateway;

class PaymentGatewayFactory
{
    public static function make(string $methodCode): PaymentGateway
    {
        $mode = env('PAYMENT_GATEWAY_MODE', 'sandbox');
        if ($mode === 'mock') {
            return app(MockGateway::class);
        }
        return match ($methodCode) {
            'vnpay' => app(VNPayGateway::class),
            'momo'  => app(MoMoGateway::class),
            default => app(MockGateway::class),
        };
    }
}


