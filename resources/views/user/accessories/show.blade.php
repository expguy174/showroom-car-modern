@extends('layouts.app')

@section('title', $accessory->name . ' - AutoLux')

@push('head')
@php
    $resolveImage = function($value, $fallbackText = 'No Image'){
        $val = trim((string) $value);
        if ($val === '') {
            return 'https://via.placeholder.com/1200x800/111827/ffffff?text=' . urlencode($fallbackText);
        }
        if (filter_var($val, FILTER_VALIDATE_URL)) {
            return $val;
        }
        return 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode($val);
    };
    // Get gallery images
    $galleryRaw = $accessory->gallery;
    $gallery = is_array($galleryRaw) ? $galleryRaw : (json_decode($galleryRaw ?? '[]', true) ?: []);
    
    // Extract first image URL
    $firstImageUrl = null;
    if (!empty($gallery) && isset($gallery[0])) {
        $firstImage = $gallery[0];
        if (is_array($firstImage)) {
            // New format: array with url/file_path
            $firstImageUrl = $firstImage['url'] ?? $firstImage['file_path'] ?? null;
        } elseif (is_string($firstImage)) {
            // Old format: direct URL string
            $firstImageUrl = $firstImage;
        }
    }
    
    $mainImage = $resolveImage(
        $firstImageUrl ?? $accessory->image_url,
        $accessory->name ?? 'No Image'
    );
    $hasDiscount = (bool) ($accessory->has_discount ?? false);
    $offerPrice = (int) ($accessory->current_price ?? 0);
    $availability = $accessory->is_available ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock';
    $avgForSchema = $accessory->approved_reviews_avg ?? 0;
    $aggregateRating = ($avgForSchema ?? 0) > 0 ? [
        '@type' => 'AggregateRating',
        'ratingValue' => number_format((float)$avgForSchema, 1),
        'reviewCount' => (int) ($accessory->approvedReviews()->count())
    ] : null;
    $productJson = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $accessory->name ?? '',
        'brand' => $accessory->brand ?: 'AutoLux',
        'image' => $mainImage,
        'description' => $accessory->description ?: 'Phụ kiện ô tô chính hãng tại AutoLux Showroom',
        'sku' => (string) ($accessory->sku ?? ''),
        'offers' => [
            '@type' => 'Offer',
            'priceCurrency' => 'VND',
            'price' => (string) $offerPrice,
            'availability' => $availability,
            'url' => url()->current(),
        ],
    ];
    if ($aggregateRating) { $productJson['aggregateRating'] = $aggregateRating; }
