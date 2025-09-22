@extends('layouts.admin')

@section('title', 'Chỉnh sửa phiên bản xe')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-edit text-blue-600 mr-3"></i>
                    Cập nhật phiên bản xe: {{ $carvariant->name }}
                </h1>
                <p class="text-sm text-gray-600 mt-1">Chỉnh sửa thông tin phiên bản xe</p>
            </div>
            <a href="{{ route('admin.carvariants.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại
            </a>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.carvariants.update', $carvariant->id) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')

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
                            <label for="car_model_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Mẫu xe <span class="text-red-500">*</span>
                            </label>
                            <select name="car_model_id" id="car_model_id" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('car_model_id') border-red-300 @enderror" 
                                    required>
                                <option value="">Chọn mẫu xe...</option>
                                @foreach($carModels as $model)
                                    <option value="{{ $model->id }}" {{ old('car_model_id', $carvariant->car_model_id) == $model->id ? 'selected' : '' }}>
                                        {{ $model->carBrand->name ?? 'Unknown' }} - {{ $model->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('car_model_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Tên phiên bản <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror" 
                                   value="{{ old('name', $carvariant->name) }}" required placeholder="Ví dụ: 2.0 CVT, 1.5 Turbo...">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 @enderror" 
                                      placeholder="Mô tả về phiên bản xe...">{{ old('description', $carvariant->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="features" class="block text-sm font-medium text-gray-700 mb-2">Tính năng nổi bật</label>
                            <textarea name="features" id="features" rows="3" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('features') border-red-300 @enderror" 
                                      placeholder="Các tính năng nổi bật của phiên bản...">{{ old('features', $carvariant->features) }}</textarea>
                            @error('features')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - Pricing & Settings --}}
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-dollar-sign text-blue-600 mr-2"></i>
                        Giá bán & Cài đặt
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                Giá bán <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="price" id="price" 
                                       class="block w-full px-3 py-2 pr-12 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-300 @enderror" 
                                       value="{{ old('price', $carvariant->price ?? 0) }}" required placeholder="0" min="0" step="1000">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">VNĐ</span>
                                </div>
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Thứ tự sắp xếp</label>
                                <input type="number" name="sort_order" id="sort_order" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sort_order') border-red-300 @enderror" 
                                       value="{{ old('sort_order', $carvariant->sort_order ?? 0) }}" min="0" placeholder="0">
                                @error('sort_order')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center pt-8">
                                <div class="flex items-center">
                                    <input type="hidden" name="is_featured" value="0">
                                    <input type="checkbox" name="is_featured" id="is_featured" value="1" 
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                           {{ old('is_featured', $carvariant->is_featured) ? 'checked' : '' }}>
                                    <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                        <i class="fas fa-star text-yellow-500 mr-1"></i>
                                        Nổi bật
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active" value="1" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                   {{ old('is_active', $carvariant->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                <i class="fas fa-eye text-green-500 mr-1"></i>
                                Hiển thị công khai
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('admin.carvariants.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Hủy bỏ
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Cập nhật phiên bản xe
                </button>
            </div>
        </div>
    </form>
</div>
@endsection