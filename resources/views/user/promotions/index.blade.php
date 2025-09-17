@extends('layouts.app')

@section('title', 'Khuyến mãi & Ưu đãi')

@section('content')
<div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
    <div class="flex items-center justify-between mb-4 sm:mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight">Khuyến mãi & Ưu đãi</h1>
            <p class="text-sm sm:text-base text-gray-500 mt-1">Tận dụng các mã giảm giá và ưu đãi hấp dẫn</p>
        </div>
        <div class="hidden sm:flex items-center gap-2">
            <a href="{{ route('user.promotions.my-promotions') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 text-sm font-semibold">
                <i class="fas fa-history"></i> Đã sử dụng
            </a>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Tên khuyến mãi, mã giảm giá..." 
                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Loại khuyến mãi</label>
                <select name="type" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Tất cả</option>
                    <option value="percentage" @selected(request('type') === 'percentage')>Giảm theo %</option>
                    <option value="fixed_amount" @selected(request('type') === 'fixed_amount')>Giảm cố định</option>
                    <option value="free_shipping" @selected(request('type') === 'free_shipping')>Miễn phí vận chuyển</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                    <i class="fas fa-search mr-2"></i>Tìm kiếm
                </button>
            </div>
        </div>
    </form>

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
                                    @case('percentage') Giảm {{ $promotion->discount_value }}% @break
                                    @case('fixed_amount') Giảm {{ number_format($promotion->discount_value, 0, ',', '.') }}đ @break
                                    @case('free_shipping') Miễn phí ship @break
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

                        <!-- Action Button -->
                        <div class="mt-6">
                            <a href="{{ route('user.promotions.show', $promotion) }}" 
                               class="block w-full text-center py-2 bg-white text-indigo-600 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $promotions->links() }}
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
