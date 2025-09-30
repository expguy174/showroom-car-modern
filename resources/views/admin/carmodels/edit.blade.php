@extends('layouts.admin')

@section('title', 'Cập nhật dòng xe')

@section('content')
{{-- Flash Messages Component --}}
<x-admin.flash-messages 
    :show-icons="true"
    :dismissible="true"
    position="top-right"
    :auto-dismiss="5000" />

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
                                @foreach($carBrands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('car_brand_id', $carModel->car_brand_id) == $brand->id ? 'selected' : '' }}>
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
                            <select name="body_type" id="body_type" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('body_type') border-red-300 @enderror">
                                <option value="">-- Chọn kiểu dáng --</option>
                                <option value="sedan" {{ old('body_type', $carModel->body_type) == 'sedan' ? 'selected' : '' }}>Sedan</option>
                                <option value="suv" {{ old('body_type', $carModel->body_type) == 'suv' ? 'selected' : '' }}>SUV</option>
                                <option value="hatchback" {{ old('body_type', $carModel->body_type) == 'hatchback' ? 'selected' : '' }}>Hatchback</option>
                                <option value="wagon" {{ old('body_type', $carModel->body_type) == 'wagon' ? 'selected' : '' }}>Wagon</option>
                                <option value="coupe" {{ old('body_type', $carModel->body_type) == 'coupe' ? 'selected' : '' }}>Coupe</option>
                                <option value="convertible" {{ old('body_type', $carModel->body_type) == 'convertible' ? 'selected' : '' }}>Convertible</option>
                                <option value="pickup" {{ old('body_type', $carModel->body_type) == 'pickup' ? 'selected' : '' }}>Pickup</option>
                                <option value="van" {{ old('body_type', $carModel->body_type) == 'van' ? 'selected' : '' }}>Van</option>
                                <option value="minivan" {{ old('body_type', $carModel->body_type) == 'minivan' ? 'selected' : '' }}>Minivan</option>
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
                                <option value="economy" {{ old('segment', $carModel->segment) == 'economy' ? 'selected' : '' }}>Economy - Xe tiết kiệm</option>
                                <option value="compact" {{ old('segment', $carModel->segment) == 'compact' ? 'selected' : '' }}>Compact - Xe nhỏ gọn</option>
                                <option value="mid-size" {{ old('segment', $carModel->segment) == 'mid-size' ? 'selected' : '' }}>Mid-size - Xe cỡ trung</option>
                                <option value="full-size" {{ old('segment', $carModel->segment) == 'full-size' ? 'selected' : '' }}>Full-size - Xe cỡ lớn</option>
                                <option value="luxury" {{ old('segment', $carModel->segment) == 'luxury' ? 'selected' : '' }}>Luxury - Xe sang trọng</option>
                                <option value="premium" {{ old('segment', $carModel->segment) == 'premium' ? 'selected' : '' }}>Premium - Xe cao cấp</option>
                                <option value="sports" {{ old('segment', $carModel->segment) == 'sports' ? 'selected' : '' }}>Sports - Xe thể thao</option>
                                <option value="exotic" {{ old('segment', $carModel->segment) == 'exotic' ? 'selected' : '' }}>Exotic - Xe siêu sang</option>
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
                                <option value="gasoline" {{ old('fuel_type', $carModel->fuel_type) == 'gasoline' ? 'selected' : '' }}>Gasoline - Xăng</option>
                                <option value="diesel" {{ old('fuel_type', $carModel->fuel_type) == 'diesel' ? 'selected' : '' }}>Diesel - Dầu</option>
                                <option value="hybrid" {{ old('fuel_type', $carModel->fuel_type) == 'hybrid' ? 'selected' : '' }}>Hybrid - Lai</option>
                                <option value="electric" {{ old('fuel_type', $carModel->fuel_type) == 'electric' ? 'selected' : '' }}>Electric - Điện</option>
                                <option value="plug-in_hybrid" {{ old('fuel_type', $carModel->fuel_type) == 'plug-in_hybrid' ? 'selected' : '' }}>Plug-in Hybrid - Lai sạc điện</option>
                                <option value="hydrogen" {{ old('fuel_type', $carModel->fuel_type) == 'hydrogen' ? 'selected' : '' }}>Hydrogen - Hydro</option>
                            </select>
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
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-images text-blue-600 mr-2"></i>
                            Hình ảnh hiện tại
                        </h3>
                        @if($carModel->images->count() > 0)
                            <span class="text-sm text-gray-600">{{ $carModel->images->count() }} ảnh</span>
                        @endif
                    </div>
                    
                    @if($carModel->images->count() > 0)
                        <div class="space-y-3 mb-6 max-h-96 overflow-y-auto">
                            @foreach($carModel->images as $image)
                                <div class="border border-gray-200 rounded-lg p-3 bg-white image-item" data-image-id="{{ $image->id }}">
                                    <div class="flex gap-3">
                                        <div class="relative group flex-shrink-0 h-full">
                                            <img src="{{ $image->image_url }}"
                                                 alt="{{ $image->alt_text }}"
                                                 class="w-28 h-full object-cover rounded-lg border border-gray-200 shadow-sm">
                                            @if($image->is_main)
                                                <div class="absolute -top-1 -right-1">
                                                    <span class="inline-flex items-center justify-center w-4 h-4 bg-blue-600 text-white text-xs rounded-full">
                                                        <i class="fas fa-star"></i>
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all rounded-lg flex items-center justify-center gap-1">
                                                @if(!$image->is_main)
                                                    <button type="button"
                                                            onclick="setMainImage('{{ $image->id }}')"
                                                            class="opacity-0 group-hover:opacity-100 bg-blue-600 text-white rounded-full p-1 text-xs transition-opacity"
                                                            title="Đặt làm ảnh chính">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                @endif
                                                <button type="button"
                                                        onclick="editImage('{{ $image->id }}')"
                                                        class="opacity-0 group-hover:opacity-100 bg-green-600 text-white rounded-full p-1 text-xs transition-opacity"
                                                        title="Chỉnh sửa ảnh">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button"
                                                        onclick="deleteImage('{{ $image->id }}')"
                                                        class="opacity-0 group-hover:opacity-100 bg-red-600 text-white rounded-full p-1 text-xs transition-opacity"
                                                        title="Xóa ảnh">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-2 flex-wrap">
                                                @switch($image->image_type)
                                                    @case('gallery')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                            <i class="fas fa-images mr-1"></i>Tổng quan
                                                        </span>
                                                        @break
                                                    @case('exterior')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <i class="fas fa-car mr-1"></i>Ngoại thất
                                                        </span>
                                                        @break
                                                    @case('interior')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                            <i class="fas fa-couch mr-1"></i>Nội thất
                                                        </span>
                                                        @break
                                                @endswitch
                                                
                                                @if($image->is_active)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-eye mr-1"></i>Hiển thị
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="fas fa-eye-slash mr-1"></i>Ẩn
                                                    </span>
                                                @endif
                                                
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                                    <i class="fas fa-sort-numeric-down mr-1"></i>{{ $image->sort_order }}
                                                </span>
                                            </div>
                                            
                                            @if($image->title)
                                                <h4 class="text-sm font-medium text-gray-900 mb-1 truncate" title="{{ $image->title }}">
                                                    <i class="fas fa-heading text-gray-400 mr-1"></i>{{ $image->title }}
                                                </h4>
                                            @endif
                                            
                                            @if($image->alt_text)
                                                <p class="text-xs text-blue-600 mb-1 truncate" title="{{ $image->alt_text }}">
                                                    <i class="fas fa-search text-blue-400 mr-1"></i>{{ $image->alt_text }}
                                                </p>
                                            @endif
                                            
                                            @if($image->description)
                                                <p class="text-xs text-gray-600 mb-1 truncate" title="{{ $image->description }}">
                                                    <i class="fas fa-align-left text-gray-400 mr-1"></i>{{ $image->description }}
                                                </p>
                                            @endif
                                            
                                            <p class="text-xs text-gray-400 mt-2">
                                                <i class="fas fa-calendar text-gray-400 mr-1"></i>{{ $image->created_at->format('d/m/Y H:i') }}
                                            </p>
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
                                <div class="flex items-center justify-between mb-3">
                                    <label class="block text-sm font-medium text-gray-700">
                                        <i class="fas fa-eye text-blue-600 mr-2"></i>
                                        Xem trước ảnh mới
                                    </label>
                                    <span id="previewCount" class="text-sm text-gray-600"></span>
                                </div>
                                
                                <div id="previewListView" class="space-y-3 overflow-y-auto" style="max-height: 320px;">
                                    <!-- Preview images will be inserted here -->
                                </div>
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
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $carModel->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                Hoạt động
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

