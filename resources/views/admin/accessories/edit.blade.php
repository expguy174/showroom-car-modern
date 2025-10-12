@extends('layouts.admin')

@section('title', 'Chỉnh sửa phụ kiện')

@section('content')
{{-- Flash Messages Component --}}
<x-admin.flash-messages 
    :show-icons="true"
    :dismissible="true"
    position="top-right"
    :auto-hide="5000"
/>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-edit text-blue-600 mr-3"></i>
                    Chỉnh sửa phụ kiện: {{ $accessory->name }}
                </h1>
                <p class="text-sm text-gray-600 mt-1">Chỉnh sửa thông tin phụ kiện xe hơi với đầy đủ thông tin</p>
            </div>
            <a href="{{ route('admin.accessories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại
                </a>
            </div>
        </div>

    {{-- Tab Navigation --}}
    <div class="border-b border-gray-200">
        <nav class="flex flex-wrap gap-x-4 gap-y-2 px-6" aria-label="Tabs">
            <button type="button" class="tab-button active py-3 px-2 border-b-2 border-blue-500 font-medium text-sm text-blue-600 whitespace-nowrap" data-tab="basic">
                <i class="fas fa-info-circle mr-1.5"></i>
                <span class="hidden sm:inline">Thông tin</span>
            </button>
            <button type="button" class="tab-button py-3 px-2 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap" data-tab="pricing">
                <i class="fas fa-tags mr-1.5"></i>
                <span class="hidden sm:inline">Giá</span>
            </button>
            <button type="button" class="tab-button py-3 px-2 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap" data-tab="inventory">
                <i class="fas fa-boxes mr-1.5"></i>
                <span class="hidden sm:inline">Kho</span>
            </button>
            <button type="button" class="tab-button py-3 px-2 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap" data-tab="compatibility">
                <i class="fas fa-car mr-1.5"></i>
                <span class="hidden sm:inline">Xe</span>
            </button>
            <button type="button" class="tab-button py-3 px-2 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap" data-tab="specifications">
                <i class="fas fa-cogs mr-1.5"></i>
                <span class="hidden sm:inline">Thông số</span> (<span id="specs-count">
                    @php
                        $specsCount = $accessory->specifications;
                        if (is_string($specsCount)) {
                            $specsCount = json_decode($specsCount, true) ?? [];
                        }
                        $specsCount = is_array($specsCount) ? count($specsCount) : 0;
                    @endphp
                    {{ $specsCount }}
                </span>)
            </button>
            <button type="button" class="tab-button py-3 px-2 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap" data-tab="features">
                <i class="fas fa-star mr-1.5"></i>
                <span class="hidden sm:inline">Tính năng</span> (<span id="features-count">
                    @php
                        $featuresCount = $accessory->features;
                        if (is_string($featuresCount)) {
                            $featuresCount = json_decode($featuresCount, true) ?? [];
                        }
                        $featuresCount = is_array($featuresCount) ? count($featuresCount) : 0;
                    @endphp
                    {{ $featuresCount }}
                </span>)
            </button>
            <button type="button" class="tab-button py-3 px-2 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap" data-tab="images">
                <i class="fas fa-images mr-1.5"></i>
                <span class="hidden sm:inline">Ảnh</span> (<span id="images-count">
                    @php
                        $galleryCount = $accessory->gallery ?? [];
                        
                        // Safe count calculation
                        if (is_string($galleryCount)) {
                            $galleryCount = json_decode($galleryCount, true) ?? [];
                        }
                        
                        $galleryCount = is_array($galleryCount) ? count($galleryCount) : 0;
                    @endphp
                    {{ $galleryCount }}
                </span>)
            </button>
            <button type="button" class="tab-button py-3 px-2 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap" data-tab="seo">
                <i class="fas fa-search mr-1.5"></i>
                <span class="hidden sm:inline">SEO</span>
            </button>
        </nav>
    </div>

    {{-- Form --}}
    <form id="accessoryEditForm" action="{{ route('admin.accessories.update', $accessory->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Tab Contents --}}
        <div class="p-6">
            {{-- Basic Info Tab --}}
            <div id="basic-tab" class="tab-content">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Thông tin cơ bản
                        </h3>
                        
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Tên phụ kiện <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('name', $accessory->name) }}" placeholder="Ví dụ: Lót sàn, Phim cách nhiệt...">
                        </div>

                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                                Mã SKU <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="sku" id="sku" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('sku', $accessory->sku) }}" placeholder="ACC-001">
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Danh mục <span class="text-red-500">*</span>
                            </label>
                            <select name="category" id="category" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Chọn danh mục</option>
                                <option value="interior" {{ old('category', $accessory->category) == 'interior' ? 'selected' : '' }}>Nội thất</option>
                                <option value="exterior" {{ old('category', $accessory->category) == 'exterior' ? 'selected' : '' }}>Ngoại thất</option>
                                <option value="electronics" {{ old('category', $accessory->category) == 'electronics' ? 'selected' : '' }}>Điện tử</option>
                                <option value="performance" {{ old('category', $accessory->category) == 'performance' ? 'selected' : '' }}>Hiệu suất</option>
                                <option value="safety" {{ old('category', $accessory->category) == 'safety' ? 'selected' : '' }}>An toàn</option>
                                <option value="maintenance" {{ old('category', $accessory->category) == 'maintenance' ? 'selected' : '' }}>Bảo dưỡng</option>
                            </select>
                        </div>

                        <div>
                            <label for="subcategory" class="block text-sm font-medium text-gray-700 mb-2">Danh mục con</label>
                            <input type="text" name="subcategory" id="subcategory" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('subcategory', $accessory->subcategory) }}" placeholder="Ví dụ: Thảm lót sàn">
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-align-left text-green-600 mr-2"></i>
                            Mô tả sản phẩm
                        </h3>

                        <div>
                            <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả ngắn</label>
                            <textarea name="short_description" id="short_description" rows="3" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                      placeholder="Mô tả ngắn gọn về phụ kiện...">{{ old('short_description', $accessory->short_description) }}</textarea>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả chi tiết</label>
                            <textarea name="description" id="description" rows="6" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                      placeholder="Mô tả chi tiết về phụ kiện, tính năng, lợi ích...">{{ old('description', $accessory->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">Trọng lượng (kg)</label>
                                <input type="number" name="weight" id="weight" step="0.01"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('weight', $accessory->weight) }}" placeholder="0.5">
                            </div>
                            <div>
                                <label for="dimensions" class="block text-sm font-medium text-gray-700 mb-2">Kích thước</label>
                                <input type="text" name="dimensions" id="dimensions" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('dimensions', $accessory->dimensions) }}" placeholder="L x W x H">
                            </div>
                            <div>
                                <label for="material" class="block text-sm font-medium text-gray-700 mb-2">Chất liệu</label>
                                <input type="text" name="material" id="material" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('material', $accessory->material) }}" placeholder="Nhựa, Da, Kim loại...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pricing Tab --}}
            <div id="pricing-tab" class="tab-content hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-tags text-green-600 mr-2"></i>
                            Thông tin giá
                        </h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="base_price" class="block text-sm font-medium text-gray-700 mb-2">
                                    Giá niêm yết <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="base_price" id="base_price" 
                                           class="block w-full px-3 py-2 pr-12 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                           value="{{ old('base_price', number_format($accessory->base_price ?? 0, 0, '.', '')) }}" placeholder="0" onchange="updateCurrentPriceFromBase()">
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
                                           class="block w-full px-3 py-2 pr-12 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                           value="{{ old('current_price', number_format($accessory->current_price ?? 0, 0, '.', '')) }}" placeholder="0">
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
                                   {{ old('is_on_sale', $accessory->is_on_sale) ? 'checked' : '' }}>
                            <label for="is_on_sale" class="ml-2 block text-sm text-gray-900">
                                Đang khuyến mãi
                            </label>
                        </div>

                        <div id="sale-dates" class="grid grid-cols-2 gap-4" style="display: {{ old('is_on_sale', $accessory->is_on_sale) ? 'grid' : 'none' }}">
                            <div>
                                <label for="sale_start_date" class="block text-sm font-medium text-gray-700 mb-2">Ngày bắt đầu KM</label>
                                <input type="date" name="sale_start_date" id="sale_start_date" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('sale_start_date', $accessory->sale_start_date ? $accessory->sale_start_date->format('Y-m-d') : '') }}">
                            </div>
                            <div>
                                <label for="sale_end_date" class="block text-sm font-medium text-gray-700 mb-2">Ngày kết thúc KM</label>
                                <input type="date" name="sale_end_date" id="sale_end_date" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('sale_end_date', $accessory->sale_end_date ? $accessory->sale_end_date->format('Y-m-d') : '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-tools text-purple-600 mr-2"></i>
                            Dịch vụ lắp đặt
                        </h3>

                        <div class="flex items-center">
                            <input type="hidden" name="installation_service_available" value="0">
                            <input type="checkbox" name="installation_service_available" id="installation_service_available" value="1" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                   {{ old('installation_service_available', $accessory->installation_service_available) ? 'checked' : '' }}>
                            <label for="installation_service_available" class="ml-2 block text-sm text-gray-900">
                                Có dịch vụ lắp đặt
                            </label>
                        </div>

                        <div id="installation-details" style="display: {{ old('installation_service_available', $accessory->installation_service_available) ? 'block' : 'none' }}">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="installation_fee" class="block text-sm font-medium text-gray-700 mb-2">Phí lắp đặt</label>
                                    <div class="relative">
                                        <input type="number" name="installation_fee" id="installation_fee" 
                                               class="block w-full px-3 py-2 pr-12 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                               value="{{ old('installation_fee', $accessory->installation_fee ? intval($accessory->installation_fee) : '') }}" placeholder="0">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">VNĐ</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label for="installation_time_minutes" class="block text-sm font-medium text-gray-700 mb-2">Thời gian lắp đặt (phút)</label>
                                    <input type="number" name="installation_time_minutes" id="installation_time_minutes" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                           value="{{ old('installation_time_minutes', $accessory->installation_time_minutes) }}" placeholder="30">
                                </div>
                            </div>
                            
                            <div>
                                <label for="installation_requirements" class="block text-sm font-medium text-gray-700 mb-2">Yêu cầu lắp đặt</label>
                                <textarea name="installation_requirements" id="installation_requirements" rows="3" 
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                          placeholder="Yêu cầu đặc biệt khi lắp đặt...">{{ old('installation_requirements', $accessory->installation_requirements) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Inventory Tab --}}
            <div id="inventory-tab" class="tab-content hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-boxes text-blue-600 mr-2"></i>
                            Quản lý tồn kho
                        </h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    Số lượng tồn kho <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="stock_quantity" id="stock_quantity" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('stock_quantity', $accessory->stock_quantity) }}" placeholder="Nhập số lượng tồn kho" min="1">
                            </div>
                            <div>
                                <label for="stock_status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái kho</label>
                                <select name="stock_status" id="stock_status" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="in_stock" {{ old('stock_status', $accessory->stock_status) == 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                                    <option value="low_stock" {{ old('stock_status', $accessory->stock_status) == 'low_stock' ? 'selected' : '' }}>Sắp hết hàng</option>
                                    <option value="out_of_stock" {{ old('stock_status', $accessory->stock_status) == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                                    <option value="discontinued" {{ old('stock_status', $accessory->stock_status) == 'discontinued' ? 'selected' : '' }}>Ngừng kinh doanh</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tùy chọn màu sắc</label>
                            <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                                <div id="color-options-container" class="space-y-3">
                                    <!-- Color options will be populated by JavaScript -->
                                </div>
                                <div class="mt-3 flex gap-2">
                                    <button type="button" id="add-color-option" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-plus mr-2"></i>
                                        Thêm màu sắc
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="color_options" id="color_options_hidden" value="{{ old('color_options', json_encode($accessory->color_options ?? [])) }}">
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-shield-alt text-green-600 mr-2"></i>
                            Bảo hành & Chính sách
                        </h3>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="warranty_months" class="block text-sm font-medium text-gray-700 mb-2">Thời gian bảo hành (tháng)</label>
                                <input type="number" name="warranty_months" id="warranty_months" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('warranty_months', $accessory->warranty_months) }}" placeholder="12" min="0">
                            </div>
                            <div>
                                <label for="return_policy_days" class="block text-sm font-medium text-gray-700 mb-2">Chính sách đổi trả (ngày)</label>
                                <input type="number" name="return_policy_days" id="return_policy_days" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('return_policy_days', $accessory->return_policy_days) }}" placeholder="30" min="0">
                            </div>
                        </div>

                        <div>
                            <label for="warranty_terms" class="block text-sm font-medium text-gray-700 mb-2">Điều kiện bảo hành</label>
                            <textarea name="warranty_terms" id="warranty_terms" rows="4" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                      placeholder="Điều kiện và điều khoản bảo hành...">{{ old('warranty_terms', $accessory->warranty_terms) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Compatibility Tab --}}
            <div id="compatibility-tab" class="tab-content hidden">
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-car text-blue-600 mr-2"></i>
                        Tương thích với xe
                    </h3>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hãng xe tương thích</label>
                            <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                                <div id="compatible-brands-container" class="space-y-2">
                                    <!-- Compatible brands will be populated by JavaScript -->
                                </div>
                                <button type="button" id="add-compatible-brand" class="mt-2 inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-plus mr-2"></i>
                                    Thêm hãng xe
                                </button>
                            </div>
                            <input type="hidden" name="compatible_car_brands" id="compatible_car_brands_hidden" value="{{ old('compatible_car_brands', json_encode($accessory->compatible_car_brands ?? [])) }}">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dòng xe tương thích</label>
                            <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                                <div id="compatible-models-container" class="space-y-2">
                                    <!-- Compatible models will be populated by JavaScript -->
                                </div>
                                <button type="button" id="add-compatible-model" class="mt-2 inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-plus mr-2"></i>
                                    Thêm dòng xe
                                </button>
                            </div>
                            <input type="hidden" name="compatible_car_models" id="compatible_car_models_hidden" value="{{ old('compatible_car_models', json_encode($accessory->compatible_car_models ?? [])) }}">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Năm sản xuất tương thích</label>
                            <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                                <div id="compatible-years-container" class="space-y-2">
                                    <!-- Compatible years will be populated by JavaScript -->
                                </div>
                                <button type="button" id="add-compatible-year" class="mt-2 inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-plus mr-2"></i>
                                    Thêm năm sản xuất
                                </button>
                            </div>
                            <input type="hidden" name="compatible_car_years" id="compatible_car_years_hidden" value="{{ old('compatible_car_years', json_encode($accessory->compatible_car_years ?? [])) }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Specifications Tab --}}
            <div id="specifications-tab" class="tab-content hidden">
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-cogs text-blue-600 mr-2"></i>
                            Thông số kỹ thuật
                        </h3>
                        <button type="button" id="addSpecBtn" class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                            <i class="fas fa-plus mr-2"></i>
                            Thêm thông số
                        </button>
                    </div>

                    <div id="specifications-container" class="space-y-4">
                        <!-- Specifications will be populated by JavaScript -->
                    </div>

                    <div id="no-specifications" class="text-center py-8 text-gray-500" style="display: none;">
                        <i class="fas fa-cogs text-4xl mb-4"></i>
                        <p>Chưa có thông số kỹ thuật nào. Nhấn "Thêm thông số" để bắt đầu.</p>
                    </div>
                    <input type="hidden" name="specifications" id="specifications_hidden" value="{{ old('specifications', json_encode($accessory->specifications ?? [])) }}">
                </div>
            </div>

            {{-- Features Tab --}}
            <div id="features-tab" class="tab-content hidden">
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-star text-blue-600 mr-2"></i>
                            Tính năng nổi bật
                        </h3>
                        <button type="button" id="addFeatureBtn" class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                            <i class="fas fa-plus mr-2"></i>
                            Thêm tính năng
                        </button>
                    </div>

                    <div id="features-container" class="space-y-4">
                        <!-- Features will be populated by JavaScript -->
                    </div>

                    <div id="no-features" class="text-center py-8 text-gray-500" style="display: none;">
                        <i class="fas fa-star text-4xl mb-4"></i>
                        <p>Chưa có tính năng nào. Nhấn "Thêm tính năng" để bắt đầu.</p>
                    </div>
                    <input type="hidden" name="features" id="features_hidden" value="{{ old('features', json_encode($accessory->features ?? [])) }}">
                </div>
            </div>

            {{-- Images Tab --}}
            <div id="images-tab" class="tab-content hidden">
                <div class="space-y-6">
                    {{-- Gallery Management Section --}}
                    @php
                        // Debug gallery data
                        $gallery = $accessory->gallery;
                        
                        // Debug output
                        // Always show debug for now
                        echo "<!-- DEBUG: Raw gallery data: " . json_encode($gallery) . " -->";
                        echo "<!-- DEBUG: Gallery type: " . gettype($gallery) . " -->";
                        echo "<!-- DEBUG: Gallery count: " . (is_array($gallery) ? count($gallery) : 'not array') . " -->";
                        
                        // Ensure we have a safe array
                        if (is_string($gallery)) {
                            $gallery = json_decode($gallery, true) ?? [];
                        } elseif (!is_array($gallery)) {
                            $gallery = [];
                        }
                        
                        // Final safety check
                        $gallery = is_array($gallery) ? $gallery : [];
                        
                        // Sort gallery: Primary first, then by sort_order
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
                        
                        // Always show debug for now
                        echo "<!-- DEBUG: Processed gallery: " . json_encode($gallery) . " -->";
                        echo "<!-- DEBUG: Final gallery count: " . count($gallery) . " -->";
                    @endphp
                    
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-images text-blue-600 mr-2"></i>
                                <span id="gallery-title">Quản lý hình ảnh</span>
                            </h4>
                            <button type="button" onclick="openAddImageModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Thêm hình ảnh
                            </button>
                        </div>
                        <div id="gallery-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @if($gallery && count($gallery) > 0)
                            @foreach($gallery as $index => $image)
                            <div class="bg-white p-3 rounded-lg border border-gray-200 relative image-card" data-image-index="{{ $index }}">
                                {{-- Action Buttons --}}
                                <div class="absolute top-2 right-2 z-10 flex gap-2">
                                    <button type="button" 
                                            class="edit-image-btn bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-full shadow-lg transition-colors"
                                            data-index="{{ $index }}"
                                            data-accessory-id="{{ $accessory->id }}"
                                            title="Sửa ảnh">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button type="button" 
                                            class="delete-image-btn bg-red-500 hover:bg-red-600 text-white p-2 rounded-full shadow-lg transition-colors"
                                            data-index="{{ $index }}"
                                            data-accessory-id="{{ $accessory->id }}"
                                            title="Xóa ảnh">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                                
                                <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden mb-3 relative">
                                    @if(is_string($image))
                                        {{-- Legacy seeder format: simple URL string --}}
                                        <img src="{{ $image }}" alt="Seeder Image {{ $index + 1 }}" 
                                             class="w-full h-full object-cover" 
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-full h-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center text-white font-semibold" style="display: none;">
                                            <i class="fas fa-image text-2xl"></i>
                                        </div>
                                    @elseif(is_array($image) || is_object($image))
                                        {{-- New format: object with metadata --}}
                                        @php $imageData = (array) $image; @endphp
                                        @php
                                            $imageUrl = '';
                                            if (is_string($imageData['url'] ?? null)) {
                                                $imageUrl = $imageData['url'];
                                            } elseif (is_string($imageData['file'] ?? null)) {
                                                $imageUrl = $imageData['file'];
                                            } elseif (is_string($imageData['file_path'] ?? null)) {
                                                $imageUrl = asset('storage/' . $imageData['file_path']);
                                            } else {
                                                // Fallback: No image URL found - show placeholder
                                                $imageUrl = 'https://via.placeholder.com/400x300/f3f4f6/6b7280?text=' . urlencode('Upload Failed');
                                            }
                                        @endphp
                                        <img src="{{ $imageUrl }}" 
                                             alt="{{ is_string($imageData['alt_text'] ?? null) ? $imageData['alt_text'] : (is_string($imageData['alt'] ?? null) ? $imageData['alt'] : 'Gallery Image') }}" 
                                             class="w-full h-full object-cover"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <!-- DEBUG: Image URL = {{ $imageUrl }} -->
                                        <div class="w-full h-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center text-white font-semibold" style="display: none;">
                                            <i class="fas fa-image text-2xl"></i>
                                        </div>
                                        
                                        {{-- Primary Badge Overlay --}}
                                        @if(isset($imageData['is_primary']) && $imageData['is_primary'])
                                        <div class="primary-badge absolute top-2 left-2 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-semibold flex items-center gap-1 shadow-lg">
                                            <i class="fas fa-star"></i>
                                            <span>Chính</span>
                                        </div>
                                        @endif
                                    @endif
                                </div>
                                <div class="space-y-1">
                                    @if(is_string($image))
                                        {{-- Legacy seeder image info --}}
                                        @php
                                            $imageTypeVi = 'Gallery';
                                        @endphp
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $imageTypeVi }}
                                            </span>
                                        </div>
                                        <p class="text-sm font-medium text-gray-800 mb-1">Hình ảnh {{ $index + 1 }}</p>
                                        <div class="text-xs text-gray-600">
                                            <div>Hình ảnh gallery</div>
                                        </div>
                                    @elseif(is_array($image) || is_object($image))
                                        {{-- New format image info --}}
                                        @php 
                                            $imageData = (array) $image;
                                            $imageType = is_string($imageData['image_type'] ?? null) ? $imageData['image_type'] : 'product';
                                            
                                            // Việt hóa image types
                                            $imageTypeMap = [
                                                'product' => 'Sản phẩm',
                                                'detail' => 'Chi tiết',
                                                'installation' => 'Lắp đặt',
                                                'usage' => 'Sử dụng',
                                                'gallery' => 'Gallery'
                                            ];
                                            $imageTypeVi = $imageTypeMap[$imageType] ?? ucfirst($imageType);
                                        @endphp
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $imageTypeVi }}
                                            </span>
                                        </div>
                                        <p class="text-sm font-medium text-gray-800 mb-1">
                                            {{ is_string($imageData['title'] ?? null) ? $imageData['title'] : "Hình ảnh " . ($index + 1) }}
                                        </p>
                                        <div class="text-xs text-gray-600 space-y-1">
                                            <div>{{ is_string($imageData['alt_text'] ?? null) ? $imageData['alt_text'] : (is_string($imageData['alt'] ?? null) ? $imageData['alt'] : 'Không có alt text') }}</div>
                                            @if(isset($imageData['description']) && !empty($imageData['description']))
                                            <div class="text-gray-500 italic">{{ $imageData['description'] }}</div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                            @else
                            {{-- Empty State --}}
                            <div class="col-span-full text-center py-12 text-gray-500">
                                <i class="fas fa-images text-5xl mb-4 text-gray-300"></i>
                                <p class="text-lg font-medium text-gray-600 mb-2">Chưa có hình ảnh nào</p>
                                <p class="text-sm text-gray-500">Nhấn "Thêm hình ảnh" để bắt đầu thêm ảnh vào thư viện</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Hidden input for new images --}}
                    <input type="hidden" name="gallery_json" id="new_images_hidden" value="[]">
                </div>
            </div>

            {{-- SEO & Marketing Tab --}}
            <div id="seo-tab" class="tab-content hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-search text-blue-600 mr-2"></i>
                            Tối ưu SEO
                        </h3>
                        
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                Slug URL <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="slug" id="slug" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('slug', $accessory->slug) }}" placeholder="phu-kien-xe-hoi">
                            <p class="mt-1 text-xs text-gray-500">URL thân thiện SEO (tự động tạo từ tên nếu để trống)</p>
                        </div>
                        
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('meta_title', $accessory->meta_title) }}" placeholder="Tiêu đề trang cho SEO">
                            <p class="mt-1 text-xs text-gray-500">Nên từ 50-60 ký tú</p>
                        </div>

                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="3" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                      placeholder="Mô tả trang cho SEO...">{{ old('meta_description', $accessory->meta_description) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Nên từ 150-160 ký tự</p>
                        </div>

                        <div>
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                            <input type="text" name="meta_keywords" id="meta_keywords" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('meta_keywords', $accessory->meta_keywords) }}" placeholder="từ khóa 1, từ khóa 2, từ khóa 3">
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-bullhorn text-purple-600 mr-2"></i>
                            Marketing & Hiển thị
                        </h3>

                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" name="is_featured" id="is_featured" value="1" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                       {{ old('is_featured', $accessory->is_featured) ? 'checked' : '' }}>
                                <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                    Sản phẩm nổi bật
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="hidden" name="is_bestseller" value="0">
                                <input type="checkbox" name="is_bestseller" id="is_bestseller" value="1" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                       {{ old('is_bestseller', $accessory->is_bestseller) ? 'checked' : '' }}>
                                <label for="is_bestseller" class="ml-2 block text-sm text-gray-900">
                                    Bán chạy nhất
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="hidden" name="is_new_arrival" value="0">
                                <input type="checkbox" name="is_new_arrival" id="is_new_arrival" value="1" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                       {{ old('is_new_arrival', $accessory->is_new_arrival) ? 'checked' : '' }}>
                                <label for="is_new_arrival" class="ml-2 block text-sm text-gray-900">
                                    Hàng mới về
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Thứ tự sắp xếp</label>
                            <input type="number" name="sort_order" id="sort_order" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('sort_order', $accessory->sort_order ?? 0) }}" placeholder="0" min="0">
                            <p class="mt-1 text-xs text-gray-500">Số nhỏ hơn sẽ hiển thị trước</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('admin.accessories.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Hủy bỏ
                </a>
                <button type="button" id="mainUpdateBtn" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-save mr-2" id="updateBtnIcon"></i>
                    <span id="updateBtnText">Cập nhật</span>
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Tab system and validation for edit page
let currentTab = 'basic';
let specsCount = {!! json_encode(is_array($accessory->specifications) ? count($accessory->specifications) : 0) !!};
let featuresCount = {!! json_encode(is_array($accessory->features) ? count($accessory->features) : 0) !!};
let imagesCount = 0; // Start from 0 for new images

// Use event delegation for dynamic image buttons (like CarVariant approach)
document.addEventListener('click', function(e) {
    // Handle edit image button click
    if (e.target.closest('.edit-image-btn')) {
        e.preventDefault();
        e.stopPropagation();
        
        const btn = e.target.closest('.edit-image-btn');
        
        // Check if it's a new unsaved image
        if (btn.dataset.isNew === 'true' && btn.disabled) {
            showMessage('Vui lòng đợi ảnh được lưu trước khi chỉnh sửa', 'warning');
            return false;
        }
        
        const index = btn.dataset.index;
        const accessoryId = btn.dataset.accessoryId;
        editExistingImage(index, accessoryId);
        return false;
    }
    
    // Handle delete image button click
    if (e.target.closest('.delete-image-btn')) {
        e.preventDefault();
        e.stopPropagation();
        
        const btn = e.target.closest('.delete-image-btn');
        const tempId = btn.dataset.tempId;
        
        // Check if it's a new image (has temp-id)
        if (tempId) {
            removeNewImage(tempId);
            return false;
        }
        
        // It's an existing image
        const index = btn.dataset.index;
        const accessoryId = btn.dataset.accessoryId;
        deleteExistingImage(index, accessoryId);
        return false;
    }
});

// No longer needed but keep for compatibility
function attachImageButtonListeners() {
    // Event delegation active - no manual binding needed
}

// Alias for consistency
function bindImageEventListeners() {
    // Event delegation active - buttons work automatically
}

// Re-index all image cards after deletion
function reindexImageCards() {
    const imageCards = document.querySelectorAll('.image-card');
    imageCards.forEach((card, newIndex) => {
        // Update card data-index
        card.dataset.imageIndex = newIndex;
        
        // Update edit button
        const editBtn = card.querySelector('.edit-image-btn');
        if (editBtn) {
            editBtn.dataset.index = newIndex;
        }
        
        // Update delete button
        const deleteBtn = card.querySelector('.delete-image-btn');
        if (deleteBtn) {
            deleteBtn.dataset.index = newIndex;
        }
    });
    
    // Re-attach listeners
    attachImageButtonListeners();
}

// IMPORTANT: Define callback BEFORE modal init
// Delete image callback function
window.confirmDeleteImage = function(data) {
    if (!data || !data.deleteUrl) {
        alert('Lỗi: Không có URL xóa');
        return;
    }
    
    const modal = window.deleteModalManager_deleteImageModal;
    
    // Show loading state
    if (modal) {
        modal.setLoading(true);
    }
    
    // Perform delete
    fetch(data.deleteUrl, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(responseData => {
        // Hide modal
        if (modal) {
            modal.setLoading(false);
            modal.hide();
        }
        
        if (responseData.success) {
            showMessage(responseData.message || 'Đã xóa ảnh thành công!', 'success');
            
            // Extract index from URL
            const urlParts = data.deleteUrl.split('/');
            const index = parseInt(urlParts[urlParts.length - 1]);
            
            // Find and remove image card from DOM
            const galleryContainer = document.getElementById('gallery-container');
            if (galleryContainer) {
                const imageCards = galleryContainer.querySelectorAll('.image-card');
                if (imageCards[index]) {
                    // Fade out animation
                    imageCards[index].style.transition = 'opacity 0.3s';
                    imageCards[index].style.opacity = '0';
                    setTimeout(() => {
                        imageCards[index].remove();
                        // Re-index remaining cards
                        reindexImageCards();
                        
                        // Update counts
                        updateGalleryCount();
                        
                        // Show empty state if no images left
                        const remainingCards = galleryContainer.querySelectorAll('.image-card');
                        if (remainingCards.length === 0) {
                            galleryContainer.innerHTML = `
                                <div class="col-span-full text-center py-12 text-gray-500">
                                    <i class="fas fa-images text-5xl mb-4 text-gray-300"></i>
                                    <p class="text-lg font-medium text-gray-600 mb-2">Chưa có hình ảnh nào</p>
                                    <p class="text-sm text-gray-500">Nhấn "Thêm hình ảnh" để bắt đầu thêm ảnh vào thư viện</p>
                                </div>
                            `;
                        }
                    }, 300);
                }
            }
        } else {
            showMessage(responseData.message || 'Có lỗi xảy ra khi xóa ảnh', 'error');
        }
    })
    .catch(error => {
        if (modal) {
            modal.setLoading(false);
            modal.hide();
        }
        showMessage('Có lỗi xảy ra khi xóa ảnh', 'error');
    });
};

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    initializeTabs();
    initializeConditionalFields();
    populateExistingData();
});

