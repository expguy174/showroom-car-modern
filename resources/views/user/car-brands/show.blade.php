@extends('layouts.app')

@section('title', $brand->name . ' - Hãng xe')

@section('content')
<!-- Error Message -->
@if(session('error'))
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10" aria-hidden="true">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, white 2px, transparent 2px), radial-gradient(circle at 75% 75%, white 2px, transparent 2px); background-size: 48px 48px;"></div>
    </div>
    
    <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 pt-16 sm:pt-20 pb-16 sm:pb-24">
        <!-- Breadcrumb -->
        <nav class="mb-8 sm:mb-10 text-sm text-gray-300/80" aria-label="Breadcrumb">
            <ol class="inline-flex items-center gap-2">
                <li>
                    <a href="/" class="hover:text-white transition-colors duration-200 flex items-center gap-1">
                        <i class="fas fa-home"></i>
                        <span class="hidden sm:inline">Trang chủ</span>
                    </a>
                </li>
                <li class="text-gray-400">/</li>
                <li>
                    <a href="{{ route('car-brands.index') }}" class="hover:text-white transition-colors duration-200">Hãng xe</a>
                </li>
                <li class="text-gray-400">/</li>
                <li class="text-white font-medium">{{ $brand->name }}</li>
            </ol>
        </nav>

        <!-- Brand Header -->
        <div class="grid grid-cols-1 xl:grid-cols-[auto,1fr] items-center gap-8 xl:gap-12">
            <!-- Brand Logo & Info -->
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                <!-- Logo -->
                @php $logo = $brand->logo_url ?? null; @endphp
                @if($logo)
                    <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-3xl bg-white/95 backdrop-blur-sm ring-2 ring-white/20 shadow-2xl flex items-center justify-center overflow-hidden">
                        <img src="{{ $logo }}" alt="{{ $brand->name }}" class="w-20 h-20 sm:w-28 sm:h-28 object-contain" loading="lazy" decoding="async">
                    </div>
                @else
                    <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-3xl bg-white/95 backdrop-blur-sm ring-2 ring-white/20 shadow-2xl flex items-center justify-center">
                        <i class="fas fa-car text-gray-700 text-4xl sm:text-5xl"></i>
                    </div>
                @endif
                
                <!-- Brand Basic Info -->
                <div class="flex-1">
                    <h1 class="text-4xl sm:text-5xl xl:text-6xl font-extrabold text-white tracking-tight mb-4">
                        {{ $brand->name }}
                    </h1>
                    
                    <!-- Stats Badges -->
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm text-white text-sm font-semibold border border-white/20">
                            <i class="fas fa-layer-group text-blue-300"></i>
                            {{ number_format($totalModelsCount ?? $models->count()) }} dòng xe
                        </span>
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm text-white text-sm font-semibold border border-white/20">
                            <i class="fas fa-cubes text-purple-300"></i>
                            {{ $stats['total_variants'] ?? 0 }} phiên bản
                        </span>
                        @if(!empty($stats['price_range']['min']) && !empty($stats['price_range']['max']))
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-400/20 backdrop-blur-sm text-green-200 text-sm font-semibold border border-green-400/30">
                                <i class="fas fa-tag"></i>
                                Từ {{ number_format($stats['price_range']['min'], 0, ',', '.') }}₫
                            </span>
                        @endif
                    </div>
                    
                    <!-- Description -->
                    @if(!empty($brand->description))
                        <p class="text-lg text-gray-300 leading-relaxed max-w-2xl">
                            {{ $brand->description }}
                        </p>
                    @else
                        <p class="text-lg text-gray-300 leading-relaxed max-w-2xl">
                            {{ $brand->name }} là một trong những thương hiệu xe hơi uy tín với nhiều dòng xe đa dạng, đáp ứng nhu cầu từ gia đình đến cao cấp.
                        </p>
                    @endif
                </div>
            </div>

            <!-- Brand Details Card -->
            <div class="xl:ml-auto">
                <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-6 sm:p-8 border border-white/20 shadow-2xl">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-300"></i>
                        Thông tin hãng
                    </h3>
                    
                    <div class="space-y-4">
                        @if(!empty($brand->country))
                            <div class="flex items-center gap-3 text-gray-300">
                                <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center">
                                    <i class="fas fa-flag text-white text-sm"></i>
                                </div>
                                <span>Quốc gia: <span class="font-semibold text-white">{{ $brand->country }}</span></span>
                            </div>
                        @endif
                        
                        @if(!empty($brand->founded_year))
                            <div class="flex items-center gap-3 text-gray-300">
                                <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center">
                                    <i class="fas fa-calendar text-white text-sm"></i>
                                </div>
                                <span>Năm thành lập: <span class="font-semibold text-white">{{ $brand->founded_year }}</span></span>
                            </div>
                        @endif
                        
                        @if(!empty($brand->website))
                            @php
                                $web = $brand->website;
                                if (!\Illuminate\Support\Str::startsWith($web, ['http://','https://'])) {
                                    $web = 'https://' . $web;
                                }
                            @endphp
                            <div class="flex items-center gap-3 text-gray-300">
                                <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center">
                                    <i class="fas fa-globe text-white text-sm"></i>
                                </div>
                                <span>Website: <a href="{{ $web }}" target="_blank" rel="noopener noreferrer nofollow" class="font-semibold text-white hover:text-blue-300 break-all transition-colors duration-200">{{ $brand->website }}</a></span>
                            </div>
                        @endif
                        
                        @if(!empty($brand->phone))
                            <div class="flex items-center gap-3 text-gray-300">
                                <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center">
                                    <i class="fas fa-phone text-white text-sm"></i>
                                </div>
                                <span>Điện thoại: <span class="font-semibold text-white">{{ $brand->phone }}</span></span>
                            </div>
                        @endif
                        
                        @if(!empty($brand->email))
                            <div class="flex items-center gap-3 text-gray-300">
                                <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center">
                                    <i class="fas fa-envelope text-white text-sm"></i>
                                </div>
                                <span>Email: <span class="font-semibold text-white">{{ $brand->email }}</span></span>
                            </div>
                        @endif
                        
                        @if(!empty($brand->address))
                            <div class="flex items-start gap-3 text-gray-300">
                                <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center mt-1">
                                    <i class="fas fa-map-marker-alt text-white text-sm"></i>
                                </div>
                                <span>Trụ sở: <span class="font-semibold text-white whitespace-pre-line">{{ $brand->address }}</span></span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sticky Navigation -->
    <div class="border-t border-white/10 bg-white/5 backdrop-blur-md">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-6 text-sm text-white/90">
                <a href="#models" class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 hover:bg-white/20 transition-all duration-200 border border-white/20 hover:border-white/30">
                    <i class="fas fa-layer-group"></i>
                    <span>Dòng xe & Phiên bản</span>
                </a>
                @if($featuredVariants->count() > 0)
                    <a href="#featured" class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 hover:bg-white/20 transition-all duration-200 border border-white/20 hover:border-white/30">
                        <i class="fas fa-star"></i>
                        <span>Xe nổi bật</span>
                    </a>
                @endif
                <a href="#contact" class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 hover:bg-white/20 transition-all duration-200 border border-white/20 hover:border-white/30">
                    <i class="fas fa-phone"></i>
                    <span>Liên hệ</span>
                </a>
            </div>
        </div>
    </div>
