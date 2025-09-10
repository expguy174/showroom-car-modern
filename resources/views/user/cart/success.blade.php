@extends('layouts.app')

@section('title', 'Hoàn tất đơn hàng')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Hoàn tất đơn hàng</h1>
                        <p class="text-gray-600">Đơn hàng đã được tạo thành công</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('user.cart.index') }}" class="flex items-center text-blue-600 hover:text-blue-700 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Quay về giỏ hàng
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="bg-white border-b border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="max-w-4xl mx-auto">
                <div class="flex items-center justify-center space-x-8">
                    <a href="{{ route('user.cart.index') }}" class="flex items-center space-x-3 hover:opacity-80 transition-opacity">
                        <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-semibold">1</div>
                        <span class="font-medium text-gray-500">Giỏ hàng</span>
                    </a>
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-semibold">2</div>
                        <span class="font-medium text-gray-500">Thanh toán</span>
                    </div>
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-emerald-600 text-white rounded-full flex items-center justify-center text-sm font-semibold">3</div>
                        <span class="font-semibold text-emerald-600">Hoàn tất</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="max-w-4xl mx-auto space-y-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b bg-gradient-to-r from-indigo-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="fas fa-check"></i></div>
                    <div>
                        <h1 class="text-xl md:text-2xl font-extrabold text-gray-900">Cảm ơn bạn! Đơn hàng đã được tạo</h1>
                        <div class="text-sm text-gray-600">Mã đơn: <span class="font-semibold text-indigo-700">{{ $order->order_number ?? ('#'.$order->id) }}</span></div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-6 grid grid-cols-1 md:grid-cols-5 gap-6">
                <div class="md:col-span-3 space-y-4">
                    <div class="p-4 rounded-xl border bg-white">
                        <div class="mb-3">
                            <div class="text-sm font-semibold text-gray-800">Trạng thái</div>
                        </div>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                            <div class="flex items-center justify-between sm:justify-start sm:gap-3">
                                <dt class="text-gray-500">Đơn hàng</dt>
                                <dd>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                        @class([
                                            'bg-yellow-50 text-yellow-700 border border-yellow-200' => $order->status === 'pending',
                                            'bg-blue-50 text-blue-700 border border-blue-200' => $order->status === 'confirmed',
                                            'bg-indigo-50 text-indigo-700 border border-indigo-200' => $order->status === 'shipping',
                                            'bg-emerald-50 text-emerald-700 border border-emerald-200' => $order->status === 'delivered',
                                            'bg-rose-50 text-rose-700 border border-rose-200' => $order->status === 'cancelled',
                                        ])">{{ $order->status_display }}</span>
                                </dd>
                            </div>
                            <div class="flex items-center justify-between sm:justify-start sm:gap-3">
                                <dt class="text-gray-500">Tạo lúc</dt>
                                <dd class="font-medium text-gray-900">{{ $order->created_at?->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div class="flex items-center justify-between sm:justify-start sm:gap-3">
                                <dt class="text-gray-500">Thanh toán</dt>
                                <dd>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                        @class([
                                            'bg-gray-50 text-gray-700 border border-gray-200' => $order->payment_status === 'pending',
                                            'bg-blue-50 text-blue-700 border border-blue-200' => $order->payment_status === 'processing',
                                            'bg-emerald-50 text-emerald-700 border border-emerald-200' => $order->payment_status === 'completed',
                                            'bg-rose-50 text-rose-700 border border-rose-200' => $order->payment_status === 'failed',
                                            'bg-slate-50 text-slate-700 border border-slate-200' => $order->payment_status === 'cancelled',
                                        ])">{{ $order->payment_status_display }}</span>
                                </dd>
                            </div>
                            <div class="flex items-center justify-between sm:justify-start sm:gap-3">
                                <dt class="text-gray-500">Phương thức</dt>
                                <dd class="font-medium text-gray-900">{{ $order->paymentMethod->name ?? '—' }}</dd>
                            </div>
                        </dl>
                        @if(session('payment_method') === 'bank_transfer' || $order->paymentMethod?->code === 'bank_transfer')
                        <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="text-sm font-semibold text-blue-900 mb-2">Thông tin chuyển khoản</div>
                            <div class="space-y-1 text-sm text-blue-800">
                                <div><span class="font-medium">Ngân hàng:</span> Vietcombank - CN TP.HCM</div>
                                <div><span class="font-medium">Tên tài khoản:</span> CONG TY TNHH SHOWROOM</div>
                                <div><span class="font-medium">Số tài khoản:</span> <span class="font-mono">0123456789</span></div>
                                <div><span class="font-medium">Số tiền:</span> <span class="font-bold">{{ number_format($order->grand_total ?? $order->total_price, 0, ',', '.') }} đ</span></div>
                                <div><span class="font-medium">Nội dung:</span> <span class="font-mono">{{ $order->order_number ?? ('#'.$order->id) }}</span></div>
                            </div>
                            <div class="mt-2 text-xs text-blue-700">
                                <i class="fas fa-info-circle mr-1"></i>
                                Vui lòng chuyển khoản chính xác số tiền và nội dung để hệ thống đối soát tự động.
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="rounded-xl border overflow-hidden">
                        <div class="px-4 py-3 border-b bg-gray-50 font-semibold text-gray-800">Tóm tắt đơn hàng</div>
                        <div class="px-4 py-4">
                            @php $itemsCount = $order->items->sum('quantity'); @endphp
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <div class="text-gray-500">Mã đơn</div>
                                    <div class="font-semibold text-gray-900">{{ $order->order_number ?? ('#'.$order->id) }}</div>
                                </div>
                                <div>
                                    <div class="text-gray-500">Sản phẩm</div>
                                    <div class="font-semibold text-gray-900">{{ $itemsCount }}</div>
                                </div>
                                <div>
                                    <div class="text-gray-500">Tổng cộng</div>
                                    <div class="font-semibold text-gray-900 tabular-nums">{{ number_format($order->grand_total ?? $order->total_price, 0, ',', '.') }} đ</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <aside class="md:col-span-2">
                    <div class="rounded-xl border overflow-hidden sticky top-4">
                        <div class="px-4 py-3 border-b bg-gray-50 font-semibold text-gray-800">Tổng kết</div>
                        <div class="px-4 py-4 space-y-3">
                            @php
                                $ship = $order->shippingAddress ?: $order->billingAddress;
                                $recipientName = $ship?->contact_name
                                    ?? $ship?->full_name
                                    ?? $ship?->name
                                    ?? optional($order->user)->name
                                    ?? '';
                                $recipientPhone = $ship?->phone ?? optional($order->user)->phone ?? '';
                                $recipientEmail = optional($order->user)->email ?? '';
                            @endphp
                            <div class="text-sm text-gray-700">
                                <div class="font-semibold mb-1">Người nhận</div>
                                <div class="font-medium">{{ $recipientName !== '' ? $recipientName : (optional($order->user)->name ?? '—') }}</div>
                                <div class="text-gray-500">@if($recipientPhone) {{ $recipientPhone }} @endif @if($recipientPhone && $recipientEmail) • @endif @if($recipientEmail) {{ $recipientEmail }} @endif</div>
                            </div>
                            <div class="text-sm text-gray-700">
                                <div class="font-semibold mb-1">Địa chỉ giao</div>
                                @if($ship)
                                    <div class="space-y-1">
                                        <div>{{ $ship->address_line1 ?? $ship->address ?? '' }}</div>
                                        <div class="text-gray-500">{{ $ship->ward ?? '' }}@if($ship?->ward && $ship?->district), @endif{{ $ship->district ?? '' }}@if(($ship?->ward || $ship?->district) && $ship?->city), @endif{{ $ship->city ?? '' }}</div>
                                    </div>
                                @else
                                    <div class="text-gray-500">Không có thông tin</div>
                                @endif
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-600">
                                <span>Tạm tính</span>
                                <span>{{ number_format($order->subtotal ?? 0, 0, ',', '.') }} đ</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-600">
                                <span>Thuế</span>
                                <span>{{ number_format($order->tax_total ?? 0, 0, ',', '.') }} đ</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-600">
                                <span>Vận chuyển</span>
                                <span>{{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }} đ</span>
                            </div>
                            @if((float)($order->discount_total ?? 0) > 0)
                            <div class="flex items-center justify-between text-sm text-gray-600">
                                <span>Giảm giá</span>
                                <span class="text-rose-600">-{{ number_format($order->discount_total ?? 0, 0, ',', '.') }} đ</span>
                            </div>
                            @endif
                            <div class="border-t pt-3 flex items-center justify-between text-base font-bold text-gray-900">
                                <span>Tổng cộng</span>
                                <span>{{ number_format($order->grand_total ?? $order->total_price, 0, ',', '.') }} đ</span>
                            </div>
                            
                        </div>
                    </div>
                    @if(!empty($order->note))
                    <div class="mt-4 p-4 rounded-xl border bg-white">
                        <div class="text-sm font-semibold text-gray-700 mb-2">Ghi chú</div>
                        <div class="text-sm text-gray-700">{{ $order->note }}</div>
                    </div>
                    @endif
                </aside>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  if (typeof window.showMessage === 'function') {
    var msg = <?php echo json_encode(session('success') ?? ''); ?> || 'Đặt hàng thành công!';
    if (msg) {
      window.showMessage(msg, 'success');
    }
  }
});
</script>
@endpush
