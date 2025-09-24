@extends('layouts.admin')

@section('title', 'Cập nhật dòng xe')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-edit text-blue-600 mr-3"></i>
                    Cập nhật dòng xe: {{ $carModel->name }}
                </h1>
                <p class="text-sm text-gray-600 mt-1">Chỉnh sửa thông tin dòng xe trong hệ thống</p>
            </div>
            <a href="{{ route('admin.carmodels.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại
            </a>
        </div>
    </div>

    {{-- Form --}}
    <form id="carModelEditForm" action="{{ route('admin.carmodels.update', $carModel) }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        @method('PUT')

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
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" {{ old('car_brand_id', $carModel->car_brand_id) == $car->id ? 'selected' : '' }}>
                                        {{ $car->name }}
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
                                   value="{{ old('name', $carModel->name) }}" placeholder="Ví dụ: Camry, Civic, Vios...">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 @enderror" 
                                      placeholder="Mô tả về dòng xe...">{{ old('description', $carModel->description) }}</textarea>
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
                            <input type="text" name="body_type" id="body_type" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('body_type') border-red-300 @enderror" 
                                   value="{{ old('body_type', $carModel->body_type) }}" placeholder="VD: Sedan, SUV, Hatchback...">
                            @error('body_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="segment" class="block text-sm font-medium text-gray-700 mb-2">Phân khúc</label>
                            <input type="text" name="segment" id="segment" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('segment') border-red-300 @enderror" 
                                   value="{{ old('segment', $carModel->segment) }}" placeholder="VD: A, B, C, D...">
                            @error('segment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="fuel_type" class="block text-sm font-medium text-gray-700 mb-2">Loại nhiên liệu</label>
                            <input type="text" name="fuel_type" id="fuel_type" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fuel_type') border-red-300 @enderror" 
                                   value="{{ old('fuel_type', $carModel->fuel_type) }}" placeholder="VD: Xăng, Dầu, Hybrid...">
                            @error('fuel_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="generation" class="block text-sm font-medium text-gray-700 mb-2">Thế hệ</label>
                            <input type="text" name="generation" id="generation" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('generation') border-red-300 @enderror" 
                                   value="{{ old('generation', $carModel->generation) }}" placeholder="VD: Gen 10, Thế hệ 3...">
                            @error('generation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="production_start_year" class="block text-sm font-medium text-gray-700 mb-2">Năm bắt đầu SX</label>
                            <input type="number" name="production_start_year" id="production_start_year" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('production_start_year') border-red-300 @enderror" 
                                   value="{{ old('production_start_year', $carModel->production_start_year) }}" min="1900" max="{{ date('Y') + 5 }}">
                            @error('production_start_year')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="production_end_year" class="block text-sm font-medium text-gray-700 mb-2">Năm kết thúc SX</label>
                            <input type="number" name="production_end_year" id="production_end_year" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('production_end_year') border-red-300 @enderror" 
                                   value="{{ old('production_end_year', $carModel->production_end_year) }}" min="1900" max="{{ date('Y') + 10 }}">
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
                                   value="{{ old('meta_title', $carModel->meta_title) }}" placeholder="Tiêu đề SEO...">
                            @error('meta_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="3" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('meta_description') border-red-300 @enderror" 
                                      placeholder="Mô tả SEO...">{{ old('meta_description', $carModel->meta_description) }}</textarea>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="keywords" class="block text-sm font-medium text-gray-700 mb-2">Keywords</label>
                            <input type="text" name="keywords" id="keywords" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('keywords') border-red-300 @enderror" 
                                   value="{{ old('keywords', $carModel->keywords) }}" placeholder="từ khóa, phân cách, bằng dấu phẩy">
                            @error('keywords')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN - Images & Display Settings --}}
            <div class="space-y-6">
                {{-- Current Images --}}
                <div class="bg-blue-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-images text-blue-600 mr-2"></i>
                        Hình ảnh hiện tại
                    </h3>
                    
                    @if($carModel->images->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            @foreach($carModel->images as $image)
                                <div class="border border-gray-200 rounded-lg p-3 bg-white">
                                    <div class="flex gap-3">
                                        <div class="relative group flex-shrink-0">
                                            <img src="{{ $image->image_url }}"
                                                 alt="{{ $image->alt_text }}"
                                                 class="w-24 h-18 object-cover rounded-lg border border-gray-200 shadow-sm">
                                            @if($image->is_main)
                                                <div class="absolute -top-1 -right-1">
                                                    <span class="inline-flex items-center justify-center w-4 h-4 bg-blue-600 text-white text-xs rounded-full">
                                                        <i class="fas fa-star"></i>
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all rounded-lg flex items-center justify-center">
                                                <button type="button"
                                                        onclick="deleteImage({{ $image->id }})"
                                                        class="opacity-0 group-hover:opacity-100 bg-red-600 text-white rounded-full p-1 text-xs transition-opacity">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-medium text-gray-900 mb-1">
                                                @switch($image->image_type)
                                                    @case('gallery')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <i class="fas fa-images mr-1"></i>Gallery
                                                        </span>
                                                        @break
                                                    @case('exterior')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <i class="fas fa-car mr-1"></i>Ngoại thất
                                                        </span>
                                                        @break
                                                    @case('interior')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                            <i class="fas fa-couch mr-1"></i>Nội thất
                                                        </span>
                                                        @break
                                                @endswitch
                                                @if($image->is_main)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ml-1">
                                                        <i class="fas fa-star mr-1"></i>Chính
                                                    </span>
                                                @endif
                                            </div>
                                            @if($image->description)
                                                <p class="text-xs text-gray-600 truncate">{{ $image->description }}</p>
                                            @endif
                                            <p class="text-xs text-gray-400 mt-1">{{ $image->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">Chưa có hình ảnh nào</p>
                    @endif

                    {{-- New Image Upload --}}
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-md font-medium text-gray-900 mb-4">Thêm ảnh mới</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload ảnh mới (có thể chọn nhiều ảnh)
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="new_images" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                <span>Chọn ảnh mới</span>
                                                <input id="new_images" name="new_images[]" type="file" class="sr-only" multiple accept="image/*" onchange="previewNewImages(this)">
                                            </label>
                                            <p class="pl-1">hoặc kéo thả vào đây</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF tối đa 10MB mỗi ảnh</p>
                                    </div>
                                </div>
                                @error('new_images')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @error('new_images.*')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- New Image Preview --}}
                            <div id="newImagePreview" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    <i class="fas fa-eye text-blue-600 mr-2"></i>
                                    Xem trước ảnh mới
                                </label>
                                <div id="newPreviewContainer" class="space-y-4">
                                    <!-- Preview images will be inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Display Settings --}}
                <div class="bg-blue-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-cog text-blue-600 mr-2"></i>
                        Cài đặt hiển thị
                    </h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $carModel->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                Kích hoạt
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $carModel->is_featured) ? 'checked' : '' }} 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                Nổi bật
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="hidden" name="is_new" value="0">
                            <input type="checkbox" name="is_new" id="is_new" value="1" {{ old('is_new', $carModel->is_new) ? 'checked' : '' }} 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_new" class="ml-2 block text-sm text-gray-900">
                                Mới
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="hidden" name="is_discontinued" value="0">
                            <input type="checkbox" name="is_discontinued" id="is_discontinued" value="1" {{ old('is_discontinued', $carModel->is_discontinued) ? 'checked' : '' }} 
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
                               value="{{ old('sort_order', $carModel->sort_order) }}" min="0">
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
                <i class="fas fa-save mr-2"></i>
                Cập nhật dòng xe
            </button>
        </div>
    </form>
