@extends('layouts.app')

@section('title', 'AutoLux - Premium Auto Showroom')

@section('content')
@push('head')
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "AutoDealer",
        "name": "AutoLux Showroom",
        "url": "{{ url('/') }}",
        "telephone": "+84-123-456-789",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "{{ optional($showrooms->first())->address ?? '123 Đường ABC' }}",
            "addressLocality": "{{ optional($showrooms->first())->city ?? 'TP.HCM' }}",
            "postalCode": "{{ optional($showrooms->first())->postal_code ?? '700000' }}",
            "addressCountry": "VN"
        }
    }
</script>
@endpush
<!-- Success/Error Messages -->
<div id="message-container" class="fixed top-4 right-4 z-50"></div>

{{-- ===== Hero Section ===== --}}
<section class="relative min-h-[70vh] sm:min-h-[65vh] lg:min-h-[60vh] bg-gradient-to-br from-neutral-950 via-slate-900 to-black overflow-hidden z-0 pt-16">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10 z-0">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, white 2px, transparent 2px), radial-gradient(circle at 75% 75%, white 2px, transparent 2px); background-size: 50px 50px;"></div>
    </div>

    <!-- Main Content -->
    <div class="relative z-0 min-h-[60vh] sm:min-h-[55vh] lg:min-h-[50vh] flex items-center justify-center pt-8 pb-36 sm:pb-44 lg:pb-52">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <!-- Main Heading -->
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-black mb-6 sm:mb-8 leading-tight">
                    <span class="bg-gradient-to-r from-white via-purple-200 to-white bg-clip-text text-transparent">
                        Premium
                    </span>
                    <br>
                    <span class="text-purple-300 font-light">Auto Showroom</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-lg sm:text-xl md:text-2xl text-gray-300 mb-8 sm:mb-12 max-w-2xl mx-auto leading-relaxed px-4">
                    Khám phá bộ sưu tập xe hơi cao cấp với công nghệ hiện đại và dịch vụ chuyên nghiệp
                </p>

                <!-- Search + Quick actions -->
                <div class="max-w-3xl mx-auto mt-6 sm:mt-8 px-4">
                    <form method="GET" action="{{ route('products.index') }}" class="relative">
                        <input type="search" name="q" placeholder="Tìm xe, hãng, model hoặc phụ kiện..." aria-label="Tìm kiếm"
                               class="w-full rounded-full border border-white/30 bg-white/90 backdrop-blur px-5 sm:px-6 py-3 sm:py-4 pr-28 text-slate-800 placeholder-slate-500 focus:ring-4 focus:ring-purple-400/40 focus:border-white shadow-xl" />
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex items-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold hover:from-indigo-700 hover:to-purple-700 shadow-md">
                            <i class="fas fa-search"></i>
                            <span class="hidden sm:inline">Tìm kiếm</span>
                        </button>
                    </form>
                    <div class="flex flex-wrap justify-center gap-2 sm:gap-3 mt-4">
                        <a href="{{ route('products.index', ['type' => 'car']) }}" class="px-3 sm:px-4 py-1.5 rounded-full bg-white/10 text-white border border-white/20 hover:bg-white/20 text-sm">
                            <i class="fas fa-car-side mr-1"></i> Xe hơi
                        </a>
                        <a href="{{ route('products.index', ['type' => 'accessory']) }}" class="px-3 sm:px-4 py-1.5 rounded-full bg-white/10 text-white border border-white/20 hover:bg-white/20 text-sm">
                            <i class="fas fa-puzzle-piece mr-1"></i> Phụ kiện
                        </a>
                        <a href="#featured" class="px-3 sm:px-4 py-1.5 rounded-full bg-white/10 text-white border border-white/20 hover:bg-white/20 text-sm">
                            <i class="fas fa-star mr-1"></i> Nổi bật
                        </a>
                        <a href="#promotions" class="px-3 sm:px-4 py-1.5 rounded-full bg-white/10 text-white border border-white/20 hover:bg-white/20 text-sm">
                            <i class="fas fa-tags mr-1"></i> Khuyến mãi
                        </a>
                    </div>
                    @if(isset($fuelTypes) || isset($transmissions))
                    <!-- Quick Filters: horizontal scroll -->
                    <div class="mt-5 space-y-3">
                        @if(isset($fuelTypes) && count($fuelTypes))
                        <div class="text-white/80 text-sm mb-1">Nhiên liệu</div>
                        <div class="flex gap-2 overflow-x-auto no-scrollbar snap-x snap-mandatory py-1">
                            @foreach($fuelTypes->take(8) as $ft)
                            <a href="{{ route('products.index', ['fuel_type' => $ft]) }}" class="snap-start shrink-0 px-3 py-1.5 rounded-full bg-white/10 text-white border border-white/20 hover:bg-white/20 text-sm whitespace-nowrap">{{ $ft }}</a>
                            @endforeach
                        </div>
                        @endif
                        @if(isset($transmissions) && count($transmissions))
                        <div class="text-white/80 text-sm mb-1">Hộp số</div>
                        <div class="flex gap-2 overflow-x-auto no-scrollbar snap-x snap-mandatory py-1">
                            @foreach($transmissions->take(8) as $tm)
                            <a href="{{ route('products.index', ['transmission' => $tm]) }}" class="snap-start shrink-0 px-3 py-1.5 rounded-full bg-white/10 text-white border border-white/20 hover:bg-white/20 text-sm whitespace-nowrap">{{ $tm }}</a>
                            @endforeach
                        </div>
                        @endif
                        
                    </div>
                    @endif
                </div>

                

                <!-- Scroll Indicator -->
                <div class="absolute bottom-8 sm:bottom-12 left-1/2 transform -translate-x-1/2 z-0">
                    <div class="flex flex-col items-center text-white/70 animate-bounce">
                        <span class="text-xs sm:text-sm mb-1 sm:mb-2">Scroll</span>
                        <i class="fas fa-chevron-down text-lg sm:text-xl"></i>
                    </div>
                </div>