</section>

 

<!-- Models and Variants Section -->
<section id="models" class="py-20 bg-gradient-to-b from-white to-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-10 sm:mb-12">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-100 text-indigo-700 text-sm font-semibold mb-4">
                <i class="fas fa-layer-group mr-2"></i>
                Dòng xe & Phiên bản
            </div>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3">
                Chọn dòng xe để xem phiên bản
            </h2>
            <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto">
                Dòng xe hiển thị theo hàng ngang, bên dưới là các phiên bản tương ứng. Bạn cũng có thể xem chi tiết dòng xe.
            </p>
        </div>

        @php
            $modelsWithVariants = $models->filter(function($m){ return $m->carVariants && $m->carVariants->count() > 0; })->values();
            $activeModelId = optional($modelsWithVariants->first())->id;
        @endphp

        @if($modelsWithVariants->count() === 0)
            <!-- Empty State -->
            <div class="text-center py-20">
                <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-8">
                    <i class="fas fa-car-side text-gray-400 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">Chưa có dòng xe nào</h3>
                <p class="text-gray-600 text-lg mb-8">Vui lòng quay lại sau hoặc liên hệ để biết thêm thông tin</p>
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition-colors duration-200">
                    <i class="fas fa-phone"></i>
                    Liên hệ tư vấn
                </a>
            </div>
        @else
            <!-- Models Row (horizontal) -->
            <div class="mb-8">
                <div class="flex items-stretch gap-4 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent snap-x">
                    @foreach($modelsWithVariants as $m)
                        <div class="js-model-card relative snap-start flex-shrink-0 w-[320px] sm:w-[360px] bg-white rounded-2xl border {{ $m->id === $activeModelId ? 'border-indigo-300 ring-2 ring-indigo-200' : 'border-gray-200' }} shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer" data-model-id="{{ $m->id }}">
                            <div class="relative aspect-[4/3] rounded-t-2xl overflow-hidden">
                                <img src="{{ $m->image_url }}" alt="{{ $m->name }}" class="w-full h-full object-cover">
                            </div>
                            <a href="{{ route('car-models.show', $m->id) }}" class="absolute top-2 right-2 z-20 inline-flex items-center px-2.5 py-1 rounded-full text-[12px] bg-white/95 text-gray-800 border border-gray-200 shadow-sm hover:bg-white"><i class="fas fa-external-link-alt mr-1"></i>Xem chi tiết</a>
                            <div class="p-4">
                                <h3 class="text-lg font-bold text-gray-900 mb-1 line-clamp-1">{{ $m->name }}</h3>
                                <div class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $m->description ?? ($brand->name . ' ' . $m->name) }}</div>
                                <div class="flex flex-wrap gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[12px] bg-indigo-50 text-indigo-700 border border-indigo-100"><i class="fas fa-tag mr-1"></i>{{ !empty($m->starting_price) ? (number_format($m->starting_price, 0, ',', '.') . '₫') : 'Liên hệ' }}</span>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[12px] bg-emerald-50 text-emerald-700 border border-emerald-100"><i class="fas fa-layer-group mr-1"></i>{{ $m->carVariants?->count() ?? 0 }} phiên bản</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Variants Row (horizontal per selected model) -->
            @foreach($modelsWithVariants as $m)
                @php $variants = $m->carVariants; @endphp
                <div class="js-variants-row {{ $m->id === $activeModelId ? '' : 'hidden' }}" data-model-id="{{ $m->id }}">
                    <div class="flex items-stretch gap-4 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent snap-x">
                        @foreach($variants as $variant)
                            <div class="snap-start flex-shrink-0 w-[280px] sm:w-[320px]">
                                @include('components.variant-card', ['variant' => $variant, 'showCompare' => true])
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</section>

