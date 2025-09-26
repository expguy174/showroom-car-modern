<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarBrand;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CarBrandController extends Controller
{
    public function index(Request $request)
    {
        // Get stats for all cars (unfiltered)
        $totalCars = CarBrand::count();
        $activeCars = CarBrand::where('is_active', true)->count();
        $inactiveCars = CarBrand::where('is_active', false)->count();
        $featuredCars = CarBrand::where('is_featured', true)->count();
        
        // Initialize search and status variables
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        
        $query = CarBrand::with('carModels');

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
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
                case 'featured':
                    $query->where('is_featured', true);
                    break;
            }
        }

        $carbrands = $query->orderBy('sort_order', 'asc')
                     ->orderBy('name', 'asc')
                     ->paginate(15);
        
        // Check if this is an AJAX request
        if ($request->ajax()) {
            return view('admin.carbrands.partials.table', compact('carbrands'))->render();
        }

        return view('admin.carbrands.index', compact('carbrands', 'search', 'status', 'totalCars', 'activeCars', 'inactiveCars', 'featuredCars'));
    }

    public function create()
    {
        return view('admin.carbrands.create');
    }

    public function store(Request $request)
    {
        // Check for existing active brand
        $existingBrand = CarBrand::where('name', $request->name)
                                ->whereNull('deleted_at')
                                ->first();
        
        if ($existingBrand) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tên hãng xe đã tồn tại.',
                    'errors' => ['name' => ['Tên hãng xe đã tồn tại.']]
                ], 422);
            }
            return back()->withErrors(['name' => 'Tên hãng xe đã tồn tại.'])->withInput();
        }
        
        // Check for soft deleted brand - restore if exists
        $softDeletedBrand = CarBrand::withTrashed()
                                   ->where('name', $request->name)
                                   ->whereNotNull('deleted_at')
                                   ->first();
        
        if ($softDeletedBrand) {
            // Debug log
            \Log::info("Restoring soft deleted brand: " . $softDeletedBrand->name . " (ID: " . $softDeletedBrand->id . ")");
            
            // Restore and update the soft deleted record
            $softDeletedBrand->restore();
            
            // Update with new data
            $data = $request->only([
                'country', 'description', 'meta_title', 'meta_description',
                'keywords', 'founded_year', 'website', 'phone', 'email', 'address',
                'is_active', 'is_featured', 'sort_order'
            ]);
            
            // Handle logo upload
            if ($request->hasFile('logo_path')) {
                $file = $request->file('logo_path');
                $path = $file->store('uploads/cars', 'public');
                $data['logo_path'] = $path;
            }
            
            // Set defaults
            $data['is_active'] = $request->boolean('is_active', true);
            $data['is_featured'] = $request->boolean('is_featured', false);
            $data['sort_order'] = $request->input('sort_order', 0);
            
            $softDeletedBrand->update($data);
            
            // Handle AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã khôi phục và cập nhật hãng xe thành công!',
                    'redirect' => route('admin.carbrands.show', $softDeletedBrand),
                    'car' => $softDeletedBrand
                ]);
            }
            
            return redirect()->route('admin.carbrands.show', $softDeletedBrand)
                           ->with('success', 'Đã khôi phục và cập nhật hãng xe thành công!');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
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
        ], [
            'name.required' => 'Tên hãng xe là bắt buộc.',
            'name.string' => 'Tên hãng xe phải là chuỗi ký tự.',
            'name.max' => 'Tên hãng xe không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên hãng xe này đã tồn tại.',
            'logo_path.image' => 'Logo phải là hình ảnh.',
            'logo_path.mimes' => 'Logo phải có định dạng: jpeg, png, jpg, gif, svg.',
            'logo_path.max' => 'Kích thước logo không được vượt quá 2MB.',
            'country.string' => 'Quốc gia phải là chuỗi ký tự.',
            'country.max' => 'Quốc gia không được vượt quá 100 ký tự.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'meta_title.string' => 'Meta Title phải là chuỗi ký tự.',
            'meta_title.max' => 'Meta Title không được vượt quá 255 ký tự.',
            'meta_description.string' => 'Meta Description phải là chuỗi ký tự.',
            'meta_description.max' => 'Meta Description không được vượt quá 500 ký tự.',
            'keywords.string' => 'Keywords phải là chuỗi ký tự.',
            'founded_year.integer' => 'Năm thành lập phải là số nguyên.',
            'founded_year.min' => 'Năm thành lập không được nhỏ hơn 1800.',
            'founded_year.max' => 'Năm thành lập không được lớn hơn ' . (date('Y') + 1) . '.',
            'website.url' => 'Website phải là URL hợp lệ.',
            'website.max' => 'Website không được vượt quá 2048 ký tự.',
            'phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'email.email' => 'Email phải có định dạng hợp lệ.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'address.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'sort_order.integer' => 'Thứ tự sắp xếp phải là số nguyên.',
            'sort_order.min' => 'Thứ tự sắp xếp không được nhỏ hơn 0.',
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

        $carBrand = CarBrand::create($data);
        
        // Handle AJAX request
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thêm hãng xe thành công!',
                'redirect' => route('admin.carbrands.index'),
                'car' => $carBrand
            ]);
        }
        
        return redirect()->route('admin.carbrands.index')->with('success', 'Thêm hãng xe thành công!');
    }

    public function show(CarBrand $car)
    {
        // Paginate car models for this brand
        $carModels = $car->carModels()
            ->with('carVariants')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(8, ['*'], 'models_page');
            
        return view('admin.carbrands.show', compact('car', 'carModels'));
    }

    public function edit(CarBrand $car)
    {
        return view('admin.carbrands.edit', compact('car'));
    }

    public function update(Request $request, CarBrand $car)
    {
        // Custom validation for soft delete (ignore current record)
        $existingBrand = CarBrand::where('name', $request->name)
                                ->whereNull('deleted_at')
                                ->where('id', '!=', $car->id)
                                ->first();
        
        if ($existingBrand) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tên hãng xe đã tồn tại.',
                    'errors' => ['name' => ['Tên hãng xe đã tồn tại.']]
                ], 422);
            }
            return back()->withErrors(['name' => 'Tên hãng xe đã tồn tại.'])->withInput();
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
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
        ], [
            'name.required' => 'Tên hãng xe là bắt buộc.',
            'name.string' => 'Tên hãng xe phải là chuỗi ký tự.',
            'name.max' => 'Tên hãng xe không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên hãng xe này đã tồn tại.',
            'logo_path.image' => 'Logo phải là hình ảnh.',
            'logo_path.mimes' => 'Logo phải có định dạng: jpeg, png, jpg, gif, svg.',
            'logo_path.max' => 'Kích thước logo không được vượt quá 2MB.',
            'country.string' => 'Quốc gia phải là chuỗi ký tự.',
            'country.max' => 'Quốc gia không được vượt quá 100 ký tự.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'meta_title.string' => 'Meta Title phải là chuỗi ký tự.',
            'meta_title.max' => 'Meta Title không được vượt quá 255 ký tự.',
            'meta_description.string' => 'Meta Description phải là chuỗi ký tự.',
            'meta_description.max' => 'Meta Description không được vượt quá 500 ký tự.',
            'keywords.string' => 'Keywords phải là chuỗi ký tự.',
            'founded_year.integer' => 'Năm thành lập phải là số nguyên.',
            'founded_year.min' => 'Năm thành lập không được nhỏ hơn 1800.',
            'founded_year.max' => 'Năm thành lập không được lớn hơn ' . (date('Y') + 1) . '.',
            'website.url' => 'Website phải là URL hợp lệ.',
            'website.max' => 'Website không được vượt quá 2048 ký tự.',
            'phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'email.email' => 'Email phải có định dạng hợp lệ.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'address.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'sort_order.integer' => 'Thứ tự sắp xếp phải là số nguyên.',
            'sort_order.min' => 'Thứ tự sắp xếp không được nhỏ hơn 0.',
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
        
        // Handle AJAX request
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật hãng xe thành công!',
                'redirect' => route('admin.carbrands.index'),
                'car' => $car
            ]);
        }
        
        return redirect()->route('admin.carbrands.index')->with('success', 'Cập nhật hãng xe thành công!');
    }

    public function destroy(Request $request, CarBrand $car)
    {
        // Detailed dependency analysis
        $modelsCount = $car->carModels()->count();
        $activeModelsCount = $car->carModels()->where('is_active', true)->count();
        
        // Get variants count through models
        $variantsCount = $car->carModels()
            ->withCount('carVariants')
            ->get()
            ->sum('car_variants_count');
            
        $activeVariantsCount = $car->carModels()
            ->whereHas('carVariants', function($q) {
                $q->where('is_active', true);
            })
            ->withCount(['carVariants' => function($q) {
                $q->where('is_active', true);
            }])
            ->get()
            ->sum('car_variants_count');

        // Check for orders (if orders table exists and has proper structure)
        $ordersCount = 0;
        try {
            if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'car_variant_id')) {
                $ordersCount = DB::table('orders')
                    ->join('car_variants', 'orders.car_variant_id', '=', 'car_variants.id')
                    ->join('car_models', 'car_variants.car_model_id', '=', 'car_models.id')
                    ->where('car_models.car_brand_id', $car->id)
                    ->count();
            }
        } catch (\Exception $e) {
            // If orders table doesn't exist or has different structure, skip check
            $ordersCount = 0;
        }

        // Business logic validation with detailed messages
        if ($ordersCount > 0) {
            $message = "Không thể xóa hãng xe \"{$car->name}\" vì đã có {$ordersCount} đơn hàng. Bạn có thể 'Ngừng hoạt động' thay vì xóa để giữ lịch sử đơn hàng.";
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 400);
            }
            return redirect()->route('admin.carbrands.index')->with('error', $message);
        }

        if ($activeVariantsCount > 0) {
            $message = "Không thể xóa hãng xe \"{$car->name}\" vì đang có {$activeVariantsCount} phiên bản xe đang bán. Vui lòng ngừng bán tất cả phiên bản trước khi xóa hãng xe.";
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 400);
            }
            return redirect()->route('admin.carbrands.index')->with('error', $message);
        }

        if ($activeModelsCount > 0) {
            $message = "Không thể xóa hãng xe \"{$car->name}\" vì đang có {$activeModelsCount} dòng xe đang hoạt động. Vui lòng ngừng hoạt động tất cả dòng xe trước khi xóa hãng xe.";
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 400);
            }
            return redirect()->route('admin.carbrands.index')->with('error', $message);
        }

        if ($modelsCount > 0) {
            $message = "Hãng xe \"{$car->name}\" có {$modelsCount} dòng xe (đã ngừng hoạt động). Bạn có chắc muốn xóa? Điều này sẽ xóa luôn tất cả dòng xe và phiên bản liên quan.";
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'requires_confirmation' => true
                ], 400);
            }
            return redirect()->route('admin.carbrands.index')->with('warning', $message)->with('confirm_delete', $car->id);
        }

        // Safe to delete - no dependencies
        $this->performDeletion($car);
        
        $message = "Đã xóa hãng xe \"{$car->name}\" thành công!";
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }
        
        return redirect()->route('admin.carbrands.index')->with('success', $message);
    }

    private function performDeletion(CarBrand $car)
    {
        // Delete logo if exists
        if ($car->logo_path && Storage::disk('public')->exists($car->logo_path)) {
            Storage::disk('public')->delete($car->logo_path);
        }

        // Delete all related models and variants (cascade)
        foreach ($car->carModels as $model) {
            foreach ($model->carVariants as $variant) {
                $variant->delete();
            }
            $model->delete();
        }

        // Finally delete the brand
        $car->delete();
    }

    public function toggleStatus(Request $request, CarBrand $car)
    {
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $newStatus = $request->is_active;
        $car->update(['is_active' => $newStatus]);

        $statusText = $newStatus ? 'kích hoạt' : 'tạm dừng';
        
        // Get updated stats
        $totalCars = CarBrand::count();
        $activeCars = CarBrand::where('is_active', true)->count();
        $inactiveCars = CarBrand::where('is_active', false)->count();
        $featuredCars = CarBrand::where('is_featured', true)->count();
        
        return response()->json([
            'success' => true,
            'message' => "Đã {$statusText} hãng xe \"{$car->name}\" thành công!",
            'is_active' => $newStatus,
            'stats' => [
                'totalCars' => $totalCars,
                'activeCars' => $activeCars,
                'inactiveCars' => $inactiveCars,
                'featuredCars' => $featuredCars
            ]
        ]);
    }

    /**
     * Cập nhật thống kê cho brand
     */
    private function updateBrandStatistics(CarBrand $brand) {}
}