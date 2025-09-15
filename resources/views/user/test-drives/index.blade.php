@extends('layouts.app')
@section('title', 'Lịch lái thử của tôi')
@section('content')

<div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 pt-6 sm:pt-8 pb-6">
	<div class="flex items-center justify-between mb-4 sm:mb-6">
		<div>
			<h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight">Lịch lái thử của tôi</h1>
			<p class="text-sm sm:text-base text-gray-500 mt-1">Quản lý tất cả các lịch lái thử đã đặt</p>
		</div>
		<div class="hidden sm:flex items-center gap-2">
			<a href="{{ route('test-drives.create') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 text-sm font-semibold">
				<i class="fas fa-plus"></i> Đặt lịch mới
			</a>
		</div>
	</div>

	<form action="{{ route('test-drives.index') }}" method="get" class="mb-4 sm:mb-6">
		<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
			<div class="grid grid-cols-1 md:grid-cols-3 gap-3">
				<div class="md:col-span-2">
					<label class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
					<div class="relative">
						<input type="text" name="q" value="{{ $q ?? request('q') }}" placeholder="Mã lịch, tên mẫu xe..." class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 pr-10">
						<span class="absolute inset-y-0 right-3 flex items-center text-gray-400"><i class="fas fa-search"></i></span>
					</div>
				</div>
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
					<select name="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
						<option value="">Tất cả</option>
						@foreach(\App\Models\TestDrive::STATUSES as $st)
							<option value="{{ $st }}" @selected(($status ?? request('status')) === $st)>{{ (new \App\Models\TestDrive(['status'=>$st]))->status_text }}</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>
	</form>
	<div id="summary-host">
		@include('user.test-drives.partials.summary', ['paginator' => $testDrives->withQueryString()])
	</div>

	<div id="testdrives-list-wrapper">
		@if(($testDrives->count() ?? 0) === 0)
			<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 sm:p-14 text-center">
				<div class="w-16 h-16 mx-auto mb-5 bg-gray-100 rounded-full flex items-center justify-center">
					<i class="fas fa-car-side text-2xl text-gray-400"></i>
				</div>
				<div class="text-lg sm:text-xl font-semibold text-gray-700 mb-2">Chưa có lịch lái thử</div>
				<p class="text-sm sm:text-base text-gray-500 max-w-xl mx-auto">Bạn chưa có lịch lái thử nào. Hãy đặt lịch để trải nghiệm mẫu xe bạn quan tâm.</p>
			</div>
		@else
			@include('user.test-drives.partials.list', ['testDrives' => $testDrives->withQueryString()])
		@endif
	</div>
</div>

<script>
function debounce(fn, delay){ let t; return function(...args){ clearTimeout(t); t=setTimeout(()=>fn.apply(this,args), delay); }; }

// Ajax refresh of list (no full reload)
async function refreshList(){
	const form = document.querySelector(`form[action='{{ route('test-drives.index') }}']`);
	const url = new URL(form.action, window.location.origin);
	const params = new URLSearchParams(new FormData(form));
	url.search = params.toString();
	showListLoading();
	window.scrollTo({ top: 0, behavior: 'smooth' });
	const res = await fetch(url, { headers: { 'X-Requested-With':'XMLHttpRequest' } });
	const data = await res.json().catch(()=>({}));
	if (res.ok && data && data.html){
		const target = document.getElementById('testdrives-list-wrapper');
		if (target){ target.innerHTML = data.html; bindPagination(); window.scrollTo({ top: 0, behavior: 'smooth' }); }
		// Update summary (1–10 and total) based on filtered paginator
		try{
			const summaryHtml = data.summary || '';
			if (summaryHtml){
				const summaryHost = document.getElementById('summary-host');
				if (summaryHost){ summaryHost.innerHTML = summaryHtml; }
			}
		}catch{}
	}
	hideListLoading();
}

(function(){
	const form = document.querySelector(`form[action='{{ route('test-drives.index') }}']`);
	if (!form) return;
	const inputs = form.querySelectorAll('input[name="q"], select[name="status"]');
	const handler = debounce(refreshList, 300);
	inputs.forEach(i=>{ i.addEventListener('input', handler); i.addEventListener('change', handler); });
})();

function showListLoading(){
	const target = document.getElementById('testdrives-list-wrapper');
	if (!target) return;
	const loader = '<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center text-gray-500"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div>';
	target.innerHTML = loader;
}

function hideListLoading(){ /* no-op: content replaces skeleton */ }

// Ajax pagination: intercept clicks on pagination-modern
function bindPagination(){
	// No-op: we now use delegated handler below
}

