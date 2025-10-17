@props(['accessory', 'showCompare' => false])

@php
$galleryRaw = $accessory->gallery;
$gallery = is_array($galleryRaw) ? $galleryRaw : (json_decode($galleryRaw ?? '[]', true) ?: []);

// Extract image URL from gallery
$img = null;
if (!empty($gallery) && isset($gallery[0])) {
    $firstImage = $gallery[0];
    if (is_array($firstImage)) {
        // New format: array with url/file_path
        $img = $firstImage['url'] ?? $firstImage['file_path'] ?? null;
        // If file_path exists, prepend storage path
        if (!empty($img) && !str_starts_with($img, 'http') && !str_starts_with($img, '/storage/')) {
            $img = asset('storage/' . $img);
        }
    } elseif (is_string($firstImage)) {
        // Old format: direct URL string
        $img = $firstImage;
    }
}

$name = $accessory->name ?? 'N/A';
$price = (float) ($accessory->current_price ?? 0);
$originalPrice = (float) ($accessory->base_price ?? $price);
$hasDiscount = ($originalPrice > 0) && ($price > 0) && ($originalPrice > $price);
$computedDiscount = $hasDiscount ? (int) round((($originalPrice - $price) / max($originalPrice,1)) * 100) : 0;
$finalPrice = $price;
@endphp

<div class="accessory-card group card-surface overflow-hidden h-full flex flex-col min-h-fit">
  <div class="relative">
    <a href="{{ route('accessories.show', $accessory->id) }}" class="block">
      @if($img)
      <div class="card-media w-full aspect-[4/3]">
        <img src="{{ $img }}" alt="{{ $name }}" class="card-img" loading="lazy" decoding="async" width="800" height="600" onerror="this.onerror=null;this.src='https://via.placeholder.com/800x600?text=No+Image';">
        <span class="card-overlay"></span>
        <span class="card-sheen"></span>
      </div>
      @else
      <div class="relative w-full aspect-[4/3] bg-gray-200 flex items-center justify-center" role="img" aria-label="No image">
        <i class="fas fa-puzzle-piece text-gray-400 text-3xl"></i>
      </div>
      @endif
    </a>

    <!-- Top Left Badges - Maximum 3 badges based on database fields -->
    <div class="absolute top-3 left-3 flex flex-col gap-2 pointer-events-none">
      @php
      $badgeCount = 0;
      $maxBadges = 3;
      @endphp

      <!-- Bestseller Badge (Priority 1) - is_bestseller -->
      @if(($accessory->is_bestseller ?? false) && $badgeCount < $maxBadges)
        <div class="bg-gradient-to-r from-amber-500 to-orange-500 text-white text-[10px] px-2.5 py-1 rounded-full inline-flex items-center gap-1 leading-none font-bold shadow-lg">
        <i class="fas fa-fire text-[12px] leading-none"></i> Bán chạy
    </div>
    @php $badgeCount++; @endphp
    @endif

    <!-- Featured Badge (Priority 2) - is_featured -->
    @if(($accessory->is_featured ?? false) && $badgeCount < $maxBadges)
      <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white text-[10px] px-2.5 py-1 rounded-full inline-flex items-center gap-1 leading-none font-bold shadow-lg">
      <i class="fas fa-star text-[12px] leading-none"></i> Nổi bật
  </div>
  @php $badgeCount++; @endphp
  @endif

  <!-- Sale Badge (Priority 3) - has_discount -->
  @if($hasDiscount && $badgeCount < $maxBadges)
    <div class="bg-gradient-to-r from-red-500 to-red-600 text-white text-[10px] px-2.5 py-1 rounded-full inline-flex items-center gap-1 leading-none font-bold shadow-lg">
    <i class="fas fa-tag text-[12px] leading-none"></i> Giảm giá
</div>
@php $badgeCount++; @endphp
@endif

