<?php

namespace App\Services\Payments\Providers;

use App\Services\Payments\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class MockGateway implements PaymentGateway
{
    public function createPayment(array $orderData): RedirectResponse
    {
        // Simulate success by redirecting to return URL with success code
        return redirect()->route('payment.momo.return', [
            'orderId' => 'MOCK-' . date('YmdHis'),
            'resultCode' => '0',
        ]);
    }

    public function handleReturn(Request $request)
    {
        return null;
    }

    public function handleIpn(Request $request)
    {
        return response('OK');
    }
}


