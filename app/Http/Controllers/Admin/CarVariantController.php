<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarVariant;
use App\Models\CarModel;
use App\Models\CarBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            }
        }

        $carVariants = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get car models for filter dropdown
        $carModels = CarModel::with('carBrand')->orderBy('name')->get();

        // Calculate stats
        $totalVariants = CarVariant::count();
        $activeVariants = CarVariant::where('is_active', true)->count();
        $inactiveVariants = CarVariant::where('is_active', false)->count();
        $featuredVariants = CarVariant::where('is_featured', true)->count();
        $onSaleVariants = CarVariant::where('is_on_sale', true)->count();
        $newArrivalVariants = CarVariant::where('is_new_arrival', true)->count();

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
            'newArrivalVariants'
        ));
    }

    public function create()
    {
        $carModels = CarModel::all();
        return view('admin.carvariants.create', compact('carModels'));
    }

    public function store(Request $request)
    {
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
        $carvariant->update([
            'car_model_id' => $validated['car_model_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'is_active' => $validated['is_active'],
        ]);

        return redirect()->route('admin.carvariants.index')->with('success', 'Cập nhật phiên bản xe thành công.');
    }

    public function destroy(CarVariant $carvariant)
    {
        // Check for orders (if orders table exists and has proper structure)
        $ordersCount = 0;
        try {
            if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'car_variant_id')) {
                $ordersCount = DB::table('orders')
                    ->where('car_variant_id', $carvariant->id)
                    ->count();
            }
        } catch (\Exception $e) {
            $ordersCount = 0;
        }

        // Business logic validation - NEVER delete variants with orders
        if ($ordersCount > 0) {
            $errorMessage = "KHÔNG THỂ XÓA phiên bản xe \"{$carvariant->name}\" vì đã có {$ordersCount} đơn hàng. " .
                "Đây là dữ liệu giao dịch quan trọng! Bạn chỉ có thể 'Tạm dừng' thay vì xóa.";
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 422);
            }
            
            return redirect()->route('admin.carvariants.index')->with('error', $errorMessage);
        }

        // Check for related data
        $colorsCount = $carvariant->colors()->count();
        $imagesCount = $carvariant->images()->count();

        // Safe to delete - no orders
        $carvariant->delete();
        
        $successMessage = "Đã xóa phiên bản xe \"{$carvariant->name}\" thành công!";
        
        if (request()->wantsJson() || request()->ajax()) {
            // Calculate updated stats
            $totalVariants = CarVariant::count();
            $activeVariants = CarVariant::where('is_active', true)->count();
            $inactiveVariants = CarVariant::where('is_active', false)->count();
            $featuredVariants = CarVariant::where('is_featured', true)->count();
            $onSaleVariants = CarVariant::where('is_on_sale', true)->count();
            $newArrivalVariants = CarVariant::where('is_new_arrival', true)->count();
            
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
                ]
            ]);
        }
        
        return redirect()->route('admin.carvariants.index')->with('success', $successMessage);
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
            ]
        ]);
    }
}