@extends('layouts.admin')

@section('title', 'Thêm gói trả góp mới')

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
                    Thêm gói trả góp mới
                </h1>
                <p class="text-sm text-gray-600 mt-1">Tạo gói vay tài chính mới cho khách hàng</p>
            </div>
            <a href="{{ route('admin.finance-options.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại
            </a>
        </div>
    </div>

    {{-- Form --}}
    <form id="financeForm" action="{{ route('admin.finance-options.store') }}" method="POST" class="p-6" novalidate>
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
                                Tên gói vay <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('name') }}" placeholder="VD: Gói vay ưu đãi 0%">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                Mã gói <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="code" id="code"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('code') }}" placeholder="VD: VP-001">
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Tên ngân hàng <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="bank_name" id="bank_name"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('bank_name') }}" placeholder="VD: Vietcombank, Techcombank...">
                            @error('bank_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                      placeholder="Mô tả chi tiết về gói vay...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Loan Terms --}}
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                        Điều kiện khoản vay
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="min_tenure" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kỳ hạn tối thiểu (tháng) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="min_tenure" id="min_tenure"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('min_tenure') }}" placeholder="VD: 12">
                                @error('min_tenure')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="max_tenure" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kỳ hạn tối đa (tháng) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="max_tenure" id="max_tenure"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('max_tenure') }}" placeholder="VD: 72">
                                @error('max_tenure')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="min_down_payment" class="block text-sm font-medium text-gray-700 mb-2">
                                Trả trước tối thiểu (%) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" name="min_down_payment" id="min_down_payment"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('min_down_payment') }}" placeholder="VD: 20">
                            @error('min_down_payment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="requirements" class="block text-sm font-medium text-gray-700 mb-2">Yêu cầu hồ sơ</label>
                            <textarea name="requirements" id="requirements" rows="3" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                      placeholder="VD: CMND/CCCD, Hộ khẩu, Sổ hộ khẩu...">{{ old('requirements') }}</textarea>
                            @error('requirements')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - Rates & Limits --}}
            <div class="space-y-6">
                {{-- Interest & Fees --}}
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-percent text-blue-600 mr-2"></i>
                        Lãi suất & Phí
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="interest_rate" class="block text-sm font-medium text-gray-700 mb-2">
                                Lãi suất (% / năm) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" name="interest_rate" id="interest_rate"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('interest_rate') }}" placeholder="VD: 9.5">
                            @error('interest_rate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="processing_fee" class="block text-sm font-medium text-gray-700 mb-2">Phí xử lý hồ sơ (đ)</label>
                            <input type="number" name="processing_fee" id="processing_fee"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('processing_fee') }}" placeholder="VD: 500000">
                            @error('processing_fee')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Loan Amount Limits --}}
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-money-bill-wave text-blue-600 mr-2"></i>
                        Hạn mức vay
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="min_loan_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Số tiền vay tối thiểu (đ) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="min_loan_amount" id="min_loan_amount"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('min_loan_amount') }}" placeholder="VD: 10000000">
                            @error('min_loan_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">VD: 50,000,000 đ = 50 triệu</p>
                        </div>

                        <div>
                            <label for="max_loan_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Số tiền vay tối đa (đ) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="max_loan_amount" id="max_loan_amount"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('max_loan_amount') }}" placeholder="VD: 2000000000">
                            @error('max_loan_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">VD: 2,000,000,000 đ = 2 tỷ</p>
                        </div>
                    </div>
                </div>

                {{-- Settings --}}
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-cog text-blue-600 mr-2"></i>
                        Cài đặt
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Thứ tự sắp xếp</label>
                            <input type="number" name="sort_order" id="sort_order" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('sort_order') }}" placeholder="VD: 0">
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
                <a href="{{ route('admin.finance-options.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Hủy bỏ
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Lưu gói vay
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// AJAX Form submission
document.getElementById('financeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Client-side validation first (but don't block spinner)
    const validationResult = validateFinanceForm();
    if (!validationResult.isValid) {
        // Focus the field with error
        if (validationResult.element) {
            validationResult.element.focus();
            validationResult.element.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        // Show specific flash message
        if (window.showMessage) {
            window.showMessage(validationResult.message, 'error');
        }
        return; // Stop submission
    }
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
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
            // Reset button to original state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            
            if (window.showMessage) {
                window.showMessage(data.message || 'Thêm gói vay thành công!', 'success');
            }
            
            setTimeout(() => {
                window.location.href = data.redirect || '{{ route("admin.finance-options.index") }}';
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
        
        // Handle validation errors like promotions
        if (error.status === 422 && error.data && error.data.errors) {
            // Focus first error field and show flash message
            const errors = error.data.errors;
            const firstFieldName = Object.keys(errors)[0];
            const firstErrorMessage = errors[firstFieldName][0];
            
            // Find and focus first error field
            const firstErrorField = document.querySelector(`[name="${firstFieldName}"]`);
            if (firstErrorField) {
                firstErrorField.focus();
                firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstErrorField.classList.add('border-red-300');
                firstErrorField.classList.remove('border-gray-300');
            }
            
            if (window.showMessage) {
                window.showMessage(translateError(firstErrorMessage), 'error');
            }
        } else if (error.data && error.data.message) {
            if (window.showMessage) {
                window.showMessage(error.data.message, 'error');
            }
        } else {
            if (window.showMessage) {
                window.showMessage(error.message || 'Có lỗi xảy ra khi thêm gói vay', 'error');
            }
        }
    });
});

