@extends('layouts.app')

@section('title', 'Giới thiệu - AutoLux')

@section('content')

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-emerald-900 via-teal-800 to-cyan-700 text-white overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
    
    <div class="relative container mx-auto px-4 py-20 lg:py-32">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 mb-8">
                <i class="fas fa-info-circle text-emerald-400 mr-3 text-xl"></i>
                <span class="text-sm font-medium">Về chúng tôi</span>
            </div>
            
            <h1 class="text-4xl lg:text-6xl font-bold mb-6 leading-tight">
                Giới thiệu
                <span class="text-emerald-400 block">AutoLux</span>
            </h1>
            
            <p class="text-xl lg:text-2xl text-cyan-100 max-w-3xl mx-auto leading-relaxed">
                Hơn 10 năm kinh nghiệm trong lĩnh vực kinh doanh xe hơi, chúng tôi tự hào là đối tác tin cậy của hàng nghìn khách hàng
            </p>
        </div>
    </div>
</section>

<!-- Company Overview -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <div class="inline-flex items-center bg-emerald-50 text-emerald-700 px-4 py-2 rounded-full text-sm font-semibold mb-6">
                        <i class="fas fa-star mr-2"></i>
                        Thương hiệu uy tín
                    </div>
                    
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6 leading-tight">
                        AutoLux - Nơi gặp gỡ của đam mê xe hơi
                    </h2>
                    
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        Được thành lập vào năm 2013, AutoLux đã trở thành một trong những showroom xe hơi hàng đầu tại Việt Nam. Chúng tôi cam kết mang đến cho khách hàng những sản phẩm chất lượng cao cùng dịch vụ chuyên nghiệp.
                    </p>
                    
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        Với đội ngũ nhân viên giàu kinh nghiệm và tận tâm, chúng tôi không chỉ bán xe mà còn đồng hành cùng khách hàng trong suốt quá trình sở hữu và sử dụng xe.
                    </p>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-emerald-600 mb-2">10+</div>
                            <div class="text-sm text-gray-600">Năm kinh nghiệm</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-emerald-600 mb-2">5000+</div>
                            <div class="text-sm text-gray-600">Khách hàng hài lòng</div>
                        </div>
                    </div>
                </div>
                
                <div class="relative">
                    <div class="bg-gradient-to-br from-emerald-100 to-cyan-100 rounded-3xl p-8">
                        <img src="{{ asset('images/about-showroom.jpg') }}" 
                             alt="Showroom AutoLux" 
                             class="w-full h-80 object-cover rounded-2xl shadow-2xl">
                    </div>
                    <div class="absolute -bottom-6 -right-6 bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-award text-white text-xl"></i>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">Chứng nhận ISO 9001</div>
                                <div class="text-sm text-gray-600">Quản lý chất lượng</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-20 bg-gradient-to-r from-gray-50 to-gray-100">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Sứ mệnh & Tầm nhìn
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Chúng tôi không ngừng phấn đấu để trở thành đối tác tin cậy nhất trong lĩnh vực xe hơi
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-bullseye text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Sứ mệnh</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Cung cấp những sản phẩm xe hơi chất lượng cao với giá cả hợp lý, đồng thời mang đến trải nghiệm mua sắm tuyệt vời và dịch vụ hậu mãi chuyên nghiệp cho mọi khách hàng.
                    </p>
                </div>
                
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-eye text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Tầm nhìn</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Trở thành hệ thống showroom xe hơi hàng đầu Việt Nam, được khách hàng tin tưởng và lựa chọn đầu tiên khi có nhu cầu mua sắm xe hơi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Core Values -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Giá trị cốt lõi
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Những nguyên tắc không thay đổi trong cách chúng tôi làm việc và phục vụ khách hàng
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center group">
                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-handshake text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Uy tín</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Cam kết thực hiện đúng những gì đã hứa với khách hàng
                    </p>
                </div>
                
                <div class="text-center group">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-shield-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Chất lượng</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Đảm bảo mọi sản phẩm đều đạt tiêu chuẩn chất lượng cao nhất
                    </p>
                </div>
                
                <div class="text-center group">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-heart text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Tận tâm</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Phục vụ khách hàng với tất cả sự nhiệt tình và chu đáo
                    </p>
                </div>
                
                <div class="text-center group">
                    <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-lightbulb text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Sáng tạo</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Không ngừng đổi mới để mang đến trải nghiệm tốt nhất
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-20 bg-gradient-to-r from-gray-50 to-gray-100">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Đội ngũ chuyên gia
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Những con người tài năng và tận tâm đang ngày đêm phục vụ khách hàng
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 text-center group hover:shadow-xl transition-all duration-300">
                    <div class="w-24 h-24 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-user-tie text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Nguyễn Văn A</h3>
                    <p class="text-emerald-600 font-semibold mb-3">Giám đốc điều hành</p>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        15 năm kinh nghiệm trong lĩnh vực kinh doanh xe hơi, chuyên gia về chiến lược phát triển thị trường.
                    </p>
                </div>
                
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 text-center group hover:shadow-xl transition-all duration-300">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-cogs text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Trần Thị B</h3>
                    <p class="text-blue-600 font-semibold mb-3">Trưởng phòng kỹ thuật</p>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Chuyên gia kỹ thuật với 12 năm kinh nghiệm, đảm bảo chất lượng mọi sản phẩm trước khi giao đến khách hàng.
                    </p>
                </div>
                
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 text-center group hover:shadow-xl transition-all duration-300">
                    <div class="w-24 h-24 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-headset text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Lê Văn C</h3>
                    <p class="text-purple-600 font-semibold mb-3">Trưởng phòng CSKH</p>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Chuyên gia về chăm sóc khách hàng với 10 năm kinh nghiệm, luôn sẵn sàng hỗ trợ mọi yêu cầu của khách hàng.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Achievements -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Thành tựu & Giải thưởng
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Những cột mốc quan trọng trong hành trình phát triển của AutoLux
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-4xl lg:text-5xl font-bold text-emerald-600 mb-4">2013</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Thành lập công ty</h3>
                    <p class="text-gray-600 text-sm">AutoLux chính thức được thành lập với showroom đầu tiên tại Hà Nội</p>
                </div>
                
                <div class="text-center">
                    <div class="text-4xl lg:text-5xl font-bold text-blue-600 mb-4">2016</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Mở rộng thị trường</h3>
                    <p class="text-gray-600 text-sm">Mở thêm 3 showroom tại các thành phố lớn</p>
                </div>
                
                <div class="text-center">
                    <div class="text-4xl lg:text-5xl font-bold text-purple-600 mb-4">2019</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Chứng nhận ISO</h3>
                    <p class="text-gray-600 text-sm">Đạt chứng nhận ISO 9001:2015 về quản lý chất lượng</p>
                </div>
                
                <div class="text-center">
                    <div class="text-4xl lg:text-5xl font-bold text-orange-600 mb-4">2023</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Top 10 showroom</h3>
                    <p class="text-gray-600 text-sm">Lọt vào top 10 showroom xe hơi uy tín nhất Việt Nam</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-emerald-600 to-teal-600 text-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl lg:text-4xl font-bold mb-6">
                Sẵn sàng trải nghiệm dịch vụ của chúng tôi?
            </h2>
            <p class="text-xl text-emerald-100 mb-8 max-w-2xl mx-auto">
                Hãy để AutoLux đồng hành cùng bạn trong hành trình tìm kiếm chiếc xe mơ ước
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('contact') }}" 
                   class="inline-flex items-center bg-white text-emerald-600 px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-phone mr-2"></i>
                    Liên hệ ngay
                </a>
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-emerald-600 transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-car mr-2"></i>
                    Xem xe ngay
                </a>
            </div>
        </div>
    </div>
</section>

@endsection 