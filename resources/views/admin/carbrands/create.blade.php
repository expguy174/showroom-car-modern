@extends('layouts.admin')

@section('title', 'Thêm hãng xe mới')

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
                    Thêm hãng xe mới
                </h1>
                <p class="text-sm text-gray-600 mt-1">Tạo hãng xe mới trong hệ thống</p>
            </div>
            <a href="{{ route('admin.carbrands.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại
            </a>
        </div>
    </div>

    {{-- Form --}}
    <form id="carBrandForm" action="{{ route('admin.carbrands.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
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
                                Tên hãng xe <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('name') }}" placeholder="Ví dụ: Toyota, Honda...">
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Quốc gia</label>
                            <input type="text" name="country" id="country" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 " 
                                   value="{{ old('country') }}" placeholder="Ví dụ: Nhật Bản, Hàn Quốc...">
                        </div>

                        <div>
                            <label for="founded_year" class="block text-sm font-medium text-gray-700 mb-2">Năm thành lập</label>
                            <input type="number" name="founded_year" id="founded_year" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 " 
                                   value="{{ old('founded_year') }}" min="1800" max="{{ date('Y') + 1 }}" placeholder="Ví dụ: 1937">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 " 
                                      placeholder="Mô tả về hãng xe...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-address-book text-blue-600 mr-2"></i>
                        Thông tin liên hệ
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                            <input type="url" name="website" id="website" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 " 
                                   value="{{ old('website') }}" placeholder="https://example.com">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
                                <input type="tel" name="phone" id="phone" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 " 
                                       value="{{ old('phone') }}" placeholder="+84 xxx xxx xxx">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" id="email" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 " 
                                       value="{{ old('email') }}" placeholder="contact@example.com">
                            </div>
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ</label>
                            <textarea name="address" id="address" rows="3" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 " 
                                      placeholder="Địa chỉ trụ sở chính...">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- SEO Settings --}}
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-search text-blue-600 mr-2"></i>
                        Tối ưu SEO
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 " 
                                   value="{{ old('meta_title') }}" maxlength="255" placeholder="Tiêu đề trang cho SEO">
                        </div>

                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="3" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 " 
                                      maxlength="500" placeholder="Mô tả trang cho SEO">{{ old('meta_description') }}</textarea>
                        </div>

                        <div>
                            <label for="keywords" class="block text-sm font-medium text-gray-700 mb-2">Keywords</label>
                            <input type="text" name="keywords" id="keywords" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 " 
                                   value="{{ old('keywords') }}" placeholder="từ khóa, phân cách, bằng dấu phẩy">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - Settings & Logo --}}
            <div class="space-y-6">
                {{-- Logo Upload --}}
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-image text-blue-600 mr-2"></i>
                        Logo hãng xe
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="logo_path" class="block text-sm font-medium text-gray-700 mb-2">Upload logo</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="logo_path" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Chọn logo</span>
                                            <input id="logo_path" name="logo_path" type="file" class="sr-only" accept="image/*" onchange="previewLogo(this)">
                                        </label>
                                        <p class="pl-1">hoặc kéo thả vào đây</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF tối đa 2MB</p>
                                </div>
                            </div>
                            @error('logo_path')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Logo Preview --}}
                        <div id="logoPreview" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                <i class="fas fa-eye text-blue-600 mr-2"></i>
                                Xem trước logo
                            </label>
                            <div class="relative inline-block">
                                <img id="logoPreviewImage" src="" alt="Logo preview" class="w-32 h-32 object-contain border border-gray-200 rounded-lg bg-white p-2">
                                <button type="button" onclick="removeLogo()" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 shadow-lg">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Settings --}}
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-cog text-blue-600 mr-2"></i>
                        Cài đặt hiển thị
                    </h3>
                    
                    <div class="space-y-4">

                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Thứ tự sắp xếp</label>
                            <input type="number" name="sort_order" id="sort_order" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sort_order') border-red-300 @enderror" 
                                   value="{{ old('sort_order', 0) }}" min="0" placeholder="0">
                            @error('sort_order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="flex items-center">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" id="is_active" value="1" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                    Hoạt động
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" name="is_featured" id="is_featured" value="1" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                       {{ old('is_featured') ? 'checked' : '' }}>
                                <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                    <i class="fas fa-star text-yellow-500 mr-1"></i>
                                    Nổi bật
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.carbrands.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Hủy bỏ
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Lưu hãng xe
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Logo preview functionality
document.getElementById('logo_path').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Create preview if doesn't exist
            let preview = document.getElementById('logo-preview');
            if (!preview) {
                preview = document.createElement('div');
                preview.id = 'logo-preview';
                preview.className = 'mt-4';
                e.target.closest('.space-y-1').appendChild(preview);
            }
            preview.innerHTML = `
                <div class="flex items-center justify-center">
                    <img src="${e.target.result}" alt="Logo preview" class="h-20 w-20 object-contain border border-gray-200 rounded-lg">
                </div>
            `;
        };
        reader.readAsDataURL(file);
    }
});