// Tab functionality
function initializeTabs() {
    const tabButtons = document.querySelectorAll('.tab-button');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            switchToTab(tabId);
        });
    });
}

function switchToTab(tabId) {
    // Update buttons
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    
    const activeButton = document.querySelector(`[data-tab="${tabId}"]`);
    if (activeButton) {
        activeButton.classList.add('active', 'border-blue-500', 'text-blue-600');
        activeButton.classList.remove('border-transparent', 'text-gray-500');
    }

    // Update content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    const activeContent = document.getElementById(`${tabId}-tab`);
    if (activeContent) {
        activeContent.classList.remove('hidden');
    }

    currentTab = tabId;
}

// Conditional fields
function initializeConditionalFields() {
    // Sale dates toggle
    const isOnSaleCheckbox = document.getElementById('is_on_sale');
    const saleDatesDiv = document.getElementById('sale-dates');
    
    if (isOnSaleCheckbox && saleDatesDiv) {
        isOnSaleCheckbox.addEventListener('change', function() {
            saleDatesDiv.style.display = this.checked ? 'grid' : 'none';
        });
    }

    // Installation details toggle
    const installationCheckbox = document.getElementById('installation_service_available');
    const installationDetails = document.getElementById('installation-details');
    
    if (installationCheckbox && installationDetails) {
        installationCheckbox.addEventListener('change', function() {
            installationDetails.style.display = this.checked ? 'block' : 'none';
        });
    }

    // Auto-generate slug from name
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
            if (!slugInput.value || slugInput.value === generateSlug(nameInput.dataset.oldValue || '')) {
                slugInput.value = generateSlug(this.value);
            }
            nameInput.dataset.oldValue = this.value;
        });
    }
}