// Delegated click handler to intercept pagination links anywhere in the wrapper
document.addEventListener('click', function(e){
	const anchor = e.target.closest('#testdrives-list-wrapper nav[aria-label="Pagination Navigation"] a');
	if (!anchor) return;
	e.preventDefault();
	const href = anchor.getAttribute('href');
	if (!href) return;
	showListLoading();
	window.scrollTo({ top: 0, behavior: 'smooth' });
	fetch(href, { headers: { 'X-Requested-With':'XMLHttpRequest' } })
		.then(r=>r.json())
		.then(data=>{
			if (data && data.html){
				const target = document.getElementById('testdrives-list-wrapper');
				if (target){
					target.innerHTML = data.html;
					window.scrollTo({ top: 0, behavior: 'smooth' });
					// Push URL without the page param (preserve other params)
					try{
						const u = new URL(href, window.location.origin);
						u.searchParams.delete('page');
						const clean = u.pathname + (u.searchParams.toString() ? ('?' + u.searchParams.toString()) : '');
						history.pushState({}, '', clean);
					}catch{
						// Fallback: keep current path
					}
				}
			}
		}).catch(()=>{});
});

// Initial bind on first load
bindPagination();

// Cancel action with confirm
function showConfirmDialog(title, message, confirmText, cancelText, onConfirm){
	const existing = document.querySelector('.fast-confirm-dialog');
	if (existing) existing.remove();
	const wrapper = document.createElement('div');
	wrapper.className = 'fast-confirm-dialog fixed inset-0 z-[100000] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4';
	wrapper.innerHTML = `
		<div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-200 scale-95 opacity-0">
			<div class="p-6">
				<div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4"><i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i></div>
				<h3 class="text-lg font-semibold text-gray-900 text-center mb-2">${title}</h3>
				<p class="text-gray-600 text-center mb-6">${message}</p>
				<div class="flex space-x-3">
					<button class="fast-cancel flex-1 px-4 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200">${cancelText}</button>
					<button class="fast-confirm flex-1 px-4 py-2.5 text-white bg-red-600 hover:bg-red-700 rounded-lg font-medium transition-colors duration-200">${confirmText}</button>
				</div>
			</div>
		</div>`;
	document.body.appendChild(wrapper);
	const panel = wrapper.firstElementChild;
	requestAnimationFrame(()=>{ panel.style.transform='scale(1)'; panel.style.opacity='1'; });
	wrapper.addEventListener('click', (ev)=>{ if (ev.target === wrapper) wrapper.remove(); });
	wrapper.querySelector('.fast-cancel').addEventListener('click', ()=> wrapper.remove());
	wrapper.querySelector('.fast-confirm').addEventListener('click', ()=>{ wrapper.remove(); onConfirm && onConfirm(); });
}

document.addEventListener('click', async function(e){
	const cancelBtn = e.target.closest('.js-cancel');
	if (cancelBtn){
		e.preventDefault();
		const id = cancelBtn.getAttribute('data-id');
		showConfirmDialog('Hủy lịch lái thử?', 'Bạn có chắc chắn muốn hủy lịch này? Hành động không thể hoàn tác.', 'Hủy lịch', 'Hủy bỏ', async ()=>{
			const originalHtml = cancelBtn.innerHTML;
			cancelBtn.disabled = true;
			cancelBtn.classList.remove('bg-rose-500','hover:bg-rose-600','text-white');
			cancelBtn.classList.add('bg-gray-100','text-gray-400');
			cancelBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang hủy…';
			try{
				const url = `{{ url('/test-drives') }}/${id}/cancel`;
				const res = await fetch(url, { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN': '{{ csrf_token() }}','Accept':'application/json' } });
				const data = await res.json().catch(()=>({}));
				if (res.ok && data.success){
					const card = cancelBtn.closest('.booking-card');
					if (card){
						// Keep card visual unchanged; only update badge and button state
						const badge = card.querySelector('[data-role="status-badge"]');
						if (badge){
							badge.className = 'px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-800 whitespace-nowrap inline-flex items-center';
							badge.textContent = 'Đã hủy';
						}
						// Restore original cancel button icon+label but keep disabled & gray
						cancelBtn.innerHTML = originalHtml;
						cancelBtn.setAttribute('disabled','disabled');
					}
					if (typeof showMessage==='function') showMessage(data.message,'success');
					// Update notifications UI instantly
					if (window.refreshNotifBadge) window.refreshNotifBadge();
					if (window.prependNotifItem) window.prependNotifItem('Đã hủy lịch lái thử', `Bạn đã hủy lịch lái thử #${id}.`);
				}else{
					cancelBtn.disabled = false;
					cancelBtn.classList.remove('bg-gray-100','text-gray-400');
					cancelBtn.classList.add('bg-rose-500','hover:bg-rose-600','text-white');
					cancelBtn.innerHTML = originalHtml;
					if (typeof showMessage==='function') showMessage(data.message || 'Không thể hủy lịch','error');
				}
			}catch{
				cancelBtn.disabled = false;
				cancelBtn.classList.remove('bg-gray-100','text-gray-400');
				cancelBtn.classList.add('bg-rose-500','hover:bg-rose-600','text-white');
				cancelBtn.innerHTML = originalHtml;
			}
		});
	}
});
</script>

@endsection