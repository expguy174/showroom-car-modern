@extends('layouts.admin')

@section('title', 'Thêm phiên bản xe')

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
                    Thêm phiên bản xe mới
                </h1>
                <p class="text-sm text-gray-600 mt-1">Tạo phiên bản xe mới với đầy đủ thông tin</p>
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
                Màu sắc (<span id="colors-count">0</span>)
            </button>
            <button type="button" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="specifications">
                <i class="fas fa-cogs mr-2"></i>
                Thông số kỹ thuật (<span id="specs-count">0</span>)
            </button>
            <button type="button" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="features">
                <i class="fas fa-star mr-2"></i>
                Tính năng (<span id="features-count">0</span>)
            </button>
            <button type="button" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="images">
                <i class="fas fa-images mr-2"></i>
                Hình ảnh (<span id="images-count">0</span>)
            </button>
        </nav>
    </div>
    {{-- Tab Content --}}
    <div class="p-6">
        <form id="carVariantForm" action="{{ route('admin.carvariants.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            {{-- Basic Information Tab --}}
            <div id="basic-tab" class="tab-content">
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
                                Dòng xe <span class="text-red-500">*</span>
                            </label>
                            <select name="car_model_id" id="car_model_id" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
>
                                <option value="">Chọn dòng xe...</option>
                                @foreach($carModels as $model)
                                    <option value="{{ $model->id }}" {{ old('car_model_id') == $model->id ? 'selected' : '' }}>
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
                                   value="{{ old('name') }}" placeholder="Ví dụ: 2.0 CVT, 1.5 Turbo RS...">
                        </div>

                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                                Mã SKU
                            </label>
                            <input type="text" name="sku" id="sku" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('sku') }}" placeholder="Ví dụ: HRV-20CVT-2024">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả chi tiết</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                      placeholder="Mô tả chi tiết về phiên bản xe...">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả ngắn</label>
                            <textarea name="short_description" id="short_description" rows="2" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                      placeholder="Mô tả ngắn gọn cho hiển thị danh sách...">{{ old('short_description') }}</textarea>
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
                                           value="{{ old('base_price') }}" placeholder="0">
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
                                   value="{{ old('meta_title') }}" placeholder="Tiêu đề SEO...">
                        </div>

                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="3" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                      placeholder="Mô tả SEO...">{{ old('meta_description') }}</textarea>
                        </div>

                        <div>
                            <label for="keywords" class="block text-sm font-medium text-gray-700 mb-2">Keywords</label>
                            <input type="text" name="keywords" id="keywords" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('keywords') }}" placeholder="từ khóa, phân cách, bằng dấu phẩy">
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
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Hoạt động
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="hidden" name="is_available" value="0">
                                <input type="checkbox" name="is_available" id="is_available" value="1" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                       {{ old('is_available', true) ? 'checked' : '' }}>
                                <label for="is_available" class="ml-2 block text-sm text-gray-900">
                                    Có sẵn
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" name="is_featured" id="is_featured" value="1" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                       {{ old('is_featured') ? 'checked' : '' }}>
                                <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                    Nổi bật
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

                            <div class="flex items-center">
                                <input type="hidden" name="is_bestseller" value="0">
                                <input type="checkbox" name="is_bestseller" id="is_bestseller" value="1" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                       {{ old('is_bestseller') ? 'checked' : '' }}>
                                <label for="is_bestseller" class="ml-2 block text-sm text-gray-900">
                                    Bán chạy
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Thứ tự sắp xếp</label>
                            <input type="number" name="sort_order" id="sort_order" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('sort_order', 0) }}" placeholder="0">
                        </div>
                    </div>
                </div>

            </div>
        </div>

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
                {{-- Dynamic color cards will be added here --}}
            </div>

            <div id="colorsEmptyState" class="text-center py-8 text-gray-500">
                <i class="fas fa-palette text-4xl mb-4"></i>
                <p>Chưa có màu sắc nào. Hãy thêm màu đầu tiên!</p>
            </div>
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="specificationsContainer">
                {{-- Dynamic specification cards will be added here --}}
            </div>

            <div id="specificationsEmptyState" class="text-center py-8 text-gray-500">
                <i class="fas fa-cogs text-4xl mb-4"></i>
                <p>Chưa có thông số kỹ thuật nào. Hãy thêm thông số đầu tiên!</p>
            </div>
        </div>

        {{-- Features Tab --}}
        <div id="features-tab" class="tab-content hidden">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-star text-blue-600 mr-2"></i>
                    Tính năng
                </h3>
                <button type="button" id="addFeatureBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Thêm tính năng
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="featuresContainer">
                {{-- Dynamic feature cards will be added here --}}
            </div>

            <div id="featuresEmptyState" class="text-center py-8 text-gray-500">
                <i class="fas fa-star text-4xl mb-4"></i>
                <p>Chưa có tính năng nào. Hãy thêm tính năng đầu tiên!</p>
            </div>
        </div>

        {{-- Images Tab --}}
        <div id="images-tab" class="tab-content hidden">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-images text-blue-600 mr-2"></i>
                    Hình ảnh
                </h3>
                <button type="button" id="addImageBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Thêm hình ảnh
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="imagesContainer">
                {{-- Dynamic image cards will be added here --}}
            </div>

            <div id="imagesEmptyState" class="text-center py-8 text-gray-500">
                <i class="fas fa-images text-4xl mb-4"></i>
                <p>Chưa có hình ảnh nào. Hãy thêm hình ảnh đầu tiên!</p>
            </div>
        </div>
    </div>
