<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarVariant;
use App\Models\CarModel;
use App\Models\CarBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CarVariantController extends Controller
{
    public function index(Request $request)
    {
        $query = CarVariant::with(['carModel.carBrand', 'colors', 'images']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('carModel', function($model) use ($search) {
                      $model->where('name', 'like', "%{$search}%")
                            ->orWhereHas('carBrand', function($brand) use ($search) {
                                $brand->where('name', 'like', "%{$search}%");
                            });
                  });
            });
        }

        // Car Model filter
        if ($request->filled('car_model_id')) {
            $query->where('car_model_id', $request->car_model_id);
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
            }
        }


        $carVariants = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get car models for filter dropdown - format for dropdown component
        $carModels = CarModel::with('carBrand')->orderBy('name')->get()->map(function($model) {
            return [
                'id' => $model->id,
                'name' => $model->name,
                'carBrand' => $model->carBrand ? $model->carBrand->name : ''
            ];
        });

        // Calculate stats - ensure all are integers
        $totalVariants = (int) CarVariant::count();
        $activeVariants = (int) CarVariant::where('is_active', true)->count();
        $inactiveVariants = (int) CarVariant::where('is_active', false)->count();
        $featuredVariants = (int) CarVariant::where('is_featured', true)->count();
        $onSaleVariants = (int) CarVariant::where('is_on_sale', true)->count();
        $newArrivalVariants = (int) CarVariant::where('is_new_arrival', true)->count();
        $bestsellerVariants = (int) CarVariant::where('is_bestseller', true)->count();

        // If this is an AJAX request, return only the table partial
        if ($request->ajax()) {
            return view('admin.carvariants.partials.table', compact('carVariants'))->render();
        }

        return view('admin.carvariants.index', compact(
            'carVariants', 
            'carModels', 
            'totalVariants',
            'activeVariants',
            'inactiveVariants',
            'featuredVariants',
            'onSaleVariants',
            'newArrivalVariants',
            'bestsellerVariants'
        ));
    }

    public function create()
    {
        $carModels = CarModel::all();
        return view('admin.carvariants.create', compact('carModels'));
    }

    public function store(Request $request)
    {
        // Check for soft deleted variant - restore if exists
        $softDeletedVariant = CarVariant::withTrashed()
                                       ->where('name', $request->name)
                                       ->where('car_model_id', $request->car_model_id)
                                       ->whereNotNull('deleted_at')
                                       ->first();
        
        if ($softDeletedVariant) {
            // Restore and update the soft deleted record
            $softDeletedVariant->restore();
            
            // Update with new data
            $data = $request->only([
                'description', 'price', 'is_active'
            ]);
            
            // Generate simple slug from name
            $data['slug'] = Str::slug($request->name);
            
            // Set defaults for boolean fields
            $data['is_active'] = $request->boolean('is_active', true);
            
            $softDeletedVariant->update($data);
            
            $successMessage = '✅ Đã khôi phục và cập nhật phiên bản xe thành công!';
            
            // Check if this is an AJAX request
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'redirect' => route('admin.carvariants.index')
                ]);
            }
            
            return redirect()->route('admin.carvariants.index')->with('success', $successMessage);
        }

        $validated = $request->validate([
            'car_model_id' => 'required|exists:car_models,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // Tạo CarVariant
        $carVariant = CarVariant::create([
            'car_model_id' => $validated['car_model_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'price' => $validated['price'],
            'is_active' => $validated['is_active'],
        ]);

        return redirect()->route('admin.carvariants.index')->with('success', 'Đã thêm phiên bản xe thành công.');
    }

    public function show(CarVariant $carvariant)
    {
        // Load all related data
        $carvariant->load([
            'carModel.carBrand',
            'colors',
            'images',
            'specifications',
            'featuresRelation',
            'reviews.user',
            'approvedReviews.user'
        ]);

        // Get recent orders for this variant (if orders table exists)
        $recentOrders = collect(); // Placeholder - implement if needed
        
        // Get related variants from same model
        $relatedVariants = CarVariant::where('car_model_id', $carvariant->car_model_id)
            ->where('id', '!=', $carvariant->id)
            ->where('is_active', true)
            ->with('colors', 'images')
            ->limit(6)
            ->get();

        return view('admin.carvariants.show', compact(
            'carvariant', 
            'recentOrders', 
            'relatedVariants'
        ));
    }

    public function edit(CarVariant $carvariant)
    {
        $carModels = CarModel::all();
        return view('admin.carvariants.edit', compact('carvariant', 'carModels'));
    }

    public function update(Request $request, CarVariant $carvariant)
    {
        $validated = $request->validate([
            'car_model_id' => 'required|exists:car_models,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // Cập nhật CarVariant
        $updateData = [
            'car_model_id' => $validated['car_model_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'is_active' => $validated['is_active'],
        ];
        
        // Update slug if name changed
        if ($validated['name'] !== $carvariant->name) {
            $updateData['slug'] = Str::slug($validated['name']);
        }
        
        $carvariant->update($updateData);

        return redirect()->route('admin.carvariants.index')->with('success', 'Cập nhật phiên bản xe thành công.');
    }

    public function destroy(Request $request, CarVariant $carvariant)
    {
        // Detailed dependency analysis
        $colorsCount = $carvariant->colors()->count();
        $imagesCount = $carvariant->images()->count();
        
        // Check for orders (critical business data) - via order_items polymorphic
        $ordersCount = 0;
        $pendingOrdersCount = 0;
        $completedOrdersCount = 0;
        
        try {
            if (Schema::hasTable('order_items') && Schema::hasTable('orders')) {
                // Count orders that contain this car variant
                $ordersCount = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.item_type', 'car_variant')
                    ->where('order_items.item_id', $carvariant->id)
                    ->whereNull('orders.deleted_at') // Exclude soft deleted orders
                    ->count();
                    
                // Check order statuses
                $pendingOrdersCount = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.item_type', 'car_variant')
                    ->where('order_items.item_id', $carvariant->id)
                    ->whereIn('orders.status', ['pending', 'confirmed', 'shipping'])
                    ->whereNull('orders.deleted_at')
                    ->count();
                        
                $completedOrdersCount = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.item_type', 'car_variant')
                    ->where('order_items.item_id', $carvariant->id)
                    ->whereIn('orders.status', ['delivered'])
                    ->whereNull('orders.deleted_at')
                    ->count();
            }
        } catch (\Exception $e) {
            // If tables don't exist or have different structure, skip check
            $ordersCount = 0;
        }

        // Check for service appointments (critical business data)
        $serviceAppointmentsCount = 0;
        $activeServiceCount = 0;
        try {
            if (Schema::hasTable('service_appointments')) {
                $serviceAppointmentsCount = DB::table('service_appointments')
                    ->where('car_variant_id', $carvariant->id)
                    ->count();
                    
                $activeServiceCount = DB::table('service_appointments')
                    ->where('car_variant_id', $carvariant->id)
                    ->whereIn('status', ['scheduled', 'confirmed', 'in_progress'])
                    ->count();
            }
        } catch (\Exception $e) {
            $serviceAppointmentsCount = 0;
        }

        // Check for test drives (if exists)
        $testDrivesCount = 0;
        try {
            if (Schema::hasTable('test_drives') && Schema::hasColumn('test_drives', 'car_variant_id')) {
                $testDrivesCount = DB::table('test_drives')
                    ->where('car_variant_id', $carvariant->id)
                    ->count();
            }
        } catch (\Exception $e) {
            $testDrivesCount = 0;
        }

        // Check for reviews (polymorphic relationship)
        $reviewsCount = 0;
        try {
            if (Schema::hasTable('reviews')) {
                $reviewsCount = DB::table('reviews')
                    ->where('reviewable_type', 'App\\Models\\CarVariant')
                    ->where('reviewable_id', $carvariant->id)
                    ->whereNull('deleted_at') // Exclude soft deleted reviews
                    ->count();
            }
        } catch (\Exception $e) {
            $reviewsCount = 0;
        }

        // Check if variant is currently active (first priority)
        if ($carvariant->is_active) {
            $message = "Không thể xóa phiên bản xe \"{$carvariant->name}\" vì đang ở trạng thái hoạt động. Vui lòng tạm dừng phiên bản trước khi xóa.";
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'action_suggestion' => 'deactivate',
                    'show_deactivate_button' => true
                ], 400);
            }
            return redirect()->route('admin.carvariants.index')->with('error', $message);
        }

        // CRITICAL: Never delete if has business transaction data (second priority)
        if ($ordersCount > 0 || $serviceAppointmentsCount > 0 || $testDrivesCount > 0 || $reviewsCount > 0) {
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
            if ($serviceAppointmentsCount > 0) {
                $businessData[] = "{$serviceAppointmentsCount} lịch bảo dưỡng";
                if ($activeServiceCount > 0) {
                    $businessData[] = "({$activeServiceCount} đang hoạt động)";
                }
            }
            if ($testDrivesCount > 0) {
                $businessData[] = "{$testDrivesCount} lịch lái thử";
            }
            if ($reviewsCount > 0) {
                $businessData[] = "{$reviewsCount} đánh giá";
            }
            
            $message = "KHÔNG THỂ XÓA phiên bản xe \"{$carvariant->name}\" vì có " . implode(', ', $businessData) . ". " .
                      "Đây là dữ liệu giao dịch quan trọng! Bạn chỉ có thể 'Tạm dừng' để ngừng bán nhưng vẫn giữ lịch sử.";
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'action_suggestion' => 'deactivate',
                    'show_deactivate_button' => true,
                    'business_data' => [
                        'orders_count' => $ordersCount,
                        'pending_orders' => $pendingOrdersCount,
                        'completed_orders' => $completedOrdersCount,
                        'service_appointments_count' => $serviceAppointmentsCount,
                        'active_service_count' => $activeServiceCount,
                        'test_drives_count' => $testDrivesCount,
                        'reviews_count' => $reviewsCount
                    ]
                ], 422); // Unprocessable Entity
            }
            return redirect()->route('admin.carvariants.index')->with('error', $message);
        }

        // Only allow deletion of "clean" variants with no business impact
        // Warn about non-critical data that will be deleted
        $warnings = [];
        if ($colorsCount > 0) {
            $warnings[] = "{$colorsCount} màu sắc";
        }
        if ($imagesCount > 0) {
            $warnings[] = "{$imagesCount} hình ảnh";
        }

        if (!empty($warnings)) {
            $warningMessage = "Bạn có chắc muốn xóa phiên bản xe \"{$carvariant->name}\"? Hành động này sẽ xóa vĩnh viễn " . implode(', ', $warnings) . " liên quan.";
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $warningMessage,
                    'requires_confirmation' => true,
                    'details' => [
                        'colors_count' => $colorsCount,
                        'images_count' => $imagesCount
                    ]
                ], 400);
            }
            return redirect()->route('admin.carvariants.index')
                           ->with('warning', $warningMessage)
                           ->with('confirm_delete', $carvariant->id);
        }

        // Safe to delete - no critical dependencies
        $this->performVariantDeletion($carvariant);
        
        $successMessage = "Đã xóa phiên bản xe \"{$carvariant->name}\" thành công!";
        
        if (request()->wantsJson() || request()->ajax()) {
            // Calculate updated stats
            $totalVariants = CarVariant::count();
            $activeVariants = CarVariant::where('is_active', true)->count();
            $inactiveVariants = CarVariant::where('is_active', false)->count();
            $featuredVariants = CarVariant::where('is_featured', true)->count();
            $onSaleVariants = CarVariant::where('is_on_sale', true)->count();
            $newArrivalVariants = CarVariant::where('is_new_arrival', true)->count();
            $bestsellerVariants = CarVariant::where('is_bestseller', true)->count();
            
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'stats' => [
                    'totalVariants' => $totalVariants,
                    'activeVariants' => $activeVariants,
                    'inactiveVariants' => $inactiveVariants,
                    'featuredVariants' => $featuredVariants,
                    'onSaleVariants' => $onSaleVariants,
                    'newArrivalVariants' => $newArrivalVariants,
                    'bestsellerVariants' => $bestsellerVariants,
                ]
            ]);
        }
        
        return redirect()->route('admin.carvariants.index')->with('success', $successMessage);
    }

    /**
     * Perform the actual deletion of variant and related NON-CRITICAL data only
     * This method should only be called after ensuring no business transaction data exists
     */
    private function performVariantDeletion(CarVariant $carvariant)
    {
        // Only delete non-critical data - business data should never reach this point
        
        // 1. Delete colors (HasMany relationship - non-critical)
        $carvariant->colors()->delete();
        
        // 2. Delete images and their physical files (non-critical)
        foreach ($carvariant->images as $image) {
            // Delete physical file if exists
            if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }
        
        // 3. Delete the variant itself
        $carvariant->delete();
        
        // NOTE: We do NOT delete business transaction data here:
        // - Orders (should never be deleted)
        // - Test drives (important customer interaction history)  
        // - Reviews (valuable customer feedback)
        // - Service records (maintenance history)
        // These should be preserved for business continuity and reporting
    }

    public function toggleStatus(Request $request, CarVariant $carvariant)
    {
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $newStatus = $request->is_active;
        $carvariant->update(['is_active' => $newStatus]);

        $statusText = $newStatus ? 'kích hoạt' : 'tạm dừng';
        
        // Calculate updated stats
        $totalVariants = CarVariant::count();
        $activeVariants = CarVariant::where('is_active', true)->count();
        $inactiveVariants = CarVariant::where('is_active', false)->count();
        $featuredVariants = CarVariant::where('is_featured', true)->count();
        $onSaleVariants = CarVariant::where('is_on_sale', true)->count();
        $newArrivalVariants = CarVariant::where('is_new_arrival', true)->count();
        $bestsellerVariants = CarVariant::where('is_bestseller', true)->count();
        
        return response()->json([
            'success' => true,
            'message' => "Đã {$statusText} phiên bản xe \"{$carvariant->name}\" thành công!",
            'is_active' => $newStatus,
            'stats' => [
                'totalVariants' => $totalVariants,
                'activeVariants' => $activeVariants,
                'inactiveVariants' => $inactiveVariants,
                'featuredVariants' => $featuredVariants,
                'onSaleVariants' => $onSaleVariants,
                'newArrivalVariants' => $newArrivalVariants,
                'bestsellerVariants' => $bestsellerVariants,
            ]
        ]);
    }
}