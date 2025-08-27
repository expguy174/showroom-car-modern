@extends('admin.layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">📦 CHI TIẾT ĐƠN HÀNG</h6>
        <div>
            @php
                $statusMap = [
                    'pending' => 'confirmed',
                    'confirmed' => 'shipping',
                    'shipping' => 'delivered',
                ];
            @endphp

            @if ($order->status !== 'delivered' && $order->status !== 'cancelled')
                @if (isset($statusMap[$order->status]))
                    <form method="POST" action="{{ route('admin.orders.nextStatus', $order->id) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-primary" onclick="return confirm('Chuyển sang trạng thái tiếp theo?')">
                            Sang trạng thái: {{ ucfirst($statusMap[$order->status]) }}
                        </button>
                    </form>
                @endif
                <form method="POST" action="{{ route('admin.orders.cancel', $order->id) }}" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn huỷ đơn này?')">Huỷ đơn</button>
                </form>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <p><strong>Họ tên:</strong> {{ optional($order->user)->name }}</p>
                <p><strong>Số điện thoại:</strong> {{ optional($order->user)->phone }}</p>
                <p><strong>Email:</strong> {{ optional($order->user)->email }}</p>
                <p><strong>Địa chỉ:</strong> {{ $order->shippingAddress->line1 ?? $order->billingAddress->line1 ?? 'Không có thông tin' }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Phương thức thanh toán:</strong> {{ optional($order->paymentMethod)->name ?? 'N/A' }}</p>
                <p><strong>Trạng thái:</strong>
                    @php
                        $colors = [
                            'pending' => 'badge-warning',
                            'confirmed' => 'badge-primary',
                            'shipping' => 'badge-info',
                            'delivered' => 'badge-success',
                            'cancelled' => 'badge-danger',
                        ];
                    @endphp
                    <span class="badge {{ $colors[$order->status] ?? 'badge-secondary' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
                @if ($order->note)
                    <p><strong>Ghi chú:</strong> {{ $order->note }}</p>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Sản phẩm</th>
                        <th>Hình ảnh</th>
                        <th class="text-center">Màu</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-right">Đơn giá</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->item_name ?? ($item->item->name ?? '-') }}</td>
                            <td>
                                @if (isset($item->item->image_url) && $item->item->image_url)
                                    <img src="{{ $item->item->image_url }}" alt="image" width="60">
                                @else
                                    <span class="text-muted">Không ảnh</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->color->color_name ?? '-' }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-right font-weight-bold text-lg mt-4">
            Tổng tiền: {{ number_format($order->total_price, 0, ',', '.') }} đ
        </div>
        @php($logs = $order->logs()->with('user')->orderByDesc('created_at')->limit(50)->get())
        @include('admin.orders.partials.timeline', ['logs' => $logs])
    </div>
</div>
@endsection