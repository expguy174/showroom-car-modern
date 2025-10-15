<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = Promotion::query();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true)
                          ->where(function($q) {
                              $q->whereNull('start_date')->orWhere('start_date', '<=', now());
                          })
                          ->where(function($q) {
                              $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                          });
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
                case 'expired':
                    $query->where('end_date', '<', now());
                    break;
            }
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $promotions = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get statistics
        $totalPromotions = Promotion::count();
        $activePromotions = Promotion::where('is_active', true)
            ->where(function($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })->count();
        $inactivePromotions = Promotion::where('is_active', false)->count();
        $expiredPromotions = Promotion::where('end_date', '<', now())->count();

        // AJAX request - return only table partial
        if ($request->ajax()) {
            // Stats only request
            if ($request->has('stats_only')) {
                return response()->json([
                    'total' => $totalPromotions,
                    'active' => $activePromotions,
                    'inactive' => $inactivePromotions,
                    'expired' => $expiredPromotions,
                ]);
            }
            
            return view('admin.promotions.partials.table', compact('promotions'))->render();
        }

        // Regular request - return full view
        return view('admin.promotions.index', compact(
            'promotions', 
            'totalPromotions', 
            'activePromotions', 
            'inactivePromotions', 
            'expiredPromotions'
        ));
    }

    public function destroy(Promotion $promotion, Request $request)
    {
        try {
            // Check if promotion is currently active
            if ($promotion->is_active) {
                $message = "Không thể xóa khuyến mãi \"{$promotion->name}\" ({$promotion->code}) vì đang ở trạng thái HOẠT ĐỘNG. Vui lòng tạm dừng khuyến mãi trước khi xóa.";
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'action_suggestion' => 'deactivate'
                    ], 400);
                }
                return redirect()->route('admin.promotions.index')->with('error', $message);
            }

            // Check if promotion has been used
            if ($promotion->usage_count > 0) {
                $message = "KHÔNG THỂ XÓA khuyến mãi \"{$promotion->name}\" ({$promotion->code}) vì đã được sử dụng {$promotion->usage_count} lần. " .
                          "Đây là dữ liệu giao dịch quan trọng! Bạn chỉ có thể 'Tạm dừng' để ngừng hoạt động nhưng vẫn giữ lịch sử.";
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'action_suggestion' => 'deactivate',
                        'business_data' => [
                            'usage_count' => $promotion->usage_count
                        ]
                    ], 400);
                }
                return redirect()->route('admin.promotions.index')->with('error', $message);
            }

            // Safe to delete - no usage history
            $promotionName = $promotion->name;
            $promotionCode = $promotion->code;
            
            $promotion->delete();

            $message = "Đã xóa khuyến mãi \"{$promotionName}\" ({$promotionCode}) thành công!";
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
            
            return redirect()->route('admin.promotions.index')->with('success', $message);
            
        } catch (\Exception $e) {
            $message = 'Có lỗi xảy ra khi xóa khuyến mãi: ' . $e->getMessage();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }
            
            return redirect()->route('admin.promotions.index')->with('error', $message);
        }
    }

    public function toggle(Request $request, Promotion $promotion)
    {
        try {
            $promotion->is_active = $request->boolean('is_active');
            $promotion->save();

            $status = $promotion->is_active ? 'kích hoạt' : 'tạm dừng';
            
            return response()->json([
                'success' => true,
                'message' => "Đã {$status} khuyến mãi thành công!",
                'is_active' => $promotion->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thay đổi trạng thái khuyến mãi.'
            ], 500);
        }
    }

    public function show(Promotion $promotion)
    {
        return view('admin.promotions.show', compact('promotion'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:promotions,code',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed_amount,free_shipping,brand_specific',
            'discount_value' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
        ];

        // Conditional validation for discount_value
        if ($request->type && $request->type !== 'free_shipping') {
            $rules['discount_value'] = 'required|numeric|min:0';
            
            if (in_array($request->type, ['percentage', 'brand_specific'])) {
                $rules['discount_value'] .= '|max:100';
            }
        }

        try {
            $validated = $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Dữ liệu không hợp lệ.'
                ], 422);
            }
            throw $e;
        }

        $promotion = Promotion::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Khuyến mãi đã được tạo thành công.',
                'redirect' => route('admin.promotions.index')
            ]);
        }

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Khuyến mãi đã được tạo thành công.');
    }

    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:promotions,code,' . $promotion->id,
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed_amount,free_shipping,brand_specific',
            'discount_value' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
        ];

        // Conditional validation for discount_value
        if ($request->type && $request->type !== 'free_shipping') {
            $rules['discount_value'] = 'required|numeric|min:0';
            
            if (in_array($request->type, ['percentage', 'brand_specific'])) {
                $rules['discount_value'] .= '|max:100';
            }
        }

        try {
            $validated = $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Dữ liệu không hợp lệ.'
                ], 422);
            }
            throw $e;
        }

        $promotion->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Khuyến mãi đã được cập nhật thành công.',
                'redirect' => route('admin.promotions.index')
            ]);
        }

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Khuyến mãi đã được cập nhật thành công.');
    }

}
