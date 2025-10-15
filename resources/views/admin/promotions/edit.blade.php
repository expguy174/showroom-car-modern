@extends('layouts.admin')

@section('title', 'Chỉnh sửa khuyến mãi')

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
                    Chỉnh sửa khuyến mãi
                </h1>
                <p class="text-sm text-gray-600 mt-1">Cập nhật thông tin chương trình khuyến mãi</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.promotions.show', $promotion) }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-eye mr-2"></i>
                    Xem chi tiết
                </a>
                <a href="{{ route('admin.promotions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form id="promotionForm" action="{{ route('admin.promotions.update', $promotion) }}" method="POST" class="p-6" novalidate>
        @csrf
        @method('PUT')
        
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
                            <label for="name" class="block text-sm font-medium text-gray-700">Tên khuyến mãi</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $promotion->name) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700">Mã khuyến mãi</label>
                            <input type="text" name="code" id="code" value="{{ old('code', $promotion->code) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono" required>
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Mô tả</label>
                            <textarea name="description" id="description" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $promotion->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Loại khuyến mãi</label>
                            <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="percentage" {{ old('type', $promotion->type) == 'percentage' ? 'selected' : '' }}>Giảm theo %</option>
                                <option value="fixed_amount" {{ old('type', $promotion->type) == 'fixed_amount' ? 'selected' : '' }}>Giảm cố định</option>
                                <option value="free_shipping" {{ old('type', $promotion->type) == 'free_shipping' ? 'selected' : '' }}>Miễn phí ship</option>
                                <option value="brand_specific" {{ old('type', $promotion->type) == 'brand_specific' ? 'selected' : '' }}>Theo thương hiệu</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div id="value-field">
                            <label for="discount_value" class="block text-sm font-medium text-gray-700">Giá trị giảm</label>
                            <div class="relative">
                                <input type="number" name="discount_value" id="discount_value" step="0.01" 
                                       value="{{ old('discount_value', $promotion->discount_value) }}" 
                                       class="mt-1 block w-full pr-12 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span id="value-suffix" class="text-gray-500 sm:text-sm">
                                        @if($promotion->type == 'percentage' || $promotion->type == 'brand_specific')%
                                        @elseif($promotion->type == 'fixed_amount')VNĐ
                                        @endif
                                    </span>
                                </div>
                            </div>
                            @error('discount_value')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - Usage & Limits --}}
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-cog text-blue-600 mr-2"></i>
                        Điều kiện & Giới hạn
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="min_order_amount" class="block text-sm font-medium text-gray-700">Đơn hàng tối thiểu (VNĐ)</label>
                            <input type="number" name="min_order_amount" id="min_order_amount" step="1000" 
                                   value="{{ old('min_order_amount', $promotion->min_order_amount ? intval($promotion->min_order_amount) : '') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('min_order_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div id="max-discount-field" style="display: {{ in_array($promotion->type, ['percentage', 'brand_specific']) ? 'block' : 'none' }}">
                            <label for="max_discount_amount" class="block text-sm font-medium text-gray-700">Giảm tối đa (VNĐ)</label>
                            <input type="number" name="max_discount_amount" id="max_discount_amount" step="1000" 
                                   value="{{ old('max_discount_amount', $promotion->max_discount_amount ? intval($promotion->max_discount_amount) : '') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Để trống = không giới hạn">
                            <p class="mt-1 text-xs text-gray-500">Chỉ áp dụng cho loại giảm theo % và theo thương hiệu</p>
                            @error('max_discount_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="usage_limit" class="block text-sm font-medium text-gray-700">Giới hạn sử dụng</label>
                            <input type="number" name="usage_limit" id="usage_limit" min="1" 
                                   value="{{ old('usage_limit', $promotion->usage_limit) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('usage_limit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Ngày bắt đầu</label>
                            <input type="datetime-local" name="start_date" id="start_date" 
                                   value="{{ old('start_date', $promotion->start_date ? $promotion->start_date->format('Y-m-d\TH:i') : '') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Ngày kết thúc</label>
                            <input type="datetime-local" name="end_date" id="end_date" 
                                   value="{{ old('end_date', $promotion->end_date ? $promotion->end_date->format('Y-m-d\TH:i') : '') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <div class="flex items-center">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" id="is_active" value="1" 
                                       {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">Hoạt động</label>
                            </div>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Submit Buttons --}}
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.promotions.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-times mr-2"></i>
                    Hủy bỏ
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i>
                    Cập nhật khuyến mãi
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Update value suffix and field visibility based on promotion type
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

// Initialize field visibility on page load
document.addEventListener('DOMContentLoaded', function() {
    const currentType = document.getElementById('type').value;
    const valueContainer = document.getElementById('value-field');
    
    if (currentType === 'free_shipping') {
        valueContainer.style.display = 'none';
    }
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
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reset button to original state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            
            // Show success message using flash-messages component
            window.showMessage(data.message || 'Khuyến mãi đã được cập nhật thành công.', 'success');
            
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
        console.error('Error:', error);
        
        // Handle validation errors
        if (error.response) {
            error.response.json().then(errorData => {
                if (errorData.errors) {
                    handleValidationErrors(errorData.errors);
                } else if (errorData.data && errorData.data.message) {
                    window.showMessage(errorData.data.message, 'error');
                } else {
                    window.showMessage('Có lỗi xảy ra khi cập nhật khuyến mãi.', 'error');
                }
            });
        } else {
            window.showMessage('Có lỗi xảy ra khi cập nhật khuyến mãi.', 'error');
        }
        
        // Reset button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
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
