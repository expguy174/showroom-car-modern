@props(['tableId', 'loadingId', 'formId', 'baseUrl', 'callbackName', 'emptyMessage', 'emptyIcon'])

{{-- Table Container --}}
<div id="{{ $tableId }}">
    {{ $slot }}
</div>

{{-- Loading State --}}
<div id="{{ $loadingId }}" class="hidden">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <div class="flex items-center justify-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="ml-3 text-gray-600">Đang tải...</span>
        </div>
    </div>
</div>

@push('scripts')
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
                document.getElementById(this.tableContainer.replace('#', '')).innerHTML = html;
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

// Initialize AJAX Table Manager for {{ $tableId }}
document.addEventListener('DOMContentLoaded', function() {
    const ajaxTable_{{ str_replace(['-', '_'], '', $tableId) }} = new AjaxTableManager({
        tableContainer: '#{{ $tableId }}',
        loadingContainer: '#{{ $loadingId }}',
        searchForm: '#{{ $formId }}',
        baseUrl: '{{ $baseUrl }}',
        callbackName: '{{ $callbackName }}',
        afterLoadCallback: function() {
            // Re-initialize any event listeners for new content
            if (window.initializeEventListeners) {
                window.initializeEventListeners();
            }
        }
    });
    
    ajaxTable_{{ str_replace(['-', '_'], '', $tableId) }}.init();
});
</script>
@endpush
