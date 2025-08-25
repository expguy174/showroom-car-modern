@extends('layouts.app')

@section('title', 'Tài khoản của tôi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-cog text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Tài khoản của tôi</h1>
                        <p class="text-gray-600">Quản lý thông tin cơ bản, mật khẩu và địa chỉ</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('user.customer-profiles.index') }}" class="flex items-center text-blue-600 hover:text-blue-700 font-medium">
                        <i class="fas fa-id-badge mr-2"></i>
                        Hồ sơ khách hàng
                    </a>
                    <a href="{{ route('home') }}" class="flex items-center text-blue-600 hover:text-blue-700 font-medium">
                        <i class="fas fa-home mr-2"></i>
                        Trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-7xl mx-auto">
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
            $fallback = 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=4f46e5&color=fff&size=128';
            $avatar = $user->avatar_path ? ($isAbs($user->avatar_path) ? $user->avatar_path : asset('storage/'.$user->avatar_path)) : $fallback;
            @endphp
                                <div class="relative">
                                    <img id="profile-avatar" src="{{ $avatar }}" class="w-16 h-16 rounded-full object-cover ring-3 ring-white shadow-lg" alt="Avatar" onerror="this.onerror=null;this.src='{{ $fallback }}';">
                                    <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-500 rounded-full border-2 border-white flex items-center justify-center shadow-md">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- User Info -->
                            <div class="flex-1">
                                <h2 class="text-xl font-bold text-white mb-1">{{ $user->name }}</h2>
                                <p class="text-indigo-100 text-sm mb-3">Thành viên từ {{ $user->created_at->format('M Y') }}</p>
                                
                                <!-- Stats Cards -->
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
                                <button class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg border border-white/30 hover:bg-white/30 transition-all duration-300 text-sm font-medium" data-modal-trigger="edit-profile-modal">
                                    <i class="fas fa-edit mr-1"></i>
                                    Chỉnh sửa
                                </button>
                                <button class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg border border-white/30 hover:bg-white/30 transition-all duration-300 text-sm font-medium" data-modal-trigger="change-password-modal">
                                    <i class="fas fa-key mr-1"></i>
                                    Đổi mật khẩu
                                </button>
                            </div>
                        </div>
            </div>
            </div>
            </div>
            
            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Left Column -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- User Information Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-blue-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-user-circle text-blue-600"></i>
                                Thông tin cá nhân
                            </h3>
            </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100">
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <i class="fas fa-envelope"></i>
            </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs text-gray-500 font-medium">Email</div>
                                    <div class="text-sm font-semibold text-gray-900 truncate" title="{{ $user->email }}">{{ $user->email }}</div>
            </div>
          </div>

                            <div class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-100">
                                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs text-gray-500 font-medium">Số điện thoại</div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $user->phone ?: 'Chưa cập nhật' }}</div>
                                </div>
                            </div>

                            @if($user->date_of_birth)
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-100">
                                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                                    <i class="fas fa-birthday-cake"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs text-gray-500 font-medium">Ngày sinh</div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $user->date_of_birth->format('d/m/Y') }}</div>
                                </div>
                            </div>
                            @endif

                            @if($user->gender)
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-r from-pink-50 to-rose-50 border border-pink-100">
                                <div class="w-10 h-10 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center">
                                    <i class="fas fa-venus-mars"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs text-gray-500 font-medium">Giới tính</div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $user->gender === 'male' ? 'Nam' : ($user->gender === 'female' ? 'Nữ' : 'Khác') }}</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-purple-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-bolt text-purple-600"></i>
                                Thao tác nhanh
                            </h3>
            </div>
                        <div class="p-6 space-y-3">
                            <button class="w-full inline-flex items-center justify-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5" data-modal-trigger="edit-profile-modal">
                                <i class="fas fa-edit"></i>
                                Chỉnh sửa thông tin
                            </button>
                            <button class="w-full inline-flex items-center justify-center gap-3 px-4 py-3 rounded-xl border-2 border-gray-200 text-gray-700 hover:border-purple-300 hover:bg-purple-50 transition-all duration-300 font-medium" data-modal-trigger="change-password-modal">
                                <i class="fas fa-key"></i>
                                Đổi mật khẩu
                            </button>
            </div>
          </div>
        </div>

                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-6">
                    <!-- Address Book -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-blue-50">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-address-book text-blue-600"></i>
                                    Sổ địa chỉ
                                </h3>
                                <a href="{{ route('user.addresses.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                    Quản lý <i class="fas fa-arrow-right ml-1"></i>
                                </a>
        </div>
        </div>
                        <div class="p-6">
                            @if($user->addresses()->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($user->addresses()->orderByDesc('is_default')->take(4)->get() as $addr)
                                    <div class="p-4 rounded-xl border border-gray-200 bg-gradient-to-br from-gray-50 to-blue-50 hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 hover:shadow-md">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="text-sm font-semibold text-gray-800">{{ $addr->full_name }}</div>
                @if($addr->is_default)
                                            <span class="addr-default-badge px-3 py-1 text-xs rounded-full bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-700 font-medium border border-emerald-200">
                                                <i class="fas fa-star mr-1"></i>Mặc định
                                            </span>
                @endif
              </div>
                                        <div class="space-y-2 mb-3">
                                            <div class="text-sm text-gray-600">{{ $addr->address }}</div>
                                            @if($addr->city)
                                            <div class="text-sm text-gray-600">{{ $addr->city }}{{ $addr->state ? ', ' . $addr->state : '' }}</div>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 mb-3 flex items-center gap-1">
                                            <i class="fas fa-phone text-gray-400"></i>
                                            {{ $addr->phone }}
                                        </div>
                                        @if(!$addr->is_default)
                                        <form action="{{ route('user.addresses.set-default', $addr->id) }}" method="POST" class="js-set-default-form">
                @csrf
                                            <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-gray-300 text-xs hover:bg-emerald-50 hover:border-emerald-300 transition-all duration-300">
                                                <i class="fas fa-check-circle text-emerald-600"></i>
                                                Đặt làm mặc định
                                            </button>
              </form>
                                        @endif
            </div>
            @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full mx-auto mb-6 flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-blue-500 text-2xl"></i>
                                    </div>
                                    <div class="text-gray-600 mb-2 font-medium">Chưa có địa chỉ nào</div>
                                    <div class="text-gray-500 mb-6 text-sm">Thêm địa chỉ để dễ dàng mua sắm</div>
                                    <a href="{{ route('user.addresses.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                        <i class="fas fa-plus"></i>
                                        Thêm địa chỉ đầu tiên
                                    </a>
                                </div>
            @endif
          </div>
        </div>

                    <!-- Quick Links -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-purple-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-rocket text-purple-600"></i>
                                Liên kết nhanh
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('user.customer-profiles.index') }}" class="w-full inline-flex items-center justify-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white hover:from-emerald-700 hover:to-teal-700 transition-all duration-300 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="fas fa-id-badge"></i>
                                Hồ sơ khách hàng
                            </a>
                            <a href="{{ route('user.customer-profiles.orders') }}" class="w-full inline-flex items-center justify-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="fas fa-shopping-bag"></i>
                                Đơn hàng của tôi
                            </a>
                            <a href="{{ route('user.service-appointments.index') }}" class="w-full inline-flex items-center justify-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-orange-600 to-red-600 text-white hover:from-orange-700 hover:to-red-700 transition-all duration-300 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="fas fa-calendar-check"></i>
                                Lịch hẹn dịch vụ
                            </a>
                        </div>
          </div>
              </div>
            </div>
    </div>
  </div>
