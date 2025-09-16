@extends('layouts.app')

@section('title', ($carModel->carBrand->name ?? '') . ' ' . $carModel->name)

@section('content')
@php
    $brand = $carModel->carBrand;
    // Robust hero image fallback
    $mainImage = $carModel->image_url ?? ($gallery[0] ?? null);
    if (empty($mainImage)) {
        try {
            $firstVariant = $carModel->carVariants()->where('is_active', true)->with('images')->first();
            $mainImage = $firstVariant->image_url
                ?? optional(optional($firstVariant)->images->first())->image_url
                ?? optional(optional($firstVariant)->images->first())->url
                ?? null;
        } catch (\Throwable $e) { /* ignore */ }
    }
    // Ensure gallery has items; fallback to first few active variant images
    if (empty($gallery) || count($gallery) === 0) {
        try {
            $variantImages = $carModel->carVariants()
                ->where('is_active', true)
                ->with('images')
                ->limit(4)->get()
                ->flatMap(function($v){
                    return collect($v->images ?: [])->map(function($img){
                        return $img->image_url ?? $img->url ?? null;
                    })->filter();
                })->take(6)->values()->all();
            $gallery = $variantImages;
        } catch (\Throwable $e) { /* ignore */ }
    }
    $minPrice = $stats['price_range']['min'] ?? null;
    $maxPrice = $stats['price_range']['max'] ?? null;
    $avgRating = $stats['average_rating'] ?? null;
    $ratingCount = $stats['rating_count'] ?? 0;
    $fuelTypes = $stats['fuel_types'] ?? collect();
    $transmissions = $stats['transmissions'] ?? collect();
    $seats = $stats['seating_capacities'] ?? collect();
@endphp

