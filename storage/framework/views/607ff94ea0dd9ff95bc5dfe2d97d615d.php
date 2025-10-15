<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['tableId', 'loadingId', 'formId' => null, 'baseUrl' => null, 'callbackName' => null, 'emptyMessage' => 'Không có dữ liệu', 'emptyIcon' => 'fas fa-inbox']));

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

foreach (array_filter((['tableId', 'loadingId', 'formId' => null, 'baseUrl' => null, 'callbackName' => null, 'emptyMessage' => 'Không có dữ liệu', 'emptyIcon' => 'fas fa-inbox']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>


<div id="<?php echo e($tableId); ?>">
    <?php echo e($slot); ?>

</div>


<div id="<?php echo e($loadingId); ?>" class="hidden">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <div class="flex items-center justify-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="ml-3 text-gray-600">Đang tải...</span>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// AJAX Table Manager
class AjaxTableManager {
    constructor(options) {
        this.tableContainer = options.tableContainer;
        this.loadingContainer = options.loadingContainer;
        this.searchForm = options.searchForm;
        this.baseUrl = options.baseUrl;
        this.callbackName = options.callbackName || 'loadTableData';
        this.afterLoadCallback = options.afterLoadCallback;
        this.emptyMessage = options.emptyMessage || 'Không có dữ liệu';
        this.emptyIcon = options.emptyIcon || 'fas fa-inbox';
    }
    
    init() {
        // Make loadTable available globally
        window[this.callbackName] = (url) => this.loadTable(url);
        
        // Initialize pagination listeners
        this.initializePagination();
    }
    
    loadTable(url) {
        this.showLoading();
        
        const requestUrl = url || this.baseUrl;
        
        const options = {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        };
        
        fetch(requestUrl, options)
            .then(response => response.text())
            .then(html => {
                const tableContainer = document.getElementById(this.tableContainer.replace('#', ''));
                tableContainer.innerHTML = html;
                
                // Check if result is empty (no data rows) - only once
                const tbody = tableContainer.querySelector('tbody');
                if (tbody && !tbody.dataset.emptyChecked) {
                    tbody.dataset.emptyChecked = 'true';
                    
                    const dataRows = tbody.querySelectorAll('tr:not(.empty-state)');
                    const hasRealData = Array.from(dataRows).some(row => {
                        const cells = row.querySelectorAll('td');
                        // Check if it's not an empty state row (has colspan)
                        return cells.length > 1 || (cells.length === 1 && !cells[0].hasAttribute('colspan'));
                    });
                    
                    if (!hasRealData && dataRows.length === 0) {
                        // No real data - create empty state
                        const colCount = tableContainer.querySelector('thead tr')?.children.length || 5;
                        tbody.innerHTML = 
                            '<tr class="empty-state">' +
                                '<td colspan="' + colCount + '" class="px-3 sm:px-6 py-12 text-center">' +
                                    '<div class="flex flex-col items-center">' +
                                        '<i class="' + this.emptyIcon + ' text-gray-400 text-3xl sm:text-4xl mb-4"></i>' +
                                        '<p class="text-gray-500 text-base sm:text-lg">' + this.emptyMessage + '</p>' +
                                        '<p class="text-gray-400 text-xs sm:text-sm mt-1">Thử thay đổi bộ lọc hoặc tìm kiếm</p>' +
                                    '</div>' +
                                '</td>' +
                            '</tr>';
                        console.log('Empty state created for search with no results');
                    }
                }
                
                this.hideLoading();
                
                // Re-initialize pagination for new content
                this.initializePagination();
                
                // Call after load callback if provided
                if (this.afterLoadCallback) {
                    this.afterLoadCallback();
                }
            })
            .catch(error => {
                console.error('Error loading table:', error);
                this.hideLoading();
                this.showError('Có lỗi xảy ra khi tải dữ liệu');
            });
    }
    
    showLoading() {
        const tableContainer = document.querySelector(this.tableContainer);
        const loadingContainer = document.querySelector(this.loadingContainer);
        
        if (tableContainer) tableContainer.style.display = 'none';
        if (loadingContainer) loadingContainer.classList.remove('hidden');
    }
    
    hideLoading() {
        const tableContainer = document.querySelector(this.tableContainer);
        const loadingContainer = document.querySelector(this.loadingContainer);
        
        if (loadingContainer) loadingContainer.classList.add('hidden');
        if (tableContainer) tableContainer.style.display = 'block';
    }
    
    showError(message) {
        const tableContainer = document.querySelector(this.tableContainer);
        if (tableContainer) {
            tableContainer.innerHTML = `
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-exclamation-triangle text-red-400 text-4xl mb-4"></i>
                        <p class="text-red-500 text-lg">${message}</p>
                        <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Thử lại
                        </button>
                    </div>
                </div>
            `;
        }
    }
    
    initializePagination() {
        // Handle pagination clicks
        const paginationLinks = document.querySelectorAll(`${this.tableContainer} nav a, ${this.tableContainer} .pagination a`);
        paginationLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const url = link.getAttribute('href');
                if (url) {
                    this.loadTable(url);
                }
            });
        });
    }
}

// Initialize AJAX Table Manager for <?php echo e($tableId); ?>

document.addEventListener('DOMContentLoaded', function() {
    const config = {
        tableContainer: '#<?php echo e($tableId); ?>',
        loadingContainer: '#<?php echo e($loadingId); ?>',
        afterLoadCallback: function() {
            // Re-initialize any event listeners for new content
            if (window.initializeEventListeners) {
                window.initializeEventListeners();
            }
        }
    };
    
    <?php if($formId): ?>
        config.searchForm = '#<?php echo e($formId); ?>';
    <?php endif; ?>
    
    <?php if($baseUrl): ?>
        config.baseUrl = '<?php echo e($baseUrl); ?>';
    <?php endif; ?>
    
    <?php if($callbackName): ?>
        config.callbackName = '<?php echo e($callbackName); ?>';
    <?php endif; ?>
    
    <?php
        $cleanTableId = str_replace(['-', '_'], '', $tableId);
    ?>
    
    const ajaxTable_<?php echo e($cleanTableId); ?> = new AjaxTableManager(config);
    ajaxTable_<?php echo e($cleanTableId); ?>.init();
    
    // Make it globally accessible
    window.ajaxTableManager_<?php echo e($cleanTableId); ?> = ajaxTable_<?php echo e($cleanTableId); ?>;
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/components/admin/ajax-table.blade.php ENDPATH**/ ?>