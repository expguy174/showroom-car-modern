@extends('layouts.admin')

@section('title', 'Thêm khuyến mãi')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 max-w-4xl mx-auto">
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
    <form action="{{ route('admin.promotions.store') }}" method="POST" class="p-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Left Column - Basic Info --}}
            <div class="space-y-6">
                <div>
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
                                   value="{{ old('name') }}" required placeholder="Ví dụ: Giảm giá mùa hè...">
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
                                   value="{{ old('code') }}" required placeholder="SUMMER2024" style="text-transform: uppercase;">
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
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-300 @enderror" 
                                    required>
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
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-cog text-blue-600 mr-2"></i>
                        Giá trị & Cài đặt
                    </h3>
                    
                    <div class="space-y-4">
                        <div id="value-field">
                            <label for="value" class="block text-sm font-medium text-gray-700 mb-2">
                                Giá trị <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="value" id="value" 
                                       class="block w-full px-3 py-2 pr-12 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('value') border-red-300 @enderror" 
                                       value="{{ old('value') }}" placeholder="0" min="0" step="0.01">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span id="value-suffix" class="text-gray-500 sm:text-sm">%</span>
                                </div>
                            </div>
                            @error('value')
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

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Ngày bắt đầu</label>
                                <input type="date" name="start_date" id="start_date" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-300 @enderror" 
                                       value="{{ old('start_date') }}">
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Ngày kết thúc</label>
                                <input type="date" name="end_date" id="end_date" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-300 @enderror" 
                                       value="{{ old('end_date') }}">
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
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
                                <i class="fas fa-play-circle text-green-500 mr-1"></i>
                                Kích hoạt ngay
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('admin.promotions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Hủy bỏ
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Lưu khuyến mãi
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Update value suffix based on promotion type
document.getElementById('type').addEventListener('change', function(e) {
    const valueField = document.getElementById('value');
    const valueSuffix = document.getElementById('value-suffix');
    const valueContainer = document.getElementById('value-field');
    
    switch(e.target.value) {
        case 'percentage':
            valueSuffix.textContent = '%';
            valueField.setAttribute('max', '100');
            valueField.setAttribute('step', '0.01');
            valueContainer.style.display = 'block';
            break;
        case 'fixed_amount':
            valueSuffix.textContent = 'VNĐ';
            valueField.removeAttribute('max');
            valueField.setAttribute('step', '1000');
            valueContainer.style.display = 'block';
            break;
        case 'free_shipping':
            valueContainer.style.display = 'none';
            break;
        default:
            valueSuffix.textContent = '';
            valueContainer.style.display = 'block';
    }
});

// Auto-generate code from name
document.getElementById('name').addEventListener('input', function(e) {
    const codeField = document.getElementById('code');
    if (!codeField.value) {
        const code = e.target.value
            .toUpperCase()
            .replace(/[^A-Z0-9\s]/g, '')
            .replace(/\s+/g, '')
            .substring(0, 10);
        codeField.value = code;
    }
});

// Force uppercase for code field
document.getElementById('code').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
});
</script>
@endsection
