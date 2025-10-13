{{-- Table Content Only (for AJAX) --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá trị</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Thanh toán</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
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
                        <td class="px-6 py-4 whitespace-nowrap text-left">
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
                        <td class="px-6 py-4 whitespace-nowrap text-center">
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
                        <td class="px-6 py-4 whitespace-nowrap text-center">
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 transition-colors p-1 rounded hover:bg-blue-50" 
                                   title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                {{-- Edit and Logs buttons removed - all functionality now on show page --}}
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
        <x-admin.pagination :paginator="$orders" />
    </div>
    @endif
</div>
