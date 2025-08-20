<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Lead;
use App\Models\Deposit;
use App\Models\PaymentTransaction;
use App\Models\PaymentMethod;
use App\Models\Order;
use App\Models\Installment;
use App\Models\Refund;
use App\Models\Inventory;
use App\Models\CarVariant;
use Carbon\Carbon;

class PaymentController extends Controller
{




    public function index()
    {
        $transactions = PaymentTransaction::where('user_id', Auth::id())
            ->with(['order', 'paymentMethod'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $installments = Installment::where('user_id', Auth::id())
            ->with(['order'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.payments.index', compact('transactions', 'installments'));
    }

    public function show(PaymentTransaction $transaction)
    {
        // Check if user owns this transaction
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->load(['order', 'paymentMethod', 'refunds']);

        return view('user.payments.show', compact('transaction'));
    }

    public function create(Request $request)
    {
        $orderId = $request->get('order_id');
        $inventoryId = $request->get('inventory_id');
        
        $order = null;
        $inventory = null;
        
        if ($orderId) {
            $order = Order::where('id', $orderId)
                ->where('user_id', Auth::id())
                ->with(['items.item'])
                ->firstOrFail();
        }
        
        if ($inventoryId) {
            $inventory = Inventory::where('id', $inventoryId)
                ->with(['carVariant.carModel.carBrand'])
                ->firstOrFail();
        }

        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        return view('user.payments.create', compact('order', 'inventory', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'nullable|exists:orders,id',
            'inventory_id' => 'nullable|exists:inventories,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'payment_type' => 'required|in:full,partial,installment',
            'installment_terms' => 'nullable|integer|min:1|max:60',
            'down_payment' => 'nullable|numeric|min:0',
            'monthly_payment' => 'nullable|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'card_number' => 'prohibited',
            'card_holder' => 'nullable|string|max:255',
            'card_expiry' => 'nullable|string|max:5',
            'card_cvv' => 'prohibited',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'prohibited',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $transaction = app(\App\Application\Payments\UseCases\CreatePaymentTransaction::class)->handle([
            'user_id' => Auth::id(),
            'order_id' => $request->order_id,
            'payment_method_id' => $request->payment_method_id,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'payment_type' => $request->payment_type,
            'installment_terms' => $request->installment_terms,
            'down_payment' => $request->down_payment,
            'reference_number' => $request->reference_number,
            'notes' => $request->notes,
        ]);

        return redirect()->route('user.payments.show', $transaction->id)
            ->with('success', 'Giao dịch thanh toán đã được tạo thành công!');
    }

    public function processPayment(Request $request, PaymentTransaction $transaction)
    {
        // Check if user owns this transaction
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_data' => 'required|array',
        ]);

        $processed = app(\App\Application\Payments\UseCases\ProcessPayment::class)->handle($transaction, $request->payment_data);

        return response()->json([
            'success' => $processed->status === 'completed',
            'message' => $processed->status === 'completed' ? 'Thanh toán thành công!' : 'Thanh toán thất bại!',
            'transaction_id' => $processed->id,
        ]);
    }

    public function calculateInstallment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'down_payment' => 'required|numeric|min:0',
            'installment_terms' => 'required|integer|min:1|max:60',
            'interest_rate' => 'required|numeric|min:0|max:100',
        ]);

        $amount = $request->amount;
        $downPayment = $request->down_payment;
        $terms = $request->installment_terms;
        $interestRate = $request->interest_rate;

        $loanAmount = $amount - $downPayment;
        $monthlyInterestRate = $interestRate / 100 / 12;
        
        if ($monthlyInterestRate > 0) {
            $monthlyPayment = $loanAmount * ($monthlyInterestRate * pow(1 + $monthlyInterestRate, $terms)) / (pow(1 + $monthlyInterestRate, $terms) - 1);
        } else {
            $monthlyPayment = $loanAmount / $terms;
        }

        $totalInterest = ($monthlyPayment * $terms) - $loanAmount;
        $totalAmount = $monthlyPayment * $terms;

        return response()->json([
            'monthly_payment' => round($monthlyPayment, 2),
            'total_interest' => round($totalInterest, 2),
            'total_amount' => round($totalAmount, 2),
            'down_payment' => $downPayment,
            'loan_amount' => $loanAmount,
        ]);
    }

    public function refund(Request $request, PaymentTransaction $transaction)
    {
        // Check if user owns this transaction
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'refund_amount' => 'required|numeric|min:0|max:' . $transaction->amount,
            'refund_reason' => 'required|string|max:500',
        ]);

        // Create refund record
        $refund = Refund::create([
            'payment_transaction_id' => $transaction->id,
            'refund_amount' => $request->refund_amount,
            'refund_reason' => $request->refund_reason,
            'refund_status' => 'pending',
            'requested_at' => now(),
        ]);

        return redirect()->route('user.payments.show', $transaction->id)
            ->with('success', 'Yêu cầu hoàn tiền đã được gửi thành công!');
    }

    public function installmentHistory()
    {
        $installments = Installment::where('user_id', Auth::id())
            ->with(['order'])
            ->orderBy('due_date', 'asc')
            ->paginate(15);

        return view('user.payments.installment-history', compact('installments'));
    }

    public function paymentMethods()
    {
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        $userTransactions = PaymentTransaction::where('user_id', Auth::id())
            ->with(['paymentMethod'])
            ->get()
            ->groupBy('payment_method_id');

        return view('user.payments.payment-methods', compact('paymentMethods', 'userTransactions'));
    }

    public function transactionHistory()
    {
        $transactions = PaymentTransaction::where('user_id', Auth::id())
            ->with(['order', 'paymentMethod', 'refunds'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.payments.transaction-history', compact('transactions'));
    }

    public function downloadReceipt(PaymentTransaction $transaction)
    {
        // Check if user owns this transaction
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->load(['order', 'paymentMethod']);

        // Generate PDF receipt
        // TODO: Implement PDF generation when PDF library is available
        // $pdf = \PDF::loadView('user.payments.receipt', compact('transaction'));
        // return $pdf->download('receipt_' . $transaction->transaction_number . '.pdf');
        
        // For now, return a simple response
        return response()->json([
            'message' => 'PDF generation not yet implemented',
            'transaction_number' => $transaction->transaction_number
        ]);
    }

    // Gateway simulation moved to Use Case (ProcessPayment)
}
