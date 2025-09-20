@extends('layouts.app')

@section('title', 'Tin tức & Blog - AutoLux')

@section('content')

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-purple-900 via-blue-800 to-indigo-900 text-white overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
    
    <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 mb-8">
                <i class="fas fa-newspaper text-purple-400 mr-3 text-xl"></i>
                <span class="text-sm font-medium">Cập nhật thông tin mới nhất</span>
            </div>
            
            <h1 class="text-4xl lg:text-6xl font-bold mb-6 leading-tight">
                Tin tức
                <span class="text-purple-400 block">AutoLux</span>
            </h1>
            
            <p class="text-xl lg:text-2xl text-blue-100 max-w-3xl mx-auto leading-relaxed">
                Khám phá những tin tức mới nhất về xe hơi, thị trường, công nghệ và xu hướng trong ngành ô tô
            </p>
        </div>
    </div>
</section>

<!-- Featured Blog Section -->
@if($blogs->count() > 0)
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Bài viết nổi bật
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Những bài viết mới nhất và được quan tâm nhiều nhất từ đội ngũ chuyên gia của chúng tôi
                </p>
            </div>

            <!-- Featured Blog (First Blog) -->
            @if($blogs->first())
            <div class="mb-16">
                <article class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                        <div class="relative h-64 lg:h-full overflow-hidden">
                            <img src="{{ $blogs->first()->image_url ?? asset('images/default-blog.jpg') }}" 
                                 alt="{{ $blogs->first()->title }}" 
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                            <div class="absolute top-6 left-6">
                                <span class="bg-purple-600 text-white px-4 py-2 rounded-full text-sm font-semibold">
                                    <i class="fas fa-star mr-2"></i>Nổi bật
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-8 lg:p-12 flex flex-col justify-center">
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <i class="fas fa-calendar-alt text-purple-500 mr-2"></i>
                                {{ $blogs->first()->created_at->format('d/m/Y') }}
                                <span class="mx-3 text-gray-300">•</span>
                                <i class="fas fa-clock text-purple-500 mr-2"></i>
                                {{ $blogs->first()->created_at->diffForHumans() }}
                            </div>
                            
                            <h3 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4 leading-tight hover:text-purple-600 transition-colors duration-300">
                                <a href="{{ route('blogs.show', $blogs->first()->id) }}">
                                    {{ $blogs->first()->title }}
                                </a>
                            </h3>
                            
                            <p class="text-gray-600 mb-6 leading-relaxed text-lg">
                                {{ Str::limit($blogs->first()->content, 200) }}
                            </p>
                            
                            <a href="{{ route('blogs.show', $blogs->first()->id) }}" 
                               class="inline-flex items-center bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-purple-700 hover:to-blue-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-book-open mr-2"></i>
                                Đọc bài viết đầy đủ
                            </a>
                        </div>
                    </div>
                </article>
            </div>
            @endif

            <!-- Other Blogs Grid -->
            @if($blogs->count() > 1)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($blogs->skip(1) as $blog)
                <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ $blog->image_url ?? asset('images/default-blog.jpg') }}" 
                             alt="{{ $blog->title }}" 
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                        <div class="absolute top-4 left-4">
                            <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                Blog
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center text-sm text-gray-500 mb-3">
                            <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                            {{ $blog->created_at->format('d/m/Y') }}
                            <span class="mx-2 text-gray-300">•</span>
                            <i class="fas fa-clock text-blue-500 mr-2"></i>
                            {{ $blog->created_at->diffForHumans() }}
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-3 leading-tight hover:text-blue-600 transition-colors duration-300">
                            <a href="{{ route('blogs.show', $blog->id) }}">
                                {{ Str::limit($blog->title, 60) }}
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 mb-4 leading-relaxed">
                            {{ Str::limit($blog->content, 120) }}
                        </p>
                        
                        <a href="{{ route('blogs.show', $blog->id) }}" 
                           class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-700 transition-colors duration-300 group">
                            Đọc thêm 
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                        </a>
                    </div>
                </article>
                @endforeach
            </div>
            @endif

            <!-- Pagination -->
            @if($blogs->hasPages())
            <div class="mt-16 flex justify-center">
                <div class="flex items-center space-x-2">
                    @if($blogs->onFirstPage())
                        <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-left mr-2"></i>Trước
                        </span>
                    @else
                        <a href="{{ $blogs->previousPageUrl() }}" class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-chevron-left mr-2"></i>Trước
                        </a>
                    @endif

                    @foreach($blogs->getUrlRange(1, $blogs->lastPage()) as $page => $url)
                        @if($page == $blogs->currentPage())
                            <span class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($blogs->hasMorePages())
                        <a href="{{ $blogs->nextPageUrl() }}" class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            Sau<i class="fas fa-chevron-right ml-2"></i>
                        </a>
                    @else
                        <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            Sau<i class="fas fa-chevron-right ml-2"></i>
                        </span>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- Newsletter Section -->
<section class="py-16 bg-gradient-to-r from-purple-50 to-blue-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <div class="bg-white rounded-2xl shadow-xl p-8 lg:p-12">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-envelope text-white text-2xl"></i>
                </div>
                
                <h3 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4">
                    Đăng ký nhận tin tức
                </h3>
                
                <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                    Nhận những tin tức mới nhất về xe hơi, khuyến mãi và sự kiện đặc biệt từ AutoLux
                </p>
                
                <form class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                    <input type="email" 
                           placeholder="Nhập email của bạn" 
                           class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300">
                    <button type="submit" 
                            class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-purple-700 hover:to-blue-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Đăng ký
                    </button>
                </form>
                
                <p class="text-sm text-gray-500 mt-4">
                    Chúng tôi cam kết không gửi spam. Bạn có thể hủy đăng ký bất cứ lúc nào.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Chủ đề tin tức
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Khám phá tin tức theo chủ đề bạn quan tâm
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 text-center hover:shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-car text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Xe hơi</h3>
                    <p class="text-gray-600 text-sm">Tin tức về các mẫu xe mới, đánh giá và so sánh</p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 text-center hover:shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Thị trường</h3>
                    <p class="text-gray-600 text-sm">Phân tích thị trường và xu hướng tiêu dùng</p>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 text-center hover:shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-cogs text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Công nghệ</h3>
                    <p class="text-gray-600 text-sm">Công nghệ mới trong ngành ô tô</p>
                </div>

                <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl p-6 text-center hover:shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-tools text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Bảo dưỡng</h3>
                    <p class="text-gray-600 text-sm">Hướng dẫn bảo dưỡng và chăm sóc xe</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection 