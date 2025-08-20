@extends('layouts.app')
@section('title', 'Câu hỏi thường gặp - Tài chính - AutoLux')
@section('content')

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-teal-900 via-cyan-800 to-blue-900 text-white overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
    
    <div class="relative container mx-auto px-4 py-16 lg:py-20">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 mb-8">
                <i class="fas fa-question-circle text-cyan-400 mr-3 text-xl"></i>
                <span class="text-sm font-medium">Giải đáp thắc mắc</span>
            </div>
            
            <h1 class="text-4xl lg:text-5xl font-bold mb-6 leading-tight">
                Câu hỏi thường gặp
            </h1>
            
            <p class="text-xl text-cyan-100 max-w-3xl mx-auto leading-relaxed">
                Tìm hiểu câu trả lời cho những thắc mắc phổ biến về dịch vụ tài chính mua xe. 
                Nếu bạn không tìm thấy câu trả lời, hãy liên hệ với chúng tôi.
            </p>
        </div>
    </div>
</section>

<!-- FAQ Categories -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Chọn chủ đề bạn quan tâm
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Chúng tôi đã tổ chức các câu hỏi theo chủ đề để bạn dễ dàng tìm kiếm thông tin cần thiết
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
                <button class="faq-category-btn active bg-white rounded-2xl p-6 text-center shadow-lg border-2 border-blue-500 hover:shadow-xl transition-all duration-300" data-category="general">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-info-circle text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Tổng quan</h3>
                    <p class="text-sm text-gray-600">Thông tin cơ bản về vay</p>
                </button>

                <button class="faq-category-btn bg-white rounded-2xl p-6 text-center shadow-lg border-2 border-transparent hover:shadow-xl transition-all duration-300" data-category="eligibility">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-check text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Điều kiện</h3>
                    <p class="text-sm text-gray-600">Ai có thể vay</p>
                </button>

                <button class="faq-category-btn bg-white rounded-2xl p-6 text-center shadow-lg border-2 border-transparent hover:shadow-xl transition-all duration-300" data-category="documents">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Hồ sơ</h3>
                    <p class="text-sm text-gray-600">Giấy tờ cần thiết</p>
                </button>

                <button class="faq-category-btn bg-white rounded-2xl p-6 text-center shadow-lg border-2 border-transparent hover:shadow-xl transition-all duration-300" data-category="process">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-cogs text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Quy trình</h3>
                    <p class="text-sm text-gray-600">Cách thức vay</p>
                </button>
            </div>

            <!-- FAQ Content -->
            <div class="space-y-6">
                <!-- General Category -->
                <div class="faq-category active" data-category="general">
                    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                        <h3 class="text-2xl font-bold text-gray-900 mb-8 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                            Câu hỏi chung về vay mua xe
                        </h3>
                        
                        <div class="space-y-6">
                            <div class="faq-item border-b border-gray-200 pb-6">
                                <button class="faq-question w-full text-left flex items-center justify-between hover:text-blue-600 transition-colors duration-300">
                                    <h4 class="text-lg font-semibold text-gray-900">Vay mua xe có những ưu điểm gì?</h4>
                                    <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"></i>
                                </button>
                                <div class="faq-answer hidden mt-4 text-gray-600 leading-relaxed">
                                    <p class="mb-3">Vay mua xe có nhiều ưu điểm:</p>
                                    <ul class="list-disc list-inside space-y-2 ml-4">
                                        <li>Không cần có toàn bộ số tiền mua xe ngay lập tức</li>
                                        <li>Lãi suất cạnh tranh, thường thấp hơn các hình thức vay khác</li>
                                        <li>Thời hạn vay linh hoạt từ 12-84 tháng</li>
                                        <li>Xe được mua sẽ là tài sản đảm bảo, không cần thế chấp tài sản khác</li>
                                        <li>Quy trình thủ tục đơn giản, nhanh chóng</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="faq-item border-b border-gray-200 pb-6">
                                <button class="faq-question w-full text-left flex items-center justify-between hover:text-blue-600 transition-colors duration-300">
                                    <h4 class="text-lg font-semibold text-gray-900">Tôi có thể vay bao nhiêu phần trăm giá trị xe?</h4>
                                    <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"></i>
                                </button>
                                <div class="faq-answer hidden mt-4 text-gray-600 leading-relaxed">
                                    <p>Bạn có thể vay tối đa 90% giá trị xe, tùy thuộc vào:</p>
                                    <ul class="list-disc list-inside space-y-2 ml-4 mt-3">
                                        <li>Gói vay bạn chọn</li>
                                        <li>Khả năng tài chính và thu nhập</li>
                                        <li>Lịch sử tín dụng</li>
                                        <li>Loại xe và giá trị xe</li>
                                    </ul>
                                    <p class="mt-3">Số tiền trả trước tối thiểu thường từ 10-30% giá trị xe.</p>
                                </div>
                            </div>

                            <div class="faq-item border-b border-gray-200 pb-6">
                                <button class="faq-question w-full text-left flex items-center justify-between hover:text-blue-600 transition-colors duration-300">
                                    <h4 class="text-lg font-semibold text-gray-900">Lãi suất vay có cố định không?</h4>
                                    <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"></i>
                                </button>
                                <div class="faq-answer hidden mt-4 text-gray-600 leading-relaxed">
                                    <p>Lãi suất có thể cố định hoặc thả nổi tùy thuộc vào gói vay:</p>
                                    <ul class="list-disc list-inside space-y-2 ml-4 mt-3">
                                        <li><strong>Lãi suất cố định:</strong> Không thay đổi trong suốt thời hạn vay</li>
                                        <li><strong>Lãi suất thả nổi:</strong> Có thể thay đổi theo thị trường</li>
                                    </ul>
                                    <p class="mt-3">Chúng tôi khuyến nghị bạn nên tham khảo chi tiết từng gói vay để hiểu rõ điều khoản lãi suất.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Eligibility Category -->
                <div class="faq-category hidden" data-category="eligibility">
                    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                        <h3 class="text-2xl font-bold text-gray-900 mb-8 flex items-center">
                            <i class="fas fa-user-check text-green-600 mr-3"></i>
                            Điều kiện để được vay
                        </h3>
                        
                        <div class="space-y-6">
                            <div class="faq-item border-b border-gray-200 pb-6">
                                <button class="faq-question w-full text-left flex items-center justify-between hover:text-green-600 transition-colors duration-300">
                                    <h4 class="text-lg font-semibold text-gray-900">Tôi cần bao nhiêu tuổi để được vay?</h4>
                                    <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"></i>
                                </button>
                                <div class="faq-answer hidden mt-4 text-gray-600 leading-relaxed">
                                    <p>Để được vay mua xe, bạn cần:</p>
                                    <ul class="list-disc list-inside space-y-2 ml-4 mt-3">
                                        <li>Đủ 18 tuổi trở lên</li>
                                        <li>Có năng lực hành vi dân sự đầy đủ</li>
                                        <li>Không bị hạn chế năng lực hành vi dân sự</li>
                                    </ul>
                                    <p class="mt-3">Một số gói vay có thể yêu cầu tuổi tối thiểu cao hơn (21-25 tuổi).</p>
                                </div>
                            </div>

                            <div class="faq-item border-b border-gray-200 pb-6">
                                <button class="faq-question w-full text-left flex items-center justify-between hover:text-green-600 transition-colors duration-300">
                                    <h4 class="text-lg font-semibold text-gray-900">Thu nhập tối thiểu để được vay là bao nhiêu?</h4>
                                    <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"></i>
                                </button>
                                <div class="faq-answer hidden mt-4 text-gray-600 leading-relaxed">
                                    <p>Thu nhập tối thiểu để được vay thường là 8 triệu VNĐ/tháng, tuy nhiên:</p>
                                    <ul class="list-disc list-inside space-y-2 ml-4 mt-3">
                                        <li>Một số gói vay có thể yêu cầu thu nhập cao hơn</li>
                                        <li>Thu nhập phải ổn định và có thể chứng minh được</li>
                                        <li>Tỷ lệ trả nợ không được vượt quá 50% thu nhập</li>
                                    </ul>
                                    <p class="mt-3">Nếu bạn có thu nhập thấp hơn, có thể cần người bảo lãnh hoặc chọn gói vay đặc biệt.</p>
                                </div>
                            </div>

                            <div class="faq-item border-b border-gray-200 pb-6">
                                <button class="faq-question w-full text-left flex items-center justify-between hover:text-green-600 transition-colors duration-300">
                                    <h4 class="text-lg font-semibold text-gray-900">Tôi có cần người bảo lãnh không?</h4>
                                    <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"></i>
                                </button>
                                <div class="faq-answer hidden mt-4 text-gray-600 leading-relaxed">
                                    <p>Việc yêu cầu người bảo lãnh phụ thuộc vào:</p>
                                    <ul class="list-disc list-inside space-y-2 ml-4 mt-3">
                                        <li>Khả năng tài chính của bạn</li>
                                        <li>Lịch sử tín dụng</li>
                                        <li>Gói vay bạn chọn</li>
                                        <li>Loại xe và giá trị xe</li>
                                    </ul>
                                    <p class="mt-3">Một số gói vay đặc biệt có thể yêu cầu người bảo lãnh để tăng khả năng được duyệt.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents Category -->
                <div class="faq-category hidden" data-category="documents">
                    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                        <h3 class="text-2xl font-bold text-gray-900 mb-8 flex items-center">
                            <i class="fas fa-file-alt text-purple-600 mr-3"></i>
                            Hồ sơ và giấy tờ cần thiết
                        </h3>
                        
                        <div class="space-y-6">
                            <div class="faq-item border-b border-gray-200 pb-6">
                                <button class="faq-question w-full text-left flex items-center justify-between hover:text-purple-600 transition-colors duration-300">
                                    <h4 class="text-lg font-semibold text-gray-900">Tôi cần chuẩn bị những giấy tờ gì?</h4>
                                    <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"></i>
                                </button>
                                <div class="faq-answer hidden mt-4 text-gray-600 leading-relaxed">
                                    <p>Bạn cần chuẩn bị các giấy tờ sau:</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                                        <div>
                                            <h5 class="font-semibold text-gray-800 mb-2">Giấy tờ cá nhân:</h5>
                                            <ul class="list-disc list-inside space-y-1 text-sm">
                                                <li>CMND/CCCD còn hiệu lực</li>
                                                <li>Sổ hộ khẩu/KT3</li>
                                                <li>Giấy phép lái xe</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <h5 class="font-semibold text-gray-800 mb-2">Giấy tờ tài chính:</h5>
                                            <ul class="list-disc list-inside space-y-1 text-sm">
                                                <li>Chứng minh thu nhập (3 tháng)</li>
                                                <li>Sao kê ngân hàng (3 tháng)</li>
                                                <li>Hợp đồng lao động</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="faq-item border-b border-gray-200 pb-6">
                                <button class="faq-question w-full text-left flex items-center justify-between hover:text-purple-600 transition-colors duration-300">
                                    <h4 class="text-lg font-semibold text-gray-900">Giấy tờ có cần công chứng không?</h4>
                                    <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"></i>
                                </button>
                                <div class="faq-answer hidden mt-4 text-gray-600 leading-relaxed">
                                    <p>Yêu cầu về giấy tờ:</p>
                                    <ul class="list-disc list-inside space-y-2 ml-4 mt-3">
                                        <li><strong>Bản gốc:</strong> CMND/CCCD, sổ hộ khẩu, giấy phép lái xe</li>
                                        <li><strong>Bản sao có công chứng:</strong> Hợp đồng lao động, chứng minh thu nhập</li>
                                        <li><strong>Bản gốc hoặc sao y:</strong> Sao kê ngân hàng</li>
                                    </ul>
                                    <p class="mt-3">Tất cả giấy tờ phải rõ ràng, không bị rách nát hoặc mờ nhạt.</p>
                                </div>
                            </div>

                            <div class="faq-item border-b border-gray-200 pb-6">
                                <button class="faq-question w-full text-left flex items-center justify-between hover:text-purple-600 transition-colors duration-300">
                                    <h4 class="text-lg font-semibold text-gray-900">Nếu thiếu giấy tờ thì sao?</h4>
                                    <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"></i>
                                </button>
                                <div class="faq-answer hidden mt-4 text-gray-600 leading-relaxed">
                                    <p>Nếu thiếu giấy tờ:</p>
                                    <ul class="list-disc list-inside space-y-2 ml-4 mt-3">
                                        <li>Quá trình thẩm định sẽ bị chậm trễ</li>
                                        <li>Có thể bị từ chối hồ sơ</li>
                                        <li>Nhân viên tư vấn sẽ hướng dẫn bổ sung</li>
                                    </ul>
                                    <p class="mt-3">Chúng tôi khuyến nghị bạn chuẩn bị đầy đủ giấy tờ trước khi nộp hồ sơ để tránh mất thời gian.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Process Category -->
                <div class="faq-category hidden" data-category="process">
                    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                        <h3 class="text-2xl font-bold text-gray-900 mb-8 flex items-center">
                            <i class="fas fa-cogs text-orange-600 mr-3"></i>
                            Quy trình vay và xử lý hồ sơ
                        </h3>
                        
                        <div class="space-y-6">
                            <div class="faq-item border-b border-gray-200 pb-6">
                                <button class="faq-question w-full text-left flex items-center justify-between hover:text-orange-600 transition-colors duration-300">
                                    <h4 class="text-lg font-semibold text-gray-900">Quy trình vay mất bao lâu?</h4>
                                    <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"></i>
                                </button>
                                <div class="faq-answer hidden mt-4 text-gray-600 leading-relaxed">
                                    <p>Quy trình vay thường diễn ra như sau:</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                                        <div>
                                            <h5 class="font-semibold text-gray-800 mb-2">Thời gian xử lý:</h5>
                                            <ul class="list-disc list-inside space-y-1 text-sm">
                                                <li>Tư vấn & chọn gói: 1-2 ngày</li>
                                                <li>Chuẩn bị hồ sơ: 1-3 ngày</li>
                                                <li>Thẩm định & duyệt: 3-5 ngày</li>
                                                <li>Ký hợp đồng: 1 ngày</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <h5 class="font-semibold text-gray-800 mb-2">Tổng thời gian:</h5>
                                            <p class="text-lg font-bold text-green-600">6-11 ngày</p>
                                            <p class="text-sm text-gray-600">Tùy thuộc vào độ phức tạp của hồ sơ</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="faq-item border-b border-gray-200 pb-6">
                                <button class="faq-question w-full text-left flex items-center justify-between hover:text-orange-600 transition-colors duration-300">
                                    <h4 class="text-lg font-semibold text-gray-900">Tôi có thể theo dõi tiến độ hồ sơ không?</h4>
                                    <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"></i>
                                </button>
                                <div class="faq-answer hidden mt-4 text-gray-600 leading-relaxed">
                                    <p>Có, bạn có thể theo dõi tiến độ hồ sơ qua:</p>
                                    <ul class="list-disc list-inside space-y-2 ml-4 mt-3">
                                        <li><strong>Nhân viên tư vấn:</strong> Liên hệ trực tiếp để cập nhật</li>
                                        <li><strong>Hệ thống online:</strong> Kiểm tra trạng thái hồ sơ</li>
                                        <li><strong>Email/SMS:</strong> Nhận thông báo cập nhật</li>
                                        <li><strong>Hotline:</strong> Gọi điện để được hỗ trợ</li>
                                    </ul>
                                    <p class="mt-3">Chúng tôi cam kết cập nhật thông tin hồ sơ một cách minh bạch và kịp thời.</p>
                                </div>
                            </div>

                            <div class="faq-item border-b border-gray-200 pb-6">
                                <button class="faq-question w-full text-left flex items-center justify-between hover:text-orange-600 transition-colors duration-300">
                                    <h4 class="text-lg font-semibold text-gray-900">Nếu hồ sơ bị từ chối thì sao?</h4>
                                    <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"></i>
                                </button>
                                <div class="faq-answer hidden mt-4 text-gray-600 leading-relaxed">
                                    <p>Nếu hồ sơ bị từ chối:</p>
                                    <ul class="list-disc list-inside space-y-2 ml-4 mt-3">
                                        <li>Chúng tôi sẽ giải thích lý do từ chối</li>
                                        <li>Đề xuất giải pháp khắc phục</li>
                                        <li>Hướng dẫn chuẩn bị lại hồ sơ</li>
                                        <li>Đề xuất gói vay phù hợp hơn</li>
                                    </ul>
                                    <p class="mt-3">Bạn có thể nộp lại hồ sơ sau khi đã khắc phục các vấn đề được chỉ ra.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">
                Vẫn còn thắc mắc?
            </h2>
            <p class="text-lg text-gray-600 mb-10 max-w-3xl mx-auto">
                Nếu bạn không tìm thấy câu trả lời cho câu hỏi của mình, 
                đừng ngần ngại liên hệ với đội ngũ tư vấn chuyên nghiệp của chúng tôi.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-phone text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Gọi điện</h3>
                    <p class="text-gray-600 text-sm">Liên hệ trực tiếp với nhân viên tư vấn</p>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-envelope text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Email</h3>
                    <p class="text-gray-600 text-sm">Gửi email để được tư vấn chi tiết</p>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-comments text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Chat trực tuyến</h3>
                    <p class="text-gray-600 text-sm">Chat với bot tư vấn 24/7</p>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('contact') }}" 
                   class="inline-flex items-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-xl font-semibold text-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-phone mr-3"></i>
                    Liên hệ ngay
                </a>
                <a href="{{ route('finance.calculator') }}" 
                   class="inline-flex items-center bg-white border-2 border-blue-600 text-blue-600 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-blue-50 transition-all duration-300">
                    <i class="fas fa-calculator mr-3"></i>
                    Tính toán vay
                </a>
            </div>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryBtns = document.querySelectorAll('.faq-category-btn');
    const categories = document.querySelectorAll('.faq-category');
    const faqQuestions = document.querySelectorAll('.faq-question');

    // Category switching
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;
            
            // Update active button
            categoryBtns.forEach(b => {
                b.classList.remove('active', 'border-blue-500');
                b.classList.add('border-transparent');
            });
            this.classList.add('active', 'border-blue-500');
            this.classList.remove('border-transparent');
            
            // Show selected category
            categories.forEach(cat => {
                cat.classList.add('hidden');
                if (cat.dataset.category === category) {
                    cat.classList.remove('hidden');
                }
            });
        });
    });

    // FAQ accordion
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const answer = this.nextElementSibling;
            const icon = this.querySelector('i');
            
            // Toggle answer
            answer.classList.toggle('hidden');
            
            // Rotate icon
            if (answer.classList.contains('hidden')) {
                icon.style.transform = 'rotate(0deg)';
            } else {
                icon.style.transform = 'rotate(180deg)';
            }
        });
    });
});
</script>
@endpush
