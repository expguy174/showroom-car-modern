@extends('layouts.admin')

@section('title', 'Quản lý tin tức')

@section('content')
{{-- Flash Messages Component --}}
<x-admin.flash-messages
    :show-icons="true"
    :dismissible="true"
    position="top-right"
    :auto-hide="5000" />

<div class="space-y-3 sm:space-y-4 lg:space-y-6 px-2 sm:px-0">
    {{-- Header --}}
    <x-admin.page-header
        title="Quản lý tin tức"
        description="Quản lý các bài viết và tin tức của showroom"
        icon="fas fa-newspaper">
        <a href="{{ route('admin.blogs.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>
            <span>Thêm bài viết</span>
        </a>
    </x-admin.page-header>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4">
        <x-admin.stats-card
            title="Tổng bài viết"
            :value="$stats['total'] ?? 0"
            icon="fas fa-newspaper"
            color="blue"
            description="Tất cả bài viết"
            dataStat="total" />

        <x-admin.stats-card
            title="Đang hiển thị"
            :value="$stats['active'] ?? 0"
            icon="fas fa-eye"
            color="green"
            description="Hiển thị trên website"
            dataStat="active" />

        <x-admin.stats-card
            title="Đã ẩn"
            :value="$stats['inactive'] ?? 0"
            icon="fas fa-eye-slash"
            color="red"
            description="Ẩn khỏi website"
            dataStat="inactive" />

        <x-admin.stats-card
            title="Nổi bật"
            :value="$stats['featured'] ?? 0"
            icon="fas fa-star"
            color="yellow"
            description="Bài viết nổi bật"
            dataStat="featured" />
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <form id="filterForm"
            class="grid grid-cols-1 md:grid-cols-[1fr_minmax(min-content,_auto)_auto] gap-4 items-end"
            data-base-url="{{ route('admin.blogs.index') }}">

            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <x-admin.search-input
                    name="search"
                    placeholder="Tìm theo tiêu đề, nội dung..."
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
                        'active' => 'Hiển thị',
                        'inactive' => 'Ẩn',
                        'featured' => 'Nổi bật',
                        'normal' => 'Thường'
                    ]"
                    :selected="request('status')"
                    placeholder="Tất cả"
                    onchange="loadBlogsFromDropdown"
                    :searchable="false"
                    width="w-full" />
    </div>

            {{-- Reset --}}
            <div>
                <x-admin.reset-button
                    formId="#filterForm"
                    callback="loadBlogs" />
            </div>
        </form>
    </div>

    {{-- AJAX Table Component --}}
    <x-admin.ajax-table
        table-id="blogs-content"
        loading-id="loading-state"
        form-id="#filterForm"
        base-url="{{ route('admin.blogs.index') }}"
        callback-name="loadBlogs"
        empty-message="Không có bài viết nào"
        empty-icon="fas fa-newspaper"
        after-load-callback="initializeEventListenersAndUpdateStats">
        @include('admin.blogs.partials.table', ['blogs' => $blogs])
    </x-admin.ajax-table>
</div>

{{-- Delete Modal --}}
<x-admin.delete-modal
    modal-id="deleteBlogModal"
    title="Xác nhận xóa bài viết"
    confirm-text="Xóa"
    cancel-text="Hủy"
    delete-callback-name="confirmDeleteBlog"
    entity-type="blog" />

@push('scripts')
<script>
    // Function to update stats cards
    function updateStatsCards(stats) {
        if (!stats) return;

        Object.keys(stats).forEach(statKey => {
            const cardElement = document.querySelector(`[data-stat="${statKey}"]`);
            if (cardElement) {
                const currentValue = cardElement.textContent;
                const newValue = stats[statKey];

                if (currentValue !== newValue.toString()) {
                    cardElement.textContent = newValue;
                }
            }
        });
    }

    // Function to fetch and update stats
    async function fetchAndUpdateStats() {
        try {
            const response = await fetch('{{ route("admin.blogs.stats") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const stats = await response.json();
                updateStatsCards(stats);
            }
        } catch (error) {
            console.error('Error fetching stats:', error);
        }
    }

    // Initialize event listeners
    function initializeEventListeners() {
        // Status toggle buttons
        document.querySelectorAll('.status-toggle').forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();
                const blogId = this.dataset.blogId;
                const newStatus = this.dataset.status === 'true';
                const buttonElement = this;
                const originalIcon = buttonElement.querySelector('i').className;

                // Show loading spinner
                buttonElement.querySelector('i').className = 'fas fa-spinner fa-spin w-4 h-4';
                buttonElement.disabled = true;

                try {
                    const response = await fetch(`/admin/blogs/${blogId}/toggle`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Update button appearance
                        if (newStatus) {
                            buttonElement.className = 'text-orange-600 hover:text-orange-900 status-toggle w-4 h-4 flex items-center justify-center';
                            buttonElement.title = 'Tạm dừng';
                            buttonElement.dataset.status = 'false';
                            buttonElement.querySelector('i').className = 'fas fa-pause w-4 h-4';
                        } else {
                            buttonElement.className = 'text-green-600 hover:text-green-900 status-toggle w-4 h-4 flex items-center justify-center';
                            buttonElement.title = 'Kích hoạt';
                            buttonElement.dataset.status = 'true';
                            buttonElement.querySelector('i').className = 'fas fa-play w-4 h-4';
                        }

                        // Update active status badge in table
                        const row = buttonElement.closest('tr');
                        const statusCell = row.querySelector('td:nth-child(3)'); // Cột thứ 3 (Trạng thái)
                        if (statusCell) {
                            const activeBadge = statusCell.querySelector('span');
                            if (activeBadge) {
                                if (newStatus) {
                                    activeBadge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
                                    activeBadge.innerHTML = '<i class="fas fa-eye-slash mr-1"></i>Ẩn';
                                } else {
                                    activeBadge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800';
                                    activeBadge.innerHTML = '<i class="fas fa-eye mr-1"></i>Hiển thị';
                                }
                            }
                        }

                        // Update stats cards if provided
                        if (data.stats && window.updateStatsCards) {
                            window.updateStatsCards(data.stats);
                        }

                        // Show message
                        if (window.showMessage) {
                            window.showMessage(data.message, 'success');
                        }
                    } else {
                        throw new Error(data.message || 'Có lỗi xảy ra');
                    }
                } catch (error) {
                    console.error('Toggle error:', error);
                    // Restore original state on error
                    buttonElement.querySelector('i').className = originalIcon;
                    if (window.showMessage) {
                        window.showMessage(error.message || 'Có lỗi khi thay đổi trạng thái', 'error');
                    }
                } finally {
                    buttonElement.disabled = false;
                }
            });
        });

        // Delete buttons
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.removeEventListener('click', handleDeleteButtonClick);
            button.addEventListener('click', handleDeleteButtonClick);
        });
    }

    function handleDeleteButtonClick(e) {
        e.preventDefault();
        const blogId = this.dataset.blogId;
        const blogTitle = this.dataset.blogTitle;

        if (window.deleteModalManager_deleteBlogModal) {
            window.deleteModalManager_deleteBlogModal.show({
                entityName: `bài viết "${blogTitle}"`,
                details: `<strong>Tiêu đề:</strong> ${blogTitle}<br>Hành động này không thể hoàn tác.`,
                deleteUrl: `/admin/blogs/delete/${blogId}`
            });
        }
    }

    // Dropdown callback
    window.loadBlogsFromDropdown = function() {
        const searchForm = document.getElementById('filterForm');
        if (searchForm && window.loadBlogs) {
            // Fix status value before creating FormData
            const statusInput = searchForm.querySelector('input[name="status"]');
            if (statusInput) {
                const statusMap = {
                    'Hiển thị': 'active',
                    'Ẩn': 'inactive',
                    'Nổi bật': 'featured',
                    'Thường': 'normal'
                };

                if (statusMap[statusInput.value]) {
                    statusInput.value = statusMap[statusInput.value];
                }
            }

            const formData = new FormData(searchForm);
            const url = '{{ route("admin.blogs.index") }}?' + new URLSearchParams(formData).toString();
            window.loadBlogs(url);
        }
    };

    // Search callback
    window.handleSearch = function(searchTerm, inputElement) {
        const searchForm = document.getElementById('filterForm');
        if (searchForm && window.loadBlogs) {
            const formData = new FormData(searchForm);
            const url = '{{ route("admin.blogs.index") }}?' + new URLSearchParams(formData).toString();
            window.loadBlogs(url);
        }
    };

    // Delete confirmation
    window.confirmDeleteBlog = function(data) {
        if (!data || !data.deleteUrl) return;

        if (window.deleteModalManager_deleteBlogModal) {
            window.deleteModalManager_deleteBlogModal.setLoading(true);
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
            return response.json().then(responseData => {
                if (!response.ok) {
                    // Handle validation errors (400) or other errors
                    throw {
                        status: response.status,
                        data: responseData
                    };
                }
                return responseData;
            });
        })
        .then(responseData => {
            if (responseData.success) {
                if (window.deleteModalManager_deleteBlogModal) {
                    window.deleteModalManager_deleteBlogModal.hide();
                }

                if (window.showMessage) {
                    window.showMessage(responseData.message || 'Đã xóa bài viết thành công!', 'success');
                }

                // Update stats cards if provided
                if (responseData.stats && window.updateStatsCards) {
                    window.updateStatsCards(responseData.stats);
                }

                // Reload table
                if (window.loadBlogs) {
                    window.loadBlogs();
                }
            } else {
                throw new Error(responseData.message || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (window.deleteModalManager_deleteBlogModal) {
                window.deleteModalManager_deleteBlogModal.setLoading(false);
            }

            // Show specific error message from server or default error
            const errorMessage = error.data?.message || error.message || 'Có lỗi xảy ra khi xóa bài viết!';

            if (window.showMessage) {
                window.showMessage(errorMessage, 'error');
            }
        });
    };

    // Initialize event listeners and update stats after table load
    window.initializeEventListenersAndUpdateStats = function() {
        initializeEventListeners();
        fetchAndUpdateStats();
    };

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeEventListeners();
        fetchAndUpdateStats();
    });
</script>
@endpush

@endsection