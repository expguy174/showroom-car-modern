<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 15%;">Yêu cầu hoàn tiền</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 15%;">Khách hàng</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 10%;">Số tiền</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 20%;">Lý do</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 10%;">Trạng thái</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 10%;">Ngày tạo</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 10%;">Ngày xử lý</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 10%;">Thao tác</th>
            </tr>
        </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($refunds as $refund)
                <tr class="hover:bg-gray-50" data-refund-id="{{ $refund->id }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                Refund #{{ $refund->id }}
                            </div>
                            <div class="text-sm text-gray-500">
                                @if($refund->paymentTransaction && $refund->paymentTransaction->order)
                                    Đơn hàng: {{ $refund->paymentTransaction->order->order_number ?? '#' . $refund->paymentTransaction->order_id }}
                                @else
                                    Đơn hàng: #{{ $refund->paymentTransaction->order_id ?? 'N/A' }}
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            {{ $refund->paymentTransaction->user->userProfile->name ?? $refund->paymentTransaction->user->email ?? 'N/A' }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $refund->paymentTransaction->user->email ?? '' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ number_format($refund->amount, 0, ',', '.') }} VNĐ
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 max-w-xs">
                            {{ $refund->reason ? Str::limit($refund->reason, 100) : 'Không có lý do' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center status-badge">
                        @if($refund->status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>
                                Chờ xử lý
                            </span>
                        @elseif($refund->status === 'processing')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-spinner mr-1"></i>
                                Đang xử lý
                            </span>
                        @elseif($refund->status === 'refunded')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i>
                                Đã hoàn tiền
                            </span>
                        @elseif($refund->status === 'failed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times mr-1"></i>
                                Thất bại
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $refund->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($refund->processed_at)
                            {{ $refund->processed_at->format('d/m/Y H:i') }}
                        @else
                            <span class="text-gray-400">Chưa xử lý</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        @if($refund->status === 'pending')
                            <!-- Pending: Workflow options -->
                            <div class="flex items-center justify-center gap-1">
                                <button onclick="updateRefundStatus({{ $refund->id }}, 'processing')" 
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition-colors"
                                        title="Bắt đầu xử lý yêu cầu hoàn tiền">
                                    <i class="fas fa-arrow-right mr-1"></i>
                                    Xử lý
                                </button>
                                
                                <!-- Quick actions cho cases đơn giản -->
                                <div class="relative group">
                                    <button class="inline-flex items-center px-1 py-1 text-xs text-gray-500 hover:text-gray-700 rounded"
                                            title="Thao tác nhanh">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="absolute right-0 top-full mt-1 w-32 bg-white rounded-md shadow-lg border border-gray-200 z-10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                        <button onclick="updateRefundStatus({{ $refund->id }}, 'refunded')" 
                                                class="block w-full text-left px-3 py-2 text-xs text-green-700 hover:bg-green-50">
                                            <i class="fas fa-check mr-1"></i>Hoàn tiền ngay
                                        </button>
                                        <button onclick="updateRefundStatus({{ $refund->id }}, 'failed')" 
                                                class="block w-full text-left px-3 py-2 text-xs text-red-700 hover:bg-red-50">
                                            <i class="fas fa-times mr-1"></i>Từ chối ngay
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @elseif($refund->status === 'processing')
                            <!-- Processing: Chọn Hoàn tiền hoặc Từ chối -->
                            <div class="flex items-center justify-center gap-1">
                                <button onclick="updateRefundStatus({{ $refund->id }}, 'refunded')" 
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-700 transition-colors"
                                        title="Chấp nhận và thực hiện hoàn tiền">
                                    <i class="fas fa-check mr-1"></i>
                                    Hoàn tiền
                                </button>
                                <button onclick="updateRefundStatus({{ $refund->id }}, 'failed')" 
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700 transition-colors"
                                        title="Từ chối yêu cầu hoàn tiền">
                                    <i class="fas fa-times mr-1"></i>
                                    Từ chối
                                </button>
                            </div>
                        @else
                            <!-- Completed: Chỉ hiển thị trạng thái -->
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($refund->status === 'refunded') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                @if($refund->status === 'refunded')
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Hoàn thành
                                @else
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Đã từ chối
                                @endif
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có yêu cầu hoàn tiền</h3>
                            <p class="text-gray-500">Các yêu cầu hoàn tiền từ khách hàng sẽ hiển thị ở đây.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($refunds->hasPages())
    <div class="bg-white px-4 py-3 border-t border-gray-200">
        <x-admin.pagination 
            :paginator="$refunds"
            :showInfo="true"
            :showJumper="true"
            containerClass="flex items-center justify-between" />
    </div>
    @endif
</div>
