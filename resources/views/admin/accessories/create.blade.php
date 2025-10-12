@extends('layouts.admin')

@section('title', 'Thêm phụ kiện')

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
                    <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                    Thêm phụ kiện mới
                </h1>
                <p class="text-sm text-gray-600 mt-1">Tạo phụ kiện xe hơi mới với đầy đủ thông tin</p>
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
                <span class="hidden sm:inline">Thông số</span> (<span id="specs-count">0</span>)
            </button>
            <button type="button" class="tab-button py-3 px-2 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap" data-tab="features">
                <i class="fas fa-star mr-1.5"></i>
                <span class="hidden sm:inline">Tính năng</span> (<span id="features-count">0</span>)
            </button>
            <button type="button" class="tab-button py-3 px-2 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap" data-tab="images">
                <i class="fas fa-images mr-1.5"></i>
                <span class="hidden sm:inline">Ảnh</span> (<span id="images-count">0</span>)
            </button>
            <button type="button" class="tab-button py-3 px-2 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap" data-tab="seo">
                <i class="fas fa-search mr-1.5"></i>
                <span class="hidden sm:inline">SEO</span>
            </button>
        </nav>
    </div>

    {{-- Form --}}
    <form id="accessoryForm" action="{{ route('admin.accessories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

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
                                   value="{{ old('name') }}" placeholder="Ví dụ: Lót sàn, Phim cách nhiệt...">
                        </div>

                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                                Mã SKU <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="sku" id="sku" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('sku') }}" placeholder="ACC-001">
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Danh mục <span class="text-red-500">*</span>
                            </label>
                            <select name="category" id="category" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Chọn danh mục</option>
                                <option value="interior" {{ old('category') == 'interior' ? 'selected' : '' }}>Nội thất</option>
                                <option value="exterior" {{ old('category') == 'exterior' ? 'selected' : '' }}>Ngoại thất</option>
                                <option value="electronics" {{ old('category') == 'electronics' ? 'selected' : '' }}>Điện tử</option>
                                <option value="performance" {{ old('category') == 'performance' ? 'selected' : '' }}>Hiệu suất</option>
                                <option value="safety" {{ old('category') == 'safety' ? 'selected' : '' }}>An toàn</option>
                                <option value="maintenance" {{ old('category') == 'maintenance' ? 'selected' : '' }}>Bảo dưỡng</option>
                            </select>
                        </div>

                        <div>
                            <label for="subcategory" class="block text-sm font-medium text-gray-700 mb-2">Danh mục con</label>
                            <input type="text" name="subcategory" id="subcategory" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('subcategory') }}" placeholder="Ví dụ: Thảm lót sàn">
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
                                      placeholder="Mô tả ngắn gọn về phụ kiện...">{{ old('short_description') }}</textarea>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả chi tiết</label>
                            <textarea name="description" id="description" rows="6" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                      placeholder="Mô tả chi tiết về phụ kiện, tính năng, lợi ích...">{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">Trọng lượng (kg)</label>
                                <input type="number" name="weight" id="weight" step="0.01"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('weight') }}" placeholder="0.5">
                            </div>
                            <div>
                                <label for="dimensions" class="block text-sm font-medium text-gray-700 mb-2">Kích thước</label>
                                <input type="text" name="dimensions" id="dimensions" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('dimensions') }}" placeholder="L x W x H">
                            </div>
                            <div>
                                <label for="material" class="block text-sm font-medium text-gray-700 mb-2">Chất liệu</label>
                                <input type="text" name="material" id="material" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('material') }}" placeholder="Nhựa, Da, Kim loại...">
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
                                           value="{{ old('base_price') }}" placeholder="0" onchange="updateCurrentPriceFromBase()">
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
                                           value="{{ old('current_price') }}" placeholder="0">
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
                                   {{ old('is_on_sale') ? 'checked' : '' }}>
                            <label for="is_on_sale" class="ml-2 block text-sm text-gray-900">
                                <i class="fas fa-percentage text-orange-500 mr-1"></i>
                                Đang khuyến mãi
                            </label>
                        </div>

                        <div id="sale-dates" class="grid grid-cols-2 gap-4" style="display: none;">
                            <div>
                                <label for="sale_start_date" class="block text-sm font-medium text-gray-700 mb-2">Ngày bắt đầu KM</label>
                                <input type="date" name="sale_start_date" id="sale_start_date" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('sale_start_date') }}">
                            </div>
                            <div>
                                <label for="sale_end_date" class="block text-sm font-medium text-gray-700 mb-2">Ngày kết thúc KM</label>
                                <input type="date" name="sale_end_date" id="sale_end_date" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('sale_end_date') }}">
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
                                   {{ old('installation_service_available') ? 'checked' : '' }}>
                            <label for="installation_service_available" class="ml-2 block text-sm text-gray-900">
                                <i class="fas fa-wrench text-blue-500 mr-1"></i>
                                Có dịch vụ lắp đặt
                            </label>
                        </div>

                        <div id="installation-details" style="display: none;">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="installation_fee" class="block text-sm font-medium text-gray-700 mb-2">Phí lắp đặt</label>
                                    <div class="relative">
                                        <input type="number" name="installation_fee" id="installation_fee" 
                                               class="block w-full px-3 py-2 pr-12 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                               value="{{ old('installation_fee') }}" placeholder="0">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">VNĐ</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label for="installation_time_minutes" class="block text-sm font-medium text-gray-700 mb-2">Thời gian lắp đặt (phút)</label>
                                    <input type="number" name="installation_time_minutes" id="installation_time_minutes" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                           value="{{ old('installation_time_minutes') }}" placeholder="30">
                                </div>
                            </div>
                            
                            <div>
                                <label for="installation_requirements" class="block text-sm font-medium text-gray-700 mb-2">Yêu cầu lắp đặt</label>
                                <textarea name="installation_requirements" id="installation_requirements" rows="3" 
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                          placeholder="Yêu cầu đặc biệt khi lắp đặt...">{{ old('installation_requirements') }}</textarea>
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
                                       value="{{ old('stock_quantity') }}" placeholder="Nhập số lượng tồn kho" min="1">
                            </div>
                            <div>
                                <label for="stock_status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái kho</label>
                                <select name="stock_status" id="stock_status" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="in_stock" {{ old('stock_status') == 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                                    <option value="low_stock" {{ old('stock_status') == 'low_stock' ? 'selected' : '' }}>Sắp hết hàng</option>
                                    <option value="out_of_stock" {{ old('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                                    <option value="discontinued" {{ old('stock_status') == 'discontinued' ? 'selected' : '' }}>Ngừng kinh doanh</option>
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
                            <input type="hidden" name="color_options" id="color_options_hidden" value="[]">
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
                                       value="{{ old('warranty_months') }}" placeholder="12" min="0">
                            </div>
                            <div>
                                <label for="return_policy_days" class="block text-sm font-medium text-gray-700 mb-2">Chính sách đổi trả (ngày)</label>
                                <input type="number" name="return_policy_days" id="return_policy_days" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('return_policy_days') }}" placeholder="30" min="0">
                            </div>
                        </div>

                        <div>
                            <label for="warranty_terms" class="block text-sm font-medium text-gray-700 mb-2">Điều kiện bảo hành</label>
                            <textarea name="warranty_terms" id="warranty_terms" rows="4" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                      placeholder="Điều kiện và điều khoản bảo hành...">{{ old('warranty_terms') }}</textarea>
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
                                    <!-- Compatible brands will be added here -->
                                </div>
                                <button type="button" id="add-compatible-brand" class="mt-2 inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-plus mr-1"></i>
                                    Thêm hãng xe
                                </button>
                            </div>
                            <input type="hidden" name="compatible_car_brands" id="compatible_car_brands_hidden">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dòng xe tương thích</label>
                            <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                                <div id="compatible-models-container" class="space-y-2">
                                    <!-- Compatible models will be added here -->
                                </div>
                                <button type="button" id="add-compatible-model" class="mt-2 inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-plus mr-1"></i>
                                    Thêm dòng xe
                                </button>
                            </div>
                            <input type="hidden" name="compatible_car_models" id="compatible_car_models_hidden">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Năm sản xuất tương thích</label>
                            <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                                <div id="compatible-years-container" class="space-y-2">
                                    <!-- Compatible years will be added here -->
                                </div>
                                <button type="button" id="add-compatible-year" class="mt-2 inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-plus mr-1"></i>
                                    Thêm năm sản xuất
                                </button>
                            </div>
                            <input type="hidden" name="compatible_car_years" id="compatible_car_years_hidden">
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
                        <!-- Specifications will be added here dynamically -->
                    </div>

                    <div id="no-specifications" class="text-center py-8 text-gray-500">
                        <i class="fas fa-cogs text-4xl mb-4"></i>
                        <p>Chưa có thông số kỹ thuật nào. Nhấn "Thêm thông số" để bắt đầu.</p>
                    </div>
                    <input type="hidden" name="specifications" id="specifications_hidden" value="[]">
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
                        <!-- Features will be added here dynamically -->
                    </div>

                    <div id="no-features" class="text-center py-8 text-gray-500">
                        <i class="fas fa-star text-4xl mb-4"></i>
                        <p>Chưa có tính năng nào. Nhấn "Thêm tính năng" để bắt đầu.</p>
                    </div>
                    <input type="hidden" name="features" id="features_hidden" value="[]">
                </div>
            </div>

            {{-- Images Tab --}}
            <div id="images-tab" class="tab-content hidden">
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-images text-blue-600 mr-2"></i>
                            Hình ảnh sản phẩm (<span id="images-count">0</span>)
                        </h3>
                        <button type="button" onclick="openAddImageModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Thêm hình ảnh
                        </button>
                    </div>

                    <div id="gallery-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Images will be added here dynamically -->
                    </div>

                    <div id="no-images" class="text-center py-8 text-gray-500 col-span-full">
                        <i class="fas fa-images text-5xl text-gray-400 mb-4"></i>
                        <p class="text-lg">Chưa có hình ảnh nào</p>
                        <p class="text-sm mt-2">Nhấn "Thêm hình ảnh" để upload ảnh sản phẩm</p>
                    </div>
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
                                   value="{{ old('slug') }}" placeholder="phu-kien-xe-hoi">
                            <p class="mt-1 text-xs text-gray-500">URL thân thiện SEO (tự động tạo từ tên nếu để trống)</p>
                        </div>
                        
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('meta_title') }}" placeholder="Tiêu đề trang cho SEO">
                            <p class="mt-1 text-xs text-gray-500">Nên từ 50-60 ký tự</p>
                        </div>

                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="3" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                      placeholder="Mô tả trang cho SEO...">{{ old('meta_description') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Nên từ 150-160 ký tự</p>
                        </div>

                        <div>
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                            <input type="text" name="meta_keywords" id="meta_keywords" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('meta_keywords') }}" placeholder="từ khóa 1, từ khóa 2, từ khóa 3">
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
                                       {{ old('is_featured') ? 'checked' : '' }}>
                                <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                    Sản phẩm nổi bật
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="hidden" name="is_bestseller" value="0">
                                <input type="checkbox" name="is_bestseller" id="is_bestseller" value="1" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                       {{ old('is_bestseller') ? 'checked' : '' }}>
                                <label for="is_bestseller" class="ml-2 block text-sm text-gray-900">
                                    Bán chạy nhất
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="hidden" name="is_new_arrival" value="0">
                                <input type="checkbox" name="is_new_arrival" id="is_new_arrival" value="1" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                       {{ old('is_new_arrival') ? 'checked' : '' }}>
                                <label for="is_new_arrival" class="ml-2 block text-sm text-gray-900">
                                    Hàng mới về
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Thứ tự sắp xếp</label>
                            <input type="number" name="sort_order" id="sort_order" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('sort_order', 0) }}" placeholder="0" min="0">
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
                <button type="button" id="mainSaveBtn" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-save mr-2" id="saveBtnIcon"></i>
                    <span id="saveBtnText">Thêm phụ kiện</span>
                </button>
            </div>
        </div>
    </form>
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

@push('scripts')
<script>
// Tab system
let currentTab = 'basic';
let specsCount = 0;
let featuresCount = 0;
let imagesCount = 0;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    initializeTabs();
    initializeConditionalFields();
    initializeDynamicSections();
    initializeValidation();
});

// Tab functionality
function initializeTabs() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

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

// Dynamic sections
function initializeDynamicSections() {
    // Specifications
    document.getElementById('addSpecBtn').addEventListener('click', addSpecification);
    
    // Features
    document.getElementById('addFeatureBtn').addEventListener('click', addFeature);
    
    // Images - handled by modal (openAddImageModal)
}

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
    
    // Add event listeners to new item
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

function updateSpecificationsJSON() {
    const specifications = [];
    const specItems = document.querySelectorAll('.spec-item');
    
    specItems.forEach((item, index) => {
        const category = item.querySelector('.spec-category')?.value;
        const name = item.querySelector('.spec-name')?.value;
        const value = item.querySelector('.spec-value')?.value;
        
        if (name && value) {
            specifications.push({
                category: category || 'other',
                name: name.trim(),
                value: value.trim(),
                sort_order: index
            });
        }
    });
    
    // Remove duplicates based on name
    const uniqueSpecifications = [];
    const seenNames = new Set();
    
    for (const spec of specifications) {
        const key = spec.name.toLowerCase();
        if (!seenNames.has(key)) {
            seenNames.add(key);
            uniqueSpecifications.push(spec);
        }
    }
    
    document.getElementById('specifications_hidden').value = JSON.stringify(uniqueSpecifications);
}

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
    
    // Add event listeners to new item
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

