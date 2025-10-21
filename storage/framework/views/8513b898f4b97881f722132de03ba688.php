<?php $__env->startSection('title', 'Chi tiết lịch lái thử'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <?php if (isset($component)) { $__componentOriginaldb1b157d84f8f63332f3508c9e385c0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldb1b157d84f8f63332f3508c9e385c0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.flash-messages','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.flash-messages'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldb1b157d84f8f63332f3508c9e385c0a)): ?>
<?php $attributes = $__attributesOriginaldb1b157d84f8f63332f3508c9e385c0a; ?>
<?php unset($__attributesOriginaldb1b157d84f8f63332f3508c9e385c0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldb1b157d84f8f63332f3508c9e385c0a)): ?>
<?php $component = $__componentOriginaldb1b157d84f8f63332f3508c9e385c0a; ?>
<?php unset($__componentOriginaldb1b157d84f8f63332f3508c9e385c0a); ?>
<?php endif; ?>
    
    
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <a href="<?php echo e(route('admin.test-drives.index')); ?>" 
               class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Chi tiết lịch lái thử</h1>
                <p class="text-sm text-gray-500 mt-1"><?php echo e($testDrive->test_drive_number); ?></p>
            </div>
        </div>
        
        
        <div id="statusBadgeContainer">
            <?php switch($testDrive->status):
                case ('scheduled'): ?>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock mr-2"></i>
                        Đã đặt lịch
                    </span>
                    <?php break; ?>
                <?php case ('confirmed'): ?>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-2"></i>
                        Đã xác nhận
                    </span>
                    <?php break; ?>
                <?php case ('completed'): ?>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-flag-checkered mr-2"></i>
                        Hoàn thành
                    </span>
                    <?php break; ?>
                <?php case ('cancelled'): ?>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        <i class="fas fa-times-circle mr-2"></i>
                        Đã hủy
                    </span>
                    <?php break; ?>
            <?php endswitch; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user text-blue-600 mr-2"></i>
                    Thông tin khách hàng
                </h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Họ tên</p>
                        <p class="font-medium text-gray-900"><?php echo e($testDrive->customer_name); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-900"><?php echo e($testDrive->customer_email); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Số điện thoại</p>
                        <p class="font-medium text-gray-900"><?php echo e($testDrive->customer_phone); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Địa chỉ khách hàng</p>
                        <p class="font-medium text-gray-900">
                            <?php
                                $defaultAddress = null;
                                if ($testDrive->user && $testDrive->user->addresses && $testDrive->user->addresses->isNotEmpty()) {
                                    $defaultAddress = $testDrive->user->addresses->first();
                                }
                                
                                // Build address parts and filter empty ones
                                if ($defaultAddress) {
                                    $addressParts = array_filter([
                                        $defaultAddress->address,
                                        $defaultAddress->ward,
                                        $defaultAddress->district,
                                        $defaultAddress->city
                                    ], function($part) {
                                        return !empty($part);
                                    });
                                    $fullAddress = implode(', ', $addressParts);
                                }
                            ?>
                            <?php if($defaultAddress && !empty($fullAddress)): ?>
                                <?php echo e($fullAddress); ?>

                            <?php else: ?>
                                <span class="text-gray-400">Chưa có địa chỉ</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-car text-blue-600 mr-2"></i>
                    Chi tiết lái thử
                </h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Xe lái thử</p>
                        <p class="font-medium text-gray-900"><?php echo e($testDrive->car_full_name); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Showroom</p>
                        <p class="font-medium text-gray-900"><?php echo e($testDrive->showroom->name ?? 'N/A'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Ngày hẹn</p>
                        <p class="font-medium text-gray-900"><?php echo e($testDrive->preferred_date ? $testDrive->preferred_date->format('d/m/Y') : '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Giờ hẹn</p>
                        <p class="font-medium text-gray-900"><?php echo e($testDrive->preferred_time ? substr($testDrive->preferred_time, 0, 5) : '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Thời lượng</p>
                        <p class="font-medium text-gray-900"><?php echo e($testDrive->duration_minutes ? $testDrive->duration_minutes . ' phút' : 'Chưa xác định'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Loại lái thử</p>
                        <p class="font-medium text-gray-900">
                            <?php switch($testDrive->test_drive_type):
                                case ('individual'): ?> Cá nhân <?php break; ?>
                                <?php case ('group'): ?> Nhóm <?php break; ?>
                                <?php case ('virtual'): ?> Ảo <?php break; ?>
                                <?php default: ?> <?php echo e($testDrive->test_drive_type); ?>

                            <?php endswitch; ?>
                        </p>
                    </div>
                    <?php if($testDrive->location): ?>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-500">Địa điểm lái thử</p>
                        <p class="font-medium text-gray-900"><?php echo e($testDrive->location); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <?php if($testDrive->notes || $testDrive->special_requirements): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-clipboard text-blue-600 mr-2"></i>
                    Thông tin bổ sung
                </h2>
                <?php if($testDrive->notes): ?>
                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-2">Ghi chú</p>
                    <div class="text-sm text-gray-900 whitespace-pre-line"><?php echo e($testDrive->notes); ?></div>
                </div>
                <?php endif; ?>
                <?php if($testDrive->special_requirements): ?>
                <div>
                    <p class="text-sm text-gray-500 mb-2">Yêu cầu đặc biệt</p>
                    <div class="text-sm text-gray-900 whitespace-pre-line"><?php echo e($testDrive->special_requirements); ?></div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            
            <?php if($testDrive->status == 'completed' && ($testDrive->satisfaction_rating || $testDrive->feedback)): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-star text-yellow-500 mr-2"></i>
                    Đánh giá của khách hàng
                </h2>
                <?php if($testDrive->satisfaction_rating): ?>
                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-2">Mức độ hài lòng</p>
                    <div class="flex items-center">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?php echo e($i <= $testDrive->satisfaction_rating ? 'text-yellow-400' : 'text-gray-300'); ?>"></i>
                        <?php endfor; ?>
                        <span class="ml-2 text-sm text-gray-600">(<?php echo e(number_format($testDrive->satisfaction_rating, 1)); ?>/5)</span>
                    </div>
                </div>
                <?php endif; ?>
                <?php if($testDrive->feedback): ?>
                <div>
                    <p class="text-sm text-gray-500 mb-2">Phản hồi</p>
                    <div class="text-sm text-gray-900 whitespace-pre-line"><?php echo e($testDrive->feedback); ?></div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Thao tác</h3>
                <div class="space-y-2">
                    <?php if($testDrive->status == 'scheduled'): ?>
                        <button type="button" 
                                onclick="openConfirmModal()"
                                class="w-full btn btn-success text-left">
                            <i class="fas fa-check-circle mr-2"></i>
                            Xác nhận lịch lái thử
                        </button>
                    <?php endif; ?>
                    
                    <?php if($testDrive->status == 'confirmed'): ?>
                        <button type="button"
                                onclick="openCompleteModal()"
                                class="w-full btn btn-primary text-left">
                            <i class="fas fa-check-double mr-2"></i>
                            Hoàn thành lịch lái thử
                        </button>
                    <?php endif; ?>
                    
                    <?php if(in_array($testDrive->status, ['scheduled', 'confirmed'])): ?>
                        <button type="button"
                                onclick="openCancelModal()"
                                class="w-full btn btn-danger text-left">
                            <i class="fas fa-ban mr-2"></i>
                            Hủy lịch hẹn
                        </button>
                    <?php endif; ?>

                    <?php if($testDrive->status == 'completed'): ?>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                            <i class="fas fa-check-circle text-green-600 text-2xl mb-2"></i>
                            <p class="text-sm text-green-800 font-medium">Lịch lái thử đã hoàn thành</p>
                            <p class="text-xs text-green-600 mt-1">Không có thao tác khả dụng</p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($testDrive->status == 'cancelled'): ?>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                            <i class="fas fa-times-circle text-red-600 text-2xl mb-2"></i>
                            <p class="text-sm text-red-800 font-medium">Lịch lái thử đã bị hủy</p>
                            <p class="text-xs text-red-600 mt-1">Không có thao tác khả dụng</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Kinh nghiệm lái xe</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500">Có kinh nghiệm</p>
                        <p class="text-sm font-medium text-gray-900"><?php echo e($testDrive->has_experience ? 'Có' : 'Không'); ?></p>
                    </div>
                    <?php if($testDrive->experience_level): ?>
                    <div>
                        <p class="text-xs text-gray-500">Trình độ</p>
                        <p class="text-sm font-medium text-gray-900">
                            <?php switch($testDrive->experience_level):
                                case ('beginner'): ?> Mới học <?php break; ?>
                                <?php case ('intermediate'): ?> Trung bình <?php break; ?>
                                <?php case ('advanced'): ?> Có kinh nghiệm <?php break; ?>
                                <?php case ('professional'): ?> Chuyên nghiệp <?php break; ?>
                                <?php default: ?> <?php echo e($testDrive->experience_level); ?>

                            <?php endswitch; ?>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Thời gian</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500">Ngày tạo</p>
                        <p class="text-sm font-medium text-gray-900"><?php echo e($testDrive->created_at->format('d/m/Y H:i')); ?></p>
                    </div>
                    <div id="updatedAtContainer">
                        <p class="text-xs text-gray-500">Cập nhật lần cuối</p>
                        <p class="text-sm font-medium text-gray-900" id="updatedAtValue"><?php echo e($testDrive->updated_at->format('d/m/Y H:i')); ?></p>
                    </div>
                    <div id="confirmedAtContainer" class="<?php echo e($testDrive->confirmed_at ? '' : 'hidden'); ?>">
                        <p class="text-xs text-gray-500">Ngày xác nhận</p>
                        <p class="text-sm font-medium text-gray-900" id="confirmedAtValue"><?php echo e($testDrive->confirmed_at ? $testDrive->confirmed_at->format('d/m/Y H:i') : ''); ?></p>
                    </div>
                    <div id="completedAtContainer" class="<?php echo e($testDrive->completed_at ? '' : 'hidden'); ?>">
                        <p class="text-xs text-gray-500">Ngày hoàn thành</p>
                        <p class="text-sm font-medium text-gray-900" id="completedAtValue"><?php echo e($testDrive->completed_at ? $testDrive->completed_at->format('d/m/Y H:i') : ''); ?></p>
                    </div>
                    <div id="cancelledAtContainer" class="<?php echo e($testDrive->cancelled_at ? '' : 'hidden'); ?>">
                        <p class="text-xs text-gray-500">Ngày hủy</p>
                        <p class="text-sm font-medium text-gray-900" id="cancelledAtValue"><?php echo e($testDrive->cancelled_at ? $testDrive->cancelled_at->format('d/m/Y H:i') : ''); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4" onclick="if(event.target === this) closeConfirmModal()">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">Xác nhận lịch lái thử</h3>
            </div>
        </div>
        <div class="mb-6">
            <p class="text-gray-600" id="confirmModalMessage">Bạn có chắc chắn muốn xác nhận lịch lái thử này không?</p>
        </div>
        <div class="flex space-x-3">
            <button type="button" onclick="closeConfirmModal()" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                Hủy
            </button>
            <button type="button" id="confirmModalButton" onclick="confirmTestDrive()"
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-spinner fa-spin mr-2 hidden" id="confirmButtonSpinner"></i>
                <span id="confirmModalBtnText">Xác nhận</span>
            </button>
        </div>
    </div>
</div>


<div id="completeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4" onclick="if(event.target === this) closeCompleteModal()">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-double text-purple-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">Hoàn thành lịch lái thử</h3>
            </div>
        </div>
        <div class="mb-6">
            <p class="text-gray-600">Đánh dấu lịch lái thử này là đã hoàn thành?</p>
        </div>
        <div class="flex space-x-3">
            <button type="button" onclick="closeCompleteModal()" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                Hủy
            </button>
            <button type="button" id="completeModalButton" onclick="completeTestDrive()"
                    class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-spinner fa-spin mr-2 hidden" id="completeButtonSpinner"></i>
                <span id="completeModalBtnText">Hoàn thành</span>
            </button>
        </div>
    </div>
</div>


<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4" onclick="if(event.target === this) closeCancelModal()">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                <i class="fas fa-ban text-orange-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">Hủy lịch lái thử</h3>
            </div>
        </div>
        <div class="mb-6">
            <p class="text-gray-600">Bạn có chắc chắn muốn hủy lịch lái thử này không? Hành động này không thể hoàn tác.</p>
        </div>
        <div class="flex space-x-3">
            <button type="button" onclick="closeCancelModal()" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                Không
            </button>
            <button type="button" id="cancelModalButton" onclick="cancelTestDrive()"
                    class="flex-1 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-spinner fa-spin mr-2 hidden" id="cancelButtonSpinner"></i>
                <span id="cancelModalBtnText">Hủy lịch hẹn</span>
            </button>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
let currentStatus = '<?php echo e($testDrive->status); ?>';

// Modal functions
function openConfirmModal() {
    document.getElementById('confirmModal').classList.remove('hidden');
}
function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
}
function openCompleteModal() {
    document.getElementById('completeModal').classList.remove('hidden');
}
function closeCompleteModal() {
    document.getElementById('completeModal').classList.add('hidden');
}
function openCancelModal() {
    document.getElementById('cancelModal').classList.remove('hidden');
}
function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
}

// Action functions with spinner
function confirmTestDrive() {
    const button = document.getElementById('confirmModalButton');
    const btnText = document.getElementById('confirmModalBtnText');
    const spinner = document.getElementById('confirmButtonSpinner');
    
    button.disabled = true;
    spinner.classList.remove('hidden');
    
    updateStatus('confirmed', '<?php echo e(route('admin.test-drives.confirm', $testDrive)); ?>', () => {
        button.disabled = false;
        spinner.classList.add('hidden');
    });
}

function completeTestDrive() {
    const button = document.getElementById('completeModalButton');
    const btnText = document.getElementById('completeModalBtnText');
    const spinner = document.getElementById('completeButtonSpinner');
    
    button.disabled = true;
    spinner.classList.remove('hidden');
    
    updateStatus('completed', '<?php echo e(route('admin.test-drives.complete', $testDrive)); ?>', () => {
        button.disabled = false;
        spinner.classList.add('hidden');
    });
}

function cancelTestDrive() {
    const button = document.getElementById('cancelModalButton');
    const btnText = document.getElementById('cancelModalBtnText');
    const spinner = document.getElementById('cancelButtonSpinner');
    
    button.disabled = true;
    spinner.classList.remove('hidden');
    
    updateStatus('cancelled', '<?php echo e(route('admin.test-drives.cancel', $testDrive)); ?>', () => {
        button.disabled = false;
        spinner.classList.add('hidden');
    });
}

function updateStatus(newStatus, url, resetCallback) {
    fetch(url, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateUIAfterStatusChange(newStatus);
            closeConfirmModal();
            closeCompleteModal();
            closeCancelModal();
            
            // Use flash message component
            if (window.showMessage) {
                window.showMessage(data.message || 'Cập nhật thành công', 'success');
            }
        } else {
            if (window.showMessage) {
                window.showMessage(data.message || 'Có lỗi xảy ra', 'error');
            }
            if (resetCallback) resetCallback();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.showMessage) {
            window.showMessage('Có lỗi xảy ra', 'error');
        }
        if (resetCallback) resetCallback();
    });
}

function updateUIAfterStatusChange(newStatus) {
    currentStatus = newStatus;
    
    // Update badge
    const statusBadges = {
        'pending': '<span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800"><i class="fas fa-clock mr-2"></i>Chờ xác nhận</span>',
        'confirmed': '<span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle mr-2"></i>Đã xác nhận</span>',
        'completed': '<span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800"><i class="fas fa-flag-checkered mr-2"></i>Hoàn thành</span>',
        'cancelled': '<span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800"><i class="fas fa-times-circle mr-2"></i>Đã hủy</span>'
    };
    
    const badgeContainer = document.getElementById('statusBadgeContainer');
    if (badgeContainer && statusBadges[newStatus]) {
        badgeContainer.innerHTML = statusBadges[newStatus];
    }
    
    // Update timestamp
    const now = new Date();
    const formattedDate = now.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' }) + ' ' + 
                         now.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit', hour12: false });
    
    const updatedAtValue = document.getElementById('updatedAtValue');
    if (updatedAtValue) {
        updatedAtValue.textContent = formattedDate;
    }
    
    // Update confirmed_at timestamp when confirming
    if (newStatus === 'confirmed') {
        const confirmedAtContainer = document.getElementById('confirmedAtContainer');
        const confirmedAtValue = document.getElementById('confirmedAtValue');
        if (confirmedAtContainer && confirmedAtValue) {
            confirmedAtContainer.classList.remove('hidden');
            confirmedAtValue.textContent = formattedDate;
        }
    }
    
    // Update completed_at timestamp when completing
    if (newStatus === 'completed') {
        const completedAtContainer = document.getElementById('completedAtContainer');
        const completedAtValue = document.getElementById('completedAtValue');
        if (completedAtContainer && completedAtValue) {
            completedAtContainer.classList.remove('hidden');
            completedAtValue.textContent = formattedDate;
        }
    }
    
    // Update cancelled_at timestamp when cancelling
    if (newStatus === 'cancelled') {
        const cancelledAtContainer = document.getElementById('cancelledAtContainer');
        const cancelledAtValue = document.getElementById('cancelledAtValue');
        if (cancelledAtContainer && cancelledAtValue) {
            cancelledAtContainer.classList.remove('hidden');
            cancelledAtValue.textContent = formattedDate;
        }
    }
    
    // Update action buttons based on new status without reload
    updateActionButtons(newStatus);
}

function updateActionButtons(status) {
    const actionsContainer = document.querySelector('.space-y-2');
    if (!actionsContainer) {
        console.error('Actions container not found');
        return;
    }
    
    console.log('Updating action buttons for status:', status);
    
    let buttonsHtml = '';
    
    if (status === 'pending') {
        buttonsHtml = `
            <button type="button" onclick="openConfirmModal()" class="w-full btn btn-success text-left">
                <i class="fas fa-check-circle mr-2"></i>Xác nhận lịch hẹn
            </button>
            <button type="button" onclick="openCancelModal()" class="w-full btn btn-danger text-left">
                <i class="fas fa-times-circle mr-2"></i>Hủy lịch hẹn
            </button>
        `;
    } else if (status === 'confirmed') {
        buttonsHtml = `
            <button type="button" onclick="openCompleteModal()" class="w-full btn btn-primary text-left">
                <i class="fas fa-flag-checkered mr-2"></i>Hoàn thành
            </button>
            <button type="button" onclick="openCancelModal()" class="w-full btn btn-danger text-left">
                <i class="fas fa-times-circle mr-2"></i>Hủy lịch hẹn
            </button>
        `;
    } else if (status === 'completed') {
        buttonsHtml = `
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                <i class="fas fa-check-circle text-green-600 text-2xl mb-2"></i>
                <p class="text-sm text-green-800 font-medium">Lịch lái thử đã hoàn thành</p>
                <p class="text-xs text-green-600 mt-1">Không có thao tác khả dụng</p>
            </div>
        `;
    } else if (status === 'cancelled') {
        buttonsHtml = `
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                <i class="fas fa-times-circle text-red-600 text-2xl mb-2"></i>
                <p class="text-sm text-red-800 font-medium">Lịch lái thử đã bị hủy</p>
                <p class="text-xs text-red-600 mt-1">Không có thao tác khả dụng</p>
            </div>
        `;
    }
    
    actionsContainer.innerHTML = buttonsHtml;
}

// Use global showMessage function from flash-message system
// No custom notification needed
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/test-drives/show.blade.php ENDPATH**/ ?>