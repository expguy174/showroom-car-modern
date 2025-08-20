<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\PaymentTransaction;

class PaymentController extends Controller
{
    // Initiate MoMo (demo placeholder)
    public function momoProcess(Request $request)
    {
        // Expect ?order_id= or build from latest order in session
        $orderId = $request->get('order_id');
        if (!$orderId) return redirect()->route('home')->with('error', 'Thiếu mã đơn hàng để thanh toán MoMo');
        $order = Order::find($orderId);
        if (!$order) return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng');

        // Normally: build MoMo payUrl with partner code, access key, secret, redirectUrl, ipnUrl, amount
        // For now, redirect to return URL to simulate success pending verification
        return redirect()->route('payment.momo.return', ['orderId' => $order->order_number]);
    }
    // VNPAY return URL (GET)
    public function vnpayReturn(Request $request)
    {
        // Display pending status; verification should be done via webhook
        $orderNumber = $request->get('vnp_TxnRef');
        $order = Order::where('order_number', $orderNumber)->first();
        if (!$order) {
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng');
        }
        return redirect()->route('order.success', ['order' => $order->id])
            ->with('success', 'Đang xác nhận thanh toán từ VNPAY...');
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
                    $pt = new PaymentTransaction([ 'order_id' => $order->id, 'user_id' => $order->user_id ]);
                }
                $pt->gateway_name = 'vnpay';
                $pt->gateway_transaction_id = $txnId;
                $pt->amount = $pt->amount ?: ($amount ?? 0);
                $pt->status = ($code === '00') ? 'succeeded' : 'failed';
                $pt->gateway_response = $payload;
                $pt->save();

                if ($pt->status === 'succeeded') {
                    $order->payment_status = 'paid';
                    $order->status = $order->status === 'pending' ? 'processing' : $order->status;
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
        $orderId = $request->get('orderId');
        // MoMo often sends partnerRefId or orderId; adapt as needed
        $order = Order::where('order_number', $orderId)->first();
        if (!$order) {
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng');
        }
        return redirect()->route('order.success', ['order' => $order->id])
            ->with('success', 'Đang xác nhận thanh toán từ MoMo...');
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
                if (!$pt) { $pt = new PaymentTransaction([ 'order_id' => $order->id, 'user_id' => $order->user_id ]); }
                $pt->gateway_name = 'momo';
                $pt->gateway_transaction_id = $txnId;
                $pt->amount = $pt->amount ?: ($amount ?? 0);
                $pt->status = ($status == 0) ? 'succeeded' : 'failed';
                $pt->gateway_response = $payload;
                $pt->save();

                if ($pt->status === 'succeeded') {
                    $order->payment_status = 'paid';
                    $order->status = $order->status === 'pending' ? 'processing' : $order->status;
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
}