</section>

{{-- Test Drive section removed as requested --}}
{{-- Promotions moved below accessories --}}

{{-- ===== Car Brands Section ===== --}}
<section id="brands" class="py-20 sm:py-28 bg-gradient-to-b from-white to-slate-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 sm:mb-20">
            <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-full text-sm font-semibold mb-6 shadow-lg">
                <i class="fas fa-handshake mr-3"></i>
                Đối tác chính thức
            </div>
            <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">Hãng xe đối tác</h2>
            <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Chúng tôi tự hào là đại lý chính thức của các thương hiệu xe hơi hàng đầu thế giới
            </p>
        </div>

        <!-- Featured Brands Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-8 sm:gap-10 max-w-6xl mx-auto">
            @foreach($brands as $brand)
            @include('components.brand-card', ['brand' => $brand])
            @endforeach
        </div>

        <!-- View All Brands Button -->
        <div class="text-center mt-12">
            <a href="{{ route('car-brands.index') }}"
                class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-full hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                <span>Xem tất cả hãng xe</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</section>

{{-- ===== Featured Cars Section ===== --}}
<section id="featured" class="py-20 sm:py-28 bg-gradient-to-b from-slate-50 to-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 sm:mb-20">
            <div class="inline-flex items-center px-4 py-2 bg-emerald-100 text-emerald-800 rounded-full text-sm font-medium mb-4">
                <i class="fas fa-star mr-2"></i>
                Xe nổi bật
            </div>
            <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">Xe hơi nổi bật</h2>
            <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Những mẫu xe mới nhất và được yêu thích nhất từ các hãng xe hàng đầu
            </p>
        </div>

        <!-- Featured Cars Carousel/Grid -->
        <div class="relative">
            <div class="md:hidden absolute -left-3 top-1/2 -translate-y-1/2 z-10">
                <button type="button" class="carousel-prev inline-flex items-center justify-center w-9 h-9 rounded-full bg-white shadow border border-gray-200" data-target="#featured-cars">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
            <div class="md:hidden absolute -right-3 top-1/2 -translate-y-1/2 z-10">
                <button type="button" class="carousel-next inline-flex items-center justify-center w-9 h-9 rounded-full bg-white shadow border border-gray-200" data-target="#featured-cars">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div id="featured-cars" class="flex md:grid overflow-x-auto md:overflow-visible snap-x snap-mandatory md:snap-none gap-4 sm:gap-6 lg:gap-8 md:grid-cols-3 xl:grid-cols-4 no-scrollbar">
                @foreach($featuredVariants as $variant)
                <div class="snap-start shrink-0 w-[78%] xs:w-[70%] sm:w-[60%] md:w-auto">
                    @include('components.variant-card', ['variant' => $variant, 'showCompare' => true])
                </div>
                @endforeach
            </div>
        </div>

        <!-- View All Cars Button -->
        <div class="text-center mt-12">
            <a href="{{ route('products.index') }}"
                class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-full hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                <span>Xem tất cả xe</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</section>

