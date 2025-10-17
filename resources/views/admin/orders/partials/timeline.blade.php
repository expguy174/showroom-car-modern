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
                @elseif($log->action == 'refund_status_updated')
                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exchange-alt text-indigo-600 text-sm"></i>
                    </div>
                @elseif($log->action == 'refund_request')
                    <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-hand-holding-usd text-amber-600 text-sm"></i>
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
                    <div class="w-8 h-8 bg-cyan-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-money-check-alt text-cyan-600 text-sm"></i>
                    </div>
                @elseif($log->action == 'down_payment_confirmed')
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-hand-holding-usd text-green-600 text-sm"></i>
                    </div>
                @elseif($log->action == 'user_cancel')
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-times text-red-600 text-sm"></i>
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
                        @elseif($log->action == 'refund_status_updated')
                            Cập nhật trạng thái hoàn tiền
                        @elseif($log->action == 'refund_request')
                            Yêu cầu hoàn tiền
                        @elseif($log->action == 'installments_created')
                            Tạo lịch trả góp
                        @elseif($log->action == 'installment_paid')
                            Thanh toán kỳ trả góp
                        @elseif($log->action == 'installment_completed')
                            Thanh toán kỳ cuối
                        @elseif($log->action == 'down_payment_confirmed')
                            Xác nhận tiền cọc
                        @elseif($log->action == 'user_cancel')
                            Khách hàng hủy đơn hàng
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
                                'refunded' => 'Đã hoàn tiền',
                                'cancelled' => 'Đã hủy'
                            ];
                            
                            // Use payment translations if this is a payment-related action
                            $translations = in_array($log->action, ['payment_status_changed', 'payment_refunded', 'payment_completed'])
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
                    
                    @if($log->action === 'refund_status_updated')
                    <div class="mt-2 p-3 bg-indigo-50 border border-indigo-200 rounded space-y-2">
                        <div class="flex items-center gap-2 text-xs">
                            <span class="text-gray-600">Trạng thái:</span>
                            <span class="text-gray-700">{{ $log->details['from_label'] ?? $log->details['from_status'] }}</span>
                            <i class="fas fa-arrow-right text-gray-400 text-xs"></i>
                            <span class="font-medium text-indigo-600">{{ $log->details['to_label'] ?? $log->details['to_status'] }}</span>
                        </div>
                        @if(isset($log->details['amount']))
                        <div class="flex items-center justify-between text-xs bg-white border border-indigo-200 rounded px-2 py-1.5">
                            <span class="text-indigo-700">Số tiền hoàn:</span>
                            <span class="font-bold text-indigo-600">{{ number_format($log->details['amount'], 0, ',', '.') }} VNĐ</span>
                        </div>
                        @endif
                        @if(isset($log->details['admin_notes']) && $log->details['admin_notes'])
                        <div class="text-xs">
                            <span class="text-gray-600">Ghi chú:</span>
                            <p class="text-gray-700 mt-1">{{ $log->details['admin_notes'] }}</p>
                        </div>
                        @endif
                    </div>
                    @endif
                    
                    {{-- Show additional details for order_cancelled --}}
                    @if($log->action === 'order_cancelled')
                    <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded space-y-2">
                        @php
                            $orderStatusLabels = [
                                'pending' => 'Chờ xử lý',
                                'confirmed' => 'Đã xác nhận',
                                'shipping' => 'Đang giao',
                                'delivered' => 'Đã giao',
                                'cancelled' => 'Đã hủy'
                            ];
                            $paymentStatusLabels = [
                                'pending' => 'Chờ thanh toán',
                                'partial' => 'Thanh toán một phần',
                                'completed' => 'Đã thanh toán',
                                'failed' => 'Thất bại',
                                'refunded' => 'Đã hoàn tiền',
                                'cancelled' => 'Đã hủy'
                            ];
                        @endphp
                        
                        @if(isset($log->details['order_status']))
                        <div class="flex items-center gap-2 text-xs">
                            <span class="text-gray-600">Trạng thái đơn:</span>
                            <span class="text-gray-700">{{ $orderStatusLabels[$log->details['order_status']['from']] ?? $log->details['order_status']['from'] }}</span>
                            <i class="fas fa-arrow-right text-gray-400 text-xs"></i>
                            <span class="font-medium text-red-600">{{ $orderStatusLabels[$log->details['order_status']['to']] ?? 'Đã hủy' }}</span>
                        </div>
                        @endif
                        
                        @if(isset($log->details['payment_status']))
                        <div class="flex items-center gap-2 text-xs">
                            <span class="text-gray-600">Thanh toán:</span>
                            <span class="text-gray-700">{{ $paymentStatusLabels[$log->details['payment_status']['from']] ?? $log->details['payment_status']['from'] }}</span>
                            <i class="fas fa-arrow-right text-gray-400 text-xs"></i>
                            <span class="font-medium {{ $log->details['payment_status']['to'] === 'refunded' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $paymentStatusLabels[$log->details['payment_status']['to']] ?? $log->details['payment_status']['to'] }}
                            </span>
                        </div>
                        @endif
                        
                        @if(isset($log->details['cancelled_installments']) && $log->details['cancelled_installments'] > 0)
                        <div class="flex items-center gap-2 text-xs">
                            <i class="fas fa-calendar-times text-red-500"></i>
                            <span class="text-gray-600">Đã hủy:</span>
                            <span class="font-medium text-red-700">{{ $log->details['cancelled_installments'] }} kỳ trả góp</span>
                        </div>
                        @endif
                        
                        @if(isset($log->details['refund_id']) && isset($log->details['refund_amount']))
                        <div class="flex items-center justify-between text-xs bg-amber-50 border border-amber-200 rounded px-2 py-1.5">
                            <span class="text-amber-700">Liên quan đến hoàn tiền:</span>
                            <span class="font-bold text-amber-600">{{ number_format($log->details['refund_amount'], 0, ',', '.') }} VNĐ</span>
                        </div>
                        @endif
                        
                        @if(isset($log->details['reason']) && $log->details['reason'])
                        <div class="text-xs">
                            <span class="text-gray-600">Lý do:</span>
                            <p class="text-gray-700 mt-1">
                                @if($log->details['reason'] === 'Auto-cancelled after refund completed')
                                    Tự động hủy sau khi hoàn tiền thành công
                                @else
                                    {{ $log->details['reason'] }}
                                @endif
                            </p>
                        </div>
                        @endif
                        
                        @if(isset($log->details['cancelled_by']))
                        <div class="text-xs text-gray-500 flex items-center gap-1">
                            <i class="fas fa-user-shield"></i>
                            <span>Bởi: {{ $log->details['cancelled_by'] }}</span>
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
                    
                    {{-- Show additional details for down_payment_confirmed --}}
                    @if($log->action === 'down_payment_confirmed' && isset($log->details['down_payment_amount']))
                    <div class="mt-2 p-3 bg-green-50 border border-green-200 rounded">
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-600">Số tiền cọc:</span>
                                <span class="font-medium text-green-700">{{ number_format($log->details['down_payment_amount'], 0, ',', '.') }} VNĐ</span>
                            </div>
                            @if(isset($log->details['payment_status_to']))
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $log->details['payment_status_to'] === 'partial' ? 'Thanh toán một phần' : $log->details['payment_status_to'] }}
                            </span>
                            @endif
                        </div>
                        @if(isset($log->details['payment_method_id']))
                        @php
                            $paymentMethod = \App\Models\PaymentMethod::find($log->details['payment_method_id']);
                            $paymentMethodName = $paymentMethod ? $paymentMethod->name : 'ID ' . $log->details['payment_method_id'];
                        @endphp
                        <div class="mt-1 text-xs">
                            <span class="text-gray-600">Phương thức:</span>
                            <span class="font-medium">{{ $paymentMethodName }}</span>
                        </div>
                        @endif
                        <div class="mt-2 text-xs text-green-700 bg-green-100 px-2 py-1 rounded">
                            <i class="fas fa-check-circle mr-1"></i>
                            Đơn hàng có thể được giao hàng
                        </div>
                    </div>
                    @endif
                    
                    {{-- Show additional details for order_created --}}
                    @if($log->action === 'order_created' && isset($log->details['order_number']))
                    <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded">
                        <div class="text-xs space-y-1">
                            @if(isset($log->details['order_number']))
                            <div><span class="text-gray-600">Số đơn:</span> <span class="font-medium">{{ $log->details['order_number'] }}</span></div>
                            @endif
                            @if(isset($log->details['grand_total']))
                            <div><span class="text-gray-600">Tổng tiền:</span> <span class="font-medium text-green-600">{{ number_format($log->details['grand_total'], 0, ',', '.') }} VNĐ</span></div>
                            @endif
                            @if(isset($log->details['payment_method_id']))
                            @php
                                $paymentMethod = \App\Models\PaymentMethod::find($log->details['payment_method_id']);
                                $paymentMethodName = $paymentMethod ? $paymentMethod->name : 'ID ' . $log->details['payment_method_id'];
                            @endphp
                            <div><span class="text-gray-600">Phương thức thanh toán:</span> <span class="font-medium">{{ $paymentMethodName }}</span></div>
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
                <div class="text-xs text-gray-500 mt-2 space-y-1">
                    <p>
                        <i class="fas fa-user mr-1"></i>
                        Bởi: {{ $performedBy }}
                    </p>
                    @if($log->ip_address)
                    <p>
                        <i class="fas fa-globe mr-1"></i>
                        IP: {{ $log->ip_address }}
                    </p>
                    @endif
                    @if($log->user_agent)
                    <p class="break-all">
                        <i class="fas fa-desktop mr-1"></i>
                        Thiết bị: 
                        @php
                            $userAgent = $log->user_agent;
                            // Extract browser and OS info from user agent
                            $browser = 'Unknown';
                            $os = 'Unknown';
                            
                            if (strpos($userAgent, 'Chrome') !== false) $browser = 'Chrome';
                            elseif (strpos($userAgent, 'Firefox') !== false) $browser = 'Firefox';
                            elseif (strpos($userAgent, 'Safari') !== false && strpos($userAgent, 'Chrome') === false) $browser = 'Safari';
                            elseif (strpos($userAgent, 'Edge') !== false) $browser = 'Edge';
                            
                            if (strpos($userAgent, 'Windows') !== false) $os = 'Windows';
                            elseif (strpos($userAgent, 'Mac') !== false) $os = 'macOS';
                            elseif (strpos($userAgent, 'Linux') !== false) $os = 'Linux';
                            elseif (strpos($userAgent, 'Android') !== false) $os = 'Android';
                            elseif (strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false) $os = 'iOS';
                        @endphp
                        <span class="font-medium">{{ $browser }} trên {{ $os }}</span>
                        <span class="text-gray-400 ml-2 text-xs">({{ Str::limit($userAgent, 60) }})</span>
                    </p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>


