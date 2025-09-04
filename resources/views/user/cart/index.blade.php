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
                    <a href="{{ route('products.index', ['type' => 'car']) }}" 
                       class="inline-flex items-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-car mr-3"></i>
                        Xem xe hơi
                    </a>
                    <a href="{{ route('products.index', ['type' => 'accessory']) }}" 
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
                                    <h2 class="text-xl font-bold text-gray-900">Xe hơi</h2>
                                </div>
                            </div>
                        </div>
                        <div class="p-0">
                            <div class="overflow-x-auto">
                                <table id="car-table" class="cart-table centered min-w-full">
                                    <thead>
                                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                                            <th class="px-6 py-3 whitespace-nowrap">Ảnh</th>
                                            <th class="px-6 py-3 whitespace-nowrap">Thông tin</th>
                                            <th class="px-6 py-3 whitespace-nowrap">Số lượng</th>
                                            <th class="px-6 py-3 whitespace-nowrap">Giá</th>
                                            <th class="px-6 py-3 whitespace-nowrap">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($carItems as $item)
                                            @include('user.cart.partials.car-item', ['item' => $item])
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
                                    <h2 class="text-xl font-bold text-gray-900">Phụ kiện</h2>
                                </div>
                            </div>
                                        </div>
                        <div class="p-0">
                            <div class="overflow-x-auto">
                                <table id="accessory-table" class="cart-table centered min-w-full">
                                    <thead>
                                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                                            <th class="px-6 py-3 whitespace-nowrap">Ảnh</th>
                                            <th class="px-6 py-3 whitespace-nowrap">Thông tin</th>
                                            <th class="px-6 py-3 whitespace-nowrap">Số lượng</th>
                                            <th class="px-6 py-3 whitespace-nowrap">Giá</th>
                                            <th class="px-6 py-3 whitespace-nowrap">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($accessoryItems as $item)
                                            @include('user.cart.partials.accessory-item', ['item' => $item])
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
                                                    : ($ci->item->current_price ?? 0); 
                                                return $u * $ci->quantity; 
                                            }), 0, ',', '.') }}</span> đ
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
                                                    : ($ci->item->current_price ?? 0); 
                                                return $u * $ci->quantity * 0.1; 
                                            }), 0, ',', '.') }}</span> đ
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
                                                    : ($ci->item->current_price ?? 0); 
                                                return $u * $ci->quantity * 1.1; 
                                            }), 0, ',', '.') }}</span> đ
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
<script>
 document.addEventListener('DOMContentLoaded', function() {
    // Initialize color displays
   document.querySelectorAll('[data-bg-hex]').forEach(function(el){
     const hex = el.getAttribute('data-bg-hex');
        if (hex) { 
            el.style.backgroundColor = hex; 
        }
   });
   
   // Color display initialization only - price updates handled by cart-fast.js
   
   // Function to update cart totals
   window.updateCartTotals = function() {
     let subtotal = 0;
     let tax = 0;
     let cartTotal = 0;
     
     // Calculate from all cart items
     document.querySelectorAll('.cart-item-row').forEach(function(row) {
       const totalEl = document.querySelector('.item-total[data-id="' + row.dataset.id + '"]');
       if (totalEl) {
         const total = parseInt(totalEl.textContent.replace(/[^\d]/g, ''));
         if (!isNaN(total)) subtotal += total;
       }
     });
     
     tax = Math.round(subtotal * 0.1);
     cartTotal = subtotal + tax;
     
     const nf = new Intl.NumberFormat('vi-VN');
     
     // Update summary display
     const subtotalEl = document.getElementById('subtotal');
     const taxEl = document.getElementById('tax');
     const cartTotalEl = document.getElementById('cart-total');
     
     if (subtotalEl) subtotalEl.textContent = nf.format(subtotal);
     if (taxEl) taxEl.textContent = nf.format(tax);
     if (cartTotalEl) cartTotalEl.textContent = nf.format(cartTotal);
   }
 });
