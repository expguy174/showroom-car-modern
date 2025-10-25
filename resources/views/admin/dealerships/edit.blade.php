@extends('layouts.admin')

@section('title', 'Chỉnh sửa đại lý: ' . $dealership->name)

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
                    Chỉnh sửa đại lý: {{ $dealership->name }}
                </h1>
                <p class="text-sm text-gray-600 mt-1">Cập nhật thông tin đại lý</p>
            </div>
            <a href="{{ route('admin.dealerships.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại
            </a>
        </div>
    </div>

    {{-- Form --}}
    <form id="dealershipForm" action="{{ route('admin.dealerships.update', $dealership) }}" method="POST" class="p-6">
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
                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Tên đại lý <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="{{ old('name', $dealership->name) }}"
                                placeholder="VD: Toyota Việt Nam">
                        </div>

                        {{-- Code --}}
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                Mã đại lý <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="code" id="code"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="{{ old('code', $dealership->code) }}"
                                placeholder="VD: DEALER-TOYOTA-VN">
                            <p class="mt-1 text-xs text-gray-500">Mã định danh duy nhất cho đại lý</p>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Mô tả
                            </label>
                            <textarea name="description" id="description" rows="4"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Nhập mô tả về đại lý...">{{ old('description', $dealership->description) }}</textarea>
                        </div>
                    </div>
                </div>
                {{-- Contact Info --}}
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-phone text-blue-600 mr-2"></i>
                        Thông tin liên hệ
                    </h3>

                    <div class="space-y-4">
                        {{-- Phone --}}
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Số điện thoại <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="phone" id="phone"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="{{ old('phone', $dealership->phone) }}"
                                placeholder="VD: 1900545591">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="text" name="email" id="email"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="{{ old('email', $dealership->email) }}"
                                placeholder="VD: contact@dealer.com">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - Contact & Location --}}
            <div class="space-y-6">
                {{-- Location Info --}}
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                        Địa chỉ
                    </h3>

                    <div class="space-y-4">
                        {{-- Address --}}
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Địa chỉ <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="address" id="address"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="{{ old('address', $dealership->address) }}"
                                placeholder="VD: Số 315 Trường Chinh">
                        </div>

                        {{-- City --}}
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                Thành phố <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="city" id="city"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="{{ old('city', $dealership->city) }}"
                                placeholder="VD: Hà Nội">
                        </div>

                        {{-- Country --}}
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                Quốc gia
                            </label>
                            <input type="text" name="country" id="country"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="{{ old('country', $dealership->country) }}"
                                placeholder="Vietnam">
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="flex items-center">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                    {{ old('is_active', $dealership->is_active) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">
                                    Hoạt động
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200">
            <a href="{{ route('admin.dealerships.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-times mr-2"></i>
                Hủy
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-save mr-2"></i>
                Cập nhật
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Form validation và focus
    document.getElementById('dealershipForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        // Client-side validation first
        const validation = validateForm();
        if (!validation.isValid) {
            // Focus the field with error
            if (validation.element) {
                validation.element.focus();
                validation.element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }

            // Show specific flash message
            if (window.showMessage) {
                window.showMessage(validation.message, 'error');
            }
            return; // Stop submission
        }

        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        // Show loading ONLY after validation passes
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                if (window.showMessage) {
                    window.showMessage(data.message || 'Đã cập nhật đại lý thành công!', 'success');
                }

                // Redirect
                setTimeout(() => {
                    window.location.href = '{{ route("admin.dealerships.index") }}';
                }, 1500);
            } else {
                // Handle validation errors
                if (response.status === 422 && data.errors) {
                    const firstError = Object.values(data.errors)[0][0];
                    const firstField = Object.keys(data.errors)[0];

                    // Show flash message
                    if (window.showMessage) {
                        window.showMessage(firstError, 'error');
                    }

                    // Focus first field with error
                    const field = document.querySelector(`[name="${firstField}"]`);
                    if (field) {
                        field.focus();
                        field.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                } else {
                    throw new Error(data.message || 'Có lỗi xảy ra');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            if (window.showMessage) {
                window.showMessage(error.message || 'Có lỗi xảy ra khi cập nhật đại lý!', 'error');
            }
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    function validateForm() {
        // 1. Tên đại lý (required)
        const nameField = document.getElementById('name');
        if (!nameField || !nameField.value.trim()) {
            return {
                isValid: false,
                element: nameField,
                message: 'Vui lòng nhập tên đại lý.'
            };
        }

        // 2. Mã đại lý (required)
        const codeField = document.getElementById('code');
        if (!codeField || !codeField.value.trim()) {
            return {
                isValid: false,
                element: codeField,
                message: 'Vui lòng nhập mã đại lý.'
            };
        }

        // 3. Số điện thoại (required)
        const phoneField = document.getElementById('phone');
        if (!phoneField || !phoneField.value.trim()) {
            return {
                isValid: false,
                element: phoneField,
                message: 'Vui lòng nhập số điện thoại.'
            };
        }

        // 4. Email (optional but must be valid if provided)
        const emailField = document.getElementById('email');
        if (emailField && emailField.value.trim()) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(emailField.value.trim())) {
                return {
                    isValid: false,
                    element: emailField,
                    message: 'Email không đúng định dạng.'
                };
            }
        }

        // 5. Địa chỉ (required)
        const addressField = document.getElementById('address');
        if (!addressField || !addressField.value.trim()) {
            return {
                isValid: false,
                element: addressField,
                message: 'Vui lòng nhập địa chỉ.'
            };
        }

        // 6. Thành phố (required)
        const cityField = document.getElementById('city');
        if (!cityField || !cityField.value.trim()) {
            return {
                isValid: false,
                element: cityField,
                message: 'Vui lòng nhập thành phố.'
            };
        }

        return {
            isValid: true
        };
    }
</script>
@endpush

@endsection