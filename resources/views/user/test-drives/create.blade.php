@extends('layouts.app')
@section('title', 'Đặt lịch lái thử')
@section('content')

<div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
	<div class="flex items-center justify-between mb-4 sm:mb-6">
		<div>
			<h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight">Đặt lịch lái thử</h1>
			<p class="text-sm sm:text-base text-gray-500 mt-1">Chọn mẫu xe, showroom và thời gian phù hợp với bạn</p>
		</div>
		<a href="{{ route('test-drives.index') }}" class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold"><i class="fas fa-arrow-left"></i> Lịch của tôi</a>
	</div>

	<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
		<div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
			<form method="POST" action="{{ route('test-drives.book') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="testdrive-create-form" novalidate>
				@csrf
				<div class="sm:col-span-2">
					<label class="block text-sm font-medium text-gray-700 mb-1">Mẫu xe</label>
					<select name="car_variant_id" class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
						<option value="">— Chọn mẫu xe —</option>
						@foreach(($variants ?? collect()) as $v)
							<option value="{{ $v->id }}">{{ trim(((optional(optional($v->carModel)->carBrand)->name) ? optional(optional($v->carModel)->carBrand)->name.' • ' : '') . ((optional($v->carModel)->name) ? optional($v->carModel)->name.' • ' : '') . ($v->name ?? '')) }}</option>
						@endforeach
					</select>
				</div>
				<div class="sm:col-span-2">
					<label class="block text-sm font-medium text-gray-700 mb-1">Showroom</label>
					<select name="showroom_id" class="w-full border rounded-xl px-3 py-2">
						<option value="">— Chọn showroom —</option>
						@foreach(($showrooms ?? collect()) as $s)
							<option value="{{ $s->id }}">{{ $s->name }}</option>
						@endforeach
					</select>
				</div>
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-1">Ngày mong muốn</label>
					<input type="date" name="preferred_date" required class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
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
			</form>
		</div>
		<div class="space-y-4 sm:space-y-6">
			<!-- Quy định đặt lịch -->
			<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
				<h3 class="text-base font-bold mb-3">Quy định đặt lịch</h3>
				<ul class="text-sm text-gray-600 list-disc pl-5 space-y-1">
					<li><span class="font-medium">Ngày mong muốn</span> phải <span class="font-medium">sau hôm nay</span> và trong vòng <span class="font-medium">60 ngày</span>.</li>
					<li>Không nhận lịch vào <span class="font-medium">Chủ nhật</span>.</li>
					<li><span class="font-medium">Giờ làm việc</span>: từ <span class="font-medium">08:00</span> đến <span class="font-medium">20:00</span>.</li>
				</ul>
			</div>

			<!-- Lưu ý -->
			<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
				<h3 class="text-base font-bold mb-3">Lưu ý</h3>
				<ul class="text-sm text-gray-600 list-disc pl-5 space-y-1">
					<li>Vui lòng mang theo GPLX hợp lệ khi tới lái thử.</li>
					<li>Chúng tôi sẽ gọi xác nhận trước giờ hẹn.</li>
					<li>Bạn có thể thay đổi thời gian sau khi đặt nếu cần.</li>
				</ul>
			</div>
			<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
				<h3 class="text-base font-bold mb-3">Liên hệ hỗ trợ</h3>
				<div class="text-sm text-gray-700">Hotline: <span class="font-semibold">1800 1234</span></div>
				<div class="text-sm text-gray-700">Email: <span class="font-semibold">support@autolux.vn</span></div>
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

	function validateVN(){
		const missing = [];
		const others = [];
		const requiredFields = ['car_variant_id','preferred_date','preferred_time'];
		requiredFields.forEach(function(name){
			const el = form.querySelector(`[name="${name}"]`);
			const val = el ? (el.value || '').trim() : '';
			if (!val){ missing.push(fieldLabels[name] || name); }
		});
		// date must be after today (same rule as backend)
		const dateEl = form.querySelector('[name="preferred_date"]');
		const dateVal = (dateEl && dateEl.value ? dateEl.value : '').trim();
		if (dateVal){
			try{
				const today = new Date(); today.setHours(0,0,0,0);
				const d = new Date(dateVal); d.setHours(0,0,0,0);
				if (d <= today){ others.push('Ngày mong muốn phải sau hôm nay'); }
			}catch{}
		}
		return { missing, others };
	}

	form.addEventListener('submit', async function(e){
		e.preventDefault();
		const submitBtn = form.querySelector('button[type="submit"]');
		const original = submitBtn ? submitBtn.innerHTML : '';
		const v = validateVN();
		if ((v.missing && v.missing.length) || (v.others && v.others.length)){
			// Highlight required fields
			['car_variant_id','preferred_date','preferred_time'].forEach(function(name){
				const el = form.querySelector(`[name="${name}"]`);
				if (el){ el.classList.remove('border-gray-300'); el.classList.add('border-red-500','focus:border-red-500','focus:ring-red-500'); }
			});
			// Build concise, friendly message
			let msgParts = [];
			if (v.missing.length){ msgParts.push('Thiếu: ' + v.missing.join(', ')); }
			if (v.others.length){ msgParts = msgParts.concat(v.others); }
			const summary = msgParts.join('. ');
			const msg = summary ? ('Vui lòng kiểm tra: ' + summary + '.') : 'Vui lòng kiểm tra lại thông tin.';
			if (typeof window.showMessage === 'function') window.showMessage(msg, 'warning'); else alert(msg);
			return;
		}
		if (submitBtn){
			submitBtn.disabled = true;
			submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...';
		}
		try{
			const formData = new FormData(form);
			const res = await fetch(form.action, {
				method: 'POST',
				headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
				body: formData
			});
			if (res.status === 422){
				const data = await res.json().catch(()=>({ errors:{} }));
				const messages = [];
				// Clear old highlighting
				form.querySelectorAll('input, select, textarea').forEach(el=>{
					el.classList.remove('border-red-500','focus:border-red-500','focus:ring-red-500');
				});
				if (data && data.errors){
					Object.keys(data.errors).forEach(function(field){
						const label = fieldLabels[field] || field;
						const arr = Array.isArray(data.errors[field]) ? data.errors[field] : [String(data.errors[field])];
						// Highlight field with error
						const el = form.querySelector(`[name="${field}"]`);
						if (el){ el.classList.add('border-red-500','focus:border-red-500','focus:ring-red-500'); }
						arr.forEach(m => messages.push(`${label}: ${m}`));
					});
				}
				// Fallback messages if backend didn’t provide details
				if (messages.length === 0){
					const miss = [];
					['car_variant_id','preferred_date','preferred_time'].forEach(n=>{
						const el = form.querySelector(`[name="${n}"]`);
						if (el && !el.value) miss.push(fieldLabels[n]);
					});
					if (miss.length) messages.push('Thiếu: ' + miss.join(', '));
					messages.push('Ngày phải sau hôm nay và trong 60 ngày; giờ trong 08:00–20:00');
				}
				// Build concise message: join first 2 issues, then add more-count
				let display = messages.slice(0, 2).join('. ');
				if (messages.length > 2) display += `. (+${messages.length - 2} lỗi khác)`;
				const fullMsg = display ? ('Vui lòng kiểm tra: ' + display + '.') : 'Vui lòng kiểm tra lại thông tin.';
				if (typeof window.showMessage === 'function') window.showMessage(fullMsg, 'warning'); else alert(fullMsg);
			}else if (res.ok){
				const data = await res.json().catch(()=>({ success:true }));
				if (data && data.success){
					if (typeof window.showMessage === 'function') window.showMessage(data.message || 'Đặt lịch lái thử thành công!', 'success');
					// Redirect to danh sách để đồng bộ
					setTimeout(function(){ window.location.href = "{{ route('test-drives.index') }}"; }, 1400);
				}
			}
		}catch(e){
			if (typeof window.showMessage === 'function') window.showMessage('Đã xảy ra lỗi khi đặt lịch lái thử.', 'error'); else alert('Đã xảy ra lỗi khi đặt lịch lái thử.');
		}finally{
			if (submitBtn){
				submitBtn.disabled = false;
				submitBtn.innerHTML = original;
			}
		}
	});
})();
</script>
@endsection