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
                
                {{-- Image Filter Tabs --}}
                @php
                    $imageTypes = $carModel->images->groupBy('image_type');
                    $allImages = $carModel->images->sortBy('sort_order');
                    $perPage = 3;
                    
                    // Initial load - first page of all images
                    $initialImages = $allImages->take($perPage);
                @endphp
                
                @if($imageTypes->count() > 1)
                <div class="flex flex-wrap gap-2 mb-4 border-b border-gray-200 pb-3">
                    <button onclick="filterImages('all')" 
                            class="filter-btn active px-3 py-1.5 text-sm font-medium rounded-lg transition-colors bg-blue-100 text-blue-700 border border-blue-200" 
                            data-type="all">
                        <i class="fas fa-th mr-1"></i>
                        Tất cả ({{ $allImages->count() }})
                    </button>
                    @foreach($imageTypes as $type => $images)
                        @php
                            $typeConfig = [
                                'gallery' => ['icon' => 'fas fa-images', 'text' => 'Tổng quan', 'color' => 'purple'],
                                'exterior' => ['icon' => 'fas fa-car', 'text' => 'Ngoại thất', 'color' => 'green'],
                                'interior' => ['icon' => 'fas fa-couch', 'text' => 'Nội thất', 'color' => 'orange']
                            ];
                            $config = $typeConfig[$type] ?? $typeConfig['gallery'];
                        @endphp
                        <button onclick="filterImages('{{ $type }}')" 
                                class="filter-btn px-3 py-1.5 text-sm font-medium rounded-lg transition-colors bg-gray-100 text-gray-700 border border-gray-200 hover:bg-gray-200" 
                                data-type="{{ $type }}">
                            <i class="{{ $config['icon'] }} mr-1"></i>
                            {{ $config['text'] }} ({{ $images->count() }})
                        </button>
                    @endforeach
                </div>
                @endif

                {{-- Thumbnail Gallery --}}
                <div class="space-y-4">
                    {{-- Loading indicator --}}
                    <div id="imageLoading" class="hidden text-center py-8">
                        <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-gray-500 bg-white">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Đang tải ảnh...
                        </div>
                    </div>
                    
                    <div id="imageGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($initialImages as $image)
                            <div class="image-item bg-gray-50 rounded-lg p-3 border border-gray-200 hover:border-blue-300 transition-colors" 
                                 data-type="{{ $image->image_type }}">
                                <div class="relative group cursor-pointer thumbnail-item mb-3" 
                                     onclick="changeMainImage('{{ $image->image_url }}', '{{ $image->alt_text ?? $carModel->name }}')">
                                    <img src="{{ $image->image_url }}" 
                                         alt="{{ $image->alt_text ?? $carModel->name }}"
                                         class="w-full h-24 object-cover rounded-lg border-2 border-gray-200 group-hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md {{ $image->is_main ? 'border-blue-500 ring-2 ring-blue-200' : '' }}">
                                    
                                    {{-- Image Badges --}}
                                    <div class="absolute top-1 left-1 flex flex-col gap-1">
                                        @if($image->is_main)
                                            <span class="inline-flex items-center justify-center px-1.5 py-0.5 bg-blue-600 text-white text-xs rounded-full font-medium">
                                                <i class="fas fa-star mr-1"></i>Chính
                                            </span>
                                        @endif
                                        @if(!$image->is_active)
                                            <span class="inline-flex items-center justify-center px-1.5 py-0.5 bg-red-500 text-white text-xs rounded-full">
                                                <i class="fas fa-eye-slash"></i>
                                            </span>
                                        @endif
                                    </div>
                                    
                                    {{-- Image Type Badge --}}
                                    <div class="absolute top-1 right-1">
                                        @php
                                            $typeConfig = [
                                                'gallery' => ['icon' => 'fas fa-images', 'color' => 'bg-purple-500', 'text' => 'Tổng quan'],
                                                'exterior' => ['icon' => 'fas fa-car', 'color' => 'bg-green-500', 'text' => 'Ngoại thất'],
                                                'interior' => ['icon' => 'fas fa-couch', 'color' => 'bg-orange-500', 'text' => 'Nội thất']
                                            ];
                                            $config = $typeConfig[$image->image_type] ?? $typeConfig['gallery'];
                                        @endphp
                                        <span class="inline-flex items-center justify-center w-6 h-6 {{ $config['color'] }} text-white text-xs rounded-full" 
                                              title="{{ $config['text'] }}">
                                            <i class="{{ $config['icon'] }}"></i>
                                        </span>
                                    </div>
                                </div>
                                
                                {{-- Image Info --}}
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-medium text-gray-900 truncate">
                                            {{ $image->title ?: 'Ảnh ' . $loop->iteration }}
                                        </h4>
                                        <span class="text-xs text-gray-500">#{{ $image->sort_order }}</span>
                                    </div>
                                    
                                    @if($image->alt_text)
                                        <p class="text-xs text-gray-600 truncate" title="{{ $image->alt_text }}">
                                            <i class="fas fa-search mr-1"></i>{{ $image->alt_text }}
                                        </p>
                                    @endif
                                    
                                    @if($image->description)
                                        <p class="text-xs text-gray-500 line-clamp-2" title="{{ $image->description }}">
                                            {{ $image->description }}
                                        </p>
                                    @endif
                                    
                                    <div class="flex items-center justify-between text-xs text-gray-400 pt-1 border-t border-gray-200">
                                        <span>{{ $image->created_at->format('d/m/Y') }}</span>
                                        <div class="flex items-center gap-2">
                                            @if($image->is_active)
                                                <span class="text-green-600"><i class="fas fa-eye"></i></span>
                                            @else
                                                <span class="text-red-600"><i class="fas fa-eye-slash"></i></span>
                                            @endif
                                            <span>{{ $config['text'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- Image Pagination --}}
                    <div id="imagePagination" class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div id="imageInfo" class="text-sm text-gray-600">
                            Hiển thị 1-{{ min(3, $allImages->count()) }} trong {{ $allImages->count() }} ảnh
                        </div>
                        
                        <div id="paginationControls" class="flex items-center space-x-1">
                            {{-- Pagination buttons will be generated by JavaScript --}}
                        </div>
                    </div>
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
                        <span class="font-medium">
                            @php
                                $bodyTypes = [
                                    'sedan' => 'Sedan',
                                    'suv' => 'SUV', 
                                    'hatchback' => 'Hatchback',
                                    'wagon' => 'Wagon',
                                    'coupe' => 'Coupe',
                                    'convertible' => 'Convertible',
                                    'pickup' => 'Pickup',
                                    'van' => 'Van',
                                    'minivan' => 'Minivan'
                                ];
                            @endphp
                            {{ $bodyTypes[$carModel->body_type] ?? ucfirst($carModel->body_type) }}
                        </span>
                    </div>
                    @endif
                    
                    @if($carModel->segment)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Phân khúc:</span>
                        <span class="font-medium">
                            @php
                                $segments = [
                                    'economy' => 'Xe tiết kiệm',
                                    'compact' => 'Xe nhỏ gọn', 
                                    'mid-size' => 'Xe cỡ trung',
                                    'full-size' => 'Xe cỡ lớn',
                                    'luxury' => 'Xe sang trọng',
                                    'premium' => 'Xe cao cấp',
                                    'sports' => 'Xe thể thao',
                                    'exotic' => 'Xe siêu sang'
                                ];
                            @endphp
                            {{ $segments[$carModel->segment] ?? strtoupper($carModel->segment) }}
                        </span>
                    </div>
                    @endif
                    
                    @if($carModel->fuel_type)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Nhiên liệu:</span>
                        <span class="font-medium">
                            @php
                                $fuelTypes = [
                                    'gasoline' => 'Xăng',
                                    'diesel' => 'Dầu',
                                    'hybrid' => 'Hybrid',
                                    'electric' => 'Điện',
                                    'plug-in_hybrid' => 'Plug-in Hybrid',
                                    'hydrogen' => 'Hydrogen'
                                ];
                            @endphp
                            {{ $fuelTypes[$carModel->fuel_type] ?? ucfirst($carModel->fuel_type) }}
                        </span>
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
        </div>
    </div>
</div>

<script>
// Global variables
let currentFilter = 'all';
let currentPage = 1;
let totalPages = 1;
let totalImages = {{ $allImages->count() }};
const perPage = 3;
const carModelId = {{ $carModel->id }};

// Image data from server
const allImagesData = @json($allImages->values());

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

function filterImages(type) {
    currentFilter = type;
    currentPage = 1;
    
    // Update filter button states
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active', 'bg-blue-100', 'text-blue-700', 'border-blue-200');
        btn.classList.add('bg-gray-100', 'text-gray-700', 'border-gray-200');
    });
    
    document.querySelector(`[data-type="${type}"]`).classList.remove('bg-gray-100', 'text-gray-700', 'border-gray-200');
    document.querySelector(`[data-type="${type}"]`).classList.add('active', 'bg-blue-100', 'text-blue-700', 'border-blue-200');
    
    loadImages();
}

