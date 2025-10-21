<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarVariant;
use App\Models\CarVariantImage;
use App\Models\CarModel;
use App\Models\CarBrand;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CarVariantController extends Controller
{
    public function index(Request $request)
    {
        $query = CarVariant::with(['carModel.carBrand', 'colors', 'images', 'specifications']);

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
                case 'bestseller':
                    $query->where('is_bestseller', true);
                    break;
            }
        }


        $carVariants = $query->orderBy('sort_order')
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);
        
        // Append query parameters to pagination links
        $carVariants->appends($request->except(['page', 'ajax', 'with_stats']));

        // Get car models for filter dropdown - format for dropdown component
        $carModels = CarModel::with('carBrand')->orderBy('name')->get()->map(function($model) {
            return [
                'id' => $model->id,
                'name' => $model->name,
                'carBrand' => $model->carBrand ? $model->carBrand->name : ''
            ];
        });

        // Calculate stats - ensure all are integers
        $totalVariants = (int) CarVariant::count();
        $activeVariants = (int) CarVariant::where('is_active', true)->count();
        $inactiveVariants = (int) CarVariant::where('is_active', false)->count();
        $featuredVariants = (int) CarVariant::where('is_featured', true)->count();
        $onSaleVariants = (int) CarVariant::where('is_on_sale', true)->count();
        $newArrivalVariants = (int) CarVariant::where('is_new_arrival', true)->count();
        $bestsellerVariants = (int) CarVariant::where('is_bestseller', true)->count();

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
            'newArrivalVariants',
            'bestsellerVariants'
        ));
    }

    public function create()
    {
        $carModels = CarModel::with('carBrand')->orderBy('name')->get();
        return view('admin.carvariants.create', compact('carModels'));
    }

    public function store(Request $request)
    {
        // Validate the request with Vietnamese messages
        $validated = $request->validate([
            'car_model_id' => 'required|exists:car_models,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255|unique:car_variants,sku',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'current_price' => 'required|numeric|min:0',
            'is_on_sale' => 'boolean',
            'is_active' => 'boolean',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_bestseller' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'keywords' => 'nullable|string',
            // Images handled separately below
            // Colors validation
            'colors' => 'nullable|array',
            'colors.*.color_name' => 'required_with:colors|string|max:255',
            'colors.*.color_code' => 'nullable|string|max:255',
            'colors.*.hex_code' => 'nullable|string|max:7',
            'colors.*.rgb_code' => 'nullable|string|max:255',
            'colors.*.color_type' => 'nullable|in:solid,metallic,pearlescent,matte,special',
            'colors.*.availability' => 'nullable|in:standard,optional,limited,discontinued',
            'colors.*.price_adjustment' => 'nullable|numeric|min:0',
            'colors.*.is_free' => 'nullable|boolean',
            'colors.*.description' => 'nullable|string|max:1000',
            'colors.*.is_popular' => 'nullable|boolean',
            'colors.*.sort_order' => 'nullable|integer|min:0',
            'colors.*.quantity' => 'nullable|integer|min:0',
            'colors.*.reserved' => 'nullable|integer|min:0',
            'colors.*.is_active' => 'nullable|boolean',
            // Specifications validation
            'specifications' => 'nullable|array',
            'specifications.*.category' => 'required_with:specifications|string|max:255',
            'specifications.*.spec_name' => 'required_with:specifications|string|max:255',
            'specifications.*.spec_value' => 'required_with:specifications|string|max:255',
            'specifications.*.unit' => 'nullable|string|max:50',
            'specifications.*.description' => 'nullable|string|max:1000',
            'specifications.*.spec_code' => 'nullable|string|max:255',
            'specifications.*.is_important' => 'nullable|boolean',
            'specifications.*.is_highlighted' => 'nullable|boolean',
            'specifications.*.sort_order' => 'nullable|integer|min:0',
            // Features validation
            'features' => 'nullable|array',
            'features.*.feature_name' => 'required_with:features|string|max:255',
            'features.*.category' => 'required_with:features|string|in:safety,comfort,technology,performance,exterior,interior,entertainment,convenience,wheels,audio,navigation',
            'features.*.description' => 'nullable|string|max:1000',
            'features.*.feature_code' => 'nullable|string|max:255',
            'features.*.availability' => 'nullable|string|in:standard,optional',
            'features.*.importance' => 'nullable|string|in:essential,important,nice_to_have,luxury',
            'features.*.price' => 'nullable|numeric|min:0',
            'features.*.is_included' => 'nullable|boolean',
            'features.*.is_active' => 'nullable|boolean',
            'features.*.is_featured' => 'nullable|boolean',
            'features.*.is_popular' => 'nullable|boolean',
            'features.*.is_recommended' => 'nullable|boolean',
            'features.*.sort_order' => 'nullable|integer|min:0',
            // Images validation
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            // Individual image metadata validation
            'individual_titles' => 'nullable|array',
            'individual_titles.*' => 'nullable|string|max:255',
            'individual_alt_texts' => 'nullable|array',
            'individual_alt_texts.*' => 'nullable|string|max:255',
            'individual_angles' => 'nullable|array',
            'individual_angles.*' => 'nullable|string',
            'individual_sort_orders' => 'nullable|array',
            'individual_sort_orders.*' => 'nullable|integer|min:0',
            'individual_descriptions' => 'nullable|array',
            'individual_descriptions.*' => 'nullable|string|max:1000',
            'individual_is_main' => 'nullable|array',
            'individual_is_main.*' => 'nullable|in:0,1',
            'individual_is_active' => 'nullable|array',
            'individual_is_active.*' => 'nullable|in:0,1',
            'individual_image_types' => 'nullable|array',
            'individual_image_types.*' => 'nullable|in:gallery,exterior,interior',
            'individual_color_ids' => 'nullable|array',
            'individual_color_ids.*' => 'nullable|string'
        ], [
            // Basic CarVariant validation messages
            'car_model_id.required' => 'Vui lòng chọn dòng xe.',
            'car_model_id.exists' => 'Dòng xe được chọn không tồn tại.',
            'name.required' => 'Vui lòng nhập tên phiên bản xe.',
            'name.max' => 'Tên phiên bản không được vượt quá 255 ký tự.',
            'sku.unique' => 'Mã SKU này đã được sử dụng. Vui lòng nhập mã khác.',
            'sku.max' => 'Mã SKU không được vượt quá 255 ký tự.',
            'base_price.required' => 'Vui lòng nhập giá gốc.',
            'base_price.numeric' => 'Giá gốc phải là số.',
            'base_price.min' => 'Giá gốc không được âm.',
            'current_price.required' => 'Vui lòng nhập giá hiện tại.',
            'current_price.numeric' => 'Giá hiện tại phải là số.',
            'current_price.min' => 'Giá hiện tại không được âm.',
            
            // Colors validation messages
            'colors.*.color_name.required_with' => 'Vui lòng nhập tên màu.',
            'colors.*.color_name.max' => 'Tên màu không được vượt quá 255 ký tự.',
            'colors.*.hex_code.max' => 'Mã hex không được vượt quá 7 ký tự.',
            'colors.*.color_type.in' => 'Loại màu không hợp lệ.',
            'colors.*.availability.in' => 'Tình trạng màu không hợp lệ.',
            'colors.*.price_adjustment.numeric' => 'Phụ phí màu phải là số.',
            'colors.*.price_adjustment.min' => 'Phụ phí màu không được âm.',
            'colors.*.quantity.integer' => 'Số lượng phải là số nguyên.',
            'colors.*.quantity.min' => 'Số lượng không được âm.',
            'colors.*.reserved.integer' => 'Số lượng đặt trước phải là số nguyên.',
            'colors.*.reserved.min' => 'Số lượng đặt trước không được âm.',
            
            // Specifications validation messages
            'specifications.*.category.required_with' => 'Vui lòng chọn danh mục thông số.',
            'specifications.*.spec_name.required_with' => 'Vui lòng nhập tên thông số.',
            'specifications.*.spec_name.max' => 'Tên thông số không được vượt quá 255 ký tự.',
            'specifications.*.spec_value.required_with' => 'Vui lòng nhập giá trị thông số.',
            'specifications.*.spec_value.max' => 'Giá trị thông số không được vượt quá 255 ký tự.',
            'specifications.*.unit.max' => 'Đơn vị không được vượt quá 50 ký tự.',
            
            // Features validation messages
            'features.*.feature_name.required_with' => 'Vui lòng nhập tên tính năng.',
            'features.*.feature_name.max' => 'Tên tính năng không được vượt quá 255 ký tự.',
            'features.*.category.required_with' => 'Vui lòng chọn danh mục tính năng.',
            'features.*.category.in' => 'Danh mục tính năng không hợp lệ.',
            'features.*.availability.in' => 'Tình trạng tính năng không hợp lệ.',
            'features.*.importance.in' => 'Mức độ quan trọng không hợp lệ.',
            'features.*.price.numeric' => 'Giá tính năng phải là số.',
            'features.*.price.min' => 'Giá tính năng không được âm.',
            
            // Images validation messages
            'images.*.image' => 'File phải là hình ảnh.',
            'images.*.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'images.*.max' => 'Kích thước hình ảnh không được vượt quá 10MB.',
            'individual_titles.*.max' => 'Tiêu đề hình ảnh không được vượt quá 255 ký tự.',
            'individual_alt_texts.*.max' => 'Alt text không được vượt quá 255 ký tự.',
            'individual_sort_orders.*.integer' => 'Thứ tự sắp xếp phải là số nguyên.',
            'individual_sort_orders.*.min' => 'Thứ tự sắp xếp không được âm.',
            'individual_descriptions.*.max' => 'Mô tả hình ảnh không được vượt quá 1000 ký tự.',
            'individual_image_types.*.in' => 'Loại hình ảnh không hợp lệ.',
        ]);
        
        // Auto-generate SKU if empty
        if (empty($validated['sku'])) {
            $validated['sku'] = $this->generateUniqueSKU($validated['car_model_id'], $validated['name']);
        }
        
        // Clean up any orphaned specifications for this variant name to avoid conflicts
        $this->cleanupOrphanedSpecifications($validated['car_model_id'], $validated['name']);

        // Check for soft deleted variant - restore if exists (AFTER validation)
        $softDeletedVariant = null;
        if (!empty($validated['name']) && !empty($validated['car_model_id'])) {
            $softDeletedVariant = CarVariant::withTrashed()
                                           ->where('name', $validated['name'])
                                           ->where('car_model_id', $validated['car_model_id'])
                                           ->whereNotNull('deleted_at')
                                           ->first();
        }
        
        if ($softDeletedVariant) {
            // Restore and update the soft deleted record
            $softDeletedVariant->restore();
            
            // Update with new data
            $data = $request->only([
                'sku', 'description', 'short_description', 'base_price', 'current_price',
                'is_on_sale', 'is_active', 'is_available', 'is_featured', 
                'is_new_arrival', 'is_bestseller', 'sort_order',
                'meta_title', 'meta_description', 'keywords'
            ]);
            
            // Generate simple slug from name
            $data['slug'] = Str::slug($request->name);
            
            // Set defaults for boolean fields
            $data['is_active'] = $request->boolean('is_active', true);
            
            $softDeletedVariant->update($data);
            
            // Use the restored variant for further processing
            $carVariant = $softDeletedVariant;
            $successMessage = ' Đã khôi phục và cập nhật phiên bản xe thành công!';
            
            // Continue to process colors, specifications, features...
            // (Don't return here, let it fall through to the processing below)
        } else {
            // Create new variant
            $carVariant = CarVariant::create([
            'car_model_id' => $validated['car_model_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'sku' => $validated['sku'],
            'description' => $validated['description'],
            'short_description' => $validated['short_description'],
            'base_price' => $validated['base_price'],
            'current_price' => $validated['current_price'],
            'is_on_sale' => $request->boolean('is_on_sale', false),
            'is_active' => $request->boolean('is_active', true),
            'is_available' => $request->boolean('is_available', true),
            'is_featured' => $request->boolean('is_featured', false),
            'is_new_arrival' => $request->boolean('is_new_arrival', false),
            'is_bestseller' => $request->boolean('is_bestseller', false),
            'sort_order' => $validated['sort_order'] ?? 0,
            'meta_title' => $validated['meta_title'],
            'meta_description' => $validated['meta_description'],
            'keywords' => $validated['keywords'],
        ]);
            
            $successMessage = 'Đã tạo phiên bản xe thành công!';
        }

        // Store created colors for image linking
        $createdColors = [];

        // Debug: Log request data
        Log::info('=== CARVARIANT CREATE DEBUG ===');
        Log::info('Request colors:', ['colors' => $request->input('colors', [])]);
        Log::info('Has colors: ' . ($request->has('colors') ? 'true' : 'false'));
        Log::info('Colors is array: ' . (is_array($request->colors) ? 'true' : 'false'));
        Log::info('=== END DEBUG ===');
        
        // Handle colors
        if ($request->has('colors') && is_array($request->colors)) {
            $colorInventory = [];
            foreach ($request->colors as $index => $colorData) {
                if (!empty($colorData['color_name'])) {
                    $color = $carVariant->colors()->create([
                        'color_name' => $colorData['color_name'],
                        'color_code' => $colorData['color_code'] ?? null,
                        'hex_code' => $colorData['hex_code'] ?? null,
                        'rgb_code' => $colorData['rgb_code'] ?? null,
                        'color_type' => $colorData['color_type'] ?? 'solid',
                        'availability' => $colorData['availability'] ?? 'standard',
                        'price_adjustment' => $colorData['price_adjustment'] ?? 0,
                        'is_free' => isset($colorData['is_free']) ? true : false,
                        'description' => $colorData['description'] ?? null,
                        'is_popular' => isset($colorData['is_popular']) ? true : false,
                        'is_active' => isset($colorData['is_active']) ? true : false,
                        'sort_order' => $colorData['sort_order'] ?? 0,
                    ]);
                    
                    // Store created color ID for image linking
                    $createdColors[$index] = $color->id;
                    
                    // Store inventory data
                    $quantity = (int) ($colorData['quantity'] ?? 0);
                    $reserved = (int) ($colorData['reserved'] ?? 0);
                    $colorInventory[$color->id] = [
                        'quantity' => $quantity,
                        'reserved' => $reserved,
                        'available' => max(0, $quantity - $reserved)
                    ];
                }
            }
            
            // Update color_inventory JSON field
            if (!empty($colorInventory)) {
                $carVariant->update(['color_inventory' => $colorInventory]);
            }
        }

        // Debug: Log specifications data
        Log::info('=== SPECIFICATIONS DEBUG ===');
        Log::info('Request specifications:', ['specifications' => $request->input('specifications', [])]);
        Log::info('Has specifications: ' . ($request->has('specifications') ? 'true' : 'false'));
        Log::info('Specifications is array: ' . (is_array($request->specifications) ? 'true' : 'false'));
        Log::info('=== END SPECIFICATIONS DEBUG ===');
        
        // Debug: Log images data
        Log::info('=== IMAGES DEBUG ===');
        Log::info('Has images files: ' . ($request->hasFile('images') ? 'true' : 'false'));
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            Log::info('Number of image files: ' . count($files));
            foreach ($files as $index => $file) {
                Log::info("Image file {$index}:", [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                    'valid' => $file->isValid()
                ]);
            }
        }
        Log::info('=== END IMAGES DEBUG ===');
        
        // Handle image uploads with individual metadata (AFTER colors are created)
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $titles = $request->input('individual_titles', []);
            $altTexts = $request->input('individual_alt_texts', []);
            $angles = $request->input('individual_angles', []);
            $sortOrders = $request->input('individual_sort_orders', []);
            $descriptions = $request->input('individual_descriptions', []);
            $isMainFlags = $request->input('individual_is_main', []);
            $isActiveFlags = $request->input('individual_is_active', []);
            $imageTypes = $request->input('individual_image_types', []);
            $colorIds = $request->input('individual_color_ids', []);
            
            foreach ($images as $index => $image) {
                $path = $image->store('uploads/car-variants', 'public');
                
                // Handle color linking for temp IDs
                $linkedColorId = null;
                if (!empty($colorIds[$index])) {
                    if (str_starts_with($colorIds[$index], 'temp_')) {
                        // Extract temp index (e.g., 'temp_0' -> 0)
                        $tempIndex = (int) str_replace('temp_', '', $colorIds[$index]);
                        // Get the created color ID from our stored array
                        $linkedColorId = $createdColors[$tempIndex] ?? null;
                    } else {
                        // Real color ID
                        $linkedColorId = $colorIds[$index];
                    }
                }
                
                $carVariant->images()->create([
                    'image_url' => $path,
                    'alt_text' => $altTexts[$index] ?? ($carVariant->name . ' - Hình ' . ($index + 1)),
                    'title' => $titles[$index] ?? $carVariant->name,
                    'image_type' => $imageTypes[$index] ?? 'gallery',
                    'angle' => !empty($angles[$index]) ? $angles[$index] : null,
                    'description' => $descriptions[$index] ?? null,
                    'is_main' => isset($isMainFlags[$index]) && $isMainFlags[$index] == '1',
                    'is_active' => isset($isActiveFlags[$index]) && $isActiveFlags[$index] == '1',
                    'sort_order' => $sortOrders[$index] ?? $index,
                    'car_variant_color_id' => $linkedColorId,
                ]);
            }
        }
        
        // Handle specifications
        if ($request->has('specifications') && is_array($request->specifications)) {
            foreach ($request->specifications as $specData) {
                if (!empty($specData['spec_name']) && !empty($specData['spec_value'])) {
                    $carVariant->specifications()->create([
                        'category' => $specData['category'] ?? 'other',
                        'spec_name' => $specData['spec_name'],
                        'spec_value' => $specData['spec_value'],
                        'unit' => $specData['unit'] ?? null,
                        'description' => $specData['description'] ?? null,
                        'spec_code' => $specData['spec_code'] ?? null,
                        'is_important' => isset($specData['is_important']) ? true : false,
                        'is_highlighted' => isset($specData['is_highlighted']) ? true : false,
                        'sort_order' => $specData['sort_order'] ?? 0,
                    ]);
                }
            }
        }

        // Handle features
        if ($request->has('features') && is_array($request->features)) {
            foreach ($request->features as $featureData) {
                if (!empty($featureData['feature_name'])) {
                    $carVariant->featuresRelation()->create([
                        'feature_name' => $featureData['feature_name'],
                        'description' => $featureData['description'] ?? null,
                        'feature_code' => $featureData['feature_code'] ?? null,
                        'category' => $featureData['category'] ?? 'comfort',
                        'availability' => $featureData['availability'] ?? 'standard',
                        'importance' => $featureData['importance'] ?? 'important',
                        'price' => $featureData['price'] ?? 0,
                        'is_included' => isset($featureData['is_included']) ? true : false,
                        'is_active' => isset($featureData['is_active']) ? true : false,
                        'is_featured' => isset($featureData['is_featured']) ? true : false,
                        'is_popular' => isset($featureData['is_popular']) ? true : false,
                        'is_recommended' => isset($featureData['is_recommended']) ? true : false,
                        'sort_order' => $featureData['sort_order'] ?? 0,
                    ]);
                }
            }
        }

        // Check if this is an AJAX request
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect' => route('admin.carvariants.index')
            ]);
        }

        return redirect()->route('admin.carvariants.index')->with('success', $successMessage);
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
        // Load all relationships for the edit form
        $carvariant->load([
            'carModel.carBrand',
            'colors' => function($query) {
                $query->orderBy('sort_order')->orderBy('color_name');
            },
            'specifications' => function($query) {
                $query->orderBy('category')->orderBy('sort_order')->orderBy('spec_name');
            },
            'featuresRelation' => function($query) {
                $query->orderBy('category')->orderBy('sort_order')->orderBy('feature_name');
            },
            'images' => function($query) {
                $query->orderBy('is_main', 'desc')->orderBy('sort_order')->orderBy('created_at');
            }
        ]);

        $carModels = CarModel::with('carBrand')->orderBy('name')->get();
        
        return view('admin.carvariants.edit', compact('carvariant', 'carModels'));
    }

    public function update(Request $request, CarVariant $carvariant)
    {
        // Validate the request with Vietnamese messages (same as store method)
        $validated = $request->validate([
            'car_model_id' => 'required|exists:car_models,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255|unique:car_variants,sku,' . $carvariant->id,
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'current_price' => 'required|numeric|min:0',
            'is_on_sale' => 'boolean',
            'is_active' => 'boolean',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_bestseller' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'color_inventory' => 'nullable|array',
            'color_inventory.*.quantity' => 'nullable|integer|min:0',
            'color_inventory.*.reserved' => 'nullable|integer|min:0',
            'color_inventory.*.available' => 'nullable|integer|min:0',
        ], [
            // Basic CarVariant validation messages
            'car_model_id.required' => 'Vui lòng chọn dòng xe.',
            'car_model_id.exists' => 'Dòng xe được chọn không tồn tại.',
            'name.required' => 'Vui lòng nhập tên phiên bản xe.',
            'name.max' => 'Tên phiên bản không được vượt quá 255 ký tự.',
            'sku.unique' => 'Mã SKU này đã được sử dụng. Vui lòng nhập mã khác.',
            'sku.max' => 'Mã SKU không được vượt quá 255 ký tự.',
            'base_price.required' => 'Vui lòng nhập giá gốc.',
            'base_price.numeric' => 'Giá gốc phải là số.',
            'base_price.min' => 'Giá gốc không được âm.',
            'current_price.required' => 'Vui lòng nhập giá hiện tại.',
            'current_price.numeric' => 'Giá hiện tại phải là số.',
            'current_price.min' => 'Giá hiện tại không được âm.',
            'sort_order.integer' => 'Thứ tự sắp xếp phải là số nguyên.',
            'sort_order.min' => 'Thứ tự sắp xếp không được âm.',
            'meta_title.max' => 'Meta title không được vượt quá 255 ký tự.',
            // Color inventory validation messages
            'color_inventory.*.quantity.integer' => 'Số lượng phải là số nguyên.',
            'color_inventory.*.quantity.min' => 'Số lượng không được âm.',
            'color_inventory.*.reserved.integer' => 'Số lượng đặt trước phải là số nguyên.',
            'color_inventory.*.reserved.min' => 'Số lượng đặt trước không được âm.',
            'color_inventory.*.available.integer' => 'Số lượng còn lại phải là số nguyên.',
            'color_inventory.*.available.min' => 'Số lượng còn lại không được âm.',
        ]);

        // Prepare update data
        $updateData = [
            'car_model_id' => $validated['car_model_id'],
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'description' => $validated['description'],
            'short_description' => $validated['short_description'],
            'base_price' => $validated['base_price'],
            'current_price' => $validated['current_price'],
            'is_on_sale' => $request->boolean('is_on_sale'),
            'is_active' => $request->boolean('is_active'),
            'is_available' => $request->boolean('is_available'),
            'is_featured' => $request->boolean('is_featured'),
            'is_new_arrival' => $request->boolean('is_new_arrival'),
            'is_bestseller' => $request->boolean('is_bestseller'),
            'sort_order' => $validated['sort_order'] ?? 0,
            'meta_title' => $validated['meta_title'],
            'meta_description' => $validated['meta_description'],
            'keywords' => $validated['keywords'],
        ];

        // Process color inventory data
        if ($request->has('color_inventory')) {
            $colorInventory = [];
            foreach ($request->input('color_inventory', []) as $colorId => $inventory) {
                $quantity = (int) ($inventory['quantity'] ?? 0);
                $reserved = (int) ($inventory['reserved'] ?? 0);
                $available = max(0, $quantity - $reserved); // Auto-calculate available
                
                $colorInventory[$colorId] = [
                    'quantity' => $quantity,
                    'reserved' => $reserved,
                    'available' => $available,
                ];
            }
            $updateData['color_inventory'] = $colorInventory;
        }
        
        // Update slug if name changed
        if ($validated['name'] !== $carvariant->name) {
            $updateData['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        }
        
        $carvariant->update($updateData);

        $successMessage = ' Đã cập nhật phiên bản xe thành công!';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect' => route('admin.carvariants.index')
            ]);
        }

        return redirect()->route('admin.carvariants.index')->with('success', $successMessage);
    }

    public function destroy(Request $request, CarVariant $carvariant)
    {
        // Detailed dependency analysis
        $colorsCount = $carvariant->colors()->count();
        $imagesCount = $carvariant->images()->count();
        
        // Check for orders (critical business data) - via order_items polymorphic
        $ordersCount = 0;
        $pendingOrdersCount = 0;
        $completedOrdersCount = 0;
        
        try {
            if (Schema::hasTable('order_items') && Schema::hasTable('orders')) {
                // Count orders that contain this car variant
                $ordersCount = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.item_type', 'car_variant')
                    ->where('order_items.item_id', $carvariant->id)
                    ->whereNull('orders.deleted_at') // Exclude soft deleted orders
                    ->count();
                    
                // Check order statuses
                $pendingOrdersCount = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.item_type', 'car_variant')
                    ->where('order_items.item_id', $carvariant->id)
                    ->whereIn('orders.status', ['pending', 'confirmed', 'shipping'])
                    ->whereNull('orders.deleted_at')
                    ->count();
                        
                $completedOrdersCount = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.item_type', 'car_variant')
                    ->where('order_items.item_id', $carvariant->id)
                    ->whereIn('orders.status', ['delivered'])
                    ->whereNull('orders.deleted_at')
                    ->count();
            }
        } catch (\Exception $e) {
            // If tables don't exist or have different structure, skip check
            $ordersCount = 0;
        }

        // Check for service appointments (critical business data)
        $serviceAppointmentsCount = 0;
        $activeServiceCount = 0;
        try {
            if (Schema::hasTable('service_appointments')) {
                $serviceAppointmentsCount = DB::table('service_appointments')
                    ->where('car_variant_id', $carvariant->id)
                    ->count();
                    
                $activeServiceCount = DB::table('service_appointments')
                    ->where('car_variant_id', $carvariant->id)
                    ->whereIn('status', ['scheduled', 'confirmed', 'in_progress'])
                    ->count();
            }
        } catch (\Exception $e) {
            $serviceAppointmentsCount = 0;
        }

        // Check for test drives (if exists)
        $testDrivesCount = 0;
        try {
            if (Schema::hasTable('test_drives') && Schema::hasColumn('test_drives', 'car_variant_id')) {
                $testDrivesCount = DB::table('test_drives')
                    ->where('car_variant_id', $carvariant->id)
                    ->count();
            }
        } catch (\Exception $e) {
            $testDrivesCount = 0;
        }

        // Check for reviews (polymorphic relationship)
        $reviewsCount = 0;
        try {
            if (Schema::hasTable('reviews')) {
                $reviewsCount = DB::table('reviews')
                    ->where('reviewable_type', 'App\\Models\\CarVariant')
                    ->where('reviewable_id', $carvariant->id)
                    ->whereNull('deleted_at') // Exclude soft deleted reviews
                    ->count();
            }
        } catch (\Exception $e) {
            $reviewsCount = 0;
        }

        // Check if variant is currently active (first priority)
        if ($carvariant->is_active) {
            $message = "Không thể xóa phiên bản xe \"{$carvariant->name}\" vì đang ở trạng thái hoạt động. Vui lòng tạm dừng phiên bản trước khi xóa.";
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'action_suggestion' => 'deactivate',
                    'show_deactivate_button' => true
                ], 400);
            }
            return redirect()->route('admin.carvariants.index')->with('error', $message);
        }

        // CRITICAL: Never delete if has business transaction data (second priority)
        if ($ordersCount > 0 || $serviceAppointmentsCount > 0 || $testDrivesCount > 0 || $reviewsCount > 0) {
            $businessData = [];
            if ($ordersCount > 0) {
                $businessData[] = "{$ordersCount} đơn hàng";
                if ($pendingOrdersCount > 0) {
                    $businessData[] = "({$pendingOrdersCount} đang xử lý)";
                }
                if ($completedOrdersCount > 0) {
                    $businessData[] = "({$completedOrdersCount} đã giao)";
                }
            }
            if ($serviceAppointmentsCount > 0) {
                $businessData[] = "{$serviceAppointmentsCount} lịch bảo dưỡng";
                if ($activeServiceCount > 0) {
                    $businessData[] = "({$activeServiceCount} đang hoạt động)";
                }
            }
            if ($testDrivesCount > 0) {
                $businessData[] = "{$testDrivesCount} lịch lái thử";
            }
            if ($reviewsCount > 0) {
                $businessData[] = "{$reviewsCount} đánh giá";
            }
            
            $message = "KHÔNG THỂ XÓA phiên bản xe \"{$carvariant->name}\" vì có " . implode(', ', $businessData) . ". " .
                      "Đây là dữ liệu giao dịch quan trọng! Bạn chỉ có thể 'Tạm dừng' để ngừng bán nhưng vẫn giữ lịch sử.";
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'action_suggestion' => 'deactivate',
                    'show_deactivate_button' => true,
                    'business_data' => [
                        'orders_count' => $ordersCount,
                        'pending_orders' => $pendingOrdersCount,
                        'completed_orders' => $completedOrdersCount,
                        'service_appointments_count' => $serviceAppointmentsCount,
                        'active_service_count' => $activeServiceCount,
                        'test_drives_count' => $testDrivesCount,
                        'reviews_count' => $reviewsCount
                    ]
                ], 422); // Unprocessable Entity
            }
            return redirect()->route('admin.carvariants.index')->with('error', $message);
        }

        // Only allow deletion of "clean" variants with no business impact
        // Warn about non-critical data that will be deleted
        $warnings = [];
        if ($colorsCount > 0) {
            $warnings[] = "{$colorsCount} màu sắc";
        }
        if ($imagesCount > 0) {
            $warnings[] = "{$imagesCount} hình ảnh";
        }

        // Check if user has confirmed the deletion
        $confirmed = $request->input('confirmed', false);
        
        if (!empty($warnings) && !$confirmed) {
            $warningMessage = "Bạn có chắc muốn xóa phiên bản xe \"{$carvariant->name}\"? Hành động này sẽ xóa vĩnh viễn " . implode(', ', $warnings) . " liên quan.";
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $warningMessage,
                    'requires_confirmation' => true,
                    'details' => [
                        'colors_count' => $colorsCount,
                        'images_count' => $imagesCount
                    ]
                ], 400);
            }
            return redirect()->route('admin.carvariants.index')
                           ->with('warning', $warningMessage)
                           ->with('confirm_delete', $carvariant->id);
        }

        // Safe to delete - no critical dependencies
        $this->performVariantDeletion($carvariant);
        
        $successMessage = "Đã xóa phiên bản xe \"{$carvariant->name}\" thành công!";
        
        if (request()->wantsJson() || request()->ajax()) {
            // Calculate updated stats
            $totalVariants = CarVariant::count();
            $activeVariants = CarVariant::where('is_active', true)->count();
            $inactiveVariants = CarVariant::where('is_active', false)->count();
            $featuredVariants = CarVariant::where('is_featured', true)->count();
            $onSaleVariants = CarVariant::where('is_on_sale', true)->count();
            $newArrivalVariants = CarVariant::where('is_new_arrival', true)->count();
            $bestsellerVariants = CarVariant::where('is_bestseller', true)->count();
            
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
                    'bestsellerVariants' => $bestsellerVariants,
                ]
            ]);
        }
        
        return redirect()->route('admin.carvariants.index')->with('success', $successMessage);
    }

    /**
     * Perform the actual deletion of variant and related NON-CRITICAL data only
     * This method should only be called after ensuring no business transaction data exists
     */
    private function performVariantDeletion(CarVariant $carvariant)
    {
        // Only delete non-critical data - business data should never reach this point
        
        // 1. Delete colors (HasMany relationship - non-critical)
        $carvariant->colors()->delete();
        
        // 2. Delete images and their physical files (non-critical)
        foreach ($carvariant->images as $image) {
            // Delete physical file if exists
            if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }
        
        // 3. Delete the variant itself
        $carvariant->delete();
        
        // NOTE: We do NOT delete business transaction data here:
        // - Orders (should never be deleted)
        // - Test drives (important customer interaction history)  
        // - Reviews (valuable customer feedback)
        // - Service records (maintenance history)
        // These should be preserved for business continuity and reporting
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
        $bestsellerVariants = CarVariant::where('is_bestseller', true)->count();
        
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
                'bestsellerVariants' => $bestsellerVariants,
            ]
        ]);
    }

    // Color Management Methods
    public function addColor(Request $request, CarVariant $carvariant)
    {
        $validated = $request->validate([
            'color_name' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:50',
            'hex_code' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'rgb_code' => 'nullable|string|max:50',
            'color_type' => 'required|in:solid,metallic,pearlescent,matte,special',
            'availability' => 'required|in:standard,optional,limited,discontinued',
            'price_adjustment' => 'nullable|numeric',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'inventory_quantity' => 'nullable|integer|min:0',
            'inventory_reserved' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_free' => 'boolean',
            'is_popular' => 'boolean',
        ], [
            'color_name.required' => 'Tên màu là bắt buộc.',
            'color_name.max' => 'Tên màu không được vượt quá 255 ký tự.',
            'hex_code.regex' => 'Mã hex không hợp lệ. Vui lòng nhập theo định dạng #FFFFFF.',
            'color_type.required' => 'Loại màu là bắt buộc.',
            'color_type.in' => 'Loại màu không hợp lệ.',
            'availability.required' => 'Tình trạng màu là bắt buộc.',
            'availability.in' => 'Tình trạng màu không hợp lệ.',
            'price_adjustment.numeric' => 'Phụ phí phải là số.',
            'inventory_quantity.integer' => 'Số lượng phải là số nguyên.',
            'inventory_quantity.min' => 'Số lượng không được âm.',
            'inventory_reserved.integer' => 'Số lượng đã đặt phải là số nguyên.',
            'inventory_reserved.min' => 'Số lượng đã đặt không được âm.',
        ]);

        try {
            // Create new color
            $colorData = [
                'car_variant_id' => $carvariant->id,
                'color_name' => $validated['color_name'],
                'color_code' => $validated['color_code'],
                'hex_code' => $validated['hex_code'],
                'rgb_code' => $validated['rgb_code'] ?? null,
                'color_type' => $validated['color_type'] ?? 'solid',
                'availability' => $validated['availability'] ?? 'standard',
                'price_adjustment' => $validated['price_adjustment'] ?? 0,
                'description' => $validated['description'] ?? null,
                'is_active' => $request->boolean('is_active', true),
                'is_free' => $request->boolean('is_free', true),
                'is_popular' => $request->boolean('is_popular', false),
                'sort_order' => $validated['sort_order'] ?? 0,
            ];

            $color = \App\Models\CarVariantColor::create($colorData);

            // Update color inventory
            $quantity = (int) ($validated['inventory_quantity'] ?? 0);
            $reserved = (int) ($validated['inventory_reserved'] ?? 0);
            $available = max(0, $quantity - $reserved);

            $currentInventory = $carvariant->color_inventory ?? [];
            $currentInventory[$color->id] = [
                'quantity' => $quantity,
                'reserved' => $reserved,
                'available' => $available,
            ];

            $carvariant->update(['color_inventory' => $currentInventory]);

            // Load the created color with fresh data
            $color->load(['variant']);

            return response()->json([
                'success' => true,
                'message' => "Đã thêm màu \"{$validated['color_name']}\" thành công!",
                'color' => [
                    'id' => $color->id,
                    'color_name' => $color->color_name,
                    'color_code' => $color->color_code,
                    'hex_code' => $color->hex_code,
                    'rgb_code' => $color->rgb_code,
                    'color_type' => $color->color_type,
                    'availability' => $color->availability,
                    'price_adjustment' => $color->price_adjustment,
                    'description' => $color->description,
                    'is_active' => $color->is_active,
                    'is_free' => $color->is_free,
                    'is_popular' => $color->is_popular,
                    'sort_order' => $color->sort_order,
                ],
                'inventory' => [
                    'quantity' => $quantity,
                    'reserved' => $reserved,
                    'available' => $available,
                ]
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            // Handle duplicate entry error
            if ($e->errorInfo[1] == 1062) {
                return response()->json([
                    'success' => false,
                    'message' => "Màu \"{$validated['color_name']}\" đã tồn tại trong phiên bản này. Vui lòng chọn tên khác."
                ], 422);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm màu: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm màu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateColor(Request $request, CarVariant $carvariant, $colorId)
    {
        $validated = $request->validate([
            'color_name' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:50',
            'hex_code' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'rgb_code' => 'nullable|string|max:50',
            'color_type' => 'required|in:solid,metallic,pearlescent,matte,special',
            'availability' => 'required|in:standard,optional,limited,discontinued',
            'price_adjustment' => 'nullable|numeric',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'inventory_quantity' => 'nullable|integer|min:0',
            'inventory_reserved' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_free' => 'boolean',
            'is_popular' => 'boolean',
        ], [
            'color_name.required' => 'Tên màu là bắt buộc.',
            'hex_code.regex' => 'Mã hex không hợp lệ. Vui lòng nhập theo định dạng #FFFFFF.',
            'sort_order.min' => 'Thứ tự sắp xếp không được âm.',
        ]);

        try {
            $color = \App\Models\CarVariantColor::where('id', $colorId)
                ->where('car_variant_id', $carvariant->id)
                ->firstOrFail();

            // Update color data
            $color->update([
                'color_name' => $validated['color_name'],
                'color_code' => $validated['color_code'],
                'hex_code' => $validated['hex_code'],
                'rgb_code' => $validated['rgb_code'] ?? null,
                'color_type' => $validated['color_type'],
                'availability' => $validated['availability'],
                'price_adjustment' => $validated['price_adjustment'] ?? 0,
                'description' => $validated['description'],
                'is_active' => $request->boolean('is_active', true),
                'is_free' => $request->boolean('is_free', true),
                'is_popular' => $request->boolean('is_popular', false),
                'sort_order' => $validated['sort_order'] ?? 0,
            ]);

            // Update color inventory
            $quantity = (int) ($validated['inventory_quantity'] ?? 0);
            $reserved = (int) ($validated['inventory_reserved'] ?? 0);
            $available = max(0, $quantity - $reserved);

            $currentInventory = $carvariant->color_inventory ?? [];
            $currentInventory[$colorId] = [
                'quantity' => $quantity,
                'reserved' => $reserved,
                'available' => $available,
            ];

            $carvariant->update(['color_inventory' => $currentInventory]);

            return response()->json([
                'success' => true,
                'message' => "Đã cập nhật màu \"{$validated['color_name']}\" thành công!",
                'color' => [
                    'id' => $color->id,
                    'color_name' => $color->color_name,
                    'color_code' => $color->color_code,
                    'hex_code' => $color->hex_code,
                    'rgb_code' => $color->rgb_code,
                    'color_type' => $color->color_type,
                    'availability' => $color->availability,
                    'price_adjustment' => $color->price_adjustment,
                    'description' => $color->description,
                    'is_active' => $color->is_active,
                    'is_free' => $color->is_free,
                    'is_popular' => $color->is_popular,
                    'sort_order' => $color->sort_order,
                ],
                'inventory' => [
                    'quantity' => $quantity,
                    'reserved' => $reserved,
                    'available' => $available,
                ]
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy màu cần cập nhật.'
            ], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle duplicate entry error
            if ($e->errorInfo[1] == 1062) {
                return response()->json([
                    'success' => false,
                    'message' => "Màu \"{$validated['color_name']}\" đã tồn tại trong phiên bản này. Vui lòng chọn tên khác."
                ], 422);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật màu: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật màu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addSpecification(Request $request, CarVariant $carvariant)
    {
        $validated = $request->validate([
            'spec_name' => 'required|string|max:255',
            'spec_value' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
            'category' => 'required|string|max:100',
            'spec_code' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_important' => 'boolean',
            'is_highlighted' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        try {
            $specification = $carvariant->specifications()->create([
                'spec_name' => $validated['spec_name'],
                'spec_value' => $validated['spec_value'],
                'unit' => $validated['unit'],
                'category' => $validated['category'],
                'spec_code' => $validated['spec_code'],
                'description' => $validated['description'],
                'is_important' => $validated['is_important'] ?? false,
                'is_highlighted' => $validated['is_highlighted'] ?? false,
                'sort_order' => $validated['sort_order'] ?? 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => " Đã thêm thông số \"{$validated['spec_name']}\" thành công!",
                'specification' => $specification
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            // Handle duplicate entry error
            if ($e->errorInfo[1] == 1062) {
                return response()->json([
                    'success' => false,
                    'message' => "Thông số \"{$validated['spec_name']}\" đã tồn tại trong phiên bản này. Vui lòng chọn tên khác."
                ], 422);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm thông số: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm thông số: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateSpecification(Request $request, CarVariant $carvariant, $specId)
    {
        $validated = $request->validate([
            'spec_name' => 'required|string|max:255',
            'spec_value' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
            'category' => 'required|string|max:100',
            'spec_code' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_important' => 'boolean',
            'is_highlighted' => 'boolean',
            'sort_order' => 'integer|min:0'
        ], [
            'spec_name.required' => 'Vui lòng nhập tên thông số.',
            'spec_name.max' => 'Tên thông số không được vượt quá 255 ký tự.',
            'spec_value.required' => 'Vui lòng nhập giá trị thông số.',
            'spec_value.max' => 'Giá trị thông số không được vượt quá 255 ký tự.',
            'unit.max' => 'Đơn vị không được vượt quá 50 ký tự.',
            'category.required' => 'Vui lòng chọn danh mục thông số.',
            'category.max' => 'Danh mục không được vượt quá 100 ký tự.',
            'spec_code.max' => 'Mã thông số không được vượt quá 100 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
            'sort_order.integer' => 'Thứ tự sắp xếp phải là số nguyên.',
            'sort_order.min' => 'Thứ tự sắp xếp không được âm.',
        ]);

        try {
            $specification = $carvariant->specifications()->findOrFail($specId);
            
            $specification->update([
                'spec_name' => $validated['spec_name'],
                'spec_value' => $validated['spec_value'],
                'unit' => $validated['unit'],
                'category' => $validated['category'],
                'spec_code' => $validated['spec_code'],
                'description' => $validated['description'],
                'is_important' => $validated['is_important'] ?? false,
                'is_highlighted' => $validated['is_highlighted'] ?? false,
                'sort_order' => $validated['sort_order'] ?? 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => " Đã cập nhật thông số \"{$validated['spec_name']}\" thành công!",
                'specification' => $specification->fresh()
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông số cần cập nhật.'
            ], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle duplicate entry error
            if ($e->errorInfo[1] == 1062) {
                return response()->json([
                    'success' => false,
                    'message' => "Thông số \"{$validated['spec_name']}\" đã tồn tại trong phiên bản này. Vui lòng chọn tên khác."
                ], 422);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật thông số: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật thông số: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteSpecification(Request $request, CarVariant $carvariant, $specId)
    {
        try {
            $specification = $carvariant->specifications()->findOrFail($specId);
            $specName = $specification->spec_name;
            
            $specification->delete();

            return response()->json([
                'success' => true,
                'message' => " Đã xóa thông số \"{$specName}\" thành công!"
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông số cần xóa.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa thông số: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addFeature(Request $request, CarVariant $carvariant)
    {
        $validated = $request->validate([
            'feature_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'feature_code' => 'nullable|string|max:100',
            'category' => 'required|in:safety,comfort,technology,performance,exterior,interior,entertainment,convenience,wheels,audio,navigation',
            'availability' => 'required|in:standard,optional',
            'importance' => 'required|in:essential,important,nice_to_have,luxury',
            'price' => 'numeric|min:0',
            'is_included' => 'boolean',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_popular' => 'boolean',
            'is_recommended' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        try {
            $feature = $carvariant->featuresRelation()->create([
                'feature_name' => $validated['feature_name'],
                'description' => $validated['description'] ?? null,
                'feature_code' => $validated['feature_code'] ?? null,
                'category' => $validated['category'],
                'availability' => $validated['availability'],
                'importance' => $validated['importance'],
                'price' => $validated['price'] ?? 0,
                'is_included' => $validated['is_included'] ?? true,
                'is_active' => $validated['is_active'] ?? true,
                'is_featured' => $validated['is_featured'] ?? false,
                'is_popular' => $validated['is_popular'] ?? false,
                'is_recommended' => $validated['is_recommended'] ?? false,
                'sort_order' => $validated['sort_order'] ?? 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Đã thêm tính năng \"{$validated['feature_name']}\" thành công!",
                'feature' => $feature
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            // Handle duplicate entry error
            if ($e->errorInfo[1] == 1062) {
                return response()->json([
                    'success' => false,
                    'message' => "Tính năng \"{$validated['feature_name']}\" đã tồn tại trong phiên bản này. Vui lòng chọn tên khác."
                ], 422);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm tính năng: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm tính năng: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateFeature(Request $request, CarVariant $carvariant, $featureId)
    {
        $validated = $request->validate([
            'feature_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'feature_code' => 'nullable|string|max:100',
            'category' => 'required|in:safety,comfort,technology,performance,exterior,interior,entertainment,convenience,wheels,audio,navigation',
            'availability' => 'required|in:standard,optional',
            'importance' => 'required|in:essential,important,nice_to_have,luxury',
            'price' => 'numeric|min:0',
            'is_included' => 'boolean',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_popular' => 'boolean',
            'is_recommended' => 'boolean',
            'sort_order' => 'integer|min:0'
        ], [
            'feature_name.required' => 'Vui lòng nhập tên tính năng.',
            'feature_name.max' => 'Tên tính năng không được vượt quá 255 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
            'feature_code.max' => 'Mã tính năng không được vượt quá 100 ký tự.',
            'category.required' => 'Vui lòng chọn danh mục tính năng.',
            'category.in' => 'Danh mục tính năng không hợp lệ.',
            'availability.required' => 'Vui lòng chọn tình trạng tính năng.',
            'availability.in' => 'Tình trạng tính năng không hợp lệ.',
            'importance.required' => 'Vui lòng chọn mức độ quan trọng.',
            'importance.in' => 'Mức độ quan trọng không hợp lệ.',
            'price.numeric' => 'Giá tính năng phải là số.',
            'price.min' => 'Giá tính năng không được âm.',
            'sort_order.integer' => 'Thứ tự sắp xếp phải là số nguyên.',
            'sort_order.min' => 'Thứ tự sắp xếp không được âm.',
        ]);

        try {
            $feature = $carvariant->featuresRelation()->findOrFail($featureId);
            
            $feature->update([
                'feature_name' => $validated['feature_name'],
                'description' => $validated['description'] ?? null,
                'feature_code' => $validated['feature_code'] ?? null,
                'category' => $validated['category'],
                'availability' => $validated['availability'],
                'importance' => $validated['importance'],
                'price' => $validated['price'] ?? 0,
                'is_included' => $validated['is_included'] ?? true,
                'is_active' => $validated['is_active'] ?? true,
                'is_featured' => $validated['is_featured'] ?? false,
                'is_popular' => $validated['is_popular'] ?? false,
                'is_recommended' => $validated['is_recommended'] ?? false,
                'sort_order' => $validated['sort_order'] ?? 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Đã cập nhật tính năng \"{$validated['feature_name']}\" thành công!",
                'feature' => $feature->fresh()
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy tính năng cần cập nhật.'
            ], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle duplicate entry error
            if ($e->errorInfo[1] == 1062) {
                return response()->json([
                    'success' => false,
                    'message' => "Tính năng \"{$validated['feature_name']}\" đã tồn tại trong phiên bản này. Vui lòng chọn tên khác."
                ], 422);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật tính năng: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật tính năng: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteFeature(Request $request, CarVariant $carvariant, $featureId)
    {
        try {
            $feature = $carvariant->featuresRelation()->findOrFail($featureId);
            $featureName = $feature->feature_name;
            
            $feature->delete();

            return response()->json([
                'success' => true,
                'message' => "Đã xóa tính năng \"{$featureName}\" thành công!"
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy tính năng cần xóa.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa tính năng: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteColor(Request $request, CarVariant $carvariant, $colorId)
    {
        try {
            $color = \App\Models\CarVariantColor::where('id', $colorId)
                ->where('car_variant_id', $carvariant->id)
                ->firstOrFail();

            $colorName = $color->color_name;

            // Remove from color inventory
            $currentInventory = $carvariant->color_inventory ?? [];
            unset($currentInventory[$colorId]);
            $carvariant->update(['color_inventory' => $currentInventory]);

            // Delete the color
            $color->delete();

            // Refresh the relationship to ensure accurate count
            $carvariant->load('colors');

            return response()->json([
                'success' => true,
                'message' => "Đã xóa màu \"{$colorName}\" thành công!"
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy màu cần xóa.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa màu: ' . $e->getMessage()
            ], 500);
        }
    }

    // ===== IMAGE MANAGEMENT METHODS =====
    
    /**
     * Upload multiple images for car variant
     */
    public function uploadImages(Request $request, CarVariant $carvariant)
    {
        try {
            $request->validate([
                'images' => 'required|array|min:1',
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
                // Individual settings validation (now required)
                'individual_titles' => 'required|array',
                'individual_titles.*' => 'nullable|string|max:255',
                'individual_alt_texts' => 'required|array',
                'individual_alt_texts.*' => 'nullable|string|max:255',
                'individual_angles' => 'required|array',
                'individual_angles.*' => 'nullable|string',
                'individual_sort_orders' => 'required|array',
                'individual_sort_orders.*' => 'nullable|integer|min:0',
                'individual_descriptions' => 'required|array',
                'individual_descriptions.*' => 'nullable|string|max:1000',
                'individual_is_main' => 'required|array',
                'individual_is_main.*' => 'nullable|in:0,1',
                'individual_is_active' => 'required|array',
                'individual_is_active.*' => 'nullable|in:0,1',
                'individual_image_types' => 'required|array',
                'individual_image_types.*' => 'required|in:gallery,exterior,interior',
                'individual_color_ids' => 'required|array',
                'individual_color_ids.*' => 'nullable|exists:car_variant_colors,id',
            ], [
                'images.required' => 'Vui lòng chọn ít nhất 1 hình ảnh',
                'images.*.image' => 'Tệp tải lên phải là hình ảnh',
                'images.*.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
                'images.*.max' => 'Kích thước hình ảnh không được vượt quá 10MB',
                'individual_titles.required' => 'Thiếu thông tin tiêu đề cho các ảnh',
                'individual_alt_texts.required' => 'Thiếu thông tin alt text cho các ảnh',
                'individual_image_types.required' => 'Thiếu thông tin loại hình ảnh',
                'individual_image_types.*.in' => 'Loại hình ảnh phải là: gallery, exterior hoặc interior',
                'individual_color_ids.*.exists' => 'Màu được chọn không tồn tại',
            ]);

            // Always use individual settings (no common settings anymore)
            $individualMainFlags = $request->input('individual_is_main', []);
            $hasMainImage = in_array('1', $individualMainFlags);
            
            if ($hasMainImage) {
                $carvariant->images()->update(['is_main' => false]);
            }

            $uploadedImages = [];
            $images = $request->file('images');

            foreach ($images as $index => $image) {
                // Generate unique filename
                $filename = time() . '_' . $index . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                
                // Store image
                $path = $image->storeAs('car_variant_images', $filename, 'public');
                
                // Always use individual settings
                $individualTitles = $request->input('individual_titles', []);
                $individualAltTexts = $request->input('individual_alt_texts', []);
                $individualAngles = $request->input('individual_angles', []);
                $individualSortOrders = $request->input('individual_sort_orders', []);
                $individualDescriptions = $request->input('individual_descriptions', []);
                $individualMainFlags = $request->input('individual_is_main', []);
                $individualActiveFlags = $request->input('individual_is_active', []);
                $individualImageTypes = $request->input('individual_image_types', []);
                $individualColorIds = $request->input('individual_color_ids', []);
                
                $title = $individualTitles[$index] ?? pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $altText = $individualAltTexts[$index] ?? ($title . ' - ' . $carvariant->name);
                $angle = $individualAngles[$index] ?? null;
                $sortOrder = $individualSortOrders[$index] ?? $index;
                $description = $individualDescriptions[$index] ?? null;
                $isMain = ($individualMainFlags[$index] ?? '0') === '1';
                $isActive = ($individualActiveFlags[$index] ?? '1') === '1';
                $imageType = $individualImageTypes[$index] ?? 'gallery';
                $colorId = $individualColorIds[$index] ?? null;

                // Create image record
                $imageRecord = $carvariant->images()->create([
                    'image_url' => $path,
                    'alt_text' => $altText,
                    'title' => $title,
                    'image_type' => $imageType,
                    'angle' => $angle,
                    'sort_order' => $sortOrder,
                    'car_variant_color_id' => $colorId,
                    'description' => $description,
                    'is_main' => $isMain,
                    'is_active' => $isActive,
                ]);

                $uploadedImages[] = $imageRecord;
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã upload ' . count($uploadedImages) . ' hình ảnh thành công!',
                'images' => $uploadedImages
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi upload hình ảnh: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update image information
     */
    public function updateImage(Request $request, CarVariant $carvariant, $imageId)
    {
        try {
            $request->validate([
                'title' => 'nullable|string|max:255',
                'alt_text' => 'required|string|max:255',
                'image_type' => 'required|in:gallery,exterior,interior',
                'angle' => 'nullable|string',
                'sort_order' => 'nullable|integer|min:0',
                'car_variant_color_id' => 'nullable|exists:car_variant_colors,id',
                'description' => 'nullable|string|max:1000',
                'is_main' => 'nullable|boolean',
                'is_active' => 'nullable|boolean',
                'replace_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
            ], [
                'title.max' => 'Tiêu đề hình ảnh không được vượt quá 255 ký tự.',
                'alt_text.required' => 'Vui lòng nhập alt text cho hình ảnh.',
                'alt_text.max' => 'Alt text không được vượt quá 255 ký tự.',
                'image_type.required' => 'Vui lòng chọn loại hình ảnh.',
                'image_type.in' => 'Loại hình ảnh không hợp lệ.',
                'sort_order.integer' => 'Thứ tự sắp xếp phải là số nguyên.',
                'sort_order.min' => 'Thứ tự sắp xếp không được âm.',
                'car_variant_color_id.exists' => 'Màu được chọn không tồn tại.',
                'description.max' => 'Mô tả hình ảnh không được vượt quá 1000 ký tự.',
                'replace_image.image' => 'File thay thế phải là hình ảnh.',
                'replace_image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
                'replace_image.max' => 'Kích thước hình ảnh không được vượt quá 10MB.',
            ]);

            $image = $carvariant->images()->findOrFail($imageId);

            // Handle file replacement if provided
            $updateData = [
                'title' => $request->input('title'),
                'alt_text' => $request->input('alt_text'),
                'image_type' => $request->input('image_type'),
                'angle' => $request->input('angle'),
                'sort_order' => $request->input('sort_order', 0),
                'car_variant_color_id' => $request->input('car_variant_color_id'),
                'description' => $request->input('description'),
                'is_main' => $request->boolean('is_main'),
                'is_active' => $request->boolean('is_active', true),
            ];
            
            // If new image file is provided, replace the old one
            if ($request->hasFile('replace_image')) {
                // Delete old image file
                if ($image->image_url && \Illuminate\Support\Facades\Storage::disk('public')->exists($image->image_url)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image_url);
                }
                
                // Store new image
                $file = $request->file('replace_image');
                $filename = time() . '_' . \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('car_variant_images', $filename, 'public');
                
                $updateData['image_url'] = $path;
            }
            
            // If setting as main image, unset all existing main images
            if ($request->boolean('is_main') && !$image->is_main) {
                $carvariant->images()->update(['is_main' => false]);
            }

            $image->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật thông tin hình ảnh thành công!',
                'image' => $image->fresh()
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy hình ảnh cần cập nhật.'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật hình ảnh: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete image
     */
    public function deleteImage(Request $request, CarVariant $carvariant, $imageId)
    {
        try {
            $image = $carvariant->images()->findOrFail($imageId);
            $imageName = $image->title ?: 'Hình ảnh';
            $wasMainImage = $image->is_main;
            
            // Delete file from storage
            if ($image->image_url && Storage::disk('public')->exists($image->image_url)) {
                Storage::disk('public')->delete($image->image_url);
            }
            
            // Delete database record
            $image->delete();
            
            // If deleted image was main, set first remaining image as main
            if ($wasMainImage) {
                $firstRemainingImage = $carvariant->images()->orderBy('sort_order')->first();
                if ($firstRemainingImage) {
                    $firstRemainingImage->update(['is_main' => true]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Đã xóa {$imageName} thành công!"
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy hình ảnh cần xóa.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa hình ảnh: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set image as main
     */
    public function setMainImage(Request $request, CarVariant $carvariant, $imageId)
    {
        try {
            $image = $carvariant->images()->findOrFail($imageId);
            
            // Unset all existing main images for this car variant
            $carvariant->images()->update(['is_main' => false]);
            
            // Set this image as main
            $image->update(['is_main' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Đã đặt làm ảnh chính thành công!',
                'image' => $image->fresh()
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy hình ảnh.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đặt ảnh chính: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Generate unique SKU for CarVariant
     */
    private function generateUniqueSKU($modelId, $variantName)
    {
        $model = CarModel::find($modelId);
        if (!$model) {
            return 'CV-' . time();
        }
        
        // Create base SKU from model and variant name
        $modelSlug = \Illuminate\Support\Str::slug($model->name);
        $variantSlug = \Illuminate\Support\Str::slug($variantName);
        $base = strtoupper($modelSlug . '-' . $variantSlug);
        
        // Check if base SKU is available
        $sku = $base;
        $counter = 1;
        
        while (CarVariant::where('sku', $sku)->exists()) {
            $counter++;
            $sku = $base . '-V' . $counter;
        }
        
        return $sku;
    }
    
    /**
     * Clean up orphaned specifications that might cause constraint violations
     */
    private function cleanupOrphanedSpecifications($carModelId, $variantName)
    {
        // Find soft deleted variants with same name and model
        $softDeletedVariants = CarVariant::withTrashed()
            ->where('car_model_id', $carModelId)
            ->where('name', $variantName)
            ->whereNotNull('deleted_at')
            ->pluck('id');
            
        if ($softDeletedVariants->isNotEmpty()) {
            // Delete orphaned specifications to avoid constraint violations
            \App\Models\CarVariantSpecification::whereIn('car_variant_id', $softDeletedVariants)->delete();
            \App\Models\CarVariantFeature::whereIn('car_variant_id', $softDeletedVariants)->delete();
            \App\Models\CarVariantColor::whereIn('car_variant_id', $softDeletedVariants)->delete();
            \App\Models\CarVariantImage::whereIn('car_variant_id', $softDeletedVariants)->delete();
            
            \Log::info('Cleaned up orphaned records for soft deleted CarVariants: ' . $softDeletedVariants->implode(', '));
        }
    }
}