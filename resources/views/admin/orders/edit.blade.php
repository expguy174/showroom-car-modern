@extends('admin.layouts.app')

@section('title', 'Cập nhật đơn hàng')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            Cập nhật đơn hàng
        </h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Họ tên</label>
                <input type="text" name="name" value="{{ $order->name }}" class="form-control bg-light" disabled>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ $order->email }}" class="form-control bg-light" disabled>
            </div>

            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="phone" value="{{ $order->phone }}" class="form-control bg-light" disabled>
            </div>

            <div class="form-group">
                <label>Địa chỉ</label>
                <input type="text" name="address" value="{{ $order->address }}" class="form-control bg-light" disabled>
            </div>

            <div class="form-group">
                <label>Phương thức thanh toán</label>
                <input type="text" value="{{ optional($order->paymentMethod)->name ?? 'N/A' }}" class="form-control bg-light" disabled>
            </div>

            <div class="form-group">
                <label>Trạng thái đơn hàng</label><br>
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
            </div>

            <div class="form-group">
                <label>Ghi chú</label>
                <textarea name="note" class="form-control" rows="4">{{ old('note', $order->note) }}</textarea>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-primary">
                    Cập nhật đơn hàng
                </button>
            </div>
        </form>
    </div>
</div>
@endsection