{{-- Reviews moved below Accessories --}}

{{-- ===== Featured Accessories Section ===== --}}
@if(isset($featuredAccessories) && $featuredAccessories->count())
<section id="featured-accessories" class="py-20 sm:py-28 bg-gradient-to-b from-white to-slate-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 sm:mb-20">
            <div class="inline-flex items-center px-4 py-2 bg-amber-100 text-amber-800 rounded-full text-sm font-medium mb-4">
                <i class="fas fa-puzzle-piece mr-2"></i>
                Phụ kiện nổi bật
            </div>
            <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">Phụ kiện nổi bật</h2>
            <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Sản phẩm chính hãng, tương thích đa dạng mẫu xe, sẵn sàng giao nhanh
            </p>
        </div>
        <div class="relative">
            <div class="md:hidden absolute -left-3 top-1/2 -translate-y-1/2 z-10">
                <button type="button" class="carousel-prev inline-flex items-center justify-center w-9 h-9 rounded-full bg-white shadow border border-gray-200" data-target="#featured-accs">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
            <div class="md:hidden absolute -right-3 top-1/2 -translate-y-1/2 z-10">
                <button type="button" class="carousel-next inline-flex items-center justify-center w-9 h-9 rounded-full bg-white shadow border border-gray-200" data-target="#featured-accs">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div id="featured-accs" class="flex md:grid overflow-x-auto md:overflow-visible snap-x snap-mandatory md:snap-none gap-4 sm:gap-6 lg:gap-8 md:grid-cols-3 xl:grid-cols-4 no-scrollbar">
                @foreach($featuredAccessories as $acc)
                <div class="snap-start shrink-0 w-[78%] xs:w-[70%] sm:w-[60%] md:w-auto">
                    @include('components.accessory-card', ['accessory' => $acc])
                </div>
                @endforeach
            </div>
        </div>
        <div class="text-center mt-12">
            <a href="{{ route('products.index', ['type' => 'accessory']) }}"
                class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-amber-500 to-rose-500 text-white font-semibold rounded-full hover:from-amber-600 hover:to-rose-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                <span>Xem tất cả phụ kiện</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ===== Promotions Section (after accessories) ===== --}}
