<?php

namespace App\Services\Payments\Providers;

use App\Services\Payments\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class MockGateway implements PaymentGateway
{
    public function createPayment(array $orderData): RedirectResponse
    {
        // Determine which gateway to mock based on session or request
        $gateway = session('selected_payment_method', 'momo');
        
        // Store order data in session for return processing
        session(['pending_order_data' => $orderData]);
        
        if ($gateway === 'vnpay') {
            // Mock VNPay success
            return redirect()->route('payment.vnpay.return', [
                'vnp_TxnRef' => 'MOCK-VNPAY-' . date('YmdHis'),
                'vnp_ResponseCode' => '00',
                'vnp_TransactionStatus' => '00',
                'vnp_Amount' => $orderData['grand_total'] * 100,
                'vnp_CreateDate' => date('YmdHis'),
                'vnp_ExpireDate' => date('YmdHis', strtotime('+15 minutes')),
            ]);
        } else {
            // Mock MoMo success
            return redirect()->route('payment.momo.return', [
                'orderId' => 'MOCK-MOMO-' . date('YmdHis'),
                'resultCode' => '0',
                'amount' => $orderData['grand_total'],
                'orderInfo' => 'Thanh toan don hang ' . 'MOCK-MOMO-' . date('YmdHis'),
                'orderType' => 'other',
                'transId' => 'MOCK-TRANS-' . date('YmdHis'),
                'responseTime' => time() * 1000,
            ]);
        }
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


