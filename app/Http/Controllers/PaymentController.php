<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Models\CartItem;

class PaymentController extends Controller
{
    /**
     * Verify VNPay return signature using provided secret.
     */
    private function verifyVnpaySignature(array $params, ?string $secret): bool
    {
        if (empty($secret)) return false;
        $received = $params['vnp_SecureHash'] ?? '';
        unset($params['vnp_SecureHash']);
        unset($params['vnp_SecureHashType']);
        ksort($params);
        $hashData = [];
        foreach ($params as $k => $v) {
            if (str_starts_with($k, 'vnp_')) {
                $hashData[] = urlencode($k) . '=' . urlencode((string) $v);
            }
        }
        $data = implode('&', $hashData);
        $calc = hash_hmac('sha512', $data, $secret);
        return hash_equals(strtolower($received), strtolower($calc));
    }

    /**
     * Verify MoMo return signature using provided secret.
     */
    private function verifyMoMoSignature(Request $request, string $orderId, string $resultCode, string $signature): bool
    {
        $accessKey = env('MOMO_ACCESS_KEY');
        $secretKey = env('MOMO_SECRET_KEY');
        
        if (!$accessKey || !$secretKey) {
            return true; // Skip verification if keys not available
        }
        
        $fields = [
            'accessKey'   => $accessKey,
            'amount'      => $request->get('amount'),
            'extraData'   => $request->get('extraData'),
            'message'     => $request->get('message'),
            'orderId'     => $orderId,
            'orderInfo'   => $request->get('orderInfo'),
            'orderType'   => $request->get('orderType'),
            'partnerCode' => $request->get('partnerCode'),
            'payType'     => $request->get('payType'),
            'requestId'   => $request->get('requestId'),
            'responseTime'=> $request->get('responseTime'),
            'resultCode'  => $resultCode,
            'transId'     => $request->get('transId'),
        ];
        
        $raw = [];
        foreach ($fields as $k => $v) { 
            if ($v !== null && $v !== '') $raw[] = $k.'='.$v; 
        }
        $rawStr = implode('&', $raw);
        $expectedSig = hash_hmac('sha256', $rawStr, $secretKey);
        
        return hash_equals($expectedSig, $signature);
    }
    // Initiate MoMo
    public function momoProcess(Request $request)
    {
        $mode = env('PAYMENT_GATEWAY_MODE', 'sandbox');
        // Mock mode: simulate success and return immediately
        if ($mode === 'mock') {
        $orderId = $request->get('order_id');
        if (!$orderId) return redirect()->route('home')->with('error', 'Thiếu mã đơn hàng để thanh toán MoMo');
            return redirect()->route('payment.momo.return', ['orderId' => $orderId, 'resultCode' => '0']);
        }

        // Sandbox/real: create MoMo payUrl
        $orderData = session('pending_order_data');
        if (!$orderData) {
            return redirect()->route('user.cart.checkout.form')->with('error', 'Phiên thanh toán đã hết hạn.');
        }

        $partnerCode = trim((string) env('MOMO_PARTNER_CODE', 'MOMO'));
        $accessKey   = trim((string) env('MOMO_ACCESS_KEY', ''));
        $secretKey   = trim((string) env('MOMO_SECRET_KEY', ''));
        $endpoint    = env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create');
        $redirectUrl = route('payment.momo.return');
        $ipnUrl      = route('payment.momo.webhook');
        $amount      = (int) ($orderData['grand_total'] ?? 0);

        $orderId = 'TEMP' . date('YmdHis');
        $requestId = 'REQ' . date('YmdHis') . rand(1000, 9999);
        $orderInfo = 'Thanh toan don hang ' . $orderId;
        $extraData = base64_encode(json_encode(['order_temp' => $orderId]));
        $requestType = 'captureWallet';
        $lang = 'vi';

        // Signature per MoMo v2
        $rawSignature = "accessKey={$accessKey}&amount={$amount}&extraData={$extraData}&ipnUrl={$ipnUrl}&orderId={$orderId}&orderInfo={$orderInfo}&partnerCode={$partnerCode}&redirectUrl={$redirectUrl}&requestId={$requestId}&requestType={$requestType}";
        $signature = hash_hmac('sha256', $rawSignature, $secretKey);

        $payload = [
            'partnerCode' => $partnerCode,
            'accessKey'   => $accessKey,
            'requestId'   => $requestId,
            'amount'      => $amount,
            'orderId'     => $orderId,
            'orderInfo'   => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl'      => $ipnUrl,
            'requestType' => $requestType,
            'extraData'   => $extraData,
            'lang'        => $lang,
            'signature'   => $signature,
        ];

        Log::info('MoMo create request', ['payload' => $payload]);
        try {
            $res = Http::withHeaders(['Content-Type' => 'application/json'])->post($endpoint, $payload);
            $json = $res->json();
            Log::info('MoMo create response', ['json' => $json]);
            if (!$res->successful() || empty($json['payUrl'])) {
                return redirect()->route('user.cart.checkout.form')->with('error', 'Không tạo được liên kết MoMo: ' . ($json['message'] ?? 'unknown'));
            }
            return redirect()->away($json['payUrl']);
        } catch (\Throwable $e) {
            Log::error('MoMo create error', ['error' => $e->getMessage()]);
            return redirect()->route('user.cart.checkout.form')->with('error', 'Không tạo được liên kết MoMo.');
        }
    }
    // VNPAY return URL (GET)
    public function vnpayReturn(Request $request)
    {
        // Log raw return data for debugging
        Log::info('VNPAY return hit', $request->all());

        $orderNumber = $request->get('vnp_TxnRef');
        $responseCode = $request->get('vnp_ResponseCode');
        $createDate   = $request->get('vnp_CreateDate');
        $expireDate   = $request->get('vnp_ExpireDate');

        // Skip signature verification in mock mode
        $mode = env('PAYMENT_GATEWAY_MODE', 'sandbox');
        $isSignatureValid = ($mode === 'mock') ? true : $this->verifyVnpaySignature($request->all(), env('VNPAY_HASH_SECRET'));
        Log::info('VNPAY return verification', [
            'order_ref' => $orderNumber,
            'response_code' => $responseCode,
            'vnp_CreateDate' => $createDate,
            'vnp_ExpireDate' => $expireDate,
            'signature_valid' => $isSignatureValid ? 'yes' : 'no',
        ]);

        // Optional on-screen debug without checking logs
        if ($request->boolean('debug')) {
            return response()->json([
                'gateway' => 'vnpay',
                'order_ref' => $orderNumber,
                'response_code' => $responseCode,
                'vnp_CreateDate' => $createDate,
                'vnp_ExpireDate' => $expireDate,
                'signature_valid' => $isSignatureValid,
                'all_params' => $request->all(),
            ]);
        }

        // Accept only when success code and signature valid
        if ($responseCode === '00' && $isSignatureValid) {
            $orderData = session('pending_order_data');
            if ($orderData) {
                $placeOrder = app(\App\Application\Orders\UseCases\PlaceOrder::class);
                $order = $placeOrder->handle($orderData);
                session()->forget('pending_order_data');
                
                // Clear cart after successful payment
                $this->clearCart($orderData['user_id']);
                
                return redirect()->route('user.order.success', ['order' => $order->id])
                    ->with('success', 'Thanh toán VNPay thành công!');
            }
            
            // In mock mode, show success even without session data
            if ($mode === 'mock') {
                return redirect()->route('user.cart.checkout')
                    ->with('success', 'Mock VNPay payment successful!');
            }
            
            Log::warning('VNPAY success but no pending_order_data in session', ['order_ref' => $orderNumber]);
        }

        // Provide clearer failure feedback via toast
        $message = $isSignatureValid
            ? 'Thanh toán VNPay thất bại hoặc đã hết hạn. Vui lòng thử lại.'
            : 'Xác minh chữ ký VNPay thất bại. Vui lòng thử lại.';
        return redirect()->route('user.cart.checkout.form')->with('error', $message);
    }