<!-- Featured Variants Section -->
@if($featuredVariants->count() > 0)
<section id="featured" class="py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 text-sm font-semibold mb-4">
                <i class="fas fa-star mr-2"></i>
                Xe nổi bật
            </div>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                Những phiên bản được quan tâm
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Khám phá các mẫu xe {{ $brand->name }} được khách hàng yêu thích nhất
            </p>
        </div>

        <!-- Featured Variants Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8">
            @foreach($featuredVariants as $variant)
                <div class="group transform hover:-translate-y-2 transition-all duration-300">
                    @include('components.variant-card', ['variant' => $variant, 'showCompare' => true])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Contact & CTA Section -->
<section id="contact" class="py-20 sm:py-28 bg-gradient-to-br from-indigo-900 via-purple-900 to-slate-900 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10" aria-hidden="true">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, white 2px, transparent 2px), radial-gradient(circle at 75% 75%, white 2px, transparent 2px); background-size: 48px 48px;"></div>
    </div>
    
    <div class="relative container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl sm:text-4xl xl:text-5xl font-bold text-white mb-6">
                Quan tâm đến {{ $brand->name }}?
            </h2>
            <p class="text-lg sm:text-xl text-gray-300 mb-12 max-w-3xl mx-auto leading-relaxed">
                Khám phá các mẫu xe đang có và nhận tư vấn từ đội ngũ chuyên gia giàu kinh nghiệm của chúng tôi. 
                Chúng tôi sẽ giúp bạn tìm được chiếc xe phù hợp nhất.
            </p>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                <a href="{{ route('products.index', ['brand' => $brand->id, 'type' => 'car']) }}"
                   class="group bg-white text-slate-900 px-10 py-5 rounded-2xl font-bold text-lg transition-all duration-300 hover:bg-purple-100 hover:scale-105 shadow-2xl shadow-black/20">
                    <i class="fas fa-car mr-3 group-hover:rotate-12 transition-transform duration-300"></i>
                    Xem tất cả xe {{ $brand->name }}
                </a>
                
                <a href="{{ route('contact') }}"
                   class="border-2 border-white/30 text-white px-10 py-5 rounded-2xl font-bold text-lg hover:bg-white/10 transition-all duration-300 backdrop-blur-sm hover:border-white/50">
                    <i class="fas fa-phone mr-3"></i>
                    Liên hệ tư vấn miễn phí
                </a>
            </div>
            
            <!-- Additional Info -->
            <div class="mt-12 grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
                <div class="text-white/80">
                    <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-2xl"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Bảo hành chính hãng</h3>
                    <p class="text-sm">Đảm bảo chất lượng và dịch vụ hậu mãi tốt nhất</p>
                </div>
                
                <div class="text-white/80">
                    <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-tools text-2xl"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Dịch vụ bảo dưỡng</h3>
                    <p class="text-sm">Đội ngũ kỹ thuật viên chuyên nghiệp, giàu kinh nghiệm</p>
                </div>
                
                <div class="text-white/80">
                    <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-handshake text-2xl"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Tư vấn tận tâm</h3>
                    <p class="text-sm">Hỗ trợ chọn xe phù hợp với nhu cầu và ngân sách</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* Custom scrollbar */
