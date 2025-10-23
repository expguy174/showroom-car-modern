@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

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
        title="Quản lý người dùng"
        description="Danh sách tất cả người dùng và nhân viên"
        icon="fas fa-users">
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Thêm người dùng
        </a>
    </x-admin.page-header>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-4">
        <x-admin.stats-card 
            title="Tổng người dùng"
            :value="$stats['total']"
            icon="fas fa-users"
            color="gray"
            description="Tất cả người dùng"
            dataStat="total"
            clickAction="filterAllUsers" />
        
        <x-admin.stats-card 
            title="Đang hoạt động"
            :value="$stats['active']"
            icon="fas fa-check-circle"
            color="green"
            description="Hoạt động"
            dataStat="active"
            clickAction="filterActiveUsers" />
        
        <x-admin.stats-card 
            title="Tạm khóa"
            :value="$stats['inactive']"
            icon="fas fa-ban"
            color="red"
            description="Đã tạm khóa"
            dataStat="inactive"
            clickAction="filterInactiveUsers" />
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <form id="filterForm" 
              class="grid grid-cols-1 md:grid-cols-[1fr_minmax(min-content,_auto)_minmax(min-content,_auto)_auto] gap-4 items-end"
              data-base-url="{{ route('admin.users.index') }}">
            
            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <x-admin.search-input 
                    name="search"
                    placeholder="Tên, email, điện thoại, mã NV..."
                    :value="request('search')"
                    callbackName="handleSearch"
                    :debounceTime="500"
                    size="small"
                    :showIcon="true"
                    :showClearButton="true" />
            </div>
            
            {{-- Role Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vai trò</label>
                <x-admin.custom-dropdown 
                    name="role"
                    :options="[
                        ['value' => 'user', 'text' => 'Người dùng', 'count' => $roleCounts['user']],
                        ['value' => 'admin', 'text' => 'Quản trị viên', 'count' => $roleCounts['admin']],
                        ['value' => 'manager', 'text' => 'Quản lý', 'count' => $roleCounts['manager']],
                        ['value' => 'sales_person', 'text' => 'NV Kinh doanh', 'count' => $roleCounts['sales_person']],
                        ['value' => 'technician', 'text' => 'Kỹ thuật viên', 'count' => $roleCounts['technician']]
                    ]"
                    placeholder="Tất cả"
                    optionValue="value"
                    optionText="text"
                    :selected="request('role')"
                    onchange="loadUsersFromDropdown"
                    :maxVisible="5"
                    :searchable="false"
                    width="w-full" />
            </div>
            
            {{-- Status Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <x-admin.custom-dropdown 
                    name="status"
                    :options="[
                        ['value' => 'active', 'text' => 'Hoạt động'],
                        ['value' => 'inactive', 'text' => 'Tạm khóa']
                    ]"
                    placeholder="Tất cả"
                    optionValue="value"
                    optionText="text"
                    :selected="request('status')"
                    onchange="loadUsersFromDropdown"
                    :maxVisible="3"
                    :searchable="false"
                    width="w-full" />
            </div>
            
            {{-- Reset Button --}}
            <div>
                <x-admin.reset-button 
                    formId="#filterForm" 
                    callback="loadUsers" />
            </div>
        </form>
    </div>

    {{-- AJAX Table Component --}}
    <x-admin.ajax-table 
        table-id="users-content"
        loading-id="loading-state"
        form-id="#filterForm"
        base-url="{{ route('admin.users.index') }}"
        callback-name="loadUsers"
        empty-message="Không có người dùng nào"
        empty-icon="fas fa-users"
        :show-pagination="false">
        @include('admin.users.partials.table', ['users' => $users])
    </x-admin.ajax-table>
</div>

{{-- Delete Modal Component --}}
<x-admin.delete-modal 
    modal-id="deleteUserModal"
    title="Xác nhận xóa người dùng"
    confirm-text="Xóa"
    cancel-text="Hủy"
    delete-callback-name="confirmDeleteUser"
    entity-type="user" />

@push('scripts')
<script>
// Update stats from server response (giống Services)
window.updateStatsFromServer = function(stats) {
    const statsMapping = {
        'total': 'total',
        'active': 'active',
        'inactive': 'inactive'
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

// Handle search
window.handleSearch = function(searchTerm, inputElement) {
    const searchForm = document.getElementById('filterForm');
    if (searchForm) {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.users.index") }}?' + new URLSearchParams(formData).toString();
        if (window.loadUsers) {
            window.loadUsers(url);
        }
    }
};

// Handle dropdown change
window.loadUsersFromDropdown = function(selectedValue, dropdownElement) {
    const searchForm = document.getElementById('filterForm');
    if (searchForm) {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.users.index") }}?' + new URLSearchParams(formData).toString();
        if (window.loadUsers) {
            window.loadUsers(url);
        }
    }
};

// Initialize event listeners (make it global for ajax-table component)
window.initializeEventListeners = function() {
    // Status toggle buttons
    document.querySelectorAll('.status-toggle').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const userId = this.dataset.userId;
            const newStatus = this.dataset.status === 'true';
            const buttonElement = this;
            const originalIcon = buttonElement.querySelector('i').className;
            
            // Show loading spinner
            buttonElement.querySelector('i').className = 'fas fa-spinner fa-spin w-4 h-4';
            buttonElement.disabled = true;
            
            try {
                const response = await fetch(`/admin/users/${userId}/toggle`, {
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
                    
                    // Update status badge
                    if (window.updateStatusBadge) {
                        window.updateStatusBadge(userId, newStatus, 'user');
                    }
                    
                    // Update stats cards if provided
                    if (data.stats && window.updateStatsFromServer) {
                        window.updateStatsFromServer(data.stats);
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
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const userId = this.dataset.userId;
            const userName = this.dataset.userName;
            const deleteUrl = this.dataset.deleteUrl;
            
            if (window.deleteModalManager_deleteUserModal) {
                window.deleteModalManager_deleteUserModal.show({
                    entityName: `người dùng ${userName}`,
                    details: 'Hành động này không thể hoàn tác.',
                    deleteUrl: deleteUrl
                });
            }
        });
    });
};

// Delete confirmation function (giống Services)
window.confirmDeleteUser = function(data) {
    if (!data || !data.deleteUrl) return;
    
    if (window.deleteModalManager_deleteUserModal) {
        window.deleteModalManager_deleteUserModal.setLoading(true);
    }
    
    fetch(data.deleteUrl, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (window.deleteModalManager_deleteUserModal) {
                window.deleteModalManager_deleteUserModal.hide();
            }
            
            if (window.showMessage) {
                window.showMessage(data.message || 'Xóa người dùng thành công', 'success');
            }
            
            // Reload table
            if (window.loadUsers) {
                window.loadUsers();
            }
        } else {
            throw new Error(data.message || 'Có lỗi khi xóa người dùng');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.deleteModalManager_deleteUserModal) {
            window.deleteModalManager_deleteUserModal.setLoading(false);
        }
        
        const errorMsg = error.message || 'Có lỗi khi xóa người dùng';
        if (window.showMessage) {
            window.showMessage(errorMsg, 'error');
        }
    });
};

// Legacy delete handlers (backward compatibility)
window.handleDeleteSuccess = function(data) {
    window.showMessage(data.message || 'Xóa người dùng thành công', 'success');
    // Reload table via ajax-table component
    if (window.loadUsers) {
        window.loadUsers();
    }
};

window.handleDeleteError = function(error) {
    window.showMessage(error.message || 'Có lỗi xảy ra khi xóa người dùng', 'error');
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    if (window.initializeEventListeners) {
        window.initializeEventListeners();
    }
});
</script>
@endpush
@endsection
