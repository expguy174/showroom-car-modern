@extends('admin.layouts.app')

@section('title', 'Danh sách đơn hàng')

@section('content')
<div class="card shadow mb-4">
    {{-- Header + Search --}}
    <div class="card-header py-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách đơn hàng</h6>
        <form method="GET" action="{{ route('admin.orders.index') }}" class="form-inline mt-2 mt-md-0">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2" placeholder="Tìm đơn hàng...">
            <select name="status" class="form-control mr-2">
                <option value="">Trạng thái</option>
                @foreach(\App\Models\Order::STATUSES as $st)
                    <option value="{{ $st }}" @selected(request('status')===$st)>{{ ucfirst($st) }}</option>
                @endforeach
            </select>
            <select name="payment_status" class="form-control mr-2">
                <option value="">TT thanh toán</option>
                @php($__adminPaymentStatuses=['pending','processing','completed','failed','cancelled'])
                @foreach($__adminPaymentStatuses as $pst)
                    <option value="{{ $pst }}" @selected(request('payment_status')===$pst)>{{ (new \App\Models\Order(['payment_status'=>$pst]))->payment_status_display }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Lọc</button>
        </form>
    </div>

    {{-- Table --}}
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-sm">
                <thead class="thead-light text-center">
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>SĐT</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>TT thanh toán</th>
                        <th>Ngày tạo</th>
                        <th width="250">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td class="text-center">{{ $order->id }}</td>
                            <td>{{ optional($order->user)->name }}</td>
                            <td>{{ optional($order->user)->phone }}</td>
                            <td>{{ number_format($order->total_price, 0, ',', '.') }} đ</td>
                            <td class="text-center">
                                @php
                                    $colors = [
                                        'pending' => 'warning',
                                        'confirmed' => 'info',
                                        'shipping' => 'primary',
                                        'delivered' => 'success',
                                        'cancelled' => 'danger',
                                    ];
                                @endphp
                                <span class="badge badge-{{ $colors[$order->status] ?? 'secondary' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @php
                                    $payColors = [
                                        'pending' => 'secondary',
                                        'processing' => 'info',
                                        'completed' => 'success',
                                        'failed' => 'danger',
                                        'cancelled' => 'dark',
                                    ];
                                @endphp
                                <span class="badge badge-{{ $payColors[$order->payment_status] ?? 'light' }}">
                                    {{ (new \App\Models\Order(['payment_status'=>$order->payment_status]))->payment_status_display }}
                                </span>
                            </td>
                            <td class="text-center">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                                <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <a href="{{ route('admin.orders.logs', $order->id) }}" class="btn btn-sm btn-dark">
                                    <i class="fas fa-history"></i> Logs
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Không có đơn hàng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $orders->appends(['search' => request('search')])->links() }}
        </div>
    </div>
</div>
@endsection