function updateFeaturesJSON() {
    const features = [];
    const featureItems = document.querySelectorAll('.feature-item');
    
    featureItems.forEach((item, index) => {
        const category = item.querySelector('.feature-category')?.value;
        const name = item.querySelector('.feature-name')?.value;
        const price = item.querySelector('.feature-price')?.value;
        const sortOrder = item.querySelector('.feature-sort')?.value;
        
        if (name) {
            features.push({
                category: category || 'other',
                name: name.trim(),
                price: parseFloat(price) || 0,
                sort_order: parseInt(sortOrder) || index
            });
        }
    });
    
    // Remove duplicates based on name
    const uniqueFeatures = [];
    const seenNames = new Set();
    
    for (const feature of features) {
        const key = feature.name.toLowerCase();
        if (!seenNames.has(key)) {
            seenNames.add(key);
            uniqueFeatures.push(feature);
        }
    }
    
    document.getElementById('features_hidden').value = JSON.stringify(uniqueFeatures);
}

// Multiple files storage
let modalSelectedFiles = [];
// Store uploaded files globally for form submission  
let uploadedGalleryFiles = [];

// Open add image modal
function openAddImageModal() {
    const modal = document.getElementById('addImageModal');
    if (!modal) {
        return;
    }
    modal.classList.remove('hidden');
    
    const modalImageFiles = document.getElementById('modalImageFiles');
    if (modalImageFiles) {
        modalImageFiles.value = '';
    }
    
    const modalFilesList = document.getElementById('modalFilesList');
    if (modalFilesList) {
        modalFilesList.classList.add('hidden');
    }
    
    const modalIndividualSettings = document.getElementById('modalIndividualSettings');
    if (modalIndividualSettings) {
        modalIndividualSettings.classList.add('hidden');
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
    
    if (dropZone) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.add('border-blue-500', 'bg-blue-50');
            }, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.remove('border-blue-500', 'bg-blue-50');
            }, false);
        });
        
        dropZone.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleModalFiles(files);
        }, false);
    }
    
    // Handle primary checkbox - only one can be primary
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('image-primary') && e.target.checked) {
            document.querySelectorAll('.image-primary').forEach(cb => {
                if (cb !== e.target) {
                    cb.checked = false;
                }
            });
        }
    });
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

function handleModalFiles(files) {
    if (!files || files.length === 0) return;
    
    // Validate file count
    if (files.length > 10) {
        if (window.showMessage) {
            window.showMessage('Chỉ được chọn tối đa 10 file', 'error');
        }
        return;
    }
    
    // Validate and store files
    modalSelectedFiles = [];
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        
        // Validate file type
        if (!file.type.startsWith('image/')) {
            continue;
        }
        
        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            if (window.showMessage) {
                window.showMessage(`File ${file.name} quá lớn (> 5MB)`, 'error');
            }
            continue;
        }
        
        modalSelectedFiles.push(file);
    }
    
    if (modalSelectedFiles.length > 0) {
        displayModalSelectedFiles();
        generateModalImageSettings();
    }
}

