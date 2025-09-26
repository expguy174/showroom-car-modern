@extends('layouts.admin')

@section('title', 'Chi tiết hãng xe: ' . $car->name)

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16 mr-4">
                    <img class="h-16 w-16 rounded-xl object-contain border border-gray-200 bg-white p-2" 
                         src="{{ $car->logo_url }}" alt="{{ $car->name }}">
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $car->name }}</h1>
                    <p class="text-gray-600 mt-1">{{ $car->slug }}</p>
                    <div class="flex items-center mt-2 space-x-2">
                        @if($car->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Hoạt động
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>
                                Ngừng hoạt động
                            </span>
                        @endif
                        @if($car->is_featured)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-star mr-1"></i>
                                Nổi bật
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.carbrands.edit', $car) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Chỉnh sửa
                </a>
                <a href="{{ route('admin.carbrands.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Information --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Contact Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-address-book text-blue-600 mr-2"></i>
                    Thông tin liên hệ
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($car->website)
                    <div>
                        <span class="text-gray-600 block mb-1">Website:</span>
                        <a href="{{ $car->website }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-medium">
                            {{ $car->website }} <i class="fas fa-external-link-alt text-xs ml-1"></i>
                        </a>
                    </div>
                    @endif
                    
                    @if($car->phone)
                    <div>
                        <span class="text-gray-600 block mb-1">Điện thoại:</span>
                        <a href="tel:{{ $car->phone }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            {{ $car->phone }}
                        </a>
                    </div>
                    @endif
                    
                    @if($car->email)
                    <div>
                        <span class="text-gray-600 block mb-1">Email:</span>
                        <a href="mailto:{{ $car->email }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            {{ $car->email }}
                        </a>
                    </div>
                    @endif
                    
                    @if($car->address)
                    <div class="md:col-span-2">
                        <span class="text-gray-600 block mb-1">Địa chỉ:</span>
                        <p class="font-medium">{{ $car->address }}</p>
                    </div>
                    @endif
                    
                    @if(!$car->website && !$car->phone && !$car->email && !$car->address)
                        <div class="md:col-span-2">
                            <p class="text-gray-500 text-sm">Chưa có thông tin liên hệ</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Car Models --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-car text-blue-600 mr-2"></i>
                        Dòng xe ({{ $car->carModels->count() }})
                    </h3>
                    <a href="{{ route('admin.carmodels.index') }}?brand={{ $car->id }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                @if($carModels->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($carModels as $model)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $model->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $model->carVariants->count() }} phiên bản</p>
                                    @if($model->body_type)
                                        <p class="text-xs text-gray-400">{{ ucfirst($model->body_type) }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    @if($model->is_active)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            Hoạt động
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                            Ngừng
                                        </span>
                                    @endif
                                    @if($model->is_featured)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                            <i class="fas fa-star mr-1"></i>
                                            Nổi bật
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    {{-- Mini Pagination --}}
                    @if($carModels->hasPages())
                        <div class="mt-4 flex justify-center">
                            <x-admin.mini-pagination :paginator="$carModels" />
                        </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-car text-gray-400 text-3xl mb-3"></i>
                        <p class="text-gray-500">Chưa có dòng xe nào</p>
                        <a href="{{ route('admin.carmodels.create') }}?brand_id={{ $car->id }}" class="inline-flex items-center mt-3 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Thêm dòng xe
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar Information --}}
        <div class="space-y-6">
            {{-- Basic Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info text-blue-600 mr-2"></i>
                    Thông tin cơ bản
                </h3>
                <div class="space-y-3">
                    @if($car->country)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Quốc gia:</span>
                        <span class="font-medium">
                            @php
                                $countries = [
                                    'Japan' => 'Nhật Bản',
                                    'Germany' => 'Đức',
                                    'USA' => 'Mỹ',
                                    'South Korea' => 'Hàn Quốc',
                                    'France' => 'Pháp',
                                    'Italy' => 'Ý',
                                    'United Kingdom' => 'Anh',
                                    'Sweden' => 'Thụy Điển',
                                    'Czech Republic' => 'Séc',
                                    'Spain' => 'Tây Ban Nha',
                                    'China' => 'Trung Quốc',
                                    'India' => 'Ấn Độ',
                                    'Malaysia' => 'Malaysia',
                                    'Thailand' => 'Thái Lan',
                                    'Vietnam' => 'Việt Nam'
                                ];
                            @endphp
                            {{ $countries[$car->country] ?? $car->country }}
                        </span>
                    </div>
                    @endif
                    
                    @if($car->founded_year)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Năm thành lập:</span>
                        <span class="font-medium">{{ $car->founded_year }}</span>
                    </div>
                    @endif
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Thứ tự sắp xếp:</span>
                        <span class="font-medium">{{ $car->sort_order ?? 0 }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Ngày tạo:</span>
                        <span class="font-medium">{{ $car->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Cập nhật cuối:</span>
                        <span class="font-medium">{{ $car->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            {{-- SEO Info --}}
            @if($car->meta_title || $car->meta_description || $car->keywords)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-search text-blue-600 mr-2"></i>
                    Thông tin SEO
                </h3>
                <div class="space-y-3">
                    @if($car->meta_title)
                    <div>
                        <span class="text-gray-600 block mb-1">Meta Title:</span>
                        <p class="font-medium text-sm">{{ $car->meta_title }}</p>
                    </div>
                    @endif
                    
                    @if($car->meta_description)
                    <div>
                        <span class="text-gray-600 block mb-1">Meta Description:</span>
                        <p class="font-medium text-sm">{{ $car->meta_description }}</p>
                    </div>
                    @endif
                    
                    @if($car->keywords)
                    <div>
                        <span class="text-gray-600 block mb-1">Keywords:</span>
                        <div class="flex flex-wrap gap-1">
                            @foreach(explode(',', $car->keywords) as $keyword)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ trim($keyword) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Description --}}
            @if($car->description)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Mô tả
                </h3>
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! nl2br(e($car->description)) !!}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