</form>

{{-- Action Buttons - Always Visible --}}
<div class="bg-white border-t border-gray-200 px-6 py-4 mt-8">
    <div class="flex items-center justify-between max-w-7xl mx-auto">
        <a href="{{ route('admin.carvariants.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
            <i class="fas fa-times mr-2"></i>
            Hủy bỏ
        </a>
        <button type="submit" form="carVariantForm" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
            <i class="fas fa-save mr-2"></i>
            Tạo phiên bản xe
        </button>
    </div>
</div>
</div>

{{-- Tab Switching JavaScript --}}
<script>
// Tab switching functionality
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
            const targetContent = document.getElementById(targetTab + '-tab');
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }
        });
    });
});
</script>

<script>
// AJAX Form Submission
document.getElementById('carVariantForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Sequential validation for all tabs
    const validationResult = validateAllTabs();
    if (!validationResult.isValid) {
        // Focus the field/tab with error
        if (validationResult.element) {
            validationResult.element.focus();
            validationResult.element.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        // Switch to the tab with error if needed
        if (validationResult.tabId) {
            switchToTab(validationResult.tabId);
        }
        
        // Show specific flash message
        showMessage(validationResult.message, 'error');
        return; // Stop submission
    }
    
    const form = this;
    const formData = new FormData(form);
    const submitButton = document.querySelector('button[form="carVariantForm"]');
    const originalText = submitButton ? submitButton.innerHTML : 'Tạo phiên bản xe';
    
    // Collect individual image metadata (like edit page)
    const imagesContainer = document.getElementById('imagesContainer');
    if (imagesContainer) {
        const imageCards = imagesContainer.querySelectorAll('.bg-gray-50');
        imageCards.forEach((card, index) => {
            const titleInput = card.querySelector(`input[name="individual_title_${index}"]`);
            const altInput = card.querySelector(`input[name="individual_alt_${index}"]`);
            const angleSelect = card.querySelector(`select[name="individual_angle_${index}"]`);
            const sortInput = card.querySelector(`input[name="individual_sort_${index}"]`);
            const descTextarea = card.querySelector(`textarea[name="individual_description_${index}"]`);
            const activeCheckbox = card.querySelector(`input[name="individual_is_active_${index}"]`);
            const mainCheckbox = card.querySelector(`input[name="individual_is_main_${index}"]`);
            const imageTypeSelect = card.querySelector(`select[name="individual_image_type_${index}"]`);
            const colorIdSelect = card.querySelector(`select[name="individual_color_id_${index}"]`);
            
            if (titleInput) formData.append('individual_titles[]', titleInput.value || '');
            if (altInput) formData.append('individual_alt_texts[]', altInput.value || '');
            if (angleSelect) formData.append('individual_angles[]', angleSelect.value || '');
            if (sortInput) formData.append('individual_sort_orders[]', sortInput.value || index.toString());
            if (descTextarea) formData.append('individual_descriptions[]', descTextarea.value || '');
            if (mainCheckbox) formData.append('individual_is_main[]', mainCheckbox.checked ? '1' : '0');
            if (activeCheckbox) formData.append('individual_is_active[]', activeCheckbox.checked ? '1' : '0');
            if (imageTypeSelect) formData.append('individual_image_types[]', imageTypeSelect.value || 'gallery');
            if (colorIdSelect) formData.append('individual_color_ids[]', colorIdSelect.value || '');
        });
    }
    
    // Debug: Log form data
    console.log('=== FORM DATA DEBUG ===');
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }
    console.log('=== END DEBUG ===');
    
    // Debug: Check if colors container has any inputs
    const colorsContainer = document.getElementById('colorsContainer');
    const colorInputs = colorsContainer ? colorsContainer.querySelectorAll('input, select, textarea') : [];
    console.log('Colors container exists:', !!colorsContainer);
    console.log('Number of color inputs:', colorInputs.length);
    console.log('Color inputs:', Array.from(colorInputs).map(input => input.name + '=' + input.value));
    
    // Debug: Check if specifications container has any inputs
    const specsContainer = document.getElementById('specificationsContainer');
    const specInputs = specsContainer ? specsContainer.querySelectorAll('input, select, textarea') : [];
    console.log('Specs container exists:', !!specsContainer);
    console.log('Number of spec inputs:', specInputs.length);
    console.log('Spec inputs:', Array.from(specInputs).map(input => input.name + '=' + input.value));
    
    // Show loading state
    if (submitButton) {
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang tạo...';
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
            // Show success message
            showMessage(data.message || 'Tạo phiên bản xe thành công!', 'success');
            
            // Reset button to normal state
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
            
            // Delay then redirect to index page
            setTimeout(() => {
                window.location.href = '{{ route("admin.carvariants.index") }}';
            }, 2000); // 2 second delay
            
            console.log('SUCCESS: CarVariant created successfully!');
            console.log('Response data:', data);
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
            showMessage(error.message || 'Có lỗi xảy ra khi tạo phiên bản xe. Vui lòng thử lại.', 'error');
        }
        
        // Reset button state
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        }
    });
});

// Auto-fill current_price when base_price changes
document.getElementById('base_price').addEventListener('input', function() {
    const currentPriceField = document.getElementById('current_price');
    if (!currentPriceField.value) {
        currentPriceField.value = this.value;
    }
});

// Color Management
let colorIndex = 0;

