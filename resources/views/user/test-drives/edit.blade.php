@extends('layouts.app')
@section('title', 'Sửa lịch lái thử')
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
						<h1 class="text-2xl font-bold text-gray-900">Sửa lịch lái thử</h1>
						<p class="text-sm text-gray-500">#{{ $testDrive->test_drive_number ?? $testDrive->id }}</p>
					</div>
				</div>
				<div class="flex items-center gap-4 text-sm text-gray-600">
					<span><i class="fas fa-calendar mr-1"></i>{{ optional($testDrive->preferred_date)->format('d/m/Y') }}</span>
					<span><i class="fas fa-clock mr-1"></i>{{ is_string($testDrive->preferred_time) ? substr($testDrive->preferred_time,0,5) : optional($testDrive->preferred_time)->format('H:i') }}</span>
					@php
						$statusColors = [
							'pending' => 'bg-yellow-100 text-yellow-800',
							'confirmed' => 'bg-blue-100 text-blue-800', 
							'completed' => 'bg-green-100 text-green-800',
							'cancelled' => 'bg-red-100 text-red-800'
						];
						$statusLabels = [
							'pending' => 'Chờ xác nhận',
							'confirmed' => 'Đã xác nhận',
							'completed' => 'Hoàn thành', 
							'cancelled' => 'Đã hủy'
						];
						$statusColor = $statusColors[$testDrive->status] ?? 'bg-gray-100 text-gray-800';
						$statusLabel = $statusLabels[$testDrive->status] ?? ucfirst($testDrive->status);
					@endphp
					<span class="px-2 py-1 {{ $statusColor }} rounded-full text-xs font-medium">
						{{ $statusLabel }}
					</span>
				</div>
			</div>
			<a href="{{ route('test-drives.show', $testDrive) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-white hover:shadow-sm transition-all text-sm font-medium">
				<i class="fas fa-arrow-left"></i> Quay lại
			</a>
		</div>

	@if(session('error') || $errors->any())
		<div id="td-edit-flash" data-error="{{ e(session('error')) }}" data-errors='@json($errors->all())'></div>
		<script>
		(function(){
			try{
				var host = document.getElementById('td-edit-flash');
				if (!host || typeof window.showMessage !== 'function') return;
				var single = host.getAttribute('data-error');
				if (single) window.showMessage(single, 'error');
				var list = host.getAttribute('data-errors');
				if (list){
					try { list = JSON.parse(list); } catch(e) { list = []; }
					if (Array.isArray(list)) list.forEach(function(m){ if (m) window.showMessage(m, 'error'); });
				}
			} catch(e) {}
		})();
		</script>
	@endif

	<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
		<div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
			<form method="POST" action="{{ route('test-drives.update', $testDrive) }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="testdrive-edit-form" novalidate>
				@csrf
				@method('PUT')
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-2">Mẫu xe</label>
					<select name="car_variant_id" class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
						<option value="">— Chọn mẫu xe —</option>
						@foreach($variants as $v)
							<option value="{{ $v->id }}" @selected(old('car_variant_id', $testDrive->car_variant_id) == $v->id)>{{ trim(((optional(optional($v->carModel)->carBrand)->name) ? optional(optional($v->carModel)->carBrand)->name.' • ' : '') . ((optional($v->carModel)->name) ? optional($v->carModel)->name.' • ' : '') . ($v->name ?? '')) }}</option>
						@endforeach
					</select>
				</div>
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-2">Showroom</label>
					<select name="showroom_id" id="showroom_id" class="w-full border rounded-xl px-3 py-2">
						<option value="">— Chọn showroom —</option>
						@foreach($showrooms as $s)
							<option value="{{ $s->id }}" @selected(old('showroom_id', $testDrive->showroom_id) == $s->id)>{{ $s->name }}</option>
						@endforeach
					</select>
				</div>
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-2">Ngày mong muốn</label>
					<input type="date" name="preferred_date" id="preferred_date" value="{{ old('preferred_date', optional($testDrive->preferred_date)->format('Y-m-d')) }}" required class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
				</div>
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-2">Giờ mong muốn</label>
					<input type="time" name="preferred_time" value="{{ old('preferred_time', (is_string($testDrive->preferred_time) ? substr($testDrive->preferred_time,0,5) : optional($testDrive->preferred_time)->format('H:i'))) }}" required class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
				</div>
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-2">Thời lượng (phút)</label>
					<input type="number" name="duration_minutes" min="5" value="{{ old('duration_minutes', $testDrive->duration_minutes) }}" class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="VD: 30">
				</div>
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-2">Địa điểm</label>
					<input type="text" name="location" value="{{ old('location', $testDrive->location) }}" class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="VD: Showroom Quận 1 hoặc địa chỉ khác">
				</div>
				<div class="sm:col-span-2">
					<label class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
					<textarea name="notes" rows="3" class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Yêu cầu về địa điểm, khung giờ linh hoạt...">{{ old('notes', $testDrive->notes) }}</textarea>
				</div>
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-2">Loại lịch</label>
					<select name="test_drive_type" class="w-full border rounded-xl px-3 py-2">
						<option value="individual" @selected(old('test_drive_type', $testDrive->test_drive_type) == 'individual')>Cá nhân</option>
						<option value="group" @selected(old('test_drive_type', $testDrive->test_drive_type) == 'group')>Nhóm</option>
						<option value="virtual" @selected(old('test_drive_type', $testDrive->test_drive_type) == 'virtual')>Trực tuyến</option>
					</select>
				</div>
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-2">Kinh nghiệm</label>
					<select name="experience_level" class="w-full border rounded-xl px-3 py-2">
						<option value="">— Chọn —</option>
						<option value="beginner" @selected(old('experience_level', $testDrive->experience_level) == 'beginner')>Mới bắt đầu</option>
						<option value="intermediate" @selected(old('experience_level', $testDrive->experience_level) == 'intermediate')>Trung bình</option>
						<option value="advanced" @selected(old('experience_level', $testDrive->experience_level) == 'advanced')>Nhiều kinh nghiệm</option>
					</select>
				</div>
				<div class="sm:col-span-2">
					<label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="has_experience" value="1" class="rounded" @checked(old('has_experience', $testDrive->has_experience))> Đã từng lái xe tương tự</label>
				</div>
				<div class="sm:col-span-2">
					<label class="block text-sm font-medium text-gray-700 mb-2">Yêu cầu đặc biệt</label>
					<textarea name="special_requirements" rows="3" class="w-full border rounded-xl px-3 py-2" placeholder="Ví dụ: đường thử cụ thể, nhân viên hỗ trợ,...">{{ old('special_requirements', $testDrive->special_requirements) }}</textarea>
				</div>
				<div class="sm:col-span-2">
					<button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-3 rounded-xl transition">
						<i class="fas fa-save"></i> Lưu thay đổi
					</button>
				</div>
			</form>
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

