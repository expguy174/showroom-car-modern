@extends('layouts.app')

@section('title', 'Sửa lịch bảo dưỡng')

@section('content')
<div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
  <div class="flex items-start sm:items-center justify-between gap-3 mb-4 sm:mb-6">
    <div class="min-w-0">
      <div class="flex items-center gap-2 flex-wrap">
        <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight truncate">Sửa lịch bảo dưỡng #{{ $appointment->appointment_number }}</h1>
        <span class="px-2 py-0.5 rounded-full text-xs whitespace-nowrap inline-flex items-center {{ \App\Helpers\ServiceAppointmentHelper::statusBadgeClass($appointment->status) }}" data-role="status-badge">{{ \App\Helpers\ServiceAppointmentHelper::statusLabel($appointment->status) }}</span>
      </div>
      <div class="text-xs sm:text-sm text-gray-500 mt-1">{{ \App\Helpers\ServiceAppointmentHelper::formatDate($appointment->appointment_date) }} {{ $appointment->appointment_time }} • {{ $appointment->showroom->name }}</div>
    </div>
    <a href="{{ route('user.service-appointments.show', $appointment->id) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold shrink-0"><i class="fas fa-arrow-left"></i> Quay lại</a>
  </div>

  <div class="max-w-7xl mx-auto px-0">
    <form id="sa-edit-form" method="POST" action="{{ route('user.service-appointments.update', $appointment->id) }}" class="grid grid-cols-1 lg:grid-cols-3 gap-6" novalidate>
      @csrf
      @method('PUT')

      <div class="lg:col-span-2 space-y-4 sm:space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
          <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Thông tin lịch hẹn</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm text-gray-700 mb-1">Showroom <span class="text-rose-600">*</span></label>
              <select name="showroom_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                @foreach(\App\Models\Showroom::where('is_active', true)->get() as $s)
                  <option value="{{ $s->id }}" @selected(old('showroom_id', $appointment->showroom_id)==$s->id)>{{ $s->name }}</option>
                @endforeach
              </select>
              @error('showroom_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm text-gray-700 mb-1">Xe <span class="text-rose-600">*</span></label>
              <select name="car_variant_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                @foreach(\App\Models\CarVariant::with(['carModel.carBrand'])->get() as $v)
                  <option value="{{ $v->id }}" @selected(old('car_variant_id', $appointment->car_variant_id)==$v->id)>{{ $v->carModel->carBrand->name }} {{ $v->carModel->name }} - {{ $v->name ?? 'Phiên bản' }}</option>
                @endforeach
              </select>
              @error('car_variant_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm text-gray-700 mb-1">Loại hẹn <span class="text-rose-600">*</span></label>
              @php $types=['maintenance'=>'Bảo dưỡng','repair'=>'Sửa chữa','inspection'=>'Kiểm tra','warranty_work'=>'Bảo hành','recall_service'=>'Triệu hồi','emergency'=>'Khẩn cấp','other'=>'Khác']; @endphp
              <select name="appointment_type" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                @foreach($types as $val=>$label)
                  <option value="{{ $val }}" @selected(old('appointment_type', $appointment->appointment_type)==$val)>{{ $label }}</option>
                @endforeach
              </select>
              @error('appointment_type')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm text-gray-700 mb-1">Ngày hẹn <span class="text-rose-600">*</span></label>
              <input type="date" name="appointment_date" value="{{ old('appointment_date', optional($appointment->appointment_date)->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="yyyy-mm-dd" required>
              @error('appointment_date')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm text-gray-700 mb-1">Giờ hẹn (HH:MM) <span class="text-rose-600">*</span></label>
              <input type="time" name="appointment_time" value="{{ old('appointment_time', substr((string)$appointment->appointment_time, 0, 5)) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="09:00" required>
              @error('appointment_time')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
          <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Thông tin khách hàng & xe</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm text-gray-700 mb-1">Họ tên <span class="text-rose-600">*</span></label>
              <input type="text" name="customer_name" value="{{ old('customer_name', $appointment->customer_name ?: (optional(auth()->user()->userProfile)->name ?? (auth()->user()->name ?? ''))) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
              @error('customer_name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm text-gray-700 mb-1">Số điện thoại <span class="text-rose-600">*</span></label>
              <input type="text" name="customer_phone" value="{{ old('customer_phone', $appointment->customer_phone ?: (optional(auth()->user()->userProfile)->phone ?? (auth()->user()->phone ?? ''))) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
              @error('customer_phone')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm text-gray-700 mb-1">Email <span class="text-rose-600">*</span></label>
              <input type="email" name="customer_email" value="{{ old('customer_email', $appointment->customer_email ?: (auth()->user()->email ?? '')) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
              @error('customer_email')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm text-gray-700 mb-1">Biển số</label>
              <input type="text" name="vehicle_registration" value="{{ old('vehicle_registration', $appointment->vehicle_registration) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="VD: 51A-123.45">
              @error('vehicle_registration')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm text-gray-700 mb-1">Số km hiện tại</label>
              <input type="number" name="current_mileage" value="{{ old('current_mileage', $appointment->current_mileage) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="VD: 25000">
              @error('current_mileage')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>
      </div>

      <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
          <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Nội dung yêu cầu</h2>
          <div class="space-y-4">
            <div>
              <label class="block text-sm text-gray-700 mb-1">Mô tả dịch vụ <span class="text-rose-600">*</span></label>
              <textarea name="service_description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Mô tả ngắn gọn dịch vụ bạn cần" required>{{ old('service_description', $appointment->service_description) }}</textarea>
              @error('service_description')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
              <input type="checkbox" name="is_warranty_work" value="1" class="rounded border-gray-300" @checked(old('is_warranty_work', $appointment->is_warranty_work))>
              Là công việc bảo hành
            </label>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
          <button class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 font-semibold"><i class="fas fa-save"></i> Lưu thay đổi</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const form = document.getElementById('sa-edit-form');
  if (!form) return;
  form.addEventListener('submit', async function(e){
    e.preventDefault();
    const btn = form.querySelector('button[type="submit"], button[type="button"].submit');
    const submitBtn = btn || form.querySelector('button');
    if (submitBtn){ submitBtn.disabled = true; submitBtn.classList.add('opacity-60'); }
    // Client-side minimal validation with Vietnamese toast
    try {
      const requiredFields = [
        ['showroom_id','Vui lòng chọn showroom.'],
        ['car_variant_id','Vui lòng chọn xe.'],
        ['appointment_type','Vui lòng chọn loại hẹn.'],
        ['appointment_date','Vui lòng chọn ngày hẹn.'],
        ['appointment_time','Vui lòng chọn giờ hẹn.'],
        ['customer_name','Vui lòng nhập họ tên.'],
        ['customer_phone','Vui lòng nhập số điện thoại.'],
        ['customer_email','Vui lòng nhập email.'],
        ['service_description','Vui lòng nhập mô tả dịch vụ.'],
        ['priority','Vui lòng chọn ưu tiên.']
      ];
      for (const [name, msg] of requiredFields){
        const el = form.querySelector(`[name="${name}"]`);
        if (!el) continue;
        const val = (el.value || '').toString().trim();
        if (!val){
          if (typeof window.showMessage === 'function') window.showMessage(msg, 'error');
          el.focus();
          throw new Error('validation-stop');
        }
      }
      // Time format HH:MM validation
      const timeEl = form.querySelector('[name="appointment_time"]');
      if (timeEl){
        let t = String(timeEl.value||'').trim();
        const m = t.match(/^([01]?\d|2[0-3]):([0-5]\d)(?::([0-5]\d))?$/);
        if (!m){
          if (typeof window.showMessage === 'function') window.showMessage('Giờ hẹn không hợp lệ (HH:MM).', 'error');
          timeEl.focus();
          throw new Error('validation-stop');
        }
        // Normalize value to HH:MM for consistency before submit
        timeEl.value = `${('0'+parseInt(m[1],10)).slice(-2)}:${m[2]}`;
      }
    } catch (err) {
      if (submitBtn){ submitBtn.disabled = false; submitBtn.classList.remove('opacity-60'); }
      if (err && err.message === 'validation-stop') return; // stop submit
    }
    try {
      const fd = new FormData(form);
      const res = await fetch(form.getAttribute('action'), { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept':'application/json' }, body: fd });
      const data = await res.json().catch(()=>({}));
      if (res.ok && data && data.success){
        if (typeof window.showMessage === 'function') window.showMessage(data.message || 'Cập nhật lịch bảo dưỡng thành công!', 'success');
        if (data.redirect) window.location.href = data.redirect;
      } else {
        const msg = (data && data.message) || 'Dữ liệu chưa hợp lệ hoặc có lỗi xảy ra.';
        if (typeof window.showMessage === 'function') window.showMessage(msg, 'error');
        if (data && data.errors){
          // Optionally map first field error to focus
          const firstKey = Object.keys(data.errors)[0];
          const el = firstKey ? form.querySelector(`[name="${firstKey}"]`) : null;
          if (el) el.focus();
        }
      }
    } catch {
      if (typeof window.showMessage === 'function') window.showMessage('Không thể kết nối máy chủ. Vui lòng thử lại.', 'error');
    } finally {
      if (submitBtn){ submitBtn.disabled = false; submitBtn.classList.remove('opacity-60'); }
    }
  });
})();
</script>
@endpush


