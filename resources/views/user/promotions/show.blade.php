@extends('layouts.app')

@section('title', $promotion->name . ' - Chi tiết khuyến mãi')

@section('content')
<div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('user.promotions.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                    <i class="fas fa-tags mr-2"></i>Khuyến mãi
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">{{ $promotion->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Hero Section -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-8 text-white mb-8 relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="hero-pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                <circle cx="10" cy="10" r="2" fill="currentColor"/>
                            </pattern>
                        </defs>
                        <rect width="100" height="100" fill="url(#hero-pattern)"/>
                    </svg>
                </div>

                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm font-medium mb-4 inline-block">
                                @switch($promotion->type)
                                    @case('percentage') Giảm {{ $promotion->discount_value }}% @break
                                    @case('fixed_amount') Giảm {{ number_format($promotion->discount_value, 0, ',', '.') }}đ @break
                                    @case('free_shipping') Miễn phí vận chuyển @break
                                @endswitch
                            </span>
                            <h1 class="text-3xl font-bold mb-2">{{ $promotion->name }}</h1>
                        </div>
                        @if($promotion->usage_limit)
                            <div class="text-right">
                                <p class="text-sm opacity-75">Còn lại</p>
                                <p class="text-2xl font-bold">{{ $promotion->usage_limit - $promotion->usage_count }}</p>
                                <p class="text-sm opacity-75">lượt sử dụng</p>
                            </div>
                        @endif
                    </div>

                    @if($promotion->description)
                        <p class="text-lg opacity-90 mb-6">{{ $promotion->description }}</p>
                    @endif

                    <!-- Promotion Code -->
                    <div class="bg-white bg-opacity-20 rounded-xl p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm opacity-75 mb-2">Mã khuyến mãi</p>
                                <p class="font-mono font-bold text-2xl">{{ $promotion->code }}</p>
                            </div>
                            <button onclick="copyCode('{{ $promotion->code }}')" 
                                    class="px-6 py-3 bg-white text-indigo-600 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                                <i class="fas fa-copy mr-2"></i>Sao chép
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms & Conditions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Điều kiện & Quy định</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-shopping-cart text-indigo-600 mt-1"></i>
                            <div>
                                <h3 class="font-medium text-gray-900">Giá trị đơn hàng tối thiểu</h3>
                                <p class="text-gray-600">
                                    @if($promotion->min_order_amount)
                                        {{ number_format($promotion->min_order_amount, 0, ',', '.') }} VND
                                    @else
                                        Không yêu cầu
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <i class="fas fa-users text-indigo-600 mt-1"></i>
                            <div>
                                <h3 class="font-medium text-gray-900">Giới hạn sử dụng</h3>
                                <p class="text-gray-600">
                                    @if($promotion->usage_limit)
                                        {{ $promotion->usage_limit }} lượt (còn {{ $promotion->usage_limit - $promotion->usage_count }})
                                    @else
                                        Không giới hạn
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-calendar-alt text-indigo-600 mt-1"></i>
                            <div>
                                <h3 class="font-medium text-gray-900">Thời gian áp dụng</h3>
                                <p class="text-gray-600">
                                    @if($promotion->start_date)
                                        Từ {{ $promotion->start_date->format('d/m/Y') }}
                                    @endif
                                    @if($promotion->end_date)
                                        đến {{ $promotion->end_date->format('d/m/Y') }}
                                    @else
                                        (không giới hạn)
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if(Auth::check())
                        <div class="flex items-start gap-3">
                            <i class="fas fa-history text-indigo-600 mt-1"></i>
                            <div>
                                <h3 class="font-medium text-gray-900">Bạn đã sử dụng</h3>
                                <p class="text-gray-600">{{ $userUsageCount }} lần</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-yellow-600 mt-1"></i>
                        <div>
                            <h3 class="font-medium text-yellow-800 mb-2">Lưu ý quan trọng</h3>
                            <ul class="text-sm text-yellow-700 space-y-1">
                                <li>• Mã khuyến mãi chỉ áp dụng cho một đơn hàng</li>
                                <li>• Không áp dụng đồng thời với các chương trình khuyến mãi khác</li>
                                <li>• Không hoàn tiền phần giảm giá khi hủy đơn hàng</li>
                                <li>• Mã khuyến mãi không thể chuyển nhượng</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- How to Use -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Cách sử dụng</h2>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm">1</div>
                        <div>
                            <h3 class="font-medium text-gray-900">Thêm sản phẩm vào giỏ hàng</h3>
                            <p class="text-gray-600">Chọn các sản phẩm bạn muốn mua và thêm vào giỏ hàng</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm">2</div>
                        <div>
                            <h3 class="font-medium text-gray-900">Tiến hành thanh toán</h3>
                            <p class="text-gray-600">Vào giỏ hàng và nhấn "Thanh toán" để chuyển đến trang checkout</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm">3</div>
                        <div>
                            <h3 class="font-medium text-gray-900">Nhập mã khuyến mãi</h3>
                            <p class="text-gray-600">Nhập mã <span class="font-mono font-bold">{{ $promotion->code }}</span> vào ô "Mã khuyến mãi" và nhấn "Áp dụng"</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm">4</div>
                        <div>
                            <h3 class="font-medium text-gray-900">Hoàn tất đơn hàng</h3>
                            <p class="text-gray-600">Kiểm tra lại thông tin và hoàn tất thanh toán để nhận ưu đãi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Thao tác nhanh</h3>
                <div class="space-y-3">
                    <button onclick="copyCode('{{ $promotion->code }}')" 
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                        <i class="fas fa-copy"></i> Sao chép mã
                    </button>
                    
                    <a href="{{ route('products.index') }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                        <i class="fas fa-shopping-bag"></i> Mua sắm ngay
                    </a>

                    <a href="{{ route('user.promotions.index') }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-medium">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>

            <!-- Promotion Calculator -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Tính toán ưu đãi</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Giá trị đơn hàng (VND)</label>
                        <input type="number" id="orderValue" placeholder="Nhập giá trị đơn hàng..." 
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <button onclick="calculateDiscount()" 
                            class="w-full px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 font-medium">
                        <i class="fas fa-calculator mr-2"></i>Tính toán
                    </button>
                    <div id="calculationResult" class="hidden p-4 bg-green-50 rounded-lg border border-green-200">
                        <p class="text-sm text-green-800 mb-2">Kết quả tính toán:</p>
                        <p class="font-bold text-green-900" id="discountAmount"></p>
                        <p class="text-sm text-green-700" id="finalAmount"></p>
                    </div>
                </div>
            </div>

            <!-- Share -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Chia sẻ</h3>
                <div class="flex gap-2">
                    <button onclick="sharePromotion('facebook')" 
                            class="flex-1 p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fab fa-facebook-f"></i>
                    </button>
                    <button onclick="sharePromotion('twitter')" 
                            class="flex-1 p-2 bg-blue-400 text-white rounded-lg hover:bg-blue-500">
                        <i class="fab fa-twitter"></i>
                    </button>
                    <button onclick="sharePromotion('copy')" 
                            class="flex-1 p-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        <i class="fas fa-link"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-y-full opacity-0 transition-all duration-300 z-50">
    <div class="flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        <span id="toast-message">Thông báo</span>
    </div>
</div>

<script>
const promotion = @json($promotion);

function copyCode(code) {
    navigator.clipboard.writeText(code).then(function() {
        showToast('Đã sao chép mã: ' + code);
    }).catch(function() {
        showToast('Đã sao chép mã: ' + code);
    });
}

function calculateDiscount() {
    const orderValue = parseFloat(document.getElementById('orderValue').value);
    
    if (!orderValue || orderValue <= 0) {
        showToast('Vui lòng nhập giá trị đơn hàng hợp lệ');
        return;
    }

    if (promotion.min_order_amount && orderValue < promotion.min_order_amount) {
        showToast('Đơn hàng chưa đạt giá trị tối thiểu');
        return;
    }

    let discountAmount = 0;
    
    switch (promotion.type) {
        case 'percentage':
            discountAmount = orderValue * (promotion.discount_value / 100);
            break;
        case 'fixed_amount':
            discountAmount = Math.min(promotion.discount_value, orderValue);
            break;
        case 'free_shipping':
            discountAmount = 50000; // Assuming shipping cost
            break;
    }

    const finalAmount = orderValue - discountAmount;
    
    document.getElementById('discountAmount').textContent = 
        'Giảm: ' + new Intl.NumberFormat('vi-VN').format(discountAmount) + ' VND';
    document.getElementById('finalAmount').textContent = 
        'Thành tiền: ' + new Intl.NumberFormat('vi-VN').format(finalAmount) + ' VND';
    document.getElementById('calculationResult').classList.remove('hidden');
}

function sharePromotion(platform) {
    const url = window.location.href;
    const text = `Khuyến mãi ${promotion.name} - Mã: ${promotion.code}`;
    
    switch (platform) {
        case 'facebook':
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`);
            break;
        case 'twitter':
            window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`);
            break;
        case 'copy':
            navigator.clipboard.writeText(url).then(() => {
                showToast('Đã sao chép link chia sẻ');
            });
            break;
    }
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
