@extends('layouts.app')
@section('title', 'Chi tiết lịch lái thử')
@section('content')

<div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
    @php( $__toastKind = request('toast') ?: (session('success') ? 'success' : (session('error') ? 'error' : null)) )
    @php( $__toastMsg  = request('msg') ?: (session('success') ?: session('error')) )
    @if($__toastKind && $__toastMsg)
        <span id="toast-payload" data-kind="{{ $__toastKind }}" data-msg="{{ $__toastMsg }}" style="display:none"></span>
    @endif
	<div class="flex items-center justify-between mb-4 sm:mb-6">
		<div>
			<div class="text-xs text-gray-500">Lịch lái thử</div>
			<h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight">#{{ $testDrive->test_drive_number ?? $testDrive->id }}</h1>
			<div class="mt-1 text-sm text-gray-500">Tạo lúc {{ $testDrive->created_at?->format('d/m/Y H:i') }}</div>
		</div>
		<div class="flex items-center gap-2">
			<span class="px-3 py-1 rounded-full text-sm {{ $testDrive->status_badge }} whitespace-nowrap">{{ $testDrive->status_text }}</span>
			<a href="{{ route('test-drives.index') }}" class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold"><i class="fas fa-arrow-left"></i> Quay lại</a>
		</div>
	</div>

	<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
		<!-- Left: Primary info -->
		<div class="lg:col-span-2 space-y-4 sm:space-y-6">
			<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
				<div class="px-4 sm:px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-indigo-50 flex items-center justify-between">
					<h2 class="text-lg font-bold">Thông tin lịch</h2>
					<div class="text-xs text-gray-500">Cập nhật lúc {{ $testDrive->updated_at?->format('d/m/Y H:i') }}</div>
				</div>
				<div class="p-4 sm:p-6">
					<div class="flex items-start gap-4">
						<div class="w-40 h-24 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0">
							<img src="{{ optional(optional($testDrive->carVariant)->images->first())->image_url ?: 'https://placehold.co/160x100/EEF2FF/3730A3?text=Car' }}" alt="thumb" class="w-full h-full object-cover" />
						</div>
						<div class="min-w-0 flex-1">
							<div class="text-gray-700 text-sm">Mẫu xe</div>
							<div class="font-semibold text-gray-900 truncate">
								<a href="{{ route('car-variants.show', $testDrive->car_variant_id) }}" class="hover:text-indigo-700">
									{{ optional(optional($testDrive->carVariant)->carModel)->name }} {{ optional($testDrive->carVariant)->name ?? '' }}
								</a>
							</div>
							<div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
								<div class="flex items-center gap-2"><i class="far fa-calendar"></i><span>Ngày:</span><span class="font-medium">{{ optional($testDrive->preferred_date)->format('d/m/Y') }}</span></div>
								<div class="flex items-center gap-2"><i class="far fa-clock"></i><span>Giờ:</span><span class="font-medium">{{ is_string($testDrive->preferred_time) ? $testDrive->preferred_time : optional($testDrive->preferred_time)->format('H:i') }}</span></div>
								@if($testDrive->showroom)
									<div class="flex items-center gap-2 sm:col-span-2"><i class="fas fa-store"></i><span>Showroom:</span><span class="font-medium">{{ $testDrive->showroom->name }}</span></div>
								@endif
							</div>
						</div>
					</div>
					@if(!empty($testDrive->notes))
						<div class="mt-4">
							<div class="text-gray-700 text-sm mb-1">Ghi chú</div>
							<div class="text-sm text-gray-800 whitespace-pre-line">{{ $testDrive->notes }}</div>
						</div>
					@endif
					@if(!empty($testDrive->special_requirements))
						<div class="mt-4">
							<div class="text-gray-700 text-sm mb-1">Yêu cầu đặc biệt</div>
							<div class="text-sm text-gray-800 whitespace-pre-line">{{ $testDrive->special_requirements }}</div>
						</div>
					@endif
				</div>
			</div>

			<!-- Actions -->
			<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
				<div class="flex flex-wrap items-center gap-2">
					<a href="{{ route('test-drives.index') }}" class="px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm">Quay lại danh sách</a>
					@if(in_array($testDrive->status, ['pending','confirmed']))
						<a href="{{ route('test-drives.edit', $testDrive) }}" class="px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm">Sửa</a>
						<button type="button" class="px-3 py-2 rounded-lg bg-rose-500 text-white hover:bg-rose-600 text-sm js-cancel-one" data-id="{{ $testDrive->id }}">Hủy lịch</button>
					@else
						<button type="button" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-400 text-sm cursor-not-allowed" disabled>Không thể sửa/hủy</button>
					@endif
				</div>
    </div>
  </div>

		<!-- Right: meta -->
		<div class="space-y-4 sm:space-y-6">
			<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
				<h3 class="text-base font-bold mb-3">Tóm tắt</h3>
				<dl class="grid grid-cols-1 gap-3 text-sm">
					<div class="flex items-center justify-between"><dt class="text-gray-500">Mã lịch</dt><dd class="font-medium text-gray-900">{{ $testDrive->test_drive_number ?? ('TD-'.($testDrive->id)) }}</dd></div>
					<div class="flex items-center justify-between"><dt class="text-gray-500">Trạng thái</dt><dd><span class="px-2 py-0.5 rounded-full text-xs {{ $testDrive->status_badge }} whitespace-nowrap">{{ $testDrive->status_text }}</span></dd></div>
					<div class="flex items-center justify-between"><dt class="text-gray-500">Thời lượng</dt><dd class="font-medium text-gray-900">{{ $testDrive->duration_minutes ? ($testDrive->duration_minutes.' phút') : '—' }}</dd></div>
					<div class="flex items-center justify-between"><dt class="text-gray-500">Địa điểm</dt><dd class="font-medium text-gray-900">{{ $testDrive->location ?: '—' }}</dd></div>
					<div class="flex items-center justify-between"><dt class="text-gray-500">Loại lịch</dt><dd class="font-medium text-gray-900">
						@php($typeMap=['individual'=>'Cá nhân','group'=>'Nhóm','virtual'=>'Trực tuyến'])
						{{ $typeMap[$testDrive->test_drive_type] ?? $testDrive->test_drive_type }}
					</dd></div>
					@php($expMap=[''=>'—','beginner'=>'Mới bắt đầu','intermediate'=>'Trung bình','advanced'=>'Nhiều kinh nghiệm'])
					<div class="flex items-center justify-between"><dt class="text-gray-500">Kinh nghiệm</dt><dd class="font-medium text-gray-900">{{ $expMap[$testDrive->experience_level ?? ''] ?? '—' }}</dd></div>
					<div class="flex items-center justify-between"><dt class="text-gray-500">Đã từng lái tương tự</dt><dd class="font-medium text-gray-900">{{ $testDrive->has_experience ? 'Có' : 'Không' }}</dd></div>
					@if(!empty($testDrive->confirmed_at))
						<div class="flex items-center justify-between"><dt class="text-gray-500">Xác nhận lúc</dt><dd class="font-medium text-gray-900">{{ optional($testDrive->confirmed_at)->format('d/m/Y H:i') }}</dd></div>
					@endif
					@if(!empty($testDrive->completed_at))
						<div class="flex items-center justify-between"><dt class="text-gray-500">Hoàn thành lúc</dt><dd class="font-medium text-gray-900">{{ optional($testDrive->completed_at)->format('d/m/Y H:i') }}</dd></div>
					@endif
				</dl>
			</div>
			@if($testDrive->status === 'completed')
			<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
				<h3 class="text-base font-bold mb-3">Đánh giá trải nghiệm</h3>
				<form id="ratingForm" class="space-y-3" data-submitting="0">
					@csrf
					@php($current = (int)round((float)($testDrive->satisfaction_rating ?? 0)))
					<div class="flex items-center gap-2">
						<div class="flex items-center gap-1 select-none" id="stars">
							@for($i=1;$i<=5;$i++)
								<button type="button" data-val="{{ $i }}" class="star inline-flex items-center justify-center h-9 w-9 text-2xl {{ $i <= $current ? 'text-amber-400' : 'text-gray-300' }}" aria-label="{{ $i }} sao">★</button>
							@endfor
							<input type="hidden" name="satisfaction_rating" id="ratingVal" value="{{ $current ?: '' }}">
						</div>
						<span class="text-sm text-gray-600" id="ratingCount">{{ $current ?: 0 }}/5</span>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700 mb-1">Phản hồi</label>
						<textarea name="feedback" class="w-full border rounded-xl px-3 py-2" placeholder="Chia sẻ trải nghiệm lái thử của bạn (tùy chọn)">{{ $testDrive->feedback }}</textarea>
					</div>
					<button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-lg text-sm">Lưu đánh giá</button>
				</form>
			</div>
			@endif
		</div>
  </div>
