@extends('layouts.admin')

@section('title', 'Thêm dòng xe')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                    Thêm dòng xe mới
                </h1>
                <p class="text-sm text-gray-600 mt-1">Tạo dòng xe mới cho hãng xe</p>
            </div>
            <a href="{{ route('admin.carmodels.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại
            </a>
        </div>
    </div>

    {{-- Form --}}
    <form id="carModelForm" action="{{ route('admin.carmodels.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- LEFT COLUMN - Basic Info, Specifications, SEO --}}
            <div class="space-y-6">
                {{-- Basic Info --}}
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Thông tin cơ bản
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="car_brand_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Hãng xe <span class="text-red-500">*</span>
                            </label>
                            <select name="car_brand_id" id="car_brand_id" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('car_brand_id') border-red-300 @enderror">
                                <option value="">Chọn hãng xe...</option>
                                @foreach($carBrands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('car_brand_id', request('brand_id')) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('car_brand_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Tên mẫu xe <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror" 
                                   value="{{ old('name') }}" placeholder="Ví dụ: Camry, Civic, Vios...">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 @enderror" 
                                      placeholder="Mô tả về dòng xe...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Vehicle Specifications --}}
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-car text-blue-600 mr-2"></i>
                        Thông số xe
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="body_type" class="block text-sm font-medium text-gray-700 mb-2">Kiểu dáng</label>
                            <select name="body_type" id="body_type" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('body_type') border-red-300 @enderror">
                                <option value="">-- Chọn kiểu dáng --</option>
                                <option value="sedan" {{ old('body_type') == 'sedan' ? 'selected' : '' }}>Sedan</option>
                                <option value="suv" {{ old('body_type') == 'suv' ? 'selected' : '' }}>SUV</option>
                                <option value="hatchback" {{ old('body_type') == 'hatchback' ? 'selected' : '' }}>Hatchback</option>
                                <option value="wagon" {{ old('body_type') == 'wagon' ? 'selected' : '' }}>Wagon</option>
                                <option value="coupe" {{ old('body_type') == 'coupe' ? 'selected' : '' }}>Coupe</option>
                                <option value="convertible" {{ old('body_type') == 'convertible' ? 'selected' : '' }}>Convertible</option>
                                <option value="pickup" {{ old('body_type') == 'pickup' ? 'selected' : '' }}>Pickup</option>
                                <option value="van" {{ old('body_type') == 'van' ? 'selected' : '' }}>Van</option>
                                <option value="minivan" {{ old('body_type') == 'minivan' ? 'selected' : '' }}>Minivan</option>
                            </select>
                            @error('body_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="segment" class="block text-sm font-medium text-gray-700 mb-2">Phân khúc</label>
                            <select name="segment" id="segment" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('segment') border-red-300 @enderror">
                                <option value="">-- Chọn phân khúc --</option>
                                <option value="economy" {{ old('segment') == 'economy' ? 'selected' : '' }}>Economy - Xe tiết kiệm</option>
                                <option value="compact" {{ old('segment') == 'compact' ? 'selected' : '' }}>Compact - Xe nhỏ gọn</option>
                                <option value="mid-size" {{ old('segment') == 'mid-size' ? 'selected' : '' }}>Mid-size - Xe cỡ trung</option>
                                <option value="full-size" {{ old('segment') == 'full-size' ? 'selected' : '' }}>Full-size - Xe cỡ lớn</option>
                                <option value="luxury" {{ old('segment') == 'luxury' ? 'selected' : '' }}>Luxury - Xe sang trọng</option>
                                <option value="premium" {{ old('segment') == 'premium' ? 'selected' : '' }}>Premium - Xe cao cấp</option>
                                <option value="sports" {{ old('segment') == 'sports' ? 'selected' : '' }}>Sports - Xe thể thao</option>
                                <option value="exotic" {{ old('segment') == 'exotic' ? 'selected' : '' }}>Exotic - Xe siêu sang</option>
                            </select>
                            @error('segment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="fuel_type" class="block text-sm font-medium text-gray-700 mb-2">Loại nhiên liệu</label>
                            <select name="fuel_type" id="fuel_type" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fuel_type') border-red-300 @enderror">
                                <option value="">-- Chọn loại nhiên liệu --</option>
                                <option value="gasoline" {{ old('fuel_type') == 'gasoline' ? 'selected' : '' }}>Gasoline - Xăng</option>
                                <option value="diesel" {{ old('fuel_type') == 'diesel' ? 'selected' : '' }}>Diesel - Dầu</option>
                                <option value="hybrid" {{ old('fuel_type') == 'hybrid' ? 'selected' : '' }}>Hybrid - Lai</option>
                                <option value="electric" {{ old('fuel_type') == 'electric' ? 'selected' : '' }}>Electric - Điện</option>
                                <option value="plug-in_hybrid" {{ old('fuel_type') == 'plug-in_hybrid' ? 'selected' : '' }}>Plug-in Hybrid - Lai sạc điện</option>
                                <option value="hydrogen" {{ old('fuel_type') == 'hydrogen' ? 'selected' : '' }}>Hydrogen - Hydro</option>
                            </select>
                            @error('fuel_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="generation" class="block text-sm font-medium text-gray-700 mb-2">Thế hệ</label>
                            <input type="text" name="generation" id="generation" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('generation') border-red-300 @enderror" 
                                   value="{{ old('generation') }}" placeholder="VD: Gen 10, Thế hệ 3...">
                            @error('generation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="production_start_year" class="block text-sm font-medium text-gray-700 mb-2">Năm bắt đầu SX</label>
                            <input type="number" name="production_start_year" id="production_start_year" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('production_start_year') border-red-300 @enderror" 
                                   value="{{ old('production_start_year') }}" min="1900" max="{{ date('Y') + 5 }}">
                            @error('production_start_year')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="production_end_year" class="block text-sm font-medium text-gray-700 mb-2">Năm kết thúc SX</label>
                            <input type="number" name="production_end_year" id="production_end_year" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('production_end_year') border-red-300 @enderror" 
                                   value="{{ old('production_end_year') }}" min="1900" max="{{ date('Y') + 10 }}">
                            @error('production_end_year')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- SEO Settings --}}
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-search text-blue-600 mr-2"></i>
                        Tối ưu SEO
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('meta_title') border-red-300 @enderror" 
                                   value="{{ old('meta_title') }}" placeholder="Tiêu đề SEO...">
                            @error('meta_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="3" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('meta_description') border-red-300 @enderror" 
                                      placeholder="Mô tả SEO...">{{ old('meta_description') }}</textarea>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="keywords" class="block text-sm font-medium text-gray-700 mb-2">Keywords</label>
                            <input type="text" name="keywords" id="keywords" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('keywords') border-red-300 @enderror" 
                                   value="{{ old('keywords') }}" placeholder="từ khóa, phân cách, bằng dấu phẩy">
                            @error('keywords')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN - Images & Display Settings --}}
            <div class="space-y-6">
                {{-- Image Upload --}}
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-images text-blue-600 mr-2"></i>
                        Hình ảnh dòng xe
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Upload ảnh (có thể chọn nhiều ảnh)
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Chọn ảnh</span>
                                            <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*" onchange="previewImages(this)">
                                        </label>
                                        <p class="pl-1">hoặc kéo thả vào đây</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF tối đa 10MB mỗi ảnh</p>
                                </div>
                            </div>
                            @error('images')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('images.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Image Preview --}}
                        <div id="imagePreview" class="hidden">
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-eye text-blue-600 mr-2"></i>
                                    Xem trước ảnh đã chọn
                                </label>
                                <span id="previewCount" class="text-sm text-gray-600"></span>
                            </div>
                            
                            <div id="previewListView" class="space-y-3 overflow-y-auto" style="max-height: 320px;">
                                <!-- Preview images will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Display Settings --}}
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-cog text-blue-600 mr-2"></i>
                        Cài đặt hiển thị
                    </h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active" value="1" checked
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Hoạt động
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                Nổi bật
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="hidden" name="is_new" value="0">
                            <input type="checkbox" name="is_new" id="is_new" value="1" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_new" class="ml-2 block text-sm text-gray-900">
                                Mới
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="hidden" name="is_discontinued" value="0">
                            <input type="checkbox" name="is_discontinued" id="is_discontinued" value="1" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_discontinued" class="ml-2 block text-sm text-gray-900">
                                Ngừng SX
                            </label>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Thứ tự sắp xếp</label>
                        <input type="number" name="sort_order" id="sort_order" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sort_order') border-red-300 @enderror" 
                               value="{{ old('sort_order', 0) }}" min="0">
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit Buttons --}}
        <div class="mt-8 flex items-center justify-between pt-6 border-t border-gray-200">
            <a href="{{ route('admin.carmodels.index') }}" class="inline-flex items-center px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                <i class="fas fa-times mr-2"></i>
                Hủy bỏ
            </a>
            <button type="submit" class="inline-flex items-center px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-lg">
                <i class="fas fa-plus mr-2"></i>
                Tạo dòng xe
            </button>
        </div>
    </form>
