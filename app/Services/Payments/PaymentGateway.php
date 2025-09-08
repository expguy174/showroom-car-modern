<?php

namespace App\Services\Payments;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

interface PaymentGateway
{
    /**
     * Create/initiate payment and return redirect response (or JSON in debug).
     */
    public function createPayment(array $orderData): RedirectResponse;

    /**
     * Handle return URL.
     */
    public function handleReturn(Request $request);

    /**
     * Handle IPN/webhook.
     */
    public function handleIpn(Request $request);
}


