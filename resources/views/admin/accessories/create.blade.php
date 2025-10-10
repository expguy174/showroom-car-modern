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
        <nav class="flex space-x-8 px-6" aria-label="Tabs">
            <button type="button" class="tab-button active py-4 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600" data-tab="basic">
                <i class="fas fa-info-circle mr-2"></i>
                Thông tin cơ bản
            </button>
            <button type="button" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="pricing">
                <i class="fas fa-tags mr-2"></i>
                Giá & Khuyến mãi
            </button>
            <button type="button" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="inventory">
                <i class="fas fa-boxes mr-2"></i>
                Kho & Tồn kho
            </button>
            <button type="button" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="compatibility">
                <i class="fas fa-car mr-2"></i>
                Tương thích
            </button>
            <button type="button" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="specifications">
                <i class="fas fa-cogs mr-2"></i>
                Thông số (<span id="specs-count">0</span>)
            </button>
            <button type="button" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="features">
                <i class="fas fa-star mr-2"></i>
                Tính năng (<span id="features-count">0</span>)
            </button>
            <button type="button" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="images">
                <i class="fas fa-images mr-2"></i>
                Hình ảnh (<span id="images-count">0</span>)
            </button>
            <button type="button" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="seo">
                <i class="fas fa-search mr-2"></i>
                SEO & Marketing
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
                                    <!-- Color options will be added here -->
                                </div>
                                <button type="button" id="add-color-option" class="mt-3 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-plus mr-2"></i>
                                    Thêm màu sắc
                                </button>
                            </div>
                            <input type="hidden" name="color_options" id="color_options_hidden">
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
                                       value="{{ old('warranty_months') }}" placeholder="12">
                            </div>
                            <div>
                                <label for="return_policy_days" class="block text-sm font-medium text-gray-700 mb-2">Chính sách đổi trả (ngày)</label>
                                <input type="number" name="return_policy_days" id="return_policy_days" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('return_policy_days') }}" placeholder="7">
                            </div>
                        </div>

                        <div>
                            <label for="warranty_info" class="block text-sm font-medium text-gray-700 mb-2">Thông tin bảo hành</label>
                            <textarea name="warranty_info" id="warranty_info" rows="3" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                      placeholder="Chi tiết về chính sách bảo hành...">{{ old('warranty_info') }}</textarea>
                        </div>

                        <div>
                            <label for="return_policy" class="block text-sm font-medium text-gray-700 mb-2">Chính sách đổi trả</label>
                            <textarea name="return_policy" id="return_policy" rows="3" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                      placeholder="Chi tiết về chính sách đổi trả...">{{ old('return_policy') }}</textarea>
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
                </div>
            </div>

            {{-- Images Tab --}}
            <div id="images-tab" class="tab-content hidden">
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-images text-blue-600 mr-2"></i>
                            Hình ảnh sản phẩm
                        </h3>
                        <button type="button" id="addImageBtn" class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                            <i class="fas fa-plus mr-2"></i>
                            Thêm hình ảnh
                        </button>
                    </div>

                    <div id="images-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Images will be added here dynamically -->
                    </div>

                    <div id="no-images" class="text-center py-8 text-gray-500">
                        <i class="fas fa-images text-4xl mb-4"></i>
                        <p>Chưa có hình ảnh nào. Nhấn "Thêm hình ảnh" để bắt đầu.</p>
                    </div>
                </div>
            </div>

            {{-- SEO Tab --}}
            <div id="seo-tab" class="tab-content hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-search text-green-600 mr-2"></i>
                            SEO & Tối ưu hóa
                        </h3>
                        
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                Slug URL <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="slug" id="slug" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('slug') }}" placeholder="phu-kien-xe-hoi">
                            <p class="mt-1 text-xs text-gray-500">URL thân thiện, chỉ chứa chữ thường, số và dấu gạch ngang</p>
                        </div>

                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('meta_title') }}" placeholder="Tiêu đề hiển thị trên Google">
                            <p class="mt-1 text-xs text-gray-500">Tối đa 60 ký tự</p>
                        </div>

                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="3" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                      placeholder="Mô tả ngắn hiển thị trên kết quả tìm kiếm Google">{{ old('meta_description') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Tối đa 160 ký tự</p>
                        </div>

                        <div>
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                            <input type="text" name="meta_keywords" id="meta_keywords" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('meta_keywords') }}" placeholder="phụ kiện xe, nội thất xe, ngoại thất xe">
                            <p class="mt-1 text-xs text-gray-500">Các từ khóa cách nhau bằng dấu phẩy</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-bullhorn text-purple-600 mr-2"></i>
                            Marketing & Hiển thị
                        </h3>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Thứ tự sắp xếp</label>
                                <input type="number" name="sort_order" id="sort_order" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('sort_order', 0) }}" placeholder="0">
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" id="is_active" value="1" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    <i class="fas fa-eye text-green-500 mr-1"></i>
                                    Hiển thị công khai
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" name="is_featured" id="is_featured" value="1" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                       {{ old('is_featured') ? 'checked' : '' }}>
                                <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                    <i class="fas fa-star text-yellow-500 mr-1"></i>
                                    Sản phẩm nổi bật
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="hidden" name="is_bestseller" value="0">
                                <input type="checkbox" name="is_bestseller" id="is_bestseller" value="1" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                       {{ old('is_bestseller') ? 'checked' : '' }}>
                                <label for="is_bestseller" class="ml-2 block text-sm text-gray-900">
                                    <i class="fas fa-trophy text-orange-500 mr-1"></i>
                                    Bán chạy nhất
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="hidden" name="is_popular" value="0">
                                <input type="checkbox" name="is_popular" id="is_popular" value="1" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                       {{ old('is_popular') ? 'checked' : '' }}>
                                <label for="is_popular" class="ml-2 block text-sm text-gray-900">
                                    <i class="fas fa-fire text-red-500 mr-1"></i>
                                    Phổ biến
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="hidden" name="is_new_arrival" value="0">
                                <input type="checkbox" name="is_new_arrival" id="is_new_arrival" value="1" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                       {{ old('is_new_arrival') ? 'checked' : '' }}>
                                <label for="is_new_arrival" class="ml-2 block text-sm text-gray-900">
                                    <i class="fas fa-sparkles text-blue-500 mr-1"></i>
                                    Hàng mới về
                                </label>
                            </div>
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
                    <i class="fas fa-save mr-2"></i>
                    Lưu phụ kiện
                </button>
            </div>
        </div>
    </form>
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
    
    // Images
    document.getElementById('addImageBtn').addEventListener('click', addImage);
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
                    <select name="specifications[${specsCount}][category]" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                    <input type="text" name="specifications[${specsCount}][name]" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ví dụ: Chiều dài">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Giá trị</label>
                    <input type="text" name="specifications[${specsCount}][value]" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ví dụ: 150cm">
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', specHtml);
    noSpecs.style.display = 'none';
    updateSpecsCount();
}

