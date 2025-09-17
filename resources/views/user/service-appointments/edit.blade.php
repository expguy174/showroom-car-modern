@extends('layouts.app')

@section('title', 'Sửa lịch bảo dưỡng')

@section('content')
<div class="min-h-screen bg-gray-50">
  <div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <div class="flex items-center gap-3 mb-2">
          <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
            <i class="fas fa-edit text-indigo-600"></i>
          </div>
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Sửa lịch bảo dưỡng</h1>
            <p class="text-sm text-gray-500">#{{ $appointment->appointment_number }}</p>
          </div>
        </div>
        <div class="flex items-center gap-4 text-sm text-gray-600">
          <span><i class="fas fa-calendar mr-1"></i>{{ \App\Helpers\ServiceAppointmentHelper::formatDate($appointment->appointment_date) }}</span>
          <span><i class="fas fa-clock mr-1"></i>{{ \App\Helpers\ServiceAppointmentHelper::formatTime($appointment->appointment_time) }}</span>
          <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-medium">{{ \App\Helpers\ServiceAppointmentHelper::statusLabel($appointment->status) }}</span>
        </div>
      </div>
      <a href="{{ route('user.service-appointments.show', $appointment->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-white hover:shadow-sm transition-all text-sm font-medium">
        <i class="fas fa-arrow-left"></i> Quay lại
      </a>
    </div>

    <form id="sa-edit-form" method="POST" action="{{ route('user.service-appointments.update', $appointment->id) }}">
      @csrf
      @method('PUT')
      
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
          <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
              
              <!-- 1. Showroom -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Showroom <span class="text-red-500">*</span></label>
                <select name="showroom_id" id="showroom_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                  <option value="">-- Chọn showroom --</option>
                  @foreach($showrooms as $s)
                    <option value="{{ $s->id }}" @selected(old('showroom_id', $appointment->showroom_id)==$s->id)>{{ $s->name }}</option>
                  @endforeach
                </select>
                @error('showroom_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
              </div>

              <!-- 2. Ngày hẹn -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ngày hẹn <span class="text-red-500">*</span></label>
                <input type="date" name="appointment_date" id="appointment_date" value="{{ old('appointment_date', optional($appointment->appointment_date)->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                @error('appointment_date')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
              </div>

              <!-- 3. Giờ hẹn -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Giờ hẹn <span class="text-red-500">*</span></label>
                <input type="time" name="appointment_time" id="appointment_time" value="{{ old('appointment_time', substr((string)$appointment->appointment_time, 0, 5)) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                @error('appointment_time')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
              </div>

              <!-- 4. Xe của bạn -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Xe của bạn <span class="text-red-500">*</span></label>
                <select name="car_variant_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                  <option value="">-- Chọn xe --</option>
                  @foreach($carVariants as $v)
                    <option value="{{ $v->id }}" @selected(old('car_variant_id', $appointment->car_variant_id)==$v->id)>
                      {{ $v->carModel->carBrand->name }} {{ $v->carModel->name }} - {{ $v->name ?? 'Phiên bản' }}
                    </option>
                  @endforeach
                </select>
                @error('car_variant_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
              </div>

              <!-- 5. Biển số xe -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Biển số xe</label>
                <input type="text" name="vehicle_registration" value="{{ old('vehicle_registration', $appointment->vehicle_registration) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" placeholder="VD: 30A-123.45">
                @error('vehicle_registration')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
              </div>

              <!-- 6. Số km hiện tại -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Số km hiện tại</label>
                <input type="number" step="1" name="current_mileage" value="{{ old('current_mileage', $appointment->current_mileage) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" placeholder="VD: 25000">
                @error('current_mileage')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
              </div>

              <!-- 7. Chọn dịch vụ -->
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Chọn dịch vụ <span class="text-red-500">*</span></label>
                <select name="service_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                  <option value="">-- Chọn dịch vụ --</option>
                  @foreach($services as $service)
                    <option value="{{ $service->id }}" 
                            @selected(old('service_id', $appointment->service_id) == $service->id)
                            data-price="{{ $service->price }}" data-duration="{{ $service->duration_minutes }}">
                      {{ $service->name }} 
                      @if($service->price > 0)
                        - {{ number_format($service->price, 0, ',', '.') }} VNĐ
                      @else
                        - Miễn phí
                      @endif
                    </option>
                  @endforeach
                </select>
                @error('service_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
              </div>

              <!-- 8. Chi phí ước tính -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Chi phí ước tính</label>
                <input type="number" step="1000" name="estimated_cost" value="{{ old('estimated_cost', $appointment->estimated_cost ? number_format($appointment->estimated_cost, 0, '', '') : '') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" placeholder="0" readonly>
                @error('estimated_cost')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                <div class="text-xs text-gray-500 mt-1">Tự động điền</div>
              </div>


              <!-- 9. Bảo hành checkbox -->
              <div class="flex items-center">
                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                  <input type="checkbox" name="is_warranty_work" value="1" class="rounded border-gray-300" @checked(old('is_warranty_work', $appointment->is_warranty_work))>
                  Là công việc bảo hành
                </label>
              </div>

              <!-- 10. Yêu cầu thêm -->
              <div class="md:col-span-2 lg:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">Yêu cầu thêm (tùy chọn)</label>
                <textarea name="requested_services" rows="3" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" placeholder="VD: Kiểm tra thêm lốp xe, thay bóng đèn...">{{ old('requested_services', $appointment->requested_services) }}</textarea>
                @error('requested_services')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
              </div>

              <!-- 11. Mô tả chi tiết -->
              <div class="md:col-span-2 lg:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả chi tiết</label>
                <textarea name="service_description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" placeholder="Thông tin thêm giúp kỹ thuật viên chuẩn bị tốt hơn (tùy chọn)">{{ old('service_description', $appointment->service_description) }}</textarea>
                @error('service_description')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
              </div>

              <!-- 12. Submit Button -->
              <div class="md:col-span-2 lg:col-span-3">
                <button type="submit" class="w-full bg-indigo-600 text-white py-3 px-6 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold text-lg transition-colors">
                  <i class="fas fa-save mr-2"></i> Lưu thay đổi
                </button>
              </div>

            </div>
          </div>
        </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <!-- Kiểm tra lịch trống -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
              <i class="fas fa-calendar-check text-green-600 text-sm"></i>
            </div>
            <h3 class="font-semibold text-gray-900">
              Kiểm tra lịch trống
            </h3>
          </div>
          <p class="text-sm text-gray-600 mb-4">Chọn showroom và ngày, sau đó bấm kiểm tra để xem slot còn trống.</p>
          <button type="button" id="btn-check-slots" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium transition-colors">
            <i class="fas fa-search"></i> 
            Kiểm tra slot trống
          </button>
          <div id="slot-results" class="mt-4 space-y-2"></div>
        </div>

        <!-- Lưu ý quan trọng -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
              <i class="fas fa-exclamation-triangle text-amber-600 text-sm"></i>
            </div>
            <h3 class="font-semibold text-gray-900">
              Lưu ý quan trọng
            </h3>
          </div>
          <div class="space-y-3 text-sm text-gray-600">
            <div class="flex items-start gap-2">
              <i class="fas fa-check-circle text-green-500 mt-0.5 text-xs"></i>
              <span>Chỉ có thể sửa lịch khi đang ở trạng thái "Đã lên lịch"</span>
            </div>
            <div class="flex items-start gap-2">
              <i class="fas fa-check-circle text-green-500 mt-0.5 text-xs"></i>
              <span>Vui lòng đến đúng giờ hẹn để tránh chờ đợi</span>
            </div>
            <div class="flex items-start gap-2">
              <i class="fas fa-check-circle text-green-500 mt-0.5 text-xs"></i>
              <span>Mang theo giấy tờ xe và CMND khi đến showroom</span>
            </div>
            <div class="flex items-start gap-2">
              <i class="fas fa-check-circle text-green-500 mt-0.5 text-xs"></i>
              <span>Liên hệ hotline nếu cần hỗ trợ khẩn cấp</span>
            </div>
          </div>
        </div>

        <!-- Hỗ trợ khách hàng -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
              <i class="fas fa-headset text-blue-600 text-sm"></i>
            </div>
            <h3 class="font-semibold text-gray-900">
              Hỗ trợ khách hàng
            </h3>
          </div>
          <div class="space-y-3">
            <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg">
              <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-phone text-green-600 text-xs"></i>
              </div>
              <div>
                <div class="font-medium text-gray-900">Hotline</div>
                <div class="text-blue-600">1900 1008</div>
              </div>
            </div>
            
            <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg">
              <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-envelope text-blue-600 text-xs"></i>
              </div>
              <div>
                <div class="font-medium text-gray-900">Email</div>
                <div class="text-blue-600">support@autolux.vn</div>
              </div>
            </div>
            
            <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg">
              <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                <i class="fas fa-clock text-orange-600 text-xs"></i>
              </div>
              <div>
                <div class="font-medium text-gray-900">Giờ làm việc</div>
                <div class="text-gray-600">09:00 - 17:00 (T2-T6)</div>
              </div>
            </div>
          </div>
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
        ['appointment_date','Vui lòng chọn ngày hẹn.'],
        ['appointment_time','Vui lòng chọn giờ hẹn.'],
        ['car_variant_id','Vui lòng chọn xe.'],
        ['service_id','Vui lòng chọn dịch vụ.']
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

// Slot checker functionality
(function(){
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
      
      // Handle validation errors
      if (!res.ok) {
        out.innerHTML = '';
        const errorMessage = data.message || 'Có lỗi xảy ra khi kiểm tra slot.';
        if (typeof window.showMessage === 'function') window.showMessage(errorMessage, 'error');
        return;
      }
      
      const slots = Array.isArray(data.available_slots) ? data.available_slots : [];
      if (!slots.length){
        out.innerHTML = '';
        if (typeof window.showMessage === 'function') window.showMessage('Không có thông tin cho ngày này.', 'info');
        return;
      }
      
      // Display appointment summary
      const slot = slots[0];
      let html = `<div class="space-y-3">`;
      html += `<div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">`;
      html += `<div class="font-medium text-blue-900 mb-2"><i class="fas fa-info-circle mr-2"></i>${slot.info}</div>`;
      html += `<div class="text-sm text-blue-700">Tổng lịch hẹn: ${slot.total_appointments}</div>`;
      html += `</div>`;
      
      if (slot.existing_times && slot.existing_times.length > 0) {
        html += `<div class="p-3 bg-amber-50 border border-amber-200 rounded-lg">`;
        html += `<div class="font-medium text-amber-900 mb-2"><i class="fas fa-clock mr-2"></i>Thời gian đã được đặt:</div>`;
        html += `<div class="flex flex-wrap gap-2">`;
        slot.existing_times.forEach(time => {
          html += `<span class="px-2 py-1 bg-amber-100 text-amber-800 rounded text-xs">${time}</span>`;
        });
        html += `</div>`;
        html += `<div class="text-xs text-amber-700 mt-2">Bạn có thể chọn thời gian khác trong giờ làm việc</div>`;
        html += `</div>`;
      } else {
        html += `<div class="p-3 bg-green-50 border border-green-200 rounded-lg">`;
        html += `<div class="text-sm text-green-700"><i class="fas fa-check-circle mr-2"></i>Chưa có lịch hẹn nào. Bạn có thể chọn bất kỳ thời gian nào trong giờ làm việc.</div>`;
        html += `</div>`;
      }
      
      html += `</div>`;
      out.innerHTML = html;
    } catch {
      out.innerHTML = '';
      if (typeof window.showMessage === 'function') window.showMessage('Không thể kiểm tra slot. Vui lòng thử lại.', 'error');
    }
  });
})();

// Auto-fill estimated cost when service is selected
(function(){
  const serviceSelect = document.querySelector('select[name="service_id"]');
  const costInput = document.querySelector('input[name="estimated_cost"]');
  
  if (!serviceSelect || !costInput) return;
  
  function updateEstimatedCost() {
    const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
    if (selectedOption && selectedOption.value) {
      const price = selectedOption.getAttribute('data-price');
      if (price && price !== '0') {
        // Format price without decimals
        const formattedPrice = Math.round(parseFloat(price));
        costInput.value = formattedPrice;
      } else {
        costInput.value = '0';
      }
    } else {
      costInput.value = '';
    }
  }
  
  // Update on change
  serviceSelect.addEventListener('change', updateEstimatedCost);
  
  // Update on page load if service is already selected
  if (serviceSelect.value) {
    updateEstimatedCost();
  }
})();
</script>
@endpush


