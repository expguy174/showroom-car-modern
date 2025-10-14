<div class="space-y-4">
    @if($logs->isEmpty())
        <p class="text-gray-500 text-center py-8">Chưa có lịch sử hoạt động</p>
    @else
        @foreach($logs as $log)
        <div class="flex gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <div class="flex-shrink-0">
                @if($log->action == 'order_created')
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-plus text-green-600 text-sm"></i>
                    </div>
                @elseif($log->action == 'status_changed')
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-arrow-right text-blue-600 text-sm"></i>
                    </div>
                @elseif($log->action == 'order_cancelled')
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-times text-red-600 text-sm"></i>
                    </div>
                @elseif($log->action == 'payment_pending')
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 text-sm"></i>
                    </div>
                @elseif($log->action == 'payment_status_changed')
                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-credit-card text-emerald-600 text-sm"></i>
                    </div>
                @elseif($log->action == 'payment_completed')
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-green-600 text-sm"></i>
                    </div>
                @elseif($log->action == 'payment_failed')
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation text-yellow-600 text-sm"></i>
                    </div>
                @elseif($log->action == 'tracking_updated')
                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-shipping-fast text-indigo-600 text-sm"></i>
                    </div>
                @elseif($log->action == 'note_updated')
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-edit text-purple-600 text-sm"></i>
                    </div>
                @elseif($log->action == 'payment_refunded')
                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-undo text-orange-600 text-sm"></i>
                    </div>
                @elseif($log->action == 'installments_created')
                    <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-teal-600 text-sm"></i>
                    </div>
                @elseif($log->action == 'installment_paid')
                    <div class="w-8 h-8 bg-cyan-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-money-check-alt text-cyan-600 text-sm"></i>
                    </div>
                @elseif($log->action == 'installment_completed')
                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-trophy text-emerald-600 text-sm"></i>
                    </div>
                @else
                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-info text-gray-600 text-sm"></i>
                    </div>
                @endif
            </div>
            
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1">
                    <h4 class="text-sm font-medium text-gray-900">
                        @if($log->action == 'order_created')
                            Tạo đơn hàng
                        @elseif($log->action == 'order_updated')
                            Cập nhật đơn hàng
                        @elseif($log->action == 'status_changed')
                            Chuyển trạng thái đơn hàng
                        @elseif($log->action == 'order_cancelled')
                            Hủy đơn hàng
                        @elseif($log->action == 'payment_pending')
                            Chờ thanh toán
                        @elseif($log->action == 'payment_status_changed')
                            Cập nhật trạng thái thanh toán
                        @elseif($log->action == 'payment_completed')
                            Thanh toán thành công
                        @elseif($log->action == 'payment_failed')
                            Thanh toán thất bại
                        @elseif($log->action == 'tracking_updated')
                            Cập nhật mã vận đơn
                        @elseif($log->action == 'note_updated')
                            Cập nhật ghi chú
                        @elseif($log->action == 'payment_refunded')
                            Hoàn tiền
                        @elseif($log->action == 'installments_created')
                            Tạo lịch trả góp
                        @elseif($log->action == 'installment_paid')
                            Thanh toán kỳ trả góp
                        @elseif($log->action == 'installment_completed')
                            Hoàn thành trả góp
                        @else
                            {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                        @endif
                    </h4>
                    <span class="text-xs text-gray-500">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                </div>
                
                @if($log->message)
                <p class="text-sm text-gray-600">{{ $log->message }}</p>
                @endif
                
                @if($log->details && is_array($log->details))
                <div class="text-xs text-gray-600 mt-2">
                    @if(isset($log->details['from']) && isset($log->details['to']))
                        @php
                            // Translations for both order status and payment status
                            $orderStatusTranslations = [
                                'pending' => 'Chờ xử lý',
                                'confirmed' => 'Đã xác nhận',
                                'shipping' => 'Đang giao',
                                'delivered' => 'Đã giao',
                                'cancelled' => 'Đã hủy'
                            ];
                            $paymentStatusTranslations = [
                                'pending' => 'Chờ thanh toán',
                                'completed' => 'Đã thanh toán',
                                'partial' => 'Thanh toán một phần',
                                'failed' => 'Thất bại',
                                'refunded' => 'Đã hoàn tiền'
                            ];
                            
                            // Use payment translations if this is a payment-related action
                            $translations = in_array($log->action, ['payment_status_changed', 'payment_refunded'])
                                ? $paymentStatusTranslations 
                                : $orderStatusTranslations;
                            
                            $fromText = $translations[$log->details['from']] ?? $log->details['from'];
                            $toText = $translations[$log->details['to']] ?? $log->details['to'];
                        @endphp
                        <span class="text-gray-500">Từ:</span> <span class="font-medium">{{ $fromText }}</span>
                        <i class="fas fa-arrow-right mx-2 text-gray-400"></i>
                        <span class="text-gray-500">Đến:</span> <span class="font-medium">{{ $toText }}</span>
                        
                        @if($log->action === 'payment_status_changed' && isset($log->details['paid_at']))
                        <div class="mt-1 text-gray-500">
                            <i class="fas fa-calendar-check mr-1"></i>
                            Thanh toán lúc: {{ \Carbon\Carbon::parse($log->details['paid_at'])->format('d/m/Y H:i') }}
                        </div>
                        @endif
                    @elseif(isset($log->details['status']))
                        @php
                            $statusTranslations = [
                                'pending' => 'Chờ xử lý',
                                'confirmed' => 'Đã xác nhận',
                                'shipping' => 'Đang giao',
                                'delivered' => 'Đã giao',
                                'cancelled' => 'Đã hủy'
                            ];
                            $statusText = $statusTranslations[$log->details['status']] ?? $log->details['status'];
                        @endphp
                        <span class="text-gray-500">Trạng thái:</span> <span class="font-medium">{{ $statusText }}</span>
                    @endif
                    
                    @if(isset($log->details['reason']))
                    <div class="mt-2 p-2 bg-amber-50 border border-amber-200 rounded">
                        <span class="text-gray-700 font-medium">Lý do:</span>
                        <p class="text-gray-600 mt-1">{{ $log->details['reason'] }}</p>
                    </div>
                    @endif
                    
                    @if($log->action === 'payment_refunded' && isset($log->details['refund_amount']))
                    <div class="mt-2 p-2 bg-orange-50 border border-orange-200 rounded">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-medium">Số tiền hoàn:</span>
                            <span class="text-orange-600 font-bold">{{ number_format($log->details['refund_amount'], 0, ',', '.') }} VNĐ</span>
                        </div>
                        @if(isset($log->details['refund_type']))
                        <div class="mt-1 text-xs">
                            <span class="text-gray-600">Loại:</span>
                            <span class="font-medium">{{ $log->details['refund_type'] === 'full' ? 'Hoàn toàn bộ' : 'Hoàn một phần' }}</span>
                        </div>
                        @endif
                    </div>
                    @endif
                    
                    @if($log->action === 'installments_created' && isset($log->details['total_installments']))
                    <div class="mt-2 p-3 bg-teal-50 border border-teal-200 rounded">
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-1">
                                <span class="text-gray-600">Số kỳ:</span>
                                <span class="font-medium">{{ $log->details['total_installments'] }} tháng</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="text-gray-600">Hàng tháng:</span>
                                <span class="font-medium text-teal-600">{{ number_format($log->details['monthly_amount'], 0, ',', '.') }} VNĐ</span>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if(($log->action === 'installment_paid' || $log->action === 'installment_completed') && isset($log->details['installment_number']))
                    <div class="mt-2 p-3 bg-cyan-50 border border-cyan-200 rounded">
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    Kỳ {{ $log->details['installment_number'] }}
                                </span>
                                @if(isset($log->details['amount']))
                                <span class="font-medium text-cyan-700">{{ number_format($log->details['amount'], 0, ',', '.') }} VNĐ</span>
                                @endif
                            </div>
                            @if($log->action === 'installment_completed')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                <i class="fas fa-trophy mr-1"></i> Hoàn thành toàn bộ
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                @endif
                
                @php
                    $performedBy = 'Hệ thống';
                    
                    if ($log->user) {
                        $userName = $log->user->userProfile->name ?? $log->user->email ?? ('User #' . $log->user->id);
                        $performedBy = $userName;
                    } elseif ($log->user_id && $order->user && $log->user_id == $order->user_id) {
                        $userName = $order->user->userProfile->name ?? $order->user->email ?? ('User #' . $order->user->id);
                        $performedBy = $userName . ' (Khách hàng)';
                    } elseif ($order->user && in_array($log->action, ['order_created', 'payment_pending'])) {
                        $userName = $order->user->userProfile->name ?? $order->user->email ?? ('User #' . $order->user->id);
                        $performedBy = $userName . ' (Khách hàng)';
                    }
                @endphp
                <p class="text-xs text-gray-500 mt-2">
                    <i class="fas fa-user mr-1"></i>
                    Bởi: {{ $performedBy }}
                </p>
            </div>
        </div>
        @endforeach
    @endif
</div>


