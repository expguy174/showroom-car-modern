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
            <div class="flex items-center gap-3 min-w-[140px] h-16">
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
            <div class="center-menu hidden lg:flex items-center justify-center gap-1 text-[13px] flex-1 min-w-0 h-16">
                {{-- Hãng (giữ nguyên) --}}
                <div class="dropdown-group car-dropdown-wrapper" data-dropdown="brands">
                    <button class="px-2 py-2 rounded-lg text-sm text-gray-700 hover:text-white hover:bg-indigo-600 inline-flex items-center gap-2 transition-colors duration-200" data-dropdown-trigger>
                        <i class="fas fa-industry text-[15px]"></i>
                        <span class="nav-label">Hãng</span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"></i>
                    </button>
                    <div class="dropdown-bridge"></div>
                    <div class="dropdown-menu absolute left-0 mt-2 w-[720px] z-50 opacity-0 invisible scale-95 transition-all duration-200 transform origin-top-left pointer-events-auto" data-dropdown-menu>
                        <div class="p-3 grid grid-cols-3 gap-2">
                            @forelse($navBrands as $brand)
                            <a href="{{ route('car-brands.show', $brand->id) }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-white transition-colors duration-150">
                                @php
                                    $logoRaw = $brand->logo_url ?? $brand->logo_path ?? null;
                                    $logoSrc = $logoRaw ? (filter_var($logoRaw, FILTER_VALIDATE_URL) ? $logoRaw : asset('storage/'.ltrim($logoRaw,'/'))) : null;
                                @endphp
                                <img src="{{ $logoSrc }}" alt="{{ $brand->name }}" class="w-8 h-8 object-contain rounded" loading="lazy" onerror="this.onerror=null;this.src='https://placehold.co/40x40?text=%2B';" />
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
                    <div class="dropdown-menu absolute left-0 mt-2 min-w-[260px] z-50 opacity-0 invisible scale-95 transition-all duration-200 transform origin-top-left pointer-events-auto" data-dropdown-menu>
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
                    <div class="dropdown-menu absolute left-0 mt-2 min-w-[260px] z-50 opacity-0 invisible scale-95 transition-all duration-200 transform origin-top-left pointer-events-auto" data-dropdown-menu>
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
                    <div class="dropdown-menu absolute left-0 mt-2 min-w-[260px] z-50 opacity-0 invisible scale-95 transition-all duration-200 transform origin-top-left pointer-events-auto" data-dropdown-menu>
                        <div class="p-2 grid grid-cols-1 gap-1">
                            <a href="{{ route('blogs.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white transition-colors duration-150"><i class="fas fa-newspaper text-gray-400"></i><span>Tin tức</span></a>
                            <a href="{{ route('user.promotions.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white transition-colors duration-150"><i class="fas fa-tags text-gray-400"></i><span>Khuyến mãi</span></a>
                            <a href="{{ route('user.showrooms.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white transition-colors duration-150"><i class="fas fa-store text-gray-400"></i><span>Showroom</span></a>
                            <a href="{{ route('about') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white transition-colors duration-150"><i class="fas fa-info-circle text-gray-400"></i><span>Về chúng tôi</span></a>
                            <a href="{{ route('contact') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white transition-colors duration-150"><i class="fas fa-phone text-gray-400"></i><span>Liên hệ tư vấn</span></a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Search + Actions --}}
            <div id="nav-actions" class="flex items-center gap-1 sm:gap-2 shrink-0 h-16">
                {{-- Search (Desktop) --}}
                <form action="{{ route('products.index') }}" method="GET" class="hidden lg:flex items-center self-center h-16 m-0 mb-0">
                    <div class="relative h-full flex items-center">
                        <div class="flex items-center h-10 my-auto rounded-lg border border-gray-200 bg-white leading-none">
                            <span class="pl-3 pr-2 text-gray-400 flex items-center leading-none"><i class="fas fa-search"></i></span>
                            <input id="desktop-search-input" name="q" type="search" class="search-input h-10 w-48 xl:w-72 bg-transparent border-0 outline-none focus:ring-0 text-sm leading-none mb-0" placeholder="Tìm kiếm" autocomplete="off" />
                            <button type="submit" class="px-2 py-0.5 text-indigo-600 hover:text-indigo-800 leading-none">
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                        <div class="search-suggestions absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden z-50">
                            <div class="search-suggestions-list p-2 space-y-1"></div>
                            <div class="px-2 py-1 text-xs text-gray-500 border-t"><a href="{{ route('search.advanced') }}" class="hover:underline">Tìm kiếm nâng cao</a></div>
                        </div>
                    </div>
                </form>

                {{-- Mobile: Search button --}}
                <button id="toggle-mobile-search" type="button" class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-full text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">
                    <i class="fas fa-search"></i>
                    <span class="sr-only">Mở tìm kiếm</span>
                </button>



                {{-- Wishlist --}}
                <a href="{{ route('wishlist.index') }}" class="relative inline-flex items-center justify-center w-10 h-10 rounded-full text-gray-600 hover:text-red-600 hover:bg-red-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-500">
                    <i class="fas fa-heart"></i>
                    <span id="wishlist-count-badge" class="wishlist-count absolute -top-1 -right-1 w-5 h-5 text-[10px] rounded-full bg-red-500 text-white flex items-center justify-center {{ $navWishlistCount > 0 ? 'flex' : 'hidden' }}">{{ $navWishlistCount > 99 ? '99+' : $navWishlistCount }}</span>
                    <span class="sr-only">Danh sách yêu thích</span>
                </a>

                {{-- Cart --}}
                <a href="{{ route('user.cart.index') }}" class="relative inline-flex items-center justify-center w-10 h-10 rounded-full text-gray-600 hover:text-indigo-700 hover:bg-indigo-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">
                    <i class="fas fa-shopping-cart"></i>
                    <span id="cart-count-badge" class="cart-count absolute -top-1 -right-1 w-5 h-5 text-[10px] rounded-full bg-indigo-600 text-white flex items-center justify-center {{ $navCartCount > 0 ? 'flex' : 'hidden' }}">{{ $navCartCount > 99 ? '99+' : $navCartCount }}</span>
                    <span class="sr-only">Giỏ hàng</span>
                </a>

                {{-- Notifications (mobile dropdown) --}}
                @auth
                <div class="dropdown-group notif-dropdown lg:hidden" data-dropdown="notifications-mobile">
                    <button class="relative inline-flex items-center justify-center w-10 h-10 rounded-full text-gray-600 hover:bg-amber-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-500" data-dropdown-trigger>
                        <i class="fas fa-bell"></i>
                        <span id="notif-count-badge-mobile" class="notification-count absolute -top-1 -right-1 w-5 h-5 text-[10px] rounded-full bg-amber-500 text-white items-center justify-center {{ $navUnreadNotifCount ? 'flex' : 'hidden' }}">{{ $navUnreadNotifCount > 99 ? '99+' : $navUnreadNotifCount }}</span>
                        <span class="sr-only">Thông báo</span>
                    </button>
                    <div class="dropdown-bridge"></div>
                    <div class="dropdown-menu absolute right-0 mt-2 w-[320px] max-w-[90vw] z-50 opacity-0 invisible scale-95 transition-all duration-200 transform origin-top-right" data-dropdown-menu>
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                            <div class="px-4 py-3 flex items-center justify-between border-b">
                                <div class="text-sm font-semibold text-gray-800">Thông báo</div>
                                <button id="notif-mark-all-mobile" class="text-xs text-amber-700 hover:text-white hover:bg-amber-600 border border-amber-200 px-2 py-1 rounded">Đánh dấu đã đọc</button>
                            </div>
                            <div id="notif-menu-list-mobile" class="max-h-[50vh] overflow-auto divide-y">
                                <div class="p-4 text-sm text-gray-500 flex items-center gap-2"><i class="fas fa-spinner fa-spin"></i><span>Đang tải...</span></div>
                            </div>
                            <div class="px-4 py-3 border-t bg-gray-50 text-right">
                                <a href="{{ route('notifications.index') }}" class="text-sm text-amber-700 hover:underline">Xem tất cả</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endauth

                {{-- Mobile: Open Drawer button (to the right of cart) --}}
                <button id="open-drawer" type="button" class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-full text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">
                    <i class="fas fa-bars"></i>
                    <span class="sr-only">Mở menu</span>
                </button>

                {{-- Notifications (auth only) --}}
                @auth
                <div class="dropdown-group notif-dropdown hidden lg:block" data-dropdown="notifications">
                    <button class="relative inline-flex items-center justify-center w-10 h-10 rounded-full text-gray-600 hover:bg-amber-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-500" data-dropdown-trigger>
                        <i class="fas fa-bell"></i>
                        <span id="notif-count-badge" class="notification-count absolute -top-1 -right-1 w-5 h-5 text-[10px] rounded-full bg-amber-500 text-white items-center justify-center {{ $navUnreadNotifCount ? 'flex' : 'hidden' }}">{{ $navUnreadNotifCount > 99 ? '99+' : $navUnreadNotifCount }}</span>
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
                    <button class="px-2.5 py-2 rounded-lg border border-gray-200 text-sm text-gray-700 hover:text-white hover:bg-gray-900 inline-flex items-center gap-2" data-dropdown-trigger>
                        @auth
                            @php 
                                $profile = optional(auth()->user()->userProfile);
                                $raw = $profile->avatar_path;
                                $isAbs = function($p){ return preg_match('/^https?:\/\//i', (string)$p) === 1; };
                                $fallback = 'https://ui-avatars.com/api/?name='.urlencode($profile->name ?? (auth()->user()->email ?? 'User')).'&background=4f46e5&color=fff&size=64';
                                $avatar = $raw ? ($isAbs($raw) ? $raw : asset('storage/'.ltrim($raw,'/'))) : $fallback;
                            @endphp
                            <img src="{{ $avatar }}" alt="avatar" class="w-8 h-8 rounded-full object-cover" onerror="this.onerror=null;this.src='{{ $fallback }}';" />
                            <i class="fas fa-chevron-down text-xs"></i>
                        @else
                            <i class="fas fa-user-circle"></i>
                            <i class="fas fa-chevron-down text-xs"></i>
                        @endauth
                    </button>
                    <div class="dropdown-bridge"></div>
                    <div class="dropdown-menu profile-dropdown-menu absolute right-0 mt-2 min-w-[240px] z-50">
                        <div class="p-2">
                            @auth
                            <div class="px-3 py-2">
                                @php 
                                    $profile = optional(auth()->user()->userProfile);
                                    $name = $profile->name ?? 'Tài khoản';
                                    $customerType = in_array($profile->customer_type, ['new','returning','vip','prospect']) ? $profile->customer_type : 'new';
                                    $profileType = in_array($profile->profile_type, ['customer','employee']) ? $profile->profile_type : 'customer';
                                    $isVip = ($customerType === 'vip');
                                @endphp
                                <div>
                                    <div class="text-[11px] inline-flex items-center gap-1 text-indigo-600">
                                        @php
                                            $customerTypeLabel = match($customerType) {
                                                'new' => 'Tài khoản mới',
                                                'returning' => 'Tài khoản quay lại',
                                                'vip' => 'VIP',
                                                'prospect' => 'Tài khoản tiềm năng',
                                                default => 'Tài khoản mới',
                                            };
                                        @endphp
                                        <i class="fas fa-user"></i> {{ $customerTypeLabel }}
                                    </div>
                                    <div class="mt-1 text-sm font-semibold text-gray-900 truncate">Xin chào, {{ $name }}</div>
                                    <div class="mt-3 border-t border-gray-100"></div>
                                </div>
                            </div>
                            @if(auth()->user()->isStaff())
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white">
                                <span class="inline-flex items-center justify-center w-5"><i class="fas fa-briefcase text-gray-500 text-[14px]"></i></span>
                                <span class="flex-1">Khu vực làm việc</span>
                            </a>
                            @endif
                            <a href="{{ route('user.profile.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white">
                                <span class="inline-flex items-center justify-center w-5"><i class="fas fa-user text-gray-500 text-[14px]"></i></span>
                                <span class="flex-1">Tài khoản</span>
                            </a>
                            <a href="{{ route('user.order.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white">
                                <span class="inline-flex items-center justify-center w-5"><i class="fas fa-file-invoice text-gray-500 text-[14px]"></i></span>
                                <span class="flex-1">Đơn hàng</span>
                            </a>
                            <a href="{{ route('user.service-appointments.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white">
                                <span class="inline-flex items-center justify-center w-5"><i class="fas fa-tools text-gray-500 text-[14px]"></i></span>
                                <span class="flex-1">Bảo dưỡng</span>
                            </a>
                            <a href="{{ route('test-drives.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white">
                                <span class="inline-flex items-center justify-center w-5"><i class="fas fa-car-side text-gray-500 text-[14px]"></i></span>
                                <span class="flex-1">Lái thử</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2 px-3 py-2 rounded-md hover:bg-red-50 text-red-600">
                                    <span class="inline-flex items-center justify-center w-5"><i class="fas fa-sign-out-alt text-[14px]"></i></span><span class="flex-1">Đăng xuất</span>
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login', ['redirect' => request()->fullUrl()]) }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white"><i class="fas fa-sign-in-alt text-gray-400"></i><span>Đăng nhập</span></a>
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
                    <input name="q" type="search" class="search-input w-full rounded-lg border border-gray-200 pl-10 pr-12 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Tìm kiếm" autocomplete="off" />
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
                        <span><i class="fas fa-industry mr-3 text-gray-400"></i>Hãng</span>
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
                            <a href="{{ route('user.promotions.index') }}" class="px-4 py-2 rounded-md hover:bg-gray-50 flex items-center gap-2"><i class="fas fa-tags text-gray-400"></i><span>Khuyến mãi</span></a>
                            <a href="{{ route('user.showrooms.index') }}" class="px-4 py-2 rounded-md hover:bg-gray-50 flex items-center gap-2"><i class="fas fa-store text-gray-400"></i><span>Showroom</span></a>
                            <a href="{{ route('about') }}" class="px-4 py-2 rounded-md hover:bg-gray-50 flex items-center gap-2"><i class="fas fa-info-circle text-gray-400"></i><span>Về chúng tôi</span></a>
                            <a href="{{ route('contact') }}" class="px-4 py-2 rounded-md hover:bg-gray-50 flex items-center gap-2"><i class="fas fa-phone text-gray-400"></i><span>Liên hệ tư vấn</span></a>
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
                        <span class="notification-count absolute top-2 right-2 w-5 h-5 text-[10px] rounded-full bg-amber-500 text-white items-center justify-center {{ $navUnreadNotifCount ? 'flex' : 'hidden' }}">{{ $navUnreadNotifCount > 99 ? '99+' : $navUnreadNotifCount }}</span>
                    </a>
                    @endauth
                </div>

                {{-- Auth section --}}
                <div class="pt-4 mt-2 border-t">
                    @auth
                    <div class="px-4 pb-2 text-sm text-gray-600">Xin chào, <span class="font-semibold text-gray-900">{{ optional(auth()->user()->userProfile)->name ?? 'Khách' }}</span></div>
                    @if(auth()->user()->isStaff())
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-5"><i class="fas fa-briefcase text-gray-500 text-[14px]"></i></span>
                        <span class="flex-1">Khu vực làm việc</span>
                    </a>
                    @endif
                    <a href="{{ route('user.profile.index') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-5"><i class="fas fa-user text-gray-500 text-[14px]"></i></span>
                        <span class="flex-1">Tài khoản</span>
                    </a>
                    <a href="{{ route('user.order.index') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-5"><i class="fas fa-file-invoice text-gray-500 text-[14px]"></i></span>
                        <span class="flex-1">Đơn hàng</span>
                    </a>
                    <a href="{{ route('user.service-appointments.index') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-5"><i class="fas fa-tools text-gray-500 text-[14px]"></i></span>
                        <span class="flex-1">Bảo dưỡng</span>
                    </a>
                    <a href="{{ route('test-drives.index') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-5"><i class="fas fa-car-side text-gray-500 text-[14px]"></i></span>
                        <span class="flex-1">Lái thử</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 rounded-lg hover:bg-red-50 text-red-600 flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-5"><i class="fas fa-sign-out-alt text-[14px]" style="color: #dc2626 !important;"></i></span>
                            <span class="flex-1">Đăng xuất</span>
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login', ['redirect' => request()->fullUrl()]) }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-5"><i class="fas fa-sign-in-alt text-gray-500 text-[14px]"></i></span>
                        <span class="flex-1">Đăng nhập</span>
                    </a>
                    <a href="{{ route('register') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-5"><i class="fas fa-user-plus text-gray-500 text-[14px]"></i></span>
                        <span class="flex-1">Đăng ký</span>
                    </a>
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

    // Notification UI helpers
    window.refreshNotifBadge = async function(){
        @auth
        try{
            const res = await fetch(`{{ route('notifications.unread-count') }}`, { headers: { 'X-Requested-With':'XMLHttpRequest' } });
            const data = await res.json().catch(()=>({}));
            if (res.ok && data && data.data){
                const count = data.data.unread_count || 0;
                
                // Update localStorage
                localStorage.setItem('notification_count', count);
                
                // Update CountSystem
                if (window.CountSystem) {
                    window.CountSystem.updateNotifications(count);
                }
                
                // Update nav badges
                const desktop = document.getElementById('notif-count-badge');
                const mobile = document.getElementById('notif-count-badge-mobile');
                [desktop, mobile].forEach(el => {
                    if (!el) return;
                    el.textContent = (count > 99) ? '99+' : count;
                    el.classList.toggle('flex', count > 0);
                    el.classList.toggle('hidden', !(count > 0));
                });
            }
        }catch{}
        @endauth
    };

    window.prependNotifItem = function(title, message){
        const html = `<div class="p-3 text-sm">
            <div class="font-semibold text-gray-800">${title}</div>
            <div class="text-gray-600 mt-0.5">${message}</div>
        </div>`;
        const desktopList = document.getElementById('notif-menu-list');
        const mobileList = document.getElementById('notif-menu-list-mobile');
        [desktopList, mobileList].forEach(list => {
            if (!list) return;
            // Remove loading row if present
            const first = list.firstElementChild;
            if (first && first.querySelector('.fa-spinner')){
                list.innerHTML = '';
            }
            const wrapper = document.createElement('div');
            wrapper.innerHTML = html;
            list.prepend(wrapper.firstElementChild);
        });
    };

    // Fetch and render latest notifications (top 5)
    window.refreshNotifList = async function(){
        try{
            const res = await fetch(`{{ route('notifications.index') }}`, { headers: { 'X-Requested-With':'XMLHttpRequest' } });
            const data = await res.json().catch(()=>({}));
            const items = (data && data.data && (data.data.data || data.data)) ? (data.data.data || data.data) : [];
            const render = (listEl)=>{
                if (!listEl) return;
                listEl.innerHTML = '';
                if (!items.length){
                    listEl.innerHTML = '<div class="p-4 text-sm text-gray-500">Chưa có thông báo</div>';
                    return;
                }
                items.slice(0,5).forEach(n => {
                    const row = document.createElement('div');
                    row.className = 'p-3 text-sm hover:bg-gray-50';
                    row.setAttribute('data-notif-item', 'true');
                    const isRead = n.is_read;
                    row.innerHTML = `
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 ${isRead ? 'bg-gray-100 text-gray-400' : 'bg-amber-50 text-amber-600'}">
                                <i class="fas fa-bell text-xs"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-semibold ${isRead ? 'text-gray-700' : 'text-gray-900'}">${n.title || 'Thông báo'}</div>
                                <div class="text-gray-600 mt-0.5">${n.message || ''}</div>
                            </div>
                        </div>
                    `;
                    listEl.appendChild(row);
                });
            };
            render(document.getElementById('notif-menu-list'));
            render(document.getElementById('notif-menu-list-mobile'));
        }catch{}
    };

    // Notification polling handled by Count System background sync - no duplicate timer needed

    // Load list when user opens notification dropdown
    document.addEventListener('click', function(e){
        const btn = e.target.closest('.notif-dropdown [data-dropdown-trigger]');
        if (!btn) return;
        @auth
        if (window.refreshNotifList) window.refreshNotifList();
        if (window.refreshNotifBadge) window.refreshNotifBadge();
        @endauth
    });

    // Handle mark all as read in dropdown
    @auth
    document.addEventListener('click', async function(e){
        const markAllBtn = e.target.closest('#notif-mark-all, #notif-mark-all-mobile');
        if (!markAllBtn) return;
        
        e.preventDefault();
        
        // Optimistic update - set count to 0 immediately
        if (window.CountSystem) {
            window.CountSystem.updateNotifications(0);
        }
        
        // Disable button during request
        const originalText = markAllBtn.textContent;
        markAllBtn.disabled = true;
        markAllBtn.textContent = 'Đang xử lý...';
        
        try {
            const res = await fetch(`{{ route('notifications.read-all') }}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            if (res.ok) {
                // Update dropdown UI - mark all as read
                const desktopList = document.getElementById('notif-menu-list');
                const mobileList = document.getElementById('notif-menu-list-mobile');
                
                [desktopList, mobileList].forEach(list => {
                    if (!list) return;
                    // Update all notification items to read state
                    list.querySelectorAll('[data-notif-item]').forEach(item => {
                        const icon = item.querySelector('.w-8.h-8, .w-10.h-10');
                        if (icon) {
                            icon.classList.remove('bg-amber-50', 'text-amber-600');
                            icon.classList.add('bg-gray-100', 'text-gray-400');
                        }
                        const title = item.querySelector('.font-semibold');
                        if (title) {
                            title.classList.remove('text-gray-900');
                            title.classList.add('text-gray-700');
                        }
                    });
                });
                
                // Confirm with server after delay
                setTimeout(() => {
                    if (window.refreshNotifBadge) window.refreshNotifBadge();
                }, 200);
                
                if (typeof window.showMessage === 'function') {
                    window.showMessage('Đã đánh dấu tất cả thông báo là đã đọc', 'success');
                }
            } else {
                // Rollback optimistic update on error
                if (window.CountSystem) {
                    // Refresh from server to get correct count
                    setTimeout(() => {
                        if (window.refreshNotifBadge) window.refreshNotifBadge();
                    }, 100);
                }
                throw new Error('Failed to mark as read');
            }
        } catch (error) {
            if (typeof window.showMessage === 'function') {
                window.showMessage('Không thể đánh dấu đã đọc. Vui lòng thử lại.', 'error');
            }
        } finally {
            markAllBtn.disabled = false;
            markAllBtn.textContent = originalText;
        }
    });
    @endauth
</script>