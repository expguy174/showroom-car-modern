<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\PaymentTransaction;
use App\Services\NotificationService;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();
        $paymentStatus = $request->string('payment_status')->toString();
        $q = $request->string('q')->toString();

        $orders = Order::with(['items.item', 'shippingAddress', 'billingAddress', 'paymentMethod', 'promotion'])
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
            'promotion',
            'installments' => function($query) {
                $query->orderBy('installment_number');
            },
            'installments.paymentTransaction',
            'refunds' => function($query) {
                $query->orderBy('created_at', 'desc');
            }
        ]);
        
        // Calculate installment stats if this is a finance order
        $installmentStats = null;
        if ($order->finance_option_id && $order->installments->count() > 0) {
            $installmentStats = [
                'total_installments' => $order->installments->count(),
                'paid_count' => $order->installments->where('status', 'paid')->count(),
                'pending_count' => $order->installments->where('status', 'pending')->count(),
                'overdue_count' => $order->installments->where('status', 'overdue')->count(),
                'total_paid' => $order->installments->where('status', 'paid')->sum('amount'),
                'total_remaining' => $order->installments->whereIn('status', ['pending', 'overdue'])->sum('amount'),
                'next_payment' => $order->installments->where('status', 'pending')->sortBy('due_date')->first(),
            ];
        }
        
        // Calculate total paid for refund purposes
        $totalPaid = $order->paymentTransactions()->where('status', 'completed')->sum('amount');
        
        return view('user.orders.show', compact('order', 'installmentStats', 'totalPaid'));
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

        // Order status validation
        if (!in_array($order->status, ['pending', 'confirmed'])) {
            $message = 'Đơn hàng không thể hủy ở trạng thái hiện tại.';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return back()->with('status', $message);
        }
        
        // Payment status validation
        if ($order->payment_status === 'completed') {
            $message = 'Đơn hàng đã thanh toán đầy đủ, vui lòng liên hệ hỗ trợ để hủy.';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return back()->with('status', $message);
        }
        
        // Special validation for installment orders with down payment
        if ($order->finance_option_id && $order->payment_status === 'partial') {
            $hasDownPayment = $order->paymentTransactions()
                ->where('notes', 'LIKE', '%Down payment%')
                ->where('status', 'completed')
                ->exists();
                
            if ($hasDownPayment) {
                $message = 'Đơn hàng trả góp đã xác nhận tiền cọc không thể hủy. Vui lòng liên hệ hỗ trợ.';
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }
                return back()->with('status', $message);
            }
        }
        
        // Time-based restriction: 24 hours window for cancellation
        $withinCancelWindow = $order->created_at->diffInHours(now()) <= 24;
        if (!$withinCancelWindow) {
            $message = 'Chỉ có thể hủy đơn hàng trong vòng 24 giờ sau khi đặt hàng.';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return back()->with('status', $message);
        }

        $oldStatus = $order->status;
        $oldPaymentStatus = $order->payment_status;
        
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
                        'notes' => ($transaction->notes ?? '') . ' - Hủy do khách hàng hủy đơn hàng'
                    ]);
                }
                
                // Mark completed transactions as needing refund (don't auto-refund)
                foreach ($completedTransactions as $transaction) {
                    $transaction->update([
                        'notes' => ($transaction->notes ?? '') . ' - Cần hoàn tiền do khách hàng hủy đơn hàng'
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

            // Log action
            try {
                $logMessage = $hasInstallments && $installmentsSummary 
                    ? 'Khách hàng yêu cầu hủy đơn hàng trả góp'
                    : 'Khách hàng yêu cầu hủy đơn hàng';
                    
                OrderLog::create([
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                    'action' => 'user_cancel',
                    'message' => $logMessage,
                    'details' => array_merge([
                        'order_status' => ['from' => $oldStatus, 'to' => 'cancelled'],
                        'payment_status' => ['from' => $oldPaymentStatus, 'to' => $order->payment_status],
                        'has_installments' => $hasInstallments,
                        'has_payment_transactions' => $transactions->count() > 0,
                    ], 
                    $installmentsSummary ? ['installments' => $installmentsSummary] : [],
                    $paymentTransactionsSummary ? ['payment_transactions' => $paymentTransactionsSummary] : []),
                    'ip_address' => $request->ip(),
                    'user_agent' => (string) $request->userAgent(),
                ]);
            } catch (\Throwable $e) {}

            // Notify user
            try {
                $orderNumber = $order->order_number ?? '#' . $order->id;
                $notificationTitle = 'Đơn hàng đã được hủy thành công';
                
                // Build notification message based on refund status
                $notificationMessage = "Đơn hàng {$orderNumber} đã được hủy theo yêu cầu của bạn.";
                
                if ($paymentTransactionsSummary && isset($paymentTransactionsSummary['refund_required']) && $paymentTransactionsSummary['refund_required']) {
                    $refundAmount = number_format($paymentTransactionsSummary['total_completed_amount'], 0, ',', '.');
                    $notificationMessage .= " Số tiền {$refundAmount}₫ sẽ được hoàn lại trong vòng 3-5 ngày làm việc.";
                }
                
                if ($hasInstallments && $installmentsSummary && isset($installmentsSummary['cancelled_installments']) && $installmentsSummary['cancelled_installments'] > 0) {
                    $notificationMessage .= " Đã hủy {$installmentsSummary['cancelled_installments']} kỳ trả góp còn lại.";
                }
                
                app(NotificationService::class)->send(
                    $order->user_id,
                    'order_status',
                    $notificationTitle,
                    $notificationMessage
                );
            } catch (\Throwable $e) {}

            DB::commit();

            $message = 'Đã hủy đơn hàng thành công.';
            
            // Return JSON for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
            
            return redirect()->route('user.orders.index')->with('status', $message);
            
        } catch (\Throwable $e) {
            DB::rollback();
            
            // Log error for debugging
            Log::error('User order cancellation failed', [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $errorMessage = 'Có lỗi xảy ra khi hủy đơn hàng. Vui lòng thử lại.';
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return back()->with('error', $errorMessage);
        }
    }

    public function requestRefund(Request $request, Order $order)
    {
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        // Calculate total amount paid (including installments)
        $totalPaid = $order->paymentTransactions()
            ->where('status', 'completed')
            ->sum('amount');

        // Validate refund eligibility
        if (!in_array($order->payment_status, ['partial', 'completed'])) {
            $message = 'Chỉ có thể yêu cầu hoàn tiền cho đơn hàng đã thanh toán hoặc thanh toán một phần.';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return back()->with('error', $message);
        }

        if ($totalPaid <= 0) {
            $message = 'Không thể yêu cầu hoàn tiền khi chưa có thanh toán nào.';
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
            'amount' => 'nullable|numeric|min:0|max:' . intval($totalPaid)
        ], [
            'reason.required' => 'Vui lòng nhập lý do hoàn tiền',
            'reason.max' => 'Lý do không được vượt quá 1000 ký tự',
            'amount.numeric' => 'Số tiền hoàn phải là số',
            'amount.min' => 'Số tiền hoàn phải lớn hơn 0',
            'amount.max' => 'Số tiền hoàn không được vượt quá ' . number_format($totalPaid, 0, ',', '.') . ' VNĐ (số tiền đã thanh toán)',
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
            'amount' => $request->amount ?: $totalPaid,
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


