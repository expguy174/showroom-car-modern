<footer class="bg-gray-950 text-gray-300 py-10 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 md:gap-12 lg:gap-16">
            <div class="md:col-span-1">
                <div class="flex items-center mb-5 space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-700 to-indigo-800 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-car text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-extrabold text-xl leading-tight">AutoLux</h3>
                        <p class="text-xs text-blue-300 font-medium tracking-wide opacity-80">Premium Showroom</p>
                    </div>
                </div>
                <p class="text-sm text-gray-400 leading-relaxed max-w-sm">
                    Khám phá bộ sưu tập xe sang trọng và trải nghiệm dịch vụ đẳng cấp tại showroom của chúng tôi.
                </p>
            </div>

            <div class="md:col-span-1">
                <h4 class="text-white font-bold mb-4 text-base tracking-wide">Thông tin</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="text-gray-400 hover:text-blue-400 transition duration-300 ease-in-out">Về chúng tôi</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-blue-400 transition duration-300 ease-in-out">Liên hệ</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-blue-400 transition duration-300 ease-in-out">Câu hỏi thường gặp</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-blue-400 transition duration-300 ease-in-out">Tuyển dụng</a></li>
                </ul>
            </div>

            <div class="md:col-span-1">
                <h4 class="text-white font-bold mb-4 text-base tracking-wide">Dịch vụ</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="text-gray-400 hover:text-blue-400 transition duration-300 ease-in-out">Mua bán xe</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-blue-400 transition duration-300 ease-in-out">Thuê xe</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-blue-400 transition duration-300 ease-in-out">Bảo dưỡng</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-blue-400 transition duration-300 ease-in-out">Tài chính</a></li>
                </ul>
            </div>

            <div class="md:col-span-1">
                <h4 class="text-white font-bold mb-4 text-base tracking-wide">Kết nối với chúng tôi</h4>
                <div class="flex space-x-5 text-gray-400 text-xl mb-6">
                    <a href="#" class="hover:text-blue-400 transition duration-300 ease-in-out transform hover:scale-110"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="hover:text-pink-500 transition duration-300 ease-in-out transform hover:scale-110"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="hover:text-red-500 transition duration-300 ease-in-out transform hover:scale-110"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="hover:text-blue-500 transition duration-300 ease-in-out transform hover:scale-110"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <h4 class="text-white font-bold mb-3 text-base tracking-wide">Đăng ký nhận tin</h4>
                <form id="newsletter-form" class="flex flex-col sm:flex-row gap-2">
                    <input 
                        type="email" 
                        name="newsletter_email"
                        placeholder="Nhập email của bạn" 
                        class="flex-grow p-3 rounded-lg sm:rounded-l-lg sm:rounded-r-none bg-gray-800 text-gray-300 placeholder-gray-500 border border-gray-700 text-sm transition-all duration-300 
                        focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-80 focus:border-blue-400 focus:border-2" 
                        required
                    />
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg sm:rounded-r-lg sm:rounded-l-none transition duration-300 ease-in-out text-sm font-semibold border border-blue-700 hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-80">Gửi</button>
                </form>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-10 sm:mt-12 pt-6 sm:pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
            <span>© {{ date('Y') }} AutoLux. Tất cả quyền được bảo lưu.</span>
            <div class="flex flex-wrap gap-x-8 gap-y-2 mt-5 md:mt-0">
                <a href="#" class="hover:text-blue-400 transition duration-300 ease-in-out">Chính sách bảo mật</a>
                <a href="#" class="hover:text-blue-400 transition duration-300 ease-in-out">Điều khoản dịch vụ</a>
            </div>
        </div>
    </div>
</footer>