</script>
<script>
(function(){
  // Wait for DOM to be ready
  document.addEventListener('DOMContentLoaded', function() {
    console.log('Cart page JavaScript loaded');
  });
  
  document.addEventListener('click', async function(e){
    const btn = e.target.closest('.js-duplicate-line');
    if (!btn) return;
    e.preventDefault();
    
    console.log('Duplicate button clicked', btn);
    
    try {
      const itemType = btn.getAttribute('data-item-type') || 'car_variant';
      const variantId = btn.getAttribute('data-variant-id');
      const addUrl = btn.getAttribute('data-add-url');
      
      console.log('Item type:', itemType, 'Variant ID:', variantId, 'Add URL:', addUrl);
      
      if (!variantId) {
        console.error('No variant ID found');
        return;
      }
      
      // Force create new line by using a unique options_signature
      // This ensures we always create a new line, never update existing
      const timestamp = Date.now();
      const random = Math.random().toString(36).substr(2, 9);
      const uniqueSignature = `duplicate_${timestamp}_${random}`;
      
      console.log('Creating unique signature:', uniqueSignature);
      
      const fd = new FormData();
      fd.append('item_type', itemType);
      fd.append('item_id', variantId);
      fd.append('quantity', '1');
      fd.append('options_signature', uniqueSignature);
      // No color_id to allow user to choose later
      // This will always create a new line because options_signature is unique
      
      console.log('Sending request to:', addUrl);
      
      const res = await fetch(addUrl, { 
        method: 'POST', 
        body: fd, 
        headers: { 
          'X-Requested-With':'XMLHttpRequest', 
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
        }
      });
      
      console.log('Response status:', res.status);
      const data = await res.json();
      console.log('Response data:', data);
      
      if (data && data.success) {
        console.log('Success! Adding new line to cart...');
        
        // Show success message
        if (typeof showMessage === 'function') {
          showMessage('Đã thêm cấu hình mới vào giỏ hàng!', 'success');
        }
        
        // Update cart count if available
        if (data.cart_count !== undefined && typeof window.updateCartCount === 'function') {
          window.updateCartCount(data.cart_count);
        }
        
        // Add new line to cart immediately without page refresh
        if (data.cart_item_html) {
          // Add HTML directly to DOM
          addNewCartItemHTML(data.cart_item_html);
        } else {
          // Fallback: refresh cart if no item data
          if (typeof window.fetchAllCartFromServer === 'function') {
            window.fetchAllCartFromServer();
          } else {
            // Final fallback: reload page
            window.location.reload();
          }
        }
      } else {
        console.error('Failed to add:', data);
        if (typeof showMessage === 'function') {
          showMessage((data && data.message) || 'Không thể thêm cấu hình mới', 'error');
        }
      }
    } catch (error) {
      console.error('Error:', error);
      if (typeof showMessage === 'function') {
        showMessage('Không thể thêm cấu hình mới', 'error');
      }
    }
  });
  
  // Function to add new cart item HTML directly from server
  function addNewCartItemHTML(html) {
    // Find the car table tbody (since we're adding car variants)
    const carTableBody = document.querySelector('#car-table tbody');
    
    if (!carTableBody) {
      console.error('Car table tbody not found');
      return;
    }
    
    // Add HTML directly to the end of the car items list
    try {
      carTableBody.insertAdjacentHTML('beforeend', html);
    } catch (error) {
      console.error('Error inserting HTML:', error);
      return;
    }
    
    // Initialize color displays for the new item
    const newItem = carTableBody.lastElementChild;
    
    if (newItem) {
      // Initialize color displays for the new item
      const colorOptions = newItem.querySelectorAll('.color-option[data-bg-hex]');
      colorOptions.forEach(function(el) {
        const hex = el.getAttribute('data-bg-hex');
        if (hex) {
          el.style.backgroundColor = hex;
        }
      });
      
      // Update cart totals
      if (typeof window.updateCartTotals === 'function') {
        window.updateCartTotals();
      }
    } else {
      console.error('Failed to find new item in DOM after insertion');
    }
  }
  
  
  

})();
</script>
@endpush