function loadImages() {
    showLoading();
    
    // Filter images based on current filter
    let filteredImages = allImagesData;
    if (currentFilter !== 'all') {
        filteredImages = allImagesData.filter(img => img.image_type === currentFilter);
    }
    
    // Calculate pagination
    totalImages = filteredImages.length;
    totalPages = Math.ceil(totalImages / perPage);
    
    // Get current page images
    const startIndex = (currentPage - 1) * perPage;
    const endIndex = startIndex + perPage;
    const currentImages = filteredImages.slice(startIndex, endIndex);
    
    // Simulate loading delay for better UX
    setTimeout(() => {
        renderImages(currentImages);
        updatePagination();
        hideLoading();
    }, 300);
}

function renderImages(images) {
    const imageGrid = document.getElementById('imageGrid');
    imageGrid.innerHTML = '';
    
    images.forEach((image, index) => {
        const typeConfig = {
            'gallery': { icon: 'fas fa-images', color: 'bg-purple-500', text: 'Tổng quan' },
            'exterior': { icon: 'fas fa-car', color: 'bg-green-500', text: 'Ngoại thất' },
            'interior': { icon: 'fas fa-couch', color: 'bg-orange-500', text: 'Nội thất' }
        };
        const config = typeConfig[image.image_type] || typeConfig['gallery'];
        
        const imageItem = document.createElement('div');
        imageItem.className = 'image-item bg-gray-50 rounded-lg p-3 border border-gray-200 hover:border-blue-300 transition-colors';
        imageItem.setAttribute('data-type', image.image_type);
        
        imageItem.innerHTML = `
            <div class="relative group cursor-pointer thumbnail-item mb-3" 
                 onclick="changeMainImage('${image.image_url}', '${image.alt_text || ''}')">
                <img src="${image.image_url}" 
                     alt="${image.alt_text || ''}"
                     class="w-full h-24 object-cover rounded-lg border-2 border-gray-200 group-hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md ${image.is_main ? 'border-blue-500 ring-2 ring-blue-200' : ''}">
                
                <div class="absolute top-1 left-1 flex flex-col gap-1">
                    ${image.is_main ? '<span class="inline-flex items-center justify-center px-1.5 py-0.5 bg-blue-600 text-white text-xs rounded-full font-medium"><i class="fas fa-star mr-1"></i>Chính</span>' : ''}
                    ${!image.is_active ? '<span class="inline-flex items-center justify-center px-1.5 py-0.5 bg-red-500 text-white text-xs rounded-full"><i class="fas fa-eye-slash"></i></span>' : ''}
                </div>
                
                <div class="absolute top-1 right-1">
                    <span class="inline-flex items-center justify-center w-6 h-6 ${config.color} text-white text-xs rounded-full" 
                          title="${config.text}">
                        <i class="${config.icon}"></i>
                    </span>
                </div>
            </div>
            
            <div class="space-y-1">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-medium text-gray-900 truncate">
                        ${image.title || 'Ảnh ' + (index + 1)}
                    </h4>
                    <span class="text-xs text-gray-500">#${image.sort_order}</span>
                </div>
                
                ${image.alt_text ? `<p class="text-xs text-gray-600 truncate" title="${image.alt_text}"><i class="fas fa-search mr-1"></i>${image.alt_text}</p>` : ''}
                
                ${image.description ? `<p class="text-xs text-gray-500 line-clamp-2" title="${image.description}">${image.description}</p>` : ''}
                
                <div class="flex items-center justify-between text-xs text-gray-400 pt-1 border-t border-gray-200">
                    <span>${new Date(image.created_at).toLocaleDateString('vi-VN')}</span>
                    <div class="flex items-center gap-2">
                        ${image.is_active ? '<span class="text-green-600"><i class="fas fa-eye"></i></span>' : '<span class="text-red-600"><i class="fas fa-eye-slash"></i></span>'}
                        <span>${config.text}</span>
                    </div>
                </div>
            </div>
        `;
        
        imageGrid.appendChild(imageItem);
    });
}

