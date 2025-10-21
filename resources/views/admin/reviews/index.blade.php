@extends('layouts.admin')

@section('title', 'Quản lý đánh giá')

@section('content')
{{-- Flash Messages Component --}}
<x-admin.flash-messages 
    :show-icons="true"
    :dismissible="true"
    position="top-right"
    :auto-hide="5000" />

<div class="space-y-6">
    {{-- Page Header --}}
    <x-admin.page-header 
        title="Đánh giá sản phẩm"
        description="Quản lý và kiểm duyệt đánh giá từ khách hàng"
        icon="fas fa-star">
    </x-admin.page-header>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-4 mb-6">
        <x-admin.stats-card 
            title="Tổng đánh giá"
            :value="$reviews->total()"
            icon="fas fa-star"
            color="blue"
            description="Tất cả đánh giá"
            dataStat="total" />
            
        <x-admin.stats-card 
            title="Đã duyệt"
            :value="\App\Models\Review::where('is_approved', true)->count()"
            icon="fas fa-check-circle"
            color="green"
            description="Đã phê duyệt"
            dataStat="approved" />
            
        <x-admin.stats-card 
            title="Chờ duyệt"
            :value="\App\Models\Review::where('is_approved', false)->count()"
            icon="fas fa-clock"
            color="yellow"
            description="Chưa phê duyệt"
            dataStat="pending" />
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form id="filterForm" 
              class="grid grid-cols-1 md:grid-cols-[1fr_minmax(min-content,_auto)_auto] gap-4 items-end"
              data-base-url="{{ route('admin.reviews.index') }}">
            
            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <x-admin.search-input 
                    name="search"
                    placeholder="Tìm theo người đánh giá, sản phẩm..."
                    :value="request('search')"
                    callbackName="handleSearch"
                    :debounceTime="500"
                    size="small"
                    :showIcon="true"
                    :showClearButton="true" />
            </div>
            
            {{-- Status Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <x-admin.custom-dropdown
                    name="status"
                    :options="[
                        ['value' => 'approved', 'label' => 'Đã duyệt'],
                        ['value' => 'pending', 'label' => 'Chờ duyệt']
                    ]"
                    optionValue="value"
                    optionText="label"
                    :selected="request('status')"
                    placeholder="Tất cả"
                    onchange="loadReviewsFromDropdown"
                    :searchable="false"
                    width="w-full" />
            </div>
            
            {{-- Reset --}}
            <div>
                <x-admin.reset-button 
                    formId="#filterForm" 
                    callback="loadReviews" />
            </div>
        </form>
    </div>

    {{-- AJAX Table Component --}}
    <x-admin.ajax-table 
        table-id="reviews-content"
        loading-id="loading-state"
        form-id="#filterForm"
        base-url="{{ route('admin.reviews.index') }}"
        callback-name="loadReviews"
        after-load-callback="initializeEventListeners">
        @include('admin.reviews.partials.table', ['reviews' => $reviews])
    </x-admin.ajax-table>
</div>

{{-- Delete Modal --}}
<x-admin.delete-modal 
    modal-id="deleteReviewModal"
    title="Xác nhận xóa đánh giá"
    confirm-text="Xóa"
    cancel-text="Hủy"
    delete-callback-name="confirmDeleteReview"
    entity-type="review" />

@push('scripts')
<script>
// Flash messages are now handled by FlashMessages component

// Update stats cards from toggle response
window.updateStatsFromServer = function(stats) {
    const statsMapping = {
        'total': 'total',
        'approved': 'approved',
        'pending': 'pending'
    };
    
    Object.entries(statsMapping).forEach(([serverKey, cardKey]) => {
        if (stats[serverKey] !== undefined) {
            const statElement = document.querySelector(`p[data-stat="${cardKey}"]`);
            if (statElement) {
                statElement.textContent = stats[serverKey];
            }
        }
    });
};

// Initialize event listeners
function initializeEventListeners() {
    // Delete buttons
    document.querySelectorAll('.delete-btn').forEach(button => {
        attachDeleteListener(button);
    });
    
    // Approve/Reject forms
    document.querySelectorAll('.approve-form, .reject-form').forEach(form => {
        attachFormListener(form);
    });
}

// Update button status without reloading table
function updateButtonStatus(form, newStatus) {
    const row = form.closest('tr');
    if (!row) return;
    
    const statusCell = row.querySelector('.status-cell');
    const actionsCell = row.querySelector('.actions-cell');
    
    if (statusCell) {
        // Update status badge
        if (newStatus) {
            statusCell.innerHTML = '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>Đã duyệt</span>';
        } else {
            statusCell.innerHTML = '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><i class="fas fa-clock mr-1"></i>Chờ duyệt</span>';
        }
    }
    
    if (actionsCell) {
        // Update action buttons while keeping delete button
        const reviewId = form.closest('[data-review-id]')?.dataset.reviewId;
        const deleteBtn = actionsCell.querySelector('.delete-btn');
        const deleteHtml = deleteBtn ? deleteBtn.outerHTML : '';
        
        if (reviewId) {
            if (newStatus) {
                // Show reject button + keep delete button
                actionsCell.innerHTML = `
                    <div class="flex items-center justify-center gap-2">
                        <form class="reject-form inline" action="/admin/reviews/${reviewId}/reject" method="POST">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                            <input type="hidden" name="_method" value="PATCH">
                            <button type="submit" class="text-orange-600 hover:text-orange-900" title="Bỏ duyệt">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        ${deleteHtml}
                    </div>
                `;
            } else {
                // Show approve button + keep delete button
                actionsCell.innerHTML = `
                    <div class="flex items-center justify-center gap-2">
                        <form class="approve-form inline" action="/admin/reviews/${reviewId}/approve" method="POST">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                            <input type="hidden" name="_method" value="PATCH">
                            <button type="submit" class="text-green-600 hover:text-green-900" title="Phê duyệt">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        ${deleteHtml}
                    </div>
                `;
            }
            
            // Re-attach event listeners to new buttons
            const newForm = actionsCell.querySelector('.approve-form, .reject-form');
            if (newForm) {
                attachFormListener(newForm);
            }
            
            // Re-attach delete button listener if exists
            const newDeleteBtn = actionsCell.querySelector('.delete-btn');
            if (newDeleteBtn) {
                attachDeleteListener(newDeleteBtn);
            }
        }
    }
}

// Attach event listener to a single form
function attachFormListener(form) {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const button = this.querySelector('button');
        const originalHtml = button.innerHTML;
        
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;
        
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                
                // Update stats cards if provided
                if (data.stats && window.updateStatsFromServer) {
                    window.updateStatsFromServer(data.stats);
                }
                
                // Update button status without reloading table
                if (data.new_status !== undefined) {
                    updateButtonStatus(form, data.new_status);
                }
                
                // Show message
                if (data.message && window.showMessage) {
                    window.showMessage(data.message, 'success');
                }
            } else {
                throw new Error('Request failed');
            }
        } catch (error) {
            console.error('Error:', error);
            if (window.showMessage) {
                window.showMessage('Có lỗi xảy ra!', 'error');
            }
            button.innerHTML = originalHtml;
            button.disabled = false;
        }
    });
}

