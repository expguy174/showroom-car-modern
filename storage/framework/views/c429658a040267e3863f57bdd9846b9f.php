<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['blog']));

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

foreach (array_filter((['blog']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<article class="group card-surface overflow-hidden">
  <div class="card-media">
    <?php $img = $blog->image_url ?? null; ?>
    <?php if($img): ?>
      <img src="<?php echo e($img); ?>" 
           alt="<?php echo e($blog->title); ?>" 
           class="card-img h-48" loading="lazy" decoding="async" width="600" height="300" onerror="this.onerror=null;this.src='https://via.placeholder.com/600x300?text=No+Image';">
      <span class="card-overlay"></span>
      <span class="card-sheen"></span>
    <?php else: ?>
      <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
        <i class="fas fa-newspaper text-gray-400 text-4xl"></i>
      </div>
    <?php endif; ?>
    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-medium text-purple-600">
      <?php echo e($blog->category); ?>

    </div>
  </div>
  <div class="p-6">
    <div class="flex items-center text-sm text-gray-500 mb-3">
      <i class="fas fa-calendar mr-2"></i>
      <span><?php echo e($blog->created_at->format('d/m/Y')); ?></span>
      <span class="mx-2">•</span>
      <i class="fas fa-user mr-2"></i>
      <span><?php echo e($blog->author_name); ?></span>
    </div>
    <h3 class="font-bold text-lg text-gray-900 mb-3 line-clamp-2">
      <?php echo e($blog->title); ?>

    </h3>
    <p class="text-gray-600 mb-4 line-clamp-3">
      <?php echo e($blog->excerpt); ?>

    </p>
    <a href="<?php echo e(route('blogs.show', $blog->id)); ?>" 
       class="inline-flex items-center text-purple-600 font-semibold">
      <span>Đọc thêm</span>
      <i class="fas fa-arrow-right ml-2"></i>
    </a>
  </div>
</article>


<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/components/blog-card.blade.php ENDPATH**/ ?>