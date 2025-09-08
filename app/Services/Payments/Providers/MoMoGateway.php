<?php

namespace App\Services\Payments\Providers;

use App\Services\Payments\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class MoMoGateway implements PaymentGateway
{
    public function createPayment(array $orderData): RedirectResponse
    {
        return app(\App\Http\Controllers\PaymentController::class)->momoProcess(request());
    }

    public function handleReturn(Request $request)
    {
        return app(\App\Http\Controllers\PaymentController::class)->momoReturn($request);
    }

    public function handleIpn(Request $request)
    {
        return app(\App\Http\Controllers\PaymentController::class)->momoWebhook($request);
    }
}