// Attach delete event listener to a single button
function attachDeleteListener(button) {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const reviewId = this.dataset.reviewId;
        const reviewerName = this.dataset.reviewName;
        
        if (window.deleteModalManager_deleteReviewModal) {
            window.deleteModalManager_deleteReviewModal.show({
                entityName: `đánh giá của ${reviewerName}`,
                details: 'Hành động này không thể hoàn tác.',
                deleteUrl: `/admin/reviews/${reviewId}`
            });
        }
    });
}

// Dropdown callback
window.loadReviewsFromDropdown = function() {
    const searchForm = document.getElementById('filterForm');
    if (searchForm && window.loadReviews) {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.reviews.index") }}?' + new URLSearchParams(formData).toString();
        window.loadReviews(url);
    }
};

// Search callback
window.handleSearch = function(searchTerm, inputElement) {
    const searchForm = document.getElementById('filterForm');
    if (searchForm && window.loadReviews) {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.reviews.index") }}?' + new URLSearchParams(formData).toString();
        window.loadReviews(url);
    }
};

// Delete confirmation
window.confirmDeleteReview = function(data) {
    if (!data || !data.deleteUrl) return;
    
    if (window.deleteModalManager_deleteReviewModal) {
        window.deleteModalManager_deleteReviewModal.setLoading(true);
    }
    
    fetch(data.deleteUrl, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        return response.json().then(data => {
            if (!response.ok) {
                throw { status: response.status, data: data };
            }
            return data;
        });
    })
    .then(data => {
        if (data.success) {
            if (window.deleteModalManager_deleteReviewModal) {
                window.deleteModalManager_deleteReviewModal.hide();
            }
            
            // Update stats cards if provided
            if (data.stats && window.updateStatsFromServer) {
                window.updateStatsFromServer(data.stats);
            }
            
            if (window.loadReviews) {
                window.loadReviews();
            }
            
            if (window.showMessage) {
                window.showMessage(data.message || 'Đã xóa đánh giá thành công!', 'success');
            }
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.deleteModalManager_deleteReviewModal) {
            window.deleteModalManager_deleteReviewModal.setLoading(false);
        }
        
        // Use server error message if available
        let errorMessage = 'Có lỗi xảy ra khi xóa đánh giá!';
        if (error.data && error.data.message) {
            errorMessage = error.data.message;
        } else if (error.message) {
            errorMessage = error.message;
        }
        
        if (window.showMessage) {
            window.showMessage(errorMessage, 'error');
        }
    });
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
});
</script>
@endpush

@endsection
