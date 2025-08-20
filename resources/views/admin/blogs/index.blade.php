@extends('admin.layouts.app')

@section('title', 'Danh sách bài viết')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách bài viết</h6>
            <a href="{{ route('admin.blogs.create') }}" class="btn btn-sm btn-success">
                <i class="fas fa-plus"></i> Thêm mới
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>ID</th>
                            <th>Tiêu đề</th>
                            <th>Hình ảnh</th>
                            <th>Trạng thái</th>
                            <th>Ngày đăng</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($blogs as $blog)
                            <tr>
                                <td class="text-center">{{ $blog->id }}</td>
                                <td>{{ $blog->title }}</td>
                                <td class="text-center">
                                    @php $img = $blog->image_url ?? null; @endphp
                                    @if ($img)
                                        <img src="{{ $img }}" alt="Ảnh" style="height: 50px;" class="img-thumbnail" onerror="this.onerror=null;this.src='https://via.placeholder.com/100x60?text=No+Image';">
                                    @else
                                        <span class="text-muted">Không có ảnh</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($blog->is_published)
                                        <span class="badge badge-success">Đã đăng</span>
                                    @else
                                        <span class="badge badge-secondary">Bản nháp</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ optional($blog->published_at)->format('d/m/Y H:i') }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-sm btn-info">Sửa</a>
                                    <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Xác nhận xoá?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit">Xoá</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Không có bài viết nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $blogs->links() }}
            </div>
        </div>
    </div>
@endsection