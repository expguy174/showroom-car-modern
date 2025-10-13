<?php if($paginator->hasPages()): ?>
    <?php
        $window = \Illuminate\Pagination\UrlWindow::make($paginator);
        $elements = array_filter([
            $window['first'] ?? [],
            $window['slider'] ?? [],
            $window['last'] ?? [],
        ], function ($segment) {
            return is_array($segment) && count($segment);
        });
    ?>
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        
        <div class="flex-1 flex items-center justify-between sm:hidden">
            <?php if($paginator->onFirstPage()): ?>
                <span class="px-4 py-2 rounded-full bg-gray-100 text-gray-400 text-sm inline-flex items-center gap-2">
                    <i class="fas fa-chevron-left"></i> Trước
                </span>
            <?php else: ?>
                <a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev" class="px-4 py-2 rounded-full bg-white text-gray-700 text-sm inline-flex items-center gap-2 shadow-sm border hover:bg-gray-50">
                    <i class="fas fa-chevron-left"></i> Trước
                </a>
            <?php endif; ?>

            <?php if($paginator->hasMorePages()): ?>
                <a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next" class="px-4 py-2 rounded-full bg-gray-900 text-white text-sm inline-flex items-center gap-2 shadow-sm hover:bg-black">
                    Sau <i class="fas fa-chevron-right"></i>
                </a>
            <?php else: ?>
                <span class="px-4 py-2 rounded-full bg-gray-100 text-gray-400 text-sm inline-flex items-center gap-2">
                    Sau <i class="fas fa-chevron-right"></i>
                </span>
            <?php endif; ?>
        </div>

        
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-center">
            <div class="inline-flex items-center gap-2">
                
                <?php if($paginator->onFirstPage()): ?>
                    <span class="px-3 py-2 rounded-xl bg-gray-100 text-gray-400 text-sm inline-flex items-center"><i class="fas fa-chevron-left"></i></span>
                <?php else: ?>
                    <a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev" class="px-3 py-2 rounded-xl bg-white text-gray-700 text-sm inline-flex items-center shadow-sm border hover:bg-gray-50"><i class="fas fa-chevron-left"></i></a>
                <?php endif; ?>

                
                <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pages): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php ($u = is_string($url) ? $url : (string)$url); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <span class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-semibold"><?php echo e($page); ?></span>
                        <?php else: ?>
                            <a href="<?php echo e($u); ?>" class="px-4 py-2 rounded-xl bg-white text-gray-700 text-sm shadow-sm border hover:bg-gray-50"><?php echo e($page); ?></a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                <?php if($paginator->hasMorePages()): ?>
                    <a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next" class="px-3 py-2 rounded-xl bg-gray-900 text-white text-sm inline-flex items-center shadow-sm hover:bg-black"><i class="fas fa-chevron-right"></i></a>
                <?php else: ?>
                    <span class="px-3 py-2 rounded-xl bg-gray-100 text-gray-400 text-sm inline-flex items-center"><i class="fas fa-chevron-right"></i></span>
                <?php endif; ?>
            </div>
        </div>
    </nav>
<?php endif; ?>


<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/components/pagination-modern.blade.php ENDPATH**/ ?>