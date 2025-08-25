@extends('layouts.app')
@section('title', 'Đặt lịch lái thử - AutoLux')
@section('content')

<section class="relative overflow-hidden">
  <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-white to-purple-50"></div>
  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
    <!-- Hero -->
    <div class="text-center mb-10 lg:mb-12">
      <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-gray-900">Đặt lịch lái thử</h1>
      <p class="mt-3 text-gray-600 text-base sm:text-lg max-w-2xl mx-auto">Trải nghiệm thực tế mẫu xe bạn quan tâm cùng chuyên viên tư vấn của AutoLux.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
      <!-- Booking Card -->
      <div class="lg:col-span-7">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-5 sm:p-6 lg:p-8">
          <div class="flex items-center justify-between mb-5">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Thông tin đặt lịch</h2>
            <span class="text-xs sm:text-sm text-gray-500">Chỉ mất ~1 phút</span>
          </div>
          <form id="testDriveForm" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <input type="hidden" name="car_variant_id" id="car_variant_id" value="{{ request('car_variant_id') }}">
            <div class="sm:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-1">Bạn đang quan tâm mẫu xe nào?</label>
              <select class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" onchange="document.getElementById('car_variant_id').value=this.value">
                <option value="" {{ empty(request('car_variant_id')) ? 'selected' : '' }}>— Chọn mẫu xe —</option>
                @foreach(($variants ?? collect()) as $v)
                  @php 
                    $brand = optional(optional($v->carModel)->carBrand)->name; 
                    $model = optional($v->carModel)->name; 
                    $selected = (string) $v->id === (string) request('car_variant_id');
                  @endphp
                  <option value="{{ $v->id }}" {{ $selected ? 'selected' : '' }}>{{ trim(($brand ? $brand.' • ' : '').($model ? $model.' • ' : '').($v->name ?? '')) }}</option>
                @endforeach
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên</label>
              <input type="text" name="name" required autocomplete="name" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ auth()->user()->name ?? '' }}">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
              <input type="tel" name="phone" required autocomplete="tel" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ auth()->user()->phone ?? '' }}">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
              <input type="email" name="email" autocomplete="email" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ auth()->user()->email ?? '' }}">
            </div>
            <div class="grid grid-cols-2 gap-3 sm:col-span-2">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ngày mong muốn</label>
                <input type="date" name="preferred_date" id="preferred_date" required class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Giờ mong muốn</label>
                <input type="time" name="preferred_time" required class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
              </div>
            </div>

            <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Số GPLX (tuỳ chọn)</label>
                <input type="text" name="driver_license" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="VD: 0792xxxxxxx">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">CMND/CCCD (tuỳ chọn)</label>
                <input type="text" name="id_card" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="VD: 0792xxxxxxx">
              </div>
            </div>

            <div class="sm:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
              <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Yêu cầu về địa điểm, khung giờ linh hoạt..."></textarea>
            </div>

            <div class="sm:col-span-2">
              <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-3 rounded-xl transition">
                <i class="fas fa-steering-wheel"></i> Xác nhận đặt lịch lái thử
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Side Info -->
      <div class="lg:col-span-5 space-y-6">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
          <h3 class="text-lg font-bold text-gray-900 mb-4">Vì sao nên lái thử tại AutoLux?</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center"><i class="fas fa-user-check"></i></div>
              <div><div class="font-semibold">Tư vấn cá nhân hoá</div><div class="text-sm text-gray-600">Chuyên viên giàu kinh nghiệm đồng hành suốt buổi lái thử.</div></div>
            </div>
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center"><i class="fas fa-shield-alt"></i></div>
              <div><div class="font-semibold">An toàn & bảo hiểm</div><div class="text-sm text-gray-600">Xe luôn được kiểm tra kỹ thuật và có bảo hiểm.</div></div>
            </div>
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center"><i class="fas fa-clock"></i></div>
              <div><div class="font-semibold">Linh hoạt thời gian</div><div class="text-sm text-gray-600">Chọn khung giờ thuận tiện nhất cho bạn.</div></div>
            </div>
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center"><i class="fas fa-dollar-sign"></i></div>
              <div><div class="font-semibold">Hoàn toàn miễn phí</div><div class="text-sm text-gray-600">Không phát sinh chi phí đặt lịch.</div></div>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
          <h3 class="text-lg font-bold text-gray-900 mb-4">Lịch đã đặt của bạn</h3>
          @if(($testDrives->count() ?? 0) === 0)
            <div class="text-sm text-gray-600">Bạn chưa có lịch lái thử nào.</div>
          @else
            <div id="bookings-list" class="space-y-3">
            @foreach($testDrives as $td)
              <div class="p-4 border rounded-xl flex items-start gap-4">
                <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center"><i class="fas fa-car"></i></div>
                <div class="flex-1 min-w-0">
                  <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('car-variants.show', $td->car_variant_id) }}" class="font-semibold text-gray-900 hover:text-indigo-700 truncate">#{{ $td->test_drive_number ?? $td->id }} • {{ optional(optional($td->carVariant)->carModel)->name }} {{ $td->carVariant->name ?? '' }}</a>
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $td->status_badge }}">{{ $td->status_text }}</span>
                  </div>
                  <div class="text-sm text-gray-600 mt-1">Ngày {{ optional($td->preferred_date)->format('d/m/Y') }} • Giờ {{ 
                  is_string($td->preferred_time) ? $td->preferred_time : optional($td->preferred_time)->format('H:i') }}</div>
                </div>
              </div>
            @endforeach
            </div>
            <div class="mt-3">{{ $testDrives->links() }}</div>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function(){
  // Set min date = tomorrow
  const dateEl = document.getElementById('preferred_date');
  if (dateEl){
    const t = new Date(); t.setDate(t.getDate()+1);
    const yyyy = t.getFullYear(); const mm = String(t.getMonth()+1).padStart(2,'0'); const dd = String(t.getDate()).padStart(2,'0');
    dateEl.min = `${yyyy}-${mm}-${dd}`;
  }

  const form = document.getElementById('testDriveForm');
  if (!form) return;
  form.addEventListener('submit', async function(e){
    e.preventDefault();
    const fd = new FormData(form);
    const carId = (fd.get('car_variant_id')||'').toString().trim();
    if (!carId){ if (typeof showMessage==='function') showMessage('Vui lòng chọn phiên bản xe (Car Variant)', 'warning'); return; }
    const btn = form.querySelector('button[type="submit"]');
    const old = btn ? btn.innerHTML : '';
    if (btn){ btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...'; }
    try {
      const res = await fetch(`{{ route('test-drives.book') }}`, {
        method: 'POST',
        headers: { 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: fd
      });
      const ct = res.headers.get('content-type')||'';
      if (!ct.includes('application/json')){ window.location.href = `{{ route('login') }}`; return; }
      const data = await res.json();
      if (res.ok && data && data.success){
        if (typeof showMessage==='function') showMessage(data.message || 'Đặt lịch lái thử thành công!', 'success');
        form.reset();
        // Append new booking (optimistic)
        const list = document.getElementById('bookings-list');
        if (list){
          const now = new Date(fd.get('preferred_date')+ 'T' + (fd.get('preferred_time')||'00:00'));
          const d = String(now.getDate()).padStart(2,'0') + '/' + String(now.getMonth()+1).padStart(2,'0') + '/' + now.getFullYear();
          const t = (fd.get('preferred_time')||'').toString();
          const num = (data.test_drive && (data.test_drive.test_drive_number || data.test_drive.id)) ? (data.test_drive.test_drive_number || ('#'+data.test_drive.id)) : '#Mới';
          const item = document.createElement('div');
          item.className = 'p-4 border rounded-xl flex items-start gap-4';
          item.innerHTML = '<div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center"><i class="fas fa-car"></i></div>'+
            '<div class="flex-1 min-w-0">'+
            `<div class="flex flex-wrap items-center gap-2"><span class="font-semibold text-gray-900">${num}</span>`+
            '<span class="px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-800">Chờ xác nhận</span></div>'+
            `<div class="text-sm text-gray-600 mt-1">Ngày ${d} • Giờ ${t || '—'}</div>`+
            '</div>';
          list.prepend(item);
        }
      } else {
        const msg = (data && data.message) ? data.message : 'Không thể đặt lịch. Vui lòng kiểm tra thông tin.';
        if (typeof showMessage==='function') showMessage(msg, 'error');
      }
    } catch (e) {
      if (typeof showMessage==='function') showMessage('Không thể kết nối máy chủ. Vui lòng thử lại.', 'error');
    } finally {
      if (btn){ btn.disabled = false; btn.innerHTML = old; }
    }
  });
});
</script>

@endsection