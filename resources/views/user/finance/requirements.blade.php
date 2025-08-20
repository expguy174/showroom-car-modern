@extends('layouts.app')
@section('title', 'Điều kiện vay - AutoLux')
@section('content')

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-indigo-900 via-purple-800 to-pink-900 text-white overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
    
    <div class="relative container mx-auto px-4 py-16 lg:py-20">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 mb-8">
                <i class="fas fa-clipboard-check text-pink-400 mr-3 text-xl"></i>
                <span class="text-sm font-medium">Điều kiện vay rõ ràng</span>
            </div>
            
            <h1 class="text-4xl lg:text-5xl font-bold mb-6 leading-tight">
                Điều kiện & Hồ sơ vay
            </h1>
            
            <p class="text-xl text-indigo-100 max-w-3xl mx-auto leading-relaxed">
                Tìm hiểu các yêu cầu cần thiết để được duyệt khoản vay mua xe. 
                Chúng tôi cam kết quy trình đơn giản và minh bạch.
            </p>
        </div>
    </div>
</section>

<!-- Requirements Overview -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Tổng quan điều kiện vay
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Để được duyệt khoản vay mua xe, bạn cần đáp ứng các điều kiện cơ bản về cá nhân, 
                    tài chính và tài sản đảm bảo. Dưới đây là chi tiết từng yêu cầu.
                </p>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-16">
                <div class="bg-white rounded-2xl p-6 text-center shadow-lg border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-check text-white text-2xl"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 mb-2">18+</div>
                    <div class="text-gray-600">Tuổi tối thiểu</div>
                </div>
                
                <div class="bg-white rounded-2xl p-6 text-center shadow-lg border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 mb-2">8M+</div>
                    <div class="text-gray-600">Thu nhập tối thiểu/tháng</div>
                </div>
                
                <div class="bg-white rounded-2xl p-6 text-center shadow-lg border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-percentage text-white text-2xl"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 mb-2">50%</div>
                    <div class="text-gray-600">Tỷ lệ trả nợ tối đa</div>
                </div>
                
                <div class="bg-white rounded-2xl p-6 text-center shadow-lg border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-white text-2xl"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 mb-2">3-5</div>
                    <div class="text-gray-600">Ngày xử lý hồ sơ</div>
                </div>
            </div>

            <!-- Detailed Requirements -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @foreach($requirements as $key => $group)
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br 
                                @if($key === 'personal') from-blue-500 to-indigo-600
                                @elseif($key === 'financial') from-green-500 to-emerald-600
                                @else from-purple-500 to-pink-600
                                @endif
                                rounded-xl flex items-center justify-center mr-4">
                                <i class="fas 
                                    @if($key === 'personal') fa-user
                                    @elseif($key === 'financial') fa-chart-line
                                    @else fa-shield-alt
                                    @endif
                                    text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $group['title'] }}</h3>
                        </div>
                        
                        <ul class="space-y-4">
                            @foreach($group['items'] as $item)
                            <li class="flex items-start">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                    <i class="fas fa-check text-green-600 text-xs"></i>
                                </div>
                                <span class="text-gray-700 leading-relaxed">{{ $item }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Application Process -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Quy trình đăng ký vay
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Quy trình đăng ký vay mua xe được thiết kế đơn giản và nhanh chóng. 
                    Chỉ cần 4 bước để hoàn tất hồ sơ vay.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="relative">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">
                            1
                        </div>
                        <div class="absolute -right-4 top-8 w-8 h-0.5 bg-gray-300 hidden lg:block"></div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Tư vấn & Chọn gói</h3>
                    <p class="text-gray-600">Liên hệ tư vấn để được tư vấn và chọn gói vay phù hợp nhất</p>
                </div>

                <div class="text-center">
                    <div class="relative">
                        <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">
                            2
                        </div>
                        <div class="absolute -right-4 top-8 w-8 h-0.5 bg-gray-300 hidden lg:block"></div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Chuẩn bị hồ sơ</h3>
                    <p class="text-gray-600">Chuẩn bị đầy đủ các giấy tờ cần thiết theo yêu cầu</p>
                </div>

                <div class="text-center">
                    <div class="relative">
                        <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">
                            3
                        </div>
                        <div class="absolute -right-4 top-8 w-8 h-0.5 bg-gray-300 hidden lg:block"></div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Thẩm định & Duyệt</h3>
                    <p class="text-gray-600">Hồ sơ được thẩm định và duyệt trong vòng 3-5 ngày làm việc</p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">
                        4
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Ký hợp đồng & Giải ngân</h3>
                    <p class="text-gray-600">Ký hợp đồng vay và nhận tiền để mua xe</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Document Checklist -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Danh sách giấy tờ cần thiết
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Để quá trình thẩm định diễn ra nhanh chóng, hãy chuẩn bị đầy đủ các giấy tờ sau
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Personal Documents -->
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-id-card text-blue-600 mr-3"></i>
                            Giấy tờ cá nhân
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center p-4 bg-blue-50 rounded-xl border border-blue-200">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">CMND/CCCD</div>
                                    <div class="text-sm text-gray-600">Còn hiệu lực, rõ ràng</div>
                                </div>
                            </div>
                            
                            <div class="flex items-center p-4 bg-blue-50 rounded-xl border border-blue-200">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Sổ hộ khẩu/KT3</div>
                                    <div class="text-sm text-gray-600">Bản gốc hoặc công chứng</div>
                                </div>
                            </div>
                            
                            <div class="flex items-center p-4 bg-blue-50 rounded-xl border border-blue-200">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Giấy phép lái xe</div>
                                    <div class="text-sm text-gray-600">Còn hiệu lực</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Documents -->
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-chart-line text-green-600 mr-3"></i>
                            Giấy tờ tài chính
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center p-4 bg-green-50 rounded-xl border border-green-200">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Chứng minh thu nhập</div>
                                    <div class="text-sm text-gray-600">3 tháng gần nhất</div>
                                </div>
                            </div>
                            
                            <div class="flex items-center p-4 bg-green-50 rounded-xl border border-green-200">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Sao kê ngân hàng</div>
                                    <div class="text-sm text-gray-600">3 tháng gần nhất</div>
                                </div>
                            </div>
                            
                            <div class="flex items-center p-4 bg-green-50 rounded-xl border border-green-200">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Hợp đồng lao động</div>
                                    <div class="text-sm text-gray-600">Hoặc giấy phép kinh doanh</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 p-6 bg-yellow-50 rounded-xl border border-yellow-200">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-yellow-600 text-xl mr-3 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-yellow-800 mb-2">Lưu ý quan trọng</h4>
                            <p class="text-yellow-700 text-sm">
                                Tất cả giấy tờ phải là bản gốc hoặc bản sao có công chứng. 
                                Hồ sơ không đầy đủ có thể làm chậm quá trình thẩm định. 
                                Nếu bạn cần hỗ trợ chuẩn bị hồ sơ, hãy liên hệ với nhân viên tư vấn của chúng tôi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Câu hỏi thường gặp
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Giải đáp các thắc mắc phổ biến về điều kiện vay mua xe
                </p>
            </div>

            <div class="space-y-6">
                <div class="bg-gray-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">
                        Tôi có thể vay bao nhiêu phần trăm giá trị xe?
                    </h3>
                    <p class="text-gray-600">
                        Bạn có thể vay tối đa 90% giá trị xe, tùy thuộc vào gói vay và khả năng tài chính. 
                        Số tiền trả trước tối thiểu thường từ 10-30% giá trị xe.
                    </p>
                </div>

                <div class="bg-gray-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">
                        Thời gian xử lý hồ sơ vay mất bao lâu?
                    </h3>
                    <p class="text-gray-600">
                        Thời gian xử lý hồ sơ thường từ 3-5 ngày làm việc kể từ khi nhận đủ giấy tờ. 
                        Trong trường hợp đặc biệt, thời gian có thể kéo dài hơn.
                    </p>
                </div>

                <div class="bg-gray-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">
                        Tôi có cần người bảo lãnh không?
                    </h3>
                    <p class="text-gray-600">
                        Việc yêu cầu người bảo lãnh phụ thuộc vào gói vay và khả năng tài chính của bạn. 
                        Một số gói vay đặc biệt có thể yêu cầu người bảo lãnh để tăng khả năng được duyệt.
                    </p>
                </div>

                <div class="bg-gray-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">
                        Lãi suất vay có cố định không?
                    </h3>
                    <p class="text-gray-600">
                        Lãi suất có thể cố định hoặc thả nổi tùy thuộc vào gói vay bạn chọn. 
                        Chúng tôi khuyến nghị bạn nên tham khảo chi tiết từng gói vay để hiểu rõ điều khoản.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-indigo-900 to-purple-900 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl lg:text-4xl font-bold mb-6">
            Sẵn sàng đăng ký vay?
        </h2>
        <p class="text-xl text-indigo-100 mb-10 max-w-3xl mx-auto">
            Đội ngũ tư vấn chuyên nghiệp của chúng tôi sẽ giúp bạn chuẩn bị hồ sơ 
            và chọn gói vay phù hợp nhất.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('finance.calculator') }}" 
               class="inline-flex items-center bg-pink-500 text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-pink-600 transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-calculator mr-3"></i>
                Tính toán khoản vay
            </a>
            <a href="{{ route('contact') }}" 
               class="inline-flex items-center bg-white/20 backdrop-blur-sm text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white/30 transition-all duration-300 border border-white/30">
                <i class="fas fa-phone mr-3"></i>
                Liên hệ tư vấn
            </a>
        </div>
    </div>
</section>

@endsection
