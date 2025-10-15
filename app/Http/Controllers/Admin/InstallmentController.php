<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Installment;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\PaymentMethod;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $orders = $query->orderByDesc('created_at')
                       ->paginate(20)
                       ->withQueryString();

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

        return view('admin.installments.show', compact('order'));
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
                ? "Đã xác nhận thanh toán kỳ cuối cùng (kỳ {$installment->installment_number}). Hoàn thành toàn bộ lịch trả góp!"
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

            // 5. Update payment status AFTER logging installment completion
            if ($isLastInstallment) {
                $oldPaymentStatus = $installment->order->payment_status;
                $installment->order->update(['payment_status' => 'completed']);
                
                // Create payment status change log if status actually changed
                if ($oldPaymentStatus !== 'completed') {
                    OrderLog::create([
                        'order_id' => $installment->order_id,
                        'user_id' => Auth::id(),
                        'action' => 'payment_status_changed',
                        'message' => 'Trạng thái thanh toán được cập nhật tự động sau khi hoàn thành tất cả kỳ trả góp',
                        'details' => [
                            'from' => $oldPaymentStatus,
                            'to' => 'completed',
                            'trigger' => 'installment_completion',
                            'last_installment_id' => $installment->id,
                            'admin_id' => Auth::id(),
                        ],
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ]);
                }
            }

            // 6. Create notification for user
            $notificationTitle = "Đơn hàng #{$installment->order->order_number}";
            
            $notificationMessage = $isLastInstallment
                ? "🎉 Chúc mừng! Đã hoàn thành {$installment->order->tenure_months} kỳ trả góp. Cảm ơn bạn đã tin tưởng!"
                : "Kỳ {$installment->installment_number} (" . number_format($installment->amount) . " VNĐ) đã được xác nhận thanh toán.";

            \App\Models\Notification::create([
                'user_id' => $installment->user_id,
                'type' => 'installment',
                'title' => $notificationTitle,
                'message' => $notificationMessage,
                'is_read' => false,
            ]);

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
}