document.getElementById('addColorBtn')?.addEventListener('click', function() {
    addColorCard();
});

// Specifications Management
let specIndex = 0;

document.getElementById('addSpecBtn')?.addEventListener('click', function() {
    addSpecificationCard();
});

// Features Management
let featureIndex = 0;

document.getElementById('addFeatureBtn')?.addEventListener('click', function() {
    addFeatureCard();
});

// Images Management
let imageIndex = 0;

document.getElementById('addImageBtn')?.addEventListener('click', function() {
    addImageCard();
});

function addColorCard() {
    const container = document.getElementById('colorsContainer');
    const emptyState = document.getElementById('colorsEmptyState');
    
    const colorCard = document.createElement('div');
    colorCard.className = 'bg-gray-50 rounded-lg p-4 border border-gray-200';
    colorCard.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-medium text-gray-900">Màu mới #${colorIndex + 1}</h4>
            <button type="button" class="text-red-600 hover:text-red-800 remove-color">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </div>
        
        <div class="space-y-3">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tên màu <span class="text-red-500">*</span></label>
                    <input type="text" name="colors[${colorIndex}][color_name]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="Ví dụ: Đỏ Ruby">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Mã màu hãng</label>
                    <input type="text" name="colors[${colorIndex}][color_code]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="NH-731P">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Mã hex</label>
                    <input type="text" name="colors[${colorIndex}][hex_code]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="#FF0000">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Mã RGB</label>
                    <input type="text" name="colors[${colorIndex}][rgb_code]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="255,0,0">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Loại màu</label>
                    <select name="colors[${colorIndex}][color_type]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded">
                        <option value="solid">Màu đặc</option>
                        <option value="metallic">Màu kim loại</option>
                        <option value="pearlescent">Màu ngọc trai</option>
                        <option value="matte">Màu nhám</option>
                        <option value="special">Màu đặc biệt</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tình trạng</label>
                    <select name="colors[${colorIndex}][availability]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded">
                        <option value="standard">Tiêu chuẩn</option>
                        <option value="optional">Tùy chọn</option>
                        <option value="limited">Giới hạn</option>
                        <option value="discontinued">Ngừng sản xuất</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Phụ phí (VNĐ)</label>
                    <input type="number" name="colors[${colorIndex}][price_adjustment]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="0">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Thứ tự sắp xếp</label>
                    <input type="number" name="colors[${colorIndex}][sort_order]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="0">
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Mô tả màu</label>
                <textarea name="colors[${colorIndex}][description]" rows="2" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="Mô tả chi tiết về màu sắc..."></textarea>
            </div>
            
            <div class="grid grid-cols-3 gap-2">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tồn kho</label>
                    <input type="number" name="colors[${colorIndex}][quantity]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="0">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Đã đặt</label>
                    <input type="number" name="colors[${colorIndex}][reserved]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="0">
                </div>
                <div class="flex flex-col gap-1 pt-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="colors[${colorIndex}][is_free]" value="1" class="mr-1" checked>
                        <label class="text-xs text-gray-700">Miễn phí</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="colors[${colorIndex}][is_popular]" value="1" class="mr-1">
                        <label class="text-xs text-gray-700">Phổ biến</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="colors[${colorIndex}][is_active]" value="1" class="mr-1" checked>
                        <label class="text-xs text-gray-700">Hoạt động</label>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Add remove functionality
    colorCard.querySelector('.remove-color').addEventListener('click', function() {
        colorCard.remove();
        updateColorsCount();
        updateImageColorOptions(); // Update image color options
        
        // Show empty state if no colors left
        if (container.children.length === 0) {
            emptyState.style.display = 'block';
        }
    });
    
    // Add event listener to color name input to update image options
    const colorNameInput = colorCard.querySelector('input[name*="[color_name]"]');
    if (colorNameInput) {
        colorNameInput.addEventListener('input', function() {
            updateImageColorOptions();
        });
    }
    
    container.appendChild(colorCard);
    emptyState.style.display = 'none';
    updateColorsCount();
    
    // Add validation listeners to new color card
    addValidationListenersToElement(colorCard);
    
    colorIndex++;
}