// Translate error messages to Vietnamese
function translateError(message) {
    const translations = {
        'The name field is required.': 'Tên gói vay là bắt buộc.',
        'The code field is required.': 'Mã gói vay là bắt buộc.',
        'The bank name field is required.': 'Tên ngân hàng là bắt buộc.',
        'The interest rate field is required.': 'Lãi suất là bắt buộc.',
        'The min down payment field is required.': 'Trả trước tối thiểu là bắt buộc.',
        'The min tenure field is required.': 'Kỳ hạn tối thiểu là bắt buộc.',
        'The max tenure field is required.': 'Kỳ hạn tối đa là bắt buộc.',
        'The min loan amount field is required.': 'Số tiền vay tối thiểu là bắt buộc.',
        'The max loan amount field is required.': 'Số tiền vay tối đa là bắt buộc.',
        'The interest rate must be a number.': 'Lãi suất phải là số.',
        'The min down payment must be a number.': 'Trả trước tối thiểu phải là số.',
        'The min tenure must be an integer.': 'Kỳ hạn tối thiểu phải là số nguyên.',
        'The max tenure must be an integer.': 'Kỳ hạn tối đa phải là số nguyên.',
        'The min loan amount must be an integer.': 'Số tiền vay tối thiểu phải là số nguyên.',
        'The max loan amount must be an integer.': 'Số tiền vay tối đa phải là số nguyên.',
        'The processing fee must be an integer.': 'Phí xử lý hồ sơ phải là số nguyên.',
        'The sort order must be an integer.': 'Thứ tự sắp xếp phải là số nguyên.',
        'The interest rate must be at least 0.': 'Lãi suất phải lớn hơn hoặc bằng 0.',
        'The min down payment must be at least 0.': 'Trả trước tối thiểu phải lớn hơn hoặc bằng 0.',
        'The min tenure must be at least 1.': 'Kỳ hạn tối thiểu phải ít nhất 1 tháng.',
        'The max tenure must be at least 1.': 'Kỳ hạn tối đa phải ít nhất 1 tháng.',
        'The min loan amount must be at least 0.': 'Số tiền vay tối thiểu phải lớn hơn hoặc bằng 0.',
        'The max loan amount must be at least 0.': 'Số tiền vay tối đa phải lớn hơn hoặc bằng 0.',
        'The code has already been taken.': 'Mã gói vay đã tồn tại.',
        'The name has already been taken.': 'Tên gói vay đã tồn tại.'
    };
    
    return translations[message] || message;
}

