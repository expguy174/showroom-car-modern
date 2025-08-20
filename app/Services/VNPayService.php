<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class VNPayService
{
    private $tmnCode;
    private $hashSecret;
    private $url;
    private $returnUrl;
    private $ipnUrl;

    public function __construct()
    {
        $this->tmnCode = config('services.vnpay.tmn_code');
        $this->hashSecret = config('services.vnpay.hash_secret');
        $this->url = config('services.vnpay.url');
        $this->returnUrl = config('services.vnpay.return_url');
        $this->ipnUrl = config('services.vnpay.ipn_url');
    }

    public function createPaymentUrl($orderId, $amount, $orderInfo, $locale = 'vn')
    {
        $vnpUrl = $this->url;
        $vnpReturnUrl = $this->returnUrl;
        $vnpTmnCode = $this->tmnCode;
        $vnpHashSecret = $this->hashSecret;

        $vnpTxnRef = $orderId;
        $vnpOrderInfo = $orderInfo;
        $vnpOrderType = 'billpayment';
        $vnpAmount = $amount * 100; // VNPay expects amount in VND (smallest unit)
        $vnpLocale = $locale;
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
        );

        if (!empty($this->ipnUrl)) {
            $inputData['vnp_IpnUrl'] = $this->ipnUrl;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnpUrl = $vnpUrl . "?" . $query;
        if (isset($vnpHashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnpHashSecret);
            $vnpUrl .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return $vnpUrl;
    }

    public function verifyPayment($inputData)
    {
        $vnpHashSecret = $this->hashSecret;
        $secureHash = $inputData['vnp_SecureHash'];

        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);

        ksort($inputData);
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $calculatedHash = hash_hmac('sha512', $hashData, $vnpHashSecret);

        return $calculatedHash === $secureHash;
    }

    public function getPaymentStatus($responseCode)
    {
        $statusMap = [
            '00' => 'success',
            '07' => 'pending',
            '09' => 'failed',
            '24' => 'cancelled',
        ];

        return $statusMap[$responseCode] ?? 'unknown';
    }
} 