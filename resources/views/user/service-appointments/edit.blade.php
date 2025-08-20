@extends('layouts.app')

@section('title', 'Sửa lịch bảo dưỡng')

@section('content')
<div class="bg-gray-50 min-h-screen">
  <div class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Sửa lịch bảo dưỡng</h1>
        <p class="text-sm text-gray-600 mt-1">Mã lịch: {{ $appointment->appointment_number }}</p>
      </div>
      <a href="{{ route('user.service-appointments.show', $appointment->id) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border hover:bg-gray-50 text-sm"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <form id="sa-edit-form" method="POST" action="{{ route('user.service-appointments.update', $appointment->id) }}" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      @csrf
      @method('PUT')

      <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border p-6">
          <h2 class="font-semibold text-gray-900 mb-4">Thông tin lịch hẹn</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm text-gray-700 mb-1">Showroom</label>
              <select name="showroom_id" class="w-full rounded-lg border-gray-300" required>
                @foreach(\App\Models\Showroom::where('is_active', true)->get() as $s)
                  <option value="{{ $s->id }}" @selected(old('showroom_id', $appointment->showroom_id)==$s->id)>{{ $s->name }}</option>
                @endforeach
              </select>
              @error('showroom_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm text-gray-700 mb-1">Xe</label>
              <select name="car_variant_id" class="w-full rounded-lg border-gray-300" required>
                @foreach(\App\Models\CarVariant::with(['carModel.carBrand'])->get() as $v)
                  <option value="{{ $v->id }}" @selected(old('car_variant_id', $appointment->car_variant_id)==$v->id)>{{ $v->carModel->carBrand->name }} {{ $v->carModel->name }} - {{ $v->name ?? 'Phiên bản' }}</option>
                @endforeach
              </select>
              @error('car_variant_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm text-gray-700 mb-1">Loại hẹn</label>
              @php $types=['maintenance'=>'Bảo dưỡng','repair'=>'Sửa chữa','inspection'=>'Kiểm tra','warranty_work'=>'Bảo hành','recall_service'=>'Triệu hồi','emergency'=>'Khẩn cấp','other'=>'Khác']; @endphp
              <select name="appointment_type" class="w-full rounded-lg border-gray-300" required>
                @foreach($types as $val=>$label)
                  <option value="{{ $val }}" @selected(old('appointment_type', $appointment->appointment_type)==$val)>{{ $label }}</option>
                @endforeach
              </select>
              @error('appointment_type')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm text-gray-700 mb-1">Ngày hẹn</label>
              <input type="date" name="appointment_date" value="{{ old('appointment_date', optional($appointment->appointment_date)->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300" required>
              @error('appointment_date')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm text-gray-700 mb-1">Giờ hẹn (HH:MM)</label>
              <input type="time" name="appointment_time" value="{{ old('appointment_time', $appointment->appointment_time) }}" class="w-full rounded-lg border-gray-300" required>
              @error('appointment_time')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">
          <h2 class="font-semibold text-gray-900 mb-4">Thông tin khách hàng & xe</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm text-gray-700 mb-1">Họ tên</label>
              <input type="text" name="customer_name" value="{{ old('customer_name', $appointment->customer_name) }}" class="w-full rounded-lg border-gray-300" required>
              @error('customer_name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm text-gray-700 mb-1">Số điện thoại</label>
              <input type="text" name="customer_phone" value="{{ old('customer_phone', $appointment->customer_phone) }}" class="w-full rounded-lg border-gray-300" required>
              @error('customer_phone')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm text-gray-700 mb-1">Email</label>
              <input type="email" name="customer_email" value="{{ old('customer_email', $appointment->customer_email) }}" class="w-full rounded-lg border-gray-300" required>
              @error('customer_email')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm text-gray-700 mb-1">Biển số</label>
              <input type="text" name="vehicle_registration" value="{{ old('vehicle_registration', $appointment->vehicle_registration) }}" class="w-full rounded-lg border-gray-300">
              @error('vehicle_registration')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm text-gray-700 mb-1">Số km hiện tại</label>
              <input type="number" name="current_mileage" value="{{ old('current_mileage', $appointment->current_mileage) }}" class="w-full rounded-lg border-gray-300">
              @error('current_mileage')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">
          <h2 class="font-semibold text-gray-900 mb-4">Nội dung yêu cầu</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
              <label class="block text-sm text-gray-700 mb-1">Mô tả dịch vụ (bắt buộc)</label>
              <textarea name="service_description" rows="3" class="w-full rounded-lg border-gray-300" required>{{ old('service_description', $appointment->service_description) }}</textarea>
              @error('service_description')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm text-gray-700 mb-1">Chỉ dẫn đặc biệt</label>
              <textarea name="special_instructions" rows="2" class="w-full rounded-lg border-gray-300">{{ old('special_instructions', $appointment->special_instructions) }}</textarea>
            </div>
            <div>
              <label class="block text-sm text-gray-700 mb-1">Ưu tiên</label>
              @php $priorities=['low'=>'Thấp','medium'=>'Trung bình','high'=>'Cao','urgent'=>'Khẩn']; @endphp
              <select name="priority" class="w-full rounded-lg border-gray-300" required>
                @foreach($priorities as $val=>$label)
                  <option value="{{ $val }}" @selected(old('priority', $appointment->priority)==$val)>{{ $label }}</option>
                @endforeach
              </select>
              @error('priority')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>
      </div>

      <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border p-6">
          <h2 class="font-semibold text-gray-900 mb-4">Thanh toán (tuỳ chọn)</h2>
          <div class="grid grid-cols-1 gap-4">
            <div>
              <label class="block text-sm text-gray-700 mb-1">Chi phí ước tính</label>
              <input type="number" step="0.01" name="estimated_cost" value="{{ old('estimated_cost', $appointment->estimated_cost) }}" class="w-full rounded-lg border-gray-300">
            </div>
            <div>
              <label class="block text-sm text-gray-700 mb-1">Phương thức thanh toán</label>
              @php $pms=['cash'=>'Tiền mặt','card'=>'Thẻ','bank_transfer'=>'Chuyển khoản','installment'=>'Trả góp']; @endphp
              <select name="payment_method" class="w-full rounded-lg border-gray-300">
                <option value="">-- Chọn --</option>
                @foreach($pms as $val=>$label)
                  <option value="{{ $val }}" @selected(old('payment_method', $appointment->payment_method)==$val)>{{ $label }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">
          <h2 class="font-semibold text-gray-900 mb-4">Trạng thái</h2>
          @php $statuses=['scheduled'=>'Đã lên lịch','confirmed'=>'Đã xác nhận','in_progress'=>'Đang thực hiện','completed'=>'Hoàn thành','cancelled'=>'Đã hủy']; @endphp
          <select name="status" class="w-full rounded-lg border-gray-300" required>
            @foreach($statuses as $val=>$label)
              <option value="{{ $val }}" @selected(old('status', $appointment->status)==$val)>{{ $label }}</option>
            @endforeach
          </select>
          @error('status')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">
          <button class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700"><i class="fas fa-save"></i> Lưu thay đổi</button>
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
    const btn = form.querySelector('button[type="submit"]');
    if (btn){ btn.disabled = true; btn.classList.add('opacity-60'); }
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
          Object.keys(data.errors).forEach(function(name){
            const input = form.querySelector(`[name="${name}"]`);
            const first = Array.isArray(data.errors[name]) ? data.errors[name][0] : data.errors[name];
            if (input && first){
              let hint = input.parentElement.querySelector('.text-red-600');
              if (!hint){ hint = document.createElement('div'); hint.className = 'text-sm text-red-600 mt-1'; input.parentElement.appendChild(hint); }
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
})();
</script>
@endpush