</div>

<script>
function previewImages(input) {
    const previewDiv = document.getElementById('imagePreview');
    const previewListView = document.getElementById('previewListView');
    const previewCount = document.getElementById('previewCount');
    
    // Clear previous previews
    previewListView.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        previewDiv.classList.remove('hidden');
        previewCount.textContent = `${input.files.length} ảnh`;
        
        // Create placeholders first to maintain order
        const filesArray = Array.from(input.files);
        const placeholders = [];
        
        filesArray.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                // Create placeholder
                const placeholder = document.createElement('div');
                placeholder.className = 'bg-gray-100 border border-gray-200 rounded-lg p-3 animate-pulse';
                placeholder.innerHTML = `
                    <div class="flex gap-3">
                        <div class="w-20 h-16 bg-gray-300 rounded"></div>
                        <div class="flex-1">
                            <div class="h-4 bg-gray-300 rounded mb-2"></div>
                            <div class="h-3 bg-gray-300 rounded w-1/2"></div>
                        </div>
                    </div>
                `;
                previewListView.appendChild(placeholder);
                placeholders[index] = placeholder;
                
                // Load image
                const reader = new FileReader();
                reader.onload = function(e) {
                    const listItem = createListPreviewItem(e.target.result, index);
                    // Replace placeholder with actual content
                    previewListView.replaceChild(listItem, placeholder);
                };
                reader.readAsDataURL(file);
            }
        });
    } else {
        previewDiv.classList.add('hidden');
    }
}

