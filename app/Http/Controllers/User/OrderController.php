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
        $order->load(['items.item', 'items.color', 'paymentMethod', 'billingAddress', 'shippingAddress']);
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
        $order->status = 'cancelled';
        $order->save();

        // Log action
        try {
            OrderLog::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'action' => 'user_cancel',
                'message' => 'Khách hàng yêu cầu hủy đơn hàng',
                'details' => [
                    'from' => $oldStatus,
                    'to' => 'cancelled',
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
}