{{-- Delete Image Modal --}}
<div id="deleteImageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden flex items-center justify-center">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow">
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="mb-5 text-lg font-normal text-gray-500">
                    Xác nhận xóa hình ảnh
                </h3>
                <p class="text-sm text-gray-500 mb-3">
                    Bạn có chắc chắn muốn xóa hình ảnh này không?
                </p>
                
                {{-- Impact Analysis --}}
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-3">
                    <h4 class="text-sm font-medium text-yellow-800 mb-2">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Phân tích tác động:
                    </h4>
                    <div class="text-xs text-yellow-700 space-y-1">
                        <div>• Hình ảnh sẽ bị xóa khỏi hệ thống</div>
                        <div>• File ảnh sẽ bị xóa khỏi storage</div>
                        <div class="text-red-600 font-medium mt-2">
                            <i class="fas fa-warning mr-1"></i>
                            Hành động này không thể hoàn tác!
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-center space-x-3">
                    <button type="button" 
                            onclick="closeDeleteModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 transition-colors">
                        Hủy bỏ
                    </button>
                    <button type="button" 
                            onclick="confirmDeleteImage()" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 transition-colors">
                        <i class="fas fa-trash mr-2"></i>
                        Xóa hình ảnh
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit Image Modal --}}
<div id="editImageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden flex items-center justify-center">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        Chỉnh sửa hình ảnh
                    </h3>
                    <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="editImageForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-tag text-gray-400 mr-1"></i>
                            Loại ảnh
                        </label>
                        <select id="editImageType" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="gallery">Tổng quan</option>
                            <option value="exterior">Ngoại thất</option>
                            <option value="interior">Nội thất</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-heading text-gray-400 mr-1"></i>
                            Tiêu đề ảnh
                        </label>
                        <input type="text" id="editTitle" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                               placeholder="VD: Honda Civic 2024 - Ngoại thất">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-search text-gray-400 mr-1"></i>
                            Alt text (SEO)
                        </label>
                        <input type="text" id="editAltText" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                               placeholder="VD: Hình ảnh ngoại thất Honda Civic 2024 màu trắng">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-align-left text-gray-400 mr-1"></i>
                            Mô tả ảnh
                        </label>
                        <textarea id="editDescription" rows="2"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                  placeholder="VD: Hình ảnh chi tiết ngoại thất Honda Civic 2024 với thiết kế hiện đại"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-sort-numeric-down text-gray-400 mr-1"></i>
                                Thứ tự hiển thị
                            </label>
                            <input type="number" id="editSortOrder" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                   min="1" step="1">
                        </div>
                        
                        <div class="flex items-end">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" id="editIsActive" value="1" checked
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="text-sm font-medium text-gray-700">
                                    <i class="fas fa-eye text-gray-400 mr-1"></i>
                                    Hiển thị ảnh
                                </span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex justify-between space-x-3 pt-4">
                        <button type="button" 
                                onclick="closeEditModal()" 
                                class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Hủy bỏ
                        </button>
                        <button type="button" 
                                onclick="saveImageEdit()" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Delete existing image - show modal
