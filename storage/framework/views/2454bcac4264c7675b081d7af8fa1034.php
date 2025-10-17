<?php $__env->startSection('title', 'AutoLux - Premium Auto Showroom'); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startPush('head'); ?>
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "AutoDealer",
        "name": "AutoLux Showroom",
        "url": "<?php echo e(url('/')); ?>",
        "telephone": "+84-123-456-789",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "<?php echo e(optional($showrooms->first())->address ?? '123 Đường ABC'); ?>",
            "addressLocality": "<?php echo e(optional($showrooms->first())->city ?? 'TP.HCM'); ?>",
            "postalCode": "<?php echo e(optional($showrooms->first())->postal_code ?? '700000'); ?>",
            "addressCountry": "VN"
        }
    }
</script>
<?php $__env->stopPush(); ?>
<!-- Success/Error Messages -->
<div id="message-container" class="fixed top-4 right-4 z-50"></div>


<section class="relative min-h-[70vh] sm:min-h-[65vh] lg:min-h-[60vh] bg-slate-900 overflow-hidden z-0 pt-16">
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
                    <form method="GET" action="<?php echo e(route('products.index')); ?>" class="relative">
                        <input type="search" name="q" placeholder="Tìm xe, hãng, model hoặc phụ kiện..." aria-label="Tìm kiếm"
                               class="w-full rounded-full border border-white/30 bg-white/90 backdrop-blur px-5 sm:px-6 py-3 sm:py-4 pr-28 text-slate-800 placeholder-slate-500 focus:ring-4 focus:ring-purple-400/40 focus:border-white shadow-xl" />
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex items-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold hover:from-indigo-700 hover:to-purple-700 shadow-md">
                            <i class="fas fa-search"></i>
                            <span class="hidden sm:inline">Tìm kiếm</span>
                        </button>
                    </form>
                    <div class="flex flex-wrap justify-center gap-2 sm:gap-3 mt-4">
                        <a href="<?php echo e(route('products.index', ['type' => 'car'])); ?>" class="px-3 sm:px-4 py-1.5 rounded-full bg-white/10 text-white border border-white/20 hover:bg-white/20 text-sm">
                            <i class="fas fa-car-side mr-1"></i> Xe hơi
                        </a>
                        <a href="<?php echo e(route('products.index', ['type' => 'accessory'])); ?>" class="px-3 sm:px-4 py-1.5 rounded-full bg-white/10 text-white border border-white/20 hover:bg-white/20 text-sm">
                            <i class="fas fa-puzzle-piece mr-1"></i> Phụ kiện
                        </a>
                        <a href="#featured" class="px-3 sm:px-4 py-1.5 rounded-full bg-white/10 text-white border border-white/20 hover:bg-white/20 text-sm">
                            <i class="fas fa-star mr-1"></i> Nổi bật
                        </a>
                        <a href="#promotions" class="px-3 sm:px-4 py-1.5 rounded-full bg-white/10 text-white border border-white/20 hover:bg-white/20 text-sm">
                            <i class="fas fa-tags mr-1"></i> Khuyến mãi
                        </a>
                    </div>
                    
                </div>

                

                <!-- Scroll Indicator -->
                <div class="absolute bottom-8 sm:bottom-12 left-1/2 transform -translate-x-1/2 z-0">
                    <div class="hidden sm:flex flex-col items-center text-white/70 animate-bounce">
                        <span class="text-xs sm:text-sm mb-1 sm:mb-2">Scroll</span>
                        <i class="fas fa-chevron-down text-lg sm:text-xl"></i>
                    </div>
                </div>
</section>