function generateSlug(text) {
    return text
        .toLowerCase()
        .replace(/[àáạảãâầấậẩẫăằắặẳẵ]/g, 'a')
        .replace(/[èéẹẻẽêềếệểễ]/g, 'e')
        .replace(/[ìíịỉĩ]/g, 'i')
        .replace(/[òóọỏõôồốộổỗơờớợởỡ]/g, 'o')
        .replace(/[ùúụủũưừứựửữ]/g, 'u')
        .replace(/[ỳýỵỷỹ]/g, 'y')
        .replace(/đ/g, 'd')
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
}

// Populate existing data for edit mode
function populateExistingData() {
    // TODO: Populate specifications when specifications tab is implemented
    // TODO: Populate features when features tab is implemented
    
    // Update counts
    updateSpecsCount();
    updateFeaturesCount();
    updateImagesCount();
}

function updateSpecsCount() {
    const count = document.querySelectorAll('.spec-item').length;
    const specsCountElement = document.getElementById('specs-count');
    if (specsCountElement) {
        specsCountElement.textContent = count;
    }
}

function updateFeaturesCount() {
    const count = document.querySelectorAll('.feature-item').length;
    const featuresCountElement = document.getElementById('features-count');
    if (featuresCountElement) {
        featuresCountElement.textContent = count;
    }
}

function updateImagesCount() {
    const count = document.querySelectorAll('.image-item').length;
    const imagesCountElement = document.getElementById('images-count');
    if (imagesCountElement) {
        imagesCountElement.textContent = count;
    }
}

// Validation removed - using AJAX submission instead

function validateAllTabs() {
    // Basic validation
    const basicValidation = validateBasicTab();
    if (!basicValidation.isValid) return basicValidation;
    
    // Pricing validation
    const pricingValidation = validatePricingTab();
    if (!pricingValidation.isValid) return pricingValidation;
    
    return { isValid: true };
}

function validateBasicTab() {
    const name = document.getElementById('name');
    const sku = document.getElementById('sku');
    const category = document.getElementById('category');
    
    if (!name.value.trim()) {
        return {
            isValid: false,
            element: name,
            tabId: 'basic',
            message: 'Vui lòng nhập tên phụ kiện.'
        };
    }
    
    if (name.value.trim().length < 2) {
        return {
            isValid: false,
            element: name,
            tabId: 'basic',
            message: 'Tên phụ kiện phải có ít nhất 2 ký tự.'
        };
    }
    
    if (!sku.value.trim()) {
        return {
            isValid: false,
            element: sku,
            tabId: 'basic',
            message: 'Vui lòng nhập mã SKU.'
        };
    }
    
    if (!category.value) {
        return {
            isValid: false,
            element: category,
            tabId: 'basic',
            message: 'Vui lòng chọn danh mục.'
        };
    }
    
    return { isValid: true };
}

function validatePricingTab() {
    const basePrice = document.getElementById('base_price');
    const currentPrice = document.getElementById('current_price');
    
    if (!basePrice.value || parseFloat(basePrice.value) <= 0) {
        return {
            isValid: false,
            element: basePrice,
            tabId: 'pricing',
            message: 'Vui lòng nhập giá gốc hợp lệ.'
        };
    }
    
    if (!currentPrice.value || parseFloat(currentPrice.value) <= 0) {
        return {
            isValid: false,
            element: currentPrice,
            tabId: 'pricing',
            message: 'Vui lòng nhập giá bán hiện tại hợp lệ.'
        };
    }
    
    return { isValid: true };
}

// Auto-update current price when base price changes
function updateCurrentPriceFromBase() {
    const basePrice = document.getElementById('base_price');
    const currentPrice = document.getElementById('current_price');
    
    if (basePrice && currentPrice && basePrice.value) {
        const basePriceValue = parseFloat(basePrice.value);
        const currentPriceValue = parseFloat(currentPrice.value) || 0;
        
        // Only update if current price is lower than new base price
        if (basePriceValue > currentPriceValue) {
            currentPrice.value = basePriceValue;
        }
    }
}

// Populate existing data into tabs
function populateExistingData() {
    // Get existing data from server
    const existingData = {
        colorOptions: {!! json_encode($accessory->color_options ?? '[]') !!},
        compatibleBrands: {!! json_encode($accessory->compatible_car_brands ?? '[]') !!},
        compatibleModels: {!! json_encode($accessory->compatible_car_models ?? '[]') !!},
        compatibleYears: {!! json_encode($accessory->compatible_car_years ?? '[]') !!},
        specifications: {!! json_encode($accessory->specifications ?? '[]') !!},
        features: {!! json_encode($accessory->features ?? '[]') !!}
    };

    // Populate color options
    populateColorOptions(existingData.colorOptions);
    
    // Populate compatibility data
    populateCompatibilityData(existingData.compatibleBrands, existingData.compatibleModels, existingData.compatibleYears);
    
    // Populate specifications
    populateSpecifications(existingData.specifications);
    
    // Populate features
    populateFeatures(existingData.features);
    
    // Update counters
    updateTabCounters();
    
    // Ensure all JSON fields are initialized
    updateColorOptionsJSON();
    updateCompatibleBrands();
    updateCompatibleModels();
    updateCompatibleYears();
    updateSpecificationsJSON();
    updateFeaturesJSON();
    
    // Initialize images tab - existing images are shown via Blade, new images via JS
    initializeImagesTab();
}

