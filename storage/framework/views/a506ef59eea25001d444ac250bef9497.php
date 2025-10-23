<?php $__env->startSection('title', 'Thêm người dùng'); ?>

<?php $__env->startSection('content'); ?>

<?php if (isset($component)) { $__componentOriginaldb1b157d84f8f63332f3508c9e385c0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldb1b157d84f8f63332f3508c9e385c0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.flash-messages','data' => ['showIcons' => true,'dismissible' => true,'position' => 'top-right','autoHide' => 5000]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.flash-messages'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['show-icons' => true,'dismissible' => true,'position' => 'top-right','auto-hide' => 5000]); ?>
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

<div class="px-2 sm:px-0">
    
    <?php if (isset($component)) { $__componentOriginalcb19cb35a534439097b02b8af91726ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcb19cb35a534439097b02b8af91726ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.page-header','data' => ['title' => 'Thêm người dùng','description' => 'Tạo tài khoản người dùng mới','icon' => 'fas fa-user-plus']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Thêm người dùng','description' => 'Tạo tài khoản người dùng mới','icon' => 'fas fa-user-plus']); ?>
        <a href="<?php echo e(route('admin.users.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcb19cb35a534439097b02b8af91726ee)): ?>
<?php $attributes = $__attributesOriginalcb19cb35a534439097b02b8af91726ee; ?>
<?php unset($__attributesOriginalcb19cb35a534439097b02b8af91726ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcb19cb35a534439097b02b8af91726ee)): ?>
<?php $component = $__componentOriginalcb19cb35a534439097b02b8af91726ee; ?>
<?php unset($__componentOriginalcb19cb35a534439097b02b8af91726ee); ?>
<?php endif; ?>

    
    <form id="userForm" class="mt-6">
        <?php echo csrf_field(); ?>
        
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <button type="button" data-tab="account" 
                            class="tab-button py-4 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600"
                            onclick="switchTab('account')">
                        <i class="fas fa-user-lock mr-2"></i>
                        Tài khoản
                    </button>
                    <button type="button" data-tab="profile"
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300"
                            onclick="switchTab('profile')">
                        <i class="fas fa-id-card mr-2"></i>
                        Hồ sơ
                    </button>
                    <button type="button" data-tab="address"
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300"
                            onclick="switchTab('address')">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        Địa chỉ
                    </button>
                </nav>
            </div>

            
            <div id="account-tab" class="tab-content p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-user-lock text-blue-600 mr-2"></i>
                    Thông tin tài khoản
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="user@example.com">
                    </div>

                    
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                            Vai trò <span class="text-red-500">*</span>
                        </label>
                        <select name="role" id="role" onchange="toggleEmployeeFields()"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Chọn vai trò</option>
                            <option value="user">Người dùng</option>
                            <option value="admin">Quản trị viên</option>
                            <option value="manager">Quản lý</option>
                            <option value="sales_person">NV Kinh doanh</option>
                            <option value="technician">Kỹ thuật viên</option>
                        </select>
                    </div>

                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Mật khẩu <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" id="password"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Tối thiểu 8 ký tự">
                    </div>

                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Xác nhận mật khẩu <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Nhập lại mật khẩu">
                    </div>

                    
                    <div class="md:col-span-2 space-y-3">
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" id="is_active" value="1" checked
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">
                                    Tài khoản đang hoạt động
                                </span>
                            </label>

                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="email_verified" id="email_verified" value="1"
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">
                                    Email đã xác thực
                                </span>
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 ml-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Admin có thể trực tiếp xác thực email khi tạo tài khoản
                        </p>
                    </div>
                </div>

                
                <div id="employee-fields" class="hidden mt-6">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Thông tin nhân viên</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Mã nhân viên
                            </label>
                            <input type="text" name="employee_id" id="employee_id"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="NV001">
                        </div>

                        
                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                                Phòng ban
                            </label>
                            <input type="text" name="department" id="department"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Phòng kinh doanh">
                        </div>

                        
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                                Chức vụ
                            </label>
                            <input type="text" name="position" id="position"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Trưởng phòng">
                        </div>

                        
                        <div>
                            <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Ngày tuyển dụng
                            </label>
                            <input type="date" name="hire_date" id="hire_date"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
            </div>

            
            <div id="profile-tab" class="tab-content hidden p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-id-card text-blue-600 mr-2"></i>
                    Thông tin cá nhân
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Họ và tên <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Nguyễn Văn A">
                    </div>

                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Số điện thoại
                        </label>
                        <input type="text" name="phone" id="phone"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0987654321">
                    </div>

                    
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Ngày sinh
                        </label>
                        <input type="date" name="birth_date" id="birth_date"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                            Giới tính
                        </label>
                        <select name="gender" id="gender"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Chọn giới tính</option>
                            <option value="male">Nam</option>
                            <option value="female">Nữ</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>

                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ảnh đại diện
                        </label>
                        <input type="file" name="avatar" id="avatar" accept="image/*" onchange="previewAvatar(this)"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF tối đa 2MB</p>
                        
                        
                        <div id="avatarPreview" class="hidden mt-4">
                            <img id="avatarPreviewImage" src="" alt="Avatar preview" class="w-32 h-32 object-cover rounded-full border-2 border-gray-200">
                        </div>
                    </div>
                </div>

                
                <div class="mt-6">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Thông tin bằng lái xe</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="driver_license_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Số bằng lái
                            </label>
                            <input type="text" name="driver_license_number" id="driver_license_number"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="driver_license_class" class="block text-sm font-medium text-gray-700 mb-2">
                                Hạng bằng lái
                            </label>
                            <input type="text" name="driver_license_class" id="driver_license_class"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="B1, B2, C...">
                        </div>

                        <div>
                            <label for="driver_license_issue_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Ngày cấp
                            </label>
                            <input type="date" name="driver_license_issue_date" id="driver_license_issue_date"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="driver_license_expiry_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Ngày hết hạn
                            </label>
                            <input type="date" name="driver_license_expiry_date" id="driver_license_expiry_date"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="driving_experience_years" class="block text-sm font-medium text-gray-700 mb-2">
                                Số năm kinh nghiệm lái xe
                            </label>
                            <input type="number" name="driving_experience_years" id="driving_experience_years"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                
                <div id="customer-preferences" class="mt-6 hidden">
                    <h4 class="text-md font-medium text-gray-900 mb-4">
                        <i class="fas fa-heart text-red-600 mr-2"></i>
                        Sở thích mua xe
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="budget_min" class="block text-sm font-medium text-gray-700 mb-2">
                                Ngân sách tối thiểu (VNĐ)
                            </label>
                            <input type="number" name="budget_min" id="budget_min" step="1000000"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="500000000">
                        </div>
                        <div>
                            <label for="budget_max" class="block text-sm font-medium text-gray-700 mb-2">
                                Ngân sách tối đa (VNĐ)
                            </label>
                            <input type="number" name="budget_max" id="budget_max" step="1000000"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="1000000000">
                        </div>
                        <div>
                            <label for="purchase_purpose" class="block text-sm font-medium text-gray-700 mb-2">
                                Mục đích mua xe
                            </label>
                            <select name="purchase_purpose" id="purchase_purpose"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Chọn mục đích</option>
                                <option value="personal">Cá nhân</option>
                                <option value="business">Kinh doanh</option>
                                <option value="family">Gia đình</option>
                                <option value="investment">Đầu tư</option>
                            </select>
                        </div>
                        <div>
                            <label for="customer_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Loại khách hàng
                            </label>
                            <select name="customer_type" id="customer_type"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="new">Mới</option>
                                <option value="returning">Quay lại</option>
                                <option value="vip">VIP</option>
                                <option value="prospect">Tiềm năng</option>
                            </select>
                        </div>
                        <div>
                            <label for="preferred_car_types" class="block text-sm font-medium text-gray-700 mb-2">
                                Loại xe ưa thích
                            </label>
                            <input type="text" name="preferred_car_types" id="preferred_car_types"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Sedan, SUV, Hatchback... (phân cách bởi dấu phẩy)">
                            <p class="mt-1 text-xs text-gray-500">Ví dụ: Sedan, SUV, Hatchback</p>
                        </div>
                        <div>
                            <label for="preferred_brands" class="block text-sm font-medium text-gray-700 mb-2">
                                Thương hiệu ưa thích
                            </label>
                            <input type="text" name="preferred_brands" id="preferred_brands"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Toyota, Honda, Mercedes... (phân cách bởi dấu phẩy)">
                            <p class="mt-1 text-xs text-gray-500">Ví dụ: Toyota, Honda, BMW</p>
                        </div>
                        <div>
                            <label for="preferred_colors" class="block text-sm font-medium text-gray-700 mb-2">
                                Màu sắc ưa thích
                            </label>
                            <input type="text" name="preferred_colors" id="preferred_colors"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Trắng, Đen, Bạc... (phân cách bởi dấu phẩy)">
                            <p class="mt-1 text-xs text-gray-500">Ví dụ: Trắng, Đen, Xanh</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="is_vip" id="is_vip" value="1"
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">
                                    Đánh dấu là khách hàng VIP
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                
                <div id="employee-info" class="mt-6 hidden">
                    <h4 class="text-md font-medium text-gray-900 mb-4">
                        <i class="fas fa-briefcase text-green-600 mr-2"></i>
                        Thông tin nhân viên
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="employee_salary" class="block text-sm font-medium text-gray-700 mb-2">
                                Lương (VNĐ/tháng)
                            </label>
                            <input type="number" name="employee_salary" id="employee_salary" step="100000"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="15000000">
                        </div>
                        <div class="md:col-span-2">
                            <label for="employee_skills" class="block text-sm font-medium text-gray-700 mb-2">
                                Kỹ năng
                            </label>
                            <textarea name="employee_skills" id="employee_skills" rows="3"
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Kỹ năng bán hàng, tư vấn khách hàng, am hiểu sản phẩm..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            
            <div id="address-tab" class="tab-content hidden p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                            Địa chỉ liên hệ
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Có thể thêm nhiều địa chỉ, chọn 1 làm mặc định</p>
                    </div>
                    <button type="button" onclick="addAddressCard()" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Thêm địa chỉ
                    </button>
                </div>

                
                <?php $__errorArgs = ['addresses'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                            <span class="text-sm text-red-600 font-medium"><?php echo e($message); ?></span>
                        </div>
                    </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                
                <div id="addressesContainer" class="space-y-4">
                    
                </div>

                
                <div id="addressesEmptyState" class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                    <i class="fas fa-map-marker-alt text-gray-400 text-4xl mb-3"></i>
                    <p class="text-gray-500 mb-2">Chưa có địa chỉ nào</p>
                    <p class="text-sm text-gray-400">Nhấn "Thêm địa chỉ" để bắt đầu</p>
                </div>
            </div>
        </div>

        
        <div class="flex justify-between space-x-3 pt-6 border-t border-gray-200">
            <a href="<?php echo e(route('admin.users.index')); ?>" 
               class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-times mr-2"></i>
                Hủy
            </a>
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-save mr-2"></i>
                Thêm
            </button>
        </div>
    </form>
</div>

<script>
// DOMContentLoaded wrapper
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    window.switchTab = function(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });
        
        // Remove active state from all buttons
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('border-blue-500', 'text-blue-600');
            btn.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Show target tab
        const targetTab = document.getElementById(tabName + '-tab');
        if (targetTab) {
            targetTab.classList.remove('hidden');
        }
        
        // Activate button
        const targetBtn = document.querySelector(`[data-tab="${tabName}"]`);
        if (targetBtn) {
            targetBtn.classList.remove('border-transparent', 'text-gray-500');
            targetBtn.classList.add('border-blue-500', 'text-blue-600');
        }
    };

    // Toggle employee fields and customer preferences based on role
    window.toggleEmployeeFields = function() {
        const role = document.getElementById('role').value;
        const employeeFields = document.getElementById('employee-fields');
        const customerPreferences = document.getElementById('customer-preferences');
        const employeeInfo = document.getElementById('employee-info');
        
        if (role && role !== 'user') {
            // Employee/Admin/Manager/Sales/Technician
            employeeFields.classList.remove('hidden');
            employeeInfo.classList.remove('hidden');
            customerPreferences.classList.add('hidden');
        } else {
            // Customer
            employeeFields.classList.add('hidden');
            employeeInfo.classList.add('hidden');
            customerPreferences.classList.remove('hidden');
        }
    };

    // Avatar preview
    window.previewAvatar = function(input) {
        if (input.files && input.files[0]) {
            // File size validation (2MB)
            if (input.files[0].size > 2 * 1024 * 1024) {
                window.showMessage('Kích thước ảnh không được vượt quá 2MB', 'error');
                input.value = '';
                return;
            }
            
            // File type validation
            if (!input.files[0].type.match('image.*')) {
                window.showMessage('Vui lòng chọn file ảnh hợp lệ', 'error');
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreviewImage').src = e.target.result;
                document.getElementById('avatarPreview').classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    // Validation function
    function validateUserForm() {
        // 1. Email validation
        const emailField = document.getElementById('email');
        if (!emailField.value.trim()) {
            return {
                isValid: false,
                element: emailField,
                message: 'Vui lòng nhập email.',
                tabId: 'account'
            };
        }
        
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(emailField.value.trim())) {
            return {
                isValid: false,
                element: emailField,
                message: 'Email không hợp lệ.',
                tabId: 'account'
            };
        }

        // 2. Role validation
        const roleField = document.getElementById('role');
        if (!roleField.value) {
            return {
                isValid: false,
                element: roleField,
                message: 'Vui lòng chọn vai trò.',
                tabId: 'account'
            };
        }

        // 3. Password validation
        const passwordField = document.getElementById('password');
        if (!passwordField.value.trim()) {
            return {
                isValid: false,
                element: passwordField,
                message: 'Vui lòng nhập mật khẩu.',
                tabId: 'account'
            };
        }
        
        if (passwordField.value.length < 8) {
            return {
                isValid: false,
                element: passwordField,
                message: 'Mật khẩu phải có ít nhất 8 ký tự.',
                tabId: 'account'
            };
        }

        // 4. Password confirmation
        const passwordConfirmField = document.getElementById('password_confirmation');
        if (!passwordConfirmField.value.trim()) {
            return {
                isValid: false,
                element: passwordConfirmField,
                message: 'Vui lòng xác nhận mật khẩu.',
                tabId: 'account'
            };
        }
        
        if (passwordField.value !== passwordConfirmField.value) {
            return {
                isValid: false,
                element: passwordConfirmField,
                message: 'Mật khẩu xác nhận không khớp.',
                tabId: 'account'
            };
        }

        // 5. Name validation (Profile tab)
        const nameField = document.getElementById('name');
        if (!nameField.value.trim()) {
            return {
                isValid: false,
                element: nameField,
                message: 'Vui lòng nhập họ và tên.',
                tabId: 'profile'
            };
        }
        
        if (nameField.value.trim().length < 2) {
            return {
                isValid: false,
                element: nameField,
                message: 'Họ và tên phải có ít nhất 2 ký tự.',
                tabId: 'profile'
            };
        }

        return { isValid: true };
    }

    // === ADDRESS MANAGEMENT ===
    let addressIndex = 0;

    function toggleEmptyState() {
        const container = document.getElementById('addressesContainer');
        const emptyState = document.getElementById('addressesEmptyState');
        const hasAddresses = container.children.length > 0;
        
        if (hasAddresses) {
            emptyState.classList.add('hidden');
        } else {
            emptyState.classList.remove('hidden');
        }
    }

    window.addAddressCard = function() {
        const container = document.getElementById('addressesContainer');
        const index = addressIndex++;
        
        const addressCard = document.createElement('div');
        addressCard.className = 'address-card bg-white border border-gray-300 rounded-lg p-6';
        addressCard.dataset.index = index;
        
        const isFirst = container.children.length === 0;
        
        addressCard.innerHTML = `
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-md font-medium text-gray-900">
                    <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                    Địa chỉ #${index + 1}
                </h4>
                <div class="flex items-center space-x-2">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="default_address_index" value="${index}" 
                               ${isFirst ? 'checked' : ''}
                               onchange="setDefaultAddress(${index})"
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">Mặc định</span>
                    </label>
                    <button type="button" onclick="removeAddressCard(${index})" 
                            class="text-red-600 hover:text-red-800 p-2">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Loại địa chỉ</label>
                    <select name="addresses[${index}][type]" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="home">Nhà riêng</option>
                        <option value="work">Cơ quan</option>
                        <option value="billing">Thanh toán</option>
                        <option value="shipping">Giao hàng</option>
                        <option value="other">Khác</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tên người liên hệ</label>
                    <input type="text" name="addresses[${index}][contact_name]" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Họ và tên">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                    <input type="text" name="addresses[${index}][phone]" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="0912345678">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Thành phố</label>
                    <input type="text" name="addresses[${index}][city]" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Hồ Chí Minh, Hà Nội...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quận/Huyện</label>
                    <input type="text" name="addresses[${index}][state]" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Quận 1, Củ Chi...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mã bưu điện</label>
                    <input type="text" name="addresses[${index}][postal_code]" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="700000">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quốc gia</label>
                    <select name="addresses[${index}][country]" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="Vietnam" selected>Việt Nam</option>
                        <option value="China">Trung Quốc</option>
                        <option value="Japan">Nhật Bản</option>
                        <option value="South Korea">Hàn Quốc</option>
                        <option value="Thailand">Thái Lan</option>
                        <option value="Singapore">Singapore</option>
                        <option value="Malaysia">Malaysia</option>
                        <option value="United States">Hoa Kỳ</option>
                        <option value="United Kingdom">Anh</option>
                        <option value="Germany">Đức</option>
                        <option value="France">Pháp</option>
                        <option value="Other">Khác</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ chi tiết</label>
                    <textarea name="addresses[${index}][address]" rows="2" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Số nhà, tên đường, phường/xã..."></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
                    <textarea name="addresses[${index}][notes]" rows="2" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Ghi chú thêm..."></textarea>
                </div>
                <input type="hidden" name="addresses[${index}][is_default]" value="${isFirst ? '1' : '0'}" class="is-default-input">
            </div>
        `;
        
        container.appendChild(addressCard);
        toggleEmptyState();
    };

    window.removeAddressCard = function(index) {
        const card = document.querySelector(`.address-card[data-index="${index}"]`);
        if (card) {
            const wasDefault = card.querySelector('input[type="radio"]').checked;
            card.remove();
            toggleEmptyState();
            
            if (wasDefault) {
                const allCards = document.querySelectorAll('.address-card');
                if (allCards.length > 0) {
                    const firstCard = allCards[0];
                    const firstIndex = firstCard.dataset.index;
                    const firstRadio = firstCard.querySelector('input[type="radio"]');
                    firstRadio.checked = true;
                    setDefaultAddress(firstIndex);
                }
            }
        }
    };

    window.setDefaultAddress = function(index) {
        document.querySelectorAll('.is-default-input').forEach(input => input.value = '0');
        const card = document.querySelector(`.address-card[data-index="${index}"]`);
        if (card) {
            card.querySelector('.is-default-input').value = '1';
        }
    };

    // Form submission
    document.getElementById('userForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Client-side validation
        const validationResult = validateUserForm();
        if (!validationResult.isValid) {
            // Switch to tab with error
            if (validationResult.tabId) {
                window.switchTab(validationResult.tabId);
            }
            
            // Focus field with error
            if (validationResult.element) {
                validationResult.element.focus();
                validationResult.element.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            // Show error message
            window.showMessage(validationResult.message, 'error');
            return;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';
        
        // Prepare form data
        const formData = new FormData(this);
        
        // AJAX submission
        fetch('<?php echo e(route("admin.users.store")); ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(async response => {
            const data = await response.json();
            
            if (!response.ok) {
                // Handle validation errors (422)
                if (response.status === 422 && data.errors) {
                    const firstError = Object.values(data.errors)[0];
                    window.showMessage(firstError, 'error');
                } else {
                    window.showMessage(data.message || 'Có lỗi xảy ra', 'error');
                }
                
                // Restore button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                return;
            }
            
            // Success
            if (data.success) {
                window.showMessage(data.message, 'success');
                
                // Redirect after short delay
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.showMessage('Có lỗi xảy ra khi tạo người dùng', 'error');
            
            // Restore button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        })
        .finally(() => {
            // Restore button if not redirecting
            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            }, 2000);
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/users/create.blade.php ENDPATH**/ ?>