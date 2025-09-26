@extends('layouts.admin')

@section('title', 'Chi tiết phiên bản xe: ' . $carvariant->name)

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16 mr-4">
                    @if($carvariant->images->where('is_main', true)->first())
                        <img class="h-16 w-16 rounded-xl object-cover border border-gray-200 bg-white p-1" 
                             src="{{ $carvariant->images->where('is_main', true)->first()->image_url }}" 
                             alt="{{ $carvariant->name }}">
                    @else
                        <div class="h-16 w-16 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center">
                            <i class="fas fa-car text-gray-400 text-xl"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $carvariant->name }}</h1>
                    <p class="text-gray-600 mt-1">{{ $carvariant->carModel->carBrand->name }} {{ $carvariant->carModel->name }} • {{ $carvariant->sku }}</p>
                    <div class="flex items-center mt-2 space-x-2">
                        @if($carvariant->is_active)
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

                        @if($carvariant->is_featured)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-star mr-1"></i>
                                Nổi bật
                            </span>
                        @endif

                        @if($carvariant->is_on_sale)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                <i class="fas fa-tags mr-1"></i>
                                Khuyến mãi
                            </span>
                        @endif

                        @if($carvariant->is_new_arrival)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-certificate mr-1"></i>
                                Mới về
                            </span>
                        @endif

                        @if($carvariant->is_bestseller)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-crown mr-1"></i>
                                Bán chạy
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.carvariants.edit', $carvariant) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Chỉnh sửa
                </a>
                <a href="{{ route('admin.carvariants.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Information --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Pricing Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-dollar-sign text-green-600 mr-2"></i>
                    Thông tin giá
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600">Giá gốc</div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($carvariant->base_price, 0, ',', '.') }} VNĐ</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="text-sm text-green-600">Giá hiện tại</div>
                        <div class="text-2xl font-bold text-green-700">{{ number_format($carvariant->current_price, 0, ',', '.') }} VNĐ</div>
                        @if($carvariant->base_price > $carvariant->current_price)
                            <div class="text-sm text-green-600 mt-1">
                                Tiết kiệm: {{ number_format($carvariant->base_price - $carvariant->current_price, 0, ',', '.') }} VNĐ
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Description --}}
            @if($carvariant->description)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Mô tả chi tiết
                </h3>
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! nl2br(e($carvariant->description)) !!}
                </div>
            </div>
            @endif

            {{-- Images Gallery --}}
            @if($carvariant->images->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-images text-purple-600 mr-2"></i>
                    Thư viện ảnh ({{ $carvariant->images->count() }})
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($carvariant->images as $image)
                    <div class="relative group">
                        <img src="{{ $image->image_url }}" alt="{{ $image->alt_text }}" 
                             class="w-full h-32 object-cover rounded-lg border border-gray-200">
                        @if($image->is_main)
                            <div class="absolute top-2 left-2">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-star mr-1"></i>
                                    Chính
                                </span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all rounded-lg flex items-center justify-center">
                            <button onclick="viewImage('{{ $image->image_url }}', '{{ $image->title }}')" 
                                    class="opacity-0 group-hover:opacity-100 bg-white text-gray-800 px-3 py-1 rounded text-sm font-medium transition-all">
                                <i class="fas fa-eye mr-1"></i>
                                Xem
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Colors --}}
            @if($carvariant->colors->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-palette text-pink-600 mr-2"></i>
                    Màu sắc có sẵn ({{ $carvariant->colors->count() }})
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($carvariant->colors as $color)
                    <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                        <div class="w-8 h-8 rounded-full border border-gray-300 mr-3" 
                             style="background-color: {{ $color->hex_code }}"></div>
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">{{ $color->name }}</div>
                            <div class="text-sm text-gray-500">{{ $color->hex_code }}</div>
                        </div>
                        @if($color->stock_quantity !== null)
                        <div class="text-sm text-gray-600">
                            Tồn kho: {{ $color->stock_quantity }}
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Specifications --}}
            @if($carvariant->specifications->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-cogs text-gray-600 mr-2"></i>
                    Thông số kỹ thuật ({{ $carvariant->specifications->count() }})
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($carvariant->specifications as $spec)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="font-medium text-gray-700">{{ $spec->name }}</span>
                        <span class="text-gray-900">{{ $spec->value }} {{ $spec->unit }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Features --}}
            @if($carvariant->featuresRelation->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-list-check text-green-600 mr-2"></i>
                    Tính năng nổi bật ({{ $carvariant->featuresRelation->count() }})
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($carvariant->featuresRelation as $feature)
                    <div class="flex items-center p-3 bg-green-50 rounded-lg">
                        <i class="fas fa-check-circle text-green-600 mr-3"></i>
                        <div>
                            <div class="font-medium text-gray-900">{{ $feature->name }}</div>
                            @if($feature->description)
                                <div class="text-sm text-gray-600">{{ $feature->description }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Related Variants --}}
            @if($relatedVariants->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-car text-blue-600 mr-2"></i>
                    Phiên bản khác cùng dòng xe ({{ $relatedVariants->count() }})
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($relatedVariants as $variant)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $variant->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $variant->sku }}</p>
                                <p class="text-lg font-bold text-green-600 mt-1">
                                    {{ number_format($variant->current_price, 0, ',', '.') }} VNĐ
                                </p>
                            </div>
                            <div class="ml-4">
                                <a href="{{ route('admin.carvariants.show', $variant) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
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
                        <span class="font-medium">{{ $carvariant->carModel->carBrand->name }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Dòng xe:</span>
                        <span class="font-medium">{{ $carvariant->carModel->name }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">SKU:</span>
                        <span class="font-medium font-mono text-sm">{{ $carvariant->sku }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Slug:</span>
                        <span class="font-medium font-mono text-sm">{{ $carvariant->slug }}</span>
                    </div>
                    
                    @if($carvariant->short_description)
                    <div>
                        <span class="text-gray-600 block mb-1">Mô tả ngắn:</span>
                        <p class="font-medium text-sm">{{ $carvariant->short_description }}</p>
                    </div>
                    @endif
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Ngày tạo:</span>
                        <span class="font-medium">{{ $carvariant->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Cập nhật cuối:</span>
                        <span class="font-medium">{{ $carvariant->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            {{-- SEO Info --}}
            @if($carvariant->meta_title || $carvariant->meta_description || $carvariant->keywords)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-search text-blue-600 mr-2"></i>
                    Thông tin SEO
                </h3>
                <div class="space-y-3">
                    @if($carvariant->meta_title)
                    <div>
                        <span class="text-gray-600 block mb-1">Meta Title:</span>
                        <p class="font-medium text-sm">{{ $carvariant->meta_title }}</p>
                    </div>
                    @endif
                    
                    @if($carvariant->meta_description)
                    <div>
                        <span class="text-gray-600 block mb-1">Meta Description:</span>
                        <p class="font-medium text-sm">{{ $carvariant->meta_description }}</p>
                    </div>
                    @endif
                    
                    @if($carvariant->keywords)
                    <div>
                        <span class="text-gray-600 block mb-1">Keywords:</span>
                        <div class="flex flex-wrap gap-1">
                            @foreach(explode(',', $carvariant->keywords) as $keyword)
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

            {{-- Statistics --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                    Thống kê
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Số màu:</span>
                        <span class="font-medium">{{ $carvariant->colors->count() }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Số ảnh:</span>
                        <span class="font-medium">{{ $carvariant->images->count() }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Thông số kỹ thuật:</span>
                        <span class="font-medium">{{ $carvariant->specifications->count() }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Tính năng:</span>
                        <span class="font-medium">{{ $carvariant->featuresRelation->count() }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Đánh giá:</span>
                        <span class="font-medium">{{ $carvariant->reviews->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Image Modal --}}
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-4xl max-h-full overflow-auto">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h3 id="imageTitle" class="text-lg font-semibold text-gray-900"></h3>
            <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-4">
            <img id="modalImage" src="" alt="" class="max-w-full h-auto">
        </div>
    </div>
</div>

<script>
function viewImage(url, title) {
    document.getElementById('modalImage').src = url;
    document.getElementById('imageTitle').textContent = title || 'Hình ảnh';
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});
</script>
@endsection