function populateColorOptions(colorOptionsJson) {
    try {
        const colorOptions = typeof colorOptionsJson === 'string' ? JSON.parse(colorOptionsJson) : colorOptionsJson;
        
        
        // Clear container first to prevent duplicates
        const container = document.getElementById('color-options-container');
        container.innerHTML = '';
        
        if (Array.isArray(colorOptions) && colorOptions.length > 0) {
            // Limit to prevent UI overload - only show first 10 colors
            const limitedColors = colorOptions.slice(0, 10);
            
            limitedColors.forEach(option => {
                if (option.name && option.hex) {
                    addColorOption(option.name, option.hex);
                }
            });
            
        }
    } catch (e) {
        // No existing color options to load
    }
}

function populateCompatibilityData(brandsJson, modelsJson, yearsJson) {
    try {
        // Populate brands
        const brands = typeof brandsJson === 'string' ? JSON.parse(brandsJson) : brandsJson;
        
        // Clear containers first to prevent duplicates
        document.getElementById('compatible-brands-container').innerHTML = '';
        document.getElementById('compatible-models-container').innerHTML = '';
        document.getElementById('compatible-years-container').innerHTML = '';
        
        if (Array.isArray(brands) && brands.length > 0) {
            // Limit to prevent UI overload
            const limitedBrands = brands.slice(0, 20);
            limitedBrands.forEach(brand => addCompatibleBrand(brand));
        }
        
        // Populate models
        const models = typeof modelsJson === 'string' ? JSON.parse(modelsJson) : modelsJson;
        
        if (Array.isArray(models) && models.length > 0) {
            const limitedModels = models.slice(0, 20);
            limitedModels.forEach(model => addCompatibleModel(model));
        }
        
        // Populate years
        const years = typeof yearsJson === 'string' ? JSON.parse(yearsJson) : yearsJson;
        
        if (Array.isArray(years) && years.length > 0) {
            const limitedYears = years.slice(0, 20);
            limitedYears.forEach(year => addCompatibleYear(year));
        }
    } catch (e) {
        // No existing compatibility data to load
    }
}


function updateTabCounters() {
    // Update specifications count
    const specsContainer = document.getElementById('specifications-container');
    const specsCountElement = document.getElementById('specs-count');
    if (specsContainer && specsCountElement) {
        const count = specsContainer.children.length;
        specsCountElement.textContent = count;
    }
    
    // Update features count
    const featuresContainer = document.getElementById('features-container');
    const featuresCountElement = document.getElementById('features-count');
    if (featuresContainer && featuresCountElement) {
        const count = featuresContainer.children.length;
        featuresCountElement.textContent = count;
    }
    
    // Update images count
    const imagesContainer = document.getElementById('images-container');
    const imagesCountElement = document.getElementById('images-count');
    if (imagesContainer && imagesCountElement) {
        const count = imagesContainer.children.length;
        imagesCountElement.textContent = count;
    }
}

// Copy all functions from create.blade.php for full functionality
// Color Options Management
let colorOptionIndex = 0;

function addColorOption(name = '', hex = '') {
    const container = document.getElementById('color-options-container');
    const colorDiv = document.createElement('div');
    colorDiv.className = 'flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg';
    colorDiv.innerHTML = `
        <input type="text" placeholder="Tên màu" value="${name}" 
               class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm color-name-input">
        <input type="color" value="${hex || '#000000'}" 
               class="w-12 h-10 border border-gray-300 rounded color-picker-input">
        <input type="text" placeholder="#000000" value="${hex}" 
               class="w-20 px-2 py-2 border border-gray-300 rounded text-sm hex-input">
        <button type="button" onclick="removeColorOption(this)" 
                class="text-red-600 hover:text-red-800 p-2">
            <i class="fas fa-trash"></i>
        </button>
    `;
    
    // Add event listeners
    const colorPicker = colorDiv.querySelector('.color-picker-input');
    const hexInput = colorDiv.querySelector('.hex-input');
    
    colorPicker.addEventListener('change', function() {
        hexInput.value = this.value;
        updateColorOptionsJSON();
    });
    
    hexInput.addEventListener('input', function() {
        if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
            colorPicker.value = this.value;
        }
        updateColorOptionsJSON();
    });
    
    colorDiv.querySelector('.color-name-input').addEventListener('input', updateColorOptionsJSON);
    
    container.appendChild(colorDiv);
    updateColorOptionsJSON();
    colorOptionIndex++;
}

function removeColorOption(button) {
    const colorDiv = button.closest('div');
    if (colorDiv) {
        colorDiv.remove();
        updateColorOptionsJSON();
    }
}

function updateColorOptionsJSON() {
    const container = document.getElementById('color-options-container');
    const colorOptions = [];
    
    // Get color divs by looking for ones with color-name-input (skip warning divs)
    container.querySelectorAll('div').forEach(div => {
        const nameInput = div.querySelector('.color-name-input');
        const hexInput = div.querySelector('.hex-input');
        
        // Only process divs that have color inputs (actual color options)
        if (nameInput && hexInput) {
            const name = nameInput.value.trim();
            const hex = hexInput.value.trim();
            if (name && hex) {
                colorOptions.push({ name, hex });
            }
        }
    });
    
    // Remove exact duplicates - keep first occurrence
    const uniqueColorsByName = colorOptions.filter((color, index) => 
        colorOptions.findIndex(c => 
            c.name.toLowerCase() === color.name.toLowerCase()
        ) === index
    );
    
    document.getElementById('color_options_hidden').value = JSON.stringify(uniqueColorsByName);
}

// Compatible Brands Management
function addCompatibleBrand(brand = '') {
    const container = document.getElementById('compatible-brands-container');
    const brandDiv = document.createElement('div');
    brandDiv.className = 'flex items-center gap-2';
    brandDiv.innerHTML = `
        <input type="text" placeholder="Tên hãng xe" value="${brand}" 
               class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm brand-input">
        <button type="button" onclick="removeCompatibleItem(this)" 
                class="text-red-600 hover:text-red-800 p-1">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    brandDiv.querySelector('.brand-input').addEventListener('input', updateCompatibleBrands);
    container.appendChild(brandDiv);
    updateCompatibleBrands();
}

function updateCompatibleBrands() {
    const container = document.getElementById('compatible-brands-container');
    const brands = [];
    
    container.querySelectorAll('.brand-input').forEach(input => {
        const brand = input.value.trim();
        if (brand) {
            brands.push(brand);
        }
    });
    
    // Remove duplicates and sort (consistent with years)
    const uniqueBrands = [...new Set(brands)].sort();
    
    document.getElementById('compatible_car_brands_hidden').value = JSON.stringify(uniqueBrands);
}

// Compatible Models Management
function addCompatibleModel(model = '') {
    const container = document.getElementById('compatible-models-container');
    const modelDiv = document.createElement('div');
    modelDiv.className = 'flex items-center gap-2';
    modelDiv.innerHTML = `
        <input type="text" placeholder="Tên dòng xe" value="${model}" 
               class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm model-input">
        <button type="button" onclick="removeCompatibleItem(this)" 
                class="text-red-600 hover:text-red-800 p-1">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    modelDiv.querySelector('.model-input').addEventListener('input', updateCompatibleModels);
    container.appendChild(modelDiv);
    updateCompatibleModels();
}

function updateCompatibleModels() {
    const container = document.getElementById('compatible-models-container');
    const models = [];
    
    container.querySelectorAll('.model-input').forEach(input => {
        const model = input.value.trim();
        if (model) {
            models.push(model);
        }
    });
    
    // Remove duplicates and sort (consistent with years)
    const uniqueModels = [...new Set(models)].sort();
    
    document.getElementById('compatible_car_models_hidden').value = JSON.stringify(uniqueModels);
}

// Compatible Years Management
function addCompatibleYear(year = '') {
    const container = document.getElementById('compatible-years-container');
    const yearDiv = document.createElement('div');
    yearDiv.className = 'flex items-center gap-2';
    yearDiv.innerHTML = `
        <input type="number" placeholder="Năm sản xuất" value="${year}" 
               class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm year-input" 
               min="1990" max="2030">
        <button type="button" onclick="removeCompatibleItem(this)" 
                class="text-red-600 hover:text-red-800 p-1">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    yearDiv.querySelector('.year-input').addEventListener('input', updateCompatibleYears);
    container.appendChild(yearDiv);
    updateCompatibleYears();
}

function updateCompatibleYears() {
    const container = document.getElementById('compatible-years-container');
    const years = [];
    
    container.querySelectorAll('.year-input').forEach(input => {
        const year = input.value.trim();
        if (year && !isNaN(year) && year >= 1990 && year <= 2030) {
            years.push(year);
        }
    });
    
    // Remove duplicates and sort
    const uniqueYears = [...new Set(years)].sort();
    
    document.getElementById('compatible_car_years_hidden').value = JSON.stringify(uniqueYears);
}

function removeCompatibleItem(button) {
    const container = button.closest('div').parentElement;
    button.closest('div').remove();
    
    // Update appropriate JSON based on container
    if (container.id === 'compatible-brands-container') {
        updateCompatibleBrands();
    } else if (container.id === 'compatible-models-container') {
        updateCompatibleModels();
    } else if (container.id === 'compatible-years-container') {
        updateCompatibleYears();
    }
}

// Initialize event listeners for edit page
document.addEventListener('DOMContentLoaded', function() {
    // Color options
    if (document.getElementById('add-color-option')) {
        document.getElementById('add-color-option').addEventListener('click', () => {
            addColorOption();
        });
    }
    
    // Compatible brands
    if (document.getElementById('add-compatible-brand')) {
        document.getElementById('add-compatible-brand').addEventListener('click', () => addCompatibleBrand());
    }
    
    // Compatible models
    if (document.getElementById('add-compatible-model')) {
        document.getElementById('add-compatible-model').addEventListener('click', () => addCompatibleModel());
    }
    
    // Compatible years
    if (document.getElementById('add-compatible-year')) {
        document.getElementById('add-compatible-year').addEventListener('click', () => addCompatibleYear());
    }
    
    // Specifications
    if (document.getElementById('addSpecBtn')) {
        document.getElementById('addSpecBtn').addEventListener('click', () => {
            addSpecification();
        });
    }
    
    // Features
    if (document.getElementById('addFeatureBtn')) {
        document.getElementById('addFeatureBtn').addEventListener('click', () => {
            addFeature();
        });
    }
    
    // Images - legacy button (no longer used, using "Thêm hình ảnh" button instead)
    // Removed addImageBtn check - using new gallery management system
});

// Specifications Management
function addSpecification() {
    specsCount++;
    const container = document.getElementById('specifications-container');
    const noSpecs = document.getElementById('no-specifications');
    
    const specHtml = `
        <div class="spec-item bg-gray-50 p-4 rounded-lg border" data-spec-id="${specsCount}">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-medium text-gray-900">Thông số #${specsCount}</h4>
                <button type="button" class="text-red-600 hover:text-red-800" onclick="removeSpecification(${specsCount})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
                    <select class="spec-category block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Chọn danh mục</option>
                        <option value="dimensions">Kích thước</option>
                        <option value="weight">Trọng lượng</option>
                        <option value="material">Chất liệu</option>
                        <option value="performance">Hiệu suất</option>
                        <option value="compatibility">Tương thích</option>
                        <option value="other">Khác</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tên thông số</label>
                    <input type="text" class="spec-name block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ví dụ: Chiều dài">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Giá trị</label>
                    <input type="text" class="spec-value block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ví dụ: 150cm">
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', specHtml);
    
    // Add event listeners to the new specification inputs
    const newSpecItem = container.lastElementChild;
    const inputs = newSpecItem.querySelectorAll('select, input');
    inputs.forEach(input => {
        input.addEventListener('input', updateSpecificationsJSON);
        input.addEventListener('change', updateSpecificationsJSON);
    });
    
    noSpecs.style.display = 'none';
    updateSpecsCount();
    updateSpecificationsJSON();
}

