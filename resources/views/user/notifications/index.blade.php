@extends('layouts.app')
@section('title', 'Thông báo')
@section('content')

<div class="max-w-5xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
	<div class="flex items-center justify-between mb-4 sm:mb-6">
		<div>
			<h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight">Thông báo</h1>
			<p class="text-sm sm:text-base text-gray-500 mt-1">Tất cả cập nhật mới nhất liên quan đến tài khoản và giao dịch của bạn</p>
		</div>
		<div class="flex items-center gap-2">
			<button id="btn-mark-all" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-amber-200 text-amber-700 hover:bg-amber-50 text-sm font-semibold"><i class="fas fa-check-double"></i> Đánh dấu đã đọc</button>
			<button id="btn-refresh" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold"><i class="fas fa-sync"></i> Làm mới</button>
		</div>
	</div>

	<div id="notif-list" class="bg-white rounded-2xl shadow-sm border border-gray-100 divide-y">
		<div class="p-6 text-center text-gray-500" id="notif-empty" style="display:none">Chưa có thông báo</div>
		{{-- Items will be injected here by JS on first load --}}
	</div>

	<div class="flex items-center justify-center mt-4">
		<button id="btn-load-more" class="px-4 py-2 text-sm rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 hidden">Tải thêm</button>
	</div>
</div>

<script>
(function(){
	let page = 1; let loading = false; let hasMore = true;
	const listEl = document.getElementById('notif-list');
	const emptyEl = document.getElementById('notif-empty');
	const btnMore = document.getElementById('btn-load-more');
	const btnRefresh = document.getElementById('btn-refresh');
	const btnMarkAll = document.getElementById('btn-mark-all');

	function renderItem(n){
		const row = document.createElement('div');
		row.className = 'p-4 sm:p-5 hover:bg-gray-50 transition-colors';
		row.innerHTML = `
			<div class="flex items-start gap-3">
				<div class="w-9 h-9 rounded-full flex items-center justify-center ${n.is_read ? 'bg-gray-100 text-gray-400' : 'bg-amber-50 text-amber-600'}">
					<i class="fas ${iconByType(n.type)}"></i>
				</div>
				<div class="flex-1 min-w-0">
					<div class="flex items-center justify-between gap-3">
						<div class="min-w-0">
							<div class="text-sm font-semibold ${n.is_read ? 'text-gray-700' : 'text-gray-900'} truncate">${escapeHtml(n.title || 'Thông báo')}</div>
							<div class="text-sm text-gray-600 mt-0.5 break-words">${escapeHtml(n.message || '')}</div>
						</div>
						<div class="shrink-0 text-xs text-gray-500 whitespace-nowrap">${formatTime(n.created_at)}</div>
					</div>
					<div class="mt-2 flex items-center gap-2">
						${n.data && n.data.order_id ? `<a href="/user/orders/${n.data.order_id}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border text-xs text-gray-700 hover:bg-gray-50"><i class="fas fa-file-invoice"></i> Xem đơn</a>` : ''}
						${n.data && n.data.test_drive_id ? `<a href="/test-drives/${n.data.test_drive_id}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border text-xs text-gray-700 hover:bg-gray-50"><i class="fas fa-car-side"></i> Xem lái thử</a>` : ''}
						${n.data && n.data.appointment_id ? `<a href="/service-appointments/${n.data.appointment_id}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border text-xs text-gray-700 hover:bg-gray-50"><i class="fas fa-tools"></i> Xem bảo dưỡng</a>` : ''}
						<button data-id="${n.id}" class="btn-read inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border text-xs ${n.is_read ? 'text-gray-400 cursor-not-allowed' : 'text-amber-700 hover:bg-amber-50'}" ${n.is_read ? 'disabled' : ''}><i class="fas fa-check"></i> Đã đọc</button>
						<button data-id="${n.id}" class="btn-del inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border text-xs text-rose-700 hover:bg-rose-50"><i class="fas fa-trash"></i> Xóa</button>
					</div>
				</div>
			</div>`;
		return row;
	}

	function iconByType(t){
		switch(t){
			case 'order_status': return 'fa-file-invoice';
			case 'payment': return 'fa-credit-card';
			case 'test_drive': return 'fa-car-side';
			case 'service_appointment': return 'fa-tools';
			default: return 'fa-bell';
		}
	}
	function escapeHtml(s){ return String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[m])); }
	function formatTime(iso){ try{ const d=new Date(iso); return d.toLocaleString(); }catch{ return ''; } }

	async function load(pageToLoad){
		if (loading || !hasMore) return; loading = true;
		if (pageToLoad === 1){ listEl.innerHTML = '<div class="p-6 text-center text-gray-500"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div>'; }
		const res = await fetch(`{{ route('notifications.index') }}?page=${pageToLoad}`, { headers: { 'X-Requested-With':'XMLHttpRequest' } });
		const data = await res.json().catch(()=>({}));
		if (!res.ok || !data || !data.data){ listEl.innerHTML = '<div class="p-6 text-center text-gray-500">Không tải được thông báo</div>'; loading=false; return; }
		const items = data.data.data || data.data;
		if (pageToLoad === 1) listEl.innerHTML = '';
		if (!items.length && pageToLoad === 1){ emptyEl.style.display = 'block'; btnMore.classList.add('hidden'); loading=false; hasMore=false; return; }
		items.forEach(n => listEl.appendChild(renderItem(n)));
		emptyEl.style.display = 'none';
		// simple hasMore detection
		hasMore = !!data.data.next_page_url;
		btnMore.classList.toggle('hidden', !hasMore);
		page = pageToLoad;
		loading = false;
		if (window.refreshNotifBadge) window.refreshNotifBadge();
	}

	btnMore.addEventListener('click', function(){ load(page + 1); });
	btnRefresh.addEventListener('click', function(){ page = 1; hasMore = true; load(1); });
	btnMarkAll.addEventListener('click', async function(){
		try{
			const res = await fetch(`{{ route('notifications.read-all') }}`, { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'{{ csrf_token() }}' } });
			if (res.ok){
				// mark all visually
				listEl.querySelectorAll('.btn-read').forEach(b=>{ b.setAttribute('disabled','disabled'); b.classList.add('text-gray-400'); b.classList.remove('text-amber-700'); });
				if (window.refreshNotifBadge) window.refreshNotifBadge();
				if (typeof window.showMessage==='function') window.showMessage('Đã đánh dấu tất cả là đã đọc','success');
			}
		}catch{}
	});

	document.addEventListener('click', async function(e){
		const readBtn = e.target.closest('.btn-read');
		const delBtn = e.target.closest('.btn-del');
		if (readBtn){
			const id = readBtn.getAttribute('data-id');
			const res = await fetch(`/notifications/${id}/read`, { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'{{ csrf_token() }}' } });
			if (res.ok){ readBtn.setAttribute('disabled','disabled'); readBtn.classList.add('text-gray-400'); readBtn.classList.remove('text-amber-700'); if (window.refreshNotifBadge) window.refreshNotifBadge(); }
		}
		if (delBtn){
			const id = delBtn.getAttribute('data-id');
			const res = await fetch(`/notifications/${id}`, { method:'DELETE', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'{{ csrf_token() }}' } });
			if (res.ok){ delBtn.closest('.p-4').remove(); }
		}
	});

	// initial load
	load(1);
})();
</script>

@endsection
