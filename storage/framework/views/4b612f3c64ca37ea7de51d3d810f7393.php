<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['variant', 'showCompare' => false]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['variant', 'showCompare' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
$img = $variant->image_url ?? optional($variant->images->first())->image_url;
$brand = $variant->carModel->carBrand->name ?? '';
$model = $variant->carModel->name ?? '';
$modelId = $variant->carModel->id ?? null;
$brandId = $variant->carModel->carBrand->id ?? null;
$fuelRaw = strtolower((string)($variant->fuel_type ?? ''));
$fuelMap = [
'gasoline' => 'Xăng',
'petrol' => 'Xăng',
'diesel' => 'Dầu',
'hybrid' => 'Hybrid',
'plug-in_hybrid' => 'Hybrid sạc ngoài',
'electric' => 'Điện',
'hydrogen' => 'Hydro',
'lpg' => 'Gas',
'cng' => 'CNG',
'ethanol' => 'Ethanol',
];
$fuelVi = $fuelMap[$fuelRaw] ?? ($variant->fuel_type ?? null);

$transmissionRaw = strtolower((string)($variant->transmission ?? ''));
$transmissionMap = [
'manual' => 'Số sàn',
'automatic' => 'Tự động',
'cvt' => 'CVT',
'dct' => 'DCT',
'amt' => 'AMT',
'semi-automatic' => 'Bán tự động',
'sequential' => 'Tuần tự',
];
$transmissionVi = $transmissionMap[$transmissionRaw] ?? ($variant->transmission ?? null);

$power = $variant->power_output ?: ($variant->power ?? null);
$powerDisplay = '';
if (!empty($power)) {
if (str_contains(strtolower($power), 'kw')) {
$powerDisplay = str_replace('kW', 'kW', $power);
} elseif (str_contains(strtolower($power), 'hp')) {
$powerDisplay = str_replace('HP', 'mã lực', $power);
} else {
$powerDisplay = is_numeric($power) ? ($power . ' mã lực') : $power;
}
}

$seats = $variant->seating_capacity;
$engineSize = $variant->engine_size ?? null;
$warrantyYears = $variant->warranty_years ?? null;
// Pull from seeded specifications (VN labels) as fallbacks
$engineType = method_exists($variant, 'getSpecValue') ? ($variant->getSpecValue('Loại động cơ', 'engine') ?? $engineSize) : $engineSize;
$transmissionText = method_exists($variant, 'getSpecValue') ? ($variant->getSpecValue('Hộp số', 'transmission') ?? $variant->transmission) : $variant->transmission;
$maxPower = method_exists($variant, 'getSpecValue') ? ($variant->getSpecValue('Công suất tối đa', 'engine') ?? null) : null;
if (empty($powerDisplay) && !empty($maxPower)) {
    $powerDisplay = is_numeric($maxPower) ? ($maxPower . ' mã lực') : $maxPower;
}
$fuelConsumption = method_exists($variant, 'getSpecValue') ? ($variant->getSpecValue('Tiêu thụ nhiên liệu', 'fuel') ?? null) : null;
// Build primary info values to avoid N/A
$fuelDisplay = $fuelVi ?: ($engineType ?: ($fuelConsumption ? ($fuelConsumption . ' L/100km') : null));
// Price computation aligned with model fields
$originalPrice = (float) ($variant->base_price ?? 0);
$currentPrice = (float) ($variant->current_price ?? 0);
$hasAutoDiscount = ($variant->has_discount ?? false) || (($originalPrice > 0) && ($currentPrice > 0) && ($currentPrice < $originalPrice));
$computedDiscountPercentage = $hasAutoDiscount
  ? (int) round((($originalPrice - $currentPrice) / max($originalPrice, 1)) * 100)
  : 0;
?>

<div class="variant-card group card-surface overflow-hidden h-full flex flex-col min-h-fit">
  <div class="relative">
    <a href="<?php echo e(route('car-variants.show', $variant->id)); ?>" class="block">
      <?php if($img): ?>
      <div class="card-media w-full aspect-[4/3]">
        <img src="<?php echo e($img); ?>" data-src="<?php echo e($img); ?>" alt="<?php echo e($variant->name); ?>" class="card-img" loading="lazy" decoding="async" width="800" height="600" onerror="this.onerror=null;this.src='https://via.placeholder.com/800x600?text=No+Image';">
        <span class="card-overlay"></span>
        <span class="card-sheen"></span>
      </div>
      <?php else: ?>
      <div class="relative w-full aspect-[4/3] bg-gray-200 flex items-center justify-center" role="img" aria-label="No image">
        <i class="fas fa-car text-gray-400 text-3xl"></i>
      </div>
      <?php endif; ?>
    </a>

    <!-- Top Right Action Buttons - All with consistent spacing -->
    <div class="absolute top-3 right-3 flex flex-col items-end z-10 gap-2">

      <!-- Wishlist Button - Always visible -->
      <?php
        $__inWishlist = \App\Helpers\WishlistHelper::isInWishlist('car_variant', $variant->id);
      ?>
      <button type="button"
              class="w-9 h-9 sm:w-10 sm:h-10 inline-flex items-center justify-center bg-white/90 hover:bg-white border-2 border-gray-200 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 js-wishlist-toggle <?php echo e($__inWishlist ? 'in-wishlist' : 'not-in-wishlist'); ?>"
              aria-label="Yêu thích" title="Yêu thích" aria-pressed="<?php echo e($__inWishlist ? 'true' : 'false'); ?>"
              data-item-type="car_variant" data-item-id="<?php echo e($variant->id); ?>">
        <i class="<?php echo e($__inWishlist ? 'fas text-red-500' : 'far text-gray-700'); ?> fa-heart text-sm sm:text-base"></i>
      </button>

      <!-- Compare Button (if enabled) - Hidden by default, visible on hover -->
      <?php if($showCompare): ?>
      <?php $compareList = json_decode(request()->cookie('compare_variants', '[]'), true) ?: []; ?>
      <button type="button" class="w-9 h-9 sm:w-10 sm:h-10 inline-flex items-center justify-center bg-white/90 hover:bg-white border-2 <?php echo e(in_array($variant->id, $compareList) ? 'border-indigo-300' : 'border-gray-200'); ?> rounded-full shadow-lg hover:shadow-xl transition-all duration-300 js-compare-toggle opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto" aria-label="So sánh" title="So sánh" data-variant-id="<?php echo e($variant->id); ?>">
        <i class="fas fa-balance-scale <?php echo e(in_array($variant->id, $compareList) ? 'text-indigo-600' : 'text-gray-700'); ?> text-sm sm:text-base"></i>
      </button>
      <?php endif; ?>

      <!-- Share Button - Hidden by default, visible on hover -->
      <button type="button" class="w-9 h-9 sm:w-10 sm:h-10 inline-flex items-center justify-center bg-white/90 hover:bg-white border-2 border-gray-200 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 js-share-variant opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto" aria-label="Chia sẻ mẫu xe" title="Chia sẻ" data-variant-id="<?php echo e($variant->id); ?>" data-share-url="<?php echo e(route('car-variants.show', $variant->id)); ?>">
        <i class="fas fa-share-alt text-gray-700 text-sm sm:text-base"></i>
      </button>
    </div>


    <!-- Top Left Badges - Maximum 3 badges based on database fields -->
    <div class="absolute top-3 left-3 flex flex-col gap-2 pointer-events-none">
      <?php
      $badgeCount = 0;
      $maxBadges = 3;
      ?>

      <!-- Bestseller Badge (Priority 1) - is_bestseller -->
      <?php if(($variant->is_bestseller ?? false) && $badgeCount < $maxBadges): ?>
        <div class="bg-gradient-to-r from-amber-500 to-orange-500 text-white text-[10px] px-2.5 py-1 rounded-full inline-flex items-center gap-1 leading-none font-bold shadow-lg">
        <i class="fas fa-fire text-[12px] leading-none"></i> Bán chạy
    </div>
    <?php $badgeCount++; ?>
    <?php endif; ?>

    <!-- Featured Badge (Priority 2) - is_featured -->
    <?php if(($variant->is_featured ?? false) && $badgeCount < $maxBadges): ?>
      <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white text-[10px] px-2.5 py-1 rounded-full inline-flex items-center gap-1 leading-none font-bold shadow-lg">
      <i class="fas fa-star text-[12px] leading-none"></i> Nổi bật
  </div>
  <?php $badgeCount++; ?>
  <?php endif; ?>

  <!-- Discount Badge (Priority 3) - has_discount -->
  <?php if($variant->has_discount && ($variant->discount_percentage ?? 0) > 0 && $badgeCount < $maxBadges): ?>
    <div class="bg-gradient-to-r from-red-500 to-red-600 text-white text-[10px] px-2.5 py-1 rounded-full inline-flex items-center gap-1 leading-none font-bold shadow-lg">
    <i class="fas fa-tag text-[12px] leading-none"></i> Giảm giá
</div>
<?php $badgeCount++; ?>
<?php endif; ?>

<!-- New Arrival Badge (Priority 4) - is_new_arrival -->
<?php if(($variant->is_new_arrival ?? false) && $badgeCount < $maxBadges): ?>
  <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white text-[10px] px-2.5 py-1 rounded-full inline-flex items-center gap-1 leading-none font-bold shadow-lg">
  <i class="fas fa-certificate text-[12px] leading-none"></i> Mới
  </div>
  <?php $badgeCount++; ?>
  <?php endif; ?>
  </div>

  </div>

  <div class="p-4 sm:p-5 flex flex-col justify-between flex-1 space-y-3 sm:space-y-4">
    <!-- Brand & Model Row -->
    <div class="flex items-center gap-2">
      <?php if($brandId && !empty($brand)): ?>
      <a href="<?php echo e(route('car-brands.show', $brandId)); ?>" class="text-xs sm:text-sm text-gray-700 hover:text-indigo-600 transition-colors duration-300 font-medium" aria-label="Xem hãng <?php echo e($brand); ?>"><?php echo e($brand); ?></a>
      <?php elseif(!empty($brand)): ?>
      <span class="text-xs sm:text-sm text-gray-700 font-medium"><?php echo e($brand); ?></span>
      <?php else: ?>
      <span class="text-xs sm:text-sm text-gray-500 font-medium">N/A</span>
      <?php endif; ?>
      <?php if(!empty($model) && $modelId): ?>
      <span class="text-gray-500 text-xs">•</span>
      <a href="<?php echo e(route('car-models.show', $modelId)); ?>" class="text-xs sm:text-sm text-gray-600 hover:text-indigo-600 transition-colors duration-300 font-medium" aria-label="Xem dòng xe <?php echo e($model); ?>"><?php echo e($model); ?></a>
      <?php elseif(!empty($model)): ?>
      <span class="text-gray-500 text-xs">•</span>
      <span class="text-xs sm:text-sm text-gray-600 font-medium"><?php echo e($model); ?></span>
      <?php endif; ?>
    </div>

    <!-- Variant Name (Main Title) -->
    <a href="<?php echo e(route('car-variants.show', $variant->id)); ?>" class="text-base sm:text-lg font-bold text-gray-900 hover:text-indigo-600 transition-colors duration-300 block line-clamp-2" aria-label="Xem phiên bản <?php echo e($variant->name); ?>">
      <?php echo e($variant->name ?? 'N/A'); ?>

    </a>

    <!-- Rating Row -->
    <?php ($avg = $variant->approved_reviews_avg); ?>
    <?php ($rc = $variant->approved_reviews_count); ?>
    <?php if(!is_null($avg) && $avg > 0): ?>
    <div class="flex items-center gap-2">
      <span class="inline-flex items-center gap-1 text-xs sm:text-sm text-amber-500" title="Đánh giá">
        <i class="fas fa-star"></i>
        <span class="font-semibold text-gray-800"><?php echo e(number_format($avg, 1)); ?></span>
        <?php if(!is_null($rc) && $rc > 0): ?>
        <span class="text-gray-500">(<?php echo e(number_format($rc)); ?> đánh giá)</span>
        <?php endif; ?>
      </span>
    </div>
    <?php else: ?>
    <div class="flex items-center gap-2">
      <span class="inline-flex items-center gap-1 text-xs sm:text-sm text-gray-400" title="Chưa có đánh giá">
        <i class="far fa-star"></i>
        <span class="text-gray-500">Chưa có đánh giá</span>
      </span>
    </div>
    <?php endif; ?>

    <!-- Price Section -->
    <div class="flex flex-col gap-1">
      <?php if($currentPrice > 0): ?>
      <div class="flex items-baseline gap-2">
        <span class="price-main text-indigo-600 font-bold text-base sm:text-lg whitespace-nowrap shrink-0"><?php echo e(number_format($currentPrice, 0, ',', '.')); ?>₫</span>
        <?php if($hasAutoDiscount): ?>
        <span class="text-xs text-gray-400 line-through decoration-2 decoration-gray-400"><?php echo e(number_format($originalPrice, 0, ',', '.')); ?>₫</span>
        <?php endif; ?>
      </div>
      <div class="flex items-center gap-2 flex-wrap">
        <?php if($hasAutoDiscount && $computedDiscountPercentage > 0): ?>
        <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">
          <i class="fas fa-tag mr-1"></i>
          Giảm <?php echo e($computedDiscountPercentage); ?>%
        </span>
        <?php endif; ?>
        
        <?php if (isset($component)) { $__componentOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stock-badge','data' => ['stock' => \App\Helpers\StockHelper::getCarTotalStock($variant->color_inventory ?? []),'type' => 'car_variant','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stock-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['stock' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\App\Helpers\StockHelper::getCarTotalStock($variant->color_inventory ?? [])),'type' => 'car_variant','size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4)): ?>
<?php $attributes = $__attributesOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4; ?>
<?php unset($__attributesOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4)): ?>
<?php $component = $__componentOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4; ?>
<?php unset($__componentOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4); ?>
<?php endif; ?>
      </div>
      <?php else: ?>
      <span class="text-gray-500 font-medium whitespace-nowrap">Liên hệ</span>
      <?php endif; ?>
    </div>

    <!-- Product Info removed per request -->

    <?php ($colorRequired = (isset($variant->colors) && method_exists($variant->colors, 'count') && $variant->colors->count() > 0)); ?>
    <?php ($totalStock = \App\Helpers\StockHelper::getCarTotalStock($variant->color_inventory ?? [])); ?>
    <?php ($isOutOfStock = $totalStock <= 0); ?>

    <!-- Action Buttons -->
    <div class="grid grid-cols-1 gap-2">
      <?php if($isOutOfStock): ?>
        <button disabled class="w-full px-3 sm:px-4 py-3 text-sm font-semibold text-gray-400 bg-gray-100 border border-gray-200 rounded-xl cursor-not-allowed min-h-[44px] flex items-center justify-center">
          <i class="fas fa-times mr-2"></i>
          <span>Hết hàng</span>
        </button>
      <?php else: ?>
        <button type="button" class="action-btn w-full px-3 sm:px-4 py-3 text-sm font-semibold text-indigo-700 border border-indigo-200 rounded-xl hover:bg-indigo-50 hover:border-indigo-300 whitespace-nowrap leading-none truncate max-w-full min-h-[44px] flex items-center justify-center js-add-to-cart transition-all duration-300" aria-label="Thêm vào giỏ" title="Thêm vào giỏ" data-item-type="car_variant" data-item-id="<?php echo e($variant->id); ?>">
          <i class="fas fa-cart-plus mr-2"></i><span>Thêm vào giỏ</span>
        </button>
      <?php endif; ?>
    </div>
  </div>
  </div>
  <?php $__env->startPush('scripts'); ?>
  <script>
    document.addEventListener('DOMContentLoaded', async function() {
      try {
        const cards = Array.from(document.querySelectorAll('[data-variant-id]')).map(el => parseInt(el.getAttribute('data-variant-id'))).filter(Boolean);
        const uniqueIds = Array.from(new Set(cards));
        if (uniqueIds.length === 0) return;
        const url = '';
        const res = await fetch(url, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        const data = await res.json();
        if (!(data && data.success && data.data)) return;
        const map = data.data;
        // For each card, if in_deposit -> show badge, disable deposit button
        document.querySelectorAll('.js-open-quote-deposit').forEach(btn => {
          const id = parseInt(btn.getAttribute('data-variant-id'));
          if (map[id]) {
            btn.disabled = true;
            btn.classList.add('opacity-60', 'cursor-not-allowed');
            const card = btn.closest('.group');
            const badge = card && card.querySelector('.deposit-badge');
            if (badge) {
              badge.classList.remove('hidden');
            }
            btn.title = 'Bạn đã có khoản đặt cọc đang hiệu lực cho mẫu xe này';
          }
        });
      } catch {}
    });
  </script>

  <?php $__env->startPush('styles'); ?>
  <style>
    /* Ultra-small screens fine-tuning */
    @media (max-width: 360px) {
      .variant-card .title {
        font-size: 0.95rem;
      }

      .variant-card .subtitle {
        font-size: 0.8125rem;
      }

      .variant-card .price-main {
        font-size: 0.8rem !important;
      }

      .variant-card .price-old {
        font-size: 0.625rem !important;
      }

      .variant-card .price-off {
        font-size: 0.625rem !important;
      }

      .variant-card .action-btn {
        padding: 0.5rem 0.625rem;
        font-size: 0.75rem;
        min-height: 36px;
      }
    }
  </style>
  <?php $__env->stopPush(); ?>
  <?php $__env->stopPush(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/components/variant-card.blade.php ENDPATH**/ ?>