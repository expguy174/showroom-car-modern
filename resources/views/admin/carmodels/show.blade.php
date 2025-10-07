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
                
                {{-- Main Image Display with Navigation --}}
                <div class="mb-4 max-w-2xl mx-auto">
                    @php $mainImage = $carModel->images->where('is_main', true)->first() ?? $carModel->images->first() @endphp
                    <div class="relative group">
                        <img id="mainImage" 
                             src="{{ $mainImage->image_url }}" 
                             alt="{{ $mainImage->alt_text ?? $carModel->name }}"
                             class="w-full h-64 md:h-80 object-cover rounded-lg border border-gray-200 shadow-sm cursor-pointer"
                             onclick="viewImage('{{ $mainImage->image_url }}', '{{ $mainImage->title ?? $carModel->name }}')">
                        
                        {{-- Navigation Arrows --}}
                        @if($carModel->images->count() > 1)
                        <button id="prevBtn" onclick="previousImage()" 
                                class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-70 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button id="nextBtn" onclick="nextImage()" 
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-70 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        @endif
                        
                        {{-- Image Counter --}}
                        @if($carModel->images->count() > 1)
                        <div class="absolute bottom-4 right-4 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm">
                            <span id="currentImageIndex">1</span> / {{ $carModel->images->count() }}
                        </div>
                        @endif
                    </div>
                </div>
                
                {{-- Image Filter Tabs --}}
                @php
                    $imageTypes = $carModel->images->groupBy('image_type');
                    $allImages = $carModel->images->sortBy('sort_order');
                    // Default to desktop size (4), JavaScript will handle responsive
                    $perPage = 4;
                    
                    // Initial load - first page of all images
                    $initialImages = $allImages->take($perPage);
                @endphp
                
                @if($imageTypes->count() > 0)
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
                                'gallery' => ['icon' => 'fas fa-images', 'text' => 'Thư viện'],
                                'exterior' => ['icon' => 'fas fa-car', 'text' => 'Ngoại thất'],
                                'interior' => ['icon' => 'fas fa-couch', 'text' => 'Nội thất'],
                                'engine' => ['icon' => 'fas fa-cog', 'text' => 'Động cơ'],
                                'wheel' => ['icon' => 'fas fa-circle', 'text' => 'Bánh xe'],
                                'detail' => ['icon' => 'fas fa-search-plus', 'text' => 'Chi tiết']
                            ];
                            $config = $typeConfig[$type] ?? ['icon' => 'fas fa-images', 'text' => ucfirst($type)];
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
                    
                    <div id="imageGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($initialImages as $image)
                            <div class="image-item relative bg-gray-50 rounded-lg overflow-hidden border border-gray-200 hover:border-blue-300 transition-colors" 
                                 data-type="{{ $image->image_type }}">
                                
                                {{-- Image --}}
                                <div class="relative cursor-pointer" 
                                     onclick="changeMainImage('{{ $image->image_url }}', '{{ $image->alt_text ?? $carModel->name }}')">
                                    <img src="{{ $image->image_url }}" 
                                         alt="{{ $image->alt_text ?? $carModel->name }}"
                                         class="w-full h-32 object-cover hover:opacity-90 transition-opacity">
                                </div>
                                
                                {{-- Top badges --}}
                                <div class="absolute top-2 left-2 flex flex-wrap gap-1">
                                    @if($image->is_main)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-star mr-1"></i>Chính
                                        </span>
                                    @endif
                                </div>
                                
                                {{-- Image info --}}
                                <div class="p-2">
                                    @if($image->title)
                                        <p class="text-xs font-medium text-gray-900 truncate mb-1">{{ $image->title }}</p>
                                    @endif
                                    @if($image->alt_text)
                                        <p class="text-xs text-gray-600 truncate">{{ $image->alt_text }}</p>
                                    @endif
                                    <div class="flex items-center justify-between mt-1">
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                @switch($image->image_type)
                                                    @case('gallery') Thư viện @break
                                                    @case('interior') Nội thất @break
                                                    @case('exterior') Ngoại thất @break
                                                    @default {{ ucfirst($image->image_type) }}
                                                @endswitch
                                            </span>
                                        </div>
                                        @if(!$image->is_active)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                Ẩn
                                            </span>
                                        @endif
                                    </div>
                                    @if($image->description)
                                        <p class="text-xs text-gray-500 mt-1 truncate" title="{{ $image->description }}">{{ $image->description }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- Image Pagination --}}
                    <div id="imagePagination" class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div id="imageInfo" class="text-sm text-gray-600">
                            Hiển thị 1-{{ min(4, $allImages->count()) }} trong {{ $allImages->count() }} ảnh
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
// Images data array
const images = [
    @foreach($carModel->images as $index => $image)
    {
        url: "{{ $image->image_url }}",
        alt: "{{ addslashes($image->alt_text ?? $carModel->name) }}",
        title: "{{ addslashes($image->title ?? $carModel->name) }}",
        image_type: "{{ $image->image_type ?? 'gallery' }}"
    }@if(!$loop->last),@endif
    @endforeach
];

