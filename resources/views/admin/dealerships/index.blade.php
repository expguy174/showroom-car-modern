@extends('layouts.admin')

@section('title', 'Quản lý đại lý')

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
        title="Quản lý đại lý"
        description="Quản lý thông tin các đại lý phân phối xe"
        icon="fas fa-handshake">
        <a href="{{ route('admin.dealerships.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>
            <span>Thêm đại lý</span>
        </a>
    </x-admin.page-header>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <x-admin.stats-card
            title="Tổng đại lý"
            :value="$stats['total']"
            icon="fas fa-handshake"
            color="blue"
            description="Tất cả đại lý"
            dataStat="total" />

        <x-admin.stats-card
            title="Hoạt động"
            :value="$stats['active']"
            icon="fas fa-check-circle"
            color="green"
            description="Đại lý hoạt động"
            dataStat="active" />

        <x-admin.stats-card
            title="Tạm dừng"
            :value="$stats['inactive']"
            icon="fas fa-pause-circle"
            color="red"
            description="Đại lý tạm dừng"
            dataStat="inactive" />
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form id="filterForm"
            class="grid grid-cols-1 md:grid-cols-[1fr_minmax(min-content,_auto)_auto] gap-4 items-end"
            data-base-url="{{ route('admin.dealerships.index') }}">

            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <x-admin.search-input
                    name="search"
                    placeholder="Tìm theo tên, mã, thành phố..."
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
                    onchange="loadDealershipsFromDropdown"
                    :searchable="false"
                    width="w-full" />
            </div>

            {{-- Reset --}}
            <div>
                <x-admin.reset-button
                    formId="#filterForm"
                    callback="loadDealerships" />
            </div>
        </form>
    </div>

    {{-- AJAX Table Component --}}
    <x-admin.ajax-table
        table-id="dealerships-content"
        loading-id="loading-state"
        form-id="#filterForm"
        base-url="{{ route('admin.dealerships.index') }}"
        callback-name="loadDealerships"
        empty-message="Không có đại lý nào"
        empty-icon="fas fa-handshake"
        after-load-callback="initializeEventListenersAndUpdateStats">
        @include('admin.dealerships.partials.table', ['dealerships' => $dealerships])
    </x-admin.ajax-table>
</div>

{{-- Delete Modal --}}
<x-admin.delete-modal
    modal-id="deleteDealershipModal"
    title="Xác nhận xóa đại lý"
    confirm-text="Xóa"
    cancel-text="Hủy"
    delete-callback-name="confirmDeleteDealership"
    entity-type="dealership" />

@push('scripts')
<script>
    // Update stats cards from toggle response (copy từ payment methods)
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

    // Initialize event listeners (copy từ payment methods)
    function initializeEventListeners() {
        // Status toggle buttons
        document.querySelectorAll('.status-toggle').forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();
                const dealershipId = this.dataset.dealershipId;
                const newStatus = this.dataset.status === 'true';
                const buttonElement = this;

                // Show loading state
                const originalIcon = buttonElement.querySelector('i').className;
                buttonElement.querySelector('i').className = 'fas fa-spinner fa-spin w-4 h-4';
                buttonElement.disabled = true;

                try {
                    const response = await fetch(`/admin/dealerships/${dealershipId}/toggle-status`, {
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
                        const dealershipId = buttonElement.dataset.dealershipId;
                        const row = document.querySelector(`tr[data-dealership-id="${dealershipId}"]`);
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
                const dealershipId = this.dataset.dealershipId;
                const dealershipName = this.dataset.dealershipName;

                if (window.deleteModalManager_deleteDealershipModal) {
                    window.deleteModalManager_deleteDealershipModal.show({
                        entityName: `đại lý ${dealershipName}`,
                        details: 'Hành động này không thể hoàn tác.',
                        deleteUrl: `/admin/dealerships/${dealershipId}`
                    });
                }
            });
        });
    }

    // Dropdown callback (giống contact messages)
    window.loadDealershipsFromDropdown = function() {
        const searchForm = document.getElementById('filterForm');
        if (searchForm && window.loadDealerships) {
            // Fix is_active value trước khi tạo FormData
            const isActiveInput = searchForm.querySelector('input[name="is_active"]');
            if (isActiveInput && isActiveInput.value) {
                // Custom dropdown có thể set text value, cần map về actual value
                const statusMap = {
                    'Hoạt động': '1',
                    'Tạm dừng': '0'
                };

                if (statusMap[isActiveInput.value]) {
                    isActiveInput.value = statusMap[isActiveInput.value];
                }
            }

            const formData = new FormData(searchForm);
            const url = '{{ route("admin.dealerships.index") }}?' + new URLSearchParams(formData).toString();
            window.loadDealerships(url);
        }
    };

    // Search callback (giống contact messages)
    window.handleSearch = function(searchTerm, inputElement) {
        const searchForm = document.getElementById('filterForm');
        if (searchForm && window.loadDealerships) {
            const formData = new FormData(searchForm);
            const url = '{{ route("admin.dealerships.index") }}?' + new URLSearchParams(formData).toString();
            window.loadDealerships(url);
        }
    };

    // Delete confirmation
    window.confirmDeleteDealership = function(data) {
        if (!data || !data.deleteUrl) return;

        if (window.deleteModalManager_deleteDealershipModal) {
            window.deleteModalManager_deleteDealershipModal.setLoading(true);
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
                        // Handle validation errors (422) or other errors
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
                    if (window.deleteModalManager_deleteDealershipModal) {
                        window.deleteModalManager_deleteDealershipModal.hide();
                    }

                    if (window.showMessage) {
                        window.showMessage(responseData.message || 'Đã xóa đại lý thành công!', 'success');
                    }

                    if (responseData.stats) {
                        window.updateStatsFromServer(responseData.stats);
                    }

                    if (window.loadDealerships) {
                        window.loadDealerships();
                    }
                } else {
                    throw new Error(responseData.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (window.deleteModalManager_deleteDealershipModal) {
                    window.deleteModalManager_deleteDealershipModal.setLoading(false);
                }
                
                // Show specific error message from server or default error
                const errorMessage = error.data?.message || error.message || 'Có lỗi xảy ra khi xóa đại lý!';
                
                if (window.showMessage) {
                    window.showMessage(errorMessage, 'error');
                }
            });
    }

    // Initialize event listeners and update stats after table load
    window.initializeEventListenersAndUpdateStats = function() {
        initializeEventListeners();
    };

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeEventListeners();
    });
</script>
@endpush
@endsection