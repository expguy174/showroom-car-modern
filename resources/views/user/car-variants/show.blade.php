@extends('layouts.app')

@section('title', $variant->name . ' - AutoLux')

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
    // Pick main image by type priority: gallery main -> first gallery -> first exterior -> first interior
    $imagesGrouped = optional($variant->images)->groupBy('image_type') ?? collect();
    $gallery = $imagesGrouped->get('gallery', collect());
    $exterior = $imagesGrouped->get('exterior', collect());
    $interior = $imagesGrouped->get('interior', collect());
    $colorSwatches = collect();
    $pick = $gallery->firstWhere('is_main', true)
        ?: $gallery->first()
        ?: $exterior->first()
        ?: $interior->first();
    $mainImage = $resolveImage(
        optional($pick)->image_url,
        $variant->name ?? 'No Image'
    );
    $brandName = optional(optional($variant->carModel)->carBrand)->name;
    $modelName = optional($variant->carModel)->name;
    $hasDiscount = (bool) ($variant->has_discount ?? false);
    $offerPrice = (int) ($variant->current_price ?? 0);
    $availability = $variant->is_available ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock';
    $avgForSchema = $variant->approved_reviews_avg ?? 0;
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
                            <a href="{{ route('car-brands.show', optional(optional($variant->carModel)->carBrand)->id ?? 1) }}" class="text-gray-500 hover:text-blue-600 transition-colors">{{ optional(optional($variant->carModel)->carBrand)->name ?? 'Xe hơi' }}</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('car-models.show', optional($variant->carModel)->id ?? 1) }}" class="text-gray-500 hover:text-blue-600 transition-colors">{{ optional($variant->carModel)->name ?? 'Model' }}</a>
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
                            $fallback = 'Không có ảnh';
                            $main = $resolveImage($variant->image_url ?? (optional(optional($variant->images)->first())->image_url), $variant->name ?? $fallback);
                        @endphp
                        <img src="{{ $main }}"
                         id="main-image"
                             class="w-full h-full object-cover cursor-zoom-in group-hover:scale-105 transition-transform duration-700"
                             alt="{{ $variant->name }}"
                             loading="lazy" decoding="async" width="1200" height="800"
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
                
                <!-- Thumbnail Gallery grouped by image_type -->
                @php
                    $thumbBlocks = [
                        'gallery' => $gallery,
                        'exterior' => $exterior,
                        'interior' => $interior,
                    ];
                @endphp
                <div class="space-y-2">
                    @foreach($thumbBlocks as $typeKey => $collection)
                        @if($collection->count() > 0)
                        <div class="flex items-center text-sm font-semibold text-gray-700 mt-1">
                            <i class="fas fa-images mr-2"></i>{{ Str::ucfirst($typeKey) }} ({{ $collection->count() }})
                        </div>
                        <div id="variant-thumbs-{{ $typeKey }}" class="sm:grid sm:grid-cols-4 sm:gap-3 flex gap-3 overflow-x-auto pb-2 thumbnails-container scrollbar-hide">
                            @foreach($collection as $image)
                        @php $thumb = $resolveImage($image->image_url, $variant->name ?? ''); @endphp
                        <div class="aspect-square min-w-[72px] sm:min-w-0 bg-white rounded-2xl shadow-lg overflow-hidden cursor-pointer hover:shadow-xl transition-all duration-300 thumbnail-image" 
                                     data-image="{{ $thumb }}" data-lightbox-src="{{ $thumb }}" data-type="{{ $typeKey }}" data-color-id="{{ (int)($image->car_variant_color_id ?? 0) }}" data-is-main="{{ $image->is_main ? '1' : '0' }}">
                            <img src="{{ $thumb }}" alt="{{ $variant->name }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300" loading="lazy" decoding="async" width="300" height="300">
                        </div>
                        @endforeach
                        </div>
                    @endif
                    @endforeach
                    {{-- Removed color_swatch thumbnails. Swatches are rendered from car_variant_colors above. --}}
                </div>
            </div>

            <!-- Product Info -->
            <div class="space-y-6 lg:col-span-7">
                <!-- Header -->
                <div class="space-y-4">
                    @if(optional($variant->carModel)->carBrand)
                    <div class="flex items-center gap-2">
                    <div class="inline-flex items-center bg-blue-50 text-blue-700 px-4 py-2 rounded-full text-sm font-semibold">
                        <i class="fas fa-crown mr-2"></i>
                        {{ optional(optional($variant->carModel)->carBrand)->name }}
                        </div>
                        <a href="{{ route('car-brands.show', optional(optional($variant->carModel)->carBrand)->id ?? 1) }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">Xem hãng</a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('car-models.show', optional($variant->carModel)->id ?? 1) }}" class="text-sm text-gray-700 hover:text-gray-900 hover:underline">Xem dòng</a>
                    </div>
                    @endif
                    
                    <h1 class="text-4xl lg:text-5xl font-black text-gray-900 leading-tight">{{ $variant->name }}</h1>
                    
                    @if(optional($variant->carModel)->name)
                    <p class="text-xl text-gray-600 leading-relaxed">{{ optional($variant->carModel)->name }}</p>
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
                        {{-- Hidden per request: fuel type, transmission, power chips --}}
                    </div>
                    
                    <!-- Rating -->
                    @php $avgInline = isset($approvedAvg) ? $approvedAvg : ($variant->approved_reviews_avg ?? 0); @endphp
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
                        
                    </div>
                </div>

                

                <!-- Color Options (moved before price) -->
                @if($variant->colors->count() > 0)
                <div class="space-y-3">
                    <h3 class="text-base font-semibold text-gray-900">Chọn màu sắc</h3>
                    <div id="selected-color-display" class="text-xs text-gray-700"></div>
                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                        @foreach($variant->colors as $color)
                            @php
                                $allImgs = $variant->images ?? collect();
                                $byColor = $allImgs->where('car_variant_color_id', $color->id);
                                $pickColorImg = $byColor->firstWhere('is_main', true)
                                    ?: $byColor->where('image_type','gallery')->first()
                                    ?: $byColor->where('image_type','exterior')->first()
                                    ?: $byColor->where('image_type','interior')->first();
                                $colorMainImg = $resolveImage(optional($pickColorImg)->image_url, $variant->name . ' ' . ($color->color_name ?? ''));
                            @endphp
                            <button type="button"
                                class="color-option flex flex-col items-center p-2.5 border border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1 js-color-option"
                                    data-color-id="{{ $color->id }}"
                                    data-color-name="{{ $color->color_name }}"
                                    data-image-url="{{ $colorMainImg }}"
                                    data-hex="{{ \App\Helpers\ColorHelper::getColorHex($color->color_name) }}"
                                    data-price-adjustment="{{ (int) ($color->price_adjustment ?? 0) }}">
                            <div class="relative w-6 h-6 sm:w-7 sm:h-7 rounded-full mb-1.5 ring-1 ring-inset ring-gray-300" data-bg-hex="{{ \App\Helpers\ColorHelper::getColorHex($color->color_name) }}" data-color-name="{{ $color->color_name }}">
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
                    $baseOriginal = (int) ($variant->base_price ?? 0);
                    $baseCurrent  = (int) ($variant->current_price ?? $baseOriginal);
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
                            <input type="hidden" name="color_id" value="" id="selected-color-id" class="js-selected-color-id">
                            <input type="hidden" name="" value="" id="selected-features-holder">
                            <input type="hidden" name="quantity" value="1">
                            
                        </form>
                        @endif
                    </div>
                </div>

                <!-- Features & Options selectors (split into Sẵn có vs Tuỳ chọn thêm) -->
                @php
                    $features = ($variant->featuresRelation ?? collect());
                    $included = $features->filter(function($f){
                        $isIncluded = (bool)($f->is_included ?? false) || (($f->availability ?? 'standard') === 'standard');
                        return $isIncluded;
                    });
                    $optional = $features->filter(function($f){
                        $isIncluded = (bool)($f->is_included ?? false) || (($f->availability ?? 'standard') === 'standard');
                        $fee = (float)($f->package_price ?? $f->price ?? 0);
                        return !$isIncluded || $fee > 0;
                    });
                @endphp

                @if($included->count() > 0 || $optional->count() > 0)
                <div class="mt-6 space-y-4">
                    <h3 class="text-base font-semibold text-gray-900">Tính năng</h3>
                    @if($included->count() > 0)
                        <div class="bg-white rounded-xl border p-3">
                        <div class="text-sm font-semibold text-emerald-700 mb-2 flex items-center gap-2"><i class="fas fa-check-circle"></i> Sẵn có</div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($included as $f)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium border border-emerald-100" title="{{ $f->description }}">{{ $f->feature_name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                    @if($optional->count() > 0)
                        <div class="bg-white rounded-xl border p-3">
                        <div class="text-sm font-semibold text-indigo-700 mb-2 flex items-center gap-2"><i class="fas fa-plus-circle"></i> Tuỳ chọn thêm</div>
                            <div class="space-y-2">
                            @foreach($optional as $f)
                                @php $fee = (float)($f->package_price ?? $f->price ?? 0); @endphp
                                <label class="flex items-start gap-3">
                                    <input type="checkbox" name="feature_ids[]" class="mt-1 accent-indigo-600 js-feature" value="{{ $f->id }}" data-fee="{{ (int)$fee }}">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-start gap-3">
                                            <span class="font-medium text-gray-900 flex-1">{{ $f->feature_name }}</span>
                                            @if($fee>0)
                                                <span class="text-sm text-indigo-700 font-semibold whitespace-nowrap">+{{ number_format($fee,0,',','.') }}₫</span>
                                            @endif
                                        </div>
                                        @if(!empty($f->description))
                                            <div class="text-xs text-gray-600 mt-0.5">{{ $f->description }}</div>
                                        @endif
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                @endif

                

                @php
                    $hasFeatures = collect($variant->featuresRelation ?? [])->count() > 0;
                    $hasOptions = collect($variant->options ?? [])->count() > 0;
                @endphp
                @if(!$hasFeatures && !$hasOptions)
                <div class="mt-4 p-3 rounded-xl border border-gray-200 bg-white text-sm text-gray-600">
                    Phiên bản này hiện chưa có tính năng hay tuỳ chọn để chọn thêm.
                </div>
                @endif

                    @if($variant->is_available)
                <!-- CTA row: full-width equal buttons -->
                <div class="mt-3 grid grid-cols-3 gap-2">
                    @php
                        $__inWishlistVarPage = \App\Helpers\WishlistHelper::isInWishlist('car_variant', $variant->id);
                    @endphp
                    <button type="button" class="action-btn action-ghost w-full js-wishlist-toggle {{ $__inWishlistVarPage ? 'in-wishlist' : 'not-in-wishlist' }}" aria-label="Yêu thích" title="Yêu thích" aria-pressed="{{ $__inWishlistVarPage ? 'true' : 'false' }}" data-item-type="car_variant" data-item-id="{{ $variant->id }}">
                        <i class="fa-heart {{ $__inWishlistVarPage ? 'fas' : 'far' }}"></i><span>Yêu thích</span>
                    </button>
                    <button type="button" class="action-btn action-ghost w-full js-compare-toggle" aria-label="So sánh" title="So sánh" data-variant-id="{{ $variant->id }}">
                        <i class="fas fa-balance-scale"></i><span>So sánh</span>
                    </button>
                    <form action="{{ route('user.cart.add') }}" method="POST" class="w-full add-to-cart-form" data-item-type="car_variant" data-item-id="{{ $variant->id }}">
                        @csrf
                        <input type="hidden" name="item_type" value="car_variant">
                        <input type="hidden" name="item_id" value="{{ $variant->id }}">
                        <input type="hidden" name="color_id" value="" class="js-selected-color-id">
                        <input type="hidden" name="" value="" id="selected-features-holder-2">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="action-btn action-primary w-full">
                            <i class="fas fa-cart-plus"></i><span>Thêm vào giỏ</span>
                            </button>
                    </form>
                </div>
                @endif
                <!-- Trust badges / Quick Info -->
                
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
                            
                        </div>
                    </div>
                </div>

                <!-- Specifications Tab -->
                <div id="specifications" class="tab-content hidden">
                    <div class="bg-white rounded-3xl shadow-xl p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            @php // Left: Basic Info @endphp
                            <div class="lg:col-span-1 space-y-6">
                                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                                    <div class="text-base font-semibold text-gray-900 mb-3">Thông tin cơ bản</div>
                                    <div class="rounded-xl border border-gray-100 overflow-hidden text-sm">
                                        <div class="grid grid-cols-1">
                                            <div class="flex justify-between items-center px-4 py-3 border-b border-gray-100 odd:bg-gray-50/60"><span class="text-gray-600">Phiên bản</span><span class="font-semibold text-gray-900 text-right ml-4">{{ $variant->name }}</span></div>
                                            <div class="flex justify-between items-center px-4 py-3 border-b border-gray-100 odd:bg-gray-50/60"><span class="text-gray-600">Dòng xe</span><span class="font-semibold text-gray-900 text-right ml-4">{{ $variant->carModel->name ?? 'N/A' }}</span></div>
                                            <div class="flex justify-between items-center px-4 py-3 border-b border-gray-100 odd:bg-gray-50/60"><span class="text-gray-600">Hãng</span><span class="font-semibold text-gray-900 text-right ml-4">{{ $variant->carModel->carBrand->name ?? 'N/A' }}</span></div>
                                            <div class="flex justify-between items-center px-4 py-3 border-b border-gray-100 odd:bg-gray-50/60"><span class="text-gray-600">Tình trạng</span><span class="font-semibold {{ $variant->is_available ? 'text-green-600' : 'text-red-600' }} text-right ml-4">{{ $variant->is_available ? 'Còn hàng' : 'Hết hàng' }}</span></div>
                                    @if(!empty($variant->sku))
                                            <div class="flex justify-between items-center px-4 py-3 border-b border-gray-100 odd:bg-gray-50/60"><span class="text-gray-600">SKU</span><span class="font-semibold text-gray-900 text-right ml-4">{{ $variant->sku }}</span></div>
                                    @endif
                                            <div class="flex justify-between items-center px-4 py-3 border-b border-gray-100 odd:bg-gray-50/60"><span class="text-gray-600">Bảo hành</span><span class="font-semibold text-gray-900 text-right ml-4">{{ $variant->warranty_info }}</span></div>
                                    </div>
                                    </div>
                                </div>
                            </div>

                            @php
                                // Right: Specs in tabs
                                $viCat = [
                                    'engine' => 'Động cơ', 'performance' => 'Vận hành', 'transmission' => 'Hộp số', 'dimensions' => 'Kích thước',
                                    'capacity' => 'Dung tích/Tải trọng', 'seating' => 'Chỗ ngồi', 'chassis' => 'Khung gầm', 'brake' => 'Phanh',
                                    'wheels' => 'Mâm/Lốp', 'comfort' => 'Tiện nghi', 'technology' => 'Công nghệ', 'safety' => 'An toàn', 'warranty' => 'Bảo hành',
                                ];
                                $viField = [
                                    'fuel_type'=>'Nhiên liệu','engine_type'=>'Kiểu động cơ','engine_displacement'=>'Dung tích','power_output'=>'Công suất','torque'=>'Mô-men xoắn',
                                    'acceleration'=>'Tăng tốc','top_speed'=>'Tốc độ tối đa','drivetrain'=>'Dẫn động','transmission'=>'Hộp số',
                                    'length'=>'Dài','width'=>'Rộng','height'=>'Cao','wheelbase'=>'Chiều dài cơ sở','ground_clearance'=>'Khoảng sáng gầm','turning_radius'=>'Bán kính quay vòng',
                                    'seating_capacity'=>'Số chỗ ngồi',
                                    'front_suspension'=>'Treo trước','rear_suspension'=>'Treo sau','front_brake'=>'Phanh trước','rear_brake'=>'Phanh sau',
                                    'wheel_size'=>'Kích thước mâm','tire_size'=>'Cỡ lốp','abs'=>'ABS','ebd'=>'EBD','esc'=>'ESP/ESC','airbag_count'=>'Túi khí',
                                    'warranty_years'=>'Bảo hành (năm)','warranty_km'=>'Bảo hành (km)',
                                    // Extra VI mappings per request
                                    'max_speed'=>'Tốc độ tối đa', 'auto_climate'=>'Điều hoà tự động', 'power_seats'=>'Ghế chỉnh điện', 'memory_seats'=>'Ghế nhớ vị trí', 'android_auto'=>'Android Auto',
                                ];
                                $specGroups = [];
                                foreach(($variant->specifications ?? []) as $spec){
                                    $cat = $spec->category ?: 'other';
                                    $rawLabel = trim((string)$spec->spec_name);
                                    $norm = Str::of($rawLabel)->lower()->replace([' ','-'],'_');
                                    $label = $viField[(string)$norm] ?? ($viField[(string)Str::of($rawLabel)->snake()] ?? (\App\Helpers\SpecHelper::labelFor($rawLabel) ?? $rawLabel));
                                    $val = $spec->spec_value;
                                    if (strtolower(trim($val)) === '3 years') { $val = '3 năm'; }
                                    if (strtolower(trim($val)) === 'auto climate') { $val = 'Điều hoà tự động'; }
                                    if ($val === '' || $val === null) continue;
                                    $specGroups[$cat] = $specGroups[$cat] ?? [];
                                    $specGroups[$cat][] = [
                                            'label' => $label,
                                        'value' => \App\Helpers\SpecHelper::formatValue($val, $spec->unit, $rawLabel),
                                            'order' => (int) ($spec->sort_order ?? 0),
                                        ];
                                    }
                                foreach ($specGroups as $k => $rows) {
                                    usort($rows, function($a,$b){ if($a['order']!==$b['order']) return $a['order']<=>$b['order']; return strcmp($a['label'],$b['label']); });
                                    $specGroups[$k] = $rows;
                                }
                                // Consolidate to mega groups to reduce tabs
                                $megaMap = [
                                    'overview' => ['engine','transmission'],
                                    'dimensions_capacity' => ['dimensions','capacity','seating'],
                                    'performance_chassis' => ['performance','chassis','wheels','brake'],
                                    'safety' => ['safety'],
                                    'comfort_tech' => ['comfort','technology'],
                                    // 'warranty' excluded from right tabs
                                ];
                                $megaLabels = [
                                    'overview' => 'Tổng quan',
                                    'dimensions_capacity' => 'Kích thước',
                                    'performance_chassis' => 'Vận hành',
                                    'safety' => 'An toàn',
                                    'comfort_tech' => 'Tiện nghi',
                                    'warranty' => 'Bảo hành',
                                ];
                                $megaGroups = [];
                                foreach ($megaMap as $megaKey => $cats) {
                                    $rows = [];
                                    foreach ($cats as $c) {
                                        if (!empty($specGroups[$c])) { $rows = array_merge($rows, $specGroups[$c]); }
                                    }
                                    if (!empty($rows)) { $megaGroups[$megaKey] = $rows; }
                                    }
                                @endphp
                            <div class="lg:col-span-1">
                                @if(!empty($megaGroups))
                                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                                        <div class="text-base font-semibold text-gray-900 mb-3">Thông số kỹ thuật</div>
                                        <div class="mb-4 flex flex-wrap gap-2">
                                            @foreach($megaGroups as $mKey => $_)
                                                <button type="button" class="spec-tab px-3 py-1.5 rounded-full text-sm font-medium shadow-sm {{ $loop->first ? 'bg-indigo-600 text-white is-active' : 'bg-gray-100 text-gray-800' }}" data-spec-tab="{{ $mKey }}">{{ $megaLabels[$mKey] ?? Str::title(str_replace(['-','_'],' ', $mKey)) }}</button>
                                    @endforeach
                                </div>
                                        @foreach($megaGroups as $mKey => $rows)
                                            <div class="spec-tab-panel rounded-xl border border-gray-100 p-0 overflow-hidden text-sm {{ !$loop->first ? 'hidden' : '' }}" data-spec-panel="{{ $mKey }}">
                                                <div class="grid grid-cols-1 sm:grid-cols-2">
                                            @foreach($rows as $row)
                                                @php
                                                    $v = trim((string)$row['value']);
                                                    if ($v === '1') $v = 'Có';
                                                    if ($v === '0') $v = 'Không';
                                @endphp
                                                <div class="flex justify-between items-center px-4 py-3 border-b border-gray-100 odd:bg-gray-50/60">
                                                    <span class="text-gray-600">{{ $row['label'] }}</span>
                                                    <span class="font-semibold text-gray-900 text-right ml-4">{{ $v }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                                        @else
                                    <div class="text-gray-600">Chưa có thông số cho phiên bản này.</div>
                                                @endif
                                            </div>
                                            </div>
                                        </div>
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
                                    @php $avgRating = isset($approvedAvg) ? $approvedAvg : ($variant->approved_reviews_avg ?? 0); @endphp
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
                    @include('components.variant-card', ['variant' => $related, 'showCompare' => true])
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
                        <a href="{{ route('test-drives.index') }}" class="inline-flex items-center px-6 py-3 rounded-xl bg-white text-slate-900 font-semibold hover:bg-indigo-50">
                            <i class="fas fa-car-side mr-2"></i> Đặt lịch lái thử
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
    
    function bindThumbClicks(scope){
        (scope || document).querySelectorAll('.thumbnail-image').forEach(thumb => {
        thumb.addEventListener('click', () => {
            const newSrc = thumb.getAttribute('data-image');
                if (newSrc && mainImage) {
            mainImage.src = newSrc;
                    mainImage.setAttribute('data-lightbox-src', newSrc);
                }
                document.querySelectorAll('.thumbnail-image').forEach(t => t.classList.remove('ring-2','ring-blue-500'));
                thumb.classList.add('ring-2','ring-blue-500');
        });
    });
    }
    bindThumbClicks(document);

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
    // Tabs for specs (right column)
    (function(){
        const tabs = document.querySelectorAll('.spec-tab');
        const panels = document.querySelectorAll('.spec-tab-panel');
        if (!tabs.length) return;
        // Ensure first tab looks active on initial load
        const firstTab = tabs[0];
        const firstPanel = panels[0];
        if (firstTab) firstTab.classList.add('bg-indigo-600','text-white','is-active');
        if (firstPanel) firstPanel.classList.remove('hidden');
        tabs.forEach(function(tab){
            tab.addEventListener('click', function(){
                const key = tab.getAttribute('data-spec-tab');
                tabs.forEach(function(t){ t.classList.remove('is-active','bg-indigo-600','text-white'); t.classList.add('bg-gray-100','text-gray-800'); });
                tab.classList.add('is-active','bg-indigo-600','text-white'); tab.classList.remove('bg-gray-100');
                panels.forEach(function(p){ p.classList.add('hidden'); });
                const active = document.querySelector('.spec-tab-panel[data-spec-panel="'+key+'"]');
            if (active) active.classList.remove('hidden');
        });
    });
    })();

    // Remove old accordion/see-more logic
    
    // Color selection
    const colorOptions = document.querySelectorAll('.js-color-option');
    const colorInput = document.getElementById('selected-color-id');
    const selectedColorText = document.getElementById('selected-color-display');
    const priceEl = document.getElementById('dynamic-price');
    const featureCbs = document.querySelectorAll('.js-feature');
    const optionCbs = document.querySelectorAll('.js-option');
    const featHolder = document.getElementById('selected-features-holder');
    const featHolder2 = document.getElementById('selected-features-holder-2');
    
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
            
            // Update hidden inputs for color across all add-to-cart forms
            try {
                document.querySelectorAll('input.js-selected-color-id[name="color_id"]').forEach(function(inp){ inp.value = colorId || ''; });
                if (colorInput) colorInput.value = colorId || '';
            } catch(_) {}
            // Update UI to show selected color
            if (selectedColorText) {
                const dot = `<span class=\"inline-block w-3.5 h-3.5 rounded-full align-middle mr-2\" style=\"background:${colorHex}\"></span>`;
                selectedColorText.innerHTML = `Màu đã chọn: ${dot}<span class=\"font-medium\">${colorName}</span>`;
            }
            // Swap main image prioritizing images tied to this color
            try {
                const thumbsAll = Array.from(document.querySelectorAll('.thumbnail-image'));
                // priority list: gallery(is_main) -> gallery -> exterior -> interior for this color
                const priority = [
                    (t) => t.dataset && t.dataset.type==='gallery' && t.dataset.colorId===colorId && t.dataset.isMain==='1',
                    (t) => t.dataset && t.dataset.type==='gallery' && t.dataset.colorId===colorId,
                    (t) => t.dataset && t.dataset.type==='exterior' && t.dataset.colorId===colorId,
                    (t) => t.dataset && t.dataset.type==='interior' && t.dataset.colorId===colorId,
                ];
                let chosen = null;
                for (const rule of priority) {
                    chosen = thumbsAll.find(rule);
                    if (chosen) break;
                }
                // fallback: provided color image
                if (!chosen && colorImg) {
                mainImage.src = colorImg;
                mainImage.setAttribute('data-lightbox-src', colorImg);
                } else if (chosen) {
                    const src = chosen.getAttribute('data-image');
                    if (src) {
                        mainImage.src = src;
                        mainImage.setAttribute('data-lightbox-src', src);
                        document.querySelectorAll('.thumbnail-image').forEach(t => t.classList.remove('ring-2','ring-blue-500'));
                        chosen.classList.add('ring-2','ring-blue-500');
                    }
                }
            } catch(e) {}
            // Update price with color adjustment (client-side)
            if (priceEl) {
                const baseCurrent = parseInt(priceEl.getAttribute('data-base-price') || '0', 10);
                const baseOriginal = parseInt(priceEl.getAttribute('data-base-original') || String(baseCurrent), 10);
                const origEl = document.getElementById('original-price');
                const discountPct = parseFloat(priceEl.getAttribute('data-discount') || '0');
                // New current base used for calc, includes color adjustment + selected addons
                const addons = computeSelectedAddonsTotal();
                const newCurrent = baseCurrent + priceAdj + addons;
                priceEl.textContent = newCurrent.toLocaleString('vi-VN');
                // Keep original price from baseOriginal (not adjusted by color)
                if (origEl && discountPct > 0) {
                    origEl.textContent = baseOriginal.toLocaleString('vi-VN');
                }
            }
        });
    });

    function computeSelectedAddonsTotal(){
        let total = 0;
        featureCbs.forEach(cb=>{ if (!cb.disabled && cb.checked){ const fee = parseInt(cb.getAttribute('data-fee')||'0',10); total += isNaN(fee)?0:fee; } });
        optionCbs.forEach(cb=>{ if (!cb.disabled && cb.checked){ const fee = parseInt(cb.getAttribute('data-fee')||'0',10); total += isNaN(fee)?0:fee; } });
        return total;
    }

    function refreshDynamicPrice(){
        if (!priceEl) return;
        const baseCurrent = parseInt(priceEl.getAttribute('data-base-price') || '0', 10);
        const colorActive = document.querySelector('.js-color-option.border-blue-500');
        const priceAdj = colorActive ? parseInt(colorActive.getAttribute('data-price-adjustment')||'0',10) : 0;
        const addons = computeSelectedAddonsTotal();
        const newCurrent = baseCurrent + priceAdj + addons;
        priceEl.textContent = newCurrent.toLocaleString('vi-VN');
    }

    featureCbs.forEach(cb=> cb.addEventListener('change', refreshDynamicPrice));
    optionCbs.forEach(cb=> cb.addEventListener('change', refreshDynamicPrice));

    // Sync hidden inputs before submit
    function collectSelectedIds(nodes){
        const ids = [];
        nodes.forEach(cb=>{ if (!cb.disabled && cb.checked){ const id = parseInt(cb.value,10); if(!isNaN(id)) ids.push(id); } });
        return ids;
    }
    document.querySelectorAll('form.add-to-cart-form').forEach(function(f){
        // Ensure our sync runs before any other capture handlers build FormData
        f.addEventListener('submit', function(){
            // Ensure color input synced just before submit (safe for capture order)
            try {
                const hiddenColor = f.querySelector('input.js-selected-color-id[name="color_id"]');
                const activeColorBtn = document.querySelector('.js-color-option.border-blue-500');
                const activeId = activeColorBtn ? activeColorBtn.getAttribute('data-color-id') : (colorInput ? colorInput.value : '');
                if (hiddenColor) hiddenColor.value = activeId || '';
            } catch(_){ }
            // Since checkboxes live outside the form, clone checked feature_ids into this form
            try {
                // remove previous clones
                f.querySelectorAll('input[type="hidden"][name="feature_ids[]"]').forEach(function(n){ n.remove(); });
            const feats = collectSelectedIds(featureCbs);
                feats.forEach(function(id){
                    const i = document.createElement('input');
                    i.type = 'hidden';
                    i.name = 'feature_ids[]';
                    i.value = String(id);
                    f.appendChild(i);
                });
            } catch(_){ }
        }, true);

        // Guaranteed inclusion regardless of who builds FormData
        f.addEventListener('formdata', function(e){
            try {
                const feats = collectSelectedIds(featureCbs);
                feats.forEach(function(id){ e.formData.append('feature_ids[]', String(id)); });
                const activeColorBtn = document.querySelector('.js-color-option.border-blue-500');
                const activeId = activeColorBtn ? activeColorBtn.getAttribute('data-color-id') : (colorInput ? colorInput.value : '');
                if (activeId) { e.formData.set('color_id', String(activeId)); }
            } catch(_){ }
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
    // Improve visibility for near-white colors: add darker ring
    try {
      var h = hex.replace('#','');
      if (h.length === 3) h = h.split('').map(function(c){ return c+c; }).join('');
      var r = parseInt(h.substring(0,2),16), g = parseInt(h.substring(2,4),16), b = parseInt(h.substring(4,6),16);
      var luminance = (0.2126*r + 0.7152*g + 0.0722*b)/255;
      if (luminance > 0.92) {
        el.classList.add('ring-2','ring-gray-300');
      }
    } catch (e) {}
    var cname = el.getAttribute('data-color-name');
    if (cname && !el.getAttribute('title')) { el.setAttribute('title', cname); }
  });
})();
</script>
@endsection