function updatePagination() {
    const imageInfo = document.getElementById('imageInfo');
    const paginationControls = document.getElementById('paginationControls');
    
    // Update info text
    const startIndex = (currentPage - 1) * perPage + 1;
    const endIndex = Math.min(currentPage * perPage, totalImages);
    const filterText = currentFilter !== 'all' ? ` (${getFilterText(currentFilter)})` : '';
    imageInfo.textContent = `Hiển thị ${startIndex}-${endIndex} trong ${totalImages} ảnh${filterText}`;
    
    // Generate pagination controls
    paginationControls.innerHTML = '';
    
    if (totalPages > 1) {
        // Previous button
        if (currentPage > 1) {
            const prevBtn = document.createElement('button');
            prevBtn.className = 'inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors';
            prevBtn.innerHTML = '<i class="fas fa-chevron-left mr-1"></i>Trước';
            prevBtn.onclick = () => goToPage(currentPage - 1);
            paginationControls.appendChild(prevBtn);
        }
        
        // Page numbers
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, currentPage + 2);
        
        for (let i = startPage; i <= endPage; i++) {
            const pageBtn = document.createElement('button');
            if (i === currentPage) {
                pageBtn.className = 'inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-300 rounded-lg';
            } else {
                pageBtn.className = 'inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors';
                pageBtn.onclick = () => goToPage(i);
            }
            pageBtn.textContent = i;
            paginationControls.appendChild(pageBtn);
        }
        
        // Next button
        if (currentPage < totalPages) {
            const nextBtn = document.createElement('button');
            nextBtn.className = 'inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors';
            nextBtn.innerHTML = 'Sau<i class="fas fa-chevron-right ml-1"></i>';
            nextBtn.onclick = () => goToPage(currentPage + 1);
            paginationControls.appendChild(nextBtn);
        }
    }
}

function goToPage(page) {
    currentPage = page;
    loadImages();
}

function getFilterText(filter) {
    const filterTexts = {
        'gallery': 'Tổng quan',
        'exterior': 'Ngoại thất',
        'interior': 'Nội thất'
    };
    return filterTexts[filter] || filter;
}

function showLoading() {
    document.getElementById('imageLoading').classList.remove('hidden');
    document.getElementById('imageGrid').style.opacity = '0.5';
}

function hideLoading() {
    document.getElementById('imageLoading').classList.add('hidden');
    document.getElementById('imageGrid').style.opacity = '1';
}

// Initialize pagination on page load
document.addEventListener('DOMContentLoaded', function() {
    // Calculate initial pagination for all images
    totalImages = allImagesData.length;
    totalPages = Math.ceil(totalImages / perPage);
    updatePagination();
});
</script>
@endsection
