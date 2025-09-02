@extends('layouts.app')

@section('title', 'Sổ địa chỉ')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
    
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-map-marker-alt text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Sổ địa chỉ</h1>
                        <p class="text-gray-600">Quản lý địa chỉ giao hàng và thanh toán</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('user.profile.edit') }}" class="flex items-center text-blue-600 hover:text-blue-700 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Quay về tài khoản
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <div class="text-green-800 font-medium">{{ session('success') }}</div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-red-50 to-pink-50 border border-red-200">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="text-red-800 font-medium">Có lỗi xảy ra:</div>
                    </div>
                    <ul class="list-disc list-inside ml-11 text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Add New Address Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden sticky top-8">
                        <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-blue-50">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-plus-circle text-blue-600"></i>
                                Thêm địa chỉ mới
                            </h2>
                        </div>
                        <form action="{{ route('user.addresses.store') }}" method="POST" class="p-6 space-y-4">
                            @csrf
                            
                            <!-- Personal Information -->
                            <div class="space-y-4">
                                <h3 class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-user text-gray-400"></i>
                                    Thông tin cá nhân
                                </h3>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Họ tên *</label>
                                        <input name="name" type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 @error('name') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" required value="{{ old('name', optional($user->userProfile)->name) }}" placeholder="Nhập họ tên"/>
                                        @error('name')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại *</label>
                                        <input name="phone" type="tel" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 @error('phone') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" required value="{{ old('phone', optional($user->addresses->firstWhere('is_default', true) ?: $user->addresses->first())->phone ?? null) }}" placeholder="Nhập số điện thoại"/>
                                        @error('phone')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 @error('email') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" value="{{ old('email', $user->email) }}" placeholder="Nhập email (tùy chọn)"/>
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Address Information -->
                            <div class="space-y-4">
                                <h3 class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    Thông tin địa chỉ
                                </h3>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ chính *</label>
                                    <input name="line1" type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 @error('line1') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" required placeholder="Số nhà, tên đường" value="{{ old('line1') }}"/>
                                    @error('line1')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ bổ sung</label>
                                    <input name="line2" type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 @error('line2') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" placeholder="Tòa nhà, căn hộ (tùy chọn)" value="{{ old('line2') }}"/>
                                    @error('line2')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Phường/Xã</label>
                                        <input name="ward" type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 @error('ward') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" placeholder="Phường/Xã" value="{{ old('ward') }}"/>
                                        @error('ward')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Quận/Huyện</label>
                                        <input name="district" type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 @error('district') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" placeholder="Quận/Huyện" value="{{ old('district') }}"/>
                                        @error('district')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tỉnh/Thành</label>
                                        <input name="province" type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 @error('province') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" placeholder="Tỉnh/Thành" value="{{ old('province') }}"/>
                                        @error('province')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Address Type & Default -->
                            <div class="space-y-4">
                                <h3 class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-cog text-gray-400"></i>
                                    Cài đặt
                                </h3>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Loại địa chỉ</label>
                                    <select name="type" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 @error('type') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror">
                                        <option value="shipping" {{ old('type') == 'shipping' ? 'selected' : '' }}>Giao hàng</option>
                                        <option value="billing" {{ old('type') == 'billing' ? 'selected' : '' }}>Thanh toán</option>
                                    </select>
                                    @error('type')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100">
                                    <input type="checkbox" name="is_default" value="1" id="is_default" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ old('is_default') ? 'checked' : '' }}/>
                                    <label for="is_default" class="text-sm font-medium text-gray-700">Đặt làm địa chỉ mặc định</label>
                                    @error('is_default')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="fas fa-plus mr-2"></i>
                                Thêm địa chỉ
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Address List -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-blue-50">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-address-book text-blue-600"></i>
                                    Địa chỉ của tôi
                                </h2>
                                <div class="text-sm text-gray-500">
                                    {{ $addresses->count() }} địa chỉ
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            @if($addresses->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($addresses as $addr)
                                    <div class="p-4 rounded-xl border border-gray-200 bg-gradient-to-br from-gray-50 to-blue-50 hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 hover:shadow-md group">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="text-sm font-semibold text-gray-800">{{ $addr->name }}</div>
                                            @if($addr->is_default)
                                            <span class="addr-default-badge px-3 py-1 text-xs rounded-full bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-700 font-medium border border-emerald-200">
                                                <i class="fas fa-star mr-1"></i>Mặc định
                                            </span>
                                            @endif
                                        </div>
                                        
                                        <div class="space-y-2 mb-4">
                                            <div class="text-sm text-gray-600">{{ $addr->contact_name }}</div>
                                            <div class="text-sm text-gray-600">{{ $addr->address }}</div>
                                            @if($addr->city)
                                            <div class="text-sm text-gray-600">{{ $addr->city }}{{ $addr->state ? ', ' . $addr->state : '' }}</div>
                                            @endif
                                            <div class="text-xs text-gray-500 flex items-center gap-1">
                                                <i class="fas fa-phone text-gray-400"></i>
                                                {{ $addr->phone }}
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-1 text-xs rounded-full {{ $addr->type == 'shipping' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                                    <i class="fas {{ $addr->type == 'shipping' ? 'fa-truck' : 'fa-credit-card' }} mr-1"></i>
                                                    {{ $addr->type == 'shipping' ? 'Giao hàng' : 'Thanh toán' }}
                                                </span>
                                            </div>
                                            
                                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                @if(!$addr->is_default)
                                                <form method="POST" action="{{ route('user.addresses.set-default', $addr) }}" class="js-set-default-form">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-gray-300 text-xs hover:bg-emerald-50 hover:border-emerald-300 transition-all duration-300">
                                                        <i class="fas fa-check-circle text-emerald-600"></i>
                                                        Đặt mặc định
                                                    </button>
                                                </form>
                                                @endif
                                                
                                                <form method="POST" action="{{ route('user.addresses.destroy', $addr) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')" class="js-delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-gray-300 text-xs text-red-600 hover:bg-red-50 hover:border-red-300 transition-all duration-300">
                                                        <i class="fas fa-trash"></i>
                                                        Xóa
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full mx-auto mb-6 flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-blue-500 text-2xl"></i>
                                    </div>
                                    <div class="text-gray-600 mb-2 font-medium">Chưa có địa chỉ nào</div>
                                    <div class="text-gray-500 mb-6 text-sm">Thêm địa chỉ đầu tiên để dễ dàng mua sắm</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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

  // AJAX set default address
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

  // AJAX delete address
  document.addEventListener('submit', function(e) {
    const form = e.target.closest('.js-delete-form');
    if (!form) return;
    
    if (!confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')) {
      e.preventDefault();
      return;
    }
    
    e.preventDefault();
    
    const btn = form.querySelector('button');
    const originalText = btn ? btn.innerHTML : '';
    
    if (btn) {
      btn.disabled = true;
      btn.classList.add('opacity-60');
      btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Đang xóa...';
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
          // Remove the address card
          const card = form.closest('.p-4');
          if (card) {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.95)';
            setTimeout(() => {
              card.remove();
              // Update address count
              const countElement = document.querySelector('.text-sm.text-gray-500');
              if (countElement) {
                const currentCount = parseInt(countElement.textContent);
                countElement.textContent = `${currentCount - 1} địa chỉ`;
              }
            }, 300);
          }
          
          // Show success message
          showToast('Đã xóa địa chỉ thành công!', 'success');
        } else {
          throw new Error(res?.message || 'Có lỗi xảy ra');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra khi xóa địa chỉ', 'error');
      })
      .finally(() => {
        if (btn) {
          btn.disabled = false;
          btn.classList.remove('opacity-60');
          btn.innerHTML = originalText;
        }
      });
  });
</script>
@endpush


