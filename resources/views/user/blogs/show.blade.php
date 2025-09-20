@extends('layouts.app')

@section('title', $blog->title . ' - AutoLux')

@section('content')

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white overflow-hidden">
    <div class="absolute inset-0 bg-black/40"></div>
    
    <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 mb-8">
                <i class="fas fa-newspaper text-blue-400 mr-3 text-xl"></i>
                <span class="text-sm font-medium">Blog</span>
            </div>
            
            <h1 class="text-3xl lg:text-5xl font-bold mb-6 leading-tight">
                {{ $blog->title }}
            </h1>
            
            <div class="flex items-center justify-center text-gray-300 space-x-6">
                <div class="flex items-center">
                    <i class="fas fa-calendar-alt text-blue-400 mr-2"></i>
                    <span>{{ $blog->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-clock text-blue-400 mr-2"></i>
                    <span>{{ $blog->created_at->diffForHumans() }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-user text-blue-400 mr-2"></i>
                    <span>Admin</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog Content -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center space-x-2 text-sm text-gray-600">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors duration-300">
                            <i class="fas fa-home mr-1"></i>Trang chủ
                        </a>
                    </li>
                    <li><i class="fas fa-chevron-right text-gray-400"></i></li>
                    <li>
                        <a href="{{ route('blogs.index') }}" class="hover:text-blue-600 transition-colors duration-300">
                            Blog
                        </a>
                    </li>
                    <li><i class="fas fa-chevron-right text-gray-400"></i></li>
                    <li class="text-gray-900 font-medium">{{ Str::limit($blog->title, 50) }}</li>
                </ol>
            </nav>

            <!-- Featured Image -->
            <div class="relative mb-12">
                <div class="relative h-64 lg:h-96 bg-gray-200 rounded-3xl overflow-hidden">
                    <img src="{{ $blog->image_url ?? asset('images/default-blog.jpg') }}" 
                         alt="{{ $blog->title }}" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                </div>
                
                <!-- Share Buttons -->
                <div class="absolute top-6 right-6 flex space-x-3">
                    <button onclick="shareOnFacebook()" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white hover:bg-blue-700 transition-colors duration-300">
                        <i class="fab fa-facebook-f"></i>
                    </button>
                    <button onclick="shareOnTwitter()" class="w-10 h-10 bg-blue-400 rounded-full flex items-center justify-center text-white hover:bg-blue-500 transition-colors duration-300">
                        <i class="fab fa-twitter"></i>
                    </button>
                    <button onclick="shareOnLinkedIn()" class="w-10 h-10 bg-blue-700 rounded-full flex items-center justify-center text-white hover:bg-blue-800 transition-colors duration-300">
                        <i class="fab fa-linkedin-in"></i>
                    </button>
                </div>
            </div>

            <!-- Article Content -->
            <article class="prose prose-lg max-w-none">
                <div class="text-gray-700 leading-relaxed text-lg">
                    {!! nl2br(e($blog->content)) !!}
                </div>
            </article>

            <!-- Article Footer -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">Chia sẻ:</span>
                        <div class="flex space-x-3">
                            <button onclick="shareOnFacebook()" class="text-blue-600 hover:text-blue-700 transition-colors duration-300">
                                <i class="fab fa-facebook-f text-lg"></i>
                            </button>
                            <button onclick="shareOnTwitter()" class="text-blue-400 hover:text-blue-500 transition-colors duration-300">
                                <i class="fab fa-twitter text-lg"></i>
                            </button>
                            <button onclick="shareOnLinkedIn()" class="text-blue-700 hover:text-blue-800 transition-colors duration-300">
                                <i class="fab fa-linkedin-in text-lg"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">Tags:</span>
                        <div class="flex space-x-2">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">Xe hơi</span>
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">Thị trường</span>
                            <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium">Công nghệ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Articles -->
@if($recentBlogs->count() > 0)
<section class="py-16 bg-gradient-to-r from-gray-50 to-gray-100">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Bài viết liên quan
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Khám phá thêm những bài viết thú vị khác từ AutoLux
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($recentBlogs as $relatedBlog)
                <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ $relatedBlog->image_url ?? asset('images/default-blog.jpg') }}" 
                             alt="{{ $relatedBlog->title }}" 
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
                            {{ $relatedBlog->created_at->format('d/m/Y') }}
                            <span class="mx-2 text-gray-300">•</span>
                            <i class="fas fa-clock text-blue-500 mr-2"></i>
                            {{ $relatedBlog->created_at->diffForHumans() }}
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-3 leading-tight hover:text-blue-600 transition-colors duration-300">
                            <a href="{{ route('blogs.show', $relatedBlog->id) }}">
                                {{ Str::limit($relatedBlog->title, 60) }}
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 mb-4 leading-relaxed">
                            {{ Str::limit($relatedBlog->content, 120) }}
                        </p>
                        
                        <a href="{{ route('blogs.show', $relatedBlog->id) }}" 
                           class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-700 transition-colors duration-300 group">
                            Đọc thêm 
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                        </a>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- Newsletter Section -->
<section class="py-16 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 lg:p-12 border border-white/20">
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-envelope text-white text-2xl"></i>
                </div>
                
                <h3 class="text-2xl lg:text-3xl font-bold mb-4">
                    Đăng ký nhận tin tức
                </h3>
                
                <p class="text-lg text-blue-100 mb-8 max-w-2xl mx-auto">
                    Nhận những tin tức mới nhất về xe hơi, khuyến mãi và sự kiện đặc biệt từ AutoLux
                </p>
                
                <form class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                    <input type="email" 
                           placeholder="Nhập email của bạn" 
                           class="flex-1 px-4 py-3 border border-white/30 rounded-xl focus:ring-2 focus:ring-white focus:border-white bg-white/10 text-white placeholder-white/70">
                    <button type="submit" 
                            class="bg-white text-blue-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Đăng ký
                    </button>
                </form>
                
                <p class="text-sm text-blue-200 mt-4">
                    Chúng tôi cam kết không gửi spam. Bạn có thể hủy đăng ký bất cứ lúc nào.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">
                Sẵn sàng tìm xe mơ ước?
            </h2>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                Hãy để AutoLux đồng hành cùng bạn trong hành trình tìm kiếm chiếc xe hoàn hảo
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-car mr-2"></i>
                    Xem xe ngay
                </a>
                <a href="{{ route('contact') }}" 
                   class="inline-flex items-center border-2 border-blue-600 text-blue-600 px-8 py-4 rounded-xl font-semibold hover:bg-blue-600 hover:text-white transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-phone mr-2"></i>
                    Liên hệ tư vấn
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// Social sharing functions
function shareOnFacebook() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent('{{ $blog->title }}');
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
}

function shareOnTwitter() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent('{{ $blog->title }}');
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${title}`, '_blank');
}

function shareOnLinkedIn() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent('{{ $blog->title }}');
    window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}`, '_blank');
}

// Add smooth scrolling for anchor links
document.addEventListener('DOMContentLoaded', function() {
    // Add any additional JavaScript functionality here
    console.log('Blog detail page loaded');
});
</script>
@endpush 