<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Installment;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\PaymentMethod;
use App\Models\PaymentTransaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\InstallmentPaid;

class InstallmentController extends Controller
{
    public function index(Request $request)
    {
        // Query orders that have installments
        $query = Order::with(['user.userProfile', 'financeOption', 'items.item', 'installments'])
            ->whereHas('installments');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user.userProfile', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by finance option
        if ($request->filled('finance_option_id')) {
            $query->where('finance_option_id', $request->finance_option_id);
        }

        $orders = $query->orderByDesc('created_at')->paginate(15);
        
        // Append query parameters to pagination links
        $orders->appends($request->except(['page', 'ajax', 'with_stats']));

        // Calculate statistics
        $totalOrders = Order::whereHas('installments')->count();
        $stats = [
            'total' => $totalOrders,
            'pending' => Order::whereHas('installments', function ($q) {
                $q->where('status', 'pending');
            })->count(),
            'paid' => Order::whereHas('installments', function ($q) {
                $q->whereIn('status', ['pending', 'overdue']);
            }, '=', 0)->whereHas('installments')->count(),
            'overdue' => Order::whereHas('installments', function ($q) {
                $q->where('status', 'overdue');
            })->count(),
            'total_pending_amount' => Installment::where('status', 'pending')->sum('amount'),
            'total_overdue_amount' => Installment::where('status', 'overdue')->sum('amount'),
            'total_paid_amount' => Installment::where('status', 'paid')->sum('amount'),
        ];

        // Get finance options for filter
        $financeOptions = \App\Models\FinanceOption::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Return partial view for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return view('admin.installments.partials.table', compact('orders'))->render();
        }

