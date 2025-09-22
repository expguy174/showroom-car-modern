@extends('layouts.admin')

@section('title', 'Danh sách đơn hàng')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    {{-- Header --}}
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-shopping-cart text-blue-600 mr-3"></i>
                    Danh sách đơn hàng
                </h1>
                <p class="text-sm text-gray-600 mt-1">Quản lý tất cả đơn hàng trong hệ thống</p>
            </div>
            <div class="flex items-center gap-3">
                <button class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-download mr-2"></i>
                    Xuất Excel
                </button>
            </div>
        </div>
    </div>

    {{-- Search & Filters --}}
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Tìm kiếm đơn hàng, khách hàng...">
                </div>
            </div>
            <div>
                <select name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" @selected(request('status')==='pending')>Chờ xử lý</option>
                    <option value="confirmed" @selected(request('status')==='confirmed')>Đã xác nhận</option>
                    <option value="shipping" @selected(request('status')==='shipping')>Đang giao</option>
                    <option value="delivered" @selected(request('status')==='delivered')>Đã giao</option>
                    <option value="cancelled" @selected(request('status')==='cancelled')>Đã hủy</option>
                </select>
            </div>
            <div>
                <select name="payment_status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tất cả thanh toán</option>
                    <option value="pending" @selected(request('payment_status')==='pending')>Chờ thanh toán</option>
                    <option value="processing" @selected(request('payment_status')==='processing')>Đang xử lý</option>
                    <option value="completed" @selected(request('payment_status')==='completed')>Đã thanh toán</option>
                    <option value="failed" @selected(request('payment_status')==='failed')>Thất bại</option>
                    <option value="cancelled" @selected(request('payment_status')==='cancelled')>Đã hủy</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-filter mr-2"></i>
                    Lọc
                </button>
                @if(request('search') || request('status') || request('payment_status'))
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-3 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-times"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Stats Cards --}}
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-shopping-cart text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tổng đơn hàng</p>
                        <p class="text-xl font-semibold text-gray-900">{{ $orders->total() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Chờ xử lý</p>
                        <p class="text-xl font-semibold text-gray-900">{{ $orders->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-truck text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Đang giao</p>
                        <p class="text-xl font-semibold text-gray-900">{{ $orders->where('status', 'shipping')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Hoàn thành</p>
                        <p class="text-xl font-semibold text-gray-900">{{ $orders->where('status', 'delivered')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-dollar-sign text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Doanh thu</p>
                        <p class="text-xl font-semibold text-gray-900">{{ number_format($orders->where('status', 'delivered')->sum('grand_total')) }}đ</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá trị</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thanh toán</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                    {{ substr($order->order_number ?? $order->id, -2) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">#{{ $order->order_number ?? $order->id }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->items->count() }} sản phẩm</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <div class="font-medium">{{ optional($order->user->userProfile)->name ?? 'Khách vãng lai' }}</div>
                                <div class="text-gray-500">{{ optional($order->user)->email ?? '-' }}</div>
                                @if(optional($order->user->userProfile)->phone)
                                    <div class="text-gray-500">
                                        <i class="fas fa-phone text-gray-400 mr-1"></i>
                                        {{ $order->user->userProfile->phone }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <div class="font-semibold text-lg">{{ number_format($order->grand_total ?? $order->total_price, 0, ',', '.') }}đ</div>
                                @if($order->discount_total > 0)
                                    <div class="text-green-600 text-xs">
                                        <i class="fas fa-tag mr-1"></i>
                                        Giảm {{ number_format($order->discount_total) }}đ
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusConfig = [
                                    'pending' => ['label' => 'Chờ xử lý', 'color' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fas fa-clock'],
                                    'confirmed' => ['label' => 'Đã xác nhận', 'color' => 'bg-blue-100 text-blue-800', 'icon' => 'fas fa-check'],
                                    'shipping' => ['label' => 'Đang giao', 'color' => 'bg-purple-100 text-purple-800', 'icon' => 'fas fa-truck'],
                                    'delivered' => ['label' => 'Đã giao', 'color' => 'bg-green-100 text-green-800', 'icon' => 'fas fa-check-circle'],
                                    'cancelled' => ['label' => 'Đã hủy', 'color' => 'bg-red-100 text-red-800', 'icon' => 'fas fa-times-circle']
                                ];
                                $config = $statusConfig[$order->status] ?? $statusConfig['pending'];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $config['color'] }}">
                                <i class="{{ $config['icon'] }} mr-1"></i>
                                {{ $config['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $paymentConfig = [
                                    'pending' => ['label' => 'Chờ thanh toán', 'color' => 'bg-gray-100 text-gray-800', 'icon' => 'fas fa-clock'],
                                    'processing' => ['label' => 'Đang xử lý', 'color' => 'bg-blue-100 text-blue-800', 'icon' => 'fas fa-spinner'],
                                    'completed' => ['label' => 'Đã thanh toán', 'color' => 'bg-green-100 text-green-800', 'icon' => 'fas fa-check-circle'],
                                    'failed' => ['label' => 'Thất bại', 'color' => 'bg-red-100 text-red-800', 'icon' => 'fas fa-exclamation-circle'],
                                    'cancelled' => ['label' => 'Đã hủy', 'color' => 'bg-gray-100 text-gray-800', 'icon' => 'fas fa-ban']
                                ];
                                $payConfig = $paymentConfig[$order->payment_status] ?? $paymentConfig['pending'];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $payConfig['color'] }}">
                                <i class="{{ $payConfig['icon'] }} mr-1"></i>
                                {{ $payConfig['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>{{ $order->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs">{{ $order->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 transition-colors p-1 rounded hover:bg-blue-50" 
                                   title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.orders.edit', $order->id) }}" 
                                   class="text-green-600 hover:text-green-900 transition-colors p-1 rounded hover:bg-green-50" 
                                   title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.orders.logs', $order->id) }}" 
                                   class="text-purple-600 hover:text-purple-900 transition-colors p-1 rounded hover:bg-purple-50" 
                                   title="Lịch sử">
                                    <i class="fas fa-history"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-shopping-cart text-gray-300 text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Không có đơn hàng nào</h3>
                                <p class="text-gray-500">Hệ thống chưa có đơn hàng nào được tạo.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        <x-admin.simple-pagination :paginator="$orders" />
    </div>
    @endif
</div>
@endsection