@endsection

<script>
(function(){
	const form = document.getElementById('testdrive-edit-form');
	if (!form) return;

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
				if (typeof window.showMessage === 'function') window.showMessage(data.message || 'Cập nhật lịch lái thử thành công!', 'success');
				const go = () => {
					if (data.redirect){ window.location.href = data.redirect; }
					else { window.location.href = "{{ route('test-drives.show', $testDrive) }}"; }
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

	// Slot checker functionality
	const btnCheckSlots = document.getElementById('btn-check-slots');
	const slotResults = document.getElementById('slot-results');
	
	if (btnCheckSlots && slotResults) {
		btnCheckSlots.addEventListener('click', function() {
			const showroomEl = form.querySelector('[name="showroom_id"]');
			const dateEl = form.querySelector('[name="preferred_date"]');
			
			if (!showroomEl || !showroomEl.value) {
				if (typeof window.showMessage === 'function') window.showMessage('Vui lòng chọn showroom trước.', 'error');
				return;
			}
			
			if (!dateEl || !dateEl.value) {
				if (typeof window.showMessage === 'function') window.showMessage('Vui lòng chọn ngày trước.', 'error');
				return;
			}
			
			// Mock slot data - replace with actual API call
			slotResults.innerHTML = `
				<div class="text-xs text-gray-500 mb-2">Slot trống cho ${dateEl.value}:</div>
				<div class="grid grid-cols-2 gap-1">
					<div class="px-2 py-1 bg-green-50 text-green-700 rounded text-xs">09:00</div>
					<div class="px-2 py-1 bg-green-50 text-green-700 rounded text-xs">10:00</div>
					<div class="px-2 py-1 bg-red-50 text-red-700 rounded text-xs">11:00 (Đã đặt)</div>
					<div class="px-2 py-1 bg-green-50 text-green-700 rounded text-xs">14:00</div>
					<div class="px-2 py-1 bg-green-50 text-green-700 rounded text-xs">15:00</div>
					<div class="px-2 py-1 bg-green-50 text-green-700 rounded text-xs">16:00</div>
				</div>
			`;
		});
	}
})();
</script>