function createListPreviewItem(imageSrc, index) {
    const previewItem = document.createElement('div');
    previewItem.className = 'bg-white border border-gray-200 rounded-lg p-3 shadow-sm';
    previewItem.innerHTML = `
        <div class="flex gap-3">
            <div class="relative group flex-shrink-0">
                <img src="${imageSrc}" 
                     alt="Preview ${index + 1}" 
                     class="w-20 h-16 object-cover rounded border">
                <button type="button" 
                        onclick="removePreview(this, ${index})"
                        class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium text-gray-900">Ảnh ${index + 1}</span>
                        <span id="mainBadge_${index}" class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full hidden">Chính</span>
                    </div>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="main_image_index" value="${index}" 
                               class="text-blue-600 focus:ring-blue-500" onchange="updateMainImageBadges(${index})">
                        <span class="text-xs font-medium text-blue-600">Đặt làm ảnh chính</span>
                    </label>
                </div>
                <div class="space-y-2">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Loại ảnh</label>
                            <select name="image_types[]" class="w-full text-xs border rounded px-2 py-1" onchange="updateImageInfoOnTypeChange(this)">
                                <option value="gallery" ${index === 0 ? 'selected' : ''}>Tổng quan</option>
                                <option value="exterior">Ngoại thất</option>
                                <option value="interior">Nội thất</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Thứ tự</label>
                            <input type="number" name="image_sort_orders[]" value="${index + 1}" min="1" 
                                   class="w-full text-xs border rounded px-2 py-1">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Tiêu đề ảnh</label>
                        <input type="text" name="image_titles[]" 
                               class="w-full text-xs border rounded px-2 py-1 image-title-input"
                               placeholder="VD: Honda Civic 2024 - Ngoại thất">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Alt text (SEO)</label>
                        <input type="text" name="image_alt_texts[]" 
                               class="w-full text-xs border rounded px-2 py-1 image-alt-input"
                               placeholder="VD: Hình ảnh ngoại thất Honda Civic 2024">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Mô tả ảnh</label>
                        <textarea name="image_descriptions[]" rows="2"
                                  class="w-full text-xs border rounded px-2 py-1 image-description-input"
                                  placeholder="VD: Hình ảnh ngoại thất chi tiết"></textarea>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="image_is_active[${index}]" value="1" checked
                                   class="rounded border-gray-300 text-blue-600">
                            <span class="text-xs text-gray-700">
                                <i class="fas fa-eye text-gray-400 mr-1"></i>Hiển thị ảnh
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    `;
    return previewItem;
}

// Update main image badges
function updateMainImageBadges(selectedIndex) {
    // Hide all badges first
    document.querySelectorAll('[id^="mainBadge_"]').forEach(badge => {
        badge.classList.add('hidden');
    });
    
    // Show badge for selected image
    const selectedBadge = document.getElementById(`mainBadge_${selectedIndex}`);
    if (selectedBadge) {
        selectedBadge.classList.remove('hidden');
    }
}

