<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AccessoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Accessory::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('sku', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhere('short_description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

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
                case 'in_stock':
                    $query->where('stock_status', 'in_stock');
                    break;
                case 'out_of_stock':
                    $query->where('stock_status', 'out_of_stock');
                    break;
            }
        }

        // Order by: sort_order first, then featured, then by created_at desc, then by id desc for stable pagination
        $accessories = $query->orderBy('sort_order')
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15);

        // Append query parameters to pagination links
        $accessories->appends($request->except(['page', 'ajax', 'with_stats']));

        // Calculate statistics
        $totalAccessories = Accessory::count();
        $activeAccessories = Accessory::where('is_active', true)->count();
        $inactiveAccessories = Accessory::where('is_active', false)->count();
        $featuredAccessories = Accessory::where('is_featured', true)->count();

        // Get categories for dropdown with Vietnamese translation
        $categoryTranslations = Accessory::getCategoryTranslations();

        $categories = Accessory::select('category')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->get()
            ->map(function ($item) use ($categoryTranslations) {
                return [
                    'value' => $item->category,
                    'label' => $categoryTranslations[$item->category] ?? ucfirst($item->category)
                ];
            })
            ->toArray();

        // Handle AJAX requests
        if ($request->ajax()) {
            return view('admin.accessories.partials.table', compact('accessories'))->render();
        }

        return view('admin.accessories.index', [
            'accessories' => $accessories,
            'totalAccessories' => $totalAccessories,
            'activeAccessories' => $activeAccessories,
            'inactiveAccessories' => $inactiveAccessories,
            'featuredAccessories' => $featuredAccessories,
            'categories' => $categories,
            'search' => $request->search,
        ]);
    }

    public function create()
    {
        return view('admin.accessories.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Basic Info
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:accessories,sku,NULL,id,deleted_at,NULL',
            'category' => 'required|string|in:interior,exterior,electronics,performance,safety,maintenance,car_care,utility',
            'subcategory' => 'nullable|string|max:100',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:100',
            'material' => 'nullable|string|max:100',

            // Pricing
            'base_price' => 'required|numeric|min:0',
            'current_price' => 'required|numeric|min:0',
            'is_on_sale' => 'boolean',
            'sale_start_date' => 'nullable|date',
            'sale_end_date' => 'nullable|date|after_or_equal:sale_start_date',

            // Installation Service
            'installation_service_available' => 'boolean',
            'installation_fee' => 'nullable|numeric|min:0',
            'installation_time_minutes' => 'nullable|integer|min:0',
            'installation_requirements' => 'nullable|string',

            // Inventory
            'stock_quantity' => 'required|integer|min:1',
            'reserved_quantity' => 'nullable|integer|min:0',
            'stock_status' => 'required|in:in_stock,low_stock,out_of_stock,discontinued',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'supplier_name' => 'nullable|string|max:255',
            'supplier_sku' => 'nullable|string|max:100',
            'lead_time_days' => 'nullable|integer|min:0',
            'color_options' => 'nullable|json',
            'warranty_months' => 'nullable|integer|min:0',
            'return_policy_days' => 'nullable|integer|min:0',
            'warranty_terms' => 'nullable|string',

            // Compatibility
            'compatible_car_brands' => 'nullable|json',
            'compatible_car_models' => 'nullable|json',
            'compatible_car_years' => 'nullable|json',
            'compatible_years' => 'nullable|string|max:100',
            'compatibility_notes' => 'nullable|string',

            // Specifications & Features (JSON)
            'specifications' => 'nullable|json',
            'features' => 'nullable|json',

            // Images
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_json' => 'nullable|json',
            'gallery.*' => 'nullable',
            'gallery.*.file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',

            // SEO
            'slug' => 'nullable|string|max:255|unique:accessories,slug,NULL,id,deleted_at,NULL',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',

            // Settings
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_bestseller' => 'boolean',
        ], [
            // Custom Vietnamese validation messages
            'name.required' => 'Vui lòng nhập tên phụ kiện.',
            'name.string' => 'Tên phụ kiện phải là chuỗi ký tự.',
            'name.max' => 'Tên phụ kiện không được vượt quá 255 ký tự.',
            'sku.required' => 'Vui lòng nhập mã SKU.',
            'sku.string' => 'Mã SKU phải là chuỗi ký tự.',
            'sku.max' => 'Mã SKU không được vượt quá 100 ký tự.',
            'sku.unique' => 'Mã SKU này đã tồn tại.',
            'category.required' => 'Vui lòng chọn danh mục.',
            'category.in' => 'Danh mục không hợp lệ.',
            'base_price.required' => 'Vui lòng nhập giá niêm yết.',
            'base_price.numeric' => 'Giá niêm yết phải là số.',
            'base_price.min' => 'Giá niêm yết không được âm.',
            'current_price.required' => 'Vui lòng nhập giá hiện tại.',
            'current_price.numeric' => 'Giá hiện tại phải là số.',
            'current_price.min' => 'Giá hiện tại không được âm.',
            'stock_quantity.required' => 'Vui lòng nhập số lượng tồn kho.',
            'stock_quantity.integer' => 'Số lượng tồn kho phải là số nguyên.',
            'stock_quantity.min' => 'Số lượng tồn kho phải lớn hơn 0.',
            'stock_status.required' => 'Vui lòng chọn trạng thái kho.',
            'stock_status.in' => 'Trạng thái kho không hợp lệ.',
            'image_path.image' => 'Tệp tải lên phải là hình ảnh.',
            'image_path.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'image_path.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'slug.unique' => 'Slug URL này đã tồn tại.',
            'slug.max' => 'Slug URL không được vượt quá 255 ký tự.',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle image upload
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('accessories', 'public');
            $validated['image_path'] = $imagePath;
        }

        // Handle gallery file uploads
        $galleryData = [];
        if ($request->filled('gallery_json')) {
            $galleryData = json_decode($request->gallery_json, true) ?: [];
        }


        // Process gallery file uploads
        // Try to get files using both methods
        $galleryFiles = [];
        
        // Method 1: Direct hasFile check
        if ($request->hasFile('gallery')) {
            $galleryFiles = $request->file('gallery');
        }
        // Method 2: Check nested structure in allFiles
        elseif (!empty($request->allFiles()['gallery'] ?? [])) {
            $galleryFiles = $request->allFiles()['gallery'];
        }
        
        if (!empty($galleryFiles) && is_array($galleryFiles)) {

                // Reset gallery data to avoid duplicates
                $uploadedImages = [];
                $metadataCounter = 0;

                foreach ($galleryFiles as $index => $fileData) {
                // Handle both structures: gallery[1][file] and gallery[1] = file
                $file = null;
                if (is_array($fileData) && isset($fileData['file'])) {
                    $file = $fileData['file'];
                } elseif ($fileData instanceof \Illuminate\Http\UploadedFile) {
                    $file = $fileData;
                }

                if ($file && $file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
                    // Store the uploaded file
                    $filePath = $file->store('accessories/gallery', 'public');
                    
                    // Create full URL (not just asset path)
                    $fileUrl = url('storage/' . $filePath);

                    // Get corresponding metadata by counter (sequential match)
                    $metadata = $galleryData[$metadataCounter] ?? [];
                    $metadataCounter++;

                    $finalData = array_merge($metadata, [
                        'url' => $fileUrl,
                        'file_path' => $filePath,
                        'original_name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                    ]);

                    $uploadedImages[] = $finalData;
                }
            }
            
            // Replace gallery data with uploaded images
            $galleryData = $uploadedImages;
        }

        // Set gallery data for new accessory (no existing gallery to preserve)
        if (!empty($galleryData)) {
            $validated['gallery'] = $galleryData;
        } else {
            $validated['gallery'] = [];
        }
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_on_sale'] = $request->boolean('is_on_sale');
        $validated['is_new_arrival'] = $request->boolean('is_new_arrival');
        $validated['is_bestseller'] = $request->boolean('is_bestseller');
        $validated['installation_service_available'] = $request->boolean('installation_service_available');

        // Handle JSON fields
        if ($request->filled('compatible_car_brands')) {
            $validated['compatible_car_brands'] = json_decode($request->compatible_car_brands, true);
        }
        if ($request->filled('compatible_car_models')) {
            $validated['compatible_car_models'] = json_decode($request->compatible_car_models, true);
        }
        if ($request->filled('specifications')) {
            $validated['specifications'] = json_decode($request->specifications, true);
        }
        if ($request->filled('features')) {
            $validated['features'] = json_decode($request->features, true);
        }
        if ($request->filled('color_options')) {
            $validated['color_options'] = json_decode($request->color_options, true);
        }

        // Check if there's a soft deleted accessory with same SKU
        $existingAccessory = Accessory::onlyTrashed()->where('sku', $validated['sku'])->first();

        $isRestore = false;
        if ($existingAccessory) {
            // Restore and update the existing accessory
            $existingAccessory->restore();
            $existingAccessory->update($validated);
            $isRestore = true;
            $accessoryName = $validated['name'];
        } else {
            // Create new accessory
            $newAccessory = Accessory::create($validated);
            $accessoryName = $newAccessory->name;
        }

        // Determine success message
        $successMessage = $isRestore
            ? "Đã khôi phục phụ kiện '{$accessoryName}' thành công!"
            : "Đã tạo phụ kiện '{$accessoryName}' thành công!";

        // Handle AJAX requests
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect' => route('admin.accessories.index')
            ]);
        }

        return redirect()->route('admin.accessories.index')->with('success', $successMessage);
    }

    public function edit($id)
    {
        $accessory = Accessory::findOrFail($id);
        return view('admin.accessories.edit', compact('accessory'));
    }

    public function update(Request $request, $id)
    {
        $accessory = Accessory::findOrFail($id);

        try {
            $validated = $request->validate([
                // Basic Info
                'name' => 'required|string|max:255',
                'sku' => 'required|string|max:100|unique:accessories,sku,' . $id . ',id,deleted_at,NULL',
                'category' => 'required|string|in:interior,exterior,electronics,performance,safety,maintenance,car_care,utility',
                'subcategory' => 'nullable|string|max:100',
                'short_description' => 'nullable|string|max:500',
                'description' => 'nullable|string',
                'weight' => 'nullable|numeric|min:0',
                'dimensions' => 'nullable|string|max:100',
                'material' => 'nullable|string|max:100',

                // Pricing
                'base_price' => 'required|numeric|min:0',
                'current_price' => 'required|numeric|min:0',
                'is_on_sale' => 'boolean',
                'sale_start_date' => 'nullable|date',
                'sale_end_date' => 'nullable|date|after_or_equal:sale_start_date',

                // Installation Service
                'installation_service_available' => 'boolean',
                'installation_fee' => 'nullable|numeric|min:0',
                'installation_time_minutes' => 'nullable|integer|min:0',
                'installation_requirements' => 'nullable|string',

                // Inventory
                'stock_quantity' => 'required|integer|min:1',
                'reserved_quantity' => 'nullable|integer|min:0',
                'stock_status' => 'required|in:in_stock,low_stock,out_of_stock,discontinued',
                'low_stock_threshold' => 'nullable|integer|min:0',
                'supplier_name' => 'nullable|string|max:255',
                'supplier_sku' => 'nullable|string|max:100',
                'lead_time_days' => 'nullable|integer|min:0',
                'color_options' => 'nullable|json',
                'warranty_months' => 'nullable|integer|min:0',
                'return_policy_days' => 'nullable|integer|min:0',
                'warranty_terms' => 'nullable|string',

                // Compatibility
                'compatible_car_brands' => 'nullable|json',
                'compatible_car_models' => 'nullable|json',
                'compatible_car_years' => 'nullable|json',
                'compatible_years' => 'nullable|string|max:100',
                'compatibility_notes' => 'nullable|string',

                // Specifications & Features (JSON)
                'specifications' => 'nullable|json',
                'features' => 'nullable|json',

                // Images
                'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'gallery_json' => 'nullable|json',
                'gallery.*' => 'nullable',
                'gallery.*.file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',

                // SEO
                'slug' => 'nullable|string|max:255|unique:accessories,slug,' . $id . ',id,deleted_at,NULL',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:255',

                // Settings
                'sort_order' => 'nullable|integer|min:0',
                'is_active' => 'boolean',
                'is_featured' => 'boolean',
                'is_new_arrival' => 'boolean',
                'is_bestseller' => 'boolean',
            ], [
                // Custom Vietnamese validation messages
                'name.required' => 'Vui lòng nhập tên phụ kiện.',
                'name.string' => 'Tên phụ kiện phải là chuỗi ký tự.',
                'name.max' => 'Tên phụ kiện không được vượt quá 255 ký tự.',
                'sku.required' => 'Vui lòng nhập mã SKU.',
                'sku.string' => 'Mã SKU phải là chuỗi ký tự.',
                'sku.max' => 'Mã SKU không được vượt quá 100 ký tự.',
                'sku.unique' => 'Mã SKU này đã tồn tại.',
                'category.required' => 'Vui lòng chọn danh mục.',
                'category.in' => 'Danh mục không hợp lệ.',
                'base_price.required' => 'Vui lòng nhập giá niêm yết.',
                'base_price.numeric' => 'Giá niêm yết phải là số.',
                'base_price.min' => 'Giá niêm yết không được âm.',
                'current_price.required' => 'Vui lòng nhập giá hiện tại.',
                'current_price.numeric' => 'Giá hiện tại phải là số.',
                'current_price.min' => 'Giá hiện tại không được âm.',
                'stock_quantity.required' => 'Vui lòng nhập số lượng tồn kho.',
                'stock_quantity.integer' => 'Số lượng tồn kho phải là số nguyên.',
                'stock_quantity.min' => 'Số lượng tồn kho phải lớn hơn 0.',
                'stock_status.required' => 'Vui lòng chọn trạng thái kho.',
                'stock_status.in' => 'Trạng thái kho không hợp lệ.',
                'image_path.image' => 'Tệp tải lên phải là hình ảnh.',
                'image_path.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
                'image_path.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
                'slug.unique' => 'Slug URL này đã tồn tại.',
                'slug.max' => 'Slug URL không được vượt quá 255 ký tự.',
            ]);

            // IMPORTANT: Remove gallery from validated data 
            // We handle it separately to preserve existing images
            unset($validated['gallery']);
            unset($validated['gallery_json']);

            // Handle image upload
            if ($request->hasFile('image_path')) {
                // Delete old image if exists
                if ($accessory->image_path && Storage::disk('public')->exists($accessory->image_path)) {
                    Storage::disk('public')->delete($accessory->image_path);
                }

                $imagePath = $request->file('image_path')->store('accessories', 'public');
                $validated['image_path'] = $imagePath;
            }

            // Handle gallery file uploads - UPDATE METHOD - FIXED
            $newGalleryItems = [];

            // Get metadata from JSON (ONLY FOR NEW IMAGES)
            $metadataArray = [];
            if ($request->filled('gallery_json')) {
                $metadataArray = json_decode($request->gallery_json, true) ?: [];
            }

            Log::info('=== GALLERY DEBUG START ===', [
                'has_gallery_json' => $request->filled('gallery_json'),
                'metadata_count' => count($metadataArray),
                'has_gallery_files' => $request->hasFile('gallery'),
                'metadata' => $metadataArray
            ]);

            // CRITICAL: Only process if there are ACTUAL FILE UPLOADS
            // This prevents duplicate entries when form is resubmitted without new files
            $galleryFiles = $request->file('gallery') ?? [];
            if (!empty($galleryFiles)) {
                Log::info('Gallery files found', ['file_keys' => array_keys($galleryFiles)]);

                foreach ($galleryFiles as $index => $fileData) {
                    Log::info('Processing gallery index', ['index' => $index, 'data_type' => gettype($fileData)]);

                    // Extract actual file from nested structure
                    $file = null;
                    if (is_array($fileData) && isset($fileData['file'])) {
                        $file = $fileData['file'];
                    } elseif ($fileData instanceof \Illuminate\Http\UploadedFile) {
                        $file = $fileData;
                    }

                    // IMPORTANT: Only process valid, uploaded files
                    // Skip if file is null or invalid (prevents processing existing image metadata)
                    if ($file && $file->isValid()) {
                        Log::info('Valid file found', ['name' => $file->getClientOriginalName()]);

                        // Store the file
                        $filePath = $file->store('accessories/gallery', 'public');
                        $fileUrl = asset('storage/' . $filePath);

                        // Get corresponding metadata - now supports both array and object structure
                        // Check if metadata has the same index key (for object structure)
                        $metadata = [];
                        if (isset($metadataArray[$index])) {
                            $metadata = $metadataArray[$index];
                        } elseif (isset($metadataArray[(string)$index])) {
                            // Try string key for object structure
                            $metadata = $metadataArray[(string)$index];
                        } else {
                            // Fallback: use first available metadata or empty array
                            $metadata = !empty($metadataArray) ? reset($metadataArray) : [];
                        }

                        // Create complete image data
                        $imageData = array_merge($metadata, [
                            'url' => $fileUrl,
                            'file_path' => $filePath,
                            'original_name' => $file->getClientOriginalName(),
                            'size' => $file->getSize(),
                            'mime_type' => $file->getMimeType(),
                            'uploaded_at' => now()->toISOString()
                        ]);

                        $newGalleryItems[] = $imageData;

                        Log::info('File processed successfully', [
                            'index' => $index,
                            'file_path' => $filePath,
                            'file_url' => $fileUrl,
                            'metadata' => $metadata,
                            'final_data' => $imageData
                        ]);
                    } else {
                        Log::info('Skipped invalid or missing file at index', ['index' => $index]);
                    }
                }
            }

            // PRESERVE EXISTING IMAGES: Only add new images, never modify existing ones
            if (!empty($newGalleryItems)) {
                // CRITICAL: Get current gallery from FRESH database query
                // $accessory->gallery may have been modified during validation
                $freshAccessory = Accessory::find($accessory->id);
                $currentGallery = $freshAccessory->gallery ?? [];
                if (!is_array($currentGallery)) {
                    $currentGallery = [];
                }
                
                Log::info('Fresh gallery from database', [
                    'fresh_gallery' => $currentGallery,
                    'fresh_count' => count($currentGallery),
                    'model_gallery' => $accessory->gallery,
                    'model_count' => is_array($accessory->gallery) ? count($accessory->gallery) : 0
                ]);

                Log::info('Current gallery before merge', [
                    'current_gallery' => $currentGallery,
                    'current_count' => count($currentGallery)
                ]);

                // Check if any new image is marked as primary
                $hasNewPrimary = false;
                foreach ($newGalleryItems as $newImage) {
                    if (isset($newImage['is_primary']) && $newImage['is_primary']) {
                        $hasNewPrimary = true;
                        break;
                    }
                }
                
                // If new primary image exists, remove primary flag from existing images
                $updatedGallery = $currentGallery;
                if ($hasNewPrimary) {
                    foreach ($updatedGallery as &$existingImage) {
                        if (isset($existingImage['is_primary'])) {
                            unset($existingImage['is_primary']);
                        }
                    }
                    unset($existingImage); // Break reference
                }
                
                // Append new images to existing gallery
                foreach ($newGalleryItems as $newImage) {
                    $updatedGallery[] = $newImage;
                }

                $validated['gallery'] = $updatedGallery;
                
                Log::info('Added new images to existing gallery', [
                    'existing_images' => count($currentGallery),
                    'new_images' => count($newGalleryItems),
                    'total_images' => count($updatedGallery),
                    'final_gallery' => $updatedGallery
                ]);
            }
            // If no new images, don't modify gallery field at all

            // Auto-generate slug only if empty
            // Don't override user's custom slug even if name changes
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            // Handle boolean fields
            $validated['is_active'] = $request->boolean('is_active', true);
            $validated['is_featured'] = $request->boolean('is_featured');
            $validated['is_on_sale'] = $request->boolean('is_on_sale');
            $validated['is_new_arrival'] = $request->boolean('is_new_arrival');
            $validated['is_bestseller'] = $request->boolean('is_bestseller');
            $validated['installation_service_available'] = $request->boolean('installation_service_available');

            // Handle JSON fields
            if ($request->filled('compatible_car_brands')) {
                $validated['compatible_car_brands'] = json_decode($request->compatible_car_brands, true);
            }
            if ($request->filled('compatible_car_models')) {
                $validated['compatible_car_models'] = json_decode($request->compatible_car_models, true);
            }
            if ($request->filled('specifications')) {
                $validated['specifications'] = json_decode($request->specifications, true);
            }
            if ($request->filled('features')) {
                $validated['features'] = json_decode($request->features, true);
            }
            if ($request->filled('color_options')) {
                $validated['color_options'] = json_decode($request->color_options, true);
            }

            // CRITICAL: If no gallery in validated, explicitly set it to preserve existing
            if (!isset($validated['gallery'])) {
                // Get fresh gallery from database to preserve
                $freshAccessory = Accessory::find($accessory->id);
                $validated['gallery'] = $freshAccessory->gallery ?? [];
                Log::info('No gallery in validated - preserving from database', [
                    'preserved_gallery_count' => is_array($validated['gallery']) ? count($validated['gallery']) : 0
                ]);
            }
            
            Log::info('About to update accessory', [
                'validated_has_gallery' => isset($validated['gallery']),
                'validated_gallery_count' => is_array($validated['gallery'] ?? null) ? count($validated['gallery']) : 0
            ]);
            
            $accessory->update($validated);
            
            // Force refresh model to get latest data
            $accessory->refresh();

            // Debug: Log what was actually saved
            Log::info('Accessory updated successfully', [
                'accessory_id' => $accessory->id,
                'gallery_data' => $accessory->gallery,
                'gallery_count' => is_array($accessory->gallery) ? count($accessory->gallery) : 0,
            ]);

            // Return JSON for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cập nhật phụ kiện "' . $accessory->name . '" thành công!',
                    'accessory' => $accessory,
                    'gallery' => $accessory->gallery ?? [] // Return updated gallery with correct indices
                ]);
            }

            return redirect()->route('admin.accessories.index')->with([
                'success' => 'Cập nhật phụ kiện "' . $accessory->name . '" thành công!',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return JSON for AJAX validation errors
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $e->errors()
                ], 422);
            }

            // Re-throw for normal form submission
            throw $e;
        } catch (\Exception $e) {
            // Handle other errors
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi cập nhật phụ kiện: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật phụ kiện');
        }
    }

    public function show(Accessory $accessory)
    {
        // Eager load all relationships for comprehensive display
        $accessory->load([
            // No direct relationships like CarVariant, but we can load related data if exists
        ]);

        // Calculate statistics
        $stats = [
            'total_accessories' => Accessory::count(),
            'same_category' => Accessory::where('category', $accessory->category)->count(),
            'active_accessories' => Accessory::where('is_active', true)->count(),
            'featured_accessories' => Accessory::where('is_featured', true)->count()
        ];

        return view('admin.accessories.show', compact('accessory', 'stats'));
    }

    public function destroy($id)
    {
        $accessory = Accessory::findOrFail($id);

        // Check for orders (critical business data) - via order_items polymorphic
        $ordersCount = 0;
        $pendingOrdersCount = 0;
        $completedOrdersCount = 0;

        try {
            if (Schema::hasTable('order_items') && Schema::hasTable('orders')) {
                // Count orders that contain this accessory
                $ordersCount = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.item_type', 'accessory')
                    ->where('order_items.item_id', $accessory->id)
                    ->whereNull('orders.deleted_at') // Exclude soft deleted orders
                    ->count();

                // Check order statuses for detailed reporting
                $pendingOrdersCount = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.item_type', 'accessory')
                    ->where('order_items.item_id', $accessory->id)
                    ->whereIn('orders.status', ['pending', 'confirmed', 'shipping'])
                    ->whereNull('orders.deleted_at')
                    ->count();

                $completedOrdersCount = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.item_type', 'accessory')
                    ->where('order_items.item_id', $accessory->id)
                    ->whereIn('orders.status', ['delivered'])
                    ->whereNull('orders.deleted_at')
                    ->count();
            }

            // Fallback: Check direct accessory_id column if exists (legacy support)
            if ($ordersCount === 0 && Schema::hasTable('orders') && Schema::hasColumn('orders', 'accessory_id')) {
                $ordersCount = DB::table('orders')
                    ->where('accessory_id', $accessory->id)
                    ->whereNull('deleted_at')
                    ->count();
            }
        } catch (\Exception $e) {
            // If tables don't exist or have different structure, skip check
            $ordersCount = 0;
        }

        // Check if accessory is currently active (first priority - like CarVariant)
        if ($accessory->is_active) {
            $message = "Không thể xóa phụ kiện \"{$accessory->name}\" vì đang ở trạng thái hoạt động. Vui lòng tạm dừng phụ kiện trước khi xóa.";

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'action_suggestion' => 'deactivate',
                    'show_deactivate_button' => true
                ], 400);
            }
            return redirect()->route('admin.accessories.index')->with('error', $message);
        }

        // CRITICAL: Never delete if has business transaction data (second priority)
        if ($ordersCount > 0) {
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

            $businessDataText = implode(' ', $businessData);
            $message = "KHÔNG THỂ XÓA phụ kiện \"{$accessory->name}\" vì đã có {$businessDataText}. " .
                "Đây là dữ liệu giao dịch quan trọng không được phép xóa! Bạn chỉ có thể 'Tạm dừng' thay vì xóa.";

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'business_data' => [
                        'total_orders' => $ordersCount,
                        'pending_orders' => $pendingOrdersCount,
                        'completed_orders' => $completedOrdersCount
                    ],
                    'action_suggestion' => 'deactivate'
                ], 400);
            }
            return redirect()->route('admin.accessories.index')->with('error', $message);
        }

        // Delete image if exists
        if ($accessory->image_path && Storage::disk('public')->exists($accessory->image_path)) {
            Storage::disk('public')->delete($accessory->image_path);
        }

        // Safe to delete - no business constraints
        $accessoryName = $accessory->name;
        $categoryName = $accessory->category ? $this->getCategoryDisplayName($accessory->category) : 'Không xác định';

        // Perform deletion
        $accessory->delete();

        $successMessage = "✅ Đã xóa phụ kiện \"{$accessoryName}\" (danh mục: {$categoryName}) thành công! " .
            "Tất cả dữ liệu liên quan đã được xóa khỏi hệ thống.";

        if (request()->wantsJson() || request()->ajax()) {
            // Calculate updated statistics for stats cards
            $stats = [
                'active' => Accessory::where('is_active', true)->count(),
                'inactive' => Accessory::where('is_active', false)->count(),
                'total' => Accessory::count(),
                'featured' => Accessory::where('is_featured', true)->count()
            ];

            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'stats' => $stats
            ]);
        }

        return redirect()->route('admin.accessories.index')->with(
            'success',
            "Đã xóa phụ kiện \"{$accessoryName}\" thành công!"
        );
    }

    /**
     * Restore the specified soft-deleted accessory
     */
    public function restore($id)
    {
        $accessory = Accessory::withTrashed()->findOrFail($id);
        $accessory->restore();

        // Handle AJAX requests
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã khôi phục phụ kiện thành công!'
            ]);
        }

        return redirect()->route('admin.accessories.index')->with('success', 'Đã khôi phục phụ kiện thành công!');
    }

    public function toggleStatus(Request $request, $id)
    {
        $accessory = Accessory::findOrFail($id);

        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $newStatus = $request->is_active;
        $accessory->update(['is_active' => $newStatus]);

        $statusText = $newStatus ? 'kích hoạt' : 'tạm dừng';

        // Calculate updated statistics for stats cards
        $stats = [
            'active' => Accessory::where('is_active', true)->count(),
            'inactive' => Accessory::where('is_active', false)->count(),
            'total' => Accessory::count(),
            'featured' => Accessory::where('is_featured', true)->count()
        ];

        return response()->json([
            'success' => true,
            'message' => "Đã {$statusText} phụ kiện \"{$accessory->name}\" thành công!",
            'is_active' => $newStatus,
            'stats' => $stats
        ]);
    }

    /**
     * Get a specific image data
     */
    public function getImage($id, $index)
    {
        $accessory = Accessory::findOrFail($id);
        
        $gallery = $accessory->gallery ?? [];
        if (!is_array($gallery)) {
            $gallery = [];
        }
        
        // CRITICAL: Sort gallery exactly like the view does
        // Primary images first, then by sort_order
        usort($gallery, function($a, $b) {
            // Primary images always come first
            $isPrimaryA = isset($a['is_primary']) && $a['is_primary'] ? 1 : 0;
            $isPrimaryB = isset($b['is_primary']) && $b['is_primary'] ? 1 : 0;
            
            if ($isPrimaryA !== $isPrimaryB) {
                return $isPrimaryB - $isPrimaryA; // Primary first (descending)
            }
            
            // Then sort by sort_order (ascending)
            $sortA = isset($a['sort_order']) ? (int)$a['sort_order'] : 999;
            $sortB = isset($b['sort_order']) ? (int)$b['sort_order'] : 999;
            return $sortA - $sortB;
        });
        
        if (!isset($gallery[$index])) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy hình ảnh'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'image' => $gallery[$index]
        ]);
    }
    
    /**
     * Update a specific image metadata
     */
    public function updateImage(Request $request, $id, $index)
    {
        $accessory = Accessory::findOrFail($id);
        
        $gallery = $accessory->gallery ?? [];
        if (!is_array($gallery)) {
            $gallery = [];
        }
        
        // CRITICAL: Sort gallery to match frontend display order
        usort($gallery, function($a, $b) {
            $isPrimaryA = isset($a['is_primary']) && $a['is_primary'] ? 1 : 0;
            $isPrimaryB = isset($b['is_primary']) && $b['is_primary'] ? 1 : 0;
            if ($isPrimaryA !== $isPrimaryB) {
                return $isPrimaryB - $isPrimaryA;
            }
            $sortA = isset($a['sort_order']) ? (int)$a['sort_order'] : 999;
            $sortB = isset($b['sort_order']) ? (int)$b['sort_order'] : 999;
            return $sortA - $sortB;
        });
        
        if (!isset($gallery[$index])) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy hình ảnh'
            ], 404);
        }
        
        // Update metadata
        if (is_array($gallery[$index])) {
            $gallery[$index]['title'] = $request->input('title', '');
            $gallery[$index]['alt_text'] = $request->input('alt_text', '');
            $gallery[$index]['image_type'] = $request->input('image_type', 'product');
            $gallery[$index]['sort_order'] = (int) $request->input('sort_order', 0);
            
            if ($request->has('description')) {
                $gallery[$index]['description'] = $request->input('description');
            }
            
            // Handle primary flag
            if ($request->input('is_primary')) {
                // Remove is_primary from all other images
                foreach ($gallery as $i => $img) {
                    if ($i != $index && is_array($img)) {
                        $gallery[$i]['is_primary'] = false;
                    }
                }
                $gallery[$index]['is_primary'] = true;
            } else {
                $gallery[$index]['is_primary'] = false;
            }
        }
        
        $accessory->update(['gallery' => $gallery]);
        
        Log::info('Image metadata updated', [
            'accessory_id' => $id,
            'index' => $index,
            'title' => $request->input('title')
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật thông tin ảnh thành công!'
        ]);
    }
    
    /**
     * Delete a specific image from accessory gallery
     */
    public function deleteImage(Request $request, $id, $index)
    {
        $accessory = Accessory::findOrFail($id);
        
        // Get current gallery
        $gallery = $accessory->gallery ?? [];
        if (!is_array($gallery)) {
            $gallery = [];
        }
        
        // CRITICAL: Sort gallery to match frontend display order
        usort($gallery, function($a, $b) {
            $isPrimaryA = isset($a['is_primary']) && $a['is_primary'] ? 1 : 0;
            $isPrimaryB = isset($b['is_primary']) && $b['is_primary'] ? 1 : 0;
            if ($isPrimaryA !== $isPrimaryB) {
                return $isPrimaryB - $isPrimaryA;
            }
            $sortA = isset($a['sort_order']) ? (int)$a['sort_order'] : 999;
            $sortB = isset($b['sort_order']) ? (int)$b['sort_order'] : 999;
            return $sortA - $sortB;
        });
        
        // Validate index
        if (!isset($gallery[$index])) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy hình ảnh'
            ], 404);
        }
        
        // Get image data before deletion
        $imageData = $gallery[$index];
        
        // Delete physical file if it's an uploaded image (has file_path)
        if (is_array($imageData) && isset($imageData['file_path'])) {
            $filePath = $imageData['file_path'];
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
                Log::info('Deleted image file', ['file_path' => $filePath]);
            }
        }
        
        // Remove image from gallery array
        unset($gallery[$index]);
        
        // Re-index array to avoid gaps
        $gallery = array_values($gallery);
        
        // Update accessory
        $accessory->update(['gallery' => $gallery]);
        
        Log::info('Image deleted from gallery', [
            'accessory_id' => $id,
            'index' => $index,
            'remaining_images' => count($gallery)
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Đã xóa hình ảnh thành công!',
            'remaining_count' => count($gallery)
        ]);
    }

    /**
     * Generate a unique slug for the accessory
     */
    private function generateUniqueSlug($name, $excludeId = null)
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 2;

        // Loop để tìm slug unique
        while (true) {
            // Kiểm tra slug hiện tại (bao gồm cả soft deleted)
            $exists = Accessory::withTrashed()
                ->where('slug', $slug)
                ->when($excludeId, function ($query, $excludeId) {
                    return $query->where('id', '!=', $excludeId);
                })
                ->exists();

            // Nếu không tồn tại thì dùng slug này
            if (!$exists) {
                break;
            }

            // Nếu tồn tại thì tạo slug mới với số
            $slug = $baseSlug . '-' . $counter;
            $counter++;

            // Giới hạn để tránh vòng lặp vô hạn
            if ($counter > 1000) {
                $slug = $baseSlug . '-' . time();
                break;
            }
        }

        return $slug;
    }

    /**
     * Get category display name in Vietnamese
     */
    private function getCategoryDisplayName($category)
    {
        $categoryMap = [
            'electronics' => 'Điện tử',
            'interior' => 'Nội thất',
            'exterior' => 'Ngoại thất',
            'safety' => 'An toàn',
            'performance' => 'Hiệu suất',
            'comfort' => 'Tiện nghi',
            'maintenance' => 'Bảo dưỡng',
            'decoration' => 'Trang trí',
            'car_care' => 'Chăm sóc xe',
            'utility' => 'Tiện ích'
        ];
        return $categoryMap[$category] ?? $category;
    }
}
