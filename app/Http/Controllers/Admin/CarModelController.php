<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarBrand;
use App\Models\CarModel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CarModelController extends Controller
{
    public function index(Request $request)
    {
        // Get stats for all car models (unfiltered)
        $totalModels = CarModel::count();
        $activeModels = CarModel::where('is_active', true)->count();
        $inactiveModels = CarModel::where('is_active', false)->count();
        $featuredModels = CarModel::where('is_featured', true)->count();
        $newModels = CarModel::where('is_new', true)->count();
        
        $query = CarModel::with(['carBrand', 'carVariants']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('body_type', 'like', "%{$search}%")
                  ->orWhere('segment', 'like', "%{$search}%")
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
                case 'featured':
                    $query->where('is_featured', true);
                    break;
                case 'new':
                    $query->where('is_new', true);
                    break;
                case 'discontinued':
                    $query->where('is_discontinued', true);
                    break;
            }
        }

        $carModels = $query->orderBy('name', 'asc')->paginate(15);
        $brands = \App\Models\CarBrand::orderBy('name')->get();

        // Check if this is an AJAX request
        if ($request->ajax()) {
            return view('admin.carmodels.partials.table', compact('carModels'))->render();
        }

        return view('admin.carmodels.index', compact('carModels', 'brands', 'totalModels', 'activeModels', 'inactiveModels', 'featuredModels', 'newModels'));
    }

    public function create()
    {
        $cars = CarBrand::all();
        return view('admin.carmodels.create', compact('cars'));
    }

    public function store(Request $request)
    {
        // Check for soft deleted model - restore if exists
        $softDeletedModel = CarModel::withTrashed()
                                   ->where('name', $request->name)
                                   ->where('car_brand_id', $request->car_brand_id)
                                   ->whereNotNull('deleted_at')
                                   ->first();
        
        if ($softDeletedModel) {
            // Restore and update the soft deleted record
            $softDeletedModel->restore();
            
            // Update with new data
            $data = $request->only([
                'description', 'body_type', 'segment', 'fuel_type',
                'production_start_year', 'production_end_year', 'generation',
                'meta_title', 'meta_description', 'keywords',
                'is_active', 'is_featured', 'is_new', 'is_discontinued', 'sort_order'
            ]);
            
            // Set defaults for boolean fields
            $data['is_active'] = $request->boolean('is_active', true);
            $data['is_featured'] = $request->boolean('is_featured', false);
            $data['is_new'] = $request->boolean('is_new', false);
            $data['is_discontinued'] = $request->boolean('is_discontinued', false);
            $data['sort_order'] = $request->input('sort_order', 0);
            
            $softDeletedModel->update($data);
            
            // Handle image uploads for restored model
            if ($request->hasFile('images')) {
                $mainImageIndex = $request->input('main_image_index', 0);
                $imageTypes = $request->input('image_types', []);
                $imageDescriptions = $request->input('image_descriptions', []);
                
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('uploads/car-models', 'public');
                    
                    $softDeletedModel->images()->create([
                        'image_url' => $path,
                        'alt_text' => $softDeletedModel->name . ' - Ảnh ' . ($index + 1),
                        'title' => $softDeletedModel->name,
                        'description' => $imageDescriptions[$index] ?? 'Hình ảnh của ' . $softDeletedModel->name,
                        'image_type' => $imageTypes[$index] ?? 'gallery',
                        'is_main' => $index == $mainImageIndex,
                        'is_active' => true,
                        'sort_order' => $index,
                    ]);
                }
            }
            
            return redirect()->route('admin.carmodels.show', $softDeletedModel)
                           ->with('success', '✅ Đã khôi phục và cập nhật dòng xe thành công!');
        }

        $request->validate([
            'car_brand_id' => 'required|exists:car_brands,id',
            'name' => 'required|string|max:255|unique:car_models,name,NULL,id,deleted_at,NULL',
            'description' => 'nullable|string',
            'body_type' => 'nullable|string|max:100',
            'segment' => 'nullable|string|max:100',
            'fuel_type' => 'nullable|string|max:100',
            'production_start_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 5),
            'production_end_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'generation' => 'nullable|string|max:100',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'keywords' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_discontinued' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'image_types' => 'nullable|array',
            'image_types.*' => 'required|in:gallery,exterior,interior',
            'image_titles' => 'nullable|array',
            'image_titles.*' => 'nullable|string|max:255',
            'image_alt_texts' => 'nullable|array',
            'image_alt_texts.*' => 'nullable|string|max:255',
            'image_descriptions' => 'nullable|array',
            'image_descriptions.*' => 'nullable|string|max:500',
            'image_sort_orders' => 'nullable|array',
            'image_sort_orders.*' => 'nullable|integer|min:0',
            'image_is_active' => 'nullable|array',
            'main_image_index' => 'nullable|integer|min:0',
        ], [
            'car_brand_id.required' => 'Vui lòng chọn hãng xe.',
            'car_brand_id.exists' => 'Hãng xe được chọn không tồn tại.',
            'name.required' => 'Tên dòng xe là bắt buộc.',
            'name.string' => 'Tên dòng xe phải là chuỗi ký tự.',
            'name.max' => 'Tên dòng xe không được vượt quá 255 ký tự.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'body_type.string' => 'Kiểu dáng phải là chuỗi ký tự.',
            'body_type.max' => 'Kiểu dáng không được vượt quá 100 ký tự.',
            'segment.string' => 'Phân khúc phải là chuỗi ký tự.',
            'segment.max' => 'Phân khúc không được vượt quá 100 ký tự.',
            'fuel_type.string' => 'Loại nhiên liệu phải là chuỗi ký tự.',
            'fuel_type.max' => 'Loại nhiên liệu không được vượt quá 100 ký tự.',
            'production_start_year.integer' => 'Năm bắt đầu sản xuất phải là số nguyên.',
            'production_start_year.min' => 'Năm bắt đầu sản xuất không được nhỏ hơn 1900.',
            'production_start_year.max' => 'Năm bắt đầu sản xuất không được lớn hơn ' . (date('Y') + 5) . '.',
            'production_end_year.integer' => 'Năm kết thúc sản xuất phải là số nguyên.',
            'production_end_year.min' => 'Năm kết thúc sản xuất không được nhỏ hơn 1900.',
            'production_end_year.max' => 'Năm kết thúc sản xuất không được lớn hơn ' . (date('Y') + 10) . '.',
            'generation.string' => 'Thế hệ phải là chuỗi ký tự.',
            'generation.max' => 'Thế hệ không được vượt quá 100 ký tự.',
            'meta_title.string' => 'Meta Title phải là chuỗi ký tự.',
            'meta_title.max' => 'Meta Title không được vượt quá 255 ký tự.',
            'meta_description.string' => 'Meta Description phải là chuỗi ký tự.',
            'meta_description.max' => 'Meta Description không được vượt quá 500 ký tự.',
            'keywords.string' => 'Keywords phải là chuỗi ký tự.',
            'sort_order.integer' => 'Thứ tự sắp xếp phải là số nguyên.',
            'sort_order.min' => 'Thứ tự sắp xếp không được nhỏ hơn 0.',
        ]);

        $data = $request->only([
            'car_brand_id', 'name', 'description', 'body_type', 'segment', 'fuel_type',
            'production_start_year', 'production_end_year', 'generation',
            'meta_title', 'meta_description', 'keywords',
            'is_active', 'is_featured', 'is_new', 'is_discontinued', 'sort_order'
        ]);

        // Set defaults for boolean fields
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured', false);
        $data['is_new'] = $request->boolean('is_new', false);
        $data['is_discontinued'] = $request->boolean('is_discontinued', false);
        $data['sort_order'] = $request->input('sort_order', 0);

        $carModel = CarModel::create($data);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $mainImageIndex = $request->input('main_image_index', 0);
            $imageTypes = $request->input('image_types', []);
            $imageTitles = $request->input('image_titles', []);
            $imageAltTexts = $request->input('image_alt_texts', []);
            $imageDescriptions = $request->input('image_descriptions', []);
            $imageSortOrders = $request->input('image_sort_orders', []);
            $imageIsActive = $request->input('image_is_active', []);
            
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('uploads/car-models', 'public');
                
                // Generate smart defaults
                $imageType = $imageTypes[$index] ?? 'gallery';
                $altText = $imageAltTexts[$index] ?? $carModel->name . ' - Ảnh ' . ($index + 1);
                $title = !empty($imageTitles[$index]) ? $imageTitles[$index] : $carModel->name . ' - ' . ucfirst($imageType);
                $description = !empty($imageDescriptions[$index]) ? $imageDescriptions[$index] : 
                    match($imageType) {
                        'gallery' => 'Hình ảnh tổng quan của ' . $carModel->name,
                        'exterior' => 'Hình ảnh ngoại thất ' . $carModel->name,
                        'interior' => 'Hình ảnh nội thất ' . $carModel->name,
                        default => 'Hình ảnh của ' . $carModel->name
                    };

                $carModel->images()->create([
                    'image_url' => $path,
                    'alt_text' => $altText,
                    'title' => $title,
                    'description' => $description,
                    'image_type' => $imageType,
                    'is_main' => $index == $mainImageIndex,
                    'is_active' => true, // Always active for new uploads
                    'sort_order' => $imageSortOrders[$index] ?? ($index * 10),
                ]);
            }
        }

        return redirect()->route('admin.carmodels.index')->with('success', '✅ Thêm dòng xe thành công!');
    }

    public function edit(CarModel $carmodel)
    {
        $cars = CarBrand::all();
        return view('admin.carmodels.edit', [
            'carModel' => $carmodel,
            'cars' => $cars
        ]);
    }

    public function show(CarModel $carmodel)
    {
        $carmodel->load(['carBrand', 'images']);
        
        // Paginate car variants for this model
        $carVariants = $carmodel->carVariants()
            
            ->orderBy('name')
            ->paginate(6, ['*'], 'variants_page');
            
        return view('admin.carmodels.show', [
            'carModel' => $carmodel,
            'carVariants' => $carVariants
        ]);
    }

    public function update(Request $request, CarModel $carmodel)
    {
        $request->validate([
            'car_brand_id' => 'required|exists:car_brands,id',
            'name' => 'required|string|max:255|unique:car_models,name,' . $carmodel->id . ',id,deleted_at,NULL',
            'description' => 'nullable|string',
            'body_type' => 'nullable|string|max:100',
            'segment' => 'nullable|string|max:100',
            'fuel_type' => 'nullable|string|max:100',
            'production_start_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 5),
            'production_end_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'generation' => 'nullable|string|max:100',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'keywords' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_discontinued' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ], [
            'car_brand_id.required' => 'Vui lòng chọn hãng xe.',
            'car_brand_id.exists' => 'Hãng xe được chọn không tồn tại.',
            'name.required' => 'Tên dòng xe là bắt buộc.',
            'name.string' => 'Tên dòng xe phải là chuỗi ký tự.',
            'name.max' => 'Tên dòng xe không được vượt quá 255 ký tự.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'body_type.string' => 'Kiểu dáng phải là chuỗi ký tự.',
            'body_type.max' => 'Kiểu dáng không được vượt quá 100 ký tự.',
            'segment.string' => 'Phân khúc phải là chuỗi ký tự.',
            'segment.max' => 'Phân khúc không được vượt quá 100 ký tự.',
            'fuel_type.string' => 'Loại nhiên liệu phải là chuỗi ký tự.',
            'fuel_type.max' => 'Loại nhiên liệu không được vượt quá 100 ký tự.',
            'production_start_year.integer' => 'Năm bắt đầu sản xuất phải là số nguyên.',
            'production_start_year.min' => 'Năm bắt đầu sản xuất không được nhỏ hơn 1900.',
            'production_start_year.max' => 'Năm bắt đầu sản xuất không được lớn hơn ' . (date('Y') + 5) . '.',
            'production_end_year.integer' => 'Năm kết thúc sản xuất phải là số nguyên.',
            'production_end_year.min' => 'Năm kết thúc sản xuất không được nhỏ hơn 1900.',
            'production_end_year.max' => 'Năm kết thúc sản xuất không được lớn hơn ' . (date('Y') + 10) . '.',
            'generation.string' => 'Thế hệ phải là chuỗi ký tự.',
            'generation.max' => 'Thế hệ không được vượt quá 100 ký tự.',
            'meta_title.string' => 'Meta Title phải là chuỗi ký tự.',
            'meta_title.max' => 'Meta Title không được vượt quá 255 ký tự.',
            'meta_description.string' => 'Meta Description phải là chuỗi ký tự.',
            'meta_description.max' => 'Meta Description không được vượt quá 500 ký tự.',
            'keywords.string' => 'Keywords phải là chuỗi ký tự.',
            'sort_order.integer' => 'Thứ tự sắp xếp phải là số nguyên.',
            'sort_order.min' => 'Thứ tự sắp xếp không được nhỏ hơn 0.',
        ]);

        $data = $request->only([
            'car_brand_id', 'name', 'description', 'body_type', 'segment', 'fuel_type',
            'production_start_year', 'production_end_year', 'generation',
            'meta_title', 'meta_description', 'keywords',
            'is_active', 'is_featured', 'is_new', 'is_discontinued', 'sort_order'
        ]);

        // Set defaults for boolean fields
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured', false);
        $data['is_new'] = $request->boolean('is_new', false);
        $data['is_discontinued'] = $request->boolean('is_discontinued', false);
        $data['sort_order'] = $request->input('sort_order', 0);

        $carmodel->update($data);

        return redirect()->route('admin.carmodels.show', $carmodel)->with('success', '✅ Cập nhật dòng xe thành công!');
    }

    public function destroy(CarModel $carmodel)
    {
        // Detailed dependency analysis
        $variantsCount = $carmodel->carVariants()->count();
        $activeVariantsCount = $carmodel->carVariants()->where('is_active', true)->count();
        
        // Check for orders (if orders table exists and has proper structure)
        $ordersCount = 0;
        try {
            if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'car_variant_id')) {
                $ordersCount = DB::table('orders')
                    ->join('car_variants', 'orders.car_variant_id', '=', 'car_variants.id')
                    ->where('car_variants.car_model_id', $carmodel->id)
                    ->count();
            }
        } catch (\Exception $e) {
            $ordersCount = 0;
        }

        // Business logic validation with detailed messages
        if ($ordersCount > 0) {
            return redirect()->route('admin.carmodels.index')->with('error', 
                "⚠️ Không thể xóa dòng xe \"{$carmodel->name}\" vì đã có {$ordersCount} đơn hàng. " .
                "Bạn có thể 'Ngừng hoạt động' thay vì xóa để giữ lịch sử đơn hàng."
            );
        }

        if ($activeVariantsCount > 0) {
            return redirect()->route('admin.carmodels.index')->with('error', 
                "⚠️ Không thể xóa dòng xe \"{$carmodel->name}\" vì đang có {$activeVariantsCount} phiên bản đang bán. " .
                "Vui lòng ngừng bán tất cả phiên bản trước khi xóa dòng xe."
            );
        }

        if ($variantsCount > 0) {
            return redirect()->route('admin.carmodels.index')->with('warning', 
                "⚠️ Dòng xe \"{$carmodel->name}\" có {$variantsCount} phiên bản (đã ngừng hoạt động). " .
                "Bạn có chắc muốn xóa? Điều này sẽ xóa luôn tất cả phiên bản liên quan."
            )->with('confirm_delete', $carmodel->id);
        }

        // Safe to delete - no dependencies
        $this->performModelDeletion($carmodel);
        
        return redirect()->route('admin.carmodels.index')->with('success', 
            "✅ Đã xóa dòng xe \"{$carmodel->name}\" thành công!"
        );
    }

    private function performModelDeletion(CarModel $carmodel)
    {
        // Delete all model images and their files
        foreach ($carmodel->images as $image) {
            if ($image->image_url && Storage::disk('public')->exists($image->image_url)) {
                Storage::disk('public')->delete($image->image_url);
            }
            $image->delete();
        }

        // Delete all related variants (cascade)
        foreach ($carmodel->carVariants as $variant) {
            $variant->delete();
        }

        // Finally delete the model
        $carmodel->delete();
    }

    public function toggleStatus(Request $request, CarModel $carmodel)
    {
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $newStatus = $request->is_active;
        $carmodel->update(['is_active' => $newStatus]);

        $statusText = $newStatus ? 'kích hoạt' : 'ngừng hoạt động';
        
        // Get updated stats
        $totalModels = CarModel::count();
        $activeModels = CarModel::where('is_active', true)->count();
        $inactiveModels = CarModel::where('is_active', false)->count();
        $featuredModels = CarModel::where('is_featured', true)->count();
        $newModels = CarModel::where('is_new', true)->count();
        
        return response()->json([
            'success' => true,
            'message' => "✅ Đã {$statusText} dòng xe \"{$carmodel->name}\" thành công!",
            'is_active' => $newStatus,
            'stats' => [
                'totalModels' => $totalModels,
                'activeModels' => $activeModels,
                'inactiveModels' => $inactiveModels,
                'featuredModels' => $featuredModels,
                'newModels' => $newModels
            ]
        ]);
    }
}
