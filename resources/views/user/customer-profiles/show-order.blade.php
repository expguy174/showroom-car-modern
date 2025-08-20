@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #' . ($order->order_number ?? $order->id))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-receipt text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Chi tiết đơn hàng</h1>
                        <p class="text-gray-600">Mã đơn: #{{ $order->order_number ?? $order->id }}</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('user.customer-profiles.orders') }}" class="flex items-center text-blue-600 hover:text-blue-700 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Quay về danh sách đơn hàng
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Timeline -->
    <div class="bg-white border-b border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="max-w-4xl mx-auto">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tiến trình đơn hàng</h3>
                <div class="relative">
                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                    @php
                        $statuses = [
                            ['key' => 'pending', 'name' => 'Chờ xử lý', 'icon' => 'clock', 'completed' => true],
                            ['key' => 'confirmed', 'name' => 'Đã xác nhận', 'icon' => 'check-circle', 'completed' => in_array($order->status, ['confirmed', 'shipped', 'delivered'])],
                            ['key' => 'shipped', 'name' => 'Đang vận chuyển', 'icon' => 'truck', 'completed' => in_array($order->status, ['shipped', 'delivered'])],
                            ['key' => 'delivered', 'name' => 'Đã giao hàng', 'icon' => 'home', 'completed' => $order->status === 'delivered']
                        ];
                        
                        if ($order->status === 'cancelled') {
                            $statuses = [
                                ['key' => 'pending', 'name' => 'Chờ xử lý', 'icon' => 'clock', 'completed' => true],
                                ['key' => 'cancelled', 'name' => 'Đã hủy', 'icon' => 'times-circle', 'completed' => true, 'color' => 'red']
                            ];
                        }
                    @endphp
                    <div class="space-y-4">
                        @foreach($statuses as $status)
                            <div class="relative flex items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $status['completed'] ? 'bg-' . ($status['color'] ?? 'emerald') . '-100 text-' . ($status['color'] ?? 'emerald') . '-600' : 'bg-gray-100 text-gray-400' }}">
                                    <i class="fas fa-{{ $status['icon'] }} text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium {{ $status['completed'] ? 'text-gray-900' : 'text-gray-500' }}">{{ $status['name'] }}</div>
                                    @if($status['key'] === $order->status)
                                        <div class="text-xs text-gray-500">{{ $order->updated_at->format('d/m/Y H:i') }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-6xl mx-auto space-y-6">
            <!-- Order Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Order Info -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-600"></i>
                            Thông tin đơn hàng
                        </h3>
                    </div>
                    <div class="px-6 py-4 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Mã đơn hàng</span>
                            <span class="font-semibold">#{{ $order->order_number ?? $order->id }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Ngày đặt</span>
                            <span class="font-semibold">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Cập nhật cuối</span>
                            <span class="font-semibold">{{ $order->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($order->paymentMethod)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Phương thức thanh toán</span>
                                <span class="font-semibold">{{ $order->paymentMethod->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status Info -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-clipboard-check text-emerald-600"></i>
                            Trạng thái
                        </h3>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        @php
                            $statusColors = [
                                'pending' => 'amber',
                                'confirmed' => 'blue',
                                'shipped' => 'indigo',
                                'delivered' => 'emerald',
                                'cancelled' => 'red'
                            ];
                            $paymentColors = [
                                'pending' => 'amber',
                                'paid' => 'emerald',
                                'failed' => 'red'
                            ];
                            $statusColor = $statusColors[$order->status] ?? 'gray';
                            $paymentColor = $paymentColors[$order->payment_status] ?? 'gray';
                        @endphp
                        <div>
                            <div class="text-sm text-gray-600 mb-1">Trạng thái đơn hàng</div>
                            <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-{{ $statusColor }}-50 text-{{ $statusColor }}-700">
                                <i class="fas fa-box mr-2"></i>
                                {{ $order->status_display ?? 'Chưa xác định' }}
                            </span>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600 mb-1">Trạng thái thanh toán</div>
                            <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-{{ $paymentColor }}-50 text-{{ $paymentColor }}-700">
                                <i class="fas fa-credit-card mr-2"></i>
                                {{ $order->payment_status_display ?? 'Chưa xác định' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-cogs text-purple-600"></i>
                            Thao tác
                        </h3>
                    </div>
                    <div class="px-6 py-4 space-y-3">
                        @if(in_array($order->status, ['pending', 'confirmed']))
                            <button class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                                <i class="fas fa-times"></i>
                                Hủy đơn hàng
                            </button>
                        @endif
                        <button class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                            <i class="fas fa-download"></i>
                            Tải hóa đơn
                        </button>
                        <button class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                            <i class="fas fa-print"></i>
                            In đơn hàng
                        </button>
                        <button class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                            <i class="fas fa-headset"></i>
                            Hỗ trợ
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Order Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-shopping-bag text-blue-600"></i>
                                Sản phẩm đã đặt ({{ $order->items->count() }} sản phẩm)
                            </h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach($order->items as $item)
                                @php
                                    $model = $item->item;
                                    $unit = $item->price;
                                    $line = $item->line_total ?: ($unit * $item->quantity);
                                    $img = null;
                                    if ($item->item_type === 'car_variant' && $model?->images?->isNotEmpty()) {
                                        $f = $model->images->first();
                                        $img = $f->image_url ?: ($f->image_path ? asset('storage/'.$f->image_path) : null);
                                    } elseif ($item->item_type === 'accessory') {
                                        $img = $model?->image_url ? (filter_var($model->image_url, FILTER_VALIDATE_URL) ? $model->image_url : asset('storage/'.$model->image_url)) : null;
                                    }
                                @endphp
                                <div class="px-6 py-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div class="w-20 h-16 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0 shadow-sm">
                                            @if($img)
                                                <img src="{{ $img }}" class="w-full h-full object-cover" alt="{{ $model?->name ?? $item->item_name }}" />
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <i class="fas fa-image text-xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="font-semibold text-gray-900 text-base mb-1">
                                                {{ $model?->name ?? $item->item_name }}
                                            </div>
                                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                                <div class="flex items-center gap-1">
                                                    <i class="fas fa-hashtag text-xs"></i>
                                                    <span>Số lượng: {{ $item->quantity }}</span>
                                                </div>
                                                @if($item->color)
                                                    <div class="flex items-center gap-1">
                                                        <i class="fas fa-palette text-xs"></i>
                                                        <span>Màu: {{ $item->color->color_name }}</span>
                                                    </div>
                                                @endif
                                                <div class="flex items-center gap-1">
                                                    <i class="fas fa-tag text-xs"></i>
                                                    <span>{{ $item->item_type === 'car_variant' ? 'Xe hơi' : 'Phụ kiện' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm text-gray-500 mb-1">Đơn giá</div>
                                            <div class="font-semibold text-gray-900">{{ number_format($unit) }} đ</div>
                                            <div class="text-xs text-gray-500 mt-1">x{{ $item->quantity }}</div>
                                        </div>
                                        <div class="text-right pl-4">
                                            <div class="text-sm text-gray-500 mb-1">Thành tiền</div>
                                            <div class="text-lg font-bold text-indigo-600">{{ number_format($line) }} đ</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Customer Info -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-user text-emerald-600"></i>
                                Thông tin khách hàng
                            </h3>
                        </div>
                        <div class="px-6 py-4 space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Họ và tên</div>
                                    <div class="font-semibold text-gray-900">{{ $order->name }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Số điện thoại</div>
                                    <div class="font-semibold text-gray-900">{{ $order->phone }}</div>
                                </div>
                            </div>
                            @if($order->email)
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Email</div>
                                    <div class="font-semibold text-gray-900">{{ $order->email }}</div>
                                </div>
                            </div>
                            @endif
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center mt-1">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Địa chỉ giao hàng</div>
                                    <div class="font-semibold text-gray-900">{{ $order->shippingAddress->line1 ?? $order->address ?? 'Không có thông tin' }}</div>
                                    @if($order->shippingAddress && ($order->shippingAddress->district || $order->shippingAddress->province))
                                        <div class="text-sm text-gray-600 mt-1">
                                            {{ $order->shippingAddress->district ? $order->shippingAddress->district . ', ' : '' }}{{ $order->shippingAddress->province }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-calculator text-blue-600"></i>
                                Tổng kết thanh toán
                            </h3>
                        </div>
                        <div class="px-6 py-4 space-y-4">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 flex items-center gap-2">
                                        <i class="fas fa-shopping-bag text-sm text-gray-400"></i>
                                        Tạm tính
                                    </span>
                                    <span class="font-semibold">{{ number_format($order->subtotal ?? 0) }} đ</span>
                                </div>
                                @if(($order->discount_total ?? 0) > 0)
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 flex items-center gap-2">
                                        <i class="fas fa-percentage text-sm text-green-500"></i>
                                        Giảm giá
                                    </span>
                                    <span class="font-semibold text-green-600">-{{ number_format($order->discount_total) }} đ</span>
                                </div>
                                @endif
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 flex items-center gap-2">
                                        <i class="fas fa-receipt text-sm text-gray-400"></i>
                                        Thuế
                                    </span>
                                    <span class="font-semibold">{{ number_format($order->tax_total ?? 0) }} đ</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 flex items-center gap-2">
                                        <i class="fas fa-truck text-sm text-gray-400"></i>
                                        Phí vận chuyển
                                    </span>
                                    <span class="font-semibold">{{ number_format($order->shipping_fee ?? 0) }} đ</span>
                                </div>
                            </div>
                            <div class="border-t pt-4">
                                <div class="flex items-center justify-between text-lg">
                                    <span class="font-bold text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-coins text-yellow-500"></i>
                                        Tổng cộng
                                    </span>
                                    <span class="font-bold text-indigo-600 text-xl">{{ number_format($order->grand_total ?? $order->total_price) }} đ</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-rocket text-purple-600"></i>
                                Thao tác nhanh
                            </h3>
                        </div>
                        <div class="px-6 py-4 space-y-3">
                            <a href="{{ route('home') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                                <i class="fas fa-home"></i>
                                Về trang chủ
                            </a>
                            <a href="{{ route('user.customer-profiles.orders') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 font-medium transition-colors">
                                <i class="fas fa-list"></i>
                                Xem tất cả đơn hàng
                            </a>
                            <a href="{{ route('products.index') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 font-medium transition-colors">
                                <i class="fas fa-car"></i>
                                Tiếp tục mua sắm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
