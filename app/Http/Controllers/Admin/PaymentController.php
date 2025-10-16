<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentTransaction;
use App\Models\PaymentMethod;
use App\Models\Installment;
use App\Models\Refund;
use App\Models\User;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentTransaction::with(['user', 'order', 'paymentMethod']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method_id') && $request->payment_method_id) {
            $query->where('payment_method_id', $request->payment_method_id);
        }

        // payment_type has been removed from schema; skip this filter

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_number', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', '%' . $search . '%')
                         ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);
        $paymentMethods = PaymentMethod::all();
        $statuses = \App\Models\PaymentTransaction::STATUSES;
        $paymentTypes = [];

        return view('admin.payments.index', compact('transactions', 'paymentMethods', 'statuses', 'paymentTypes'));
    }

    public function show(PaymentTransaction $transaction)
    {
        $transaction->load(['user', 'order', 'paymentMethod', 'refunds']);
        
        return view('admin.payments.show', compact('transaction'));
    }

    public function edit(PaymentTransaction $transaction)
    {
        $paymentMethods = PaymentMethod::all();
        $users = User::all();
        $statuses = \App\Models\PaymentTransaction::STATUSES;
        
        return view('admin.payments.edit', compact('transaction', 'paymentMethods', 'users', 'statuses'));
    }

    public function update(Request $request, PaymentTransaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', \App\Models\PaymentTransaction::STATUSES),
            'amount' => 'required|numeric|min:0',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $data = $request->all();
        
        // Update payment_date if status is completed
        if ($request->status === 'completed' && $transaction->status !== 'completed') {
            $data['payment_date'] = now();
        }

        $transaction->update($data);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Cập nhật giao dịch thanh toán thành công!');
    }

    public function destroy(PaymentTransaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Đã xóa giao dịch thanh toán thành công!');
    }

    public function updateStatus(Request $request, PaymentTransaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', \App\Models\PaymentTransaction::STATUSES),
            'notes' => 'nullable|string|max:500',
        ]);

        $data = ['status' => $request->status];

        // Add payment_date if status is completed
        if ($request->status === 'completed') {
            $data['payment_date'] = now();
        }

        // Add notes if provided
        if ($request->notes) {
            $data['notes'] = $request->notes;
        }

        $transaction->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công!',
            'status' => $request->status
        ]);
    }

    public function dashboard()
    {
        // Today's transactions
        $todayTransactions = PaymentTransaction::whereDate('created_at', today())
            ->with(['user', 'paymentMethod'])
            ->get();

        // This month's transactions
        $monthTransactions = PaymentTransaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->with(['user', 'paymentMethod'])
            ->get();

        // Statistics
        $stats = [
            'total_transactions' => PaymentTransaction::count(),
            'total_completed' => PaymentTransaction::where('status', 'completed')->count(),
            'total_pending' => PaymentTransaction::where('status', 'pending')->count(),
            'total_failed' => PaymentTransaction::where('status', 'failed')->count(),
            'total_amount' => PaymentTransaction::where('status', 'completed')->sum('amount'),
            'today_amount' => $todayTransactions->where('status', 'completed')->sum('amount'),
            'month_amount' => $monthTransactions->where('status', 'completed')->sum('amount'),
        ];

        // Recent transactions
        $recentTransactions = PaymentTransaction::with(['user', 'paymentMethod'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Payment method distribution
        $paymentMethodStats = PaymentTransaction::where('status', 'completed')
            ->with('paymentMethod')
            ->get()
            ->groupBy('payment_method_id')
            ->map(function ($transactions) {
                return [
                    'count' => $transactions->count(),
                    'amount' => $transactions->sum('amount'),
                    'method' => $transactions->first()->paymentMethod->name ?? 'Unknown'
                ];
            });

        return view('admin.payments.dashboard', compact('todayTransactions', 'monthTransactions', 'stats', 'recentTransactions', 'paymentMethodStats'));
    }

    public function installments()
    {
        $installments = Installment::with(['user', 'order'])
            ->orderBy('due_date', 'asc')
            ->paginate(15);

        return view('admin.payments.installments', compact('installments'));
    }

    public function refunds(Request $request)
    {
        $query = Refund::with(['paymentTransaction.user', 'paymentTransaction.order']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%")
                  ->orWhereHas('paymentTransaction.user', function($userQuery) use ($search) {
                      $userQuery->whereHas('userProfile', function($profileQuery) use ($search) {
                          $profileQuery->where('name', 'like', "%{$search}%");
                      })->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $refunds = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Handle AJAX requests
        if ($request->ajax()) {
            return view('admin.payments.partials.refunds-table', compact('refunds'))->render();
        }

        return view('admin.payments.refunds', compact('refunds'));
    }

    public function updateRefundStatus(Request $request, Refund $refund)
    {
        $request->validate([
            // Chuẩn hoá theo enum của bảng refunds: pending, processing, refunded, failed
            'refund_status' => 'required|in:pending,processing,refunded,failed',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $data = ['refund_status' => $request->refund_status];

        if ($request->refund_status === 'processing') {
            $data['processed_at'] = now();
        } elseif ($request->refund_status === 'refunded') {
            $data['processed_at'] = now();
        }

        if ($request->admin_notes) {
            $data['admin_notes'] = $request->admin_notes;
        }

        $oldStatus = $refund->status;
        $refund->update($data);

        // Create OrderLog for refund status change
        if ($refund->paymentTransaction && $refund->paymentTransaction->order_id) {
            $order = $refund->paymentTransaction->order;
            $statusLabels = [
                'pending' => 'Chờ xử lý',
                'processing' => 'Đang xử lý', 
                'refunded' => 'Đã hoàn tiền',
                'failed' => 'Thất bại'
            ];

            \App\Models\OrderLog::create([
                'order_id' => $order->id,
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'action' => 'refund_status_updated',
                'message' => 'Admin cập nhật trạng thái yêu cầu hoàn tiền',
                'details' => [
                    'refund_id' => $refund->id,
                    'from_status' => $oldStatus,
                    'to_status' => $request->refund_status,
                    'from_label' => $statusLabels[$oldStatus] ?? $oldStatus,
                    'to_label' => $statusLabels[$request->refund_status] ?? $request->refund_status,
                    'amount' => $refund->amount,
                    'admin_notes' => $request->admin_notes,
                    'admin_id' => \Illuminate\Support\Facades\Auth::id(),
                    'admin_name' => \Illuminate\Support\Facades\Auth::user()->name ?? 'Admin',
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            // AUTO CANCEL ORDER when refund is completed
            if ($request->refund_status === 'refunded' && $order->status !== 'cancelled') {
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
                
                // Update order status
                $order->update(['status' => 'cancelled']);
                
                // Update payment status (keep partial if installment had paid, otherwise cancelled)
                if ($order->payment_status === 'pending') {
                    $order->update(['payment_status' => 'cancelled']);
                }
                
                // Log auto cancellation with full details
                \App\Models\OrderLog::create([
                    'order_id' => $order->id,
                    'user_id' => \Illuminate\Support\Facades\Auth::id(),
                    'action' => 'order_cancelled',
                    'message' => 'Tự động hủy đơn hàng sau khi hoàn tiền thành công',
                    'details' => [
                        'order_status' => ['from' => $oldOrderStatus, 'to' => 'cancelled'],
                        'payment_status' => ['from' => $oldPaymentStatus, 'to' => $order->payment_status],
                        'reason' => 'Auto-cancelled after refund completed',
                        'refund_id' => $refund->id,
                        'refund_amount' => $refund->amount,
                        'cancelled_installments' => $cancelledInstallments,
                        'cancelled_by' => \Illuminate\Support\Facades\Auth::user()->name ?? 'Admin',
                        'admin_id' => \Illuminate\Support\Facades\Auth::id(),
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái hoàn tiền thành công!',
            'status' => $request->refund_status
        ]);
    }

    public function export(Request $request)
    {
        $query = PaymentTransaction::with(['user', 'paymentMethod']);

        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_method_id') && $request->payment_method_id) {
            $query->where('payment_method_id', $request->payment_method_id);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->get();

        $filename = 'payment_transactions_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Mã giao dịch', 'Khách hàng', 'Số điện thoại', 'Email', 'Phương thức thanh toán',
                'Loại thanh toán', 'Số tiền', 'Trạng thái', 'Ngày tạo', 'Ngày xử lý'
            ]);

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->transaction_number,
                    $transaction->customer_name,
                    $transaction->customer_phone,
                    $transaction->customer_email,
                    $transaction->paymentMethod->name ?? 'N/A',
                    ucfirst($transaction->payment_type),
                    number_format($transaction->amount),
                    ucfirst($transaction->status),
                    $transaction->created_at->format('d/m/Y H:i'),
                    $transaction->processed_at ? $transaction->processed_at->format('d/m/Y H:i') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function reports()
    {
        // Monthly revenue for the last 12 months
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = PaymentTransaction::where('status', 'completed')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');
            
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue
            ];
        }

        // Payment method distribution
        $paymentMethodDistribution = PaymentTransaction::where('status', 'completed')
            ->with('paymentMethod')
            ->get()
            ->groupBy('payment_method_id')
            ->map(function ($transactions) {
                return [
                    'method' => $transactions->first()->paymentMethod->name ?? 'Unknown',
                    'count' => $transactions->count(),
                    'amount' => $transactions->sum('amount'),
                    'percentage' => round(($transactions->count() / PaymentTransaction::where('status', 'completed')->count()) * 100, 2)
                ];
            });

        // Daily transactions for the last 30 days
        $dailyTransactions = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = PaymentTransaction::whereDate('created_at', $date)->count();
            $amount = PaymentTransaction::where('status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('amount');
            
            $dailyTransactions[] = [
                'date' => $date->format('d/m'),
                'count' => $count,
                'amount' => $amount
            ];
        }

        return view('admin.payments.reports', compact('monthlyRevenue', 'paymentMethodDistribution', 'dailyTransactions'));
    }
}