function removeSpecification(id) {
    const specItem = document.querySelector(`[data-spec-id="${id}"]`);
    if (specItem) {
        specItem.remove();
        updateSpecsCount();
        
        if (document.querySelectorAll('.spec-item').length === 0) {
            document.getElementById('no-specifications').style.display = 'block';
        }
        updateSpecificationsJSON();
    }
}

function updateSpecsCount() {
    const count = document.querySelectorAll('.spec-item').length;
    const countElement = document.getElementById('specs-count');
    if (countElement) {
        countElement.textContent = count;
    }
}

function updateSpecificationsJSON() {
    const specifications = [];
    const specItems = document.querySelectorAll('.spec-item');
    
    specItems.forEach((item, index) => {
        const category = item.querySelector('.spec-category')?.value;
        const name = item.querySelector('.spec-name')?.value;
        const value = item.querySelector('.spec-value')?.value;
        
        if (category && name && value) {
            specifications.push({
                category: category.trim(),
                name: name.trim(),
                value: value.trim()
            });
        }
    });
    
    
    // Remove exact duplicates - keep first occurrence
    const uniqueSpecifications = specifications.filter((spec, index) => 
        specifications.findIndex(s => 
            s.category === spec.category && 
            s.name === spec.name && 
            s.value === spec.value
        ) === index
    );
    
    // Show feedback if deduplication occurred
    if (specifications.length !== uniqueSpecifications.length) {
        const duplicateCount = specifications.length - uniqueSpecifications.length;
        const duplicatedSpecs = specifications.filter((spec, index) => 
            specifications.findIndex(s => 
                s.category === spec.category && 
                s.name === spec.name && 
                s.value === spec.value
            ) !== index
        );
    }
    
    
    document.getElementById('specifications_hidden').value = JSON.stringify(uniqueSpecifications);
}

// Features Management
function addFeature() {
    featuresCount++;
    const container = document.getElementById('features-container');
    const noFeatures = document.getElementById('no-features');
    
    const featureHtml = `
        <div class="feature-item bg-gray-50 p-4 rounded-lg border" data-feature-id="${featuresCount}">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-medium text-gray-900">Tính năng #${featuresCount}</h4>
                <button type="button" class="text-red-600 hover:text-red-800" onclick="removeFeature(${featuresCount})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
                    <select class="feature-category block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Chọn danh mục</option>
                        <option value="comfort">Tiện nghi</option>
                        <option value="safety">An toàn</option>
                        <option value="technology">Công nghệ</option>
                        <option value="performance">Hiệu suất</option>
                        <option value="aesthetic">Thẩm mỹ</option>
                        <option value="other">Khác</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tên tính năng</label>
                    <input type="text" class="feature-name block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ví dụ: Chống trượt">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phụ phí (VNĐ)</label>
                    <input type="number" class="feature-price block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Thứ tự</label>
                    <input type="number" class="feature-sort block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="0">
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', featureHtml);
    
    // Add event listeners to the new feature inputs
    const newFeatureItem = container.lastElementChild;
    const inputs = newFeatureItem.querySelectorAll('select, input');
    inputs.forEach(input => {
        input.addEventListener('input', updateFeaturesJSON);
        input.addEventListener('change', updateFeaturesJSON);
    });
    
    noFeatures.style.display = 'none';
    updateFeaturesCount();
    updateFeaturesJSON();
}

function removeFeature(id) {
    const featureItem = document.querySelector(`[data-feature-id="${id}"]`);
    if (featureItem) {
        featureItem.remove();
        updateFeaturesCount();
        
        if (document.querySelectorAll('.feature-item').length === 0) {
            document.getElementById('no-features').style.display = 'block';
        }
        updateFeaturesJSON();
    }
}

function updateFeaturesCount() {
    const count = document.querySelectorAll('.feature-item').length;
    const countElement = document.getElementById('features-count');
    if (countElement) {
        countElement.textContent = count;
    }
}

function updateFeaturesJSON() {
    const features = [];
    const featureItems = document.querySelectorAll('.feature-item');
    
    featureItems.forEach((item, index) => {
        const category = item.querySelector('.feature-category')?.value;
        const name = item.querySelector('.feature-name')?.value;
        const price = item.querySelector('.feature-price')?.value;
        const sortOrder = item.querySelector('.feature-sort')?.value;
        
        if (category && name) {
            features.push({
                category: category.trim(),
                name: name.trim(),
                price: price ? parseFloat(price) || 0 : 0,
                sort_order: sortOrder ? parseInt(sortOrder) || 0 : 0
            });
        }
    });
    
    
    // Remove exact duplicates - keep first occurrence
    const uniqueFeatures = features.filter((feature, index) => 
        features.findIndex(f => 
            f.category === feature.category && 
            f.name === feature.name && 
            f.price === feature.price
        ) === index
    );
    
    // Show feedback if deduplication occurred
    if (features.length !== uniqueFeatures.length) {
        const duplicateCount = features.length - uniqueFeatures.length;
        const duplicatedFeatures = features.filter((feature, index) => 
            features.findIndex(f => 
                f.category === feature.category && 
                f.name === feature.name && 
                f.price === feature.price
            ) !== index
        );
    }
    
    
    document.getElementById('features_hidden').value = JSON.stringify(uniqueFeatures);
}

// Images Management
function addImage() {
    imagesCount++;
    const container = document.getElementById('images-container');
    const noImages = document.getElementById('no-images');
    
    const imageHtml = `
        <div class="image-item bg-white p-4 rounded-lg border" data-image-id="${imagesCount}">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-medium text-gray-900">Hình ảnh #${imagesCount}</h4>
                <button type="button" class="text-red-600 hover:text-red-800" onclick="removeImage(${imagesCount})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chọn hình ảnh</label>
                    <input type="file" name="gallery[${imagesCount}][file]" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" onchange="previewImage(this, ${imagesCount})">
                </div>
                <div id="preview-${imagesCount}" class="hidden">
                    <img class="h-32 w-full object-cover rounded-lg border">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tiêu đề</label>
                        <input type="text" class="image-title block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Tiêu đề hình ảnh">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alt text</label>
                        <input type="text" class="image-alt block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Mô tả hình ảnh">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Loại hình ảnh</label>
                        <select class="image-type block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="product">Sản phẩm</option>
                            <option value="detail">Chi tiết</option>
                            <option value="installation">Lắp đặt</option>
                            <option value="usage">Sử dụng</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Thứ tự</label>
                        <input type="number" class="image-sort block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="0">
                    </div>
                </div>
                <div class="flex items-center">
                    <input type="hidden" name="gallery[${imagesCount}][is_main]" value="0">
                    <input type="checkbox" value="1" class="image-primary h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label class="ml-2 block text-sm text-gray-900">
                        <i class="fas fa-star text-yellow-500 mr-1"></i>
                        Hình ảnh chính
                    </label>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', imageHtml);
    
    // Add event listeners to the new image inputs
    const newImageItem = container.lastElementChild;
    const inputs = newImageItem.querySelectorAll('input[type="text"], input[type="number"], select, input[type="checkbox"]');
    inputs.forEach(input => {
        input.addEventListener('input', updateImagesJSON);
        input.addEventListener('change', updateImagesJSON);
    });
    
    noImages.style.display = 'none';
    updateImagesCount();
    updateImagesJSON();
}

function removeImage(id) {
    const imageItem = document.querySelector(`[data-image-id="${id}"]`);
    if (imageItem) {
        imageItem.remove();
        updateImagesCount();
        
        if (document.querySelectorAll('#images-container .image-item').length === 0) {
            document.getElementById('no-images').style.display = 'block';
        }
        updateImagesJSON();
    }
}

function previewImage(input, id) {
    const file = input.files[0];
    const preview = document.getElementById(`preview-${id}`);
    const img = preview.querySelector('img');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
}

function updateImagesCount() {
    // Update tab count
    const countElement = document.getElementById('images-count');
    if (countElement) {
        // Count all images in gallery
        const totalImages = document.querySelectorAll('#gallery-container .image-card').length;
        countElement.textContent = totalImages;
    }
}

function updateImagesJSON() {
    const images = {};  // Use object instead of array to match file indices
    const imageItems = document.querySelectorAll('#gallery-container .image-card.new-image');
    
    imageItems.forEach((item) => {
        const fileInput = item.querySelector('input[type="file"]');
        const tempId = item.getAttribute('data-temp-id');  // Get the actual index used in form
        const title = item.querySelector('.image-title')?.value?.trim() || '';
        const altText = item.querySelector('.image-alt')?.value?.trim() || '';
        const imageType = item.querySelector('.image-type')?.value || 'product';
        const sortOrder = parseInt(item.querySelector('.image-sort')?.value) || 0;
        const description = item.querySelector('.image-description')?.value?.trim() || '';
        const isPrimary = item.querySelector('.image-primary')?.checked || false;
        
        // Only include if we have an actual file selected (not just metadata)
        if (fileInput && fileInput.files && fileInput.files[0] && tempId) {
            const imageData = {
                title: title,
                alt_text: altText,
                image_type: imageType,
                sort_order: sortOrder
            };
            
            if (description) {
                imageData.description = description;
            }
            
            if (isPrimary) {
                imageData.is_primary = true;
            }
            
            // Store with the same index as the file input
            images[tempId] = imageData;
        }
    });
    
    // Update hidden field for new images only
    document.getElementById('new_images_hidden').value = JSON.stringify(images);
}

// Populate functions for existing data
let specificationsPopulated = false;

function populateSpecifications(specificationsJson) {
    if (specificationsPopulated) {
        return;
    }
    
    try {
        const specifications = typeof specificationsJson === 'string' ? JSON.parse(specificationsJson) : specificationsJson;
        
        
        // Clear container first to prevent duplicates
        const container = document.getElementById('specifications-container');
        container.innerHTML = '';
        
        // Reset counter to prevent ID conflicts
        specsCount = 0;
        
        if (Array.isArray(specifications) && specifications.length > 0) {
            specifications.forEach((spec, index) => {
                if (spec.name && spec.value) {
                    // Create specification with existing data
                    specsCount++;
                    const container = document.getElementById('specifications-container');
                    const specHtml = `
                        <div class="spec-item bg-gray-50 p-4 rounded-lg border" data-spec-id="${specsCount}">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-medium text-gray-900">Thông số #${specsCount}</h4>
                                <button type="button" class="text-red-600 hover:text-red-800" onclick="removeSpecification(${specsCount})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
                                    <select class="spec-category block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Chọn danh mục</option>
                                        <option value="dimensions" ${spec.category === 'dimensions' ? 'selected' : ''}>Kích thước</option>
                                        <option value="weight" ${spec.category === 'weight' ? 'selected' : ''}>Trọng lượng</option>
                                        <option value="material" ${spec.category === 'material' ? 'selected' : ''}>Chất liệu</option>
                                        <option value="performance" ${spec.category === 'performance' ? 'selected' : ''}>Hiệu suất</option>
                                        <option value="compatibility" ${spec.category === 'compatibility' ? 'selected' : ''}>Tương thích</option>
                                        <option value="other" ${spec.category === 'other' ? 'selected' : ''}>Khác</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tên thông số</label>
                                    <input type="text" value="${spec.name || ''}" class="spec-name block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ví dụ: Chiều dài">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Giá trị</label>
                                    <input type="text" value="${spec.value || ''}" class="spec-value block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ví dụ: 150cm">
                                </div>
                            </div>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', specHtml);
                    
                    // Add event listeners to the populated specification inputs
                    const newSpecItem = container.lastElementChild;
                    const inputs = newSpecItem.querySelectorAll('select, input');
                    inputs.forEach(input => {
                        input.addEventListener('input', updateSpecificationsJSON);
                        input.addEventListener('change', updateSpecificationsJSON);
                    });
                }
            });
            document.getElementById('no-specifications').style.display = 'none';
            updateSpecsCount();
            updateSpecificationsJSON();
        } else {
            document.getElementById('no-specifications').style.display = 'block';
        }
        
        specificationsPopulated = true; // Mark as populated
    } catch (e) {
        document.getElementById('no-specifications').style.display = 'block';
        specificationsPopulated = true; // Mark as populated even if empty
    }
}

// Populate functions for existing data
let featuresPopulated = false;

function populateFeatures(featuresJson) {
    if (featuresPopulated) {
        return;
    }
    
    try {
        const features = typeof featuresJson === 'string' ? JSON.parse(featuresJson) : featuresJson;
        
        
        // Clear container first to prevent duplicates
        const container = document.getElementById('features-container');
        container.innerHTML = '';
        
        // Reset counter to prevent ID conflicts
        featuresCount = 0;
        
        if (Array.isArray(features) && features.length > 0) {
            features.forEach(feature => {
                if (feature.name || feature.title) {
                    // Create feature with existing data
                    featuresCount++;
                    const container = document.getElementById('features-container');
                    const featureHtml = `
                        <div class="feature-item bg-gray-50 p-4 rounded-lg border" data-feature-id="${featuresCount}">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-medium text-gray-900">Tính năng #${featuresCount}</h4>
                                <button type="button" class="text-red-600 hover:text-red-800" onclick="removeFeature(${featuresCount})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
                                    <select class="feature-category block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Chọn danh mục</option>
                                        <option value="comfort" ${feature.category === 'comfort' ? 'selected' : ''}>Tiện nghi</option>
                                        <option value="safety" ${feature.category === 'safety' ? 'selected' : ''}>An toàn</option>
                                        <option value="technology" ${feature.category === 'technology' ? 'selected' : ''}>Công nghệ</option>
                                        <option value="performance" ${feature.category === 'performance' ? 'selected' : ''}>Hiệu suất</option>
                                        <option value="aesthetic" ${feature.category === 'aesthetic' ? 'selected' : ''}>Thẩm mỹ</option>
                                        <option value="other" ${feature.category === 'other' ? 'selected' : ''}>Khác</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tên tính năng</label>
                                    <input type="text" value="${feature.name || feature.title || ''}" class="feature-name block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ví dụ: Chống trượt">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phụ phí (VNĐ)</label>
                                    <input type="number" value="${feature.price || ''}" class="feature-price block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Thứ tự</label>
                                    <input type="number" value="${feature.sort_order || 0}" class="feature-sort block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', featureHtml);
                    
                    // Add event listeners to the populated feature inputs
                    const newFeatureItem = container.lastElementChild;
                    const inputs = newFeatureItem.querySelectorAll('select, input');
                    inputs.forEach(input => {
                        input.addEventListener('input', updateFeaturesJSON);
                        input.addEventListener('change', updateFeaturesJSON);
                    });
                }
            });
            document.getElementById('no-features').style.display = 'none';
            updateFeaturesCount();
            updateFeaturesJSON();
        } else {
            document.getElementById('no-features').style.display = 'block';
        }
        
        featuresPopulated = true; // Mark as populated
    } catch (e) {
        document.getElementById('no-features').style.display = 'block';
        featuresPopulated = true; // Mark as populated even if empty
    }
}