function addSpecificationCard() {
    const container = document.getElementById('specificationsContainer');
    const emptyState = document.getElementById('specificationsEmptyState');
    
    const specCard = document.createElement('div');
    specCard.className = 'bg-gray-50 rounded-lg p-4 border border-gray-200';
    specCard.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-medium text-gray-900">Thông số #${specIndex + 1}</h4>
            <button type="button" class="text-red-600 hover:text-red-800 remove-spec">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </div>
        
        <div class="space-y-3">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Danh mục <span class="text-red-500">*</span></label>
                    <select name="specifications[${specIndex}][category]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded">
                        <option value="">Chọn danh mục...</option>
                        <option value="engine">Động cơ</option>
                        <option value="transmission">Hộp số</option>
                        <option value="performance">Hiệu suất</option>
                        <option value="dimensions">Kích thước</option>
                        <option value="weight">Trọng lượng</option>
                        <option value="fuel">Nhiên liệu</option>
                        <option value="safety">An toàn</option>
                        <option value="comfort">Tiện nghi</option>
                        <option value="exterior">Ngoại thất</option>
                        <option value="interior">Nội thất</option>
                        <option value="technology">Công nghệ</option>
                        <option value="other">Khác</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tên thông số <span class="text-red-500">*</span></label>
                    <input type="text" name="specifications[${specIndex}][spec_name]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="VD: Công suất tối đa">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Giá trị <span class="text-red-500">*</span></label>
                    <input type="text" name="specifications[${specIndex}][spec_value]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="VD: 150">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Đơn vị</label>
                    <input type="text" name="specifications[${specIndex}][unit]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="VD: hp, mm, kg">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Mã thông số</label>
                    <input type="text" name="specifications[${specIndex}][spec_code]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="Mã của hãng">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Thứ tự sắp xếp</label>
                    <input type="number" name="specifications[${specIndex}][sort_order]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="0">
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Mô tả chi tiết</label>
                <textarea name="specifications[${specIndex}][description]" rows="2" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="Mô tả thêm về thông số này..."></textarea>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="flex items-center">
                    <input type="checkbox" name="specifications[${specIndex}][is_important]" value="1" class="mr-1">
                    <label class="text-xs text-gray-700">Thông số quan trọng</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="specifications[${specIndex}][is_highlighted]" value="1" class="mr-1">
                    <label class="text-xs text-gray-700">Thông số nổi bật</label>
                </div>
            </div>
        </div>
    `;
    
    // Add remove functionality
    specCard.querySelector('.remove-spec').addEventListener('click', function() {
        specCard.remove();
        updateSpecsCount();
        
        // Show empty state if no specs left
        if (container.children.length === 0) {
            emptyState.style.display = 'block';
        }
    });
    
    container.appendChild(specCard);
    emptyState.style.display = 'none';
    updateSpecsCount();
    
    // Add validation listeners to new specification card
    addValidationListenersToElement(specCard);
    
    specIndex++;
}

function updateColorsCount() {
    const count = document.getElementById('colorsContainer').children.length;
    document.getElementById('colors-count').textContent = count;
}

function updateSpecsCount() {
    const count = document.getElementById('specificationsContainer').children.length;
    const countElement = document.getElementById('specs-count');
    if (countElement) {
        countElement.textContent = count;
    }
}

function addFeatureCard() {
    const container = document.getElementById('featuresContainer');
    const emptyState = document.getElementById('featuresEmptyState');
    
    const featureCard = document.createElement('div');
    featureCard.className = 'bg-gray-50 rounded-lg p-4 border border-gray-200';
    featureCard.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-medium text-gray-900">Tính năng #${featureIndex + 1}</h4>
            <button type="button" class="text-red-600 hover:text-red-800 remove-feature">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </div>
        
        <div class="space-y-3">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Danh mục <span class="text-red-500">*</span></label>
                    <select name="features[${featureIndex}][category]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded">
                        <option value="">Chọn danh mục...</option>
                        <option value="safety">An toàn</option>
                        <option value="comfort">Tiện nghi</option>
                        <option value="technology">Công nghệ</option>
                        <option value="performance">Hiệu suất</option>
                        <option value="exterior">Ngoại thất</option>
                        <option value="interior">Nội thất</option>
                        <option value="entertainment">Giải trí</option>
                        <option value="convenience">Tiện ích</option>
                        <option value="wheels">Bánh xe</option>
                        <option value="audio">Âm thanh</option>
                        <option value="navigation">Dẫn đường</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tên tính năng <span class="text-red-500">*</span></label>
                    <input type="text" name="features[${featureIndex}][feature_name]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="VD: ABS, ESP, Camera lùi">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tình trạng</label>
                    <select name="features[${featureIndex}][availability]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded">
                        <option value="standard">Tiêu chuẩn</option>
                        <option value="optional">Tùy chọn</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Mức độ quan trọng</label>
                    <select name="features[${featureIndex}][importance]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded">
                        <option value="essential">Thiết yếu</option>
                        <option value="important" selected>Quan trọng</option>
                        <option value="nice_to_have">Hữu ích</option>
                        <option value="luxury">Sang trọng</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Giá (VNĐ)</label>
                    <input type="number" name="features[${featureIndex}][price]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="0">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Mã tính năng</label>
                    <input type="text" name="features[${featureIndex}][feature_code]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="Mã của hãng">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Thứ tự sắp xếp</label>
                    <input type="number" name="features[${featureIndex}][sort_order]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="0">
                </div>
                <div class="flex items-center pt-6">
                    <input type="checkbox" name="features[${featureIndex}][is_included]" value="1" class="mr-1" checked>
                    <label class="text-xs text-gray-700">Bao gồm trong giá</label>
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Mô tả chi tiết</label>
                <textarea name="features[${featureIndex}][description]" rows="2" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="Mô tả chi tiết về tính năng này..."></textarea>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" name="features[${featureIndex}][is_featured]" value="1" class="mr-1">
                    <label class="text-xs text-gray-700">Nổi bật</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="features[${featureIndex}][is_popular]" value="1" class="mr-1">
                    <label class="text-xs text-gray-700">Phổ biến</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="features[${featureIndex}][is_recommended]" value="1" class="mr-1">
                    <label class="text-xs text-gray-700">Khuyến nghị</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="features[${featureIndex}][is_active]" value="1" class="mr-1" checked>
                    <label class="text-xs text-gray-700">Hoạt động</label>
                </div>
            </div>
        </div>
    `;
    
    // Add remove functionality
    featureCard.querySelector('.remove-feature').addEventListener('click', function() {
        featureCard.remove();
        updateFeaturesCount();
        
        // Show empty state if no features left
        if (container.children.length === 0) {
            emptyState.style.display = 'block';
        }
    });
    
    container.appendChild(featureCard);
    emptyState.style.display = 'none';
    updateFeaturesCount();
    
    // Add validation listeners to new feature card
    addValidationListenersToElement(featureCard);
    
    featureIndex++;
}