    // VNPAY webhook/IPN (POST)
    public function vnpayWebhook(Request $request)
    {
        try {
            $payload = $request->all();
            // TODO: verify signature with VNPAY secret
            $orderNumber = $payload['vnp_TxnRef'] ?? null;
            $txnId = $payload['vnp_TransactionNo'] ?? null;
            $amount = isset($payload['vnp_Amount']) ? ((int)$payload['vnp_Amount'])/100 : null;
            $code = $payload['vnp_ResponseCode'] ?? null;

            if (!$orderNumber) return response('Missing order ref', 400);
            $order = Order::where('order_number', $orderNumber)->first();
            if (!$order) return response('Order not found', 404);

            DB::transaction(function () use ($order, $txnId, $amount, $code, $payload) {
                /** @var PaymentTransaction|null $pt */
                $pt = $order->paymentTransactions()->latest('id')->first();
                if (!$pt) {
                    $pt = new PaymentTransaction([
                        'order_id' => $order->id,
                        'user_id' => $order->user_id,
                        'payment_method_id' => $order->payment_method_id,
                    ]);
                }
                $pt->transaction_number = $pt->transaction_number ?: ('VNPAY-' . ($txnId ?: uniqid()));
                $pt->amount = $pt->amount ?: (float) ($amount ?? 0);
                $pt->currency = $pt->currency ?: 'VND';
                $pt->status = ($code === '00') ? 'completed' : 'failed';
                $pt->payment_date = $pt->payment_date ?: now();
                $pt->notes = $pt->notes ?: 'VNPAY IPN';
                $pt->save();

                if ($pt->status === 'completed') {
                    $order->payment_status = 'completed';
                    $order->paid_at = $order->paid_at ?: now();
                    $order->transaction_id = $order->transaction_id ?: $pt->transaction_number;
                    $order->status = $order->status === 'pending' ? 'confirmed' : $order->status;
                } else {
                    $order->payment_status = 'failed';
                }
                $order->save();
            });
            return response('OK');
        } catch (\Throwable $e) {
            Log::error('VNPAY webhook error', ['error' => $e->getMessage()]);
            return response('ERR', 500);
        }
    }