// Initialize images tab - simplified approach (DISABLED - using new gallery system)
function initializeImagesTab() {
    // Legacy code - no longer needed with new gallery management
    return;
}

// Save current tab before form submission
function saveCurrentTab() {
    sessionStorage.setItem('currentEditTab', currentTab);
}

// Restore current tab on page load
function restoreCurrentTab() {
    const savedTab = sessionStorage.getItem('currentEditTab');
    if (savedTab && document.querySelector(`[data-tab="${savedTab}"]`)) {
        switchToTab(savedTab);
    }
}

// Component will provide showMessage function

// Button Loading State Management
function setButtonLoading(loading = true) {
    const btn = document.getElementById('mainUpdateBtn');
    const icon = document.getElementById('updateBtnIcon');
    const text = document.getElementById('updateBtnText');
    
    if (loading) {
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        icon.className = 'fas fa-spinner fa-spin mr-2';
        text.textContent = 'Đang cập nhật...';
    } else {
        btn.disabled = false;
        btn.classList.remove('opacity-75', 'cursor-not-allowed');
        icon.className = 'fas fa-save mr-2';
        text.textContent = 'Cập nhật';
    }
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('accessoryEditForm');
    if (form) {
        // AJAX form submission (like CarVariant)
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic validation check
            const name = document.getElementById('name')?.value?.trim();
            const category = document.getElementById('category')?.value;
            const sku = document.getElementById('sku')?.value?.trim();
            
            
            if (!name) {
                switchToTab('basic');
                showMessage('Vui lòng nhập tên phụ kiện.', 'error');
                setTimeout(() => document.getElementById('name')?.focus(), 100);
                return;
            }
            
            if (!category) {
                switchToTab('basic');
                showMessage('Vui lòng chọn danh mục.', 'error');
                setTimeout(() => document.getElementById('category')?.focus(), 100);
                return;
            }
            
            if (!sku) {
                switchToTab('basic');
                showMessage('Vui lòng nhập mã SKU.', 'error');
                setTimeout(() => document.getElementById('sku')?.focus(), 100);
                return;
            }
            
            // Force update all JSON fields before submission
            updateColorOptionsJSON();
            updateCompatibleBrands();
            updateCompatibleModels();
            updateCompatibleYears();
            updateSpecificationsJSON();
            updateFeaturesJSON();
            updateImagesJSON();
            
            const formData = new FormData(form);
            const submitButton = document.getElementById('mainUpdateBtn');
            
            // Simple: Only send new images, controller will append to existing
            const newImagesValue = document.getElementById('new_images_hidden').value;
            const newImagesArray = JSON.parse(newImagesValue || '[]');
            
            
            // Send as gallery_json (what controller expects) but only new images
            if (newImagesArray.length > 0) {
                formData.set('gallery_json', JSON.stringify(newImagesArray));
            } else {
                // Don't send gallery_json if no new images
                formData.delete('gallery_json');
            }
            
            // Keep gallery file uploads, only remove non-file gallery fields if any
            // Don't delete 'gallery' as it contains the actual file uploads
            
            
            // Show loading state
            setButtonLoading(true);
            saveCurrentTab();
            
            // Add _method field for Laravel PUT request
            formData.append('_method', 'PUT');
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => {
                if (!response.ok) {
                    // Handle HTTP error status codes
                    return response.json().then(errorData => {
                        throw { status: response.status, data: errorData };
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    showMessage(data.message || 'Cập nhật thông tin thành công!', 'success');
                    
                    // Delay then redirect to index page
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.accessories.index") }}';
                    }, 2000); // 2 second delay
                } else {
                    showMessage((data && data.message) || 'Có lỗi xảy ra khi cập nhật', 'error');
                    setButtonLoading(false);
                }
            })
            .catch(error => {
                // Handle different error types (like CarVariant)
                if (error.status === 422 && error.data && error.data.errors) {
                    // Validation errors
                    let errorMessage = 'Dữ liệu không hợp lệ:\n';
                    for (const field in error.data.errors) {
                        errorMessage += `• ${error.data.errors[field][0]}\n`;
                    }
                    showMessage(errorMessage, 'error');
                } else if (error.status === 404) {
                    showMessage('Phụ kiện không tồn tại hoặc đã bị xóa', 'error');
                } else if (error.status === 403) {
                    showMessage('Bạn không có quyền thực hiện thao tác này', 'error');
                } else if (error.data && error.data.message) {
                    showMessage(error.data.message, 'error');
                } else {
                    showMessage('Có lỗi xảy ra khi cập nhật. Vui lòng thử lại.', 'error');
                }
            })
            .finally(() => {
                // Reset button state
                setButtonLoading(false);
            });
        });
    }
    
    // Update button click handler
    const updateBtn = document.getElementById('mainUpdateBtn');
    if (updateBtn) {
        updateBtn.addEventListener('click', function() {
            const form = document.getElementById('accessoryEditForm');
            if (form) {
                // Trigger form submit event (which will use AJAX)
                form.dispatchEvent(new Event('submit'));
            }
        });
    }
    
    // Restore tab if coming back from submission
    restoreCurrentTab();
    
    // Initialize existing data
    populateExistingData();
    
    // Handle primary image checkbox - only one can be primary
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('image-primary') && e.target.checked) {
            // Uncheck all other primary checkboxes
            document.querySelectorAll('.image-primary').forEach(cb => {
                if (cb !== e.target) {
                    cb.checked = false;
                }
            });
        }
    });
});

// Multiple files storage
let modalSelectedFiles = [];

// Open add image modal
function openAddImageModal() {
    const modal = document.getElementById('addImageModal');
    if (!modal) {
        return;
    }
    modal.classList.remove('hidden');
    // Reset
    modalSelectedFiles = [];
    
    const modalImageFiles = document.getElementById('modalImageFiles');
    if (modalImageFiles) {
        modalImageFiles.value = '';
    }
    
    const modalFilesList = document.getElementById('modalFilesList');
    if (modalFilesList) {
        modalFilesList.classList.add('hidden');
    }
    
    const modalImagesList = document.getElementById('modalImagesList');
    if (modalImagesList) {
        modalImagesList.innerHTML = '';
    }
}

// Close add image modal
function closeAddImageModal() {
    const modal = document.getElementById('addImageModal');
    if (modal) {
        modal.classList.add('hidden');
    }
    modalSelectedFiles = [];
}

// Handle multiple file selection
document.addEventListener('DOMContentLoaded', function() {
    const modalFileInput = document.getElementById('modalImageFiles');
    const dropZone = document.getElementById('modalDropZone');
    
    if (modalFileInput) {
        modalFileInput.addEventListener('change', function(e) {
            handleModalFiles(e.target.files);
        });
    }
    
    // Drag & Drop support
    if (dropZone) {
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.add('border-blue-500', 'bg-blue-50');
        });
        
        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('border-blue-500', 'bg-blue-50');
        });
        
        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('border-blue-500', 'bg-blue-50');
            handleModalFiles(e.dataTransfer.files);
        });
    }
});

// Handle selected files
function handleModalFiles(files) {
    if (!files || files.length === 0) return;
    
    modalSelectedFiles = Array.from(files);
    displayModalFilesList();
    displayModalSettingsList();
    
    // Show sections
    document.getElementById('modalFilesList').classList.remove('hidden');
    document.getElementById('modalIndividualSettings').classList.remove('hidden');
}

