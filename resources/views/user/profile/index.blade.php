@extends('layouts.app')

@section('title', 'Trung tâm tài khoản')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Modern Header (non-sticky) -->
    <div class="bg-white shadow-sm border-b border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-cog text-white text-lg sm:text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Trung tâm tài khoản</h1>
                        <p class="text-sm sm:text-base text-gray-600">Quản lý tài khoản và hồ sơ khách hàng</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center text-blue-600 hover:text-blue-700 font-medium text-sm sm:text-base transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i>
                        <span class="hidden sm:inline">Trang chủ</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Account Section -->
            <div id="panel-account" class="block">
                {{-- FULL content from user/profile/edit.blade.php to keep UI/UX identical --}}
                <!-- Modern Profile Overview Section -->
                <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-3xl shadow-2xl mb-8 overflow-hidden relative">
                    <!-- Enhanced Background Pattern -->
                        <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-0 left-0 w-32 h-32 bg-white rounded-full -translate-x-16 -translate-y-16"></div>
                        <div class="absolute top-1/2 right-0 w-20 h-20 bg-white rounded-full translate-x-10 -translate-y-10"></div>
                        <div class="absolute bottom-0 left-1/3 w-16 h-16 bg-white rounded-full translate-y-8"></div>
                        <div class="absolute top-1/4 right-1/4 w-8 h-8 bg-white rounded-full"></div>
                        </div>
                    
                    <div class="relative p-6 sm:p-8">
                        <div class="flex flex-col items-center lg:flex-row lg:items-center gap-4 lg:gap-6">
                            <!-- Avatar Section with improved design -->
                            <div class="relative flex-shrink-0">
                                    @php
                                    $isAbs = function($p){ return preg_match('/^https?:\/\//i', (string)$p) === 1; };
                                    $fallback = 'https://ui-avatars.com/api/?name='.urlencode(optional($user->userProfile)->name ?? ($user->email ?? 'User')).'&background=4f46e5&color=fff&size=128';
                                    $profileAvatar = optional($user->userProfile)->avatar_path;
                                    $avatar = $profileAvatar ? ($isAbs($profileAvatar) ? $profileAvatar : asset('storage/'.$profileAvatar)) : $fallback;
                                    @endphp
                                    <div class="relative">
                                        <div class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden ring-4 ring-white shadow-2xl">
                                            <img id="profile-avatar" src="{{ $avatar }}" class="absolute inset-0 w-full h-full object-cover" alt="Avatar" onerror="this.onerror=null;this.src='{{ $fallback }}';">
                                        </div>
                                        <!-- Verified tick (outside clip to guarantee visibility) -->
                                        <div class="absolute bottom-0 right-0 translate-x-1 translate-y-1 w-5 h-5 bg-emerald-500 rounded-full border-2 border-white flex items-center justify-center shadow-md z-20 pointer-events-none">
                                            <i class="fas fa-check text-white text-[10px] leading-none"></i>
                                    </div>
                                </div>
                                        </div>
                            
                            <!-- User Info with cleaner layout -->
                            <div class="flex-1 min-w-0 text-center lg:text-left">
                                <h2 class="text-2xl sm:text-3xl font-bold text-white mb-1 sm:mb-2 truncate" data-field="profile_name">{{ optional($user->userProfile)->name ?? 'Khách' }}</h2>
                                <p class="text-indigo-100 text-sm sm:text-base truncate">{{ $user->email }}</p>
                                        </div>
                            
                            <!-- Quick Actions with improved responsive design -->
                            <div class="flex flex-col sm:flex-row lg:flex-col gap-3 flex-shrink-0">
                                <button class="bg-white/20 backdrop-blur-sm text-white px-4 py-3 rounded-xl border border-white/30 hover:bg-white/30 transition-all duration-300 text-sm font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5" data-avatar-upload>
                                    <i class="fas fa-camera mr-2"></i>
                                    <span class="hidden sm:inline">Cập nhật ảnh</span>
                                    <span class="sm:hidden">Ảnh</span>
                                </button>
                                <button class="bg-white/20 backdrop-blur-sm text-white px-4 py-3 rounded-xl border border-white/30 hover:bg-white/30 transition-all duration-300 text-sm font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5" data-modal-trigger="change-password-modal">
                                    <i class="fas fa-key mr-2"></i>
                                    <span class="hidden sm:inline">Đổi mật khẩu</span>
                                    <span class="sm:hidden">Mật khẩu</span>
                                </button>
                                        </div>
                            <!-- Hidden avatar upload form (static) -->
                            <form id="avatar-upload-form" action="{{ route('user.profile.avatar.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                                @csrf
                                @method('patch')
                                <input id="avatar-uploader" type="file" name="avatar" accept="image/*">
                            </form>
                            <!-- Hidden avatar upload form (file chooser without modal) -->
                            <form id="avatar-upload-form" action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                                @csrf
                                @method('patch')
                                <input id="avatar-uploader" type="file" name="avatar" accept="image/*">
                            </form>
                                    </div>
                                </div>
                                </div>

                <!-- Optimized Main Content Grid -->
                <div class="grid grid-cols-1 xl:grid-cols-4 gap-6 lg:gap-8">
                    <!-- Left Sidebar - Sticky on desktop -->
                    <div class="xl:col-span-1 space-y-6">
                        <!-- Quick Stats Card -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden lg:sticky lg:top-24">
                            <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-blue-50">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-chart-line text-blue-600"></i>
                                    Thống kê nhanh
                                </h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="flex items-center justify-between p-3 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                            <i class="fas fa-shopping-bag text-sm"></i>
                            </div>
                                        <div>
                                            <div class="text-sm text-gray-600">Tổng đơn hàng</div>
                                            <div class="text-lg font-bold text-gray-900">{{ $orders->count() }}</div>
                        </div>
                    </div>
                </div>

                                <div class="flex items-center justify-between p-3 rounded-xl bg-gradient-to-r from-emerald-50 to-green-50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                            <i class="fas fa-map-marker-alt text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm text-gray-600">Địa chỉ</div>
                                            <div class="text-lg font-bold text-gray-900">{{ $user->addresses()->count() }}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between p-3 rounded-xl bg-gradient-to-r from-purple-50 to-pink-50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                                            <i class="fas fa-calendar text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm text-gray-600">Ngày tham gia</div>
                                            <div class="text-lg font-bold text-gray-900">{{ $user->created_at->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content Column -->
                    <div class="xl:col-span-3 space-y-6 lg:space-y-8">
                        <!-- Enhanced Address Book -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-blue-50">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-address-book text-blue-600"></i>
                                        Sổ địa chỉ
                                    </h3>
                                    <a href="{{ route('user.addresses.index') }}" class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors duration-200">
                                        Quản lý 
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="p-6">
                                @if($user->addresses()->count() > 0)
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                                    @foreach($user->addresses()->orderByDesc('is_default')->take(4)->get() as $addr)
                                    <div class="group p-5 rounded-2xl border border-gray-200 bg-gradient-to-br from-gray-50 to-blue-50 hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 hover:shadow-lg hover:-translate-y-1" data-address-card data-address-id="{{ $addr->id }}">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                                    <i class="fas fa-map-marker-alt text-sm"></i>
                                                </div>
                                                <div>
                                            <div class="text-sm font-semibold text-gray-800" data-address-header>{{ $addr->contact_name }}</div>
                                                    <div class="text-xs text-gray-500">Liên hệ</div>
                                                </div>
                                            </div>
                                            <div class="addr-action shrink-0">
                                            @if($addr->is_default)
                                                <span class="addr-default-badge px-3 py-1 text-xs rounded-full bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-700 font-medium border border-emerald-200">
                                                    <i class="fas fa-star mr-1"></i>Mặc định
                                                </span>
                                                @else
                                                <form action="{{ route('user.addresses.set-default', $addr->id) }}" method="POST" class="js-set-default-form inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 text-xs rounded-full border border-gray-300 text-gray-700 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-700 transition-all duration-300">
                                                        <i class="fas fa-check-circle text-emerald-600"></i>
                                                        Đặt mặc định
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        </div>
                                        
                                        <div class="space-y-3 mb-4">
                                            <div class="text-sm text-gray-700 leading-relaxed flex items-start gap-2">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px] font-medium">Địa chỉ</span>
                                                <span class="text-gray-700">{{ $addr->address }}</span>
                                            </div>
                                            @if($addr->city || $addr->state)
                                            <div class="flex flex-wrap items-center gap-2">
                                                @php
                                                    $__typeRaw = $addr->type ?? ($addr->address_type ?? ($addr->label ?? null));
                                                    $__typeKey = is_string($__typeRaw) ? strtolower(trim($__typeRaw)) : null;
                                                    $__typeLabel = match($__typeKey){
                                                        'home' => 'Nhà riêng',
                                                        'work' => 'Cơ quan',
                                                        'office' => 'Cơ quan',
                                                        'billing' => 'Thanh toán',
                                                        'shipping' => 'Giao hàng',
                                                        'other' => 'Khác',
                                                        default => ($__typeRaw ?: null)
                                                    };
                                                    $__countryRaw = $addr->country ?? 'Vietnam';
                                                    $__countryLabel = match(strtolower($__countryRaw)){
                                                        'vietnam','viet nam' => 'Việt Nam',
                                                        'united states','us','usa' => 'Hoa Kỳ',
                                                        'japan' => 'Nhật Bản',
                                                        default => ($__countryRaw ?: '')
                                                    };
                                                @endphp
                                                @if($addr->state)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100 text-[11px]">
                                                    <i class="fas fa-landmark text-[10px]"></i>
                                                    <span>Quận/Huyện: {{ $addr->state }}</span>
                                                </span>
                                                @endif
                                            @if($addr->city)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 text-[11px]">
                                                    <i class="fas fa-city text-[10px]"></i>
                                                    <span>Tỉnh/Thành phố: {{ $addr->city }}</span>
                                                </span>
                                            @endif
                                                @if($__typeLabel)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 border border-sky-100 text-[11px]">
                                                    <i class="fas fa-tag text-[10px]"></i>
                                                    <span>Kiểu: {{ $__typeLabel }}</span>
                                                </span>
                                                @endif
                                                
                                        </div>
                                        @endif
                                        </div>

                                        <div class="text-xs text-gray-600 mb-4 flex items-center gap-2">
                                            <i class="fas fa-phone text-gray-400"></i>
                                            <span>Điện thoại: {{ $addr->phone }}</span>
                                        </div>
                                        @if($addr->notes)
                                        <div class="text-xs text-gray-600 mb-4 flex items-start gap-2">
                                            <i class="fas fa-sticky-note text-gray-400 mt-0.5"></i>
                                            <span>Ghi chú: {{ $addr->notes }}</span>
                                        </div>
                                        @endif
                                        
                                        
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full mx-auto mb-6 flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-blue-500 text-2xl"></i>
                                    </div>
                                    <div class="text-gray-600 mb-2 font-medium text-lg">Chưa có địa chỉ nào</div>
                                    <div class="text-gray-500 mb-6 text-sm max-w-md mx-auto">Thêm địa chỉ để dễ dàng mua sắm và nhận hàng nhanh chóng</div>
                                    <a href="{{ route('user.addresses.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                        <i class="fas fa-plus"></i>
                                        Thêm địa chỉ đầu tiên
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>

                        
                    </div>
                </div>

                {{-- Modals (inlined from user/profile/edit.blade.php) --}}
                <div id="edit-profile-modal" class="modal-overlay" style="position:fixed;inset:0;background:rgba(0,0,0,.5);display:none;align-items:center;justify-content:center;z-index:10000">
                  <div class="modal-panel">
                    <div class="modal-header">
                      <div class="font-semibold text-gray-900">Cập nhật thông tin</div>
                      <button type="button" data-modal-close class="text-gray-500 hover:text-red-500"><i class="fas fa-times"></i></button>
                    </div>
                    <form id="edit-profile-form" method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
                      @csrf
                      @method('patch')
                      <div class="modal-body space-y">
                        <div>
                          <label class="block text-sm text-gray-700">Ảnh đại diện</label>
                          <div class="flex items-center gap-3">
                            <img id="avatar-preview" src="{{ $user->avatar_path ? (Str::startsWith($user->avatar_path, ['http://','https://']) ? $user->avatar_path : asset('storage/'.$user->avatar_path)) : 'https://ui-avatars.com/api/?name='.urlencode(optional($user->userProfile)->name ?? ($user->email ?? 'User')).'&background=4f46e5&color=fff&size=96' }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
                            <input id="avatar-input" class="form-input" type="file" name="avatar" accept="image/*" form="edit-profile-form">
                          </div>
                          @error('avatar')<div class="error-text">{{ $message }}</div>@enderror
                          <div class="text-[11px] text-gray-500 mt-1">Hỗ trợ jpg/png/webp (tối đa 2MB).</div>
                        </div>
                        <div>
                          <label class="block text-sm text-gray-700">Họ tên</label>
                          <input class="form-input" name="name" type="text" value="{{ old('name', optional($user->userProfile)->name) }}" required>
                          @error('name')<div class="error-text">{{ $message }}</div>@enderror
                        </div>
                        <div>
                          <label class="block text-sm text-gray-700">Số điện thoại</label>
                          <input class="form-input" name="phone" type="text" value="{{ old('phone', $user->phone) }}" placeholder="Ví dụ: +84 90 123 4567">
                          @error('phone')<div class="error-text">{{ $message }}</div>@enderror
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                          <div>
                            <label class="block text-sm text-gray-700">Ngày sinh</label>
                            <input class="form-input" name="date_of_birth" type="date" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}">
                            @error('date_of_birth')<div class="error-text">{{ $message }}</div>@enderror
                          </div>
                          <div>
                            <label class="block text-sm text-gray-700">Giới tính</label>
                            <select name="gender" class="form-input">
                              <option value="">-- Chọn --</option>
                              <option value="male" @selected(old('gender', $user->gender)==='male')>Nam</option>
                              <option value="female" @selected(old('gender', $user->gender)==='female')>Nữ</option>
                              <option value="other" @selected(old('gender', $user->gender)==='other')>Khác</option>
                            </select>
                            @error('gender')<div class="error-text">{{ $message }}</div>@enderror
                          </div>
                          <div>
                            <label class="block text-sm text-gray-700">Quốc tịch</label>
                            <input class="form-input" name="nationality" type="text" value="{{ old('nationality', $user->nationality) }}" placeholder="Ví dụ: Việt Nam">
                            @error('nationality')<div class="error-text">{{ $message }}</div>@enderror
                          </div>
                        </div>
                        <div>
                          <label class="block text-sm text-gray-700">Email (tên đăng nhập)</label>
                          <input class="form-input" name="email" type="email" value="{{ old('email', $user->email) }}" required readonly>
                          <div class="text-[11px] text-gray-500 mt-1">Email là tên tài khoản đăng nhập. Nếu cần đổi, vui lòng liên hệ hỗ trợ.</div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" data-modal-close class="btn btn-outline">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                      </div>
                    </form>
                  </div>
                </div>

                <div id="change-password-modal" class="modal-overlay" style="position:fixed;inset:0;background:rgba(0,0,0,.5);display:none;align-items:center;justify-content:center;z-index:10000">
                  <div class="modal-panel">
                    <div class="modal-header">
                      <div class="font-semibold text-gray-900">Đổi mật khẩu</div>
                      <button type="button" data-modal-close class="text-gray-500 hover:text-red-500"><i class="fas fa-times"></i></button>
                    </div>
                    <form method="POST" action="{{ route('password.update') }}">
                      @csrf
                      @method('put')
                      <div class="modal-body space-y">
                        <div>
                          <label class="block text-sm text-gray-700">Mật khẩu hiện tại</label>
                          <input class="form-input" name="current_password" type="password" autocomplete="current-password">
                          @error('current_password')<div class="error-text">{{ $message }}</div>@enderror
                        </div>
                        <div>
                          <label class="block text-sm text-gray-700">Mật khẩu mới</label>
                          <input class="form-input" name="password" type="password" autocomplete="new-password">
                          @error('password')<div class="error-text">{{ $message }}</div>@enderror
                        </div>
                        <div>
                          <label class="block text-sm text-gray-700">Xác nhận mật khẩu mới</label>
                          <input class="form-input" name="password_confirmation" type="password" autocomplete="new-password">
                          @error('password_confirmation')<div class="error-text">{{ $message }}</div>@enderror
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" data-modal-close class="btn btn-outline">Hủy</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                      </div>
                    </form>
                  </div>
                </div>
            </div>

            <!-- Enhanced Profile Section -->
            <div id="panel-profile" class="block mt-8 lg:mt-12">
                <!-- Optimized Main Content Grid -->
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 lg:gap-8">
                    <!-- Main Content -->
                    <div class="xl:col-span-2 space-y-6 lg:space-y-8">
                        <!-- Summary from user_profiles schema -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-indigo-50">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-user-tag text-indigo-600"></i>
                                    Thông tin hồ sơ
                                </h3>
                                    <button class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-700 font-medium transition-colors duration-200 hover:bg-indigo-50 px-3 py-2 rounded-lg" data-modal-trigger="edit-profile-info-modal">
                                        <i class="fas fa-edit"></i>
                                        Cập nhật
                                    </button>
                                </div>
                            </div>
                            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center"><i class="fas fa-id-badge"></i></div>
                                    <div>
                                        <div class="text-xs text-gray-500">Loại hồ sơ</div>
                                        <div class="text-sm font-medium text-gray-900">{{ ($customerProfile->profile_type ?? 'customer') === 'employee' ? 'Nhân viên' : 'Khách hàng' }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                                    <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center"><i class="fas fa-user"></i></div>
                                    @php
                                        $__ct = $customerProfile->customer_type ?? 'new';
                                        $__ctLabel = match($__ct){
                                            'new' => 'Tài khoản mới',
                                            'returning' => 'Tài khoản quay lại',
                                            'vip' => 'VIP',
                                            'prospect' => 'Tài khoản tiềm năng',
                                            default => 'Tài khoản mới'
                                        };
                                    @endphp
                                    <div>
                                        <div class="text-xs text-gray-500">Loại tài khoản</div>
                                        <div class="text-sm font-medium text-gray-900">{{ $__ctLabel }}</div>
                                    </div>
                                </div>
                                @if($customerProfile->birth_date)
                                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center"><i class="fas fa-birthday-cake"></i></div>
                                    <div>
                                        <div class="text-xs text-gray-500">Ngày sinh</div>
                                        <div class="text-sm font-medium text-gray-900" data-field="birth_date">{{ $customerProfile->birth_date->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                                @endif
                                @if($customerProfile->gender)
                                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                                    <div class="w-8 h-8 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center"><i class="fas fa-venus-mars"></i></div>
                                    <div>
                                        <div class="text-xs text-gray-500">Giới tính</div>
                                        <div class="text-sm font-medium text-gray-900" data-field="gender">{{ $customerProfile->gender_display ?? $customerProfile->gender }}</div>
                                    </div>
                                </div>
                                @endif
                                @if($customerProfile->purchase_purpose)
                                <div class="md:col-span-2 flex items-start gap-3 p-3 rounded-lg bg-gray-50">
                                    <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center"><i class="fas fa-bullseye"></i></div>
                                    <div class="min-w-0">
                                        <div class="text-xs text-gray-500">Mục đích mua</div>
                                        <div class="text-sm font-medium text-gray-900 break-words" data-field="purchase_purpose">{{ $customerProfile->purchase_purpose }}</div>
                                    </div>
                                </div>
                                @endif
                                
                                @if(($customerProfile->profile_type ?? 'customer') === 'employee')
                                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                                    <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center"><i class="fas fa-donate"></i></div>
                                    <div>
                                        <div class="text-xs text-gray-500">Lương (nhân viên)</div>
                                        <div class="text-sm font-medium text-gray-900">{{ $customerProfile->employee_salary ? (number_format($customerProfile->employee_salary).' VNĐ') : '—' }}</div>
                                    </div>
                                </div>
                                @endif
                                @if(($customerProfile->profile_type ?? 'customer') === 'employee' && $customerProfile->employee_skills)
                                <div class="md:col-span-2 flex items-start gap-3 p-3 rounded-lg bg-gray-50">
                                    <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center"><i class="fas fa-tools"></i></div>
                                    <div class="min-w-0">
                                        <div class="text-xs text-gray-500">Kỹ năng (nhân viên)</div>
                                        <div class="text-sm font-medium text-gray-900 break-words">{{ $customerProfile->employee_skills }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        

                        <!-- Driver License Information -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-emerald-50">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-id-card text-emerald-600"></i>
                                    Thông tin bằng lái xe
                                </h3>
                                    <button class="inline-flex items-center gap-2 text-sm text-emerald-600 hover:text-emerald-700 font-medium transition-colors duration-200 hover:bg-emerald-50 px-3 py-2 rounded-lg" data-modal-trigger="edit-driver-license-modal">
                                        <i class="fas fa-edit"></i>
                                        Cập nhật
                                    </button>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        @if($customerProfile->driver_license_number ?? null)
                                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                                <i class="fas fa-id-card text-sm"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="text-xs text-gray-500">Số bằng lái</div>
                                                <div class="text-sm font-medium text-gray-900" data-field="driver_license_number">{{ $customerProfile->driver_license_number }}</div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($customerProfile->driver_license_issue_date ?? null)
                                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                                            <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                                                <i class="fas fa-calendar-plus text-sm"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="text-xs text-gray-500">Ngày cấp</div>
                                                <div class="text-sm font-medium text-gray-900" data-field="driver_license_issue_date">{{ $customerProfile->driver_license_issue_date->format('d/m/Y') }}</div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="space-y-4">
                                        @if($customerProfile->driver_license_expiry_date ?? null)
                                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                                            <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                                                <i class="fas fa-calendar-times text-sm"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="text-xs text-gray-500">Ngày hết hạn</div>
                                                <div class="text-sm font-medium text-gray-900" data-field="driver_license_expiry_date">{{ $customerProfile->driver_license_expiry_date->format('d/m/Y') }}</div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($customerProfile->driving_experience_years ?? null)
                                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                                            <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                                                <i class="fas fa-car text-sm"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="text-xs text-gray-500">Kinh nghiệm lái xe</div>
                                                <div class="text-sm font-medium text-gray-900" data-field="driving_experience_years">{{ $customerProfile->driving_experience_years }} năm</div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        </div>

                    <!-- Enhanced Sidebar -->
                    <div class="xl:col-span-1 space-y-6">
                        <!-- Enhanced Preferences Summary -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden lg:sticky lg:top-24">
                            <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-pink-50">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-heart text-pink-600"></i>
                                    Sở thích
                                </h3>
                                    <button class="inline-flex items-center gap-2 text-sm text-pink-600 hover:text-pink-700 font-medium transition-colors duration-200 hover:bg-pink-50 px-3 py-2 rounded-lg" data-modal-trigger="edit-preferences-modal">
                                        <i class="fas fa-edit"></i>
                                        Cập nhật
                                    </button>
                            </div>
                            </div>
                            <div class="p-6 space-y-6">
                                @php
                                    $__carTypesRaw = $customerProfile->preferred_car_types ?? null;
                                    $__carTypes = is_array($__carTypesRaw) ? $__carTypesRaw : (is_string($__carTypesRaw) ? (json_decode($__carTypesRaw, true) ?: []) : []);
                                    $__brandsRaw = $customerProfile->preferred_brands ?? null;
                                    $__brands = is_array($__brandsRaw) ? $__brandsRaw : (is_string($__brandsRaw) ? (json_decode($__brandsRaw, true) ?: []) : []);
                                @endphp
                                
                                @if(!empty($__carTypes))
                                <div class="space-y-3">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-car text-pink-500 text-sm"></i>
                                        <div class="text-sm font-medium text-gray-700">Loại xe ưa thích</div>
                                    </div>
                                    <div class="flex flex-wrap gap-2" data-field="preferred_car_types">
                                        @foreach($__carTypes as $type)
                                            <span class="inline-block bg-gradient-to-r from-pink-100 to-rose-100 text-pink-700 text-xs px-3 py-1.5 rounded-full font-medium border border-pink-200">
                                                {{ $type }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                @if(!empty($__brands))
                                <div class="space-y-3">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-star text-blue-500 text-sm"></i>
                                        <div class="text-sm font-medium text-gray-700">Hãng xe ưa thích</div>
                                    </div>
                                    <div class="flex flex-wrap gap-2" data-field="preferred_brands">
                                        @foreach($__brands as $brand)
                                            <span class="inline-block bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 text-xs px-3 py-1.5 rounded-full font-medium border border-blue-200">
                                                {{ $brand }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                @if(($customerProfile->budget_min ?? null) && ($customerProfile->budget_max ?? null))
                                <div class="space-y-3">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-wallet text-emerald-500 text-sm"></i>
                                        <div class="text-sm font-medium text-gray-700">Khoảng giá</div>
                                    </div>
                                    <div class="p-3 rounded-xl bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200">
                                        <div class="text-sm font-semibold text-gray-900" data-field="preferences_budget">
                                            {{ number_format($customerProfile->budget_min) }} - {{ number_format($customerProfile->budget_max) }} VNĐ
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                            </div>
                        </div>

                        

                        
                    </div>
                </div>
                <!-- END full content -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>

  // Toast helper uses global showMessage if present
  function showToast(message, type = 'success') {
    if (typeof window.showMessage === 'function') { window.showMessage(message, type); return; }
    alert(message);
  }

  // Modal open/close delegation
  (function(){
    function bindModal(id){
      const overlay = document.getElementById(id);
      if (!overlay) return;
      overlay.addEventListener('click', e => { if (e.target === overlay) { overlay.classList.remove('show'); overlay.style.display = 'none'; } });
      overlay.querySelectorAll('[data-modal-close]').forEach(btn => btn.addEventListener('click', () => { overlay.classList.remove('show'); overlay.style.display = 'none'; }));
    }
    
    // Make bindModal globally available
    window.bindModal = bindModal;
    
    document.addEventListener('click', function(e){
      const trg = e.target.closest('[data-modal-trigger]');
      if (!trg) return;
      const id = trg.getAttribute('data-modal-trigger');
      const overlay = document.getElementById(id);
      if (overlay){ overlay.style.display = 'flex'; overlay.classList.add('show'); }
    });
    ['edit-profile-modal','change-password-modal'].forEach(bindModal);
  })();

  // AJAX submit: profile update (same behavior as edit page)
  (function(){
    const form = document.getElementById('edit-profile-form');
    if (!form) return;
    form.addEventListener('submit', async function(ev){
      ev.preventDefault();
      const submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn){ submitBtn.disabled = true; submitBtn.classList.add('opacity-60'); }
      try {
        const fd = new FormData(form);
        const res = await fetch(form.getAttribute('action'), { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept':'application/json' }, body: fd });
        const data = await res.json().catch(()=>({}));
        if (res.ok && data && data.success){
          if (typeof window.showMessage === 'function') window.showMessage('Cập nhật hồ sơ thành công', 'success');
          const modal = document.getElementById('edit-profile-modal'); if (modal){ modal.classList.remove('show'); modal.style.display='none'; }
        } else {
          const msg = (data && data.message) ? data.message : 'Có lỗi xảy ra. Vui lòng kiểm tra lại.';
          if (typeof window.showMessage === 'function') window.showMessage(msg, 'error');
        }
      } catch {
        if (typeof window.showMessage === 'function') window.showMessage('Không thể kết nối máy chủ. Vui lòng thử lại.', 'error');
      } finally { if (submitBtn){ submitBtn.disabled = false; submitBtn.classList.remove('opacity-60'); } }
    });
  })();

  // AJAX submit: change password
  (function(){
    const pwForm = document.querySelector('#change-password-modal form');
    if (!pwForm) return;
    pwForm.addEventListener('submit', async function(ev){
      ev.preventDefault();
      const submitBtn = pwForm.querySelector('button[type="submit"]');
      if (submitBtn){ submitBtn.disabled = true; submitBtn.classList.add('opacity-60'); }
      try {
        const fd = new FormData(pwForm);
        const res = await fetch(pwForm.getAttribute('action'), { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept':'application/json' }, body: fd });
        const data = await res.json().catch(()=>({}));
        if (res.ok && (data.success || res.status === 200)){
          if (typeof window.showMessage === 'function') window.showMessage('Đổi mật khẩu thành công', 'success');
          const modal = document.getElementById('change-password-modal'); if (modal){ modal.classList.remove('show'); modal.style.display='none'; }
          pwForm.reset();
        } else {
          const msg = (data && data.message) ? data.message : 'Đổi mật khẩu thất bại. Vui lòng kiểm tra lại.';
          if (typeof window.showMessage === 'function') window.showMessage(msg, 'error');
        }
      } catch {
        if (typeof window.showMessage === 'function') window.showMessage('Không thể kết nối máy chủ. Vui lòng thử lại.', 'error');
      } finally { if (submitBtn){ submitBtn.disabled = false; submitBtn.classList.remove('opacity-60'); } }
    });
  })();

  // Avatar live preview
  document.addEventListener('change', function(e){
    const input = e.target && (e.target.id === 'avatar-input' ? e.target : e.target.closest('#avatar-input'));
    if (!input) return;
    const file = input.files && input.files[0];
    if (!file) return;
    if (!file.type || !/^image\//i.test(file.type)) { window.showMessage && window.showMessage('Vui lòng chọn tệp hình ảnh hợp lệ.', 'error'); input.value=''; return; }
    const maxBytes = 2 * 1024 * 1024; if (file.size > maxBytes) { window.showMessage && window.showMessage('Ảnh vượt quá 2MB, vui lòng chọn ảnh khác.', 'error'); input.value=''; return; }
    const reader = new FileReader();
    reader.onload = function(evt){
      const dataUrl = evt && evt.target ? evt.target.result : null; if (!dataUrl) return;
      const modalPreview = document.getElementById('avatar-preview'); const sidebarAvatar = document.getElementById('profile-avatar');
      if (modalPreview) modalPreview.src = dataUrl; if (sidebarAvatar) sidebarAvatar.src = dataUrl;
    };
    reader.readAsDataURL(file);
  });

  // Direct avatar upload without modal: open file chooser and POST
  (function(){
    const trigger = document.querySelector('[data-avatar-upload]');
    if (!trigger) return;
    // Create hidden form once
    const form = document.getElementById('avatar-upload-form');
    const input = document.getElementById('avatar-uploader');
    trigger.addEventListener('click', function(){ input && input.click(); });
    input && input.addEventListener('change', async function(){
      const file = input.files && input.files[0]; if (!file) return;
      if (!file.type || !/^image\//i.test(file.type)) { window.showMessage && window.showMessage('Vui lòng chọn tệp hình ảnh hợp lệ.', 'error'); input.value=''; return; }
      const maxBytes = 2 * 1024 * 1024; if (file.size > maxBytes) { window.showMessage && window.showMessage('Ảnh vượt quá 2MB, vui lòng chọn ảnh khác.', 'error'); input.value=''; return; }
      try {
        const fd = new FormData(form); fd.set('avatar', file);
        const res = await fetch(form.action, { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept':'application/json' }, body: fd });
        const data = await res.json().catch(()=>({}));
        if (res.ok && (data.success || res.status === 200)){
          // immediate preview
          const reader = new FileReader(); reader.onload = function(evt){ const img = document.getElementById('profile-avatar'); if (img && evt && evt.target) img.src = evt.target.result; }; reader.readAsDataURL(file);
          window.showMessage && window.showMessage('Cập nhật ảnh đại diện thành công', 'success');
        } else {
          const msg = (data && data.message) ? data.message : 'Cập nhật ảnh thất bại. Vui lòng thử lại.'; window.showMessage && window.showMessage(msg, 'error');
        }
      } catch { window.showMessage && window.showMessage('Không thể kết nối máy chủ. Vui lòng thử lại.', 'error'); }
      finally { input.value=''; }
    });
  })();

  // Inline set-default address via fetch
  document.addEventListener('submit', function(e){
    const form = e.target.closest('.js-set-default-form');
    if (!form) return;
    e.preventDefault();
    const btn = form.querySelector('button'); const originalText = btn ? btn.innerHTML : '';
    if (btn){ btn.disabled = true; btn.classList.add('opacity-60'); btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Đang xử lý...'; }
    fetch(form.getAttribute('action'), { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }})
      .then(r=>r.json()).then(res=>{
        if (res && res.success){
          // Clear existing default markers and buttons
          document.querySelectorAll('.addr-default-badge').forEach(el=>el.remove());
          document.querySelectorAll('.js-set-default-form').forEach(f=>f.classList.remove('hidden'));
          const card = form.closest('[data-address-card]') || form.closest('.p-4');
          if (card){
            // Place badge consistently in the right header action area
            const actionBox = card.querySelector('.addr-action');
            if (actionBox){
              actionBox.innerHTML = '';
              const badge = document.createElement('span');
              badge.className='addr-default-badge px-3 py-1 text-xs rounded-full bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-700 font-medium border border-emerald-200';
              badge.innerHTML='<i class="fas fa-star mr-1"></i>Mặc định';
              actionBox.appendChild(badge);
            }
            // Hide this form button in header action
            form.classList.add('hidden');
            // Remove any legacy "Đặt làm mặc định" forms not in action area
            card.querySelectorAll('.js-set-default-form').forEach(f=>{ if (!actionBox || !actionBox.contains(f)) f.remove(); });
            // Move this card to top of the grid
            const columnsGrid = card.parentElement; // grid of cards
            if (columnsGrid && columnsGrid.firstElementChild){ columnsGrid.insertBefore(card, columnsGrid.firstElementChild); }

            // Ensure other cards have a "Đặt làm mặc định" form (some originals may not render a form)
            const currentAction = form.getAttribute('action');
            const currentId = card.getAttribute('data-address-id');
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const cards = columnsGrid ? Array.from(columnsGrid.querySelectorAll('[data-address-card]')) : [];
            cards.forEach(c => {
              const addrId = c.getAttribute('data-address-id');
              if (!addrId || addrId === currentId) return;
              let setForm = c.querySelector('.js-set-default-form');
              if (!setForm){
                // derive target action by replacing current id with this addr id
                let actionUrl = currentAction;
                if (currentId && actionUrl.endsWith('/'+currentId)){
                  actionUrl = actionUrl.slice(0, -1*currentId.length) + addrId;
                }
                setForm = document.createElement('form');
                setForm.className = 'js-set-default-form inline';
                setForm.method = 'POST';
                setForm.action = actionUrl;
                setForm.innerHTML = '<input type="hidden" name="_token" value="'+token+'">\
                  <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 text-xs rounded-full border border-gray-300 text-gray-700 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-700 transition-all duration-300">\
                  <i class="fas fa-check-circle text-emerald-600"></i>Đặt mặc định</button>';
                // append into the consistent header action area
                const actionArea = c.querySelector('.addr-action');
                if (actionArea){ actionArea.innerHTML=''; actionArea.appendChild(setForm); }
                else { c.appendChild(setForm); }
              }
            });
          }
          window.showMessage && window.showMessage('Đã đặt làm địa chỉ mặc định!', 'success');
        } else { throw new Error(); }
      }).catch(()=>{ window.showMessage && window.showMessage('Có lỗi xảy ra khi đặt địa chỉ mặc định', 'error'); })
      .finally(()=>{ if (btn){ btn.disabled=false; btn.classList.remove('opacity-60'); btn.innerHTML = originalText; } });
  });

  // Bind new modals - try multiple times to ensure they exist
  function bindNewModals() {
    const modalIds = ['edit-profile-info-modal','edit-driver-license-modal','edit-preferences-modal'];
    modalIds.forEach(id => {
      const modal = document.getElementById(id);
      if (modal && !modal.hasAttribute('data-bound')) {
        window.bindModal(id);
        modal.setAttribute('data-bound', 'true');
      }
    });
  }
  
  // Try immediately and after a delay
  bindNewModals();
  setTimeout(bindNewModals, 100);
  setTimeout(bindNewModals, 500);

  // Update profile info display without reload
  function updateProfileInfoDisplay(data) {
    // Update name
    if (data.name) {
      const nameElement = document.querySelector('[data-field="profile_name"]');
      if (nameElement) {
        nameElement.textContent = data.name;
      }
    }
    // Update birth date
    if (data.birth_date) {
      const birthDateElement = document.querySelector('[data-field="birth_date"]');
      if (birthDateElement) {
        const date = new Date(data.birth_date);
        birthDateElement.textContent = date.toLocaleDateString('vi-VN');
      }
    }
    
    // Update gender
    if (data.gender) {
      const genderElement = document.querySelector('[data-field="gender"]');
      if (genderElement) {
        const genderMap = { 'male': 'Nam', 'female': 'Nữ', 'other': 'Khác' };
        genderElement.textContent = genderMap[data.gender] || data.gender;
      }
    }
    
    // Update purchase purpose
    if (data.purchase_purpose) {
      const purposeElement = document.querySelector('[data-field="purchase_purpose"]');
      if (purposeElement) {
        purposeElement.textContent = data.purchase_purpose;
      }
    }
    
    // Update budget
    if (data.budget_min && data.budget_max) {
      const budgetElement = document.querySelector('[data-field="budget"]');
      if (budgetElement) {
        budgetElement.textContent = `${new Intl.NumberFormat('vi-VN').format(data.budget_min)} - ${new Intl.NumberFormat('vi-VN').format(data.budget_max)} VNĐ`;
      }
    }
  }

  // Update driver license display without reload
  function updateDriverLicenseDisplay(data) {
    // Update license number
    if (data.driver_license_number) {
      const licenseElement = document.querySelector('[data-field="driver_license_number"]');
      if (licenseElement) {
        licenseElement.textContent = data.driver_license_number;
      }
    }
    
    // Update issue date
    if (data.driver_license_issue_date) {
      const issueDateElement = document.querySelector('[data-field="driver_license_issue_date"]');
      if (issueDateElement) {
        const date = new Date(data.driver_license_issue_date);
        issueDateElement.textContent = date.toLocaleDateString('vi-VN');
      }
    }
    
    // Update expiry date
    if (data.driver_license_expiry_date) {
      const expiryDateElement = document.querySelector('[data-field="driver_license_expiry_date"]');
      if (expiryDateElement) {
        const date = new Date(data.driver_license_expiry_date);
        expiryDateElement.textContent = date.toLocaleDateString('vi-VN');
      }
    }
    
    // Update experience years
    if (data.driving_experience_years) {
      const experienceElement = document.querySelector('[data-field="driving_experience_years"]');
      if (experienceElement) {
        experienceElement.textContent = `${data.driving_experience_years} năm`;
      }
    }
  }

  // Update preferences display without reload
  function updatePreferencesDisplay(data) {
    // Update car types
    if (data.preferred_car_types) {
      const carTypesContainer = document.querySelector('[data-field="preferred_car_types"]');
      if (carTypesContainer) {
        const types = Array.isArray(data.preferred_car_types) ? data.preferred_car_types : JSON.parse(data.preferred_car_types || '[]');
        carTypesContainer.innerHTML = types.map(type => 
          `<span class="inline-block bg-gradient-to-r from-pink-100 to-rose-100 text-pink-700 text-xs px-3 py-1.5 rounded-full font-medium border border-pink-200">${type}</span>`
        ).join(' ');
      }
    }
    
    // Update brands
    if (data.preferred_brands) {
      const brandsContainer = document.querySelector('[data-field="preferred_brands"]');
      if (brandsContainer) {
        const brands = Array.isArray(data.preferred_brands) ? data.preferred_brands : JSON.parse(data.preferred_brands || '[]');
        brandsContainer.innerHTML = brands.map(brand => 
          `<span class="inline-block bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 text-xs px-3 py-1.5 rounded-full font-medium border border-blue-200">${brand}</span>`
        ).join(' ');
      }
    }
    
    // Update budget
    if (data.budget_min && data.budget_max) {
      const budgetElement = document.querySelector('[data-field="preferences_budget"]');
      if (budgetElement) {
        budgetElement.textContent = `${new Intl.NumberFormat('vi-VN').format(data.budget_min)} - ${new Intl.NumberFormat('vi-VN').format(data.budget_max)} VNĐ`;
      }
    }
  }

  // AJAX submit: Profile Info Update
  (function(){
    const form = document.getElementById('edit-profile-info-form');
    if (!form) return;
    form.addEventListener('submit', async function(ev){
      ev.preventDefault();
      const submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn){ submitBtn.disabled = true; submitBtn.classList.add('opacity-60'); submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang cập nhật...'; }
      try {
        const fd = new FormData(form);
        const res = await fetch(form.getAttribute('action'), { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept':'application/json' }, body: fd });
        const data = await res.json().catch(()=>({}));
        if (res.ok && data && data.success){
          if (typeof window.showMessage === 'function') window.showMessage('Cập nhật thông tin hồ sơ thành công', 'success');
          const modal = document.getElementById('edit-profile-info-modal'); if (modal){ modal.classList.remove('show'); modal.style.display='none'; }
          // Update profile info display without reload
          updateProfileInfoDisplay(data.data || {});
        } else {
          const msg = (data && data.message) ? data.message : 'Có lỗi xảy ra. Vui lòng kiểm tra lại.';
          if (typeof window.showMessage === 'function') window.showMessage(msg, 'error');
        }
      } catch {
        if (typeof window.showMessage === 'function') window.showMessage('Không thể kết nối máy chủ. Vui lòng thử lại.', 'error');
      } finally { 
        if (submitBtn){ 
          submitBtn.disabled = false; 
          submitBtn.classList.remove('opacity-60'); 
          submitBtn.innerHTML = 'Cập nhật'; 
        } 
      }
    });
  })();

  // AJAX submit: Driver License Update
  (function(){
    const form = document.getElementById('edit-driver-license-form');
    if (!form) return;
    form.addEventListener('submit', async function(ev){
      ev.preventDefault();
      const submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn){ submitBtn.disabled = true; submitBtn.classList.add('opacity-60'); submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang cập nhật...'; }
      try {
        const fd = new FormData(form);
        const res = await fetch(form.getAttribute('action'), { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept':'application/json' }, body: fd });
        const data = await res.json().catch(()=>({}));
        if (res.ok && data && data.success){
          if (typeof window.showMessage === 'function') window.showMessage('Cập nhật thông tin bằng lái xe thành công', 'success');
          const modal = document.getElementById('edit-driver-license-modal'); if (modal){ modal.classList.remove('show'); modal.style.display='none'; }
          // Update driver license display without reload
          updateDriverLicenseDisplay(data.data || {});
        } else {
          const msg = (data && data.message) ? data.message : 'Có lỗi xảy ra. Vui lòng kiểm tra lại.';
          if (typeof window.showMessage === 'function') window.showMessage(msg, 'error');
        }
      } catch {
        if (typeof window.showMessage === 'function') window.showMessage('Không thể kết nối máy chủ. Vui lòng thử lại.', 'error');
      } finally { 
        if (submitBtn){ 
          submitBtn.disabled = false; 
          submitBtn.classList.remove('opacity-60'); 
          submitBtn.innerHTML = 'Cập nhật'; 
        } 
      }
    });
  })();

  // AJAX submit: Preferences Update
  (function(){
    const form = document.getElementById('edit-preferences-form');
    if (!form) return;
    form.addEventListener('submit', async function(ev){
      ev.preventDefault();
      const submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn){ submitBtn.disabled = true; submitBtn.classList.add('opacity-60'); submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang cập nhật...'; }
      try {
        const fd = new FormData(form);
        const res = await fetch(form.getAttribute('action'), { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept':'application/json' }, body: fd });
        const data = await res.json().catch(()=>({}));
        if (res.ok && data && data.success){
          if (typeof window.showMessage === 'function') window.showMessage('Cập nhật sở thích thành công', 'success');
          const modal = document.getElementById('edit-preferences-modal'); if (modal){ modal.classList.remove('show'); modal.style.display='none'; }
          // Update preferences display without reload
          updatePreferencesDisplay(data.data || {});
        } else {
          const msg = (data && data.message) ? data.message : 'Có lỗi xảy ra. Vui lòng kiểm tra lại.';
          if (typeof window.showMessage === 'function') window.showMessage(msg, 'error');
        }
      } catch {
        if (typeof window.showMessage === 'function') window.showMessage('Không thể kết nối máy chủ. Vui lòng thử lại.', 'error');
      } finally { 
        if (submitBtn){ 
          submitBtn.disabled = false; 
          submitBtn.classList.remove('opacity-60'); 
          submitBtn.innerHTML = 'Cập nhật'; 
        } 
      }
    });
  })();
</script>
@endpush

<!-- Modal: Edit Profile Info -->
<div id="edit-profile-info-modal" class="modal-overlay" style="position:fixed;inset:0;background:rgba(0,0,0,.5);display:none;align-items:center;justify-content:center;z-index:10000">
    <div class="modal-panel max-w-2xl w-full mx-4">
        <div class="modal-header">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <i class="fas fa-user-tag text-sm"></i>
                </div>
                <div class="font-semibold text-gray-900">Cập nhật thông tin hồ sơ</div>
            </div>
            <button type="button" data-modal-close class="text-gray-500 hover:text-red-500 transition-colors duration-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="edit-profile-info-form" method="POST" action="{{ route('user.profile.general.update') }}">
            @csrf
            @method('patch')
            <div class="modal-body space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Họ tên <span class="text-red-500">*</span></label>
                    <input class="form-input" name="name" type="text" value="{{ old('name', optional($user->userProfile)->name ?? '') }}" required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ngày sinh</label>
                        <input class="form-input" name="birth_date" type="date" value="{{ old('birth_date', $customerProfile->birth_date ? $customerProfile->birth_date->format('Y-m-d') : '') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Giới tính</label>
                        <select name="gender" class="form-input">
                            <option value="">-- Chọn --</option>
                            <option value="male" @selected(($customerProfile->gender ?? '') === 'male')>Nam</option>
                            <option value="female" @selected(($customerProfile->gender ?? '') === 'female')>Nữ</option>
                            <option value="other" @selected(($customerProfile->gender ?? '') === 'other')>Khác</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mục đích mua</label>
                    <textarea class="form-input" name="purchase_purpose" rows="3" placeholder="Mô tả mục đích mua xe của bạn...">{{ old('purchase_purpose', $customerProfile->purchase_purpose ?? '') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ngân sách tối thiểu (VNĐ)</label>
                        <input class="form-input" name="budget_min" type="number" value="{{ old('budget_min', $customerProfile->budget_min ?? '') }}" placeholder="Ví dụ: 500000000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ngân sách tối đa (VNĐ)</label>
                        <input class="form-input" name="budget_max" type="number" value="{{ old('budget_max', $customerProfile->budget_max ?? '') }}" placeholder="Ví dụ: 1000000000">
                    </div>
                </div>

                @if(($customerProfile->profile_type ?? 'customer') === 'employee')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lương (VNĐ)</label>
                        <input class="form-input" name="employee_salary" type="number" value="{{ old('employee_salary', $customerProfile->employee_salary ?? '') }}" placeholder="Ví dụ: 15000000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kỹ năng</label>
                        <input class="form-input" name="employee_skills" type="text" value="{{ old('employee_skills', $customerProfile->employee_skills ?? '') }}" placeholder="Ví dụ: Bán hàng, Tư vấn...">
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" data-modal-close class="btn btn-outline">Hủy</button>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Driver License -->
<div id="edit-driver-license-modal" class="modal-overlay" style="position:fixed;inset:0;background:rgba(0,0,0,.5);display:none;align-items:center;justify-content:center;z-index:10000">
    <div class="modal-panel max-w-2xl w-full mx-4">
        <div class="modal-header">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <i class="fas fa-id-card text-sm"></i>
                </div>
                <div class="font-semibold text-gray-900">Cập nhật thông tin bằng lái xe</div>
            </div>
            <button type="button" data-modal-close class="text-gray-500 hover:text-red-500 transition-colors duration-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="edit-driver-license-form" method="POST" action="{{ route('user.profile.license.update') }}">
            @csrf
            @method('patch')
            <div class="modal-body space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Số bằng lái</label>
                        <input class="form-input" name="driver_license_number" type="text" value="{{ old('driver_license_number', $customerProfile->driver_license_number ?? '') }}" placeholder="Ví dụ: A123456789">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kinh nghiệm lái xe (năm)</label>
                        <input class="form-input" name="driving_experience_years" type="number" value="{{ old('driving_experience_years', $customerProfile->driving_experience_years ?? '') }}" placeholder="Ví dụ: 5">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ngày cấp</label>
                        <input class="form-input" name="driver_license_issue_date" type="date" value="{{ old('driver_license_issue_date', $customerProfile->driver_license_issue_date ? $customerProfile->driver_license_issue_date->format('Y-m-d') : '') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ngày hết hạn</label>
                        <input class="form-input" name="driver_license_expiry_date" type="date" value="{{ old('driver_license_expiry_date', $customerProfile->driver_license_expiry_date ? $customerProfile->driver_license_expiry_date->format('Y-m-d') : '') }}">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-modal-close class="btn btn-outline">Hủy</button>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Preferences -->
<div id="edit-preferences-modal" class="modal-overlay" style="position:fixed;inset:0;background:rgba(0,0,0,.5);display:none;align-items:center;justify-content:center;z-index:10000">
    <div class="modal-panel max-w-2xl w-full mx-4">
        <div class="modal-header">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center">
                    <i class="fas fa-heart text-sm"></i>
                </div>
                <div class="font-semibold text-gray-900">Cập nhật sở thích</div>
            </div>
            <button type="button" data-modal-close class="text-gray-500 hover:text-red-500 transition-colors duration-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="edit-preferences-form" method="POST" action="{{ route('user.profile.preferences.update') }}">
            @csrf
            @method('patch')
            <div class="modal-body space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Loại xe ưa thích</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @php
                            $carTypes = ['Sedan', 'SUV', 'Hatchback', 'Coupe', 'Convertible', 'Pickup', 'Crossover', 'Wagon'];
                            $selectedTypes = is_array($customerProfile->preferred_car_types ?? null) ? $customerProfile->preferred_car_types : (is_string($customerProfile->preferred_car_types ?? null) ? json_decode($customerProfile->preferred_car_types, true) ?: [] : []);
                        @endphp
                        @foreach($carTypes as $type)
                        <label class="flex items-center gap-2 p-3 rounded-lg border border-gray-200 hover:bg-pink-50 cursor-pointer">
                            <input type="checkbox" name="preferred_car_types[]" value="{{ $type }}" @checked(in_array($type, $selectedTypes)) class="rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                            <span class="text-sm text-gray-700">{{ $type }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Hãng xe ưa thích</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @php
                            $brands = ['Toyota', 'Honda', 'Ford', 'BMW', 'Mercedes-Benz', 'Audi', 'Hyundai', 'Kia', 'Mazda', 'Nissan', 'Volkswagen', 'Lexus'];
                            $selectedBrands = is_array($customerProfile->preferred_brands ?? null) ? $customerProfile->preferred_brands : (is_string($customerProfile->preferred_brands ?? null) ? json_decode($customerProfile->preferred_brands, true) ?: [] : []);
                        @endphp
                        @foreach($brands as $brand)
                        <label class="flex items-center gap-2 p-3 rounded-lg border border-gray-200 hover:bg-pink-50 cursor-pointer">
                            <input type="checkbox" name="preferred_brands[]" value="{{ $brand }}" @checked(in_array($brand, $selectedBrands)) class="rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                            <span class="text-sm text-gray-700">{{ $brand }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ngân sách tối thiểu (VNĐ)</label>
                        <input class="form-input" name="budget_min" type="number" value="{{ old('budget_min', $customerProfile->budget_min ?? '') }}" placeholder="Ví dụ: 500000000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ngân sách tối đa (VNĐ)</label>
                        <input class="form-input" name="budget_max" type="number" value="{{ old('budget_max', $customerProfile->budget_max ?? '') }}" placeholder="Ví dụ: 1000000000">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-modal-close class="btn btn-outline">Hủy</button>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>
</div>



