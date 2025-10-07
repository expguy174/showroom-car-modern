<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AccessoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Accessory::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('short_description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
                case 'featured':
                    $query->where('is_featured', true);
                    break;
                case 'on_sale':
                    $query->where('is_on_sale', true);
                    break;
                case 'new_arrival':
                    $query->where('is_new_arrival', true);
                    break;
                case 'bestseller':
                    $query->where('is_bestseller', true);
                    break;
                case 'in_stock':
                    $query->where('stock_status', 'in_stock');
                    break;
                case 'out_of_stock':
                    $query->where('stock_status', 'out_of_stock');
                    break;
            }
        }

        // Order by: featured first, then by created_at desc, then by id desc for stable pagination
        $accessories = $query->orderByDesc('is_featured')
                            ->orderByDesc('created_at')
                            ->orderByDesc('id')
                            ->paginate(15);

        // Calculate statistics
        $totalAccessories = Accessory::count();
        $activeAccessories = Accessory::where('is_active', true)->count();
        $inactiveAccessories = Accessory::where('is_active', false)->count();
        $featuredAccessories = Accessory::where('is_featured', true)->count();

        // Get categories for dropdown with Vietnamese translation
        $categoryTranslations = Accessory::getCategoryTranslations();

        $categories = Accessory::select('category')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->get()
            ->map(function ($item) use ($categoryTranslations) {
                return [
                    'value' => $item->category,
                    'label' => $categoryTranslations[$item->category] ?? ucfirst($item->category)
                ];
            })
            ->toArray();

        // Handle AJAX requests
        if ($request->ajax()) {
            return view('admin.accessories.partials.table', compact('accessories'))->render();
        }

        return view('admin.accessories.index', [
            'accessories' => $accessories,
            'totalAccessories' => $totalAccessories,
            'activeAccessories' => $activeAccessories,
            'inactiveAccessories' => $inactiveAccessories,
            'featuredAccessories' => $featuredAccessories,
            'categories' => $categories,
            'search' => $request->search,
        ]);
    }

    public function create()
    {
        return view('admin.accessories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:accessories,sku',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'category' => 'required|string',
            'subcategory' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'current_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'stock_status' => 'required|in:in_stock,low_stock,out_of_stock,discontinued',
            'is_active' => 'required|boolean',
            'is_featured' => 'boolean',
            'is_on_sale' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_bestseller' => 'boolean',
        ]);

        Accessory::create(array_merge($validated, [
            'slug' => \Str::slug($validated['name']),
            'is_featured' => $request->boolean('is_featured'),
            'is_on_sale' => $request->boolean('is_on_sale'),
            'is_new_arrival' => $request->boolean('is_new_arrival'),
            'is_bestseller' => $request->boolean('is_bestseller'),
        ]));

        return redirect()->route('admin.accessories.index')->with('success', 'Thêm phụ kiện thành công!');
    }

    public function edit($id)
    {
        $accessory = Accessory::findOrFail($id);
        return view('admin.accessories.edit', compact('accessory'));
    }

    public function update(Request $request, $id)
    {
        $accessory = Accessory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:accessories,sku,' . $id,
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'category' => 'required|string',
            'subcategory' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'current_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'stock_status' => 'required|in:in_stock,low_stock,out_of_stock,discontinued',
            'is_active' => 'required|boolean',
            'is_featured' => 'boolean',
            'is_on_sale' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_bestseller' => 'boolean',
        ]);

        $accessory->update(array_merge($validated, [
            'slug' => Str::slug($validated['name']),
            'is_featured' => $request->boolean('is_featured'),
            'is_on_sale' => $request->boolean('is_on_sale'),
            'is_new_arrival' => $request->boolean('is_new_arrival'),
            'is_bestseller' => $request->boolean('is_bestseller'),
        ]));

        return redirect()->route('admin.accessories.index')->with('success', 'Cập nhật phụ kiện thành công!');
    }

    public function show(Accessory $accessory)
    {
        // Eager load all relationships for comprehensive display
        $accessory->load([
            // No direct relationships like CarVariant, but we can load related data if exists
        ]);

        // Calculate statistics
        $stats = [
            'total_accessories' => Accessory::count(),
            'same_category' => Accessory::where('category', $accessory->category)->count(),
            'active_accessories' => Accessory::where('is_active', true)->count(),
            'featured_accessories' => Accessory::where('is_featured', true)->count()
        ];

        return view('admin.accessories.show', compact('accessory', 'stats'));
    }

    public function destroy($id)
    {
        $accessory = Accessory::findOrFail($id);
        
        // Check for orders (critical business data) - via order_items polymorphic
        $ordersCount = 0;
        $pendingOrdersCount = 0;
        $completedOrdersCount = 0;
        
        try {
            if (Schema::hasTable('order_items') && Schema::hasTable('orders')) {
                // Count orders that contain this accessory
                $ordersCount = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.item_type', 'accessory')
                    ->where('order_items.item_id', $accessory->id)
                    ->whereNull('orders.deleted_at') // Exclude soft deleted orders
                    ->count();
                    
                // Check order statuses for detailed reporting
                $pendingOrdersCount = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.item_type', 'accessory')
                    ->where('order_items.item_id', $accessory->id)
                    ->whereIn('orders.status', ['pending', 'confirmed', 'shipping'])
                    ->whereNull('orders.deleted_at')
                    ->count();
                        
                $completedOrdersCount = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.item_type', 'accessory')
                    ->where('order_items.item_id', $accessory->id)
                    ->whereIn('orders.status', ['delivered'])
                    ->whereNull('orders.deleted_at')
                    ->count();
            }
            
            // Fallback: Check direct accessory_id column if exists (legacy support)
            if ($ordersCount === 0 && Schema::hasTable('orders') && Schema::hasColumn('orders', 'accessory_id')) {
                $ordersCount = DB::table('orders')
                    ->where('accessory_id', $accessory->id)
                    ->whereNull('deleted_at')
                    ->count();
            }
        } catch (\Exception $e) {
            // If tables don't exist or have different structure, skip check
            $ordersCount = 0;
        }

        // Check if accessory is currently active (first priority - like CarVariant)
        if ($accessory->is_active) {
            $message = "Không thể xóa phụ kiện \"{$accessory->name}\" vì đang ở trạng thái hoạt động. Vui lòng tạm dừng phụ kiện trước khi xóa.";
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'action_suggestion' => 'deactivate',
                    'show_deactivate_button' => true
                ], 400);
            }
            return redirect()->route('admin.accessories.index')->with('error', $message);
        }

        // CRITICAL: Never delete if has business transaction data (second priority)
        if ($ordersCount > 0) {
            $businessData = [];
            if ($ordersCount > 0) {
                $businessData[] = "{$ordersCount} đơn hàng";
                if ($pendingOrdersCount > 0) {
                    $businessData[] = "({$pendingOrdersCount} đang xử lý)";
                }
                if ($completedOrdersCount > 0) {
                    $businessData[] = "({$completedOrdersCount} đã giao)";
                }
            }
            
            $businessDataText = implode(' ', $businessData);
            $message = "KHÔNG THỂ XÓA phụ kiện \"{$accessory->name}\" vì đã có {$businessDataText}. " .
                      "Đây là dữ liệu giao dịch quan trọng không được phép xóa! Bạn chỉ có thể 'Tạm dừng' thay vì xóa.";
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'business_data' => [
                        'total_orders' => $ordersCount,
                        'pending_orders' => $pendingOrdersCount,
                        'completed_orders' => $completedOrdersCount
                    ],
                    'action_suggestion' => 'deactivate'
                ], 400);
            }
            return redirect()->route('admin.accessories.index')->with('error', $message);
        }

        // Delete image if exists
        if ($accessory->image_path && Storage::disk('public')->exists($accessory->image_path)) {
            Storage::disk('public')->delete($accessory->image_path);
        }

        // Safe to delete - no business constraints
        $accessoryName = $accessory->name;
        $categoryName = $accessory->category ? $this->getCategoryDisplayName($accessory->category) : 'Không xác định';
        
        // Perform deletion
        $accessory->delete();
        
        $successMessage = "✅ Đã xóa phụ kiện \"{$accessoryName}\" (danh mục: {$categoryName}) thành công! " .
                         "Tất cả dữ liệu liên quan đã được xóa khỏi hệ thống.";
        
        if (request()->wantsJson() || request()->ajax()) {
            // Calculate updated statistics for stats cards
            $stats = [
                'active' => Accessory::where('is_active', true)->count(),
                'inactive' => Accessory::where('is_active', false)->count(),
                'total' => Accessory::count(),
                'featured' => Accessory::where('is_featured', true)->count()
            ];
            
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'stats' => $stats
            ]);
        }
        
        return redirect()->route('admin.accessories.index')->with('success', 
            "Đã xóa phụ kiện \"{$accessoryName}\" thành công!"
        );
    }

    public function toggleStatus(Request $request, $id)
    {
        $accessory = Accessory::findOrFail($id);
        
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $newStatus = $request->is_active;
        $accessory->update(['is_active' => $newStatus]);

        $statusText = $newStatus ? 'kích hoạt' : 'tạm dừng';
        
        // Calculate updated statistics for stats cards
        $stats = [
            'active' => Accessory::where('is_active', true)->count(),
            'inactive' => Accessory::where('is_active', false)->count(),
            'total' => Accessory::count(),
            'featured' => Accessory::where('is_featured', true)->count()
        ];
        
        return response()->json([
            'success' => true,
            'message' => "Đã {$statusText} phụ kiện \"{$accessory->name}\" thành công!",
            'is_active' => $newStatus,
            'stats' => $stats
        ]);
    }

    /**
     * Get category display name in Vietnamese
     */
    private function getCategoryDisplayName($category)
    {
        $categoryMap = [
            'electronics' => 'Điện tử',
            'interior' => 'Nội thất',
            'exterior' => 'Ngoại thất',
            'safety' => 'An toàn',
            'performance' => 'Hiệu suất',
            'comfort' => 'Tiện nghi',
            'maintenance' => 'Bảo dưỡng',
            'decoration' => 'Trang trí'
        ];
        return $categoryMap[$category] ?? $category;
    }
}