function displayModalSelectedFiles() {
    const filesList = document.getElementById('modalFilesList');
    const filesItems = document.getElementById('modalFilesItems');
    
    if (!filesList || !filesItems) return;
    
    filesItems.innerHTML = '';
    
    modalSelectedFiles.forEach((file, index) => {
        const fileItem = document.createElement('div');
        fileItem.className = 'flex items-center justify-between p-2 bg-gray-50 rounded-lg';
        fileItem.innerHTML = `
            <div class="flex items-center gap-3">
                <i class="fas fa-image text-blue-600"></i>
                <span class="text-sm text-gray-700">${file.name}</span>
                <span class="text-xs text-gray-500">(${(file.size / 1024).toFixed(1)} KB)</span>
            </div>
            <button type="button" onclick="removeModalFile(${index})" class="text-red-600 hover:text-red-800">
                <i class="fas fa-times"></i>
            </button>
        `;
        filesItems.appendChild(fileItem);
    });
    
    filesList.classList.remove('hidden');
}

function removeModalFile(index) {
    modalSelectedFiles.splice(index, 1);
    
    if (modalSelectedFiles.length === 0) {
        document.getElementById('modalFilesList').classList.add('hidden');
        document.getElementById('modalIndividualSettings').classList.add('hidden');
        document.getElementById('modalImageFiles').value = '';
    } else {
        displayModalSelectedFiles();
        generateModalImageSettings();
    }
}

function generateModalImageSettings() {
    const modalImagesList = document.getElementById('modalImagesList');
    const modalIndividualSettings = document.getElementById('modalIndividualSettings');
    
    if (!modalImagesList || !modalIndividualSettings) return;
    
    modalImagesList.innerHTML = '';
    
    modalSelectedFiles.forEach((file, index) => {
        // Generate smart metadata for this image
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
        
        modalImagesList.appendChild(settingCard);
    });
    
    modalIndividualSettings.classList.remove('hidden');
}

function handleModalPrimaryChange(index) {
    // Uncheck all other primary checkboxes
    modalSelectedFiles.forEach((file, i) => {
        if (i !== index) {
            const checkbox = document.getElementById(`modalPrimary_${i}`);
            if (checkbox) {
                checkbox.checked = false;
            }
        }
    });
}

// Generate smart metadata based on filename and context
function generateSmartMetadata(file, index) {
    const accessoryName = document.getElementById('name')?.value || 'phụ kiện';
    
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
    const name = accessoryName || document.getElementById('name')?.value || 'phụ kiện';
    const descriptions = {
        'product': `Hình ảnh tổng quan ${name}, thể hiện đầy đủ thiết kế và tính năng nổi bật của sản phẩm.`,
        'detail': `Ảnh chi tiết cận cảnh ${name}, làm nổi bật chất lượng hoàn thiện và các chi tiết quan trọng.`,
        'installation': `Hướng dẫn lắp đặt ${name}, minh họa rõ ràng quy trình và vị trí lắp đặt sản phẩm.`,
        'usage': `Ảnh sử dụng thực tế ${name}, thể hiện sản phẩm trong môi trường sử dụng hàng ngày.`
    };
    return descriptions[type] || descriptions['product'];
}

// Update description when image type changes
function updateDescriptionForType(index, type) {
    const accessoryName = document.getElementById('name')?.value || 'phụ kiện';
    const descTextarea = document.getElementById(`modalDesc_${index}`);
    if (descTextarea) {
        descTextarea.value = getDescriptionForType(type, accessoryName);
    }
}

function submitMultipleImages() {
    if (modalSelectedFiles.length === 0) {
        if (window.showMessage) {
            window.showMessage('Vui lòng chọn ít nhất 1 hình ảnh', 'error');
        }
        return;
    }
    
    // Validate all required fields
    let isValid = true;
    modalSelectedFiles.forEach((file, index) => {
        const title = document.getElementById(`modalTitle_${index}`);
        const alt = document.getElementById(`modalAlt_${index}`);
        
        if (!title?.value || !alt?.value) {
            isValid = false;
        }
    });
    
    if (!isValid) {
        if (window.showMessage) {
            window.showMessage('Vui lòng điền đầy đủ tiêu đề và alt text cho tất cả ảnh', 'error');
        }
        return;
    }
    
    // Save count before clearing
    const imagesAddedCount = modalSelectedFiles.length;
    
    // Add all images to gallery
    modalSelectedFiles.forEach((file, index) => {
        const data = {
            file: file,
            title: document.getElementById(`modalTitle_${index}`).value,
            altText: document.getElementById(`modalAlt_${index}`).value,
            imageType: document.getElementById(`modalType_${index}`).value,
            sortOrder: parseInt(document.getElementById(`modalSort_${index}`).value) || index,
            description: document.getElementById(`modalDesc_${index}`)?.value || '',
            isPrimary: document.getElementById(`modalPrimary_${index}`).checked
        };
        
        addImageToGallery(data);
    });
    
    closeAddImageModal();
    
    // Update tab count after adding images
    updateImagesCount();
}

function addImageToGallery(data) {
    const container = document.getElementById('gallery-container');
    const noImages = document.getElementById('no-images');
    
    if (!container) return;
    
    // Hide empty state
    if (noImages) {
        noImages.style.display = 'none';
    }
    
    // Remove primary badge from existing images if new image is primary
    if (data.isPrimary) {
        const existingCards = container.querySelectorAll('.image-card');
        existingCards.forEach(card => {
            const existingBadge = card.querySelector('.primary-badge');
            if (existingBadge) {
                existingBadge.remove();
            }
            // Also update the hidden input
            const primaryInput = card.querySelector('input[name$="[is_primary]"]');
            if (primaryInput) {
                primaryInput.value = '0';
            }
        });
    }
    
    imagesCount++;
    
    // Create object URL for preview
    let imageUrl;
    try {
        imageUrl = URL.createObjectURL(data.file);
    } catch (error) {
        if (window.showMessage) {
            window.showMessage('Không thể hiển thị ảnh', 'error');
        }
        return;
    }
    
    // Type mapping (Vietnamese labels)
    const typeMap = {
        'product': 'Sản phẩm',
        'detail': 'Chi tiết',
        'installation': 'Lắp đặt',
        'usage': 'Sử dụng'
    };
    
    const cardDiv = document.createElement('div');
    cardDiv.className = 'bg-white p-3 rounded-lg border border-gray-200 relative image-card';
    cardDiv.setAttribute('data-temp-id', imagesCount);
    
    cardDiv.innerHTML = `
        <div class="absolute top-2 right-2 z-10 flex gap-2">
            <button type="button" onclick="editImageFromGallery(${imagesCount})" 
                    class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-full shadow-lg transition-colors"
                    title="Sửa ảnh">
                <i class="fas fa-edit text-xs"></i>
            </button>
            <button type="button" onclick="removeImageFromGallery(${imagesCount})" 
                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full shadow-lg transition-colors"
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
            <input type="hidden" name="gallery[${imagesCount}][title]" value="${data.title}">
            <input type="hidden" name="gallery[${imagesCount}][alt_text]" value="${data.altText}">
            <input type="hidden" name="gallery[${imagesCount}][image_type]" value="${data.imageType}">
            <input type="hidden" name="gallery[${imagesCount}][sort_order]" value="${data.sortOrder}">
            <input type="hidden" name="gallery[${imagesCount}][description]" value="${data.description || ''}">
            <input type="hidden" name="gallery[${imagesCount}][is_primary]" value="${data.isPrimary ? '1' : '0'}">
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
    
    // Store file in hidden input for backward compatibility
    const fileInput = cardDiv.querySelector(`[data-file-storage="${imagesCount}"]`);
    if (fileInput) {
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(data.file);
        fileInput.files = dataTransfer.files;
    }
    
    // IMPORTANT: Also store file in global array for form submission
    uploadedGalleryFiles.push({
        file: data.file,
        index: imagesCount
    });
    
    updateImagesCount();
}

function removeImageFromGallery(tempId) {
    const card = document.querySelector(`[data-temp-id="${tempId}"]`);
    if (!card) return;
    
    // Get image title from card
    const titleElement = card.querySelector('p.text-sm.font-medium.text-gray-800');
    const imageTitle = titleElement ? titleElement.textContent.trim() : `Hình ảnh #${tempId}`;
    
    // Confirm before delete
    if (confirm(`Bạn có chắc chắn muốn xóa "${imageTitle}"?`)) {
        // Remove from global files array
        uploadedGalleryFiles = uploadedGalleryFiles.filter(item => item.index !== tempId);
        
        card.remove();
        updateImagesCount();
        
        // Show empty state if no images
        const container = document.getElementById('gallery-container');
        if (container && container.children.length === 0) {
            const noImages = document.getElementById('no-images');
            if (noImages) {
                noImages.style.display = 'block';
            }
        }
    }
}

function editImageFromGallery(tempId) {
    const card = document.querySelector(`[data-temp-id="${tempId}"]`);
    if (!card) return;
    
    // Get current data from hidden inputs
    const title = card.querySelector('input[name$="[title]"]')?.value || '';
    const altText = card.querySelector('input[name$="[alt_text]"]')?.value || '';
    const imageType = card.querySelector('input[name$="[image_type]"]')?.value || 'product';
    const sortOrder = card.querySelector('input[name$="[sort_order]"]')?.value || 0;
    const description = card.querySelector('input[name$="[description]"]')?.value || '';
    const isPrimary = card.querySelector('input[name$="[is_primary]"]')?.value === '1';
    
    // Get current image URL
    const currentImage = card.querySelector('.aspect-video img');
    const currentImageUrl = currentImage ? currentImage.src : '';
    
    // Populate modal
    document.getElementById('editTempId').value = tempId;
    document.getElementById('editImageTitle').value = title;
    document.getElementById('editImageAlt').value = altText;
    document.getElementById('editImageType').value = imageType;
    document.getElementById('editImageSort').value = sortOrder;
    document.getElementById('editImageDesc').value = description;
    document.getElementById('editImagePrimary').checked = isPrimary;
    document.getElementById('editImagePreview').src = currentImageUrl;
    
    // Clear file input
    document.getElementById('editImageFile').value = '';
    
    // Show modal
    document.getElementById('editImageModal').classList.remove('hidden');
}

function closeEditImageModal() {
    document.getElementById('editImageModal').classList.add('hidden');
}

