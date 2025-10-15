@extends('layouts.admin')

@section('title', 'Thêm phương thức thanh toán')

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
                    Thêm phương thức thanh toán mới
                </h1>
                <p class="text-sm text-gray-600 mt-1">Tạo phương thức thanh toán mới cho khách hàng</p>
            </div>
            <a href="{{ route('admin.payment-methods.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại
            </a>
        </div>
    </div>

    {{-- Form --}}
    <form id="paymentMethodForm" action="{{ route('admin.payment-methods.store') }}" method="POST" class="p-6" novalidate>
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
                                Tên phương thức <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror" 
                                   value="{{ old('name') }}" placeholder="VD: Thẻ tín dụng Visa">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="provider" class="block text-sm font-medium text-gray-700 mb-2">
                                Nhà cung cấp
                            </label>
                            <input type="text" name="provider" id="provider"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('provider') border-red-300 @enderror" 
                                   value="{{ old('provider') }}" placeholder="VD: Visa, MasterCard, VNPay">
                            @error('provider')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                Mã phương thức <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="code" id="code"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('code') border-red-300 @enderror" 
                                   value="{{ old('code') }}" placeholder="VD: cash, vnpay, momo">
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Loại phương thức <span class="text-red-500">*</span>
                            </label>
                            <select name="type" id="type" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-300 @enderror">
                                <option value="">Chọn loại phương thức</option>
                                <option value="online" {{ old('type') == 'online' ? 'selected' : '' }}>Trực tuyến</option>
                                <option value="offline" {{ old('type') == 'offline' ? 'selected' : '' }}>Trực tiếp</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                            <textarea name="notes" id="notes" rows="3" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-300 @enderror" 
                                      placeholder="Ghi chú về phương thức thanh toán...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - Settings --}}
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-cog text-blue-600 mr-2"></i>
                        Cài đặt
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="fee_flat" class="block text-sm font-medium text-gray-700 mb-2">
                                Phí cố định (VNĐ)
                            </label>
                            <input type="number" name="fee_flat" id="fee_flat" step="1000" min="0"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fee_flat') border-red-300 @enderror" 
                                   value="{{ old('fee_flat', 0) }}" placeholder="0">
                            <p class="mt-1 text-xs text-gray-500">Phí cố định tính bằng VNĐ (0 = miễn phí)</p>
                            @error('fee_flat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="fee_percent" class="block text-sm font-medium text-gray-700 mb-2">
                                Phí theo tỷ lệ (%)
                            </label>
                            <input type="number" name="fee_percent" id="fee_percent" step="0.01" min="0" max="100"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fee_percent') border-red-300 @enderror" 
                                   value="{{ old('fee_percent', 0) }}" placeholder="0.00">
                            <p class="mt-1 text-xs text-gray-500">Phí tính theo % giá trị giao dịch (0 = miễn phí)</p>
                            @error('fee_percent')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cấu hình phương thức</label>
                            <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-xs text-blue-700">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Tùy chọn: Thêm cấu hình cho phương thức thanh toán (VD: API key, endpoint, merchant ID...)
                                </p>
                            </div>
                            <div id="config-container" class="space-y-3">
                                <!-- Bắt đầu với container trống -->
                            </div>
                            <button type="button" id="add-config-row" class="mt-2 inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-plus mr-1"></i>
                                Thêm cấu hình
                            </button>
                            <input type="hidden" name="config" id="config">
                            <p class="mt-1 text-xs text-gray-500">Ví dụ: api_key → your_key, endpoint → https://api.example.com</p>
                            @error('config')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Thứ tự sắp xếp</label>
                            <input type="number" name="sort_order" id="sort_order" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sort_order') border-red-300 @enderror" 
                                   value="{{ old('sort_order', 0) }}" placeholder="0">
                            @error('sort_order')
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
                <a href="{{ route('admin.payment-methods.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Hủy bỏ
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Thêm phương thức
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// AJAX form submission
document.getElementById('paymentMethodForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Clear previous errors
    clearErrors();
    
    // Client-side validation first
    const validationResult = validatePaymentMethodForm();
    if (!validationResult.isValid) {
        // Focus the field with error
        if (validationResult.element) {
            validationResult.element.focus();
            validationResult.element.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Add error styling
            validationResult.element.classList.add('border-red-300');
            validationResult.element.classList.remove('border-gray-300');
        }
        
        // Show specific flash message
        if (window.showMessage) {
            window.showMessage(validationResult.message, 'error');
        }
        return; // Stop submission
    }
    
    // Show loading state - target the form's submit button specifically
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';
    
    // Prepare form data
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Server response:', text);
                try {
                    const data = JSON.parse(text);
                    throw { status: response.status, data: data };
                } catch (e) {
                    throw { status: response.status, data: { message: 'Server error: ' + text.substring(0, 100) } };
                }
            });
        }
        return response.text().then(text => {
            console.log('Success response:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Invalid JSON response:', text);
                throw { status: 200, data: { message: 'Invalid JSON response from server' } };
            }
        });
    })
    .then(data => {
        if (data.success) {
            // Reset button to original state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            
            if (window.showMessage) {
                window.showMessage(data.message || 'Thêm phương thức thanh toán thành công!', 'success');
            }
            
            setTimeout(() => {
                window.location.href = data.redirect || '{{ route("admin.payment-methods.index") }}';
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
            if (window.showMessage) {
                window.showMessage(error.data.message, 'error');
            }
        } else {
            if (window.showMessage) {
                window.showMessage(error.message || 'Có lỗi xảy ra khi thêm phương thức thanh toán', 'error');
            }
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

// Client-side validation function
function validatePaymentMethodForm() {
    // 1. Tên phương thức
    const nameField = document.getElementById('name');
    if (!nameField) {
        console.error('Name field not found');
        return { isValid: true }; // Skip validation if field not found
    }
    if (!nameField.value.trim()) {
        return {
            isValid: false,
            element: nameField,
            message: 'Vui lòng nhập tên phương thức thanh toán.'
        };
    }
    
    if (nameField.value.trim().length < 3) {
        return {
            isValid: false,
            element: nameField,
            message: 'Tên phương thức phải có ít nhất 3 ký tự.'
        };
    }
    
    // 2. Mã phương thức
    const codeField = document.getElementById('code');
    if (!codeField) {
        console.error('Code field not found');
        return { isValid: true };
    }
    if (!codeField.value.trim()) {
        return {
            isValid: false,
            element: codeField,
            message: 'Vui lòng nhập mã phương thức.'
        };
    }
    
    // 3. Loại phương thức
    const typeField = document.getElementById('type');
    if (!typeField) {
        console.error('Type field not found');
        return { isValid: true };
    }
    if (!typeField.value) {
        return {
            isValid: false,
            element: typeField,
            message: 'Vui lòng chọn loại phương thức.'
        };
    }
    
    // 4. Phí cố định (optional nhưng phải hợp lệ nếu nhập)
    const feeFlatField = document.getElementById('fee_flat');
    if (feeFlatField.value.trim()) {
        const feeFlat = parseFloat(feeFlatField.value);
        if (isNaN(feeFlat) || feeFlat < 0) {
            return {
                isValid: false,
                element: feeFlatField,
                message: 'Phí cố định phải là số không âm.'
            };
        }
    }
    
    // 5. Phí theo tỷ lệ (optional nhưng phải hợp lệ nếu nhập)
    const feePercentField = document.getElementById('fee_percent');
    if (feePercentField.value.trim()) {
        const feePercent = parseFloat(feePercentField.value);
        if (isNaN(feePercent) || feePercent < 0 || feePercent > 100) {
            return {
                isValid: false,
                element: feePercentField,
                message: 'Phí theo tỷ lệ phải từ 0% đến 100%.'
            };
        }
    }
    
    // 6. Cấu hình được handle tự động bởi key-value inputs
    
    // 7. Thứ tự sắp xếp (optional nhưng phải hợp lệ nếu nhập)
    const sortOrderField = document.getElementById('sort_order');
    if (sortOrderField.value.trim()) {
        const sortOrder = parseInt(sortOrderField.value);
        if (isNaN(sortOrder) || sortOrder < 0) {
            return {
                isValid: false,
                element: sortOrderField,
                message: 'Thứ tự sắp xếp phải là số không âm.'
            };
        }
    }
    
    return { isValid: true };
}

// Handle validation errors
function handleValidationErrors(errors) {
    // Define field priority order (top to bottom, left to right)
    const fieldOrder = [
        'name', 'code', 'provider', 'type', 'fee_flat', 'fee_percent',
        'config', 'notes', 'sort_order', 'is_active'
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
        'The name field is required.': 'Tên phương thức thanh toán là bắt buộc.',
        'The code field is required.': 'Mã phương thức là bắt buộc.',
        'The code has already been taken.': 'Mã phương thức đã tồn tại.',
        'The type field is required.': 'Loại phương thức là bắt buộc.',
        'The fee flat must be a number.': 'Phí cố định phải là số.',
        'The fee flat must be at least 0.': 'Phí cố định phải lớn hơn hoặc bằng 0.',
        'The fee percent must be a number.': 'Phí theo tỷ lệ phải là số.',
        'The fee percent must be at least 0.': 'Phí theo tỷ lệ phải lớn hơn hoặc bằng 0.',
        'The fee percent may not be greater than 100.': 'Phí theo tỷ lệ không được lớn hơn 100%.',
        'The config must be valid JSON.': 'Cấu hình JSON không hợp lệ.',
        'The sort order must be a number.': 'Thứ tự sắp xếp phải là số.',
        'The sort order must be at least 0.': 'Thứ tự sắp xếp phải lớn hơn hoặc bằng 0.'
    };
    
    return translations[message] || message;
}

// Config management
document.addEventListener('DOMContentLoaded', function() {
    const configContainer = document.getElementById('config-container');
    const addConfigBtn = document.getElementById('add-config-row');
    const configHiddenInput = document.getElementById('config');

    // Add new config row
    addConfigBtn.addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.className = 'config-row flex items-center gap-2';
        newRow.innerHTML = `
            <input type="text" placeholder="Nhập key (VD: api_key)" 
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 config-key">
            <span class="text-gray-400 text-sm">→</span>
            <input type="text" placeholder="Nhập value (VD: your_key_here)" 
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 config-value">
            <button type="button" class="ml-2 w-8 h-8 flex items-center justify-center text-red-500 hover:text-red-700 hover:bg-red-50 rounded-full transition-colors remove-config-row" title="Xóa cấu hình này">
                <i class="fas fa-times text-sm"></i>
            </button>
        `;
        configContainer.appendChild(newRow);
        updateConfigJson();
        
        // Focus vào key input của row mới
        newRow.querySelector('.config-key').focus();
    });

    // Remove config row
    configContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-config-row')) {
            const row = e.target.closest('.config-row');
            // Luôn cho phép xóa, không giới hạn số lượng tối thiểu
            row.remove();
            updateConfigJson();
        }
    });

    // Update config when inputs change
    configContainer.addEventListener('input', updateConfigJson);

    function updateConfigJson() {
        const config = {};
        const rows = configContainer.querySelectorAll('.config-row');
        
        rows.forEach(row => {
            const key = row.querySelector('.config-key').value.trim();
            const value = row.querySelector('.config-value').value.trim();
            
            if (key && value) {
                config[key] = value;
            }
        });
        
        configHiddenInput.value = Object.keys(config).length > 0 ? JSON.stringify(config) : '';
    }

    // Initialize
    updateConfigJson();
});
</script>
@endsection
