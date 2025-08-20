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
        $query = CarModel::with('carBrand');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $carModels = $query->latest()->paginate(10);

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