@extends('layouts.app')

@section('title', 'Đặt lịch bảo dưỡng')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Đặt lịch bảo dưỡng</h1>
                    <p class="text-sm text-gray-600 mt-1">Chọn showroom, xe và thời gian phù hợp</p>
                </div>
                <a href="{{ route('user.service-appointments.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border hover:bg-gray-50 text-sm">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form id="sa-create-form" method="POST" action="{{ route('user.service-appointments.store') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="font-semibold text-gray-900 mb-4">Thông tin lịch hẹn</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Showroom</label>
                            <select name="showroom_id" id="showroom_id" class="w-full rounded-lg border-gray-300" required>
                                <option value="">-- Chọn showroom --</option>
                                @foreach($showrooms as $s)
                                    <option value="{{ $s->id }}" @selected(old('showroom_id')==$s->id)>{{ $s->name }}</option>
                                @endforeach
                            </select>
                            @error('showroom_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Xe</label>
                            <select name="car_variant_id" id="car_variant_id" class="w-full rounded-lg border-gray-300" required>
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
                            <label class="block text-sm text-gray-700 mb-1">Loại hẹn</label>
                            <select name="appointment_type" class="w-full rounded-lg border-gray-300" required>
                                @php $types=['maintenance'=>'Bảo dưỡng','repair'=>'Sửa chữa','inspection'=>'Kiểm tra','warranty_work'=>'Bảo hành','recall_service'=>'Triệu hồi','emergency'=>'Khẩn cấp','other'=>'Khác']; @endphp
                                @foreach($types as $val=>$label)
                                    <option value="{{ $val }}" @selected(old('appointment_type')==$val)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('appointment_type')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Ngày hẹn</label>
                            <input type="date" name="appointment_date" id="appointment_date" value="{{ old('appointment_date') }}" class="w-full rounded-lg border-gray-300" required>
                            @error('appointment_date')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Giờ hẹn (HH:MM)</label>
                            <input type="time" name="appointment_time" id="appointment_time" value="{{ old('appointment_time') }}" class="w-full rounded-lg border-gray-300" required>
                            @error('appointment_time')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="font-semibold text-gray-900 mb-4">Thông tin khách hàng & xe</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Họ tên</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" class="w-full rounded-lg border-gray-300" required>
                            @error('customer_name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Số điện thoại</label>
                            <input type="text" name="customer_phone" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" class="w-full rounded-lg border-gray-300" required>
                            @error('customer_phone')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Email</label>
                            <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}" class="w-full rounded-lg border-gray-300" required>
                            @error('customer_email')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Biển số</label>
                            <input type="text" name="vehicle_registration" value="{{ old('vehicle_registration') }}" class="w-full rounded-lg border-gray-300">
                            @error('vehicle_registration')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">VIN</label>
                            <input type="text" name="vehicle_vin" value="{{ old('vehicle_vin') }}" class="w-full rounded-lg border-gray-300">
                            @error('vehicle_vin')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Năm sản xuất</label>
                            <input type="number" name="vehicle_year" value="{{ old('vehicle_year') }}" class="w-full rounded-lg border-gray-300">
                            @error('vehicle_year')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Số km hiện tại</label>
                            <input type="number" step="1" name="current_mileage" value="{{ old('current_mileage') }}" class="w-full rounded-lg border-gray-300">
                            @error('current_mileage')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="font-semibold text-gray-900 mb-4">Nội dung yêu cầu</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm text-gray-700 mb-1">Dịch vụ yêu cầu (bắt buộc)</label>
                            <textarea name="requested_services" rows="3" class="w-full rounded-lg border-gray-300" required>{{ old('requested_services') }}</textarea>
                            @error('requested_services')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm text-gray-700 mb-1">Mô tả chi tiết</label>
                            <textarea name="service_description" rows="3" class="w-full rounded-lg border-gray-300">{{ old('service_description') }}</textarea>
                            @error('service_description')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Phàn nàn của khách</label>
                            <textarea name="customer_complaints" rows="2" class="w-full rounded-lg border-gray-300">{{ old('customer_complaints') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Chỉ dẫn đặc biệt</label>
                            <textarea name="special_instructions" rows="2" class="w-full rounded-lg border-gray-300">{{ old('special_instructions') }}</textarea>
                        </div>
                        <div>
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="is_warranty_work" value="1" class="rounded border-gray-300" @checked(old('is_warranty_work'))>
                                Là công việc bảo hành
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="font-semibold text-gray-900 mb-4">Kiểm tra lịch trống</h2>
                    <p class="text-sm text-gray-600 mb-3">Chọn showroom và ngày, sau đó bấm kiểm tra để xem slot còn trống.</p>
                    <button type="button" id="btn-check-slots" class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg border hover:bg-gray-50">
                        <i class="fas fa-calendar-check"></i> Kiểm tra slot trống
                    </button>
                    <div id="slot-results" class="mt-4 space-y-2 text-sm"></div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="font-semibold text-gray-900 mb-4">Xác nhận</h2>
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                        <i class="fas fa-save"></i> Đặt lịch
                    </button>
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
    form.addEventListener('submit', async function(e){
      e.preventDefault();
      const btn = form.querySelector('button[type="submit"]');
      if (btn){ btn.disabled = true; btn.classList.add('opacity-60'); }
      try {
        const fd = new FormData(form);
        const res = await fetch(form.getAttribute('action'), { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept':'application/json' }, body: fd });
        const data = await res.json().catch(()=>({}));
        if (res.ok && data && data.success){
          if (typeof window.showMessage === 'function') window.showMessage(data.message || 'Đặt lịch bảo dưỡng thành công!', 'success');
          if (data.redirect){ window.location.href = data.redirect; }
          else if (data.id){ window.location.href = `{{ url('service-appointments') }}/${data.id}`; }
        } else {
          const msg = (data && data.message) || 'Dữ liệu chưa hợp lệ hoặc có lỗi xảy ra.';
          if (typeof window.showMessage === 'function') window.showMessage(msg, 'error');
          // Hiển thị lỗi dưới các trường
          if (data && data.errors){
            Object.keys(data.errors).forEach(function(name){
              const input = form.querySelector(`[name="${name}"]`);
              const first = Array.isArray(data.errors[name]) ? data.errors[name][0] : data.errors[name];
              if (input && first){
                let hint = input.parentElement.querySelector('.text-red-600');
                if (!hint){
                  hint = document.createElement('div');
                  hint.className = 'text-sm text-red-600 mt-1';
                  input.parentElement.appendChild(hint);
                }
                hint.textContent = String(first);
              }
            });
          }
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
      out.innerHTML = '<div class="text-red-600">Vui lòng chọn showroom và ngày.</div>';
      return;
    }
    out.innerHTML = '<div class="text-gray-600"><i class="fas fa-spinner fa-spin"></i> Đang kiểm tra...</div>';
    try {
      const res = await fetch("{{ route('user.service-appointments.check-availability') }}", { method:'POST', headers:{ 'Content-Type':'application/json', 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify({ showroom_id: showroom, date }) });
      const data = await res.json();
      const slots = Array.isArray(data.available_slots) ? data.available_slots : [];
      if (!slots.length){ out.innerHTML = '<div class="text-gray-600">Không còn slot trống cho ngày này.</div>'; return; }
      out.innerHTML = slots.map(s=>`<div class="flex items-center justify-between px-3 py-2 rounded border ${s.status==='available'?'border-emerald-200 bg-emerald-50':'border-amber-200 bg-amber-50'}"><span><i class="fas fa-clock mr-2"></i>${s.time}</span><span class="text-xs text-gray-600">Còn ${s.available} chỗ</span></div>`).join('');
    } catch { out.innerHTML = '<div class="text-red-600">Không thể kiểm tra slot. Vui lòng thử lại.</div>'; }
  });
})();
</script>
@endpush