<!-- Hero / Intro -->
<section class="relative overflow-hidden bg-gradient-to-br from-neutral-950 via-slate-900 to-black">

    <!-- Background Pattern to match brand page -->
    <div class="absolute inset-0 opacity-10" aria-hidden="true">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, white 2px, transparent 2px), radial-gradient(circle at 75% 75%, white 2px, transparent 2px); background-size: 48px 48px;"></div>
    </div>

    <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 pt-14 sm:pt-20 pb-12 sm:pb-16">
        <!-- Breadcrumb -->
        <nav class="mb-6 sm:mb-8 text-sm text-gray-300/80" aria-label="Breadcrumb">
            <ol class="inline-flex items-center gap-2">
                <li>
                    <a href="/" class="hover:text-white transition-colors duration-200 flex items-center gap-1">
                        <i class="fas fa-home"></i>
                        <span class="hidden sm:inline">Trang chủ</span>
                    </a>
                </li>
                <li class="text-gray-400">/</li>
                <li>
                    <a href="{{ route('car-brands.index') }}" class="hover:text-white transition-colors duration-200">Hãng xe</a>
                </li>
                <li class="text-gray-400">/</li>
                <li>
                    @if($brand)
                        <a href="{{ route('car-brands.show', $brand->id) }}" class="hover:text-white transition-colors duration-200">{{ $brand->name }}</a>
                    @else
                        <span class="text-white/80">Hãng</span>
                    @endif
                </li>
                <li class="text-gray-400">/</li>
                <li class="text-white font-medium">{{ $carModel->name }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-[1.1fr,0.9fr] items-center gap-6 sm:gap-8 lg:gap-12">
            <!-- Visual / Gallery preview -->
            <div class="order-2 lg:order-1">
                <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden bg-white/5 border border-white/10 shadow-2xl">
                    @if(!empty($gallery) && count($gallery) > 1)
                        <!-- Main Image -->
                    <div class="relative aspect-[16/10] rounded-2xl overflow-hidden bg-gradient-to-br from-slate-100 via-gray-50 to-slate-200 group shadow-2xl hover:shadow-3xl transition-all duration-700">
                            <!-- Navigation Arrows -->
                            <button id="gallery-prev" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-black/30 hover:bg-black/50 backdrop-blur-lg rounded-full flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-105 shadow-2xl border border-white/30 -translate-x-2 hover:-translate-x-1">
                                <i class="fas fa-chevron-left text-lg -ml-0.5"></i>
                            </button>
                            <button id="gallery-next" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-black/30 hover:bg-black/50 backdrop-blur-lg rounded-full flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-105 shadow-2xl border border-white/30 translate-x-2 hover:translate-x-1">
                                <i class="fas fa-chevron-right text-lg -mr-0.5"></i>
                            </button>
                            
                            <!-- Main Image with Loading Skeleton -->
                        <div class="absolute inset-0 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 animate-pulse" id="image-skeleton"></div>
                            <img id="main-gallery-image" 
                             src="{{ $gallery[0] }}" 
                             alt="Car Gallery"
                             class="w-full h-full object-cover transition-all duration-500 ease-out hover:scale-110 opacity-0 cursor-zoom-in"
                             onload="this.style.opacity='1'; document.getElementById('image-skeleton').style.display='none'"
                             data-lightbox-src="{{ $gallery[0] }}">
                            
                            <!-- Enhanced Progress Dots -->
                        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex space-x-2 z-10 bg-black/20 backdrop-blur-md rounded-full px-4 py-2">
                            @foreach($gallery as $index => $image)
                                <button class="gallery-dot w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white transition-all duration-300 cursor-pointer shadow-sm hover:scale-125 {{ $index === 0 ? 'bg-white scale-125' : '' }}"></button>
                            @endforeach
                        </div>
                            
                            <!-- Image Counter -->
                            <div class="absolute top-6 right-6 bg-black/40 backdrop-blur-md text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg border border-white/10">
                                <span id="current-slide">1</span> / {{ count($gallery) }}
                            </div>
                            
                            <!-- Enhanced Gradient Overlays -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-black/10 pointer-events-none"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-black/10 via-transparent to-black/10 pointer-events-none"></div>
                        
                        <!-- Floating Brand Badge -->
                        <div class="absolute top-6 left-6 bg-white/90 backdrop-blur-md text-gray-800 px-4 py-2 rounded-full text-sm font-bold shadow-lg border border-white/20">
                            {{ $carModel->carBrand->name }}
                        </div>
                        </div>    
                        <!-- Clean Thumbnail Slider -->
                        <div class="">
                            <div class="flex justify-center">
                                <div class="relative max-w-sm">
                                    <!-- Slider Container -->
                                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-5 shadow-xl">
                                        <div class="overflow-hidden rounded-xl">
                                            <div id="thumbnail-slider" class="flex transition-transform duration-500 ease-out">
                                                @php
                                                    $chunks = array_chunk($gallery, 4);
                                                @endphp
                                                @foreach($chunks as $chunkIndex => $chunk)
                                                    <div class="flex-shrink-0 w-full grid grid-cols-4 gap-3">
                                                        @foreach($chunk as $imgIndex => $img)
                                                            @php
                                                                $globalIndex = $chunkIndex * 4 + $imgIndex;
                                                            @endphp
                                                            <button type="button" class="gallery-thumb relative w-16 h-12 rounded-lg overflow-hidden transition-all duration-300 border-2 {{ $globalIndex === 0 ? 'border-white/60 ring-1 ring-white/40' : 'border-white/20 hover:border-white/50' }} shadow-md hover:shadow-lg hover:scale-105"
                                                                    data-index="{{ $globalIndex }}">
                                                                <img src="{{ $img }}" alt="{{ $carModel->name }}" class="w-full h-full object-cover">
                                                                <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300"></div>
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Navigation Arrows -->
                                    @if(count($chunks) > 1)
                                        <button id="slider-prev" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-12 w-10 h-10 bg-black/30 hover:bg-black/50 backdrop-blur-md rounded-full flex items-center justify-center text-white transition-all duration-300 shadow-lg border border-white/20 hover:scale-110 disabled:opacity-30">
                                            <i class="fas fa-chevron-left text-sm"></i>
                                        </button>
                                        <button id="slider-next" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-12 w-10 h-10 bg-black/30 hover:bg-black/50 backdrop-blur-md rounded-full flex items-center justify-center text-white transition-all duration-300 shadow-lg border border-white/20 hover:scale-110">
                                            <i class="fas fa-chevron-right text-sm"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Single Image -->
                        <div class="relative aspect-[4/3] sm:aspect-[16/9] lg:aspect-[2/1]">
                            @if($mainImage)
                                <img id="model-hero-image" src="{{ $mainImage }}" data-src="{{ $mainImage }}" alt="{{ $carModel->name }}" class="absolute inset-0 w-full h-full object-cover lazy-image transition-opacity duration-300 ease-in-out">
                                <div class="absolute inset-0 skeleton-image"></div>
                            @else
                                <div class="absolute inset-0 image-error">
                                    <i class="fas fa-car-side text-2xl"></i>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Heading / Meta -->
            <div class="order-1 lg:order-2">
                <div class="grid grid-cols-1 xl:grid-cols-[auto,1fr] items-center gap-4 sm:gap-6 xl:gap-8">
                    <!-- Logo -->
                    <div class="flex items-center justify-center xl:justify-start">
                        @if($brand && $brand->logo_url)
                            <div class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 rounded-xl sm:rounded-2xl bg-white/95 backdrop-blur-sm ring-2 ring-white/20 shadow-2xl flex items-center justify-center overflow-hidden">
                                <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="w-12 h-12 sm:w-16 sm:h-16 lg:w-20 lg:h-20 object-contain" loading="lazy" decoding="async">
                            </div>
                        @else
                            <div class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 rounded-xl sm:rounded-2xl bg-white/95 backdrop-blur-sm ring-2 ring-white/20 shadow-2xl flex items-center justify-center">
                                <i class="fas fa-car text-gray-700 text-lg sm:text-2xl lg:text-3xl"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Basic Info -->
                    <div class="min-w-0 text-center xl:text-left">
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight">
                            {{ $brand->name ?? '' }} {{ $carModel->name }}
                        </h1>

                        @if(!empty($carModel->description))
                            <p class="mt-2 sm:mt-3 text-sm sm:text-base text-gray-200 leading-relaxed max-w-2xl xl:max-w-3xl">
                                {{ $carModel->description }}
                            </p>
                        @endif

                        <!-- Simple badges -->
                        <div class="mt-3 sm:mt-4 flex flex-wrap items-center justify-center xl:justify-start gap-1.5 sm:gap-2">
                            @php $hasBody = !empty($carModel->body_type); @endphp
                            <span class="inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-full bg-white/10 text-white text-xs sm:text-sm font-medium border border-white/20">
                                <i class="fas fa-car text-xs sm:text-sm"></i>
                                <span class="whitespace-nowrap">{{ $hasBody ? $carModel->body_type : 'Kiểu dáng: Đang cập nhật' }}</span>
                            </span>
                            @if(!empty($carModel->segment))
                                <span class="inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-full bg-white/10 text-white text-xs sm:text-sm font-medium border border-white/20">
                                    <i class="fas fa-layer-group text-xs sm:text-sm"></i>
                                    <span class="whitespace-nowrap">{{ $carModel->segment }}</span>
                                </span>
                            @endif
                            <span class="inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-full bg-white/10 text-white text-xs sm:text-sm font-medium border border-white/20">
                                <i class="fas fa-calendar-alt text-xs sm:text-sm"></i>
                                <span class="whitespace-nowrap">
                                @if(!empty($carModel->production_start_year) || !empty($carModel->production_end_year))
                                    {{ $carModel->production_start_year }}@if(!empty($carModel->production_end_year)) – {{ $carModel->production_end_year }} @endif
                                @else
                                    Năm ra mắt: Đang cập nhật
                                @endif
                                </span>
                            </span>
                            <span class="inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-full bg-white/10 text-white text-xs sm:text-sm font-medium border border-white/20">
                                <i class="fas fa-history text-xs sm:text-sm"></i>
                                <span class="whitespace-nowrap">{{ !empty($carModel->generation) ? $carModel->generation : 'Thế hệ: Đang cập nhật' }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sticky section nav -->
    <div class="border-t border-white/10 bg-white/5 backdrop-blur-md">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-2 sm:py-3">
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 sm:gap-3 lg:gap-4 text-xs sm:text-sm text-white/90">
                <a href="#variants" class="flex items-center px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-full bg-white/10 hover:bg-white/20 transition-all duration-200 border border-white/20 hover:border-white/30">
                    <i class="fas fa-layer-group text-xs sm:text-sm"></i>
                    <span class="ml-2 whitespace-nowrap">Phiên bản</span>
                </a>
                @if(($featuredVariants ?? collect())->count() > 0)
                <a href="#featured" class="flex items-center px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-full bg-white/10 hover:bg-white/20 transition-all duration-200 border border-white/20 hover:border-white/30">
                    <i class="fas fa-star text-xs sm:text-sm"></i>
                    <span class="ml-2 whitespace-nowrap">Nổi bật</span>
                </a>
                @endif
                @if(($relatedModels ?? collect())->count() > 0)
                <a href="#related" class="flex items-center px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-full bg-white/10 hover:bg-white/20 transition-all duration-200 border border-white/20 hover:border-white/30">
                    <i class="fas fa-car-side text-xs sm:text-sm"></i>
                    <span class="ml-2 whitespace-nowrap">Mẫu liên quan</span>
                </a>
                @endif
            </div>
        </div>
    </div>

</section>

<!-- Variants -->
<section id="variants" class="py-16 sm:py-20 bg-gradient-to-b from-white to-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-6 sm:mb-8 lg:mb-12 px-4">
            <div class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-indigo-100 text-indigo-700 text-xs sm:text-sm font-semibold mb-3 sm:mb-4">
                <i class="fas fa-layer-group text-xs sm:text-sm"></i>
                <span class="ml-2">Các phiên bản của {{ $carModel->name }}</span>
            </div>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900">Chọn phiên bản phù hợp</h2>
            @if($minPrice)
                <p class="text-sm sm:text-base lg:text-lg text-gray-600 max-w-2xl mx-auto mt-2 sm:mt-3">Giá tham khảo từ {{ number_format($minPrice, 0, ',', '.') }}₫ @if($maxPrice && $maxPrice > $minPrice) đến {{ number_format($maxPrice, 0, ',', '.') }}₫ @endif</p>
            @endif
        </div>

        @if(($variants ?? collect())->count() === 0)
            <div class="text-center py-20">
                <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-8"><i class="fas fa-car-side text-gray-400 text-5xl"></i></div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">Chưa có phiên bản</h3>
                <p class="text-gray-600 text-lg mb-8">Vui lòng quay lại sau hoặc liên hệ để biết thêm thông tin</p>
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition-colors duration-200"><i class="fas fa-phone"></i>Liên hệ tư vấn</a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 lg:gap-8 px-4 sm:px-0">
                @foreach($variants as $variant)
                    <div class="group">
                        @include('components.variant-card', ['variant' => $variant, 'showCompare' => true])
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    </section>

{{-- Quick compare table removed as requested --}}

@if(($featuredVariants ?? collect())->count() > 0)
<section id="featured" class="py-16 sm:py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 sm:mb-10 lg:mb-12 px-4">
            <div class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-yellow-100 text-yellow-700 text-xs sm:text-sm font-semibold mb-3 sm:mb-4">
                <i class="fas fa-star text-xs sm:text-sm"></i>
                <span class="ml-2">Phiên bản nổi bật</span>
            </div>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900">Được quan tâm nhiều</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 lg:gap-8 px-4 sm:px-0">
            @foreach($featuredVariants as $variant)
                <div class="group">
                    @include('components.variant-card', ['variant' => $variant, 'showCompare' => true])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section (moved to end of page) -->
@if(($relatedModels ?? collect())->count() > 0)
<section id="related" class="py-16 sm:py-20 bg-gradient-to-b from-gray-50 to-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 sm:mb-10 lg:mb-12 px-4">
            <div class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold mb-3 sm:mb-4">
                <i class="fas fa-car-side text-xs sm:text-sm"></i>
                <span class="ml-2">Mẫu liên quan</span>
            </div>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900">Các dòng xe khác của {{ $brand->name ?? 'hãng' }}</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 lg:gap-8 px-4 sm:px-0">
            @foreach($relatedModels as $m)
            @php
                $relImg = null;
                try {
                    // 1) images relation or attribute (collection/array/json) -> pick first image_url/url/path
                    if (isset($m->images)) {
                        if ($m->images instanceof \Illuminate\Support\Collection) {
                            $first = $m->images->first();
                            $relImg = $first->image_url ?? $first->url ?? $first->path ?? $relImg;
                        } elseif (is_array($m->images) && count($m->images)) {
                            $first = $m->images[0];
                            if (is_array($first)) { $relImg = $first['image_url'] ?? $first['url'] ?? $first['path'] ?? $relImg; }
                        } elseif (is_string($m->images)) {
                            $arr = json_decode($m->images, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($arr) && count($arr)) {
                                $first = $arr[0];
                                if (is_array($first)) { $relImg = $first['image_url'] ?? $first['url'] ?? $first['path'] ?? $relImg; }
                                elseif (is_string($first)) { $relImg = $first; }
                            }
                        }
                    }
                    // 2) gallery first (array/json/string)
                    if (empty($relImg) && !empty($m->gallery)) {
                        if (is_array($m->gallery) && count($m->gallery)) {
                            $relImg = $m->gallery[0];
                        } elseif (is_string($m->gallery)) {
                            $g = json_decode($m->gallery, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($g) && count($g)) {
                                $relImg = $g[0] ?? null;
                            } elseif (filter_var($m->gallery, FILTER_VALIDATE_URL)) {
                                $relImg = $m->gallery;
                            }
                        }
                    }
                    // 3) image_url field
                    if (empty($relImg)) {
                        $relImg = $m->image_url ?? null;
                    }
                    // 4) Fallback to first active variant image
                    if (empty($relImg)) {
                        $fv = $m->carVariants()->where('is_active', true)->with('images')->first();
                        $relImg = $fv->image_url
                            ?? optional(optional($fv)->images->first())->image_url
                            ?? optional(optional($fv)->images->first())->url
                            ?? optional(optional($fv)->images->first())->path
                            ?? $relImg;
                    }
                } catch (\Throwable $e) { /* ignore */ }
            @endphp
            <a href="{{ route('car-models.show', $m->id) }}" class="group block">
                <div class="variant-card card-surface overflow-hidden h-full flex flex-col min-h-fit rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-xl transition-shadow duration-300">
                    <div class="relative">
                        <div class="card-media w-full aspect-[4/3]">
                            <img src="{{ $relImg }}" data-src="{{ $relImg }}" alt="{{ $m->name }}" class="card-img" onerror="this.onerror=null;this.src='https://via.placeholder.com/800x600?text=No+Image';">
                            <span class="card-overlay"></span>
                            <span class="card-sheen"></span>
                        </div>
                        <!-- Top-left badges -->
                        <div class="absolute top-3 left-3 flex flex-col gap-2 pointer-events-none">
                            <div class="bg-white/90 backdrop-blur-sm text-gray-800 text-[10px] px-2.5 py-1 rounded-full inline-flex items-center gap-1 leading-none font-bold border border-gray-200 shadow">
                                <i class="fas fa-layer-group text-[12px] leading-none"></i>
                                {{ $m->carVariants?->count() ?? 0 }} phiên bản
                            </div>
                            @if(!empty($m->starting_price))
                            <div class="bg-indigo-50 text-indigo-700 text-[10px] px-2.5 py-1 rounded-full inline-flex items-center gap-1 leading-none font-bold border border-indigo-100 shadow">
                                <i class="fas fa-tag text-[12px] leading-none"></i>
                                Từ {{ number_format($m->starting_price, 0, ',', '.') }}₫
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="p-4 sm:p-5 flex flex-col justify-between flex-1 space-y-3 sm:space-y-4">
                        <div class="flex items-center gap-2">
                            <span class="text-xs sm:text-sm text-gray-700 font-medium">{{ $brand->name ?? '' }}</span>
                        </div>
                        <div class="text-base sm:text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-2">{{ $m->name }}</div>
                        <div class="flex items-center justify-between mt-auto">
                            @if(!empty($m->starting_price))
                                <span class="text-sm text-gray-700">Giá từ <strong class="text-indigo-600">{{ number_format($m->starting_price, 0, ',', '.') }}₫</strong></span>
                            @else
                                <span class="text-sm text-gray-500">Giá: Liên hệ</span>
                            @endif
                            <span class="inline-flex items-center gap-1 text-indigo-600 text-sm font-semibold opacity-0 group-hover:opacity-100 transition-opacity">
                                <span>Xem chi tiết</span>
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section (moved to end of page) -->
<section class="py-16 sm:py-20 bg-gradient-to-br from-neutral-950 via-slate-900 to-black">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-3 sm:mb-4 px-4">Sẵn sàng trải nghiệm {{ $carModel->name }}?</h2>
        <p class="text-base sm:text-lg text-gray-300 mb-6 sm:mb-8 max-w-2xl mx-auto px-4">Liên hệ tư vấn hoặc đặt lịch lái thử để cảm nhận thực tế.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('contact') }}" class="group bg-white text-slate-900 px-8 py-4 min-h-[48px] rounded-full font-bold text-lg transition-all duration-300 hover:bg-purple-100 hover:scale-[1.02] shadow-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-purple-600 focus-visible:ring-offset-2">
                <i class="fas fa-phone mr-2"></i>
                Nhận tư vấn/Báo giá
            </a>
            @auth
            <a href="{{ route('test-drives.index') }}" class="border-2 border-white/30 text-white px-8 py-4 min-h-[48px] rounded-full font-bold text-lg hover:bg-white/10 transition-all duration-300 backdrop-blur-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-neutral-900/40">
                <i class="fas fa-steering-wheel mr-2"></i>
                Đặt lái thử
            </a>
            @else
            <a href="{{ route('login', ['redirect' => request()->fullUrl()]) }}" class="border-2 border-white/30 text-white px-8 py-4 min-h-[48px] rounded-full font-bold text-lg hover:bg-white/10 transition-all duration-300 backdrop-blur-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-neutral-900/40">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Đăng nhập để đặt lái thử
            </a>
            @endauth
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .skeleton-image { position:absolute; inset:0; background: linear-gradient(90deg,#f0f0f0 25%,#e0e0e0 50%,#f0f0f0 75%); background-size:200% 100%; animation:loading 1.5s infinite; }
    @keyframes loading { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
    
    /* Gallery slider styles */
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    
    /* Gallery transitions */
    #gallery-track {
        transition: transform 0.5s ease-in-out;
    }
    
    .gallery-dot {
        transition: all 0.2s ease;
    }
    
    .gallery-thumb {
        transition: all 0.2s ease;
    }
    
    /* Compact tweaks for very small screens */
    @media (max-width: 360px){
        .action-btn { padding: .6rem .75rem !important; font-size: .85rem !important; }
        .gallery-thumb { width: 3rem !important; height: 2rem !important; }
    }
</style>
@endpush

@push('scripts')
<script>
// Paint color dots from data attribute to avoid invalid inline CSS parsing issues
document.addEventListener('DOMContentLoaded', function(){
  document.querySelectorAll('.js-color-dot').forEach(function(el){
    var c = el.getAttribute('data-color') || '#e5e7eb';
    if (c && typeof c === 'string') {
      if (c[0] !== '#') c = '#' + c.replace(/^#+/, '');
      el.style.backgroundColor = c;
    }
  });
  // Hero image fallback to first thumbnail if missing or error
  try {
    var hero = document.getElementById('model-hero-image');
    var thumbsWrap = document.getElementById('model-hero-thumbs');
    var skeleton = hero ? hero.closest('.relative')?.querySelector('.skeleton-image') : null;
    // Swap hero to selected thumbnail
    function selectThumb(btn){
      if (!hero || !btn) return;
      var src = btn.getAttribute('data-src');
      if (!src || src === hero.src) return;
      
      // Simple fade effect - no skeleton
      hero.style.opacity = '0.3';
      
      setTimeout(() => {
        hero.src = src;
        hero.style.opacity = '1';
      }, 150);
      
      // active state
      try {
        thumbsWrap?.querySelectorAll('.js-hero-thumb').forEach(function(b){
          b.classList.remove('ring-2','ring-indigo-300','border-white/40');
          b.classList.add('border-white/20');
          b.setAttribute('aria-pressed', 'false');
        });
        btn.classList.add('ring-2','ring-indigo-300','border-white/40');
        btn.classList.remove('border-white/20');
        btn.setAttribute('aria-pressed', 'true');
      } catch(e) {}
    }
    // Bind click handlers
    if (thumbsWrap) {
      thumbsWrap.querySelectorAll('.js-hero-thumb').forEach(function(btn, idx){
        btn.addEventListener('click', function(){ selectThumb(btn); });
        if (idx === 0) {
          btn.setAttribute('aria-pressed', 'true');
          btn.classList.add('ring-2','ring-indigo-300','border-white/40');
          btn.classList.remove('border-white/20');
        }
      });
    }
    // Hide skeleton after image load and reveal image
    if (hero) {
      hero.addEventListener('load', function(){
        if (skeleton) skeleton.style.display = 'none';
        hero.classList.add('loaded');
      });
    }
    function swapToFirstThumb(){
      if (!thumbsWrap) return;
      var firstBtn = thumbsWrap.querySelector('.js-hero-thumb');
      if (firstBtn) selectThumb(firstBtn);
    }
    if (hero) {
      hero.addEventListener('error', swapToFirstThumb, { once: true });
      // If src is empty, fallback to first thumb
      setTimeout(function(){
        try {
          var hasSize = hero.naturalWidth > 0 && hero.naturalHeight > 0;
          if (!hasSize) swapToFirstThumb();
          else { hero.classList.add('loaded'); }
        } catch(e) {}
      }, 500);
    }
  } catch(e) {}
});
// Clean Gallery Slider
document.addEventListener('DOMContentLoaded', function() {
    let currentSlide = 0;
    let currentSliderPage = 0;
    let autoPlayInterval = null;
    let isTransitioning = false;
    
    // Get gallery elements
    const thumbnails = document.querySelectorAll('.gallery-thumb');
    const thumbnailSlider = document.getElementById('thumbnail-slider');
    const galleryDots = document.querySelectorAll('.gallery-dot');
    const sliderDots = document.querySelectorAll('.slider-dot');
    const galleryImages = Array.from(thumbnails).map(thumb => {
        return thumb.querySelector('img').src;
    });
    
    const itemsPerSlide = 4;
    const totalSlides = Math.ceil(galleryImages.length / itemsPerSlide);
    
    function changeGalleryImage(index, smooth = true) {
        if (isTransitioning || !galleryImages[index]) return;
        
        const mainImage = document.getElementById('main-gallery-image');
        if (!mainImage) return;
        
        isTransitioning = true;
        currentSlide = index;
        
        // Simple fade effect
        if (smooth) {
            mainImage.style.opacity = '0.3';
            
            setTimeout(() => {
                mainImage.src = galleryImages[index];
                mainImage.setAttribute('data-lightbox-src', galleryImages[index]);
                mainImage.style.opacity = '1';
                isTransitioning = false;
            }, 150);
        } else {
            mainImage.src = galleryImages[index];
            mainImage.setAttribute('data-lightbox-src', galleryImages[index]);
            isTransitioning = false;
        }
        
        // Update dots
        galleryDots.forEach((dot, i) => {
            if (i === index) {
                dot.classList.add('bg-white', 'scale-125');
                dot.classList.remove('bg-white/50');
            } else {
                dot.classList.remove('bg-white', 'scale-125');
                dot.classList.add('bg-white/50');
            }
        });
        
        // Update thumbnail slider
        updateThumbnailSlider();
        
        // Reset autoplay
        resetAutoPlay();
    }
    
    function updateThumbnailSlider() {
        // Update active thumbnail
        thumbnails.forEach((thumb, i) => {
            if (i === currentSlide) {
                thumb.classList.add('border-white/60', 'ring-1', 'ring-white/40');
                thumb.classList.remove('border-white/20');
            } else {
                thumb.classList.remove('border-white/60', 'ring-1', 'ring-white/40');
                thumb.classList.add('border-white/20');
            }
        });
        
        // Auto-navigate to correct slide page
        const targetSliderPage = Math.floor(currentSlide / itemsPerSlide);
        if (targetSliderPage !== currentSliderPage) {
            currentSliderPage = targetSliderPage;
            updateSliderPosition();
        }
    }
    
    function updateSliderPosition() {
        if (!thumbnailSlider) return;
        
        const translateX = -currentSliderPage * 100;
        thumbnailSlider.style.transform = `translateX(${translateX}%)`;
        
        // Update slider dots (if they exist)
        if (sliderDots.length > 0) {
            sliderDots.forEach((dot, i) => {
                if (i === currentSliderPage) {
                    dot.classList.add('bg-white');
                    dot.classList.remove('bg-white/40');
                } else {
                    dot.classList.remove('bg-white');
                    dot.classList.add('bg-white/40');
                }
            });
        }
        
        // Update navigation buttons
        const prevBtn = document.getElementById('slider-prev');
        const nextBtn = document.getElementById('slider-next');
        
        if (prevBtn) {
            prevBtn.disabled = currentSliderPage === 0;
            prevBtn.style.opacity = currentSliderPage === 0 ? '0.3' : '1';
        }
        
        if (nextBtn) {
            nextBtn.disabled = currentSliderPage === totalSlides - 1;
            nextBtn.style.opacity = currentSliderPage === totalSlides - 1 ? '0.3' : '1';
        }
    }
    
    function nextSlide() {
        const next = (currentSlide + 1) % galleryImages.length;
        changeGalleryImage(next);
    }
    
    function prevSlide() {
        const prev = (currentSlide - 1 + galleryImages.length) % galleryImages.length;
        changeGalleryImage(prev);
    }
    
    function nextSliderPage() {
        if (currentSliderPage < totalSlides - 1) {
            currentSliderPage++;
            updateSliderPosition();
        }
    }
    
    function prevSliderPage() {
        if (currentSliderPage > 0) {
            currentSliderPage--;
            updateSliderPosition();
        }
    }
    
    function startAutoPlay() {
        if (galleryImages.length <= 1) return;
        if (autoPlayInterval) clearInterval(autoPlayInterval);
        autoPlayInterval = setInterval(nextSlide, 7000);
    }
    
    function stopAutoPlay() {
        if (autoPlayInterval) {
            clearInterval(autoPlayInterval);
            autoPlayInterval = null;
        }
    }
    
    function resetAutoPlay() {
        stopAutoPlay();
        setTimeout(startAutoPlay, 4000);
    }
    
    // Event listeners
    document.getElementById('gallery-next')?.addEventListener('click', nextSlide);
    document.getElementById('gallery-prev')?.addEventListener('click', prevSlide);
    document.getElementById('slider-next')?.addEventListener('click', nextSliderPage);
    document.getElementById('slider-prev')?.addEventListener('click', prevSliderPage);
    
    // Thumbnail click handlers
    thumbnails.forEach((thumb, index) => {
        thumb.addEventListener('click', () => changeGalleryImage(index));
    });
    
    // Slider dot click handlers (if they exist)
    if (sliderDots.length > 0) {
        sliderDots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSliderPage = index;
                updateSliderPosition();
            });
        });
    }
    
    // Dot click handlers
    galleryDots.forEach((dot, index) => {
        dot.addEventListener('click', () => changeGalleryImage(index));
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') prevSlide();
        if (e.key === 'ArrowRight') nextSlide();
    });
    
    // Auto-play with hover pause
    if (galleryImages.length > 1) {
        setTimeout(startAutoPlay, 3000);
        
        const mainImage = document.getElementById('main-gallery-image');
        if (mainImage) {
            mainImage.parentElement.addEventListener('mouseenter', stopAutoPlay);
            mainImage.parentElement.addEventListener('mouseleave', () => {
                setTimeout(startAutoPlay, 2000);
            });
        }
    }
    
    // Initialize
    if (galleryImages.length > 0) {
        updateSliderPosition();
        changeGalleryImage(0, false);
    }
});

// Smooth scroll for local anchors with header offset
document.querySelectorAll('a[href^="#"]').forEach(function(anchor){
    anchor.addEventListener('click', function(e){
        const href = this.getAttribute('href');
        const target = document.querySelector(href);
        if (!target) return;
        e.preventDefault();
        const headerOffset = 100;
        const elementPosition = target.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
        window.scrollTo({ top: offsetPosition, behavior: 'smooth' });
    });
});

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
const mainGalleryImage = document.getElementById('main-gallery-image');
if (mainGalleryImage) {
    mainGalleryImage.addEventListener('click', function(){
        const src = this.getAttribute('data-lightbox-src') || this.src;
        openLightbox(src);
    });
}
</script>
@endpush
