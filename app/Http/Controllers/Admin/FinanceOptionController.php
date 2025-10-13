<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceOption;
use Illuminate\Http\Request;

class FinanceOptionController extends Controller
{
    public function index()
    {
        $financeOptions = FinanceOption::orderBy('sort_order')->paginate(15);
        return view('admin.finance-options.index', compact('financeOptions'));
    }

    public function create()
    {
        return view('admin.finance-options.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'program_name' => 'required|string|max:255',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'min_down_payment' => 'required|numeric|min:0|max:100',
            'max_tenure_months' => 'required|integer|min:1|max:360',
            'processing_fee' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        FinanceOption::create($validated);

        return redirect()->route('admin.finance-options.index')
            ->with('success', 'Đã tạo gói trả góp thành công!');
    }

    public function edit(FinanceOption $financeOption)
    {
        return view('admin.finance-options.edit', compact('financeOption'));
    }

    public function update(Request $request, FinanceOption $financeOption)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'program_name' => 'required|string|max:255',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'min_down_payment' => 'required|numeric|min:0|max:100',
            'max_tenure_months' => 'required|integer|min:1|max:360',
            'processing_fee' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $financeOption->update($validated);

        return redirect()->route('admin.finance-options.index')
            ->with('success', 'Đã cập nhật gói trả góp thành công!');
    }

    public function destroy(FinanceOption $financeOption)
    {
        $financeOption->delete();

        return redirect()->route('admin.finance-options.index')
            ->with('success', 'Đã xóa gói trả góp thành công!');
    }

    public function toggleActive(FinanceOption $financeOption)
    {
        $financeOption->update(['is_active' => !$financeOption->is_active]);
        
        $status = $financeOption->is_active ? 'kích hoạt' : 'tắt';
        return redirect()->route('admin.finance-options.index')
            ->with('success', "Đã {$status} gói: {$financeOption->bank_name} - {$financeOption->program_name}");
    }
}
