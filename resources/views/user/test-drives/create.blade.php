@extends('layouts.app')
@section('title', 'Đặt lịch lái thử')
@section('content')

<div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
	<div class="flex items-start sm:items-center justify-between gap-3 mb-4 sm:mb-6">
		<div class="min-w-0">
			<div class="flex items-center gap-2 flex-wrap">
				<h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight truncate">Đặt lịch lái thử</h1>
			</div>
			<div class="text-xs sm:text-sm text-gray-500 mt-1">Chọn mẫu xe, showroom và thời gian phù hợp với bạn</div>
		</div>
		<a href="{{ route('test-drives.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold shrink-0"><i class="fas fa-arrow-left"></i> Quay lại</a>
	</div>

	<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
		<!-- Main Form -->
		<div class="lg:col-span-2">
			<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
				<form method="POST" action="{{ route('test-drives.book') }}" id="testdrive-create-form" novalidate>
					@csrf
					<h2 class="text-xl font-bold text-gray-900 mb-6">Thông tin đặt lịch</h2>
					
					<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-1">Mẫu xe</label>
					<select name="car_variant_id" class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
						<option value="">— Chọn mẫu xe —</option>
						@foreach(($variants ?? collect()) as $v)
							<option value="{{ $v->id }}">{{ trim(((optional(optional($v->carModel)->carBrand)->name) ? optional(optional($v->carModel)->carBrand)->name.' • ' : '') . ((optional($v->carModel)->name) ? optional($v->carModel)->name.' • ' : '') . ($v->name ?? '')) }}</option>
						@endforeach
					</select>
				</div>
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-1">Showroom</label>
					<select name="showroom_id" id="showroom_id" class="w-full border rounded-xl px-3 py-2">
						<option value="">— Chọn showroom —</option>
						@foreach(($showrooms ?? collect()) as $s)
							<option value="{{ $s->id }}">{{ $s->name }}</option>
						@endforeach
					</select>
				</div>
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-1">Ngày mong muốn</label>
					<input type="date" name="preferred_date" id="preferred_date" required class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
				</div>
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-1">Giờ mong muốn</label>
					<input type="time" name="preferred_time" required class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
				</div>
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-1">Thời lượng (phút)</label>
					<input type="number" name="duration_minutes" min="5" class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="VD: 30">
				</div>
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-1">Địa điểm</label>
					<input type="text" name="location" class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="VD: Showroom Quận 1 hoặc địa chỉ khác">
				</div>
				<div class="sm:col-span-2">
					<label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
					<textarea name="notes" rows="3" class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Yêu cầu về địa điểm, khung giờ linh hoạt..."></textarea>
				</div>
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-1">Loại lịch</label>
					<select name="test_drive_type" class="w-full border rounded-xl px-3 py-2">
						<option value="individual">Cá nhân</option>
						<option value="group">Nhóm</option>
						<option value="virtual">Trực tuyến</option>
					</select>
				</div>
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-1">Kinh nghiệm</label>
					<select name="experience_level" class="w-full border rounded-xl px-3 py-2">
						<option value="">— Chọn —</option>
						<option value="beginner">Mới bắt đầu</option>
						<option value="intermediate">Trung bình</option>
						<option value="advanced">Nhiều kinh nghiệm</option>
					</select>
				</div>
				<div class="sm:col-span-2">
					<label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="has_experience" value="1" class="rounded"> Đã từng lái xe tương tự</label>
				</div>
				<div class="sm:col-span-2">
					<label class="block text-sm font-medium text-gray-700 mb-1">Yêu cầu đặc biệt</label>
					<textarea name="special_requirements" rows="3" class="w-full border rounded-xl px-3 py-2" placeholder="Ví dụ: đường thử cụ thể, nhân viên hỗ trợ,..."></textarea>
				</div>
				<div class="sm:col-span-2">
					<button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-3 rounded-xl transition">
						<i class="fas fa-check"></i> Xác nhận đặt lịch
					</button>
				</div>
					</div>
				</form>
			</div>
		</div>
		<div class="space-y-6">
			<!-- Slot Checker -->
			<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
				<h3 class="text-lg font-semibold text-gray-900 mb-3">
					<i class="fas fa-calendar-check text-indigo-600 mr-2"></i>
					Kiểm tra lịch trống
				</h3>
				<p class="text-sm text-gray-600 mb-4">Chọn showroom và ngày, sau đó bấm kiểm tra để xem slot còn trống.</p>
				<button type="button" id="btn-check-slots" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium transition-colors">
					<i class="fas fa-search"></i> 
					Kiểm tra slot trống
				</button>
				<div id="slot-results" class="mt-4 space-y-2 text-sm"></div>
			</div>

			<!-- Notes -->
			<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
				<h3 class="text-lg font-semibold text-gray-900 mb-3">
					<i class="fas fa-info-circle text-blue-600 mr-2"></i>
					Lưu ý quan trọng
				</h3>
				<ul class="space-y-2 text-sm text-gray-700">
					<li class="flex items-start gap-2">
						<i class="fas fa-id-card text-gray-400 mt-0.5 text-xs"></i>
						Vui lòng mang theo GPLX hợp lệ khi tới lái thử.
					</li>
					<li class="flex items-start gap-2">
						<i class="fas fa-phone text-gray-400 mt-0.5 text-xs"></i>
						Chúng tôi sẽ gọi xác nhận trước giờ hẹn.
					</li>
					<li class="flex items-start gap-2">
						<i class="fas fa-calendar-alt text-gray-400 mt-0.5 text-xs"></i>
						Bạn có thể thay đổi thời gian sau khi đặt nếu cần.
					</li>
					<li class="flex items-start gap-2">
						<i class="fas fa-clock text-gray-400 mt-0.5 text-xs"></i>
						Giờ làm việc: 09:00 - 16:59 (T2-T6).
					</li>
				</ul>
			</div>

			<!-- Contact Support -->
			<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
				<h3 class="text-lg font-semibold text-gray-900 mb-3">
					<i class="fas fa-headset text-green-600 mr-2"></i>
					Hỗ trợ khách hàng
				</h3>
				<div class="space-y-3 text-sm">
					<div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg">
						<div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
							<i class="fas fa-phone text-green-600 text-xs"></i>
						</div>
						<div>
							<div class="font-medium text-gray-900">Hotline 24/7</div>
							<div class="text-green-600 font-semibold">1900 1234</div>
						</div>
					</div>
					
					<div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg">
						<div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
							<i class="fas fa-envelope text-blue-600 text-xs"></i>
						</div>
						<div>
							<div class="font-medium text-gray-900">Email hỗ trợ</div>
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
</div>