.scrollbar-thin::-webkit-scrollbar {
    height: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: transparent;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background-color: rgba(156, 163, 175, 0.5);
    border-radius: 9999px;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background-color: rgba(156, 163, 175, 0.8);
}

/* Smooth transitions */
.model-tab {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.variants-row {
    transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
}

/* Hover effects */
.group:hover .variant-card {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Responsive improvements */
@media (max-width: 640px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}

/* Animation classes */
.fade-in {
    animation: fadeIn 0.6s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@push('scripts')
<script>
// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            e.preventDefault();
            const headerOffset = 100; // Account for sticky header
            const elementPosition = target.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }
    });
});

// Model tabs functionality
document.addEventListener('click', function(e) {
    const card = e.target.closest('.js-model-card');
    if (!card) return;
    const id = card.getAttribute('data-model-id');
    
    // Update card highlight
    document.querySelectorAll('.js-model-card').forEach(c => {
        c.classList.remove('ring-2', 'ring-indigo-200', 'border-indigo-300');
        c.classList.add('border-gray-200');
    });
    card.classList.remove('border-gray-200');
    card.classList.add('ring-2', 'ring-indigo-200', 'border-indigo-300');
    
    // Toggle rows
    document.querySelectorAll('.js-variants-row').forEach(row => row.classList.add('hidden'));
    const target = document.querySelector('.js-variants-row[data-model-id="' + id + '"]');
    if (target) target.classList.remove('hidden');
});

// Intersection Observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('fade-in');
        }
    });
}, observerOptions);

// Observe elements for animation
document.addEventListener('DOMContentLoaded', function() {
    const animatedElements = document.querySelectorAll('.js-variants-row, .featured-variants');
    animatedElements.forEach(el => observer.observe(el));
});

// Smooth scroll selected card into view on mobile
document.addEventListener('click', function(e){
    const card = e.target.closest('.js-model-card');
    if (!card) return;
    if (window.innerWidth <= 768) {
        card.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    }
});
</script>
@endpush
