{{-- Main Navigation (Modern, showroom-oriented) --}}
@php
// Ensure variables from View Composer exist
$navBrands = isset($navBrands) ? $navBrands : collect();

$navCartCount = isset($navCartCount) ? $navCartCount : 0;
$navWishlistCount = isset($navWishlistCount) ? $navWishlistCount : 0;
$navUnreadNotifCount = isset($navUnreadNotifCount) ? $navUnreadNotifCount : 0;
@endphp

<nav id="main-nav" class="sticky top-0 z-40 bg-white/85 backdrop-blur-md border-b border-gray-100">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-16 flex items-center justify-between gap-2">
            {{-- Left: Brand / Home --}}
            <div class="flex items-center gap-3 min-w-[140px]">
                <a href="{{ route('home') }}" class="group inline-flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-blue-700 to-indigo-700 shadow-md">
                        <i class="fas fa-car text-white"></i>
                    </span>
                    <div class="leading-tight">
                        <div class="font-extrabold text-gray-900 group-hover:text-indigo-700">AutoLux</div>
                        <div class="text-[10px] uppercase tracking-wider text-indigo-500">Premium Showroom</div>
                    </div>
                </a>
            </div>

            {{-- Center: Primary Navigation (Desktop) --}}
            <div class="center-menu hidden lg:flex items-center justify-center gap-1 text-[13px] flex-1 min-w-0">
                {{-- Hãng (giữ nguyên) --}}
                <div class="dropdown-group car-dropdown-wrapper" data-dropdown="brands">
                    <button class="px-2 py-2 rounded-lg text-sm text-gray-700 hover:text-white hover:bg-indigo-600 inline-flex items-center gap-2 transition-colors duration-200" data-dropdown-trigger>
                        <i class="fas fa-flag-checkered text-[15px]"></i>
                        <span class="nav-label">Hãng</span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"></i>
                    </button>
                    <div class="dropdown-bridge"></div>
                    <div class="dropdown-menu absolute left-0 mt-2 w-[720px] z-50 opacity-0 invisible scale-95 transition-all duration-200 transform origin-top-left" data-dropdown-menu>
                        <div class="p-3 grid grid-cols-3 gap-2">
                            @forelse($navBrands as $brand)
                            <a href="{{ route('car-brands.show', $brand->id) }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-white transition-colors duration-150">
                                <img src="{{ $brand->logo_path }}" alt="{{ $brand->name }}" class="w-8 h-8 object-contain rounded" loading="lazy" onerror="this.onerror=null;this.src='https://placehold.co/40x40?text=%2B';" />
                                <div class="min-w-0">
                                    <div class="font-semibold text-sm truncate">{{ $brand->name }}</div>
                                    @php $modelsCount = $brand->relationLoaded('carModels') ? $brand->carModels->count() : ($brand->carModels()->count()); @endphp
                                    <div class="text-xs text-gray-500 truncate"><i class="fas fa-layer-group text-gray-400 mr-1"></i>{{ number_format($modelsCount) }} dòng xe</div>
                                </div>
                            </a>
                            @empty
                            <div class="col-span-3 text-sm text-gray-500 px-2 py-1">Chưa có hãng xe khả dụng</div>
                            @endforelse
                        </div>
                        <div class="px-3 pb-3 flex items-center justify-end">
                            <a href="{{ route('car-brands.index') }}" class="text-sm text-indigo-700 hover:underline inline-flex items-center gap-1 transition-colors duration-150">
                                Xem tất cả hãng xe <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Kho (Tất cả, Xe hơi, Phụ kiện) --}}
                <div class="dropdown-group" data-dropdown="inventory">
                    <button class="px-2 py-2 rounded-lg text-sm text-gray-700 hover:text-white hover:bg-indigo-600 inline-flex items-center gap-2 transition-colors duration-200" data-dropdown-trigger>
                        <i class="fas fa-warehouse text-[15px]"></i>
                        <span class="nav-label">Kho</span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"></i>
                    </button>
                    <div class="dropdown-bridge"></div>
                    <div class="dropdown-menu absolute left-0 mt-2 min-w-[260px] z-50 opacity-0 invisible scale-95 transition-all duration-200 transform origin-top-left" data-dropdown-menu>
                        <div class="p-2">
                            <a href="{{ route('products.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white transition-colors duration-150"><i class="fas fa-th-large text-gray-400"></i><span>Tất cả</span></a>
                            <a href="{{ route('products.index', ['type' => 'car']) }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white transition-colors duration-150"><i class="fas fa-car-side text-gray-400"></i><span>Xe hơi</span></a>
                            <a href="{{ route('products.index', ['type' => 'accessory']) }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white transition-colors duration-150"><i class="fas fa-puzzle-piece text-gray-400"></i><span>Phụ kiện</span></a>
                        </div>
                    </div>
                </div>

                {{-- Tài chính --}}
                <div class="dropdown-group" data-dropdown="finance">
                    <button class="px-2 py-2 rounded-lg text-sm text-gray-700 hover:text-white hover:bg-indigo-600 inline-flex items-center gap-2 transition-colors duration-200" data-dropdown-trigger>
                        <i class="fas fa-hand-holding-usd text-[15px]"></i>
                        <span class="nav-label">Tài chính</span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"></i>
                    </button>
                    <div class="dropdown-bridge"></div>
                    <div class="dropdown-menu absolute left-0 mt-2 min-w-[260px] z-50 opacity-0 invisible scale-95 transition-all duration-200 transform origin-top-left" data-dropdown-menu>
                        <div class="p-2">
                            <a href="{{ route('finance.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white transition-colors duration-150"><i class="fas fa-info-circle text-gray-400"></i><span>Tổng quan</span></a>
                            <a href="{{ route('finance.calculator') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white transition-colors duration-150"><i class="fas fa-calculator text-gray-400"></i><span>Máy tính trả góp</span></a>
                            <a href="{{ route('finance.requirements') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white transition-colors duration-150"><i class="fas fa-clipboard-list text-gray-400"></i><span>Điều kiện vay</span></a>
                            <a href="{{ route('finance.faq') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white transition-colors duration-150"><i class="fas fa-question-circle text-gray-400"></i><span>FAQ</span></a>
                        </div>
                    </div>
                </div>

                {{-- Thông tin (nhóm tiết kiệm không gian) --}}
                <div class="dropdown-group" data-dropdown="info">
                    <button class="px-2 py-2 rounded-lg text-sm text-gray-700 hover:text-white hover:bg-indigo-600 inline-flex items-center gap-2 transition-colors duration-200" data-dropdown-trigger>
                        <i class="fas fa-compass text-[15px]"></i>
                        <span class="nav-label">Thông tin</span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"></i>
                    </button>
                    <div class="dropdown-bridge"></div>
                    <div class="dropdown-menu absolute left-0 mt-2 min-w-[260px] z-50 opacity-0 invisible scale-95 transition-all duration-200 transform origin-top-left" data-dropdown-menu>
                        <div class="p-2 grid grid-cols-1 gap-1">
                            <a href="{{ route('blogs.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white transition-colors duration-150"><i class="fas fa-newspaper text-gray-400"></i><span>Tin tức</span></a>
                            <a href="{{ route('about') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white transition-colors duration-150"><i class="fas fa-info-circle text-gray-400"></i><span>Giới thiệu</span></a>
                            <a href="{{ route('contact') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white transition-colors duration-150"><i class="fas fa-phone text-gray-400"></i><span>Liên hệ</span></a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Search + Actions --}}
            <div id="nav-actions" class="flex items-center gap-2 shrink-0">
                {{-- Search (Desktop) --}}
                <form action="{{ route('products.index') }}" method="GET" class="relative hidden lg:block self-center">
                    <div class="relative h-10 flex items-center">
                        <input id="desktop-search-input" name="q" type="search" class="search-input h-10 w-56 xl:w-72 rounded-lg border border-gray-200 pl-10 pr-10 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Tìm kiếm xe, hãng, model..." autocomplete="off" />
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <button type="submit" class="absolute right-1 top-1/2 -translate-y-1/2 px-2 py-1 text-indigo-600 hover:text-indigo-800">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                        <div class="search-suggestions absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden z-50">
                            <div class="search-suggestions-list p-2 space-y-1"></div>
                            <div class="px-2 py-1 text-xs text-gray-500 border-t"><a href="{{ route('search.advanced') }}" class="hover:underline">Tìm kiếm nâng cao</a></div>
                        </div>
                    </div>
                </form>

                {{-- Mobile: Search button --}}
                <button id="toggle-mobile-search" type="button" class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-200 text-gray-600 hover:text-gray-900 hover:border-gray-300">
                    <i class="fas fa-search"></i>
                    <span class="sr-only">Mở tìm kiếm</span>
                </button>



                {{-- Wishlist --}}
                <a href="{{ route('wishlist.index') }}" class="relative inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-200 bg-white text-gray-600 hover:text-red-600 hover:border-red-300">
                    <i class="fas fa-heart"></i>
                    <span id="wishlist-count-badge" class="wishlist-count absolute -top-1 -right-1 w-5 h-5 text-[10px] rounded-full bg-red-500 text-white flex items-center justify-center {{ $navWishlistCount > 0 ? 'flex' : 'hidden' }}">{{ $navWishlistCount > 99 ? '99+' : $navWishlistCount }}</span>
                    <span class="sr-only">Danh sách yêu thích</span>
                </a>

                {{-- Cart --}}
                <a href="{{ route('user.cart.index') }}" class="relative inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-200 bg-white text-gray-600 hover:text-indigo-700 hover:border-indigo-300">
                    <i class="fas fa-shopping-cart"></i>
                    <span id="cart-count-badge" class="cart-count absolute -top-1 -right-1 w-5 h-5 text-[10px] rounded-full bg-indigo-600 text-white flex items-center justify-center {{ $navCartCount > 0 ? 'flex' : 'hidden' }}">{{ $navCartCount > 99 ? '99+' : $navCartCount }}</span>
                    <span class="sr-only">Giỏ hàng</span>
                </a>

                {{-- Notifications (mobile simple link) --}}
                @auth
                <a href="{{ route('notifications.index') }}" class="lg:hidden relative inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-200 bg-white text-gray-600 hover:text-amber-600 hover:border-amber-300">
                    <i class="fas fa-bell"></i>
                    <span id="notif-count-badge-mobile" class="absolute -top-1 -right-1 w-5 h-5 text-[10px] rounded-full bg-amber-500 text-white items-center justify-center {{ $navUnreadNotifCount ? 'flex' : 'hidden' }}">{{ $navUnreadNotifCount > 99 ? '99+' : $navUnreadNotifCount }}</span>
                    <span class="sr-only">Thông báo</span>
                </a>
                @endauth

                {{-- Mobile: Open Drawer button (to the right of cart) --}}
                <button id="open-drawer" type="button" class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-200 text-gray-600 hover:text-gray-900 hover:border-gray-300">
                    <i class="fas fa-bars"></i>
                    <span class="sr-only">Mở menu</span>
                </button>

                {{-- Notifications (auth only) --}}
                @auth
                <div class="dropdown-group hidden lg:block" data-dropdown="notifications">
                    <button class="relative inline-flex items-center justify-center w-10 h-10 rounded-lg border bg-white text-gray-600 hover:text-amber-600 hover:border-amber-300" style="border-color:#e5e7eb" data-dropdown-trigger>
                        <i class="fas fa-bell"></i>
                        <span id="notif-count-badge" class="absolute -top-1 -right-1 w-5 h-5 text-[10px] rounded-full bg-amber-500 text-white items-center justify-center {{ $navUnreadNotifCount ? 'flex' : 'hidden' }}">{{ $navUnreadNotifCount > 99 ? '99+' : $navUnreadNotifCount }}</span>
                        <span class="sr-only">Thông báo</span>
                    </button>
                    <div class="dropdown-bridge"></div>
                    <div class="dropdown-menu absolute right-0 mt-2 w-[360px] max-w-[90vw] z-50 opacity-0 invisible scale-95 transition-all duration-200 transform origin-top-right" data-dropdown-menu>
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                            <div class="px-4 py-3 flex items-center justify-between border-b">
                                <div class="text-sm font-semibold text-gray-800">Thông báo</div>
                                <button id="notif-mark-all" class="text-xs text-amber-700 hover:text-white hover:bg-amber-600 border border-amber-200 px-2 py-1 rounded">Đánh dấu đã đọc</button>
                            </div>
                            <div id="notif-menu-list" class="max-h-[60vh] overflow-auto divide-y">
                                <div class="p-4 text-sm text-gray-500 flex items-center gap-2"><i class="fas fa-spinner fa-spin"></i><span>Đang tải...</span></div>
                            </div>
                            <div class="px-4 py-3 border-t bg-gray-50 text-right">
                                <a href="{{ route('notifications.index') }}" class="text-sm text-amber-700 hover:underline">Xem tất cả</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endauth

                {{-- Profile / Auth --}}
                <div class="profile-dropdown-wrapper dropdown-group hidden lg:block">
                    <button class="px-2.5 py-2 rounded-lg border border-gray-200 text-sm text-gray-700 hover:text-white hover:bg-gray-900 inline-flex items-center gap-2">
                        <i class="fas fa-user-circle"></i>
                        <span class="nav-label">@auth {{ \Illuminate\Support\Str::limit(auth()->user()->name, 14) }} @else Tài khoản @endauth</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div class="dropdown-bridge"></div>
                    <div class="dropdown-menu profile-dropdown-menu absolute right-0 mt-2 min-w-[240px] z-50">
                        <div class="p-2">
                            @auth
                            <a href="{{ route('user.profile.edit') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white"><i class="fas fa-user-cog text-gray-400"></i><span>Tài khoản</span></a>
                            <a href="{{ route('user.customer-profiles.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white"><i class="fas fa-id-badge text-gray-400"></i><span>Hồ sơ</span></a>
                            <a href="{{ route('user.customer-profiles.orders') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white"><i class="fas fa-file-invoice-dollar text-gray-400"></i><span>Đơn hàng</span></a>
                            <a href="{{ route('user.service-appointments.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white"><i class="fas fa-calendar-check text-gray-400"></i><span>Bảo dưỡng</span></a>
                            <a href="{{ route('test-drives.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white"><i class="fas fa-car text-gray-400"></i><span>Lái thử</span></a>
                            @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white"><i class="fas fa-tachometer-alt text-gray-400"></i><span>Admin Dashboard</span></a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2 px-3 py-2 rounded-md hover:bg-red-50 text-red-600">
                                    <i class="fas fa-sign-out-alt"></i><span>Đăng xuất</span>
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white"><i class="fas fa-sign-in-alt text-gray-400"></i><span>Đăng nhập</span></a>
                            <a href="{{ route('register') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white"><i class="fas fa-user-plus text-gray-400"></i><span>Tạo tài khoản</span></a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile: Search bar below header --}}
    <div id="mobile-search" class="lg:hidden bg-white/90 border-t border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
            <form action="{{ route('products.index') }}" method="GET" class="relative">
                <div class="relative">
                    <input name="q" type="search" class="search-input w-full rounded-lg border border-gray-200 pl-10 pr-12 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Tìm kiếm xe, hãng, model..." autocomplete="off" />
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 px-2 py-1 text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    <div class="search-suggestions absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden z-50">
                        <div class="search-suggestions-list p-2 space-y-1"></div>
                        <div class="px-2 py-1 text-xs text-gray-500 border-t"><a href="{{ route('search.advanced') }}" class="hover:underline">Tìm kiếm nâng cao</a></div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Mobile Drawer --}}
    <div id="mobile-menu-sheet" class="lg:hidden fixed inset-0 z-50 hidden" aria-hidden="true">
        <div id="drawer-backdrop" class="drawer-backdrop absolute inset-0 bg-black/40 opacity-0"></div>
        <div id="drawer-panel" class="drawer-panel fixed top-0 right-0 left-auto h-auto max-h-[90vh] w-[92vw] sm:w-[360px] md:w-[420px] max-w-[95vw] bg-white shadow-2xl translate-x-full transition-transform duration-200 overflow-y-auto">
            <div class="h-12 px-4 flex items-center justify-between border-b">
                <span class="text-base font-semibold text-gray-700 tracking-wide leading-none">Danh mục</span>
                <button id="close-drawer" type="button" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 text-gray-600 hover:text-gray-900 hover:border-gray-300">
                    <i class="fas fa-times"></i>
                    <span class="sr-only">Đóng</span>
                </button>
            </div>

            <div class="p-4 space-y-2">
                {{-- Mobile menu items (đồng bộ desktop) --}}
                <div>
                    <button type="button" class="mobile-dropdown-btn w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center justify-between">
                        <span><i class="fas fa-flag-checkered mr-3 text-gray-400"></i>Hãng</span>
                        <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                    </button>
                    <div class="mobile-dropdown-content pl-2">
                        <div class="grid grid-cols-1 gap-1 max-h-64 overflow-y-auto pr-2">
                            @foreach($navBrands as $brand)
                            <a href="{{ route('car-brands.show', $brand->id) }}" class="flex items-center gap-3 px-4 py-2 rounded-md hover:bg-gray-50 transition-colors duration-150">
                                <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="w-6 h-6 object-contain rounded" loading="lazy" onerror="this.onerror=null;this.src='https://placehold.co/32x32?text=%2B';" />
                                <span class="text-sm flex-1 truncate">{{ $brand->name }}</span>
                                @php $modelsCount = $brand->relationLoaded('carModels') ? $brand->carModels->count() : ($brand->carModels()->count()); @endphp
                                <span class="text-[11px] text-gray-500 whitespace-nowrap"><i class="fas fa-layer-group mr-1 text-gray-400"></i>{{ number_format($modelsCount) }}</span>
                            </a>
                            @endforeach
                            <a href="{{ route('car-brands.index') }}" class="px-4 py-2 text-sm text-indigo-700 hover:underline">Xem tất cả hãng</a>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="button" class="mobile-dropdown-btn w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center justify-between">
                        <span><i class="fas fa-warehouse mr-3 text-gray-400"></i>Kho</span>
                        <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                    </button>
                    <div class="mobile-dropdown-content">
                        <div class="grid grid-cols-1 gap-1 pl-2 pr-2">
                            <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-md hover:bg-gray-50 flex items-center gap-2"><i class="fas fa-th-large text-gray-400"></i><span>Tất cả</span></a>
                            <a href="{{ route('products.index', ['type' => 'car']) }}" class="px-4 py-2 rounded-md hover:bg-gray-50 flex items-center gap-2"><i class="fas fa-car-side text-gray-400"></i><span>Xe hơi</span></a>
                            <a href="{{ route('products.index', ['type' => 'accessory']) }}" class="px-4 py-2 rounded-md hover:bg-gray-50 flex items-center gap-2"><i class="fas fa-puzzle-piece text-gray-400"></i><span>Phụ kiện</span></a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('test-drives.index') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center gap-2"><i class="fas fa-road mr-3 text-gray-400"></i><span>Lái thử</span></a>

                <div>
                    <button type="button" class="mobile-dropdown-btn w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center justify-between">
                        <span><i class="fas fa-hand-holding-usd mr-3 text-gray-400"></i>Tài chính</span>
                        <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                    </button>
                    <div class="mobile-dropdown-content">
                        <div class="grid grid-cols-1 gap-1 pl-2 pr-2">
                            <a href="{{ route('finance.index') }}" class="px-4 py-2 rounded-md hover:bg-gray-50 flex items-center gap-2"><i class="fas fa-info-circle text-gray-400"></i><span>Tổng quan</span></a>
                            <a href="{{ route('finance.calculator') }}" class="px-4 py-2 rounded-md hover:bg-gray-50 flex items-center gap-2"><i class="fas fa-calculator text-gray-400"></i><span>Máy tính trả góp</span></a>
                            <a href="{{ route('finance.requirements') }}" class="px-4 py-2 rounded-md hover:bg-gray-50 flex items-center gap-2"><i class="fas fa-clipboard-list text-gray-400"></i><span>Điều kiện vay</span></a>
                            <a href="{{ route('finance.faq') }}" class="px-4 py-2 rounded-md hover:bg-gray-50 flex items-center gap-2"><i class="fas fa-question-circle text-gray-400"></i><span>FAQ</span></a>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="button" class="mobile-dropdown-btn w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center justify-between">
                        <span><i class="fas fa-compass mr-3 text-gray-400"></i>Thông tin</span>
                        <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                    </button>
                    <div class="mobile-dropdown-content">
                        <div class="grid grid-cols-1 gap-1 pl-2 pr-2">
                            <a href="{{ route('blogs.index') }}" class="px-4 py-2 rounded-md hover:bg-gray-50 flex items-center gap-2"><i class="fas fa-newspaper text-gray-400"></i><span>Tin tức</span></a>
                            <a href="{{ route('about') }}" class="px-4 py-2 rounded-md hover:bg-gray-50 flex items-center gap-2"><i class="fas fa-info-circle text-gray-400"></i><span>Giới thiệu</span></a>
                            <a href="{{ route('contact') }}" class="px-4 py-2 rounded-md hover:bg-gray-50 flex items-center gap-2"><i class="fas fa-phone text-gray-400"></i><span>Liên hệ</span></a>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="pt-4 mt-2 border-t">
                    <div class="px-4 pb-2 text-sm text-gray-600 font-medium">Truy cập nhanh</div>
                    <a href="{{ route('user.cart.index') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 relative">
                        <i class="fas fa-shopping-cart mr-3 text-gray-400"></i>Giỏ hàng
                        <span id="cart-count-badge-mobile" class="cart-count absolute top-2 right-2 w-5 h-5 text-[10px] rounded-full bg-indigo-600 text-white flex items-center justify-center {{ $navCartCount > 0 ? 'flex' : 'hidden' }}">{{ $navCartCount > 99 ? '99+' : $navCartCount }}</span>
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 relative">
                        <i class="fas fa-heart mr-3 text-gray-400"></i>Danh sách yêu thích
                        <span id="wishlist-count-badge-mobile" class="wishlist-count absolute top-2 right-2 w-5 h-5 text-[10px] rounded-full bg-red-500 text-white flex items-center justify-center {{ $navWishlistCount > 0 ? 'flex' : 'hidden' }}">{{ $navWishlistCount > 99 ? '99+' : $navWishlistCount }}</span>
                    </a>
                    @auth
                    <a href="{{ route('notifications.index') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 relative">
                        <i class="fas fa-bell mr-3 text-gray-400"></i>Thông báo
                        <span class="absolute top-2 right-2 w-5 h-5 text-[10px] rounded-full bg-amber-500 text-white items-center justify-center {{ $navUnreadNotifCount ? 'flex' : 'hidden' }}">{{ $navUnreadNotifCount > 99 ? '99+' : $navUnreadNotifCount }}</span>
                    </a>
                    @endauth
                </div>

                {{-- Auth section --}}
                <div class="pt-4 mt-2 border-t">
                    @auth
                    <div class="px-4 pb-2 text-sm text-gray-600">Xin chào, <span class="font-semibold text-gray-900">{{ auth()->user()->name }}</span></div>
                    <a href="{{ route('user.profile.edit') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50"><i class="fas fa-user-cog mr-3 text-gray-400"></i>Tài khoản</a>
                    <a href="{{ route('user.customer-profiles.index') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50"><i class="fas fa-id-badge mr-3 text-gray-400"></i>Hồ sơ khách hàng</a>
                    <a href="{{ route('user.customer-profiles.orders') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50"><i class="fas fa-file-invoice-dollar mr-3 text-gray-400"></i>Đơn hàng</a>
                    <a href="{{ route('user.service-appointments.index') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50"><i class="fas fa-calendar-check mr-3 text-gray-400"></i>Lịch hẹn</a>
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50"><i class="fas fa-tachometer-alt mr-3 text-gray-400"></i>Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="px-4 pt-2">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-red-600 text-white hover:bg-red-700">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </button>
                    </form>
                    @else
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg border hover:bg-gray-50">
                            <i class="fas fa-sign-in-alt"></i> Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-gray-900 text-white hover:bg-gray-800">
                            <i class="fas fa-user-plus"></i> Đăng ký
                        </a>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>

