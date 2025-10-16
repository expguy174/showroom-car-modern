@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . ($order->order_number ?? $order->id))

@section('content')
{{-- Flash Messages Component --}}
<x-admin.flash-messages 
    :show-icons="true"
    :dismissible="true"
    position="top-right"
    :auto-hide="5000" />

<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-shopping-cart text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Đơn hàng #{{ $order->order_number ?? $order->id }}
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Tạo lúc {{ $order->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                {{-- Edit button removed - all editing done inline on this page --}}
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Order Items --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-box text-blue-600 mr-3"></i>
                    Sản phẩm đã đặt ({{ $order->items->count() }})
                </h2>
                
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        {{-- Product Image --}}
                        <div class="flex-shrink-0 w-20 h-20 bg-gray-200 rounded-lg overflow-hidden">
                            @if(isset($item->item->image_url) && $item->item->image_url)
                                <img src="{{ str_starts_with($item->item->image_url, 'http') ? $item->item->image_url : asset('storage/' . $item->item->image_url) }}" 
                                     alt="{{ $item->item_name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Product Info --}}
                        <div class="flex-1 min-w-0">
                            @php
                                $metadata = is_string($item->item_metadata) ? json_decode($item->item_metadata, true) : $item->item_metadata;
                            @endphp
                            
                            <h3 class="text-sm font-medium text-gray-900">
                                {{ $metadata['base_name'] ?? ($item->item->name ?? $item->item_name ?? 'Sản phẩm không xác định') }}
                            </h3>
                            <div class="flex items-center gap-3 mt-1 text-xs text-gray-600">
                                <span>SL: {{ $item->quantity }}</span>
                                @php
                                    // Prioritize metadata color, fallback to relationship
                                    $colorDisplay = null;
                                    if ($metadata && isset($metadata['color']) && $metadata['color']) {
                                        $colorDisplay = [
                                            'name' => $metadata['color']['name'],
                                            'hex' => $metadata['color']['hex'] ?? '#cccccc'
                                        ];
                                    } elseif ($item->color) {
                                        $colorDisplay = [
                                            'name' => $item->color->color_name,
                                            'hex' => $item->color->hex_code ?? '#cccccc'
                                        ];
                                    }
                                @endphp
                                @if($colorDisplay)
                                <span class="flex items-center">
                                    Màu: 
                                    <div class="w-3 h-3 rounded-full border border-gray-300 mx-1" 
                                         style="background-color: {{ $colorDisplay['hex'] }}"></div>
                                    {{ $colorDisplay['name'] }}
                                </span>
                                @endif
                            </div>
                            
                            @if($metadata && isset($metadata['features']) && !empty($metadata['features']))
                            <p class="text-xs text-gray-600 mt-1">
                                Tuỳ chọn: {{ collect($metadata['features'])->pluck('name')->join(', ') }}
                            </p>
                            @endif
                        </div>

                        {{-- Price Breakdown --}}
                        <div class="text-right text-xs space-y-0.5">
                            @if($metadata)
                                {{-- Base Price --}}
                                @if(isset($metadata['base_price']))
                                <div class="text-gray-500">
                                    <span class="line-through">Gốc: {{ number_format($metadata['base_price'], 0, ',', '.') }} đ</span>
                                </div>
                                @endif
                                
                                {{-- Discount if any --}}
                                @if($item->discount_amount > 0)
                                <div class="text-red-500">
                                    Giảm giá: -{{ number_format($item->discount_amount, 0, ',', '.') }} đ
                                </div>
                                @endif
                                
                                {{-- Current Price --}}
                                @if(isset($metadata['base_price']))
                                <div class="text-gray-700">
                                    Hiện tại: {{ number_format($metadata['base_price'] - ($item->discount_amount ?? 0), 0, ',', '.') }} đ
                                </div>
                                @endif
                                
                                {{-- Color Price --}}
                                @if(isset($metadata['color_price']) && $metadata['color_price'] != 0)
                                <div class="text-blue-600">
                                    Màu {{ $metadata['color']['name'] ?? '' }}: +{{ number_format($metadata['color_price'], 0, ',', '.') }} đ
                                </div>
                                @endif
                                
                                {{-- Features --}}
                                @if(isset($metadata['features']) && !empty($metadata['features']))
                                    @foreach($metadata['features'] as $feature)
                                    <div class="text-blue-600">
                                        {{ $feature['name'] }}: +{{ number_format($feature['price'], 0, ',', '.') }} đ
                                    </div>
                                    @endforeach
                                @endif
                            @endif
                            
                            {{-- Line Total --}}
                            <div class="pt-1 border-t border-gray-300 mt-1">
                                <p class="text-sm font-bold text-gray-900">
                                    {{ number_format($item->line_total, 0, ',', '.') }} đ
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Order Summary --}}
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tạm tính:</span>
                            <span class="text-gray-900">{{ number_format($order->subtotal, 0, ',', '.') }} VNĐ</span>
                        </div>
                        @if($order->discount_total > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Giảm giá:</span>
                            <span class="text-red-600">-{{ number_format($order->discount_total, 0, ',', '.') }} VNĐ</span>
                        </div>
                        @endif
                        @if($order->tax_total > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Thuế:</span>
                            <span class="text-gray-900">{{ number_format($order->tax_total, 0, ',', '.') }} VNĐ</span>
                        </div>
                        @endif
                        @if($order->shipping_fee > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Phí vận chuyển:</span>
                            <span class="text-gray-900">{{ number_format($order->shipping_fee, 0, ',', '.') }} VNĐ</span>
                        </div>
                        @endif
                        @if($order->payment_fee > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Phí thanh toán:</span>
                            <span class="text-gray-900">{{ number_format($order->payment_fee, 0, ',', '.') }} VNĐ</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200">
                            <span class="text-gray-900">Tổng cộng:</span>
                            <span class="text-blue-600">{{ number_format($order->grand_total ?? $order->total_price, 0, ',', '.') }} VNĐ</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order Timeline --}}
            @php
                $totalLogs = $order->logs()->count();
                $showAll = request()->boolean('all_logs');
                $displayLogs = $order->logs()->with('user')->orderByDesc('created_at')
                    ->when(!$showAll, fn($q) => $q->limit(20))
                    ->get();
            @endphp
            
            @if($totalLogs > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-history text-purple-600 mr-3"></i>
                        Lịch sử đơn hàng
                        <span class="ml-2 px-2 py-1 bg-purple-100 text-purple-700 text-xs font-medium rounded-full">
                            {{ $totalLogs }} hoạt động
                        </span>
                    </h2>
                    
                    @if($totalLogs > 20)
                    <button onclick="loadAllLogs()" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                        <i class="fas fa-expand-alt mr-1"></i>
                        Xem tất cả ({{ $totalLogs }})
                    </button>
                    @endif
                </div>
                
                <div id="timeline-container" class="max-h-[40rem] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                    @include('admin.orders.partials.timeline', ['logs' => $displayLogs])
                </div>
                
                @if($totalLogs > 20 && !$showAll)
                <div class="mt-4 text-center">
                    <button onclick="loadAllLogs()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-chevron-down mr-2"></i>
                        Tải thêm ({{ $totalLogs - 20 }} hoạt động)
                    </button>
                </div>

                @endif
                
                @if($totalLogs > 20)
                <script>
                function loadAllLogs() {
                    const button = event.target;
                    button.disabled = true;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang tải...';
                    
                    fetch('{{ route("admin.orders.show", $order->id) }}?all_logs=1')
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newTimeline = doc.querySelector('#timeline-container');
                            if (newTimeline) {
                                document.querySelector('#timeline-container').innerHTML = newTimeline.innerHTML;
                            }
                            // Hide load more button
                            const loadMoreDiv = button.closest('.mt-4');
                            if (loadMoreDiv) loadMoreDiv.style.display = 'none';
                        })
                        .catch(error => {
                            console.error('Error loading logs:', error);
                            button.disabled = false;
                            button.innerHTML = '<i class="fas fa-chevron-down mr-2"></i>Tải thêm';
                        });
                }
                </script>
                @endif
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Status & Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Trạng thái</h3>
                
                {{-- Order Status --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái đơn hàng</label>
                    @if($order->status == 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-2"></i>Chờ xử lý
                        </span>
                    @elseif($order->status == 'confirmed')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-check-circle mr-2"></i>Đã xác nhận
                        </span>
                    @elseif($order->status == 'shipping')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                            <i class="fas fa-truck mr-2"></i>Đang giao
                        </span>
                    @elseif($order->status == 'delivered')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-2"></i>Đã giao
                        </span>
                    @elseif($order->status == 'cancelled')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-2"></i>Đã hủy
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-question mr-2"></i>{{ $order->status ?? 'Không xác định' }}
                        </span>
                    @endif
                </div>

                {{-- Payment Status --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái thanh toán</label>
                    @if($order->payment_status == 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-clock mr-2"></i>Chờ thanh toán
                        </span>
                    @elseif($order->payment_status == 'processing')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-spinner mr-2"></i>Đang xử lý
                        </span>
                    @elseif($order->payment_status == 'completed')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-2"></i>Đã thanh toán
                        </span>
                    @elseif($order->payment_status == 'failed')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Thất bại
                        </span>
                    @elseif($order->payment_status == 'cancelled')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-times-circle mr-2"></i>Đã hủy
                        </span>
                    @elseif($order->payment_status == 'partial')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                            <i class="fas fa-coins mr-2"></i>Thanh toán một phần
                        </span>
                    @elseif($order->payment_status == 'refunded')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                            <i class="fas fa-undo mr-2"></i>Đã hoàn tiền
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-question mr-2"></i>{{ $order->payment_status ?? 'Không xác định' }}
                        </span>
                    @endif
                </div>

                {{-- Payment Actions --}}
                <div class="pt-4 border-t border-gray-200 space-y-3">
                    {{-- Update Payment Status --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            {{ $order->payment_status == 'refunded' ? 'Trạng thái thanh toán' : 'Cập nhật trạng thái thanh toán' }}
                        </label>
                        @if($order->payment_status == 'refunded')
                            {{-- Read-only display for refunded orders --}}
                            <div class="flex items-center gap-2 px-4 py-2 bg-purple-50 border border-purple-200 rounded-lg">
                                <i class="fas fa-undo text-purple-600"></i>
                                <span class="text-sm font-medium text-purple-800">Đã hoàn tiền</span>
                                <span class="text-xs text-purple-600 ml-auto">(Không thể thay đổi)</span>
                            </div>
                        @elseif($order->finance_option_id && $order->installments()->exists())
                            {{-- Special handling for installment orders --}}
                            @php
                                $unpaidInstallments = $order->installments()->where('status', '!=', 'paid')->count();
                                $totalInstallments = $order->installments()->count();
                                $paidInstallments = $totalInstallments - $unpaidInstallments;
                            @endphp
                            
                            @if($order->status == 'cancelled')
                                {{-- Read-only display for cancelled installment orders --}}
                                <div class="flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                                    <i class="fas fa-ban text-gray-600"></i>
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-gray-800">Đơn hàng trả góp đã hủy</span>
                                        <div class="text-xs text-gray-600 mt-1">
                                            Đã thanh toán: {{ $paidInstallments }}/{{ $totalInstallments }} kỳ trước khi hủy
                                        </div>
                                    </div>
                                </div>
                            @elseif($order->payment_status == 'completed')
                                <div class="flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-200 rounded-lg">
                                    <i class="fas fa-check text-green-600"></i>
                                    <span class="text-sm font-medium text-green-800">Đã thanh toán</span>
                                    <span class="text-xs text-green-600 ml-auto">(Tự động sau khi hoàn thành trả góp)</span>
                                </div>
                            @else
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 border border-blue-200 rounded-lg">
                                        <i class="fas fa-calendar-check text-blue-600"></i>
                                        <div class="flex-1">
                                            <span class="text-sm font-medium text-blue-800">Đơn hàng trả góp</span>
                                            <div class="text-xs text-blue-600 mt-1">
                                                Đã thanh toán: {{ $paidInstallments }}/{{ $totalInstallments }} kỳ
                                                @if($unpaidInstallments > 0)
                                                    (còn {{ $unpaidInstallments }} kỳ)
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 px-2">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Trạng thái sẽ tự động chuyển thành "Đã thanh toán" khi hoàn thành tất cả kỳ trả góp
                                    </p>
                                </div>
                            @endif
                        @else
                            {{-- Editable dropdown for regular orders (not cancelled) --}}
                            @if($order->status == 'cancelled')
                                {{-- Read-only display for cancelled orders --}}
                                <div class="flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                                    <i class="fas fa-ban text-gray-600"></i>
                                    <span class="text-sm font-medium text-gray-800">{{ $order->payment_status == 'cancelled' ? 'Đã hủy' : ucfirst($order->payment_status) }}</span>
                                    <span class="text-xs text-gray-600 ml-auto">(Đơn hàng đã hủy)</span>
                                </div>
                            @elseif($order->payment_status == 'completed')
                                {{-- Read-only display for completed payments --}}
                                <div class="flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-200 rounded-lg">
                                    <i class="fas fa-check text-green-600"></i>
                                    <span class="text-sm font-medium text-green-800">Đã thanh toán</span>
                                    <span class="text-xs text-green-600 ml-auto">(Không thể thay đổi)</span>
                                </div>
                            @elseif($order->payment_status == 'failed')
                                {{-- Allow retry for failed payments --}}
                                <form method="POST" action="{{ route('admin.orders.update-payment-status', $order) }}" class="flex gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="payment_status" class="flex-1 text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                        <option value="failed" selected>Thất bại</option>
                                        <option value="completed">Đã thanh toán</option>
                                    </select>
                                    <button type="submit" class="px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium rounded-lg transition">
                                        Cập nhật
                                    </button>
                                </form>
                            @else
                                {{-- Pending status - only allow completed or failed --}}
                                <form method="POST" action="{{ route('admin.orders.update-payment-status', $order) }}" class="flex gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="payment_status" class="flex-1 text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                        <option value="pending" selected>Chờ thanh toán</option>
                                        <option value="completed">Đã thanh toán</option>
                                        <option value="failed">Thất bại</option>
                                    </select>
                                    <button type="submit" class="px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium rounded-lg transition">
                                        Cập nhật
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>

                    {{-- Refund Actions --}}
                    @if($order->payment_status == 'completed')
                    @php
                        $hasUserRefundRequest = $order->hasPendingRefundRequest();
                    @endphp
                    
                    @if($hasUserRefundRequest)
                        {{-- If user has pending refund request --}}
                        <a href="{{ route('admin.payments.refunds') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                            <i class="fas fa-list mr-2"></i>
                            Xử lý yêu cầu hoàn tiền
                        </a>
                        <p class="text-xs text-blue-600 mt-1 text-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Khách hàng đã yêu cầu hoàn tiền
                        </p>
                    @else
                        {{-- Direct refund by admin --}}
                        <button onclick="document.getElementById('refundModal').classList.remove('hidden')" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition">
                            <i class="fas fa-undo mr-2"></i>
                            Hoàn tiền trực tiếp
                        </button>
                        <p class="text-xs text-gray-500 mt-1 text-center">
                            Admin chủ động hoàn tiền
                        </p>
                    @endif
                    @endif
                </div>

                {{-- Quick Actions --}}
                @php
                    // Check if customer has any refund request (exclude only rejected/failed)
                    $hasActiveRefund = $order->refunds()->whereIn('refunds.status', ['pending', 'processing', 'refunded'])->exists();
                    $hasPendingRefund = $order->refunds()->whereIn('refunds.status', ['pending', 'processing'])->exists();
                @endphp
                @if($order->status != 'delivered' && $order->status != 'cancelled' && !$hasActiveRefund)
                <div class="pt-4 border-t border-gray-200 space-y-2">
                    @if($order->status == 'pending')
                        @if(in_array($order->payment_status, ['partial', 'completed']))
                            {{-- Show confirm button when payment is partial or completed --}}
                            <form method="POST" action="{{ route('admin.orders.nextStatus', $order->id) }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-arrow-right mr-2"></i>
                                    Xác nhận đơn
                                </button>
                            </form>
                        @elseif($order->finance_option_id && $order->down_payment_amount)
                            {{-- Show down payment confirmation for installment orders --}}
                            <div class="w-full space-y-2">
                                <button onclick="openDownPaymentModal()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-credit-card mr-2"></i>
                                    Xác nhận tiền cọc
                                </button>
                                <button disabled class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                                    <i class="fas fa-lock mr-2"></i>
                                    Xác nhận đơn
                                </button>
                                <p class="text-xs text-amber-600 mt-2 text-center">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Vui lòng xác nhận tiền cọc {{ number_format($order->down_payment_amount) }} VNĐ trước
                                </p>
                            </div>
                        @else
                            {{-- Show disabled state for one-time payment orders --}}
                            <div class="w-full">
                                <button disabled class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                                    <i class="fas fa-lock mr-2"></i>
                                    Xác nhận đơn
                                </button>
                                <p class="text-xs text-amber-600 mt-2 text-center">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Vui lòng cập nhật trạng thái thanh toán thành "Đã thanh toán" trước
                                </p>
                            </div>
                        @endif
                    @elseif($order->status == 'confirmed')
                        @if(in_array($order->payment_status, ['partial', 'completed']))
                            {{-- Allow shipping when payment is partial or completed --}}
                            <form method="POST" action="{{ route('admin.orders.nextStatus', $order->id) }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-arrow-right mr-2"></i>
                                    Bắt đầu giao hàng
                                </button>
                            </form>
                        @else
                            {{-- Show disabled state when payment not completed --}}
                            <div class="w-full">
                                <button disabled class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                                    <i class="fas fa-lock mr-2"></i>
                                    Bắt đầu giao hàng
                                </button>
                                <p class="text-xs text-amber-600 mt-2 text-center">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    @if($order->finance_option_id)
                                        Cần xác nhận tiền cọc trước khi giao hàng
                                    @else
                                        Cần hoàn tất thanh toán trước khi giao hàng
                                    @endif
                                </p>
                            </div>
                        @endif
                    @elseif($order->status == 'shipping')
                    <form method="POST" action="{{ route('admin.orders.nextStatus', $order->id) }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-arrow-right mr-2"></i>
                            Hoàn tất giao hàng
                        </button>
                    </form>
                    @endif

                    @php
                        // Check if order can be cancelled
                        $canCancel = true;
                        $cancelReason = '';
                        
                        // Cannot cancel delivered or already cancelled orders
                        if (in_array($order->status, ['delivered', 'cancelled'])) {
                            $canCancel = false;
                            $cancelReason = $order->status === 'delivered' 
                                ? 'Đơn hàng đã giao không thể hủy' 
                                : 'Đơn hàng đã được hủy';
                        }
                        // Cannot cancel fully paid orders
                        elseif ($order->payment_status === 'completed') {
                            $canCancel = false;
                            $cancelReason = 'Đơn hàng đã thanh toán đầy đủ không thể hủy';
                        }
                        // Cannot cancel installment orders with confirmed down payment
                        elseif ($order->finance_option_id && $order->payment_status === 'partial') {
                            $hasDownPayment = $order->paymentTransactions()
                                ->where('notes', 'LIKE', '%Down payment%')
                                ->where('status', 'completed')
                                ->exists();
                            if ($hasDownPayment) {
                                $canCancel = false;
                                $cancelReason = 'Đơn trả góp đã xác nhận tiền cọc không thể hủy';
                            }
                        }
                    @endphp

                    @if($canCancel)
                    <form method="POST" action="{{ route('admin.orders.cancel', $order->id) }}" class="w-full" id="cancelOrderForm">
                        @csrf
                        <input type="hidden" name="reason" id="cancelReason" value="">
                        <button type="button" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors" 
                                onclick="showCancelOrderModal()">
                            <i class="fas fa-times-circle mr-2"></i>
                            Hủy đơn hàng
                        </button>
                    </form>
                    @else
                    <div class="w-full p-3 bg-gray-100 border border-gray-200 rounded-lg text-center">
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-2"></i>
                            {{ $cancelReason }}
                        </div>
                    </div>
                    @endif
                </div>
                @elseif($hasActiveRefund)
                {{-- Show notice when customer has refund --}}
                <div class="pt-4 border-t border-gray-200">
                    @if($hasPendingRefund)
                        <div class="w-full p-4 bg-amber-50 border border-amber-200 rounded-lg">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-hand-holding-usd text-amber-600 text-xl mt-0.5"></i>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-amber-900 mb-1">Khách hàng yêu cầu hoàn tiền</h4>
                                    <p class="text-xs text-amber-800 leading-relaxed">
                                        Đơn hàng đang chờ xử lý hoàn tiền. Vui lòng phê duyệt hoặc từ chối yêu cầu trong trang Hoàn tiền.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="w-full p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-green-600 text-xl mt-0.5"></i>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-green-900 mb-1">Hoàn tiền đã hoàn tất</h4>
                                    <p class="text-xs text-green-800 leading-relaxed">
                                        Đơn hàng này đã được xử lý hoàn tiền thành công. Quy trình đã kết thúc.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                @endif
            </div>

            {{-- Customer Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user text-blue-600 mr-2"></i>
                    Thông tin khách hàng
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="font-medium text-gray-700">Họ tên:</span>
                        <p class="text-gray-900 mt-1">
                            {{ $order->shippingAddress->contact_name ?? $order->billingAddress->contact_name ?? $order->user->name ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Email:</span>
                        <p class="text-gray-900 mt-1">{{ $order->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Số điện thoại:</span>
                        <p class="text-gray-900 mt-1">
                            {{ $order->shippingAddress->phone ?? $order->billingAddress->phone ?? $order->user->phone ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Billing & Shipping Address --}}
            @if($order->billingAddress || $order->shippingAddress)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                    Địa chỉ
                </h3>
                <div class="space-y-4 text-sm">
                    @if($order->billingAddress)
                    <div class="pb-3 @if($order->shippingAddress && $order->billing_address_id != $order->shipping_address_id) border-b border-gray-200 @endif">
                        <h4 class="font-medium text-gray-700 mb-2">
                            <i class="fas fa-file-invoice mr-1"></i>
                            Địa chỉ thanh toán:
                        </h4>
                        <p class="text-gray-900">{{ $order->billingAddress->address }}</p>
                        <p class="text-gray-900">{{ $order->billingAddress->city }}@if($order->billingAddress->state), {{ $order->billingAddress->state }}@endif</p>
                        @if($order->billingAddress->postal_code)
                        <p class="text-gray-600 text-xs">Mã bưu điện: {{ $order->billingAddress->postal_code }}</p>
                        @endif
                        <p class="text-gray-600 text-xs mt-1">{{ $order->billingAddress->contact_name }} - {{ $order->billingAddress->phone }}</p>
                    </div>
                    @endif
                    
                    @if($order->shippingAddress && $order->billing_address_id != $order->shipping_address_id)
                    <div>
                        <h4 class="font-medium text-gray-700 mb-2">
                            <i class="fas fa-shipping-fast mr-1"></i>
                            Địa chỉ giao hàng:
                        </h4>
                        <p class="text-gray-900">{{ $order->shippingAddress->address }}</p>
                        <p class="text-gray-900">{{ $order->shippingAddress->city }}@if($order->shippingAddress->state), {{ $order->shippingAddress->state }}@endif</p>
                        @if($order->shippingAddress->postal_code)
                        <p class="text-gray-600 text-xs">Mã bưu điện: {{ $order->shippingAddress->postal_code }}</p>
                        @endif
                        <p class="text-gray-600 text-xs mt-1">{{ $order->shippingAddress->contact_name }} - {{ $order->shippingAddress->phone }}</p>
                    </div>
                    @elseif($order->billing_address_id == $order->shipping_address_id && $order->billingAddress)
                    <p class="text-xs text-gray-500 italic">
                        <i class="fas fa-check-circle text-green-600 mr-1"></i>
                        Địa chỉ giao hàng trùng với địa chỉ thanh toán
                    </p>
                    @endif
                    @if($order->shipping_method)
                    <div>
                        <span class="font-medium text-gray-700">Phương thức:</span>
                        <p class="text-gray-900 mt-1">
                            @if($order->shipping_method == 'standard')
                                Giao hàng tiêu chuẩn
                            @elseif($order->shipping_method == 'express')
                                Giao hàng nhanh
                            @else
                                {{ $order->shipping_method }}
                            @endif
                        </p>
                    </div>
                    @endif
                    @if($order->tracking_number)
                    <div>
                        <span class="font-medium text-gray-700">Mã vận đơn:</span>
                        <p class="text-gray-900 mt-1 font-mono">{{ $order->tracking_number }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Payment Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-credit-card text-green-600 mr-2"></i>
                    Thông tin thanh toán
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="font-medium text-gray-700">Loại thanh toán:</span>
                        <p class="text-gray-900 mt-1">
                            @if($order->isInstallmentOrder())
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Trả góp
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>
                                    Thanh toán một lần
                                </span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Phương thức:</span>
                        <p class="text-gray-900 mt-1">{{ $order->paymentMethod->name ?? 'N/A' }}</p>
                    </div>
                    @if($order->transaction_id)
                    <div>
                        <span class="font-medium text-gray-700">Mã giao dịch:</span>
                        <p class="text-gray-900 mt-1 font-mono">{{ $order->transaction_id }}</p>
                    </div>
                    @endif
                    @if($order->paid_at)
                    <div>
                        <span class="font-medium text-gray-700">Thanh toán lúc:</span>
                        <p class="text-gray-900 mt-1">{{ $order->paid_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Finance Info (for installment orders) --}}
            @if($order->isInstallmentOrder())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-money-bill-wave text-orange-600 mr-2"></i>
                    Thông tin trả góp
                </h3>
                <div class="space-y-3 text-sm">
                    @if($order->financeOption)
                    <div>
                        <span class="font-medium text-gray-700">Gói tài chính:</span>
                        <p class="text-gray-900 mt-1">{{ $order->financeOption->name }}</p>
                    </div>
                    @endif
                    @if($order->down_payment_amount)
                    <div>
                        <span class="font-medium text-gray-700">Trả trước:</span>
                        <p class="text-gray-900 mt-1 font-semibold text-blue-600">
                            {{ number_format($order->down_payment_amount, 0, ',', '.') }} VNĐ
                        </p>
                    </div>
                    @endif
                    @if($order->tenure_months)
                    <div>
                        <span class="font-medium text-gray-700">Thời hạn:</span>
                        <p class="text-gray-900 mt-1">{{ $order->tenure_months }} tháng</p>
                    </div>
                    @endif
                    @if($order->monthly_payment_amount)
                    <div>
                        <span class="font-medium text-gray-700">Trả hàng tháng:</span>
                        <p class="text-gray-900 mt-1 font-semibold text-orange-600">
                            {{ number_format($order->monthly_payment_amount, 0, ',', '.') }} VNĐ/tháng
                        </p>
                    </div>
                    @endif
                    @if($order->tenure_months && $order->monthly_payment_amount && $order->down_payment_amount)
                    <div class="pt-3 border-t border-gray-200">
                        <span class="font-medium text-gray-700">Tổng số tiền trả:</span>
                        <p class="text-gray-900 mt-1 font-bold">
                            {{ number_format($order->down_payment_amount + ($order->monthly_payment_amount * $order->tenure_months), 0, ',', '.') }} VNĐ
                        </p>
                    </div>
                    @endif
                    
                    {{-- Installments Status --}}
                    <div class="pt-3 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <span class="font-medium text-gray-700">Lịch trả góp:</span>
                                @if($order->installments->count() > 0)
                                    @php
                                        $totalInstallments = $order->installments->count();
                                        $paidInstallments = $order->installments->where('status', 'paid')->count();
                                        $isCompleted = $paidInstallments === $totalInstallments;
                                    @endphp
                                    <div class="mt-2 space-y-2">
                                        <div class="flex items-center gap-2">
                                            @if($isCompleted)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    <i class="fas fa-check-double mr-1"></i>
                                                    Hoàn thành {{ $totalInstallments }}/{{ $totalInstallments }} kỳ
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Đã trả {{ $paidInstallments }}/{{ $totalInstallments }} kỳ
                                                </span>
                                            @endif
                                        </div>
                                        {{-- Progress Bar --}}
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-600 h-2 rounded-full transition-all" style="width: {{ $totalInstallments > 0 ? ($paidInstallments / $totalInstallments * 100) : 0 }}%"></div>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-yellow-600 text-sm mt-1">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Chưa tạo lịch
                                    </p>
                                @endif
                            </div>
                            @if($order->installments->count() == 0)
                            <form method="POST" action="{{ route('admin.orders.generate-installments', $order) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-medium rounded-lg transition">
                                    <i class="fas fa-calendar-plus mr-1"></i>
                                    Tạo lịch trả góp
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    
                    {{-- View Installments Details --}}
                    @if($order->installments->count() > 0)
                    <div class="pt-3 border-t border-gray-200">
                        <a href="{{ route('admin.installments.show', $order->id) }}" 
                           class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Xem chi tiết lịch trả góp
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Notes --}}
            @if($order->note)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-sticky-note text-yellow-600 mr-2"></i>
                    Ghi chú
                </h3>
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $order->note }}</p>
            </div>
            @endif
        </div>
    </div>
</div>


{{-- Mark as Paid Modal --}}
<div id="markAsPaidModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Xác nhận thanh toán kỳ trả góp</h3>
                <button onclick="document.getElementById('markAsPaidModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form method="POST" id="markAsPaidForm" onsubmit="return handleMarkAsPaid(event)">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Phương thức thanh toán <span class="text-red-500">*</span>
                    </label>
                    <select name="payment_method_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500" required>
                        <option value="">Chọn phương thức</option>
                        @foreach(\App\Models\PaymentMethod::where('is_active', true)->get() as $method)
                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ngày thanh toán <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="payment_date" 
                           value="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                           required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                    <textarea name="notes" 
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                              placeholder="Ghi chú về giao dịch (tùy chọn)"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" 
                            onclick="document.getElementById('markAsPaidModal').classList.add('hidden')"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Hủy
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                        <i class="fas fa-check mr-2"></i>
                        Xác nhận thanh toán
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showMarkAsPaidModal(installmentId) {
    const form = document.getElementById('markAsPaidForm');
    form.action = `/admin/installments/${installmentId}/mark-as-paid`;
    
    document.getElementById('markAsPaidModal').classList.remove('hidden');
}

function handleMarkAsPaid(event) {
    event.preventDefault();
    const form = event.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Đang xử lý...';
    
    fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (window.showMessage) {
                window.showMessage(data.message, 'success');
            }
            document.getElementById('markAsPaidModal').classList.add('hidden');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.showMessage) {
            window.showMessage(error.message || 'Có lỗi xảy ra khi xác nhận thanh toán', 'error');
        }
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
    
    return false;
}
</script>

{{-- Refund Modal --}}
<div id="refundModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Hoàn tiền trực tiếp</h3>
                <p class="text-sm text-gray-600 mt-1">Admin chủ động hoàn tiền cho khách hàng</p>
                <button onclick="document.getElementById('refundModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.orders.refund', $order) }}" id="refundForm">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Số tiền hoàn</label>
                    <input type="number" 
                           id="refundAmount"
                           name="refund_amount" 
                           max="{{ number_format($order->grand_total, 0, '.', '') }}"
                           value="{{ number_format($order->grand_total, 0, '.', '') }}"
                           step="1000"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <p class="mt-1 text-xs text-gray-500">Tổng đơn hàng: {{ number_format($order->grand_total, 0, ',', '.') }} VNĐ</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lý do hoàn tiền</label>
                    <textarea id="refundReason"
                              name="refund_reason" 
                              rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                              placeholder="Nhập lý do hoàn tiền..."></textarea>
                </div>

                <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 mb-4">
                    <p class="text-sm text-orange-800">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Lưu ý:</strong> Hành động này sẽ thay đổi trạng thái thanh toán thành "Đã hoàn tiền" và gửi thông báo đến khách hàng.
                    </p>
                </div>

                <div class="flex gap-3">
                    <button type="button" 
                            onclick="document.getElementById('refundModal').classList.add('hidden')"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Hủy
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition">
                        <i class="fas fa-check mr-2"></i>
                        Xác nhận
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Refund Form Validation
document.getElementById('refundForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const amountInput = document.getElementById('refundAmount');
    const reasonInput = document.getElementById('refundReason');
    const maxAmount = parseFloat('{{ number_format($order->grand_total, 0, ".", "") }}');
    
    const amount = parseFloat(amountInput.value);
    const reason = reasonInput.value.trim();
    
    // Validate amount
    if (!amountInput.value || isNaN(amount) || amount <= 0) {
        window.showMessage('Vui lòng nhập số tiền hoàn hợp lệ', 'error');
        amountInput.focus();
        return;
    }
    
    if (amount > maxAmount) {
        window.showMessage('Số tiền hoàn không được vượt quá tổng đơn hàng (' + new Intl.NumberFormat('vi-VN').format(maxAmount) + ' VNĐ)', 'error');
        amountInput.focus();
        return;
    }
    
    // Validate reason
    if (!reason) {
        window.showMessage('Vui lòng nhập lý do hoàn tiền', 'error');
        reasonInput.focus();
        return;
    }
    
    if (reason.length < 10) {
        window.showMessage('Lý do hoàn tiền phải có ít nhất 10 ký tự', 'error');
        reasonInput.focus();
        return;
    }
    
    // All validation passed - submit form
    this.submit();
});

// Down Payment Modal Functions
function openDownPaymentModal() {
    document.getElementById('downPaymentModal').classList.remove('hidden');
}

function closeDownPaymentModal() {
    document.getElementById('downPaymentModal').classList.add('hidden');
    
    // Reset form and button state
    const form = document.getElementById('downPaymentForm');
    const submitBtn = document.getElementById('downPaymentSubmitBtn');
    const btnText = document.getElementById('downPaymentBtnText');
    const btnSpinner = document.getElementById('downPaymentBtnSpinner');
    
    if (form) form.reset();
    if (submitBtn) submitBtn.disabled = false;
    if (btnText) btnText.classList.remove('hidden');
    if (btnSpinner) btnSpinner.classList.add('hidden');
}

// Handle down payment form submission
function handleDownPaymentSubmit(event) {
    const submitBtn = document.getElementById('downPaymentSubmitBtn');
    const btnText = document.getElementById('downPaymentBtnText');
    const btnSpinner = document.getElementById('downPaymentBtnSpinner');
    
    // Show loading state
    submitBtn.disabled = true;
    btnText.classList.add('hidden');
    btnSpinner.classList.remove('hidden');
    
    // Allow form to submit normally
    return true;
}

// Close down payment modal when clicking outside
@if($order->finance_option_id && $order->down_payment_amount && $order->payment_status === 'pending')
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('downPaymentModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDownPaymentModal();
            }
        });
    }
});
@endif
</script>

{{-- Down Payment Confirmation Modal --}}
@if($order->finance_option_id && $order->down_payment_amount && $order->payment_status === 'pending')
<div id="downPaymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-credit-card text-green-600 mr-2"></i>
                    Xác nhận tiền cọc
                </h3>
                
                <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <strong>Số tiền cọc:</strong> {{ number_format($order->down_payment_amount) }} VNĐ
                    </p>
                    <p class="text-sm text-blue-600 mt-1">
                        Sau khi xác nhận tiền cọc, đơn hàng có thể được giao hàng.
                    </p>
                    <p class="text-xs text-amber-600 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Chỉ chấp nhận phương thức thanh toán cần xác minh thủ công (tiền mặt/chuyển khoản) cho tiền cọc trả góp.
                    </p>
                </div>

                <form id="downPaymentForm" method="POST" action="{{ route('admin.orders.confirm-down-payment', $order->id) }}" onsubmit="handleDownPaymentSubmit(event)">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Phương thức thanh toán <span class="text-red-500">*</span>
                            </label>
                            <select name="payment_method_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Chọn phương thức thanh toán</option>
                                @foreach($manualPaymentMethods as $method)
                                    <option value="{{ $method->id }}">{{ $method->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Ngày thanh toán <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="payment_date" required value="{{ date('Y-m-d') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Ghi chú
                            </label>
                            <textarea name="notes" rows="3" placeholder="Ghi chú về việc thanh toán tiền cọc..."
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="closeDownPaymentModal()" 
                                class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Hủy
                        </button>
                        <button type="submit" id="downPaymentSubmitBtn"
                                class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                            <span id="downPaymentBtnText">
                                <i class="fas fa-check mr-2"></i>
                                Xác nhận tiền cọc
                            </span>
                            <span id="downPaymentBtnSpinner" class="hidden">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Đang xử lý...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Cancel Order Modal --}}
<div id="cancelOrderModal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-200">
        <div class="p-6">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Xác nhận hủy đơn hàng</h3>
            <p class="text-gray-600 text-center mb-4">
                Bạn có chắc chắn muốn hủy đơn hàng <strong>#{{ $order->order_number ?? $order->id }}</strong>?
            </p>
            <p class="text-sm text-gray-500 text-center mb-6">
                Giá trị: <strong>{{ number_format($order->grand_total, 0, ',', '.') }} VNĐ</strong>
                @if($order->finance_option_id)
                <br>Đơn hàng trả góp sẽ hủy tất cả kỳ chưa thanh toán.
                @endif
            </p>
            
            
            @if($order->status === 'shipping')
            <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                <div class="flex items-start">
                    <input type="checkbox" id="forceCancel" class="mt-0.5 mr-2">
                    <label for="forceCancel" class="text-sm text-amber-800">
                        <strong>Xác nhận:</strong> Tôi đã liên hệ đơn vị vận chuyển để dừng giao hàng
                    </label>
                </div>
            </div>
            @endif
            
            <div class="flex space-x-3">
                <button type="button" onclick="closeCancelOrderModal()" 
                        class="flex-1 px-4 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200">
                    Hủy bỏ
                </button>
                <button type="button" onclick="confirmCancelOrder()" id="confirmCancelBtn"
                        class="flex-1 px-4 py-2.5 text-white bg-red-600 hover:bg-red-700 rounded-lg font-medium transition-colors duration-200">
                    Xác nhận hủy
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Cancel Order Modal Functions
function showCancelOrderModal() {
    document.getElementById('cancelOrderModal').classList.remove('hidden');
}

function closeCancelOrderModal() {
    document.getElementById('cancelOrderModal').classList.add('hidden');
    @if($order->status === 'shipping')
    document.getElementById('forceCancel').checked = false;
    @endif
}

function confirmCancelOrder() {
    const confirmBtn = document.getElementById('confirmCancelBtn');
    
    @if($order->status === 'shipping')
    const forceCancel = document.getElementById('forceCancel').checked;
    if (!forceCancel) {
        alert('Vui lòng xác nhận đã liên hệ đơn vị vận chuyển');
        return;
    }
    @endif
    
    // Show loading state
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang hủy...';
    
    // Set default reason and submit form
    document.getElementById('cancelReason').value = 'Admin hủy đơn hàng';
    @if($order->status === 'shipping')
    // Add force_cancel field for shipping orders
    const forceCancelInput = document.createElement('input');
    forceCancelInput.type = 'hidden';
    forceCancelInput.name = 'force_cancel';
    forceCancelInput.value = '1';
    document.getElementById('cancelOrderForm').appendChild(forceCancelInput);
    @endif
    
    document.getElementById('cancelOrderForm').submit();
}

// Close modal when clicking outside
document.getElementById('cancelOrderModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCancelOrderModal();
    }
});
</script>

@endsection