function removeSpecification(id) {
    const specItem = document.querySelector(`[data-spec-id="${id}"]`);
    if (specItem) {
        specItem.remove();
        updateSpecsCount();
        
        if (document.querySelectorAll('.spec-item').length === 0) {
            document.getElementById('no-specifications').style.display = 'block';
        }
    }
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
                    <select name="features[${featuresCount}][category]" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                    <input type="text" name="features[${featuresCount}][name]" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ví dụ: Chống trượt">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phụ phí (VNĐ)</label>
                    <input type="number" name="features[${featuresCount}][price]" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Thứ tự</label>
                    <input type="number" name="features[${featuresCount}][sort_order]" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="0">
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', featureHtml);
    noFeatures.style.display = 'none';
    updateFeaturesCount();
}

function removeFeature(id) {
    const featureItem = document.querySelector(`[data-feature-id="${id}"]`);
    if (featureItem) {
        featureItem.remove();
        updateFeaturesCount();
        
        if (document.querySelectorAll('.feature-item').length === 0) {
            document.getElementById('no-features').style.display = 'block';
        }
    }
}

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
                        <input type="text" name="gallery[${imagesCount}][title]" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Tiêu đề hình ảnh">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alt text</label>
                        <input type="text" name="gallery[${imagesCount}][alt_text]" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Mô tả hình ảnh">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Loại hình ảnh</label>
                        <select name="gallery[${imagesCount}][image_type]" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="product">Sản phẩm</option>
                            <option value="detail">Chi tiết</option>
                            <option value="installation">Lắp đặt</option>
                            <option value="usage">Sử dụng</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Thứ tự</label>
                        <input type="number" name="gallery[${imagesCount}][sort_order]" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="0">
                    </div>
                </div>
                <div class="flex items-center">
                    <input type="hidden" name="gallery[${imagesCount}][is_main]" value="0">
                    <input type="checkbox" name="gallery[${imagesCount}][is_main]" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label class="ml-2 block text-sm text-gray-900">
                        <i class="fas fa-star text-yellow-500 mr-1"></i>
                        Hình ảnh chính
                    </label>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', imageHtml);
    noImages.style.display = 'none';
    updateImagesCount();
}

function removeImage(id) {
    const imageItem = document.querySelector(`[data-image-id="${id}"]`);
    if (imageItem) {
        imageItem.remove();
        updateImagesCount();
        
        if (document.querySelectorAll('.image-item').length === 0) {
            document.getElementById('no-images').style.display = 'block';
        }
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

function updateSpecsCount() {
    const count = document.querySelectorAll('.spec-item').length;
    document.getElementById('specs-count').textContent = count;
}

function updateFeaturesCount() {
    const count = document.querySelectorAll('.feature-item').length;
    document.getElementById('features-count').textContent = count;
}

function updateImagesCount() {
    const count = document.querySelectorAll('.image-item').length;
    document.getElementById('images-count').textContent = count;
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
    
    // Disable button and show loading
    if (saveBtn) {
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang lưu...';
    }
    
    // Create FormData from form
    const formData = new FormData(form);
    
    // Submit via fetch
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
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
            if (window.showMessage) {
                window.showMessage(data.message || 'Đã tạo phụ kiện thành công!', 'success');
            }
            
            // Redirect to index page
            if (data.redirect) {
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            }
        } else {
            throw { data: data };
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Handle validation errors (422)
        if (error.status === 422 && error.data && error.data.errors) {
            const errors = error.data.errors;
            // Get first error message directly
            const firstError = Object.values(errors)[0][0];
            if (window.showMessage) {
                window.showMessage(firstError, 'error');
            }
        } else if (error.data && error.data.message) {
            if (window.showMessage) {
                window.showMessage(error.data.message, 'error');
            }
        } else {
            if (window.showMessage) {
                window.showMessage(error.message || 'Có lỗi xảy ra khi tạo phụ kiện', 'error');
            }
        }
    })
    .finally(() => {
        // Re-enable button
        if (saveBtn) {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Lưu';
        }
    });
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
});
</script>
@endpush
@endsection