function updateFeaturesCount() {
    const count = document.getElementById('featuresContainer').children.length;
    const countElement = document.getElementById('features-count');
    if (countElement) {
        countElement.textContent = count;
    }
}

function addImageCard() {
    const container = document.getElementById('imagesContainer');
    const emptyState = document.getElementById('imagesEmptyState');
    
    const imageCard = document.createElement('div');
    imageCard.className = 'bg-gray-50 rounded-lg p-4 border border-gray-200';
    imageCard.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-medium text-gray-900">Hình ảnh #${imageIndex + 1}</h4>
            <button type="button" class="text-red-600 hover:text-red-800 remove-image">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </div>
        
        <div class="space-y-3">
            <!-- Image Upload -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-2">Chọn hình ảnh <span class="text-red-500">*</span></label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors cursor-pointer" onclick="document.getElementById('imageFile_${imageIndex}').click()">
                    <div class="image-upload-area">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                        <p class="text-lg font-medium text-gray-700 mb-2">Click để chọn hình ảnh</p>
                        <p class="text-sm text-gray-500">Hoặc kéo thả file vào đây</p>
                        <p class="text-xs text-gray-400 mt-2">Hỗ trợ: JPG, PNG, GIF (Tối đa 10MB mỗi file)</p>
                    </div>
                    <div class="image-preview hidden">
                        <img class="w-full h-32 object-cover rounded" alt="Preview">
                        <p class="text-xs text-green-600 mt-2">Đã chọn hình ảnh</p>
                    </div>
                </div>
                <input type="file" id="imageFile_${imageIndex}" name="images[]" class="hidden" accept="image/*" onchange="previewImage(this, ${imageIndex})">
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Loại hình ảnh</label>
                    <select name="individual_image_type_${imageIndex}" class="w-full px-2 py-1 text-sm border border-gray-300 rounded">
                        <option value="gallery">Thư viện</option>
                        <option value="exterior">Ngoại thất</option>
                        <option value="interior">Nội thất</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Góc chụp</label>
                    <select name="individual_angle_${imageIndex}" class="w-full px-2 py-1 text-sm border border-gray-300 rounded">
                        <option value="" selected>Không xác định</option>
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
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Tiêu đề 
                        <span class="text-xs text-gray-500">(tự động sinh nếu để trống)</span>
                    </label>
                    <input type="text" name="individual_title_${imageIndex}" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="Để trống để tự động sinh hoặc nhập tiêu đề tùy chỉnh">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Thứ tự sắp xếp</label>
                    <input type="number" name="individual_sort_${imageIndex}" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="0">
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    Alt text (SEO) 
                    <span class="text-xs text-gray-500">(tự động sinh nếu để trống)</span>
                </label>
                <input type="text" name="individual_alt_${imageIndex}" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="Để trống để tự động sinh hoặc nhập mô tả SEO tùy chỉnh">
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Liên kết với màu</label>
                <select name="individual_color_id_${imageIndex}" class="w-full px-2 py-1 text-sm border border-gray-300 rounded">
                    <option value="">Không liên kết</option>
                    <!-- Colors will be populated dynamically from created colors -->
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    Mô tả chi tiết 
                    <span class="text-xs text-gray-500">(tự động sinh nếu để trống)</span>
                </label>
                <textarea name="individual_description_${imageIndex}" rows="2" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" placeholder="Để trống để tự động sinh hoặc nhập mô tả tùy chỉnh"></textarea>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" name="individual_is_main_${imageIndex}" value="1" class="mr-1">
                    <label class="text-xs text-gray-700">Ảnh chính</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="individual_is_active_${imageIndex}" value="1" class="mr-1" checked>
                    <label class="text-xs text-gray-700">Hoạt động</label>
                </div>
            </div>
        </div>
    `;
    
    // Add remove functionality
    imageCard.querySelector('.remove-image').addEventListener('click', function() {
        imageCard.remove();
        updateImagesCount();
        
        // Show empty state if no images left
        if (container.children.length === 0) {
            emptyState.style.display = 'block';
        }
    });
    
    container.appendChild(imageCard);
    emptyState.style.display = 'none';
    updateImagesCount();
    updateImageColorOptions(); // Populate color options for new image card
    
    // Add validation listeners to new image card
    addValidationListenersToElement(imageCard);
    
    // Add auto-generation listeners for image metadata
    addImageMetadataListeners(imageCard);
    
    imageIndex++;
}

function previewImage(input, index) {
    const card = input.closest('.bg-gray-50');
    const uploadArea = card.querySelector('.image-upload-area');
    const previewArea = card.querySelector('.image-preview');
    const previewImg = previewArea.querySelector('img');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file size (10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('Kích thước file quá lớn! Vui lòng chọn file nhỏ hơn 10MB.');
            input.value = '';
            return;
        }
        
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Vui lòng chọn file hình ảnh!');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            uploadArea.classList.add('hidden');
            previewArea.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function updateImagesCount() {
    const count = document.getElementById('imagesContainer').children.length;
    const countElement = document.getElementById('images-count');
    if (countElement) {
        countElement.textContent = count;
    }
}

// Update color options in all image cards when colors change
function updateImageColorOptions() {
    const colorSelects = document.querySelectorAll('select[name*="individual_color_id_"]');
    const colorContainer = document.getElementById('colorsContainer');
    
    colorSelects.forEach(select => {
        // Save current value
        const currentValue = select.value;
        
        // Clear options except first one
        select.innerHTML = '<option value="">Không liên kết</option>';
        
        // Add options from created colors
        if (colorContainer) {
            const colorCards = colorContainer.querySelectorAll('.bg-gray-50');
            colorCards.forEach((card, index) => {
                const colorNameInput = card.querySelector('input[name*="[color_name]"]');
                if (colorNameInput && colorNameInput.value.trim()) {
                    const option = document.createElement('option');
                    option.value = `temp_${index}`; // Temporary ID for new colors
                    option.textContent = colorNameInput.value.trim();
                    select.appendChild(option);
                }
            });
        }
        
        // Restore value if still exists
        if (currentValue && select.querySelector(`option[value="${currentValue}"]`)) {
            select.value = currentValue;
        }
    });
}

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
    
    const colorCards = colorsContainer.querySelectorAll('.bg-gray-50');
    
    for (let i = 0; i < colorCards.length; i++) {
        const card = colorCards[i];
        const colorNameInput = card.querySelector('input[name*="[color_name]"]');
        
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
            
            // Check hex code if provided
            const hexInput = card.querySelector('input[name*="[hex_code]"]');
            if (hexInput && hexInput.value.trim()) {
                const hexPattern = /^#[0-9A-Fa-f]{6}$/;
                if (!hexPattern.test(hexInput.value.trim())) {
                    return {
                        isValid: false,
                        element: hexInput,
                        message: `Mã hex của màu thứ ${i + 1} không hợp lệ. Ví dụ: #FF0000`,
                        tabId: 'colors'
                    };
                }
            }
            
            // Check inventory numbers
            const quantityInput = card.querySelector('input[name*="[quantity]"]');
            const reservedInput = card.querySelector('input[name*="[reserved]"]');
            
            if (quantityInput && reservedInput) {
                const quantity = parseInt(quantityInput.value) || 0;
                const reserved = parseInt(reservedInput.value) || 0;
                
                if (reserved > quantity) {
                    return {
                        isValid: false,
                        element: reservedInput,
                        message: `Số lượng đặt trước của màu thứ ${i + 1} không thể lớn hơn tổng số lượng.`,
                        tabId: 'colors'
                    };
                }
            }
        }
    }
    
    return { isValid: true };
}

