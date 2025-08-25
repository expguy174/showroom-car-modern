@extends('layouts.app')

@section('title', $variant->name . ' - AutoLux')

@push('head')
@php
    $mainImage = $variant->image_url 
        ?? optional(optional($variant->images)->first())->image_url 
        ?? 'https://via.placeholder.com/1200x800/cccccc/ffffff?text=No+Image';
    $brandName = optional(optional($variant->carModel)->carBrand)->name;
    $modelName = optional($variant->carModel)->name;
    $hasDiscount = ($variant->has_discount ?? false) && ($variant->discount_percentage ?? 0) > 0;
    $offerPrice = $hasDiscount ? (int) round($variant->price * (1 - ($variant->discount_percentage/100))) : (int) ($variant->price ?? 0);
    $availability = $variant->is_available ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock';
    $avgForSchema = $variant->approved_reviews_avg ?? $variant->average_rating ?? 0;
    $aggregateRating = ($avgForSchema ?? 0) > 0 ? [
        '@type' => 'AggregateRating',
        'ratingValue' => number_format((float)$avgForSchema, 1),
        'reviewCount' => (int) (\App\Models\Review::where('reviewable_type', \App\Models\CarVariant::class)->where('reviewable_id', $variant->id)->where('is_approved', true)->count())
    ] : null;
    $productJson = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => trim(($brandName ? $brandName.' ' : '').($modelName ? $modelName.' ' : '').($variant->name ?? '')),
        'brand' => $brandName ?: 'AutoLux',
        'model' => $modelName ?: null,
        'image' => $mainImage,
        'description' => $variant->description ?: 'Phiên bản xe chính hãng tại AutoLux Showroom',
        'sku' => (string) ($variant->id ?? ''),
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
<div id="car-variant-page" class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50">
    
    <!-- Breadcrumb -->
    <div class="bg-white border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
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
                            <a href="{{ route('car-brands.show', $variant->carModel->carBrand->id ?? 1) }}" class="text-gray-500 hover:text-blue-600 transition-colors">{{ $variant->carModel->carBrand->name ?? 'Xe hơi' }}</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('car-models.show', $variant->carModel->id ?? 1) }}" class="text-gray-500 hover:text-blue-600 transition-colors">{{ $variant->carModel->name ?? 'Model' }}</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-gray-900 font-medium">{{ $variant->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Image Gallery -->
            <div class="space-y-5 lg:col-span-5">
                <!-- Main Image -->
                <div class="relative group">
                    <div class="aspect-[3/2] bg-white rounded-3xl shadow-2xl overflow-hidden">
                        @php 
                            $fallback = 'https://via.placeholder.com/1200x800/cccccc/ffffff?text=Khong+anh';
                            $main = $variant->image_url ?? (optional(optional($variant->images)->first())->image_url) ?? $fallback;
                        @endphp
                        <img src="{{ $main }}"
                         id="main-image"
                             class="w-full h-full object-cover cursor-zoom-in group-hover:scale-105 transition-transform duration-700"
                             alt="{{ $variant->name }}"
                             data-lightbox-src="{{ $main }}">
                    </div>
                    
                    <!-- Badges -->
                    <div class="absolute top-6 left-6 flex flex-col gap-3">
                        @if($variant->is_featured)
                        <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-gray-900 px-4 py-2 rounded-full text-sm font-bold shadow-xl">
                            <i class="fas fa-star mr-2"></i> Nổi bật
                        </div>
                        @endif
                        @if($variant->is_bestseller)
                        <div class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-full text-sm font-bold shadow-xl">
                            <i class="fas fa-fire mr-2"></i> Bán chạy
                        </div>
                        @endif
                        @if($variant->is_new_arrival)
                        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-full text-sm font-bold shadow-xl">
                            <i class="fas fa-sparkles mr-2"></i> Mới
                        </div>
                        @endif
                    </div>
                    

                    
                    
                </div>
                
                <!-- Thumbnail Gallery -->
                <div id="variant-thumbs" class="sm:grid sm:grid-cols-4 sm:gap-3 flex gap-3 overflow-x-auto pb-2 thumbnails-container scrollbar-hide">
                    @if($variant->colors->count() > 0)
                    @foreach($variant->colors as $color)
                        <div class="aspect-square min-w-[72px] sm:min-w-0 bg-white rounded-2xl shadow-lg overflow-hidden cursor-pointer hover:shadow-xl transition-all duration-300 thumbnail-image" 
                             data-image="{{ $color->image_url }}" data-lightbox-src="{{ $color->image_url }}">
                            <img src="{{ $color->image_url }}" alt="{{ $color->color_name }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300" loading="lazy">
                        </div>
                    @endforeach
                    @endif
                    
                    @if($variant->images && $variant->images->where('is_main', false)->count() > 0)
                        @foreach($variant->images->where('is_main', false)->take(4 - $variant->colors->count()) as $image)
                        <div class="aspect-square min-w-[72px] sm:min-w-0 bg-white rounded-2xl shadow-lg overflow-hidden cursor-pointer hover:shadow-xl transition-all duration-300 thumbnail-image" 
                             data-image="{{ $image->image_url }}" data-lightbox-src="{{ $image->image_url }}">
                            <img src="{{ $image->image_url }}" alt="{{ $variant->name }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300" loading="lazy">
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="space-y-6 lg:col-span-7">
                <!-- Header -->
                <div class="space-y-4">
                    @if($variant->carModel->carBrand)
                    <div class="flex items-center gap-2">
                    <div class="inline-flex items-center bg-blue-50 text-blue-700 px-4 py-2 rounded-full text-sm font-semibold">
                        <i class="fas fa-crown mr-2"></i>
                        {{ $variant->carModel->carBrand->name }}
                        </div>
                        <a href="{{ route('car-brands.show', $variant->carModel->carBrand->id) }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">Xem hãng</a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('car-models.show', $variant->carModel->id) }}" class="text-sm text-gray-700 hover:text-gray-900 hover:underline">Xem dòng</a>
                    </div>
                    @endif
                    
                    <h1 class="text-4xl lg:text-5xl font-black text-gray-900 leading-tight">{{ $variant->name }}</h1>
                    
                    @if($variant->carModel->name)
                    <p class="text-xl text-gray-600 leading-relaxed">{{ $variant->carModel->name }}</p>
                    @endif
                    @if(!empty($variant->short_description))
                    <p class="text-base text-gray-600 leading-relaxed">{{ $variant->short_description }}</p>
                    @endif
                    @php 
                        $countryRaw = trim((string)optional(optional($variant->carModel)->carBrand)->country);
                        $countryMap = [
                            'Germany' => 'Đức', 'DE' => 'Đức', 'Deutschland' => 'Đức',
                            'Japan' => 'Nhật Bản', 'JP' => 'Nhật Bản', 'Nippon' => 'Nhật Bản',
                            'United States' => 'Mỹ', 'United States of America' => 'Mỹ', 'USA' => 'Mỹ', 'US' => 'Mỹ', 'America' => 'Mỹ',
                            'United Kingdom' => 'Anh', 'UK' => 'Anh', 'Great Britain' => 'Anh', 'GB' => 'Anh', 'England' => 'Anh',
                            'Italy' => 'Ý', 'IT' => 'Ý', 'Italia' => 'Ý',
                            'France' => 'Pháp', 'FR' => 'Pháp',
                            'South Korea' => 'Hàn Quốc', 'Republic of Korea' => 'Hàn Quốc', 'Korea' => 'Hàn Quốc', 'KR' => 'Hàn Quốc',
                            'China' => 'Trung Quốc', 'CN' => 'Trung Quốc',
                            'Sweden' => 'Thụy Điển', 'SE' => 'Thụy Điển',
                            'Spain' => 'Tây Ban Nha', 'ES' => 'Tây Ban Nha',
                            'India' => 'Ấn Độ', 'IN' => 'Ấn Độ',
                            'Vietnam' => 'Việt Nam', 'Viet Nam' => 'Việt Nam', 'VN' => 'Việt Nam'
                        ];
                        $countryVi = $countryMap[$countryRaw] ?? $countryRaw;
                    @endphp
                    <div class="flex flex-wrap items-center gap-2 mt-2">
                        @if(!empty($countryVi))
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-gray-100 text-gray-700 text-xs">
                            <i class="fas fa-globe-asia mr-1 text-gray-400"></i>{{ $countryVi }}
                        </span>
                        @endif
                        @if(!empty($variant->fuel_type))
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs">
                            <i class="fas fa-gas-pump mr-1"></i>{{ Str::of($variant->fuel_type)->lower()->replace('gasoline','xăng')->replace('petrol','xăng')->replace('diesel','dầu')->replace('electric','điện')->replace('hybrid','hybrid')->title() }}
                        </span>
                        @endif
                        @if(!empty($variant->transmission))
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs">
                            <i class="fas fa-cog mr-1"></i>{{ Str::of($variant->transmission)->lower()->replace('automatic','tự động')->replace('manual','số sàn')->replace('cvt','CVT')->upper() }}
                        </span>
                        @endif
                        @if(!empty($variant->power))
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs">
                            <i class="fas fa-tachometer-alt mr-1"></i>{{ $variant->power }}
                        </span>
                        @endif
                    </div>
                    
                    <!-- Rating -->
                    @php $avgInline = isset($approvedAvg) ? $approvedAvg : ($variant->approved_reviews_avg ?? $variant->average_rating ?? 0); @endphp
                    <div id="rating-summary-inline" class="flex items-center space-x-4 {{ ($avgInline>0?'':'hidden') }}">
                        <div id="rating-stars-inline" class="flex items-center space-x-1" data-avg="{{ (float)$avgInline }}">
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
                        @php $approvedCount = isset($approvedCount) ? $approvedCount : ($variant->approved_reviews_count ?? $variant->approvedReviews()->count()); @endphp
                        <span class="text-gray-500 {{ (($approvedCount ?? 0)>0?'':'hidden') }}">(<span id="rating-count-inline">{{ number_format($approvedCount ?? 0) }}</span> đánh giá)</span>
                        <div class="flex flex-wrap items-center gap-2 pt-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 text-gray-700 text-xs"><i class="fas fa-receipt mr-1"></i>Đã gồm VAT</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-green-100 text-green-700 text-xs"><i class="fas fa-truck mr-1"></i>Giao hàng miễn phí</span>
                        </div>
                    </div>
                </div>

                

                <!-- Color Options (moved before price) -->
                @if($variant->colors->count() > 0)
                <div class="space-y-3">
                    <h3 class="text-base font-semibold text-gray-900">Chọn màu sắc</h3>
                    <div id="selected-color-display" class="text-xs text-gray-700"></div>
                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                        @foreach($variant->colors as $color)
                            <button type="button"
                                class="color-option flex flex-col items-center p-2.5 border border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1 js-color-option"
                                    data-color-id="{{ $color->id }}"
                                    data-color-name="{{ $color->color_name }}"
                                    data-image-url="{{ $color->image_url }}"
                                    data-hex="{{ \App\Helpers\ColorHelper::getColorHex($color->color_name) }}"
                                    data-price-adjustment="{{ (int) ($color->price_adjustment ?? 0) }}">
                            <div class="relative w-6 h-6 sm:w-7 sm:h-7 rounded-full mb-1.5" data-bg-hex="{{ \App\Helpers\ColorHelper::getColorHex($color->color_name) }}" data-color-name="{{ $color->color_name }}">
                                <i class="fas fa-check text-white text-[10px] absolute inset-0 m-auto w-3 h-3 flex items-center justify-center hidden"></i>
                            </div>
                            <span class="text-xs text-gray-700 font-medium truncate max-w-[90px]">{{ $color->color_name }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Price + Inline Actions -->
                @php 
                    $hasDiscount = ($variant->has_discount && ($variant->discount_percentage ?? 0) > 0);
                    $baseOriginal = (int) ($variant->price ?? 0);
                    $baseCurrent  = $hasDiscount
                        ? (int) round($baseOriginal * (1 - ($variant->discount_percentage/100)))
                        : $baseOriginal;
                    $discountPct  = (float) ($variant->discount_percentage ?? 0);
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
                            @if($variant->is_available)
                            <div class="inline-flex items-center px-3 py-1.5 rounded-full bg-green-50 text-green-700 text-sm font-semibold" aria-live="polite">
                                <i class="fas fa-check-circle mr-2"></i> Còn hàng
                            </div>
                            @else
                            <div class="inline-flex items-center px-3 py-1.5 rounded-full bg-red-50 text-red-700 text-sm font-semibold" aria-live="polite">
                                <i class="fas fa-times-circle mr-2"></i> Hết hàng
                            </div>
                            @endif
                        </div>
                        @if($variant->is_available)
                        <form action="{{ route('user.cart.add') }}" method="POST" class="add-to-cart-form flex items-center gap-2" data-item-type="car_variant" data-item-id="{{ $variant->id }}">
                            @csrf
                            <input type="hidden" name="item_type" value="car_variant">
                            <input type="hidden" name="item_id" value="{{ $variant->id }}">
                            <input type="hidden" name="color_id" value="" id="selected-color-id">
                            <input type="hidden" name="quantity" value="1">
                            
                        </form>
                        @endif
                    </div>
                </div>

                    @if($variant->is_available)
                <!-- CTA row: full-width equal buttons -->
                <div class="mt-3 grid grid-cols-2 gap-2">
                    <button type="button" class="action-btn action-ghost w-full js-wishlist-toggle" aria-label="Yêu thích" title="Yêu thích" data-item-type="car_variant" data-item-id="{{ $variant->id }}">
                        <i class="fas fa-heart"></i><span>Yêu thích</span>
                    </button>
                    <form action="{{ route('user.cart.add') }}" method="POST" class="w-full add-to-cart-form" data-item-type="car_variant" data-item-id="{{ $variant->id }}">
                        @csrf
                        <input type="hidden" name="item_type" value="car_variant">
                        <input type="hidden" name="item_id" value="{{ $variant->id }}">
                        <input type="hidden" name="color_id" value="" id="selected-color-id">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="action-btn action-primary w-full">
                            <i class="fas fa-cart-plus"></i><span>Thêm vào giỏ</span>
                            </button>
                    </form>
                </div>
                @endif
                <!-- Trust badges / Quick Info -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 pt-6 border-t border-gray-200">
                    <div class="text-center p-4 bg-white rounded-xl border">
                        <i class="fas fa-shipping-fast text-2xl text-emerald-600 mb-2"></i>
                        <div class="text-sm font-semibold">Giao hàng miễn phí</div>
                        <div class="text-xs text-gray-500">Trong 3-5 ngày</div>
                    </div>
                    <div class="text-center p-4 bg-white rounded-xl border">
                        <i class="fas fa-shield-alt text-2xl text-indigo-600 mb-2"></i>
                        <div class="text-sm font-semibold">Bảo hành {{ $variant->warranty_years ?? 12 }} năm</div>
                        <div class="text-xs text-gray-500">Chính hãng</div>
                    </div>
                    <div class="text-center p-4 bg-white rounded-xl border">
                        <i class="fas fa-tools text-2xl text-amber-600 mb-2"></i>
                        <div class="text-sm font-semibold">Bảo dưỡng định kỳ</div>
                        <div class="text-xs text-gray-500">Ưu đãi khách hàng mới</div>
                    </div>
                    <div class="text-center p-4 bg-white rounded-xl border">
                        <i class="fas fa-hand-holding-usd text-2xl text-rose-600 mb-2"></i>
                        <div class="text-sm font-semibold">Hỗ trợ tài chính</div>
                        <div class="text-xs text-gray-500">Lãi suất ưu đãi</div>
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
                    @if(($variant->options ?? collect())->count() > 0)
                    <button class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 text-lg font-semibold" data-tab="options">
                        <i class="fas fa-puzzle-piece mr-2"></i>
                        Tùy chọn
                    </button>
                    @endif
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
                                @php $desc = $variant->description ?? 'Chưa có mô tả chi tiết cho phiên bản này.'; @endphp
                                <div id="description-content" class="relative line-clamp-6" data-expanded="false">
                                    {!! nl2br(e($desc)) !!}
                                </div>
                                @if(($variant->description))
                                <button id="toggle-description" type="button" class="mt-3 inline-flex items-center text-indigo-700 font-semibold hover:underline text-sm">
                                    Xem thêm
                                </button>
                                @endif
                            </div>
                            @php $featList = ($variant->featuresRelation ?? collect())->filter(function($f){ return (bool)($f->is_included ?? true); })->take(12); @endphp
                            @if($featList->count() > 0)
                            <div class="mt-8">
                                <h4 class="text-xl font-semibold text-gray-900 mb-4">Tính năng nổi bật</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($featList as $f)
                                    <div class="flex items-center gap-2 bg-gradient-to-r from-gray-50 to-white border rounded-xl p-3">
                                        <i class="fas fa-check-circle text-emerald-600"></i>
                                        <span class="font-medium text-gray-900">{{ $f->feature_name ?? 'Tính năng' }}</span>
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
                                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Tên phiên bản:</span>
                                        <span class="font-semibold text-gray-900">{{ $variant->name }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Dòng xe:</span>
                                        <span class="font-semibold text-gray-900">{{ $variant->carModel->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Hãng xe:</span>
                                        <span class="font-semibold text-gray-900">{{ $variant->carModel->carBrand->name ?? 'N/A' }}</span>
                                    </div>
                                    @if(!empty($variant->sku))
                                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">SKU:</span>
                                        <span class="font-semibold text-gray-900">{{ $variant->sku }}</span>
                                    </div>
                                    @endif
                                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Trạng thái:</span>
                                        <span class="font-semibold text-green-600">{{ $variant->is_available ? 'Còn hàng' : 'Hết hàng' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Tồn kho:</span>
                                        <span class="font-semibold text-gray-900">{{ number_format((int)($variant->effective_stock_quantity ?? $variant->stock_quantity ?? 0), 0, ',', '.') }}</span>
                                    </div>
                                    @if(!empty($variant->warranty_years))
                                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Bảo hành:</span>
                                        <span class="font-semibold text-gray-900">{{ $variant->warranty_years }} năm</span>
                                    </div>
                                    @endif
                                    @if(!empty($variant->fuel_consumption) && (float)$variant->fuel_consumption > 0)
                                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Tiêu thụ nhiên liệu (kết hợp):</span>
                                        <span class="font-semibold text-gray-900">{{ is_numeric($variant->fuel_consumption) ? number_format((float)$variant->fuel_consumption, 1, ',', '.') . ' L/100km' : $variant->fuel_consumption }}</span>
                                    </div>
                                    @endif
                                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Lượt xem:</span>
                                        <span class="font-semibold text-gray-900">{{ number_format((int)($variant->view_count ?? 0), 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <h4 class="text-xl font-semibold text-gray-900 mb-4">Thông số chi tiết</h4>
                                <!-- Tabs header will be rendered below after grouped specs are built -->
                                @php
                                    $groupedSpecs = [
                                        'performance' => [], 'battery' => [], 'dimensions' => [], 'capacity' => [], 'safety' => [], 'warranty' => [],
                                    ];
                                    $seenKeys = [];
                                    foreach (($variant->specifications ?? []) as $spec) {
                                        $groupKey = \App\Helpers\SpecHelper::groupForCategory($spec->category ?? '');
                                        if (!$groupKey) { continue; }
                                        if (empty($spec->spec_value)) { continue; }
                                        $specName = (string) $spec->spec_name;
                                        $label = \App\Helpers\SpecHelper::labelFor($specName);
                                        $value = \App\Helpers\SpecHelper::formatValue($spec->spec_value, $spec->unit, $specName);
                                        if ($value === '') { continue; }
                                        $normKey = $groupKey + '|' + strtolower(trim(str_replace(['-',' '],'_', $specName)));
                                        if (isset($seenKeys[$normKey])) { continue; }
                                        $seenKeys[$normKey] = true;
                                        $groupedSpecs[$groupKey][] = [
                                            'label' => $label,
                                            'value' => $value,
                                            'important' => (bool) $spec->is_important,
                                            'order' => (int) ($spec->sort_order ?? 0),
                                        ];
                                    }
                                    // Ensure curated essentials if missing, including more fields
                                    $ensure = [
                                        'performance' => [
                                            ['engine_type', $variant->getSpecValue('engine_type','engine') ?? $variant->getSpecValue('engine_type')],
                                            ['engine_size', $variant->getSpecValue('engine_size','engine') ?? $variant->engine_size],
                                            ['engine_displacement', $variant->getSpecValue('engine_displacement','engine') ?? $variant->getSpecValue('engine_displacement')],
                                            ['cylinders', $variant->getSpecValue('cylinders','engine') ?? $variant->getSpecValue('cylinders')],
                                            ['power_output', $variant->power_output ?? $variant->getSpecValue('power')],
                                            ['torque', $variant->getSpecValue('torque','performance') ?? $variant->torque],
                                            ['transmission', $variant->transmission],
                                            ['drivetrain', $variant->drivetrain],
                                            ['fuel_type', $variant->fuel_type],
                                            ['consumption_combined', $variant->getSpecValue('consumption_combined','fuel') ?? $variant->getSpecValue('fuel_consumption','fuel') ?? $variant->fuel_consumption],
                                            ['top_speed', $variant->getSpecValue('top_speed','performance') ?? $variant->getSpecValue('max_speed','performance')],
                                        ],
                                        'battery' => [
                                            ['battery_capacity', $variant->getSpecValue('battery_capacity','battery') ?? $variant->getSpecValue('battery_capacity')],
                                            ['battery_range', $variant->getSpecValue('battery_range','battery') ?? $variant->getSpecValue('battery_range')],
                                            ['charging_time', $variant->getSpecValue('charging_time','battery') ?? $variant->getSpecValue('charging_time')],
                                        ],
                                        'dimensions' => [
                                            ['length', $variant->getSpecValue('length','dimensions') ?? $variant->getSpecValue('length')],
                                            ['width', $variant->getSpecValue('width','dimensions') ?? $variant->getSpecValue('width')],
                                            ['height', $variant->getSpecValue('height','dimensions') ?? $variant->getSpecValue('height')],
                                            ['wheelbase', $variant->getSpecValue('wheelbase','dimensions') ?? $variant->getSpecValue('wheelbase')],
                                            ['ground_clearance', $variant->getSpecValue('ground_clearance','dimensions') ?? $variant->getSpecValue('ground_clearance')],
                                            ['track_width_front', $variant->getSpecValue('track_width_front','dimensions')],
                                            ['track_width_rear', $variant->getSpecValue('track_width_rear','dimensions')],
                                            ['turning_radius', $variant->getSpecValue('turning_radius','dimensions')],
                                        ],
                                        'capacity' => [
                                            ['fuel_tank_capacity', $variant->getSpecValue('fuel_tank_capacity','capacity') ?? $variant->getSpecValue('fuel_tank_capacity')],
                                            ['cargo_volume', $variant->getSpecValue('cargo_volume','capacity') ?? $variant->getSpecValue('cargo_volume')],
                                            ['trunk_volume', $variant->getSpecValue('trunk_volume','capacity') ?? $variant->getSpecValue('trunk_volume')],
                                            ['payload', $variant->getSpecValue('payload','capacity') ?? $variant->getSpecValue('payload')],
                                            ['towing_capacity', $variant->getSpecValue('towing_capacity','capacity') ?? $variant->getSpecValue('towing_capacity')],
                                        ],
                                        'safety' => [
                                            ['airbag_count', $variant->getSpecValue('airbag_count','safety') ?? $variant->airbag_count],
                                            ['abs', $variant->getSpecValue('abs','safety') ?? ($variant->has_abs ? 'Standard' : null)],
                                            ['ebd', $variant->getSpecValue('ebd','safety')],
                                            ['esc', $variant->getSpecValue('esc','safety') ?? ($variant->has_esp ? 'Standard' : null)],
                                            ['traction_control', $variant->getSpecValue('traction_control','safety') ?? ($variant->has_traction_control ? 'Standard' : null)],
                                            ['parking_sensors', $variant->getSpecValue('parking_sensors','safety') ?? ($variant->has_parking_sensors ? 'Có' : null)],
                                            ['rear_camera', $variant->getSpecValue('rear_camera','safety') ?? ($variant->has_rear_camera ? 'Có' : null)],
                                            ['camera_360', $variant->getSpecValue('camera_360','safety') ?? ($variant->has_360_camera ? 'Có' : null)],
                                            ['front_brake', $variant->getSpecValue('front_brake','safety')],
                                            ['rear_brake', $variant->getSpecValue('rear_brake','safety')],
                                            ['front_suspension', $variant->getSpecValue('front_suspension','safety')],
                                            ['rear_suspension', $variant->getSpecValue('rear_suspension','safety')],
                                            ['steering_type', $variant->getSpecValue('steering_type','safety')],
                                            ['tire_size', $variant->getSpecValue('tire_size','safety')],
                                            ['wheel_size', $variant->getSpecValue('wheel_size','safety')],
                                        ],
                                        'warranty' => [
                                            ['warranty_years', $variant->getSpecValue('warranty_years','warranty')],
                                            ['warranty_km', $variant->getSpecValue('warranty_km','warranty')],
                                            ['warranty_details', $variant->getSpecValue('warranty_details','warranty')],
                                        ],
                                    ];
                                    foreach ($ensure as $gk => $pairs) {
                                        foreach ($pairs as $pair) {
                                            [$name, $val] = $pair;
                                            if (!$val) { continue; }
                                            $specName = strtolower($name);
                                            $normKey = $gk . '|' . $specName;
                                            if (isset($seenKeys[$normKey])) { continue; }
                                            $seenKeys[$normKey] = true;
                                            $label = \App\Helpers\SpecHelper::labelFor($specName);
                                            $groupedSpecs[$gk][] = [
                                                'label' => $label,
                                                'value' => \App\Helpers\SpecHelper::formatValue($val, null, $specName),
                                                'important' => false,
                                                'order' => 999,
                                            ];
                                        }
                                    }
                                    foreach ($groupedSpecs as $gk => $rows) {
                                        usort($rows, function($a, $b) {
                                            if ($a['important'] !== $b['important']) { return $a['important'] ? -1 : 1; }
                                            if ($a['order'] !== $b['order']) { return $a['order'] <=> $b['order']; }
                                            return strcmp($a['label'], $b['label']);
                                        });
                                        $groupedSpecs[$gk] = $rows;
                                    }
                                @endphp
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @php
                                        // Compose tab list: predefined order first, then any extra groups from seeder
                                        $predefinedOrder = \App\Helpers\SpecHelper::groupOrder();
                                        $groupIcons = \App\Helpers\SpecHelper::groupIcons();
                                        $tabs = [];
                                        // Add predefined groups
                                        foreach ($predefinedOrder as $gk) { if (array_key_exists($gk, $groupedSpecs)) $tabs[] = $gk; }
                                        // Add any remaining seeder-defined groups
                                        foreach (array_keys($groupedSpecs) as $gk) { if (!in_array($gk, $tabs, true)) $tabs[] = $gk; }
                                        // Determine active group: first with data
                                        $activeGroup = null;
                                        foreach ($tabs as $gk) { $rows = $groupedSpecs[$gk] ?? []; $cnt=0; foreach($rows as $r){ if(!empty($r['value'])) $cnt++; } if ($cnt>0){ $activeGroup=$gk; break; } }
                                        if ($activeGroup === null) { $activeGroup = $tabs[0] ?? 'performance'; }
                                    @endphp
                                    @foreach($tabs as $groupKey)
                                        @php $rows = $groupedSpecs[$groupKey] ?? []; $visibleCount = 0; foreach($rows as $r){ if(!empty($r['value'])) $visibleCount++; } @endphp
                                        @if($visibleCount > 0)
                                            @php $isActive = $activeGroup === $groupKey; $label = \App\Helpers\SpecHelper::labelForCategory($groupKey); $icon = $groupIcons[$groupKey] ?? 'fas fa-list'; @endphp
                                            <button type="button" class="spec-tab-button px-3 py-1.5 rounded-lg text-sm font-medium flex items-center gap-2 {{ $isActive ? 'bg-indigo-600 text-white' : 'bg-gray-100 hover:bg-gray-200' }}" data-spec="{{ $groupKey }}">
                                                <i class="{{ $icon }}"></i> {{ $label }}
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                                @foreach($groupedSpecs as $groupKey => $rows)
                                    @php $visibleCount = 0; foreach($rows as $r){ if(!empty($r['value'])) $visibleCount++; } @endphp
                                    <div id="spec-{{ $groupKey }}" class="spec-group {{ $groupKey !== $activeGroup ? 'hidden' : '' }}">
                                        <div class="grid grid-cols-1 gap-3 spec-rows">
                                            @php $rowIndex=0; @endphp
                                            @foreach($rows as $row)
                                                @php $value = $row['value'] ?? null; @endphp
                                                @if(!empty($value))
                                                <div class="flex justify-between items-center py-3 border-b border-gray-100 spec-row {{ $rowIndex >= 4 ? 'hidden' : '' }}" data-row-index="{{ $rowIndex }}">
                                                    <span class="text-gray-600 font-medium">{{ $row['label'] }}:</span>
                                                    <span class="font-semibold text-gray-900">{{ $value }}</span>
                                                </div>
                                                @php $rowIndex++; @endphp
                                                @endif
                                            @endforeach
                                        </div>
                                        @if($visibleCount > 4)
                                        <button type="button" class="mt-2 inline-flex items-center text-indigo-700 font-semibold text-sm spec-toggle" data-target="spec-{{ $groupKey }}" data-collapsed="true">
                                            Xem thêm
                                        </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Options Tab -->
                @php
                    $opts = ($variant->options ?? collect());
                    $hasOpts = $opts->count() > 0;
                @endphp
                @if($hasOpts)
                <div id="options" class="tab-content hidden">
                    <div class="bg-white rounded-3xl shadow-xl p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Tùy chọn & Gói nâng cấp</h3>
                        @php
                            $byCat = [];
                            foreach ($opts as $o) {
                                if (isset($o->is_active) && !$o->is_active) continue;
                                $cat = $o->category ? (string) Str::of($o->category)->lower()->replace(['-','_'],' ')->title() : 'Khác';
                                $byCat[$cat] = $byCat[$cat] ?? [];
                                $byCat[$cat][] = $o;
                            }
                        @endphp
                        <div class="space-y-6">
                            @foreach($byCat as $cat => $list)
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-3">{{ $cat }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($list as $o)
                                    <div class="flex items-start gap-3 p-4 rounded-xl border border-gray-200 bg-white">
                                        @php $icon = $o->icon_url ?? null; $img = $o->image_url ?? null; @endphp
                                        @if($icon)
                                            <img src="{{ $icon }}" alt="icon" class="w-8 h-8 object-contain mt-0.5" loading="lazy">
                                        @elseif($img)
                                            <img src="{{ $img }}" alt="{{ $o->option_name }}" class="w-12 h-12 object-cover rounded-lg" loading="lazy">
                                        @else
                                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center mt-0.5"><i class="fas fa-cube text-gray-400"></i></div>
                                        @endif
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2">
                                                <div class="font-semibold text-gray-900 truncate">{{ $o->option_name }}</div>
                                                @if($o->is_included)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs">Bao gồm</span>
                                                @endif
                                                @if($o->is_popular)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 text-xs">Phổ biến</span>
                                                @endif
                                                @if($o->is_recommended)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-xs">Khuyên dùng</span>
                                                @endif
                                            </div>
                                            @if(!empty($o->description))
                                            <div class="text-sm text-gray-600 line-clamp-2">{{ $o->description }}</div>
                                            @endif
                                            <div class="mt-2 flex items-center gap-3">
                                                @if(!is_null($o->price) && $o->price > 0)
                                                    <div class="text-indigo-700 font-bold">{{ number_format($o->price, 0, ',', '.') }}₫</div>
                                                    @if(!empty($o->package_price) && $o->package_price > $o->price)
                                                        <div class="text-xs text-gray-500 line-through">{{ number_format($o->package_price, 0, ',', '.') }}₫</div>
                                                    @endif
                                                @else
                                                    <div class="text-gray-700 font-medium">Miễn phí</div>
                                                @endif
                                                @if(!empty($o->stock_quantity))
                                                    <span class="text-xs text-gray-500">Kho: {{ number_format((int)$o->stock_quantity, 0, ',', '.') }}</span>
                                                @endif
                                            </div>
                                        </div>
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
                                    @php $avgRating = isset($approvedAvg) ? $approvedAvg : ($variant->approved_reviews_avg ?? $variant->average_rating ?? 0); @endphp
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
                                    @php $approvedCount2 = isset($approvedCount) ? $approvedCount : ($variant->approved_reviews_count ?? $variant->approvedReviews()->count()); @endphp
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
                                                $starCount = \App\Models\Review::where('reviewable_type', 'App\\Models\\CarVariant')
                                                    ->where('reviewable_id', $variant->id)
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
                                    <input type="hidden" name="reviewable_type" value="App\Models\CarVariant">
                                    <input type="hidden" name="reviewable_id" value="{{ $variant->id }}">
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-1">Chấm sao</label>
                                        <div class="flex items-center gap-2">
                                            @for($i=1;$i<=5;$i++)
                                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                                <input type="radio" name="rating" value="{{ $i }}" class="accent-yellow-500" required>
                                                <i class="fas fa-star text-yellow-400"></i>
                                            </label>
                                            @endfor
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-1">Tiêu đề (tuỳ chọn)</label>
                                        <input type="text" name="title" class="w-full border rounded-lg px-3 py-2" maxlength="255">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-1">Nội dung</label>
                                        <textarea name="comment" class="w-full border rounded-lg px-3 py-2" rows="4" required minlength="10"></textarea>
                                    </div>
                                    <div class="flex items-center justify-end gap-2 pt-2">
                                        <button type="button" id="review-cancel" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Hủy</button>
                                        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Gửi đánh giá</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @else
                        <div class="mt-6 text-sm text-gray-600">Vui lòng đăng nhập để thêm đánh giá.</div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if(isset($relatedVariants) && $relatedVariants->count() > 0)
        <div class="mt-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-gray-900 mb-4">Sản phẩm liên quan</h2>
                    <p class="text-lg text-gray-600">Những mẫu xe tương tự bạn có thể quan tâm</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedVariants as $related)
                    @include('components.variant-card', ['variant' => $related])
            @endforeach
                </div>
                    </div>
                </div>
            @endif
            
            <!-- Final CTA -->
            <section class="mt-16 bg-gradient-to-br from-indigo-900 via-purple-900 to-slate-900 text-white overflow-hidden">
                <div class="container mx-auto px-6 lg:px-8 py-10 text-center">
                    <h3 class="text-3xl font-bold mb-3">Sẵn sàng trải nghiệm {{ $variant->name }}?</h3>
                    <p class="text-indigo-200 mb-6 max-w-2xl mx-auto">Liên hệ đội ngũ tư vấn hoặc đặt lịch lái thử để cảm nhận trực tiếp.</p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('test_drives.index') }}" class="inline-flex items-center px-6 py-3 rounded-xl bg-white text-slate-900 font-semibold hover:bg-indigo-50">
                            <i class="fas fa-steering-wheel mr-2"></i> Đặt lịch lái thử
                        </a>
                        <a href="{{ route('contact') }}" class="inline-flex items-center px-6 py-3 rounded-xl border border-white/30 text-white hover:bg-white/10">
                            <i class="fas fa-phone mr-2"></i> Liên hệ tư vấn
                        </a>
                    </div>
                </div>
            </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetTab = button.getAttribute('data-tab');
            
            // Remove active state from all tabs
            tabButtons.forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            
    // Hide all tab contents
            tabContents.forEach(content => {
        content.classList.add('hidden');
    });
    
            // Activate clicked tab
            button.classList.remove('border-transparent', 'text-gray-500');
            button.classList.add('border-blue-500', 'text-blue-600');
            
            // Show target content
            document.getElementById(targetTab).classList.remove('hidden');
    });
});

    // Thumbnail gallery
    const thumbnails = document.querySelectorAll('.thumbnail-image');
    const mainImage = document.getElementById('main-image');
    
    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', () => {
            const newSrc = thumb.getAttribute('data-image');
            mainImage.src = newSrc;
            
            // Update active thumbnail
            thumbnails.forEach(t => t.classList.remove('ring-2', 'ring-blue-500'));
            thumb.classList.add('ring-2', 'ring-blue-500');
        });
    });

    // Fallback: if main image fails or has no size, use first thumbnail image
    try {
        function fallbackToFirstThumb(){
            const first = document.querySelector('#variant-thumbs .thumbnail-image img');
            const src = first ? first.getAttribute('src') : null;
            if (src && mainImage) {
                mainImage.src = src;
                mainImage.setAttribute('data-lightbox-src', src);
            }
        }
        if (mainImage) {
            mainImage.addEventListener('error', function(){ fallbackToFirstThumb(); }, { once: true });
            setTimeout(function(){
                try { if (!(mainImage.naturalWidth > 0 && mainImage.naturalHeight > 0)) fallbackToFirstThumb(); } catch(e) {}
            }, 400);
        }
    } catch(e) {}

    // Simple Lightbox (no external lib)
    function openLightbox(src){
        const overlay = document.createElement('div');
        overlay.className = 'fixed inset-0 z-[10000] bg-black/90 flex items-center justify-center p-4 cursor-zoom-out';
        overlay.setAttribute('role','dialog');
        overlay.setAttribute('aria-modal','true');
        const img = document.createElement('img');
        img.src = src;
        img.alt = mainImage?.alt || 'Ảnh xe';
        img.className = 'max-w-full max-h-[90vh] rounded-xl shadow-2xl';
        overlay.appendChild(img);
        document.body.appendChild(overlay);
        function close(){ overlay.remove(); }
        overlay.addEventListener('click', close);
        document.addEventListener('keydown', function esc(e){ if(e.key==='Escape'){ close(); document.removeEventListener('keydown', esc); } });
    }
    if (mainImage) {
        mainImage.addEventListener('click', function(){
            const src = this.getAttribute('data-lightbox-src') || this.src;
            openLightbox(src);
        });
    }
    thumbnails.forEach(thumb => {
        thumb.addEventListener('dblclick', function(){
            const src = this.getAttribute('data-lightbox-src') || this.getAttribute('data-image');
            if (src) openLightbox(src);
        });
    });
    // Description toggle
    const desc = document.getElementById('description-content');
    const toggleBtn = document.getElementById('toggle-description');
    if (desc && toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            const expanded = desc.getAttribute('data-expanded') === 'true';
            if (expanded) {
                desc.classList.add('line-clamp-6');
                desc.setAttribute('data-expanded','false');
                toggleBtn.textContent = 'Xem thêm';
            } else {
                desc.classList.remove('line-clamp-6');
                desc.setAttribute('data-expanded','true');
                toggleBtn.textContent = 'Thu gọn';
            }
        });
    }

    // Spec mini-tabs
    const specTabs = document.querySelectorAll('.spec-tab-button');
    const specGroups = document.querySelectorAll('.spec-group');
    specTabs.forEach(function(btn){
        btn.addEventListener('click', function(){
            const key = btn.getAttribute('data-spec');
            // active state
            specTabs.forEach(function(b){ b.classList.remove('bg-indigo-600','text-white'); b.classList.add('bg-gray-100'); });
            btn.classList.add('bg-indigo-600','text-white'); btn.classList.remove('bg-gray-100');
            // toggle groups
            specGroups.forEach(function(g){ g.classList.add('hidden'); });
            const active = document.getElementById('spec-'+key);
            if (active) active.classList.remove('hidden');
        });
    });

    // Spec expand/collapse per group
    document.querySelectorAll('.spec-toggle').forEach(function(tg){
        tg.addEventListener('click', function(){
            const targetId = tg.getAttribute('data-target');
            const container = document.getElementById(targetId);
            if (!container) return;
            const collapsed = tg.getAttribute('data-collapsed') === 'true';
            container.querySelectorAll('.spec-row').forEach(function(row){
                const idx = parseInt(row.getAttribute('data-row-index')||'0',10);
                if (idx >= 4) row.classList.toggle('hidden');
            });
            tg.textContent = collapsed ? 'Thu gọn' : 'Xem thêm';
            tg.setAttribute('data-collapsed', collapsed ? 'false' : 'true');
        });
    });
    
    // Color selection
    const colorOptions = document.querySelectorAll('.js-color-option');
    const colorInput = document.getElementById('selected-color-id');
    const selectedColorText = document.getElementById('selected-color-display');
    const priceEl = document.getElementById('dynamic-price');
    
    colorOptions.forEach(option => {
        option.addEventListener('click', () => {
            const colorId = option.getAttribute('data-color-id');
            const colorName = option.getAttribute('data-color-name') || '';
            const colorHex = option.getAttribute('data-hex') || '';
            const colorImg = option.getAttribute('data-image-url') || '';
            const priceAdj = parseInt(option.getAttribute('data-price-adjustment') || '0', 10);
            
            // Remove active state from all options
            colorOptions.forEach(opt => {
                opt.classList.remove('border-blue-500', 'bg-blue-50');
                opt.classList.add('border-gray-200');
                const check = opt.querySelector('.fa-check');
                if (check) check.classList.add('hidden');
            });
            
            // Activate selected option
            option.classList.remove('border-gray-200');
            option.classList.add('border-blue-500', 'bg-blue-50');
            const selectedCheck = option.querySelector('.fa-check');
            if (selectedCheck) selectedCheck.classList.remove('hidden');
            
            // Update hidden input
            colorInput.value = colorId;
            // Update UI to show selected color
            if (selectedColorText) {
                const dot = `<span class=\"inline-block w-3.5 h-3.5 rounded-full align-middle mr-2\" style=\"background:${colorHex}\"></span>`;
                selectedColorText.innerHTML = `Màu đã chọn: ${dot}<span class=\"font-medium\">${colorName}</span>`;
            }
            // If color has its own image, swap main image immediately
            if (colorImg && mainImage) {
                mainImage.src = colorImg;
                mainImage.setAttribute('data-lightbox-src', colorImg);
            }
            // Update price with color adjustment (client-side)
            if (priceEl) {
                const baseCurrent = parseInt(priceEl.getAttribute('data-base-price') || '0', 10);
                const baseOriginal = parseInt(priceEl.getAttribute('data-base-original') || String(baseCurrent), 10);
                const origEl = document.getElementById('original-price');
                const discountPct = parseFloat(priceEl.getAttribute('data-discount') || '0');
                // New current price includes color adjustment
                const newCurrent = baseCurrent + priceAdj;
                priceEl.textContent = newCurrent.toLocaleString('vi-VN');
                // Keep original price from baseOriginal (not adjusted by color)
                if (origEl && discountPct > 0) {
                    origEl.textContent = baseOriginal.toLocaleString('vi-VN');
                }
            }
        });
    });

    // Set default selected color text on initial load if nothing selected
    (function initSelectedColorText(){
        if (!selectedColorText) return;
        const anySelected = Array.from(colorOptions).some(opt => opt.classList.contains('border-blue-500'));
        const hasValue = !!(colorInput && colorInput.value);
        if (!anySelected && !hasValue) {
            selectedColorText.innerHTML = 'Màu đã chọn: <span class="italic text-gray-500">Chưa chọn</span>';
        }
    })();
    
    // Animate rating bars after DOM is ready for CSS validator compatibility
    document.querySelectorAll('.rating-bar').forEach(function(el){
        var pct = parseFloat(el.getAttribute('data-percentage')||'0');
        if (isNaN(pct)) pct = 0; pct = Math.max(0, Math.min(100, pct));
        requestAnimationFrame(function(){ el.style.width = pct + '%'; });
    });

    // Buy now button
    // Load reviews with pagination
    (function initReviews(){
        const listEl = document.getElementById('reviews-list');
        const pagerEl = document.getElementById('reviews-pagination');
        const openFormBtn = document.getElementById('open-review-form');
        const reviewModal = document.getElementById('review-modal');
        const reviewCloseBtn = document.getElementById('review-modal-close');
        const reviewCancelBtn = document.getElementById('review-cancel');
        const formEl = document.getElementById('review-form');
        const errorBox = document.getElementById('review-form-errors');
        if (!listEl || !pagerEl) return;
        let currentPage = 1; const perPage = 10; const reviewableId = Number('{{ $variant->id }}');
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
                return `<div class=\"border-b border-gray-100 pb-4\"><div class=\"flex items-center gap-3 mb-1\"><div class=\"w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center\"><i class=\"fas fa-user text-gray-500\"></i></div><div><div class=\"font-semibold text-gray-900\">${name}</div><div class=\"text-sm\">${stars}</div></div><div class=\"ml-auto text-xs text-gray-500\">${time}</div></div><div class=\"text-gray-700\">${(rv.comment||'').replace(/</g,'&lt;')}</div></div>`;
            }).join('');
            renderPager(data);
        }
        function updateInlineSummary(){
            const type = 'App%5CModels%5CCarVariant';
            fetch(`{{ route('reviews.summary') }}?reviewable_type=${type}&reviewable_id=${reviewableId}`,{headers:{'X-Requested-With':'XMLHttpRequest'}})
                .then(r=>r.json()).then(s=>{
                    if (!s || !s.success) return;
                    const avg = parseFloat(s.approved_avg||0);
                    const count = parseInt(s.approved_count||0,10);
                    // Header near price
                    const wrap = document.getElementById('rating-summary-inline');
                    const avgEl = document.getElementById('rating-avg-inline');
                    const cntEl = document.getElementById('rating-count-inline');
                    const starsWrap = document.getElementById('rating-stars-inline');
                    if (wrap && avgEl && cntEl && starsWrap){
                        avgEl.textContent = avg.toFixed(1);
                        cntEl.textContent = new Intl.NumberFormat('vi-VN').format(count);
                        wrap.classList.toggle('hidden', avg <= 0);
                        const full = Math.floor(avg); const hasHalf = (avg - full) >= 0.5 ? 1 : 0; let html='';
                        for (let i=1;i<=5;i++){
                            if (i<=full) html += '<i class="fas fa-star text-yellow-400 text-lg"></i>';
                            else if (i===full+1 && hasHalf) html += '<i class="fas fa-star-half-alt text-yellow-400 text-lg"></i>';
                            else html += '<i class="far fa-star text-gray-300 text-lg"></i>';
                        }
                        starsWrap.innerHTML = html;
                    }
                    // Reviews tab large summary
                    const avgBig = document.getElementById('reviews-avg');
                    const starsBig = document.getElementById('reviews-stars');
                    const countBig = document.getElementById('reviews-count');
                    if (avgBig) avgBig.textContent = avg.toFixed(1);
                    if (countBig) countBig.textContent = new Intl.NumberFormat('vi-VN').format(count);
                    if (starsBig){
                        const full = Math.floor(avg); const hasHalf = (avg - full) >= 0.5 ? 1 : 0; let html='';
                        for (let i=1;i<=5;i++){
                            if (i<=full) html += '<i class="fas fa-star text-yellow-400 text-xl"></i>';
                            else if (i===full+1 && hasHalf) html += '<i class="fas fa-star-half-alt text-yellow-400 text-xl"></i>';
                            else html += '<i class="far fa-star text-gray-300 text-xl"></i>';
                        }
                        starsBig.innerHTML = html;
                    }
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
            const type = 'App%5CModels%5CCarVariant';
            fetch(`{{ route('reviews.get') }}?reviewable_type=${type}&reviewable_id=${reviewableId}&page=${page}`,{headers:{'X-Requested-With':'XMLHttpRequest'}})
                .then(function(r){ return r.json(); }).then(function(d){ currentPage = d.current_page||1; renderList(d); })
                .catch(function(){ listEl.innerHTML='<div class="text-center text-gray-500">Không tải được đánh giá</div>'; });
        }
        pagerEl.addEventListener('click', function(e){ const btn=e.target.closest('button[data-page]'); if(!btn) return; load(parseInt(btn.getAttribute('data-page'),10)); });
        function openReviewModal(){
            if (!reviewModal) return;
            reviewModal.style.display = 'flex';
            reviewModal.classList.remove('hidden');
            if (errorBox){ errorBox.classList.add('hidden'); errorBox.innerHTML=''; }
            setTimeout(()=>{ const first = reviewModal.querySelector('input[name="rating"]'); if(first) first.focus(); }, 0);
        }
        function closeReviewModal(){
            if (!reviewModal) return;
            reviewModal.classList.add('hidden');
            reviewModal.style.display = 'none';
        }
        if (openFormBtn && reviewModal){ openFormBtn.addEventListener('click', openReviewModal); }
        if (reviewCloseBtn){ reviewCloseBtn.addEventListener('click', closeReviewModal); }
        if (reviewCancelBtn){ reviewCancelBtn.addEventListener('click', closeReviewModal); }
        // Close on backdrop click
        document.addEventListener('click', function(e){ const m = e.target.closest('#review-modal'); if (!m) return; if (e.target === m) closeReviewModal(); });
        // Close on ESC
        document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeReviewModal(); });
        if (formEl){
            formEl.addEventListener('submit', function(ev){ ev.preventDefault(); const fd=new FormData(formEl);
                if (errorBox){ errorBox.classList.add('hidden'); errorBox.innerHTML=''; }
                fetch(`{{ route('reviews.store') }}`,{method:'POST', headers:{'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}, body:fd})
                .then(async function(r){
                    const ct = r.headers.get('content-type')||''; const data = ct.includes('application/json') ? await r.json() : {};
                    if (r.ok && data && data.success){
                        closeReviewModal(); formEl.reset(); load(1); updateInlineSummary();
                        if (typeof showMessage==='function'){ showMessage(data.message || 'Gửi đánh giá thành công','success'); }
                    } else {
                        // Try to extract validation errors
                        const errs = (data && (data.errors || data.message)) ? data : null;
                        if (errs && errorBox){
                            let html = '';
                            if (errs.errors){
                                const list = Object.values(errs.errors).flat();
                                html = `<ul class="list-disc list-inside">${list.map(m=>`<li>${m}</li>`).join('')}</ul>`;
                            } else if (errs.message){
                                html = `<div>${errs.message}</div>`;
                            }
                            if (html){ errorBox.innerHTML = html; errorBox.classList.remove('hidden'); }
                        }
                        if (typeof showMessage==='function'){ showMessage('Gửi đánh giá thất bại','error'); }
                    }
                })
                .catch(function(){ if (typeof showMessage==='function'){ showMessage('Gửi đánh giá thất bại','error'); } });
            });
        }
        load(1); updateInlineSummary();
    })();
});
</script>

<script>
// Áp màu cho các phần tử có data-bg-hex và đặt title từ tên màu
(function(){
  document.querySelectorAll('[data-bg-hex]').forEach(function(el){
    var hex = el.getAttribute('data-bg-hex');
    if (hex) { el.style.backgroundColor = hex; }
    var cname = el.getAttribute('data-color-name');
    if (cname && !el.getAttribute('title')) { el.setAttribute('title', cname); }
  });
})();
</script>
@endsection