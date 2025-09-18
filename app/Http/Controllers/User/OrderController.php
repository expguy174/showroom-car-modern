<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderLog;
use App\Services\NotificationService;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();
        $paymentStatus = $request->string('payment_status')->toString();
        $q = $request->string('q')->toString();

        $orders = Order::with(['items.item', 'shippingAddress', 'billingAddress', 'paymentMethod'])
            ->where('user_id', Auth::id())
            ->when($status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($paymentStatus !== '', function ($query) use ($paymentStatus) {
                $query->where('payment_status', $paymentStatus);
            })
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('order_number', 'like', "%{$q}%")
                        ->orWhere('tracking_number', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        // AJAX partial rendering for list updates
        if ($request->ajax()) {
            return response()->json([
                'html' => view('user.orders.partials.list', [ 'orders' => $orders ])->render(),
                'pagination' => view('components.pagination-modern', ['paginator' => $orders->withQueryString()])->render(),
                'summary' => view('user.orders.partials.summary', ['paginator' => $orders->withQueryString()])->render(),
            ]);
        }

        return view('user.orders.index', [
            'orders' => $orders,
            'status' => $status,
            'payment_status' => $paymentStatus,
            'q' => $q,
        ]);
    }

    public function show(Request $request, Order $order)
    {
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }
        $order->load([
            'items.item', 
            'items.color', 
            'paymentMethod', 
            'financeOption',
            'billingAddress', 
            'shippingAddress',
            'installments' => function($query) {
                $query->orderBy('installment_number');
            },
            'refunds' => function($query) {
                $query->orderBy('created_at', 'desc');
            }
        ]);
        return view('user.orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        // Placeholder: this keeps route compatibility; real checkout logic lives in CartController
        abort(404);
    }

    public function cancel(Request $request, Order $order)
    {
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }
        if (!in_array($order->status, ['pending', 'confirmed'])) {
            $message = 'Đơn hàng không thể hủy ở trạng thái hiện tại.';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return back()->with('status', $message);
        }
        if ($order->payment_status === 'completed') {
            $message = 'Đơn hàng đã thanh toán, vui lòng liên hệ hỗ trợ để hủy.';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return back()->with('status', $message);
        }

        $oldStatus = $order->status;
        $oldPaymentStatus = $order->payment_status;
        
        $order->status = 'cancelled';
        // Update payment status based on current status
        if ($order->payment_status === 'pending') {
            $order->payment_status = 'cancelled';
        } elseif ($order->payment_status === 'processing') {
            $order->payment_status = 'failed'; // Processing payments become failed when order is cancelled
        }
        // Note: completed payments remain completed (handled by validation above)
        
        $order->save();

        // Log action
        try {
            OrderLog::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'action' => 'user_cancel',
                'message' => 'Khách hàng yêu cầu hủy đơn hàng',
                'details' => [
                    'order_status' => ['from' => $oldStatus, 'to' => 'cancelled'],
                    'payment_status' => ['from' => $oldPaymentStatus, 'to' => $order->payment_status],
                ],
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
            ]);
        } catch (\Throwable $e) {}

        // Notify user
        try {
            app(NotificationService::class)->send(
                $order->user_id,
                'order_status',
                'Đơn hàng đã hủy',
                'Đơn hàng ' . ($order->order_number ?? ('#'.$order->id)) . ' đã được hủy theo yêu cầu của bạn.'
            );
        } catch (\Throwable $e) {}

        $message = 'Đã hủy đơn hàng thành công.';
        
        // Return JSON for AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }
        
        return redirect()->route('user.orders.index')->with('status', $message);
    }

    public function requestRefund(Request $request, Order $order)
    {
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        // Validate refund eligibility
        if ($order->payment_status !== 'completed') {
            $message = 'Chỉ có thể yêu cầu hoàn tiền cho đơn hàng đã thanh toán.';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return back()->with('error', $message);
        }

        if ($order->status === 'cancelled') {
            $message = 'Đơn hàng đã bị hủy, không thể yêu cầu hoàn tiền.';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return back()->with('error', $message);
        }

        // Check if refund already exists
        $existingRefund = \App\Models\Refund::whereHas('paymentTransaction', function($query) use ($order) {
            $query->where('order_id', $order->id);
        })->whereIn('status', ['pending', 'processing'])->first();

        if ($existingRefund) {
            $message = 'Đã có yêu cầu hoàn tiền đang được xử lý cho đơn hàng này.';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return back()->with('error', $message);
        }

        $request->validate([
            'reason' => 'required|string|max:1000',
            'amount' => 'nullable|numeric|min:0|max:' . $order->grand_total
        ]);

        // Get payment transaction for this order
        $paymentTransaction = \App\Models\PaymentTransaction::where('order_id', $order->id)
            ->where('status', 'completed')
            ->first();

        if (!$paymentTransaction) {
            $message = 'Không tìm thấy giao dịch thanh toán để hoàn tiền.';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return back()->with('error', $message);
        }

        // Create refund request
        $refund = \App\Models\Refund::create([
            'payment_transaction_id' => $paymentTransaction->id,
            'amount' => $request->amount ?: $order->grand_total,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        // Log action
        try {
            OrderLog::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'action' => 'refund_request',
                'message' => 'Khách hàng yêu cầu hoàn tiền',
                'details' => [
                    'refund_id' => $refund->id,
                    'amount' => $refund->amount,
                    'reason' => $refund->reason,
                ],
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
            ]);
        } catch (\Throwable $e) {}

        // Notify user
        try {
            app(NotificationService::class)->send(
                $order->user_id,
                'refund_request',
                'Yêu cầu hoàn tiền đã được gửi',
                'Yêu cầu hoàn tiền cho đơn hàng ' . ($order->order_number ?? ('#'.$order->id)) . ' đã được gửi và đang chờ xử lý.'
            );
        } catch (\Throwable $e) {}

        $message = 'Yêu cầu hoàn tiền đã được gửi thành công. Chúng tôi sẽ xử lý trong vòng 3-5 ngày làm việc.';
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }
        
        return back()->with('success', $message);
    }
}


