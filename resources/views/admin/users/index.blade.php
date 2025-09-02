@extends('admin.layouts.app')

@section('title', 'Danh sách người dùng')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            Danh sách người dùng
        </h6>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="thead-light text-center">
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Quyền</th>
                        <th>Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="text-center">{{ $user->id }}</td>
                            <td>{{ optional($user->userProfile)->name ?? '-' }}</td>
                            <td>{{ $user->email ?? '-' }}</td>
                            <td>{{ optional($user->addresses->firstWhere('is_default', true) ?: $user->addresses->first())->phone ?? '-' }}</td>
                            <td class="text-center">
                                @if ($user->role === 'admin')
                                    <span class="badge badge-primary">Admin</span>
                                @else
                                    <span class="badge badge-secondary">User</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Không có người dùng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection