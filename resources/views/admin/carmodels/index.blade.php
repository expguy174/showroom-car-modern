@extends('admin.layouts.app')

@section('title', 'Danh sách mẫu xe')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách mẫu xe</h6>
        <a href="{{ route('admin.carmodels.create') }}" class="btn btn-sm btn-success">
            <i class="fas fa-plus"></i> Thêm mới
        </a>
    </div>
    <div class="card-body">
        <form class="form-inline mb-3" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2" placeholder="Tìm kiếm...">
            <button type="submit" class="btn btn-primary">Tìm</button>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Tên mẫu xe</th>
                        <th>Hãng</th>
                        <th>Trạng thái</th>
                        <th width="180">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($carModels as $model)
                    <tr>
                        <td>{{ $model->id }}</td>
                        <td>{{ $model->name }}</td>
                        <td>{{ $model->carBrand->name ?? 'N/A' }}</td>
                        <td>
                            @if ($model->is_active)
                                <span class="badge badge-success">Hiển thị</span>
                            @else
                                <span class="badge badge-secondary">Ẩn</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.carmodels.edit', $model) }}" class="btn btn-sm btn-info">Sửa</a>
                            <form action="{{ route('admin.carmodels.destroy', $model) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá mẫu xe này?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Xoá</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Không có dữ liệu.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $carModels->appends(request()->query())->links() }}
    </div>
</div>
@endsection