// Validate Specifications Tab
function validateSpecificationsTab() {
    const specsContainer = document.getElementById('specificationsContainer');
    if (!specsContainer) return { isValid: true };
    
    const specCards = specsContainer.querySelectorAll('.bg-gray-50');
    
    for (let i = 0; i < specCards.length; i++) {
        const card = specCards[i];
        const categorySelect = card.querySelector('select[name*="[category]"]');
        const specNameInput = card.querySelector('input[name*="[spec_name]"]');
        const specValueInput = card.querySelector('input[name*="[spec_value]"]');
        
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
    
    const featureCards = featuresContainer.querySelectorAll('.bg-gray-50');
    
    for (let i = 0; i < featureCards.length; i++) {
        const card = featureCards[i];
        const categorySelect = card.querySelector('select[name*="[category]"]');
        const featureNameInput = card.querySelector('input[name*="[feature_name]"]');
        
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
        
        // Check price if provided
        const priceInput = card.querySelector('input[name*="[price]"]');
        if (priceInput && priceInput.value.trim()) {
            const price = parseFloat(priceInput.value);
            if (isNaN(price) || price < 0) {
                return {
                    isValid: false,
                    element: priceInput,
                    message: `Giá tính năng thứ ${i + 1} phải là số và không được âm.`,
                    tabId: 'features'
                };
            }
        }
    }
    
    return { isValid: true };
}

// Validate Images Tab
function validateImagesTab() {
    const imagesContainer = document.getElementById('imagesContainer');
    if (!imagesContainer) return { isValid: true };
    
    const imageCards = imagesContainer.querySelectorAll('.bg-gray-50');
    
    for (let i = 0; i < imageCards.length; i++) {
        const card = imageCards[i];
        const fileInput = card.querySelector('input[type="file"]');
        const altTextInput = card.querySelector('input[name*="individual_alt_"]');
        const imageTypeSelect = card.querySelector('select[name*="individual_image_type_"]');
        
        // Check if image is selected
        if (fileInput && (!fileInput.files || fileInput.files.length === 0)) {
            return {
                isValid: false,
                element: fileInput,
                message: `Vui lòng chọn hình ảnh cho slot thứ ${i + 1}.`,
                tabId: 'images'
            };
        }
        
        // Auto-generate image metadata if empty
        const titleInput = card.querySelector('input[name*="individual_title_"]');
        const descriptionTextarea = card.querySelector('textarea[name*="individual_description_"]');
        
        // Auto-generate title if empty
        if (titleInput && !titleInput.value.trim()) {
            const autoTitle = generateAutoTitle(i + 1, card);
            titleInput.value = autoTitle;
            titleInput.classList.add('bg-yellow-50', 'border-yellow-300');
            titleInput.title = 'Tiêu đề được tự động sinh';
        }
        
        // Auto-generate alt text if empty
        if (altTextInput && !altTextInput.value.trim()) {
            const autoAltText = generateAutoAltText(i + 1, card);
            altTextInput.value = autoAltText;
            altTextInput.classList.add('bg-yellow-50', 'border-yellow-300');
            altTextInput.title = 'Alt text được tự động sinh';
        }
        
        // Auto-generate description if empty
        if (descriptionTextarea && !descriptionTextarea.value.trim()) {
            const autoDescription = generateAutoDescription(i + 1, card);
            descriptionTextarea.value = autoDescription;
            descriptionTextarea.classList.add('bg-yellow-50', 'border-yellow-300');
            descriptionTextarea.title = 'Mô tả được tự động sinh';
        }
        
        // Check image type
        if (imageTypeSelect && !imageTypeSelect.value) {
            return {
                isValid: false,
                element: imageTypeSelect,
                message: `Vui lòng chọn loại hình ảnh cho slot thứ ${i + 1}.`,
                tabId: 'images'
            };
        }
    }
    
    return { isValid: true };
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

// Add event listeners to clear flash messages when user interacts with form
function addFlashMessageClearListeners() {
    // Clear messages when user types in any input
    const allInputs = document.querySelectorAll('input, select, textarea');
    allInputs.forEach(input => {
        input.addEventListener('input', clearFlashMessages);
        input.addEventListener('change', clearFlashMessages);
        input.addEventListener('focus', clearFlashMessages);
    });
    
    // Clear messages when user clicks on tabs
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.addEventListener('click', clearFlashMessages);
    });
    
    // Clear messages when user adds new items
    const addButtons = document.querySelectorAll('[class*="add-"], .add-color, .add-spec, .add-feature, .add-image');
    addButtons.forEach(button => {
        button.addEventListener('click', clearFlashMessages);
    });
}