// Remove preview item
function removePreview(button, index) {
    // Remove from list view
    const listItem = button.closest('.bg-white');
    if (listItem) listItem.remove();
    
    // Update file input
    const fileInput = document.getElementById('images');
    const dt = new DataTransfer();
    
    Array.from(fileInput.files).forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    fileInput.files = dt.files;
    
    // Update preview count
    const previewCount = document.getElementById('previewCount');
    const remainingCount = fileInput.files.length;
    
    if (remainingCount > 0) {
        previewCount.textContent = `${remainingCount} ảnh`;
    } else {
        document.getElementById('imagePreview').classList.add('hidden');
    }
}

// AJAX Form Submission for CarModel Create
document.getElementById('carModelForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const submitText = submitBtn.querySelector('.submit-text') || submitBtn;
    const originalText = submitText.textContent;
    
    // Show loading state
    submitBtn.disabled = true;
    submitText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';
    
    // Clear previous errors
    document.querySelectorAll('.error-message').forEach(el => el.remove());
    document.querySelectorAll('.border-red-300').forEach(el => {
        el.classList.remove('border-red-300');
        el.classList.add('border-gray-300');
    });
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw { status: response.status, data: data };
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            showMessage(data.message || 'Thêm dòng xe thành công!', 'success');
            
            // Redirect after delay
            setTimeout(() => {
                window.location.href = data.redirect || '/admin/carmodels';
            }, 1500);
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Handle validation errors (422)
        if (error.status === 422 && error.data && error.data.errors) {
            displayValidationErrors(error.data.errors);
            showMessage(error.data.message, 'error');
        } else if (error.data && error.data.message) {
            showMessage(error.data.message, 'error');
        } else {
            showMessage(error.message || 'Có lỗi xảy ra khi thêm dòng xe', 'error');
        }
    })
    .finally(() => {
        // Reset button state
        submitBtn.disabled = false;
        submitText.textContent = originalText;
    });
});

function displayValidationErrors(errors) {
    Object.keys(errors).forEach(field => {
        const input = document.querySelector(`[name="${field}"]`);
        if (input) {
            // Add error border
            input.classList.remove('border-gray-300');
            input.classList.add('border-red-300');
            // Add error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message mt-1 text-sm text-red-600';
            errorDiv.textContent = errors[field][0];
            input.parentNode.appendChild(errorDiv);
        }
    });
}

// Auto-fill image information based on car model name and image type
function autoFillImageInfo(imageItem, index) {
    const carModelName = document.getElementById('name').value || 'Dòng xe';
    const imageTypeSelect = imageItem.querySelector('select[name="image_types[]"]');
    const imageType = imageTypeSelect.value;
    
    const titleInput = imageItem.querySelector('.image-title-input');
    const altInput = imageItem.querySelector('.image-alt-input');
    const descriptionInput = imageItem.querySelector('.image-description-input');
    
    // Only auto-fill if fields are empty
    if (!titleInput.value.trim()) {
        const typeText = {
            'gallery': 'Tổng quan',
            'exterior': 'Ngoại thất', 
            'interior': 'Nội thất'
        };
        titleInput.value = `${carModelName} - ${typeText[imageType] || 'Tổng quan'}`;
    }
    
    if (!altInput.value.trim()) {
        const typeText = {
            'gallery': 'Hình ảnh tổng quan',
            'exterior': 'Hình ảnh ngoại thất', 
            'interior': 'Hình ảnh nội thất'
        };
        altInput.value = `${typeText[imageType] || 'Hình ảnh'} ${carModelName} chất lượng cao`;
    }
    
    if (!descriptionInput.value.trim()) {
        const typeText = {
            'gallery': 'Hình ảnh tổng quan toàn diện',
            'exterior': 'Hình ảnh ngoại thất chi tiết', 
            'interior': 'Hình ảnh nội thất sang trọng'
        };
        descriptionInput.value = `${typeText[imageType] || 'Hình ảnh chi tiết'} của ${carModelName} với thiết kế hiện đại và chất lượng cao.`;
    }
}

// Update image info when image type changes
function updateImageInfoOnTypeChange(selectElement) {
    const imageItem = selectElement.closest('.bg-white');
    const index = Array.from(imageItem.parentNode.children).indexOf(imageItem);
    
    // Clear existing values to trigger auto-fill
    const titleInput = imageItem.querySelector('.image-title-input');
    const altInput = imageItem.querySelector('.image-alt-input');
    const descriptionInput = imageItem.querySelector('.image-description-input');
    
    // Clear fields to allow auto-fill with new type
    titleInput.value = '';
    altInput.value = '';
    descriptionInput.value = '';
    
    // Auto-fill with new type
    autoFillImageInfo(imageItem, index);
}
</script>
@endsection
