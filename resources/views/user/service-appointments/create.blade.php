@extends('layouts.app')

@section('title', 'Đặt lịch bảo dưỡng')

@section('content')
<div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
    <div class="flex items-start sm:items-center justify-between gap-3 mb-4 sm:mb-6">
        <div class="min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
                <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight truncate">Đặt lịch bảo dưỡng</h1>
            </div>
            <div class="text-xs sm:text-sm text-gray-500 mt-1">Chọn showroom, xe và thời gian phù hợp</div>
        </div>
        <a href="{{ route('user.service-appointments.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>

    <div class="max-w-7xl mx-auto px-0">
        <form id="sa-create-form" method="POST" action="{{ route('user.service-appointments.store') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-6" novalidate>
            @csrf
            <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Thông tin lịch hẹn</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Showroom <span class="text-rose-600">*</span></label>
                            <select name="showroom_id" id="showroom_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                                <option value="">-- Chọn showroom --</option>
                                @foreach($showrooms as $s)
                                    <option value="{{ $s->id }}" @selected(old('showroom_id')==$s->id)>{{ $s->name }}</option>
                                @endforeach
                            </select>
                            @error('showroom_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Xe <span class="text-rose-600">*</span></label>
                            <select name="car_variant_id" id="car_variant_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                                <option value="">-- Chọn xe --</option>
                                @foreach($carVariants as $v)
                                    <option value="{{ $v->id }}" @selected(old('car_variant_id')==$v->id)>
                                        {{ $v->carModel->carBrand->name }} {{ $v->carModel->name }} - {{ $v->name ?? 'Phiên bản' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('car_variant_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Loại hẹn <span class="text-rose-600">*</span></label>
                            <select name="appointment_type" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                                @php $types=['maintenance'=>'Bảo dưỡng','repair'=>'Sửa chữa','inspection'=>'Kiểm tra','warranty_work'=>'Bảo hành','recall_service'=>'Triệu hồi','emergency'=>'Khẩn cấp','other'=>'Khác']; @endphp
                                @foreach($types as $val=>$label)
                                    <option value="{{ $val }}" @selected(old('appointment_type')==$val)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('appointment_type')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Ngày hẹn <span class="text-rose-600">*</span></label>
                            <input type="date" name="appointment_date" id="appointment_date" value="{{ old('appointment_date') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" placeholder="yyyy-mm-dd" required>
                            @error('appointment_date')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Giờ hẹn (HH:MM) <span class="text-rose-600">*</span></label>
                            <input type="time" name="appointment_time" id="appointment_time" value="{{ old('appointment_time') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" placeholder="09:00" required>
                            @error('appointment_time')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Thông tin khách hàng & xe</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Họ tên <span class="text-rose-600">*</span></label>
                            <input type="text" name="customer_name" value="{{ old('customer_name', optional(auth()->user()->userProfile)->name ?? (auth()->user()->name ?? '')) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                            @error('customer_name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Số điện thoại <span class="text-rose-600">*</span></label>
                            <input type="text" name="customer_phone" value="{{ old('customer_phone', optional(auth()->user()->userProfile)->phone ?? (auth()->user()->phone ?? '')) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                            @error('customer_phone')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Email <span class="text-rose-600">*</span></label>
                            <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                            @error('customer_email')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Biển số</label>
                            <input type="text" name="vehicle_registration" value="{{ old('vehicle_registration') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" placeholder="VD: 51A-123.45">
                            @error('vehicle_registration')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Số km hiện tại</label>
                            <input type="number" step="1" name="current_mileage" value="{{ old('current_mileage') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" placeholder="VD: 25000">
                            @error('current_mileage')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Nội dung yêu cầu</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm text-gray-700 mb-1">Dịch vụ yêu cầu <span class="text-rose-600">*</span></label>
                            <textarea name="requested_services" rows="3" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" placeholder="VD: Bảo dưỡng định kỳ 20.000km" required>{{ old('requested_services') }}</textarea>
                            @error('requested_services')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm text-gray-700 mb-1">Mô tả chi tiết</label>
                            <textarea name="service_description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" placeholder="Thông tin thêm giúp kỹ thuật viên chuẩn bị tốt hơn (tùy chọn)">{{ old('service_description') }}</textarea>
                            @error('service_description')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="is_warranty_work" value="1" class="rounded border-gray-300" @checked(old('is_warranty_work'))>
                                Là công việc bảo hành
                            </label>
                        </div>
                        <div class="md:col-span-2">
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm transition font-semibold">
                                <i class="fas fa-save"></i> Đặt lịch
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4 sm:space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Kiểm tra lịch trống</h2>
                    <p class="text-sm text-gray-600 mb-3">Chọn showroom và ngày, sau đó bấm kiểm tra để xem slot còn trống.</p>
                    <button type="button" id="btn-check-slots" class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1">
                        <i class="fas fa-calendar-check"></i> Kiểm tra slot trống
                    </button>
                    <div id="slot-results" class="mt-4 space-y-2 text-sm"></div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3">Lưu ý</h2>
                    <ul class="list-disc pl-5 text-sm text-gray-700 space-y-1">
                        <li>Thời gian xác nhận có thể mất đến 30 phút.</li>
                        <li>Vui lòng đến sớm 10 phút để làm thủ tục.</li>
                        <li>Nếu cần đổi lịch, bạn có thể thực hiện trong mục lịch bảo dưỡng.</li>
                    </ul>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3">Liên hệ hỗ trợ</h2>
                    <div class="text-sm text-gray-700 space-y-1">
                        <div><i class="fas fa-phone mr-2 text-gray-500"></i>Hotline: <span class="font-medium">1900 1234</span></div>
                        <div><i class="fas fa-envelope mr-2 text-gray-500"></i>Email: <span class="font-medium">support@example.vn</span></div>
                        <div><i class="fas fa-clock mr-2 text-gray-500"></i>Giờ làm việc: 08:00–18:00 (T2–T7)</div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  // AJAX submit create form
  const form = document.getElementById('sa-create-form');
  if (form){
    // mini summary binding removed with header change
    form.addEventListener('submit', async function(e){
      e.preventDefault();
      const btn = form.querySelector('button[type="submit"]');
      if (btn){ btn.disabled = true; btn.classList.add('opacity-60'); }
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
          ['requested_services','Vui lòng nhập dịch vụ yêu cầu.']
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
      } catch (err) {
        if (btn){ btn.disabled = false; btn.classList.remove('opacity-60'); }
        if (err && err.message === 'validation-stop') return; // stop submit
      }
      try {
        const fd = new FormData(form);
        const res = await fetch(form.getAttribute('action'), { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept':'application/json' }, body: fd });
        const data = await res.json().catch(()=>({}));
        if (res.ok && data && data.success){
          if (typeof window.showMessage === 'function') window.showMessage(data.message || 'Đặt lịch bảo dưỡng thành công!', 'success');
          const go = () => {
            if (data.redirect){ window.location.href = data.redirect; }
            else if (data.id){ window.location.href = `{{ url('service-appointments') }}/${data.id}`; }
          };
          setTimeout(go, 1400);
        } else {
          // Hiển thị lỗi bằng toast chung (tiếng Việt), không chèn HTML dưới trường
          let message = (data && data.message) || 'Dữ liệu chưa hợp lệ. Vui lòng kiểm tra lại.';
          if (data && data.errors){
            try {
              const list = Object.values(data.errors).map(v => Array.isArray(v) ? v[0] : v).filter(Boolean);
              if (list.length){ message = list.join('\n'); }
            } catch {}
          }
          if (typeof window.showMessage === 'function') window.showMessage(message, 'error');
        }
      } catch {
        if (typeof window.showMessage === 'function') window.showMessage('Không thể kết nối máy chủ. Vui lòng thử lại.', 'error');
      } finally {
        if (btn){ btn.disabled = false; btn.classList.remove('opacity-60'); }
      }
    });
  }
  const btn = document.getElementById('btn-check-slots');
  const out = document.getElementById('slot-results');
  if (!btn || !out) return;
  btn.addEventListener('click', async function(){
    const showroom = document.getElementById('showroom_id')?.value;
    const date = document.getElementById('appointment_date')?.value;
    if (!showroom || !date){
      if (typeof window.showMessage === 'function') window.showMessage('Vui lòng chọn showroom và ngày.', 'error');
      return;
    }
    out.innerHTML = '<div class="text-gray-600"><i class="fas fa-spinner fa-spin"></i> Đang kiểm tra...</div>';
    try {
      const res = await fetch("{{ route('user.service-appointments.check-availability') }}", { method:'POST', headers:{ 'Content-Type':'application/json', 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify({ showroom_id: showroom, date }) });
      const data = await res.json();
      const slots = Array.isArray(data.available_slots) ? data.available_slots : [];
      if (!slots.length){
        out.innerHTML = '';
        if (typeof window.showMessage === 'function') window.showMessage('Không còn slot trống cho ngày này.', 'info');
        return;
      }
      out.innerHTML = slots.map(s=>`<div class=\"flex items-center justify-between px-3 py-2 rounded border ${s.status==='available'?'border-emerald-200 bg-emerald-50':'border-amber-200 bg-amber-50'}\"><span><i class=\"fas fa-clock mr-2\"></i>${s.time}</span><span class=\"text-xs text-gray-600\">Còn ${s.available} chỗ</span></div>`).join('');
    } catch {
      out.innerHTML = '';
      if (typeof window.showMessage === 'function') window.showMessage('Không thể kiểm tra slot. Vui lòng thử lại.', 'error');
    }
  });
})();
</script>
@endpush


