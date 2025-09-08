@extends('layouts.app')

@section('title', 'Trung tâm tài khoản')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <div class="bg-white shadow-sm border-b border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-cog text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Trung tâm tài khoản</h1>
                        <p class="text-gray-600">Quản lý tài khoản và hồ sơ khách hàng</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center text-blue-600 hover:text-blue-700 font-medium"><i class="fas fa-home mr-2"></i>Trang chủ</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Account Section -->
            <div id="panel-account" class="block">
                {{-- FULL content from user/profile/edit.blade.php to keep UI/UX identical --}}
                <!-- Profile Overview Section -->
                <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-2xl shadow-lg mb-6 overflow-hidden">
                    <div class="relative">
                        <!-- Background Pattern -->
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute top-0 left-0 w-24 h-24 bg-white rounded-full -translate-x-12 -translate-y-12"></div>
                            <div class="absolute top-1/2 right-0 w-16 h-16 bg-white rounded-full translate-x-8 -translate-y-8"></div>
                            <div class="absolute bottom-0 left-1/3 w-12 h-12 bg-white rounded-full translate-y-6"></div>
                        </div>
                        <div class="relative p-6">
                            <div class="flex items-center gap-4">
                                <!-- Avatar Section -->
                                <div class="relative">
                                    @php
                                    $isAbs = function($p){ return preg_match('/^https?:\/\//i', (string)$p) === 1; };
                                    $fallback = 'https://ui-avatars.com/api/?name='.urlencode(optional($user->userProfile)->name ?? ($user->email ?? 'User')).'&background=4f46e5&color=fff&size=128';
                                    $profileAvatar = optional($user->userProfile)->avatar_path;
                                    $avatar = $profileAvatar ? ($isAbs($profileAvatar) ? $profileAvatar : asset('storage/'.$profileAvatar)) : $fallback;
                                    @endphp
                                    <div class="relative">
                                        <img id="profile-avatar" src="{{ $avatar }}" class="w-16 h-16 rounded-full object-cover ring-3 ring-white shadow-lg" alt="Avatar" onerror="this.onerror=null;this.src='{{ $fallback }}';">
                                        <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-500 rounded-full border-2 border-white flex items-center justify-center shadow-md">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                                <!-- User Info + Stats -->
                                <div class="flex-1">
                                    <h2 class="text-xl font-bold text-white mb-1">{{ optional($user->userProfile)->name ?? 'Khách' }}</h2>
                                    <p class="text-indigo-100 text-sm mb-3">{{ $user->email }}</p>
                                    <div class="flex gap-3">
                                        <div class="bg-white/20 backdrop-blur-sm rounded-lg px-3 py-1.5 border border-white/30">
                                            <div class="text-white text-sm font-bold">{{ $orders->count() }}</div>
                                            <div class="text-indigo-100 text-xs">Đơn hàng</div>
                                        </div>
                                        <div class="bg-white/20 backdrop-blur-sm rounded-lg px-3 py-1.5 border border-white/30">
                                            <div class="text-white text-sm font-bold">{{ $user->addresses()->count() }}</div>
                                            <div class="text-indigo-100 text-xs">Địa chỉ</div>
                                        </div>
                                        <div class="bg-white/20 backdrop-blur-sm rounded-lg px-3 py-1.5 border border-white/30">
                                            <div class="text-white text-sm font-bold">{{ $user->created_at->format('d/m/Y') }}</div>
                                            <div class="text-indigo-100 text-xs">Ngày tham gia</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Quick Actions -->
                                <div class="flex gap-2">
                                    <button class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg border border-white/30 hover:bg-white/30 transition-all duration-300 text-sm font-medium" data-modal-trigger="edit-profile-modal"><i class="fas fa-edit mr-1"></i>Chỉnh sửa</button>
                                    <button class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg border border-white/30 hover:bg-white/30 transition-all duration-300 text-sm font-medium" data-modal-trigger="change-password-modal"><i class="fas fa-key mr-1"></i>Đổi mật khẩu</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Left Column -->
                    <div class="lg:col-span-1 space-y-6">

                        
                    </div>

                    <!-- Main Right Column -->
                    <div class="lg:col-span-3 space-y-6">
                        <!-- Address Book -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-blue-50">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2"><i class="fas fa-address-book text-blue-600"></i>Sổ địa chỉ</h3>
                                    <a href="{{ route('user.addresses.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Quản lý <i class="fas fa-arrow-right ml-1"></i></a>
                                </div>
                            </div>
                            <div class="p-6">
                                @if($user->addresses()->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($user->addresses()->orderByDesc('is_default')->take(4)->get() as $addr)
                                    <div class="p-4 rounded-xl border border-gray-200 bg-gradient-to-br from-gray-50 to-blue-50 hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 hover:shadow-md">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="text-sm font-semibold text-gray-800">{{ $addr->contact_name }}</div>
                                            @if($addr->is_default)
                                            <span class="addr-default-badge px-3 py-1 text-xs rounded-full bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-700 font-medium border border-emerald-200"><i class="fas fa-star mr-1"></i>Mặc định</span>
                                            @endif
                                        </div>
                                        <div class="space-y-2 mb-3">
                                            <div class="text-sm text-gray-600">{{ $addr->address }}</div>
                                            @if($addr->city)
                                            <div class="text-sm text-gray-600">{{ $addr->city }}{{ $addr->state ? ', ' . $addr->state : '' }}</div>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 mb-3 flex items-center gap-1"><i class="fas fa-phone text-gray-400"></i>{{ $addr->phone }}</div>
                                        @if(!$addr->is_default)
                                        <form action="{{ route('user.addresses.set-default', $addr->id) }}" method="POST" class="js-set-default-form">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-gray-300 text-xs hover:bg-emerald-50 hover:border-emerald-300 transition-all duration-300"><i class="fas fa-check-circle text-emerald-600"></i>Đặt làm mặc định</button>
                                        </form>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full mx-auto mb-6 flex items-center justify-center"><i class="fas fa-map-marker-alt text-blue-500 text-2xl"></i></div>
                                    <div class="text-gray-600 mb-2 font-medium">Chưa có địa chỉ nào</div>
                                    <div class="text-gray-500 mb-6 text-sm">Thêm địa chỉ để dễ dàng mua sắm</div>
                                    <a href="{{ route('user.addresses.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"><i class="fas fa-plus"></i>Thêm địa chỉ đầu tiên</a>
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

            <!-- Profile Section -->
            <div id="panel-profile" class="block mt-8">
                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Summary from user_profiles schema -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b bg-gray-50">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-user-tag text-indigo-600"></i>
                                    Thông tin hồ sơ
                                </h3>
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
                                        <div class="text-sm font-medium text-gray-900">{{ $customerProfile->birth_date->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                                @endif
                                @if($customerProfile->gender)
                                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                                    <div class="w-8 h-8 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center"><i class="fas fa-venus-mars"></i></div>
                                    <div>
                                        <div class="text-xs text-gray-500">Giới tính</div>
                                        <div class="text-sm font-medium text-gray-900">{{ $customerProfile->gender_display ?? $customerProfile->gender }}</div>
                                    </div>
                                </div>
                                @endif
                                @if($customerProfile->purchase_purpose)
                                <div class="md:col-span-2 flex items-start gap-3 p-3 rounded-lg bg-gray-50">
                                    <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center"><i class="fas fa-bullseye"></i></div>
                                    <div class="min-w-0">
                                        <div class="text-xs text-gray-500">Mục đích mua</div>
                                        <div class="text-sm font-medium text-gray-900 break-words">{{ $customerProfile->purchase_purpose }}</div>
                                    </div>
                                </div>
                                @endif
                                @if($customerProfile->budget_min && $customerProfile->budget_max)
                                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="fas fa-wallet"></i></div>
                                    <div>
                                        <div class="text-xs text-gray-500">Khoảng ngân sách</div>
                                        <div class="text-sm font-medium text-gray-900">{{ number_format($customerProfile->budget_min) }} - {{ number_format($customerProfile->budget_max) }} VNĐ</div>
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
                            <div class="px-6 py-4 border-b bg-gray-50">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-id-card text-emerald-600"></i>
                                    Thông tin bằng lái xe
                                </h3>
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
                                                <div class="text-sm font-medium text-gray-900">{{ $customerProfile->driver_license_number }}</div>
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
                                                <div class="text-sm font-medium text-gray-900">{{ $customerProfile->driver_license_issue_date->format('d/m/Y') }}</div>
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
                                                <div class="text-sm font-medium text-gray-900">{{ $customerProfile->driver_license_expiry_date->format('d/m/Y') }}</div>
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
                                                <div class="text-sm font-medium text-gray-900">{{ $customerProfile->driving_experience_years }} năm</div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Orders -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-shopping-bag text-emerald-600"></i>
                                        Đơn hàng gần đây
                                    </h3>
                                    <a href="{{ route('user.customer-profiles.orders') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                                        Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="p-6">
                                @php $__orders = isset($recentOrders) ? $recentOrders : ($orders ?? collect()); @endphp
                                @if($__orders->count() > 0)
                                <div class="space-y-4">
                                    @foreach($__orders->take(3) as $order)
                                    <div class="p-4 rounded-xl border border-gray-200 bg-gray-50 hover:bg-gray-100 transition-colors">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="text-sm font-semibold text-gray-800">#{{ $order->order_number ?? $order->id }}</div>
                                            <div class="text-lg font-bold text-emerald-600">{{ number_format($order->grand_total ?? $order->total_price) }} đ</div>
                                        </div>
                                        <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs">{{ $order->status_display ?? ucfirst($order->status) }}</span>
                                                <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-700 text-xs">{{ $order->payment_status_display ?? 'Chưa xác định' }}</span>
                                            </div>
                                        </div>
                                        <div class="text-xs text-gray-600 flex items-center gap-1"><i class="fas fa-box text-gray-400"></i><span>{{ $order->items->count() }} sản phẩm</span></div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                                        <i class="fas fa-shopping-bag text-gray-400 text-xl"></i>
                                    </div>
                                    <div class="text-gray-500 mb-4">Bạn chưa có đơn hàng nào</div>
                                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                        <i class="fas fa-car"></i>
                                        Mua sắm ngay
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Recent Test Drives -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-car text-blue-600"></i>
                                        Lái thử gần đây
                                    </h3>
                                    <a href="{{ route('user.customer-profiles.test-drives') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                        Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="p-6">
                                @if(($testDrives->count() ?? 0) > 0)
                                <div class="space-y-4">
                                    @foreach($testDrives->take(3) as $testDrive)
                                    <div class="p-4 rounded-xl border border-gray-200 bg-gray-50 hover:bg-gray-100 transition-colors">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="text-sm font-semibold text-gray-800">
                                                {{ $testDrive->carVariant->carModel->carBrand->name ?? 'N/A' }} {{ $testDrive->carVariant->carModel->name ?? 'N/A' }}
                                            </div>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                {{ $testDrive->status === 'completed' ? 'bg-green-100 text-green-700' : 
                                                   ($testDrive->status === 'scheduled' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                                                {{ ucfirst($testDrive->status) }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-500 mb-2">
                                            @if($testDrive->preferred_date)
                                                <div class="flex items-center gap-1 mb-1">
                                                    <i class="fas fa-calendar text-gray-400"></i>
                                                    <span>{{ $testDrive->preferred_date->format('d/m/Y') }} {{ $testDrive->preferred_time }}</span>
                                                </div>
                                            @endif
                                            @if($testDrive->showroom_name)
                                                <div class="flex items-center gap-1">
                                                    <i class="fas fa-store text-gray-400"></i>
                                                    <span>{{ $testDrive->showroom_name }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                                        <i class="fas fa-car text-gray-400 text-xl"></i>
                                    </div>
                                    <div class="text-gray-500 mb-4">Bạn chưa đặt lịch lái thử nào</div>
                                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-car"></i>
                                        Đặt lịch lái thử
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Preferences Summary -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b bg-gray-50">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-heart text-pink-600"></i>
                                    Sở thích
                                </h3>
                            </div>
                            <div class="p-6 space-y-4">
                                @php
                                    $__carTypesRaw = $customerProfile->preferred_car_types ?? null;
                                    $__carTypes = is_array($__carTypesRaw) ? $__carTypesRaw : (is_string($__carTypesRaw) ? (json_decode($__carTypesRaw, true) ?: []) : []);
                                    $__brandsRaw = $customerProfile->preferred_brands ?? null;
                                    $__brands = is_array($__brandsRaw) ? $__brandsRaw : (is_string($__brandsRaw) ? (json_decode($__brandsRaw, true) ?: []) : []);
                                @endphp
                                @if(!empty($__carTypes))
                                <div>
                                    <div class="text-xs text-gray-500 mb-2">Loại xe ưa thích</div>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($__carTypes as $type)
                                            <span class="inline-block bg-pink-100 text-pink-700 text-xs px-3 py-1 rounded-full font-medium">{{ $type }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                @if(!empty($__brands))
                                <div>
                                    <div class="text-xs text-gray-500 mb-2">Hãng xe ưa thích</div>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($__brands as $brand)
                                            <span class="inline-block bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-medium">{{ $brand }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                @if(($customerProfile->budget_min ?? null) && ($customerProfile->budget_max ?? null))
                                <div>
                                    <div class="text-xs text-gray-500 mb-2">Khoảng giá</div>
                                    <div class="text-sm font-medium text-gray-900">{{ number_format($customerProfile->budget_min) }} - {{ number_format($customerProfile->budget_max) }} VNĐ</div>
                                </div>
                                @endif
                                <a href="{{ route('user.customer-profiles.preferences') }}" class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700 font-medium">
                                    <i class="fas fa-edit"></i>
                                    Cập nhật sở thích
                                </a>
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
          document.querySelectorAll('.addr-default-badge').forEach(el=>el.remove());
          document.querySelectorAll('.js-set-default-form').forEach(f=>f.classList.remove('hidden'));
          const card = form.closest('.p-4');
          if (card){
            const header = card.querySelector('.text-sm.font-semibold');
            if (header){ const badge = document.createElement('span'); badge.className='addr-default-badge ml-2 px-3 py-1 text-xs rounded-full bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-700 font-medium border border-emerald-200'; badge.innerHTML='<i class="fas fa-star mr-1"></i>Mặc định'; header.appendChild(badge); }
            form.classList.add('hidden');
          }
          window.showMessage && window.showMessage('Đã đặt làm địa chỉ mặc định!', 'success');
        } else { throw new Error(); }
      }).catch(()=>{ window.showMessage && window.showMessage('Có lỗi xảy ra khi đặt địa chỉ mặc định', 'error'); })
      .finally(()=>{ if (btn){ btn.disabled=false; btn.classList.remove('opacity-60'); btn.innerHTML = originalText; } });
  });
</script>
@endpush



