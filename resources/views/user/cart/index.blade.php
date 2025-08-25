@extends('layouts.app')

@section('title', 'Giỏ hàng - Showroom Ô tô')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Giỏ hàng</h1>
                        <p class="text-gray-600">Quản lý sản phẩm đã chọn</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('products.index') }}" class="flex items-center text-blue-600 hover:text-blue-700 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>
        </div>

    <!-- Progress Steps -->
    <div class="bg-white border-b border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="max-w-4xl mx-auto">
                <div class="flex items-center justify-center space-x-8">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold">1</div>
                        <span class="font-semibold text-blue-600">Giỏ hàng</span>
                    </div>
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-semibold">2</div>
                        <span class="font-medium text-gray-500">Thanh toán</span>
                    </div>
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-semibold">3</div>
                        <span class="font-medium text-gray-500">Hoàn tất</span>
                    </div>
                </div>
            </div>
        </div>
        </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <span class="text-green-800 font-medium">{{ session('success') }}</span>
            </div>
        @endif
        
        @if($cartItems->isEmpty())
            <!-- Empty Cart State -->
            <div class="text-center py-16">
                <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full mx-auto mb-8 flex items-center justify-center">
                    <i class="fas fa-car text-blue-500 text-5xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-4">Giỏ hàng trống</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto text-lg">
                    Bạn chưa có sản phẩm nào trong giỏ hàng. Hãy khám phá các xe hơi và phụ kiện chất lượng cao!
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
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Main Cart Items -->
                <div class="lg:col-span-8 xl:col-span-9 space-y-6">
                    <!-- Product Categories -->
                    @php
                        $carItems = $cartItems->where('item_type', 'car_variant');
                        $accessoryItems = $cartItems->where('item_type', 'accessory');
                    @endphp

                    @if($carItems->count() > 0)
                    <!-- Car Items Section -->
                    <div id="car-section" class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-car text-gray-700 text-xl"></i>
                                    <h2 class="text-xl font-bold text-gray-900">Xe hơi <span id="car-count-wrap">(<span id="car-count">{{ $carItems->count() }}</span>)</span></h2>
                                </div>
                            </div>
                        </div>
                        <div class="p-0">
                            <div class="overflow-x-auto">
                                <table id="car-table" class="cart-table centered min-w-full">
                                    <thead>
                                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                                            <th class="px-6 py-3">Ảnh</th>
                                            <th class="px-6 py-3">Thông tin</th>
                                            <th class="px-6 py-3">Số lượng</th>
                                            <th class="px-6 py-3">Giá</th>
                                            <th class="px-6 py-3">Tổng</th>
                                            <th class="px-6 py-3">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($carItems as $item)
                                            @include('cart.partials.car-item', ['item' => $item])
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                                                </div>
                                            </div>
                                            @endif

                    @if($accessoryItems->count() > 0)
                    <!-- Accessory Items Section -->
                    <div id="accessory-section" class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-tools text-gray-700 text-xl"></i>
                                    <h2 class="text-xl font-bold text-gray-900">Phụ kiện <span id="accessory-count-wrap">(<span id="accessory-count">{{ $accessoryItems->count() }}</span>)</span></h2>
                                </div>
                            </div>
                                        </div>
                        <div class="p-0">
                            <div class="overflow-x-auto">
                                <table id="accessory-table" class="cart-table centered min-w-full">
                                    <thead>
                                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                                            <th class="px-6 py-3">Ảnh</th>
                                            <th class="px-6 py-3">Thông tin</th>
                                            <th class="px-6 py-3">Số lượng</th>
                                            <th class="px-6 py-3">Giá</th>
                                            <th class="px-6 py-3">Tổng</th>
                                            <th class="px-6 py-3">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($accessoryItems as $item)
                                            @include('cart.partials.accessory-item', ['item' => $item])
                                                @endforeach
                                    </tbody>
                                </table>
                            </div>
                                            </div>
                                        </div>
                                        @endif

                    <!-- Clear Cart Button -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <button type="button" id="clear-cart-btn" 
                                class="w-full bg-red-50 hover:bg-red-100 text-red-600 hover:text-red-700 px-6 py-4 rounded-xl font-semibold transition duration-300 flex items-center justify-center space-x-3" 
                                data-url="{{ route('user.cart.clear') }}" data-csrf="{{ csrf_token() }}">
                            <i class="fas fa-trash"></i>
                            <span>Xóa toàn bộ giỏ hàng</span>
                        </button>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-4 xl:col-span-3">
                    <div class="sticky top-8">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-100">
                                <h3 class="text-xl font-bold text-gray-900">Tóm tắt đơn hàng</h3>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Summary Items -->
                                <div class="space-y-3">
                                    <div class="flex justify-between text-gray-600">
                                        <span>Tạm tính</span>
                                        <span class="font-semibold text-gray-900">
                                            <span id="subtotal">{{ number_format($cartItems->sum(function($ci){ 
                                                $u = ($ci->item_type==='car_variant' && method_exists($ci->item,'getPriceWithColorAdjustment')) 
                                                    ? $ci->item->getPriceWithColorAdjustment($ci->color_id) 
                                                    : ($ci->item->price ?? 0); 
                                                return $u * $ci->quantity; 
                                            })) }}</span> đ
                                        </span>
                                    </div>
                                    <div class="flex justify-between text-gray-600">
                                        <span>Phí vận chuyển</span>
                                        <span class="text-green-600 font-semibold">Miễn phí</span>
                                    </div>
                                    <div class="flex justify-between text-gray-600">
                                        <span>Thuế VAT</span>
                                        <span class="font-semibold text-gray-900">
                                            <span id="tax">{{ number_format($cartItems->sum(function($ci){ 
                                                $u = ($ci->item_type==='car_variant' && method_exists($ci->item,'getPriceWithColorAdjustment')) 
                                                    ? $ci->item->getPriceWithColorAdjustment($ci->color_id) 
                                                    : ($ci->item->price ?? 0); 
                                                return $u * $ci->quantity * 0.1; 
                                            })) }}</span> đ
                                        </span>
                                    </div>
                                </div>

                                <!-- Total -->
                                <div class="border-t border-gray-200 pt-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-xl font-bold text-gray-900">Tổng:</span>
                                        <span class="text-2xl font-bold text-blue-600">
                                            <span id="cart-total">{{ number_format($cartItems->sum(function($ci){ 
                                                $u = ($ci->item_type==='car_variant' && method_exists($ci->item,'getPriceWithColorAdjustment')) 
                                                    ? $ci->item->getPriceWithColorAdjustment($ci->color_id) 
                                                    : ($ci->item->price ?? 0); 
                                                return $u * $ci->quantity * 1.1; 
                                            })) }}</span> đ
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">Đã bao gồm VAT</p>
                                </div>

                                <!-- Checkout Button -->
                                <form action="{{ route('user.cart.checkout.form') }}" method="GET">
                                    <button type="submit" 
                                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-4 rounded-xl font-semibold transition duration-300 transform hover:scale-105 shadow-lg text-lg flex items-center justify-center space-x-3">
                                        <i class="fas fa-credit-card"></i>
                                        <span>Tiến hành thanh toán</span>
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
        @endif
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/cart.css') }}">
<style>
    .cart-item-card {
        transition: all 0.3s ease;
    }
    
    .cart-item-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .color-option {
        transition: all 0.2s ease;
    }
    
    .color-option:hover {
        transform: scale(1.1);
    }
    
    .quantity-control {
        transition: all 0.2s ease;
    }
    
    .quantity-control:hover {
        background-color: #f3f4f6;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/cart-manager.js') }}"></script>
<script>
 document.addEventListener('DOMContentLoaded', function() {
    // Initialize color displays
   document.querySelectorAll('[data-bg-hex]').forEach(function(el){
     const hex = el.getAttribute('data-bg-hex');
        if (hex) { 
            el.style.backgroundColor = hex; 
        }
   });
    
    // CartManager auto-initializes inside cart-manager.js. No manual init needed here.
 });
</script>
@endpush