@extends('layouts.app')
@section('title', 'Thông báo')
@section('content')

<div class="max-w-6xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
	<div class="flex items-center justify-between mb-4 sm:mb-6">
		<div>
			<h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight">Thông báo</h1>
			<p class="text-sm sm:text-base text-gray-500 mt-1">Tất cả cập nhật liên quan đến tài khoản và giao dịch của bạn</p>
		</div>
		<div class="flex items-center gap-2">
			<button id="btn-mark-all" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-amber-200 text-amber-700 hover:bg-amber-50 text-sm font-semibold"><i class="fas fa-check-double"></i> Đánh dấu đã đọc</button>
			<button id="btn-refresh" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold"><i class="fas fa-sync"></i> Làm mới</button>
			<button id="btn-delete-all" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-rose-200 text-rose-700 hover:bg-rose-50 text-sm font-semibold"><i class="fas fa-trash"></i> Xóa tất cả</button>
		</div>
	</div>

	<div id="notif-list" class="bg-white rounded-2xl shadow-sm border border-gray-100 divide-y" data-initial-has-more="{{ $notifications->hasMorePages() ? '1' : '0' }}" data-initial-count="{{ $notifications->count() }}">
		@if($notifications->count() > 0)
			@foreach($notifications as $notification)
				<div class="p-4 sm:p-5 hover:bg-gray-50 transition-colors" data-notif-id="{{ $notification->id }}">
					<div class="flex items-start gap-3">
						<div class="w-10 h-10 rounded-full flex items-center justify-center {{ $notification->is_read ? 'bg-gray-100 text-gray-400' : 'bg-amber-50 text-amber-600' }}">
							<i class="fas {{ $notification->type === 'order_status' ? 'fa-file-invoice' : ($notification->type === 'payment' ? 'fa-credit-card' : ($notification->type === 'test_drive' ? 'fa-car-side' : ($notification->type === 'service_appointment' ? 'fa-tools' : 'fa-bell'))) }}"></i>
						</div>
						<div class="flex-1 min-w-0">
							<div class="flex items-center justify-between gap-3">
								<div class="min-w-0">
									<div class="text-sm sm:text-base font-semibold {{ $notification->is_read ? 'text-gray-700' : 'text-gray-900' }} truncate">{{ $notification->title ?: 'Thông báo' }}</div>
									<div class="text-sm text-gray-600 mt-0.5 break-words">{{ $notification->message ?: '' }}</div>
								</div>
								<div class="shrink-0 text-xs text-gray-500 whitespace-nowrap">{{ $notification->created_at->format('d/m/Y H:i') }}</div>
							</div>
							<div class="mt-2 flex items-center flex-wrap gap-2">
								@if($notification->type === 'order_status')
									<a href="/user/orders" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border text-xs text-gray-700 hover:bg-gray-50"><i class="fas fa-file-invoice"></i> Xem đơn hàng</a>
								@endif
								@if($notification->type === 'test_drive')
									<a href="/test-drives" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border text-xs text-gray-700 hover:bg-gray-50"><i class="fas fa-car-side"></i> Xem lái thử</a>
								@endif
								@if($notification->type === 'service_appointment')
									<a href="/service-appointments" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border text-xs text-gray-700 hover:bg-gray-50"><i class="fas fa-tools"></i> Xem bảo dưỡng</a>
								@endif
								<button data-id="{{ $notification->id }}" class="btn-read inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border text-xs {{ $notification->is_read ? 'text-gray-400 cursor-not-allowed' : 'text-amber-700 hover:bg-amber-50' }}" {{ $notification->is_read ? 'disabled' : '' }}><i class="fas fa-check"></i> Đã đọc</button>
								<button data-id="{{ $notification->id }}" class="btn-del inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border text-xs text-rose-700 hover:bg-rose-50"><i class="fas fa-trash"></i> Xóa</button>
							</div>
						</div>
					</div>
				</div>
			@endforeach
		@else
			<div class="p-10 sm:p-14 text-center" id="notif-empty" style="display: block;">
				<div class="w-16 h-16 mx-auto mb-5 bg-gray-100 rounded-full flex items-center justify-center">
					<i class="fas fa-bell-slash text-2xl text-gray-400"></i>
				</div>
				<h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-2">Chưa có thông báo</h3>
				<p class="text-sm sm:text-base text-gray-500 max-w-xl mx-auto">Bạn sẽ nhận được thông báo khi có cập nhật về đơn hàng, lịch lái thử, bảo dưỡng và các hoạt động khác.</p>
			</div>
		@endif
	</div>

	<div class="flex items-center justify-center mt-4 gap-3">
		<button id="btn-load-more" class="px-4 py-2 text-sm rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 hidden">Tải thêm</button>
		<span id="no-more" class="text-xs text-gray-500 hidden">Đã tải hết tất cả thông báo</span>
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
	const btnDeleteAll = document.getElementById('btn-delete-all');
	const noMoreEl = document.getElementById('no-more');

	function renderItem(n){
		const row = document.createElement('div');
		row.className = 'p-4 sm:p-5 hover:bg-gray-50 transition-colors';
		row.setAttribute('data-notif-id', String(n.id || ''));
		row.innerHTML = `
			<div class="flex items-start gap-3">
				<div class="w-10 h-10 rounded-full flex items-center justify-center ${n.is_read ? 'bg-gray-100 text-gray-400' : 'bg-amber-50 text-amber-600'}">
					<i class="fas ${iconByType(n.type)}"></i>
				</div>
				<div class="flex-1 min-w-0">
					<div class="flex items-center justify-between gap-3">
						<div class="min-w-0">
							<div class="text-sm sm:text-base font-semibold ${n.is_read ? 'text-gray-700' : 'text-gray-900'} truncate">${escapeHtml(n.title || 'Thông báo')}</div>
							<div class="text-sm text-gray-600 mt-0.5 break-words">${escapeHtml(n.message || '')}</div>
						</div>
						<div class="shrink-0 text-xs text-gray-500 whitespace-nowrap">${formatTime(n.created_at)}</div>
					</div>
					<div class="mt-2 flex items-center flex-wrap gap-2">
						${n.type === 'order_status' ? `<a href="/user/orders" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border text-xs text-gray-700 hover:bg-gray-50"><i class="fas fa-file-invoice"></i> Xem đơn hàng</a>` : ''}
						${n.type === 'test_drive' ? `<a href="/test-drives" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border text-xs text-gray-700 hover:bg-gray-50"><i class="fas fa-car-side"></i> Xem lái thử</a>` : ''}
						${n.type === 'service_appointment' ? `<a href="/service-appointments" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border text-xs text-gray-700 hover:bg-gray-50"><i class="fas fa-tools"></i> Xem bảo dưỡng</a>` : ''}
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
	function pad2(n){ return String(n).padStart(2,'0'); }
	function formatTime(iso){
		try{
			const d = new Date(iso);
			const dd = pad2(d.getDate());
			const mm = pad2(d.getMonth() + 1);
			const yyyy = d.getFullYear();
			const hh = pad2(d.getHours());
			const mi = pad2(d.getMinutes());
			return `${dd}/${mm}/${yyyy} ${hh}:${mi}`;
		}catch{ return ''; }
	}

	function updateControls(hasItems, moreAvailable){
		if (!btnMore || !noMoreEl) return;
		if (!hasItems){
			btnMore.classList.add('hidden');
			btnMore.style.display = 'none';
			btnMore.disabled = true;
			noMoreEl.classList.add('hidden');
			noMoreEl.style.display = 'none';
			return;
		}
		if (moreAvailable){
			btnMore.classList.remove('hidden');
			btnMore.style.display = '';
			btnMore.disabled = false;
			noMoreEl.classList.add('hidden');
			noMoreEl.style.display = 'none';
		} else {
			btnMore.classList.add('hidden');
			btnMore.style.display = 'none';
			btnMore.disabled = true;
			noMoreEl.classList.remove('hidden');
			noMoreEl.style.display = 'inline-block';
		}
	}

	function showEmptyState(){
		listEl.innerHTML = `
			<div class="p-10 sm:p-14 text-center">
				<div class="w-16 h-16 mx-auto mb-5 bg-gray-100 rounded-full flex items-center justify-center">
					<i class="fas fa-bell-slash text-2xl text-gray-400"></i>
				</div>
				<h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-2">Chưa có thông báo</h3>
				<p class="text-sm sm:text-base text-gray-500 max-w-xl mx-auto">Bạn sẽ nhận được thông báo khi có cập nhật về đơn hàng, lịch lái thử, bảo dưỡng và các hoạt động khác.</p>
			</div>
		`;
		emptyEl && (emptyEl.style.display = 'none');
		hasMore = false;
		updateControls(false, false);
	}

	function checkAndShowEmptyState(){
		const remainingItems = listEl.querySelectorAll('[data-notif-id]');
		const hasEmptyState = listEl.querySelector('.fa-bell-slash');
		if (remainingItems.length === 0 && !hasEmptyState) {
			showEmptyState();
		}
	}

	async function load(pageToLoad){
		if (loading || !hasMore) return;
		loading = true;
		let refreshHTML;
		if (pageToLoad === 1 && btnRefresh){
			refreshHTML = btnRefresh.innerHTML;
			btnRefresh.disabled = true;
			btnRefresh.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang làm mới';
		}
		let moreHTML;
		if (pageToLoad > 1 && btnMore && !btnMore.classList.contains('hidden')){
			moreHTML = btnMore.innerHTML;
			btnMore.disabled = true;
			btnMore.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tải...';
		}
		const hadItemsBefore = !!listEl.querySelector('[data-notif-id]');
		try {
			const res = await fetch(`{{ route('notifications.index') }}?page=${pageToLoad}`, { headers: { 'X-Requested-With':'XMLHttpRequest', 'Accept':'application/json' }, cache: 'no-cache' });
			const contentType = res.headers.get('content-type') || '';
			if (res.status === 401 || contentType.indexOf('text/html') !== -1) {
				if (typeof window.showMessage === 'function') window.showMessage('Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.', 'warning');
				return;
			}
			let data; try { data = await res.json(); } catch (_) { data = null; }
			if (!res.ok || !data || typeof data !== 'object' || !('data' in data)) throw new Error('Failed to load notifications');

			const items = data && data.data && Array.isArray(data.data.data) ? data.data.data : (Array.isArray(data && data.data) ? data.data : []);
			const pagination = (data && data.data && typeof data.data === 'object' && !Array.isArray(data.data)) ? data.data : {};
			const currentPage = Number(pagination.current_page || pageToLoad);
			const lastPage = Number(pagination.last_page || currentPage);
			if (pageToLoad === 1 && items.length > 0){ listEl.innerHTML = ''; }

			if (!items.length && pageToLoad === 1){ 
				if (hadItemsBefore) {
					hasMore = false;
					updateControls(true, false);
					return;
				} else {
					showEmptyState();
					return; 
				}
			}
			if (!items.length && pageToLoad > 1){
				hasMore = false;
				updateControls(true, false);
				return;
			}

			const fragment = document.createDocumentFragment();
			items.forEach(n => {
				if (!n || !n.id) return;
				if (listEl.querySelector(`[data-notif-id="${n.id}"]`)) return;
				fragment.appendChild(renderItem(n));
			});
			listEl.appendChild(fragment);
			hasMore = currentPage < lastPage;
			updateControls(true, hasMore);
			page = currentPage;
			if (window.refreshNotifBadge) window.refreshNotifBadge();
		} catch (error) {
			console.error('Error loading notifications:', error);
		} finally {
			loading = false;
			if (pageToLoad === 1 && btnRefresh && refreshHTML){ btnRefresh.disabled = false; btnRefresh.innerHTML = refreshHTML; }
			if (pageToLoad > 1 && btnMore && moreHTML){
				if (hasMore) { btnMore.disabled = false; btnMore.innerHTML = moreHTML; } 
				else { btnMore.disabled = true; btnMore.classList.add('hidden'); btnMore.style.display='none'; }
			}
		}
	}

	btnMore.addEventListener('click', function(){ if (loading || !hasMore) return; load(page + 1); });
	btnRefresh.addEventListener('click', function(){ page = 1; hasMore = true; noMoreEl.classList.add('hidden'); load(1).then(()=> setTimeout(checkAndShowEmptyState, 100)); });

	btnMarkAll.addEventListener('click', async function(){
		try{
			const res = await fetch(`{{ route('notifications.read-all') }}`, { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'{{ csrf_token() }}' } });
			if (res.ok){
				listEl.querySelectorAll('.btn-read').forEach(b=>{ b.setAttribute('disabled','disabled'); b.classList.add('text-gray-400'); b.classList.remove('text-amber-700'); });
				listEl.querySelectorAll('.w-10.h-10.rounded-full').forEach(icon=>{ icon.classList.remove('bg-amber-50','text-amber-600'); icon.classList.add('bg-gray-100','text-gray-400'); });
				listEl.querySelectorAll('.text-sm.font-semibold, .text-base.font-semibold').forEach(title=>{ title.classList.remove('text-gray-900'); title.classList.add('text-gray-700'); });
				if (window.refreshNotifBadge) window.refreshNotifBadge();
				if (typeof window.showMessage==='function') window.showMessage('Đã đánh dấu tất cả là đã đọc','success');
			}
		}catch{}
	});

	btnDeleteAll.addEventListener('click', async function(){
		const confirmDialog = () => new Promise(resolve => {
			const existing = document.querySelector('.fast-confirm-dialog');
			if (existing) existing.remove();
			const wrapper = document.createElement('div');
			wrapper.className = 'fast-confirm-dialog fixed inset-0 z-[100000] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4';
			wrapper.innerHTML = `
				<div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-200 scale-95 opacity-0">
					<div class="p-6">
						<div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4"><i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i></div>
						<h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Xóa tất cả thông báo?</h3>
						<p class="text-gray-600 text-center mb-6">Hành động không thể hoàn tác. Tất cả thông báo sẽ bị xóa.</p>
						<div class="flex space-x-3">
							<button class="fast-cancel flex-1 px-4 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200">Hủy bỏ</button>
							<button class="fast-confirm flex-1 px-4 py-2.5 text-white bg-red-600 hover:bg-red-700 rounded-lg font-medium transition-colors duration-200">Xóa tất cả</button>
						</div>
					</div>
				</div>`;
			document.body.appendChild(wrapper);
			const panel = wrapper.firstElementChild;
			requestAnimationFrame(()=>{ panel.style.transform='scale(1)'; panel.style.opacity='1'; });
			wrapper.addEventListener('click', (ev)=>{ if (ev.target === wrapper){ wrapper.remove(); resolve(false); } });
			wrapper.querySelector('.fast-cancel').addEventListener('click', ()=>{ wrapper.remove(); resolve(false); });
			wrapper.querySelector('.fast-confirm').addEventListener('click', ()=>{ wrapper.remove(); resolve(true); });
		});

		const ok = await confirmDialog();
		if (!ok) return;
		btnDeleteAll.disabled = true;
		btnDeleteAll.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xóa...';
		try{
			const res = await fetch(`{{ route('notifications.delete-all') }}`, { method:'DELETE', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json' } });
			if (!res.ok) throw new Error('fail');
			listEl.innerHTML = '';
			showEmptyState();
			page = 1; hasMore = false;
			if (window.refreshNotifBadge) window.refreshNotifBadge();
			if (typeof window.showMessage==='function') window.showMessage('Đã xóa tất cả thông báo','success');
		} catch(e){
			if (typeof window.showMessage==='function') window.showMessage('Không thể xóa tất cả. Vui lòng thử lại.','error');
		} finally {
			btnDeleteAll.disabled = false;
			btnDeleteAll.innerHTML = '<i class="fas fa-trash"></i> Xóa tất cả';
		}
	});

	document.addEventListener('click', async function(e){
		const readBtn = e.target.closest('.btn-read');
		const delBtn = e.target.closest('.btn-del');
		if (readBtn){
			const id = readBtn.getAttribute('data-id');
			const res = await fetch(`/notifications/${id}/read`, { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'{{ csrf_token() }}' } });
			if (res.ok){ 
				readBtn.setAttribute('disabled','disabled'); 
				readBtn.classList.add('text-gray-400'); 
				readBtn.classList.remove('text-amber-700'); 
				const row = readBtn.closest('[data-notif-id]');
				if (row){
					const icon = row.querySelector('.w-10.h-10.rounded-full');
					if (icon){ icon.classList.remove('bg-amber-50','text-amber-600'); icon.classList.add('bg-gray-100','text-gray-400'); }
					const title = row.querySelector('.text-sm.font-semibold, .text-base.font-semibold');
					if (title){ title.classList.remove('text-gray-900'); title.classList.add('text-gray-700'); }
				}
				if (window.refreshNotifBadge) window.refreshNotifBadge(); 
			}
		}
		if (delBtn){
			const id = delBtn.getAttribute('data-id');
			const res = await fetch(`/notifications/${id}`, { method:'DELETE', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'{{ csrf_token() }}' } });
			if (res.ok){ 
				const row = delBtn.closest('[data-notif-id]');
				if (row) row.remove();
				checkAndShowEmptyState();
				const remaining = listEl.querySelectorAll('[data-notif-id]').length;
				if (remaining === 0){ updateControls(false, false); }
			}
		}
	});

	(function(){
		const initialCount = Number(document.getElementById('notif-list').getAttribute('data-initial-count') || '0');
		const initialHasMore = document.getElementById('notif-list').getAttribute('data-initial-has-more') === '1';
		hasMore = initialHasMore;
		if (initialCount === 0){ hasMore = false; updateControls(false, false); } else { updateControls(true, hasMore); }
	})();
})();
</script>

@endsection
