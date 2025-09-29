<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarBrand;
use App\Models\CarModel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        $carModels = $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc')->paginate(15);
        $brands = \App\Models\CarBrand::orderBy('sort_order')->orderBy('name')->get();

        // Check if this is an AJAX request
        if ($request->ajax()) {
            return view('admin.carmodels.partials.table', compact('carModels'))->render();
        }

        return view('admin.carmodels.index', compact('carModels', 'brands', 'totalModels', 'activeModels', 'inactiveModels', 'featuredModels', 'newModels'));
    }

    public function create()
    {
        $carBrands = CarBrand::orderBy('name')->get();
        return view('admin.carmodels.create', compact('carBrands'));
    }

    public function store(Request $request)
    {
        try {
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
            
            $successMessage = '✅ Đã khôi phục và cập nhật dòng xe thành công!';
            
            // Check if this is an AJAX request
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'redirect' => route('admin.carmodels.index')
                ]);
            }
            
            return redirect()->route('admin.carmodels.index')->with('success', $successMessage);
        }

        $request->validate([
            'car_brand_id' => 'required|exists:car_brands,id',
            'name' => 'required|string|max:255|unique:car_models,name,NULL,id,deleted_at,NULL',
            'slug' => 'nullable|string|max:255|unique:car_models,slug,NULL,id,deleted_at,NULL',
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
            'name.unique' => 'Tên dòng xe này đã tồn tại.',
            'slug.string' => 'Slug phải là chuỗi ký tự.',
            'slug.max' => 'Slug không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug này đã tồn tại.',
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
            'meta_title.string' => 'Tiêu đề SEO phải là chuỗi ký tự.',
            'meta_title.max' => 'Tiêu đề SEO không được vượt quá 255 ký tự.',
            'meta_description.string' => 'Mô tả SEO phải là chuỗi ký tự.',
            'meta_description.max' => 'Mô tả SEO không được vượt quá 500 ký tự.',
            'keywords.string' => 'Từ khóa phải là chuỗi ký tự.',
            'sort_order.integer' => 'Thứ tự sắp xếp phải là số nguyên.',
            'sort_order.min' => 'Thứ tự sắp xếp không được nhỏ hơn 0.',
            'images.array' => 'Hình ảnh phải là một mảng.',
            'images.max' => 'Không được tải lên quá 10 hình ảnh.',
            'images.*.image' => 'Tệp tải lên phải là hình ảnh.',
            'images.*.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, svg.',
            'images.*.max' => 'Kích thước hình ảnh không được vượt quá 10MB.',
            'image_types.array' => 'Loại hình ảnh phải là một mảng.',
            'image_types.*.required' => 'Vui lòng chọn loại hình ảnh.',
            'image_types.*.in' => 'Loại hình ảnh phải là: gallery, exterior, hoặc interior.',
            'image_titles.array' => 'Tiêu đề hình ảnh phải là một mảng.',
            'image_titles.*.string' => 'Tiêu đề hình ảnh phải là chuỗi ký tự.',
            'image_titles.*.max' => 'Tiêu đề hình ảnh không được vượt quá 255 ký tự.',
            'image_alt_texts.array' => 'Alt text hình ảnh phải là một mảng.',
            'image_alt_texts.*.string' => 'Alt text hình ảnh phải là chuỗi ký tự.',
            'image_alt_texts.*.max' => 'Alt text hình ảnh không được vượt quá 255 ký tự.',
            'image_descriptions.array' => 'Mô tả hình ảnh phải là một mảng.',
            'image_descriptions.*.string' => 'Mô tả hình ảnh phải là chuỗi ký tự.',
            'image_descriptions.*.max' => 'Mô tả hình ảnh không được vượt quá 500 ký tự.',
            'image_sort_orders.array' => 'Thứ tự hình ảnh phải là một mảng.',
            'image_sort_orders.*.integer' => 'Thứ tự hình ảnh phải là số nguyên.',
            'image_sort_orders.*.min' => 'Thứ tự hình ảnh không được nhỏ hơn 0.',
            'main_image_index.integer' => 'Chỉ số hình ảnh chính phải là số nguyên.',
            'main_image_index.min' => 'Chỉ số hình ảnh chính không được nhỏ hơn 0.',
        ]);

        $data = $request->only([
            'car_brand_id', 'name', 'description', 'body_type', 'segment', 'fuel_type',
            'production_start_year', 'production_end_year', 'generation',
            'meta_title', 'meta_description', 'keywords',
            'is_active', 'is_featured', 'is_new', 'is_discontinued', 'sort_order'
        ]);
        
        // Generate unique slug
        $data['slug'] = $this->generateUniqueSlug($data['name']);

        // Set defaults for boolean fields
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured', false);
        $data['is_new'] = $request->boolean('is_new', false);
        $data['is_discontinued'] = $request->boolean('is_discontinued', false);
        $data['sort_order'] = $request->input('sort_order', 0);

        $carModel = CarModel::create($data);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $mainImageIndex = $request->input('main_image_index');
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
                
                // Generate smart title based on image type
                $title = !empty($imageTitles[$index]) ? $imageTitles[$index] : 
                    match($imageType) {
                        'gallery' => $carModel->name . ' - Tổng quan',
                        'exterior' => $carModel->name . ' - Ngoại thất', 
                        'interior' => $carModel->name . ' - Nội thất',
                        default => $carModel->name . ' - Ảnh ' . ($index + 1)
                    };
                
                // Generate smart alt text based on image type (more SEO friendly)
                $altText = !empty($imageAltTexts[$index]) ? $imageAltTexts[$index] : 
                    match($imageType) {
                        'gallery' => 'Hình ảnh tổng quan ' . $carModel->name . ' chất lượng cao',
                        'exterior' => 'Hình ảnh ngoại thất ' . $carModel->name . ' góc nhìn đẹp', 
                        'interior' => 'Hình ảnh nội thất ' . $carModel->name . ' thiết kế hiện đại',
                        default => $carModel->name . ' - Ảnh chất lượng cao ' . ($index + 1)
                    };
                
                // Generate smart description based on image type (more detailed)
                $description = !empty($imageDescriptions[$index]) ? $imageDescriptions[$index] : 
                    match($imageType) {
                        'gallery' => 'Hình ảnh tổng quan toàn diện của ' . $carModel->name . ' với góc nhìn đẹp mắt, thể hiện rõ nét thiết kế và đặc điểm nổi bật của xe.',
                        'exterior' => 'Hình ảnh ngoại thất chi tiết của ' . $carModel->name . ' với thiết kế hiện đại, thể hiện đường nét và phong cách độc đáo của mẫu xe.', 
                        'interior' => 'Hình ảnh nội thất sang trọng của ' . $carModel->name . ' với không gian rộng rãi, tiện nghi hiện đại và chất liệu cao cấp.',
                        default => ''
                    };

                $carModel->images()->create([
                    'image_url' => $path,
                    'alt_text' => $altText,
                    'title' => $title,
                    'description' => $description,
                    'image_type' => $imageType,
                    'is_main' => ($mainImageIndex !== null && $mainImageIndex !== '' && $index == $mainImageIndex),
                    'is_active' => true, // Always active for new uploads
                    'sort_order' => $imageSortOrders[$index] ?? ($index + 1),
                ]);
            }
        }

        $successMessage = '✅ Thêm dòng xe thành công!';
        
        // Check if this is an AJAX request
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect' => route('admin.carmodels.index')
            ]);
        }
        
        return redirect()->route('admin.carmodels.index')->with('success', $successMessage);
        
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors for AJAX requests
            if ($request->wantsJson() || $request->ajax()) {
                // Get first error message for toast
                $firstError = collect($e->errors())->flatten()->first();
                
                return response()->json([
                    'success' => false,
                    'message' => $firstError ?: 'Dữ liệu không hợp lệ',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            // Log the full error for debugging
            Log::error('CarModel creation error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Handle other errors
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi thêm dòng xe: ' . $e->getMessage(),
                    'debug' => [
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Có lỗi xảy ra khi thêm dòng xe: ' . $e->getMessage());
        }
    }

    public function edit(CarModel $carmodel)
    {
        $carBrands = CarBrand::orderBy('name')->get();
        return view('admin.carmodels.edit', [
            'carModel' => $carmodel,
            'carBrands' => $carBrands
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
        try {
            $request->validate([
            'car_brand_id' => 'required|exists:car_brands,id',
            'name' => 'required|string|max:255|unique:car_models,name,' . $carmodel->id . ',id,deleted_at,NULL',
            'slug' => 'nullable|string|max:255|unique:car_models,slug,' . $carmodel->id . ',id,deleted_at,NULL',
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
            'name.unique' => 'Tên dòng xe này đã tồn tại.',
            'slug.string' => 'Slug phải là chuỗi ký tự.',
            'slug.max' => 'Slug không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug này đã tồn tại.',
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
            'meta_title.string' => 'Tiêu đề SEO phải là chuỗi ký tự.',
            'meta_title.max' => 'Tiêu đề SEO không được vượt quá 255 ký tự.',
            'meta_description.string' => 'Mô tả SEO phải là chuỗi ký tự.',
            'meta_description.max' => 'Mô tả SEO không được vượt quá 500 ký tự.',
            'keywords.string' => 'Từ khóa phải là chuỗi ký tự.',
            'sort_order.integer' => 'Thứ tự sắp xếp phải là số nguyên.',
            'sort_order.min' => 'Thứ tự sắp xếp không được nhỏ hơn 0.',
            'images.array' => 'Hình ảnh phải là một mảng.',
            'images.max' => 'Không được tải lên quá 10 hình ảnh.',
            'images.*.image' => 'Tệp tải lên phải là hình ảnh.',
            'images.*.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, svg.',
            'images.*.max' => 'Kích thước hình ảnh không được vượt quá 10MB.',
            'image_types.array' => 'Loại hình ảnh phải là một mảng.',
            'image_types.*.required' => 'Vui lòng chọn loại hình ảnh.',
            'image_types.*.in' => 'Loại hình ảnh phải là: gallery, exterior, hoặc interior.',
            'image_titles.array' => 'Tiêu đề hình ảnh phải là một mảng.',
            'image_titles.*.string' => 'Tiêu đề hình ảnh phải là chuỗi ký tự.',
            'image_titles.*.max' => 'Tiêu đề hình ảnh không được vượt quá 255 ký tự.',
            'image_alt_texts.array' => 'Alt text hình ảnh phải là một mảng.',
            'image_alt_texts.*.string' => 'Alt text hình ảnh phải là chuỗi ký tự.',
            'image_alt_texts.*.max' => 'Alt text hình ảnh không được vượt quá 255 ký tự.',
            'image_descriptions.array' => 'Mô tả hình ảnh phải là một mảng.',
            'image_descriptions.*.string' => 'Mô tả hình ảnh phải là chuỗi ký tự.',
            'image_descriptions.*.max' => 'Mô tả hình ảnh không được vượt quá 500 ký tự.',
            'image_sort_orders.array' => 'Thứ tự hình ảnh phải là một mảng.',
            'image_sort_orders.*.integer' => 'Thứ tự hình ảnh phải là số nguyên.',
            'image_sort_orders.*.min' => 'Thứ tự hình ảnh không được nhỏ hơn 0.',
            'main_image_index.integer' => 'Chỉ số hình ảnh chính phải là số nguyên.',
            'main_image_index.min' => 'Chỉ số hình ảnh chính không được nhỏ hơn 0.',
        ]);

        $data = $request->only([
            'car_brand_id', 'name', 'description', 'body_type', 'segment', 'fuel_type',
            'production_start_year', 'production_end_year', 'generation',
            'meta_title', 'meta_description', 'keywords',
            'is_active', 'is_featured', 'is_new', 'is_discontinued', 'sort_order'
        ]);
        
        // Generate unique slug only if name changed
        if (!empty($data['name']) && $data['name'] !== $carmodel->name) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $carmodel->id);
        }

        // Set defaults for boolean fields
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured', false);
        $data['is_new'] = $request->boolean('is_new', false);
        $data['is_discontinued'] = $request->boolean('is_discontinued', false);
        $data['sort_order'] = $request->input('sort_order', 0);

        $carmodel->update($data);
        
        // Handle new images upload
        if ($request->hasFile('new_images')) {
            $newImageTypes = $request->input('new_image_types', []);
            $newImageTitles = $request->input('new_image_titles', []);
            $newImageAltTexts = $request->input('new_image_alt_texts', []);
            $newImageDescriptions = $request->input('new_image_descriptions', []);
            $newImageSortOrders = $request->input('new_image_sort_orders', []);
            $newImageIsActive = $request->input('new_image_is_active', []);
            $newMainImageIndex = $request->input('new_main_image_index');
            
            // If a new image is set as main, reset all existing images to not main
            if ($newMainImageIndex !== null && $newMainImageIndex !== '') {
                $carmodel->images()->update(['is_main' => false]);
            }
            
            foreach ($request->file('new_images') as $index => $image) {
                $path = $image->store('uploads/car-models', 'public');
                
                // Generate smart defaults
                $imageType = $newImageTypes[$index] ?? 'gallery';
                
                // Generate smart title based on image type
                $title = !empty($newImageTitles[$index]) ? $newImageTitles[$index] : 
                    match($imageType) {
                        'gallery' => $carmodel->name . ' - Tổng quan',
                        'exterior' => $carmodel->name . ' - Ngoại thất', 
                        'interior' => $carmodel->name . ' - Nội thất',
                        default => $carmodel->name . ' - Ảnh ' . ($index + 1)
                    };
                
                // Generate smart alt text based on image type (more SEO friendly)
                $altText = !empty($newImageAltTexts[$index]) ? $newImageAltTexts[$index] :
                    match($imageType) {
                        'gallery' => 'Hình ảnh tổng quan ' . $carmodel->name . ' chất lượng cao',
                        'exterior' => 'Hình ảnh ngoại thất ' . $carmodel->name . ' góc nhìn đẹp', 
                        'interior' => 'Hình ảnh nội thất ' . $carmodel->name . ' thiết kế hiện đại',
                        default => $carmodel->name . ' - Ảnh chất lượng cao ' . ($index + 1)
                    };
                
                // Generate smart description based on image type (more detailed)
                $description = !empty($newImageDescriptions[$index]) ? $newImageDescriptions[$index] : 
                    match($imageType) {
                        'gallery' => 'Hình ảnh tổng quan toàn diện của ' . $carmodel->name . ' với góc nhìn đẹp mắt, thể hiện rõ nét thiết kế và đặc điểm nổi bật của xe.',
                        'exterior' => 'Hình ảnh ngoại thất chi tiết của ' . $carmodel->name . ' với thiết kế hiện đại, thể hiện đường nét và phong cách độc đáo của mẫu xe.', 
                        'interior' => 'Hình ảnh nội thất sang trọng của ' . $carmodel->name . ' với không gian rộng rãi, tiện nghi hiện đại và chất liệu cao cấp.',
                        default => ''
                    };

                $carmodel->images()->create([
                    'image_url' => $path,
                    'alt_text' => $altText,
                    'title' => $title,
                    'description' => $description,
                    'image_type' => $imageType,
                    'is_main' => ($newMainImageIndex !== null && $newMainImageIndex !== '' && $index == $newMainImageIndex),
                    'is_active' => isset($newImageIsActive[$index]) ? (bool)$newImageIsActive[$index] : true,
                    'sort_order' => $newImageSortOrders[$index] ?? ($index + 1),
                ]);
            }
        }

        $successMessage = '✅ Cập nhật dòng xe thành công!';
        
        // Check if this is an AJAX request
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect' => route('admin.carmodels.index')
            ]);
        }

        return redirect()->route('admin.carmodels.index')->with('success', $successMessage);
        
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors for AJAX requests
            if ($request->wantsJson() || $request->ajax()) {
                // Get first error message for toast
                $firstError = collect($e->errors())->flatten()->first();
                
                return response()->json([
                    'success' => false,
                    'message' => $firstError ?: 'Dữ liệu không hợp lệ',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            // Handle other errors
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi cập nhật dòng xe: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Có lỗi xảy ra khi cập nhật dòng xe: ' . $e->getMessage());
        }
    }

    public function destroy(CarModel $carmodel)
    {
        // Detailed dependency analysis
        $variantsCount = $carmodel->carVariants()->count();
        $activeVariantsCount = $carmodel->carVariants()->where('is_active', true)->count();
        
        // Check for orders (via order_items polymorphic relationship)
        $ordersCount = 0;
        try {
            if (Schema::hasTable('order_items') && Schema::hasTable('orders')) {
                $ordersCount = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->join('car_variants', 'order_items.item_id', '=', 'car_variants.id')
                    ->where('order_items.item_type', 'car_variant')
                    ->where('car_variants.car_model_id', $carmodel->id)
                    ->whereNull('orders.deleted_at')
                    ->count();
            }
        } catch (\Exception $e) {
            $ordersCount = 0;
        }

        // Check for service appointments
        $serviceAppointmentsCount = 0;
        try {
            if (Schema::hasTable('service_appointments')) {
                $serviceAppointmentsCount = DB::table('service_appointments')
                    ->join('car_variants', 'service_appointments.car_variant_id', '=', 'car_variants.id')
                    ->where('car_variants.car_model_id', $carmodel->id)
                    ->count();
            }
        } catch (\Exception $e) {
            $serviceAppointmentsCount = 0;
        }

        // Check if model has active variants (first priority)
        if ($activeVariantsCount > 0) {
            $message = "Không thể xóa dòng xe \"{$carmodel->name}\" vì đang có {$activeVariantsCount} phiên bản đang bán. Vui lòng tạm dừng tất cả phiên bản trước khi xóa dòng xe.";
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'action_suggestion' => 'deactivate',
                    'show_deactivate_button' => true
                ], 400);
            }
            
            return redirect()->route('admin.carmodels.index')->with('error', $message);
        }

        // CRITICAL: Never delete if has business transaction data (second priority)
        if ($ordersCount > 0 || $serviceAppointmentsCount > 0) {
            $businessData = [];
            if ($ordersCount > 0) {
                $businessData[] = "{$ordersCount} đơn hàng";
            }
            if ($serviceAppointmentsCount > 0) {
                $businessData[] = "{$serviceAppointmentsCount} lịch bảo dưỡng";
            }
            
            $message = "KHÔNG THỂ XÓA dòng xe \"{$carmodel->name}\" vì có " . implode(', ', $businessData) . ". " .
                      "Đây là dữ liệu giao dịch quan trọng! Bạn chỉ có thể 'Tạm dừng' để ngừng hoạt động nhưng vẫn giữ lịch sử.";
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'action_suggestion' => 'deactivate',
                    'show_deactivate_button' => true
                ], 422); // Unprocessable Entity
            }
            
            return redirect()->route('admin.carmodels.index')->with('error', $message);
        }

        if ($variantsCount > 0) {
            $warningMessage = "Bạn có chắc muốn xóa dòng xe \"{$carmodel->name}\"? Hành động này sẽ xóa vĩnh viễn {$variantsCount} phiên bản xe (đã ngừng hoạt động) cùng tất cả dữ liệu liên quan.";
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $warningMessage,
                    'requires_confirmation' => true
                ], 400);
            }
            
            return redirect()->route('admin.carmodels.index')->with('warning', $warningMessage)
                ->with('confirm_delete', $carmodel->id);
        }

        // Safe to delete - no dependencies
        $this->performModelDeletion($carmodel);
        
        // Get updated stats
        $totalModels = CarModel::count();
        $activeModels = CarModel::where('is_active', true)->count();
        $inactiveModels = CarModel::where('is_active', false)->count();
        $featuredModels = CarModel::where('is_featured', true)->count();
        $newModels = CarModel::where('is_new', true)->count();
        
        // Return JSON response for AJAX requests
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Đã xóa dòng xe \"{$carmodel->name}\" thành công!",
                'stats' => [
                    'totalModels' => $totalModels,
                    'activeModels' => $activeModels,
                    'inactiveModels' => $inactiveModels,
                    'featuredModels' => $featuredModels,
                    'newModels' => $newModels
                ]
            ]);
        }
        
        return redirect()->route('admin.carmodels.index')->with('success', 
            "Đã xóa dòng xe \"{$carmodel->name}\" thành công!"
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

        $statusText = $newStatus ? 'kích hoạt' : 'tạm dừng';
        
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
    
    /**
     * Generate unique slug for CarModel
     */
    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = \Illuminate\Support\Str::slug($name);
        $slug = $baseSlug;
        $counter = 2;
        
        $query = CarModel::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        
        while ($query->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
            
            $query = CarModel::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }
        
        return $slug;
    }
    
    /**
     * Delete CarModel image
     */
    public function deleteImage($imageId)
    {
        try {
            // Find the image
            $image = \App\Models\CarModelImage::findOrFail($imageId);
            
            // Delete the file from storage
            if ($image->image_url && Storage::disk('public')->exists($image->image_url)) {
                Storage::disk('public')->delete($image->image_url);
            }
            
            // Delete the database record
            $image->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Xóa ảnh thành công!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Delete image error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa ảnh: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Set CarModel image as main
     */
    public function setMainImage($imageId)
    {
        try {
            // Find the image
            $image = \App\Models\CarModelImage::findOrFail($imageId);
            
            // Remove main status from all images of this car model
            \App\Models\CarModelImage::where('car_model_id', $image->car_model_id)
                                    ->update(['is_main' => false]);
            
            // Set this image as main
            $image->update(['is_main' => true]);
            
            return response()->json([
                'success' => true,
                'message' => 'Đã đặt làm ảnh chính!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Set main image error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đặt ảnh chính: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update CarModel image details
     */
    public function updateImage(Request $request, $imageId)
    {
        try {
            // Find the image
            $image = \App\Models\CarModelImage::findOrFail($imageId);
            
            // Validate input
            $request->validate([
                'image_type' => 'required|in:gallery,exterior,interior',
                'title' => 'nullable|string|max:255',
                'alt_text' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'sort_order' => 'nullable|integer|min:0',
                'is_active' => 'boolean',
            ]);
            
            // Update image
            $image->update([
                'image_type' => $request->image_type,
                'title' => $request->title,
                'alt_text' => $request->alt_text,
                'description' => $request->description,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->boolean('is_active', true),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật ảnh thành công!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Update image error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật ảnh: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Clean up old image descriptions and alt texts
     */
    public function cleanupImageData()
    {
        try {
            $updatedCount = 0;
            
            // Get all images with old description pattern
            $images = \App\Models\CarModelImage::where('description', 'LIKE', 'Hình ảnh của %')
                                              ->orWhere('description', 'LIKE', 'Hình ảnh tổng quan của %')
                                              ->orWhere('description', 'LIKE', 'Hình ảnh ngoại thất của %')
                                              ->orWhere('description', 'LIKE', 'Hình ảnh nội thất của %')
                                              ->orWhere('alt_text', 'LIKE', '% - Ảnh %')
                                              ->get();
            
            foreach ($images as $image) {
                $carModel = $image->carModel;
                if (!$carModel) continue;
                
                // Generate new smart alt text and description
                $newAltText = match($image->image_type) {
                    'gallery' => $carModel->name . ' - Hình ảnh tổng quan',
                    'exterior' => $carModel->name . ' - Hình ảnh ngoại thất', 
                    'interior' => $carModel->name . ' - Hình ảnh nội thất',
                    default => $carModel->name . ' - Hình ảnh'
                };
                
                // Clear old description or set empty
                $newDescription = '';
                
                $image->update([
                    'alt_text' => $newAltText,
                    'description' => $newDescription
                ]);
                
                $updatedCount++;
            }
            
            return response()->json([
                'success' => true,
                'message' => "Đã cập nhật {$updatedCount} hình ảnh thành công!"
            ]);
            
        } catch (\Exception $e) {
            Log::error('Cleanup image data error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật: ' . $e->getMessage()
            ], 500);
        }
    }
}
