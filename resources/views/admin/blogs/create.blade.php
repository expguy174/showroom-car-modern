@extends('layouts.admin')

@section('title', 'Tạo bài viết')

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
        window.location.href = "{{ route('admin.blogs.index') }}";
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
                    Tạo bài viết mới
                </h1>
                <p class="text-sm text-gray-600 mt-1">Viết bài viết mới cho blog</p>
            </div>
            <a href="{{ route('admin.blogs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại
            </a>
        </div>
    </div>

    {{-- Form --}}
    <form id="blogForm" action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
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
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Tiêu đề bài viết <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-300 @enderror" 
                                   value="{{ old('title') }}" 
                                   placeholder="Nhập tiêu đề bài viết...">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                Nội dung bài viết <span class="text-red-500">*</span>
                            </label>
                            <textarea name="content" id="content" rows="8" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-300 @enderror" 
                                      placeholder="Nhập nội dung bài viết...">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - Media & Settings --}}
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-image text-blue-600 mr-2"></i>
                        Hình ảnh
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="image_path" class="block text-sm font-medium text-gray-700 mb-2">
                                Hình ảnh bài viết
                            </label>
                            
                            {{-- Image Preview --}}
                            <div id="imagePreview" class="hidden mb-4">
                                <div class="relative">
                                    <img id="previewImg" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg border border-gray-200">
                                    <div class="absolute top-2 right-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-upload mr-1"></i>Mới upload
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Upload Area --}}
                            <div id="uploadArea" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image_path" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span id="uploadText">Upload hình ảnh</span>
                                            <input id="image_path" name="image_path" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">hoặc kéo thả vào đây</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF tối đa 2MB</p>
                                </div>
                            </div>
                            @error('image_path')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                        </div>

                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-cog text-blue-600 mr-2"></i>
                        Cài đặt
                    </h3>
                    
                    <div class="space-y-4">
                                <div class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label class="ml-3 text-sm text-gray-700">
                                <span class="font-medium">Hiển thị</span>
                                <p class="text-xs text-gray-500">Bài viết sẽ hiển thị trên website</p>
                                    </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="is_featured" 
                                   value="1"
                                   {{ old('is_featured', false) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label class="ml-3 text-sm text-gray-700">
                                <span class="font-medium">Nổi bật</span>
                                <p class="text-xs text-gray-500">Bài viết sẽ được ưu tiên hiển thị</p>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-8 flex items-center justify-between pt-6 border-t border-gray-200">
            <a href="{{ route('admin.blogs.index') }}" 
               class="px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Hủy
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>
                Tạo
                </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview image when selected
document.getElementById('image_path').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
                // Show image preview
                const preview = document.getElementById('imagePreview');
                const previewImg = document.getElementById('previewImg');
                const uploadArea = document.getElementById('uploadArea');
                const uploadText = document.getElementById('uploadText');
                
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
                
                // Change upload text to "Thay đổi ảnh"
                uploadText.textContent = 'Thay đổi ảnh';
            };
            reader.readAsDataURL(file);
        }
    });

    // AJAX form submission
    document.getElementById('blogForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear previous errors
        clearErrors();
        
        // Client-side validation first
        const validation = validateForm();
        if (!validation.isValid) {
            // Focus the field with error
            if (validation.element) {
                validation.element.focus();
                validation.element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Add error styling
                validation.element.classList.add('border-red-300');
                validation.element.classList.remove('border-gray-300');
            }
            
            // Show specific flash message
            if (window.showMessage) {
                window.showMessage(validation.message, 'error');
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
            return response.json();
        })
        .then(data => {
            console.log('Parsed response data:', data);
            
            // Show success message
            if (window.showMessage) {
                window.showMessage(data.message || 'Tạo bài viết thành công!', 'success');
            }
            
            // Wait 2 seconds then redirect
            setTimeout(() => {
                window.location.href = data.redirect || '{{ route("admin.blogs.index") }}';
            }, 2000);
        })
        .catch(error => {
            console.error('Error details:', error);
            
            // Restore button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            
            // Show error message
            if (window.showMessage) {
                const errorMessage = error.data?.message || error.message || 'Có lỗi xảy ra khi tạo bài viết. Vui lòng thử lại.';
                window.showMessage(errorMessage, 'error');
            }
        });
    });

    function validateForm() {
        // 1. Tiêu đề (required)
        const titleField = document.getElementById('title');
        if (!titleField || !titleField.value.trim()) {
            return {
                isValid: false,
                element: titleField,
                message: 'Vui lòng nhập tiêu đề bài viết.'
            };
        }
        if (titleField.value.trim().length < 3) {
            return {
                isValid: false,
                element: titleField,
                message: 'Tiêu đề bài viết phải có ít nhất 3 ký tự.'
            };
        }

        // 2. Nội dung (required)
        const contentField = document.getElementById('content');
        if (!contentField || !contentField.value.trim()) {
            return {
                isValid: false,
                element: contentField,
                message: 'Vui lòng nhập nội dung bài viết.'
            };
        }

        // 3. Hình ảnh (optional nhưng phải hợp lệ nếu có)
        const imageField = document.getElementById('image_path');
        if (imageField && imageField.files.length > 0) {
            const file = imageField.files[0];
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];
            if (!allowedTypes.includes(file.type)) {
                return {
                    isValid: false,
                    element: imageField,
                    message: 'Hình ảnh phải có định dạng JPEG, PNG, JPG, GIF hoặc SVG.'
                };
            }
            if (file.size > 2 * 1024 * 1024) { // 2MB
                return {
                    isValid: false,
                    element: imageField,
                    message: 'Kích thước hình ảnh không được vượt quá 2MB.'
                };
            }
        }

        return { isValid: true };
    }

    // Clear errors function
    function clearErrors() {
        document.querySelectorAll('.border-red-300').forEach(input => {
            input.classList.remove('border-red-300');
            input.classList.add('border-gray-300');
        });
    }

    // Focus on first error field if validation fails
    const firstError = document.querySelector('.border-red-300');
    if (firstError) {
        firstError.focus();
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>
@endsection