// Display file list (top section)
function displayModalFilesList() {
    const container = document.getElementById('modalFilesItems');
    container.innerHTML = '';
    
    modalSelectedFiles.forEach((file, index) => {
        const fileItem = document.createElement('div');
        fileItem.className = 'flex items-center justify-between p-2 bg-gray-50 rounded border border-gray-200';
        
        fileItem.innerHTML = `
            <div class="flex items-center gap-2 flex-1 min-w-0">
                <i class="fas fa-image text-blue-500 flex-shrink-0"></i>
                <span class="text-sm text-gray-900 truncate">${file.name}</span>
                <span class="text-xs text-gray-500 flex-shrink-0">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
            </div>
            <button type="button" onclick="removeModalImage(${index})" 
                    class="text-red-600 hover:text-red-800 p-1 flex-shrink-0">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        container.appendChild(fileItem);
    });
}

// Generate smart metadata based on filename and context
function generateSmartMetadata(file, index) {
    const accessoryName = '{{ $accessory->name }}';
    
    // Clean filename
    let cleanFileName = file.name.replace(/\.[^/.]+$/, ''); // Remove extension
    cleanFileName = cleanFileName.replace(/[-_]/g, ' '); // Replace dashes/underscores with spaces
    cleanFileName = cleanFileName.replace(/\d+/g, '').trim(); // Remove numbers
    
    // Generate title
    let title = `${accessoryName}`;
    if (index > 0) {
        title += ` - ${index + 1}`;
    }
    
    // Generate alt text
    let altText = `Hình ảnh ${accessoryName}`;
    if (cleanFileName && cleanFileName.length > 2) {
        altText += ` - ${cleanFileName}`;
    }
    
    // Default descriptions by type (will be updated when type changes)
    const defaultDesc = `Ảnh chất lượng cao của ${accessoryName}, thể hiện rõ nét thiết kế, màu sắc và đặc điểm nổi bật của sản phẩm.`;
    
    return {
        title: title,
        altText: altText,
        description: defaultDesc
    };
}

// Get description based on image type
function getDescriptionForType(type, accessoryName) {
    const descriptions = {
        'product': `Hình ảnh tổng quan ${accessoryName}, thể hiện đầy đủ thiết kế và tính năng nổi bật của sản phẩm.`,
        'detail': `Ảnh chi tiết cận cảnh ${accessoryName}, làm nổi bật chất lượng hoàn thiện và các chi tiết quan trọng.`,
        'installation': `Hướng dẫn lắp đặt ${accessoryName}, minh họa rõ ràng quy trình và vị trí lắp đặt sản phẩm.`,
        'usage': `Ảnh sử dụng thực tế ${accessoryName}, thể hiện sản phẩm trong môi trường sử dụng hàng ngày.`
    };
    return descriptions[type] || descriptions['product'];
}

// Display settings list (bottom section)
function displayModalSettingsList() {
    const container = document.getElementById('modalImagesList');
    const accessoryName = '{{ $accessory->name }}';
    container.innerHTML = '';
    
    modalSelectedFiles.forEach((file, index) => {
        const metadata = generateSmartMetadata(file, index);
        
        const settingCard = document.createElement('div');
        settingCard.className = 'bg-white p-4 rounded-lg border border-gray-200';
        settingCard.dataset.index = index;
        
        settingCard.innerHTML = `
            <div class="flex items-start gap-3 mb-3 pb-3 border-b border-gray-100">
                <i class="fas fa-image text-blue-600 text-lg mt-1"></i>
                <div class="flex-1">
                    <h5 class="font-medium text-gray-900">${file.name}</h5>
                    <p class="text-xs text-gray-500 mt-0.5">Ảnh ${index + 1}</p>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tiêu đề</label>
                        <input type="text" id="modalTitle_${index}" required
                               value="${metadata.title}"
                               placeholder="Tên hình ảnh"
                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alt Text</label>
                        <input type="text" id="modalAlt_${index}" required
                               value="${metadata.altText}"
                               placeholder="Mô tả cho SEO"
                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Loại hình ảnh</label>
                        <select id="modalType_${index}" 
                                onchange="updateDescriptionForType(${index}, this.value)"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="product">Sản phẩm</option>
                            <option value="detail">Chi tiết</option>
                            <option value="installation">Lắp đặt</option>
                            <option value="usage">Sử dụng</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Thứ tự</label>
                        <input type="number" id="modalSort_${index}" value="${index}"
                               min="0"
                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả riêng</label>
                    <textarea id="modalDesc_${index}" rows="2"
                              placeholder="Mô tả chi tiết cho hình ảnh này"
                              class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">${metadata.description}</textarea>
                </div>
                
                <div class="flex items-center pt-2">
                    <input type="checkbox" id="modalPrimary_${index}" ${index === 0 ? 'checked' : ''}
                           onchange="handleModalPrimaryChange(${index})"
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="modalPrimary_${index}" class="ml-2 block text-sm text-gray-900">
                        Đặt làm ảnh chính
                    </label>
                </div>
            </div>
        `;
        
        container.appendChild(settingCard);
    });
}

// Update description when image type changes
function updateDescriptionForType(index, type) {
    const accessoryName = '{{ $accessory->name }}';
    const descTextarea = document.getElementById(`modalDesc_${index}`);
    if (descTextarea) {
        descTextarea.value = getDescriptionForType(type, accessoryName);
    }
}

// Remove image from modal list
function removeModalImage(index) {
    modalSelectedFiles.splice(index, 1);
    
    if (modalSelectedFiles.length === 0) {
        document.getElementById('modalFilesList').classList.add('hidden');
        document.getElementById('modalIndividualSettings').classList.add('hidden');
    } else {
        displayModalFilesList();
        displayModalSettingsList();
    }
}

// Handle primary checkbox (only one can be checked)
function handleModalPrimaryChange(index) {
    document.querySelectorAll('[id^="modalPrimary_"]').forEach((checkbox, i) => {
        if (i !== index) {
            checkbox.checked = false;
        }
    });
}

// Submit multiple images
function submitMultipleImages() {
    // Check if any files selected
    if (modalSelectedFiles.length === 0) {
        showMessage('Vui lòng chọn ít nhất một hình ảnh', 'error');
        return;
    }
    
    // Validate all required fields
    let isValid = true;
    modalSelectedFiles.forEach((file, index) => {
        const title = document.getElementById(`modalTitle_${index}`);
        const alt = document.getElementById(`modalAlt_${index}`);
        
        if (!title.value.trim() || !alt.value.trim()) {
            isValid = false;
            title.classList.add('border-red-500');
            alt.classList.add('border-red-500');
        }
    });
    
    if (!isValid) {
        showMessage('Vui lòng điền đầy đủ thông tin cho tất cả ảnh', 'error');
        return;
    }
    
    // Add all images to main form
    modalSelectedFiles.forEach((file, index) => {
        const data = {
            file: file,
            title: document.getElementById(`modalTitle_${index}`).value,
            altText: document.getElementById(`modalAlt_${index}`).value,
            imageType: document.getElementById(`modalType_${index}`).value,
            sortOrder: document.getElementById(`modalSort_${index}`).value,
            description: document.getElementById(`modalDesc_${index}`).value,
            isPrimary: document.getElementById(`modalPrimary_${index}`).checked
        };
        
        addImageToForm(data);
    });
    
    closeAddImageModal();
    
    // Save images via AJAX (no reload)
    saveImagesToDatabase();
}

// Convert new images to saved state (no reload needed)
function convertNewImagesToSaved() {
    const accessoryId = '{{ $accessory->id }}';
    const container = document.getElementById('gallery-container');
    const newImageCards = container.querySelectorAll('.image-card.new-image');
    
    // Get current max index from existing images
    const allCards = container.querySelectorAll('.image-card');
    let maxIndex = -1;
    allCards.forEach(card => {
        if (!card.classList.contains('new-image')) {
            const idx = parseInt(card.getAttribute('data-image-index'));
            if (!isNaN(idx) && idx > maxIndex) {
                maxIndex = idx;
            }
        }
    });
    
    // Convert each new image
    newImageCards.forEach((card, i) => {
        const newIndex = maxIndex + 1 + i;
        
        // Remove new-image class
        card.classList.remove('new-image');
        
        // Update data-image-index
        card.setAttribute('data-image-index', newIndex);
        
        // Remove data-temp-id
        card.removeAttribute('data-temp-id');
        
        // Update buttons with correct data attributes
        const editBtn = card.querySelector('.edit-image-btn');
        const deleteBtn = card.querySelector('.delete-image-btn');
        
        if (editBtn) {
            // Enable edit button
            editBtn.removeAttribute('disabled');
            editBtn.removeAttribute('data-is-new');
            
            // Update data attributes
            editBtn.setAttribute('data-index', newIndex);
            editBtn.setAttribute('data-accessory-id', accessoryId);
            
            // Update title
            editBtn.setAttribute('title', 'Sửa ảnh');
        }
        
        if (deleteBtn) {
            // Update data attributes
            deleteBtn.setAttribute('data-index', newIndex);
            deleteBtn.setAttribute('data-accessory-id', accessoryId);
            
            // Remove temp-id attribute
            deleteBtn.removeAttribute('data-temp-id');
        }
    });
    
    // Update counts
    updateGalleryCount();
}

// Save images to database via AJAX (no page reload)
function saveImagesToDatabase() {
    const form = document.getElementById('accessoryEditForm');
    if (!form) {
        return;
    }
    
    const formData = new FormData(form);
    
    // Send AJAX request
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showMessage('Đã lưu hình ảnh thành công!', 'success');
            
            // Convert new images to saved state - no reload needed!
            convertNewImagesToSaved();
        } else {
            showMessage('Lỗi: ' + data.message, 'error');
        }
    })
    .catch(error => {
        showMessage('Có lỗi xảy ra khi lưu ảnh', 'error');
    });
}

// Add single image to gallery (show immediately in management section)
function addImageToForm(data) {
    const container = document.getElementById('gallery-container');
    
    // Remove empty state if exists
    const emptyState = container.querySelector('.col-span-full.text-center');
    if (emptyState) {
        emptyState.remove();
    }
    
    const typeMap = {
        'product': 'Sản phẩm',
        'detail': 'Chi tiết',
        'installation': 'Lắp đặt',
        'usage': 'Sử dụng'
    };
    
    // Get current count
    const currentCards = container.querySelectorAll('.image-card');
    const newIndex = currentCards.length;
    
    imagesCount++; // Still need for form submission
    
    // Create object URL for preview
    let imageUrl;
    try {
        imageUrl = URL.createObjectURL(data.file);
    } catch (error) {
        showMessage('Không thể hiển thị ảnh', 'error');
        return;
    }
    
    // Remove primary badge from existing images if new image is primary
    if (data.isPrimary) {
        const existingCards = container.querySelectorAll('.image-card');
        existingCards.forEach(card => {
            const existingBadge = card.querySelector('.primary-badge');
            if (existingBadge) {
                existingBadge.remove();
            }
        });
    }
    
    // Create card element
    const cardDiv = document.createElement('div');
    cardDiv.className = 'bg-white p-3 rounded-lg border border-gray-200 relative image-card new-image';
    cardDiv.setAttribute('data-image-index', newIndex);
    cardDiv.setAttribute('data-temp-id', imagesCount);
    
    cardDiv.innerHTML = `
        <div class="absolute top-2 right-2 z-10 flex gap-2">
            <button type="button" 
                    class="edit-image-btn bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-full shadow-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    data-index="${newIndex}"
                    data-accessory-id="{{ $accessory->id }}"
                    data-is-new="true"
                    disabled
                    title="Ảnh chưa lưu - click để lưu trước">
                <i class="fas fa-edit text-xs"></i>
            </button>
            <button type="button" 
                    class="delete-image-btn bg-red-500 hover:bg-red-600 text-white p-2 rounded-full shadow-lg transition-colors"
                    data-index="${newIndex}"
                    data-accessory-id="{{ $accessory->id }}"
                    data-temp-id="${imagesCount}"
                    title="Xóa ảnh">
                <i class="fas fa-trash text-xs"></i>
            </button>
        </div>
        
        <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden mb-3 relative">
            <img src="${imageUrl}" 
                 alt="${data.altText}" 
                 class="w-full h-full object-cover">
            
            ${data.isPrimary ? `
            <div class="primary-badge absolute top-2 left-2 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-semibold flex items-center gap-1 shadow-lg">
                <i class="fas fa-star"></i>
                <span>Chính</span>
            </div>
            ` : ''}
            
            <input type="file" name="gallery[${imagesCount}][file]" class="hidden" data-file-storage="${imagesCount}">
            <input type="hidden" class="image-title" value="${data.title}">
            <input type="hidden" class="image-alt" value="${data.altText}">
            <input type="hidden" class="image-type" value="${data.imageType}">
            <input type="hidden" class="image-sort" value="${data.sortOrder}">
            <input type="hidden" class="image-description" value="${data.description || ''}">
            <input type="checkbox" class="image-primary" ${data.isPrimary ? 'checked' : ''} style="display:none">
        </div>
        
        <div class="space-y-1">
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                    ${typeMap[data.imageType] || data.imageType}
                </span>
            </div>
            <p class="text-sm font-medium text-gray-800 mb-1">${data.title}</p>
            <div class="text-xs text-gray-600 space-y-1">
                <div>${data.altText}</div>
                ${data.description ? `<div class="text-gray-500 italic">${data.description}</div>` : ''}
            </div>
        </div>
    `;
    
    container.appendChild(cardDiv);
    
    // Store file in hidden input
    const fileInput = cardDiv.querySelector(`[data-file-storage="${imagesCount}"]`);
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(data.file);
    fileInput.files = dataTransfer.files;
    
    // Update gallery counteroll to show new image
    cardDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    
    // Update gallery title count
    updateGalleryCount();
    updateImagesJSON();
}

// Update gallery count in title
function updateGalleryCount() {
    // Only update tab count, not title (title has no count anymore)
    updateImagesCount();
}

// Remove new image from UI (before saving)
function removeNewImage(tempId) {
    const container = document.getElementById('gallery-container');
    const card = container.querySelector(`[data-temp-id="${tempId}"]`);
    
    if (card) {
        // Fade out animation
        card.style.transition = 'opacity 0.3s';
        card.style.opacity = '0';
        
        setTimeout(() => {
            card.remove();
            updateGalleryCount();
            updateImagesJSON();
            
            // Show empty state if no images left
            const remainingCards = container.querySelectorAll('.image-card');
            if (remainingCards.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full text-center py-12 text-gray-500">
                        <i class="fas fa-images text-5xl mb-4 text-gray-300"></i>
                        <p class="text-lg font-medium text-gray-600 mb-2">Chưa có hình ảnh nào</p>
                        <p class="text-sm text-gray-500">Nhấn "Thêm hình ảnh" để bắt đầu thêm ảnh vào thư viện</p>
                    </div>
                `;
            }
        }, 300);
    }
}

