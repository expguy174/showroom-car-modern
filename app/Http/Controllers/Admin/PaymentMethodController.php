<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentMethod::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('provider', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
            }
        }
        
        $paymentMethods = $query->orderBy('sort_order')->paginate(15);
        
        // Return partial view for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return view('admin.payment-methods.partials.table', compact('paymentMethods'))->render();
        }
        
        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('admin.payment-methods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:payment_methods',
            'provider' => 'nullable|string|max:255',
            'type' => 'required|in:online,offline',
            'fee_flat' => 'nullable|numeric|min:0',
            'fee_percent' => 'nullable|numeric|min:0|max:100',
            'config' => 'nullable|json',
            'notes' => 'nullable|string'
        ]);

        PaymentMethod::create($request->all());

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Phương thức thanh toán đã được tạo thành công!');
    }

    public function show(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.show', compact('paymentMethod'));
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:payment_methods,code,' . $paymentMethod->id,
            'provider' => 'nullable|string|max:255',
            'type' => 'required|in:online,offline',
            'fee_flat' => 'nullable|numeric|min:0',
            'fee_percent' => 'nullable|numeric|min:0|max:100',
            'config' => 'nullable|json',
            'notes' => 'nullable|string'
        ]);

        $paymentMethod->update($request->all());

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Phương thức thanh toán đã được cập nhật thành công!');
    }

    public function destroy(PaymentMethod $paymentMethod, Request $request)
    {
        $paymentMethod->delete();
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa phương thức thanh toán thành công!'
            ]);
        }
        
        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Đã xóa phương thức thanh toán thành công!');
    }

    public function toggleActive(PaymentMethod $paymentMethod, Request $request)
    {
        $paymentMethod->update(['is_active' => !$paymentMethod->is_active]);
        
        $status = $paymentMethod->is_active ? 'kích hoạt' : 'tắt';
        $message = "Đã {$status} phương thức: {$paymentMethod->name}";
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }
        
        return redirect()->route('admin.payment-methods.index')
            ->with('success', $message);
    }

    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update(['is_active' => !$paymentMethod->is_active]);
        
        // Get updated stats
        $stats = [
            'total' => PaymentMethod::count(),
            'active' => PaymentMethod::where('is_active', true)->count(),
            'inactive' => PaymentMethod::where('is_active', false)->count(),
        ];
        
        return response()->json([
            'success' => true,
            'is_active' => $paymentMethod->is_active,
            'stats' => $stats,
            'message' => 'Đã cập nhật trạng thái thành công!'
        ]);
    }
}