function saveEditedImage() {
    const tempId = parseInt(document.getElementById('editTempId').value);
    const card = document.querySelector(`[data-temp-id="${tempId}"]`);
    if (!card) return;
    
    // Get new values from modal
    const title = document.getElementById('editImageTitle').value;
    const altText = document.getElementById('editImageAlt').value;
    const imageType = document.getElementById('editImageType').value;
    const sortOrder = document.getElementById('editImageSort').value;
    const description = document.getElementById('editImageDesc').value;
    const isPrimary = document.getElementById('editImagePrimary').checked;
    const fileInput = document.getElementById('editImageFile');
    const newFile = fileInput.files[0];
    
    // Update hidden inputs
    card.querySelector('input[name$="[title]"]').value = title;
    card.querySelector('input[name$="[alt_text]"]').value = altText;
    card.querySelector('input[name$="[image_type]"]').value = imageType;
    card.querySelector('input[name$="[sort_order]"]').value = sortOrder;
    card.querySelector('input[name$="[description]"]').value = description;
    card.querySelector('input[name$="[is_primary]"]').value = isPrimary ? '1' : '0';
    
    // If new file selected, update image and uploadedGalleryFiles
    if (newFile) {
        // Validate file
        if (!newFile.type.startsWith('image/')) {
            showMessage('Vui lòng chọn file ảnh hợp lệ', 'error');
            return;
        }
        if (newFile.size > 5 * 1024 * 1024) { // 5MB
            showMessage('Kích thước ảnh không được vượt quá 5MB', 'error');
            return;
        }
        
        // Create preview URL
        const reader = new FileReader();
        reader.onload = function(e) {
            // Update image display
            const imgElement = card.querySelector('.aspect-video img');
            if (imgElement) {
                imgElement.src = e.target.result;
            }
        };
        reader.readAsDataURL(newFile);
        
        // Update uploadedGalleryFiles array
        const fileIndex = uploadedGalleryFiles.findIndex(item => item.index === tempId);
        if (fileIndex !== -1) {
            uploadedGalleryFiles[fileIndex].file = newFile;
        }
    }
    
    // Update visible display
    const typeMap = {
        'product': 'Sản phẩm',
        'detail': 'Chi tiết',
        'installation': 'Lắp đặt',
        'usage': 'Sử dụng'
    };
    
    card.querySelector('.text-sm.font-medium.text-gray-800').textContent = title;
    card.querySelector('.text-xs.text-gray-600 > div').textContent = altText;
    card.querySelector('.inline-flex.items-center.px-2.py-1').textContent = typeMap[imageType] || imageType;
    
    // Update primary badge
    const existingBadge = card.querySelector('.primary-badge');
    if (isPrimary && !existingBadge) {
        const imageContainer = card.querySelector('.aspect-video');
        const badge = document.createElement('div');
        badge.className = 'primary-badge absolute top-2 left-2 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-semibold flex items-center gap-1 shadow-lg';
        badge.innerHTML = '<i class="fas fa-star"></i><span>Chính</span>';
        imageContainer.appendChild(badge);
    } else if (!isPrimary && existingBadge) {
        existingBadge.remove();
    }
    
    // Update description if exists
    const descElement = card.querySelector('.text-gray-500.italic');
    if (description) {
        if (descElement) {
            descElement.textContent = description;
        } else {
            const descDiv = document.createElement('div');
            descDiv.className = 'text-gray-500 italic';
            descDiv.textContent = description;
            card.querySelector('.text-xs.text-gray-600').appendChild(descDiv);
        }
    } else if (descElement) {
        descElement.remove();
    }
    
    closeEditImageModal();
    showMessage(newFile ? 'Đã cập nhật ảnh và thông tin' : 'Đã cập nhật thông tin ảnh', 'success');
}

function updateSpecsCount() {
    const count = document.querySelectorAll('.spec-item').length;
    document.getElementById('specs-count').textContent = count;
}

function updateFeaturesCount() {
    const count = document.querySelectorAll('.feature-item').length;
    document.getElementById('features-count').textContent = count;
}

function updateImagesCount() {
    const count = document.querySelectorAll('.image-card').length;
    // Update all elements with images-count id (tab and section title)
    document.querySelectorAll('#images-count').forEach(el => {
        el.textContent = count;
    });
}

// Validation
function initializeValidation() {
    const form = document.getElementById('accessoryForm');
    const saveBtn = document.getElementById('mainSaveBtn');
    
    // Prevent form submission and handle via JavaScript
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            // Trigger save button click to use existing validation logic
            if (saveBtn) {
                saveBtn.click();
            }
        });
    }

    if (saveBtn) {
        saveBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get current tab
            const currentTabButton = document.querySelector('.tab-button.active');
            const currentTabId = currentTabButton ? currentTabButton.getAttribute('data-tab') : 'basic';
            
            // Validate tất cả các trường bắt buộc (không chỉ tab hiện tại)
            const validationResult = validateAllRequiredFields();
            if (!validationResult.isValid) {
                // Switch to tab chứa lỗi
                if (validationResult.tabId) {
                    switchToTab(validationResult.tabId);
                }
                
                // Focus the field with error
                if (validationResult.element) {
                    validationResult.element.focus();
                    validationResult.element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                
                // Show error message
                if (window.showMessage) {
                    window.showMessage(validationResult.message, 'error');
                }
                return false;
            }
            
            // All required fields are valid, submit form via AJAX
            submitFormViaAjax();
        });
    }
}

function validateCurrentTab(tabId) {
    switch(tabId) {
        case 'basic':
            return validateBasicTab();
        case 'pricing':
            return validatePricingTab();
        case 'inventory':
            return validateInventoryTab();
        case 'compatibility':
            return validateCompatibilityTab();
        case 'specifications':
            return validateSpecificationsTab();
        case 'features':
            return validateFeaturesTab();
        case 'images':
            return validateImagesTab();
        case 'seo':
            return validateSeoTab();
        default:
            return { isValid: true };
    }
}

function getNextTab(currentTabId) {
    // Các tab có trường bắt buộc theo thứ tự
    const requiredTabs = ['basic', 'pricing', 'inventory'];
    const currentIndex = requiredTabs.indexOf(currentTabId);
    
    if (currentIndex >= 0 && currentIndex < requiredTabs.length - 1) {
        return requiredTabs[currentIndex + 1];
    }
    
    return null; // No next required tab, ready to submit
}

function validateAllRequiredFields() {
    // Validate theo thứ tự ưu tiên: Basic → Pricing → Inventory
    
    // 1. Basic validation (tên, SKU, danh mục)
    const basicValidation = validateBasicTab();
    if (!basicValidation.isValid) return basicValidation;
    
    // 2. Pricing validation (giá gốc, giá hiện tại)
    const pricingValidation = validatePricingTab();
    if (!pricingValidation.isValid) return pricingValidation;
    
    // 3. Inventory validation (số lượng, trạng thái kho)
    const inventoryValidation = validateInventoryTab();
    if (!inventoryValidation.isValid) return inventoryValidation;
    
    return { isValid: true };
}

