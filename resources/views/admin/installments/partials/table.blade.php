<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gói vay</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tiến độ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Còn nợ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                    @php
                        $totalInstallments = $order->installments->count();
                        $paidInstallments = $order->installments->where('status', 'paid')->count();
                        $unpaidAmount = $order->installments->whereIn('status', ['pending', 'overdue'])->sum('amount');
                        $hasOverdue = $order->installments->where('status', 'overdue')->count() > 0;
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors {{ $hasOverdue ? 'bg-red-50' : '' }}">
                        {{-- Order --}}
                        <td class="px-6 py-3">
                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                               class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                #{{ $order->order_number }}
                            </a>
                            @if($order->items->isNotEmpty())
                                <div class="text-xs text-gray-600 mt-1">
                                    <i class="fas fa-car text-gray-400"></i> {{ $order->items->first()->item_name }}
                                    @if($order->items->count() > 1)
                                        <span class="text-gray-400">+{{ $order->items->count() - 1 }}</span>
                                    @endif
                                </div>
                            @endif
                            <div class="text-xs font-semibold text-gray-700 mt-1">
                                {{ number_format($order->grand_total ?? $order->total_price, 0) }} đ
                            </div>
                        </td>
                        
                        {{-- Customer --}}
                        <td class="px-6 py-3">
                            @if($order->user)
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $order->user->userProfile->name ?? $order->user->email }}
                                </div>
                                @if($order->user->userProfile && $order->user->userProfile->phone)
                                <div class="text-xs text-gray-500">{{ $order->user->userProfile->phone }}</div>
                                @endif
                            @else
                                <span class="text-sm text-gray-400">N/A</span>
                            @endif
                        </td>
                        
                        {{-- Finance Option --}}
                        <td class="px-6 py-3">
                            @if($order->financeOption)
                                <div class="text-sm font-medium text-gray-900">{{ $order->financeOption->name }}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-university text-gray-400"></i> {{ $order->financeOption->bank_name }}
                                </div>
                                @if($order->tenure_months)
                                <div class="text-xs text-gray-600 mt-1">
                                    <i class="fas fa-calendar-alt text-gray-400"></i> {{ $order->tenure_months }} tháng • {{ $order->financeOption->interest_rate }}%
                                </div>
                                @endif
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        
                        {{-- Progress --}}
                        <td class="px-6 py-3 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-sm font-semibold {{ $paidInstallments === $totalInstallments ? 'text-green-600' : 'text-gray-900' }}">
                                    {{ $paidInstallments }}/{{ $totalInstallments }}
                                </span>
                                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1 max-w-[60px]">
                                    <div class="bg-green-600 h-1.5 rounded-full" style="width: {{ $totalInstallments > 0 ? ($paidInstallments / $totalInstallments * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </td>
                        
                        {{-- Unpaid Amount --}}
                        <td class="px-6 py-3 text-right">
                            <span class="text-sm font-semibold {{ $unpaidAmount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ number_format($unpaidAmount, 0) }} đ
                            </span>
                            @if($hasOverdue)
                            <div class="text-xs text-red-600 mt-1">
                                <i class="fas fa-exclamation-triangle"></i> Có quá hạn
                            </div>
                            @endif
                        </td>
                        
                        {{-- Created At --}}
                        <td class="px-6 py-3">
                            <div class="text-sm text-gray-900">{{ $order->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                        </td>
                        
                        {{-- Actions --}}
                        <td class="px-6 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.installments.show', $order->id) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors"
                                   title="Xem chi tiết lịch trả góp">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                                <p class="text-gray-500 text-lg font-medium">Không có đơn hàng trả góp nào</p>
                                <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc để xem kết quả khác</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        <x-admin.pagination :paginator="$orders" />
    </div>
    @endif
</div>
