<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarBrand;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = CarBrand::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $cars = $query->orderBy('sort_order', 'asc')
                     ->orderBy('name', 'asc')
                     ->paginate(10);
        return view('admin.cars.index', compact('cars'));
    }

    public function create()
    {
        return view('admin.cars.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:car_brands,name',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'country' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'keywords' => 'nullable|string',
            'founded_year' => 'nullable|integer|min:1800|max:' . (date('Y') + 1),
            'website' => 'nullable|url|max:2048',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only([
            'name', 'country', 'description', 'meta_title', 'meta_description', 
            'keywords', 'founded_year', 'website', 'phone', 'email', 'address',
            'is_active', 'is_featured', 'sort_order'
        ]);

        // Tạo slug tự động từ tên
        $data['slug'] = Str::slug($request->name);
        
        // Đảm bảo các trường boolean có giá trị
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured', false);
        $data['sort_order'] = $request->input('sort_order', 0);

        if ($request->hasFile('logo_path')) {
            $file = $request->file('logo_path');
            $path = $file->store('uploads/cars', 'public');
            $data['logo_path'] = $path;
        }

        CarBrand::create($data);
        return redirect()->route('admin.cars.index')->with('success', 'Thêm hãng xe thành công!');
    }

    public function edit(CarBrand $car)
    {
        return view('admin.cars.edit', compact('car'));
    }

    public function update(Request $request, CarBrand $car)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:car_brands,name,' . $car->id,
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'country' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'keywords' => 'nullable|string',
            'founded_year' => 'nullable|integer|min:1800|max:' . (date('Y') + 1),
            'website' => 'nullable|url|max:2048',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only([
            'name', 'country', 'description', 'meta_title', 'meta_description', 
            'keywords', 'founded_year', 'website', 'phone', 'email', 'address',
            'is_active', 'is_featured', 'sort_order'
        ]);

        // Cập nhật slug nếu tên thay đổi
        if ($request->name !== $car->name) {
            $data['slug'] = Str::slug($request->name);
        }
        
        // Đảm bảo các trường boolean có giá trị
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured', false);
        $data['sort_order'] = $request->input('sort_order', 0);

        if ($request->hasFile('logo_path')) {
            // Xoá ảnh cũ nếu có
            if ($car->logo_path && Storage::disk('public')->exists($car->logo_path)) {
                Storage::disk('public')->delete($car->logo_path);
            }

            // Lưu ảnh mới
            $file = $request->file('logo_path');
            $path = $file->store('uploads/cars', 'public');
            $data['logo_path'] = $path;
        }

        $car->update($data);
        
        // Cập nhật thống kê
        $this->updateBrandStatistics($car);
        
        return redirect()->route('admin.cars.index')->with('success', 'Cập nhật hãng xe thành công!');
    }

    public function destroy(CarBrand $car)
    {
        // Kiểm tra xem có car models nào đang sử dụng brand này không
        if ($car->carModels()->count() > 0) {
            return redirect()->route('admin.cars.index')
                ->with('error', 'Không thể xóa hãng xe này vì đang có dòng xe sử dụng.');
        }

        // Xóa logo nếu có
        if ($car->logo_path && Storage::disk('public')->exists($car->logo_path)) {
            Storage::disk('public')->delete($car->logo_path);
        }

        $car->delete();
        return redirect()->route('admin.cars.index')->with('success', 'Xoá hãng xe thành công!');
    }

    /**
     * Cập nhật thống kê cho brand
     */
    private function updateBrandStatistics(CarBrand $brand)
    {
        $totalModels = $brand->carModels()->count();
        $totalVariants = $brand->carModels()->withCount('carVariants')->get()->sum('car_variants_count');
        
        $brand->update([
            'total_models' => $totalModels,
            'total_variants' => $totalVariants
        ]);
    }
}