</div>
@endsection
@push('styles')
<style>
  /* Page-specific fixes for nav on Profile page */
  #main-nav .h-16 { height: 64px; display: flex; align-items: center; }
  #main-nav form[action*="search"] { align-self: center; }
  #main-nav form[action*="search"] > div { position: relative; top: 8px; }
  #main-nav #desktop-search-input { height: 40px; line-height: 40px; padding-top: 0; padding-bottom: 0; }
  #main-nav [data-dropdown="notifications"] > button { border: 1px solid #e5e7eb !important; background: #fff !important; }
</style>
@endpush

@push('scripts')
<script>
  // Toast notification function
  function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;
    
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full max-w-sm pointer-events-auto relative`;
    toast.innerHTML = `
      <div class="flex items-center gap-3">
        <i class="fas ${icon} text-lg"></i>
        <span class="flex-1">${message}</span>
        <button onclick="this.parentElement.parentElement.remove()" class="text-white/80 hover:text-white">
          <i class="fas fa-times"></i>
        </button>
      </div>
    `;
    
    container.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
      toast.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
      toast.classList.add('translate-x-full');
      setTimeout(() => toast.remove(), 300);
    }, 5000);
  }

  (function() {
    function bindModal(id) {
      const overlay = document.getElementById(id);
      if (!overlay) return;
      overlay.addEventListener('click', e => {
        if (e.target === overlay) {
          overlay.classList.remove('show');
          overlay.style.display = 'none';
        }
      });
      overlay.querySelectorAll('[data-modal-close]').forEach(btn => btn.addEventListener('click', () => {
        overlay.classList.remove('show');
        overlay.style.display = 'none';
      }));
    }
    document.addEventListener('click', function(e) {
      const trg = e.target.closest('[data-modal-trigger]');
      if (!trg) return;
      const id = trg.getAttribute('data-modal-trigger');
      const overlay = document.getElementById(id);
      if (overlay) {
        overlay.style.display = 'flex';
        overlay.classList.add('show');
      }
    });
    ['edit-profile-modal', 'change-password-modal'].forEach(bindModal);

    // AJAX set default address inline
    document.addEventListener('submit', function(e) {
      const form = e.target.closest('.js-set-default-form');
      if (!form) return;
      e.preventDefault();
      
      const btn = form.querySelector('button');
      const originalText = btn ? btn.innerHTML : '';
      
      if (btn) {
        btn.disabled = true;
        btn.classList.add('opacity-60');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Đang xử lý...';
      }
      
      fetch(form.getAttribute('action'), {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        })
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(res => {
          if (res && res.success) {
            // Remove all existing default badges
            document.querySelectorAll('.addr-default-badge').forEach(el => el.remove());
            
            // Show all set-default buttons again
            document.querySelectorAll('.js-set-default-form').forEach(f => {
              f.classList.remove('hidden');
            });
            
            // Add badge to current card and hide its button
            const card = form.closest('.p-4');
            if (card) {
              const header = card.querySelector('.text-sm.font-semibold');
              if (header) {
                const badge = document.createElement('span');
                badge.className = 'addr-default-badge ml-2 px-3 py-1 text-xs rounded-full bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-700 font-medium border border-emerald-200';
                badge.innerHTML = '<i class="fas fa-star mr-1"></i>Mặc định';
                header.appendChild(badge);
              }
              form.classList.add('hidden');
            }
            
            // Show success message
            showToast('Đã đặt làm địa chỉ mặc định!', 'success');
          } else {
            throw new Error(res?.message || 'Có lỗi xảy ra');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Có lỗi xảy ra khi đặt địa chỉ mặc định', 'error');
        })
        .finally(() => {
          if (btn) {
            btn.disabled = false;
            btn.classList.remove('opacity-60');
            btn.innerHTML = originalText;
          }
        });
    });

    // Avatar live preview (delegated, reliable)
    document.addEventListener('change', function(e) {
      const input = e.target && (e.target.id === 'avatar-input' ? e.target : e.target.closest('#avatar-input'));
      if (!input) return;
      const file = input.files && input.files[0];
      if (!file) return;
      if (!file.type || !/^image\//i.test(file.type)) {
        alert('Vui lòng chọn tệp hình ảnh hợp lệ.');
        input.value = '';
        return;
      }
      const maxBytes = 2 * 1024 * 1024; // 2MB
      if (file.size > maxBytes) {
        alert('Ảnh vượt quá 2MB, vui lòng chọn ảnh khác.');
        input.value = '';
        return;
      }
      const reader = new FileReader();
      reader.onload = function(evt) {
        const dataUrl = evt && evt.target ? evt.target.result : null;
        if (!dataUrl) return;
        const modalPreview = document.getElementById('avatar-preview');
        const sidebarAvatar = document.getElementById('profile-avatar');
        if (modalPreview) modalPreview.src = dataUrl;
        if (sidebarAvatar) sidebarAvatar.src = dataUrl;
      };
      reader.readAsDataURL(file);
    });

    // Helper to lấy thông điệp lỗi tiếng Việt từ response
    function viErrorMessage(res, data){
      // Ưu tiên hiển thị từ danh sách lỗi chi tiết để tránh chuỗi tiếng Anh kiểu "and 1 more error"
      if (data && data.errors){
        try {
          const messages = [];
          Object.keys(data.errors).forEach(k => {
            const v = data.errors[k];
            if (Array.isArray(v)) { v.forEach(m => m && messages.push(String(m))); }
            else if (v) { messages.push(String(v)); }
          });
          if (messages.length){
            const shown = messages.slice(0, 2).join(' ');
            const remain = messages.length - 2;
            return remain > 0 ? `${shown} (và còn ${remain} lỗi nữa)` : shown;
          }
        } catch {}
      }
      // Fallback sang thông điệp trả về chung nếu có
      if (data && typeof data.message === 'string' && data.message.trim()) return data.message;
      if (res && res.status === 422) return 'Dữ liệu chưa hợp lệ. Vui lòng kiểm tra lại.';
      if (res && res.status >= 500) return 'Lỗi máy chủ. Vui lòng thử lại sau.';
      return 'Có lỗi xảy ra. Vui lòng thử lại.';
    }

    // AJAX submit: profile update
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
            // Update sidebar fields
            try {
              if (data.user){
                const nameEl = document.querySelector('#profile-avatar')?.nextElementSibling?.querySelector('div');
                if (nameEl && data.user.name) nameEl.textContent = data.user.name;
                const emailRow = document.querySelector('aside .fa-envelope')?.parentElement?.querySelector('span.truncate');
                if (emailRow && data.user.email) emailRow.textContent = data.user.email;
                const phoneRow = document.querySelector('aside .fa-phone-alt')?.parentElement?.querySelector('span');
                if (phoneRow) phoneRow.textContent = data.user.phone || '—';
                const dobRow = document.querySelector('aside .fa-birthday-cake')?.parentElement?.querySelector('span');
                if (dobRow) dobRow.textContent = data.user.date_of_birth_display || '—';
                const genderRow = document.querySelector('aside .fa-venus-mars')?.parentElement?.querySelector('span');
                if (genderRow){ const g = data.user.gender; genderRow.textContent = g==='male'?'Nam':(g==='female'?'Nữ':(g? 'Khác':'—')); }
                const nationalityRow = document.querySelector('aside .fa-globe-asia')?.parentElement?.querySelector('span.truncate');
                if (nationalityRow) nationalityRow.textContent = data.user.nationality || '—';
                if (data.user.avatar_url){ const avatar = document.getElementById('profile-avatar'); if (avatar) avatar.src = data.user.avatar_url; }
              }
            } catch {}
            if (typeof window.showMessage === 'function') window.showMessage('Cập nhật hồ sơ thành công', 'success');
            const modal = document.getElementById('edit-profile-modal'); if (modal){ modal.classList.remove('show'); modal.style.display='none'; }
          } else {
            const msg = viErrorMessage(res, data);
            if (typeof window.showMessage === 'function') window.showMessage(msg, 'error');
          }
        } catch { if (typeof window.showMessage === 'function') window.showMessage('Không thể kết nối máy chủ. Vui lòng thử lại.', 'error'); }
        finally { if (submitBtn){ submitBtn.disabled = false; submitBtn.classList.remove('opacity-60'); } }
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
          if (res.ok && data && data.success){
            if (typeof window.showMessage === 'function') window.showMessage('Đổi mật khẩu thành công', 'success');
            const modal = document.getElementById('change-password-modal'); if (modal){ modal.classList.remove('show'); modal.style.display='none'; }
            pwForm.reset();
          } else {
            const msg = viErrorMessage(res, data);
            if (typeof window.showMessage === 'function') window.showMessage(msg, 'error');
          }
        } catch { if (typeof window.showMessage === 'function') window.showMessage('Không thể kết nối máy chủ. Vui lòng thử lại.', 'error'); }
        finally { if (submitBtn){ submitBtn.disabled = false; submitBtn.classList.remove('opacity-60'); } }
      });
    })();

    // Toasts for update results (robust)
    var __st = @json(session('status'));
    if (__st && typeof window.showMessage === 'function'){
      var msg = (__st === 'profile-updated') ? 'Cập nhật hồ sơ thành công' : (__st === 'password-updated' ? 'Đổi mật khẩu thành công' : null);
      if (msg) window.showMessage(msg, 'success');
    }
  })();
</script>
@endpush

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
            <img id="avatar-preview" src="{{ $user->avatar_path ? (Str::startsWith($user->avatar_path, ['http://','https://']) ? $user->avatar_path : asset('storage/'.$user->avatar_path)) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=4f46e5&color=fff&size=96' }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
            <input id="avatar-input" class="form-input" type="file" name="avatar" accept="image/*" form="edit-profile-form">
          </div>
          @error('avatar')<div class="error-text">{{ $message }}</div>@enderror
          <div class="text-[11px] text-gray-500 mt-1">Hỗ trợ jpg/png/webp (tối đa 2MB).</div>
        </div>
        <div>
          <label class="block text-sm text-gray-700">Họ tên</label>
          <input class="form-input" name="name" type="text" value="{{ old('name', $user->name) }}" required>
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
        {{-- Ẩn chỉnh sửa địa chỉ liên hệ để thống nhất dùng sổ địa chỉ --}}
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