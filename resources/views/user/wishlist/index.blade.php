@extends('layouts.app')

@section('title', 'Danh sách yêu thích')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
        
        <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full mb-6">
                    <i class="fas fa-heart text-white text-3xl"></i>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-4 leading-tight">
                    Danh sách yêu thích
                </h1>
                <p class="text-xl text-blue-100 max-w-3xl mx-auto leading-relaxed">
                    Lưu trữ và quản lý những sản phẩm bạn yêu thích để mua sau
                </p>
                
                @if($wishlistItems->count() > 0)
                    <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <div class="flex items-center bg-white/20 backdrop-blur-sm rounded-full px-6 py-3">
                            <i class="fas fa-gift text-white mr-3"></i>
                            <span class="text-white font-semibold">{{ $wishlistItems->count() }} sản phẩm yêu thích</span>
                        </div>
                        <button id="clear-all-btn"
                                class="group bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-full font-semibold transition-all duration-300 border border-white/30 hover:border-white/50">
                            <i class="fas fa-trash mr-2 group-hover:scale-110 transition-transform"></i>
                            Xóa tất cả
                        </button>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Decorative elements -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-yellow-400/20 rounded-full blur-xl"></div>
        <div class="absolute bottom-10 right-10 w-32 h-32 bg-pink-400/20 rounded-full blur-xl"></div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($wishlistItems->count() > 0)
            <!-- Filters and Sort -->
            <div class="mb-8" id="filter-section">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-filter text-gray-600"></i>
                            <span class="text-gray-700 font-medium">Lọc theo:</span>
                        </div>
                        <select id="filter-type" class="px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Tất cả</option>
                            <option value="car_variant">Xe hơi</option>
                            <option value="accessory">Phụ kiện</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-sort text-gray-600"></i>
                            <span class="text-gray-700 font-medium">Sắp xếp:</span>
                        </div>
                        <select id="sort-by" class="px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="newest">Mới nhất</option>
                            <option value="oldest">Cũ nhất</option>
                            <option value="price-low">Giá thấp → cao</option>
                            <option value="price-high">Giá cao → thấp</option>
                            <option value="name">Tên A-Z</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Wishlist Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8" id="wishlist-grid">
                @foreach($wishlistItems as $item)
                    @php
                        $product = $item->item ?? $item->product ?? null;
                        if (!$product) continue;
                        
                        // Normalize item type
                        $itemType = $item->item_type;
                        if ($itemType === 'App\\Models\\CarVariant') {
                            $itemType = 'car_variant';
                        }
                        
                        // Get proper item ID
                        $itemId = $item->item_id ?? $product->id ?? null;
                    @endphp
                    
                    @if($itemType === 'car_variant' || $itemType === 'variant')
                        <div class="wishlist-item variant-card" 
                             data-type="car_variant" 
                             data-item-id="{{ $itemId }}"
                             data-price="{{ $product->price ?? 0 }}" 
                             data-name="{{ strtolower($product->name ?? '') }}" 
                             data-date="{{ $item->created_at ?? now() }}"
                             data-item-type="car_variant">
                            <x-variant-card :variant="$product" />
                        </div>
                    @else
                        <div class="wishlist-item accessory-card" 
                             data-type="accessory" 
                             data-item-id="{{ $itemId }}"
                             data-price="{{ $product->price ?? 0 }}" 
                             data-name="{{ strtolower($product->name ?? '') }}" 
                             data-date="{{ $item->created_at ?? now() }}"
                             data-item-type="accessory">
                            <x-accessory-card :accessory="$product" />
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Empty State (Hidden by default) -->
            <div id="empty-state" class="hidden text-center py-16">
                <div class="w-32 h-32 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full mx-auto mb-8 flex items-center justify-center">
                    <i class="fas fa-heart text-gray-400 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-600 mb-4">Danh sách yêu thích trống</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">
                    Bạn chưa có sản phẩm nào trong danh sách yêu thích. Hãy khám phá các sản phẩm và thêm vào yêu thích!
                </p>
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Khám phá sản phẩm
                </a>
            </div>

        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-32 h-32 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full mx-auto mb-8 flex items-center justify-center">
                    <i class="fas fa-heart text-gray-400 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-600 mb-4">Danh sách yêu thích trống</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">
                    Bạn chưa có sản phẩm nào trong danh sách yêu thích. Hãy khám phá các sản phẩm và thêm vào yêu thích!
                </p>
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Khám phá sản phẩm
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Success/Error Messages -->
<div id="message-container" class="fixed top-4 right-4 z-50"></div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Wishlist page DOM loaded');
    
    // Check if WishlistPage class exists
    if (typeof WishlistPage === 'undefined') {
        console.error('WishlistPage class not found!');
        console.log('Available global objects:', Object.keys(window));
        return;
    }
    
    console.log('WishlistPage class found, initializing...');
    
    try {
        // Wishlist page functionality
        window.wishlistPage = new WishlistPage();
        window.wishlistPage.init();
        console.log('WishlistPage initialized successfully');
    } catch (error) {
        console.error('Error initializing WishlistPage:', error);
    }
});
</script>
@endpush 