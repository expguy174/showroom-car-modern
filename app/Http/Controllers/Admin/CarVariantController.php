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
        $query = CarVariant::with(['carModel.carBrand']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('trim_level', 'like', "%{$search}%")
                  ->orWhereHas('carModel', function($model) use ($search) {
                      $model->where('name', 'like', "%{$search}%")
                            ->orWhereHas('carBrand', function($brand) use ($search) {
                                $brand->where('name', 'like', "%{$search}%");
                            });
                  });
            });
        }

        // Brand filter
        if ($request->filled('brand')) {
            $query->whereHas('carModel.carBrand', function($brand) use ($request) {
                $brand->where('id', $request->brand);
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

        $carVariants = $query->orderBy('name', 'asc')->paginate(15);

        // Get brands for filter dropdown
        $brands = CarBrand::orderBy('name')->get();

        // Calculate stats
        $allVariants = CarVariant::all();
        $stats = [
            'total' => $allVariants->count(),
            'active' => $allVariants->where('is_active', true)->count(),
            'inactive' => $allVariants->where('is_active', false)->count(),
            'avg_price' => $allVariants->avg('price') ?? 0,
        ];

        return view('admin.carvariants.index', compact('carVariants', 'brands', 'stats'));
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
            return redirect()->route('admin.carvariants.index')->with('error', 
                "⚠️ KHÔNG THỂ XÓA phiên bản xe \"{$carvariant->name}\" vì đã có {$ordersCount} đơn hàng. " .
                "Đây là dữ liệu giao dịch quan trọng! Bạn chỉ có thể 'Ngừng bán' thay vì xóa."
            );
        }

        // Safe to delete - no orders
        $carvariant->delete();
        
        return redirect()->route('admin.carvariants.index')->with('success', 
            "✅ Đã xóa phiên bản xe \"{$carvariant->name}\" thành công!"
        );
    }

    public function toggleStatus(Request $request, CarVariant $carvariant)
    {
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $newStatus = $request->is_active;
        $carvariant->update(['is_active' => $newStatus]);

        $statusText = $newStatus ? 'kích hoạt' : 'ngừng bán';
        
        return response()->json([
            'success' => true,
            'message' => "Đã {$statusText} phiên bản xe \"{$carvariant->name}\" thành công!",
            'is_active' => $newStatus
        ]);
    }
}