<!-- New Badge (Priority 4) - is_new -->
@if(($accessory->is_new ?? false) && $badgeCount < $maxBadges)
  <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white text-[10px] px-2.5 py-1 rounded-full inline-flex items-center gap-1 leading-none font-bold shadow-lg">
  <i class="fas fa-certificate text-[12px] leading-none"></i> Mới
  </div>
  @php $badgeCount++; @endphp
  @endif
  </div>

  <!-- Top Right Action Buttons - All with consistent spacing -->
  <div class="absolute top-3 right-3 flex flex-col items-end z-10 gap-2">

    <!-- Wishlist Button - Always visible -->
    @php
      $__inWishlistAcc = \App\Helpers\WishlistHelper::isInWishlist('accessory', $accessory->id);
    @endphp
    <button type="button"
            class="w-9 h-9 sm:w-10 sm:h-10 inline-flex items-center justify-center bg-white/90 hover:bg-white border-2 border-gray-200 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 js-wishlist-toggle {{ $__inWishlistAcc ? 'in-wishlist' : 'not-in-wishlist' }}"
            aria-label="Yêu thích" title="Yêu thích" aria-pressed="{{ $__inWishlistAcc ? 'true' : 'false' }}"
            data-item-type="accessory" data-item-id="{{ $accessory->id }}">
      <i class="{{ $__inWishlistAcc ? 'fas text-red-500' : 'far text-gray-700' }} fa-heart text-sm sm:text-base"></i>
    </button>

    <!-- Compare Button (if enabled) - Hidden by default, visible on hover -->
    @if($showCompare)
    @php $compareList = json_decode(request()->cookie('compare_accessories', '[]'), true) ?: []; @endphp
    <button type="button" class="w-9 h-9 sm:w-10 sm:h-10 inline-flex items-center justify-center bg-white/90 hover:bg-white border-2 {{ in_array($accessory->id, $compareList) ? 'border-indigo-300' : 'border-gray-200' }} rounded-full shadow-lg hover:shadow-xl transition-all duration-300 js-compare-toggle opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto" aria-label="So sánh" title="So sánh" data-accessory-id="{{ $accessory->id }}">
      <i class="fas fa-balance-scale {{ in_array($accessory->id, $compareList) ? 'text-indigo-600' : 'text-gray-700' }} text-sm sm:text-base"></i>
    </button>
    @endif

    <!-- Share Button - Hidden by default, visible on hover -->
    <button type="button" class="w-9 h-9 sm:w-10 sm:h-10 inline-flex items-center justify-center bg-white/90 hover:bg-white border-2 border-gray-200 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 js-share-variant opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto" aria-label="Chia sẻ phụ kiện" title="Chia sẻ" data-variant-name="{{ $name }}" data-share-url="{{ route('accessories.show', $accessory->id) }}">
      <i class="fas fa-share-alt text-gray-700 text-sm sm:text-base"></i>
    </button>
  </div>
  </div>

  <div class="p-4 sm:p-5 flex flex-col justify-between flex-1 space-y-3 sm:space-y-4">
    <!-- Brand Row -->
    <div class="flex items-center gap-2">
      @if(!empty($accessory->brand))
      <span class="text-xs sm:text-sm text-gray-700 font-medium">{{ $accessory->brand }}</span>
      @else
      <span class="text-xs sm:text-sm text-gray-500 font-medium">Phụ kiện</span>
      @endif
    </div>

    <!-- Product Name (Main Title) -->
    <a href="{{ route('accessories.show', $accessory->id) }}" class="text-base sm:text-lg font-bold text-gray-900 hover:text-indigo-600 transition-colors duration-300 block line-clamp-2" aria-label="Xem phụ kiện {{ $name }}">
      {{ $name }}
    </a>

    <!-- Rating Row -->
    @php($accAvg = $accessory->approved_reviews_avg)
    @php($accCount = $accessory->approved_reviews_count)
    @if(!is_null($accAvg) && $accAvg > 0)
    <div class="flex items-center gap-2">
      <span class="inline-flex items-center gap-1 text-xs sm:text-sm text-amber-500" title="Đánh giá">
        <i class="fas fa-star"></i>
        <span class="font-semibold text-gray-800">{{ number_format($accAvg, 1) }}</span>
        @if(!is_null($accCount) && $accCount > 0)
        <span class="text-gray-500">({{ number_format($accCount) }} đánh giá)</span>
        @endif
      </span>
    </div>
    @else
    <div class="flex items-center gap-2">
      <span class="inline-flex items-center gap-1 text-xs sm:text-sm text-gray-400" title="Chưa có đánh giá">
        <i class="far fa-star"></i>
        <span class="text-gray-500">Chưa có đánh giá</span>
      </span>
    </div>
    @endif

    <!-- Price Section -->
    <div class="flex flex-col gap-1">
      @if($price > 0)
      <div class="flex items-baseline gap-2">
        <span class="price-main text-indigo-600 font-bold text-base sm:text-lg whitespace-nowrap shrink-0">{{ number_format($price, 0, ',', '.') }}₫</span>
        @if($hasDiscount)
        <span class="text-xs text-gray-400 line-through decoration-2 decoration-gray-400">{{ number_format($originalPrice, 0, ',', '.') }}₫</span>
        @endif
      </div>
      <div class="flex items-center gap-2 flex-wrap">
        @if($hasDiscount && $computedDiscount > 0)
        <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">
          <i class="fas fa-tag mr-1"></i>
          Giảm {{ $computedDiscount }}%
        </span>
        @endif
        {{-- Stock badge for accessories --}}
        <x-stock-badge :stock="$accessory->stock_quantity ?? 0" type="accessory" size="sm" />
      </div>
      @else
      <span class="text-gray-500 font-medium whitespace-nowrap">Liên hệ</span>
      @endif
    </div>

      <!-- Product Info removed per request -->

      <!-- Action Buttons -->
      <div class="grid grid-cols-1 gap-2">
        @if($accessory->is_available)
        <button type="button" class="action-btn w-full px-3 sm:px-4 py-3 text-sm font-semibold text-indigo-700 border border-indigo-200 rounded-xl hover:bg-indigo-50 hover:border-indigo-300 whitespace-nowrap leading-none truncate max-w-full min-h-[44px] flex items-center justify-center js-add-to-cart transition-all duration-300" aria-label="Thêm vào giỏ" title="Thêm vào giỏ" data-item-type="accessory" data-item-id="{{ $accessory->id }}">
          <i class="fas fa-cart-plus mr-2"></i>
          <span>Thêm vào giỏ</span>
        </button>
        @else
        <button disabled class="w-full px-3 sm:px-4 py-3 text-sm font-semibold text-gray-400 bg-gray-100 border border-gray-200 rounded-xl cursor-not-allowed min-h-[44px]">
          <i class="fas fa-times mr-2"></i>
          Hết hàng
        </button>
        @endif
      </div>
    </div>
  </div>

  @push('styles')
  <style>
    /* Ultra-small screens fine-tuning */
    @media (max-width: 360px) {
      .accessory-card .title {
        font-size: 0.95rem;
      }

      .accessory-card .subtitle {
        font-size: 0.8125rem;
      }

      .accessory-card .price-main {
        font-size: 0.8rem !important;
      }

      .accessory-card .price-old {
        font-size: 0.625rem !important;
      }

      .accessory-card .price-off {
        font-size: 0.625rem !important;
      }

      .accessory-card .action-btn {
        padding: 0.5rem 0.625rem;
        font-size: 0.75rem;
        min-height: 36px;
      }
    }
  </style>
  @endpush