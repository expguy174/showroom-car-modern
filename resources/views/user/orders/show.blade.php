@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4 sm:mb-6">
        <div class="px-4 sm:px-6 py-4 border-b bg-gradient-to-r from-indigo-50 to-white">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <div class="text-xs text-gray-500">Mã đơn</div>
                    <h1 class="text-lg sm:text-xl md:text-2xl font-extrabold text-gray-900">#{{ $order->order_number ?? $order->id }}</h1>
                    <div class="mt-1 text-sm text-gray-500">Tạo lúc {{ $order->created_at?->format('d/m/Y H:i') }}</div>
                </div>
                <div class="text-right">
                    @if($order->finance_option_id)
                        <!-- Finance Order Display -->
                        <div class="text-indigo-700 font-extrabold text-base sm:text-lg">{{ number_format($order->down_payment_amount ?? 0, 0, ',', '.') }} đ</div>
                        <div class="text-xs text-gray-500">Trả trước</div>
                        @if((float)($order->discount_total ?? 0) > 0)
                            <div class="text-xs text-green-600 mt-1">
                                <i class="fas fa-tag mr-1"></i>Có khuyến mãi
                            </div>
                        @else
                            <div class="text-xs text-blue-600 mt-1">
                                <i class="fas fa-credit-card mr-1"></i>{{ $order->tenure_months ?? 0 }} tháng
                            </div>
                        @endif
                    @else
                        <!-- Full Payment Display -->
                    <div class="text-indigo-700 font-extrabold text-base sm:text-lg">{{ number_format($order->grand_total, 0, ',', '.') }} đ</div>
                        <div class="text-xs text-gray-500">Tổng cộng</div>
                        @if((float)($order->discount_total ?? 0) > 0)
                            <div class="text-xs text-green-600 mt-1">
                                <i class="fas fa-tag mr-1"></i>Có khuyến mãi
                            </div>
                        @else
                            <div class="text-xs text-emerald-600 mt-1">
                                <i class="fas fa-check-circle mr-1"></i>Thanh toán đầy đủ
                            </div>
                        @endif
                    @endif
                    @if($order->status !== 'cancelled')
                        @php
                            // Cancel logic matching backend validation
                            $canCancel = true;
                            $cancelReason = '';
                            
                            // Order status validation
                            if (!in_array($order->status, ['pending', 'confirmed'])) {
                                $canCancel = false;
                                $cancelReason = 'Đơn hàng đã được xử lý, không thể hủy';
                            }
                            // Payment status validation
                            elseif ($order->payment_status === 'completed') {
                                $canCancel = false;
                                $cancelReason = 'Đơn hàng đã thanh toán đầy đủ, vui lòng liên hệ hỗ trợ';
                            }
                            // Special validation for installment orders with down payment
                            elseif ($order->finance_option_id && $order->payment_status === 'partial') {
                                $hasDownPayment = $order->paymentTransactions()
                                    ->where('notes', 'LIKE', '%Down payment%')
                                    ->where('status', 'completed')
                                    ->exists();
                                if ($hasDownPayment) {
                                    $canCancel = false;
                                    $cancelReason = 'Đơn hàng trả góp đã xác nhận tiền cọc không thể hủy';
                                }
                            }
                            
                            // Time-based restriction: 24 hours window for cancellation
                            if ($canCancel) {
                                $withinCancelWindow = $order->created_at->diffInHours(now()) <= 24;
                                if (!$withinCancelWindow) {
                                    $canCancel = false;
                                    $cancelReason = 'Chỉ có thể hủy trong vòng 24 giờ sau khi đặt hàng';
                                }
                            }
                            
                            // Final reason for cancel button
                            if ($canCancel) {
                                $cancelReason = 'Hủy đơn hàng';
                            }
                    @endphp
                        <div class="mt-2 flex items-center gap-2">
                            <a href="{{ route('user.order.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs">
                                <i class="fas fa-arrow-left"></i> Quay về
                            </a>
                            @if($order->status !== 'cancelled')
                                <form action="{{ route('user.orders.cancel', $order->id) }}" method="post" title="{{ $cancelReason }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-500 text-white hover:bg-rose-600 disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed transition-colors duration-200" {{ $canCancel ? '' : 'disabled' }}>
                            <i class="fas fa-ban"></i> Hủy đơn
                        </button>
                    </form>
                            @else
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs text-gray-500 bg-gray-100">
                                    <i class="fas fa-ban"></i> Đã hủy
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="mt-2 flex items-center gap-2">
                            <a href="{{ route('user.order.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs">
                                <i class="fas fa-arrow-left"></i> Quay về
                            </a>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs text-gray-500 bg-gray-100">
                                <i class="fas fa-ban"></i> Đã hủy
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @php
            $orderSteps = ['pending' => 'Đặt hàng', 'confirmed' => 'Xác nhận', 'shipping' => 'Vận chuyển', 'delivered' => 'Hoàn tất'];
            
            // Tiến trình thanh toán khác nhau cho đơn trả góp và đơn thanh toán 1 lần
            if ($order->finance_option_id) {
                // Đơn trả góp: Trả trước - Đang trả góp - Hoàn tất
                // Flow: pending (chưa trả trước) → partial (đã trả trước, đang trả góp) → completed (hoàn tất)
                $paySteps = ['pending' => 'Trả trước', 'partial' => 'Đang trả góp', 'completed' => 'Hoàn tất'];
            } else {
                // Đơn thanh toán 1 lần: Chờ thanh toán - Đang xử lý - Thanh toán
                // Flow: pending (chờ thanh toán) → processing (đang xử lý) → completed (đã thanh toán)
            $paySteps = ['pending' => 'Chờ thanh toán', 'processing' => 'Đang xử lý', 'completed' => 'Thanh toán'];
            }
            
            $orderKeys = array_keys($orderSteps);
            $payKeys = array_keys($paySteps);
            
            // Xử lý logic hiển thị cho đơn hàng bị hủy
            if ($order->status === 'cancelled') {
                // Đơn hàng bị hủy: chỉ hiển thị bước đầu tiên với màu đỏ
                $orderIndex = -1; // Không có bước nào hoàn thành
                $payIndex = -1; // Không có bước thanh toán nào hoàn thành
            } else {
                $orderIndex = array_search($order->status, $orderKeys);
                if ($orderIndex === false) { $orderIndex = 0; }
                
                // Xử lý đặc biệt cho đơn trả góp
                if ($order->finance_option_id) {
                    // Với trả góp: 
                    // - pending = chưa trả trước (bước 0)
                    // - partial = đã trả trước và đang trả góp (bước 1)
                    // - completed = hoàn tất tất cả kỳ (bước 2)
                    // - processing = xử lý trả trước (coi như bước 0, chưa xác nhận)
                    if ($order->payment_status === 'pending' || $order->payment_status === 'processing') {
                        $payIndex = 0; // Chưa trả trước hoặc đang xử lý trả trước
                    } elseif ($order->payment_status === 'partial') {
                        $payIndex = 1; // Đang trả góp
                    } elseif ($order->payment_status === 'completed') {
                        $payIndex = 2; // Hoàn tất
                    } else {
                        // Các trạng thái khác (failed, cancelled, refunded) - hiển thị ở bước 0
                        $payIndex = 0;
                    }
                } else {
                    // Đơn thanh toán 1 lần: xử lý bình thường
                $payIndex = array_search($order->payment_status, $payKeys);
                    if ($payIndex === false) { 
                        // Nếu không tìm thấy (failed, cancelled, refunded), hiển thị ở bước 0
                        $payIndex = 0; 
                    }
                }
            }
        @endphp
        <div class="px-4 sm:px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="text-sm font-semibold text-gray-800">Tiến trình đơn hàng</div>
                </div>
                <div class="flex items-center gap-2">
                    @foreach($orderSteps as $key => $label)
                        @php $i = array_search($key, $orderKeys); @endphp
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold {{ $order->status === 'cancelled' ? ($i === 0 ? 'bg-rose-500 text-white' : 'bg-gray-200 text-gray-500') : ($i <= $orderIndex ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-500') }}">{{ $i+1 }}</div>
                            <div class="text-xs sm:text-sm text-gray-700 truncate max-w-[90px] sm:max-w-none">{{ $label }}</div>
                        </div>
                        @if(!$loop->last)
                            <div class="flex-1 h-1 rounded-full {{ $order->status === 'cancelled' ? 'bg-gray-200' : ($i < $orderIndex ? 'bg-indigo-600' : 'bg-gray-200') }}"></div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="text-sm font-semibold text-gray-800">Tiến trình thanh toán</div>
                </div>
                <div class="flex items-center gap-2">
                    @foreach($paySteps as $key => $label)
                        @php $j = array_search($key, $payKeys); @endphp
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold {{ $order->status === 'cancelled' ? ($j === 0 ? 'bg-rose-500 text-white' : 'bg-gray-200 text-gray-500') : (in_array($order->payment_status, ['failed','cancelled']) ? 'bg-rose-100 text-rose-700' : ($j <= $payIndex ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-500')) }}">{{ $j+1 }}</div>
                            <div class="text-xs sm:text-sm text-gray-700 truncate max-w-[110px] sm:max-w-none">{{ $label }}</div>
                        </div>
                        @if(!$loop->last)
                            <div class="flex-1 h-1 rounded-full {{ $order->status === 'cancelled' ? 'bg-gray-200' : (in_array($order->payment_status, ['failed','cancelled']) ? 'bg-rose-100' : ($j < $payIndex ? 'bg-emerald-600' : 'bg-gray-200')) }}"></div>
                        @endif
                    @endforeach
                </div>
                @if(in_array($order->payment_status, ['failed','cancelled']) && $order->status !== 'cancelled')
                <div class="mt-2 text-xs text-rose-600"><i class="fas fa-exclamation-circle mr-1"></i> Thanh toán không thành công</div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">

            <!-- Trạng thái -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-bold">Trạng thái</h2>
                    <div class="text-sm text-gray-500">Tạo lúc {{ $order->created_at?->format('d/m/Y H:i') }}</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Cột trái: Thông tin cơ bản -->
                    <dl class="space-y-3 text-sm">
                    <div class="flex items-center justify-between sm:justify-start sm:gap-3">
                        <dt class="text-gray-500">Mã đơn</dt>
                        <dd class="font-medium text-gray-900">#{{ $order->order_number ?? $order->id }}</dd>
                    </div>
                        <div class="flex items-center justify-between sm:justify-start sm:gap-3">
                            <dt class="text-gray-500">Loại thanh toán</dt>
                            <dd class="font-medium text-gray-900">{{ $order->payment_type_display }}</dd>
                        </div>
                        @if($order->financeOption)
                        <div class="flex items-center justify-between sm:justify-start sm:gap-3">
                            <dt class="text-gray-500">Gói trả góp</dt>
                            <dd class="font-medium text-gray-900">{{ $order->financeOption->name }}</dd>
                        </div>
                        @else
                        <div class="flex items-center justify-between sm:justify-start sm:gap-3">
                            <dt class="text-gray-500">Số sản phẩm</dt>
                            <dd class="font-medium text-gray-900">{{ $order->items->count() }} sản phẩm</dd>
                        </div>
                        @endif
                    </dl>
                    
                    <!-- Cột phải: Phương thức và Trạng thái -->
                    <dl class="space-y-3 text-sm">
                        <div class="flex items-center justify-between sm:justify-start sm:gap-3">
                            <dt class="text-gray-500">Phương thức</dt>
                            <dd class="font-medium text-gray-900">{{ $order->paymentMethod->name ?? '—' }}</dd>
                        </div>
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
                </dl>
                </div>
                
                @if(!$order->finance_option_id)
                <!-- Payment Type Info for Full Payment -->
                <div class="mt-4 p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center mt-0.5">
                            <i class="fas fa-check text-emerald-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-emerald-800 mb-1">Thanh toán một lần</h4>
                            <p class="text-sm text-emerald-700">Đơn hàng này được thanh toán toàn bộ một lần, không có lịch trả góp.</p>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($order->paymentMethod && in_array($order->paymentMethod->code, ['bank_transfer']) && !$order->finance_option_id)
                <!-- Bank Transfer Info for Full Payment -->
                <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="text-sm font-semibold text-blue-900 mb-2 flex items-center gap-2">
                        <i class="fas fa-university"></i>
                        Thông tin chuyển khoản
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-blue-800 mb-3">
                        <div><span class="font-medium">Ngân hàng:</span> Vietcombank - CN TP.HCM</div>
                        <div><span class="font-medium">Tên tài khoản:</span> CONG TY TNHH SHOWROOM</div>
                        <div><span class="font-medium">Số tài khoản:</span> <span class="font-mono">0123456789</span></div>
                        <div><span class="font-medium">Nội dung:</span> <span class="font-mono">{{ $order->order_number ?? ('#'.$order->id) }}</span></div>
                    </div>
                    <div class="text-center p-2 bg-blue-100 rounded border border-blue-300">
                        <div class="text-xs text-blue-700 font-medium">Số tiền cần chuyển</div>
                        <div class="text-lg font-bold text-blue-900">{{ number_format($order->grand_total ?? $order->total_price, 0, ',', '.') }} đ</div>
                    </div>
                    <div class="mt-2 text-xs text-blue-700">
                        <i class="fas fa-info-circle mr-1"></i>
                        Vui lòng chuyển khoản chính xác số tiền và nội dung để hệ thống đối soát tự động.
                    </div>
                </div>
                @endif
                
                @if($order->finance_option_id && $order->financeOption)
                <!-- Finance Details Section -->
                <div class="mt-4 p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                    <div class="text-sm font-semibold text-indigo-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-calculator"></i>
                        Chi tiết trả góp
                    </div>
                    <!-- Finance Provider Info -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-4">
                        <div>
                            <div class="text-indigo-700 font-medium">Ngân hàng</div>
                            <div class="text-indigo-900">{{ $order->financeOption->bank_name }}</div>
                        </div>
                        <div>
                            <div class="text-indigo-700 font-medium">Lãi suất</div>
                            <div class="text-indigo-900">{{ $order->financeOption->interest_rate }}%/năm</div>
                        </div>
                    </div>

                    <!-- Finance Amount Breakdown -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm mb-4">
                        <div class="text-center p-3 bg-white rounded-lg border border-indigo-100">
                            <div class="text-indigo-700 font-medium text-xs mb-1">Trả trước</div>
                            <div class="text-indigo-900 font-bold text-lg">{{ number_format($order->down_payment_amount ?? 0, 0, ',', '.') }} đ</div>
                        </div>
                        <div class="text-center p-3 bg-white rounded-lg border border-indigo-100">
                            <div class="text-indigo-700 font-medium text-xs mb-1">Số tiền vay</div>
                            <div class="text-indigo-900 font-bold text-lg">{{ number_format(($order->subtotal ?? $order->total_price) - ($order->down_payment_amount ?? 0), 0, ',', '.') }} đ</div>
                        </div>
                        <div class="text-center p-3 bg-white rounded-lg border border-indigo-100">
                            <div class="text-indigo-700 font-medium text-xs mb-1">Trả hàng tháng</div>
                            <div class="text-indigo-900 font-bold text-lg">{{ number_format($order->monthly_payment_amount ?? 0, 0, ',', '.') }} đ</div>
                        </div>
                    </div>

                    <!-- Tenure Info -->
                    <div class="text-center mb-4">
                        <div class="text-indigo-700 font-medium text-sm">Thời hạn vay</div>
                        <div class="text-indigo-900 font-semibold text-lg">{{ $order->tenure_months ?? 0 }} tháng</div>
                    </div>
                    
                    <!-- Additional Costs Info -->
                    @if($order->tax_total > 0 || $order->shipping_fee > 0 || $order->payment_fee > 0)
                    <div class="p-3 bg-amber-50 rounded-lg border border-amber-200 mb-3">
                        <div class="text-xs text-amber-800 mb-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            <span class="font-medium">Lưu ý về chi phí bổ sung:</span>
                        </div>
                        <div class="text-xs text-amber-700 space-y-1">
                            @if($order->tax_total > 0)
                            <div>• Thuế: {{ number_format($order->tax_total, 0, ',', '.') }} đ (thanh toán riêng)</div>
                            @endif
                            @if($order->shipping_fee > 0)
                            <div>• Phí vận chuyển: {{ number_format($order->shipping_fee, 0, ',', '.') }} đ (thanh toán riêng)</div>
                            @endif
                            @if($order->payment_fee > 0)
                            <div>• Phí thanh toán: {{ number_format($order->payment_fee, 0, ',', '.') }} đ (thanh toán riêng)</div>
                            @endif
                            <div class="mt-1 font-medium">→ Trả góp chỉ áp dụng cho giá trị sản phẩm</div>
                        </div>
                    </div>
                    @endif
                    @if($order->paymentMethod && in_array($order->paymentMethod->code, ['bank_transfer']))
                    <!-- Bank Transfer Info for Finance -->
                    <div class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="text-sm font-semibold text-blue-900 mb-2 flex items-center gap-2">
                            <i class="fas fa-university"></i>
                            Thông tin chuyển khoản (trả trước)
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-blue-800 mb-3">
                            <div><span class="font-medium">Ngân hàng:</span> Vietcombank - CN TP.HCM</div>
                            <div><span class="font-medium">Tên tài khoản:</span> CONG TY TNHH SHOWROOM</div>
                            <div><span class="font-medium">Số tài khoản:</span> <span class="font-mono">0123456789</span></div>
                            <div><span class="font-medium">Nội dung:</span> <span class="font-mono">{{ $order->order_number ?? ('#'.$order->id) }}</span></div>
                        </div>
                        <div class="text-center p-2 bg-blue-100 rounded border border-blue-300">
                            <div class="text-xs text-blue-700 font-medium">Số tiền cần chuyển</div>
                            <div class="text-lg font-bold text-blue-900">{{ number_format($order->down_payment_amount ?? 0, 0, ',', '.') }} đ</div>
                            <div class="text-xs text-blue-600">(Khoản trả trước)</div>
                        </div>
                        <div class="mt-2 text-xs text-blue-700">
                            <i class="fas fa-info-circle mr-1"></i>
                            Chuyển khoản chính xác số tiền và nội dung để hệ thống đối soát tự động.
                        </div>
                    </div>
                    @endif
                    
                    <div class="mt-3 p-3 bg-white rounded-lg border border-indigo-100">
                        <div class="text-xs text-indigo-700 flex items-start gap-2">
                            <i class="fas fa-info-circle mt-0.5 flex-shrink-0"></i>
                            <div>
                                <div class="font-medium mb-1">Lưu ý quan trọng:</div>
                                <ul class="space-y-1">
                                    @if($order->paymentMethod && in_array($order->paymentMethod->code, ['bank_transfer']))
                                    <li>• Sau khi chuyển khoản, ngân hàng sẽ liên hệ để hoàn tất thủ tục vay</li>
                                    @else
                                    <li>• Bạn đã thanh toán khoản trả trước qua {{ $order->paymentMethod->name ?? 'phương thức đã chọn' }}</li>
                                    @endif
                                    <li>• Ngân hàng sẽ liên hệ để hoàn tất thủ tục vay trong 1-2 ngày làm việc</li>
                                    <li>• Vui lòng chuẩn bị đầy đủ hồ sơ theo yêu cầu của ngân hàng</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="px-4 sm:px-6 py-4 border-b flex items-center justify-between gap-2">
                    <h2 class="text-lg font-bold">Thông tin đơn hàng</h2>
                    <div class="text-sm text-gray-500">Sản phẩm ({{ $order->items->count() }})</div>
                </div>
                <div class="divide-y max-h-[400px] overflow-y-auto">
                    @forelse($order->items->sortBy(function($it){ return $it->item_type === 'car_variant' ? 0 : 1; }) as $it)
                        @php
                            $model = $it->item;
                            $unit = $it->price;
                            $line = $it->line_total ?: ($unit * $it->quantity);
                            $meta = is_array($it->item_metadata) ? $it->item_metadata : (json_decode($it->item_metadata ?? 'null', true) ?: []);
                            $img = null;
                            if ($it->item_type === 'car_variant' && $model?->images?->isNotEmpty()) {
                                $f = $model->images->first();
                                $img = $f->image_url ?: ($f->image_path ? asset('storage/'.$f->image_path) : null);
                            } elseif ($it->item_type === 'accessory') {
                                $galleryRaw = $model->gallery ?? null;
                                $gallery = is_array($galleryRaw) ? $galleryRaw : (json_decode($galleryRaw ?? '[]', true) ?: []);
                                $firstGalleryImg = $gallery[0] ?? null;
                                if ($firstGalleryImg) {
                                    $img = $firstGalleryImg;
                                } elseif (!empty($model->image_url)) {
                                    $img = filter_var($model->image_url, FILTER_VALIDATE_URL) ? $model->image_url : asset('storage/'.$model->image_url);
                                } else {
                                    $img = asset('images/default-accessory.jpg');
                                }
                            }
                        @endphp
                        <div class="px-4 py-3 flex items-center gap-3 flex-wrap">
                            <div class="w-16 h-12 rounded-md bg-gray-100 overflow-hidden flex-shrink-0">
                                @if($img)
                                    <img src="{{ $img }}" class="w-full h-full object-cover" alt="{{ $model?->name ?? $it->item_name }}" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-[11px]">No image</div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-sm font-medium text-gray-900 line-clamp-2" title="{{ $model?->name ?? $it->item_name }}">{{ $model?->name ?? $it->item_name }}</div>
                                @if($it->item_type === 'car_variant')
                                    <div class="text-[11px] text-gray-500 whitespace-normal break-words">
                                        @php 
                                            $colorName = $it->color?->color_name;
                                            $colorHex = $colorName ? \App\Helpers\ColorHelper::getColorHex($colorName) : null;
                                        @endphp
                                        SL: {{ $it->quantity }}
                                        <span>•</span>
                                        <span class="inline-flex items-center gap-1">
                                            <span>Màu:</span>
                                            @if($colorName)
                                                <span class="inline-flex items-center gap-1">
                                                    <span class="inline-block w-3 h-3 rounded-full border border-gray-300" style="background-color: {{ $colorHex ?? '#d1d5db' }};"></span>
                                                    <span class="text-gray-700">{{ $colorName }}</span>
                                                </span>
                                            @else
                                                <span class="text-gray-400">Chưa chọn</span>
                                            @endif
                                        </span>
                                        @php $featureNames = $meta['feature_names'] ?? []; @endphp
                                        @if(!empty($featureNames))
                                            <div class="mt-1 space-y-1">
                                                <div class="text-[11px] text-gray-600">Tùy chọn:
                                                    @foreach($featureNames as $fname)
                                                        <span class="inline-flex items-center gap-1 mr-2">{{ $fname }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        @php $optionNames = $meta['option_names'] ?? []; @endphp
                                        @if(!empty($optionNames))
                                            <div class="mt-1 space-y-1">
                                                <div class="text-[11px] text-gray-600">Gói:
                                                    @foreach($optionNames as $oname)
                                                        <span class="inline-flex items-center gap-1 mr-2">{{ $oname }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-[11px] text-gray-500">SL: {{ $it->quantity }}</div>
                                @endif
                            </div>
                            <div class="text-right sm:shrink-0 sm:min-w-[140px]">
                                <div class="text-xs text-gray-500 whitespace-nowrap leading-none">Đơn giá</div>
                                <div class="text-sm font-semibold text-gray-900 whitespace-nowrap tabular-nums leading-none">{{ number_format($unit) }} đ</div>
                                <div class="text-xs text-gray-500 whitespace-nowrap leading-none mt-2">Tổng</div>
                                <div class="text-sm font-semibold text-gray-900 whitespace-nowrap tabular-nums leading-none">{{ number_format($line) }} đ</div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500">Không có sản phẩm trong đơn hàng</div>
                    @endforelse
                </div>
            </div>

        </div>

        <div class="space-y-4 sm:space-y-6">
            <!-- Tổng kết (giống success) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h3 class="text-base font-bold mb-4">Tổng kết</h3>
                @php
                    $ship = $order->shippingAddress ?: $order->billingAddress;
                    // Ưu tiên contact_name -> full_name -> name trên address; sau đó fallback về user->name
                    $recipientName = $ship?->contact_name
                        ?? $ship?->full_name
                        ?? $ship?->name
                        ?? optional($order->user)->name
                        ?? '';
                    $recipientPhone = $ship?->phone ?? optional($order->user)->phone ?? '';
                    $recipientEmail = optional($order->user)->email ?? '';
                @endphp
                <div class="space-y-3 text-sm">
                    <div class="text-gray-700">
                        <div class="font-semibold mb-1">Người nhận</div>
                        <div class="font-medium">{{ $recipientName !== '' ? $recipientName : (optional($order->user)->name ?? '—') }}</div>
                        <div class="text-gray-500">@if($recipientPhone) {{ $recipientPhone }} @endif @if($recipientPhone && $recipientEmail) • @endif @if($recipientEmail) {{ $recipientEmail }} @endif</div>
                    </div>
                    <div class="text-gray-700">
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
                </div>
                <div class="mt-4 border-t pt-4 space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Tạm tính</span>
                        <span class="text-gray-900 font-medium">{{ number_format($order->subtotal ?? 0, 0, ',', '.') }} đ</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Thuế ({{ number_format(($order->tax_rate ?? 0.1) * 100, 1) }}%)</span>
                        <span class="text-gray-900 font-medium">{{ number_format($order->tax_total ?? 0, 0, ',', '.') }} đ</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">
                            Vận chuyển
                            @if($order->shipping_method)
                                <span class="text-xs text-blue-600 ml-1">
                                    ({{ $order->shipping_method === 'express' ? 'Nhanh' : ($order->shipping_method === 'standard' ? 'Tiêu chuẩn' : ucfirst($order->shipping_method)) }})
                                </span>
                            @endif
                        </span>
                        <span class="text-gray-900 font-medium">{{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }} đ</span>
                    </div>
                    @if((float)($order->payment_fee ?? 0) > 0)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">
                            Phí thanh toán
                            @if($order->paymentMethod)
                                <span class="text-xs text-gray-500 ml-1">({{ $order->paymentMethod->name }})</span>
                            @endif
                        </span>
                        <span class="text-gray-900 font-medium">{{ number_format($order->payment_fee ?? 0, 0, ',', '.') }} đ</span>
                    </div>
                    @endif
                    @if((float)($order->discount_total ?? 0) > 0)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">
                            Giảm giá
                            @if($order->promotion)
                                <span class="text-xs text-green-600 ml-1">({{ $order->promotion->code }})</span>
                            @endif
                        </span>
                        <span class="text-rose-600 font-medium">-{{ number_format($order->discount_total ?? 0, 0, ',', '.') }} đ</span>
                    </div>
                    @endif
                    @if($order->financeOption && $order->down_payment_amount)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Trả trước</span>
                        <span class="text-gray-900 font-medium">{{ number_format($order->down_payment_amount, 0, ',', '.') }} đ</span>
                    </div>
                    @if($order->monthly_payment_amount && $order->tenure_months)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Trả góp</span>
                        <span class="text-gray-900 font-medium">{{ number_format($order->monthly_payment_amount, 0, ',', '.') }} đ/tháng × {{ $order->tenure_months }} tháng</span>
                    </div>
                    @endif
                    @endif
                    <div class="pt-2 mt-2 border-t flex items-center justify-between">
                        <span class="text-gray-700 font-semibold">Tổng cộng</span>
                        <span class="text-indigo-700 font-extrabold text-lg">{{ number_format($order->grand_total ?? $order->total_price, 0, ',', '.') }} đ</span>
                    </div>
                    
                </div>
            </div>

            <!-- Promotion Details Section -->
            @if($order->promotion)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-tag text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Khuyến mãi đã áp dụng</h3>
                        <p class="text-sm text-gray-600">Thông tin chi tiết về ưu đãi</p>
                    </div>
                </div>
                
                <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $order->promotion->code }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    @switch($order->promotion->type)
                                        @case('percentage')
                                            Giảm theo %
                                            @break
                                        @case('fixed_amount')
                                            Giảm cố định
                                            @break
                                        @case('free_shipping')
                                            Miễn phí ship
                                            @break
                                        @case('brand_specific')
                                            Theo thương hiệu
                                            @break
                                        @case('category_specific')
                                            Theo danh mục
                                            @break
                                        @case('buy_x_get_y')
                                            Mua X tặng Y
                                            @break
                                        @case('bundle_discount')
                                            Combo giảm giá
                                            @break
                                        @case('tiered_discount')
                                            Giảm theo bậc
                                            @break
                                        @case('time_based')
                                            Flash Sale
                                            @break
                                        @default
                                            {{ ucfirst($order->promotion->type) }}
                                    @endswitch
                                </span>
                            </div>
                            <h4 class="font-semibold text-green-900 mb-1">{{ $order->promotion->name }}</h4>
                            <p class="text-sm text-green-700">{{ $order->promotion->description }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-green-900">-{{ number_format($order->discount_total, 0, ',', '.') }} đ</div>
                            <div class="text-xs text-green-600">Đã tiết kiệm</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Ghi chú --}}
            @if($order->note)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h3 class="text-base font-bold mb-2">Ghi chú</h3>
                <p class="text-sm text-gray-700">{{ $order->note }}</p>
            </div>
            @endif

            <!-- Cancelled Order Information -->
            @if($order->status === 'cancelled')
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="fas fa-ban text-rose-600"></i>
                        <h3 class="text-base font-bold text-rose-800">Đơn hàng đã hủy</h3>
                    </div>
                    
                    @php
                        $cancelledRefunds = $order->refunds->whereIn('status', ['pending', 'processing', 'refunded']);
                        $totalRefunded = $cancelledRefunds->where('status', 'refunded')->sum('amount');
                        $pendingRefund = $cancelledRefunds->whereIn('status', ['pending', 'processing'])->first();
                        
                        // Calculate payment info
                        if($order->finance_option_id) {
                            $paidInstallments = $order->installments()->where('status', 'paid')->count();
                            $totalInstallments = $order->installments()->count();
                            $totalPaidAmount = $order->paymentTransactions()->where('status', 'completed')->sum('amount');
                        } else {
                            $totalPaidAmount = $order->paymentTransactions()->where('status', 'completed')->sum('amount');
                        }
                    @endphp
                    
                    <div class="space-y-3">
                        {{-- Payment Status --}}
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Loại đơn hàng</p>
                                    <p class="text-sm font-semibold text-gray-900">
                                        @if($order->finance_option_id)
                                            Trả góp
                                        @else
                                            Thanh toán 1 lần
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Tổng giá trị</p>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ number_format($order->grand_total, 0, ',', '.') }} đ
                                    </p>
                                </div>
                            </div>
                            
                            @if($order->finance_option_id)
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-600 mb-1">Tiến độ trả góp</p>
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $paidInstallments }}/{{ $totalInstallments }} kỳ
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-600 mb-1">Đã thanh toán</p>
                                        <p class="text-sm font-semibold text-blue-600">
                                            {{ number_format($totalPaidAmount, 0, ',', '.') }} đ
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <p class="text-xs text-gray-600">Đã thanh toán</p>
                                    <p class="text-sm font-semibold text-blue-600">
                                        {{ number_format($totalPaidAmount, 0, ',', '.') }} đ
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        {{-- Cancellation Info --}}
                        <div class="bg-rose-50 border border-rose-200 rounded-lg p-3">
                            <p class="text-xs text-rose-700">
                                <i class="fas fa-calendar-times mr-1"></i>
                                Đơn hàng đã bị hủy vào {{ $order->updated_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif


            <!-- Refund Section for Active Orders (Partial or Completed Payment) -->
            @if(in_array($order->payment_status, ['partial', 'completed']) && $order->status !== 'cancelled')
                @php
                    $existingRefund = $order->refunds->whereIn('status', ['pending', 'processing'])->first();
                    $hasFailedRefund = $order->refunds->where('status', 'failed')->count() > 0;
                    
                    // Refund policy:
                    // - Within 30 days from order date
                    // - No pending/processing refund
                    // - No failed refund (once rejected, cannot request again)
                    $canRequestRefund = !$existingRefund 
                        && !$hasFailedRefund
                        && $order->created_at->diffInDays(now()) <= 30;
                    
                    // Calculate total paid for partial payment orders
                    $totalPaid = $order->paymentTransactions()->where('status', 'completed')->sum('amount');
                @endphp
                
                @if($existingRefund)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                        <h3 class="text-base font-bold mb-4">Yêu cầu hoàn tiền</h3>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-clock text-yellow-600 mt-1"></i>
                                <div>
                                    <h4 class="font-medium text-yellow-800">Đang xử lý yêu cầu hoàn tiền</h4>
                                    <p class="text-sm text-yellow-700 mt-1">
                                        Số tiền: <span class="font-medium">{{ number_format($existingRefund->amount, 0, ',', '.') }} đ</span>
                                    </p>
                                    <p class="text-sm text-yellow-700">
                                        Lý do: {{ $existingRefund->reason }}
                                    </p>
                                    <p class="text-xs text-yellow-600 mt-2">
                                        Yêu cầu từ {{ $existingRefund->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($canRequestRefund)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-bold">Yêu cầu hoàn tiền</h3>
                            <span class="text-xs text-gray-500">Trong vòng 30 ngày</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">
                            Nếu bạn không hài lòng với đơn hàng, bạn có thể yêu cầu hoàn tiền trong vòng 30 ngày kể từ ngày đặt hàng.
                        </p>
                        <button onclick="openRefundModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium">
                            <i class="fas fa-undo"></i> Yêu cầu hoàn tiền
                        </button>
                    </div>
                @endif
            @endif
            
            <!-- Completed Refunds Section -->
            @if($order->refunds->where('status', 'refunded')->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <h3 class="text-base font-bold mb-4">Lịch sử hoàn tiền</h3>
                    <div class="space-y-3">
                        @foreach($order->refunds->where('status', 'refunded') as $refund)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-green-800">Hoàn tiền thành công</h4>
                                        <p class="text-sm text-green-700 mt-1">
                                            Số tiền: <span class="font-medium">{{ number_format($refund->amount, 0, ',', '.') }} VNĐ</span>
                                        </p>
                                        <p class="text-sm text-green-700">
                                            Lý do: {{ $refund->reason }}
                                        </p>
                                        @if($refund->admin_notes)
                                        <div class="mt-2 p-2 bg-green-100 border border-green-300 rounded">
                                            <p class="text-xs text-green-800 font-medium">Ghi chú:</p>
                                            <p class="text-sm text-green-900 mt-1">{{ $refund->admin_notes }}</p>
                                        </div>
                                        @endif
                                        <p class="text-xs text-green-600 mt-2">
                                            Hoàn tiền lúc {{ $refund->processed_at?->format('d/m/Y H:i') ?? $refund->updated_at->format('d/m/Y H:i') }}
                                        </p>
                                        <p class="text-xs text-green-500 mt-1">
                                            <i class="fas fa-info-circle"></i> Tiền sẽ về tài khoản trong vòng 3-5 ngày làm việc.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Failed Refunds Section -->
            @if($order->refunds->where('status', 'failed')->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <h3 class="text-base font-bold mb-4">Yêu cầu hoàn tiền bị từ chối</h3>
                    <div class="space-y-3">
                        @foreach($order->refunds->where('status', 'failed') as $refund)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-times-circle text-red-600 mt-1"></i>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-red-800">Yêu cầu không được chấp nhận</h4>
                                        <p class="text-sm text-red-700 mt-1">
                                            Số tiền yêu cầu: <span class="font-medium">{{ number_format($refund->amount, 0, ',', '.') }} VNĐ</span>
                                        </p>
                                        <p class="text-sm text-red-700">
                                            Lý do yêu cầu: {{ $refund->reason }}
                                        </p>
                                        @if($refund->admin_notes)
                                        <div class="mt-2 p-2 bg-red-100 border border-red-300 rounded">
                                            <p class="text-xs text-red-800 font-medium">Lý do từ chối:</p>
                                            <p class="text-sm text-red-900 mt-1">{{ $refund->admin_notes }}</p>
                                        </div>
                                        @endif
                                        <p class="text-xs text-red-600 mt-2">
                                            Từ chối lúc {{ $refund->updated_at->format('d/m/Y H:i') }}
                                        </p>
                                        <p class="text-xs text-red-500 mt-2">
                                            <i class="fas fa-info-circle"></i> Nếu có thắc mắc, vui lòng liên hệ bộ phận hỗ trợ.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif


            {{-- Lịch Trả Góp - Simple Version (Hide if cancelled) --}}
            @if($order->finance_option_id && $installmentStats && $order->status !== 'cancelled')
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h3 class="text-base font-bold mb-3">Lịch Trả Góp</h3>
                
                {{-- Quick Stats --}}
                <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-lg p-3 mb-3">
                    <div class="flex justify-between items-center text-sm">
                        <div>
                            <div class="text-gray-600 text-xs">Tiến độ</div>
                            <div class="font-bold text-gray-900">{{ $installmentStats['paid_count'] }}/{{ $installmentStats['total_installments'] }} kỳ</div>
                        </div>
                        <div class="text-right">
                            <div class="text-gray-600 text-xs">Còn nợ</div>
                            <div class="font-bold text-red-600">{{ number_format($installmentStats['total_remaining']) }} đ</div>
                        </div>
                    </div>
                </div>

                {{-- Next Payment --}}
                @if($installmentStats['next_payment'])
                <div class="bg-blue-50 border-l-4 border-blue-500 rounded p-3 mb-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="text-xs font-medium text-blue-800">Kỳ tiếp theo</div>
                            <div class="text-sm font-bold text-blue-900 mt-1">
                                Kỳ {{ $installmentStats['next_payment']->installment_number }} - {{ number_format($installmentStats['next_payment']->amount) }} đ
                            </div>
                            <div class="text-xs text-blue-600 mt-1">
                                Đến hạn: {{ $installmentStats['next_payment']->due_date->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-green-50 border-l-4 border-green-500 rounded p-3 mb-3">
                    <div class="text-sm font-medium text-green-800">
                        Đã hoàn thành tất cả các kỳ!
                    </div>
                </div>
                @endif

                {{-- Payment Methods & Instructions --}}
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <div class="text-xs font-semibold text-yellow-800 mb-2">Phương thức thanh toán trả góp:</div>
                    
                    <div class="space-y-2 mb-3">
                        {{-- Bank Transfer - RECOMMENDED --}}
                        <div class="bg-white rounded-lg p-2.5 border-2 border-green-200">
                            <div class="flex items-center justify-between mb-1">
                                <div class="font-semibold text-xs text-gray-800">💳 Chuyển khoản ngân hàng</div>
                                <span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-medium">Khuyến nghị</span>
                            </div>
                            <div class="text-xs text-gray-600 mb-1">
                                Chuyển khoản với nội dung:
                            </div>
                            <div class="mb-1">
                                <span class="font-mono text-xs bg-yellow-50 px-2 py-1 rounded border border-yellow-200 inline-block">TRAGOP-{{ $order->order_number }}-KY[X]</span>
                            </div>
                            <div class="text-[10px] text-gray-500">
                                • Nhanh chóng, có chứng từ điện tử
                            </div>
                        </div>
                        
                        {{-- Cash at Showroom --}}
                        <div class="bg-white rounded-lg p-2.5 border border-yellow-100">
                            <div class="font-semibold text-xs text-gray-800 mb-1">💵 Tiền mặt tại showroom</div>
                            <div class="text-xs text-gray-600 mb-1">
                                Đến trực tiếp showroom để thanh toán và nhận biên nhận
                            </div>
                            <div class="text-[10px] text-gray-500">
                                • Địa chỉ: (địa chỉ showroom của bạn)
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-2 mb-2">
                        <div class="text-[10px] text-blue-700">
                            <strong>Lưu ý:</strong> Không chấp nhận thanh toán qua ví điện tử (MoMo, VNPay...) cho các khoản trả góp. Vui lòng sử dụng chuyển khoản ngân hàng hoặc đến showroom.
                        </div>
                    </div>
                    
                    <div class="text-xs font-semibold text-yellow-800 mb-1">⚠️ Quy định quan trọng:</div>
                    <ul class="text-xs text-yellow-700 space-y-0.5">
                        <li>• Thanh toán đúng hạn để tránh phí phạt</li>
                        <li>• Sau khi chuyển khoản, liên hệ hotline <strong>0909.xxx.xxx</strong> để xác nhận</li>
                        <li>• Thanh toán trước hạn được chấp nhận và khuyến khích</li>
                        <li>• Trễ hạn trên 7 ngày sẽ bị tính phí phạt theo quy định</li>
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div id="refundModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50" onclick="closeRefundModal()">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Yêu cầu hoàn tiền</h3>
                <button type="button" onclick="closeRefundModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('user.orders.refund', $order) }}" method="POST" id="refundForm">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Số tiền hoàn (VND)</label>
                        <input type="number" name="amount" id="refundAmount"
                               value="{{ intval($totalPaid) }}"
                               max="{{ intval($totalPaid) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                               placeholder="Nhập số tiền hoàn">
                        <p class="text-xs text-gray-500 mt-1">
                            Đã thanh toán: {{ number_format($totalPaid, 0, ',', '.') }} VNĐ
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lý do hoàn tiền</label>
                        <textarea name="reason" id="refundReason" rows="4"
                                  class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                  placeholder="Vui lòng mô tả lý do bạn muốn hoàn tiền..."></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeRefundModal()" 
                            class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-medium">
                        Hủy
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium">
                        Gửi yêu cầu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
// Handle cancel order form with confirm dialog (same as orders index page)
document.addEventListener('click', function(e) {
    if (e.target.closest('form[action*="/cancel"]')) {
        e.preventDefault();
        const form = e.target.closest('form');
        const button = form.querySelector('button[type="submit"]');
        
        const orderNumber = '#{{ $order->order_number ?? $order->id }}';
        const orderAmount = '{{ number_format($order->grand_total, 0, ",", ".") }} đ';
        
        // Enhanced confirm dialog with more details
        let confirmMessage = `Bạn có chắc chắn muốn hủy đơn hàng ${orderNumber}?\n\nGiá trị đơn hàng: ${orderAmount}`;
        
        @if($order->finance_option_id)
        confirmMessage += `\nLưu ý: Nếu đã thanh toán trả trước, bạn có thể yêu cầu hoàn tiền sau khi hủy.`;
        @endif
        
        confirmMessage += `\n\nHành động này không thể hoàn tác.`;
        
        showConfirmDialog(
            'Xác nhận hủy đơn hàng',
            confirmMessage,
            'Xác nhận hủy',
            'Không hủy',
            () => {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Đang hủy...';
                
                // Submit the form and reload page after success
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        // Parse JSON response
                        return response.json().then(data => {
                            if (data.success) {
                                // Show success message
                                if (typeof window.showMessage === 'function') {
                                    window.showMessage(data.message || 'Đã hủy đơn hàng thành công', 'success');
                                }
                                
                                // Reload page to show updated status
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            } else {
                                throw new Error(data.message || 'Failed to cancel order');
                            }
                        });
                    } else {
                        // Handle different error status codes
                        if (response.status === 403) {
                            throw new Error('Bạn không có quyền hủy đơn hàng này');
                        } else if (response.status === 422) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Đơn hàng không thể hủy ở trạng thái hiện tại');
                            });
                        } else {
                            throw new Error('Có lỗi xảy ra khi hủy đơn hàng');
                        }
                    }
                })
                .catch(error => {
                    console.error('Cancel order error:', error);
                    if (typeof window.showMessage === 'function') {
                        window.showMessage(error.message || 'Có lỗi xảy ra khi hủy đơn hàng', 'error');
                    } else {
                        alert(error.message || 'Có lỗi xảy ra khi hủy đơn hàng');
                    }
                    
                    // Reset button
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-ban"></i> Hủy đơn';
                });
            }
        );
    }
});

// Confirm dialog function (same as orders index page)
function showConfirmDialog(title, message, confirmText, cancelText, onConfirm){
    const existing = document.querySelector('.fast-confirm-dialog');
    if (existing) existing.remove();
    const wrapper = document.createElement('div');
    wrapper.className = 'fast-confirm-dialog fixed inset-0 z-[100000] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4';
    wrapper.innerHTML = `
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-200 scale-95 opacity-0">
            <div class="p-6">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">${title}</h3>
                <p class="text-gray-600 text-center mb-6">${message}</p>
                <div class="flex space-x-3">
                    <button class="fast-cancel flex-1 px-4 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200">${cancelText}</button>
                    <button class="fast-confirm flex-1 px-4 py-2.5 text-white bg-red-600 hover:bg-red-700 rounded-lg font-medium transition-colors duration-200">${confirmText}</button>
                </div>
            </div>
        </div>`;
    document.body.appendChild(wrapper);
    const panel = wrapper.firstElementChild;
    
    // Animate in
    requestAnimationFrame(() => {
        panel.style.transform = 'scale(1)';
        panel.style.opacity = '1';
    });
    
    // Handle clicks
    wrapper.querySelector('.fast-cancel').addEventListener('click', () => {
        wrapper.remove();
    });
    
    wrapper.querySelector('.fast-confirm').addEventListener('click', () => {
        wrapper.remove();
        onConfirm();
    });
    
    // Close on backdrop click
    wrapper.addEventListener('click', (e) => {
        if (e.target === wrapper) {
            wrapper.remove();
        }
    });
}

// Refund modal functions
function openRefundModal() {
    document.getElementById('refundModal').classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('refundAmount').focus();
    }, 100);
}

function closeRefundModal() {
    document.getElementById('refundModal').classList.add('hidden');
    document.getElementById('refundForm').reset();
    document.getElementById('refundAmount').value = '{{ intval($totalPaid) }}';
}

// Handle ESC key to close modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('refundModal');
        if (!modal.classList.contains('hidden')) {
            closeRefundModal();
        }
    }
});