    // MoMo return URL (GET)
    public function momoReturn(Request $request)
    {
        Log::info('MoMo return hit', $request->all());

        $orderId = (string) $request->get('orderId');
        $resultCode = (string) $request->get('resultCode');
        $signature = (string) $request->get('signature');

        // Skip signature verification in mock mode
        $mode = env('PAYMENT_GATEWAY_MODE', 'sandbox');
        $signatureValid = ($mode === 'mock') ? true : $this->verifyMoMoSignature($request, $orderId, $resultCode, $signature);
        Log::info('MoMo return verification', ['orderId'=>$orderId,'resultCode'=>$resultCode,'signature_valid'=>$signatureValid?'yes':'no']);

        if ($request->boolean('debug')) {
            return response()->json([
                'gateway' => 'momo',
                'order_id' => $orderId,
                'resultCode' => $resultCode,
                'signature_valid' => $signatureValid,
                'all_params' => $request->all(),
            ]);
        }

        if ($resultCode === '0' && $signatureValid) {
            $orderData = session('pending_order_data');
            if ($orderData) {
                $placeOrder = app(\App\Application\Orders\UseCases\PlaceOrder::class);
                $order = $placeOrder->handle($orderData);
                session()->forget('pending_order_data');
                
                // Clear cart after successful payment
                $this->clearCart($orderData['user_id']);
                
                return redirect()->route('user.order.success', ['order' => $order->id])
                    ->with('success', 'Thanh toán MoMo thành công!');
            }
            
            // In mock mode, show success even without session data
            if ($mode === 'mock') {
                return redirect()->route('user.cart.checkout')
                    ->with('success', 'Mock MoMo payment successful!');
            }
            
            Log::warning('MoMo success but no pending_order_data in session', ['orderId' => $orderId]);
        }

        return redirect()->route('user.cart.checkout.form')
            ->with('error', 'Thanh toán MoMo thất bại hoặc bị từ chối. Vui lòng thử lại.');
    }

    // MoMo webhook/IPN (POST)
    public function momoWebhook(Request $request)
    {
        try {
            $payload = $request->all();
            // TODO: verify signature with MoMo secret
            $orderNumber = $payload['orderId'] ?? ($payload['partnerRefId'] ?? null);
            $txnId = $payload['transId'] ?? null;
            $status = $payload['resultCode'] ?? null;
            $amount = $payload['amount'] ?? null;

            if (!$orderNumber) return response('Missing order ref', 400);
            $order = Order::where('order_number', $orderNumber)->first();
            if (!$order) return response('Order not found', 404);

            DB::transaction(function () use ($order, $txnId, $status, $amount, $payload) {
                $pt = $order->paymentTransactions()->latest('id')->first();
                if (!$pt) {
                    $pt = new PaymentTransaction([
                        'order_id' => $order->id,
                        'user_id' => $order->user_id,
                        'payment_method_id' => $order->payment_method_id,
                    ]);
                }
                $pt->transaction_number = $pt->transaction_number ?: ('MOMO-' . ($txnId ?: uniqid()));
                $pt->amount = $pt->amount ?: (float) ($amount ?? 0);
                $pt->currency = $pt->currency ?: 'VND';
                $pt->status = ((string) $status === '0') ? 'completed' : 'failed';
                $pt->payment_date = $pt->payment_date ?: now();
                $pt->notes = $pt->notes ?: 'MOMO IPN';
                $pt->save();

                if ($pt->status === 'completed') {
                    $order->payment_status = 'completed';
                    $order->paid_at = $order->paid_at ?: now();
                    $order->transaction_id = $order->transaction_id ?: $pt->transaction_number;
                    $order->status = $order->status === 'pending' ? 'confirmed' : $order->status;
                } else {
                    $order->payment_status = 'failed';
                }
                $order->save();
            });
            return response('OK');
        } catch (\Throwable $e) {
            Log::error('MoMo webhook error', ['error' => $e->getMessage()]);
            return response('ERR', 500);
        }
    }

    /**
     * Clear cart items for user after successful payment
     */
    private function clearCart($userId)
    {
        if (!$userId) return;
        
        CartItem::where('user_id', $userId)->delete();
        
        Log::info('Cart cleared after successful payment', ['user_id' => $userId]);
    }
}