{{-- Inline interactions for mobile drawer and search toggle --}}
<script>
    (function() {
        const openBtn = document.getElementById('open-drawer');
        const closeBtn = document.getElementById('close-drawer');
        const sheet = document.getElementById('mobile-menu-sheet');
        const panel = document.getElementById('drawer-panel');
        const backdrop = document.getElementById('drawer-backdrop');
        const mobileSearchToggle = document.getElementById('toggle-mobile-search');
        const mobileSearch = document.getElementById('mobile-search');

        function openSheet() {
            if (!sheet || !panel || !backdrop) return;
            sheet.classList.remove('hidden');
            requestAnimationFrame(() => {
                panel.classList.remove('translate-x-full');
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
            });
            sheet.setAttribute('aria-hidden', 'false');
        }

        function closeSheet() {
            if (!sheet || !panel || !backdrop) return;
            panel.classList.add('translate-x-full');
            backdrop.classList.add('opacity-0');
            backdrop.classList.remove('opacity-100');
            setTimeout(() => {
                sheet.classList.add('hidden');
                sheet.setAttribute('aria-hidden', 'true');
            }, 200);
        }

        function toggleMobileSearch() {
            if (!mobileSearch) return;
            mobileSearch.classList.toggle('show');
        }

        openBtn && openBtn.addEventListener('click', openSheet);
        closeBtn && closeBtn.addEventListener('click', closeSheet);
        backdrop && backdrop.addEventListener('click', closeSheet);
        // Close on ESC
        document.addEventListener('keydown', function(e){
            if (e.key === 'Escape') { closeSheet(); }
        });
        mobileSearchToggle && mobileSearchToggle.addEventListener('click', toggleMobileSearch);

        // Mobile nested dropdowns
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.mobile-dropdown-btn');
            if (!btn) return;
            const content = btn.parentElement.querySelector('.mobile-dropdown-content');
            if (!content) return;
            btn.classList.toggle('active');
            content.classList.toggle('show');
        });
    })();
</script>