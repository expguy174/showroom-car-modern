<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use App\Services\NotificationService;
use App\Traits\SendsOrderNotifications;

class OrderController extends Controller
{
    use SendsOrderNotifications;
    public function index(Request $request)
    {
        $query = Order::with(['user.userProfile', 'items']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                    ->orWhere('tracking_number', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('email', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('user.userProfile', function ($pq) use ($search) {
                        $pq->where('name', 'like', '%' . $search . '%')
                           ->orWhere('phone', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->orderByDesc('created_at')->paginate(10);

        // Calculate statistics (from all orders, not just current page)
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();

        // Handle AJAX requests
        if ($request->ajax()) {
            return view('admin.orders.partials.table', compact('orders'))->render();
        }

        return view('admin.orders.index', compact(
            'orders',
            'totalOrders',
            'pendingOrders', 
            'cancelledOrders',
            'deliveredOrders'
        ));
    }

    public function show(Request $request, $id)
    {
        $order = Order::with([
            'user.userProfile', 
            'items.item', 
            'items.color',
            'paymentMethod',
            'financeOption',
            'billingAddress',
            'shippingAddress',
            'promotion',
            'logs.user.userProfile',
            'paymentTransactions',
            'installments' => function($query) {
                $query->orderBy('installment_number');
            }
        ])->findOrFail($id);
        
        // Support loading all logs via AJAX
        if ($request->boolean('all_logs')) {
            $displayLogs = $order->logs()->with('user.userProfile')->orderByDesc('created_at')->get();
            return view('admin.orders.show', compact('order'));
        }
        
        return view('admin.orders.show', compact('order'));
    }

    // Edit page removed - all editing done inline on show page
    // Update moved to specialized methods: updateStatus, cancel, updateTracking

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        
        // Only allow deletion of cancelled orders for data cleanup
        if ($order->status !== 'cancelled') {
            return redirect('/admin/orders')->with('error', 'Chỉ có thể xóa đơn hàng đã bị hủy.');
        }

        $order->items()->delete();
        $order->logs()->delete();
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

        // Business logic: Admin can cancel pending, confirmed, and shipping orders
        // Delivered and cancelled orders cannot be cancelled
        if (in_array($oldStatus, ['delivered', 'cancelled'])) {
            $errorMessages = [
                'delivered' => 'Không thể hủy đơn hàng đã giao. Vui lòng tạo yêu cầu hoàn trả nếu cần thiết.',
                'cancelled' => 'Đơn hàng đã được hủy trước đó.',
            ];
            
            return redirect()->back()->with('error', $errorMessages[$oldStatus]);
        }

        // Validate reason (required for all cancellations, especially shipping)
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
            'force_cancel' => 'nullable|boolean', // Confirmation for shipping orders
        ], [
            'reason.required' => 'Vui lòng nhập lý do hủy đơn hàng',
            'reason.max' => 'Lý do không được vượt quá 500 ký tự',
        ]);

        // Extra validation for shipping orders - require explicit confirmation
        if ($oldStatus === 'shipping' && !$request->boolean('force_cancel')) {
            return redirect()->back()->with('warning', 
                'Đơn hàng đang trong quá trình giao. Để hủy, vui lòng xác nhận đã liên hệ đơn vị vận chuyển.'
            )->withInput();
        }

        // Update order status to cancelled
        $order->update(['status' => 'cancelled']);

        // Log cancellation with reason
        $logMessage = $oldStatus === 'shipping' 
            ? 'Hủy đơn hàng đang giao (đã xác nhận liên hệ vận chuyển)' 
            : 'Hủy đơn hàng';

        OrderLog::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'action' => 'order_cancelled',
            'message' => $logMessage,
            'details' => [
                'from' => $oldStatus,
                'to' => 'cancelled',
                'reason' => $validated['reason'],
                'cancelled_by' => Auth::user()->name ?? 'Admin',
                'admin_id' => Auth::id(),
                'was_shipping' => $oldStatus === 'shipping',
                'tracking_number' => $order->tracking_number,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Notify customer about cancellation
        try {
            if ($order->user_id) {
                $this->notifyOrderCancelled($order, $validated['reason']);
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send cancellation notification', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }

        $successMessage = $oldStatus === 'shipping'
            ? 'Đơn hàng đang giao đã được hủy thành công. Vui lòng đảm bảo đã phối hợp với đơn vị vận chuyển.'
            : 'Đơn hàng đã được hủy thành công.';

        return redirect()->back()->with('success', $successMessage);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:confirmed,shipping,delivered,cancelled'
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        // Validate status transition logic
        $allowedTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['shipping', 'cancelled'],
            'shipping' => ['delivered', 'cancelled'], // Allow cancel but recommend using cancel() method for better tracking
            'delivered' => [], // No transitions from delivered (use return/refund process instead)
            'cancelled' => [], // No transitions from cancelled
        ];

        if (!in_array($newStatus, $allowedTransitions[$oldStatus] ?? [])) {
            $errorMessages = [
                'delivered_to_any' => 'Không thể thay đổi trạng thái đơn hàng đã giao. Vui lòng tạo yêu cầu hoàn trả nếu cần.',
                'cancelled_to_any' => 'Không thể thay đổi trạng thái đơn hàng đã hủy.',
            ];
            
            $errorKey = $oldStatus === 'delivered' ? 'delivered_to_any' 
                : ($oldStatus === 'cancelled' ? 'cancelled_to_any' : null);
            
            return redirect()->back()->with('error', 
                $errorMessages[$errorKey] ?? 'Không thể chuyển từ trạng thái "' . $oldStatus . '" sang "' . $newStatus . '"'
            );
        }
        
        // Special warning for shipping to cancelled transition
        if ($oldStatus === 'shipping' && $newStatus === 'cancelled') {
            // Add warning but allow it - recommend using dedicated cancel route instead
            session()->flash('warning', 'Lưu ý: Đang hủy đơn hàng trong quá trình giao. Vui lòng đảm bảo đã liên hệ đơn vị vận chuyển.');
        }

        $order->update(['status' => $newStatus]);
        
        // Auto-complete payment for COD when delivered
        $oldPaymentStatus = $order->payment_status;
        if ($newStatus === 'delivered' && $order->paymentMethod && $order->paymentMethod->code === 'cod' && $order->payment_status === 'pending') {
            $order->update([
                'payment_status' => 'completed',
                'paid_at' => now()
            ]);
            
            // Log payment completion
            OrderLog::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'action' => 'payment_status_changed',
                'message' => 'Tự động hoàn thành thanh toán COD khi giao hàng',
                'details' => [
                    'from' => $oldPaymentStatus,
                    'to' => 'completed',
                    'paid_at' => $order->paid_at,
                    'payment_method' => 'cod',
                    'auto' => true,
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        // Log the status change
        OrderLog::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'action' => 'status_changed',
            'details' => [
                'from' => $oldStatus,
                'to' => $newStatus,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Send notification to user
        try {
            if ($order->user_id) {
                $this->notifyOrderStatusChanged($order, $oldStatus, $newStatus);
            }
        } catch (\Throwable $e) {
            // Log error but don't fail the status update
            Log::error('Failed to send order status notification', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }

        $statusLabels = [
            'confirmed' => 'đã xác nhận',
            'shipping' => 'đang giao hàng', 
            'delivered' => 'đã giao thành công',
            'cancelled' => 'đã hủy',
        ];

        return redirect()->back()->with('success', 'Đơn hàng ' . ($statusLabels[$newStatus] ?? $newStatus));
    }

    public function updateTracking(Request $request, Order $order)
    {
        $validated = $request->validate([
            'tracking_number' => 'required|string|max:100',
        ], [
            'tracking_number.required' => 'Vui lòng nhập mã vận đơn',
        ]);

        $oldTracking = $order->tracking_number;
        $order->update(['tracking_number' => $validated['tracking_number']]);

        // Log tracking update
        OrderLog::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'action' => 'tracking_updated',
            'message' => 'Cập nhật mã vận đơn',
            'details' => [
                'old_tracking' => $oldTracking,
                'new_tracking' => $validated['tracking_number'],
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Notify user
        try {
            if ($order->user_id) {
                $this->notifyTrackingUpdated($order, $validated['tracking_number']);
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send tracking notification', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->back()->with('success', 'Đã cập nhật mã vận đơn thành công.');
    }

    public function updateNote(Request $request, Order $order)
    {
        $validated = $request->validate([
            'note' => 'nullable|string|max:1000',
        ]);

        $oldNote = $order->note;
        $order->update(['note' => $validated['note']]);

        // Log note update
        if ($oldNote !== $validated['note']) {
            OrderLog::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'action' => 'note_updated',
                'message' => 'Cập nhật ghi chú đơn hàng',
                'details' => [
                    'old_note' => $oldNote,
                    'new_note' => $validated['note'],
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }

        return redirect()->back()->with('success', 'Đã cập nhật ghi chú.');
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,completed,failed,refunded',
        ]);

        $oldStatus = $order->payment_status;
        $newStatus = $validated['payment_status'];

        if ($oldStatus === $newStatus) {
            return redirect()->back()->with('info', 'Trạng thái thanh toán không thay đổi.');
        }

        // Business logic validation - Prevent invalid status transitions
        $invalidTransitions = [
            'completed' => ['pending', 'failed'], // Đã thanh toán không thể về chờ/thất bại
            'refunded' => ['pending', 'completed', 'failed'], // Đã hoàn tiền không thể chuyển sang trạng thái khác
        ];

        if (isset($invalidTransitions[$oldStatus]) && in_array($newStatus, $invalidTransitions[$oldStatus])) {
            return redirect()->back()->with('error', 
                'Không thể chuyển từ "' . $this->getStatusLabel($oldStatus) . '" sang "' . $this->getStatusLabel($newStatus) . '". ' .
                'Vui lòng sử dụng chức năng hoàn tiền nếu cần.'
            );
        }

        // Prevent manual change to 'refunded' - must use refund button
        if ($newStatus === 'refunded') {
            return redirect()->back()->with('error', 
                'Không thể thay đổi trạng thái thành "Đã hoàn tiền" trực tiếp. ' .
                'Vui lòng sử dụng nút "Hoàn tiền" bên dưới để xử lý hoàn tiền.'
            );
        }

        // Create PaymentTransaction when marking as completed
        if ($newStatus === 'completed') {
            // Check if PaymentTransaction already exists
            $existingTransaction = $order->paymentTransactions()
                ->where('status', 'completed')
                ->first();

            if (!$existingTransaction) {
                // Create new PaymentTransaction
                \App\Models\PaymentTransaction::create([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'payment_method_id' => $order->payment_method_id,
                    'transaction_number' => 'PAY-' . $order->order_number . '-' . uniqid(),
                    'amount' => $order->grand_total,
                    'status' => 'completed',
                    'payment_date' => now(),
                    'notes' => 'Payment confirmed by admin',
                ]);
            }

            // Update paid_at
            if (!$order->paid_at) {
                $order->update(['paid_at' => now()]);
            }
        }

        // Update payment status
        $order->update(['payment_status' => $newStatus]);

        // Sync PaymentTransaction status if exists (except for 'refunded' - tracked separately in refunds table)
        if ($newStatus !== 'refunded' && $order->paymentTransactions()->exists()) {
            $order->paymentTransactions()->latest()->first()->update([
                'status' => $newStatus,
            ]);
        }

        // Log payment status change
        OrderLog::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'action' => 'payment_status_changed',
            'message' => 'Thay đổi trạng thái thanh toán',
            'details' => [
                'from' => $oldStatus,
                'to' => $newStatus,
                'paid_at' => $newStatus === 'completed' ? now() : null,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Send notification to user
        try {
            if ($order->user_id) {
                $this->notifyPaymentStatusChanged($order, $oldStatus, $newStatus);
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send payment status notification', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }

        $statusLabels = [
            'pending' => 'chờ thanh toán',
            'completed' => 'đã thanh toán',
            'failed' => 'thanh toán thất bại',
            'refunded' => 'đã hoàn tiền',
        ];

        return redirect()->back()->with('success', 'Đơn hàng ' . ($statusLabels[$newStatus] ?? $newStatus));
    }

    public function refund(Request $request, Order $order)
    {
        // Validate refund request
        $validated = $request->validate([
            'refund_amount' => 'required|numeric|min:0|max:' . $order->grand_total,
            'refund_reason' => 'required|string|max:500',
        ], [
            'refund_amount.required' => 'Vui lòng nhập số tiền hoàn',
            'refund_amount.max' => 'Số tiền hoàn không được vượt quá tổng đơn hàng',
            'refund_reason.required' => 'Vui lòng nhập lý do hoàn tiền',
        ]);

        // Only allow refund for completed payments
        if ($order->payment_status !== 'completed') {
            return redirect()->back()->with('error', 'Chỉ có thể hoàn tiền cho đơn hàng đã thanh toán.');
        }

        $oldPaymentStatus = $order->payment_status;
        $refundAmount = $validated['refund_amount'];
        $isFullRefund = $refundAmount >= $order->grand_total;

        // Get or create payment transaction
        $paymentTransaction = $order->paymentTransactions()
            ->where('status', 'completed')
            ->latest()
            ->first();

        if (!$paymentTransaction) {
            // Create a payment transaction record if none exists
            $paymentTransaction = \App\Models\PaymentTransaction::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'payment_method_id' => $order->payment_method_id,
                'transaction_number' => 'PAY-' . $order->order_number . '-' . uniqid(),
                'amount' => $order->grand_total,
                'status' => 'completed',
                'payment_date' => $order->paid_at ?? now(),
                'notes' => 'Payment record created for refund processing',
            ]);
        }

        // Create refund record in refunds table
        $refund = \App\Models\Refund::create([
            'payment_transaction_id' => $paymentTransaction->id,
            'amount' => $refundAmount,
            'reason' => $validated['refund_reason'],
            'status' => 'refunded',
            'processed_at' => now(),
            'meta' => json_encode([
                'refund_type' => $isFullRefund ? 'full' : 'partial',
                'processed_by_user_id' => Auth::id(),
                'processed_by_name' => Auth::user()->name ?? 'Admin',
                'original_amount' => $order->grand_total,
            ]),
        ]);

        // Update payment status
        $order->update(['payment_status' => 'refunded']);

        // Log refund in order_logs
        OrderLog::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'action' => 'payment_refunded',
            'message' => $isFullRefund ? 'Hoàn tiền toàn bộ đơn hàng' : 'Hoàn tiền một phần',
            'details' => [
                'from' => $oldPaymentStatus,
                'to' => 'refunded',
                'refund_id' => $refund->id,
                'payment_transaction_id' => $paymentTransaction->id,
                'refund_amount' => $refundAmount,
                'total_amount' => $order->grand_total,
                'refund_type' => $isFullRefund ? 'full' : 'partial',
                'reason' => $validated['refund_reason'],
                'refunded_by' => Auth::user()->name ?? 'Admin',
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Notify user
        try {
            if ($order->user_id) {
                app(\App\Services\NotificationService::class)->send(
                    $order->user_id,
                    'order_refund',
                    'Đơn hàng đã được hoàn tiền',
                    'Đơn hàng ' . ($order->order_number ?? '#'.$order->id) . ' đã được hoàn ' . number_format($refundAmount, 0, ',', '.') . ' VNĐ. Lý do: ' . $validated['refund_reason']
                );
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send refund notification', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->back()->with('success', 'Đã hoàn tiền thành công: ' . number_format($refundAmount, 0, ',', '.') . ' VNĐ (Refund ID: #' . $refund->id . ')');
    }

    /**
     * Create installment schedule for finance orders
     */
    private function createInstallmentSchedule(Order $order)
    {
        if (!$order->finance_option_id || !$order->tenure_months || !$order->monthly_payment_amount) {
            return;
        }

        // Check if installments already exist
        if ($order->installments()->count() > 0) {
            return;
        }

        $tenureMonths = $order->tenure_months;
        $monthlyAmount = $order->monthly_payment_amount;

        for ($i = 1; $i <= $tenureMonths; $i++) {
            \App\Models\Installment::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'finance_option_id' => $order->finance_option_id,
                'installment_number' => $i,
                'amount' => $monthlyAmount,
                'due_date' => now()->addMonths($i)->startOfMonth(),
                'status' => 'pending',
            ]);
        }

        // Log installment schedule creation
        OrderLog::create([
            'order_id' => $order->id,
            'user_id' => Auth::id() ?? $order->user_id,
            'action' => 'installments_created',
            'message' => 'Tạo lịch trả góp tự động',
            'details' => [
                'tenure_months' => $tenureMonths,
                'monthly_amount' => $monthlyAmount,
                'total_installments' => $tenureMonths,
                'finance_option_id' => $order->finance_option_id,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Notify user about installments created
        try {
            if ($order->user_id) {
                $this->notifyInstallmentsCreated($order, $tenureMonths, $monthlyAmount);
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send installments created notification', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Generate installments for existing finance order (manual trigger)
     */
    public function generateInstallments(Order $order)
    {
        if (!$order->finance_option_id) {
            return redirect()->back()->with('error', 'Đơn hàng này không phải đơn trả góp.');
        }

        if ($order->installments()->count() > 0) {
            return redirect()->back()->with('warning', 'Lịch trả góp đã được tạo trước đó.');
        }

        $this->createInstallmentSchedule($order);

        return redirect()->back()->with('success', 'Đã tạo lịch trả góp: ' . $order->tenure_months . ' kỳ.');
    }

    /**
     * Get payment status label in Vietnamese
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'Chờ thanh toán',
            'completed' => 'Đã thanh toán',
            'failed' => 'Thanh toán thất bại',
            'refunded' => 'Đã hoàn tiền',
        ];

        return $labels[$status] ?? $status;
    }
}