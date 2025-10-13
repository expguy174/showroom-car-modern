<?php if($paginator->hasPages()): ?>
<nav class="flex items-center justify-between" aria-label="Pagination">
    <div class="flex-1 flex justify-between sm:hidden">
        
        <?php if($paginator->onFirstPage()): ?>
            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                Trước
            </span>
        <?php else: ?>
            <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-blue-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                Trước
            </a>
        <?php endif; ?>

        <?php if($paginator->hasMorePages()): ?>
            <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-blue-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                Sau
            </a>
        <?php else: ?>
            <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                Sau
            </span>
        <?php endif; ?>
    </div>

    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-gray-700 leading-5">
                Hiển thị
                <span class="font-medium"><?php echo e($paginator->firstItem()); ?></span>
                đến
                <span class="font-medium"><?php echo e($paginator->lastItem()); ?></span>
                trong tổng số
                <span class="font-medium"><?php echo e($paginator->total()); ?></span>
                kết quả
            </p>
        </div>

        <div>
            <div class="inline-flex rounded-md shadow-sm" role="group">
                
                <?php if($paginator->onFirstPage()): ?>
                    <span class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-l-md">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                <?php else: ?>
                    <a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev" class="pagination-link relative inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50 focus:z-10 focus:outline-none transition-colors">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                <?php endif; ?>

                
                <?php
                    $start = max(1, $paginator->currentPage() - 2);
                    $end = min($paginator->lastPage(), $paginator->currentPage() + 2);
                ?>

                
                <?php if($start > 1): ?>
                    <a href="<?php echo e($paginator->url(1)); ?>" class="pagination-link relative inline-flex items-center justify-center w-10 h-10 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:z-10 focus:outline-none transition-colors">
                        1
                    </a>
                    <?php if($start > 2): ?>
                        <span class="relative inline-flex items-center justify-center w-10 h-10 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default">
                            ...
                        </span>
                    <?php endif; ?>
                <?php endif; ?>

                
                <?php for($page = $start; $page <= $end; $page++): ?>
                    <?php if($page == $paginator->currentPage()): ?>
                        <span class="relative inline-flex items-center justify-center w-10 h-10 -ml-px text-sm font-medium text-white bg-blue-600 border border-blue-600 cursor-default z-10 focus:outline-none">
                            <?php echo e($page); ?>

                        </span>
                    <?php else: ?>
                        <a href="<?php echo e($paginator->url($page)); ?>" class="pagination-link relative inline-flex items-center justify-center w-10 h-10 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:z-10 focus:outline-none transition-colors">
                            <?php echo e($page); ?>

                        </a>
                    <?php endif; ?>
                <?php endfor; ?>

                
                <?php if($end < $paginator->lastPage()): ?>
                    <?php if($end < $paginator->lastPage() - 1): ?>
                        <span class="relative inline-flex items-center justify-center w-10 h-10 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default">
                            ...
                        </span>
                    <?php endif; ?>
                    <a href="<?php echo e($paginator->url($paginator->lastPage())); ?>" class="pagination-link relative inline-flex items-center justify-center w-10 h-10 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:z-10 focus:outline-none transition-colors">
                        <?php echo e($paginator->lastPage()); ?>

                    </a>
                <?php endif; ?>

                
                <?php if($paginator->hasMorePages()): ?>
                    <a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next" class="pagination-link relative inline-flex items-center justify-center w-10 h-10 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50 focus:z-10 focus:outline-none transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                <?php else: ?>
                    <span class="relative inline-flex items-center justify-center w-10 h-10 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-r-md">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<?php endif; ?>
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/components/admin/pagination.blade.php ENDPATH**/ ?>