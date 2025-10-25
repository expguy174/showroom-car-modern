<?php $__env->startSection('title', 'Lịch bảo dưỡng'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
    <?php ( $__toastKind = session('toast.kind') ); ?>
    <?php ( $__toastMsg  = session('toast.msg') ); ?>
    <?php if($__toastKind && $__toastMsg): ?>
        <span id="toast-payload" data-kind="<?php echo e($__toastKind); ?>" data-msg="<?php echo e($__toastMsg); ?>" style="display:none"></span>
    <?php endif; ?>
    <div class="flex items-center justify-between mb-4 sm:mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight">Lịch bảo dưỡng</h1>
            <p class="text-sm sm:text-base text-gray-500 mt-1">Quản lý lịch bảo dưỡng và sửa chữa xe</p>
        </div>
        <div class="hidden sm:flex items-center gap-2">
            <a href="<?php echo e(route('user.service-appointments.create')); ?>" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 text-sm font-semibold">
                <i class="fas fa-plus"></i> Đặt lịch mới
            </a>
        </div>
    </div>

    <form id="sa-filter-form" action="<?php echo e(route('user.service-appointments.index')); ?>" method="get" class="mb-4 sm:mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                    <div class="relative">
                        <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Mã lịch, xe, showroom..." class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 pr-10">
                        <span class="absolute inset-y-0 right-3 flex items-center text-gray-400"><i class="fas fa-search"></i></span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                    <?php ($saStatuses=['scheduled'=>'Đã lên lịch','confirmed'=>'Đã xác nhận','in_progress'=>'Đang thực hiện','completed'=>'Hoàn thành','cancelled'=>'Đã hủy']); ?>
                    <select name="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Tất cả</option>
                        <?php $__currentLoopData = $saStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php if(request('status')===$key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dịch vụ</label>
                    <select name="service_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Tất cả dịch vụ</option>
                        <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($service->id); ?>" <?php if(request('service_id')==$service->id): echo 'selected'; endif; ?>><?php echo e($service->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
        </div>
    </form>

    <div id="sa-summary-host">
        <?php echo $__env->make('user.service-appointments.partials.summary', ['paginator' => $appointments->withQueryString()], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <div id="sa-list-wrapper">
        <?php echo $__env->make('user.service-appointments.partials.list', ['appointments' => $appointments], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <div id="sa-pagination" class="mt-6">
        <?php echo $__env->make('components.pagination-modern', ['paginator' => $appointments->withQueryString()], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
	const form = document.getElementById('sa-filter-form');
	const wrapper = document.getElementById('sa-list-wrapper');
	const pagination = document.getElementById('sa-pagination');
	if (!form || !wrapper) return;

	function scrollToTop(){
		try{
			const nav = document.getElementById('main-nav');
			const headerOffset = (nav && typeof nav.offsetHeight === 'number') ? (nav.offsetHeight + 12) : 80;
			const y = (form.getBoundingClientRect().top + window.scrollY) - headerOffset;
			window.scrollTo({ top: Math.max(0, y), behavior: 'smooth' });
		}catch{}
	}

	function submitAjax(url){
		const params = new URLSearchParams(new FormData(form));
		const fetchUrl = url || (form.getAttribute('action') + '?' + params.toString());
		scrollToTop();
		wrapper.innerHTML = '<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center text-gray-500"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div>';
		fetch(fetchUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
			.then(r => r.json())
			.then(res => {
				if (res && res.html !== undefined) {
					wrapper.innerHTML = res.html || '';
					if (pagination) pagination.innerHTML = res.pagination || '';
					try{
						const summaryHtml = res.summary || '';
						if (summaryHtml){
							const host = document.getElementById('sa-summary-host');
							if (host){ host.innerHTML = summaryHtml; }
						}
					}catch{}
					bindPagination();
					scrollToTop();
				} else {
					wrapper.innerHTML = '<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center text-gray-500">Không tải được dữ liệu</div>';
				}
			})
			.catch(() => {
				wrapper.innerHTML = '<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center text-gray-500">Có lỗi xảy ra</div>';
			});
	}

	function bindPagination(){
		if (!pagination) return;
		pagination.querySelectorAll('a').forEach(a => {
			a.addEventListener('click', function(e){
				e.preventDefault();
				const url = this.getAttribute('href');
				if (url) submitAjax(url);
			});
		});
	}

	function debounce(fn, wait){ let t; return function(){ const args = arguments, ctx = this; clearTimeout(t); t = setTimeout(function(){ fn.apply(ctx, args); }, wait); }; }

	form.addEventListener('submit', function(e){ e.preventDefault(); submitAjax(); });
	form.querySelectorAll('select').forEach(s => s.addEventListener('change', function(){ submitAjax(); }));
	const qInput = form.querySelector('input[name="q"]');
	if (qInput){ const debounced = debounce(function(){ submitAjax(); }, 300); qInput.addEventListener('input', debounced); }

	bindPagination();
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/user/service-appointments/index.blade.php ENDPATH**/ ?>