// Handle refund form submission
document.getElementById('refundForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    const amountInput = form.querySelector('#refundAmount');
    const reasonInput = form.querySelector('#refundReason');
    
    // Client-side validation with Vietnamese toast messages
    const amountValue = parseFloat(amountInput.value);
    const reasonValue = reasonInput.value.trim();
    const maxAmount = parseInt('{{ $order->grand_total }}');
    
    if (!amountValue || amountValue <= 0) {
        if (typeof window.showMessage === 'function') {
            window.showMessage('Vui lòng nhập số tiền hoàn hợp lệ', 'error');
        } else {
            alert('Vui lòng nhập số tiền hoàn hợp lệ');
        }
        amountInput.focus();
        return;
    }
    
    if (amountValue > maxAmount) {
        if (typeof window.showMessage === 'function') {
            window.showMessage('Số tiền hoàn không được vượt quá ' + maxAmount.toLocaleString('vi-VN') + ' đ', 'error');
        } else {
            alert('Số tiền hoàn không được vượt quá ' + maxAmount.toLocaleString('vi-VN') + ' đ');
        }
        amountInput.focus();
        return;
    }
    
    if (!reasonValue) {
        if (typeof window.showMessage === 'function') {
            window.showMessage('Vui lòng nhập lý do hoàn tiền', 'error');
        } else {
            alert('Vui lòng nhập lý do hoàn tiền');
        }
        reasonInput.focus();
        return;
    }
    
    if (reasonValue.length < 10) {
        if (typeof window.showMessage === 'function') {
            window.showMessage('Lý do hoàn tiền phải có ít nhất 10 ký tự', 'error');
        } else {
            alert('Lý do hoàn tiền phải có ít nhất 10 ký tự');
        }
        reasonInput.focus();
        return;
    }
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang gửi...';
    
    // Use FormData from form directly
    const formData = new FormData(form);
    
    // Submit form
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show Vietnamese success message
            const successMessage = data.message || 'Yêu cầu hoàn tiền đã được gửi thành công!';
            if (typeof window.showMessage === 'function') {
                window.showMessage(successMessage, 'success');
            } else {
                alert(successMessage);
            }
            
            // Close modal immediately
            closeRefundModal();
            
            // Reload page to show updated status
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra khi gửi yêu cầu hoàn tiền');
        }
    })
    .catch(error => {
        console.error('Refund request error:', error);
        if (typeof window.showMessage === 'function') {
            window.showMessage(error.message || 'Có lỗi xảy ra khi gửi yêu cầu hoàn tiền', 'error');
        } else {
            alert(error.message || 'Có lỗi xảy ra khi gửi yêu cầu hoàn tiền');
        }
        
        // Reset button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Modal close functionality is now handled by onclick attributes in HTML
</script>
@endsection