function validateAllTabs() {
    // Basic validation
    const basicValidation = validateBasicTab();
    if (!basicValidation.isValid) return basicValidation;
    
    // Pricing validation
    const pricingValidation = validatePricingTab();
    if (!pricingValidation.isValid) return pricingValidation;
    
    // SEO validation
    const seoValidation = validateSeoTab();
    if (!seoValidation.isValid) return seoValidation;
    
    return { isValid: true };
}

function validateBasicTab() {
    const name = document.getElementById('name');
    const sku = document.getElementById('sku');
    const category = document.getElementById('category');
    
    // Validate tên phụ kiện
    if (!name.value.trim()) {
        return {
            isValid: false,
            element: name,
            tabId: 'basic',
            message: 'Vui lòng nhập tên phụ kiện.'
        };
    }
    
    // Validate mã SKU
    if (!sku.value.trim()) {
        return {
            isValid: false,
            element: sku,
            tabId: 'basic',
            message: 'Vui lòng nhập mã SKU.'
        };
    }
    
    // Validate danh mục
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

// Add missing validation functions for other tabs
function validateInventoryTab() {
    const stockQuantity = document.getElementById('stock_quantity');
    const stockStatus = document.getElementById('stock_status');
    
    // Validate số lượng tồn kho
    if (!stockQuantity.value || parseFloat(stockQuantity.value) <= 0) {
        return {
            isValid: false,
            element: stockQuantity,
            tabId: 'inventory',
            message: 'Vui lòng nhập số lượng tồn kho (phải lớn hơn 0).'
        };
    }
    
    // Validate trạng thái kho
    if (!stockStatus.value) {
        return {
            isValid: false,
            element: stockStatus,
            tabId: 'inventory',
            message: 'Vui lòng chọn trạng thái kho.'
        };
    }
    
    return { isValid: true };
}

function validateCompatibilityTab() {
    // Compatibility tab validation - optional fields
    return { isValid: true };
}

function validateSpecificationsTab() {
    // Specifications tab validation - optional fields
    return { isValid: true };
}

function validateFeaturesTab() {
    // Features tab validation - optional fields
    return { isValid: true };
}

function validateImagesTab() {
    // Images tab validation - optional fields
    return { isValid: true };
}

function validatePricingTab() {
    const basePrice = document.getElementById('base_price');
    const currentPrice = document.getElementById('current_price');
    
    // Validate giá niêm yết
    if (!basePrice.value || parseFloat(basePrice.value) <= 0) {
        return {
            isValid: false,
            element: basePrice,
            tabId: 'pricing',
            message: 'Vui lòng nhập giá niêm yết hợp lệ (lớn hơn 0).'
        };
    }
    
    // Validate giá hiện tại
    if (!currentPrice.value || parseFloat(currentPrice.value) < 0) {
        return {
            isValid: false,
            element: currentPrice,
            tabId: 'pricing',
            message: 'Vui lòng nhập giá hiện tại hợp lệ.'
        };
    }
    
    return { isValid: true };
}

function validateSeoTab() {
    // SEO tab không bắt buộc - cho phép bỏ trống
    return { isValid: true };
}

function submitFormViaAjax() {
    const form = document.getElementById('accessoryForm');
    const saveBtn = document.getElementById('mainSaveBtn');
    const saveBtnIcon = saveBtn?.querySelector('i');
    const saveBtnText = saveBtn?.querySelector('span') || saveBtn;
    
    // Disable button and show loading
    if (saveBtn) {
        saveBtn.disabled = true;
        saveBtn.classList.add('opacity-75', 'cursor-not-allowed');
        if (saveBtnIcon) {
            saveBtnIcon.className = 'fas fa-spinner fa-spin mr-2';
        }
        if (saveBtnText.tagName === 'SPAN') {
            saveBtnText.textContent = 'Đang lưu...';
        } else {
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang lưu...';
        }
    }
    
    // Prepare gallery metadata JSON before submit
    const galleryMetadata = [];
    const imageCards = document.querySelectorAll('.image-card');
    imageCards.forEach((card, index) => {
        const isPrimaryValue = card.querySelector('input[name$="[is_primary]"]')?.value;
        const metadata = {
            title: card.querySelector('input[name$="[title]"]')?.value || '',
            alt_text: card.querySelector('input[name$="[alt_text]"]')?.value || '',
            image_type: card.querySelector('input[name$="[image_type]"]')?.value || 'product',
            sort_order: parseInt(card.querySelector('input[name$="[sort_order]"]')?.value) || index,
            description: card.querySelector('input[name$="[description]"]')?.value || ''
        };
        
        // Add is_primary only if true (to match edit page behavior)
        if (isPrimaryValue === '1' || isPrimaryValue === 1 || isPrimaryValue === true) {
            metadata.is_primary = true;
        }
        
        galleryMetadata.push(metadata);
    });
    
    // Create FormData from form (like edit page does)
    const formData = new FormData(form);
    
    // Add gallery metadata JSON (controller expects this)
    if (galleryMetadata.length > 0) {
        formData.set('gallery_json', JSON.stringify(galleryMetadata));
    }
    
    // CRITICAL: Remove old gallery entries and manually append files
    // Hidden file inputs may not be properly collected, so we do it manually
    const keysToDelete = [];
    for (let key of formData.keys()) {
        if (key.startsWith('gallery[') && key.includes('[file]')) {
            keysToDelete.push(key);
        }
    }
    keysToDelete.forEach(key => formData.delete(key));
    
    // Manually append files from uploadedGalleryFiles array
    if (uploadedGalleryFiles.length > 0) {
        uploadedGalleryFiles.forEach((item, idx) => {
            const sequentialIndex = idx + 1;
            formData.append(`gallery[${sequentialIndex}][file]`, item.file);
        });
    }
    
    // Submit via XMLHttpRequest (more reliable with files than fetch)
    const xhr = new XMLHttpRequest();
    xhr.open('POST', form.action, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            try {
                const data = JSON.parse(xhr.responseText);
                
                if (data && data.success) {
                    // Show success message
                    if (window.showMessage) {
                        window.showMessage(data.message || 'Tạo phụ kiện thành công!', 'success');
                    }
                    
                    // Redirect after 1.5 seconds
                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ route("admin.accessories.index") }}';
                    }, 1500);
                }
            } catch (e) {
                if (window.showMessage) {
                    window.showMessage('Lỗi xử lý response từ server', 'error');
                }
            }
        } else {
            // Handle error response
            try {
                const data = JSON.parse(xhr.responseText);
                if (xhr.status === 422 && data.errors) {
                    const firstError = Object.values(data.errors)[0][0];
                    if (window.showMessage) {
                        window.showMessage(firstError, 'error');
                    }
                } else if (data.message) {
                    if (window.showMessage) {
                        window.showMessage(data.message, 'error');
                    }
                }
            } catch (e) {
                if (window.showMessage) {
                    window.showMessage('Có lỗi xảy ra khi tạo phụ kiện', 'error');
                }
            }
        }
        
        // Re-enable button
        if (saveBtn) {
            saveBtn.disabled = false;
            saveBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            if (saveBtnIcon) {
                saveBtnIcon.className = 'fas fa-save mr-2';
            }
            if (saveBtnText.tagName === 'SPAN') {
                saveBtnText.textContent = 'Thêm phụ kiện';
            } else {
                saveBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Thêm phụ kiện';
            }
        }
    };
    
    xhr.onerror = function() {
        if (window.showMessage) {
            window.showMessage('Lỗi kết nối đến server', 'error');
        }
        
        // Re-enable button
        if (saveBtn) {
            saveBtn.disabled = false;
            saveBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            if (saveBtnIcon) {
                saveBtnIcon.className = 'fas fa-save mr-2';
            }
            if (saveBtnText.tagName === 'SPAN') {
                saveBtnText.textContent = 'Thêm phụ kiện';
            } else {
                saveBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Thêm phụ kiện';
            }
        }
    };
    
    // Send FormData
    xhr.send(formData);
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
    button.closest('div').remove();
    updateColorOptionsJSON();
}

