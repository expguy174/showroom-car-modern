<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarVariant;
use App\Models\CarModel;
use App\Models\CarBrand;

use Illuminate\Http\Request;

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
        $carvariant->delete();

        return redirect()->route('admin.carvariants.index')->with('success', 'Đã xoá phiên bản xe thành công.');
    }
}