let currentImageIndex = 0;

// Pagination variables
let currentPage = 1;
let perPage = 4;
let totalImages = 0;
let totalPages = 0;
let currentFilter = 'all';
let filteredImages = [];
let allImagesData = [];

// Change main image by index
function changeMainImageByIndex(index) {
    if (index >= 0 && index < images.length) {
        currentImageIndex = index;
        const image = images[index];
        changeMainImage(image.url, image.alt, image.title);
        updateImageCounter();
        updateThumbnailActive();
    }
}

// Change main image when clicking thumbnail
function changeMainImage(url, alt, title) {
    const mainImage = document.getElementById('mainImage');
    if (mainImage) {
        mainImage.src = url;
        mainImage.alt = alt || 'Hình ảnh';
        // Update onclick for modal
        mainImage.onclick = function() {
            viewImage(url, title || alt || 'Hình ảnh');
        };
    }
}

// Navigate to previous image
function previousImage() {
    const prevIndex = currentImageIndex > 0 ? currentImageIndex - 1 : images.length - 1;
    changeMainImageByIndex(prevIndex);
    // Sync with thumbnail pagination
    syncThumbnailPagination(prevIndex);
}

// Navigate to next image
function nextImage() {
    const nextIndex = currentImageIndex < images.length - 1 ? currentImageIndex + 1 : 0;
    changeMainImageByIndex(nextIndex);
    // Sync with thumbnail pagination
    syncThumbnailPagination(nextIndex);
}

// Sync thumbnail pagination when using main image arrows
function syncThumbnailPagination(imageIndex) {
    // Find which page contains this image
    const targetPage = Math.ceil((imageIndex + 1) / perPage);
    if (targetPage !== currentPage) {
        currentPage = targetPage;
        renderImages();
        updatePagination();
    }
    // Update active thumbnail
    updateThumbnailActive();
}

// Update image counter
function updateImageCounter() {
    const counter = document.getElementById('currentImageIndex');
    if (counter) {
        counter.textContent = currentImageIndex + 1;
    }
}

// Update thumbnail active state
function updateThumbnailActive() {
    const thumbnails = document.querySelectorAll('.thumbnail-item');
    thumbnails.forEach((thumb, index) => {
        const thumbIndex = parseInt(thumb.getAttribute('data-index'));
        if (thumbIndex === currentImageIndex) {
            thumb.classList.add('active');
            thumb.querySelector('img').classList.add('border-blue-500', 'border-2');
        } else {
            thumb.classList.remove('active');
            thumb.querySelector('img').classList.remove('border-blue-500', 'border-2');
        }
    });
}

function viewImage(url, title) {
    document.getElementById('modalImage').src = url;
    document.getElementById('imageTitle').textContent = title || 'Hình ảnh';
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (document.getElementById('imageModal').classList.contains('hidden')) {
        // Only work when modal is closed
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            previousImage();
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            nextImage();
        }
    }
});

