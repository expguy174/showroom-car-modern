<?php

namespace App\Services\Payments\Providers;

use App\Services\Payments\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class VNPayGateway implements PaymentGateway
{
    public function createPayment(array $orderData): RedirectResponse
    {
        // Delegate to existing controller logic via route helper
        return app(\App\Http\Controllers\User\CartController::class)->processVNPayPayment($orderData);
    }

    public function handleReturn(Request $request)
    {
        return app(\App\Http\Controllers\PaymentController::class)->vnpayReturn($request);
    }

    public function handleIpn(Request $request)
    {
        return app(\App\Http\Controllers\PaymentController::class)->vnpayWebhook($request);
    }
}


