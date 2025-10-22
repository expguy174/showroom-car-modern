@extends('layouts.admin')

@section('title', 'Thêm dịch vụ mới')

@section('content')
{{-- Flash Messages Component --}}
<x-admin.flash-messages 
    :show-icons="true"
    :dismissible="true"
    position="top-right"
    :auto-dismiss="5000" />

@if(session('success'))
<script>
    // Auto redirect after 2 seconds
    setTimeout(function() {
        window.location.href = "{{ route('admin.services.index') }}";
    }, 2000);
</script>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-200 max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                    Thêm dịch vụ mới
                </h1>
                <p class="text-sm text-gray-600 mt-1">Tạo dịch vụ mới cho hệ thống</p>
            </div>
            <a href="{{ route('admin.services.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại
            </a>
        </div>
    </div>

    {{-- Form --}}
    <form id="serviceForm" action="{{ route('admin.services.store') }}" method="POST" class="p-6">
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
                                Tên dịch vụ <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror" 
                                   value="{{ old('name') }}" 
                                   placeholder="VD: Bảo dưỡng định kỳ 10,000 km">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                Mã dịch vụ <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="code" id="code"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('code') border-red-300 @enderror" 
                                   value="{{ old('code') }}" 
                                   placeholder="VD: BD-10K, SC-BRAKE">
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Danh mục dịch vụ <span class="text-red-500">*</span>
                            </label>
                            <select name="category" id="category" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-300 @enderror">
                                <option value="">Chọn danh mục</option>
                                <option value="maintenance" {{ old('category') == 'maintenance' ? 'selected' : '' }}>Bảo dưỡng</option>
                                <option value="repair" {{ old('category') == 'repair' ? 'selected' : '' }}>Sửa chữa</option>
                                <option value="diagnostic" {{ old('category') == 'diagnostic' ? 'selected' : '' }}>Chẩn đoán</option>
                                <option value="cosmetic" {{ old('category') == 'cosmetic' ? 'selected' : '' }}>Làm đẹp</option>
                                <option value="emergency" {{ old('category') == 'emergency' ? 'selected' : '' }}>Khẩn cấp</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                    Giá dịch vụ (VNĐ) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="price" id="price"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-300 @enderror" 
                                       value="{{ old('price') }}" 
                                       placeholder="500000">
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Thời gian (phút)
                                </label>
                                <input type="number" name="duration_minutes" id="duration_minutes"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('duration_minutes') border-red-300 @enderror" 
                                       value="{{ old('duration_minutes') }}" 
                                       placeholder="60">
                                @error('duration_minutes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Mô tả dịch vụ
                            </label>
                            <textarea name="description" id="description" rows="4" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 @enderror" 
                                      placeholder="Mô tả chi tiết về dịch vụ...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - Additional Info --}}
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-cog text-blue-600 mr-2"></i>
                        Thông tin bổ sung
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="requirements" class="block text-sm font-medium text-gray-700 mb-2">
                                Yêu cầu thực hiện
                            </label>
                            <textarea name="requirements" id="requirements" rows="4" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('requirements') border-red-300 @enderror" 
                                      placeholder="Các yêu cầu trước khi thực hiện dịch vụ...">{{ old('requirements') }}</textarea>
                            @error('requirements')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Ghi chú
                            </label>
                            <textarea name="notes" id="notes" rows="3" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-300 @enderror" 
                                      placeholder="Ghi chú thêm về dịch vụ...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                                Thứ tự sắp xếp
                            </label>
                            <input type="number" name="sort_order" id="sort_order"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sort_order') border-red-300 @enderror" 
                                   value="{{ old('sort_order', 0) }}">
                            @error('sort_order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-toggle-on text-blue-600 mr-2"></i>
                        Cài đặt
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="ml-3">
                                <span class="text-sm font-medium text-gray-700">Hoạt động</span>
                                <span class="text-xs text-gray-500 block">Cho phép khách hàng đặt lịch dịch vụ này</span>
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounde"
                                   {{ old('is_featured') ? 'checked' : '' }}>
                            <label for="is_featured" class="ml-3">
                                <span class="text-sm font-medium text-gray-700">Nổi bật</span>
                                <span class="text-xs text-gray-500 block">Hiển thị ưu tiên trên trang chủ</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.services.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-times mr-2"></i>
                Hủy
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-save mr-2"></i>
                Thêm
            </button>
        </div>
    </form>
</div>

<script>
// AJAX form submission
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('serviceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Clear previous errors
    clearErrors();
    
    // Client-side validation first
    const validationResult = validateServiceForm();
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
                window.showMessage(data.message || 'Thêm dịch vụ thành công!', 'success');
            }
            
            setTimeout(() => {
                window.location.href = data.redirect || '{{ route("admin.services.index") }}';
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
                window.showMessage(error.message || 'Có lỗi xảy ra khi thêm dịch vụ', 'error');
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
function validateServiceForm() {
    // 1. Tên dịch vụ
    const nameField = document.getElementById('name');
    if (!nameField) {
        console.error('Name field not found');
        return { isValid: true };
    }
    if (!nameField.value.trim()) {
        return {
            isValid: false,
            element: nameField,
            message: 'Vui lòng nhập tên dịch vụ.'
        };
    }
    
    if (nameField.value.trim().length < 3) {
        return {
            isValid: false,
            element: nameField,
            message: 'Tên dịch vụ phải có ít nhất 3 ký tự.'
        };
    }
    
    // 2. Mã dịch vụ
    const codeField = document.getElementById('code');
    if (!codeField) {
        console.error('Code field not found');
        return { isValid: true };
    }
    if (!codeField.value.trim()) {
        return {
            isValid: false,
            element: codeField,
            message: 'Vui lòng nhập mã dịch vụ.'
        };
    }
    
    // 3. Danh mục
    const categoryField = document.getElementById('category');
    if (!categoryField) {
        console.error('Category field not found');
        return { isValid: true };
    }
    if (!categoryField.value) {
        return {
            isValid: false,
            element: categoryField,
            message: 'Vui lòng chọn danh mục dịch vụ.'
        };
    }
    
    // 4. Giá dịch vụ (required)
    const priceField = document.getElementById('price');
    if (!priceField) {
        console.error('Price field not found');
        return { isValid: true };
    }
    if (!priceField.value.trim()) {
        return {
            isValid: false,
            element: priceField,
            message: 'Vui lòng nhập giá dịch vụ.'
        };
    }
    const price = parseFloat(priceField.value);
    if (isNaN(price) || price < 0) {
        return {
            isValid: false,
            element: priceField,
            message: 'Giá dịch vụ phải là số không âm.'
        };
    }
    
    // 5. Thời gian (optional nhưng phải hợp lệ nếu nhập)
    const durationField = document.getElementById('duration_minutes');
    if (durationField && durationField.value.trim()) {
        const duration = parseInt(durationField.value);
        if (isNaN(duration) || duration < 0) {
            return {
                isValid: false,
                element: durationField,
                message: 'Thời gian thực hiện phải là số không âm.'
            };
        }
    }
    
    // 6. Thứ tự sắp xếp (optional nhưng phải hợp lệ nếu nhập)
    const sortOrderField = document.getElementById('sort_order');
    if (sortOrderField && sortOrderField.value.trim()) {
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
        'name', 'code', 'category', 'price', 'duration_minutes',
        'description', 'requirements', 'notes', 'sort_order', 'is_active', 'is_featured'
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
        if (window.showMessage) {
            window.showMessage(firstErrorMessage, 'error');
        }
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
        'The name field is required.': 'Tên dịch vụ là bắt buộc.',
        'The name must be at least 3 characters.': 'Tên dịch vụ phải có ít nhất 3 ký tự.',
        'The code field is required.': 'Mã dịch vụ là bắt buộc.',
        'The code has already been taken.': 'Mã dịch vụ đã tồn tại.',
        'The category field is required.': 'Danh mục dịch vụ là bắt buộc.',
        'The price must be a number.': 'Giá dịch vụ phải là số.',
        'The price must be at least 0.': 'Giá dịch vụ phải lớn hơn hoặc bằng 0.',
        'The duration minutes must be a number.': 'Thời gian thực hiện phải là số.',
        'The duration minutes must be at least 0.': 'Thời gian thực hiện phải lớn hơn hoặc bằng 0.',
        'The sort order must be a number.': 'Thứ tự sắp xếp phải là số.',
        'The sort order must be at least 0.': 'Thứ tự sắp xếp phải lớn hơn hoặc bằng 0.',
        'The selected category is invalid.': 'Danh mục được chọn không hợp lệ.'
    };
    
    return translations[message] || message;
}
}); // End DOMContentLoaded
</script>
@endsection
