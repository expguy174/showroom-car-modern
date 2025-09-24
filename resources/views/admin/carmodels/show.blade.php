@extends('layouts.admin')

@section('title', 'Chi tiết dòng xe: ' . $carModel->name)

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16 mr-4">
                    <img class="h-16 w-16 rounded-xl object-cover border border-gray-200 bg-white p-2" 
                         src="{{ $carModel->image_url }}" alt="{{ $carModel->name }}">
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $carModel->name }}</h1>
                    <p class="text-gray-600 mt-1">{{ $carModel->carBrand->name }} • {{ $carModel->slug }}</p>
                    <div class="flex items-center mt-2 space-x-2">
                        @if($carModel->is_active)
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
                        @if($carModel->is_featured)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-star mr-1"></i>
                                Nổi bật
                            </span>
                        @endif
                        @if($carModel->is_new)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-sparkles mr-1"></i>
                                Mới
                            </span>
                        @endif
                        @if($carModel->is_discontinued)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-ban mr-1"></i>
                                Ngừng sản xuất
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.carmodels.edit', $carModel) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Chỉnh sửa
                </a>
                <a href="{{ route('admin.carmodels.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Information --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Image Gallery --}}
            @if($carModel->images->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-images text-blue-600 mr-2"></i>
                    Thư viện ảnh ({{ $carModel->images->count() }} ảnh)
                </h2>
                
                {{-- Main Image --}}
                <div class="mb-4 max-w-2xl mx-auto">
                    @php $mainImage = $carModel->images->where('is_main', true)->first() ?? $carModel->images->first() @endphp
                    <img id="mainImage" 
                         src="{{ $mainImage->image_url }}" 
                         alt="{{ $mainImage->alt_text ?? $carModel->name }}"
                         class="w-full h-64 md:h-80 object-cover rounded-lg border border-gray-200 shadow-sm">
                </div>
                
                {{-- Thumbnail Gallery --}}
                <div class="grid grid-cols-3 md:grid-cols-5 lg:grid-cols-6 gap-3">
                    @foreach($carModel->images->where('is_active', true) as $image)
                        <div class="relative group cursor-pointer thumbnail-item" 
                             onclick="changeMainImage('{{ $image->image_url }}', '{{ $image->alt_text ?? $carModel->name }}')">
                            <img src="{{ $image->image_url }}" 
                                 alt="{{ $image->alt_text ?? $carModel->name }}"
                                 class="w-full h-20 md:h-24 object-cover rounded-lg border-2 border-gray-200 group-hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md {{ $image->is_main ? 'border-blue-500 ring-2 ring-blue-200' : '' }}">
                            @if($image->is_main)
                                <div class="absolute -top-1 -right-1">
                                    <span class="inline-flex items-center justify-center w-4 h-4 bg-blue-600 text-white text-xs rounded-full">
                                        <i class="fas fa-star"></i>
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Description --}}
            @if($carModel->description)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Mô tả
                </h3>
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! nl2br(e($carModel->description)) !!}
                </div>
            </div>
            @endif

            {{-- Car Variants --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-cubes text-blue-600 mr-2"></i>
                        Phiên bản ({{ $carModel->carVariants->count() }})
                    </h3>
                    <a href="{{ route('admin.carvariants.index') }}?model={{ $carModel->id }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                @if($carVariants->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($carVariants as $variant)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $variant->name }}</h4>
                                    @if($variant->price)
                                        <p class="text-sm text-blue-600 font-semibold">{{ number_format($variant->price) }} VNĐ</p>
                                    @endif
                                    @if($variant->engine_type)
                                        <p class="text-xs text-gray-400">{{ $variant->engine_type }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    @if($variant->is_active)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            Hoạt động
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                            Ngừng
                                        </span>
                                    @endif
                                    @if($variant->is_featured)
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
                    @if($carVariants->hasPages())
                        <div class="mt-4 flex justify-center">
                            <x-admin.mini-pagination :paginator="$carVariants" />
                        </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-cubes text-gray-400 text-3xl mb-3"></i>
                        <p class="text-gray-500">Chưa có phiên bản nào</p>
                        <a href="{{ route('admin.carvariants.create') }}?model_id={{ $carModel->id }}" class="inline-flex items-center mt-3 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Thêm phiên bản
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
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Hãng xe:</span>
                        <span class="font-medium">{{ $carModel->carBrand->name }}</span>
                    </div>
                    
                    @if($carModel->body_type)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Kiểu dáng:</span>
                        <span class="font-medium">{{ ucfirst($carModel->body_type) }}</span>
                    </div>
                    @endif
                    
                    @if($carModel->segment)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Phân khúc:</span>
                        <span class="font-medium">{{ strtoupper($carModel->segment) }}</span>
                    </div>
                    @endif
                    
                    @if($carModel->fuel_type)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Nhiên liệu:</span>
                        <span class="font-medium">{{ ucfirst($carModel->fuel_type) }}</span>
                    </div>
                    @endif
                    
                    @if($carModel->generation)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Thế hệ:</span>
                        <span class="font-medium">{{ $carModel->generation }}</span>
                    </div>
                    @endif
                    
                    @if($carModel->production_start_year)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Năm sản xuất:</span>
                        <span class="font-medium">
                            {{ $carModel->production_start_year }}
                            @if($carModel->production_end_year && $carModel->production_end_year != $carModel->production_start_year)
                                - {{ $carModel->production_end_year }}
                            @endif
                        </span>
                    </div>
                    @endif
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Thứ tự sắp xếp:</span>
                        <span class="font-medium">{{ $carModel->sort_order ?? 0 }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Ngày tạo:</span>
                        <span class="font-medium">{{ $carModel->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Cập nhật cuối:</span>
                        <span class="font-medium">{{ $carModel->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            {{-- SEO Info --}}
            @if($carModel->meta_title || $carModel->meta_description || $carModel->keywords)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-search text-blue-600 mr-2"></i>
                    Thông tin SEO
                </h3>
                <div class="space-y-3">
                    @if($carModel->meta_title)
                    <div>
                        <span class="text-gray-600 block mb-1">Meta Title:</span>
                        <p class="font-medium text-sm">{{ $carModel->meta_title }}</p>
                    </div>
                    @endif
                    
                    @if($carModel->meta_description)
                    <div>
                        <span class="text-gray-600 block mb-1">Meta Description:</span>
                        <p class="font-medium text-sm">{{ $carModel->meta_description }}</p>
                    </div>
                    @endif
                    
                    @if($carModel->keywords)
                    <div>
                        <span class="text-gray-600 block mb-1">Keywords:</span>
                        <div class="flex flex-wrap gap-1">
                            @foreach(explode(',', $carModel->keywords) as $keyword)
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
        </div>
    </div>
</div>

<script>
function changeMainImage(imageUrl, altText) {
    const mainImage = document.getElementById('mainImage');
    if (mainImage) {
        mainImage.src = imageUrl;
        mainImage.alt = altText;
    }
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail-item img').forEach(img => {
        img.classList.remove('border-blue-500', 'ring-2', 'ring-blue-200');
        img.classList.add('border-gray-200');
    });
    
    // Add active class to clicked thumbnail
    event.target.classList.remove('border-gray-200');
    event.target.classList.add('border-blue-500', 'ring-2', 'ring-blue-200');
}
</script>
@endsection