function updateColorOptionsJSON() {
    const container = document.getElementById('color-options-container');
    const colorOptions = [];
    
    container.querySelectorAll('.flex.items-center').forEach(div => {
        const name = div.querySelector('.color-name-input').value;
        const hex = div.querySelector('.hex-input').value;
        if (name && hex) {
            colorOptions.push({ name, hex });
        }
    });
    
    document.getElementById('color_options_hidden').value = JSON.stringify(colorOptions);
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
        if (input.value.trim()) {
            brands.push(input.value.trim());
        }
    });
    
    document.getElementById('compatible_car_brands_hidden').value = JSON.stringify(brands);
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
        if (input.value.trim()) {
            models.push(input.value.trim());
        }
    });
    
    document.getElementById('compatible_car_models_hidden').value = JSON.stringify(models);
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

// Initialize event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Color options
    document.getElementById('add-color-option').addEventListener('click', () => addColorOption());
    
    // Compatible brands
    document.getElementById('add-compatible-brand').addEventListener('click', () => addCompatibleBrand());
    
    // Compatible models
    document.getElementById('add-compatible-model').addEventListener('click', () => addCompatibleModel());
    
    // Compatible years
    document.getElementById('add-compatible-year').addEventListener('click', () => addCompatibleYear());
    
    // No default colors - let user add as needed
    
    // Edit Image Modal - Close on backdrop click
    const editModal = document.getElementById('editImageModal');
    if (editModal) {
        editModal.addEventListener('click', function(e) {
            if (e.target === editModal) {
                closeEditImageModal();
            }
        });
        
        // ESC key to close
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !editModal.classList.contains('hidden')) {
                closeEditImageModal();
            }
        });
    }
    
    // File input preview in edit modal
    const editFileInput = document.getElementById('editImageFile');
    if (editFileInput) {
        editFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('editImagePreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

// Edit Image Modal HTML
</script>

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
            <input type="hidden" id="editTempId">
            
            <form id="editImageForm" class="space-y-4">
                {{-- Image Preview & Upload --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ảnh hiện tại</label>
                    <div class="w-full h-48 bg-gray-100 rounded-lg overflow-hidden mb-3">
                        <img id="editImagePreview" src="" alt="Preview" class="w-full h-full object-cover">
                    </div>
                    <label class="block">
                        <span class="sr-only">Chọn ảnh mới</span>
                        <input type="file" id="editImageFile" accept="image/*"
                               class="block w-full text-sm text-gray-500
                               file:mr-4 file:py-2 file:px-4
                               file:rounded-lg file:border-0
                               file:text-sm file:font-semibold
                               file:bg-blue-50 file:text-blue-700
                               hover:file:bg-blue-100
                               cursor-pointer">
                    </label>
                    <p class="mt-2 text-xs text-gray-500">
                        Chọn ảnh mới để thay thế (JPG, PNG, GIF, tối đa 5MB)
                    </p>
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
            <button type="button" onclick="saveEditedImage()"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                <i class="fas fa-save mr-2"></i>
                <span>Lưu thay đổi</span>
            </button>
        </div>
    </div>
</div>

@endpush
@endsection