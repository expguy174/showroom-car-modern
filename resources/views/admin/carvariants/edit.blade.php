@extends('layouts.admin')

@section('title', 'Chỉnh sửa phiên bản xe')

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
                    Cập nhật phiên bản xe: {{ $carvariant->name }}
                </h1>
                <p class="text-sm text-gray-600 mt-1">Quản lý đầy đủ thông tin phiên bản xe</p>
            </div>
            <a href="{{ route('admin.carvariants.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại
            </a>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="border-b border-gray-200">
        <nav class="flex space-x-8 px-6" aria-label="Tabs">
            <button type="button" class="tab-button active py-4 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600" data-tab="basic">
                <i class="fas fa-info-circle mr-2"></i>
                Thông tin cơ bản
            </button>
            <button type="button" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="colors">
                <i class="fas fa-palette mr-2"></i>
                Màu sắc ({{ $carvariant->colors->count() }})
            </button>
            <button type="button" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="specifications">
                <i class="fas fa-cogs mr-2"></i>
                Thông số kỹ thuật ({{ $carvariant->specifications->count() }})
            </button>
            <button type="button" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="features">
                <i class="fas fa-star mr-2"></i>
                Tính năng ({{ $carvariant->featuresRelation->count() }})
            </button>
            <button type="button" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="images">
                <i class="fas fa-images mr-2"></i>
                Hình ảnh ({{ $carvariant->images->count() }})
            </button>
        </nav>
    </div>

    {{-- Tab Content --}}
    <div class="p-6">
        {{-- Basic Information Tab --}}
        <div id="basic-tab" class="tab-content">
            <form id="basicInfoForm" action="{{ route('admin.carvariants.update', $carvariant->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- LEFT COLUMN - Basic Info & Pricing --}}
                    <div class="space-y-6">
                        {{-- Basic Info --}}
                        <div class="bg-gray-50 rounded-lg p-5">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                Thông tin cơ bản
                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="car_model_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Mẫu xe <span class="text-red-500">*</span>
                                    </label>
                                    <select name="car_model_id" id="car_model_id" 
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
>
                                        <option value="">Chọn mẫu xe...</option>
                                        @foreach($carModels as $model)
                                            <option value="{{ $model->id }}" {{ old('car_model_id', $carvariant->car_model_id) == $model->id ? 'selected' : '' }}>
                                                {{ $model->carBrand->name ?? 'Unknown' }} - {{ $model->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tên phiên bản <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                           value="{{ old('name', $carvariant->name) }}" placeholder="Ví dụ: 2.0 CVT, 1.5 Turbo RS...">
                                </div>

                                <div>
                                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                                        Mã SKU
                                    </label>
                                    <input type="text" name="sku" id="sku" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                           value="{{ old('sku', $carvariant->sku) }}" placeholder="Ví dụ: HRV-20CVT-2024">
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả chi tiết</label>
                                    <textarea name="description" id="description" rows="4" 
                                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                              placeholder="Mô tả chi tiết về phiên bản xe...">{{ old('description', $carvariant->description) }}</textarea>
                                </div>

                                <div>
                                    <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả ngắn</label>
                                    <textarea name="short_description" id="short_description" rows="2" 
                                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                              placeholder="Mô tả ngắn gọn cho hiển thị danh sách...">{{ old('short_description', $carvariant->short_description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Pricing --}}
                        <div class="bg-gray-50 rounded-lg p-5">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-dollar-sign text-blue-600 mr-2"></i>
                                Thông tin giá
                            </h3>
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="base_price" class="block text-sm font-medium text-gray-700 mb-2">
                                            Giá niêm yết <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" name="base_price" id="base_price" 
                                                   class="block w-full px-3 py-2 pr-16 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                                   value="{{ old('base_price', number_format($carvariant->base_price, 0, '.', '')) }}" placeholder="0">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">VNĐ</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="current_price" class="block text-sm font-medium text-gray-700 mb-2">
                                            Giá bán hiện tại <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" name="current_price" id="current_price" 
                                                   class="block w-full px-3 py-2 pr-16 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                                   value="{{ old('current_price', number_format($carvariant->current_price, 0, '.', '')) }}" placeholder="0">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">VNĐ</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <input type="hidden" name="is_on_sale" value="0">
                                    <input type="checkbox" name="is_on_sale" id="is_on_sale" value="1" 
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                           {{ old('is_on_sale', $carvariant->is_on_sale) ? 'checked' : '' }}>
                                    <label for="is_on_sale" class="ml-2 block text-sm text-gray-900">
                                        Đang khuyến mãi
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- RIGHT COLUMN - SEO & Settings --}}
                    <div class="space-y-6">
                        {{-- SEO & Marketing --}}
                        <div class="bg-gray-50 rounded-lg p-5">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-search text-blue-600 mr-2"></i>
                                SEO & Marketing
                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                                    <input type="text" name="meta_title" id="meta_title" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                           value="{{ old('meta_title', $carvariant->meta_title) }}" placeholder="Tiêu đề SEO...">
                                </div>

                                <div>
                                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                                    <textarea name="meta_description" id="meta_description" rows="3" 
                                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                              placeholder="Mô tả SEO...">{{ old('meta_description', $carvariant->meta_description) }}</textarea>
                                </div>

                                <div>
                                    <label for="keywords" class="block text-sm font-medium text-gray-700 mb-2">Keywords</label>
                                    <input type="text" name="keywords" id="keywords" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                           value="{{ old('keywords', $carvariant->keywords) }}" placeholder="từ khóa, phân cách, bằng dấu phẩy">
                                </div>
                            </div>
                        </div>

                        {{-- Display Settings --}}
                        <div class="bg-gray-50 rounded-lg p-5">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-cog text-blue-600 mr-2"></i>
                                Cài đặt hiển thị
                            </h3>
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                               {{ old('is_active', $carvariant->is_active) ? 'checked' : '' }}>
                                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                            Hoạt động
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="hidden" name="is_available" value="0">
                                        <input type="checkbox" name="is_available" id="is_available" value="1" 
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                               {{ old('is_available', $carvariant->is_available) ? 'checked' : '' }}>
                                        <label for="is_available" class="ml-2 block text-sm text-gray-900">
                                            Có sẵn
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="hidden" name="is_featured" value="0">
                                        <input type="checkbox" name="is_featured" id="is_featured" value="1" 
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                               {{ old('is_featured', $carvariant->is_featured) ? 'checked' : '' }}>
                                        <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                            Nổi bật
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="hidden" name="is_new_arrival" value="0">
                                        <input type="checkbox" name="is_new_arrival" id="is_new_arrival" value="1" 
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                               {{ old('is_new_arrival', $carvariant->is_new_arrival) ? 'checked' : '' }}>
                                        <label for="is_new_arrival" class="ml-2 block text-sm text-gray-900">
                                            Mới
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="hidden" name="is_bestseller" value="0">
                                        <input type="checkbox" name="is_bestseller" id="is_bestseller" value="1" 
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                               {{ old('is_bestseller', $carvariant->is_bestseller) ? 'checked' : '' }}>
                                        <label for="is_bestseller" class="ml-2 block text-sm text-gray-900">
                                            Bán chạy
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Thứ tự sắp xếp</label>
                                    <input type="number" name="sort_order" id="sort_order" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                           value="{{ old('sort_order', $carvariant->sort_order) }}" placeholder="0">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </form>
        </div>

        {{-- Colors Tab --}}
        <div id="colors-tab" class="tab-content hidden">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-palette text-blue-600 mr-2"></i>
                    Quản lý màu sắc
                </h3>
                <button type="button" id="addColorBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Thêm màu
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4" id="colorsContainer">
                @foreach($carvariant->colors as $color)
                @php
                    $inventory = $carvariant->color_inventory[$color->id] ?? ['quantity' => 0, 'reserved' => 0, 'available' => 0];
                @endphp
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200" 
                     data-color-id="{{ $color->id }}"
                     data-color-name="{{ $color->color_name }}"
                     data-color-code="{{ $color->color_code }}"
                     data-hex-code="{{ $color->hex_code }}"
                     data-color-type="{{ $color->color_type }}"
                     data-availability="{{ $color->availability }}"
                     data-price-adjustment="{{ $color->price_adjustment }}"
                     data-description="{{ $color->description }}"
                     data-sort-order="{{ $color->sort_order ?? 0 }}"
                     data-is-active="{{ $color->is_active ? '1' : '0' }}"
                     data-is-free="{{ $color->is_free ? '1' : '0' }}"
                     data-is-popular="{{ $color->is_popular ? '1' : '0' }}">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            @if($color->hex_code)
                                <div class="w-6 h-6 rounded-full border border-gray-300 mr-2" style="background-color: {{ $color->hex_code }};"></div>
                            @endif
                            <h4 class="font-medium text-gray-900">{{ $color->color_name }}</h4>
                        </div>
                        <button type="button" class="text-red-600 hover:text-red-800 delete-color" data-color-id="{{ $color->id }}">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </div>
                    
                    {{-- Color Info --}}
                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <p><strong>Mã màu:</strong> 
                            @if($color->color_code)
                                {{ $color->color_code }}
                            @else
                                <span class="text-gray-400 italic">Chưa có mã màu</span>
                            @endif
                        </p>
                        <p><strong>Hex:</strong> 
                            @if($color->hex_code)
                                <span class="font-mono text-sm">{{ $color->hex_code }}</span>
                            @else
                                <span class="text-gray-400 italic">Chưa có mã hex</span>
                            @endif
                        </p>
                        <p><strong>RGB:</strong> 
                            @if($color->rgb_code)
                                <span class="font-mono text-sm">{{ $color->rgb_code }}</span>
                            @else
                                <span class="text-gray-400 italic">Chưa có mã RGB</span>
                            @endif
                        </p>
                        <p><strong>Loại:</strong> 
                            @switch($color->color_type)
                                @case('solid') Màu đặc @break
                                @case('metallic') Màu kim loại @break
                                @case('pearlescent') Màu ngọc trai @break
                                @case('matte') Màu nhám @break
                                @case('special') Màu đặc biệt @break
                                @default {{ ucfirst($color->color_type) }}
                            @endswitch
                        </p>
                        <p><strong>Tình trạng:</strong> 
                            @switch($color->availability)
                                @case('standard') Tiêu chuẩn @break
                                @case('optional') Tùy chọn @break
                                @case('limited') Giới hạn @break
                                @case('discontinued') Ngừng sản xuất @break
                                @default {{ ucfirst($color->availability) }}
                            @endswitch
                        </p>
                        <p><strong>Phụ phí:</strong> <span class="text-blue-600 font-medium">{{ $color->price_adjustment > 0 ? '+' : '' }}{{ number_format($color->price_adjustment) }} VNĐ</span></p>
                        <p><strong>Mô tả:</strong> 
                            @if($color->description)
                                {{ $color->description }}
                            @else
                                <span class="text-gray-400 italic">Chưa có mô tả</span>
                            @endif
                        </p>
                    </div>

                    {{-- Inventory Management --}}
                    <div class="border-t pt-4">
                        <h5 class="text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-boxes text-blue-500 mr-1"></i>
                            Quản lý tồn kho
                        </h5>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Tổng số</label>
                                <div class="w-full px-2 py-1 text-sm border border-gray-300 rounded bg-gray-50 text-gray-700 inventory-quantity-display"
                                     data-color-id="{{ $color->id }}">{{ $inventory['quantity'] }}</div>
                                <input type="hidden" name="color_inventory[{{ $color->id }}][quantity]" value="{{ $inventory['quantity'] }}" class="inventory-quantity">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Đã đặt</label>
                                <div class="w-full px-2 py-1 text-sm border border-gray-300 rounded bg-gray-50 text-gray-700 inventory-reserved-display"
                                     data-color-id="{{ $color->id }}">{{ $inventory['reserved'] }}</div>
                                <input type="hidden" name="color_inventory[{{ $color->id }}][reserved]" value="{{ $inventory['reserved'] }}" class="inventory-reserved">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Có sẵn</label>
                                <div class="w-full px-2 py-1 text-sm border border-gray-300 rounded bg-gray-50 text-gray-700 inventory-available-display"
                                     data-color-id="{{ $color->id }}">{{ $inventory['available'] }}</div>
                                <input type="hidden" name="color_inventory[{{ $color->id }}][available]" value="{{ $inventory['available'] }}" class="inventory-available">
                            </div>
                        </div>
                        
                        {{-- Stock Status --}}
                        <div class="mt-3 flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                @if($inventory['available'] > 10)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Còn hàng
                                    </span>
                                @elseif($inventory['available'] > 0)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Sắp hết
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Hết hàng
                                    </span>
                                @endif
                                
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $color->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $color->is_active ? 'Hoạt động' : 'Tạm dừng' }}
                                </span>
                                
                                @if($color->is_free)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Miễn phí</span>
                                @endif
                                
                                @if($color->is_popular)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Phổ biến</span>
                                @endif
                            </div>
                            <button type="button" class="text-blue-600 hover:text-blue-800 edit-color" data-color-id="{{ $color->id }}">
                                <i class="fas fa-edit text-sm mr-1"></i>
                                Sửa
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($carvariant->colors->count() == 0)
                <div class="col-span-full text-center py-8 text-gray-500">
                    <i class="fas fa-palette text-4xl mb-4"></i>
                    <p>Chưa có màu sắc nào. Hãy thêm màu đầu tiên!</p>
                </div>
            @endif
        </div>

        {{-- Specifications Tab --}}
        <div id="specifications-tab" class="tab-content hidden">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-cogs text-blue-600 mr-2"></i>
                    Thông số kỹ thuật
                </h3>
                <button type="button" id="addSpecBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Thêm thông số
                </button>
            </div>

            <div class="space-y-6" id="specificationsContainer">
                @php
                    $specCategories = $carvariant->specifications->groupBy('category');
                @endphp
                
                @foreach($specCategories as $category => $specs)
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-4">
                        @switch($category)
                            @case('engine') Động cơ @break
                            @case('performance') Hiệu suất @break
                            @case('dimensions') Kích thước @break
                            @case('fuel') Nhiên liệu @break
                            @case('transmission') Hộp số @break
                            @case('brake') Phanh @break
                            @case('chassis') Khung gầm @break
                            @case('seating') Ghế ngồi @break
                            @case('safety') An toàn @break
                            @case('comfort') Tiện nghi @break
                            @case('technology') Công nghệ @break
                            @case('warranty') Bảo hành @break
                            @case('wheels') Bánh xe @break
                            @default {{ $category ?: 'Khác' }}
                        @endswitch
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($specs as $spec)
                        <div class="bg-white rounded-lg p-3 border border-gray-200" 
                             data-spec-id="{{ $spec->id }}"
                             data-spec-name="{{ $spec->spec_name }}"
                             data-spec-value="{{ $spec->spec_value }}"
                             data-unit="{{ $spec->unit }}"
                             data-category="{{ $spec->category }}"
                             data-spec-code="{{ $spec->spec_code }}"
                             data-description="{{ $spec->description }}"
                             data-is-important="{{ $spec->is_important ? '1' : '0' }}"
                             data-is-highlighted="{{ $spec->is_highlighted ? '1' : '0' }}"
                             data-sort-order="{{ $spec->sort_order ?? 0 }}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <p class="font-medium text-gray-900">{{ $spec->spec_name }}</p>
                                        @if($spec->spec_code)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-mono bg-gray-100 text-gray-600">
                                                {{ $spec->spec_code }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        {{ $spec->spec_value }}{{ $spec->unit ? ' ' . $spec->unit : '' }}
                                    </p>
                                    @if($spec->description)
                                        <p class="text-xs text-gray-500 mt-1">{{ $spec->description }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($spec->is_important)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Quan trọng
                                        </span>
                                    @endif
                                    @if($spec->is_highlighted)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Nổi bật
                                        </span>
                                    @endif
                                    <button type="button" class="text-blue-600 hover:text-blue-800 edit-spec" data-spec-id="{{ $spec->id }}">
                                        <i class="fas fa-edit text-sm"></i>
                                    </button>
                                    <button type="button" class="text-red-600 hover:text-red-800 delete-spec" data-spec-id="{{ $spec->id }}">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            @if($carvariant->specifications->count() == 0)
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-cogs text-4xl mb-4"></i>
                    <p>Chưa có thông số kỹ thuật nào. Hãy thêm thông số đầu tiên!</p>
                </div>
            @endif
        </div>

        {{-- Features Tab --}}
        <div id="features-tab" class="tab-content hidden">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-star text-blue-600 mr-2"></i>
                    Tính năng nổi bật
                </h3>
                <button type="button" id="addFeatureBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Thêm tính năng
                </button>
            </div>

            <div class="space-y-6" id="featuresContainer">
                @php
                    $featureCategories = $carvariant->featuresRelation->groupBy('category');
                @endphp
                
                @foreach($featureCategories as $category => $features)
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-4">
                        @switch($category)
                            @case('safety') An toàn @break
                            @case('comfort') Tiện nghi @break
                            @case('technology') Công nghệ @break
                            @case('performance') Hiệu suất @break
                            @case('exterior') Ngoại thất @break
                            @case('interior') Nội thất @break
                            @case('entertainment') Giải trí @break
                            @case('convenience') Tiện ích @break
                            @case('wheels') Bánh xe @break
                            @case('audio') Âm thanh @break
                            @case('navigation') Định vị @break
                            @default {{ $category ?: 'Khác' }}
                        @endswitch
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($features as $feature)
                        <div class="bg-white rounded-lg p-3 border border-gray-200" 
                             data-feature-id="{{ $feature->id }}"
                             data-feature-name="{{ $feature->feature_name }}"
                             data-description="{{ $feature->description ?? '' }}"
                             data-feature-code="{{ $feature->feature_code ?? '' }}"
                             data-category="{{ $feature->category }}"
                             data-availability="{{ $feature->availability }}"
                             data-importance="{{ $feature->importance }}"
                             data-price="{{ $feature->price ?? 0 }}"
                             data-is-included="{{ $feature->is_included ? '1' : '0' }}"
                             data-is-active="{{ $feature->is_active ? '1' : '0' }}"
                             data-is-featured="{{ $feature->is_featured ? '1' : '0' }}"
                             data-is-popular="{{ $feature->is_popular ? '1' : '0' }}"
                             data-is-recommended="{{ $feature->is_recommended ? '1' : '0' }}"
                             data-sort-order="{{ $feature->sort_order ?? 0 }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        @if($feature->icon_path)
                                            <i class="{{ $feature->icon_path }} text-blue-600 mr-1"></i>
                                        @endif
                                        <p class="font-medium text-gray-900">{{ $feature->feature_name }}</p>
                                        @if($feature->feature_code)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-mono bg-gray-100 text-gray-600">
                                                {{ $feature->feature_code }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($feature->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $feature->description }}</p>
                                    @endif
                                    @if($feature->price > 0)
                                        <p class="text-sm font-medium text-green-600 mt-1">
                                            +{{ number_format($feature->price, 0, ',', '.') }} VND
                                        </p>
                                    @endif
                                    <div class="flex flex-wrap items-center gap-1 mt-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $feature->availability == 'standard' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $feature->availability == 'standard' ? 'Tiêu chuẩn' : 'Tùy chọn' }}
                                        </span>
                                        @if($feature->is_active)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                Hoạt động
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Tạm dừng
                                            </span>
                                        @endif
                                        @if($feature->is_featured)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Nổi bật
                                            </span>
                                        @endif
                                        @if($feature->is_popular)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                Phổ biến
                                            </span>
                                        @endif
                                        @if($feature->is_recommended)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                Khuyến nghị
                                            </span>
                                        @endif
                                        @switch($feature->importance)
                                            @case('essential')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Thiết yếu
                                                </span>
                                                @break
                                            @case('important')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                    Quan trọng
                                                </span>
                                                @break
                                            @case('luxury')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    Sang trọng
                                                </span>
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="button" class="text-blue-600 hover:text-blue-800 edit-feature" data-feature-id="{{ $feature->id }}">
                                        <i class="fas fa-edit text-sm"></i>
                                    </button>
                                    <button type="button" class="text-red-600 hover:text-red-800 delete-feature" data-feature-id="{{ $feature->id }}">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            @if($carvariant->featuresRelation->count() == 0)
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-star text-4xl mb-4"></i>
                    <p>Chưa có tính năng nào. Hãy thêm tính năng đầu tiên!</p>
                </div>
            @endif
        </div>

        {{-- Images Tab --}}
        <div id="images-tab" class="tab-content hidden">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-images text-blue-600 mr-2"></i>
                    Quản lý hình ảnh
                </h3>
                <div class="space-x-2">
                    <button type="button" id="uploadImagesBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-upload mr-2"></i>
                        Upload hình ảnh
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="imagesContainer">
                @foreach($carvariant->images as $image)
                <div class="relative bg-gray-50 rounded-lg overflow-hidden border border-gray-200" 
                     data-image-id="{{ $image->id }}"
                     data-image-url="{{ $image->image_url }}"
                     data-alt-text="{{ $image->alt_text }}"
                     data-title="{{ $image->title }}"
                     data-image-type="{{ $image->image_type }}"
                     data-angle="{{ $image->angle }}"
                     data-description="{{ $image->description }}"
                     data-sort-order="{{ $image->sort_order }}"
                     data-is-main="{{ $image->is_main ? '1' : '0' }}"
                     data-is-active="{{ $image->is_active ? '1' : '0' }}"
                     data-color-id="{{ $image->car_variant_color_id }}">
                    
                    {{-- Image --}}
                    <div class="relative">
                        <img src="{{ str_starts_with($image->image_url, 'http') ? $image->image_url : asset('storage/' . $image->image_url) }}" 
                             alt="{{ $image->alt_text }}" 
                             class="w-full h-32 object-cover">
                    </div>
                    
                    {{-- Top badges and actions --}}
                    <div class="absolute top-2 left-2 flex flex-wrap gap-1">
                        @if($image->is_main)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-star mr-1"></i>Chính
                            </span>
                        @endif
                    </div>
                    
                    <div class="absolute top-2 right-2 flex space-x-1">
                        <button type="button" class="edit-image bg-white bg-opacity-80 hover:bg-opacity-100 text-blue-600 p-1 rounded" data-image-id="{{ $image->id }}" title="Chỉnh sửa">
                            <i class="fas fa-edit text-xs"></i>
                        </button>
                        <button type="button" class="delete-image bg-red-500 bg-opacity-80 hover:bg-opacity-100 text-white p-1 rounded" data-image-id="{{ $image->id }}" title="Xóa ảnh">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </div>
                    
                    {{-- Image info --}}
                    <div class="p-2">
                        @if($image->title)
                            <p class="text-xs font-medium text-gray-900 truncate mb-1">{{ $image->title }}</p>
                        @endif
                        @if($image->alt_text)
                            <p class="text-xs text-gray-600 truncate">{{ $image->alt_text }}</p>
                        @endif
                        <div class="flex items-center justify-between mt-1">
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    @switch($image->image_type)
                                        @case('gallery') Thư viện @break
                                        @case('interior') Nội thất @break
                                        @case('exterior') Ngoại thất @break
                                        @default {{ ucfirst($image->image_type) }}
                                    @endswitch
                                </span>
                                @if($image->angle)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        @switch($image->angle)
                                            @case('front') Trước @break
                                            @case('side') Bên @break
                                            @case('rear') Sau @break
                                            @case('interior') Trong @break
                                            @case('wheel') Bánh xe @break
                                            @case('headlight') Đèn pha @break
                                            @case('grille') Lưới tản nhiệt @break
                                            @case('dashboard') Bảng điều khiển @break
                                            @case('seats') Ghế ngồi @break
                                            @case('console') Bảng điều khiển giữa @break
                                            @case('trunk') Cốp xe @break
                                            @case('steering') Vô lăng @break
                                            @case('door') Cửa xe @break
                                            @default {{ ucfirst($image->angle) }}
                                        @endswitch
                                    </span>
                                @endif
                            </div>
                            @if($image->is_active)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                    Hiện
                                </span>
                            @else
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                    Ẩn
                                </span>
                            @endif
                        </div>
                        @if($image->description)
                            <p class="text-xs text-gray-500 mt-1 truncate" title="{{ $image->description }}">{{ $image->description }}</p>
                        @endif
                        @if($image->car_variant_color_id)
                            @php
                                $linkedColor = $carvariant->colors->find($image->car_variant_color_id);
                            @endphp
                            @if($linkedColor)
                                <div class="flex items-center mt-1">
                                    <div class="w-3 h-3 rounded-full border border-gray-300 mr-1" style="background-color: {{ $linkedColor->hex_code }}"></div>
                                    <span class="text-xs text-gray-500">{{ $linkedColor->color_name }}</span>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            @if($carvariant->images->count() == 0)
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-images text-4xl mb-4"></i>
                    <p>Chưa có hình ảnh nào. Hãy upload hình ảnh đầu tiên!</p>
                </div>
            @endif
        </div>
    </div>
    
    {{-- Action Buttons - Always Visible --}}
    <div class="border-t border-gray-200 px-6 py-4 mt-8">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.carvariants.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-times mr-2"></i>
                Hủy bỏ
            </a>
            <button type="submit" form="basicInfoForm" id="mainUpdateBtn" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-save mr-2"></i>
                Cập nhật thông tin
            </button>
        </div>
    </div>
</div>

{{-- Image Upload Modal --}}
<div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        {{-- Header --}}
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-upload text-blue-600 mr-2"></i>
                Upload hình ảnh mới
            </h3>
            <button type="button" id="closeUploadModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        {{-- Body --}}
        <div class="px-6 pb-6">
            <form id="uploadForm" class="space-y-4" enctype="multipart/form-data">
                <input type="hidden" name="car_variant_id" value="{{ $carvariant->id }}">
                
                {{-- File Upload --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chọn hình ảnh <span class="text-red-500">*</span></label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                        <input type="file" id="imageFiles" name="images[]" multiple accept="image/*" class="hidden">
                        <div id="uploadArea" class="cursor-pointer" onclick="document.getElementById('imageFiles').click()">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                            <p class="text-lg font-medium text-gray-700 mb-2">Click để chọn hình ảnh</p>
                            <p class="text-sm text-gray-500">Hoặc kéo thả file vào đây</p>
                            <p class="text-xs text-gray-400 mt-2">Hỗ trợ: JPG, PNG, GIF (Tối đa 10MB mỗi file)</p>
                        </div>
                        <div id="selectedFiles" class="mt-4 hidden">
                            <p class="text-sm font-medium text-gray-700 mb-2">Ảnh đã chọn:</p>
                            <div id="fileList" class="space-y-2"></div>
                        </div>
                    </div>
                </div>
                
                
                {{-- Individual Image Settings --}}
                <div id="individualSettings" class="hidden">
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-700">
                            <i class="fas fa-images mr-2 text-blue-600"></i>
                            Cài đặt cho từng ảnh
                        </h4>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-magic mr-1"></i>
                            Thông tin đã được tự động tạo, bạn có thể chỉnh sửa nếu cần
                        </p>
                    </div>
                    <div id="individualImagesList" class="space-y-4">
                        {{-- Will be populated by JavaScript --}}
                    </div>
                </div>
            </form>
        </div>
        
        {{-- Footer --}}
        <div class="flex items-center justify-between px-6 py-4 bg-gray-50 border-t border-gray-200">
            <button type="button" id="cancelUpload"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                Hủy
            </button>
            <button type="submit" form="uploadForm" id="uploadSubmitBtn"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-upload mr-2"></i>
                Upload hình ảnh
            </button>
        </div>
    </div>
</div>

{{-- Image Edit Modal --}}
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        {{-- Header --}}
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 id="imageModalTitle" class="text-lg font-semibold text-gray-900">
                <i class="fas fa-edit text-blue-600 mr-2"></i>
                Chỉnh sửa hình ảnh
            </h3>
            <button type="button" id="closeImageModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        {{-- Body --}}
        <div class="px-6 pb-6">
            <form id="imageForm" class="space-y-4" enctype="multipart/form-data">
                <input type="hidden" id="imageId" name="image_id">
                <input type="hidden" name="car_variant_id" value="{{ $carvariant->id }}">
                
                {{-- Image Preview --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hình ảnh hiện tại</label>
                    <div class="w-full h-48 bg-gray-100 rounded-lg overflow-hidden">
                        <img id="imagePreview" src="" alt="" class="w-full h-full object-cover">
                    </div>
                </div>
                
                {{-- Replace Image --}}
                <div class="mb-4">
                    <label for="replace_image" class="block text-sm font-medium text-gray-700 mb-2">Thay thế hình ảnh (tùy chọn)</label>
                    <input type="file" id="replace_image" name="replace_image" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Chọn file mới nếu muốn thay thế hình ảnh hiện tại</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Title --}}
                    <div>
                        <label for="image_title" class="block text-sm font-medium text-gray-700 mb-1">
                            Tiêu đề 
                            <span class="text-xs text-gray-500">(tự động sinh nếu để trống)</span>
                        </label>
                        <input type="text" id="image_title" name="title" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Để trống để tự động sinh hoặc nhập tiêu đề tùy chỉnh">
                    </div>
                    
                    {{-- Alt Text --}}
                    <div>
                        <label for="image_alt_text" class="block text-sm font-medium text-gray-700 mb-1">
                            Alt Text (SEO) 
                            <span class="text-xs text-gray-500">(tự động sinh nếu để trống)</span>
                        </label>
                        <input type="text" id="image_alt_text" name="alt_text"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Để trống để tự động sinh hoặc nhập mô tả SEO tùy chỉnh">
                    </div>
                    
                    {{-- Image Type --}}
                    <div>
                        <label for="image_type" class="block text-sm font-medium text-gray-700 mb-1">Loại hình ảnh</label>
                        <select id="image_type" name="image_type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="gallery">Thư viện</option>
                            <option value="exterior">Ngoại thất</option>
                            <option value="interior">Nội thất</option>
                        </select>
                    </div>
                    
                    {{-- Angle --}}
                    <div>
                        <label for="image_angle" class="block text-sm font-medium text-gray-700 mb-1">Góc chụp</label>
                        <select id="image_angle" name="angle" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Không xác định</option>
                            <optgroup label="Góc chụp cơ bản">
                                <option value="front">Mặt trước</option>
                                <option value="side">Mặt bên</option>
                                <option value="rear">Mặt sau</option>
                                <option value="interior">Nội thất</option>
                            </optgroup>
                            <optgroup label="Chi tiết ngoại thất">
                                <option value="headlight">Đèn pha</option>
                                <option value="grille">Lưới tản nhiệt</option>
                                <option value="wheel">Bánh xe</option>
                                <option value="door">Cửa xe</option>
                                <option value="trunk">Cốp xe</option>
                            </optgroup>
                            <optgroup label="Chi tiết nội thất">
                                <option value="dashboard">Bảng điều khiển</option>
                                <option value="seats">Ghế ngồi</option>
                                <option value="console">Bảng điều khiển giữa</option>
                                <option value="steering">Vô lăng</option>
                            </optgroup>
                        </select>
                    </div>
                    
                    {{-- Sort Order --}}
                    <div>
                        <label for="image_sort_order" class="block text-sm font-medium text-gray-700 mb-1">Thứ tự hiển thị</label>
                        <input type="number" id="image_sort_order" name="sort_order" value="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0">
                    </div>
                    
                    {{-- Linked Color --}}
                    <div>
                        <label for="image_color_id" class="block text-sm font-medium text-gray-700 mb-1">Liên kết với màu</label>
                        <select id="image_color_id" name="car_variant_color_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Không liên kết</option>
                            @foreach($carvariant->colors as $color)
                                <option value="{{ $color->id }}">{{ $color->color_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                {{-- Description --}}
                <div>
                    <label for="image_description" class="block text-sm font-medium text-gray-700 mb-1">
                        Mô tả chi tiết 
                        <span class="text-xs text-gray-500">(tự động sinh nếu để trống)</span>
                    </label>
                    <textarea id="image_description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Để trống để tự động sinh hoặc nhập mô tả tùy chỉnh"></textarea>
                </div>
                
                {{-- Checkboxes --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="image_is_main" name="is_main" value="1"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="image_is_main" class="ml-2 block text-sm text-gray-700">Ảnh chính</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="image_is_active" name="is_active" value="1" checked
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="image_is_active" class="ml-2 block text-sm text-gray-700">Hiển thị</label>
                    </div>
                </div>
            </form>
        </div>
        
        {{-- Footer --}}
        <div class="flex items-center justify-between px-6 py-4 bg-gray-50 border-t border-gray-200">
            <button type="button" id="cancelImageEdit"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                Hủy
            </button>
            <button type="submit" form="imageForm" id="saveImageBtn"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-save mr-2"></i>
                Lưu hình ảnh
            </button>
        </div>
    </div>
</div>

<script>
// Colors data for JavaScript
const carVariantColors = @json($carvariant->colors);

// Tab functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Add active class to clicked button
            this.classList.add('active', 'border-blue-500', 'text-blue-600');
            this.classList.remove('border-transparent', 'text-gray-500');
            
            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            // Show target tab content
            document.getElementById(targetTab + '-tab').classList.remove('hidden');
        });
    });
    
    // Update images tab counter on page load
    updateImagesTabCounter();

    // Auto-fill current_price when base_price changes
    document.getElementById('base_price').addEventListener('input', function() {
        const currentPriceField = document.getElementById('current_price');
        if (!currentPriceField.value) {
            currentPriceField.value = this.value;
        }
    });

    // AJAX form submission for basic info
    document.getElementById('basicInfoForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        const submitButton = document.getElementById('mainUpdateBtn');
        const originalText = submitButton ? submitButton.innerHTML : 'Cập nhật thông tin';
        
        // Show loading state
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang cập nhật...';
        }
        
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
                showMessage(data.message || 'Cập nhật thông tin thành công!', 'success');
                
                // Delay then redirect to index page
                setTimeout(() => {
                    window.location.href = '{{ route("admin.carvariants.index") }}';
                }, 2000); // 2 second delay
            } else {
                throw new Error(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Handle specific database constraint violations
            if (error.data && error.data.message) {
                let errorMessage = error.data.message;
                
                // Handle duplicate entry for unique constraint
                if (errorMessage.includes('Duplicate entry') && errorMessage.includes('car_variants_car_model_id_name_unique')) {
                    errorMessage = 'Tên phiên bản xe này đã tồn tại cho dòng xe đã chọn. Vui lòng sử dụng tên khác.';
                }
                // Handle other constraint violations
                else if (errorMessage.includes('Duplicate entry') && errorMessage.includes('sku')) {
                    errorMessage = 'Mã SKU này đã tồn tại. Hệ thống sẽ tự động tạo mã SKU mới.';
                }
                // Handle foreign key constraints
                else if (errorMessage.includes('foreign key constraint')) {
                    errorMessage = 'Dữ liệu tham chiếu không hợp lệ. Vui lòng kiểm tra lại thông tin.';
                }
                
                showMessage(errorMessage, 'error');
            }
            // Handle validation errors (422)
            else if (error.status === 422 && error.data && error.data.errors) {
                const errors = error.data.errors;
                let errorMessage = 'Dữ liệu không hợp lệ:\n';
                
                for (const field in errors) {
                    errorMessage += `• ${errors[field][0]}\n`;
                }
                
                showMessage(errorMessage, 'error');
            }
            // Generic error handling
            else {
                showMessage(error.message || 'Có lỗi xảy ra khi cập nhật thông tin. Vui lòng thử lại.', 'error');
            }
        })
        .finally(() => {
            // Reset button state
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
    });

    // Specifications management
    const specModal = document.getElementById('specModal');
    const specForm = document.getElementById('specForm');
    const specIdInput = document.getElementById('specId');
    const specModalTitle = document.getElementById('specModalTitle');
    const specDeleteDialog = document.getElementById('specDeleteDialog');
    let specToDelete = null;
    
    // Open modal for adding new specification
    document.getElementById('addSpecBtn')?.addEventListener('click', function() {
        resetSpecForm();
        specModalTitle.textContent = 'Thêm thông số mới';
        specModal.classList.remove('hidden');
    });
    
    // Close spec modal
    function closeSpecModal() {
        specModal.classList.add('hidden');
        resetSpecForm();
    }
    
    document.getElementById('closeSpecModal')?.addEventListener('click', closeSpecModal);
    document.getElementById('cancelSpecBtn')?.addEventListener('click', closeSpecModal);
    
    // Close modal when clicking outside
    specModal?.addEventListener('click', function(e) {
        if (e.target === specModal) {
            closeSpecModal();
        }
    });
    
    // Delete dialog management
    function closeSpecDeleteDialog() {
        specDeleteDialog.classList.add('hidden');
        specToDelete = null;
    }
    
    document.getElementById('cancelSpecDeleteBtn')?.addEventListener('click', closeSpecDeleteDialog);
    
    // Confirm delete
    document.getElementById('confirmSpecDeleteBtn')?.addEventListener('click', function() {
        if (specToDelete) {
            performDeleteSpec(specToDelete);
        }
    });
    
    // Close delete dialog when clicking outside
    specDeleteDialog?.addEventListener('click', function(e) {
        if (e.target === specDeleteDialog) {
            closeSpecDeleteDialog();
        }
    });
    
    // ESC key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!specModal.classList.contains('hidden')) {
                closeSpecModal();
            }
            if (!specDeleteDialog.classList.contains('hidden')) {
                closeSpecDeleteDialog();
            }
        }
    });
    
    // Reset spec form
    function resetSpecForm() {
        specForm.reset();
        specIdInput.value = '';
    }
    
    // Edit specification function
    function editSpec(specId) {
        // Find spec data from existing DOM
        const specCard = document.querySelector(`[data-spec-id="${specId}"]`);
        if (!specCard) return;
        
        // Get data from data attributes
        const specName = specCard.dataset.specName;
        const specValue = specCard.dataset.specValue;
        const unit = specCard.dataset.unit || '';
        let category = specCard.dataset.category || 'other';
        const specCode = specCard.dataset.specCode || '';
        const description = specCard.dataset.description || '';
        const isImportant = specCard.dataset.isImportant === '1';
        const isHighlighted = specCard.dataset.isHighlighted === '1';
        const sortOrder = specCard.dataset.sortOrder || '0';
        
        // Get category from parent section
        const categorySection = specCard.closest('.bg-gray-50');
        if (categorySection) {
            const categoryTitle = categorySection.querySelector('h4').textContent.trim();
            // Reverse map category title to value
            const categoryReverseMap = {
                'Động cơ': 'engine',
                'Hiệu suất': 'performance',
                'Kích thước': 'dimensions',
                'Nhiên liệu': 'fuel',
                'Hộp số': 'transmission',
                'Phanh': 'brake',
                'Khung gầm': 'chassis',
                'Ghế ngồi': 'seating',
                'An toàn': 'safety',
                'Tiện nghi': 'comfort',
                'Công nghệ': 'technology',
                'Bảo hành': 'warranty',
                'Bánh xe': 'wheels'
            };
            category = categoryReverseMap[categoryTitle] || 'other';
        }
        
        // Fill form
        specIdInput.value = specId;
        document.getElementById('spec_name').value = specName;
        document.getElementById('spec_value').value = specValue;
        document.getElementById('unit').value = unit;
        document.getElementById('category').value = category;
        document.getElementById('spec_code').value = specCode;
        document.getElementById('spec_description').value = description;
        document.getElementById('is_important').checked = isImportant;
        document.getElementById('is_highlighted').checked = isHighlighted;
        document.getElementById('sort_order_spec').value = sortOrder;
        
        // Update modal title and show
        specModalTitle.textContent = 'Chỉnh sửa thông số';
        specModal.classList.remove('hidden');
    }
    
    // Event delegation for edit buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.edit-spec')) {
            const specId = e.target.closest('.edit-spec').dataset.specId;
            editSpec(specId);
        }
        
        if (e.target.closest('.delete-spec')) {
            const specId = e.target.closest('.delete-spec').dataset.specId;
            const specCard = document.querySelector(`[data-spec-id="${specId}"]`);
            const specName = specCard.querySelector('.font-medium').textContent.trim();
            
            // Show delete confirmation dialog
            specToDelete = specId;
            document.getElementById('specDeleteMessage').textContent = 
                `Bạn có chắc chắn muốn xóa thông số "${specName}" không? Hành động này không thể hoàn tác.`;
            specDeleteDialog.classList.remove('hidden');
        }
    });
    
    // Perform delete specification with API call
    function performDeleteSpec(specId) {
        const confirmBtn = document.getElementById('confirmSpecDeleteBtn');
        const originalText = confirmBtn.innerHTML;
        const carVariantId = document.querySelector('input[name="car_variant_id"]').value;
        
        // Show loading state
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xóa...';
        
        // Make API call to delete specification
        fetch(`/admin/carvariants/${carVariantId}/specifications/${specId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
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
                // Remove from DOM
                const specCard = document.querySelector(`[data-spec-id="${specId}"]`);
                if (specCard) {
                    const categorySection = specCard.closest('.bg-gray-50');
                    specCard.remove();
                    
                    // Check if category section is empty
                    if (categorySection && categorySection.querySelectorAll('[data-spec-id]').length === 0) {
                        categorySection.remove();
                    }
                    
                    // Check if no specs left
                    const specsContainer = document.getElementById('specificationsContainer');
                    if (specsContainer.children.length === 0) {
                        specsContainer.innerHTML = `
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-cogs text-4xl mb-4"></i>
                                <p>Chưa có thông số kỹ thuật nào. Hãy thêm thông số đầu tiên!</p>
                            </div>
                        `;
                    }
                }
                
                showMessage(data.message, 'success');
                closeSpecDeleteDialog();
                
                // Update tab count after DOM update
                setTimeout(() => {
                    updateSpecTabCount();
                }, 10);
            } else {
                showMessage(data.message || 'Có lỗi xảy ra khi xóa thông số', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (error.data && error.data.message) {
                showMessage(error.data.message, 'error');
            } else {
                showMessage('Có lỗi xảy ra khi xóa thông số', 'error');
            }
        })
        .finally(() => {
            // Reset button state
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = originalText;
        });
    }
    
    // Category mapping for display
    const categoryMap = {
        'engine': 'Động cơ',
        'performance': 'Hiệu suất', 
        'dimensions': 'Kích thước',
        'fuel': 'Nhiên liệu',
        'transmission': 'Hộp số',
        'brake': 'Phanh',
        'chassis': 'Khung gầm',
        'seating': 'Ghế ngồi',
        'safety': 'An toàn',
        'comfort': 'Tiện nghi',
        'technology': 'Công nghệ',
        'warranty': 'Bảo hành',
        'wheels': 'Bánh xe',
        'other': 'Khác'
    };
    
    // Handle spec form submission
    specForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const isEdit = specIdInput.value !== '';
        
        // Validation
        const specName = document.getElementById('spec_name').value.trim();
        if (!specName) {
            showMessage('Vui lòng nhập tên thông số', 'error');
            document.getElementById('spec_name').focus();
            return;
        }
        
        const specValue = document.getElementById('spec_value').value.trim();
        if (!specValue) {
            showMessage('Vui lòng nhập giá trị thông số', 'error');
            document.getElementById('spec_value').focus();
            return;
        }
        
        // Get form data
        const formData = {
            spec_name: specName,
            spec_value: specValue,
            unit: document.getElementById('unit').value.trim(),
            category: document.getElementById('category').value,
            spec_code: document.getElementById('spec_code').value.trim(),
            description: document.getElementById('spec_description').value.trim(),
            is_important: document.getElementById('is_important').checked ? 1 : 0,
            is_highlighted: document.getElementById('is_highlighted').checked ? 1 : 0,
            sort_order: parseInt(document.getElementById('sort_order_spec').value) || 0
        };
        
        // Get submit button for loading state
        const saveBtn = document.querySelector('button[form="specForm"]');
        const originalText = saveBtn.innerHTML;
        const carVariantId = document.querySelector('input[name="car_variant_id"]').value;
        
        // Show loading state
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang lưu...';
        
        // Prepare API call
        const url = isEdit ? 
            `/admin/carvariants/${carVariantId}/specifications/${specIdInput.value}` :
            `/admin/carvariants/${carVariantId}/specifications`;
        const method = isEdit ? 'PUT' : 'POST';
        
        // Make API call
        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
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
                // Update DOM with real data from server
                if (isEdit) {
                    updateSpecInDOM(specIdInput.value, data.specification);
                } else {
                    addSpecToDOM(data.specification);
                }
                
                showMessage(data.message, 'success');
                closeSpecModal();
                
                // Update tab count after DOM update
                setTimeout(() => {
                    updateSpecTabCount();
                }, 10);
            } else {
                showMessage(data.message || 'Có lỗi xảy ra khi lưu thông số', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (error.data && error.data.errors) {
                // Handle validation errors
                const firstError = Object.values(error.data.errors)[0][0];
                showMessage(firstError, 'error');
            } else if (error.data && error.data.message) {
                showMessage(error.data.message, 'error');
            } else {
                showMessage('Có lỗi xảy ra khi lưu thông số', 'error');
            }
        })
        .finally(() => {
            // Reset button state
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        });
    });
    
    // Add new spec to DOM
    function addSpecToDOM(specData) {
        const specsContainer = document.getElementById('specificationsContainer');
        
        // Remove empty state if exists
        const emptyMessage = specsContainer.querySelector('.text-center.py-8');
        if (emptyMessage) {
            emptyMessage.remove();
        }
        
        // Find or create category section
        const categoryName = categoryMap[specData.category] || 'Khác';
        let categorySection = Array.from(specsContainer.children).find(section => {
            const title = section.querySelector('h4');
            return title && title.textContent.trim() === categoryName;
        });
        
        if (!categorySection) {
            // Create new category section
            categorySection = document.createElement('div');
            categorySection.className = 'bg-gray-50 rounded-lg p-4';
            categorySection.innerHTML = `
                <h4 class="font-medium text-gray-900 mb-4">${categoryName}</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
            `;
            specsContainer.appendChild(categorySection);
        }
        
        // Create spec card with real ID from server
        const specCard = createSpecCardHtml(specData.id, specData);
        const grid = categorySection.querySelector('.grid');
        grid.insertAdjacentHTML('beforeend', specCard);
    }
    
    // Update existing spec in DOM
    function updateSpecInDOM(specId, specData) {
        const specCard = document.querySelector(`[data-spec-id="${specId}"]`);
        if (!specCard) return;
        
        // Update spec card content
        const newSpecCard = createSpecCardHtml(specId, specData);
        specCard.outerHTML = newSpecCard;
    }
    
    // Create spec card HTML
    function createSpecCardHtml(specId, specData) {
        const valueWithUnit = specData.spec_value + (specData.unit ? ' ' + specData.unit : '');
        const specCodeHtml = specData.spec_code ? 
            `<span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-mono bg-gray-100 text-gray-600">${specData.spec_code}</span>` : '';
        const descriptionHtml = specData.description ? 
            `<p class="text-xs text-gray-500 mt-1">${specData.description}</p>` : '';
        const importantBadge = specData.is_important ? 
            `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Quan trọng</span>` : '';
        const highlightedBadge = specData.is_highlighted ? 
            `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Nổi bật</span>` : '';
        
        return `
            <div class="bg-white rounded-lg p-3 border border-gray-200" 
                 data-spec-id="${specId}"
                 data-spec-name="${specData.spec_name}"
                 data-spec-value="${specData.spec_value}"
                 data-unit="${specData.unit || ''}"
                 data-category="${specData.category}"
                 data-spec-code="${specData.spec_code || ''}"
                 data-description="${specData.description || ''}"
                 data-is-important="${specData.is_important ? '1' : '0'}"
                 data-is-highlighted="${specData.is_highlighted ? '1' : '0'}"
                 data-sort-order="${specData.sort_order || 0}">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <p class="font-medium text-gray-900">${specData.spec_name}</p>
                            ${specCodeHtml}
                        </div>
                        <p class="text-sm text-gray-600">${valueWithUnit}</p>
                        ${descriptionHtml}
                    </div>
                    <div class="flex items-center space-x-2">
                        ${importantBadge}
                        ${highlightedBadge}
                        <button type="button" class="text-blue-600 hover:text-blue-800 edit-spec" data-spec-id="${specId}">
                            <i class="fas fa-edit text-sm"></i>
                        </button>
                        <button type="button" class="text-red-600 hover:text-red-800 delete-spec" data-spec-id="${specId}">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    // Features management
    const featureModal = document.getElementById('featureModal');
    const featureForm = document.getElementById('featureForm');
    const featureIdInput = document.getElementById('featureId');
    const featureModalTitle = document.getElementById('featureModalTitle');
    const featureDeleteDialog = document.getElementById('featureDeleteDialog');
    
    // Event delegation for feature cards (handles dynamically added elements)
    document.addEventListener('click', function(e) {
        // Handle edit feature button clicks
        if (e.target.closest('.edit-feature')) {
            e.preventDefault();
            const featureCard = e.target.closest('[data-feature-id]');
            if (featureCard) {
                const featureId = featureCard.getAttribute('data-feature-id');
                editFeature(featureId);
            }
        }
        
        // Handle delete feature button clicks
        if (e.target.closest('.delete-feature')) {
            e.preventDefault();
            const featureCard = e.target.closest('[data-feature-id]');
            if (featureCard) {
                const featureId = featureCard.getAttribute('data-feature-id');
                const featureName = featureCard.querySelector('.font-medium').textContent.trim();
                showFeatureDeleteDialog(featureId, featureName);
            }
        }
    });
    let featureToDelete = null;
    
    // Open modal for adding new feature
    document.getElementById('addFeatureBtn')?.addEventListener('click', function() {
        resetFeatureForm();
        featureModalTitle.textContent = 'Thêm tính năng mới';
        featureModal.classList.remove('hidden');
    });
    
    // Close feature modal
    function closeFeatureModal() {
        featureModal.classList.add('hidden');
        resetFeatureForm();
    }
    
    document.getElementById('closeFeatureModal')?.addEventListener('click', closeFeatureModal);
    document.getElementById('cancelFeatureBtn')?.addEventListener('click', closeFeatureModal);
    
    // Close modal when clicking outside
    featureModal?.addEventListener('click', function(e) {
        if (e.target === featureModal) {
            closeFeatureModal();
        }
    });
    
    // Delete dialog management
    function closeFeatureDeleteDialog() {
        featureDeleteDialog.classList.add('hidden');
        featureToDelete = null;
    }
    
    document.getElementById('cancelFeatureDeleteBtn')?.addEventListener('click', closeFeatureDeleteDialog);
    
    // Confirm delete
    document.getElementById('confirmFeatureDeleteBtn')?.addEventListener('click', function() {
        if (featureToDelete) {
            performDeleteFeature(featureToDelete);
        }
    });
    
    // Close delete dialog when clicking outside
    featureDeleteDialog?.addEventListener('click', function(e) {
        if (e.target === featureDeleteDialog) {
            closeFeatureDeleteDialog();
        }
    });
    
    // ESC key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!featureModal.classList.contains('hidden')) {
                closeFeatureModal();
            }
            if (!featureDeleteDialog.classList.contains('hidden')) {
                closeFeatureDeleteDialog();
            }
        }
    });
    
    // Reset feature form
    function resetFeatureForm() {
        featureForm.reset();
        featureIdInput.value = '';
        // Reset checkboxes to default
        document.getElementById('is_included').checked = true;
        document.getElementById('is_active').checked = true;
        document.getElementById('is_featured').checked = false;
        document.getElementById('is_popular').checked = false;
        document.getElementById('is_recommended').checked = false;
    }
    
    // Category mapping for features
    const featureCategoryMap = {
        'safety': 'An toàn',
        'comfort': 'Tiện nghi',
        'technology': 'Công nghệ',
        'performance': 'Hiệu suất',
        'exterior': 'Ngoại thất',
        'interior': 'Nội thất',
        'entertainment': 'Giải trí',
        'convenience': 'Tiện ích',
        'wheels': 'Bánh xe',
        'audio': 'Âm thanh',
        'navigation': 'Định vị'
    };
    
    // Event delegation for feature edit/delete buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.edit-feature')) {
            const featureId = e.target.closest('.edit-feature').dataset.featureId;
            editFeature(featureId);
        }
        
        if (e.target.closest('.delete-feature')) {
            const featureId = e.target.closest('.delete-feature').dataset.featureId;
            const featureCard = document.querySelector(`[data-feature-id="${featureId}"]`);
            const featureName = featureCard.querySelector('.font-medium').textContent.trim();
            
            // Show delete confirmation dialog
            featureToDelete = featureId;
            document.getElementById('featureDeleteMessage').textContent = 
                `Bạn có chắc chắn muốn xóa tính năng "${featureName}" không? Hành động này không thể hoàn tác.`;
            featureDeleteDialog.classList.remove('hidden');
        }
    });
    
    // Edit feature function
    function editFeature(featureId) {
        const featureCard = document.querySelector(`[data-feature-id="${featureId}"]`);
        if (!featureCard) return;
        
        // Extract data from data attributes (much more reliable than parsing text)
        const featureName = featureCard.dataset.featureName;
        const featureCode = featureCard.dataset.featureCode || '';
        const description = featureCard.dataset.description || '';
        const category = featureCard.dataset.category;
        const availability = featureCard.dataset.availability;
        const importance = featureCard.dataset.importance;
        const price = parseFloat(featureCard.dataset.price) || 0;
        const isIncluded = featureCard.dataset.isIncluded === '1';
        const isActive = featureCard.dataset.isActive === '1';
        const isFeatured = featureCard.dataset.isFeatured === '1';
        const isPopular = featureCard.dataset.isPopular === '1';
        const isRecommended = featureCard.dataset.isRecommended === '1';
        const sortOrder = parseInt(featureCard.dataset.sortOrder) || 0;
        
        // Fill form with data from attributes
        featureIdInput.value = featureId;
        document.getElementById('feature_name').value = featureName;
        document.getElementById('feature_description').value = description;
        document.getElementById('feature_code').value = featureCode;
        document.getElementById('feature_category').value = category;
        document.getElementById('feature_availability').value = availability;
        document.getElementById('importance').value = importance;
        document.getElementById('price').value = price;
        document.getElementById('is_included').checked = isIncluded;
        document.getElementById('feature_is_active').checked = isActive;
        document.getElementById('feature_is_featured').checked = isFeatured;
        document.getElementById('feature_is_popular').checked = isPopular;
        document.getElementById('is_recommended').checked = isRecommended;
        document.getElementById('sort_order_feature').value = sortOrder;
        
        // Update modal title and show
        featureModalTitle.textContent = 'Chỉnh sửa tính năng';
        featureModal.classList.remove('hidden');
    }
    
    // Handle feature form submission (like specifications)
    featureForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const featureName = document.getElementById('feature_name').value.trim();
        if (!featureName) {
            showMessage('Vui lòng nhập tên tính năng', 'error');
            return;
        }
        
        const isEdit = featureIdInput.value !== '';
        const carVariantId = document.querySelector('input[name="car_variant_id"]').value;
        const url = isEdit ? 
            `/admin/carvariants/${carVariantId}/features/${featureIdInput.value}` :
            `/admin/carvariants/${carVariantId}/features`;
        
        // Get submit button for loading state (like specifications)
        const saveBtn = document.querySelector('button[form="featureForm"]');
        const originalText = saveBtn.innerHTML;
        
        // Show loading state
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang lưu...';
        
        const formData = {
            feature_name: featureName,
            description: document.getElementById('feature_description').value.trim() || null,
            feature_code: document.getElementById('feature_code').value.trim() || null,
            category: document.getElementById('feature_category').value,
            availability: document.getElementById('feature_availability').value,
            importance: document.getElementById('importance').value,
            price: parseFloat(document.getElementById('price').value) || 0,
            is_included: document.getElementById('is_included').checked ? 1 : 0,
            is_active: document.getElementById('feature_is_active').checked ? 1 : 0,
            is_featured: document.getElementById('feature_is_featured').checked ? 1 : 0,
            is_popular: document.getElementById('feature_is_popular').checked ? 1 : 0,
            is_recommended: document.getElementById('is_recommended').checked ? 1 : 0,
            sort_order: parseInt(document.getElementById('sort_order_feature').value) || 0
        };
        
        
        
        
        fetch(url, {
            method: isEdit ? 'PUT' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    try {
                        const errorData = JSON.parse(text);
                        const error = new Error(`HTTP ${response.status}`);
                        error.status = response.status;
                        error.data = errorData;
                        throw error;
                    } catch (parseError) {
                        if (parseError.data) {
                            throw parseError;
                        } else {
                            throw new Error(`HTTP ${response.status}: ${text}`);
                        }
                    }
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                
                // Update DOM immediately
                if (isEdit) {
                    updateFeatureInDOM(featureIdInput.value, data.feature);
                } else {
                    addFeatureToDOM(data.feature);
                }
                
                closeFeatureModal();
                
                // Update tab count
                setTimeout(() => {
                    updateFeatureTabCount();
                }, 100);
            } else {
                showMessage(data.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .finally(() => {
            // Reset button state
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        })
        .catch(error => {
            // Handle different types of errors
            if (error.data && error.data.message) {
                // Server validation error (422) or other API errors
                showMessage(error.data.message, 'error');
            } else if (error.data && error.data.errors) {
                // Laravel validation errors
                const firstError = Object.values(error.data.errors)[0][0];
                showMessage(firstError, 'error');
            } else {
                // Network or other errors
                showMessage('Có lỗi xảy ra khi lưu tính năng', 'error');
            }
        });
    });
    
    // Perform delete feature with API call
    function performDeleteFeature(featureId) {
        const confirmBtn = document.getElementById('confirmFeatureDeleteBtn');
        const originalText = confirmBtn.innerHTML;
        const carVariantId = document.querySelector('input[name="car_variant_id"]').value;
        
        // Show loading state
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xóa...';
        
        // Make API call to delete feature
        fetch(`/admin/carvariants/${carVariantId}/features/${featureId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
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
                // Remove from DOM
                const featureCard = document.querySelector(`[data-feature-id="${featureId}"]`);
                if (featureCard) {
                    const categorySection = featureCard.closest('.bg-gray-50');
                    featureCard.remove();
                    
                    // Check if category section is empty
                    if (categorySection && categorySection.querySelectorAll('[data-feature-id]').length === 0) {
                        categorySection.remove();
                    }
                    
                    // Check if no features left
                    const featuresContainer = document.getElementById('featuresContainer');
                    if (featuresContainer.children.length === 0) {
                        featuresContainer.innerHTML = `
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-star text-4xl mb-4"></i>
                                <p>Chưa có tính năng nào. Hãy thêm tính năng đầu tiên!</p>
                            </div>
                        `;
                    }
                }
                
                showMessage(data.message, 'success');
                closeFeatureDeleteDialog();
                
                // Update tab count after DOM update
                setTimeout(() => {
                    updateFeatureTabCount();
                }, 10);
            } else {
                showMessage(data.message || 'Có lỗi xảy ra khi xóa tính năng', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (error.data && error.data.message) {
                showMessage(error.data.message, 'error');
            } else {
                showMessage('Có lỗi xảy ra khi xóa tính năng', 'error');
            }
        })
        .finally(() => {
            // Reset button state
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = originalText;
        });
    }
    
    // Add new feature to DOM
    function addFeatureToDOM(featureData) {
        const featuresContainer = document.getElementById('featuresContainer');
        
        // Remove empty state if exists
        const emptyMessage = featuresContainer.querySelector('.text-center.py-8');
        if (emptyMessage) {
            emptyMessage.remove();
        }
        
        // Find or create category section
        const categoryName = featureCategoryMap[featureData.category] || 'Khác';
        let categorySection = Array.from(featuresContainer.children).find(section => {
            const title = section.querySelector('h4');
            return title && title.textContent.trim() === categoryName;
        });
        
        if (!categorySection) {
            // Create new category section
            categorySection = document.createElement('div');
            categorySection.className = 'bg-gray-50 rounded-lg p-4';
            categorySection.innerHTML = `
                <h4 class="font-medium text-gray-900 mb-4">${categoryName}</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
            `;
            featuresContainer.appendChild(categorySection);
        }
        
        // Create feature card with real ID from server
        const featureCard = createFeatureCardHtml(featureData.id, featureData);
        const grid = categorySection.querySelector('.grid');
        grid.insertAdjacentHTML('beforeend', featureCard);
    }
    
    // Update existing feature in DOM - handle category changes
    function updateFeatureInDOM(featureId, featureData) {
        const oldFeatureCard = document.querySelector(`[data-feature-id="${featureId}"]`);
        if (!oldFeatureCard) {
            return;
        }
        
        // Remove old card first
        const oldCategorySection = oldFeatureCard.closest('.bg-gray-50');
        oldFeatureCard.remove();
        
        // Check if old category section is now empty
        if (oldCategorySection) {
            const remainingCards = oldCategorySection.querySelectorAll('[data-feature-id]');
            if (remainingCards.length === 0) {
                oldCategorySection.remove();
            }
        }
        
        // Add to correct category (reuse addFeatureToDOM logic)
        addFeatureToDOM(featureData);
    }
    
    // Create feature card HTML
    function createFeatureCardHtml(featureId, featureData) {
        const featureCodeHtml = featureData.feature_code ? 
            `<span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-mono bg-gray-100 text-gray-600">${featureData.feature_code}</span>` : '';
        const descriptionHtml = featureData.description ? 
            `<p class="text-sm text-gray-600 mt-1">${featureData.description}</p>` : '';
        const priceHtml = featureData.price > 0 ? 
            `<p class="text-sm font-medium text-green-600 mt-1">+${new Intl.NumberFormat('vi-VN').format(featureData.price)} VND</p>` : '';
        
        const availabilityBadge = featureData.availability === 'standard' ? 
            `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Tiêu chuẩn</span>` :
            `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Tùy chọn</span>`;
        
        // Helper function to check boolean/number values
        const isTrue = (value) => value === true || value === 1;
        
        
        const activeBadge = isTrue(featureData.is_active) ? 
            `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Hoạt động</span>` : 
            `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Tạm dừng</span>`;
        const featuredBadge = isTrue(featureData.is_featured) ? 
            `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Nổi bật</span>` : '';
        const popularBadge = isTrue(featureData.is_popular) ? 
            `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Phổ biến</span>` : '';
        const recommendedBadge = isTrue(featureData.is_recommended) ? 
            `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Khuyến nghị</span>` : '';
        
        let importanceBadge = '';
        switch(featureData.importance) {
            case 'essential':
                importanceBadge = `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Thiết yếu</span>`;
                break;
            case 'important':
                importanceBadge = `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Quan trọng</span>`;
                break;
            case 'luxury':
                importanceBadge = `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">Sang trọng</span>`;
                break;
            case 'nice_to_have':
                importanceBadge = `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Tốt nếu có</span>`;
                break;
        }
        
        return `
            <div class="bg-white rounded-lg p-3 border border-gray-200" 
                 data-feature-id="${featureId}"
                 data-feature-name="${featureData.feature_name}"
                 data-description="${featureData.description || ''}"
                 data-feature-code="${featureData.feature_code || ''}"
                 data-category="${featureData.category}"
                 data-availability="${featureData.availability}"
                 data-importance="${featureData.importance}"
                 data-price="${featureData.price || 0}"
                 data-is-included="${isTrue(featureData.is_included) ? '1' : '0'}"
                 data-is-active="${isTrue(featureData.is_active) ? '1' : '0'}"
                 data-is-featured="${isTrue(featureData.is_featured) ? '1' : '0'}"
                 data-is-popular="${isTrue(featureData.is_popular) ? '1' : '0'}"
                 data-is-recommended="${isTrue(featureData.is_recommended) ? '1' : '0'}"
                 data-sort-order="${featureData.sort_order || 0}">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <p class="font-medium text-gray-900">${featureData.feature_name}</p>
                            ${featureCodeHtml}
                        </div>
                        ${descriptionHtml}
                        ${priceHtml}
                        <div class="flex flex-wrap items-center gap-1 mt-2">
                            ${availabilityBadge}
                            ${activeBadge}
                            ${featuredBadge}
                            ${popularBadge}
                            ${recommendedBadge}
                            ${importanceBadge}
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" class="text-blue-600 hover:text-blue-800 edit-feature" data-feature-id="${featureId}">
                            <i class="fas fa-edit text-sm"></i>
                        </button>
                        <button type="button" class="text-red-600 hover:text-red-800 delete-feature" data-feature-id="${featureId}">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    // Upload Modal Management
    const uploadModal = document.getElementById('uploadModal');
    const uploadForm = document.getElementById('uploadForm');
    const imageFiles = document.getElementById('imageFiles');
    const selectedFiles = document.getElementById('selectedFiles');
    const fileList = document.getElementById('fileList');
    
    // Open upload modal
    document.getElementById('uploadImagesBtn')?.addEventListener('click', function() {
        uploadModal.classList.remove('hidden');
    });
    
    // Close upload modal
    function closeUploadModal() {
        uploadModal.classList.add('hidden');
        uploadForm.reset();
        selectedFiles.classList.add('hidden');
        fileList.innerHTML = '';
        // Reset individual settings
        document.getElementById('individualSettings').classList.add('hidden');
        document.getElementById('individualImagesList').innerHTML = '';
    }
    
    document.getElementById('closeUploadModal')?.addEventListener('click', closeUploadModal);
    document.getElementById('cancelUpload')?.addEventListener('click', closeUploadModal);
    
    // Close upload modal when clicking outside
    uploadModal?.addEventListener('click', function(e) {
        if (e.target === uploadModal) {
            closeUploadModal();
        }
    });
    
    // Handle remove file button clicks and main image toggle
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-file-btn')) {
            const fileIndex = parseInt(e.target.closest('.remove-file-btn').dataset.fileIndex);
            console.log('Removing file at index:', fileIndex); // Debug
            removeFile(fileIndex);
        }
    });
    
    // Handle main image checkbox toggle and customization level
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('individual-main-checkbox')) {
            const selectedIndex = parseInt(e.target.dataset.mainIndex);
            handleMainImageToggle(selectedIndex);
        }
        
        // No batch operations needed
    });
    
    // Handle file selection
    imageFiles?.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        if (files.length > 0) {
            displaySelectedFiles(files);
        }
    });
    
    // Display selected files
    function displaySelectedFiles(files) {
        fileList.innerHTML = '';
        selectedFiles.classList.remove('hidden');
        
        files.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-2 bg-gray-100 rounded';
            fileItem.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-image text-blue-500 mr-2"></i>
                    <span class="text-sm text-gray-700">${file.name}</span>
                    <span class="text-xs text-gray-500 ml-2">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                </div>
                <button type="button" class="remove-file-btn text-red-500 hover:text-red-700" data-file-index="${index}">
                    <i class="fas fa-times"></i>
                </button>
            `;
            fileList.appendChild(fileItem);
        });
        
        // Always show individual settings for any files
        if (files.length > 0) {
            showIndividualSettings(files);
        } else {
            document.getElementById('individualSettings').classList.add('hidden');
        }
    }
    
    // Show individual settings for each file
    function showIndividualSettings(files) {
        const individualSettings = document.getElementById('individualSettings');
        const individualImagesList = document.getElementById('individualImagesList');
        
        individualSettings.classList.remove('hidden');
        individualImagesList.innerHTML = '';
        
        files.forEach((file, index) => {
            // Generate smart defaults
            const fileName = file.name.replace(/\.[^/.]+$/, ''); // Remove extension
            const cleanFileName = fileName.replace(/[_-]/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            
            // Smart title generation
            const defaultTitle = `{{ $carvariant->name }} - ${cleanFileName}`;
            
            // Smart alt text for SEO
            const defaultAlt = `Hình ảnh ${cleanFileName} của {{ $carvariant->name }} {{ $carvariant->carModel->carBrand->name }} - Ảnh ${index + 1}`;
            
            // Smart description
            const defaultDescription = `Ảnh chi tiết ${cleanFileName} của {{ $carvariant->name }}, thể hiện đầy đủ thiết kế và tính năng của xe.`;
            
            const settingItem = document.createElement('div');
            settingItem.className = 'border border-gray-200 rounded-lg p-4 bg-white shadow-sm';
            settingItem.innerHTML = `
                <div class="flex items-center justify-between mb-3">
                    <h5 class="text-sm font-medium text-gray-900 flex items-center">
                        <i class="fas fa-image text-blue-500 mr-2"></i>
                        ${file.name}
                    </h5>
                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                        Ảnh ${index + 1}
                    </span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Tiêu đề</label>
                        <input type="text" name="individual_title_${index}" 
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                               value="${defaultTitle}"
                               placeholder="Tiêu đề cho ảnh này">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Alt Text</label>
                        <input type="text" name="individual_alt_${index}" 
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                               value="${defaultAlt}"
                               placeholder="Mô tả ngắn gọn">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Loại hình ảnh</label>
                        <select name="individual_image_type_${index}" 
                                class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500">
                            <option value="gallery">Thư viện</option>
                            <option value="exterior">Ngoại thất</option>
                            <option value="interior">Nội thất</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Góc chụp</label>
                        <select name="individual_angle_${index}" 
                                class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500">
                            <option value="">Không xác định</option>
                            <optgroup label="Góc chụp cơ bản">
                                <option value="front">Mặt trước</option>
                                <option value="side">Mặt bên</option>
                                <option value="rear">Mặt sau</option>
                                <option value="interior">Nội thất</option>
                            </optgroup>
                            <optgroup label="Chi tiết ngoại thất">
                                <option value="headlight">Đèn pha</option>
                                <option value="grille">Lưới tản nhiệt</option>
                                <option value="wheel">Bánh xe</option>
                                <option value="door">Cửa xe</option>
                                <option value="trunk">Cốp xe</option>
                            </optgroup>
                            <optgroup label="Chi tiết nội thất">
                                <option value="dashboard">Bảng điều khiển</option>
                                <option value="seats">Ghế ngồi</option>
                                <option value="console">Bảng điều khiển giữa</option>
                                <option value="steering">Vô lăng</option>
                            </optgroup>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Liên kết với màu</label>
                        <select name="individual_color_id_${index}" 
                                class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500">
                            <option value="">Không liên kết</option>
                            @foreach($carvariant->colors as $color)
                                <option value="{{ $color->id }}">{{ $color->color_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Thứ tự</label>
                        <input type="number" name="individual_sort_${index}" value="${index}"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="mt-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Mô tả riêng</label>
                    <textarea name="individual_description_${index}" rows="2"
                              class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                              placeholder="Mô tả riêng cho ảnh này...">${defaultDescription}</textarea>
                </div>
                
                <div class="flex items-center space-x-4 mt-3">
                    <label class="flex items-center">
                        <input type="checkbox" name="individual_is_main_${index}" value="1" 
                               class="h-3 w-3 text-blue-600 focus:ring-blue-500 border-gray-300 rounded individual-main-checkbox"
                               data-main-index="${index}">
                        <span class="ml-1 text-xs text-gray-700">Ảnh chính</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="individual_is_active_${index}" value="1" checked
                               class="h-3 w-3 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-1 text-xs text-gray-700">Hiển thị</span>
                    </label>
                </div>
            `;
            
            individualImagesList.appendChild(settingItem);
        });
    }
    
    // Handle main image toggle (only one can be main)
    function handleMainImageToggle(selectedIndex) {
        const checkboxes = document.querySelectorAll('.individual-main-checkbox');
        checkboxes.forEach((checkbox, index) => {
            if (index !== selectedIndex) {
                checkbox.checked = false;
            }
        });
    }
    
    
    // Remove file from selection
    function removeFile(index) {
        console.log('removeFile called with index:', index); // Debug
        const dt = new DataTransfer();
        const files = Array.from(imageFiles.files);
        console.log('Current files count:', files.length); // Debug
        
        files.forEach((file, i) => {
            if (i !== index) {
                dt.items.add(file);
            }
        });
        
        imageFiles.files = dt.files;
        console.log('New files count:', dt.files.length); // Debug
        
        if (dt.files.length === 0) {
            selectedFiles.classList.add('hidden');
            document.getElementById('individualSettings').classList.add('hidden');
        } else {
            displaySelectedFiles(Array.from(dt.files));
        }
    }
    
    // Handle upload form submission
    uploadForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!imageFiles.files || imageFiles.files.length === 0) {
            showMessage('Vui lòng chọn ít nhất một hình ảnh', 'error');
            return;
        }
        
        const submitBtn = document.getElementById('uploadSubmitBtn');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang upload...';
        
        // Prepare form data
        const formData = new FormData();
        
        // Add files
        for (let i = 0; i < imageFiles.files.length; i++) {
            formData.append('images[]', imageFiles.files[i]);
        }
        
        // Always use individual settings for each image
        for (let i = 0; i < imageFiles.files.length; i++) {
            const titleInput = document.querySelector(`input[name="individual_title_${i}"]`);
            const altInput = document.querySelector(`input[name="individual_alt_${i}"]`);
            const angleSelect = document.querySelector(`select[name="individual_angle_${i}"]`);
            const sortInput = document.querySelector(`input[name="individual_sort_${i}"]`);
            const descTextarea = document.querySelector(`textarea[name="individual_description_${i}"]`);
            const activeCheckbox = document.querySelector(`input[name="individual_is_active_${i}"]`);
            const mainCheckbox = document.querySelector(`input[name="individual_is_main_${i}"]`);
            const imageTypeSelect = document.querySelector(`select[name="individual_image_type_${i}"]`);
            const colorIdSelect = document.querySelector(`select[name="individual_color_id_${i}"]`);
            
            formData.append(`individual_titles[]`, titleInput?.value || '');
            formData.append(`individual_alt_texts[]`, altInput?.value || '');
            formData.append(`individual_angles[]`, angleSelect?.value || '');
            formData.append(`individual_sort_orders[]`, sortInput?.value || i.toString());
            formData.append(`individual_descriptions[]`, descTextarea?.value || '');
            formData.append(`individual_is_main[]`, mainCheckbox?.checked ? '1' : '0');
            formData.append(`individual_is_active[]`, activeCheckbox?.checked ? '1' : '0');
            formData.append(`individual_image_types[]`, imageTypeSelect?.value || 'gallery');
            formData.append(`individual_color_ids[]`, colorIdSelect?.value || '');
        }
        
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        // Upload images
        fetch(`/admin/carvariants/{{ $carvariant->id }}/images`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                closeUploadModal();
                // Add new images to DOM without reload
                if (data.images && data.images.length > 0) {
                    data.images.forEach((image, index) => {
                        addImageToDOM(image, false); // Don't update counter for each image
                    });
                    // Update counter once after all images are added
                    updateImagesTabCounter();
                }
            } else {
                showMessage(data.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            showMessage('Có lỗi xảy ra khi upload hình ảnh', 'error');
        })
        .finally(() => {
            // Reset button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    // Inventory calculation for colors
    function calculateAvailable(colorId) {
        const quantityInput = document.querySelector(`input[data-color-id="${colorId}"].inventory-quantity`);
        const reservedInput = document.querySelector(`input[data-color-id="${colorId}"].inventory-reserved`);
        const availableInput = document.querySelector(`input[data-color-id="${colorId}"].inventory-available`);
        
        if (quantityInput && reservedInput && availableInput) {
            const quantity = parseInt(quantityInput.value) || 0;
            const reserved = parseInt(reservedInput.value) || 0;
            const available = Math.max(0, quantity - reserved);
            
            availableInput.value = available;
            
            // Update stock status badge
            updateStockStatus(colorId, available);
        }
    }

    function updateStockStatus(colorId, available) {
        const colorCard = document.querySelector(`[data-color-id="${colorId}"]`);
        if (!colorCard) return;
        
        const statusContainer = colorCard.querySelector('.flex.items-center.space-x-2');
        if (!statusContainer) return;
        
        // Remove existing stock status badge
        const existingBadge = statusContainer.querySelector('.inline-flex.items-center');
        if (existingBadge && (existingBadge.textContent.includes('Còn hàng') || 
                             existingBadge.textContent.includes('Sắp hết') || 
                             existingBadge.textContent.includes('Hết hàng'))) {
            existingBadge.remove();
        }
        
        // Create new stock status badge
        let badgeClass, badgeIcon, badgeText;
        if (available > 10) {
            badgeClass = 'bg-green-100 text-green-800';
            badgeIcon = 'fas fa-check-circle';
            badgeText = 'Còn hàng';
        } else if (available > 0) {
            badgeClass = 'bg-yellow-100 text-yellow-800';
            badgeIcon = 'fas fa-exclamation-triangle';
            badgeText = 'Sắp hết';
        } else {
            badgeClass = 'bg-red-100 text-red-800';
            badgeIcon = 'fas fa-times-circle';
            badgeText = 'Hết hàng';
        }
        
        const newBadge = document.createElement('span');
        newBadge.className = `inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${badgeClass}`;
        newBadge.innerHTML = `<i class="${badgeIcon} mr-1"></i>${badgeText}`;
        
        // Insert at the beginning of the container
        statusContainer.insertBefore(newBadge, statusContainer.firstChild);
    }

    // Add event listeners for inventory inputs
    document.addEventListener('input', function(e) {
        if (e.target.matches('.inventory-quantity, .inventory-reserved')) {
            const colorId = e.target.getAttribute('data-color-id');
            if (colorId) {
                calculateAvailable(colorId);
            }
        }
    });

    // Initialize calculations on page load
    document.querySelectorAll('.inventory-quantity').forEach(input => {
        const colorId = input.getAttribute('data-color-id');
        if (colorId) {
            calculateAvailable(colorId);
        }
    });

    // Modal Management
    const colorModal = document.getElementById('colorModal');
    const colorForm = document.getElementById('colorForm');
    const modalTitle = document.getElementById('modalTitle');
    const colorIdInput = document.getElementById('colorId');
    
    // Open modal for adding new color
    document.getElementById('addColorBtn')?.addEventListener('click', function() {
        resetColorForm();
        modalTitle.textContent = 'Thêm màu mới';
        colorModal.classList.remove('hidden');
    });
    
    // Event delegation for edit and delete buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.edit-color')) {
            const colorId = e.target.closest('.edit-color').dataset.colorId;
            editColor(colorId);
        }
        
        if (e.target.closest('.delete-color')) {
            const colorId = e.target.closest('.delete-color').dataset.colorId;
            deleteColor(colorId);
        }
    });
    
    // Close modal
    function closeModal() {
        colorModal.classList.add('hidden');
        resetColorForm();
    }
    
    document.getElementById('closeModal')?.addEventListener('click', closeModal);
    document.getElementById('cancelBtn')?.addEventListener('click', closeModal);
    
    // Close modal when clicking outside
    colorModal?.addEventListener('click', function(e) {
        if (e.target === colorModal) {
            closeModal();
        }
    });
    
    // Reset form
    function resetColorForm() {
        colorForm.reset();
        colorIdInput.value = '';
        document.getElementById('hex_picker').value = '#FFFFFF';
        document.getElementById('hex_code').value = '#FFFFFF';
        document.getElementById('rgb_code').value = '';
        document.getElementById('color_sort_order').value = '0';
        
        // Target modal checkboxes specifically
        const modalCheckbox = document.querySelector('#colorModal #is_active');
        const isFreeCheckbox = document.querySelector('#colorModal #is_free');
        const isPopularCheckbox = document.querySelector('#colorModal #is_popular');
        
        if (modalCheckbox) modalCheckbox.checked = true;
        if (isFreeCheckbox) isFreeCheckbox.checked = true;
        if (isPopularCheckbox) isPopularCheckbox.checked = false;
    }
    
    // Sync color picker with hex input
    document.getElementById('hex_picker')?.addEventListener('input', function() {
        document.getElementById('hex_code').value = this.value.toUpperCase();
    });
    
    document.getElementById('hex_code')?.addEventListener('input', function() {
        const hex = this.value;
        if (/^#[0-9A-F]{6}$/i.test(hex)) {
            document.getElementById('hex_picker').value = hex;
        }
    });
    
    // Edit color function
    function editColor(colorId) {
        // Find color data from existing DOM
        const colorCard = document.querySelector(`[data-color-id="${colorId}"]`);
        if (!colorCard) return;
        
        // Get data from data attributes
        const colorName = colorCard.dataset.colorName;
        const colorCode = colorCard.dataset.colorCode || '';
        const hexCode = colorCard.dataset.hexCode || '#FFFFFF';
        const colorType = colorCard.dataset.colorType || 'solid';
        const availability = colorCard.dataset.availability || 'standard';
        const priceAdjustment = parseFloat(colorCard.dataset.priceAdjustment) || 0;
        const description = colorCard.dataset.description || '';
        const sortOrder = colorCard.dataset.sortOrder || '0';
        const isActive = colorCard.dataset.isActive === '1';
        const isFree = colorCard.dataset.isFree === '1';
        const isPopular = colorCard.dataset.isPopular === '1';
        
        // Get inventory data from DOM (still need to parse these)
        const quantityDisplay = colorCard.querySelector('.inventory-quantity-display');
        const reservedDisplay = colorCard.querySelector('.inventory-reserved-display');
        
        // Fill form
        colorIdInput.value = colorId;
        document.getElementById('color_name').value = colorName;
        document.getElementById('color_code').value = colorCode;
        document.getElementById('hex_code').value = hexCode;
        document.getElementById('hex_picker').value = hexCode;
        document.getElementById('color_type').value = colorType;
        document.getElementById('availability').value = availability;
        document.getElementById('price_adjustment').value = priceAdjustment;
        document.getElementById('inventory_quantity').value = quantityDisplay?.textContent || 0;
        document.getElementById('inventory_reserved').value = reservedDisplay?.textContent || 0;
        
        // Set values for new fields
        document.getElementById('color_description').value = description;
        document.getElementById('rgb_code').value = '';
        document.getElementById('color_sort_order').value = sortOrder;
        
        // Target the correct checkboxes in modal
        const modalCheckbox = document.querySelector('#colorModal #is_active');
        const isFreeCheckbox = document.querySelector('#colorModal #is_free');
        const isPopularCheckbox = document.querySelector('#colorModal #is_popular');
        
        if (modalCheckbox) modalCheckbox.checked = isActive;
        if (isFreeCheckbox) isFreeCheckbox.checked = isFree;
        if (isPopularCheckbox) isPopularCheckbox.checked = isPopular;
        
        modalTitle.textContent = 'Chỉnh sửa màu';
        colorModal.classList.remove('hidden');
    }
    
    // Delete color function
    let colorToDelete = null;
    function deleteColor(colorId) {
        colorToDelete = colorId;
        
        // Find color name for better UX
        const colorCard = document.querySelector(`[data-color-id="${colorId}"]`);
        const colorName = colorCard ? colorCard.querySelector('h4').textContent.trim() : 'màu này';
        
        document.getElementById('deleteColorMessage').textContent = 
            `Bạn có chắc chắn muốn xóa màu "${colorName}" không? Hành động này không thể hoàn tác.`;
        
        document.getElementById('deleteColorDialog').classList.remove('hidden');
    }
    
    // Handle form submission
    colorForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const isEdit = colorIdInput.value !== '';
        
        // Validation
        const colorName = document.getElementById('color_name').value.trim();
        // Validate required fields
        if (!colorName.trim()) {
            showMessage('Vui lòng nhập tên màu', 'error');
            document.getElementById('color_name').focus();
            return;
        }
        
        // Check for duplicate color name (only for new colors)
        if (!colorIdInput.value) {
            const existingColors = document.querySelectorAll('#colorsContainer .font-medium.text-gray-900');
            const existingNames = Array.from(existingColors).map(el => el.textContent.toLowerCase().trim());
            if (existingNames.includes(colorName.toLowerCase().trim())) {
                showMessage(`Màu "${colorName}" đã tồn tại trong phiên bản này. Vui lòng chọn tên khác.`, 'error');
                document.getElementById('color_name').focus();
                return;
            }
        }
        
        if (colorName.length < 2) {
            showMessage('Tên màu phải có ít nhất 2 ký tự', 'error');
            document.getElementById('color_name').focus();
            return;
        }
        
        const hexCode = document.getElementById('hex_code').value.trim();
        if (hexCode && !/^#[0-9A-F]{6}$/i.test(hexCode)) {
            showMessage('Mã hex không hợp lệ. Vui lòng nhập theo định dạng #FFFFFF', 'error');
            document.getElementById('hex_code').focus();
            return;
        }
        
        const priceAdjustment = document.getElementById('price_adjustment').value;
        if (priceAdjustment && (isNaN(priceAdjustment) || priceAdjustment < -999999999 || priceAdjustment > 999999999)) {
            showMessage('Phụ phí không hợp lệ', 'error');
            document.getElementById('price_adjustment').focus();
            return;
        }
        
        const quantity = parseInt(document.getElementById('inventory_quantity').value) || 0;
        const reserved = parseInt(document.getElementById('inventory_reserved').value) || 0;
        
        if (reserved > quantity) {
            showMessage('Số lượng đã đặt không thể lớn hơn tổng số lượng', 'error');
            document.getElementById('inventory_reserved').focus();
            return;
        }
        
        // If validation passes, perform the action
        const saveBtn = document.getElementById('saveBtn');
        const originalText = saveBtn.innerHTML;
        
        // Show loading state
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang lưu...';
        
        // Prepare form data
        const formData = new FormData();
        formData.append('car_variant_id', document.querySelector('input[name="car_variant_id"]').value);
        formData.append('color_name', colorName);
        formData.append('color_code', document.getElementById('color_code').value.trim());
        formData.append('hex_code', hexCode);
        formData.append('color_type', document.getElementById('color_type').value);
        formData.append('availability', document.getElementById('availability').value);
        formData.append('price_adjustment', document.getElementById('price_adjustment').value || 0);
        formData.append('description', document.getElementById('color_description').value.trim());
        formData.append('rgb_code', document.getElementById('rgb_code').value.trim());
        formData.append('sort_order', document.getElementById('color_sort_order').value || 0);
        formData.append('inventory_quantity', quantity);
        formData.append('inventory_reserved', reserved);
        
        // Get checkbox values from modal specifically
        const modalCheckbox = document.querySelector('#colorModal #is_active');
        const isFreeCheckbox = document.querySelector('#colorModal #is_free');
        const isPopularCheckbox = document.querySelector('#colorModal #is_popular');
        
        formData.append('is_active', modalCheckbox?.checked ? 1 : 0);
        formData.append('is_free', isFreeCheckbox?.checked ? 1 : 0);
        formData.append('is_popular', isPopularCheckbox?.checked ? 1 : 0);
        
        if (isEdit) {
            formData.append('color_id', colorIdInput.value);
            performUpdateColor(formData, saveBtn, originalText);
        } else {
            performAddColor(formData, saveBtn, originalText);
        }
    });

    // Delete dialog handlers
    const deleteDialog = document.getElementById('deleteColorDialog');
    
    // Close delete dialog
    function closeDeleteDialog() {
        deleteDialog.classList.add('hidden');
        colorToDelete = null;
    }
    
    document.getElementById('cancelDeleteBtn')?.addEventListener('click', closeDeleteDialog);
    
    // Close dialog when clicking outside
    deleteDialog?.addEventListener('click', function(e) {
        if (e.target === deleteDialog) {
            closeDeleteDialog();
        }
    });
    
    // Confirm delete
    document.getElementById('confirmDeleteBtn')?.addEventListener('click', function() {
        if (colorToDelete) {
            performDeleteColor(colorToDelete);
        }
    });

    // Keyboard support for modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!colorModal.classList.contains('hidden')) {
                closeModal();
            }
            if (!deleteDialog.classList.contains('hidden')) {
                closeDeleteDialog();
            }
        }
    });

    // CRUD Functions for Colors
    function performAddColor(formData, saveBtn, originalText) {
        const carVariantId = document.querySelector('input[name="car_variant_id"]').value;
        
        fetch(`/admin/carvariants/${carVariantId}/colors`, {
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
                // Add new color card to DOM
                const colorsContainer = document.getElementById('colorsContainer');
                const emptyMessage = colorsContainer.querySelector('.text-center.py-8');
                if (emptyMessage) {
                    emptyMessage.remove();
                }
                
                const newColorHtml = createColorCardHtml(data.color, data.inventory);
                colorsContainer.insertAdjacentHTML('afterbegin', newColorHtml);
                
                // Update color inventory in the main form
                updateColorInventoryInMainForm(data.color.id, data.inventory);
                
                // Update color count in tab
                updateColorCount();
                
                showMessage(data.message, 'success');
                closeModal();
            } else {
                showMessage(data.message || 'Có lỗi xảy ra khi thêm màu', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (error.data && error.data.errors) {
                // Handle validation errors
                const firstError = Object.values(error.data.errors)[0][0];
                showMessage(firstError, 'error');
            } else {
                showMessage(error.data?.message || 'Có lỗi xảy ra khi thêm màu', 'error');
            }
        })
        .finally(() => {
            // Reset button state
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        });
    }
    
    function performUpdateColor(formData, saveBtn, originalText) {
        const colorId = formData.get('color_id');
        const carVariantId = document.querySelector('input[name="car_variant_id"]').value;
        
        // Add _method for Laravel method spoofing
        formData.append('_method', 'PUT');
        
        fetch(`/admin/carvariants/${carVariantId}/colors/${colorId}`, {
            method: 'POST', // Use POST with _method spoofing
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
                // Update the color card in DOM
                const colorCard = document.querySelector(`[data-color-id="${colorId}"]`);
                if (colorCard) {
                    // Update data attributes first (for modal functionality)
                    colorCard.dataset.colorName = data.color.color_name;
                    colorCard.dataset.colorCode = data.color.color_code || '';
                    colorCard.dataset.hexCode = data.color.hex_code || '#FFFFFF';
                    colorCard.dataset.colorType = data.color.color_type || 'solid';
                    colorCard.dataset.availability = data.color.availability || 'standard';
                    colorCard.dataset.priceAdjustment = data.color.price_adjustment || '0';
                    colorCard.dataset.description = data.color.description || '';
                    colorCard.dataset.sortOrder = data.color.sort_order || '0';
                    colorCard.dataset.isActive = data.color.is_active ? '1' : '0';
                    colorCard.dataset.isFree = data.color.is_free ? '1' : '0';
                    colorCard.dataset.isPopular = data.color.is_popular ? '1' : '0';
                    
                    // Update color name
                    const nameElement = colorCard.querySelector('h4');
                    if (nameElement) nameElement.textContent = data.color.color_name;
                    
                    // Update hex preview
                    const hexPreview = colorCard.querySelector('[style*="background-color"]');
                    if (hexPreview && data.color.hex_code) {
                        hexPreview.style.backgroundColor = data.color.hex_code;
                    }
                    
                    // Update color info (type, availability, price)
                    const colorInfoPs = colorCard.querySelectorAll('.space-y-2 p');
                    colorInfoPs.forEach(p => {
                        const text = p.innerHTML;
                        if (text.includes('<strong>Loại:</strong>')) {
                            p.innerHTML = `<strong>Loại:</strong> ${colorTypeMap[data.color.color_type] || data.color.color_type}`;
                        } else if (text.includes('<strong>Tình trạng:</strong>')) {
                            p.innerHTML = `<strong>Tình trạng:</strong> ${availabilityMap[data.color.availability] || data.color.availability}`;
                        } else if (text.includes('<strong>Phụ phí:</strong>')) {
                            p.innerHTML = `<strong>Phụ phí:</strong> <span class="text-blue-600 font-medium">${data.color.price_adjustment > 0 ? '+' : ''}${new Intl.NumberFormat('vi-VN').format(data.color.price_adjustment)} VNĐ</span>`;
                        } else if (text.includes('<strong>Mã màu:</strong>')) {
                            p.innerHTML = `<strong>Mã màu:</strong> ${data.color.color_code ? data.color.color_code : '<span class="text-gray-400 italic">Chưa có mã màu</span>'}`;
                        } else if (text.includes('<strong>Hex:</strong>')) {
                            p.innerHTML = `<strong>Hex:</strong> ${data.color.hex_code ? `<span class="font-mono text-sm">${data.color.hex_code}</span>` : '<span class="text-gray-400 italic">Chưa có mã hex</span>'}`;
                        } else if (text.includes('<strong>RGB:</strong>')) {
                            p.innerHTML = `<strong>RGB:</strong> ${data.color.rgb_code ? `<span class="font-mono text-sm">${data.color.rgb_code}</span>` : '<span class="text-gray-400 italic">Chưa có mã RGB</span>'}`;
                        } else if (text.includes('<strong>Mô tả:</strong>')) {
                            p.innerHTML = `<strong>Mô tả:</strong> ${data.color.description ? data.color.description : '<span class="text-gray-400 italic">Chưa có mô tả</span>'}`;
                        }
                    });
                    
                    // Add missing fields if they don't exist
                    const colorInfoContainer = colorCard.querySelector('.space-y-2');
                    if (colorInfoContainer) {
                        const requiredFields = [
                            { key: 'Mã màu', value: data.color.color_code ? data.color.color_code : '<span class="text-gray-400 italic">Chưa có mã màu</span>' },
                            { key: 'Hex', value: data.color.hex_code ? `<span class="font-mono text-sm">${data.color.hex_code}</span>` : '<span class="text-gray-400 italic">Chưa có mã hex</span>' },
                            { key: 'RGB', value: data.color.rgb_code ? `<span class="font-mono text-sm">${data.color.rgb_code}</span>` : '<span class="text-gray-400 italic">Chưa có mã RGB</span>' },
                            { key: 'Mô tả', value: data.color.description ? data.color.description : '<span class="text-gray-400 italic">Chưa có mô tả</span>' }
                        ];
                        
                        requiredFields.forEach(field => {
                            let hasField = false;
                            colorInfoPs.forEach(p => {
                                if (p.innerHTML.includes(`<strong>${field.key}:</strong>`)) {
                                    hasField = true;
                                }
                            });
                            
                            if (!hasField) {
                                const fieldP = document.createElement('p');
                                fieldP.innerHTML = `<strong>${field.key}:</strong> ${field.value}`;
                                
                                // Insert in correct order
                                if (field.key === 'Mã màu') {
                                    colorInfoContainer.insertBefore(fieldP, colorInfoContainer.firstChild);
                                } else if (field.key === 'Hex') {
                                    const colorCodeP = colorInfoContainer.querySelector('p:has(strong:contains("Mã màu"))') || 
                                                     Array.from(colorInfoContainer.querySelectorAll('p')).find(p => p.innerHTML.includes('<strong>Mã màu:</strong>'));
                                    if (colorCodeP) {
                                        colorCodeP.insertAdjacentElement('afterend', fieldP);
                                    } else {
                                        colorInfoContainer.insertBefore(fieldP, colorInfoContainer.firstChild);
                                    }
                                } else if (field.key === 'RGB') {
                                    const hexP = Array.from(colorInfoContainer.querySelectorAll('p')).find(p => p.innerHTML.includes('<strong>Hex:</strong>'));
                                    if (hexP) {
                                        hexP.insertAdjacentElement('afterend', fieldP);
                                    } else {
                                        colorInfoContainer.insertBefore(fieldP, colorInfoContainer.firstChild);
                                    }
                                } else if (field.key === 'Mô tả') {
                                    const priceP = Array.from(colorInfoContainer.querySelectorAll('p')).find(p => p.innerHTML.includes('<strong>Phụ phí:</strong>'));
                                    if (priceP) {
                                        priceP.insertAdjacentElement('afterend', fieldP);
                                    } else {
                                        colorInfoContainer.appendChild(fieldP);
                                    }
                                }
                            }
                        });
                    }
                    
                    // Update data attributes for future edits
                    colorCard.dataset.isActive = data.color.is_active ? '1' : '0';
                    colorCard.dataset.isFree = data.color.is_free ? '1' : '0';
                    colorCard.dataset.isPopular = data.color.is_popular ? '1' : '0';
                    
                    // Update badges container completely
                    const badgesContainer = colorCard.querySelector('.flex.items-center.space-x-2');
                    if (badgesContainer) {
                        // Get stock badge (first child)
                        const stockBadge = badgesContainer.firstElementChild;
                        
                        // Clear all badges except stock badge
                        badgesContainer.innerHTML = '';
                        if (stockBadge) {
                            badgesContainer.appendChild(stockBadge);
                        }
                        
                        // Add active badge
                        const activeBadge = document.createElement('span');
                        activeBadge.className = `inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${data.color.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;
                        activeBadge.textContent = data.color.is_active ? 'Hoạt động' : 'Tạm dừng';
                        badgesContainer.appendChild(activeBadge);
                        
                        // Add free badge if applicable
                        if (data.color.is_free) {
                            const freeBadge = document.createElement('span');
                            freeBadge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
                            freeBadge.textContent = 'Miễn phí';
                            badgesContainer.appendChild(freeBadge);
                        }
                        
                        // Add popular badge if applicable
                        if (data.color.is_popular) {
                            const popularBadge = document.createElement('span');
                            popularBadge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800';
                            popularBadge.textContent = 'Phổ biến';
                            badgesContainer.appendChild(popularBadge);
                        }
                    }
                    
                    // Update inventory displays
                    const quantityDisplay = colorCard.querySelector('.inventory-quantity-display');
                    const reservedDisplay = colorCard.querySelector('.inventory-reserved-display');
                    const availableDisplay = colorCard.querySelector('.inventory-available-display');
                    const quantityInput = colorCard.querySelector('.inventory-quantity');
                    const reservedInput = colorCard.querySelector('.inventory-reserved');
                    const availableInput = colorCard.querySelector('.inventory-available');
                    
                    if (quantityDisplay) quantityDisplay.textContent = data.inventory.quantity;
                    if (reservedDisplay) reservedDisplay.textContent = data.inventory.reserved;
                    if (availableDisplay) availableDisplay.textContent = data.inventory.available;
                    if (quantityInput) quantityInput.value = data.inventory.quantity;
                    if (reservedInput) reservedInput.value = data.inventory.reserved;
                    if (availableInput) availableInput.value = data.inventory.available;
                    
                    // Update stock badge
                    const stockBadges = colorCard.querySelectorAll('.bg-green-100, .bg-yellow-100, .bg-red-100');
                    stockBadges.forEach(badge => {
                        const text = badge.textContent.trim();
                        if (text === 'Còn hàng' || text === 'Sắp hết' || text === 'Hết hàng') {
                            const newStockBadge = getStockBadgeHtml(data.inventory.available);
                            badge.outerHTML = newStockBadge;
                        }
                    });
                    
                    // Recalculate available
                    calculateAvailable(colorId);
                    
                    // Update color inventory in main form
                    updateColorInventoryInMainForm(colorId, data.inventory);
                }
                
                showMessage(data.message, 'success');
                closeModal();
            } else {
                showMessage(data.message || 'Có lỗi xảy ra khi cập nhật màu', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (error.data && error.data.errors) {
                // Handle validation errors
                const firstError = Object.values(error.data.errors)[0][0];
                showMessage(firstError, 'error');
            } else {
                showMessage(error.data?.message || 'Có lỗi xảy ra khi cập nhật màu', 'error');
            }
        })
        .finally(() => {
            // Reset button state
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        });
    }
    
    function performDeleteColor(colorId) {
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        const originalText = confirmBtn.innerHTML;
        const carVariantId = document.querySelector('input[name="car_variant_id"]').value;
        
        // Show loading state
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xóa...';
        
        fetch(`/admin/carvariants/${carVariantId}/colors/${colorId}`, {
            method: 'DELETE',
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
                // Remove from DOM
                const colorCard = document.querySelector(`[data-color-id="${colorId}"]`);
                if (colorCard) {
                    colorCard.remove();
                }
                
                // Remove from color inventory in main form
                removeColorFromInventory(colorId);
                
                // Update color count in tab
                updateColorCount();
                
                // Check if no colors left
                const colorsContainer = document.getElementById('colorsContainer');
                if (colorsContainer.children.length === 0) {
                    colorsContainer.innerHTML = `
                        <div class="col-span-full text-center py-8 text-gray-500">
                            <i class="fas fa-palette text-4xl mb-4"></i>
                            <p>Chưa có màu sắc nào. Hãy thêm màu đầu tiên!</p>
                        </div>
                    `;
                }
                
                showMessage(data.message, 'success');
                closeDeleteDialog();
            } else {
                showMessage(data.message || 'Có lỗi xảy ra khi xóa màu', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage(error.data?.message || 'Có lỗi xảy ra khi xóa màu', 'error');
        })
        .finally(() => {
            // Reset button state
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = originalText;
        });
    }
    
    // Translation mappings
    const colorTypeMap = {
        'solid': 'Màu đặc',
        'metallic': 'Màu kim loại', 
        'pearlescent': 'Màu ngọc trai',
        'matte': 'Màu nhám',
        'special': 'Màu đặc biệt'
    };
    
    const availabilityMap = {
        'standard': 'Tiêu chuẩn',
        'optional': 'Tùy chọn',
        'limited': 'Giới hạn',
        'discontinued': 'Ngừng sản xuất'
    };
    
    // Reverse mappings for edit function
    const colorTypeReverseMap = Object.fromEntries(
        Object.entries(colorTypeMap).map(([key, value]) => [value.toLowerCase(), key])
    );
    
    const availabilityReverseMap = Object.fromEntries(
        Object.entries(availabilityMap).map(([key, value]) => [value.toLowerCase(), key])
    );

    // Helper functions
    function createColorCardHtml(color, inventory) {
        const stockBadge = getStockBadgeHtml(inventory.available);
        const activeBadge = color.is_active 
            ? '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Hoạt động</span>'
            : '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Tạm dừng</span>';
            
        return `
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200" 
                 data-color-id="${color.id}"
                 data-color-name="${color.color_name}"
                 data-color-code="${color.color_code || ''}"
                 data-hex-code="${color.hex_code || '#FFFFFF'}"
                 data-color-type="${color.color_type || 'solid'}"
                 data-availability="${color.availability || 'standard'}"
                 data-price-adjustment="${color.price_adjustment || '0'}"
                 data-description="${color.description || ''}"
                 data-sort-order="${color.sort_order || '0'}"
                 data-is-active="${color.is_active ? '1' : '0'}"
                 data-is-free="${color.is_free ? '1' : '0'}"
                 data-is-popular="${color.is_popular ? '1' : '0'}">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        ${color.hex_code ? `<div class="w-6 h-6 rounded-full border border-gray-300 mr-2" style="background-color: ${color.hex_code};"></div>` : ''}
                        <h4 class="font-medium text-gray-900">${color.color_name}</h4>
                    </div>
                    <button type="button" class="text-red-600 hover:text-red-800 delete-color" data-color-id="${color.id}">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
                
                <div class="space-y-2 text-sm text-gray-600 mb-4">
                    <p><strong>Mã màu:</strong> ${color.color_code ? color.color_code : '<span class="text-gray-400 italic">Chưa có mã màu</span>'}</p>
                    <p><strong>Hex:</strong> ${color.hex_code ? `<span class="font-mono text-sm">${color.hex_code}</span>` : '<span class="text-gray-400 italic">Chưa có mã hex</span>'}</p>
                    <p><strong>RGB:</strong> ${color.rgb_code ? `<span class="font-mono text-sm">${color.rgb_code}</span>` : '<span class="text-gray-400 italic">Chưa có mã RGB</span>'}</p>
                    <p><strong>Loại:</strong> ${colorTypeMap[color.color_type] || color.color_type}</p>
                    <p><strong>Tình trạng:</strong> ${availabilityMap[color.availability] || color.availability}</p>
                    <p><strong>Phụ phí:</strong> <span class="text-blue-600 font-medium">${color.price_adjustment > 0 ? '+' : ''}${new Intl.NumberFormat('vi-VN').format(color.price_adjustment)} VNĐ</span></p>
                    <p><strong>Mô tả:</strong> ${color.description ? color.description : '<span class="text-gray-400 italic">Chưa có mô tả</span>'}</p>
                </div>

                <div class="border-t pt-4">
                    <h5 class="text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-boxes text-blue-500 mr-1"></i>
                        Quản lý tồn kho
                    </h5>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Tổng số</label>
                            <div class="w-full px-2 py-1 text-sm border border-gray-300 rounded bg-gray-50 text-gray-700 inventory-quantity-display"
                                 data-color-id="${color.id}">${inventory.quantity}</div>
                            <input type="hidden" name="color_inventory[${color.id}][quantity]" value="${inventory.quantity}" class="inventory-quantity">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Đã đặt</label>
                            <div class="w-full px-2 py-1 text-sm border border-gray-300 rounded bg-gray-50 text-gray-700 inventory-reserved-display"
                                 data-color-id="${color.id}">${inventory.reserved}</div>
                            <input type="hidden" name="color_inventory[${color.id}][reserved]" value="${inventory.reserved}" class="inventory-reserved">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Có sẵn</label>
                            <div class="w-full px-2 py-1 text-sm border border-gray-300 rounded bg-gray-50 text-gray-700 inventory-available-display"
                                 data-color-id="${color.id}">${inventory.available}</div>
                            <input type="hidden" name="color_inventory[${color.id}][available]" value="${inventory.available}" class="inventory-available">
                        </div>
                    </div>
                    
                    <div class="mt-3 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            ${stockBadge}
                            ${activeBadge}
                            ${color.is_free ? '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Miễn phí</span>' : ''}
                            ${color.is_popular ? '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Phổ biến</span>' : ''}
                        </div>
                        <button type="button" class="text-blue-600 hover:text-blue-800 edit-color" data-color-id="${color.id}">
                            <i class="fas fa-edit text-sm mr-1"></i>
                            Sửa
                        </button>
                    </div>
                </div>
            </div>
        `;
    }
    
    function getStockBadgeHtml(available) {
        if (available > 10) {
            return '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Còn hàng</span>';
        } else if (available > 0) {
            return '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Sắp hết</span>';
        } else {
            return '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Hết hàng</span>';
        }
    }
    
    function updateColorCount() {
        const colorsContainer = document.getElementById('colorsContainer');
        
        // Count only direct children that have data-color-id attribute
        let count = 0;
        for (let child of colorsContainer.children) {
            if (child.hasAttribute('data-color-id')) {
                count++;
            }
        }
        
        
        // Update tab button text
        const colorTab = document.querySelector('[data-tab="colors"]');
        if (colorTab) {
            colorTab.innerHTML = `<i class="fas fa-palette mr-2"></i>Màu sắc (${count})`;
        }
    }
    
    // Update specification tab count
    function updateSpecTabCount() {
        const specsContainer = document.getElementById('specificationsContainer');
        
        // Count only div elements with data-spec-id (not buttons)
        const specCards = specsContainer.querySelectorAll('div[data-spec-id]');
        const count = specCards.length;
        
        // Update tab button text
        const specTab = document.querySelector('[data-tab="specifications"]');
        if (specTab) {
            specTab.innerHTML = `<i class="fas fa-cogs mr-2"></i>Thông số kỹ thuật (${count})`;
        }
    }
    
    // Update feature tab count
    function updateFeatureTabCount() {
        const featuresContainer = document.getElementById('featuresContainer');
        
        // Count only div elements with data-feature-id (not buttons)
        const featureCards = featuresContainer.querySelectorAll('div[data-feature-id]');
        const count = featureCards.length;
        
        // Update tab button text
        const featureTab = document.querySelector('[data-tab="features"]');
        if (featureTab) {
            featureTab.innerHTML = `<i class="fas fa-star mr-2"></i>Tính năng (${count})`;
        }
    }

    function updateColorInventoryInMainForm(colorId, inventory) {
        // This would update the main form's color_inventory data
        // For now, we'll just ensure the inputs exist and have the right values
        const existingQuantityInput = document.querySelector(`input[name="color_inventory[${colorId}][quantity]"]`);
        const existingReservedInput = document.querySelector(`input[name="color_inventory[${colorId}][reserved]"]`);
        const existingAvailableInput = document.querySelector(`input[name="color_inventory[${colorId}][available]"]`);
        
        if (existingQuantityInput) existingQuantityInput.value = inventory.quantity;
        if (existingReservedInput) existingReservedInput.value = inventory.reserved;
        if (existingAvailableInput) existingAvailableInput.value = inventory.available;
    }
    
    function removeColorFromInventory(colorId) {
        // Remove color inventory inputs from main form
        const quantityInput = document.querySelector(`input[name="color_inventory[${colorId}][quantity]"]`);
        const reservedInput = document.querySelector(`input[name="color_inventory[${colorId}][reserved]"]`);
        const availableInput = document.querySelector(`input[name="color_inventory[${colorId}][available]"]`);
        
        if (quantityInput) quantityInput.remove();
        if (reservedInput) reservedInput.remove();
        if (availableInput) availableInput.remove();
    }

    // Image Management JavaScript
    const imageModal = document.getElementById('imageModal');
    const imageForm = document.getElementById('imageForm');
    const imageIdInput = document.getElementById('imageId');
    const imagePreview = document.getElementById('imagePreview');

    // Open image edit modal
    function editImage(imageId) {
        const imageCard = document.querySelector(`[data-image-id="${imageId}"]`);
        if (!imageCard) return;
        
        // Get data from data attributes
        const imageUrl = imageCard.dataset.imageUrl;
        const altText = imageCard.dataset.altText || '';
        const title = imageCard.dataset.title || '';
        const imageType = imageCard.dataset.imageType || 'gallery';
        const angle = imageCard.dataset.angle || '';
        const description = imageCard.dataset.description || '';
        const sortOrder = imageCard.dataset.sortOrder || '0';
        const isMain = imageCard.dataset.isMain === '1';
        const isActive = imageCard.dataset.isActive === '1';
        const colorId = imageCard.dataset.colorId || '';
    
    // Fill form
    imageIdInput.value = imageId;
    document.getElementById('image_title').value = title;
    document.getElementById('image_alt_text').value = altText;
    document.getElementById('image_type').value = imageType;
    document.getElementById('image_angle').value = angle;
    document.getElementById('image_description').value = description;
    document.getElementById('image_sort_order').value = sortOrder;
    document.getElementById('image_is_main').checked = isMain;
    document.getElementById('image_is_active').checked = isActive;
    document.getElementById('image_color_id').value = colorId;
    
    // Set image preview
    const fullImageUrl = imageUrl.startsWith('http') ? imageUrl : `/storage/${imageUrl}`;
    imagePreview.src = fullImageUrl;
    
    // Reset file input
    document.getElementById('replace_image').value = '';
    
    // Show modal
    imageModal.classList.remove('hidden');
}

// Close image modal
function closeImageModal() {
    imageModal.classList.add('hidden');
    imageForm.reset();
    imageIdInput.value = '';
}

// Helper functions for DOM manipulation
function addImageToDOM(image, updateCounter = true) {
    const imagesContainer = document.getElementById('imagesContainer');
    const imageCard = createImageCard(image);
    
    // Remove empty state if exists
    const emptyState = imagesContainer.querySelector('.text-center.py-8');
    if (emptyState) {
        emptyState.remove();
    }
    
    // If this image is set as main, remove main status from other images
    if (image.is_main) {
        document.querySelectorAll('[data-image-id]').forEach(card => {
            if (card.dataset.imageId !== image.id.toString()) {
                card.dataset.isMain = '0';
                // Find main badge specifically (contains fa-star icon)
                const mainBadge = card.querySelector('.bg-green-100 .fa-star')?.parentElement;
                if (mainBadge) {
                    mainBadge.remove();
                }
            }
        });
    }
    
    // Add new image at the beginning (prepend)
    imagesContainer.insertBefore(imageCard, imagesContainer.firstChild);
    
    // Add a subtle animation
    imageCard.style.opacity = '0';
    imageCard.style.transform = 'translateY(-10px)';
    setTimeout(() => {
        imageCard.style.transition = 'all 0.3s ease';
        imageCard.style.opacity = '1';
        imageCard.style.transform = 'translateY(0)';
    }, 50);
    
    // Update images tab counter if requested
    if (updateCounter) {
        updateImagesTabCounter();
    }
}

function updateImageInDOM(image) {
    const existingCard = document.querySelector(`[data-image-id="${image.id}"]`);
    if (existingCard) {
        const newCard = createImageCard(image);
        existingCard.replaceWith(newCard);
        
        // If this image is set as main, remove main status from other images
        if (image.is_main) {
            document.querySelectorAll('[data-image-id]').forEach(card => {
                if (card.dataset.imageId !== image.id.toString()) {
                    card.dataset.isMain = '0';
                    // Find main badge specifically (contains fa-star icon)
                    const mainBadge = card.querySelector('.bg-green-100 .fa-star')?.parentElement;
                    if (mainBadge) {
                        mainBadge.remove();
                    }
                }
            });
        }
    }
}

function removeImageFromDOM(imageId) {
    const imageCard = document.querySelector(`[data-image-id="${imageId}"]`);
    if (imageCard) {
        imageCard.remove();
        
        // Check if no images left, show empty state
        const imagesContainer = document.getElementById('imagesContainer');
        const remainingImages = imagesContainer.querySelectorAll('[data-image-id]');
        if (remainingImages.length === 0) {
            imagesContainer.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-images text-4xl mb-4"></i>
                    <p>Chưa có hình ảnh nào. Hãy upload hình ảnh đầu tiên!</p>
                </div>
            `;
        }
        
        // Update images tab counter
        updateImagesTabCounter();
    }
}

function createImageCard(image) {
    const imageCard = document.createElement('div');
    imageCard.className = 'relative bg-gray-50 rounded-lg overflow-hidden border border-gray-200';
    imageCard.setAttribute('data-image-id', image.id);
    imageCard.setAttribute('data-image-url', image.image_url);
    imageCard.setAttribute('data-alt-text', image.alt_text || '');
    imageCard.setAttribute('data-title', image.title || '');
    imageCard.setAttribute('data-image-type', image.image_type || 'gallery');
    imageCard.setAttribute('data-angle', image.angle || '');
    imageCard.setAttribute('data-description', image.description || '');
    imageCard.setAttribute('data-sort-order', image.sort_order || '0');
    imageCard.setAttribute('data-is-main', image.is_main ? '1' : '0');
    imageCard.setAttribute('data-is-active', image.is_active ? '1' : '0');
    imageCard.setAttribute('data-color-id', image.car_variant_color_id || '');
    
    const imageUrl = image.image_url.startsWith('http') ? image.image_url : `/storage/${image.image_url}`;
    
    imageCard.innerHTML = `
        <div class="relative cursor-pointer">
            <img src="${imageUrl}" 
                 alt="${image.alt_text || ''}" 
                 class="w-full h-32 object-cover hover:opacity-90 transition-opacity">
        </div>
        
        <div class="absolute top-2 left-2 flex flex-wrap gap-1">
            ${image.is_main ? '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-star mr-1"></i>Chính</span>' : ''}
        </div>
        
        <div class="absolute top-2 right-2 flex space-x-1">
            <button type="button" class="edit-image bg-white bg-opacity-80 hover:bg-opacity-100 text-blue-600 p-1 rounded" data-image-id="${image.id}" title="Chỉnh sửa">
                <i class="fas fa-edit text-xs"></i>
            </button>
            <button type="button" class="delete-image bg-red-500 bg-opacity-80 hover:bg-opacity-100 text-white p-1 rounded" data-image-id="${image.id}" title="Xóa ảnh">
                <i class="fas fa-trash text-xs"></i>
            </button>
        </div>
        
        <div class="p-2">
            ${image.title ? `<p class="text-xs font-medium text-gray-900 truncate mb-1">${image.title}</p>` : ''}
            ${image.alt_text ? `<p class="text-xs text-gray-600 truncate">${image.alt_text}</p>` : ''}
            <div class="flex items-center justify-between mt-1">
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                        ${getImageTypeText(image.image_type)}
                    </span>
                    ${image.angle ? `<span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">${getAngleText(image.angle)}</span>` : ''}
                </div>
                ${image.is_active ? '<span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Hiện</span>' : '<span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Ẩn</span>'}
            </div>
            ${image.description ? `<p class="text-xs text-gray-500 mt-1 truncate" title="${image.description}">${image.description}</p>` : ''}
            ${image.car_variant_color_id ? getColorDisplay(image.car_variant_color_id) : ''}
        </div>
    `;
    
    return imageCard;
}

function getColorDisplay(colorId) {
    // Find color in the global colors data
    const color = carVariantColors.find(c => c.id == colorId);
    
    if (color) {
        return `
            <div class="flex items-center mt-1">
                <div class="w-3 h-3 rounded-full border border-gray-300 mr-1" style="background-color: ${color.hex_code}"></div>
                <span class="text-xs text-gray-500">${color.color_name}</span>
            </div>
        `;
    }
    return '';
}

function getImageTypeText(imageType) {
    switch(imageType) {
        case 'gallery': return 'Thư viện';
        case 'interior': return 'Nội thất';
        case 'exterior': return 'Ngoại thất';
        default: return imageType;
    }
}

function getAngleText(angle) {
    switch(angle) {
        case 'front': return 'Trước';
        case 'side': return 'Bên';
        case 'rear': return 'Sau';
        case 'interior': return 'Trong';
        case 'wheel': return 'Bánh xe';
        case 'headlight': return 'Đèn pha';
        case 'grille': return 'Lưới tản nhiệt';
        case 'dashboard': return 'Bảng điều khiển';
        case 'seats': return 'Ghế ngồi';
        case 'console': return 'Bảng điều khiển giữa';
        case 'trunk': return 'Cốp xe';
        case 'steering': return 'Vô lăng';
        case 'door': return 'Cửa xe';
        default: return angle;
    }
}

// Update images tab counter
function updateImagesTabCounter() {
    const imagesContainer = document.getElementById('imagesContainer');
    if (!imagesContainer) {
        console.warn('imagesContainer not found');
        return;
    }
    
    // Count only direct image card children (not nested elements)
    // Try multiple selectors to find the right one
    let imageCards = imagesContainer.querySelectorAll(':scope > [data-image-id]');
    
    // Fallback: count by image card class if :scope doesn't work
    if (imageCards.length === 0) {
        imageCards = imagesContainer.querySelectorAll('.relative.bg-gray-50.rounded-lg[data-image-id]');
    }
    
    // Another fallback: count direct children with data-image-id
    if (imageCards.length === 0) {
        imageCards = Array.from(imagesContainer.children).filter(child => child.hasAttribute('data-image-id'));
    }
    
    const count = imageCards.length;
    
    // Find and update the images tab button
    const imagesTab = document.querySelector('[data-tab="images"]');
    if (imagesTab) {
        const icon = imagesTab.querySelector('i');
        const iconClass = icon ? icon.className : 'fas fa-images';
        imagesTab.innerHTML = `<i class="${iconClass} mr-2"></i>Hình ảnh (${count})`;
    }
}

    // Handle image form submission (edit)
    imageForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const imageId = document.getElementById('imageId').value;
        if (!imageId) {
            showMessage('Không tìm thấy ID hình ảnh', 'error');
            return;
        }
        
        const saveBtn = document.getElementById('saveImageBtn');
        const originalText = saveBtn.innerHTML;
        
        // Show loading state
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang lưu...';
        
        // Prepare form data
        const formData = new FormData();
        formData.append('title', document.getElementById('image_title').value);
        formData.append('alt_text', document.getElementById('image_alt_text').value);
        formData.append('image_type', document.getElementById('image_type').value);
        formData.append('angle', document.getElementById('image_angle').value);
        formData.append('sort_order', document.getElementById('image_sort_order').value);
        formData.append('car_variant_color_id', document.getElementById('image_color_id').value);
        formData.append('description', document.getElementById('image_description').value);
        formData.append('is_main', document.getElementById('image_is_main').checked ? '1' : '0');
        formData.append('is_active', document.getElementById('image_is_active').checked ? '1' : '0');
        
        // Add replace image file if selected
        const replaceImageFile = document.getElementById('replace_image').files[0];
        if (replaceImageFile) {
            formData.append('replace_image', replaceImageFile);
        }
        
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('_method', 'PUT');
        
        // Update image
        fetch(`/admin/carvariants/{{ $carvariant->id }}/images/${imageId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                closeImageModal();
                // Update image in DOM without reload
                if (data.image) {
                    updateImageInDOM(data.image);
                }
            } else {
                showMessage(data.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(error => {
            console.error('Update error:', error);
            showMessage('Có lỗi xảy ra khi cập nhật hình ảnh', 'error');
        })
        .finally(() => {
            // Reset button state
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        });
    });

    // Event listeners for image management
    document.addEventListener('click', function(e) {
        // Edit image
        if (e.target.closest('.edit-image')) {
            const imageId = e.target.closest('.edit-image').dataset.imageId;
            editImage(imageId);
        }
        
        // Delete image
        if (e.target.closest('.delete-image')) {
            const imageId = e.target.closest('.delete-image').dataset.imageId;
            deleteImage(imageId);
        }
    });
    
    // Delete image function
    // Variable to store image to delete
    let imageToDelete = null;
    
    function deleteImage(imageId) {
        const imageCard = document.querySelector(`[data-image-id="${imageId}"]`);
        const imageName = imageCard?.dataset.title || 'hình ảnh';
        
        // Store image ID for confirmation
        imageToDelete = imageId;
        
        // Update modal message with image name
        document.getElementById('deleteImageMessage').textContent = 
            `Bạn có chắc chắn muốn xóa "${imageName}" không? Hành động này không thể hoàn tác.`;
        
        // Show modal
        document.getElementById('deleteImageDialog').classList.remove('hidden');
    }
    
    // Delete image dialog handlers
    const deleteImageDialog = document.getElementById('deleteImageDialog');
    
    // Close delete image dialog
    function closeImageDeleteDialog() {
        deleteImageDialog.classList.add('hidden');
        imageToDelete = null;
    }
    
    // Cancel button
    document.getElementById('cancelImageDeleteBtn')?.addEventListener('click', closeImageDeleteDialog);
    
    // Close dialog when clicking outside
    deleteImageDialog?.addEventListener('click', function(e) {
        if (e.target === deleteImageDialog) {
            closeImageDeleteDialog();
        }
    });
    
    // Confirm delete image
    document.getElementById('confirmImageDeleteBtn')?.addEventListener('click', function() {
        if (imageToDelete) {
            performDeleteImage(imageToDelete);
        }
    });
    
    function performDeleteImage(imageId) {
        const confirmBtn = document.getElementById('confirmImageDeleteBtn');
        const originalText = confirmBtn.innerHTML;
        
        // Show loading state
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Xóa...';
        
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('_method', 'DELETE');
        
        fetch(`/admin/carvariants/{{ $carvariant->id }}/images/${imageId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                // Remove image from DOM without reload
                removeImageFromDOM(imageId);
                // Close modal
                closeImageDeleteDialog();
            } else {
                showMessage(data.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            showMessage('Có lỗi xảy ra khi xóa hình ảnh', 'error');
        })
        .finally(() => {
            // Reset button state
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = originalText;
        });
    }

// Close modal events
document.getElementById('closeImageModal').addEventListener('click', closeImageModal);
document.getElementById('cancelImageEdit').addEventListener('click', closeImageModal);

// Close image modal when clicking outside
imageModal?.addEventListener('click', function(e) {
    if (e.target === imageModal) {
        closeImageModal();
    }
});

// Preview image when file is selected
document.getElementById('replace_image')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// ESC key to close modals
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (!uploadModal.classList.contains('hidden')) {
            closeUploadModal();
        }
        if (!imageModal.classList.contains('hidden')) {
            closeImageModal();
        }
        if (!deleteImageDialog.classList.contains('hidden')) {
            closeImageDeleteDialog();
        }
    }
});
});
</script>

{{-- Color Management Modal --}}
<div id="colorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        {{-- Header --}}
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900" id="modalTitle">
                <i class="fas fa-palette text-blue-600 mr-2"></i>
                Thêm màu mới
            </h3>
            <button type="button" id="closeModal" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-2 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        {{-- Body --}}
        <div class="px-6 pb-6">
            
            <form id="colorForm" class="space-y-4">
                <input type="hidden" id="colorId" name="color_id">
                <input type="hidden" name="car_variant_id" value="{{ $carvariant->id }}">
                
                <div class="!mt-0">
                    <label for="color_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Tên màu <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="color_name" name="color_name"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ví dụ: Trắng Ngọc Trai">
                </div>
                
                {{-- Thông tin màu sắc --}}
                <div class="border-b pb-4 mb-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">🎨 Thông tin màu sắc</h4>
                    
                    <div class="mb-4">
                        <label for="color_code" class="block text-sm font-medium text-gray-700 mb-1">Mã màu hãng</label>
                        <input type="text" id="color_code" name="color_code"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ví dụ: NH-883P">
                    </div>
                    
                    <div class="mb-4">
                        <label for="hex_code" class="block text-sm font-medium text-gray-700 mb-1">Mã hex</label>
                        <div class="flex items-center space-x-2">
                            <input type="color" id="hex_picker" class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
                            <input type="text" id="hex_code" name="hex_code"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="#FFFFFF">
                        </div>
                    </div>
                    
                    <div>
                        <label for="rgb_code" class="block text-sm font-medium text-gray-700 mb-1">Mã RGB</label>
                        <input type="text" id="rgb_code" name="rgb_code" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="rgb(255, 255, 255)">
                    </div>
                </div>
                
                {{-- Phân loại --}}
                <div class="border-b pb-4 mb-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">📋 Phân loại</h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="color_type" class="block text-sm font-medium text-gray-700 mb-1">Loại màu</label>
                            <select id="color_type" name="color_type" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="solid">Màu đặc</option>
                                <option value="metallic">Màu kim loại</option>
                                <option value="pearlescent">Màu ngọc trai</option>
                                <option value="matte">Màu nhám</option>
                                <option value="special">Màu đặc biệt</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="availability" class="block text-sm font-medium text-gray-700 mb-1">Tình trạng</label>
                            <select id="availability" name="availability"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="standard">Tiêu chuẩn</option>
                                <option value="optional">Tùy chọn</option>
                                <option value="limited">Giới hạn</option>
                                <option value="discontinued">Ngừng sản xuất</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                {{-- Giá cả và chính sách --}}
                <div class="border-b pb-4 mb-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">💰 Giá cả & Chính sách</h4>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="price_adjustment" class="block text-sm font-medium text-gray-700 mb-1">Phụ phí (VNĐ)</label>
                            <input type="number" id="price_adjustment" name="price_adjustment" value="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="0">
                            <p class="text-xs text-gray-500 mt-1">Số dương: phụ phí | Số âm: giảm giá</p>
                        </div>
                        <div>
                            <label for="color_sort_order" class="block text-sm font-medium text-gray-700 mb-1">Thứ tự hiển thị</label>
                            <input type="number" id="color_sort_order" name="sort_order" value="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="0">
                            <p class="text-xs text-gray-500 mt-1">Số nhỏ hiển thị trước</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-start">
                            <input type="checkbox" id="is_free" name="is_free" value="1" checked
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1">
                            <div class="ml-2">
                                <label for="is_free" class="block text-sm text-gray-700 font-medium">Màu miễn phí</label>
                                <p class="text-xs text-gray-500">Không tính thêm tiền (độc lập với phụ phí)</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <input type="checkbox" id="is_popular" name="is_popular" value="1"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1">
                            <div class="ml-2">
                                <label for="is_popular" class="block text-sm text-gray-700 font-medium">Màu phổ biến</label>
                                <p class="text-xs text-gray-500">Được khách hàng ưa chuộng</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label for="color_description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                    <textarea id="color_description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Mô tả về màu sắc..."></textarea>
                </div>
                
                {{-- Inventory Section --}}
                <div class="border-t pt-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-boxes text-blue-500 mr-1"></i>
                        Quản lý tồn kho
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="inventory_quantity" class="block text-xs text-gray-600 mb-1">Tổng số</label>
                            <input type="number" id="inventory_quantity" name="inventory_quantity" value="0"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="inventory_reserved" class="block text-xs text-gray-600 mb-1">Đã đặt</label>
                            <input type="number" id="inventory_reserved" name="inventory_reserved" value="0"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">Hoạt động</label>
                </div>
                
            </form>
        </div>
        
        {{-- Footer --}}
        <div class="flex justify-between p-6 border-t border-gray-200 bg-gray-50 rounded-b-xl">
            <button type="button" id="cancelBtn" 
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                Hủy
            </button>
            <button type="submit" form="colorForm" id="saveBtn"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-save mr-2"></i>
                Lưu màu
            </button>
        </div>
    </div>
</div>

{{-- Delete Color Confirmation Dialog --}}
<div id="deleteColorDialog" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
        {{-- Header --}}
        <div class="p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Xác nhận xóa màu</h3>
            <p class="text-sm text-gray-600" id="deleteColorMessage">
                Bạn có chắc chắn muốn xóa màu này không? Hành động này không thể hoàn tác.
            </p>
        </div>
        
        {{-- Footer --}}
        <div class="flex justify-between p-6 border-t border-gray-200 bg-gray-50 rounded-b-xl">
            <button type="button" id="cancelDeleteBtn" 
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                Hủy
            </button>
            <button type="button" id="confirmDeleteBtn"
                    class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                <i class="fas fa-trash mr-2"></i>
                Xóa
            </button>
        </div>
    </div>
</div>

{{-- Delete Image Confirmation Dialog --}}
<div id="deleteImageDialog" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
        {{-- Header --}}
        <div class="p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Xác nhận xóa hình ảnh</h3>
            <p class="text-sm text-gray-600" id="deleteImageMessage">
                Bạn có chắc chắn muốn xóa hình ảnh này không? Hành động này không thể hoàn tác.
            </p>
        </div>
        
        {{-- Footer --}}
        <div class="flex justify-between p-6 border-t border-gray-200 bg-gray-50 rounded-b-xl">
            <button type="button" id="cancelImageDeleteBtn" 
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                Hủy
            </button>
            <button type="button" id="confirmImageDeleteBtn"
                    class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                <i class="fas fa-trash mr-2"></i>
                Xóa
            </button>
        </div>
    </div>
</div>

{{-- Specification Management Modal --}}
<div id="specModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        {{-- Header --}}
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 id="specModalTitle" class="text-xl font-semibold text-gray-900">Thêm thông số mới</h2>
            <button type="button" id="closeSpecModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        {{-- Body --}}
        <div class="px-6 pb-6">
            <form id="specForm" class="space-y-4">
                <input type="hidden" id="specId" name="spec_id">
                <input type="hidden" name="car_variant_id" value="{{ $carvariant->id }}">
                
                <div class="!mt-0">
                    <label for="spec_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Tên thông số <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="spec_name" name="spec_name"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ví dụ: Công suất tối đa">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="spec_value" class="block text-sm font-medium text-gray-700 mb-1">
                            Giá trị <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="spec_value" name="spec_value"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ví dụ: 150">
                    </div>
                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Đơn vị</label>
                        <input type="text" id="unit" name="unit"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ví dụ: HP, km/h">
                    </div>
                </div>
                
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Danh mục</label>
                    <select id="category" name="category"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="engine">Động cơ</option>
                        <option value="performance">Hiệu suất</option>
                        <option value="dimensions">Kích thước</option>
                        <option value="fuel">Nhiên liệu</option>
                        <option value="transmission">Hộp số</option>
                        <option value="brake">Phanh</option>
                        <option value="chassis">Khung gầm</option>
                        <option value="seating">Ghế ngồi</option>
                        <option value="safety">An toàn</option>
                        <option value="comfort">Tiện nghi</option>
                        <option value="technology">Công nghệ</option>
                        <option value="warranty">Bảo hành</option>
                        <option value="wheels">Bánh xe</option>
                        <option value="other">Khác</option>
                    </select>
                </div>
                
                <div>
                    <label for="spec_code" class="block text-sm font-medium text-gray-700 mb-1">Mã thông số hãng</label>
                    <input type="text" id="spec_code" name="spec_code"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ví dụ: ENG-001, TRANS-002">
                </div>
                
                <div>
                    <label for="spec_description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                    <textarea id="spec_description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Mô tả chi tiết về thông số..."></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" id="is_important" name="is_important" value="1"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_important" class="ml-2 block text-sm text-gray-700">Thông số quan trọng</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="is_highlighted" name="is_highlighted" value="1"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_highlighted" class="ml-2 block text-sm text-gray-700">Thông số nổi bật</label>
                        </div>
                    </div>
                    <div>
                        <label for="sort_order_spec" class="block text-sm font-medium text-gray-700 mb-1">Thứ tự</label>
                        <input type="number" id="sort_order_spec" name="sort_order" value="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </form>
        </div>
        
        {{-- Footer --}}
        <div class="flex items-center justify-between p-6 border-t border-gray-200">
            <button type="button" id="cancelSpecBtn"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                Hủy
            </button>
            <button type="submit" form="specForm"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-save mr-2"></i>
                Lưu thông số
            </button>
        </div>
    </div>
</div>

{{-- Specification Delete Confirmation Dialog --}}
<div id="specDeleteDialog" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
            <div class="text-center">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Xác nhận xóa thông số</h3>
                <p class="text-sm text-gray-500" id="specDeleteMessage">
                    Bạn có chắc chắn muốn xóa thông số này không? Hành động này không thể hoàn tác.
                </p>
            </div>
        </div>
        <div class="flex items-center justify-between space-x-3 px-6 py-4 bg-gray-50 rounded-b-xl">
            <button type="button" id="cancelSpecDeleteBtn"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                Hủy
            </button>
            <button type="button" id="confirmSpecDeleteBtn"
                    class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                <i class="fas fa-trash mr-2"></i>
                Xóa
            </button>
        </div>
    </div>
</div>

{{-- Feature Management Modal --}}
<div id="featureModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        {{-- Header --}}
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 id="featureModalTitle" class="text-xl font-semibold text-gray-900">Thêm tính năng mới</h2>
            <button type="button" id="closeFeatureModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        {{-- Body --}}
        <div class="px-6 pb-6">
            <form id="featureForm" class="space-y-4">
                <input type="hidden" id="featureId" name="feature_id">
                <input type="hidden" name="car_variant_id" value="{{ $carvariant->id }}">
                
                <div class="!mt-0">
                    <label for="feature_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Tên tính năng <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="feature_name" name="feature_name"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ví dụ: Hệ thống phanh ABS">
                </div>
                
                <div>
                    <label for="feature_description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                    <textarea id="feature_description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Mô tả chi tiết về tính năng..."></textarea>
                </div>
                
                <div>
                    <label for="feature_code" class="block text-sm font-medium text-gray-700 mb-1">Mã tính năng</label>
                    <input type="text" id="feature_code" name="feature_code"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ví dụ: ABS-001">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="feature_category" class="block text-sm font-medium text-gray-700 mb-1">Danh mục</label>
                        <select id="feature_category" name="category"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="safety" selected>An toàn</option>
                            <option value="comfort">Tiện nghi</option>
                            <option value="technology">Công nghệ</option>
                            <option value="performance">Hiệu suất</option>
                            <option value="exterior">Ngoại thất</option>
                            <option value="interior">Nội thất</option>
                            <option value="entertainment">Giải trí</option>
                            <option value="convenience">Tiện ích</option>
                            <option value="wheels">Bánh xe</option>
                            <option value="audio">Âm thanh</option>
                            <option value="navigation">Định vị</option>
                        </select>
                    </div>
                    <div>
                        <label for="feature_availability" class="block text-sm font-medium text-gray-700 mb-1">Tình trạng</label>
                        <select id="feature_availability" name="availability"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="standard" selected>Tiêu chuẩn</option>
                            <option value="optional">Tùy chọn</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="importance" class="block text-sm font-medium text-gray-700 mb-1">Mức độ quan trọng</label>
                        <select id="importance" name="importance"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="essential" selected>Thiết yếu</option>
                            <option value="important">Quan trọng</option>
                            <option value="nice_to_have">Tốt nếu có</option>
                            <option value="luxury">Sang trọng</option>
                        </select>
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Giá phụ phí (VND)</label>
                        <input type="number" id="price" name="price" value="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" id="is_included" name="is_included" value="1" checked
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_included" class="ml-2 block text-sm text-gray-700">Có sẵn</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="feature_is_active" name="is_active" value="1" checked
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="feature_is_active" class="ml-2 block text-sm text-gray-700">Hiển thị</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="feature_is_featured" name="is_featured" value="1"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="feature_is_featured" class="ml-2 block text-sm text-gray-700">Nổi bật</label>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" id="feature_is_popular" name="is_popular" value="1"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="feature_is_popular" class="ml-2 block text-sm text-gray-700">Phổ biến</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="is_recommended" name="is_recommended" value="1"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_recommended" class="ml-2 block text-sm text-gray-700">Khuyến nghị</label>
                        </div>
                        <div>
                            <label for="sort_order_feature" class="block text-sm font-medium text-gray-700 mb-1">Thứ tự</label>
                            <input type="number" id="sort_order_feature" name="sort_order" value="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        {{-- Footer --}}
        <div class="flex items-center justify-between p-6 border-t border-gray-200">
            <button type="button" id="cancelFeatureBtn"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                Hủy
            </button>
            <button type="submit" form="featureForm"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-save mr-2"></i>
                Lưu tính năng
            </button>
        </div>
    </div>
</div>

{{-- Feature Delete Confirmation Dialog --}}
<div id="featureDeleteDialog" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
            <div class="text-center">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Xác nhận xóa tính năng</h3>
                <p class="text-sm text-gray-500" id="featureDeleteMessage">
                    Bạn có chắc chắn muốn xóa tính năng này không? Hành động này không thể hoàn tác.
                </p>
            </div>
        </div>
        <div class="flex items-center justify-between space-x-3 px-6 py-4 bg-gray-50 rounded-b-xl">
            <button type="button" id="cancelFeatureDeleteBtn"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                Hủy
            </button>
            <button type="button" id="confirmFeatureDeleteBtn"
                    class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                <i class="fas fa-trash mr-2"></i>
                Xóa
            </button>
        </div>
    </div>
</div>

{{-- Sequential Validation & Auto-Generation System --}}
<script>
// ===== SEQUENTIAL VALIDATION SYSTEM =====

// Comprehensive validation for all tabs
function validateAllTabs() {
    // 1. Validate Basic Info Tab
    const basicValidation = validateBasicTab();
    if (!basicValidation.isValid) {
        return basicValidation;
    }
    
    // 2. Validate Colors Tab (if has colors)
    const colorsValidation = validateColorsTab();
    if (!colorsValidation.isValid) {
        return colorsValidation;
    }
    
    // 3. Validate Specifications Tab (if has specs)
    const specsValidation = validateSpecificationsTab();
    if (!specsValidation.isValid) {
        return specsValidation;
    }
    
    // 4. Validate Features Tab (if has features)
    const featuresValidation = validateFeaturesTab();
    if (!featuresValidation.isValid) {
        return featuresValidation;
    }
    
    // 5. Validate Images Tab (if has images)
    const imagesValidation = validateImagesTab();
    if (!imagesValidation.isValid) {
        return imagesValidation;
    }
    
    return { isValid: true };
}

// Validate Basic Info Tab
function validateBasicTab() {
    const fieldsToValidate = [
        { selector: '#car_model_id', name: 'car_model_id', message: 'Vui lòng chọn dòng xe trước khi tiếp tục.' },
        { selector: '#name', name: 'name', message: 'Vui lòng nhập tên phiên bản trước khi tiếp tục.' },
        { selector: '#base_price', name: 'base_price', message: 'Vui lòng nhập giá gốc trước khi tiếp tục.' },
        { selector: '#current_price', name: 'current_price', message: 'Vui lòng nhập giá hiện tại trước khi tiếp tục.' }
    ];
    
    for (const field of fieldsToValidate) {
        const element = document.querySelector(field.selector);
        if (element) {
            const isValid = validateField(field.name, element.value, element);
            if (!isValid) {
                return {
                    isValid: false,
                    element: element,
                    message: field.message,
                    tabId: 'basic'
                };
            }
        }
    }
    
    return { isValid: true };
}

// Validate Colors Tab
function validateColorsTab() {
    const colorsContainer = document.getElementById('colorsContainer');
    if (!colorsContainer) return { isValid: true };
    
    const colorCards = colorsContainer.querySelectorAll('.color-item');
    
    for (let i = 0; i < colorCards.length; i++) {
        const card = colorCards[i];
        const colorNameInput = card.querySelector('input[name*="color_name"]');
        
        if (colorNameInput) {
            const colorName = colorNameInput.value.trim();
            if (!colorName) {
                return {
                    isValid: false,
                    element: colorNameInput,
                    message: `Vui lòng nhập tên cho màu thứ ${i + 1}.`,
                    tabId: 'colors'
                };
            }
            
            if (colorName.length < 2) {
                return {
                    isValid: false,
                    element: colorNameInput,
                    message: `Tên màu thứ ${i + 1} phải có ít nhất 2 ký tự.`,
                    tabId: 'colors'
                };
            }
        }
    }
    
    return { isValid: true };
}

// Validate Specifications Tab
function validateSpecificationsTab() {
    const specsContainer = document.getElementById('specificationsContainer');
    if (!specsContainer) return { isValid: true };
    
    const specCards = specsContainer.querySelectorAll('.spec-item');
    
    for (let i = 0; i < specCards.length; i++) {
        const card = specCards[i];
        const categorySelect = card.querySelector('select[name*="category"]');
        const specNameInput = card.querySelector('input[name*="spec_name"]');
        const specValueInput = card.querySelector('input[name*="spec_value"]');
        
        if (categorySelect && !categorySelect.value) {
            return {
                isValid: false,
                element: categorySelect,
                message: `Vui lòng chọn danh mục cho thông số thứ ${i + 1}.`,
                tabId: 'specifications'
            };
        }
        
        if (specNameInput && !specNameInput.value.trim()) {
            return {
                isValid: false,
                element: specNameInput,
                message: `Vui lòng nhập tên thông số thứ ${i + 1}.`,
                tabId: 'specifications'
            };
        }
        
        if (specValueInput && !specValueInput.value.trim()) {
            return {
                isValid: false,
                element: specValueInput,
                message: `Vui lòng nhập giá trị thông số thứ ${i + 1}.`,
                tabId: 'specifications'
            };
        }
    }
    
    return { isValid: true };
}

// Validate Features Tab
function validateFeaturesTab() {
    const featuresContainer = document.getElementById('featuresContainer');
    if (!featuresContainer) return { isValid: true };
    
    const featureCards = featuresContainer.querySelectorAll('.feature-item');
    
    for (let i = 0; i < featureCards.length; i++) {
        const card = featureCards[i];
        const categorySelect = card.querySelector('select[name*="category"]');
        const featureNameInput = card.querySelector('input[name*="feature_name"]');
        
        if (categorySelect && !categorySelect.value) {
            return {
                isValid: false,
                element: categorySelect,
                message: `Vui lòng chọn danh mục cho tính năng thứ ${i + 1}.`,
                tabId: 'features'
            };
        }
        
        if (featureNameInput && !featureNameInput.value.trim()) {
            return {
                isValid: false,
                element: featureNameInput,
                message: `Vui lòng nhập tên tính năng thứ ${i + 1}.`,
                tabId: 'features'
            };
        }
    }
    
    return { isValid: true };
}

// Validate Images Tab
function validateImagesTab() {
    // For edit page, images are optional since they might already exist
    // Only validate new image uploads if any
    const newImageInputs = document.querySelectorAll('input[type="file"][name="images[]"]');
    
    for (let i = 0; i < newImageInputs.length; i++) {
        const fileInput = newImageInputs[i];
        if (fileInput.files && fileInput.files.length > 0) {
            // If user selected new image, validate metadata
            const altTextInput = document.getElementById('image_alt_text');
            const imageTypeSelect = document.getElementById('image_type');
            
            // Auto-generate alt text if empty
            if (altTextInput && !altTextInput.value.trim()) {
                const autoAltText = generateAutoAltTextEdit();
                altTextInput.value = autoAltText;
                altTextInput.classList.add('bg-yellow-50', 'border-yellow-300');
                altTextInput.title = 'Alt text được tự động sinh';
            }
            
            // Auto-generate title if empty
            const titleInput = document.getElementById('image_title');
            if (titleInput && !titleInput.value.trim()) {
                const autoTitle = generateAutoTitleEdit();
                titleInput.value = autoTitle;
                titleInput.classList.add('bg-yellow-50', 'border-yellow-300');
                titleInput.title = 'Tiêu đề được tự động sinh';
            }
            
            // Auto-generate description if empty
            const descriptionTextarea = document.getElementById('image_description');
            if (descriptionTextarea && !descriptionTextarea.value.trim()) {
                const autoDescription = generateAutoDescriptionEdit();
                descriptionTextarea.value = autoDescription;
                descriptionTextarea.classList.add('bg-yellow-50', 'border-yellow-300');
                descriptionTextarea.title = 'Mô tả được tự động sinh';
            }
        }
    }
    
    return { isValid: true };
}

// Individual field validation
function validateField(fieldName, value, fieldElement) {
    let isValid = true;
    
    // Clear previous visual error state
    const existingError = fieldElement.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
    fieldElement.classList.remove('border-red-300');
    
    // Validate based on field name
    switch(fieldName) {
        case 'car_model_id':
            if (!value || value === '') {
                isValid = false;
            }
            break;
            
        case 'name':
            if (!value || value.trim() === '') {
                isValid = false;
            } else if (value.length > 255) {
                isValid = false;
            }
            break;
            
        case 'base_price':
            if (!value || value === '') {
                isValid = false;
            } else if (isNaN(value) || parseFloat(value) < 0) {
                isValid = false;
            }
            break;
            
        case 'current_price':
            if (!value || value === '') {
                isValid = false;
            } else if (isNaN(value) || parseFloat(value) < 0) {
                isValid = false;
            }
            break;
    }
    
    // Add visual indicator if invalid (red border)
    if (!isValid) {
        fieldElement.classList.add('border-red-300');
    }
    
    return isValid;
}

// Switch to specific tab
function switchToTab(tabId) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show target tab content
    const targetContent = document.getElementById(tabId + '-tab');
    if (targetContent) {
        targetContent.classList.remove('hidden');
    }
    
    // Activate target tab button
    const targetButton = document.querySelector(`[data-tab="${tabId}"]`);
    if (targetButton) {
        targetButton.classList.remove('border-transparent', 'text-gray-500');
        targetButton.classList.add('border-blue-500', 'text-blue-600');
    }
}

// ===== AUTO-GENERATION SYSTEM =====

// Auto-generate alt text for edit page
function generateAutoAltTextEdit() {
    const variantName = document.getElementById('name')?.value?.trim() || 'Phiên bản xe';
    const carModelSelect = document.getElementById('car_model_id');
    let modelName = 'xe';
    
    // Extract model name more carefully
    if (carModelSelect?.selectedOptions[0]?.textContent) {
        const fullText = carModelSelect.selectedOptions[0].textContent.trim();
        const parts = fullText.split(' - ');
        if (parts.length > 1) {
            modelName = parts[1].trim();
        } else {
            modelName = fullText.trim();
        }
    }
    
    // Get angle
    const angleSelect = document.getElementById('image_angle');
    const angle = angleSelect?.value || 'front';
    
    // Generate contextual alt text with proper spacing
    let altText = `${variantName} ${modelName}`.trim();
    
    // Add angle/view description
    const angleDescriptions = {
        'front': 'góc nhìn phía trước',
        'side': 'góc nhìn bên hông',
        'rear': 'góc nhìn phía sau',
        'interior': 'khoảng nội thất',
        'dashboard': 'bảng điều khiển',
        'seats': 'ghế ngồi',
        'steering': 'vô lăng',
        'headlight': 'đèn pha',
        'grille': 'lưới tản nhiệt',
        'wheel': 'bánh xe',
        'door': 'cửa xe',
        'trunk': 'cốp xe'
    };
    
    if (angleDescriptions[angle]) {
        altText += ` - ${angleDescriptions[angle]}`;
    }
    
    return altText.trim();
}

// Auto-generate title for edit page
function generateAutoTitleEdit() {
    const variantName = document.getElementById('name')?.value?.trim() || 'Phiên bản xe';
    const carModelSelect = document.getElementById('car_model_id');
    let modelName = 'xe';
    
    // Extract model name more carefully
    if (carModelSelect?.selectedOptions[0]?.textContent) {
        const fullText = carModelSelect.selectedOptions[0].textContent.trim();
        const parts = fullText.split(' - ');
        if (parts.length > 1) {
            modelName = parts[1].trim();
        } else {
            modelName = fullText.trim();
        }
    }
    
    return `Hình ảnh - ${variantName} ${modelName}`.trim();
}

// Auto-generate description for edit page
function generateAutoDescriptionEdit() {
    const variantName = document.getElementById('name')?.value?.trim() || 'Phiên bản xe';
    const carModelSelect = document.getElementById('car_model_id');
    let modelName = 'xe';
    
    // Extract model name more carefully
    if (carModelSelect?.selectedOptions[0]?.textContent) {
        const fullText = carModelSelect.selectedOptions[0].textContent.trim();
        const parts = fullText.split(' - ');
        if (parts.length > 1) {
            modelName = parts[1].trim();
        } else {
            modelName = fullText.trim();
        }
    }
    
    // Get image type
    const imageTypeSelect = document.getElementById('image_type');
    const imageType = imageTypeSelect?.value || 'gallery';
    
    const typeDescriptions = {
        'gallery': 'Hình ảnh tổng quan',
        'exterior': 'Hình ảnh ngoại thất',
        'interior': 'Hình ảnh nội thất'
    };
    
    const carInfo = `${variantName} ${modelName}`.trim();
    return `${typeDescriptions[imageType] || 'Hình ảnh'} của ${carInfo}. Chất lượng cao, thể hiện đầy đủ chi tiết và đặc điểm nổi bật của xe.`.trim();
}

// Clear all flash messages
function clearFlashMessages() {
    const flashMessages = document.querySelectorAll('.flash-message, [class*="flash-"], .alert, .toast');
    flashMessages.forEach(msg => {
        if (msg.remove) {
            msg.remove();
        } else {
            msg.style.display = 'none';
        }
    });
}

// Add event listeners for auto-generation
document.addEventListener('DOMContentLoaded', function() {
    // Add listeners for image metadata auto-generation
    const imageTypeSelect = document.getElementById('image_type');
    const angleSelect = document.getElementById('image_angle');
    const variantNameInput = document.getElementById('name');
    const carModelSelect = document.getElementById('car_model_id');
    
    const regenerateImageMetadata = () => {
        const altTextInput = document.getElementById('image_alt_text');
        const titleInput = document.getElementById('image_title');
        const descriptionTextarea = document.getElementById('image_description');
        
        // Only regenerate if field was auto-generated (has yellow background)
        if (altTextInput && altTextInput.classList.contains('bg-yellow-50')) {
            const newAltText = generateAutoAltTextEdit();
            altTextInput.value = newAltText;
        }
        
        if (titleInput && titleInput.classList.contains('bg-yellow-50')) {
            const newTitle = generateAutoTitleEdit();
            titleInput.value = newTitle;
        }
        
        if (descriptionTextarea && descriptionTextarea.classList.contains('bg-yellow-50')) {
            const newDescription = generateAutoDescriptionEdit();
            descriptionTextarea.value = newDescription;
        }
    };
    
    // Add listeners to trigger regeneration
    if (imageTypeSelect) {
        imageTypeSelect.addEventListener('change', regenerateImageMetadata);
    }
    
    if (angleSelect) {
        angleSelect.addEventListener('change', regenerateImageMetadata);
    }
    
    if (variantNameInput) {
        variantNameInput.addEventListener('input', regenerateImageMetadata);
    }
    
    if (carModelSelect) {
        carModelSelect.addEventListener('change', regenerateImageMetadata);
    }
    
    // Allow user to manually edit and remove auto-generation
    ['image_alt_text', 'image_title', 'image_description'].forEach(fieldId => {
        const input = document.getElementById(fieldId);
        if (input) {
            input.addEventListener('input', function() {
                // Remove auto-generation styling when user manually edits
                this.classList.remove('bg-yellow-50', 'border-yellow-300');
                this.title = '';
            });
        }
    });
    
    // Add flash message clear listeners
    const allInputs = document.querySelectorAll('input, select, textarea');
    allInputs.forEach(input => {
        input.addEventListener('input', clearFlashMessages);
        input.addEventListener('change', clearFlashMessages);
        input.addEventListener('focus', clearFlashMessages);
    });
});

// Override existing form submission to include validation
const mainUpdateBtn = document.getElementById('mainUpdateBtn');
if (mainUpdateBtn) {
    mainUpdateBtn.addEventListener('click', function(e) {
        // Run sequential validation before saving
        const validationResult = validateAllTabs();
        if (!validationResult.isValid) {
            e.preventDefault();
            e.stopPropagation();
            
            // Focus the field with error
            if (validationResult.element) {
                validationResult.element.focus();
                validationResult.element.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            // Switch to the tab with error if needed
            if (validationResult.tabId) {
                switchToTab(validationResult.tabId);
            }
            
            // Show specific flash message
            if (typeof showMessage === 'function') {
                showMessage(validationResult.message, 'error');
            } else {
                alert(validationResult.message);
            }
            
            return false;
        }
    });
}
</script>
@endsection
