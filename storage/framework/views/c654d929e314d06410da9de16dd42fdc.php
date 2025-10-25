<footer class="bg-gray-950 text-gray-300">
    <div class="container mx-auto px-4 py-12">
        <!-- Main Footer Content -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-8 lg:gap-16">
            <!-- Brand Section -->
            <div class="flex-shrink-0">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-car text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-2xl">AutoLux</h3>
                        <p class="text-blue-300 text-sm font-medium">Premium Showroom</p>
                    </div>
                </div>
                <p class="text-gray-400 text-sm max-w-sm leading-relaxed">
                    Showroom xe hơi hàng đầu với dịch vụ chuyên nghiệp và đa dạng các dòng xe.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="flex flex-wrap gap-16 lg:gap-16">
                <div class="flex flex-col space-y-3">
                    <h4 class="text-white font-semibold text-sm uppercase tracking-wide">Sản phẩm</h4>
                    <a href="<?php echo e(route('products.index')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Tất cả</a>
                    <a href="<?php echo e(route('products.index', ['type' => 'car'])); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Xe hơi</a>
                    <a href="<?php echo e(route('products.index', ['type' => 'accessory'])); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Phụ kiện</a>
                </div>

                <div class="flex flex-col space-y-3">
                    <h4 class="text-white font-semibold text-sm uppercase tracking-wide">Thông tin</h4>
                    <a href="<?php echo e(route('blogs.index')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Tin tức</a>
                    <a href="<?php echo e(route('user.promotions.index')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Khuyến mãi</a>
                    <a href="<?php echo e(route('user.showrooms.index')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Showroom</a>
                </div>

                <div class="flex flex-col space-y-3">
                    <h4 class="text-white font-semibold text-sm uppercase tracking-wide">Hỗ trợ</h4>
                    <a href="<?php echo e(route('about')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Về chúng tôi</a>
                    <a href="<?php echo e(route('finance.calculator')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Tính trả góp</a>
                    <a href="<?php echo e(route('contact')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Liên hệ tư vấn</a>
                </div>
            </div>

            <!-- Social Links -->
            <div class="flex-shrink-0">
                <h4 class="text-white font-semibold text-sm uppercase tracking-wide mb-4">Kết nối</h4>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-blue-600 rounded-full flex items-center justify-center transition-colors">
                        <i class="fab fa-facebook-f text-gray-300 hover:text-white"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-pink-600 rounded-full flex items-center justify-center transition-colors">
                        <i class="fab fa-instagram text-gray-300 hover:text-white"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-red-600 rounded-full flex items-center justify-center transition-colors">
                        <i class="fab fa-youtube text-gray-300 hover:text-white"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col sm:flex-row justify-between items-center text-sm text-gray-500">
            <p>© <?php echo e(date('Y')); ?> AutoLux. Tất cả quyền được bảo lưu.</p>
            <div class="flex space-x-6 mt-4 sm:mt-0">
                <a href="#" class="hover:text-white transition-colors">Chính sách bảo mật</a>
                <a href="#" class="hover:text-white transition-colors">Điều khoản</a>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/components/footer.blade.php ENDPATH**/ ?>