// Auto-generate image metadata functions
function generateAutoAltText(imageNumber, imageCard) {
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
    const angleSelect = imageCard.querySelector('select[name*="individual_angle_"]');
    const angle = angleSelect?.value || 'front';
    
    // Get linked color
    const colorSelect = imageCard.querySelector('select[name*="individual_color_id_"]');
    const colorName = colorSelect?.selectedOptions[0]?.textContent?.trim() || '';
    
    // Generate contextual alt text with proper spacing
    let altText = `${variantName} ${modelName}`.trim();
    
    // Add color if specified
    if (colorName && colorName !== 'Không liên kết') {
        altText += ` màu ${colorName}`;
    }
    
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

function generateAutoTitle(imageNumber, imageCard) {
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
    
    return `Hình ảnh ${imageNumber} - ${variantName} ${modelName}`.trim();
}

function generateAutoDescription(imageNumber, imageCard) {
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
    const imageTypeSelect = imageCard.querySelector('select[name*="individual_image_type_"]');
    const imageType = imageTypeSelect?.value || 'gallery';
    
    const typeDescriptions = {
        'gallery': 'Hình ảnh tổng quan',
        'exterior': 'Hình ảnh ngoại thất',
        'interior': 'Hình ảnh nội thất'
    };
    
    const carInfo = `${variantName} ${modelName}`.trim();
    return `${typeDescriptions[imageType] || 'Hình ảnh'} của ${carInfo}. Chất lượng cao, thể hiện đầy đủ chi tiết và đặc điểm nổi bật của xe.`.trim();
}

// Add listeners for auto-generation when related fields change
function addImageMetadataListeners(imageCard) {
    const titleInput = imageCard.querySelector('input[name*="individual_title_"]');
    const altTextInput = imageCard.querySelector('input[name*="individual_alt_"]');
    const descriptionTextarea = imageCard.querySelector('textarea[name*="individual_description_"]');
    const imageTypeSelect = imageCard.querySelector('select[name*="individual_image_type_"]');
    const angleSelect = imageCard.querySelector('select[name*="individual_angle_"]');
    const colorSelect = imageCard.querySelector('select[name*="individual_color_id_"]');
    
    // Get image number from card
    const getImageNumber = () => {
        const allCards = document.querySelectorAll('#imagesContainer .bg-gray-50');
        return Array.from(allCards).indexOf(imageCard) + 1;
    };
    
    // Function to regenerate metadata for auto-generated fields
    const regenerateMetadata = () => {
        const imageNumber = getImageNumber();
        
        // Only regenerate if field was auto-generated (has yellow background)
        if (titleInput && titleInput.classList.contains('bg-yellow-50')) {
            const newTitle = generateAutoTitle(imageNumber, imageCard);
            titleInput.value = newTitle;
        }
        
        if (altTextInput && altTextInput.classList.contains('bg-yellow-50')) {
            const newAltText = generateAutoAltText(imageNumber, imageCard);
            altTextInput.value = newAltText;
        }
        
        if (descriptionTextarea && descriptionTextarea.classList.contains('bg-yellow-50')) {
            const newDescription = generateAutoDescription(imageNumber, imageCard);
            descriptionTextarea.value = newDescription;
        }
    };
    
    // Add listeners to trigger regeneration
    if (imageTypeSelect) {
        imageTypeSelect.addEventListener('change', regenerateMetadata);
    }
    
    if (angleSelect) {
        angleSelect.addEventListener('change', regenerateMetadata);
    }
    
    if (colorSelect) {
        colorSelect.addEventListener('change', regenerateMetadata);
    }
    
    // Listen for variant name changes (global)
    const variantNameInput = document.getElementById('name');
    if (variantNameInput) {
        variantNameInput.addEventListener('input', regenerateMetadata);
    }
    
    // Listen for car model changes (global)
    const carModelSelect = document.getElementById('car_model_id');
    if (carModelSelect) {
        carModelSelect.addEventListener('change', regenerateMetadata);
    }
    
    // Allow user to manually edit and remove auto-generation
    [titleInput, altTextInput, descriptionTextarea].forEach(input => {
        if (input) {
            input.addEventListener('input', function() {
                // Remove auto-generation styling when user manually edits
                this.classList.remove('bg-yellow-50', 'border-yellow-300');
                this.title = '';
            });
        }
    });
}

// Show validation progress to user
function showValidationProgress(currentTab, totalTabs) {
    const progressMessage = `Đang kiểm tra ${currentTab}/${totalTabs} tabs...`;
    // You can show this in a progress indicator if needed
    console.log(progressMessage);
}

// Add validation listeners to dynamic elements
function addValidationListenersToElement(element) {
    const inputs = element.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        // Clear flash messages on interaction
        input.addEventListener('input', clearFlashMessages);
        input.addEventListener('change', clearFlashMessages);
        input.addEventListener('focus', clearFlashMessages);
        
        // Add specific validation for important fields
        if (input.name && (input.name.includes('color_name') || input.name.includes('spec_name') || input.name.includes('feature_name'))) {
            input.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    this.classList.add('border-red-300');
                } else {
                    this.classList.remove('border-red-300');
                }
            });
        }
        
        // Hex code validation
        if (input.name && input.name.includes('hex_code')) {
            input.addEventListener('blur', function() {
                const hexPattern = /^#[0-9A-Fa-f]{6}$/;
                if (this.value.trim() && !hexPattern.test(this.value.trim())) {
                    this.classList.add('border-red-300');
                } else {
                    this.classList.remove('border-red-300');
                }
            });
        }
        
        // Numeric validation for prices
        if (input.name && input.name.includes('price') && input.type === 'number') {
            input.addEventListener('blur', function() {
                const value = parseFloat(this.value);
                if (this.value.trim() && (isNaN(value) || value < 0)) {
                    this.classList.add('border-red-300');
                } else {
                    this.classList.remove('border-red-300');
                }
            });
        }
    });
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

