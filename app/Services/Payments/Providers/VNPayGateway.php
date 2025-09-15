<?php

namespace App\Services\Payments\Providers;

use App\Services\Payments\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class VNPayGateway implements PaymentGateway
{
    public function createPayment(array $orderData): RedirectResponse
    {
        $vnpUrl = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnpReturnUrl = route('payment.vnpay.return');
        $vnpTmnCode = trim((string) env('VNPAY_TMN_CODE', '2QXUI4J4'));
        $vnpHashSecret = trim((string) env('VNPAY_HASH_SECRET', 'RAOEXHYVSDDIIENYWSLDIIZALPXUTMQK'));
        
        // Generate temporary order number for VNPay: only alphanumeric (no special chars)
        $tempOrderNumber = 'TEMP' . date('YmdHis') . strtoupper(substr(bin2hex(random_bytes(6)), 0, 6));
        
        $vnpTxnRef = $tempOrderNumber;
        $vnpOrderInfo = 'Thanh toan don hang ' . $tempOrderNumber;
        $vnpOrderType = 'other';
        $vnpAmount = $orderData['grand_total'] * 100; // VNPay expects amount in cents
        $vnpLocale = 'vn';
        $vnpCurrCode = 'VND';
        $vnpIpAddr = request()->ip();
        
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnpTmnCode,
            "vnp_Amount" => $vnpAmount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => $vnpCurrCode,
            "vnp_IpAddr" => $vnpIpAddr,
            "vnp_Locale" => $vnpLocale,
            "vnp_OrderInfo" => $vnpOrderInfo,
            "vnp_OrderType" => $vnpOrderType,
            "vnp_ReturnUrl" => $vnpReturnUrl,
            "vnp_TxnRef" => $vnpTxnRef,
            "vnp_ExpireDate" => date('YmdHis', strtotime('+15 minutes')),
        );
        
        // Remove empty entries
        $inputData = array_filter($inputData, function($v){ return $v !== null && $v !== ''; });
        
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode((string)$value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode((string)$value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode((string)$value) . '&';
        }
        
        if (!empty($vnpHashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnpHashSecret);
            $vnpUrl = $vnpUrl . "?" . rtrim($query, '&') . '&vnp_SecureHash=' . $vnpSecureHash;
        } else {
            $vnpUrl = $vnpUrl . "?" . rtrim($query, '&');
        }
        
        // Store order data in session for later processing
        session(['vnpay_order_data' => $orderData]);
        session(['vnpay_temp_order_number' => $tempOrderNumber]);
        
        Log::info('VNPay payment created', [
            'temp_order_number' => $tempOrderNumber,
            'amount' => $vnpAmount,
            'return_url' => $vnpReturnUrl
        ]);
        
        return redirect($vnpUrl);
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