</div>

<!-- Edit modal removed -->

<script>
// Confirm cancel handler kept elsewhere

// Rating: simple interactive stars + AJAX save (no edit toggle)
(function(){
	const form = document.getElementById('ratingForm');
	if (!form) return;
	const wrap = form.querySelector('#stars');
	const val = form.querySelector('#ratingVal');
	const countEl = form.querySelector('#ratingCount');
	let current = parseInt(val.value||'0',10) || 0;
	function paint(n){
		[...wrap.querySelectorAll('.star')].forEach((btn, idx)=>{
			btn.classList.toggle('text-amber-400', idx < n);
			btn.classList.toggle('text-gray-300', idx >= n);
		});
		if (countEl) countEl.textContent = String(n||0) + '/5';
	}
	paint(current);
	wrap.addEventListener('mouseover', (e)=>{ const b=e.target.closest('[data-val]'); if(!b) return; paint(parseInt(b.getAttribute('data-val'),10)); });
	wrap.addEventListener('mouseout', ()=>{ paint(current); });
	wrap.addEventListener('click', (e)=>{ const b=e.target.closest('[data-val]'); if(!b) return; current=parseInt(b.getAttribute('data-val'),10); val.value=String(current); paint(current); });

	form.addEventListener('submit', async function(ev){
		ev.preventDefault(); ev.stopPropagation(); if (form.dataset.submitting==='1') return; form.dataset.submitting='1';
		if (!val.value){ if (typeof showMessage==='function') showMessage('Vui lòng chọn số sao','warning'); form.dataset.submitting=''; return; }
		const fd = new FormData(form);
		try{
			const res = await fetch(`{{ route('test-drives.rate', $testDrive) }}`, { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json' }, body: fd });
			const data = await res.json().catch(()=>({}));
			if (res.ok && data.success){ if (typeof showMessage==='function') showMessage(data.message,'success'); }
			else { if (typeof showMessage==='function') showMessage(data.message || 'Không thể lưu đánh giá','error'); }
		} catch {} finally {
			form.dataset.submitting='';
		}
	});
})();
</script>

@endsection






