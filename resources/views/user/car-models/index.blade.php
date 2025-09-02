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

        <div class="grid grid-cols-1 lg:grid-cols-[1.1fr,0.9fr] items-center gap-8 lg:gap-12">
            <!-- Visual / Gallery preview -->
            <div class="order-2 lg:order-1">
                <div class="relative rounded-3xl overflow-hidden bg-white/5 border border-white/10 shadow-2xl">
                    <div class="relative aspect-[16/9] sm:aspect-[2/1]">
                        @if($mainImage)
                            <img id="model-hero-image" src="{{ $mainImage }}" data-src="{{ $mainImage }}" alt="{{ $carModel->name }}" class="absolute inset-0 w-full h-full object-cover lazy-image">
                            <div class="absolute inset-0 skeleton-image"></div>
                        @else
                            <div class="absolute inset-0 image-error">
                                <i class="fas fa-car-side text-2xl"></i>
                            </div>
                        @endif
                    </div>
                    @if(!empty($gallery))
                    <div class="px-4 sm:px-6 pb-4 sm:pb-6 pt-3">
                        <div id="model-hero-thumbs" class="flex items-center gap-3 overflow-x-auto scrollbar-hide">
                            @foreach($gallery as $img)
                                <button type="button" class="js-hero-thumb flex-shrink-0 w-24 h-16 sm:w-28 sm:h-20 rounded-xl overflow-hidden border border-white/20 cursor-pointer hover:scale-[1.02] transition-transform duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                    data-src="{{ $img }}" aria-label="Chọn ảnh {{ $loop->iteration }}">
                                    <img src="{{ $img }}" alt="{{ $carModel->name }}" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Heading / Meta -->
            <div class="order-1 lg:order-2">
                <div class="grid grid-cols-1 xl:grid-cols-[auto,1fr] items-center gap-6 xl:gap-8">
                    <!-- Logo -->
                    <div class="flex items-center justify-center xl:justify-start">
                        @if($brand && $brand->logo_url)
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-white/95 backdrop-blur-sm ring-2 ring-white/20 shadow-2xl flex items-center justify-center overflow-hidden">
                                <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="w-16 h-16 sm:w-20 sm:h-20 object-contain" loading="lazy" decoding="async">
                            </div>
                        @else
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-white/95 backdrop-blur-sm ring-2 ring-white/20 shadow-2xl flex items-center justify-center">
                                <i class="fas fa-car text-gray-700 text-2xl sm:text-3xl"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Basic Info -->
                    <div class="min-w-0 text-center xl:text-left">
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                            {{ $brand->name ?? '' }} {{ $carModel->name }}
                        </h1>

                        @if(!empty($carModel->description))
                            <p class="mt-3 text-gray-200 leading-relaxed max-w-2xl xl:max-w-3xl">
                                {{ $carModel->description }}
                            </p>
                        @endif

                        <!-- Simple badges -->
                        <div class="mt-4 flex flex-wrap items-center justify-center xl:justify-start gap-2">
                            @php $hasBody = !empty($carModel->body_type); @endphp
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 text-white text-sm font-medium border border-white/20">
                                <i class="fas fa-car"></i>
                                {{ $hasBody ? $carModel->body_type : 'Kiểu dáng: Đang cập nhật' }}
                            </span>
                            @if(!empty($carModel->segment))
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 text-white text-sm font-medium border border-white/20">
                                    <i class="fas fa-layer-group"></i>
                                    {{ $carModel->segment }}
                                </span>
                            @endif
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 text-white text-sm font-medium border border-white/20">
                                <i class="fas fa-calendar-alt"></i>
                                @if(!empty($carModel->production_start_year) || !empty($carModel->production_end_year))
                                    {{ $carModel->production_start_year }}@if(!empty($carModel->production_end_year)) – {{ $carModel->production_end_year }} @endif
                                @else
                                    Năm ra mắt: Đang cập nhật
                                @endif
                            </span>
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 text-white text-sm font-medium border border-white/20">
                                <i class="fas fa-history"></i>
                                {{ !empty($carModel->generation) ? $carModel->generation : 'Thế hệ: Đang cập nhật' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sticky section nav -->
    <div class="border-t border-white/10 bg-white/5 backdrop-blur-md">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 text-sm text-white/90">
                <a href="#variants" class="flex items-center gap-2 px-3 py-2 rounded-full bg-white/10 hover:bg-white/20 transition-all duration-200 border border-white/20 hover:border-white/30"><i class="fas fa-layer-group"></i><span>Phiên bản</span></a>
                @if(($featuredVariants ?? collect())->count() > 0)
                <a href="#featured" class="flex items-center gap-2 px-3 py-2 rounded-full bg-white/10 hover:bg-white/20 transition-all duration-200 border border-white/20 hover:border-white/30"><i class="fas fa-star"></i><span>Nổi bật</span></a>
                @endif
                @if(($relatedModels ?? collect())->count() > 0)
                <a href="#related" class="flex items-center gap-2 px-3 py-2 rounded-full bg-white/10 hover:bg-white/20 transition-all duration-200 border border-white/20 hover:border-white/30"><i class="fas fa-car-side"></i><span>Mẫu liên quan</span></a>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Variants -->
<section id="variants" class="py-16 sm:py-20 bg-gradient-to-b from-white to-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 sm:mb-12">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-100 text-indigo-700 text-sm font-semibold mb-4">
                <i class="fas fa-layer-group mr-2"></i>
                Các phiên bản của {{ $carModel->name }}
            </div>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Chọn phiên bản phù hợp</h2>
            @if($minPrice)
                <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto mt-3">Giá tham khảo từ {{ number_format($minPrice, 0, ',', '.') }}₫ @if($maxPrice && $maxPrice > $minPrice) đến {{ number_format($maxPrice, 0, ',', '.') }}₫ @endif</p>
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
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8">
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
        <div class="text-center mb-10 sm:mb-12">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 text-sm font-semibold mb-4"><i class="fas fa-star mr-2"></i>Phiên bản nổi bật</div>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Được quan tâm nhiều</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8">
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
        <div class="text-center mb-10 sm:mb-12">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-slate-100 text-slate-700 text-sm font-semibold mb-4"><i class="fas fa-car-side mr-2"></i>Mẫu liên quan</div>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Các dòng xe khác của {{ $brand->name ?? 'hãng' }}</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8">
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
        <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4">Sẵn sàng trải nghiệm {{ $carModel->name }}?</h2>
        <p class="text-lg text-gray-300 mb-8 max-w-2xl mx-auto">Liên hệ tư vấn hoặc đặt lịch lái thử để cảm nhận thực tế.</p>
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
    /* Compact tweaks for very small screens */
    @media (max-width: 360px){
        .action-btn { padding: .6rem .75rem !important; font-size: .85rem !important; }
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
      if (!src) return;
      if (skeleton) skeleton.style.display = 'block';
      hero.classList.remove('loaded');
      hero.src = src;
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
      // If src is empty or a skeleton overlay remains after 500ms, fallback
      setTimeout(function(){
        try {
          var hasSize = hero.naturalWidth > 0 && hero.naturalHeight > 0;
          if (!hasSize) swapToFirstThumb();
          else { if (skeleton) skeleton.style.display='none'; hero.classList.add('loaded'); }
        } catch(e) {}
      }, 500);
    }
  } catch(e) {}
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
</script>
@endpush