@if(isset($promotions) && $promotions->count())
<section id="promotions" class="py-16 sm:py-20 bg-gradient-to-b from-slate-50 to-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8 sm:mb-12">
            <div>
                <div class="inline-flex items-center px-4 py-2 bg-amber-100 text-amber-800 rounded-full text-sm font-medium mb-3">
                    <i class="fas fa-tags mr-2"></i>
                    Ưu đãi hiện hành
                </div>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Khuyến mãi hấp dẫn</h2>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-amber-700 font-semibold hover:text-amber-800">
                Xem xe áp dụng <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
            @foreach($promotions as $promo)
            <div class="bg-white rounded-2xl border border-amber-100 shadow p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $promo->name }}</h3>
                        @if($promo->code)
                        <div class="mt-1 inline-flex items-center gap-2 text-xs font-semibold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full">
                            Mã: {{ $promo->code }}
                        </div>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-extrabold text-amber-600">
                            @if($promo->type === 'percentage')
                                -{{ (int) $promo->discount_value }}%
                            @else
                                -{{ number_format((int) $promo->discount_value, 0, ',', '.') }}₫
                            @endif
                        </div>
                        <div class="text-xs text-gray-500">{{ optional($promo->start_date)->format('d/m') }} - {{ optional($promo->end_date)->format('d/m') }}</div>
                    </div>
                </div>
                @if($promo->description)
                <p class="mt-3 text-sm text-gray-700 line-clamp-2">{{ $promo->description }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    </section>
@endif

{{-- ===== Reviews Section (moved) ===== --}}
@if(isset($recentReviews) && $recentReviews->count())
<section id="reviews" class="py-20 sm:py-28 bg-gradient-to-b from-white to-slate-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 sm:mb-16">
            <div class="inline-flex items-center px-4 py-2 bg-amber-100 text-amber-800 rounded-full text-sm font-medium mb-4">
                <i class="fas fa-star mr-2"></i>
                Đánh giá mới nhất
            </div>
            <h2 class="text-4xl sm:text-5xl font-bold text-gray-900">Khách hàng nói gì</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @foreach($recentReviews as $review)
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">{{ optional($review->user)->name ?? 'Khách hàng' }}</div>
                            <div class="text-xs text-gray-500">{{ $review->created_at->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    <div class="text-yellow-400">
                        {!! $review->stars !!}
                    </div>
                </div>
                <div class="text-gray-700 line-clamp-3">{{ $review->comment }}</div>
                @php($rv = $review->reviewable)
                @if($rv)
                <div class="mt-4 text-sm text-gray-600">
                    @if($review->reviewable_type === \App\Models\CarVariant::class)
                    @php($brand = optional(optional($rv->carModel)->carBrand)->name)
                    @php($model = optional($rv->carModel)->name)
                    @php($firstImg = ($rv->images && $rv->images->count()) ? ($rv->images->first()) : null)
                    @php($rawSrc = $firstImg ? ($firstImg->image_url ?? ($firstImg->image_path ?? ($firstImg->path ?? null))) : null)
                    @php($isAbs = $rawSrc && (str_starts_with($rawSrc, 'http://') || str_starts_with($rawSrc, 'https://')))
                    @php($thumb = $rawSrc ? ($isAbs ? $rawSrc : asset('storage/'.$rawSrc)) : null)
                    <div class="flex items-center gap-2">
                        <span class="text-gray-500">Về:</span>
                        @if($thumb)
                        <img src="{{ $thumb }}" alt="Ảnh xe" class="w-8 h-8 rounded object-cover border border-gray-200" loading="lazy">
                        @else
                        <span class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center text-gray-400 border border-gray-200"><i class="fas fa-car text-[11px]"></i></span>
                        @endif
                        <a href="{{ route('car-variants.show', $rv->id) }}" class="font-medium text-gray-800 hover:text-indigo-600 truncate">
                            {{ trim(($brand ? $brand.' ' : '').($model ? $model.' • ' : '').($rv->name ?? '')) }}
                        </a>
                    </div>
                    @elseif($review->reviewable_type === \App\Models\Accessory::class)
                    @php($accRaw = $rv->image_url ?? ($rv->main_image_path ?? ($rv->image_path ?? null)))
                    @php($accAbs = $accRaw && (str_starts_with($accRaw, 'http://') || str_starts_with($accRaw, 'https://')))
                    @php($accThumb = $accRaw ? ($accAbs ? $accRaw : asset('storage/'.$accRaw)) : null)
                    <div class="flex items-center gap-2">
                        <span class="text-gray-500">Về:</span>
                        @if($accThumb)
                        <img src="{{ $accThumb }}" alt="Ảnh phụ kiện" class="w-8 h-8 rounded object-cover border border-gray-200" loading="lazy">
                        @else
                        <span class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center text-gray-400 border border-gray-200"><i class="fas fa-puzzle-piece text-[11px]"></i></span>
                        @endif
                        <a href="{{ route('accessories.show', $rv->id) }}" class="font-medium text-gray-800 hover:text-indigo-600 truncate">
                            {{ $rv->name ?? 'Phụ kiện' }}
                        </a>
                    </div>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ===== Our Showrooms Section (moved before Services) ===== --}}
@if(isset($showrooms) && $showrooms->count())
<section id="showrooms" class="py-20 sm:py-28 bg-gradient-to-b from-slate-50 to-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">Hệ thống Showroom</h2>
            <p class="text-lg text-gray-600">Liên hệ tư vấn – lái thử – bảo dưỡng</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @foreach($showrooms as $s)
            <div class="bg-white rounded-2xl shadow p-6 border">
                <h3 class="text-xl font-semibold text-gray-900">{{ $s->name }}</h3>
                <p class="text-gray-600 mt-1">{{ $s->full_address ?? ( ($s->address ?? '') . ( $s->city ? ', '.$s->city : '') ) }}</p>
                <div class="mt-3 text-sm text-gray-700 space-y-1">
                    @if($s->phone)
                    <div><i class="fas fa-phone mr-2 text-gray-500"></i>{{ $s->phone }}</div>
                    @endif
                    @if($s->email)
                    <div><i class="fas fa-envelope mr-2 text-gray-500"></i>{{ $s->email }}</div>
                    @endif
                    @if($s->opening_time && $s->closing_time)
                    <div><i class="fas fa-clock mr-2 text-gray-500"></i>Mở cửa: {{ $s->opening_time }} - {{ $s->closing_time }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ===== Latest News Section ===== --}}