let currentImageId = null;
let currentEditImageId = null;

function deleteImage(imageId) {
    currentImageId = String(imageId);
    showDeleteModal();
}

function showDeleteModal() {
    document.getElementById('deleteImageModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteImageModal').classList.add('hidden');
    // Don't reset currentImageId here - only reset after successful deletion
}

function confirmDeleteImage() {
    if (!currentImageId) return;
    
    fetch(`/admin/car-model-images/${currentImageId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Xóa ảnh thành công!', 'success');
            closeDeleteModal();
            
            // Remove the image element from DOM immediately
            const imageElement = document.querySelector(`[data-image-id="${currentImageId}"]`);
            
            if (imageElement) {
                // Add fade out animation
                imageElement.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                imageElement.style.opacity = '0';
                imageElement.style.transform = 'scale(0.95)';
                
                // Remove element after animation
                setTimeout(() => {
                    imageElement.remove();
                    
                    // Update image count
                    updateImageCount();
                    
                    // Also try to update immediately without waiting
                    setTimeout(() => {
                        updateImageCount();
                    }, 100);
                    
                    // Check if no images left, show empty state
                    const imageGrid = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2');
                    if (imageGrid && imageGrid.children.length === 0) {
                        // Replace the entire images section with empty state
                        const imagesSection = imageGrid.closest('.bg-gray-50');
                        if (imagesSection) {
                            imagesSection.innerHTML = `
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    <i class="fas fa-images text-blue-600 mr-2"></i>
                                    Hình ảnh hiện tại
                                </h3>
                                <div class="text-center py-12 text-gray-500">
                                    <i class="fas fa-image text-6xl mb-4 text-gray-300"></i>
                                    <p class="text-lg font-medium mb-2">Chưa có hình ảnh nào</p>
                                    <p class="text-sm">Thêm ảnh mới bằng cách tải lên ở phía dưới</p>
                                </div>
                            `;
                        }
                    }
                }, 300);
            }
            
            // Reset currentImageId after successful deletion
            currentImageId = null;
        } else {
            showMessage('Có lỗi xảy ra khi xóa ảnh', 'error');
            currentImageId = null; // Reset on error too
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Có lỗi xảy ra khi xóa ảnh', 'error');
        currentImageId = null; // Reset on error too
    });
}

// Close modal when clicking outside
document.getElementById('deleteImageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
        closeEditModal();
    }
});

// Close edit modal when clicking outside
document.getElementById('editImageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});


// Edit image functions
function editImage(imageId) {
    console.log('Edit image called with ID:', imageId);
    currentEditImageId = String(imageId);
    console.log('Set currentEditImageId to:', currentEditImageId);
    
    // Get current image data from DOM
    const imageContainer = document.querySelector(`[data-image-id="${imageId}"]`);
    console.log('Found container for edit:', imageContainer);
    if (imageContainer) {
        // Get current values
        const typeElement = imageContainer.querySelector('.bg-blue-100, .bg-green-100, .bg-purple-100');
        let currentType = 'gallery';
        if (typeElement) {
            if (typeElement.textContent.includes('Ngoại thất')) currentType = 'exterior';
            else if (typeElement.textContent.includes('Nội thất')) currentType = 'interior';
        }
        
        const altText = imageContainer.querySelector('img').alt || '';
        
        // Get title from the h4 element, remove icon and trim
        const titleElement = imageContainer.querySelector('h4.text-sm.font-medium');
        let title = '';
        if (titleElement) {
            const titleText = titleElement.textContent || '';
            // Remove icon (first character) and trim
            title = titleText.replace(/^[^\w\s]*\s*/, '').trim();
        }
        
        // Get description from p element (NOT the date element), remove icon and trim  
        const descElement = imageContainer.querySelector('.text-xs.text-gray-600:not(.bg-gray-50)');
        let description = '';
        if (descElement) {
            const descText = descElement.textContent || '';
            // Remove icon (first character) and trim
            description = descText.replace(/^[^\w\s]*\s*/, '').trim();
        }
        
        // Get sort order from badge
        const sortElement = imageContainer.querySelector('.bg-gray-100.text-gray-700');
        const sortOrder = sortElement ? sortElement.textContent.replace(/^[^\d]*/, '').trim() : '0';
        
        // Get active status from badge
        const activeElement = imageContainer.querySelector('.bg-green-100.text-green-800, .bg-red-100.text-red-800');
        const isActive = activeElement ? activeElement.textContent.includes('Hiển thị') : true;
        
        // Set form values
        document.getElementById('editImageType').value = currentType;
        document.getElementById('editTitle').value = title;
        document.getElementById('editAltText').value = altText;
        document.getElementById('editDescription').value = description;
        document.getElementById('editSortOrder').value = sortOrder;
        document.getElementById('editIsActive').checked = isActive;
    }
    
    showEditModal();
}

function showEditModal() {
    document.getElementById('editImageModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editImageModal').classList.add('hidden');
    currentEditImageId = null;
}

function saveImageEdit() {
    console.log('Save image edit called, currentEditImageId:', currentEditImageId);
    if (!currentEditImageId) {
        console.error('No currentEditImageId set!');
        return;
    }
    
    const imageType = document.getElementById('editImageType').value;
    const title = document.getElementById('editTitle').value;
    const altText = document.getElementById('editAltText').value;
    const description = document.getElementById('editDescription').value;
    const sortOrder = document.getElementById('editSortOrder').value;
    const isActive = document.getElementById('editIsActive').checked;
    
    fetch(`/admin/car-model-images/${currentEditImageId}/update`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            image_type: imageType,
            title: title,
            alt_text: altText,
            description: description,
            sort_order: sortOrder,
            is_active: isActive
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Cập nhật ảnh thành công!', 'success');
            
            // Update UI immediately without reload (BEFORE closing modal)
            console.log('Looking for image with ID:', currentEditImageId);
            const imageContainer = document.querySelector(`[data-image-id="${currentEditImageId}"]`);
            console.log('Found image container:', imageContainer);
            
            if (!imageContainer) {
                console.error('Image container not found! Available containers:');
                document.querySelectorAll('[data-image-id]').forEach(el => {
                    console.log('- Container ID:', el.getAttribute('data-image-id'));
                });
                return;
            }
            
            if (imageContainer) {
                // Update image type badge
                const badgeContainer = imageContainer.querySelector('.flex.items-center.gap-2.mb-2.flex-wrap');
                console.log('Badge container found:', badgeContainer);
                if (badgeContainer) {
                    // Clear all badges and rebuild
                    badgeContainer.innerHTML = '';
                    
                    // Add type badge
                    const typeBadge = document.createElement('span');
                    if (imageType === 'gallery') {
                        typeBadge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
                        typeBadge.innerHTML = '<i class="fas fa-images mr-1"></i>Tổng quan';
                    } else if (imageType === 'exterior') {
                        typeBadge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800';
                        typeBadge.innerHTML = '<i class="fas fa-car mr-1"></i>Ngoại thất';
                    } else if (imageType === 'interior') {
                        typeBadge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800';
                        typeBadge.innerHTML = '<i class="fas fa-couch mr-1"></i>Nội thất';
                    }
                    badgeContainer.appendChild(typeBadge);
                    
                    // Add status badge
                    const statusBadge = document.createElement('span');
                    if (isActive) {
                        statusBadge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800';
                        statusBadge.innerHTML = '<i class="fas fa-eye mr-1"></i>Hiển thị';
                    } else {
                        statusBadge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800';
                        statusBadge.innerHTML = '<i class="fas fa-eye-slash mr-1"></i>Ẩn';
                    }
                    badgeContainer.appendChild(statusBadge);
                    
                    // Add sort order badge
                    const sortBadge = document.createElement('span');
                    sortBadge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700';
                    sortBadge.innerHTML = `<i class="fas fa-sort-numeric-down mr-1"></i>${sortOrder}`;
                    badgeContainer.appendChild(sortBadge);
                    
                    console.log('Badges updated successfully');
                }
                
                // Update title
                const titleElement = imageContainer.querySelector('h4.text-sm.font-medium');
                if (title) {
                    if (titleElement) {
                        titleElement.innerHTML = `<i class="fas fa-heading text-gray-400 mr-1"></i>${title}`;
                        titleElement.title = title;
                    } else {
                        // Create title element if doesn't exist
                        const newTitle = document.createElement('h4');
                        newTitle.className = 'text-sm font-medium text-gray-900 mb-1 truncate';
                        newTitle.title = title;
                        newTitle.innerHTML = `<i class="fas fa-heading text-gray-400 mr-1"></i>${title}`;
                        const badgeDiv = imageContainer.querySelector('.flex.items-center.gap-2.mb-2.flex-wrap');
                        badgeDiv.parentNode.insertBefore(newTitle, badgeDiv.nextSibling);
                    }
                } else if (titleElement) {
                    titleElement.remove();
                }
                
                // Update alt text
                const img = imageContainer.querySelector('img');
                if (img) {
                    img.alt = altText;
                }
                const altElement = imageContainer.querySelector('.text-xs.text-blue-600');
                if (altText) {
                    if (altElement) {
                        altElement.innerHTML = `<i class="fas fa-search text-blue-400 mr-1"></i>${altText}`;
                        altElement.title = altText;
                    } else {
                        // Create alt element if doesn't exist
                        const newAlt = document.createElement('p');
                        newAlt.className = 'text-xs text-blue-600 mb-1 truncate';
                        newAlt.title = altText;
                        newAlt.innerHTML = `<i class="fas fa-search text-blue-400 mr-1"></i>${altText}`;
                        const titleEl = imageContainer.querySelector('h4.text-sm.font-medium') || imageContainer.querySelector('.flex.items-center.gap-2.mb-2.flex-wrap');
                        titleEl.parentNode.insertBefore(newAlt, titleEl.nextSibling);
                    }
                } else if (altElement) {
                    altElement.remove();
                }
                
                // Update description (exclude date element)
                const descriptionElement = imageContainer.querySelector('.text-xs.text-gray-600:not(.text-gray-400)');
                if (description) {
                    if (descriptionElement) {
                        descriptionElement.innerHTML = `<i class="fas fa-align-left text-gray-400 mr-1"></i>${description}`;
                        descriptionElement.title = description;
                    } else {
                        // Create description element if doesn't exist
                        const newDesc = document.createElement('p');
                        newDesc.className = 'text-xs text-gray-600 mb-1 truncate';
                        newDesc.title = description;
                        newDesc.innerHTML = `<i class="fas fa-align-left text-gray-400 mr-1"></i>${description}`;
                        // Insert before the date element
                        const dateElement = imageContainer.querySelector('.text-xs.text-gray-400');
                        if (dateElement) {
                            dateElement.parentNode.insertBefore(newDesc, dateElement);
                        }
                    }
                } else if (descriptionElement) {
                    descriptionElement.remove();
                }
                
                console.log('All updates completed successfully');
            }
            
            // Close modal AFTER updating UI
            closeEditModal();
        } else {
            showMessage('Có lỗi xảy ra khi cập nhật ảnh', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Có lỗi xảy ra khi cập nhật ảnh', 'error');
    });
}

// Set main image
function setMainImage(imageId) {
    fetch(`/admin/car-model-images/${imageId}/set-main`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Đã đặt làm ảnh chính!', 'success');
            
            // Update UI immediately without reload
            // Remove star icon from all images
            document.querySelectorAll('.absolute.-top-1.-right-1').forEach(starContainer => {
                starContainer.remove();
            });
            
            // Add star button back to all non-main images
            document.querySelectorAll('[data-image-id]').forEach(container => {
                const overlay = container.querySelector('.absolute.inset-0');
                if (overlay && container.getAttribute('data-image-id') !== imageId) {
                    // Check if star button doesn't exist
                    if (!overlay.querySelector('.bg-blue-600')) {
                        const starBtn = document.createElement('button');
                        starBtn.type = 'button';
                        starBtn.onclick = () => setMainImage(container.getAttribute('data-image-id'));
                        starBtn.className = 'opacity-0 group-hover:opacity-100 bg-blue-600 text-white rounded-full p-1 text-xs transition-opacity';
                        starBtn.title = 'Đặt làm ảnh chính';
                        starBtn.innerHTML = '<i class="fas fa-star"></i>';
                        overlay.insertBefore(starBtn, overlay.firstChild);
                    }
                }
            });
            
            // Remove star button from new main image and add star icon
            const newMainContainer = document.querySelector(`[data-image-id="${imageId}"]`);
            if (newMainContainer) {
                // Remove star button from overlay
                const starButton = newMainContainer.querySelector('.bg-blue-600');
                if (starButton) {
                    starButton.remove();
                }
                
                // Add star icon to top-right corner
                const imageContainer = newMainContainer.querySelector('.relative.group');
                if (imageContainer) {
                    const starIcon = document.createElement('div');
                    starIcon.className = 'absolute -top-1 -right-1';
                    starIcon.innerHTML = '<span class="inline-flex items-center justify-center w-4 h-4 bg-blue-600 text-white text-xs rounded-full"><i class="fas fa-star"></i></span>';
                    imageContainer.appendChild(starIcon);
                }
            }
        } else {
            showMessage('Có lỗi xảy ra khi đặt ảnh chính', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Có lỗi xảy ra khi đặt ảnh chính', 'error');
    });
}

// Preview new images
function previewNewImages(input) {
    const previewDiv = document.getElementById('newImagePreview');
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
                        onclick="removeNewPreview(this, ${index})"
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
                        <input type="radio" name="new_main_image_index" value="${index}" 
                               class="text-blue-600 focus:ring-blue-500" onchange="updateMainImageBadges(${index})">
                        <span class="text-xs font-medium text-blue-600">Đặt làm ảnh chính</span>
                    </label>
                </div>
                <div class="space-y-2">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Loại ảnh</label>
                            <select name="new_image_types[]" class="w-full text-xs border rounded px-2 py-1">
                                <option value="gallery" ${index === 0 ? 'selected' : ''}>Tổng quan</option>
                                <option value="exterior">Ngoại thất</option>
                                <option value="interior">Nội thất</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Thứ tự</label>
                            <input type="number" name="new_image_sort_orders[]" value="${index + 1}" min="1" 
                                   class="w-full text-xs border rounded px-2 py-1">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Tiêu đề ảnh</label>
                        <input type="text" name="new_image_titles[]" 
                               class="w-full text-xs border rounded px-2 py-1"
                               placeholder="VD: Honda Civic 2024 - Ngoại thất">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Alt text (SEO)</label>
                        <input type="text" name="new_image_alt_texts[]" 
                               class="w-full text-xs border rounded px-2 py-1"
                               placeholder="VD: Hình ảnh ngoại thất Honda Civic 2024">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Mô tả ảnh</label>
                        <textarea name="new_image_descriptions[]" rows="2"
                                  class="w-full text-xs border rounded px-2 py-1"
                                  placeholder="VD: Hình ảnh ngoại thất chi tiết"></textarea>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="new_image_is_active[${index}]" value="1" checked
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


// Remove preview item
function removeNewPreview(button, index) {
    // Remove from list view
    const listItem = button.closest('.bg-white');
    if (listItem) listItem.remove();
    
    // Update file input (this is tricky, might need to rebuild)
    const fileInput = document.getElementById('new_images');
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
        document.getElementById('newImagePreview').classList.add('hidden');
    }
}

// Update image count in header
function updateImageCount() {
    const imageItems = document.querySelectorAll('.image-item[data-image-id]');
    
    // Find the count element by looking for spans that contain "ảnh"
    let countEl = null;
    const spans = document.querySelectorAll('span.text-sm.text-gray-600');
    for (let span of spans) {
        if (span.textContent && span.textContent.includes('ảnh')) {
            countEl = span;
            break;
        }
    }
    
    // Alternative: look in the header area specifically
    if (!countEl) {
        const headerArea = document.querySelector('.flex.items-center.justify-between.mb-4');
        if (headerArea) {
            countEl = headerArea.querySelector('span.text-sm.text-gray-600');
        }
    }
    
    if (countEl) {
        const count = imageItems.length;
        
        if (count > 0) {
            countEl.textContent = `${count} ảnh`;
            countEl.style.display = 'inline';
        } else {
            // Hide the count when no images  
            countEl.style.display = 'none';
        }
    }
}

// Update main image badges for new images
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

// AJAX Form Submission for Edit Form
document.getElementById('carModelEditForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    // Show loading state
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang cập nhật...';
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
            showMessage(data.message || 'Cập nhật dòng xe thành công!', 'success');
            
            // Redirect after delay (2.5s as requested)
            setTimeout(() => {
                window.location.href = data.redirect || '/admin/carmodels';
            }, 2500);
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Handle validation errors (422)
        if (error.status === 422 && error.data && error.data.errors) {
            const errors = error.data.errors;
            let errorMessage = 'Dữ liệu không hợp lệ:\n';
            
            for (const field in errors) {
                errorMessage += `• ${errors[field][0]}\n`;
            }
            
            showMessage(errorMessage, 'error');
        } else if (error.data && error.data.message) {
            showMessage(error.data.message, 'error');
        } else {
            showMessage(error.message || 'Có lỗi xảy ra khi cập nhật dòng xe', 'error');
        }
        
        // Reset button state
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
});

</script>
@endsection