        return view('admin.installments.index', compact('orders', 'stats', 'financeOptions'));
    }

    public function show(Order $order)
    {
        // Load relationships
        $order->load([
            'user.userProfile',
            'financeOption',
            'items.item', // Load order items for product info
            'installments' => function ($query) {
                $query->orderBy('installment_number');
            },
            'installments.paymentTransaction.paymentMethod'
        ]);

        // Check if order has installments
        if ($order->installments->isEmpty()) {
            return redirect()->route('admin.installments.index')
                ->with('warning', 'Đơn hàng này không có lịch trả góp.');
        }

        // Get manual verification payment methods for installment confirmation
        $manualPaymentMethods = $this->getManualPaymentMethods();
        
        return view('admin.installments.show', compact('order', 'manualPaymentMethods'));
    }

    /**
     * Get manual verification payment methods for installment confirmation
     * Excludes auto-confirm online gateways that don't allow admin control
     */
    private function getManualPaymentMethods()
    {
        return \App\Models\PaymentMethod::where('is_active', true)
            ->whereNotIn('code', ['vnpay', 'momo', 'zalopay', 'paypal', 'stripe'])
            ->orderBy('name')
            ->get();
    }

    public function markAsPaid(Request $request, Installment $installment)
    {
        if ($installment->status === 'paid') {
            return redirect()->route('admin.installments.show', $installment->order_id)->with('warning', 'Kỳ trả góp này đã được thanh toán.');
        }

        $validated = $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'payment_date' => 'required|date',
            'payment_time' => 'required',
            'notes' => 'nullable|string|max:500',
        ], [
            'payment_method_id.required' => 'Vui lòng chọn phương thức thanh toán.',
            'payment_date.required' => 'Vui lòng chọn ngày thanh toán.',
            'payment_time.required' => 'Vui lòng chọn giờ thanh toán.',
        ]);

        // Combine date and time into datetime
        $paymentDateTime = $validated['payment_date'] . ' ' . $validated['payment_time'];

        DB::beginTransaction();
        try {
            // 1. Create Payment Transaction
            $transaction = PaymentTransaction::create([
                'order_id' => $installment->order_id,
                'user_id' => $installment->user_id,
                'payment_method_id' => $validated['payment_method_id'],
                'transaction_number' => 'INST-' . time() . '-' . rand(1000, 9999),
                'amount' => $installment->amount,
                'currency' => 'VND',
                'status' => 'completed',
                'payment_date' => $paymentDateTime,
                'notes' => $validated['notes'] ?? "Thanh toán kỳ {$installment->installment_number}",
            ]);

            // 2. Update Installment
            $installment->update([
                'status' => 'paid',
                'paid_at' => $paymentDateTime,
                'payment_transaction_id' => $transaction->id,
            ]);

            // 3. Check if all installments paid
            $remainingUnpaid = Installment::where('order_id', $installment->order_id)
                ->whereIn('status', ['pending', 'overdue'])
                ->count();

            $isLastInstallment = false;
            if ($remainingUnpaid === 0) {
                $isLastInstallment = true;
            }

            // 4. Log installment action FIRST (before payment status change)
            $logMessage = $isLastInstallment
                ? "Đã thanh toán kỳ cuối cùng (kỳ {$installment->installment_number}/{$installment->order->tenure_months})"
                : "Đã xác nhận thanh toán kỳ {$installment->installment_number}";

            OrderLog::create([
                'order_id' => $installment->order_id,
                'user_id' => Auth::id(),
                'action' => $isLastInstallment ? 'installment_completed' : 'installment_paid',
                'message' => $logMessage,
                'details' => [
                    'installment_id' => $installment->id,
                    'installment_number' => $installment->installment_number,
                    'amount' => $installment->amount,
                    'transaction_id' => $transaction->id,
                    'payment_method_id' => $validated['payment_method_id'],
                    'admin_id' => Auth::id(),
                    'is_last_installment' => $isLastInstallment,
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // 5. Installment payments don't change order payment_status
            // Down payment confirmation handles payment_status = 'partial'
            // Only last installment changes payment_status to 'completed'
            
            // Update payment status to 'completed' when all installments are paid
            if ($isLastInstallment) {
                $order = $installment->order;
                $oldPaymentStatus = $order->payment_status;
                $order->update(['payment_status' => 'completed']);

                OrderLog::create([
                    'order_id' => $installment->order_id,
                    'user_id' => Auth::id(),
                    'action' => 'payment_completed',
                    'message' => 'Hoàn thành thanh toán đầy đủ sau khi hoàn thành tất cả kỳ trả góp',
                    'details' => [
                        'from' => $oldPaymentStatus,
                        'to' => 'completed',
                        'trigger' => 'installments_completed',
                        'total_installments' => $order->tenure_months,
                        'last_installment_id' => $installment->id,
                        'admin_id' => Auth::id(),
                    ],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }

            // 6. Create notification for installment payment
            if ($installment->installment_number == 1) {
                // First installment paid - order can now be processed
                Notification::create([
                    'user_id' => $installment->user_id,
                    'type' => 'payment_completed',
                    'title' => "Kỳ đầu đã thanh toán - #{$installment->order->order_number}",
                    'message' => "💳 Kỳ đầu tiên đã được xác nhận. Chúng tôi sẽ tiến hành xử lý và giao hàng. Bạn có thể tiếp tục thanh toán các kỳ tiếp theo theo lịch.",
                    'is_read' => false,
                ]);
            } elseif ($isLastInstallment) {
                // Last installment - comprehensive notification
                Notification::create([
                    'user_id' => $installment->user_id,
                    'type' => 'payment_completed',
                    'title' => "Hoàn thành thanh toán - #{$installment->order->order_number}",
                    'message' => "🎉 Chúc mừng! Đã hoàn thành kỳ cuối ({$installment->installment_number}/{$installment->order->tenure_months}) và toàn bộ chương trình trả góp. Đơn hàng đã được thanh toán đầy đủ!",
                    'is_read' => false,
                ]);
            } else {
                // Regular installment payment
                Notification::create([
                    'user_id' => $installment->user_id,
                    'type' => 'installment',
                    'title' => "Đơn hàng #{$installment->order->order_number}",
                    'message' => "Kỳ {$installment->installment_number} (" . number_format($installment->amount) . " VNĐ) đã được xác nhận thanh toán.",
                    'is_read' => false,
                ]);
            }

            // Clear notification cache for user
            $this->clearUserNotificationCache($installment->user_id);

            DB::commit();

            // 6. Send email notification
            try {
                if ($installment->user && $installment->user->email) {
                    Mail::to($installment->user->email)->send(new InstallmentPaid($installment, $isLastInstallment));
                }
            } catch (\Exception $e) {
                Log::error('Failed to send installment paid email', [
                    'installment_id' => $installment->id,
                    'error' => $e->getMessage()
                ]);
            }

            if ($request->ajax() || $request->wantsJson()) {
                // Get updated stats after payment
                $stats = [
                    'total' => Installment::count(),
                    'pending' => Installment::where('status', 'pending')->count(),
                    'paid' => Installment::where('status', 'paid')->count(),
                    'overdue' => Installment::where('status', 'overdue')->count(),
                ];

                // Special message for last installment
                $message = $isLastInstallment 
                    ? "🎉 Đã xác nhận thanh toán kỳ cuối cùng (kỳ {$installment->installment_number})! Đơn hàng #{$installment->order->order_number} đã hoàn thành toàn bộ lịch trả góp!"
                    : "Đã xác nhận thanh toán kỳ {$installment->installment_number} thành công!";

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'stats' => $stats,
                    'is_last_installment' => $isLastInstallment,
                ]);
            }

            $message = $isLastInstallment
                ? "Đã xác nhận thanh toán kỳ cuối cùng (kỳ {$installment->installment_number})! Đơn hàng đã hoàn thành toàn bộ lịch trả góp!"
                : "Đã xác nhận thanh toán kỳ {$installment->installment_number} thành công!";

            return redirect()->route('admin.installments.show', $installment->order_id)->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error marking installment as paid', [
                'installment_id' => $installment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->route('admin.installments.show', $installment->order_id)->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function cancel(Request $request, Installment $installment)
    {
        if ($installment->status === 'paid') {
            return redirect()->route('admin.installments.show', $installment->order_id)->with('error', 'Không thể hủy kỳ đã thanh toán.');
        }

        $validated = $request->validate([
            'cancel_reason' => 'required|string|max:500',
        ], [
            'cancel_reason.required' => 'Vui lòng nhập lý do hủy.',
        ]);

        DB::beginTransaction();
        try {
            $installment->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            OrderLog::create([
                'order_id' => $installment->order_id,
                'user_id' => Auth::id(),
                'action' => 'installment_cancelled',
                'message' => "Đã hủy kỳ {$installment->installment_number}",
                'details' => [
                    'installment_id' => $installment->id,
                    'cancel_reason' => $validated['cancel_reason'],
                    'admin_id' => Auth::id(),
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('admin.installments.show', $installment->order_id)->with('success', "Đã hủy kỳ {$installment->installment_number}.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.installments.show', $installment->order_id)->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
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
}