// Close modal when clicking outside
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Pagination functions
function updatePagination() {
    const imageInfo = document.getElementById('imageInfo');
    const paginationControls = document.getElementById('paginationControls');
    
    // Update info text
    const startIndex = (currentPage - 1) * perPage + 1;
    const endIndex = Math.min(currentPage * perPage, totalImages);
    imageInfo.textContent = `Hiển thị ${startIndex}-${endIndex} trong ${totalImages} ảnh`;
    
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
        
        // Page numbers (show max 5 pages)
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, startPage + 4);
        
        for (let i = startPage; i <= endPage; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = i === currentPage 
                ? 'inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-300 rounded-lg'
                : 'inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors';
            pageBtn.textContent = i;
            if (i !== currentPage) {
                pageBtn.onclick = () => goToPage(i);
            }
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
    if (page >= 1 && page <= totalPages) {
        currentPage = page;
        renderImages();
        updatePagination();
    }
}

function renderImages() {
    const imageGrid = document.getElementById('imageGrid');
    const startIndex = (currentPage - 1) * perPage;
    const endIndex = startIndex + perPage;
    const imagesToShow = filteredImages.slice(startIndex, endIndex);
    
    // Clear current images
    imageGrid.innerHTML = '';
    
    // Render new images
    imagesToShow.forEach((imageData, index) => {
        const actualIndex = startIndex + index;
        const card = createImageCard(imageData, actualIndex);
        imageGrid.appendChild(card);
    });
}

function createImageCard(imageData, index) {
    const card = document.createElement('div');
    card.className = 'image-item relative bg-gray-50 rounded-lg overflow-hidden border border-gray-200 hover:border-blue-300 transition-colors thumbnail-item';
    card.setAttribute('data-type', imageData.image_type);
    card.setAttribute('data-index', imageData.index);
    
    card.innerHTML = `
        <div class="relative cursor-pointer" onclick="changeMainImageByIndex(${imageData.index})">
            <img src="${imageData.url}" alt="${imageData.alt}" class="w-full h-32 object-cover hover:opacity-90 transition-opacity">
        </div>
        <div class="p-2">
            <p class="text-xs font-medium text-gray-900 truncate mb-1">${imageData.title}</p>
            <p class="text-xs text-gray-600 truncate">${imageData.alt}</p>
            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                ${getTypeText(imageData.image_type)}
            </span>
        </div>
    `;
    
    return card;
}

// Filter images by type
function filterImages(type) {
    currentFilter = type;
    currentPage = 1;
    
    // Update filter button states
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active', 'bg-blue-100', 'text-blue-700', 'border-blue-200');
        btn.classList.add('bg-gray-100', 'text-gray-700', 'border-gray-200');
    });
    
    // Set active filter button
    const activeBtn = document.querySelector(`[data-type="${type}"]`);
    if (activeBtn) {
        activeBtn.classList.remove('bg-gray-100', 'text-gray-700', 'border-gray-200');
        activeBtn.classList.add('active', 'bg-blue-100', 'text-blue-700', 'border-blue-200');
    }
    
    // Filter images
    if (type === 'all') {
        filteredImages = [...allImagesData];
    } else {
        filteredImages = allImagesData.filter(img => img.image_type === type);
    }
    
    // Update pagination
    totalImages = filteredImages.length;
    totalPages = Math.ceil(totalImages / perPage);
    
    // Render filtered images
    renderImages();
    updatePagination();
}

// Get Vietnamese text for image type
function getTypeText(type) {
    const typeConfig = {
        'gallery': 'Thư viện',
        'exterior': 'Ngoại thất', 
        'interior': 'Nội thất',
        'engine': 'Động cơ',
        'wheel': 'Bánh xe',
        'detail': 'Chi tiết'
    };
    return typeConfig[type] || type;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set initial active thumbnail
    updateThumbnailActive();
    
    // Initialize pagination with actual image types from PHP
    allImagesData = images.map((img, index) => ({
        ...img,
        index: index
    }));
    filteredImages = [...allImagesData];
    totalImages = filteredImages.length;
    totalPages = Math.ceil(totalImages / perPage);
    updatePagination();
});
</script>
@endsection
