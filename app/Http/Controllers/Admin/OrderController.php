<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\PaymentTransaction;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Services\NotificationService;
use App\Traits\SendsOrderNotifications;
use App\Application\Orders\UseCases\ConfirmOrder;
use App\Application\Orders\UseCases\CancelOrder;

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
        
        // Get manual verification payment methods for down payment modal
        $manualPaymentMethods = $this->getManualPaymentMethods();
        
        // Calculate total paid for refund form
        $totalPaid = $order->paymentTransactions()
            ->where('status', 'completed')
            ->sum('amount');
        
        return view('admin.orders.show', compact('order', 'manualPaymentMethods', 'totalPaid'));
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
        $oldPaymentStatus = $order->payment_status;

        // Business logic: Admin can cancel pending, confirmed, and shipping orders
        // Delivered and cancelled orders cannot be cancelled
        if (in_array($oldStatus, ['delivered', 'cancelled'])) {
            $errorMessages = [
                'delivered' => 'Không thể hủy đơn hàng đã giao. Vui lòng tạo yêu cầu hoàn trả nếu cần thiết.',
                'cancelled' => 'Đơn hàng đã được hủy trước đó.',
            ];
            
            return redirect()->back()->with('error', $errorMessages[$oldStatus]);
        }

        // Payment status validation - Cannot cancel fully paid orders
        if ($order->payment_status === 'completed') {
            return redirect()->back()->with('error', 
                'Không thể hủy đơn hàng đã thanh toán đầy đủ. Vui lòng tạo yêu cầu hoàn tiền thay vì hủy đơn hàng.'
            );
        }

        // Special validation for installment orders with down payment
        if ($order->finance_option_id && $order->payment_status === 'partial') {
            // Check if down payment has been confirmed
            $hasDownPayment = $order->paymentTransactions()
                ->where('notes', 'LIKE', '%Down payment%')
                ->where('status', 'completed')
                ->exists();
                
            if ($hasDownPayment) {
                return redirect()->back()->with('error', 
                    'Không thể hủy đơn hàng trả góp đã xác nhận tiền cọc. Vui lòng tạo yêu cầu hoàn tiền cho tiền cọc.'
                );
            }
        }

        // Validate shipping confirmation if needed
        $validated = $request->validate([
            'reason' => 'nullable|string|max:500', // Optional now
            'force_cancel' => 'nullable|boolean', // Confirmation for shipping orders
        ]);

        // Extra validation for shipping orders - require explicit confirmation
        if ($oldStatus === 'shipping' && !$request->boolean('force_cancel')) {
            return redirect()->back()->with('warning', 
                'Đơn hàng đang trong quá trình giao. Để hủy, vui lòng xác nhận đã liên hệ đơn vị vận chuyển.'
            )->withInput();
        }

        DB::beginTransaction();
        
        try {
            // Handle installments if order has them
        $hasInstallments = $order->installments()->exists();
        $installmentsSummary = null;
        
        if ($hasInstallments) {
            $installments = $order->installments()->get();
            $paidCount = $installments->where('status', 'paid')->count();
            $pendingCount = $installments->whereIn('status', ['pending', 'overdue'])->count();
            $totalPaid = $installments->where('status', 'paid')->sum('amount');
            
            // Cancel all pending installments
            $order->installments()
                ->whereIn('status', ['pending', 'overdue'])
                ->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now()
                ]);
            
            $installmentsSummary = [
                'total_installments' => $installments->count(),
                'paid_installments' => $paidCount,
                'cancelled_installments' => $pendingCount,
                'total_paid_amount' => $totalPaid,
                'refund_required' => $paidCount > 0
            ];
        }

        // Handle payment transactions
        $paymentTransactionsSummary = null;
        $transactions = $order->paymentTransactions()->get();
        
        if ($transactions->count() > 0) {
            $pendingTransactions = $transactions->whereIn('status', ['pending', 'processing']);
            $completedTransactions = $transactions->where('status', 'completed');
            
            // Cancel pending/processing transactions
            foreach ($pendingTransactions as $transaction) {
                $transaction->update([
                    'status' => 'cancelled',
                    'notes' => ($transaction->notes ?? '') . ' - Hủy do admin hủy đơn hàng'
                ]);
            }
            
            // Mark completed transactions as needing refund (don't auto-refund)
            foreach ($completedTransactions as $transaction) {
                $transaction->update([
                    'notes' => ($transaction->notes ?? '') . ' - Cần hoàn tiền do admin hủy đơn hàng'
                ]);
            }
            
            $paymentTransactionsSummary = [
                'total_transactions' => $transactions->count(),
                'cancelled_transactions' => $pendingTransactions->count(),
                'completed_transactions' => $completedTransactions->count(),
                'total_completed_amount' => $completedTransactions->sum('amount'),
                'refund_required' => $completedTransactions->count() > 0
            ];
        }

        // Update order status to cancelled
        $order->status = 'cancelled';
        
        // Update payment status based on current status
        if ($order->payment_status === 'pending') {
            $order->payment_status = 'cancelled'; // Pending payments become cancelled
        } elseif ($order->payment_status === 'partial') {
            // Keep partial status for installment orders (some payments already made)
            $order->payment_status = 'partial';
        }
        // Note: completed and failed payments remain as-is
        
        $order->save();

        // Log cancellation with reason
        $logMessage = $oldStatus === 'shipping' 
            ? 'Hủy đơn hàng đang giao (đã xác nhận liên hệ vận chuyển)' 
            : 'Hủy đơn hàng';

        OrderLog::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'action' => 'order_cancelled',
            'message' => $logMessage,
            'details' => array_merge([
                'order_status' => ['from' => $oldStatus, 'to' => 'cancelled'],
                'payment_status' => ['from' => $oldPaymentStatus, 'to' => $order->payment_status],
                'cancelled_by' => Auth::user()->name ?? 'Admin',
                'admin_id' => Auth::id(),
                'was_shipping' => $oldStatus === 'shipping',
                'tracking_number' => $order->tracking_number,
                'has_installments' => $hasInstallments,
                'has_payment_transactions' => $transactions->count() > 0,
            ], 
            $installmentsSummary ? ['installments' => $installmentsSummary] : [],
            $paymentTransactionsSummary ? ['payment_transactions' => $paymentTransactionsSummary] : []),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Notify customer about cancellation
        try {
            if ($order->user_id) {
                $this->notifyOrderCancelled($order, $paymentTransactionsSummary, $installmentsSummary);
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send cancellation notification', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }

            DB::commit();

            $successMessage = $oldStatus === 'shipping'
                ? 'Đơn hàng đang giao đã được hủy thành công. Vui lòng đảm bảo đã phối hợp với đơn vị vận chuyển.'
                : 'Đơn hàng đã được hủy thành công.';

            // Add installment info to success message if applicable
            if ($hasInstallments && $installmentsSummary) {
                $installmentInfo = " Đã hủy {$installmentsSummary['cancelled_installments']} kỳ trả góp còn lại.";
                if ($installmentsSummary['refund_required']) {
                    $installmentInfo .= " Cần xử lý hoàn tiền cho {$installmentsSummary['paid_installments']} kỳ đã thanh toán (" . number_format($installmentsSummary['total_paid_amount']) . " VNĐ).";
                }
                $successMessage .= $installmentInfo;
            }

            return redirect()->back()->with('success', $successMessage);
            
        } catch (\Throwable $e) {
            DB::rollback();
            
            Log::error('Admin order cancellation failed', [
                'order_id' => $order->id,
                'admin_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi hủy đơn hàng. Vui lòng thử lại.');
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:confirmed,shipping,delivered,cancelled'
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        // Business Rule: Require payment completion before confirming order (except for cancellation)
        if ($newStatus === 'confirmed' && !in_array($order->payment_status, ['partial', 'completed'])) {
            if ($order->finance_option_id) {
                return redirect()->back()->with('error',
                    'Không thể xác nhận đơn hàng trả góp khi chưa xác nhận tiền cọc. Vui lòng xác nhận tiền cọc trước.'
                );
            } else {
                return redirect()->back()->with('error',
                    'Không thể xác nhận đơn hàng khi chưa hoàn tất thanh toán. Vui lòng cập nhật trạng thái thanh toán trước.'
                );
            }
        }

        // Business Rule: Require order confirmation before shipping
        if ($newStatus === 'shipping' && !in_array($order->payment_status, ['partial', 'completed'])) {
            if ($order->finance_option_id) {
                return redirect()->back()->with('error',
                    'Không thể giao hàng khi chưa xác nhận tiền cọc.'
                );
            } else {
                return redirect()->back()->with('error',
                    'Không thể giao hàng khi chưa hoàn tất thanh toán.'
                );
            }
        }

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
        
        // Handle stock management based on status change
        if ($newStatus === 'confirmed' && $oldStatus === 'pending') {
            try {
                app(ConfirmOrder::class)->handle($order);
                Log::info('Stock confirmed for order', ['order_id' => $order->id]);
            } catch (\Exception $e) {
                Log::error('Failed to confirm stock for order', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        if ($newStatus === 'cancelled') {
            try {
                app(CancelOrder::class)->handle($order);
                Log::info('Stock restored for cancelled order', ['order_id' => $order->id]);
            } catch (\Exception $e) {
                Log::error('Failed to restore stock for cancelled order', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
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
                // Clear notification cache for user
                $this->clearUserNotificationCache($order->user_id);
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
            'payment_status' => 'required|in:pending,partial,completed,failed,refunded,cancelled',
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

        // Special validation for installment orders
        if ($order->finance_option_id && $order->installments()->exists()) {
            if ($newStatus === 'completed') {
                // Check if all installments are paid
                $unpaidInstallments = $order->installments()
                    ->where('status', '!=', 'paid')
                    ->count();
                
                if ($unpaidInstallments > 0) {
                    return redirect()->back()->with('error', 
                        'Không thể đánh dấu "Đã thanh toán" khi còn ' . $unpaidInstallments . ' kỳ trả góp chưa thanh toán. ' .
                        'Vui lòng xác nhận thanh toán từng kỳ trong phần "Lịch trả góp".'
                    );
                }
            }
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

        // Create notification for payment completion (one-time payment orders)
        if ($newStatus === 'completed' && $oldStatus !== 'completed') {
            \App\Models\Notification::create([
                'user_id' => $order->user_id,
                'type' => 'payment_completed',
                'title' => "Thanh toán hoàn tất - #{$order->order_number}",
                'message' => "💳 Thanh toán đơn hàng của bạn đã được xác nhận. Chúng tôi sẽ tiến hành xử lý và giao hàng sớm nhất.",
                'is_read' => false,
            ]);
        }

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
                // Clear notification cache for user
                $this->clearUserNotificationCache($order->user_id);
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
        // Calculate total amount paid (including installments)
        $totalPaid = $order->paymentTransactions()
            ->where('status', 'completed')
            ->sum('amount');
        
        // Validate refund request
        $validated = $request->validate([
            'refund_amount' => 'required|numeric|min:0|max:' . $totalPaid,
            'refund_reason' => 'required|string|max:500',
        ], [
            'refund_amount.required' => 'Vui lòng nhập số tiền hoàn',
            'refund_amount.max' => 'Số tiền hoàn không được vượt quá số tiền đã thanh toán (' . number_format($totalPaid, 0, ',', '.') . ' VNĐ)',
            'refund_reason.required' => 'Vui lòng nhập lý do hoàn tiền',
        ]);
        
        // Check if any payment has been made
        if ($totalPaid <= 0) {
            return redirect()->back()->with('error', 'Không thể hoàn tiền cho đơn hàng chưa có thanh toán nào.');
        }

        $oldPaymentStatus = $order->payment_status;
        $refundAmount = $validated['refund_amount'];
        $isFullRefund = $refundAmount >= $totalPaid;

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

        // AUTO CANCEL ORDER when refund is processed (same logic as PaymentController)
        $oldOrderStatus = $order->status;
        $oldPaymentStatus = $order->payment_status;
        
        // Cancel pending/overdue installments (if any)
        $cancelledInstallments = 0;
        if ($order->installments()->exists()) {
            $cancelledInstallments = $order->installments()
                ->whereIn('status', ['pending', 'overdue'])
                ->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now()
                ]);
        }
        
        // Cancel pending/processing payment transactions (if any)
        $order->paymentTransactions()
            ->whereIn('status', ['pending', 'processing'])
            ->update([
                'status' => 'cancelled',
                'notes' => \Illuminate\Support\Facades\DB::raw("CONCAT(COALESCE(notes, ''), ' - Hủy do hoàn tiền')")
            ]);
        
        // Update order status to cancelled
        if ($order->status !== 'cancelled') {
            $order->update(['status' => 'cancelled']);
        }
        
        // Update payment status to refunded
        $order->update(['payment_status' => 'refunded']);

        // Log refund in order_logs
        OrderLog::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'action' => 'payment_refunded',
            'message' => $isFullRefund ? 'Admin hoàn tiền toàn bộ đơn hàng' : 'Admin hoàn tiền một phần',
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
        
        // Log order cancellation if order was not already cancelled
        if ($oldOrderStatus !== 'cancelled') {
            OrderLog::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'action' => 'order_cancelled',
                'message' => 'Tự động hủy đơn hàng sau khi admin hoàn tiền',
                'details' => [
                    'order_status' => ['from' => $oldOrderStatus, 'to' => 'cancelled'],
                    'payment_status' => ['from' => $oldPaymentStatus, 'to' => 'refunded'],
                    'reason' => 'Tự động hủy đơn hàng sau khi admin hoàn tiền trực tiếp',
                    'refund_id' => $refund->id,
                    'refund_amount' => $refundAmount,
                    'cancelled_installments' => $cancelledInstallments,
                    'cancelled_by' => Auth::user()->name ?? 'Admin',
                    'admin_id' => Auth::id(),
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }

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
     * Confirm down payment for installment order
     */
    public function confirmDownPayment(Request $request, Order $order)
    {
        // Validate that this is an installment order
        if (!$order->finance_option_id || !$order->down_payment_amount) {
            return redirect()->back()->with('error', 'Đơn hàng này không phải đơn trả góp hoặc không có tiền cọc.');
        }

        // Check if down payment already confirmed
        $existingDownPayment = $order->paymentTransactions()
            ->where('notes', 'LIKE', '%Down payment%')
            ->where('status', 'completed')
            ->first();

        if ($existingDownPayment) {
            return redirect()->back()->with('warning', 'Tiền cọc đã được xác nhận trước đó.');
        }

        $validated = $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            // 1. Create PaymentTransaction for down payment
            $transaction = PaymentTransaction::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'payment_method_id' => $validated['payment_method_id'],
                'transaction_number' => 'DP-' . time() . '-' . $order->id,
                'amount' => $order->down_payment_amount,
                'status' => 'completed',
                'payment_date' => $validated['payment_date'],
                'notes' => 'Down payment for installment order - ' . ($validated['notes'] ?? ''),
            ]);

            // 2. Update order payment status to 'partial' (enough to ship)
            $oldPaymentStatus = $order->payment_status;
            $order->update([
                'payment_status' => 'partial',
                'down_payment_confirmed_at' => now(),
                'down_payment_percentage' => ($order->down_payment_amount / $order->grand_total) * 100,
            ]);

            // 3. Create order log
            OrderLog::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'action' => 'down_payment_confirmed',
                'message' => 'Xác nhận tiền cọc - Đơn hàng có thể giao hàng',
                'details' => [
                    'down_payment_amount' => $order->down_payment_amount,
                    'payment_status_from' => $oldPaymentStatus,
                    'payment_status_to' => 'partial',
                    'transaction_id' => $transaction->id,
                    'payment_method_id' => $validated['payment_method_id'],
                    'admin_id' => Auth::id(),
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // 4. Create notification for customer
            \App\Models\Notification::create([
                'user_id' => $order->user_id,
                'type' => 'payment_completed',
                'title' => "Tiền cọc đã xác nhận - #{$order->order_number}",
                'message' => "💳 Tiền cọc " . number_format($order->down_payment_amount) . " VNĐ đã được xác nhận. Chúng tôi sẽ tiến hành xử lý và giao hàng sớm nhất. Bạn có thể thanh toán các kỳ trả góp theo lịch.",
                'is_read' => false,
            ]);

            // Clear notification cache for user
            $this->clearUserNotificationCache($order->user_id);

            DB::commit();

            return redirect()->back()->with('success', 
                'Đã xác nhận tiền cọc ' . number_format($order->down_payment_amount) . ' VNĐ. Đơn hàng có thể giao hàng.'
            );

        } catch (\Throwable $e) {
            DB::rollback();
            Log::error('Failed to confirm down payment', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Không thể xác nhận tiền cọc. Vui lòng thử lại.');
        }
    }

    /**
     * Get manual verification payment methods for down payment confirmation
     * Excludes auto-confirm online gateways that don't allow admin control
     */
    private function getManualPaymentMethods()
    {
        return \App\Models\PaymentMethod::where('is_active', true)
            ->whereNotIn('code', ['vnpay', 'momo', 'zalopay', 'paypal', 'stripe'])
            ->orderBy('name')
            ->get();
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

    /**
     * Clear notification cache for a specific user
     */
    private function clearUserNotificationCache($userId)
    {
        // Clear all pages for this user (same as NotificationController)
        for ($page = 1; $page <= 10; $page++) {
            Cache::forget("user_notifications_{$userId}_page_{$page}");
        }
    }
    
    /**
     * Notify customer about order cancellation
     */
    private function notifyOrderCancelled($order, $paymentTransactionsSummary = null, $installmentsSummary = null)
    {
        $orderNumber = $order->order_number ?? '#' . $order->id;
        $notificationTitle = 'Đơn hàng đã bị hủy';
        
        // Build notification message
        $notificationMessage = "Đơn hàng {$orderNumber} đã bị hủy bởi quản trị viên. Vui lòng liên hệ bộ phận chăm sóc khách hàng để biết thêm chi tiết.";
        
        // Add refund information if applicable
        if ($paymentTransactionsSummary && isset($paymentTransactionsSummary['refund_required']) && $paymentTransactionsSummary['refund_required']) {
            $refundAmount = number_format($paymentTransactionsSummary['total_completed_amount'], 0, ',', '.');
            $notificationMessage .= " Số tiền {$refundAmount}₫ sẽ được hoàn lại trong vòng 3-5 ngày làm việc.";
        }
        
        // Add installment information if applicable
        if ($installmentsSummary && isset($installmentsSummary['cancelled_installments']) && $installmentsSummary['cancelled_installments'] > 0) {
            $notificationMessage .= " Đã hủy {$installmentsSummary['cancelled_installments']} kỳ trả góp còn lại.";
        }
        
        app(\App\Services\NotificationService::class)->send(
            $order->user_id,
            'order_status',
            $notificationTitle,
            $notificationMessage
        );
    }
}