// AJAX Form submission with flash message
document.getElementById('carBrandForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Client-side validation first
    const validationResult = validateCarBrandForm();
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
    const submitText = submitBtn.querySelector('span') || submitBtn;
    const originalText = submitText.textContent;
    
    // Show loading state
    submitBtn.disabled = true;
    submitText.textContent = 'Đang xử lý...';
    
    // Clear previous errors
    document.querySelectorAll('.error-message').forEach(el => el.remove());
    document.querySelectorAll('.border-red-300').forEach(el => {
        el.classList.remove('border-red-300');
        el.classList.add('border-gray-300');
    });
    
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
            // Show success flash message on current page
            if (window.showMessage) {
                window.showMessage(data.message || 'Thêm hãng xe thành công!', 'success');
            }
            
            // Wait 2.5 seconds then redirect
            setTimeout(() => {
                window.location.href = data.redirect || '/admin/carbrands';
            }, 2500);
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Handle validation errors (422) - Show direct error message
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
                window.showMessage(error.message || 'Có lỗi xảy ra khi thêm hãng xe', 'error');
            }
        }
    })
    .finally(() => {
        // Reset button state
        submitBtn.disabled = false;
        submitText.textContent = originalText;
    });
});

// Removed displayValidationErrors function - using flash messages only

// Client-side validation function
function validateCarBrandForm() {
    // 1. Validate required name field
    const nameField = document.getElementById('name');
    if (!nameField.value.trim()) {
        return {
            isValid: false,
            element: nameField,
            message: 'Vui lòng nhập tên hãng xe.'
        };
    }
    
    if (nameField.value.trim().length < 2) {
        return {
            isValid: false,
            element: nameField,
            message: 'Tên hãng xe phải có ít nhất 2 ký tự.'
        };
    }
    
    // 2. Validate website URL format if provided
    const websiteField = document.getElementById('website');
    if (websiteField.value.trim()) {
        const urlPattern = /^https?:\/\/.+/;
        if (!urlPattern.test(websiteField.value.trim())) {
            return {
                isValid: false,
                element: websiteField,
                message: 'Website phải bắt đầu với http:// hoặc https://'
            };
        }
    }
    
    // 3. Validate email format if provided
    const emailField = document.getElementById('email');
    if (emailField.value.trim()) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(emailField.value.trim())) {
            return {
                isValid: false,
                element: emailField,
                message: 'Vui lòng nhập địa chỉ email hợp lệ.'
            };
        }
    }
    
    // 4. Validate founded year if provided
    const foundedYearField = document.getElementById('founded_year');
    if (foundedYearField.value.trim()) {
        const year = parseInt(foundedYearField.value);
        const currentYear = new Date().getFullYear();
        if (year < 1800 || year > currentYear + 1) {
            return {
                isValid: false,
                element: foundedYearField,
                message: `Năm thành lập phải từ 1800 đến ${currentYear + 1}.`
            };
        }
    }
    
    return { isValid: true };
}

// Clean JavaScript - no toast messages needed, using flash messages instead
</script>
@endsection