@endphp
<link rel="preload" as="image" href="{{ $mainImage }}">
<script type="application/ld+json">{!! json_encode($productJson, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
<div id="accessory-page" class="accessory-show-page min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50">
    <!-- Breadcrumb -->
    <div class="bg-white border-b border-gray-100 shadow-sm">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('home') }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                            <i class="fas fa-home"></i>
                            <span class="sr-only">Trang chủ</span>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('products.index', ['type' => 'accessory']) }}" class="text-gray-500 hover:text-blue-600 transition-colors">Phụ kiện</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-gray-900 font-medium">{{ $accessory->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-8">
            <!-- Image Gallery -->
            <div class="space-y-3 lg:space-y-5 lg:col-span-5">
                <!-- Main Image -->
                <div class="relative group">
                    <div class="aspect-[3/2] bg-white rounded-3xl shadow-2xl overflow-hidden">
                        <img src="{{ $mainImage }}"
                             id="main-image"
                             class="w-full h-full object-cover cursor-zoom-in transition-all duration-500 ease-out hover:scale-110"
                             alt="{{ $accessory->name }}"
                             loading="lazy" decoding="async" width="1200" height="800"
                             data-lightbox-src="{{ $mainImage }}">
                    </div>
                    
                    <!-- Badges -->
                    <div class="absolute top-6 left-6 flex flex-col gap-3">
                        @if($accessory->is_featured)
                        <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-gray-900 px-4 py-2 rounded-full text-sm font-bold shadow-xl">
                            <i class="fas fa-star mr-2"></i> Nổi bật
                        </div>
                        @endif
                        @if($accessory->is_bestseller)
                        <div class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-full text-sm font-bold shadow-xl">
                            <i class="fas fa-fire mr-2"></i> Bán chạy
                        </div>
                        @endif
                        @if($accessory->is_new_arrival)
                        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-full text-sm font-bold shadow-xl">
                            <i class="fas fa-sparkles mr-2"></i> Mới
                        </div>
                        @endif
                    </div>
                    

                    
                </div>
                
                <!-- Thumbnail Gallery -->
                @if(!empty($gallery))
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm font-semibold text-gray-700 mt-1">
                        <div class="flex items-center">
                            <i class="fas fa-images mr-2"></i>Thư viện ảnh ({{ count($gallery) }})
                        </div>
                        @if(count($gallery) > 4)
                        <div class="flex items-center gap-2">
                            <button type="button" class="slide-prev w-8 h-8 rounded-full bg-white shadow-sm hover:shadow-md border border-gray-200 flex items-center justify-center text-gray-500 hover:text-gray-700 transition-all duration-200" data-target="accessory-thumbs">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </button>
                            <button type="button" class="slide-next w-8 h-8 rounded-full bg-white shadow-sm hover:shadow-md border border-gray-200 flex items-center justify-center text-gray-500 hover:text-gray-700 transition-all duration-200" data-target="accessory-thumbs">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </button>
                        </div>
                        @endif
                    </div>
                    <div id="accessory-thumbs" class="relative overflow-hidden">
                        @php
                            $chunkedGallery = array_chunk($gallery, 4);
                            $totalPages = count($chunkedGallery);
                        @endphp
                        <div class="flex transition-transform duration-500 ease-in-out thumbnails-slider" data-slide-index="0" data-total-pages="{{ $totalPages }}">
                            @foreach($chunkedGallery as $pageIndex => $pageImages)
                            <div class="w-full flex-shrink-0">
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 px-1">
                                    @foreach($pageImages as $image)
                                    @php $thumb = $resolveImage($image, $accessory->name ?? ''); @endphp
                                    <div class="aspect-square w-full bg-white rounded-2xl shadow-lg overflow-hidden cursor-pointer hover:shadow-xl transition-all duration-300 thumbnail-image" 
                                                 data-image="{{ $thumb }}" data-lightbox-src="{{ $thumb }}">
                                        <img src="{{ $thumb }}" alt="{{ $accessory->name }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300" loading="lazy" decoding="async" width="300" height="300">
                    </div>
                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Product Info -->
            <div class="space-y-4 lg:space-y-6 lg:col-span-7 mt-4 lg:mt-0">
                <!-- Header -->
                <div class="space-y-4">
                    @if($accessory->brand)
                    <div class="flex items-center gap-2">
                    <div class="inline-flex items-center bg-blue-50 text-blue-700 px-4 py-2 rounded-full text-sm font-semibold">
                        <i class="fas fa-crown mr-2"></i>
                        {{ $accessory->brand }}
                        </div>
                        @if($accessory->category)
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('products.index', ['type' => 'accessory', 'acc_category' => $accessory->category]) }}" class="text-sm text-gray-700 hover:text-gray-900 hover:underline">{{ $accessory->category }}</a>
                        @endif
                    </div>
                    @endif
                    
                    <h1 class="text-4xl lg:text-5xl font-black text-gray-900 leading-tight">{{ $accessory->name }}</h1>
                    
                    @if($accessory->short_description)
                    <p class="text-xl text-gray-600 leading-relaxed">{{ $accessory->short_description }}</p>
                    @endif
                    
                    <!-- Rating -->
                    @php $avgInline = $accessory->approved_reviews_avg ?? 0; $accCount = $accessory->approved_reviews_count ?? $accessory->reviews()->where('is_approved', true)->count(); @endphp
                    <div id="rating-summary-inline" class="flex items-center space-x-4 {{ ($avgInline>0?'':'hidden') }}">
                        <div class="flex items-center space-x-1" id="rating-stars-inline" data-avg="{{ (float)$avgInline }}">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $avgInline)
                                    <i class="fas fa-star text-yellow-400 text-lg"></i>
                                @elseif($i - $avgInline < 1)
                                    <i class="fas fa-star-half-alt text-yellow-400 text-lg"></i>
                                @else
                                    <i class="far fa-star text-gray-300 text-lg"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-lg font-semibold text-gray-900"><span id="rating-avg-inline">{{ number_format((float)$avgInline, 1) }}</span></span>
                        <span class="text-gray-500 {{ ($accCount>0?'':'hidden') }}">(<span id="rating-count-inline">{{ number_format($accCount ?? 0) }}</span> đánh giá)</span>
                    </div>
                </div>
                
                <!-- Price + Inline Actions -->
                @php 
                    $hasDiscount = ($accessory->has_discount && ($accessory->discount_percentage ?? 0) > 0);
                    $baseOriginal = (int) ($accessory->base_price ?? 0);
                    $baseCurrent  = (int) ($accessory->current_price ?? $baseOriginal);
                    $discountPct  = (float) ($accessory->discount_percentage ?? 0);
                @endphp
                <div class="inline-block bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl p-4 lg:p-5 border border-blue-100">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-3">
                            <div class="text-2xl lg:text-3xl font-extrabold text-blue-700" aria-label="Giá">
                                <span id="dynamic-price"
                                      data-base-price="{{ (int) $baseCurrent }}"
                                      data-base-original="{{ (int) $baseOriginal }}"
                                      data-discount="{{ $hasDiscount ? $discountPct : 0 }}">{{ number_format($baseCurrent, 0, ',', '.') }}</span>₫
                            </div>
                            @if($hasDiscount)
                                <span class="text-sm lg:text-base text-gray-500 line-through">
                                    <span id="original-price">{{ number_format($baseOriginal, 0, ',', '.') }}</span>₫
                            </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-xs font-bold">-{{ rtrim(rtrim(number_format($discountPct, 1), '0'), '.') }}%</span>
                            @endif
                            {{-- Stock Badge --}}
                            <x-stock-badge :stock="$accessory->stock_quantity ?? 0" type="accessory" size="md" />
                        </div>
                    </div>
                </div>
                
                @if($accessory->is_available)
                    <!-- CTA row: full-width equal buttons -->
                <div class="mt-3 grid grid-cols-2 gap-2">
                    @php
                        $__inWishlistAccPage = \App\Helpers\WishlistHelper::isInWishlist('accessory', $accessory->id);
                    @endphp
                        <button type="button" class="action-btn action-ghost w-full js-wishlist-toggle {{ $__inWishlistAccPage ? 'in-wishlist' : 'not-in-wishlist' }}" aria-label="Yêu thích" title="Yêu thích" aria-pressed="{{ $__inWishlistAccPage ? 'true' : 'false' }}" data-item-type="accessory" data-item-id="{{ $accessory->id }}">
                        <i class="fa-heart {{ $__inWishlistAccPage ? 'fas' : 'far' }}"></i><span>Yêu thích</span>
                        </button>
                        <form action="{{ route('user.cart.add') }}" method="POST" class="w-full add-to-cart-form" data-item-type="accessory" data-item-id="{{ $accessory->id }}">
                            @csrf
                            <input type="hidden" name="item_type" value="accessory">
                            <input type="hidden" name="item_id" value="{{ $accessory->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="action-btn action-primary w-full" 
                                @if(($accessory->stock_quantity ?? 0) <= 0) disabled @endif>
                                <i class="fas fa-cart-plus"></i>
                                <span>{{ ($accessory->stock_quantity ?? 0) > 0 ? 'Thêm vào giỏ' : 'Hết hàng' }}</span>
                            </button>
                        </form>
                    </div>
                    @endif

                <!-- Additional Info Section -->
                <div class="mt-8 space-y-6">
                    <!-- Trust Badges -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-award text-yellow-600 mr-3"></i>
                            Cam kết chất lượng
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-certificate text-green-600"></i>
                </div>
                                <span class="text-xs text-gray-600">Chính hãng 100%</span>
                            </div>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-thumbs-up text-blue-600"></i>
                                </div>
                                <span class="text-xs text-gray-600">Chất lượng cao</span>
                            </div>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-star text-purple-600"></i>
                                </div>
                                <span class="text-xs text-gray-600">Đánh giá 5 sao</span>
                            </div>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-heart text-red-600"></i>
                                </div>
                                <span class="text-xs text-gray-600">Khách hàng tin tưởng</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabs Section -->
        <div class="mt-16">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button class="tab-button border-b-2 border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 text-lg font-semibold" data-tab="description">
                        <i class="fas fa-info-circle mr-2"></i>
                        Mô tả
                    </button>
                    <button class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 text-lg font-semibold" data-tab="specifications">
                        <i class="fas fa-cog mr-2"></i>
                        Thông số
                    </button>
                    <button class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 text-lg font-semibold" data-tab="compatibility">
                        <i class="fas fa-car mr-2"></i>
                        Tương thích
                    </button>
                    <button class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 text-lg font-semibold" data-tab="reviews">
                        <i class="fas fa-star mr-2"></i>
                        Đánh giá
                    </button>
                </nav>
            </div>
            
            <!-- Tab Content -->
            <div class="py-12">
                <!-- Description Tab -->
                <div id="description" class="tab-content">
                    <div class="prose prose-lg max-w-none">
                        <div class="bg-white rounded-3xl shadow-xl p-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">Mô tả chi tiết</h3>
                            <div class="text-gray-700 leading-relaxed">
                                {!! nl2br(e($accessory->description ?? 'Chưa có mô tả chi tiết cho sản phẩm này.')) !!}
                            </div>
                            
                            @if($accessory->features)
                            <div class="mt-8">
                                <h4 class="text-xl font-semibold text-gray-900 mb-4">Tính năng nổi bật</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach(json_decode($accessory->features, true) ?? [] as $feature => $value)
                                    <div class="flex items-center space-x-3 bg-gray-50 rounded-xl p-4">
                                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                        <span class="font-medium text-gray-900">{{ $feature }}: {{ $value }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Specifications Tab -->
                <div id="specifications" class="tab-content hidden">
                    <div class="bg-white rounded-3xl shadow-xl p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Thông số kỹ thuật</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <h4 class="text-xl font-semibold text-gray-900 mb-4">Thông tin cơ bản</h4>
                                <div class="space-y-4">
                                    @if($accessory->category)
                                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Danh mục:</span>
                                        <span class="font-semibold text-gray-900">
                                            @switch($accessory->category)
                                                @case('electronics')
                                                    Thiết bị điện tử
                                                    @break
                                                @case('interior')
                                                    Nội thất
                                                    @break
                                                @case('exterior')
                                                    Ngoại thất
                                                    @break
                                                @case('performance')
                                                    Hiệu suất
                                                    @break
                                                @case('safety')
                                                    An toàn
                                                    @break
                                                @case('maintenance')
                                                    Bảo dưỡng
                                                    @break
                                                @case('lighting')
                                                    Đèn chiếu sáng
                                                    @break
                                                @case('audio')
                                                    Âm thanh
                                                    @break
                                                @default
                                                    {{ $accessory->category }}
                                            @endswitch
                                        </span>
                                    </div>
                                    @endif
                                    @if($accessory->subcategory)
                                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Danh mục con:</span>
                                        <span class="font-semibold text-gray-900">
                                            @switch($accessory->subcategory)
                                                @case('camera')
                                                    Camera
                                                    @break
                                                @case('dash_cam')
                                                    Camera hành trình
                                                    @break
                                                @case('gps')
                                                    Định vị GPS
                                                    @break
                                                @case('speaker')
                                                    Loa
                                                    @break
                                                @case('seat_cover')
                                                    Bọc ghế
                                                    @break
                                                @case('floor_mat')
                                                    Thảm sàn
                                                    @break
                                                @case('steering_wheel')
                                                    Vô lăng
                                                    @break
                                                @case('body_kit')
                                                    Body kit
                                                    @break
                                                @case('spoiler')
                                                    Cánh gió
                                                    @break
                                                @case('rim')
                                                    Mâm xe
                                                    @break
                                                @case('tire')
                                                    Lốp xe
                                                    @break
                                                @case('headlight')
                                                    Đèn pha
                                                    @break
                                                @case('taillight')
                                                    Đèn hậu
                                                    @break
                                                @case('led_strip')
                                                    Dải LED
                                                    @break
                                                @case('engine_oil')
                                                    Dầu động cơ
                                                    @break
                                                @case('brake_pad')
                                                    Má phanh
                                                    @break
                                                @case('air_filter')
                                                    Lọc gió
                                                    @break
                                                @case('battery')
                                                    Ắc quy
                                                    @break
                                                @case('alarm')
                                                    Báo động
                                                    @break
                                                @case('sensor')
                                                    Cảm biến
                                                    @break
                                                @default
                                                    {{ $accessory->subcategory }}
                                            @endswitch
                                        </span>
                                    </div>
                                    @endif
                                    @if($accessory->brand)
                                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Thương hiệu:</span>
                                        <span class="font-semibold text-gray-900">{{ $accessory->brand }}</span>
                                    </div>
                                    @endif
                                    @if($accessory->model)
                                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Model:</span>
                                        <span class="font-semibold text-gray-900">{{ $accessory->model }}</span>
                                    </div>
                                    @endif
                                    @if($accessory->sku)
                                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">SKU:</span>
                                        <span class="font-semibold text-gray-900 font-mono">{{ $accessory->sku }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="space-y-6">
                                <h4 class="text-xl font-semibold text-gray-900 mb-4">Thông số vật lý</h4>
                                <div class="space-y-4">
                                    @if($accessory->weight)
                                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Trọng lượng:</span>
                                        <span class="font-semibold text-gray-900">{{ $accessory->weight }} kg</span>
                                    </div>
                                    @endif
                                    @if($accessory->dimensions)
                                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Kích thước:</span>
                                        <span class="font-semibold text-gray-900">{{ $accessory->dimensions }}</span>
                                    </div>
                                    @endif
                                    @if($accessory->material)
                                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Chất liệu:</span>
                                        <span class="font-semibold text-gray-900">{{ $accessory->material }}</span>
                                    </div>
                                    @endif
                                    @if($accessory->warranty_months)
                                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Bảo hành:</span>
                                        <span class="font-semibold text-gray-900">{{ $accessory->warranty_months }} tháng</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        @if($accessory->specifications)
                        <div class="mt-8">
                            <h4 class="text-xl font-semibold text-gray-900 mb-4">Thông số chi tiết</h4>
                            <div class="bg-gray-50 rounded-2xl p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach(json_decode($accessory->specifications, true) ?? [] as $spec => $value)
                                    <div class="bg-white rounded-xl p-4 shadow-sm">
                                        <div class="text-sm text-gray-500 font-medium mb-1">{{ $spec }}</div>
                                        <div class="text-lg font-semibold text-gray-900">{{ $value }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Compatibility Tab -->
                <div id="compatibility" class="tab-content hidden">
                    <div class="bg-white rounded-3xl shadow-xl p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Tương thích xe</h3>
                        
                        @if($accessory->compatible_car_brands || $accessory->compatible_car_models)
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            @if($accessory->compatible_car_brands)
                            <div>
                                <h4 class="text-xl font-semibold text-gray-900 mb-4">Thương hiệu tương thích</h4>
                                <div class="space-y-3">
                                    @foreach(json_decode($accessory->compatible_car_brands, true) ?? [] as $brand)
                                    <div class="flex items-center space-x-3 bg-blue-50 rounded-xl p-4">
                                        <i class="fas fa-car text-blue-500"></i>
                                        <span class="font-medium text-gray-900">{{ $brand }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            @if($accessory->compatible_car_models)
                            <div>
                                <h4 class="text-xl font-semibold text-gray-900 mb-4">Model tương thích</h4>
                                <div class="space-y-3">
                                    @foreach(json_decode($accessory->compatible_car_models, true) ?? [] as $model)
                                    <div class="flex items-center space-x-3 bg-green-50 rounded-xl p-4">
                                        <i class="fas fa-car-side text-green-500"></i>
                                        <span class="font-medium text-gray-900">{{ $model }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @else
                        <div class="text-center py-12">
                            <i class="fas fa-car text-gray-300 text-6xl mb-4"></i>
                            <p class="text-gray-500 text-lg">Chưa có thông tin tương thích xe</p>
                        </div>
                        @endif
                        
                        @if($accessory->installation_service_available)
                        <div class="mt-8 bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl p-6 border border-green-200">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-tools text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-semibold text-gray-900">Dịch vụ lắp đặt</h4>
                                    <p class="text-gray-600">Chúng tôi cung cấp dịch vụ lắp đặt chuyên nghiệp</p>
                                    @if($accessory->installation_fee)
                                    <p class="text-green-600 font-semibold mt-2">Phí lắp đặt: {{ number_format($accessory->installation_fee, 0, ',', '.') }}₫</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Reviews Tab with pagination & submit -->
                <div id="reviews" class="tab-content hidden">
                    <div class="bg-white rounded-3xl shadow-xl p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-bold text-gray-900">Đánh giá sản phẩm</h3>
                            @auth
                            <button type="button" id="open-review-form" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                                <i class="fas fa-plus"></i> Viết đánh giá
                            </button>
                            @endauth
                        </div>
                        
                        <div id="reviews-summary" class="mb-8">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                <!-- Rating Summary - Centered and Prominent -->
                                <div class="lg:col-span-1 text-center bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                                    @php $avgRating = $accessory->approved_reviews_avg ?? 0; @endphp
                                    <div id="reviews-avg" class="text-6xl font-black text-blue-600 mb-3">{{ number_format((float) $avgRating, 1) }}</div>
                                    <div id="reviews-stars" class="flex items-center justify-center gap-1 mb-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $avgRating)
                                                <i class="fas fa-star text-yellow-400 text-xl"></i>
                                            @elseif($i - $avgRating < 1)
                                                <i class="fas fa-star-half-alt text-yellow-400 text-xl"></i>
                                            @else
                                                <i class="far fa-star text-gray-300 text-xl"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    @php $approvedCount2 = $accessory->approved_reviews_count ?? $accessory->reviews()->where('is_approved', true)->count(); @endphp
                                    <div class="text-gray-700 font-medium text-lg">(<span id="reviews-count">{{ number_format($approvedCount2 ?? 0) }}</span> đánh giá)</div>
                                </div>
                                
                                <!-- Rating Distribution Chart - Enhanced Design -->
                                <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                        <i class="fas fa-chart-bar text-indigo-600"></i>
                                        Phân bố đánh giá
                                    </h4>
                                    <div class="space-y-3">
                                        @for($star = 5; $star >= 1; $star--)
                                            @php
                                                $starCount = \App\Models\Review::where('reviewable_type', 'App\\Models\\Accessory')
                                                    ->where('reviewable_id', $accessory->id)
                                                    ->where('is_approved', true)
                                                    ->where('rating', $star)
                                                    ->count();
                                                $percentage = $approvedCount2 > 0 ? ($starCount / $approvedCount2) * 100 : 0;
                                            @endphp
                                            <div class="flex items-center gap-4">
                                                <div class="flex items-center gap-2 w-20">
                                                    <span class="text-sm font-medium text-gray-700">{{ $star }}</span>
                                                    <i class="fas fa-star text-yellow-400"></i>
                                                </div>
                                                <div class="flex-1 bg-gray-100 rounded-full h-3 overflow-hidden">
                                                    <div class="bg-gradient-to-r from-yellow-400 to-orange-400 h-3 rounded-full transition-all duration-500 ease-out rating-bar" data-star="{{ $star }}" data-percentage="{{ $percentage }}" style="width:0%"></div>
                                                </div>
                                                <div class="w-16 text-right">
                                                    <span class="text-sm font-medium text-gray-700 review-star-count" data-star="{{ $star }}">{{ $starCount }}</span>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="reviews-list" class="space-y-6"></div>
                        <div id="reviews-pagination" class="mt-6 flex items-center justify-center gap-2"></div>

                        @auth
                        <!-- Modal viết đánh giá (ẩn mặc định) -->
                        <div id="review-modal" class="fixed inset-0 z-[10000] bg-black/60 hidden" style="display:none; align-items:center; justify-content:center;" role="dialog" aria-modal="true" aria-labelledby="review-modal-title">
                            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-5 relative">
                                <button type="button" class="absolute top-3 right-3 text-gray-400 hover:text-red-500" id="review-modal-close" aria-label="Đóng"><i class="fas fa-times text-lg"></i></button>
                                <h4 id="review-modal-title" class="text-xl font-bold text-gray-900 mb-4">Viết đánh giá</h4>
                                <div id="review-form-errors" class="hidden mb-3 p-3 rounded-lg bg-red-50 text-red-700 text-sm"></div>
                                <form id="review-form" class="space-y-3">
                                    <input type="hidden" name="reviewable_type" value="App\Models\Accessory">
                                    <input type="hidden" name="reviewable_id" value="{{ $accessory->id }}">
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-1">Chấm sao <span class="text-red-500">*</span></label>
                                        <div class="flex items-center gap-2">
                                            @for($i=1;$i<=5;$i++)
                                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                                <input type="radio" name="rating" value="{{ $i }}" class="accent-yellow-500">
                                                <i class="fas fa-star text-yellow-400"></i>
                                            </label>
                                            @endfor
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-1">Tiêu đề (tuỳ chọn)</label>
                                        <input type="text" name="title" class="w-full border rounded-lg px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-1">Nội dung <span class="text-red-500">*</span></label>
                                        <textarea name="comment" class="w-full border rounded-lg px-3 py-2" rows="4" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."></textarea>
                                    </div>
                                    <div class="flex items-center justify-end gap-2 pt-2">
                                        <button type="button" id="review-cancel" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Hủy</button>
                                        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Gửi đánh giá</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endauth
                        @guest
                        <div class="mt-6 text-sm text-gray-600">Vui lòng đăng nhập để thêm đánh giá.</div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Products -->
        @if(isset($relatedAccessories) && $relatedAccessories->count() > 0)
        <div class="mt-20 pb-20">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-gray-900 mb-4">Sản phẩm liên quan</h2>
                <p class="text-xl text-gray-600">Khám phá thêm các phụ kiện tương tự</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedAccessories as $related)
                    @include('components.accessory-card', ['accessory' => $related])
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image gallery functionality
    const accessoryMainImage = document.getElementById('main-image');
    
    // Thumbnail click handlers - removed duplicate

    // Thumbnail Gallery Slider Functionality
    (function(){
        const slideButtons = document.querySelectorAll('.slide-prev, .slide-next');
        
        function getVisibleCount() {
            const width = window.innerWidth;
            if (width < 640) return 2; // mobile: 2 columns
            if (width < 768) return 3; // tablet: 3 columns  
            return 4; // desktop: 4 columns
        }
        
        function updateSlider(container, currentPage = 0) {
            const slider = container.querySelector('.thumbnails-slider');
            if (!slider) return;
            
            const totalPages = parseInt(slider.getAttribute('data-total-pages') || '1');
            const translateX = -currentPage * 100;
            
            slider.style.transform = `translateX(${translateX}%)`;
            slider.setAttribute('data-current-page', currentPage);
            
            // Update button states
            const prevBtn = container.parentElement.querySelector('.slide-prev');
            const nextBtn = container.parentElement.querySelector('.slide-next');
            
            if (prevBtn) {
                prevBtn.style.opacity = currentPage <= 0 ? '0.5' : '1';
                prevBtn.disabled = currentPage <= 0;
            }
            
            if (nextBtn) {
                nextBtn.style.opacity = currentPage >= totalPages - 1 ? '0.5' : '1';
                nextBtn.disabled = currentPage >= totalPages - 1;
            }
        }
        
        slideButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const container = document.getElementById(targetId);
                if (!container) return;
                
                const slider = container.querySelector('.thumbnails-slider');
                if (!slider) return;
                
                const totalPages = parseInt(slider.getAttribute('data-total-pages') || '1');
                let currentPage = parseInt(slider.getAttribute('data-current-page') || '0');
                
                if (this.classList.contains('slide-next')) {
                    currentPage = Math.min(currentPage + 1, totalPages - 1);
                } else {
                    currentPage = Math.max(currentPage - 1, 0);
                }
                
                updateSlider(container, currentPage);
            });
        });
        
        // Initialize all sliders
        function initializeSliders() {
            slideButtons.forEach(button => {
                const targetId = button.getAttribute('data-target');
                const container = document.getElementById(targetId);
                if (!container) return;
                
                updateSlider(container, 0);
            });
        }
        
        // Initialize on load
        initializeSliders();
        
        // Re-initialize on window resize
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(initializeSliders, 250);
        });
    })();

    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active classes from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Add active classes to clicked button
            this.classList.remove('border-transparent', 'text-gray-500');
            this.classList.add('border-blue-500', 'text-blue-600');
            
            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            // Show target tab content
            const targetContent = document.getElementById(targetTab);
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }
        });
    });
    
    // Thumbnail gallery
    const thumbnails = document.querySelectorAll('.thumbnail-image');
    
    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', () => {
            const newSrc = thumb.getAttribute('data-image');
            if (newSrc && accessoryMainImage && newSrc !== accessoryMainImage.src) {
                // Simple fade effect
                accessoryMainImage.style.opacity = '0.3';
                
                setTimeout(() => {
                    accessoryMainImage.src = newSrc;
                    accessoryMainImage.setAttribute('data-lightbox-src', newSrc);
                    accessoryMainImage.style.opacity = '1';
                }, 150);
            }
            
            // Update active thumbnail
            thumbnails.forEach(t => t.classList.remove('ring-2', 'ring-blue-500'));
            thumb.classList.add('ring-2', 'ring-blue-500');
        });
    });
    
    // Add to cart form handling - simplified like car-variant page
    document.querySelectorAll('form.add-to-cart-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            // Let the form submit normally - no preventDefault
            // The backend will handle the response and redirect
        });
    });
    
    // Simple reviews functionality
    const reviewModal = document.getElementById('review-modal');
    const openReviewFormBtn = document.getElementById('open-review-form');
    const reviewModalClose = document.getElementById('review-modal-close');
    const reviewCancelBtn = document.getElementById('review-cancel');
    const reviewForm = document.getElementById('review-form');
    
    // Open review modal
    if (openReviewFormBtn && reviewModal) {
        openReviewFormBtn.addEventListener('click', () => {
            reviewModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    }
    
    // Close review modal
    if (reviewModalClose && reviewModal) {
        reviewModalClose.addEventListener('click', () => {
            reviewModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            if (reviewForm) reviewForm.reset();
        });
    }
    
    if (reviewCancelBtn && reviewModal) {
        reviewCancelBtn.addEventListener('click', () => {
            reviewModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            if (reviewForm) reviewForm.reset();
        });
    }
    
    // Close modal when clicking outside
    if (reviewModal) {
        reviewModal.addEventListener('click', (e) => {
            if (e.target === reviewModal) {
                reviewModal.style.display = 'none';
                document.body.style.overflow = 'auto';
                if (reviewForm) reviewForm.reset();
            }
        });
    }
    
    // Submit review is handled inside initAccessoryReviews to enable AJAX reload
    
    // Load and render reviews (like car-variant)
    (function initAccessoryReviews(){
        const listEl = document.getElementById('reviews-list');
        const pagerEl = document.getElementById('reviews-pagination');
        const openFormBtn = document.getElementById('open-review-form');
        const reviewModal = document.getElementById('review-modal');
        const reviewCloseBtn = document.getElementById('review-modal-close');
        const reviewCancelBtn = document.getElementById('review-cancel');
        const formEl = document.getElementById('review-form');
        const errorBox = document.getElementById('review-form-errors');
        if (!listEl || !pagerEl) return;
        let currentPage = 1;
        const accessoryId = Number('{{ $accessory->id }}');
        const type = 'App%5CModels%5CAccessory';
        function renderPager(meta){
            pagerEl.innerHTML = '';
            const current_page = meta && meta.current_page ? meta.current_page : 1;
            const last_page = meta && meta.last_page ? meta.last_page : 1;
            if (!current_page || !last_page || last_page <= 1) return;
            const makeBtn = (p, label = p, active=false) => `<button data-page="${p}" class="px-3 py-1 rounded ${active?'bg-indigo-600 text-white':'bg-gray-100 hover:bg-gray-200'}">${label}</button>`;
            const parts = [];
            if (current_page>1) parts.push(makeBtn(current_page-1,'‹'));
            for(let p=Math.max(1,current_page-1); p<=Math.min(last_page,current_page+1); p++){ parts.push(makeBtn(p,String(p),p===current_page)); }
            if (current_page<last_page) parts.push(makeBtn(current_page+1,'›'));
            pagerEl.innerHTML = parts.join('');
        }
        function renderList(data){
            const items = (data && data.data) ? data.data : [];
            if (items.length===0){ listEl.innerHTML = '<div class="text-center text-gray-500">Chưa có đánh giá nào</div>'; return; }
            listEl.innerHTML = items.map(rv=>{
                const stars = Array.from({length:5},(_,i)=> i<rv.rating?'<i class="fas fa-star text-yellow-400"></i>':'<i class="far fa-star text-gray-300"></i>').join('');
                const name = ((rv.user && rv.user.name) ? rv.user.name : 'Khách hàng');
                const time = (new Date(rv.created_at)).toLocaleDateString('vi-VN');
                const safeComment = (rv.comment||'').replace(/</g,'&lt;');
                const safeTitle = (rv.title||'').replace(/</g,'&lt;');
                const titleHtml = safeTitle ? `<div class=\"font-medium text-gray-900 mb-1\">${safeTitle}</div>` : '';
                return `<div class=\"border-b border-gray-100 pb-4\"><div class=\"flex items-center gap-3 mb-1\"><div class=\"w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center\"><i class=\"fas fa-user text-gray-500\"></i></div><div><div class=\"font-semibold text-gray-900\">${name}</div><div class=\"text-sm\">${stars}</div></div><div class=\"ml-auto text-xs text-gray-500\">${time}</div></div>${titleHtml}<div class=\"text-gray-700\">${safeComment}</div></div>`;
            }).join('');
            renderPager(data);
        }
        function updateInlineSummary(){
            fetch(`{{ route('reviews.summary') }}?reviewable_type=${type}&reviewable_id=${accessoryId}`,{headers:{'X-Requested-With':'XMLHttpRequest'}})
                .then(r=>r.json()).then(s=>{
                    if (!s || !s.success) return;
                    const avg = parseFloat(s.approved_avg||0);
                    const count = parseInt(s.approved_count||0,10);
                    const wrap = document.getElementById('rating-summary-inline');
                    const avgEl = document.getElementById('rating-avg-inline');
                    const cntEl = document.getElementById('rating-count-inline');
                    const starsWrap = document.getElementById('rating-stars-inline');
                    if (!wrap || !avgEl || !cntEl || !starsWrap) return;
                    avgEl.textContent = avg.toFixed(1);
                    cntEl.textContent = new Intl.NumberFormat('vi-VN').format(count);
                    wrap.classList.toggle('hidden', avg <= 0);
                    // Re-render stars
                    const full = Math.floor(avg);
                    const hasHalf = (avg - full) >= 0.5 ? 1 : 0;
                    let html = '';
                    for (let i=1;i<=5;i++){
                        if (i<=full) html += '<i class="fas fa-star text-yellow-400 text-lg"></i>';
                        else if (i===full+1 && hasHalf) html += '<i class="fas fa-star-half-alt text-yellow-400 text-lg"></i>';
                        else html += '<i class="far fa-star text-gray-300 text-lg"></i>';
                    }
                    starsWrap.innerHTML = html;

                    // Update reviews tab summary (big numbers and distribution)
                    const avgBig = document.getElementById('reviews-avg');
                    const starsBig = document.getElementById('reviews-stars');
                    const countBig = document.getElementById('reviews-count');
                    if (avgBig) avgBig.textContent = avg.toFixed(1);
                    if (countBig) countBig.textContent = new Intl.NumberFormat('vi-VN').format(count);
                    if (starsBig){
                        let htmlBig='';
                        for (let i=1;i<=5;i++){
                            if (i<=full) htmlBig += '<i class="fas fa-star text-yellow-400 text-xl"></i>';
                            else if (i===full+1 && hasHalf) htmlBig += '<i class="fas fa-star-half-alt text-yellow-400 text-xl"></i>';
                            else htmlBig += '<i class="far fa-star text-gray-300 text-xl"></i>';
                        }
                        starsBig.innerHTML = htmlBig;
                    }
                    // Update distribution bars/counts if provided
                    if (s.distribution){
                        for (let star=1; star<=5; star++){
                            const cnt = s.distribution[star] || 0;
                            const pct = count>0 ? (cnt / count) * 100 : 0;
                            const bar = document.querySelector(`.rating-bar[data-star="${star}"]`);
                            const label = document.querySelector(`.review-star-count[data-star="${star}"]`);
                            if (bar){ bar.setAttribute('data-percentage', pct.toFixed(2)); bar.style.width = pct + '%'; }
                            if (label){ label.textContent = String(cnt); }
                        }
                    }
                }).catch(()=>{});
        }
        function load(page){
            fetch(`{{ route('reviews.get') }}?reviewable_type=${type}&reviewable_id=${accessoryId}&page=${page}`,{headers:{'X-Requested-With':'XMLHttpRequest'}})
                .then(function(r){ return r.json(); })
                .then(function(d){ currentPage = d.current_page||1; renderList(d); })
                .catch(function(){ listEl.innerHTML='<div class="text-center text-gray-500">Không tải được đánh giá</div>'; });
        }
        pagerEl.addEventListener('click', function(e){ const btn=e.target.closest('button[data-page]'); if(!btn) return; load(parseInt(btn.getAttribute('data-page'),10)); });
        // Open/close modal like car-variant
        function openReview(){ if (!reviewModal) return; reviewModal.style.display='flex'; reviewModal.classList.remove('hidden'); document.body.style.overflow='hidden'; if (errorBox){ errorBox.classList.add('hidden'); errorBox.innerHTML=''; } }
        function closeReview(){ if (!reviewModal) return; reviewModal.classList.add('hidden'); reviewModal.style.display='none'; document.body.style.overflow='auto'; }
        if (openFormBtn && reviewModal){ openFormBtn.addEventListener('click', openReview); }
        if (reviewCloseBtn){ reviewCloseBtn.addEventListener('click', closeReview); }
        if (reviewCancelBtn){ reviewCancelBtn.addEventListener('click', closeReview); }
        document.addEventListener('click', function(e){ const m = e.target.closest('#review-modal'); if (!m) return; if (e.target === m) closeReview(); });
        document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeReview(); });

        // AJAX submit with Vietnamese validation
        if (formEl){
            formEl.addEventListener('submit', function(ev){ 
                ev.preventDefault(); 
                
                
                // Client-side validation with toast messages - show rating error first
                // Check rating first
                const ratingInputs = formEl.querySelectorAll('input[name="rating"]');
                const ratingChecked = Array.from(ratingInputs).some(input => input.checked);
                if (!ratingChecked) {
                    if (typeof showMessage === 'function') {
                        showMessage('Vui lòng chọn số sao đánh giá', 'error');
                    }
                    return; // Stop submission if rating not selected
                }
                
                // Check comment after rating is valid
                const commentInput = formEl.querySelector('textarea[name="comment"]');
                if (!commentInput.value.trim()) {
                    if (typeof showMessage === 'function') {
                        showMessage('Vui lòng nhập nội dung đánh giá', 'error');
                    }
                    return; // Stop submission if comment empty
                } else if (commentInput.value.trim().length < 10) {
                    if (typeof showMessage === 'function') {
                        showMessage('Nội dung đánh giá phải có ít nhất 10 ký tự', 'error');
                    }
                    return; // Stop submission if comment too short
                }
                
                const fd=new FormData(formEl);
                if (errorBox){ errorBox.classList.add('hidden'); errorBox.innerHTML=''; }
                fetch(`{{ route('reviews.store') }}`,{method:'POST', headers:{'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}, body:fd})
                .then(async function(r){
                    const ct = r.headers.get('content-type')||''; const data = ct.includes('application/json') ? await r.json() : {};
                    if (r.ok && data && data.success){
                        closeReview(); formEl.reset(); load(1);
                        if (typeof showMessage==='function'){ showMessage(data.message || 'Gửi đánh giá thành công','success'); }
                        updateInlineSummary();
                    } else {
                        const errs = (data && (data.errors || data.message)) ? data : null;
                        if (errs) {
                            // Convert English validation messages to Vietnamese and show via toast
                            const vietnameseErrors = [];
                            if (errs.errors) {
                                Object.entries(errs.errors).forEach(([field, messages]) => {
                                    messages.forEach(msg => {
                                        let vietnameseMsg = msg;
                                        // Convert common validation messages
                                        if (msg.includes('rating') && msg.includes('required')) {
                                            vietnameseMsg = 'Vui lòng chọn số sao đánh giá';
                                        } else if (msg.includes('comment') && msg.includes('required')) {
                                            vietnameseMsg = 'Vui lòng nhập nội dung đánh giá';
                                        } else if (msg.includes('comment') && msg.includes('min')) {
                                            vietnameseMsg = 'Nội dung đánh giá phải có ít nhất 10 ký tự';
                                        } else if (msg.includes('title') && msg.includes('max')) {
                                            vietnameseMsg = 'Tiêu đề không được vượt quá 255 ký tự';
                                        }
                                        vietnameseErrors.push(vietnameseMsg);
                                    });
                                });
                            } else if (errs.message) {
                                vietnameseErrors.push(errs.message);
                            }
                            
                            // Show errors via toast instead of error box
                            if (typeof showMessage === 'function') {
                                vietnameseErrors.forEach(msg => showMessage(msg, 'error'));
                            }
                        }
                        if (typeof showMessage==='function'){ showMessage('Gửi đánh giá thất bại','error'); }
                    }
                })
                .catch(function(){ if (typeof showMessage==='function'){ showMessage('Gửi đánh giá thất bại','error'); } });
            });
        }
        load(1);
        updateInlineSummary();
    })();

    // Animate rating bars
    setTimeout(() => {
        const ratingBars = document.querySelectorAll('.rating-bar');
        ratingBars.forEach(bar => {
            const percentage = bar.getAttribute('data-percentage');
            bar.style.width = percentage + '%';
        });
    }, 500);

    // Simple Lightbox (no external lib)
    function openLightbox(src){
        const overlay = document.createElement('div');
        overlay.className = 'fixed inset-0 z-[10000] bg-black/90 flex items-center justify-center p-4 cursor-zoom-out';
        overlay.setAttribute('role','dialog');
        overlay.innerHTML = `<img src="${src}" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl" alt="Lightbox Image">`;
        overlay.onclick = () => document.body.removeChild(overlay);
        overlay.onkeydown = (e) => { if(e.key === 'Escape') document.body.removeChild(overlay); };
        document.body.appendChild(overlay);
        overlay.focus();
    }

    // Add lightbox click handlers
    const accessoryMainImageLightbox = document.getElementById('main-image');
    if (accessoryMainImageLightbox) {
        accessoryMainImageLightbox.addEventListener('click', function(){
            const src = this.getAttribute('data-lightbox-src') || this.src;
            openLightbox(src);
        });
    }
    
    // Add double-click lightbox for thumbnails
    document.querySelectorAll('.thumbnail-image').forEach(thumb => {
        thumb.addEventListener('dblclick', function(){
            const src = this.getAttribute('data-lightbox-src') || this.getAttribute('data-image');
            if (src) openLightbox(src);
        });
    });
});
</script>
@endsection
