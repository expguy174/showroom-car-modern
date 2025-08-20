@extends('layouts.app')

@section('title', ($carModel->carBrand->name ?? '') . ' ' . $carModel->name)

@section('content')
@php
    $brand = $carModel->carBrand;
    $mainImage = $carModel->image_url ?? ($gallery[0] ?? null);
    $minPrice = $stats['price_range']['min'] ?? null;
    $maxPrice = $stats['price_range']['max'] ?? null;
    $avgRating = $stats['average_rating'] ?? null;
    $ratingCount = $stats['rating_count'] ?? 0;
    $fuelTypes = $stats['fuel_types'] ?? collect();
    $transmissions = $stats['transmissions'] ?? collect();
    $seats = $stats['seating_capacities'] ?? collect();
@endphp

<!-- Hero / Intro -->
<section class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-900 to-slate-900">
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
                    <a href="{{ route('brands.index') }}" class="hover:text-white transition-colors duration-200">Hãng xe</a>
                </li>
                <li class="text-gray-400">/</li>
                <li>
                    @if($brand)
                        <a href="{{ route('brands.show', $brand->id) }}" class="hover:text-white transition-colors duration-200">{{ $brand->name }}</a>
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
                                <div class="flex-shrink-0 w-24 h-16 sm:w-28 sm:h-20 rounded-xl overflow-hidden border border-white/20 cursor-pointer hover:scale-[1.02] transition-transform duration-200">
                                    <img data-src="{{ $img }}" alt="{{ $carModel->name }}" class="w-full h-full object-cover lazy-image">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Heading / Meta -->
            <div class="order-1 lg:order-2">
                <div class="flex items-center gap-4 mb-5">
                    @if($brand && $brand->logo_url)
                        <div class="w-14 h-14 rounded-2xl bg-white/90 backdrop-blur ring-2 ring-white/30 shadow flex items-center justify-center overflow-hidden">
                            <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="w-12 h-12 object-contain" loading="lazy" decoding="async">
                        </div>
                    @endif
                    <div class="min-w-0">
                        <h1 class="text-3xl sm:text-4xl xl:text-5xl font-extrabold text-white tracking-tight">{{ $brand->name ?? '' }} {{ $carModel->name }}</h1>
                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            @if($minPrice)
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-green-400/20 text-green-100 text-sm font-semibold border border-green-400/30"><i class="fas fa-tag"></i>Từ {{ number_format($minPrice, 0, ',', '.') }}₫</span>
                            @endif
                            @if(!empty($avgRating))
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-400/20 text-amber-100 text-sm font-semibold border border-amber-400/30"><i class="fas fa-star"></i>{{ number_format($avgRating, 1) }} @if($ratingCount) <span class="opacity-80">({{ number_format($ratingCount) }})</span> @endif</span>
                            @endif
                            @if($carModel->is_new)
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-400/20 text-indigo-100 text-sm font-semibold border border-indigo-400/30"><i class="fas fa-bolt"></i>Mới</span>
                            @endif
                            @if($carModel->is_featured)
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-purple-400/20 text-purple-100 text-sm font-semibold border border-purple-400/30"><i class="fas fa-star"></i>Nổi bật</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if(!empty($carModel->description))
                    <p class="text-gray-200/90 leading-relaxed mb-6">{{ $carModel->description }}</p>
                @endif

                <!-- Quick specs -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 mb-6">
                    @if(!empty($carModel->body_type))
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10 text-white">
                        <i class="fas fa-car text-white/80"></i>
                        <div>
                            <div class="text-xs text-white/70">Kiểu dáng</div>
                            <div class="text-sm font-semibold">{{ $carModel->body_type }}</div>
                        </div>
                    </div>
                    @endif
                    @if($fuelTypes && $fuelTypes->count())
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10 text-white">
                        <i class="fas fa-gas-pump text-white/80"></i>
                        <div>
                            <div class="text-xs text-white/70">Nhiên liệu</div>
                            <div class="text-sm font-semibold uppercase">{{ strtoupper(implode(', ', $fuelTypes->toArray())) }}</div>
                        </div>
                    </div>
                    @endif
                    @if($transmissions && $transmissions->count())
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10 text-white">
                        <i class="fas fa-cogs text-white/80"></i>
                        <div>
                            <div class="text-xs text-white/70">Hộp số</div>
                            <div class="text-sm font-semibold uppercase">{{ strtoupper(implode(', ', $transmissions->toArray())) }}</div>
                        </div>
                    </div>
                    @endif
                    @if($seats && $seats->count())
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10 text-white">
                        <i class="fas fa-users text-white/80"></i>
                        <div>
                            <div class="text-xs text-white/70">Số chỗ</div>
                            <div class="text-sm font-semibold">{{ implode(', ', $seats->toArray()) }}</div>
                        </div>
                    </div>
                    @endif
                    @if(!empty($carModel->production_start_year))
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10 text-white">
                        <i class="fas fa-calendar-alt text-white/80"></i>
                        <div>
                            <div class="text-xs text-white/70">Năm ra mắt</div>
                            <div class="text-sm font-semibold">{{ $carModel->production_start_year }}@if(!empty($carModel->production_end_year)) – {{ $carModel->production_end_year }} @endif</div>
                        </div>
                    </div>
                    @endif
                </div>

                @if(($highlightSpecs ?? []) && count($highlightSpecs))
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($highlightSpecs as $label => $val)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10 text-white">
                        <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center">
                            <i class="fas fa-info text-white/80"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs text-white/70">{{ $label }}</div>
                            <div class="text-sm font-semibold truncate">{{ $val }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                @if(($aggregatedColors ?? []) && count($aggregatedColors))
                @php
                    $__showColors = array_slice($aggregatedColors, 0, 8);
                @endphp
                <div class="mt-4">
                    <div class="text-xs text-white/70 mb-2">Màu ngoại thất</div>
                    <div class="flex flex-wrap items-center gap-3">
                        @foreach($__showColors as $c)
                        @php
                            $hexClr = $c['hex'] ?? null;
                            if ($hexClr) { $hexClr = '#' . ltrim($hexClr, '#'); } else { $hexClr = '#e5e7eb'; }
                        @endphp
                        <div class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-full bg-white/5 border border-white/10">
                            <span class="w-3.5 h-3.5 rounded-full border js-color-dot" data-color="{{ $hexClr }}"></span>
                            <span class="text-xs text-white/90">{{ $c['name'] }}</span>
                        </div>
                        @endforeach
                        @if(count($aggregatedColors) > count($__showColors))
                            <span class="text-xs text-white/80">+{{ count($aggregatedColors) - count($__showColors) }} màu</span>
                        @endif
                    </div>
                </div>
                @endif

                @if(($featuresByCategory ?? []) && count($featuresByCategory))
                @php
                    $_flat = [];
                    foreach ($featuresByCategory as $cat => $items) { foreach ($items as $it) { $_flat[] = $it; } }
                    $_flat = array_slice(array_values(array_unique($_flat)), 0, 8);
                @endphp
                @if(count($_flat))
                <div class="mt-4">
                    <div class="text-xs text-white/70 mb-2">Tính năng nổi bật</div>
                    <div class="flex flex-wrap items-center gap-2">
                        @foreach($_flat as $f)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full bg-white/5 border border-white/10 text-[12px] text-white/90"><i class="fas fa-check text-emerald-300"></i>{{ $f }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
                @endif

                <!-- CTAs -->
                <div class="mt-5 flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <a href="#variants" class="action-btn action-primary">
                        <i class="fas fa-layer-group"></i>
                        <span>Xem các phiên bản</span>
                    </a>
                    <a href="{{ route('test_drives.index') }}" class="action-btn action-ghost">
                        <i class="fas fa-steering-wheel"></i>
                        <span>Đặt lái thử</span>
                    </a>
                    <a href="{{ route('contact') }}" class="action-btn action-ghost">
                        <i class="fas fa-phone"></i>
                        <span>Nhận tư vấn/Báo giá</span>
                    </a>
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
                    <div class="group transform hover:-translate-y-2 transition-all duration-300">
                        @include('components.variant-card', ['variant' => $variant, 'showCompare' => true])
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    </section>

@if(($compareMatrix ?? []) && count($compareMatrix) && ($variants ?? collect())->count() > 0)
<section id="compare" class="py-12 sm:py-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-4 mb-6 sm:mb-8">
            <div>
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-900">Bảng so sánh nhanh</h3>
                <p class="text-gray-600 mt-1">Đối chiếu các thông số chính giữa các phiên bản</p>
            </div>
        </div>
        <div class="overflow-x-auto rounded-xl border border-gray-200">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 sm:p-4 text-left text-gray-600 font-semibold min-w-[160px]">Thông số</th>
                        @foreach($variants as $variant)
                            <th class="p-3 sm:p-4 text-left text-gray-600 font-semibold min-w-[200px]">
                                <div class="text-gray-900 font-bold truncate">{{ $variant->name }}</div>
                                <div class="text-[12px] text-indigo-600 font-semibold">@if($variant->final_price) {{ number_format($variant->final_price, 0, ',', '.') }}₫ @else Liên hệ @endif</div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($compareMatrix as $label => $row)
                        <tr>
                            <td class="p-3 sm:p-4 font-medium bg-gray-50 text-gray-800">{{ $label }}</td>
                            @foreach($row as $val)
                                <td class="p-3 sm:p-4 text-gray-800">{{ $val }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endif

@php /* Đã đưa Màu sắc và Tính năng lên phần giới thiệu để gọn hơn */ @endphp

@if(($featuredVariants ?? collect())->count() > 0)
<section id="featured" class="py-16 sm:py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 sm:mb-12">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 text-sm font-semibold mb-4"><i class="fas fa-star mr-2"></i>Phiên bản nổi bật</div>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Được quan tâm nhiều</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8">
            @foreach($featuredVariants as $variant)
                <div class="group transform hover:-translate-y-2 transition-all duration-300">
                    @include('components.variant-card', ['variant' => $variant, 'showCompare' => true])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if(($relatedModels ?? collect())->count() > 0)
<section id="related" class="py-16 sm:py-20 bg-gradient-to-b from-gray-50 to-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 sm:mb-12">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-slate-100 text-slate-700 text-sm font-semibold mb-4"><i class="fas fa-car-side mr-2"></i>Mẫu liên quan</div>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Các dòng xe khác của {{ $brand->name ?? 'hãng' }}</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8">
            @foreach($relatedModels as $m)
            <a href="{{ route('car_models.show', $m->id) }}" class="group block bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                <div class="relative aspect-[4/3]">
                    <img data-src="{{ $m->image_url }}" alt="{{ $m->name }}" class="absolute inset-0 w-full h-full object-cover lazy-image">
                    <div class="absolute inset-0 skeleton-image"></div>
                </div>
                <div class="p-4">
                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-1">
                        <span>{{ $brand->name ?? '' }}</span>
                        <span class="text-gray-400">•</span>
                        <span>{{ $m->carVariants?->count() ?? 0 }} phiên bản</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 line-clamp-1 group-hover:text-indigo-600 transition-colors">{{ $m->name }}</h3>
                    <div class="mt-2 text-sm text-gray-700">
                        @if(!empty($m->starting_price))
                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100"><i class="fas fa-tag"></i>{{ number_format($m->starting_price, 0, ',', '.') }}₫</span>
                        @else
                            <span class="text-gray-500">Giá: Liên hệ</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
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
    function swapToFirstThumb(){
      if (!thumbsWrap) return;
      var firstImg = thumbsWrap.querySelector('img');
      var src = firstImg ? (firstImg.getAttribute('data-src') || firstImg.getAttribute('src')) : null;
      if (hero && src) {
        hero.src = src;
      }
    }
    if (hero) {
      hero.addEventListener('error', swapToFirstThumb, { once: true });
      // If src is empty or a skeleton overlay remains after 500ms, fallback
      setTimeout(function(){
        try {
          var hasSize = hero.naturalWidth > 0 && hero.naturalHeight > 0;
          if (!hasSize) swapToFirstThumb();
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


