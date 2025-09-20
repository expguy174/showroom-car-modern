@extends('layouts.app')

@section('title', 'Khuyến mãi & Ưu đãi')

@section('content')
<div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-gray-900 mb-3">🎉 Khuyến mãi đặc biệt</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-4">Khám phá các ưu đãi hấp dẫn dành riêng cho bạn. Sao chép mã và sử dụng khi mua xe!</p>
        @if($promotions->total() > 0)
            <p class="text-sm text-gray-500">
                Có <span class="font-semibold text-orange-600">{{ $promotions->total() }}</span> mã khuyến mãi đang có sẵn
            </p>
        @endif
    </div>

    <!-- Search and Filter Section -->
    <div class="mb-10">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <form method="GET">
                <!-- Single Row Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">
                    <!-- Search Field -->
                    <div class="lg:col-span-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-search mr-2 text-orange-500"></i>Tìm kiếm khuyến mãi
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Nhập tên khuyến mãi, mã giảm giá..." 
                               class="w-full pl-4 pr-4 py-3 rounded-xl border border-gray-300 focus:border-orange-500 focus:ring-orange-500 text-gray-900 placeholder-gray-500">
                    </div>
                    
                    <!-- Filter Field -->
                    <div class="lg:col-span-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-filter mr-2 text-orange-500"></i>Loại khuyến mãi
                        </label>
                        <select name="type" 
                                class="w-full py-3 px-4 rounded-xl border border-gray-300 focus:border-orange-500 focus:ring-orange-500 focus:ring-2 text-gray-900">
                            <option value="">🏷️ Tất cả loại</option>
                            <option value="percentage" @selected(request('type') === 'percentage')>📊 Giảm theo %</option>
                            <option value="fixed_amount" @selected(request('type') === 'fixed_amount')>💰 Giảm cố định</option>
                            <option value="free_shipping" @selected(request('type') === 'free_shipping')>🚚 Miễn phí ship</option>
                            <option value="brand_specific" @selected(request('type') === 'brand_specific')>🚗 Theo thương hiệu</option>
                        </select>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="lg:col-span-3">
                        <div class="flex gap-2">
                            <button type="submit" 
                                    class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-orange-600 text-white rounded-xl font-semibold hover:bg-orange-700 transition-colors duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-search mr-2"></i>Tìm kiếm
                            </button>
                            @if(request()->hasAny(['search', 'type']))
                                <a href="{{ route('user.promotions.index') }}" 
                                   class="inline-flex items-center justify-center px-3 py-3 bg-gray-100 text-gray-600 rounded-xl font-medium hover:bg-gray-200 transition-colors duration-200">
                                    <i class="fas fa-refresh"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Active Filters Display -->
        @if(request()->hasAny(['search', 'type']))
            <div class="mt-4 flex flex-wrap items-center gap-2">
                <span class="text-sm text-gray-600">Bộ lọc đang áp dụng:</span>
                @if(request('search'))
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                        <i class="fas fa-search mr-1"></i>"{{ request('search') }}"
                    </span>
                @endif
                @if(request('type'))
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-filter mr-1"></i>
                        @switch(request('type'))
                            @case('percentage') Giảm theo % @break
                            @case('fixed_amount') Giảm cố định @break
                            @case('free_shipping') Miễn phí ship @break
                            @case('brand_specific') Theo thương hiệu @break
                            @default {{ request('type') }}
                        @endswitch
                    </span>
                @endif
            </div>
        @endif
    </div>

    <!-- Promotions Grid -->
    @if($promotions->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($promotions as $promotion)
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white relative overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-{{ $promotion->id }}" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                    <circle cx="10" cy="10" r="2" fill="currentColor"/>
                                </pattern>
                            </defs>
                            <rect width="100" height="100" fill="url(#pattern-{{ $promotion->id }})"/>
                        </svg>
                    </div>

                    <div class="relative z-10">
                        <!-- Type Badge -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-2 py-1 bg-white bg-opacity-20 rounded-full text-xs font-medium">
                                @switch($promotion->type)
                                    @case('percentage') 
                                        Giảm {{ $promotion->discount_value }}%
                                        @if($promotion->max_discount_amount)
                                            <span class="block text-[10px] opacity-75">Tối đa {{ number_format($promotion->max_discount_amount, 0, ',', '.') }}đ</span>
                                        @endif
                                        @break
                                    @case('fixed_amount') Giảm {{ number_format($promotion->discount_value, 0, ',', '.') }}đ @break
                                    @case('free_shipping') Miễn phí ship @break
                                    @case('brand_specific')
                                        Giảm {{ $promotion->discount_value }}%
                                        @if($promotion->max_discount_amount)
                                            <span class="block text-[10px] opacity-75">Tối đa {{ number_format($promotion->max_discount_amount, 0, ',', '.') }}đ</span>
                                        @endif
                                        @break
                                @endswitch
                            </span>
                            @if($promotion->usage_limit)
                                <span class="text-xs opacity-75">
                                    {{ $promotion->usage_limit - $promotion->usage_count }} lượt còn lại
                                </span>
                            @endif
                        </div>

                        <!-- Title -->
                        <h3 class="text-xl font-bold mb-2">{{ $promotion->name }}</h3>
                        
                        <!-- Description -->
                        @if($promotion->description)
                            <p class="text-sm opacity-90 mb-4 line-clamp-2">{{ $promotion->description }}</p>
                        @endif

                        <!-- Code -->
                        <div class="bg-white bg-opacity-20 rounded-lg p-3 mb-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs opacity-75 mb-1">Mã khuyến mãi</p>
                                    <p class="font-mono font-bold text-lg">{{ $promotion->code }}</p>
                                </div>
                                <button onclick="copyCode('{{ $promotion->code }}')" 
                                        class="p-2 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-colors">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Min Order & Expiry -->
                        <div class="space-y-2 text-sm opacity-90">
                            @if($promotion->min_order_amount)
                                <p><i class="fas fa-shopping-cart w-4"></i> Đơn tối thiểu: {{ number_format($promotion->min_order_amount, 0, ',', '.') }}đ</p>
                            @endif
                            @if($promotion->end_date)
                                <p><i class="fas fa-clock w-4"></i> Hết hạn: {{ $promotion->end_date->format('d/m/Y') }}</p>
                            @endif
                        </div>

                        <!-- Status Badge -->
                        <div class="mt-4 pt-4 border-t border-white border-opacity-20">
                            <div class="flex items-center justify-between text-xs">
                                <span class="px-2 py-1 bg-white bg-opacity-20 rounded-full">
                                    {{ $promotion->status_text }}
                                </span>
                                @if($promotion->usage_limit)
                                    <span class="opacity-75">
                                        Còn {{ $promotion->usage_limit - $promotion->usage_count }} lượt
                                    </span>
                                @else
                                    <span class="opacity-75">Không giới hạn</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($promotions->hasPages())
            <div class="mt-16 flex justify-center">
                <div class="flex items-center space-x-2">
                    @if($promotions->onFirstPage())
                        <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-left mr-2"></i>Trước
                        </span>
                    @else
                        <a href="{{ $promotions->appends(request()->query())->previousPageUrl() }}" class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-chevron-left mr-2"></i>Trước
                        </a>
                    @endif

                    @foreach($promotions->appends(request()->query())->getUrlRange(1, $promotions->lastPage()) as $page => $url)
                        @if($page == $promotions->currentPage())
                            <span class="px-4 py-2 bg-orange-600 text-white rounded-lg font-semibold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($promotions->hasMorePages())
                        <a href="{{ $promotions->appends(request()->query())->nextPageUrl() }}" class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            Sau<i class="fas fa-chevron-right ml-2"></i>
                        </a>
                    @else
                        <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            Sau<i class="fas fa-chevron-right ml-2"></i>
                        </span>
                    @endif
                </div>
            </div>
            
            <!-- Pagination Info -->
            <div class="mt-4 text-center text-sm text-gray-500">
                Hiển thị {{ $promotions->firstItem() }}-{{ $promotions->lastItem() }} 
                trong tổng số {{ $promotions->total() }} mã khuyến mãi
            </div>
        @endif

        <!-- How to use -->
        <div class="mt-12 bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl p-6 text-center">
            <h3 class="text-lg font-bold text-gray-900 mb-2">💡 Cách sử dụng mã khuyến mãi</h3>
            <p class="text-gray-600 mb-4">Nhấn vào nút <i class="fas fa-copy mx-1"></i> để sao chép mã, sau đó sử dụng khi thanh toán đơn hàng</p>
            <div class="flex flex-wrap justify-center gap-4 text-sm">
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <span>Áp dụng tự động khi thanh toán</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-clock text-blue-500"></i>
                    <span>Có thời hạn sử dụng</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-shopping-cart text-purple-500"></i>
                    <span>Có điều kiện đơn tối thiểu</span>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-tags text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Không có khuyến mãi nào</h3>
            <p class="text-gray-500 mb-6">Hiện tại chưa có chương trình khuyến mãi nào phù hợp với tìm kiếm của bạn.</p>
            <a href="{{ route('user.promotions.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 font-medium">
                <i class="fas fa-refresh"></i> Xem tất cả
            </a>
        </div>
    @endif
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-y-full opacity-0 transition-all duration-300 z-50">
    <div class="flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        <span id="toast-message">Đã sao chép mã khuyến mãi!</span>
    </div>
</div>

<script>
function copyCode(code) {
    navigator.clipboard.writeText(code).then(function() {
        showToast('Đã sao chép mã: ' + code);
    }).catch(function() {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = code;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showToast('Đã sao chép mã: ' + code);
    });
}

function showToast(message) {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');
    
    toastMessage.textContent = message;
    toast.classList.remove('translate-y-full', 'opacity-0');
    toast.classList.add('translate-y-0', 'opacity-100');
    
    setTimeout(() => {
        toast.classList.add('translate-y-full', 'opacity-0');
        toast.classList.remove('translate-y-0', 'opacity-100');
    }, 3000);
}
</script>
@endsection
