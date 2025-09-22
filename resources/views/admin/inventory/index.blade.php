@extends('layouts.admin')

@section('title', 'Quản lý Kho hàng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Quản lý Kho hàng</h3>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.inventory.export') }}" class="btn btn-success">
                                <i class="fas fa-download"></i> Xuất Excel
                            </a>
                            <a href="{{ route('admin.inventory.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Thêm xe mới
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('admin.inventory.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tìm kiếm</label>
                                    <input type="text" name="search" class="form-control" 
                                           value="{{ request('search') }}" placeholder="VIN, biển số, tên xe...">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Showroom</label>
                                    <select name="showroom_id" class="form-control">
                                        <option value="">Tất cả</option>
                                        @foreach($showrooms as $showroom)
                                            <option value="{{ $showroom->id }}" {{ request('showroom_id') == $showroom->id ? 'selected' : '' }}>
                                                {{ $showroom->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <select name="status" class="form-control">
                                        <option value="">Tất cả</option>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Tình trạng</label>
                                    <select name="condition" class="form-control">
                                        <option value="">Tất cả</option>
                                        @foreach($conditions as $condition)
                                            <option value="{{ $condition }}" {{ request('condition') == $condition ? 'selected' : '' }}>
                                                {{ ucfirst($condition) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Hãng xe</label>
                                    <select name="car_brand" class="form-control">
                                        <option value="">Tất cả</option>
                                        @foreach($carBrands as $brand)
                                            <option value="{{ $brand }}" {{ request('car_brand') == $brand ? 'selected' : '' }}>
                                                {{ $brand }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary form-control">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-car"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tổng số xe</span>
                                    <span class="info-box-number">{{ $inventories->total() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Có sẵn</span>
                                    <span class="info-box-number">{{ $inventories->where('status', 'available')->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Đã đặt</span>
                                    <span class="info-box-number">{{ $inventories->where('status', 'reserved')->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-times"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Đã bán</span>
                                    <span class="info-box-number">{{ $inventories->where('status', 'sold')->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>VIN</th>
                                    <th>Xe</th>
                                    <th>Màu sắc</th>
                                    <th>Số km</th>
                                    <th>Tình trạng</th>
                                    <th>Trạng thái</th>
                                    <th>Giá bán</th>
                                    <th>Showroom</th>
                                    <th>Ngày nhập</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inventories as $inventory)
                                <tr>
                                    <td>{{ $inventory->id }}</td>
                                    <td>
                                        <span class="font-monospace">{{ $inventory->vin }}</span>
                                        @if($inventory->license_plate)
                                            <br><small class="text-muted">{{ $inventory->license_plate }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $inventory->carVariant->carModel->carBrand->name }}</strong><br>
                                        <small>{{ $inventory->carVariant->carModel->name }} - {{ $inventory->carVariant->name }}</small>
                                    </td>
                                    <td>{{ $inventory->color }}</td>
                                    <td>{{ number_format($inventory->mileage) }} km</td>
                                    <td>
                                        <span class="badge badge-{{ $inventory->condition == 'excellent' ? 'success' : ($inventory->condition == 'good' ? 'info' : ($inventory->condition == 'fair' ? 'warning' : 'danger')) }}">
                                            {{ ucfirst($inventory->condition) }}
                                        </span>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm status-select" 
                                                data-inventory-id="{{ $inventory->id }}"
                                                style="width: auto;">
                                            <option value="available" {{ $inventory->status == 'available' ? 'selected' : '' }}>Có sẵn</option>
                                            <option value="reserved" {{ $inventory->status == 'reserved' ? 'selected' : '' }}>Đã đặt</option>
                                            <option value="sold" {{ $inventory->status == 'sold' ? 'selected' : '' }}>Đã bán</option>
                                            <option value="in_transit" {{ $inventory->status == 'in_transit' ? 'selected' : '' }}>Đang vận chuyển</option>
                                            <option value="maintenance" {{ $inventory->status == 'maintenance' ? 'selected' : '' }}>Bảo dưỡng</option>
                                            <option value="test_drive" {{ $inventory->status == 'test_drive' ? 'selected' : '' }}>Lái thử</option>
                                        </select>
                                    </td>
                                    <td>{{ number_format($inventory->selling_price) }} VNĐ</td>
                                    <td>{{ $inventory->showroom->name }}</td>
                                    <td>{{ $inventory->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.inventory.show', $inventory->id) }}" 
                                               class="btn btn-sm btn-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.inventory.edit', $inventory->id) }}" 
                                               class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.inventory.destroy', $inventory->id) }}" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa xe này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center">Không có dữ liệu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $inventories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status change handler
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function() {
            const inventoryId = this.dataset.inventoryId;
            const newStatus = this.value;
            
            fetch(`/admin/inventory/${inventoryId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    toastr.success(data.message);
                } else {
                    // Show error message
                    toastr.error('Có lỗi xảy ra khi cập nhật trạng thái');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('Có lỗi xảy ra khi cập nhật trạng thái');
            });
        });
    });
});
</script>
@endpush
@endsection