// Edit existing image
function editExistingImage(index, accessoryId) {
    // Fetch current image data
    fetch(`/admin/accessories/${accessoryId}/get-image/${index}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const image = data.image;
                
                // Populate form
                document.getElementById('editImageIndex').value = index;
                document.getElementById('editAccessoryId').value = accessoryId;
                document.getElementById('editImagePreview').src = image.url || image;
                document.getElementById('editImageTitle').value = image.title || '';
                document.getElementById('editImageAlt').value = image.alt_text || image.alt || '';
                document.getElementById('editImageType').value = image.image_type || 'product';
                document.getElementById('editImageSort').value = image.sort_order || 0;
                document.getElementById('editImageDesc').value = image.description || '';
                document.getElementById('editImagePrimary').checked = image.is_primary || false;
                
                // Show modal
                document.getElementById('editImageModal').classList.remove('hidden');
            } else {
                showMessage(data.message || 'Không thể tải thông tin ảnh', 'error');
            }
        })
        .catch(error => {
            showMessage('Có lỗi xảy ra khi tải thông tin ảnh', 'error');
        });
}

// Close edit image modal
function closeEditImageModal() {
    document.getElementById('editImageModal').classList.add('hidden');
}

// Setup modal close on backdrop click and ESC key
document.addEventListener('DOMContentLoaded', function() {
    // Edit Image Modal
    const editModal = document.getElementById('editImageModal');
    if (editModal) {
        // Close on backdrop click
        editModal.addEventListener('click', function(e) {
            if (e.target === editModal) {
                closeEditImageModal();
            }
        });
    }
    
    // Add Images Modal
    const addModal = document.getElementById('addImageModal');
    if (addModal) {
        // Close on backdrop click
        addModal.addEventListener('click', function(e) {
            if (e.target === addModal) {
                closeAddImageModal();
            }
        });
    }
    
    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Check which modal is open and close it
            if (editModal && !editModal.classList.contains('hidden')) {
                closeEditImageModal();
            } else if (addModal && !addModal.classList.contains('hidden')) {
                closeAddImageModal();
            }
        }
    });
});

// Save edited image
function saveEditImage() {
    const index = document.getElementById('editImageIndex').value;
    const accessoryId = document.getElementById('editAccessoryId').value;
    const saveBtn = document.getElementById('saveEditImageBtn');
    const saveBtnText = document.getElementById('saveEditImageText');
    
    const data = {
        title: document.getElementById('editImageTitle').value,
        alt_text: document.getElementById('editImageAlt').value,
        image_type: document.getElementById('editImageType').value,
        sort_order: document.getElementById('editImageSort').value,
        description: document.getElementById('editImageDesc').value,
        is_primary: document.getElementById('editImagePrimary').checked
    };
    
    // Show spinner
    saveBtn.disabled = true;
    saveBtnText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang lưu...';
    
    // Send update request
    fetch(`/admin/accessories/${accessoryId}/update-image/${index}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(responseData => {
        // Reset button
        saveBtn.disabled = false;
        saveBtnText.innerHTML = 'Lưu thay đổi';
        
        if (responseData.success) {
            showMessage(responseData.message || 'Đã cập nhật ảnh thành công!', 'success');
            
            // Update UI card immediately instead of reloading
            updateImageCardUI(index, data);
            
            closeEditImageModal();
        } else {
            showMessage(responseData.message || 'Có lỗi xảy ra khi cập nhật ảnh', 'error');
        }
    })
    .catch(error => {
        saveBtn.disabled = false;
        saveBtnText.innerHTML = 'Lưu thay đổi';
        showMessage('Có lỗi xảy ra khi cập nhật ảnh', 'error');
    });
}

// Update image card UI after edit
function updateImageCardUI(index, data) {
    // Find all image cards
    const imageCards = document.querySelectorAll('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3 .image-card');
    
    if (imageCards[index]) {
        const card = imageCards[index];
        
        // Update title
        const titleEl = card.querySelector('.text-sm.font-medium.text-gray-800');
        if (titleEl) {
            titleEl.textContent = data.title || 'Không có tiêu đề';
        }
        
        // Update type badge (only type, no primary badge here)
        const badgeContainer = card.querySelector('.flex.items-center.gap-2.mb-2');
        if (badgeContainer) {
            const typeMap = {
                'product': 'Sản phẩm',
                'detail': 'Chi tiết',
                'installation': 'Lắp đặt',
                'usage': 'Sử dụng'
            };
            
            badgeContainer.innerHTML = `
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                    ${typeMap[data.image_type] || data.image_type}
                </span>
            `;
        }
        
        // Update primary badge overlay (on image)
        const imageContainer = card.querySelector('.aspect-video.relative');
        if (imageContainer) {
            // Remove existing primary badge
            const existingBadge = imageContainer.querySelector('.primary-badge');
            if (existingBadge) {
                existingBadge.remove();
            }
            
            // Add new badge if primary
            if (data.is_primary) {
                const badgeHtml = `
                    <div class="primary-badge absolute top-2 left-2 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-semibold flex items-center gap-1 shadow-lg">
                        <i class="fas fa-star"></i>
                        <span>Chính</span>
                    </div>
                `;
                imageContainer.insertAdjacentHTML('beforeend', badgeHtml);
                
                // Remove primary badge from other cards
                imageCards.forEach((otherCard, i) => {
                    if (i !== parseInt(index)) {
                        const otherImageContainer = otherCard.querySelector('.aspect-video.relative');
                        if (otherImageContainer) {
                            const otherBadge = otherImageContainer.querySelector('.primary-badge');
                            if (otherBadge) {
                                otherBadge.remove();
                            }
                        }
                    }
                });
            }
        }
        
        // Update metadata section (alt text and description)
        const metaContainer = card.querySelector('.text-xs.text-gray-600.space-y-1');
        if (metaContainer) {
            metaContainer.innerHTML = `
                <div>${data.alt_text || 'Không có alt text'}</div>
                ${data.description ? `<div class="text-gray-500 italic">${data.description}</div>` : ''}
            `;
        }
    }
}

// Delete existing image from gallery
function deleteExistingImage(index, accessoryId) {
    if (!window.deleteModalManager_deleteImageModal) {
        alert('Modal chưa được khởi tạo!');
        return;
    }
    
    const deleteUrl = `/admin/accessories/${accessoryId}/delete-image/${index}`;
    
    // Get image title from DOM
    const imageCard = document.querySelector(`.image-card[data-image-index="${index}"]`);
    let imageName = `Hình ảnh #${index + 1}`;
    
    if (imageCard) {
        // Try to get title from the card
        const titleElement = imageCard.querySelector('p.text-sm.font-medium.text-gray-800');
        if (titleElement && titleElement.textContent.trim()) {
            imageName = titleElement.textContent.trim();
        }
    }
    
    // Show modal
    window.deleteModalManager_deleteImageModal.show({
        entityName: imageName,
        deleteUrl: deleteUrl
    });
}

</script>
@endpush
{{-- Delete Image Modal --}}
<x-admin.delete-modal 
    modal-id="deleteImageModal"
    title="Xác nhận xóa hình ảnh"
    delete-callback-name="confirmDeleteImage" />

{{-- Edit Image Modal --}}
<div id="editImageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden">
        {{-- Header --}}
        <div class="flex-shrink-0 flex items-center justify-between px-6 py-4 border-b border-gray-200 rounded-t-xl">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-edit text-blue-600 mr-2"></i>
                Chỉnh sửa hình ảnh
            </h3>
            <button type="button" onclick="closeEditImageModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        {{-- Body --}}
        <div class="flex-1 overflow-y-auto px-6 py-6">
            <form id="editImageForm" class="space-y-4">
                <input type="hidden" id="editImageIndex">
                <input type="hidden" id="editAccessoryId">
                
                {{-- Image Preview --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hình ảnh hiện tại</label>
                    <div class="w-full h-48 bg-gray-100 rounded-lg overflow-hidden">
                        <img id="editImagePreview" src="" alt="" class="w-full h-full object-cover">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="editImageTitle" class="block text-sm font-medium text-gray-700 mb-2">Tiêu đề</label>
                        <input type="text" id="editImageTitle"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               placeholder="Tiêu đề hình ảnh">
                    </div>
                    <div>
                        <label for="editImageAlt" class="block text-sm font-medium text-gray-700 mb-2">Alt Text</label>
                        <input type="text" id="editImageAlt"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               placeholder="Mô tả cho SEO">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="editImageType" class="block text-sm font-medium text-gray-700 mb-2">Loại hình ảnh</label>
                        <select id="editImageType" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="product">Sản phẩm</option>
                            <option value="detail">Chi tiết</option>
                            <option value="installation">Lắp đặt</option>
                            <option value="usage">Sử dụng</option>
                        </select>
                    </div>
                    <div>
                        <label for="editImageSort" class="block text-sm font-medium text-gray-700 mb-2">Thứ tự</label>
                        <input type="number" id="editImageSort" min="0"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div>
                    <label for="editImageDesc" class="block text-sm font-medium text-gray-700 mb-2">Mô tả riêng</label>
                    <textarea id="editImageDesc" rows="2"
                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="Mô tả chi tiết cho hình ảnh này"></textarea>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="editImagePrimary"
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="editImagePrimary" class="ml-2 block text-sm text-gray-900">
                        Đặt làm ảnh chính
                    </label>
                </div>
            </form>
        </div>
        
        {{-- Footer --}}
        <div class="flex-shrink-0 bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-between items-center rounded-b-xl">
            <button type="button" onclick="closeEditImageModal()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg font-medium transition-colors">
                <i class="fas fa-times mr-2"></i>
                Hủy
            </button>
            <button type="button" id="saveEditImageBtn" onclick="saveEditImage()"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                <i class="fas fa-save mr-2"></i>
                <span id="saveEditImageText">Lưu thay đổi</span>
            </button>
        </div>
    </div>
</div>

{{-- Add Images Modal (Multiple Upload) --}}
<div id="addImageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden">
        {{-- Header --}}
        <div class="flex-shrink-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between rounded-t-xl">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-images text-blue-600"></i>
                </div>
                <h3 class="ml-3 text-lg font-semibold text-gray-900">Thêm Hình Ảnh Mới</h3>
            </div>
            <button type="button" onclick="closeAddImageModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        {{-- Content --}}
        <div class="flex-1 overflow-y-auto p-6">
            {{-- File Upload Area --}}
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Chọn hình ảnh</label>
                <div id="modalDropZone" class="text-center hover:border-blue-400 transition-colors cursor-pointer">
                    <input type="file" id="modalImageFiles" multiple accept="image/*" class="hidden">
                    <div onclick="document.getElementById('modalImageFiles').click()">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                        <p class="text-base font-medium text-gray-700 mb-1">Click để chọn hình ảnh</p>
                        <p class="text-sm text-gray-500">Hoặc kéo thả file vào đây</p>
                        <p class="text-xs text-gray-400 mt-2">Hỗ trợ: JPG, PNG, GIF (Tối đa 10MB mỗi file)</p>
                    </div>
                </div>
                
                {{-- Selected Files List --}}
                <div id="modalFilesList" class="mt-4 hidden">
                    <p class="text-sm font-medium text-gray-700 mb-2">Ảnh đã chọn:</p>
                    <div id="modalFilesItems" class="space-y-2">
                        {{-- File items will be added here --}}
                    </div>
                </div>
            </div>
            
            {{-- Individual Settings Section --}}
            <div id="modalIndividualSettings" class="hidden">
                <div class="mb-4 pb-3 border-b border-gray-200">
                    <h4 class="text-sm font-medium text-gray-900 flex items-center">
                        <i class="fas fa-cog mr-2 text-blue-600"></i>
                        Cài đặt cho từng ảnh
                    </h4>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-magic mr-1"></i>
                        Thông tin đã được tự động tạo, bạn có thể chỉnh sửa nếu cần
                    </p>
                </div>
                <div id="modalImagesList" class="space-y-4">
                    {{-- Will be populated by JavaScript --}}
                </div>
            </div>
        </div>
        
        {{-- Footer --}}
        <div class="flex-shrink-0 bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-between items-center rounded-b-xl">
            <button type="button" onclick="closeAddImageModal()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg font-medium transition-colors">
                <i class="fas fa-times mr-2"></i>
                Hủy
            </button>
            <button type="button" id="modalSubmitBtn" onclick="submitMultipleImages()"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                <i class="fas fa-upload mr-2"></i>
                Thêm hình ảnh
            </button>
        </div>
    </div>
</div>

@endsection





