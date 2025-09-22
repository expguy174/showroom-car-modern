<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarModel;
use App\Models\CarBrand;

class CarModelController extends Controller
{
    public function index(Request $request)
    {
        $query = CarModel::with(['carBrand', 'carVariants']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('carBrand', function($brand) use ($search) {
                      $brand->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Brand filter
        if ($request->filled('brand')) {
            $query->where('car_brand_id', $request->brand);
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

        $carModels = $query->orderBy('name', 'asc')->paginate(15);

        return view('admin.carmodels.index', compact('carModels'));
    }

    public function create()
    {
        $cars = CarBrand::all();
        return view('admin.carmodels.create', compact('cars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_brand_id' => 'required|exists:car_brands,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        CarModel::create([
            'car_brand_id' => $request->car_brand_id,
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.carmodels.index')->with('success', 'Thêm mẫu xe thành công!');
    }

    public function edit(CarModel $carmodel)
    {
        $cars = CarBrand::all();
        return view('admin.carmodels.edit', [
            'carModel' => $carmodel,
            'cars' => $cars
        ]);
    }

    public function update(Request $request, CarModel $carmodel)
    {
        $request->validate([
            'car_brand_id' => 'required|exists:car_brands,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $carmodel->update([
            'car_brand_id' => $request->car_brand_id,
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.carmodels.index')->with('success', 'Cập nhật mẫu xe thành công!');
    }

    public function destroy(CarModel $carmodel)
    {
        $carmodel->delete();
        return redirect()->route('admin.carmodels.index')->with('success', 'Xoá mẫu xe thành công!');
    }
}