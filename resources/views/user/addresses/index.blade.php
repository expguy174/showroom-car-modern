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
                    <a href="{{ route('user.profile.index') }}" class="flex items-center text-blue-600 hover:text-blue-700 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Quay về tài khoản
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mx-auto">
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

            

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Add New Address Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
                        <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-blue-50">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-plus-circle text-blue-600"></i>
                                <span id="address-form-title">Thêm địa chỉ</span>
                            </h2>
                        </div>
                        <form id="address-create-form" action="{{ route('user.addresses.store') }}" method="POST" class="p-5 space-y-3" novalidate>
                            @csrf
                            
                            <!-- Personal Information -->
                            <div class="space-y-4">
                                <h3 class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-user text-gray-400"></i>
                                    Thông tin liên hệ
                                </h3>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-1">Họ tên liên hệ *</label>
                                        <input id="contact_name" name="contact_name" type="text" autocomplete="name" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 @error('contact_name') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" required value="{{ old('contact_name', optional($user->userProfile)->name) }}" placeholder="Nhập họ tên liên hệ"/>
                                        @error('contact_name')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                                        <input id="phone" name="phone" type="tel" autocomplete="tel" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 @error('phone') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" value="{{ old('phone', optional($user->userProfile)->phone ?? (optional($user->addresses->firstWhere('is_default', true) ?: $user->addresses->first())->phone ?? null)) }}" placeholder="Nhập số điện thoại" required/>
                                        @error('phone')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Address Information -->
                            <div class="space-y-4">
                                <h3 class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    Thông tin địa chỉ
                                </h3>
                                
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ *</label>
                                    <textarea id="address" name="address" rows="3" autocomplete="street-address" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 @error('address') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" placeholder="Số nhà, tên đường, tòa nhà/căn hộ (nếu có)" required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label for="state" class="block text-sm font-medium text-gray-700 mb-1">Quận/Huyện</label>
                                        <input id="state" name="state" type="text" autocomplete="address-level2" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 @error('state') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" placeholder="VD: Hoàn Kiếm" value="{{ old('state') }}"/>
                                        @error('state')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Tỉnh/Thành phố *</label>
                                        <input id="city" name="city" type="text" autocomplete="address-level1" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 @error('city') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" placeholder="VD: Hà Nội" value="{{ old('city') }}" required/>
                                        @error('city')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Mã bưu chính</label>
                                    <input id="postal_code" name="postal_code" type="text" autocomplete="postal-code" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 @error('postal_code') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" placeholder="VD: 100000" value="{{ old('postal_code') }}"/>
                                    @error('postal_code')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Quốc gia</label>
                                        <input id="country" name="country" type="text" autocomplete="country-name" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 @error('country') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" placeholder="Ví dụ: Việt Nam" value="{{ old('country', 'Việt Nam') }}"/>
                                        @error('country')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="address_type" class="block text-sm font-medium text-gray-700 mb-1">Loại địa chỉ</label>
                                        <select id="address_type" name="type" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 @error('type') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror">
                                            @php $__typeOld = old('type','home'); @endphp
                                            <option value="home" {{ $__typeOld==='home' ? 'selected' : '' }}>Nhà riêng</option>
                                            <option value="work" {{ $__typeOld==='work' ? 'selected' : '' }}>Cơ quan</option>
                                            <option value="billing" {{ $__typeOld==='billing' ? 'selected' : '' }}>Thanh toán</option>
                                            <option value="shipping" {{ $__typeOld==='shipping' ? 'selected' : '' }}>Giao hàng</option>
                                            <option value="other" {{ $__typeOld==='other' ? 'selected' : '' }}>Khác</option>
                                        </select>
                                        @error('type')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Notes & Default -->
                            <div class="space-y-4">
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
                                    <textarea id="notes" name="notes" rows="2" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 @error('notes') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" placeholder="Ví dụ: Giao giờ hành chính">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div id="default-checkbox-wrap" class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 {{ ($addresses->count() ?? 0) === 0 ? 'hidden' : '' }}">
                                    <input type="checkbox" name="is_default" value="1" id="is_default" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ old('is_default') ? 'checked' : '' }} aria-label="Đặt làm địa chỉ mặc định"/>
                                    <label for="is_default" class="text-sm font-medium text-gray-700">Đặt làm địa chỉ mặc định</label>
                                    @error('is_default')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="flex gap-3">
                                <button type="submit" id="address-submit-btn" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 font-medium shadow-md hover:shadow-lg transform hover:-translate-y-0.5 {{ (!empty($addressLimitReached) && $addressLimitReached) ? 'opacity-60 cursor-not-allowed' : '' }}" {{ (!empty($addressLimitReached) && $addressLimitReached) ? 'disabled' : '' }}>
                                    <i class="fas fa-plus"></i>
                                    <span class="js-submit-text">Thêm địa chỉ</span>
                                </button>
                                <button type="button" id="address-cancel-edit" class="hidden flex-1 inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-300 font-medium">
                                    Hủy
                            </button>
                            </div>
                            <input type="hidden" name="_method" value="POST" class="js-method-field"/>
                            <input type="hidden" name="editing_id" value="" class="js-editing-id"/>
                            @if(!empty($addressLimitReached) && $addressLimitReached)
                                <div class="text-[13px] text-amber-700 bg-amber-50 border border-amber-200 rounded-lg p-3">
                                    Bạn đã đạt giới hạn {{ $maxAddresses ?? 20 }} địa chỉ. Vui lòng xóa bớt địa chỉ cũ để thêm địa chỉ mới.
                                </div>
                            @endif
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
                                <div class="text-sm text-gray-500 flex items-center gap-2">
                                    <span>{{ isset($addressCount) ? $addressCount : $addresses->count() }} / {{ $maxAddresses ?? 20 }} địa chỉ</span>
                                    @if(!empty($addressLimitReached) && $addressLimitReached)
                                        <span class="px-2 py-0.5 text-[11px] rounded-full bg-amber-100 text-amber-800 border border-amber-200">Đã đạt giới hạn</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            @if($addresses->count() > 0)
                                <div id="addresses-grid" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($addresses as $addr)
                                    <div class="group relative p-5 pb-16 rounded-2xl border border-gray-200 bg-gradient-to-br from-gray-50 to-blue-50 hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 cursor-pointer" data-address-card data-address-id="{{ $addr->id }}" data-is-default="{{ $addr->is_default ? 1 : 0 }}" data-set-default-action="{{ route('user.addresses.set-default', $addr) }}" data-update-action="{{ route('user.addresses.update', $addr) }}" data-contact-name="{{ e($addr->contact_name) }}" data-phone="{{ e($addr->phone) }}" data-address="{{ e($addr->address) }}" data-city="{{ e($addr->city) }}" data-state="{{ e($addr->state) }}" data-postal-code="{{ e($addr->postal_code) }}" data-country="{{ e($addr->country) }}" data-type="{{ e($addr->type) }}" data-notes="{{ e($addr->notes) }}">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                                    <i class="fas fa-map-marker-alt text-sm"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-sm font-semibold text-gray-800 truncate whitespace-nowrap sm:max-w-[12rem] md:max-w-[16rem] lg:max-w-none" data-address-header title="{{ $addr->contact_name }}">{{ $addr->contact_name }}</div>
                                                    <div class="text-xs text-gray-500">Liên hệ</div>
                                                </div>
                                            </div>
                                            <div class="addr-action shrink-0 flex items-center">
                                            @if($addr->is_default)
                                            <span class="addr-default-badge px-3 py-1 text-xs rounded-full bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-700 font-medium border border-emerald-200">
                                                <i class="fas fa-star mr-1"></i>Mặc định
                                                </span>
                                                @else
                                                <form method="POST" action="{{ route('user.addresses.set-default', $addr) }}" class="js-set-default-form inline" onclick="event.stopPropagation();">
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
                                            <div class="text-sm text-gray-700 leading-relaxed flex items-start gap-2 min-w-0">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px] font-medium shrink-0">Địa chỉ</span>
                                                <span class="text-gray-700 truncate" title="{{ $addr->address }}">{{ $addr->address }}</span>
                                            </div>
                                            @if($addr->city || $addr->state)
                                            <div class="flex flex-wrap items-center gap-2">
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
                                                @php
                                                    $__typeKey = strtolower($addr->type ?? '');
                                                    $__typeLabel = match($__typeKey){
                                                        'home' => 'Nhà riêng',
                                                        'work','office' => 'Cơ quan',
                                                        'billing' => 'Thanh toán',
                                                        'shipping' => 'Giao hàng',
                                                        default => ($addr->type ?: null)
                                                    };
                                                @endphp
                                                @if($__typeLabel)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 border border-sky-100 text-[11px]">
                                                    <i class="fas fa-tag text-[10px]"></i>
                                                    <span>Kiểu: {{ $__typeLabel }}</span>
                                                </span>
                                                @endif
                                            </div>
                                            @endif
                                        </div>

                                        <div class="text-xs text-gray-600 mb-2 flex items-center gap-2">
                                            <i class="fas fa-phone text-gray-400"></i>
                                            <span>Điện thoại: {{ $addr->phone }}</span>
                                        </div>
                                        @if($addr->notes)
                                        <div class="text-xs text-gray-600 mb-1 flex items-start gap-2">
                                            <i class="fas fa-sticky-note text-gray-400 mt-0.5"></i>
                                            <span>Ghi chú: {{ $addr->notes }}</span>
                                        </div>
                                                @endif
                                                
                                        <div class="">
                                            <form method="POST" action="{{ route('user.addresses.destroy', $addr) }}" class="js-delete-form absolute right-5 bottom-4">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="js-delete-btn inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-gray-300 text-xs text-red-600 hover:bg-red-50 hover:border-red-300 transition-all duration-300">
                                                        <i class="fas fa-trash"></i>
                                                        Xóa
                                                    </button>
                                                </form>
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
  // Normalize addresses list on load: default first, correct action area
  (function(){
    const cards = Array.from(document.querySelectorAll('[data-address-card]'));
    if (cards.length === 0) return;
    const grid = cards[0].parentElement;
    if (cards.length === 0) return;
    // Sort default first
    cards.sort((a,b)=> (parseInt(b.getAttribute('data-is-default')||'0') - parseInt(a.getAttribute('data-is-default')||'0')));
    cards.forEach(c=>grid.appendChild(c));
    // Normalize action area in each card strictly by is_default flag
    const token = document.querySelector('meta[name="csrf-token"]').content;
    cards.forEach((c)=>{
      const isDefault = (c.getAttribute('data-is-default') === '1');
      const actionArea = c.querySelector('.addr-action');
      if (!actionArea) return;
      actionArea.innerHTML = '';
      if (isDefault){
        const badge = document.createElement('span');
        badge.className = 'addr-default-badge px-3 py-1 text-xs rounded-full bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-700 font-medium border border-emerald-200';
        badge.innerHTML = '<i class="fas fa-star mr-1"></i>Mặc định';
        actionArea.appendChild(badge);
      } else {
        const form = document.createElement('form');
        form.className = 'js-set-default-form inline';
        form.method = 'POST';
        form.action = c.getAttribute('data-set-default-action');
        form.innerHTML = '<input type="hidden" name="_token" value="'+token+'">\
          <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 text-xs rounded-full border border-gray-300 text-gray-700 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-700 transition-all duration-300">\
            <i class="fas fa-check-circle text-emerald-600"></i>Đặt mặc định\
          </button>';
        actionArea.appendChild(form);
      }
    });
  })();

  // Toast notification function
  const __notify = (msg, type='success') => {
    try { if (typeof window.showMessage === 'function') { window.showMessage(msg, type); return; } } catch(_) {}
    try { if (typeof window.showToast === 'function') { window.showToast(msg, type); return; } } catch(_) {}
    // Fallback: lightweight responsive toast
    const colors = { success: 'bg-green-600', error: 'bg-red-600', warning: 'bg-yellow-600', info: 'bg-blue-600' };
    const wrapper = document.createElement('div');
    wrapper.className = `fixed top-4 right-4 z-[9999] max-w-sm w-[92vw] sm:w-96`;
    const toast = document.createElement('div');
    toast.className = `${colors[type]||colors.info} text-white px-4 py-3 rounded-lg shadow-lg flex items-start gap-3 animate-[fadeIn_.2s_ease]`;
    toast.innerHTML = `<i class="fas ${type==='success'?'fa-check-circle':type==='error'?'fa-exclamation-circle':type==='warning'?'fa-exclamation-triangle':'fa-info-circle'} mt-0.5"></i><div class="flex-1 leading-snug">${msg}</div><button class="text-white/90 hover:text-white"><i class="fas fa-times"></i></button>`;
    const closeBtn = toast.querySelector('button');
    closeBtn.addEventListener('click', ()=> wrapper.remove());
    wrapper.appendChild(toast);
    document.body.appendChild(wrapper);
    setTimeout(()=>{ try { wrapper.remove(); } catch(_) {} }, 3500);
  };

  // Session-based toasts are emitted below in separate script tags to avoid linter issues

  // AJAX create address with toast feedback
  (function(){
    const form = document.getElementById('address-create-form');
    if (!form) return;
    const methodField = form.querySelector('.js-method-field');
    const editingIdField = form.querySelector('.js-editing-id');
    const submitText = form.querySelector('.js-submit-text');
    const cancelBtn = document.getElementById('address-cancel-edit');
    const defaultCreateAction = form.getAttribute('action');
    const submitBtn = document.getElementById('address-submit-btn');
    const titleEl = document.getElementById('address-form-title');
    let prevWasDefault = false;

    // Reset to create mode
    function switchToCreateMode(){
      form.setAttribute('action', defaultCreateAction);
      methodField.value = 'POST';
      editingIdField.value = '';
      submitText.textContent = 'Thêm địa chỉ';
      if (titleEl) titleEl.textContent = 'Thêm địa chỉ';
      submitBtn.innerHTML = '<i class="fas fa-plus mr-2"></i><span class="js-submit-text">Thêm địa chỉ</span>';
      cancelBtn.classList.add('hidden');
      form.reset();
      // Keep default country localized
      const countryInput = form.querySelector('[name="country"]');
      if (countryInput && !countryInput.value) countryInput.value = 'Việt Nam';
    }
    // Expose for external callers (e.g., after deleting a card)
    try { window.__addressFormResetCreate = switchToCreateMode; } catch(_) {}

    cancelBtn && cancelBtn.addEventListener('click', function(){ switchToCreateMode(); });

    // Click card to edit
    document.addEventListener('click', function(e){
      const card = e.target.closest('[data-address-card]');
      if (!card) return;
      // Ignore clicks originating from interactive controls
      if (e.target.closest('.js-delete-form') || e.target.closest('.js-delete-btn') || e.target.closest('.js-set-default-form')) return;
      const updateUrl = card.getAttribute('data-update-action');
      if (!updateUrl) return;
      // Track previous default state
      prevWasDefault = (card.getAttribute('data-is-default') === '1');
      // Fill form
      const setVal = (name, val)=>{ const el = form.querySelector(`[name="${name}"]`); if (el) el.value = val || ''; };
      setVal('contact_name', card.getAttribute('data-contact-name'));
      setVal('phone', card.getAttribute('data-phone'));
      setVal('address', card.getAttribute('data-address'));
      setVal('city', card.getAttribute('data-city'));
      setVal('state', card.getAttribute('data-state'));
      setVal('postal_code', card.getAttribute('data-postal-code'));
      // Localize country display
      const countryRaw = card.getAttribute('data-country') || '';
      setVal('country', countryRaw === 'Vietnam' ? 'Việt Nam' : (countryRaw || 'Việt Nam'));
      setVal('type', (card.getAttribute('data-type') || '').toLowerCase());
      setVal('notes', card.getAttribute('data-notes'));
      // is_default cannot be changed here (use set-default flow)
      const chk = form.querySelector('[name="is_default"]'); if (chk) chk.checked = (card.getAttribute('data-is-default') === '1');
      // Switch to update mode
      form.setAttribute('action', updateUrl);
      methodField.value = 'PUT';
      editingIdField.value = card.getAttribute('data-address-id') || '';
      submitText.textContent = 'Cập nhật địa chỉ';
      if (titleEl) titleEl.textContent = 'Cập nhật địa chỉ';
      submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i><span class="js-submit-text">Cập nhật</span>';
      cancelBtn.classList.remove('hidden');
      // Do not auto-scroll the page on card click
    });

    form.addEventListener('submit', async function(ev){
      ev.preventDefault();
      // Client-side validation (Vietnamese)
      const contactName = (form.querySelector('[name="contact_name"]').value || '').trim();
      const phoneEl = form.querySelector('[name="phone"]');
      if (phoneEl) { try { phoneEl.setCustomValidity(''); } catch(_) {} }
      const phone = (phoneEl?.value || '').trim();
      const addressVal = (form.querySelector('[name="address"]').value || '').trim();
      const city = (form.querySelector('[name="city"]').value || '').trim();
      const state = (form.querySelector('[name="state"]').value || '').trim();
      const type = (form.querySelector('[name="type"]').value || '').trim();
      const isDefaultChecked = !!form.querySelector('[name="is_default"]') && form.querySelector('[name="is_default"]').checked;
      const allowedTypes = ['home','work','billing','shipping','other'];
      const errors = [];
      // Order: Họ tên, Số điện thoại, Địa chỉ, Quận/Huyện, Tỉnh/Thành phố
      if (!contactName) { errors.push('Họ tên liên hệ là bắt buộc.'); }
      if (!phone) { errors.push('Số điện thoại là bắt buộc.'); }
      if (!addressVal) { errors.push('Địa chỉ là bắt buộc.'); }
      if (!state) { errors.push('Quận/Huyện là bắt buộc.'); }
      if (!city) { errors.push('Tỉnh/Thành phố là bắt buộc.'); }
      if (type && !allowedTypes.includes(type)) errors.push('Loại địa chỉ không hợp lệ.');
      const phoneRegex = /^[0-9+\-\s()]+$/;
      if (phone) {
        if (!phoneRegex.test(phone)) errors.splice(1, 0, 'Số điện thoại không hợp lệ.');
        if (phone.length < 10) errors.splice(1, 0, 'Số điện thoại phải có ít nhất 10 ký tự.');
        if (phone.length > 15) errors.splice(1, 0, 'Số điện thoại không được vượt quá 15 ký tự.');
      }
      if (errors.length) { __notify(errors.join('\n'), 'error'); return; }
      const submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn){ submitBtn.disabled = true; submitBtn.classList.add('opacity-60'); submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Đang xử lý...'; }
      try {
        const fd = new FormData(form);
        // Ensure both name and contact_name are present for backend compatibility
        const nameVal = (fd.get('name') || '').toString().trim();
        if (nameVal && !fd.get('contact_name')) { fd.set('contact_name', nameVal); }
        if (!fd.get('name') && fd.get('contact_name')) { fd.set('name', fd.get('contact_name')); }
        // Localize country value to backend format
        const countryVal = (fd.get('country') || '').toString().trim();
        if (countryVal === 'Việt Nam') fd.set('country', 'Vietnam');
        // Sensible defaults
        if (!fd.get('type')) fd.set('type','home');
        if (!fd.get('country')) fd.set('country','Vietnam');
        
        // Decide method based on mode
        const isUpdate = (methodField.value.toUpperCase() === 'PUT');
        const url = form.getAttribute('action');
        const res = await fetch(url, {
          method: isUpdate ? 'POST' : 'POST',
          headers: {
            'X-Requested-With':'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept':'application/json'
          },
          body: (function(){ if (isUpdate) { fd.set('_method','PUT'); } return fd; })()
        });

        // If validation error
        if (res.status === 422) {
          let msg = 'Dữ liệu không hợp lệ. Vui lòng kiểm tra lại.';
          const data = await res.json().catch(()=>null);
          if (data && data.errors){
            const list = Object.values(data.errors).flat();
            msg = list.join('\n');
          } else if (data && data.message){ msg = data.message; }
          __notify(msg, 'error');
          return;
        }

        // Treat other 2xx/3xx as success
        // UX safeguard: if updating and checkbox "is_default" was checked, immediately paint badge on the edited card
        if (isUpdate && isDefaultChecked) {
          try {
            const editedId = (editingIdField && editingIdField.value) ? editingIdField.value : null;
            const editedCard = editedId ? document.querySelector(`[data-address-card][data-address-id="${editedId}"]`) : null;
            if (editedCard) {
              editedCard.setAttribute('data-is-default','1');
              const actionArea = editedCard.querySelector('.addr-action');
              if (actionArea) {
                actionArea.innerHTML = '<span class="addr-default-badge px-3 py-1 text-xs rounded-full bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-700 font-medium border border-emerald-200"><i class="fas fa-star mr-1"></i>Mặc định</span>';
              }
              // Move to top
              const gridEl = document.getElementById('addresses-grid') || (editedCard.parentElement);
              if (gridEl && gridEl.firstElementChild) gridEl.insertBefore(editedCard, gridEl.firstElementChild);
              // Restore other cards' buttons
              const token2 = document.querySelector('meta[name="csrf-token"]').content;
              const baseUrl = editedCard.getAttribute('data-set-default-action') || '';
              const cards = Array.from((gridEl || document).querySelectorAll('[data-address-card]'));
              cards.forEach(c => {
                if (c === editedCard) return;
                c.setAttribute('data-is-default','0');
                const act = c.querySelector('.addr-action');
                if (!act) return;
                act.innerHTML = '';
                let actionUrl = baseUrl;
                const currMatch = baseUrl.match(/\/(\d+)\/default$/);
                const currId = currMatch ? currMatch[1] : null;
                const otherId = c.getAttribute('data-address-id');
                if (currId && otherId && baseUrl.endsWith('/'+currId+'/default')) {
                  actionUrl = baseUrl.slice(0, -(''+currId).length - 8) + otherId + '/default';
                }
                const nf = document.createElement('form');
                nf.className = 'js-set-default-form inline';
                nf.method = 'POST';
                nf.action = actionUrl;
                nf.innerHTML = '<input type="hidden" name="_token" value="'+token2+'">\
                  <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 text-xs rounded-full border border-gray-300 text-gray-700 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-700 transition-all duration-300">\
                    <i class="fas fa-check-circle text-emerald-600"></i>Đặt mặc định\
                  </button>';
                act.appendChild(nf);
              });
            }
          } catch(_) {}
        }
        let data = null;
        try { data = await res.json(); } catch(_) { /* may be html */ }
        if (data && data.success && data.address) {
          __notify(data.message || (isUpdate ? 'Cập nhật địa chỉ thành công' : 'Thêm địa chỉ thành công'), 'success');
          // Append or update card
          let grid = document.getElementById('addresses-grid');
          if (!grid) {
            const panel = document.querySelector('.lg\\:col-span-2 .p-6');
            if (panel) {
              panel.innerHTML = '<div id="addresses-grid" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>';
              grid = document.getElementById('addresses-grid');
            }
          }
          if (grid) {
            const preCount = (grid.querySelectorAll('[data-address-card]') || []).length;
            const a = data.address;
            if (!isUpdate && preCount === 0) { a.is_default = true; }
            else if (!isUpdate && isDefaultChecked && !a.is_default) { a.is_default = true; }
            // If user checked default in form but server didn't echo flag, enforce client-side for UX
            if (isUpdate && isDefaultChecked && !a.is_default) { a.is_default = true; }
            const typeKey = (a.type || '').toLowerCase();
            const typeLabel = typeKey === 'home' ? 'Nhà riêng'
              : (typeKey === 'work' || typeKey === 'office' ? 'Cơ quan'
              : (typeKey === 'billing' ? 'Thanh toán'
              : (typeKey === 'shipping' ? 'Giao hàng' : (a.type || ''))));
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const existing = grid.querySelector(`[data-address-card][data-address-id="${a.id}"]`);
            const countryDisplay = (a.country === 'Vietnam' || a.country === 'Việt Nam') ? 'Việt Nam' : (a.country || 'Việt Nam');
            const cardHtml = `
              <div class=\"flex items-start justify-between mb-4\"> 
                <div class=\"flex items-center gap-3\"> 
                  <div class=\"w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center\"> 
                    <i class=\"fas fa-map-marker-alt text-sm\"></i> 
                  </div> 
                  <div> 
                    <div class=\"text-sm font-semibold text-gray-800\" data-address-header>${a.contact_name || ''}</div> 
                    <div class=\"text-xs text-gray-500\">Liên hệ</div> 
                  </div> 
                </div> 
                <div class=\"addr-action shrink-0 flex items-center\"></div> 
              </div>
              <div class=\"space-y-3 mb-4\"> 
                <div class=\"text-sm text-gray-700 leading-relaxed flex items-start gap-2\"> 
                  <span class=\"inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px] font-medium\">Địa chỉ</span> 
                  <span class=\"text-gray-700\">${a.address || ''}</span> 
                </div>
                ${(a.city || a.state) ? `
                <div class=\"flex flex-wrap items-center gap-2\"> 
                  ${a.state ? `<span class=\"inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100 text-[11px]\"> 
                    <i class=\"fas fa-landmark text-[10px]\"></i> 
                    <span>Quận/Huyện: ${a.state}</span> 
                  </span>` : ''} 
                  ${a.city ? `<span class=\"inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 text-[11px]\"> 
                    <i class=\"fas fa-city text-[10px]\"></i> 
                    <span>Tỉnh/Thành phố: ${a.city}</span> 
                  </span>` : ''} 
                  ${typeLabel ? `<span class=\"inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 border border-sky-100 text-[11px]\"> 
                    <i class=\"fas fa-tag text-[10px]\"></i> 
                    <span>Kiểu: ${typeLabel}</span> 
                  </span>` : ''} 
                </div>` : ''} 
              </div>
              <div class=\"text-xs text-gray-600 mb-2 flex items-center gap-2\"> 
                <i class=\"fas fa-phone text-gray-400\"></i> 
                <span>Điện thoại: ${a.phone || ''}</span> 
              </div> 
              ${a.notes ? `<div class=\"text-xs text-gray-600 mb-1 flex items-start gap-2\"> 
                <i class=\"fas fa-sticky-note text-gray-400 mt-0.5\"></i> 
                <span>Ghi chú: ${a.notes}</span> 
              </div>` : ''}
              <div class=\"\"> 
                <form method=\"POST\" action=\"${data.urls?.destroy || '#'}\" class=\"js-delete-form absolute right-5 bottom-4\"> 
                  <input type=\"hidden\" name=\"_token\" value=\"${token}\"> 
                  <input type=\"hidden\" name=\"_method\" value=\"DELETE\"> 
                  <button type=\"button\" class=\"js-delete-btn inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-gray-300 text-xs text-red-600 hover:bg-red-50 hover:border-red-300 transition-all duration-300\"> 
                    <i class=\"fas fa-trash\"></i> 
                    Xóa 
        </button>
                </form> 
              </div>`;

            if (existing) {
              // Update existing card content and datasets
              existing.setAttribute('data-contact-name', a.contact_name || '');
              existing.setAttribute('data-phone', a.phone || '');
              existing.setAttribute('data-address', a.address || '');
              existing.setAttribute('data-city', a.city || '');
              existing.setAttribute('data-state', a.state || '');
              existing.setAttribute('data-postal-code', a.postal_code || '');
              existing.setAttribute('data-country', a.country || 'Vietnam');
              existing.setAttribute('data-type', a.type || 'home');
              existing.setAttribute('data-notes', a.notes || '');
              existing.setAttribute('data-is-default', a.is_default ? '1' : '0');
              existing.innerHTML = cardHtml;
              // Restore structural attributes
              existing.setAttribute('data-set-default-action', data.urls?.set_default || '');
              existing.setAttribute('data-update-action', data.urls?.update || existing.getAttribute('data-update-action') || '');
            } else {
              const card = document.createElement('div');
              card.className = 'group relative p-5 pb-16 rounded-2xl border border-gray-200 bg-gradient-to-br from-gray-50 to-blue-50 hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 cursor-pointer';
              card.setAttribute('data-address-card','');
              card.setAttribute('data-address-id', a.id);
              card.setAttribute('data-is-default', a.is_default ? '1' : '0');
              card.setAttribute('data-set-default-action', data.urls?.set_default || '');
              card.setAttribute('data-update-action', data.urls?.update || (data.urls?.set_default ? (data.urls.set_default.replace(/\/default$/, '')) : ''));
              card.setAttribute('data-contact-name', a.contact_name || '');
              card.setAttribute('data-phone', a.phone || '');
              card.setAttribute('data-address', a.address || '');
              card.setAttribute('data-city', a.city || '');
              card.setAttribute('data-state', a.state || '');
              card.setAttribute('data-postal-code', a.postal_code || '');
              card.setAttribute('data-country', a.country || 'Vietnam');
              card.setAttribute('data-type', a.type || 'home');
              card.setAttribute('data-notes', a.notes || '');
              card.innerHTML = cardHtml;
              // If grid is currently empty, mark this new card as default and inject badge BEFORE appending
              try {
                const gridWasEmpty = !grid.querySelector('[data-address-card]');
                if (gridWasEmpty) {
                  console.debug('[Addresses] Pre-append: grid empty, forcing first card as default');
                  card.setAttribute('data-is-default','1');
                  const act0 = card.querySelector('.addr-action');
                  if (act0) {
                    act0.innerHTML = '<span class="addr-default-badge px-3 py-1 text-xs rounded-full bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-700 font-medium border border-emerald-200" style="display:inline-flex;align-items:center;gap:4px;z-index:1;position:relative;"><i class="fas fa-star mr-1"></i>Mặc định</span>';
                    console.debug('[Addresses] Pre-append: badge injected into .addr-action');
                  } else {
                    console.debug('[Addresses] Pre-append: .addr-action not found on card');
                  }
                  const wrap0 = document.getElementById('default-checkbox-wrap');
                  if (wrap0) { wrap0.classList.remove('hidden'); console.debug('[Addresses] Pre-append: default checkbox shown'); }
                }
              } catch(_) {}
              grid.appendChild(card);
              // Fallback: if this is the very first card, force badge and mark default immediately
              try {
                const currentCards = grid.querySelectorAll('[data-address-card]');
                if (currentCards.length === 1) {
                  console.debug('[Addresses] Post-append: only one card, forcing default');
                  const firstCard = currentCards[0];
                  firstCard.setAttribute('data-is-default','1');
                  const actionBox = firstCard.querySelector('.addr-action');
                  if (actionBox) {
                     try { actionBox.style.display = 'flex'; actionBox.style.alignItems = 'center'; } catch(_) {}
                    while (actionBox.firstChild) actionBox.removeChild(actionBox.firstChild);
                    const badge2 = document.createElement('span');
                    badge2.className = 'addr-default-badge px-3 py-1 text-xs rounded-full bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-700 font-medium border border-emerald-200';
                    badge2.style.display = 'inline-flex';
                    badge2.style.alignItems = 'center';
                    badge2.style.gap = '4px';
                    const icon2 = document.createElement('i');
                    icon2.className = 'fas fa-star mr-1';
                    badge2.appendChild(icon2);
                    badge2.appendChild(document.createTextNode('Mặc định'));
                    actionBox.appendChild(badge2);
                    // Schedule a re-check in case another script wipes the action area
                    setTimeout(function(){
                      const actCheck = firstCard.querySelector('.addr-action');
                      if (actCheck && !actCheck.querySelector('.addr-default-badge')) {
                        while (actCheck.firstChild) actCheck.removeChild(actCheck.firstChild);
                        const b = document.createElement('span');
                        b.className = 'addr-default-badge px-3 py-1 text-xs rounded-full bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-700 font-medium border border-emerald-200';
                        b.style.display = 'inline-flex'; b.style.alignItems = 'center'; b.style.gap = '4px';
                        const ic = document.createElement('i'); ic.className = 'fas fa-star mr-1'; b.appendChild(ic);
                        b.appendChild(document.createTextNode('Mặc định'));
                        actCheck.appendChild(b);
                      }
                    }, 50);
                  } else {
                    console.debug('[Addresses] Post-append: .addr-action missing on first card');
                  }
                  try { firstCard.scrollIntoView({behavior:'smooth', block:'nearest'}); } catch(_) {}
                  const wrap = document.getElementById('default-checkbox-wrap');
                  if (wrap) { wrap.classList.remove('hidden'); }
                } else {
                  console.debug('[Addresses] Post-append: total cards =', currentCards.length);
                }
              } catch (e) { }
            }

            // Populate action area for the affected card (new or updated)
            const targetCard = existing || grid.querySelector(`[data-address-card][data-address-id="${a.id}"]`);
            if (targetCard) {
              // Keep is-default attribute in sync
              targetCard.setAttribute('data-is-default', a.is_default ? '1' : '0');
              const actionArea = targetCard.querySelector('.addr-action');
              if (actionArea) {
                try { actionArea.style.display = 'flex'; actionArea.style.alignItems = 'center'; } catch(_) {}
                actionArea.innerHTML = '';
                if (a.is_default) {
                  const badge = document.createElement('span');
                  badge.className = 'addr-default-badge px-3 py-1 text-xs rounded-full bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-700 font-medium border border-emerald-200';
                  badge.innerHTML = '<i class="fas fa-star mr-1"></i>Mặc định';
                  actionArea.appendChild(badge);
                } else {
                  const f = document.createElement('form');
                  f.className = 'js-set-default-form inline';
                  f.method = 'POST';
                  f.action = (data.urls?.set_default || targetCard.getAttribute('data-set-default-action') || '#');
                  f.innerHTML = '<input type="hidden" name="_token" value="'+token+'">\
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 text-xs rounded-full border border-gray-300 text-gray-700 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-700 transition-all duration-300">\
                      <i class="fas fa-check-circle text-emerald-600"></i>Đặt mặc định\
                    </button>';
                  actionArea.appendChild(f);
                }
              }
            }
            // Force-default for the very first address (no reload). Ensure badge + checkbox update immediately.
            try {
              const gridEl = document.getElementById('addresses-grid') || (targetCard ? targetCard.parentElement : null);
              const totalCards = gridEl ? gridEl.querySelectorAll('[data-address-card]').length : 0;
              if (!isUpdate && totalCards === 1 && targetCard) {
                a.is_default = true; // so downstream logic also treats it as default
                targetCard.setAttribute('data-is-default','1');
                const actForce = targetCard.querySelector('.addr-action');
                if (actForce) {
                  actForce.innerHTML = '<span class="addr-default-badge px-3 py-1 text-xs rounded-full bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-700 font-medium border border-emerald-200"><i class="fas fa-star mr-1"></i>Mặc định</span>';
                }
                if (gridEl && gridEl.firstElementChild) gridEl.insertBefore(targetCard, gridEl.firstElementChild);
                const wrap = document.getElementById('default-checkbox-wrap');
                if (wrap) wrap.classList.remove('hidden');
              }
            } catch (_) {}

            // If it is default, move to top and update other cards' action areas
            const cards = Array.from(document.querySelectorAll('[data-address-card]'));
            if (a.is_default && cards.length) {
              document.querySelectorAll('.addr-default-badge').forEach(el => el.remove());
              cards.forEach(c => {
                if (String(c.getAttribute('data-address-id')) !== String(a.id)) {
                  c.setAttribute('data-is-default','0');
                  const act = c.querySelector('.addr-action');
                  if (!act) return;
                  act.innerHTML = '';
                  const token2 = document.querySelector('meta[name="csrf-token"]').content;
                  const baseUrl = (data.urls?.set_default || targetCard.getAttribute('data-set-default-action') || '');
                  let actionUrl = baseUrl;
                  const currMatch = baseUrl.match(/\/(\d+)\/default$/);
                  const currId = currMatch ? currMatch[1] : null;
                  const otherId = c.getAttribute('data-address-id');
                  if (currId && otherId && baseUrl.endsWith('/'+currId+'/default')) {
                    actionUrl = baseUrl.slice(0, -(''+currId).length - 8) + otherId + '/default';
                  }
                  const nf = document.createElement('form');
                  nf.className = 'js-set-default-form inline';
                  nf.method = 'POST';
                  nf.action = actionUrl;
                  nf.innerHTML = '<input type="hidden" name="_token" value="'+token2+'">\
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 text-xs rounded-full border border-gray-300 text-gray-700 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-700 transition-all duration-300">\
                      <i class="fas fa-check-circle text-emerald-600"></i>Đặt mặc định\
                    </button>';
                  act.appendChild(nf);
                }
              });
              // Move default to top
              const gridEl = document.getElementById('addresses-grid');
              const defCard = gridEl && gridEl.querySelector(`[data-address-card][data-address-id="${a.id}"]`);
              if (gridEl && defCard) gridEl.insertBefore(defCard, gridEl.firstElementChild);
            }

            // Update count only on create
            if (!existing) {
              const countElement = document.querySelector('.text-sm.text-gray-500');
              if (countElement) {
                const currentCount = parseInt((countElement.textContent || '0').replace(/\D/g,'')) || 0;
                countElement.textContent = `${currentCount + 1} địa chỉ`;
              }
            }
          }

          // After success, if update mode: switch back to create mode (but keep form values if you want)
          if (isUpdate) {
            switchToCreateMode();
          } else {
            form.reset();
            const countryInput = form.querySelector('[name="country"]');
            if (countryInput && !countryInput.value) countryInput.value = 'Việt Nam';
          }
          // Final fallback: ensure badge exists if user ticked default
          if (isUpdate && isDefaultChecked) {
            setTimeout(()=>{
              const editedId = (editingIdField && editingIdField.value) ? editingIdField.value : (data && data.address ? data.address.id : null);
              const card = editedId ? document.querySelector(`[data-address-card][data-address-id="${editedId}"]`) : null;
              if (card) {
                const actionArea = card.querySelector('.addr-action');
                card.setAttribute('data-is-default','1');
                if (actionArea && !actionArea.querySelector('.addr-default-badge')) {
                  actionArea.innerHTML = '<span class="addr-default-badge px-3 py-1 text-xs rounded-full bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-700 font-medium border border-emerald-200"><i class="fas fa-star mr-1"></i>Mặc định</span>';
                }
                const gridEl = document.getElementById('addresses-grid') || card.parentElement;
                if (gridEl && gridEl.firstElementChild) gridEl.insertBefore(card, gridEl.firstElementChild);
              }
            }, 0);
          } else if (!isUpdate && isDefaultChecked) {
            setTimeout(()=>{
              const newId = (data && data.address ? data.address.id : null);
              const card = newId ? document.querySelector(`[data-address-card][data-address-id="${newId}"]`) : null;
              if (card) {
                card.setAttribute('data-is-default','1');
                const actionArea = card.querySelector('.addr-action');
                if (actionArea) {
                  actionArea.innerHTML = '<span class="addr-default-badge px-3 py-1 text-xs rounded-full bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-700 font-medium border border-emerald-200"><i class="fas fa-star mr-1"></i>Mặc định</span>';
                }
                const gridEl = document.getElementById('addresses-grid') || card.parentElement;
                if (gridEl && gridEl.firstElementChild) gridEl.insertBefore(card, gridEl.firstElementChild);
                // Demote others to show "Đặt mặc định" buttons
                const token2 = document.querySelector('meta[name="csrf-token"]').content;
                const baseUrl = card.getAttribute('data-set-default-action') || '';
                const cards = Array.from((gridEl || document).querySelectorAll('[data-address-card]'));
                cards.forEach(c => {
                  if (c === card) return;
                  c.setAttribute('data-is-default','0');
                  const act = c.querySelector('.addr-action');
                  if (!act) return;
                  act.innerHTML = '';
                  let actionUrl = baseUrl;
                  const currMatch = baseUrl.match(/\/(\d+)\/default$/);
                  const currId = currMatch ? currMatch[1] : null;
                  const otherId = c.getAttribute('data-address-id');
                  if (currId && otherId && baseUrl.endsWith('/'+currId+'/default')) {
                    actionUrl = baseUrl.slice(0, -(''+currId).length - 8) + otherId + '/default';
                  }
                  const nf = document.createElement('form');
                  nf.className = 'js-set-default-form inline';
                  nf.method = 'POST';
                  nf.action = actionUrl;
                  nf.innerHTML = '<input type="hidden" name="_token" value="'+token2+'">\
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 text-xs rounded-full border border-gray-300 text-gray-700 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-700 transition-all duration-300">\
                      <i class="fas fa-check-circle text-emerald-600"></i>Đặt mặc định\
                    </button>';
                  act.appendChild(nf);
                });
              }
            }, 0);
          }
          return;
        }

        __notify(isUpdate ? 'Cập nhật địa chỉ thành công' : 'Thêm địa chỉ thành công', 'success');
      } catch (e){
        __notify('Không thể kết nối máy chủ. Vui lòng thử lại.', 'error');
      } finally {
        if (submitBtn){ submitBtn.disabled = false; submitBtn.classList.remove('opacity-60'); const isUpd = (methodField.value.toUpperCase()==='PUT'); submitBtn.innerHTML = isUpd ? '<i class="fas fa-save mr-2"></i><span class="js-submit-text">Cập nhật</span>' : '<i class="fas fa-plus mr-2"></i><span class="js-submit-text">Thêm địa chỉ</span>'; }
      }
    });
  })();

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
          // Remove all existing badges
          document.querySelectorAll('.addr-default-badge').forEach(el => el.remove());
          // Reveal any previously hidden set-default buttons
          document.querySelectorAll('.js-set-default-form').forEach(f => f.classList.remove('hidden'));
          
          // Current card
          const card = form.closest('[data-address-card]') || form.closest('.p-4');
          if (card) {
            const actionBox = card.querySelector('.addr-action');
            if (actionBox) {
              actionBox.innerHTML = '';
              const badge = document.createElement('span');
              badge.className = 'addr-default-badge px-3 py-1 text-xs rounded-full bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-700 font-medium border border-emerald-200';
              badge.innerHTML = '<i class="fas fa-star mr-1"></i>Mặc định';
              actionBox.appendChild(badge);
            }
            // Mark attribute and remove any forms outside action area
            card.setAttribute('data-is-default','1');
            card.querySelectorAll('.js-set-default-form').forEach(f => { if (!actionBox || !actionBox.contains(f)) f.remove(); });
            // Move this card to the top of the grid
            const grid = card.parentElement;
            if (grid && grid.firstElementChild) {
              grid.insertBefore(card, grid.firstElementChild);
            }
            // Ensure other cards have set-default button in the same action area
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const currentUrl = form.getAttribute('action');
            const currentIdMatch = currentUrl.match(/\/(\d+)$/);
            const currentId = currentIdMatch ? currentIdMatch[1] : null;
            const cards = grid ? Array.from(grid.querySelectorAll('[data-address-card]')) : [];
            cards.forEach(c => {
              if (c === card) return;
              c.setAttribute('data-is-default','0');
              const actionArea = c.querySelector('.addr-action');
              if (!actionArea) return;
              // Clear action area
              actionArea.innerHTML = '';
              // Create set-default form
              const addrIdAttr = c.getAttribute('data-address-id');
              let actionUrl = currentUrl;
              if (addrIdAttr && currentId && actionUrl.endsWith('/' + currentId + '/default')) {
                actionUrl = actionUrl.slice(0, -currentId.length - 8) + addrIdAttr + '/default';
              }
              const newForm = document.createElement('form');
              newForm.className = 'js-set-default-form inline';
              newForm.method = 'POST';
              newForm.action = actionUrl;
              newForm.innerHTML = '<input type="hidden" name="_token" value="' + token + '">\
                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 text-xs rounded-full border border-gray-300 text-gray-700 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-700 transition-all duration-300">\
                  <i class="fas fa-check-circle text-emerald-600"></i>Đặt mặc định\
                </button>';
              actionArea.appendChild(newForm);
            });
          }
          __notify('Đã đặt làm địa chỉ mặc định!', 'success');
        } else {
          throw new Error(res?.message || 'Có lỗi xảy ra');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        __notify('Có lỗi xảy ra khi đặt địa chỉ mặc định', 'error');
      })
      .finally(() => {
        if (btn) {
          btn.disabled = false;
          btn.classList.remove('opacity-60');
          btn.innerHTML = originalText;
        }
      });
  });

  // AJAX delete address (click-based, no native submit)
  document.addEventListener('click', function(e) {
    const btn = e.target.closest('.js-delete-btn');
    if (!btn) return;
    const form = btn.closest('.js-delete-form');
    if (!form) return;
    
    // Modern confirm dialog
    const showConfirmDialog = (title, message, confirmText, cancelText, onConfirm) => {
      const existing = document.querySelector('.fast-confirm-dialog');
      if (existing) existing.remove();
      const wrapper = document.createElement('div');
      wrapper.className = 'fast-confirm-dialog fixed inset-0 z-[10000] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4';
      wrapper.innerHTML = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-200 scale-95 opacity-0">
          <div class="p-6">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
              <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">${title}</h3>
            <p class="text-gray-600 text-center mb-6">${message}</p>
            <div class="flex flex-col sm:flex-row gap-3">
              <button class="fast-cancel flex-1 px-4 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200">${cancelText}</button>
              <button class="fast-confirm flex-1 px-4 py-2.5 text-white bg-red-600 hover:bg-red-700 rounded-lg font-medium transition-colors duration-200">${confirmText}</button>
            </div>
          </div>
        </div>`;
      document.body.appendChild(wrapper);
      const panel = wrapper.firstElementChild;
      requestAnimationFrame(()=>{ panel.classList.remove('scale-95','opacity-0'); panel.classList.add('scale-100','opacity-100'); });
      const close = ()=>{ wrapper.remove(); };
      wrapper.addEventListener('click', ev => { if (ev.target === wrapper) close(); });
      wrapper.querySelector('.fast-cancel').addEventListener('click', close);
      wrapper.querySelector('.fast-confirm').addEventListener('click', () => { close(); onConfirm && onConfirm(); });
      document.addEventListener('keydown', function esc(e){ if (e.key==='Escape'){ close(); document.removeEventListener('keydown', esc); } });
    };

    showConfirmDialog('Xóa địa chỉ này?', 'Bạn có chắc chắn muốn xóa địa chỉ đã chọn? Hành động này không thể hoàn tác.', 'Xóa', 'Hủy', () => {
    const originalText = btn ? btn.innerHTML : '';
      if (btn) { btn.disabled = true; btn.classList.add('opacity-60'); btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Đang xóa...'; }

      const url = form.getAttribute('action');
      const fd = new FormData(form);
      if (!fd.get('_method')) fd.set('_method','DELETE');

      fetch(url, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json'
        },
        body: fd
      })
      .then(async (response) => {
        if (response.status === 204) return { success: true };
        if (!response.ok) {
          let errMsg = 'Có lỗi xảy ra khi xóa địa chỉ';
          try { const j = await response.json(); if (j && j.message) errMsg = j.message; } catch(_) {}
          throw new Error(errMsg);
        }
        try { return await response.json(); } catch(_) { return { success: true }; }
      })
      .then(res => {
        if (!res || res.success !== true) throw new Error(res?.message || 'Có lỗi xảy ra');
        const card = form.closest('[data-address-card]');
          if (card) {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.95)';
            setTimeout(() => {
              card.remove();
              const countElement = document.querySelector('.text-sm.text-gray-500');
              if (countElement) {
              const currentCount = parseInt((countElement.textContent || '0').replace(/\D/g,'')) || 1;
              countElement.textContent = `${Math.max(0, currentCount - 1)} địa chỉ`;
              }
              // If one card remains, force it as default and show badge immediately
              const gridAfter = document.getElementById('addresses-grid');
              if (gridAfter) {
                const remainingCards = gridAfter.querySelectorAll('[data-address-card]');
                if (remainingCards.length === 1) {
                  const onlyCard = remainingCards[0];
                  onlyCard.setAttribute('data-is-default','1');
                  const actionArea = onlyCard.querySelector('.addr-action');
                  if (actionArea) {
                    actionArea.innerHTML = '<span class="addr-default-badge px-3 py-1 text-xs rounded-full bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-700 font-medium border border-emerald-200"><i class="fas fa-star mr-1"></i>Mặc định</span>';
                  }
                }
              }
              // If form is currently in edit mode for this card, switch back to create mode
              try {
                const editingId = document.querySelector('.js-editing-id')?.value || '';
                if (editingId && card.getAttribute('data-address-id') === editingId) {
                  window.__addressFormResetCreate && window.__addressFormResetCreate();
                }
              } catch(_) {}
              // If no cards remain, render empty state
              const grid = document.getElementById('addresses-grid');
              if (grid && grid.querySelectorAll('[data-address-card]').length === 0) {
                const panel = grid.parentElement;
                if (panel) {
                  panel.innerHTML = `
                    <div class="text-center py-12">
                      <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full mx-auto mb-6 flex items-center justify-center">
                        <i class="fas fa-map-marker-alt text-blue-500 text-2xl"></i>
                      </div>
                      <div class="text-gray-600 mb-2 font-medium">Chưa có địa chỉ nào</div>
                      <div class="text-gray-500 mb-6 text-sm">Thêm địa chỉ đầu tiên để dễ dàng mua sắm</div>
                    </div>`;
                }
                // Hide default checkbox on form when list becomes empty
                const wrap = document.getElementById('default-checkbox-wrap');
                if (wrap) wrap.classList.add('hidden');
              }
            }, 300);
        }
        __notify('Đã xóa địa chỉ thành công!', 'success');
      })
      .catch(error => {
        console.error('Error:', error);
        __notify(error.message || 'Có lỗi xảy ra khi xóa địa chỉ', 'error');
      })
      .finally(() => {
        if (btn) { btn.disabled = false; btn.classList.remove('opacity-60'); btn.innerHTML = originalText; }
      });
      });
  });
</script>

@if(session('success'))
  <div id="flash-success-msg" data-msg='@json(session('success'))'></div>
  <script>window.addEventListener('DOMContentLoaded', function(){ try{ var el=document.getElementById('flash-success-msg'); if(el){ var m=JSON.parse(el.getAttribute('data-msg')); __notify(m,'success'); el.remove(); } }catch(_){ } });</script>
@endif
@if(session('warning'))
  <div id="flash-warning-msg" data-msg='@json(session('warning'))'></div>
  <script>window.addEventListener('DOMContentLoaded', function(){ try{ var el=document.getElementById('flash-warning-msg'); if(el){ var m=JSON.parse(el.getAttribute('data-msg')); __notify(m,'warning'); el.remove(); } }catch(_){ } });</script>
@endif
@if($errors->any())
  <div id="flash-error-msg" data-msg='@json(implode("\n", $errors->all()))'></div>
  <script>window.addEventListener('DOMContentLoaded', function(){ try{ var el=document.getElementById('flash-error-msg'); if(el){ var m=JSON.parse(el.getAttribute('data-msg')); __notify(m,'error'); el.remove(); } }catch(_){ } });</script>
@endif
@endpush


