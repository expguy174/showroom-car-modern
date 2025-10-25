<?php $__env->startSection('title', 'Chi tiết lịch lái thử'); ?>
<?php $__env->startSection('content'); ?>

<div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
    <?php ( $__toastKind = request('toast') ?: (session('success') ? 'success' : (session('error') ? 'error' : null)) ); ?>
    <?php ( $__toastMsg  = request('msg') ?: (session('success') ?: session('error')) ); ?>
    <?php if($__toastKind && $__toastMsg): ?>
        <span id="toast-payload" data-kind="<?php echo e($__toastKind); ?>" data-msg="<?php echo e($__toastMsg); ?>" style="display:none"></span>
    <?php endif; ?>
	<div class="flex items-start sm:items-center justify-between gap-3 mb-4 sm:mb-6">
		<div class="min-w-0">
			<div class="flex items-center gap-2 flex-wrap">
				<h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight truncate">Lịch lái thử #<?php echo e($testDrive->test_drive_number ?? $testDrive->id); ?></h1>
				<span class="px-2 py-0.5 rounded-full text-xs whitespace-nowrap inline-flex items-center 
					<?php switch($testDrive->status ?? 'pending'):
						case ('pending'): ?> bg-yellow-100 text-yellow-800 <?php break; ?>
						<?php case ('confirmed'): ?> bg-blue-100 text-blue-800 <?php break; ?>
						<?php case ('completed'): ?> bg-green-100 text-green-800 <?php break; ?>
						<?php case ('cancelled'): ?> bg-red-100 text-red-800 <?php break; ?>
						<?php default: ?> bg-gray-100 text-gray-800
					<?php endswitch; ?>
				" data-role="status-badge">
					<?php switch($testDrive->status ?? 'pending'):
						case ('pending'): ?> Chờ xác nhận <?php break; ?>
						<?php case ('confirmed'): ?> Đã xác nhận <?php break; ?>
						<?php case ('completed'): ?> Hoàn thành <?php break; ?>
						<?php case ('cancelled'): ?> Đã hủy <?php break; ?>
						<?php default: ?> <?php echo e(ucfirst($testDrive->status ?? 'Pending')); ?>

					<?php endswitch; ?>
				</span>
			</div>
			<div class="text-xs sm:text-sm text-gray-500 mt-1"><?php echo e(optional($testDrive->preferred_date)->format('d/m/Y')); ?> <?php echo e(is_string($testDrive->preferred_time) ? substr($testDrive->preferred_time,0,5) : optional($testDrive->preferred_time)->format('H:i')); ?> • <?php echo e($testDrive->showroom->name ?? 'Chưa chọn showroom'); ?></div>
		</div>
		<a href="<?php echo e(route('test-drives.index')); ?>" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold shrink-0">
			<i class="fas fa-arrow-left"></i> Quay lại
		</a>
	</div>

	<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
		<!-- Left: Primary info -->
		<div class="lg:col-span-2 space-y-4 sm:space-y-6">
			<!-- Thông tin chính -->
			<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
				<div class="px-4 sm:px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-indigo-50 flex items-center justify-between">
					<h2 class="text-lg font-bold">Chi tiết lịch lái thử</h2>
					<div class="text-xs text-gray-500">Cập nhật lúc <?php echo e($testDrive->updated_at?->format('d/m/Y H:i')); ?></div>
				</div>
				<div class="p-4 sm:p-6">
					<!-- Thông tin xe và lịch hẹn -->
					<div class="flex items-start gap-4 mb-6">
						<div class="w-40 h-24 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0">
							<img src="<?php echo e(optional(optional($testDrive->carVariant)->images->first())->image_url ?: 'https://placehold.co/160x100/EEF2FF/3730A3?text=Car'); ?>" alt="thumb" class="w-full h-full object-cover" />
						</div>
						<div class="min-w-0 flex-1">
							<div class="text-gray-700 text-sm">Mẫu xe</div>
							<div class="font-semibold text-gray-900 truncate mb-3">
								<a href="<?php echo e(route('car-variants.show', $testDrive->car_variant_id)); ?>" class="hover:text-indigo-700">
									<?php echo e(optional(optional($testDrive->carVariant)->carModel)->name); ?> <?php echo e(optional($testDrive->carVariant)->name ?? ''); ?>

								</a>
							</div>
							<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
								<div class="flex items-center gap-2"><i class="far fa-calendar text-indigo-600"></i><span>Ngày:</span><span class="font-medium"><?php echo e(optional($testDrive->preferred_date)->format('d/m/Y')); ?></span></div>
								<div class="flex items-center gap-2"><i class="far fa-clock text-indigo-600"></i><span>Giờ:</span><span class="font-medium"><?php echo e(is_string($testDrive->preferred_time) ? substr($testDrive->preferred_time,0,5) : optional($testDrive->preferred_time)->format('H:i')); ?></span></div>
								<?php if($testDrive->showroom): ?>
									<div class="flex items-center gap-2"><i class="fas fa-store text-indigo-600"></i><span>Showroom:</span><span class="font-medium"><?php echo e($testDrive->showroom->name); ?></span></div>
								<?php endif; ?>
								<div class="flex items-center gap-2"><i class="fas fa-hourglass-half text-indigo-600"></i><span>Thời lượng:</span><span class="font-medium"><?php echo e($testDrive->duration_minutes ? ($testDrive->duration_minutes.' phút') : '—'); ?></span></div>
							</div>
						</div>
					</div>

					<!-- Chi tiết bổ sung -->
					<div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-100">
						<div class="space-y-4">
							<h4 class="font-semibold text-gray-900 flex items-center gap-2">
								<i class="fas fa-info-circle text-indigo-600"></i>
								Thông tin cơ bản
							</h4>
							<dl class="space-y-2 text-sm">
								<div class="flex justify-between"><dt class="text-gray-500">Mã lịch:</dt><dd class="font-medium"><?php echo e($testDrive->test_drive_number ?? ('TD-'.($testDrive->id))); ?></dd></div>
								<div class="flex justify-between"><dt class="text-gray-500">Trạng thái:</dt><dd><span data-role="summary-status-badge" class="px-2 py-0.5 rounded-full text-xs 
									<?php switch($testDrive->status ?? 'pending'):
										case ('pending'): ?> bg-yellow-100 text-yellow-800 <?php break; ?>
										<?php case ('confirmed'): ?> bg-blue-100 text-blue-800 <?php break; ?>
										<?php case ('completed'): ?> bg-green-100 text-green-800 <?php break; ?>
										<?php case ('cancelled'): ?> bg-red-100 text-red-800 <?php break; ?>
										<?php default: ?> bg-gray-100 text-gray-800
									<?php endswitch; ?>
								">
									<?php switch($testDrive->status ?? 'pending'):
										case ('pending'): ?> Chờ xác nhận <?php break; ?>
										<?php case ('confirmed'): ?> Đã xác nhận <?php break; ?>
										<?php case ('completed'): ?> Hoàn thành <?php break; ?>
										<?php case ('cancelled'): ?> Đã hủy <?php break; ?>
										<?php default: ?> <?php echo e(ucfirst($testDrive->status ?? 'Pending')); ?>

									<?php endswitch; ?>
								</span></dd></div>
								<div class="flex justify-between"><dt class="text-gray-500">Địa điểm:</dt><dd class="font-medium"><?php echo e($testDrive->location ?: '—'); ?></dd></div>
								<div class="flex justify-between"><dt class="text-gray-500">Loại lịch:</dt><dd class="font-medium">
									<?php ($typeMap=['individual'=>'Cá nhân','group'=>'Nhóm','virtual'=>'Trực tuyến']); ?>
									<?php echo e($typeMap[$testDrive->test_drive_type] ?? $testDrive->test_drive_type); ?>

								</dd></div>
							</dl>
						</div>

						<div class="space-y-4">
							<h4 class="font-semibold text-gray-900 flex items-center gap-2">
								<i class="fas fa-user-check text-indigo-600"></i>
								Kinh nghiệm lái xe
							</h4>
							<dl class="space-y-2 text-sm">
								<?php ($expMap=[''=>'—','beginner'=>'Mới bắt đầu','intermediate'=>'Trung bình','advanced'=>'Nhiều kinh nghiệm']); ?>
								<div class="flex justify-between"><dt class="text-gray-500">Mức độ:</dt><dd class="font-medium"><?php echo e($expMap[$testDrive->experience_level ?? ''] ?? '—'); ?></dd></div>
								<div class="flex justify-between"><dt class="text-gray-500">Đã từng lái tương tự:</dt><dd class="font-medium"><?php echo e($testDrive->has_experience ? 'Có' : 'Không'); ?></dd></div>
								<?php if(!empty($testDrive->confirmed_at)): ?>
									<div class="flex justify-between"><dt class="text-gray-500">Xác nhận lúc:</dt><dd class="font-medium"><?php echo e(optional($testDrive->confirmed_at)->format('d/m/Y H:i')); ?></dd></div>
								<?php endif; ?>
								<?php if(!empty($testDrive->completed_at)): ?>
									<div class="flex justify-between"><dt class="text-gray-500">Hoàn thành lúc:</dt><dd class="font-medium"><?php echo e(optional($testDrive->completed_at)->format('d/m/Y H:i')); ?></dd></div>
								<?php endif; ?>
							</dl>
						</div>
					</div>

					<!-- Ghi chú và yêu cầu -->
					<?php if(!empty($testDrive->notes) || !empty($testDrive->special_requirements)): ?>
						<div class="pt-6 border-t border-gray-100 mt-6">
							<h4 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
								<i class="fas fa-sticky-note text-indigo-600"></i>
								Ghi chú & Yêu cầu
							</h4>
							<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
								<?php if(!empty($testDrive->notes)): ?>
									<div>
										<div class="text-gray-700 text-sm mb-2 font-medium">Ghi chú</div>
										<div class="text-sm text-gray-800 bg-gray-50 rounded-lg p-3 whitespace-pre-line"><?php echo e($testDrive->notes); ?></div>
									</div>
								<?php endif; ?>
								<?php if(!empty($testDrive->special_requirements)): ?>
									<div>
										<div class="text-gray-700 text-sm mb-2 font-medium">Yêu cầu đặc biệt</div>
										<div class="text-sm text-gray-800 bg-gray-50 rounded-lg p-3 whitespace-pre-line"><?php echo e($testDrive->special_requirements); ?></div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
	  </div>

		<!-- Right: meta -->
		<div class="space-y-4 sm:space-y-6">
			<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
				<h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Hành động</h2>
				<?php if(in_array($testDrive->status, ['pending','confirmed'])): ?>
					<div class="flex flex-col gap-2">
						<a href="<?php echo e(route('test-drives.edit', $testDrive)); ?>" class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-amber-500 text-white hover:bg-amber-600 font-semibold text-sm"><i class="fas fa-edit"></i> Sửa</a>
						<button type="button" class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-rose-600 text-white hover:bg-rose-700 font-semibold text-sm js-cancel-one" data-id="<?php echo e($testDrive->id); ?>" data-cancel-url="<?php echo e(url('/test-drives/'.$testDrive->id.'/cancel')); ?>"><i class="fas fa-times"></i> Hủy lịch</button>
					</div>
				<?php else: ?>
					<div class="text-sm text-gray-600">Lịch hẹn đã ở trạng thái <?php echo e($testDrive->status_text); ?>.</div>
				<?php endif; ?>
			</div>
			<?php if($testDrive->status === 'completed'): ?>
			<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
				<h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3">Đánh giá trải nghiệm</h2>
				<form id="ratingForm" class="space-y-3" data-submitting="0">
					<?php echo csrf_field(); ?>
					<?php ($current = (int)round((float)($testDrive->satisfaction_rating ?? 0))); ?>
					<div class="flex items-center gap-2">
						<div class="flex items-center gap-1 select-none" id="stars">
							<?php for($i=1;$i<=5;$i++): ?>
								<button type="button" data-val="<?php echo e($i); ?>" class="star inline-flex items-center justify-center h-9 w-9 text-2xl <?php echo e($i <= $current ? 'text-amber-400' : 'text-gray-300'); ?>" aria-label="<?php echo e($i); ?> sao">★</button>
							<?php endfor; ?>
							<input type="hidden" name="satisfaction_rating" id="ratingVal" value="<?php echo e($current ?: ''); ?>">
						</div>
						<span class="text-sm text-gray-600" id="ratingCount"><?php echo e($current ?: 0); ?>/5</span>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700 mb-1">Phản hồi</label>
						<textarea name="feedback" class="w-full border rounded-xl px-3 py-2" placeholder="Chia sẻ trải nghiệm lái thử của bạn (tùy chọn)"><?php echo e($testDrive->feedback); ?></textarea>
					</div>
					<button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-lg text-sm">Lưu đánh giá</button>
				</form>
			</div>
			<?php endif; ?>
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
			const res = await fetch(`<?php echo e(route('test-drives.rate', $testDrive)); ?>`, { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>','Accept':'application/json' }, body: fd });
			const data = await res.json().catch(()=>({}));
			if (res.ok && data.success){ if (typeof showMessage==='function') showMessage(data.message,'success'); }
			else { if (typeof showMessage==='function') showMessage(data.message || 'Không thể lưu đánh giá','error'); }
		} catch {} finally {
			form.dataset.submitting='';
		}
	});
})();
</script>
<script>
(function(){
	document.addEventListener('click', async function(e){
		const btn = e.target.closest('.js-cancel-one');
		if (!btn) return;
		e.preventDefault();
		// Modern dialog like index page
		const confirmDialog = () => new Promise(resolve => {
			const existing = document.querySelector('.fast-confirm-dialog');
			if (existing) existing.remove();
			const wrapper = document.createElement('div');
			wrapper.className = 'fast-confirm-dialog fixed inset-0 z-[100000] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4';
			wrapper.innerHTML = `
				<div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-200 scale-95 opacity-0">
					<div class="p-6">
						<div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4"><i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i></div>
						<h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Hủy lịch lái thử?</h3>
						<p class="text-gray-600 text-center mb-6">Bạn có chắc chắn muốn hủy lịch này? Hành động không thể hoàn tác.</p>
						<div class="flex space-x-3">
							<button class="fast-cancel flex-1 px-4 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200">Hủy bỏ</button>
							<button class="fast-confirm flex-1 px-4 py-2.5 text-white bg-red-600 hover:bg-red-700 rounded-lg font-medium transition-colors duration-200">Hủy lịch</button>
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
		const originalHtml = btn.innerHTML;
		btn.disabled = true;
		btn.classList.remove('hover:bg-rose-600');
		btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang hủy...';
		const url = btn.getAttribute('data-cancel-url');
		try{
			const res = await fetch(url, { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>','Accept':'application/json' } });
			const data = await res.json().catch(()=>({}));
			if (res.ok && data && data.success){
				// Update status badges (header and summary) to exact cancelled style as initially rendered
				const topBadge = document.querySelector('[data-role="status-badge"]');
				if (topBadge){
					topBadge.className = 'px-2 py-0.5 rounded-full text-xs whitespace-nowrap inline-flex items-center bg-red-100 text-red-800';
					topBadge.textContent = 'Đã hủy';
				}
				const summaryBadge = document.querySelector('[data-role="summary-status-badge"]');
				if (summaryBadge){
					summaryBadge.className = 'px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-800 whitespace-nowrap';
					summaryBadge.textContent = 'Đã hủy';
				}
				// After success: disable button and remove edit
				const actions = btn.closest('.flex.flex-col.gap-2');
				if (actions){
					const editLink = actions.querySelector('a[href$="/edit"]');
					if (editLink) editLink.remove();
				}
				btn.disabled = true;
				btn.innerHTML = '<i class="fas fa-ban"></i> Đã hủy';
				btn.classList.remove('hover:bg-rose-700');
				// Replace Actions card content with state text
				const actionsCard = btn.closest('.bg-white');
				if (actionsCard){
					const title = actionsCard.querySelector('h3');
					let node = title ? title.nextSibling : null;
					while (node){ const next = node.nextSibling; actionsCard.removeChild(node); node = next; }
					const msg = document.createElement('div');
					msg.className = 'text-sm text-gray-600';
					msg.textContent = 'Lịch hẹn đã ở trạng thái Đã hủy.';
					actionsCard.appendChild(msg);
				}
				if (window.showMessage) window.showMessage(data.message || 'Đã hủy lịch lái thử', 'success');
				if (window.refreshNotifBadge) window.refreshNotifBadge();
				if (window.prependNotifItem) window.prependNotifItem('Đã hủy lịch lái thử', 'Bạn đã hủy một lịch lái thử.');
			} else {
				throw new Error((data && data.message) ? data.message : 'Hủy lịch thất bại');
			}
		} catch(err){
			if (window.showMessage) window.showMessage(err.message || 'Hủy lịch thất bại', 'error');
			btn.disabled = false;
			btn.innerHTML = originalHtml;
			btn.classList.add('hover:bg-rose-600');
		}
	});
})();
</script>

<?php $__env->stopSection(); ?>







<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/user/test-drives/show.blade.php ENDPATH**/ ?>