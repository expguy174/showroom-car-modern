@extends('admin.layouts.app')

@section('title', 'Danh sách hãng xe')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách hãng xe</h6>
            <a href="{{ route('admin.cars.create') }}" class="btn btn-sm btn-success">
                <i class="fas fa-plus"></i> Thêm mới
            </a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.cars.index') }}" class="form-inline mb-3">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2"
                    placeholder="Tìm kiếm tên hãng xe...">
                <button type="submit" class="btn btn-primary">Tìm</button>
            </form>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th width="50">ID</th>
                            <th width="80">Logo</th>
                            <th>Tên hãng</th>
                            <th>Quốc gia</th>
                            <th>Năm TL</th>
                            <th>Trạng thái</th>
                            <th>Thống kê</th>
                            <th width="150" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cars as $car)
                            <tr>
                                <td>{{ $car->id }}</td>
                                <td>
                                    @if ($car->logo_path)
                                        <img src="{{ asset('storage/' . $car->logo_path) }}" alt="Logo" class="img-thumbnail"
                                            style="height: 50px; width: 50px; object-fit: contain;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 50px; width: 50px; border: 1px solid #dee2e6;">
                                            <i class="fas fa-car text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $car->name }}</strong>
                                    @if($car->is_featured)
                                        <span class="badge badge-warning ml-1">Nổi bật</span>
                                    @endif
                                    @if($car->slug)
                                        <br><small class="text-muted">Slug: {{ $car->slug }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($car->country)
                                        <span class="badge badge-info">{{ $car->country }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($car->founded_year)
                                        {{ $car->founded_year }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($car->is_active)
                                        <span class="badge badge-success">Kích hoạt</span>
                                    @else
                                        <span class="badge badge-secondary">Không kích hoạt</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="fas fa-layer-group"></i> {{ $car->total_models ?? 0 }} dòng xe<br>
                                        <i class="fas fa-cubes"></i> {{ $car->total_variants ?? 0 }} phiên bản
                                    </small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.cars.edit', $car) }}" class="btn btn-sm btn-info" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.cars.destroy', $car) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Bạn có chắc muốn xoá hãng xe này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                    Không có dữ liệu hãng xe nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($cars->hasPages())
                <div class="mt-3">
                    {{ $cars->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection