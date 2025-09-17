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
                    <div class="text-indigo-700 font-extrabold text-base sm:text-lg">{{ number_format($order->grand_total, 0, ',', '.') }} đ</div>
                    <div class="text-xs text-gray-500">Tổng thanh toán</div>
                    @php
                        $canCancel = in_array($order->status, ['pending', 'confirmed']) && $order->payment_status !== 'completed';
                    @endphp
                    <form action="{{ route('user.orders.cancel', $order->id) }}" method="post" class="mt-2" title="{{ $canCancel ? 'Hủy đơn' : 'Không thể hủy ở trạng thái hiện tại' }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-500 text-white hover:bg-rose-600 disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed transition-colors duration-200" {{ $canCancel ? '' : 'disabled' }}>
                            <i class="fas fa-ban"></i> Hủy đơn
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @php
            $orderSteps = ['pending' => 'Đặt hàng', 'confirmed' => 'Xác nhận', 'shipping' => 'Vận chuyển', 'delivered' => 'Hoàn tất'];
            // Bỏ chữ "Đã" trong nhãn tiến trình
            $paySteps = ['pending' => 'Chờ thanh toán', 'processing' => 'Đang xử lý', 'completed' => 'Thanh toán'];
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
                $payIndex = array_search($order->payment_status, $payKeys);
                if ($payIndex === false) { $payIndex = 0; }
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
                @if($order->status === 'cancelled')
                <div class="mt-2 text-xs text-rose-600"><i class="fas fa-ban mr-1"></i> Đơn hàng đã bị hủy</div>
                @endif
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
                @if($order->status === 'cancelled')
                <div class="mt-2 text-xs text-rose-600"><i class="fas fa-ban mr-1"></i> Đơn hàng đã bị hủy</div>
                @elseif(in_array($order->payment_status, ['failed','cancelled']))
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
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div class="flex items-center justify-between sm:justify-start sm:gap-3">
                        <dt class="text-gray-500">Mã đơn</dt>
                        <dd class="font-medium text-gray-900">#{{ $order->order_number ?? $order->id }}</dd>
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
                        <dt class="text-gray-500">Phương thức</dt>
                        <dd class="font-medium text-gray-900">{{ $order->paymentMethod->name ?? '—' }}</dd>
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
                    @endif
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

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="px-4 sm:px-6 py-4 border-b flex items-center justify-between gap-2">
                    <h2 class="text-lg font-bold">Thông tin đơn hàng</h2>
                    <div class="text-sm text-gray-500">Sản phẩm ({{ $order->items->count() }})</div>
                </div>
                <div class="divide-y">
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
                                                    <span class="inline-block w-3 h-3 rounded-full border border-gray-200 bg-gray-200"></span>
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

            @if($order->note)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h3 class="text-base font-bold mb-2">Ghi chú</h3>
                <p class="text-sm text-gray-700">{{ $order->note }}</p>
            </div>
            @endif
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
                        <span class="text-gray-600">Thuế</span>
                        <span class="text-gray-900 font-medium">{{ number_format($order->tax_total ?? 0, 0, ',', '.') }} đ</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Vận chuyển</span>
                        <span class="text-gray-900 font-medium">{{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }} đ</span>
                    </div>
                    @if((float)($order->discount_total ?? 0) > 0)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Giảm giá</span>
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

            @if(($order->paymentMethod?->code === 'bank_transfer') || (session('payment_method') === 'bank_transfer'))
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h3 class="text-base font-bold mb-3">Hướng dẫn chuyển khoản</h3>
                <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="text-sm font-semibold text-blue-900 mb-2">Thông tin chuyển khoản</div>
                    <div class="space-y-1 text-sm text-blue-800">
                        <div><span class="font-medium">Ngân hàng:</span> Vietcombank - CN TP.HCM</div>
                        <div><span class="font-medium">Tên tài khoản:</span> CONG TY TNHH SHOWROOM</div>
                        <div><span class="font-medium">Số tài khoản:</span> <span class="font-mono">0123456789</span></div>
                        <div><span class="font-medium">Số tiền:</span> <span class="font-bold">{{ number_format($order->grand_total ?? $order->total_price, 0, ',', '.') }} đ</span></div>
                        <div><span class="font-medium">Nội dung:</span> <span class="font-mono">{{ $order->order_number ?? ('#'.$order->id) }}</span></div>
                    </div>
                    <div class="mt-2 text-xs text-blue-700">
                        <i class="fas fa-info-circle mr-1"></i>
                        Vui lòng chuyển khoản chính xác số tiền và nội dung để hệ thống đối soát tự động.
                    </div>
                </div>
            </div>
            @endif

            <!-- Refund Section -->
            @if($order->payment_status === 'completed' && $order->status !== 'cancelled')
                @php
                    $existingRefund = $order->refunds->whereIn('status', ['pending', 'processing'])->first();
                    $canRequestRefund = !$existingRefund && $order->created_at->diffInDays(now()) <= 30; // 30 days refund policy
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

            <!-- Payment Type Information -->
            @if(!$order->isInstallmentOrder())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-credit-card text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Thanh toán một lần</h3>
                            <p class="text-sm text-gray-600">Đơn hàng này được thanh toán toàn bộ một lần, không có lịch trả góp.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Installments Section -->
            @if($order->isInstallmentOrder() && $order->installments->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <h3 class="text-base font-bold mb-4">Lịch trả góp</h3>
                    <div class="space-y-3">
                        @foreach($order->installments as $installment)
                            <div class="flex items-center justify-between p-3 rounded-lg border
                                @if($installment->status === 'paid') border-green-200 bg-green-50
                                @elseif($installment->status === 'overdue') border-red-200 bg-red-50
                                @elseif($installment->due_date && $installment->due_date->isPast()) border-orange-200 bg-orange-50
                                @else border-gray-200 bg-gray-50
                                @endif">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-sm font-medium">Kỳ {{ $installment->installment_number }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                            @if($installment->status === 'paid') bg-green-100 text-green-800
                                            @elseif($installment->status === 'overdue') bg-red-100 text-red-800
                                            @elseif($installment->status === 'cancelled') bg-gray-100 text-gray-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            @switch($installment->status)
                                                @case('paid') Đã thanh toán @break
                                                @case('overdue') Quá hạn @break
                                                @case('cancelled') Đã hủy @break
                                                @default Chờ thanh toán
                                            @endswitch
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        @if($installment->due_date)
                                            Hạn thanh toán: {{ $installment->due_date->format('d/m/Y') }}
                                        @endif
                                        @if($installment->paid_at)
                                            • Đã thanh toán: {{ $installment->paid_at->format('d/m/Y') }}
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-gray-900">
                                        {{ number_format($installment->amount, 0, ',', '.') }} đ
                                    </div>
                                    @if($installment->status === 'pending' && $installment->due_date && $installment->due_date->isFuture())
                                        <button class="text-indigo-600 hover:text-indigo-700 text-sm font-medium mt-1">
                                            Thanh toán
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Installment Summary -->
                    @php
                        $totalInstallments = $order->installments->count();
                        $paidInstallments = $order->installments->where('status', 'paid')->count();
                        $totalPaid = $order->installments->where('status', 'paid')->sum('amount');
                        $totalRemaining = $order->installments->where('status', '!=', 'paid')->sum('amount');
                    @endphp
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Tiến độ:</span>
                                <span class="font-medium">{{ $paidInstallments }}/{{ $totalInstallments }} kỳ</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Đã thanh toán:</span>
                                <span class="font-medium text-green-600">{{ number_format($totalPaid, 0, ',', '.') }} đ</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Còn lại:</span>
                                <span class="font-medium text-orange-600">{{ number_format($totalRemaining, 0, ',', '.') }} đ</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Lãi suất:</span>
                                <span class="font-medium">{{ $order->financeOption->interest_rate ?? ($order->installments->first()->interest_rate ?? 0) }}%/năm</span>
                            </div>
                            @if($order->financeOption)
                            <div>
                                <span class="text-gray-600">Ngân hàng:</span>
                                <span class="font-medium">{{ $order->financeOption->bank_name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Thời hạn:</span>
                                <span class="font-medium">{{ $order->tenure_months ?? $totalInstallments }} tháng</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div id="refundModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Yêu cầu hoàn tiền</h3>
            <form action="{{ route('user.orders.refund', $order) }}" method="POST" id="refundForm">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Số tiền hoàn (VND)</label>
                        <input type="number" name="amount" max="{{ $order->grand_total }}" 
                               value="{{ $order->grand_total }}"
                               class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                        <p class="text-xs text-gray-500 mt-1">Tối đa: {{ number_format($order->grand_total, 0, ',', '.') }} đ</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lý do hoàn tiền</label>
                        <textarea name="reason" rows="4" required 
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
        
        // Use confirm dialog like orders index page
        showConfirmDialog(
            'Hủy đơn hàng?',
            `Bạn có chắc chắn muốn hủy đơn hàng ${orderNumber}? Hành động này không thể hoàn tác.`,
            'Hủy đơn',
            'Hủy bỏ',
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
}

function closeRefundModal() {
    document.getElementById('refundModal').classList.add('hidden');
}

// Handle refund form submission
document.getElementById('refundForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang gửi...';
    
    // Submit form
    fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            if (typeof window.showMessage === 'function') {
                window.showMessage(data.message, 'success');
            } else {
                alert(data.message);
            }
            
            // Close modal and reload page
            closeRefundModal();
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
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

// Close modal when clicking outside
document.getElementById('refundModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRefundModal();
    }
});
</script>
@endsection