<section class="py-20 sm:py-28 bg-gradient-to-b from-white to-slate-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 sm:mb-20">
            <div class="inline-flex items-center px-4 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-medium mb-4">
                <i class="fas fa-newspaper mr-2"></i>
                Tin tức mới nhất
            </div>
            <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">Tin tức & Sự kiện</h2>
            <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Cập nhật những tin tức mới nhất về ngành ô tô, đánh giá xe mới và các sự kiện đặc biệt
            </p>
        </div>

        <!-- News Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($blogs as $blog)
            @include('components.blog-card', ['blog' => $blog])
            @endforeach
        </div>

        <!-- View All News Button -->
        <div class="text-center mt-12">
            <a href="{{ route('blogs.index') }}"
                class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-full hover:from-purple-700 hover:to-pink-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                <span>Xem tất cả tin tức</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</section>

{{-- Promotions moved above; old block removed --}}

{{-- ===== CTA Section ===== --}}
<section class="py-20 sm:py-28 bg-gradient-to-br from-slate-900 via-neutral-900 to-black">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl sm:text-5xl font-bold text-white mb-6">
            Sẵn sàng sở hữu xe mơ ước?
        </h2>
        <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto leading-relaxed">
            Hãy để chúng tôi giúp bạn tìm được chiếc xe hoàn hảo với dịch vụ chuyên nghiệp và giá cả hợp lý
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('products.index') }}"
                class="group bg-white text-slate-900 px-8 py-4 rounded-full font-bold text-lg transition-all duration-300 hover:bg-purple-100 hover:scale-[1.02] shadow-xl">
                <i class="fas fa-car mr-2 group-hover:rotate-12 transition-transform"></i>
                Khám phá xe ngay
            </a>
            <a href="{{ route('contact') }}"
                class="border-2 border-white/30 text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-white/10 transition-all duration-300 backdrop-blur-sm">
                <i class="fas fa-phone mr-2"></i>
                Liên hệ tư vấn
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Compare logic moved to layout (global)

    

    // Toast notification function
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium shadow-lg transform translate-x-full transition-transform duration-300`;

        if (type === 'success') {
            toast.className += ' bg-green-500';
        } else if (type === 'error') {
            toast.className += ' bg-red-500';
        } else {
            toast.className += ' bg-blue-500';
        }

        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.remove('translate-x-full');
        }, 100);

        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
            }
        });
    }, observerOptions);

    // Observe all sections
    document.querySelectorAll('section').forEach(section => {
        observer.observe(section);
    });

    // Carousel controls
    document.querySelectorAll('.carousel-prev, .carousel-next').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetSel = btn.getAttribute('data-target');
            const container = document.querySelector(targetSel);
            if (!container) return;
            const card = container.querySelector('> div');
            const delta = (card ? card.clientWidth + 16 : 280) * (btn.classList.contains('carousel-next') ? 1 : -1);
            container.scrollBy({ left: delta, behavior: 'smooth' });
        });
    });

    // Toggle quick search on mobile
    document.getElementById('toggle-quick-search')?.addEventListener('click', function() {
        const el = document.getElementById('quick-search-card');
        if (!el) return;
        const isHidden = el.classList.contains('hidden');
        if (isHidden) {
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    });

    // Test drive form removed
</script>
@endpush

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-clamp: 2;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-clamp: 3;
    }

    .animate-fade-in {
        animation: fadeInUp 0.8s ease-out forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Hover effects for cards */
    .group:hover .group-hover\:scale-110 {
        transform: scale(1.1);
    }

    /* Smooth transitions - chỉ áp dụng cho các element cụ thể */
    .transition-smooth {
        transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }

    /* Horizontal scroll helpers */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush