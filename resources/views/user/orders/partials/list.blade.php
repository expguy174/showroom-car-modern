@if($orders->count() === 0)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 sm:p-14 text-center">
        <div class="w-16 h-16 mx-auto mb-5 bg-gray-100 rounded-full flex items-center justify-center">
            <i class="fas fa-box text-2xl text-gray-400"></i>
        </div>
        <div class="text-lg sm:text-xl font-semibold text-gray-700 mb-2">Chưa có đơn hàng</div>
        <p class="text-sm sm:text-base text-gray-500 max-w-xl mx-auto">Bạn chưa có đơn hàng nào. Khám phá sản phẩm và đặt hàng ngay hôm nay.</p>
        
    </div>
@else
    <div class="space-y-3 sm:space-y-4">
        @foreach($orders as $order)
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow order-card" data-id="{{ $order->id }}">
                <div class="p-3 sm:p-5 flex flex-col h-full">
                    <div class="flex-1 flex flex-col gap-3 sm:gap-4">
                        <div class="flex items-start sm:items-center justify-between gap-2">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <a href="{{ route('user.orders.show', $order) }}" class="text-gray-800 font-semibold truncate hover:underline">#{{ $order->order_number ?? $order->id }}</a>
                                    <span class="hidden xs:inline text-gray-400">•</span>
                                    <span class="text-gray-500 text-xs sm:text-sm">{{ $order->created_at?->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="text-xs sm:text-sm text-gray-500 mt-1 truncate" data-role="order-meta">{{ $order->items->count() }} sản phẩm</div>
                            </div>
                            @php($canCancel = in_array($order->status, ['pending','confirmed']) && $order->payment_status !== 'completed')
                            <div class="text-right shrink-0 ml-2">
                                <div class="text-indigo-700 font-extrabold text-sm sm:text-lg">{{ number_format($order->grand_total, 0, ',', '.') }} đ</div>
                                <div class="text-[11px] sm:text-xs text-gray-500">Tổng thanh toán</div>
                            </div>
                        </div>

                        
                    </div>
                    @if($order->tracking_number)
                    <div class="mt-2 sm:mt-3 text-[11px] sm:text-xs text-gray-500">
                        Mã vận đơn: <span class="font-medium text-gray-700">{{ $order->tracking_number }}</span>
                        @if($order->estimated_delivery)
                            <span class="ml-2">• Dự kiến: {{ $order->estimated_delivery->format('d/m/Y') }}</span>
                        @endif
                    </div>
                    @endif
                    <div class="mt-3 sm:mt-4 flex items-center justify-between">
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3" data-role="status-container">
                            <span class="inline-flex items-center py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-semibold
                                @class([
                                    'bg-yellow-50 text-yellow-700 border border-yellow-200' => $order->status === 'pending',
                                    'bg-blue-50 text-blue-700 border border-blue-200' => $order->status === 'confirmed',
                                    'bg-indigo-50 text-indigo-700 border border-indigo-200' => $order->status === 'shipping',
                                    'bg-emerald-50 text-emerald-700 border border-emerald-200' => $order->status === 'delivered',
                                    'bg-rose-50 text-rose-700 border border-rose-200' => $order->status === 'cancelled',
                                ])" data-role="status-badge">
                                <i class="fas 
                                    @if($order->status === 'pending') fa-clock
                                    @elseif($order->status === 'confirmed') fa-check-circle
                                    @elseif($order->status === 'shipping') fa-shipping-fast
                                    @elseif($order->status === 'delivered') fa-check-double
                                    @elseif($order->status === 'cancelled') fa-ban
                                    @else fa-box
                                    @endif mr-1"></i> {{ $order->status_display }}
                            </span>
                            <span class="inline-flex items-center py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-semibold
                                @class([
                                    'bg-gray-50 text-gray-700 border border-gray-200' => $order->payment_status === 'pending',
                                    'bg-blue-50 text-blue-700 border border-blue-200' => $order->payment_status === 'processing',
                                    'bg-emerald-50 text-emerald-700 border border-emerald-200' => $order->payment_status === 'completed',
                                    'bg-rose-50 text-rose-700 border border-rose-200' => $order->payment_status === 'failed',
                                    'bg-slate-50 text-slate-700 border border-slate-200' => $order->payment_status === 'cancelled',
                                ])">
                                <i class="fas 
                                    @if($order->payment_status === 'pending') fa-clock
                                    @elseif($order->payment_status === 'processing') fa-spinner
                                    @elseif($order->payment_status === 'completed') fa-check-circle
                                    @elseif($order->payment_status === 'failed') fa-times-circle
                                    @elseif($order->payment_status === 'cancelled') fa-ban
                                    @else fa-credit-card
                                    @endif mr-1"></i> {{ $order->payment_status_display }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('user.orders.show', $order) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs"><i class="fas fa-eye"></i> Chi tiết</a>
                            <form action="{{ route('user.orders.cancel', $order) }}" method="post" title="{{ $canCancel ? 'Hủy đơn' : 'Không thể hủy ở trạng thái hiện tại' }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-500 text-white hover:bg-rose-600 disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed" {{ $canCancel ? '' : 'disabled' }}>
                                    <i class="fas fa-ban"></i> Hủy đơn
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif


