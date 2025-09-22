@extends('layouts.admin')

@section('title', 'Lịch sử đơn hàng')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-clipboard-list"></i> LỊCH SỬ ĐƠN HÀNG #{{ $order->id }}
        </h6>
    </div>
    <div class="card-body">
        <p><strong>Khách hàng:</strong> {{ optional($order->user)->name }}</p>
        <p><strong>Trạng thái hiện tại:</strong> 
            <span class="badge 
                @if($order->status == 'pending') badge-warning
                @elseif($order->status == 'confirmed') badge-info
                @elseif($order->status == 'shipping') badge-primary
                @elseif($order->status == 'delivered') badge-success
                @elseif($order->status == 'cancelled') badge-danger
                @endif
            ">
                {{ ucfirst($order->status) }}
            </span>
        </p>

        <div class="table-responsive mt-4">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Người thao tác</th>
                        <th>Hành động</th>
                        <th>Thời gian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $log)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                {{ $log->user->name ?? 'System' }}<br>
                                <small class="text-muted">{{ $log->user->email ?? '-' }}</small>
                            </td>
                            <td>{{ $log->action }}</td>
                            <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Chưa có lịch sử thao tác nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-right mt-3">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>
</div>
@endsection