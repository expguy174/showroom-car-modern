@extends('layouts.admin')

@section('title', 'Quản lý showroom')

@section('content')
{{-- Flash Messages Component --}}
<x-admin.flash-messages
    :show-icons="true"
    :dismissible="true"
    position="top-right"
    :auto-dismiss="5000" />

<div class="space-y-6">
    {{-- Page Header --}}
    <x-admin.page-header
        title="Quản lý showroom"
        description="Quản lý thông tin các showroom trưng bày xe"
        icon="fas fa-building">
        <a href="{{ route('admin.showrooms.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>
            <span>Thêm showroom</span>
        </a>
    </x-admin.page-header>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <x-admin.stats-card
            title="Tổng showroom"
            :value="$stats['total']"
            icon="fas fa-building"
            color="blue"
            description="Tất cả showroom"
            dataStat="total" />

        <x-admin.stats-card
            title="Hoạt động"
            :value="$stats['active']"
            icon="fas fa-check-circle"
            color="green"
            description="Showroom hoạt động"
            dataStat="active" />

        <x-admin.stats-card
            title="Tạm dừng"
            :value="$stats['inactive']"
            icon="fas fa-pause-circle"
            color="red"
            description="Showroom tạm dừng"
            dataStat="inactive" />
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form method="GET" id="filterForm" class="grid grid-cols-1 md:grid-cols-[1fr_minmax(min-content,_auto)_auto] gap-4 items-end">

            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <x-admin.search-input
                    name="search"
                    placeholder="Tìm theo tên, địa chỉ, điện thoại..."
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
                    name="is_active"
                    :options="[
                        '1' => 'Hoạt động',
                        '0' => 'Tạm dừng'
                    ]"
                    :selected="request('is_active')"
                    placeholder="Tất cả"
                    onchange="submitFilterForm"
                    :searchable="false"
                    width="w-full" />
            </div>

            {{-- Reset --}}
            <div>
                <x-admin.reset-button
                    formId="#filterForm"
                    callback="resetFilters" />
            </div>
        </form>
    </div>

    {{-- AJAX Table Component --}}
    <x-admin.ajax-table
        table-id="showrooms-content"
        loading-id="loading-state"
        form-id="filterForm"
        base-url="{{ route('admin.showrooms.index') }}"
        callback-name="loadShowrooms"
        empty-message="Không có showroom nào"
        empty-icon="fas fa-building"
        after-load-callback="initializeEventListeners">
        @include('admin.showrooms.partials.table', ['showrooms' => $showrooms])
    </x-admin.ajax-table>
</div>

{{-- Delete Modal --}}
<x-admin.delete-modal
    modal-id="deleteShowroomModal"
    title="Xác nhận xóa showroom"
    confirm-text="Xóa"
    cancel-text="Hủy"
    delete-callback-name="confirmDeleteShowroom"
    entity-type="showroom" />