// Client-side validation function - Hợp lý, logic và việt hóa
function validateFinanceForm() {
    // ===== PHẦN 1: THÔNG TIN CƠ BẢN =====
    
    // 1. Tên gói vay
    const nameField = document.getElementById('name');
    if (!nameField.value.trim()) {
        return {
            isValid: false,
            element: nameField,
            message: 'Vui lòng nhập tên gói vay.'
        };
    }
    
    if (nameField.value.trim().length < 3) {
        return {
            isValid: false,
            element: nameField,
            message: 'Tên gói vay phải có ít nhất 3 ký tự.'
        };
    }
    
    if (nameField.value.trim().length > 255) {
        return {
            isValid: false,
            element: nameField,
            message: 'Tên gói vay không được vượt quá 255 ký tự.'
        };
    }
    
    // 2. Mã gói vay
    const codeField = document.getElementById('code');
    if (!codeField.value.trim()) {
        return {
            isValid: false,
            element: codeField,
            message: 'Vui lòng nhập mã gói vay.'
        };
    }
    
    if (codeField.value.trim().length > 255) {
        return {
            isValid: false,
            element: codeField,
            message: 'Mã gói vay không được vượt quá 255 ký tự.'
        };
    }
    
    // 3. Tên ngân hàng
    const bankNameField = document.getElementById('bank_name');
    if (!bankNameField.value.trim()) {
        return {
            isValid: false,
            element: bankNameField,
            message: 'Vui lòng nhập tên ngân hàng.'
        };
    }
    
    if (bankNameField.value.trim().length > 255) {
        return {
            isValid: false,
            element: bankNameField,
            message: 'Tên ngân hàng không được vượt quá 255 ký tự.'
        };
    }
    
    // ===== PHẦN 2: KỲ HẠN VAY =====
    
    // 4. Kỳ hạn tối thiểu
    const minTenureField = document.getElementById('min_tenure');
    if (!minTenureField.value.trim()) {
        return {
            isValid: false,
            element: minTenureField,
            message: 'Vui lòng nhập kỳ hạn tối thiểu.'
        };
    }
    
    const minTenure = parseInt(minTenureField.value);
    if (isNaN(minTenure)) {
        return {
            isValid: false,
            element: minTenureField,
            message: 'Kỳ hạn tối thiểu phải là số nguyên.'
        };
    }
    
    if (minTenure < 1) {
        return {
            isValid: false,
            element: minTenureField,
            message: 'Kỳ hạn tối thiểu phải ít nhất 1 tháng.'
        };
    }
    
    if (minTenure > 360) {
        return {
            isValid: false,
            element: minTenureField,
            message: 'Kỳ hạn tối thiểu không được vượt quá 360 tháng (30 năm).'
        };
    }
    
    // 5. Kỳ hạn tối đa
    const maxTenureField = document.getElementById('max_tenure');
    if (!maxTenureField.value.trim()) {
        return {
            isValid: false,
            element: maxTenureField,
            message: 'Vui lòng nhập kỳ hạn tối đa.'
        };
    }
    
    const maxTenure = parseInt(maxTenureField.value);
    if (isNaN(maxTenure)) {
        return {
            isValid: false,
            element: maxTenureField,
            message: 'Kỳ hạn tối đa phải là số nguyên.'
        };
    }
    
    if (maxTenure < 1) {
        return {
            isValid: false,
            element: maxTenureField,
            message: 'Kỳ hạn tối đa phải ít nhất 1 tháng.'
        };
    }
    
    if (maxTenure > 360) {
        return {
            isValid: false,
            element: maxTenureField,
            message: 'Kỳ hạn tối đa không được vượt quá 360 tháng (30 năm).'
        };
    }
    
    if (maxTenure < minTenure) {
        return {
            isValid: false,
            element: maxTenureField,
            message: 'Kỳ hạn tối đa (' + maxTenure + ' tháng) phải lớn hơn hoặc bằng kỳ hạn tối thiểu (' + minTenure + ' tháng).'
        };
    }
    
    // ===== PHẦN 3: TỶ LỆ TRẢ TRƯỚC =====
    
    // 6. Tỷ lệ trả trước tối thiểu
    const minDownPaymentField = document.getElementById('min_down_payment');
    if (!minDownPaymentField.value.trim()) {
        return {
            isValid: false,
            element: minDownPaymentField,
            message: 'Vui lòng nhập tỷ lệ trả trước tối thiểu.'
        };
    }
    
    const minDownPayment = parseFloat(minDownPaymentField.value);
    if (isNaN(minDownPayment)) {
        return {
            isValid: false,
            element: minDownPaymentField,
            message: 'Tỷ lệ trả trước tối thiểu phải là số.'
        };
    }
    
    if (minDownPayment < 0) {
        return {
            isValid: false,
            element: minDownPaymentField,
            message: 'Tỷ lệ trả trước tối thiểu không được âm.'
        };
    }
    
    if (minDownPayment > 100) {
        return {
            isValid: false,
            element: minDownPaymentField,
            message: 'Tỷ lệ trả trước tối thiểu không được vượt quá 100%.'
        };
    }
    
    // ===== PHẦN 4: LÃI SUẤT =====
    
    // 7. Lãi suất
    const interestRateField = document.getElementById('interest_rate');
    if (!interestRateField.value.trim()) {
        return {
            isValid: false,
            element: interestRateField,
            message: 'Vui lòng nhập lãi suất.'
        };
    }
    
    const interestRate = parseFloat(interestRateField.value);
    if (isNaN(interestRate)) {
        return {
            isValid: false,
            element: interestRateField,
            message: 'Lãi suất phải là số.'
        };
    }
    
    if (interestRate < 0) {
        return {
            isValid: false,
            element: interestRateField,
            message: 'Lãi suất không được âm.'
        };
    }
    
    if (interestRate > 100) {
        return {
            isValid: false,
            element: interestRateField,
            message: 'Lãi suất không được vượt quá 100%/năm.'
        };
    }
    
    // ===== PHẦN 5: PHÍ XỬ LÝ (OPTIONAL) =====
    
    // 8. Phí xử lý hồ sơ (optional nhưng phải hợp lệ nếu nhập)
    const processingFeeField = document.getElementById('processing_fee');
    if (processingFeeField.value.trim()) {
        const processingFee = parseFloat(processingFeeField.value);
        if (isNaN(processingFee)) {
            return {
                isValid: false,
                element: processingFeeField,
                message: 'Phí xử lý hồ sơ phải là số.'
            };
        }
        
        if (processingFee < 0) {
            return {
                isValid: false,
                element: processingFeeField,
                message: 'Phí xử lý hồ sơ không được âm.'
            };
        }
    }
    
    // ===== PHẦN 6: HẠN MỨC VAY =====
    
    // 9. Số tiền vay tối thiểu
    const minLoanField = document.getElementById('min_loan_amount');
    if (!minLoanField.value.trim()) {
        return {
            isValid: false,
            element: minLoanField,
            message: 'Vui lòng nhập số tiền vay tối thiểu.'
        };
    }
    
    const minLoan = parseFloat(minLoanField.value);
    if (isNaN(minLoan)) {
        return {
            isValid: false,
            element: minLoanField,
            message: 'Số tiền vay tối thiểu phải là số.'
        };
    }
    
    if (minLoan < 1000000) {
        return {
            isValid: false,
            element: minLoanField,
            message: 'Số tiền vay tối thiểu phải từ 1.000.000 đ (1 triệu) trở lên.'
        };
    }
    
    // 10. Số tiền vay tối đa
    const maxLoanField = document.getElementById('max_loan_amount');
    if (!maxLoanField.value.trim()) {
        return {
            isValid: false,
            element: maxLoanField,
            message: 'Vui lòng nhập số tiền vay tối đa.'
        };
    }
    
    const maxLoan = parseFloat(maxLoanField.value);
    if (isNaN(maxLoan)) {
        return {
            isValid: false,
            element: maxLoanField,
            message: 'Số tiền vay tối đa phải là số.'
        };
    }
    
    if (maxLoan < 1000000) {
        return {
            isValid: false,
            element: maxLoanField,
            message: 'Số tiền vay tối đa phải từ 1.000.000 đ (1 triệu) trở lên.'
        };
    }
    
    if (maxLoan < minLoan) {
        const minLoanFormatted = minLoan.toLocaleString('vi-VN');
        const maxLoanFormatted = maxLoan.toLocaleString('vi-VN');
        return {
            isValid: false,
            element: maxLoanField,
            message: 'Số tiền vay tối đa (' + maxLoanFormatted + ' đ) phải lớn hơn hoặc bằng số tiền vay tối thiểu (' + minLoanFormatted + ' đ).'
        };
    }
    
    // ===== PHẦN 7: SORT ORDER (OPTIONAL) =====
    
    // 11. Thứ tự sắp xếp (optional nhưng phải hợp lệ nếu nhập)
    const sortOrderField = document.getElementById('sort_order');
    if (sortOrderField.value.trim()) {
        const sortOrder = parseInt(sortOrderField.value);
        if (isNaN(sortOrder)) {
            return {
                isValid: false,
                element: sortOrderField,
                message: 'Thứ tự sắp xếp phải là số nguyên.'
            };
        }
        
        if (sortOrder < 0) {
            return {
                isValid: false,
                element: sortOrderField,
                message: 'Thứ tự sắp xếp không được âm.'
            };
        }
    }
    
    // ===== VALIDATION THÀNH CÔNG =====
    return { isValid: true };
}
</script>
@endsection
