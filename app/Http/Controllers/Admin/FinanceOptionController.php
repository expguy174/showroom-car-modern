<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FinanceOptionController extends Controller
{
    public function index(Request $request)
    {
        $query = FinanceOption::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bank_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
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
        
        $financeOptions = $query->orderBy('sort_order')->paginate(15);
        
        // Return partial view for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return view('admin.finance-options.partials.table', compact('financeOptions'))->render();
        }
        
        return view('admin.finance-options.index', compact('financeOptions'));
    }

    public function create()
    {
        return view('admin.finance-options.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:finance_options,code',
            'bank_name' => 'required|string|max:255',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'min_down_payment' => 'required|numeric|min:0|max:100',
            'min_tenure' => 'required|integer|min:1|max:360',
            'max_tenure' => 'required|integer|min:1|max:360',
            'min_loan_amount' => 'required|numeric|min:1000000',
            'max_loan_amount' => 'required|numeric|min:1000000',
            'processing_fee' => 'nullable|numeric|min:0',
            'requirements' => 'nullable|string',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ], [
            'code.unique' => 'Mã gói vay "' . $request->code . '" đã tồn tại. Vui lòng chọn mã khác.',
            'name.required' => 'Vui lòng nhập tên gói vay.',
            'code.required' => 'Vui lòng nhập mã gói vay.',
            'bank_name.required' => 'Vui lòng nhập tên ngân hàng.',
            'min_loan_amount.min' => 'Số tiền vay tối thiểu phải từ 1.000.000 đ trở lên.',
            'max_loan_amount.min' => 'Số tiền vay tối đa phải từ 1.000.000 đ trở lên.',
        ]);

        // Handle checkbox - check actual value
        $validated['is_active'] = $request->input('is_active') == '1' ? true : false;
        
        // Set default values for nullable fields
        $validated['processing_fee'] = $validated['processing_fee'] ?? 0;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $financeOption = FinanceOption::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thêm gói vay thành công!',
                'redirect' => route('admin.finance-options.index')
            ]);
        }

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
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:finance_options,code,' . $financeOption->id,
            'bank_name' => 'required|string|max:255',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'min_down_payment' => 'required|numeric|min:0|max:100',
            'min_tenure' => 'required|integer|min:1|max:360',
            'max_tenure' => 'required|integer|min:1|max:360',
            'min_loan_amount' => 'required|numeric|min:1000000',
            'max_loan_amount' => 'required|numeric|min:1000000',
            'processing_fee' => 'nullable|numeric|min:0',
            'requirements' => 'nullable|string',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ], [
            'code.unique' => 'Mã gói vay "' . $request->code . '" đã được sử dụng. Vui lòng chọn mã khác.',
            'name.required' => 'Vui lòng nhập tên gói vay.',
            'code.required' => 'Vui lòng nhập mã gói vay.',
            'bank_name.required' => 'Vui lòng nhập tên ngân hàng.',
            'min_loan_amount.min' => 'Số tiền vay tối thiểu phải từ 1.000.000 đ trở lên.',
            'max_loan_amount.min' => 'Số tiền vay tối đa phải từ 1.000.000 đ trở lên.',
        ]);

        // Handle checkbox - check actual value
        $validated['is_active'] = $request->input('is_active') == '1' ? true : false;
        
        // Set default values for nullable fields
        $validated['processing_fee'] = $validated['processing_fee'] ?? 0;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $financeOption->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật gói vay thành công!',
                'redirect' => route('admin.finance-options.index')
            ]);
        }

        return redirect()->route('admin.finance-options.index')
            ->with('success', 'Đã cập nhật gói trả góp thành công!');
    }

    public function destroy(FinanceOption $financeOption, Request $request)
    {
        // Detailed dependency analysis
        $ordersCount = 0;
        $pendingOrdersCount = 0;
        $completedOrdersCount = 0;
        $installmentsCount = 0;
        $unpaidInstallmentsCount = 0;
        
        // Check orders using this finance option
        try {
            if (Schema::hasTable('orders')) {
                $ordersCount = DB::table('orders')
                    ->where('finance_option_id', $financeOption->id)
                    ->whereNull('deleted_at')
                    ->count();
                    
                // Check pending orders (critical!)
                $pendingOrdersCount = DB::table('orders')
                    ->where('finance_option_id', $financeOption->id)
                    ->whereIn('status', ['pending', 'confirmed', 'processing', 'shipping'])
                    ->whereNull('deleted_at')
                    ->count();
                    
                // Check completed orders
                $completedOrdersCount = DB::table('orders')
                    ->where('finance_option_id', $financeOption->id)
                    ->whereIn('status', ['delivered', 'completed'])
                    ->whereNull('deleted_at')
                    ->count();
            }
        } catch (\Exception $e) {
            $ordersCount = 0;
        }
        
        // Check installments linked to this finance option
        try {
            if (Schema::hasTable('installments')) {
                $installmentsCount = DB::table('installments')
                    ->where('finance_option_id', $financeOption->id)
                    ->count();
                    
                // Check unpaid installments (very critical!)
                $unpaidInstallmentsCount = DB::table('installments')
                    ->where('finance_option_id', $financeOption->id)
                    ->whereIn('status', ['pending', 'overdue'])
                    ->count();
            }
        } catch (\Exception $e) {
            $installmentsCount = 0;
        }
        
        // FIRST: Check if finance option itself is active
        if ($financeOption->is_active) {
            $message = "Không thể xóa gói vay \"{$financeOption->name}\" ({$financeOption->bank_name}) vì đang ở trạng thái HOẠT ĐỘNG. Vui lòng tạm dừng gói vay trước khi xóa.";
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'action_suggestion' => 'deactivate',
                    'show_deactivate_button' => true
                ], 400);
            }
            return redirect()->route('admin.finance-options.index')->with('error', $message);
        }
        
        // SECOND: Check if has orders or installments (business data)
        if ($ordersCount > 0 || $installmentsCount > 0) {
            $businessData = [];
            if ($ordersCount > 0) {
                $businessData[] = "{$ordersCount} đơn hàng";
                if ($pendingOrdersCount > 0) {
                    $businessData[] = "({$pendingOrdersCount} đang xử lý)";
                }
                if ($completedOrdersCount > 0) {
                    $businessData[] = "({$completedOrdersCount} đã hoàn thành)";
                }
            }
            if ($installmentsCount > 0) {
                $businessData[] = "{$installmentsCount} kỳ trả góp";
                if ($unpaidInstallmentsCount > 0) {
                    $businessData[] = "({$unpaidInstallmentsCount} chưa thanh toán)";
                }
            }
            
            $message = "KHÔNG THỂ XÓA gói vay \"{$financeOption->name}\" ({$financeOption->bank_name}) vì có " . implode(' ', $businessData) . " đang sử dụng. " .
                      "Đây là dữ liệu giao dịch quan trọng! Bạn chỉ có thể 'Tạm dừng' để ngừng hoạt động nhưng vẫn giữ lịch sử.";
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'action_suggestion' => 'deactivate',
                    'show_deactivate_button' => true,
                    'business_data' => [
                        'orders_count' => $ordersCount,
                        'pending_orders' => $pendingOrdersCount,
                        'completed_orders' => $completedOrdersCount,
                        'installments_count' => $installmentsCount,
                        'unpaid_installments' => $unpaidInstallmentsCount
                    ]
                ], 422); // Unprocessable Entity
            }
            return redirect()->route('admin.finance-options.index')->with('error', $message);
        }
        
        // Safe to delete - no dependencies
        $financeOption->delete();
        
        $message = "Đã xóa gói vay \"{$financeOption->name}\" ({$financeOption->bank_name}) thành công!";
        
        if ($request->ajax() || $request->wantsJson()) {
            // Get updated stats after deletion
            $totalOptions = FinanceOption::count();
            $activeOptions = FinanceOption::where('is_active', true)->count();
            $inactiveOptions = FinanceOption::where('is_active', false)->count();
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'stats' => [
                    'total' => $totalOptions,
                    'active' => $activeOptions,
                    'inactive' => $inactiveOptions
                ]
            ]);
        }

        return redirect()->route('admin.finance-options.index')
            ->with('success', $message);
    }

    public function toggleActive(FinanceOption $financeOption, Request $request)
    {
        $financeOption->update(['is_active' => !$financeOption->is_active]);
        
        $status = $financeOption->is_active ? 'kích hoạt' : 'tắt';
        $message = "Đã {$status} gói: {$financeOption->bank_name} - {$financeOption->name}";
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }
        
        return redirect()->route('admin.finance-options.index')
            ->with('success', $message);
    }

    public function toggleStatus(FinanceOption $financeOption)
    {
        $financeOption->update(['is_active' => !$financeOption->is_active]);
        
        // Get updated stats
        $stats = [
            'total' => FinanceOption::count(),
            'active' => FinanceOption::where('is_active', true)->count(),
            'inactive' => FinanceOption::where('is_active', false)->count(),
        ];
        
        return response()->json([
            'success' => true,
            'is_active' => $financeOption->is_active,
            'stats' => $stats,
            'message' => 'Đã cập nhật trạng thái thành công!'
        ]);
    }
}
