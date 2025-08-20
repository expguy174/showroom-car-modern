<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('name')->paginate(10);
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

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Phương thức thanh toán đã được xóa thành công!');
    }

    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update(['is_active' => !$paymentMethod->is_active]);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Trạng thái đã được cập nhật!');
    }
}
