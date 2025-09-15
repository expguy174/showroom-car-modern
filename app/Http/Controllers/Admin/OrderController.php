<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Services\NotificationService;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status') && $request->payment_status !== '') {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('tracking_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('email', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->orderByDesc('created_at')->paginate(10);

        return view('admin.orders.index', [
            'orders' => $orders,
            'search' => $request->search,
            'status' => $request->status,
            'payment_status' => $request->payment_status,
        ]);
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items', 'paymentMethod'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::with(['items', 'paymentMethod'])->findOrFail($id);
        $users = User::all();
        $statuses = \App\Models\Order::STATUSES;
        return view('admin.orders.edit', compact('order', 'users', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'note' => 'nullable|string',
            'status' => 'required|in:' . implode(',', \App\Models\Order::STATUSES),
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $before = [
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'payment_method_id' => $order->payment_method_id,
            'tracking_number' => $order->tracking_number,
        ];

        // Only persist columns that actually exist on orders table
        $data = Arr::only($validated, ['user_id', 'note', 'status', 'payment_method_id', 'tracking_number']);
        $order->update($data);

        $after = [
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'payment_method_id' => $order->payment_method_id,
            'tracking_number' => $order->tracking_number,
        ];

        OrderLog::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'action' => 'order_updated',
            'details' => [
                'before' => $before,
                'after' => $after,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Send notification to user for status/track changes
        try {
            if ($order->user_id) {
                if ($before['status'] !== $after['status']) {
                    $title = match($order->status){
                        'confirmed' => 'Đơn hàng đã xác nhận',
                        'shipping' => 'Đơn hàng đang giao',
                        'delivered' => 'Đơn hàng đã giao',
                        'cancelled' => 'Đơn hàng đã hủy',
                        default => 'Cập nhật đơn hàng',
                    };
                    app(NotificationService::class)->send(
                        $order->user_id,
                        'order_status',
                        $title,
                        'Đơn ' . ($order->order_number ?? ('#'.$order->id)) . ' đã chuyển sang trạng thái ' . $order->status . '.'
                    );
                }
                if (($before['tracking_number'] ?? null) !== ($after['tracking_number'] ?? null) && $order->tracking_number){
                    app(NotificationService::class)->send(
                        $order->user_id,
                        'order_status',
                        'Cập nhật mã vận đơn',
                        'Đơn ' . ($order->order_number ?? ('#'.$order->id)) . ' có mã vận đơn mới: ' . $order->tracking_number . '.'
                    );
                }
            }
        } catch (\Throwable $e) {}

        return redirect('/admin/orders')->with('success', 'Cập nhật đơn hàng thành công!');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->items()->delete();
        $order->delete();

        return redirect('/admin/orders')->with('success', 'Đã xóa đơn hàng!');
    }

    public function nextStatus(Order $order)
    {
        $statusFlow = [
            'pending' => 'confirmed',
            'confirmed' => 'shipping',
            'shipping' => 'delivered',
        ];

        $currentStatus = $order->status;

        if (isset($statusFlow[$currentStatus])) {
            $newStatus = $statusFlow[$currentStatus];

            $order->update(['status' => $newStatus]);

            OrderLog::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'action' => 'status_changed',
                'details' => [
                    'from' => $currentStatus,
                    'to' => $newStatus,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return redirect()->back()->with('success', 'Chuyển trạng thái thành công.');
        }

        return redirect()->back()->with('error', 'Không thể chuyển trạng thái.');
    }

    public function cancel(Request $request, Order $order)
    {
        $oldStatus = $order->status;

        if ($oldStatus !== 'cancelled') {
            // Inventory restock removed as schema no longer tracks per-color or variant stock quantities
            $order->update(['status' => 'cancelled']);

            OrderLog::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'action' => 'order_cancelled',
                'details' => [
                    'from' => $oldStatus,
                    'to' => 'cancelled',
                    'reason' => $request->get('reason'),
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return redirect()->back()->with('success', 'Đơn hàng đã bị huỷ.');
        }

        return redirect()->back()->with('error', 'Đơn hàng đã huỷ trước đó.');
    }

    public function logs($orderId)
    {
        $order = Order::findOrFail($orderId);
        $logs = $order->logs()->with('user')->orderByDesc('created_at')->get();

        return view('admin.orders.logs', compact('order', 'logs'));
    }
}