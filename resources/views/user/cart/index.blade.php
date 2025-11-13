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
            <div>
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
        {{-- Removed inline alert boxes; rely on global toast system --}}
        <!-- Base empty state template for instant client-side rendering -->
        <template id="cart-empty-template">
            <div class="text-center py-16">
                <div class="w-32 h-32 bg-gradient-to-br from-indigo-100 to-slate-100 rounded-2xl mx-auto mb-8 flex items-center justify-center shadow-sm">
                    <i class="fas fa-box-open text-indigo-500 text-5xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-3">Giỏ hàng của bạn đang trống</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto text-base sm:text-lg">
                    Hãy khám phá các mẫu xe và phụ kiện để thêm vào giỏ hàng.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('products.index', ['type' => 'car']) }}" 
                       class="inline-flex items-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 sm:px-8 py-3.5 sm:py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition duration-300 transform hover:scale-[1.02] shadow-lg">
                        <i class="fas fa-car-side mr-2.5"></i>
                        <span>Khám phá xe hơi</span>
                    </a>
                    <a href="{{ route('products.index', ['type' => 'accessory']) }}" 
                       class="inline-flex items-center bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-6 sm:px-8 py-3.5 sm:py-4 rounded-xl font-semibold hover:from-emerald-700 hover:to-teal-700 transition duration-300 transform hover:scale-[1.02] shadow-lg">
                        <i class="fas fa-screwdriver-wrench mr-2.5"></i>
                        <span>Khám phá phụ kiện</span>
                    </a>
                </div>
            </div>
        </template>
        
        @if($cartItems->isEmpty())
            <!-- Empty Cart State -->
            <div class="text-center py-16">
                <div class="w-32 h-32 bg-gradient-to-br from-indigo-100 to-slate-100 rounded-2xl mx-auto mb-8 flex items-center justify-center shadow-sm">
                    <i class="fas fa-box-open text-indigo-500 text-5xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-3">Giỏ hàng của bạn đang trống</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto text-base sm:text-lg">
                    Hãy khám phá các mẫu xe và phụ kiện để thêm vào giỏ hàng.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('products.index', ['type' => 'car']) }}" 
                       class="inline-flex items-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 sm:px-8 py-3.5 sm:py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition duration-300 transform hover:scale-[1.02] shadow-lg">
                        <i class="fas fa-car-side mr-2.5"></i>
                        <span>Khám phá xe hơi</span>
                    </a>
                    <a href="{{ route('products.index', ['type' => 'accessory']) }}" 
                       class="inline-flex items-center bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-6 sm:px-8 py-3.5 sm:py-4 rounded-xl font-semibold hover:from-emerald-700 hover:to-teal-700 transition duration-300 transform hover:scale-[1.02] shadow-lg">
                        <i class="fas fa-screwdriver-wrench mr-2.5"></i>
                        <span>Khám phá phụ kiện</span>
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
                                    <thead class="desktop-only">
                                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                                            <th class="px-6 py-3 whitespace-nowrap text-center">Ảnh</th>
                                            <th class="px-6 py-3 whitespace-nowrap text-left">Thông tin</th>
                                            <th class="px-6 py-3 whitespace-nowrap text-center">Số lượng</th>
                                            <th class="px-6 py-3 whitespace-nowrap text-right">Giá</th>
                                            <th class="px-6 py-3 whitespace-nowrap text-center">Thao tác</th>
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
                                    <thead class="desktop-only">
                                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                                            <th class="px-6 py-3 whitespace-nowrap text-center">Ảnh</th>
                                            <th class="px-6 py-3 whitespace-nowrap text-left">Thông tin</th>
                                            <th class="px-6 py-3 whitespace-nowrap text-center">Số lượng</th>
                                            <th class="px-6 py-3 whitespace-nowrap text-right">Giá</th>
                                            <th class="px-6 py-3 whitespace-nowrap text-center">Thao tác</th>
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
                <div id="order-summary" class="lg:col-span-4 xl:col-span-3">
                    <div class="sticky top-8">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-100">
                                <h3 class="text-lg font-bold text-gray-900">Tóm tắt đơn hàng</h3>
                            </div>
                            <div class="p-6 space-y-6">
                                @php
                                    $subtotal = 0.0;
                                    $discountTotal = 0.0;
                                    foreach ($cartItems as $ci) {
                                        $unit = 0.0;
                                        if ($ci->item_type === 'car_variant') {
                                            $base = method_exists($ci->item, 'getPriceWithColorAdjustment')
                                                ? (float) $ci->item->getPriceWithColorAdjustment($ci->color_id)
                                                : (float) ($ci->item->current_price ?? 0);
                                            $meta = session('cart_item_meta.' . $ci->id, []);
                                            $featIds = collect($meta['feature_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
                                            $featSum = !empty($featIds) ? (float) \App\Models\CarVariantFeature::whereIn('id', $featIds)->sum('price') : 0.0;
                                            $unit = max(0.0, $base + $featSum);
                                            $orig = (float) ($ci->item->base_price ?? ($ci->item->original_price ?? 0));
                                            $curr = (float) ($ci->item->current_price ?? 0);
                                            if ($orig > 0 && $curr > 0 && $curr < $orig) {
                                                $discountTotal += ($orig - $curr) * (int) $ci->quantity;
                                            }
                                        } else {
                                            $curr = (float) ($ci->item->current_price ?? 0);
                                            $orig = (float) ($ci->item->base_price ?? ($ci->item->original_price ?? 0));
                                            if ($orig > 0 && $curr > 0 && $curr < $orig) {
                                                $discountTotal += ($orig - $curr) * (int) $ci->quantity;
                                            }
                                            $unit = $curr;
                                        }
                                        $subtotal += $unit * (int) $ci->quantity;
                                    }
                                    $taxTotal = (int) round($subtotal * 0.10);
                                    $grandTotal = $subtotal + $taxTotal; // shipping free
                                @endphp

                                <!-- Summary Items -->
                                <div class="space-y-3 text-sm">
                                    <!-- Individual Products -->
                                    @foreach($cartItems->sortBy(function($item) { return $item->item_type === 'car_variant' ? 0 : 1; }) as $ci)
                                        @php
                                            $unit = 0.0;
                                            $itemName = '';
                                            if ($ci->item_type === 'car_variant') {
                                                $base = method_exists($ci->item, 'getPriceWithColorAdjustment')
                                                    ? (float) $ci->item->getPriceWithColorAdjustment($ci->color_id)
                                                    : (float) ($ci->item->current_price ?? 0);
                                                $meta = session('cart_item_meta.' . $ci->id, []);
                                                $featIds = collect($meta['feature_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
                                                $featSum = !empty($featIds) ? (float) \App\Models\CarVariantFeature::whereIn('id', $featIds)->sum('price') : 0.0;
                                                $unit = max(0.0, $base + $featSum);
                                                $itemName = $ci->item->name ?? 'Xe hơi';
                                            } else {
                                                $unit = (float) ($ci->item->current_price ?? 0);
                                                $itemName = $ci->item->name ?? 'Phụ kiện';
                                            }
                                            $itemTotal = $unit * (int) $ci->quantity;
                                        @endphp
                                        <div class="flex justify-between items-center gap-2 text-gray-600 text-sm product-summary-item">
                                            <span class="truncate cursor-help" title="{{ $itemName }} (x{{ $ci->quantity }})">{{ $itemName }} x{{ $ci->quantity }}</span>
                                            <span class="font-semibold text-gray-900 whitespace-nowrap text-right">{{ number_format($itemTotal, 0, ',', '.') }} đ</span>
                                        </div>
                                    @endforeach
                                    
                                    <!-- Separator -->
                                    <div class="border-t border-gray-200 my-3"></div>
                                    
                                    <!-- Subtotal -->
                                    <div class="flex justify-between items-center gap-2 text-gray-600 text-sm">
                                        <span class="whitespace-nowrap">Tạm tính</span>
                                        <span class="font-semibold text-gray-900 whitespace-nowrap text-right"><span id="subtotal">{{ number_format($subtotal, 0, ',', '.') }}</span> đ</span>
                                    </div>
                                    
                                    <!-- Tax -->
                                    <div class="flex justify-between items-center gap-2 text-gray-600 text-sm">
                                        <span class="whitespace-nowrap">Thuế VAT (10%)</span>
                                        <span class="font-semibold text-gray-900 whitespace-nowrap text-right"><span id="tax-total">{{ number_format($taxTotal, 0, ',', '.') }}</span> đ</span>
                                    </div>
                                </div>

                                <!-- Total -->
                                <div class="border-t border-gray-200 pt-4">
                                    <div class="flex justify-between items-center gap-2">
                                        <span class="text-base font-bold text-gray-900 whitespace-nowrap flex-shrink-0">Tổng cộng:</span>
                                        <span class="text-base sm:text-lg font-bold text-blue-600 text-right truncate cursor-help" title="{{ number_format($subtotal + $taxTotal, 0, ',', '.') }} đ"><span id="cart-total">{{ number_format($subtotal + $taxTotal, 0, ',', '.') }}</span> đ</span>
                                    </div>
                                </div>

                                <!-- Checkout Button -->
                                <a href="{{ route('user.cart.checkout.form') }}" class="block w-full">
                                    <span class="w-full inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-4 rounded-xl font-semibold transition duration-300 transform hover:scale-105 shadow-lg text-base gap-3">
                                        <i class="fas fa-credit-card"></i>
                                        <span>Thanh toán</span>
                                    </span>
                                </a>

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
<style>
    /* Responsive display control - Mobile first approach */
    /* Default: Show mobile layout */
    .desktop-only {
        display: none !important;
    }
    
    .mobile-only {
        display: table-row;
    }
    
    /* Tablet and up (md breakpoint: 768px) - Show desktop layout */
    @media (min-width: 768px) {
        /* Table rows */
        tr.desktop-only {
            display: table-row !important;
        }
        
        /* Table headers */
        thead.desktop-only {
            display: table-header-group !important;
        }
        
        /* Hide mobile layout */
        .mobile-only {
            display: none !important;
        }
        
        /* Show columns that were hidden on mobile */
        .mobile-hide {
            display: table-cell !important;
        }
    }
    
    /* Mobile specific - Hide elements on small screens */
    @media (max-width: 767px) {
        .mobile-hide {
            display: none !important;
        }
        
        /* Improve mobile spacing */
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }
    
    /* Remove spinner arrows from number inputs */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    input[type=number] {
        -moz-appearance: textfield;
        appearance: textfield;
    }
    
    /* Increase spacing between cart items */
    .cart-table tbody tr.desktop-only td {
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
    }
    
    .cart-table tbody tr {
        border-bottom: 2px solid #f3f4f6;
    }
    
    /* Column alignment */
    .cart-table tbody tr.desktop-only td:nth-child(3) {
        text-align: center;
        vertical-align: middle;
    }
    
    .cart-table tbody tr.desktop-only td:nth-child(3) > div {
        display: inline-flex;
        justify-content: center;
        align-items: center;
    }
    
    .cart-table tbody tr.desktop-only td:nth-child(2) {
        text-align: left;
        vertical-align: top;
    }
    
    /* Mobile card spacing */
    @media (max-width: 767px) {
        .mobile-only td > div {
            margin-bottom: 1.5rem;
        }
        
        .mobile-only:not(:last-child) {
            margin-bottom: 0.5rem;
        }
    }
    
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
    
    /* Smooth transitions for responsive changes */
    .desktop-only,
    .mobile-only {
        transition: opacity 0.2s ease;
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
     
     const nf = new Intl.NumberFormat('vi-VN');
     
     // Update individual product items in summary
     const summaryContainer = document.querySelector('.space-y-3.text-sm');
     if (summaryContainer) {
       // Clear existing product items (keep only subtotal, tax, shipping)
       const productItems = summaryContainer.querySelectorAll('.product-summary-item');
       productItems.forEach(item => item.remove());
       
       // Get all cart items (only desktop to avoid duplicates)
       const allCartItems = [];
       const processedIds = new Set();
       
       document.querySelectorAll('.cart-item-desktop').forEach(function(row) {
         const itemId = row.dataset.id;
         const itemType = row.dataset.itemType;
         
         if (processedIds.has(itemId)) return; // Skip if already processed
         processedIds.add(itemId);
         
         const quantity = row.querySelector('.cart-qty-input, .js-cart-quantity')?.value || 1;
         const totalEl = document.querySelector('.item-total[data-id="' + itemId + '"]');
         
       if (totalEl) {
         const total = parseInt(totalEl.textContent.replace(/[^\d]/g, ''));
           if (!isNaN(total)) {
             // Get item name
             let itemName = 'Sản phẩm';
             if (itemType === 'car_variant') {
               const nameEl = row.querySelector('.font-bold.text-gray-900 a, .font-bold.text-gray-900');
               if (nameEl) {
                 itemName = nameEl.textContent.trim();
               } else {
                 itemName = 'Xe hơi';
               }
             } else {
               const nameEl = row.querySelector('.font-bold.text-gray-900 a, .font-bold.text-gray-900');
               if (nameEl) {
                 itemName = nameEl.textContent.trim();
               } else {
                 itemName = 'Phụ kiện';
               }
             }
             
             allCartItems.push({
               id: itemId,
               type: itemType,
               name: itemName,
               quantity: parseInt(quantity),
               total: total
             });
             
             subtotal += total;
           }
         }
       });
       
       // Sort: car_variant first, then accessory
       allCartItems.sort((a, b) => {
         if (a.type === 'car_variant' && b.type !== 'car_variant') return -1;
         if (a.type !== 'car_variant' && b.type === 'car_variant') return 1;
         return 0;
       });
       
       // Insert product items before separator
       const separator = summaryContainer.querySelector('.border-t.border-gray-200.my-3');
       allCartItems.forEach(function(item, index) {
        const productDiv = document.createElement('div');
        productDiv.className = 'flex justify-between items-center gap-2 text-gray-600 text-sm product-summary-item';
        productDiv.innerHTML = `
          <span class="truncate cursor-help" title="${item.name} (x${item.quantity})">${item.name} x${item.quantity}</span>
          <span class="font-semibold text-gray-900 whitespace-nowrap text-right">${nf.format(item.total)} đ</span>
        `;
         
         if (separator) {
           summaryContainer.insertBefore(productDiv, separator);
         } else {
           summaryContainer.appendChild(productDiv);
         }
       });
     }
     
     tax = Math.round(subtotal * 0.1);
     cartTotal = subtotal + tax;
     
     // Update summary display
     const subtotalEl = document.getElementById('subtotal');
     const taxEl = document.getElementById('tax-total');
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
    
    // Handle color selection
    document.addEventListener('click', async function(e) {
      const colorBtn = e.target.closest('.color-option');
      if (!colorBtn) return;
      
      e.preventDefault();
      
      const itemId = colorBtn.getAttribute('data-item-id');
      const colorId = colorBtn.getAttribute('data-color-id');
      const colorName = colorBtn.getAttribute('data-color-name');
      const updateUrl = colorBtn.getAttribute('data-update-url');
      const csrfToken = colorBtn.getAttribute('data-csrf');
      
      if (!itemId || !colorId || !updateUrl) {
        console.error('Missing required attributes for color selection');
        return;
      }
      
      console.log('Color selected:', colorName, 'for item:', itemId);
      
      try {
        const formData = new FormData();
        formData.append('color_id', colorId);
        formData.append('_token', csrfToken);
        
        const response = await fetch(updateUrl, {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        
        const data = await response.json();
        
        if (data.success) {
          // Update visual selection
          const allColorBtns = document.querySelectorAll(`.color-option[data-item-id="${itemId}"]`);
          allColorBtns.forEach(btn => {
            btn.classList.remove('border-blue-500', 'ring-2', 'ring-blue-200');
            btn.classList.add('border-gray-300');
          });
          
          colorBtn.classList.remove('border-gray-300');
          colorBtn.classList.add('border-blue-500', 'ring-2', 'ring-blue-200');
          
          // Update color name display
          const colorNameElements = document.querySelectorAll(`[data-item-id="${itemId}"] .selected-color-name`);
          colorNameElements.forEach(el => {
            el.textContent = colorName;
          });
          
          // Update data-color-id attribute on the row
          const cartRow = colorBtn.closest('.cart-item-desktop, .cart-item-row');
          if (cartRow) {
            cartRow.setAttribute('data-color-id', colorId);
          }
          
          console.log('Color updated successfully');
        } else {
          console.error('Failed to update color:', data.message);
          if (typeof window.showMessage === 'function') {
            window.showMessage('Không thể cập nhật màu sắc', 'error');
          }
        }
      } catch (error) {
        console.error('Error updating color:', error);
        if (typeof window.showMessage === 'function') {
          window.showMessage('Có lỗi xảy ra khi cập nhật màu sắc', 'error');
        }
      }
    });
  });
  
  document.addEventListener('click', async function(e){
    // Block duplicate action if confirm modal is open (same guard as delete behavior expectation)
    if (document.body.classList.contains('modal-open')) {
      const blocked = e.target.closest('.js-duplicate-line');
      if (blocked) { e.preventDefault(); e.stopPropagation(); return; }
    }
    const btn = e.target.closest('.js-duplicate-line');
    if (!btn) return;
    e.preventDefault();
    
    console.log('Duplicate button clicked', btn);
    
    // Add loading state
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;
    btn.classList.add('opacity-50', 'cursor-not-allowed');
    
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
        
        // Inject stock data for new item
        if (data.stock_data && data.cart_item_id) {
          window.cartItemStockData = window.cartItemStockData || {};
          window.cartItemStockData[data.cart_item_id] = data.stock_data;
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
    } finally {
      // Restore button state
      btn.innerHTML = originalContent;
      btn.disabled = false;
      btn.classList.remove('opacity-50', 'cursor-not-allowed');
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
<script>
(function(){
  function showToast(message, type) {
    const existing = document.getElementById('cart-toast');
    if (existing) existing.remove();
    const bg = type === 'error' ? 'bg-red-600' : (type === 'success' ? 'bg-emerald-600' : 'bg-amber-600');
    const toast = document.createElement('div');
    toast.id = 'cart-toast';
    toast.className = `${bg} text-white fixed top-4 left-1/2 -translate-x-1/2 z-50 px-4 py-3 rounded-lg shadow-lg flex items-center gap-3`;
    toast.innerHTML = `<i class=\"fas fa-info-circle\"></i><span>${message}</span>`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
  }

  function hasVariantWithoutColor() {
    // Only check desktop rows to avoid duplicates
    const desktopRows = document.querySelectorAll('.cart-item-desktop');
    
    for (const row of desktopRows) {
      const type = row.getAttribute('data-item-type');
      const colorId = row.getAttribute('data-color-id');
      
      // Only check car variants
      if (type === 'car_variant') {
        const hasNoColor = !colorId || colorId === '' || colorId === 'null' || colorId === 'undefined' || colorId === null;
        
        if (hasNoColor) {
          return true;
        }
      }
    }
    return false;
  }

  document.addEventListener('DOMContentLoaded', function(){
    const checkoutLink = document.querySelector(`a[href='{{ route('user.cart.checkout.form') }}']`);
    if (!checkoutLink) return;
    
    // Remove any existing event listeners first
    const newCheckoutLink = checkoutLink.cloneNode(true);
    checkoutLink.parentNode.replaceChild(newCheckoutLink, checkoutLink);
    
    newCheckoutLink.addEventListener('click', function(e){
      if (hasVariantWithoutColor()) {
        e.preventDefault();
        e.stopPropagation();
        if (typeof window.showMessage === 'function') {
          window.showMessage('Vui lòng chọn màu cho tất cả phiên bản trước khi thanh toán!', 'warning');
        } else {
          showToast('Vui lòng chọn màu cho tất cả phiên bản trước khi thanh toán', 'warning');
        }
        return false;
      }
      
      // Check if any item has out of stock selected
      const outOfStockItems = [];
      
      // Check car variants with colors
      document.querySelectorAll('[data-item-type="car_variant"]').forEach(function(row) {
        const itemId = row.getAttribute('data-id');
        const colorId = row.getAttribute('data-color-id');
        
        if (itemId && colorId && window.cartItemStockData && window.cartItemStockData[itemId]) {
          const stockData = window.cartItemStockData[itemId][colorId];
          if (stockData && stockData.available === 0) {
            // Get item name
            const nameEl = row.querySelector('.font-bold a, .font-bold');
            const itemName = nameEl ? nameEl.textContent.trim() : 'Sản phẩm';
            const colorName = row.querySelector('.selected-color-name');
            const colorText = colorName ? colorName.textContent.trim() : '';
            
            outOfStockItems.push(`${itemName} - Màu ${colorText}`);
          }
        }
      });
      
      // Check accessories
      document.querySelectorAll('[data-item-type="accessory"]').forEach(function(row) {
        const stockBadge = row.querySelector('[id^="stock-badge"]');
        if (stockBadge && stockBadge.textContent.includes('Hết hàng')) {
          const nameEl = row.querySelector('.font-bold a, .font-bold');
          const itemName = nameEl ? nameEl.textContent.trim() : 'Phụ kiện';
          outOfStockItems.push(itemName);
        }
      });
      
      if (outOfStockItems.length > 0) {
        e.preventDefault();
        e.stopPropagation();
        
        let message = 'Không thể thanh toán! Các sản phẩm sau đã hết hàng:\n';
        message += outOfStockItems.map((item, index) => `${index + 1}. ${item}`).join('\n');
        message += '\nVui lòng xóa hoặc chọn màu khác.';
        
        if (typeof window.showMessage === 'function') {
          window.showMessage(message.replace(/\n/g, '<br>'), 'error');
        } else {
          alert(message);
        }
        
        // Highlight out of stock items
        document.querySelectorAll('[data-item-type="car_variant"], [data-item-type="accessory"]').forEach(function(row) {
          const itemId = row.getAttribute('data-id');
          const colorId = row.getAttribute('data-color-id');
          
          let isOutOfStock = false;
          
          if (row.getAttribute('data-item-type') === 'car_variant' && colorId && window.cartItemStockData && window.cartItemStockData[itemId]) {
            const stockData = window.cartItemStockData[itemId][colorId];
            if (stockData && stockData.available === 0) {
              isOutOfStock = true;
            }
          } else if (row.getAttribute('data-item-type') === 'accessory') {
            const stockBadge = row.querySelector('[id^="stock-badge"]');
            if (stockBadge && stockBadge.textContent.includes('Hết hàng')) {
              isOutOfStock = true;
            }
          }
          
          if (isOutOfStock) {
            row.style.border = '2px solid #ef4444';
            row.style.backgroundColor = '#fef2f2';
            setTimeout(() => {
              row.style.border = '';
              row.style.backgroundColor = '';
            }, 5000);
          }
        });
        
        return false;
      }
    });
  });
  
  // Stock badge update functionality
  window.renderStockBadge = function(badgeData) {
    const sizeClasses = 'px-2 py-1 text-xs';
    return `<span class="inline-flex items-center gap-1 ${sizeClasses} font-medium rounded ${badgeData.class}">
      <span>${badgeData.icon}</span>
      <span>${badgeData.text}</span>
    </span>`;
  };
  
  // Color change handler for stock badge
  document.addEventListener('click', function(e) {
    const colorBtn = e.target.closest('.color-option');
    if (!colorBtn) return;
    
    const itemId = colorBtn.getAttribute('data-item-id');
    const colorId = parseInt(colorBtn.getAttribute('data-color-id'));
    
    if (!itemId || !colorId) return;
    if (!window.cartItemStockData || !window.cartItemStockData[itemId]) return;
    
    const stockData = window.cartItemStockData[itemId][colorId];
    if (!stockData) return;
    
    // Update badge in both desktop and mobile views
    const desktopBadge = document.getElementById('stock-badge-' + itemId);
    const mobileBadge = document.getElementById('stock-badge-mobile-' + itemId);
    
    const badgeHTML = window.renderStockBadge(stockData.badge);
    
    if (desktopBadge) {
      desktopBadge.innerHTML = badgeHTML;
      desktopBadge.style.display = '';
    }
    if (mobileBadge) {
      mobileBadge.innerHTML = badgeHTML;
      mobileBadge.style.display = '';
    }
    
    // Update data-color-id attribute for validation
    const desktopRow = document.querySelector(`.cart-item-desktop[data-id="${itemId}"]`);
    const mobileRow = document.querySelector(`.cart-item-row[data-id="${itemId}"]`);
    
    if (desktopRow) {
      desktopRow.setAttribute('data-color-id', colorId);
    }
    if (mobileRow) {
      mobileRow.setAttribute('data-color-id', colorId);
    }
  });
})();
</script>
@endpush