</div>

<script>
// Delete existing image
function deleteImage(imageId) {
    if (confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
        fetch(`/admin/car-model-images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Có lỗi xảy ra khi xóa ảnh');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa ảnh');
        });
    }
}

// Preview new images
function previewNewImages(input) {
    const previewDiv = document.getElementById('newImagePreview');
    const previewContainer = document.getElementById('newPreviewContainer');
    
    // Clear previous previews
    previewContainer.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        previewDiv.classList.remove('hidden');
        
        Array.from(input.files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow';
                    previewItem.innerHTML = `
                        <div class="p-4">
                            <div class="flex gap-4">
                                <!-- Image Preview -->
                                <div class="relative group flex-shrink-0">
                                    <img src="${e.target.result}" 
                                         alt="Preview ${index + 1}" 
                                         class="w-24 h-24 object-cover rounded-lg border-2 border-gray-200 shadow-sm">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all rounded-lg flex items-center justify-center">
                                        <button type="button" 
                                                onclick="removeNewPreview(this, ${index})"
                                                class="opacity-0 group-hover:opacity-100 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 text-sm transition-all transform hover:scale-110">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Form Controls -->
                                <div class="flex-1 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-medium text-gray-900 flex items-center">
                                            <i class="fas fa-image text-blue-500 mr-2"></i>
                                            Ảnh mới ${index + 1}
                                        </h4>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                <i class="fas fa-tag text-gray-400 mr-1"></i>
                                                Loại ảnh <span class="text-red-500">*</span>
                                            </label>
                                            <select name="new_image_types[]" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                <option value="gallery" ${index === 0 ? 'selected' : ''}>🖼️ Gallery - Tổng quan</option>
                                                <option value="exterior">🚗 Exterior - Ngoại thất</option>
                                                <option value="interior">🪑 Interior - Nội thất</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                <i class="fas fa-sort-numeric-down text-gray-400 mr-1"></i>
                                                Thứ tự hiển thị
                                            </label>
                                            <input type="number" name="new_image_sort_orders[]" 
                                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                                   value="${index * 10}" min="0" step="10">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            <i class="fas fa-search text-gray-400 mr-1"></i>
                                            Alt text (SEO)
                                        </label>
                                        <input type="text" name="new_image_alt_texts[]" 
                                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                               placeholder="VD: Honda Civic 2024 màu trắng nhìn từ phía trước">
                                    </div>
                                    
                                    <!-- Hidden fields with auto values -->
                                    <input type="hidden" name="new_image_titles[]" value="">
                                    <input type="hidden" name="new_image_descriptions[]" value="">
                                    <input type="hidden" name="new_image_is_active[${index}]" value="1">
                                </div>
                            </div>
                        </div>
                    `;
                    previewContainer.appendChild(previewItem);
                };
                
                reader.readAsDataURL(file);
            }
        });
    } else {
        previewDiv.classList.add('hidden');
    }
}

function removeNewPreview(button, index) {
    button.closest('.bg-white').remove();
}

// AJAX Form Submission for CarModel Edit
document.getElementById('carModelEditForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    formData.append('_method', 'PUT'); // Laravel method spoofing
    
    const submitBtn = form.querySelector('button[type="submit"]');
    const submitText = submitBtn.querySelector('.submit-text') || submitBtn;
    const originalText = submitText.textContent;
    
    // Show loading state
    submitBtn.disabled = true;
    submitText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang cập nhật...';
    
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
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showMessage('Cập nhật dòng xe thành công!', 'success');
            
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
        
        // Handle validation errors
        if (error.response && error.response.status === 422) {
            error.response.json().then(data => {
                displayValidationErrors(data.errors);
            });
        } else {
            showMessage('Có lỗi xảy ra khi cập nhật dòng xe', 'error');
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
</script>
@endsection
