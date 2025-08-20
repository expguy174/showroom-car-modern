@extends('admin.layouts.app')

@section('title', 'Danh sách phiên bản xe')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách phiên bản xe</h6>
            <a href="{{ route('admin.carvariants.create') }}" class="btn btn-sm btn-success">
                <i class="fas fa-plus"></i> Thêm mới
            </a>
        </div>
        <div class="card-body">

            {{-- Form tìm kiếm --}}
            <form method="GET" action="{{ route('admin.carvariants.index') }}" class="form-inline mb-3">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2"
                    placeholder="Tìm kiếm">
                <button type="submit" class="btn btn-primary">Tìm</button>
            </form>

            {{-- Bảng dữ liệu --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Tên phiên bản</th>
                            <th>Mẫu xe</th>
                            <th>Giá</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($carVariants as $variant)
                            <tr>
                                <td>{{ $variant->id }}</td>
                                <td>{{ $variant->name }}</td>
                                <td>{{ $variant->carModel->name ?? 'N/A' }}</td>
                                <td>{{ number_format($variant->price ?? 0, 0, ',', '.') }} đ</td>
                                <td>
                                    @if ($variant->is_active)
                                        <span class="badge badge-success">Hiển thị</span>
                                    @else
                                        <span class="badge badge-danger">Ẩn</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.carvariants.edit', $variant) }}"
                                        class="btn btn-sm btn-info">Sửa</a>
                                    <form action="{{ route('admin.carvariants.destroy', $variant) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xoá phiên bản xe này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Xoá</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Không có phiên bản nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Phân trang --}}
            <div class="mt-3">
                {{ $carVariants->appends(['search' => request('search')])->links() }}
            </div>

        </div>
    </div>
@endsection