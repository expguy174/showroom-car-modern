<?php $__env->startSection('title', 'Đặt lịch bảo dưỡng'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
    <div class="flex items-start sm:items-center justify-between gap-3 mb-4 sm:mb-6">
        <div class="min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
                <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight truncate">Đặt lịch bảo dưỡng</h1>
            </div>
            <div class="text-xs sm:text-sm text-gray-500 mt-1">Chọn showroom, xe và thời gian phù hợp</div>
        </div>
        <a href="<?php echo e(route('user.service-appointments.index')); ?>" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <form id="sa-create-form" method="POST" action="<?php echo e(route('user.service-appointments.store')); ?>" novalidate>
                    <?php echo csrf_field(); ?>
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Thông tin đặt lịch</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <!-- Showroom & Thời gian -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Showroom <span class="text-red-500">*</span></label>
                    <select name="showroom_id" id="showroom_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                        <option value="">-- Chọn showroom --</option>
                        <?php $__currentLoopData = $showrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s->id); ?>" <?php if(old('showroom_id')==$s->id): echo 'selected'; endif; ?>><?php echo e($s->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['showroom_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ngày hẹn <span class="text-red-500">*</span></label>
                    <input type="date" name="appointment_date" id="appointment_date" value="<?php echo e(old('appointment_date')); ?>" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                    <?php $__errorArgs = ['appointment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Giờ hẹn <span class="text-red-500">*</span></label>
                    <input type="time" name="appointment_time" id="appointment_time" value="<?php echo e(old('appointment_time')); ?>" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                    <?php $__errorArgs = ['appointment_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Xe -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Xe của bạn <span class="text-red-500">*</span></label>
                    <select name="car_variant_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                        <option value="">-- Chọn xe --</option>
                        <?php $__currentLoopData = $carVariants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($v->id); ?>" <?php if(old('car_variant_id')==$v->id): echo 'selected'; endif; ?>>
                                <?php echo e($v->carModel->carBrand->name); ?> <?php echo e($v->carModel->name); ?> - <?php echo e($v->name ?? 'Phiên bản'); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['car_variant_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Biển số xe</label>
                    <input type="text" name="vehicle_registration" value="<?php echo e(old('vehicle_registration')); ?>" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" placeholder="VD: 30A-123.45">
                    <?php $__errorArgs = ['vehicle_registration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Số km hiện tại</label>
                    <input type="number" step="1" name="current_mileage" value="<?php echo e(old('current_mileage')); ?>" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" placeholder="VD: 25000">
                    <?php $__errorArgs = ['current_mileage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Dịch vụ -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chọn dịch vụ <span class="text-red-500">*</span></label>
                    <select name="service_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                        <option value="">-- Chọn dịch vụ --</option>
                        <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($service->id); ?>" 
                                    <?php if(old('service_id', $selectedService?->id) == $service->id): echo 'selected'; endif; ?>
                                    data-price="<?php echo e($service->price); ?>" data-duration="<?php echo e($service->duration_minutes); ?>">
                                <?php echo e($service->name); ?> 
                                <?php if($service->price > 0): ?>
                                    - <?php echo e(number_format($service->price, 0, ',', '.')); ?> VNĐ
                                <?php else: ?>
                                    - Miễn phí
                                <?php endif; ?>
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['service_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chi phí ước tính</label>
                    <input type="number" step="1000" name="estimated_cost" value="<?php echo e(old('estimated_cost')); ?>" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" placeholder="0" readonly>
                    <?php $__errorArgs = ['estimated_cost'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="text-xs text-gray-500 mt-1">Tự động điền</div>
                </div>

                <?php if($selectedService): ?>
                <div class="md:col-span-2 lg:col-span-3">
                    <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-info text-blue-600 text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-blue-900"><?php echo e($selectedService->name); ?></h4>
                                <p class="text-sm text-blue-700 mt-1"><?php echo e($selectedService->description); ?></p>
                                <div class="flex items-center gap-4 mt-2 text-sm text-blue-600">
                                    <span><i class="fas fa-tag mr-1"></i>
                                        <?php if($selectedService->price > 0): ?>
                                            <?php echo e(number_format($selectedService->price, 0, ',', '.')); ?> đ
                                        <?php else: ?>
                                            Miễn phí
                                        <?php endif; ?>
                                    </span>
                                    <?php if($selectedService->duration_minutes): ?>
                                    <span><i class="fas fa-clock mr-1"></i>
                                        <?php if($selectedService->duration_minutes < 60): ?>
                                            <?php echo e($selectedService->duration_minutes); ?> phút
                                        <?php elseif($selectedService->duration_minutes < 1440): ?>
                                            <?php echo e(round($selectedService->duration_minutes / 60)); ?> giờ
                                        <?php else: ?>
                                            <?php echo e(round($selectedService->duration_minutes / 1440)); ?> ngày
                                        <?php endif; ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="flex items-center">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="is_warranty_work" value="1" class="rounded border-gray-300" <?php if(old('is_warranty_work')): echo 'checked'; endif; ?>>
                        Là công việc bảo hành
                    </label>
                </div>

                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Yêu cầu thêm (tùy chọn)</label>
                    <textarea name="requested_services" rows="3" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" placeholder="VD: Kiểm tra thêm lốp xe, thay bóng đèn..."><?php echo e(old('requested_services')); ?></textarea>
                    <?php $__errorArgs = ['requested_services'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả chi tiết</label>
                    <textarea name="service_description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" placeholder="Thông tin thêm giúp kỹ thuật viên chuẩn bị tốt hơn (tùy chọn)"><?php echo e(old('service_description')); ?></textarea>
                    <?php $__errorArgs = ['service_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="md:col-span-2 lg:col-span-3">
                    <button type="submit" class="w-full bg-indigo-600 text-white py-3 px-6 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold text-lg transition-colors">
                        <i class="fas fa-calendar-plus mr-2"></i> Đặt lịch bảo dưỡng
                    </button>
                </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
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
                        <i class="fas fa-clock text-gray-400 mt-0.5 text-xs"></i>
                        Thời gian xác nhận có thể mất đến 30 phút.
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-user-clock text-gray-400 mt-0.5 text-xs"></i>
                        Vui lòng đến sớm 10 phút để làm thủ tục.
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-calendar-alt text-gray-400 mt-0.5 text-xs"></i>
                        Nếu cần đổi lịch, bạn có thể thực hiện trong mục lịch bảo dưỡng.
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-shield-alt text-gray-400 mt-0.5 text-xs"></i>
                        Mang theo giấy tờ xe và CMND khi đến showroom.
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
  // AJAX submit create form
  const form = document.getElementById('sa-create-form');
  if (form){
    // mini summary binding removed with header change
    form.addEventListener('submit', async function(e){
      e.preventDefault();
      const btn = form.querySelector('button[type="submit"]');
      if (btn){ btn.disabled = true; btn.classList.add('opacity-60'); }
      // Client-side validation theo thứ tự chính xác với business logic
      try {
        // 1. Check showroom
        const showroomEl = form.querySelector('[name="showroom_id"]');
        if (!showroomEl || !showroomEl.value.trim()) {
          if (typeof window.showMessage === 'function') window.showMessage('Vui lòng chọn showroom.', 'error');
          if (showroomEl) showroomEl.focus();
          throw new Error('validation-stop');
        }
        
        // 2. Check ngày hẹn
        const dateEl = form.querySelector('[name="appointment_date"]');
        if (!dateEl || !dateEl.value.trim()) {
          if (typeof window.showMessage === 'function') window.showMessage('Vui lòng chọn ngày hẹn.', 'error');
          if (dateEl) dateEl.focus();
          throw new Error('validation-stop');
        }
        
        // 2.1. Check ngày phải sau hôm nay
        try {
          const today = new Date(); today.setHours(0,0,0,0);
          const selectedDate = new Date(dateEl.value); selectedDate.setHours(0,0,0,0);
          if (selectedDate <= today) {
            if (typeof window.showMessage === 'function') window.showMessage('Ngày hẹn phải sau hôm nay.', 'error');
            dateEl.focus();
            throw new Error('validation-stop');
          }
          
          // 2.2. Check cuối tuần
          const dayOfWeek = selectedDate.getDay();
          if (dayOfWeek === 0 || dayOfWeek === 6) {
            if (typeof window.showMessage === 'function') window.showMessage('Không thể đặt lịch vào cuối tuần.', 'error');
            dateEl.focus();
            throw new Error('validation-stop');
          }
        } catch(e) {
          if (e.message === 'validation-stop') throw e;
        }
        
        // 3. Check giờ hẹn
        const timeEl = form.querySelector('[name="appointment_time"]');
        if (!timeEl || !timeEl.value.trim()) {
          if (typeof window.showMessage === 'function') window.showMessage('Vui lòng chọn giờ hẹn.', 'error');
          if (timeEl) timeEl.focus();
          throw new Error('validation-stop');
        }
        
        // 3.1. Check giờ làm việc
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
        
        // 4. Check xe
        const carEl = form.querySelector('[name="car_variant_id"]');
        if (!carEl || !carEl.value.trim()) {
          if (typeof window.showMessage === 'function') window.showMessage('Vui lòng chọn xe.', 'error');
          if (carEl) carEl.focus();
          throw new Error('validation-stop');
        }
        
        // 5. Check dịch vụ
        const serviceEl = form.querySelector('[name="service_id"]');
        if (!serviceEl || !serviceEl.value.trim()) {
          if (typeof window.showMessage === 'function') window.showMessage('Vui lòng chọn dịch vụ.', 'error');
          if (serviceEl) serviceEl.focus();
          throw new Error('validation-stop');
        }
        
      } catch (err) {
        if (btn){ btn.disabled = false; btn.classList.remove('opacity-60'); }
        if (err && err.message === 'validation-stop') return; // stop submit
      }
      try {
        const fd = new FormData(form);
        const res = await fetch(form.getAttribute('action'), { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept':'application/json' }, body: fd });
        const data = await res.json().catch(()=>({}));
        if (res.ok && data && data.success){
          if (typeof window.showMessage === 'function') window.showMessage(data.message || 'Đặt lịch bảo dưỡng thành công!', 'success');
          const go = () => {
            if (data.redirect){ window.location.href = data.redirect; }
            else if (data.id){ window.location.href = `<?php echo e(url('service-appointments')); ?>/${data.id}`; }
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
        if (btn){ btn.disabled = false; btn.classList.remove('opacity-60'); }
      }
    });
  }
  const btn = document.getElementById('btn-check-slots');
  const out = document.getElementById('slot-results');
  if (!btn || !out) return;
  btn.addEventListener('click', async function(){
    const showroom = document.getElementById('showroom_id')?.value;
    const date = document.getElementById('appointment_date')?.value;
    if (!showroom || !date){
      if (typeof window.showMessage === 'function') window.showMessage('Vui lòng chọn showroom và ngày.', 'error');
      return;
    }
    out.innerHTML = '<div class="text-gray-600"><i class="fas fa-spinner fa-spin"></i> Đang kiểm tra...</div>';
    try {
      const res = await fetch("<?php echo e(route('user.service-appointments.check-availability')); ?>", { method:'POST', headers:{ 'Content-Type':'application/json', 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify({ showroom_id: showroom, date }) });
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

// Auto-fill estimated cost when service is selected
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.querySelector('select[name="service_id"]');
    const estimatedCostInput = document.querySelector('input[name="estimated_cost"]');
    
    if (serviceSelect && estimatedCostInput) {
        serviceSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.dataset.price !== undefined) {
                const price = parseFloat(selectedOption.dataset.price) || 0;
                estimatedCostInput.value = price;
            } else {
                estimatedCostInput.value = '';
            }
        });
        
        // Trigger change event if service is pre-selected
        if (serviceSelect.value) {
            serviceSelect.dispatchEvent(new Event('change'));
        }
    }
});
</script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/user/service-appointments/create.blade.php ENDPATH**/ ?>