// Individual field validation (for flash message approach)
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
            
        case 'sku':
            if (value && value.length > 255) {
                isValid = false;
            }
            break;
    }
    
    // Removed visual indicators - using flash messages only for clean form appearance
    
    return isValid;
}

// Individual field validation (for blur events) - no inline messages, flash messages only
function validateFieldWithMessage(fieldName, value, fieldElement) {
    let isValid = true;
    let errorMessage = '';
    
    // Removed inline error clearing - using flash messages only
    
    // Validate based on field name
    switch(fieldName) {
        case 'car_model_id':
            if (!value || value === '') {
                isValid = false;
                errorMessage = 'Vui lòng chọn dòng xe.';
            }
            break;
            
        case 'name':
            if (!value || value.trim() === '') {
                isValid = false;
                errorMessage = 'Vui lòng nhập tên phiên bản xe.';
            } else if (value.length > 255) {
                isValid = false;
                errorMessage = 'Tên phiên bản không được vượt quá 255 ký tự.';
            }
            break;
            
        case 'base_price':
            if (!value || value === '') {
                isValid = false;
                errorMessage = 'Vui lòng nhập giá gốc.';
            } else if (isNaN(value) || parseFloat(value) < 0) {
                isValid = false;
                errorMessage = 'Giá gốc phải là số và không được âm.';
            }
            break;
            
        case 'current_price':
            if (!value || value === '') {
                isValid = false;
                errorMessage = 'Vui lòng nhập giá hiện tại.';
            } else if (isNaN(value) || parseFloat(value) < 0) {
                isValid = false;
                errorMessage = 'Giá hiện tại phải là số và không được âm.';
            }
            break;
            
        case 'sku':
            if (value && value.length > 255) {
                isValid = false;
                errorMessage = 'Mã SKU không được vượt quá 255 ký tự.';
            }
            break;
    }
    
    // Removed inline error display - using flash messages only
    // Visual feedback removed to maintain clean form appearance
    
    return isValid;
}

// Add blur event listeners for real-time validation
document.addEventListener('DOMContentLoaded', function() {
    // Initialize flash message clear listeners
    addFlashMessageClearListeners();
    
    // Basic fields validation
    const fieldsToValidate = [
        { selector: '#car_model_id', name: 'car_model_id' },
        { selector: '#name', name: 'name' },
        { selector: '#base_price', name: 'base_price' },
        { selector: '#current_price', name: 'current_price' },
        { selector: '#sku', name: 'sku' }
    ];
    
    fieldsToValidate.forEach(field => {
        const element = document.querySelector(field.selector);
        if (element) {
            // Clear flash message when user starts typing/selecting
            element.addEventListener('input', function() {
                // Clear any existing flash messages
                const flashMessages = document.querySelectorAll('.flash-message');
                flashMessages.forEach(msg => msg.remove());
            });
            
            element.addEventListener('blur', function() {
                validateFieldWithMessage(field.name, this.value, this);
            });
            
            // Also validate on change for select elements
            if (element.tagName === 'SELECT') {
                element.addEventListener('change', function() {
                    // Clear flash messages when user selects something
                    const flashMessages = document.querySelectorAll('.flash-message');
                    flashMessages.forEach(msg => msg.remove());
                    
                    validateFieldWithMessage(field.name, this.value, this);
                });
            }
        }
    });
});
</script>
@endsection