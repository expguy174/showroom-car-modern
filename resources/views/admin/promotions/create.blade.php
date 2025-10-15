@extends('layouts.admin')

@section('title', 'Thêm khuyến mãi')

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
                    Thêm khuyến mãi mới
                </h1>
                <p class="text-sm text-gray-600 mt-1">Tạo chương trình khuyến mãi mới</p>
            </div>
            <a href="{{ route('admin.promotions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại
            </a>
        </div>
    </div>

    {{-- Form --}}
    <form id="promotionForm" action="{{ route('admin.promotions.store') }}" method="POST" class="p-6" novalidate>
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Left Column - Basic Info --}}
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Thông tin cơ bản
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Tên khuyến mãi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror" 
                                   value="{{ old('name') }}" placeholder="Ví dụ: Giảm giá mùa hè...">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                Mã khuyến mãi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="code" id="code" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('code') border-red-300 @enderror" 
                                   value="{{ old('code') }}" placeholder="SUMMER2024" style="text-transform: uppercase;">
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
                            <textarea name="description" id="description" rows="3" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 @enderror" 
                                      placeholder="Mô tả chi tiết về chương trình khuyến mãi...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Loại khuyến mãi <span class="text-red-500">*</span>
                            </label>
                            <select name="type" id="type" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-300 @enderror">
                                <option value="">Chọn loại khuyến mãi...</option>
                                <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Giảm theo phần trăm (%)</option>
                                <option value="fixed_amount" {{ old('type') == 'fixed_amount' ? 'selected' : '' }}>Giảm số tiền cố định</option>
                                <option value="free_shipping" {{ old('type') == 'free_shipping' ? 'selected' : '' }}>Miễn phí vận chuyển</option>
                                <option value="brand_specific" {{ old('type') == 'brand_specific' ? 'selected' : '' }}>Theo thương hiệu</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - Value & Settings --}}
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-cog text-blue-600 mr-2"></i>
                        Giá trị & Cài đặt
                    </h3>
                    
                    <div class="space-y-4">
                        <div id="value-field">
                            <label for="discount_value" class="block text-sm font-medium text-gray-700 mb-2">
                                Giá trị <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="discount_value" id="discount_value" 
                                       class="block w-full px-3 py-2 pr-12 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('discount_value') border-red-300 @enderror" 
                                       value="{{ old('discount_value') }}" placeholder="0" min="0" step="0.01">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span id="value-suffix" class="text-gray-500 sm:text-sm">%</span>
                                </div>
                            </div>
                            @error('discount_value')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="min_order_amount" class="block text-sm font-medium text-gray-700 mb-2">Đơn hàng tối thiểu</label>
                            <div class="relative">
                                <input type="number" name="min_order_amount" id="min_order_amount" 
                                       class="block w-full px-3 py-2 pr-12 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('min_order_amount') border-red-300 @enderror" 
                                       value="{{ old('min_order_amount') }}" placeholder="0" min="0" step="1000">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">VNĐ</span>
                                </div>
                            </div>
                            @error('min_order_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="max-discount-field">
                            <label for="max_discount_amount" class="block text-sm font-medium text-gray-700 mb-2">Giảm tối đa</label>
                            <div class="relative">
                                <input type="number" name="max_discount_amount" id="max_discount_amount" 
                                       class="block w-full px-3 py-2 pr-12 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('max_discount_amount') border-red-300 @enderror" 
                                       value="{{ old('max_discount_amount') }}" placeholder="Để trống = không giới hạn" min="0" step="1000">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">VNĐ</span>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Chỉ áp dụng cho loại giảm theo % và theo thương hiệu</p>
                            @error('max_discount_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Ngày bắt đầu</label>
                            <input type="datetime-local" name="start_date" id="start_date" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-300 @enderror" 
                                   value="{{ old('start_date') }}">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Ngày kết thúc</label>
                            <input type="datetime-local" name="end_date" id="end_date" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-300 @enderror" 
                                   value="{{ old('end_date') }}">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="usage_limit" class="block text-sm font-medium text-gray-700 mb-2">Giới hạn sử dụng</label>
                            <input type="number" name="usage_limit" id="usage_limit" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('usage_limit') border-red-300 @enderror" 
                                   value="{{ old('usage_limit') }}" placeholder="Để trống = không giới hạn" min="1">
                            @error('usage_limit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active" value="1" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                Hoạt động
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Action Buttons --}}
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.promotions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Hủy bỏ
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Thêm khuyến mãi
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Update value suffix based on promotion type
document.getElementById('type').addEventListener('change', function(e) {
    const valueField = document.getElementById('discount_value');
    const valueSuffix = document.getElementById('value-suffix');
    const valueContainer = document.getElementById('value-field');
    const maxDiscountField = document.getElementById('max-discount-field');
    
    switch(e.target.value) {
        case 'percentage':
            valueSuffix.textContent = '%';
            valueField.setAttribute('max', '100');
            valueField.setAttribute('step', '0.01');
            valueContainer.style.display = 'block';
            maxDiscountField.style.display = 'block';
            break;
        case 'fixed_amount':
            valueSuffix.textContent = 'VNĐ';
            valueField.removeAttribute('max');
            valueField.setAttribute('step', '1000');
            valueContainer.style.display = 'block';
            maxDiscountField.style.display = 'none';
            break;
        case 'free_shipping':
            valueContainer.style.display = 'none';
            maxDiscountField.style.display = 'none';
            break;
        case 'brand_specific':
            valueSuffix.textContent = '%';
            valueField.setAttribute('max', '100');
            valueField.setAttribute('step', '0.01');
            valueContainer.style.display = 'block';
            maxDiscountField.style.display = 'block';
            break;
        default:
            valueSuffix.textContent = '';
            valueContainer.style.display = 'block';
            maxDiscountField.style.display = 'none';
    }
});

// Auto-generate code from name
document.getElementById('name').addEventListener('input', function(e) {
    const codeField = document.getElementById('code');
    // Chỉ tự động sinh khi field code rỗng HOẶC chưa được user chỉnh sửa thủ công
    if (!codeField.dataset.userModified) {
        const code = e.target.value
            .toUpperCase()
            .replace(/[^A-Z0-9\s]/g, '') // Bỏ ký tự đặc biệt
            .replace(/\s+/g, '-') // Thay khoảng trắng bằng dấu gạch ngang
            .replace(/^-+|-+$/g, '') // Bỏ dấu gạch ngang đầu/cuối
            .substring(0, 15); // Tăng độ dài lên 15 ký tự
        codeField.value = code;
    }
});

// Đánh dấu khi user chỉnh sửa code thủ công và force uppercase
document.getElementById('code').addEventListener('input', function(e) {
    e.target.dataset.userModified = 'true';
    e.target.value = e.target.value.toUpperCase();
});

// AJAX form submission
document.getElementById('promotionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Clear previous errors
    clearErrors();
    
    // Show loading state - target the form's submit button specifically
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';
    
    // Prepare form data
    const formData = new FormData(this);
    
    // Submit via AJAX
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(errorData => {
                throw { status: response.status, data: errorData };
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Reset button to original state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            
            // Show success message using flash-messages component
            window.showMessage(data.message || 'Khuyến mãi đã được tạo thành công.', 'success');
            
            // Redirect after delay
            setTimeout(() => {
                window.location.href = data.redirect || '{{ route("admin.promotions.index") }}';
            }, 1500);
        } else {
            // Reset button on error
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        // Always reset button first
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        
        // Handle validation errors
        if (error.status === 422 && error.data && error.data.errors) {
            handleValidationErrors(error.data.errors);
        } else if (error.data && error.data.message) {
            window.showMessage(error.data.message, 'error');
        } else {
            window.showMessage('Có lỗi xảy ra khi tạo khuyến mãi.', 'error');
        }
    })
    .finally(() => {
        // Ensure button is always reset if not success
        if (submitBtn.innerHTML.includes('spinner')) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
});

// Handle validation errors
function handleValidationErrors(errors) {
    // Define field priority order (top to bottom, left to right)
    const fieldOrder = [
        'name', 'code', 'description', 'type', 'discount_value',
        'min_order_amount', 'max_discount_amount', 'usage_limit',
        'start_date', 'end_date', 'is_active'
    ];
    
    let firstErrorField = null;
    let firstErrorMessage = '';
    
    // Find first error field based on priority order
    for (const field of fieldOrder) {
        if (errors[field]) {
            const input = document.querySelector(`[name="${field}"]`);
            if (input) {
                firstErrorField = input;
                firstErrorMessage = translateError(errors[field][0]);
                break;
            }
        }
    }
    
    // Focus on first error field only
    if (firstErrorField) {
        firstErrorField.focus();
        firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Add error styling to first field only
        firstErrorField.classList.add('border-red-300');
        firstErrorField.classList.remove('border-gray-300');
        
        // Show flash message for first error
        window.showMessage(firstErrorMessage, 'error');
    }
}

// Clear all errors
function clearErrors() {
    document.querySelectorAll('.border-red-300').forEach(input => {
        input.classList.remove('border-red-300');
        input.classList.add('border-gray-300');
    });
}

// Translate error messages to Vietnamese
function translateError(message) {
    const translations = {
        'The name field is required.': 'Tên khuyến mãi là bắt buộc.',
        'The code field is required.': 'Mã khuyến mãi là bắt buộc.',
        'The code has already been taken.': 'Mã khuyến mãi đã tồn tại.',
        'The type field is required.': 'Loại khuyến mãi là bắt buộc.',
        'The discount value field is required.': 'Giá trị giảm là bắt buộc.',
        'The discount value must be a number.': 'Giá trị giảm phải là số.',
        'The discount value must be at least 0.': 'Giá trị giảm phải lớn hơn hoặc bằng 0.',
        'The discount value may not be greater than 100.': 'Giá trị giảm không được lớn hơn 100%.',
        'The min order amount must be a number.': 'Đơn hàng tối thiểu phải là số.',
        'The max discount amount must be a number.': 'Giảm tối đa phải là số.',
        'The usage limit must be a number.': 'Giới hạn sử dụng phải là số.',
        'The start date must be a valid date.': 'Ngày bắt đầu không hợp lệ.',
        'The end date must be a valid date.': 'Ngày kết thúc không hợp lệ.',
        'The end date must be after start date.': 'Ngày kết thúc phải sau ngày bắt đầu.'
    };
    
    return translations[message] || message;
}

</script>
@endsection