<section id="brands" class="py-16 sm:py-20 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 sm:mb-20">
            <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 rounded-full text-sm font-semibold mb-6">
                <i class="fas fa-handshake mr-3"></i>
                Đối tác chính thức
            </div>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-4">Hãng xe đối tác</h2>
            <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed md:leading-loose">
                Chúng tôi tự hào là đại lý chính thức của các thương hiệu xe hơi hàng đầu thế giới
            </p>
        </div>

        <!-- Featured Brands Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-8 sm:gap-10 max-w-6xl mx-auto">
            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('components.brand-card', ['brand' => $brand], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- View All Brands Button -->
        <div class="text-center mt-12">
            <a href="<?php echo e(route('car-brands.index')); ?>"
                class="inline-flex items-center px-8 py-4 min-h-[48px] bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-full hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600 focus-visible:ring-offset-2">
                <span>Xem tất cả hãng xe</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</section>


<section id="featured" class="py-16 sm:py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 sm:mb-20">
            <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-100 to-purple-100 text-blue-700 rounded-full text-sm font-medium mb-4">
                <i class="fas fa-star mr-2"></i>
                Xe nổi bật
            </div>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-4">Xe hơi nổi bật</h2>
            <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed md:leading-loose">
                Những mẫu xe mới nhất và được yêu thích nhất từ các hãng xe hàng đầu
            </p>
        </div>

        <!-- Featured Cars Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
            <?php $__currentLoopData = $featuredVariants->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div>
                <?php echo $__env->make('components.variant-card', ['variant' => $variant, 'showCompare' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- View All Cars Button -->
        <div class="text-center mt-12">
            <a href="<?php echo e(route('products.index')); ?>"
                class="inline-flex items-center px-8 py-4 min-h-[48px] bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-full hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600 focus-visible:ring-offset-2">
                <span>Xem tất cả xe</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</section>




<?php if(isset($featuredAccessories) && $featuredAccessories->count()): ?>
<section id="featured-accessories" class="py-16 sm:py-20 bg-blue-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 sm:mb-20">
            <div class="inline-flex items-center px-4 py-2 bg-amber-100 text-amber-700 rounded-full text-sm font-medium mb-4">
                <i class="fas fa-puzzle-piece mr-2"></i>
                Phụ kiện nổi bật
            </div>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-4">Phụ kiện nổi bật</h2>
            <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed md:leading-loose">
                Sản phẩm chính hãng, tương thích đa dạng mẫu xe, sẵn sàng giao nhanh
            </p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
            <?php $__currentLoopData = $featuredAccessories->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div>
                <?php echo $__env->make('components.accessory-card', ['accessory' => $acc], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="text-center mt-12">
            <a href="<?php echo e(route('products.index', ['type' => 'accessory'])); ?>"
                class="inline-flex items-center px-8 py-4 min-h-[48px] bg-gradient-to-r from-amber-500 to-rose-500 text-white font-semibold rounded-full hover:from-amber-600 hover:to-rose-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-600 focus-visible:ring-offset-2">
                <span>Xem tất cả phụ kiện</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>


<?php if(isset($recentReviews) && $recentReviews->count()): ?>
<section id="reviews" class="py-16 sm:py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 sm:mb-16">
            <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-medium mb-4">
                <i class="fas fa-star mr-2"></i>
                Đánh giá mới nhất
            </div>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-4">Khách hàng nói gì</h2>
            <p class="text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Những đánh giá chân thực từ khách hàng đã sử dụng dịch vụ
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            <?php $__currentLoopData = $recentReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <!-- Header with user info and rating -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold">
                            <?php echo e(strtoupper(substr(optional(optional($review->user)->userProfile)->name ?? 'K', 0, 1))); ?>

                        </div>
                        <div>
                            <div class="font-semibold text-gray-900"><?php echo e(optional(optional($review->user)->userProfile)->name ?? 'Khách hàng'); ?></div>
                            <div class="text-xs text-gray-500 flex items-center gap-1">
                                <i class="fas fa-calendar-alt"></i>
                                <?php echo e($review->created_at->format('d/m/Y')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star text-sm <?php echo e($i <= $review->rating ? 'text-yellow-400' : 'text-gray-300'); ?>"></i>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- Review title from database -->
                <?php if($review->title): ?>
                <div class="mb-3">
                    <h4 class="font-semibold text-gray-900 text-sm">"<?php echo e($review->title); ?>"</h4>
                </div>
                <?php endif; ?>

                <!-- Review content -->
                <div class="text-gray-700 line-clamp-3 leading-relaxed text-sm mb-4"><?php echo e($review->comment); ?></div>
                <?php ($rv = $review->reviewable); ?>
                <?php if($rv): ?>
                <div class="mt-4 text-sm text-gray-600">
                    <?php if($review->reviewable_type === \App\Models\CarVariant::class): ?>
                    <?php ($brand = optional(optional($rv->carModel)->carBrand)->name); ?>
                    <?php ($model = optional($rv->carModel)->name); ?>
                    <?php ($firstImg = ($rv->images && $rv->images->count()) ? ($rv->images->first()) : null); ?>
                    <?php ($rawSrc = $firstImg ? ($firstImg->image_url ?? ($firstImg->image_path ?? ($firstImg->path ?? null))) : null); ?>
                    <?php ($isAbs = $rawSrc && (str_starts_with($rawSrc, 'http://') || str_starts_with($rawSrc, 'https://'))); ?>
                    <?php ($thumb = $rawSrc ? ($isAbs ? $rawSrc : asset('storage/'.$rawSrc)) : null); ?>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-500">Về:</span>
                        <?php if($thumb): ?>
                        <img src="<?php echo e($thumb); ?>" alt="Ảnh xe" class="w-8 h-8 rounded object-cover border border-gray-200" loading="lazy" decoding="async" width="32" height="32">
                        <?php else: ?>
                        <span class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center text-gray-400 border border-gray-200"><i class="fas fa-car text-[11px]"></i></span>
                        <?php endif; ?>
                        <a href="<?php echo e(route('car-variants.show', $rv->id)); ?>" class="font-medium text-gray-800 hover:text-indigo-600 truncate">
                            <?php echo e(trim(($brand ? $brand.' ' : '').($model ? $model.' • ' : '').($rv->name ?? ''))); ?>

                        </a>
                    </div>
                    <?php elseif($review->reviewable_type === \App\Models\Accessory::class): ?>
                    <?php ($accRaw = $rv->image_url ?? ($rv->main_image_path ?? ($rv->image_path ?? null))); ?>
                    <?php ($accAbs = $accRaw && (str_starts_with($accRaw, 'http://') || str_starts_with($accRaw, 'https://'))); ?>
                    <?php ($accThumb = $accRaw ? ($accAbs ? $accRaw : asset('storage/'.$accRaw)) : null); ?>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-500">Về:</span>
                        <?php if($accThumb): ?>
                        <img src="<?php echo e($accThumb); ?>" alt="Ảnh phụ kiện" class="w-8 h-8 rounded object-cover border border-gray-200" loading="lazy" decoding="async" width="32" height="32">
                        <?php else: ?>
                        <span class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center text-gray-400 border border-gray-200"><i class="fas fa-puzzle-piece text-[11px]"></i></span>
                        <?php endif; ?>
                        <a href="<?php echo e(route('accessories.show', $rv->id)); ?>" class="font-medium text-gray-800 hover:text-indigo-600 truncate">
                            <?php echo e($rv->name ?? 'Phụ kiện'); ?>

                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Explore products call-to-action -->
        <div class="text-center mt-12">
            <a href="<?php echo e(route('products.index')); ?>"
                class="inline-flex items-center px-8 py-4 min-h-[48px] bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-full hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                <span>Khám phá sản phẩm</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>


<?php if(isset($promotions) && $promotions->count()): ?>
<section id="promotions" class="py-16 sm:py-20 bg-indigo-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center text-center gap-4 mb-8 sm:mb-12">
            <div>
                <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 rounded-full text-sm font-medium mb-3">
                    <i class="fas fa-tags mr-2"></i>
                    Ưu đãi hiện hành
                </div>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900">Khuyến mãi hấp dẫn</h2>
                <p class="mt-2 text-gray-600 max-w-2xl leading-relaxed md:leading-loose">Tiết kiệm chi phí với các chương trình ưu đãi đang diễn ra.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $promotions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promotion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white relative overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-<?php echo e($promotion->id); ?>" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                    <circle cx="10" cy="10" r="2" fill="currentColor"/>
                                </pattern>
                            </defs>
                            <rect width="100" height="100" fill="url(#pattern-<?php echo e($promotion->id); ?>)"/>
                        </svg>
                    </div>

                    <div class="relative z-10">
                        <!-- Type Badge -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-2 py-1 bg-white bg-opacity-20 rounded-full text-xs font-medium">
                                <?php switch($promotion->type):
                                    case ('percentage'): ?> 
                                        Giảm <?php echo e($promotion->discount_value); ?>%
                                        <?php if($promotion->max_discount_amount): ?>
                                            <span class="block text-[10px] opacity-75">Tối đa <?php echo e(number_format($promotion->max_discount_amount, 0, ',', '.')); ?>đ</span>
                                        <?php endif; ?>
                                        <?php break; ?>
                                    <?php case ('fixed_amount'): ?> Giảm <?php echo e(number_format($promotion->discount_value, 0, ',', '.')); ?>đ <?php break; ?>
                                    <?php case ('free_shipping'): ?> Miễn phí ship <?php break; ?>
                                    <?php case ('brand_specific'): ?>
                                        Giảm <?php echo e($promotion->discount_value); ?>%
                                        <?php if($promotion->max_discount_amount): ?>
                                            <span class="block text-[10px] opacity-75">Tối đa <?php echo e(number_format($promotion->max_discount_amount, 0, ',', '.')); ?>đ</span>
                                        <?php endif; ?>
                                        <?php break; ?>
                                <?php endswitch; ?>
                            </span>
                            <?php if($promotion->usage_limit): ?>
                                <span class="text-xs opacity-75">
                                    <?php echo e($promotion->usage_limit - $promotion->usage_count); ?> lượt còn lại
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Title -->
                        <h3 class="text-xl font-bold mb-2"><?php echo e($promotion->name); ?></h3>
                        
                        <!-- Description -->
                        <?php if($promotion->description): ?>
                            <p class="text-sm opacity-90 mb-4 line-clamp-2"><?php echo e($promotion->description); ?></p>
                        <?php endif; ?>

                        <!-- Code -->
                        <div class="bg-white bg-opacity-20 rounded-lg p-3 mb-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs opacity-75 mb-1">Mã khuyến mãi</p>
                                    <p class="font-mono font-bold text-lg"><?php echo e($promotion->code); ?></p>
                                </div>
                                <button onclick="copyCode('<?php echo e($promotion->code); ?>')" 
                                        class="p-2 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-colors">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Min Order & Expiry -->
                        <div class="space-y-2 text-sm opacity-90">
                            <?php if($promotion->min_order_amount): ?>
                                <p><i class="fas fa-shopping-cart w-4"></i> Đơn tối thiểu: <?php echo e(number_format($promotion->min_order_amount, 0, ',', '.')); ?>đ</p>
                            <?php endif; ?>
                            <?php if($promotion->end_date): ?>
                                <p><i class="fas fa-clock w-4"></i> Hết hạn: <?php echo e($promotion->end_date->format('d/m/Y')); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Status Badge -->
                        <div class="mt-4 pt-4 border-t border-white border-opacity-20">
                            <div class="flex items-center justify-between text-xs">
                                <span class="px-2 py-1 bg-white bg-opacity-20 rounded-full">
                                Đang diễn ra
                            </span>
                                <?php if($promotion->usage_limit): ?>
                                    <span class="opacity-75">
                                        Còn <?php echo e($promotion->usage_limit - $promotion->usage_count); ?> lượt
                                    </span>
                                <?php else: ?>
                                    <span class="opacity-75">Không giới hạn</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- View all promotions button -->
        <div class="text-center mt-12">
            <a href="<?php echo e(route('user.promotions.index')); ?>"
                class="inline-flex items-center px-8 py-4 min-h-[48px] bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-full hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2">
                <span>Xem tất cả khuyến mãi</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
    </section>
<?php endif; ?>



<section class="py-16 sm:py-20 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 sm:mb-20">
            <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 rounded-full text-sm font-medium mb-4">
                <i class="fas fa-newspaper mr-2"></i>
                Bài viết
            </div>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-4">Tin tức mới nhất</h2>
            <p class="text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Cập nhật những thông tin mới nhất về thế giới ô tô
            </p>
        </div>

        <!-- News Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('components.blog-card', ['blog' => $blog], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- View All News Button -->
        <div class="text-center mt-12">
            <a href="<?php echo e(route('blogs.index')); ?>"
                class="inline-flex items-center px-8 py-4 min-h-[48px] bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-full hover:from-purple-700 hover:to-pink-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-purple-600 focus-visible:ring-offset-2">
                <span>Xem tất cả tin tức</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</section>




<section class="py-16 sm:py-20 bg-slate-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-6">
            Sẵn sàng sở hữu xe mơ ước?
        </h2>
        <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto leading-relaxed">
            Hãy để chúng tôi giúp bạn tìm được chiếc xe hoàn hảo với dịch vụ chuyên nghiệp và giá cả hợp lý
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="<?php echo e(route('products.index')); ?>"
                class="group bg-white text-slate-900 px-8 py-4 min-h-[48px] rounded-full font-bold text-lg transition-all duration-300 hover:bg-purple-100 hover:scale-[1.02] shadow-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-purple-600 focus-visible:ring-offset-2">
                <i class="fas fa-car mr-2 group-hover:rotate-12 transition-transform"></i>
                Khám phá xe ngay
            </a>
            <a href="<?php echo e(route('contact')); ?>"
                class="border-2 border-white/30 text-white px-8 py-4 min-h-[48px] rounded-full font-bold text-lg hover:bg-white/10 transition-all duration-300 backdrop-blur-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-neutral-900/40">
                <i class="fas fa-phone mr-2"></i>
                Liên hệ tư vấn
            </a>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Compare logic moved to layout (global)

    

    // Toast notification function - use global showMessage if available
    function showToast(message, type = 'info') {
        if (typeof window.showMessage === 'function') {
            window.showMessage(message, type);
            return;
        }
        
        // Fallback toast with consistent styling
        const existingToasts = document.querySelectorAll('.toast-notification');
        existingToasts.forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `toast-notification`;

        const colors = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            warning: 'bg-yellow-500 text-white',
            info: 'bg-blue-500 text-white'
        };

        toast.className += ` ${colors[type] || colors.info}`;
        toast.innerHTML = `
            <div class="toast-content">
                <span class="toast-message">${message}</span>
                <button class="toast-close" aria-label="Đóng" onclick="this.closest('.toast-notification').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        document.body.appendChild(toast);

        requestAnimationFrame(() => {
            toast.classList.add('show');
        });

        setTimeout(() => {
            toast.classList.add('hide');
            setTimeout(() => {
                if (toast.parentElement) toast.remove();
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

    // Copy promotion code function
    function copyCode(code) {
        navigator.clipboard.writeText(code).then(function() {
            showToast('Đã sao chép mã: ' + code, 'success');
        }).catch(function() {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = code;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showToast('Đã sao chép mã: ' + code, 'success');
        });
    }
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/user/home.blade.php ENDPATH**/ ?>