<script>
(function(){
	const form = document.getElementById('testdrive-create-form');
	if (!form) return;
	const fieldLabels = {
		car_variant_id: 'Mẫu xe',
		showroom_id: 'Showroom',
		preferred_date: 'Ngày mong muốn',
		preferred_time: 'Giờ mong muốn',
		duration_minutes: 'Thời lượng',
		location: 'Địa điểm',
		notes: 'Ghi chú',
		special_requirements: 'Yêu cầu đặc biệt',
		has_experience: 'Kinh nghiệm',
		experience_level: 'Mức kinh nghiệm',
		test_drive_type: 'Loại lịch'
	};

	form.addEventListener('submit', async function(e){
		e.preventDefault();
		const submitBtn = form.querySelector('button[type="submit"]');
		const original = submitBtn ? submitBtn.innerHTML : '';
		
		// Client-side validation theo thứ tự: mẫu xe → showroom → ngày → giờ
		try {
			// 1. Check mẫu xe
			const carEl = form.querySelector('[name="car_variant_id"]');
			if (!carEl || !carEl.value.trim()) {
				if (typeof window.showMessage === 'function') window.showMessage('Vui lòng chọn mẫu xe.', 'error');
				if (carEl) carEl.focus();
				throw new Error('validation-stop');
			}
			
			// 2. Check showroom
			const showroomEl = form.querySelector('[name="showroom_id"]');
			if (!showroomEl || !showroomEl.value.trim()) {
				if (typeof window.showMessage === 'function') window.showMessage('Vui lòng chọn showroom.', 'error');
				if (showroomEl) showroomEl.focus();
				throw new Error('validation-stop');
			}
			
			// 3. Check ngày mong muốn
			const dateEl = form.querySelector('[name="preferred_date"]');
			if (!dateEl || !dateEl.value.trim()) {
				if (typeof window.showMessage === 'function') window.showMessage('Vui lòng chọn ngày mong muốn.', 'error');
				if (dateEl) dateEl.focus();
				throw new Error('validation-stop');
			}
			
			// 3.1. Check ngày phải sau hôm nay
			try {
				const today = new Date(); today.setHours(0,0,0,0);
				const selectedDate = new Date(dateEl.value); selectedDate.setHours(0,0,0,0);
				if (selectedDate <= today) {
					if (typeof window.showMessage === 'function') window.showMessage('Ngày mong muốn phải sau hôm nay.', 'error');
					dateEl.focus();
					throw new Error('validation-stop');
				}
				
				// 3.2. Check cuối tuần
				const dayOfWeek = selectedDate.getDay();
				if (dayOfWeek === 0 || dayOfWeek === 6) {
					if (typeof window.showMessage === 'function') window.showMessage('Không thể đặt lịch vào cuối tuần.', 'error');
					dateEl.focus();
					throw new Error('validation-stop');
				}
			} catch(e) {
				if (e.message === 'validation-stop') throw e;
			}
			
			// 4. Check giờ mong muốn
			const timeEl = form.querySelector('[name="preferred_time"]');
			if (!timeEl || !timeEl.value.trim()) {
				if (typeof window.showMessage === 'function') window.showMessage('Vui lòng chọn giờ mong muốn.', 'error');
				if (timeEl) timeEl.focus();
				throw new Error('validation-stop');
			}
			
			// 4.1. Check giờ làm việc
			try {
				const hour = parseInt(timeEl.value.split(':')[0]);
				if (hour < 9 || hour >= 17) {
					if (typeof window.showMessage === 'function') window.showMessage('Giờ hẹn phải trong khung 09:00-16:59.', 'error');
					timeEl.focus();
					throw new Error('validation-stop');
				}
			} catch(e) {
				if (e.message === 'validation-stop') throw e;
			}
			
		} catch (err) {
			if (submitBtn){ submitBtn.disabled = false; submitBtn.classList.remove('opacity-60'); }
			if (err && err.message === 'validation-stop') return; // stop submit
		}
		if (submitBtn){ submitBtn.disabled = true; submitBtn.classList.add('opacity-60'); }
		try {
			const fd = new FormData(form);
			const res = await fetch(form.getAttribute('action'), { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept':'application/json' }, body: fd });
			const data = await res.json().catch(()=>({}));
			if (res.ok && data && data.success){
				if (typeof window.showMessage === 'function') window.showMessage(data.message || 'Đặt lịch lái thử thành công!', 'success');
				const go = () => {
					if (data.redirect){ window.location.href = data.redirect; }
					else { window.location.href = "{{ route('test-drives.index') }}"; }
				};
				setTimeout(go, 1400);
			} else {
				// Backend đã đảm bảo chỉ trả về 1 error theo thứ tự ưu tiên
				let message = (data && data.message) || 'Dữ liệu chưa hợp lệ. Vui lòng kiểm tra lại.';
				if (typeof window.showMessage === 'function') window.showMessage(message, 'error');
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
    const date = document.getElementById('preferred_date')?.value;
    
    if (!date){
      if (typeof window.showMessage === 'function') window.showMessage('Vui lòng chọn ngày.', 'error');
      return;
    }
    
    out.innerHTML = '<div class="text-gray-600"><i class="fas fa-spinner fa-spin"></i> Đang kiểm tra...</div>';
    
    try {
      const res = await fetch("{{ route('test-drives.check-availability') }}", { 
        method:'POST', 
        headers:{ 
          'Content-Type':'application/json', 
          'X-Requested-With':'XMLHttpRequest', 
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
        }, 
        body: JSON.stringify({ showroom_id: showroom, date }) 
      });
      
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
</script>
@endsection