@push('scripts')
<script>
    // Update stats cards from toggle response
    window.updateStatsFromServer = function(stats) {
        const statsMapping = {
            'total': 'total',
            'active': 'active',
            'inactive': 'inactive'
        };

        Object.entries(statsMapping).forEach(([serverKey, cardKey]) => {
            if (stats[serverKey] !== undefined) {
                const statElement = document.querySelector(`[data-stat="${cardKey}"]`);
                if (statElement) {
                    statElement.textContent = stats[serverKey];
                }
            }
        });
    };

    // Initialize event listeners
    function initializeEventListeners() {
        // Status toggle buttons
        document.querySelectorAll('.status-toggle').forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();
                const showroomId = this.dataset.showroomId;
                const newStatus = this.dataset.status === 'true';
                const buttonElement = this;

                // Show loading state
                const originalIcon = buttonElement.querySelector('i').className;
                buttonElement.querySelector('i').className = 'fas fa-spinner fa-spin w-4 h-4';
                buttonElement.disabled = true;

                try {
                    const response = await fetch(`/admin/showrooms/${showroomId}/toggle-status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            is_active: newStatus ? 1 : 0
                        })
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

                        // Update status badge in table
                        const showroomId = buttonElement.dataset.showroomId;
                        const row = document.querySelector(`tr[data-showroom-id="${showroomId}"]`);
                        if (row) {
                            const statusCell = row.querySelector('td:nth-child(4)');
                            if (statusCell) {
                                const statusBadge = statusCell.querySelector('span');
                                if (statusBadge) {
                                    if (newStatus) {
                                        statusBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
                                        statusBadge.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Hoạt động';
                                    } else {
                                        statusBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800';
                                        statusBadge.innerHTML = '<i class="fas fa-pause-circle mr-1"></i>Tạm dừng';
                                    }
                                }
                            }
                        }

                        // Update stats cards if provided
                        if (data.stats && window.updateStatsFromServer) {
                            window.updateStatsFromServer(data.stats);
                        }

                        // Show message
                        if (data.message && window.showMessage) {
                            window.showMessage(data.message, 'success');
                        }
                    } else {
                        throw new Error(data.message || 'Có lỗi xảy ra');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    buttonElement.querySelector('i').className = originalIcon;
                    if (window.showMessage) {
                        window.showMessage('Có lỗi xảy ra khi cập nhật trạng thái!', 'error');
                    }
                } finally {
                    buttonElement.disabled = false;
                }
            });
        });

        // Delete buttons
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const showroomId = this.dataset.showroomId;
                const showroomName = this.dataset.showroomName;

                if (window.deleteModalManager_deleteShowroomModal) {
                    window.deleteModalManager_deleteShowroomModal.show({
                        entityName: `showroom ${showroomName}`,
                        details: 'Hành động này không thể hoàn tác.',
                        deleteUrl: `/admin/showrooms/${showroomId}`
                    });
                }
            });
        });
    }

    // Dropdown callback
    window.submitFilterForm = function() {
        // Wait for ajax table to be ready
        setTimeout(() => {
            if (window.loadShowrooms) {
                const searchForm = document.getElementById('filterForm');
                if (searchForm) {
                    // Fix is_active value before creating FormData
                    const isActiveInput = searchForm.querySelector('input[name="is_active"]');
                    if (isActiveInput && isActiveInput.value) {
                        // Custom dropdown might set text value, need to map to actual value
                        const statusMap = {
                            'Hoạt động': '1',
                            'Tạm dừng': '0'
                        };

                        if (statusMap[isActiveInput.value]) {
                            isActiveInput.value = statusMap[isActiveInput.value];
                        }
                    }

                    const formData = new FormData(searchForm);
                    const url = '{{ route("admin.showrooms.index") }}?' + new URLSearchParams(formData).toString();
                    window.loadShowrooms(url);
                }
            }
        }, 100);
    };

    // Search callback
    window.handleSearch = function(searchTerm, inputElement) {
        // Wait for ajax table to be ready
        setTimeout(() => {
            if (window.loadShowrooms) {
                const searchForm = document.getElementById('filterForm');
                if (searchForm) {
                    const formData = new FormData(searchForm);
                    const url = '{{ route("admin.showrooms.index") }}?' + new URLSearchParams(formData).toString();
                    window.loadShowrooms(url);
                }
            }
        }, 100);
    };

    // Reset filters
    window.resetFilters = function() {
        // Wait for ajax table to be ready
        setTimeout(() => {
            if (window.loadShowrooms) {
                window.loadShowrooms('{{ route("admin.showrooms.index") }}');
            }
        }, 100);
    };

    // Delete confirmation
    window.confirmDeleteShowroom = function(data) {
        if (!data || !data.deleteUrl) return;

        if (window.deleteModalManager_deleteShowroomModal) {
            window.deleteModalManager_deleteShowroomModal.setLoading(true);
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
                    if (window.deleteModalManager_deleteShowroomModal) {
                        window.deleteModalManager_deleteShowroomModal.hide();
                    }

                    if (window.showMessage) {
                        window.showMessage(responseData.message || 'Đã xóa showroom thành công!', 'success');
                    }

                    // Update stats cards if provided
                    if (responseData.stats && window.updateStatsFromServer) {
                        window.updateStatsFromServer(responseData.stats);
                    }

                    if (window.loadShowrooms) {
                        window.loadShowrooms();
                    }
                } else {
                    throw new Error(responseData.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (window.deleteModalManager_deleteShowroomModal) {
                    window.deleteModalManager_deleteShowroomModal.setLoading(false);
                }

                const errorMessage = error.data?.message || error.message || 'Có lỗi xảy ra khi xóa showroom!';

                if (window.showMessage) {
                    window.showMessage(errorMessage, 'error');
                }
            });
    };

    // Make it globally accessible for ajax-table callback
    window.initializeEventListeners = initializeEventListeners;

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeEventListeners();
    });
</script>
@endpush
@endsection