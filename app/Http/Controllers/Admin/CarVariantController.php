<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarVariant;
use App\Models\CarModel;

use Illuminate\Http\Request;

class CarVariantController extends Controller
{
    public function index(Request $request)
    {
        $query = CarVariant::with(['carModel']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhereHas('carModel', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $carVariants = $query->orderByDesc('created_at')->paginate(10);

        return view('admin.carvariants.index', compact('carVariants'));
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
            'features' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // Tạo CarVariant
        $carVariant = CarVariant::create([
            'car_model_id' => $validated['car_model_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'features' => $validated['features'],
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
            'features' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // Cập nhật CarVariant
        $carvariant->update([
            'car_model_id' => $validated['car_model_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'features' => $validated['features'],
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