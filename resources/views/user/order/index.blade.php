@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Đơn hàng của tôi</h1>
                        <p class="text-gray-600">Quản lý và theo dõi đơn hàng</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('user.customer-profiles.index') }}" class="flex items-center text-blue-600 hover:text-blue-700 font-medium">
                        <i class="fas fa-user mr-2"></i>
                        Hồ sơ của tôi
                    </a>
                    <a href="{{ route('home') }}" class="flex items-center text-blue-600 hover:text-blue-700 font-medium">
                        <i class="fas fa-home mr-2"></i>
                        Trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white border-b border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <div class="relative flex-1 sm:w-64">
                        <input type="text" placeholder="Tìm kiếm theo mã đơn, tên sản phẩm..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending">Chờ xử lý</option>
                        <option value="confirmed">Đã xác nhận</option>
                        <option value="shipped">Đang vận chuyển</option>
                        <option value="delivered">Đã giao</option>
                        <option value="cancelled">Đã hủy</option>
                    </select>
                    <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Sắp xếp theo</option>
                        <option value="newest">Mới nhất</option>
                        <option value="oldest">Cũ nhất</option>
                        <option value="highest">Giá cao nhất</option>
                        <option value="lowest">Giá thấp nhất</option>
                    </select>
                </div>
            </div>
        </div>
  </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

  @if($orders->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full mx-auto mb-8 flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-blue-500 text-5xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-4">Chưa có đơn hàng nào</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto text-lg">
                    Bạn chưa có đơn hàng nào. Hãy khám phá các sản phẩm tuyệt vời của chúng tôi!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-car mr-3"></i>
                        Xem xe hơi
                    </a>
                    <a href="{{ route('accessories.index') }}" 
                       class="inline-flex items-center bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-emerald-700 hover:to-teal-700 transition duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-tools mr-3"></i>
                        Xem phụ kiện
                    </a>
                </div>
            </div>
  @else
            <!-- Orders List -->
            <div class="space-y-6">
      @foreach($orders as $order)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <!-- Order Header -->
                        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
          <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                        <i class="fas fa-receipt"></i>
                                    </div>
                                    <div>
                                        <div class="text-lg font-semibold text-gray-900">
                                            #{{ $order->order_number ?? $order->id }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
          </div>
                                <div class="flex items-center gap-3">
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
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $statusColor }}-50 text-{{ $statusColor }}-700">
                                        <i class="fas fa-box mr-1"></i>
                                        {{ $order->status_display ?? 'Chưa xác định' }}
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $paymentColor }}-50 text-{{ $paymentColor }}-700">
                                        <i class="fas fa-credit-card mr-1"></i>
                                        {{ $order->payment_status_display ?? 'Chưa xác định' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items Preview -->
                        <div class="px-6 py-4">
                            <div class="space-y-3">
                                @foreach($order->items->take(2) as $item)
                                    @php
                                        $model = $item->item;
                                        $img = null;
                                        if ($item->item_type === 'car_variant' && $model?->images?->isNotEmpty()) {
                                            $f = $model->images->first();
                                            $img = $f->image_url ?: ($f->image_path ? asset('storage/'.$f->image_path) : null);
                                        } elseif ($item->item_type === 'accessory') {
                                            $img = $model?->image_url ? (filter_var($model->image_url, FILTER_VALIDATE_URL) ? $model->image_url : asset('storage/'.$model->image_url)) : null;
                                        }
            @endphp
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-14 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                                            @if($img)
                                                <img src="{{ $img }}" class="w-full h-full object-cover" alt="{{ $model?->name ?? $item->item_name }}" />
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <i class="fas fa-image"></i>
                                                </div>
            @endif
          </div>
                  <div class="min-w-0 flex-1">
                                            <div class="font-medium text-gray-900">{{ $model?->name ?? $item->item_name }}</div>
                                            <div class="text-sm text-gray-500">
                                                Số lượng: {{ $item->quantity }}
                                                @if($item->color)
                                                    • Màu: {{ $item->color->color_name }}
                                                @endif
                  </div>
                  </div>
                  <div class="text-right">
                                            <div class="font-semibold text-gray-900">{{ number_format($item->price) }} đ</div>
                                            <div class="text-sm text-gray-500">x{{ $item->quantity }}</div>
                  </div>
                </div>
              @endforeach
                                @if($order->items->count() > 2)
                                    <div class="text-center py-2">
                                        <span class="text-sm text-gray-500">
                                            <i class="fas fa-ellipsis-h mr-1"></i>
                                            và {{ $order->items->count() - 2 }} sản phẩm khác
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Order Summary & Actions -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-600">
                                    @php
                                        $ship = $order->shippingAddress; $bill = $order->billingAddress;
                                        $shipText = $ship ? $ship->line1 : ($bill->line1 ?? '');
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                                        <span>{{ Str::limit($shipText, 40) }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="text-right">
                                        <div class="text-sm text-gray-500">Tổng cộng</div>
                                        <div class="text-xl font-bold text-gray-900">{{ number_format($order->grand_total ?? $order->total_price) }} đ</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('user.customer-profiles.show-order', $order->id) }}" 
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                            <i class="fas fa-eye"></i>
                                            Chi tiết
                                        </a>
                                        @if(in_array($order->status, ['pending', 'confirmed']))
                                            <button class="inline-flex items-center gap-2 px-4 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition-colors text-sm font-medium">
                                                <i class="fas fa-times"></i>
                                                Hủy đơn
                                            </button>
                                        @endif
            </div>
          </div>
          </div>
          </div>
        </div>
      @endforeach
    </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="mt-8 flex items-center justify-center">
                    <nav class="bg-white rounded-2xl shadow-sm border border-gray-200 px-6 py-4">
                        <div class="flex items-center gap-2">
                            <!-- Previous -->
                            @if($orders->onFirstPage())
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg text-gray-400 cursor-not-allowed">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            @else
                                <a href="{{ $orders->previousPageUrl() }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
        @endif

                            <!-- Page Numbers -->
                            @foreach($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                                @if($page == $orders->currentPage())
                                    <span class="inline-flex items-center justify-center min-w-[40px] h-10 px-3 rounded-lg bg-blue-600 text-white font-semibold">{{ $page }}</span>
          @else
                                    <a href="{{ $url }}" class="inline-flex items-center justify-center min-w-[40px] h-10 px-3 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">{{ $page }}</a>
          @endif
                            @endforeach

                            <!-- Next -->
                            @if($orders->hasMorePages())
                                <a href="{{ $orders->nextPageUrl() }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg text-gray-400 cursor-not-allowed">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
          @endif
                        </div>
    </nav>
                </div>
    @endif
  @endif
    </div>
</div>
@endsection


