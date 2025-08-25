@extends('layouts.app')

@section('title', 'Hãng xe - AutoLux')

@section('content')
<!-- Hero Section -->
@php
    $totalBrands = $brands->count();
    $featuredCount = $brands->where('is_featured', 1)->count();
    $countryCount = $brands->pluck('country')->filter()->unique()->count();
@endphp
<section class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <div class="absolute inset-0 opacity-10" aria-hidden="true" style="background-image: radial-gradient(circle at 25% 25%, white 2px, transparent 2px), radial-gradient(circle at 75% 75%, white 2px, transparent 2px); background-size: 48px 48px;"></div>
    <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 pt-14 sm:pt-16 pb-14 sm:pb-20">
        <!-- Breadcrumb -->
        <nav class="mb-6 sm:mb-8 text-sm text-gray-300/80" aria-label="Breadcrumb">
            <ol class="inline-flex items-center gap-2">
                <li>
                    <a href="/" class="hover:text-white transition-colors"><i class="fas fa-home mr-1"></i>Trang chủ</a>
                </li>
                <li class="text-gray-400">/</li>
                <li class="text-white">Hãng xe</li>
            </ol>
        </nav>

        <div class="max-w-4xl">
            <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight mb-3 sm:mb-4">Hãng xe</h1>
            <p class="text-base sm:text-lg text-gray-300/90 leading-relaxed max-w-2xl">
                Khám phá các thương hiệu xe hơi uy tín, xem logo nhận diện, số dòng xe, và bắt đầu hành trình chọn chiếc xe phù hợp.
            </p>
        </div>

        <!-- Stats + Search -->
        <div class="mt-8 sm:mt-10 flex flex-col lg:flex-row lg:items-center gap-4 lg:gap-6">
            <!-- Stats -->
            <div class="flex items-center gap-3 sm:gap-4">
                <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-white/10 text-white text-sm font-medium">
                    <i class="fas fa-industry text-white/80"></i>
                    {{ $totalBrands }} hãng
                </span>
                <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-yellow-400/10 text-yellow-200 text-sm font-medium">
                    <i class="fas fa-star"></i>
                    {{ $featuredCount }} nổi bật
                </span>
                <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-white/10 text-white text-sm font-medium">
                    <i class="fas fa-globe-asia text-white/80"></i>
                    {{ $countryCount }} quốc gia
                </span>
            </div>

            <!-- Search -->
            <div class="w-full lg:max-w-md lg:ml-auto">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-white/60"></i>
                    <input type="text" id="brand-search" placeholder="Tìm kiếm hãng xe..." 
                           class="w-full pl-11 pr-4 py-3 rounded-full text-sm sm:text-base bg-white/10 text-white placeholder-white/60 border border-white/20 focus:outline-none focus:ring-4 focus:ring-indigo-400/40 focus:border-white/30">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Error Message -->
@if(session('error'))
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
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

<!-- Brands Grid Section -->
<section class="py-20 bg-white">
    <div id="brands-grid" class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Alphabetical Navigation (sticky for desktop) -->
        <div class="mb-12 sticky top-16 z-10 bg-white/80 backdrop-blur rounded-xl py-2">
            <div class="flex flex-wrap justify-center gap-2">
                @foreach(range('A', 'Z') as $letter)
                    <button onclick="scrollToLetter('{{ $letter }}')" 
                            class="w-10 h-10 rounded-full bg-gray-100 hover:bg-blue-500 hover:text-white transition-all duration-300 font-semibold text-sm">
                        {{ $letter }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- No search results (hidden by default) -->
        <div id="no-results" class="hidden text-center py-16">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-search text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Không tìm thấy hãng xe</h3>
            <p class="text-gray-600 mb-6">Hãy thử từ khóa khác hoặc xóa tìm kiếm hiện tại</p>
            <button id="clear-search" type="button" class="inline-flex items-center px-4 py-2 border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:border-gray-300 hover:bg-gray-50 transition-all duration-200">
                <i class="fas fa-times mr-2"></i>
                Xóa tìm kiếm
            </button>
        </div>

        <!-- Brands by Letter -->
        @foreach($groupedBrands as $letter => $brands)
        <div id="letter-{{ $letter }}" class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">
                {{ $letter }}
            </h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($brands as $brand)
                <div class="group brand-card relative bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                    @if($brand->is_featured)
                        <span class="absolute top-3 right-3 inline-flex items-center gap-1 px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold">
                            <i class="fas fa-star text-[10px]"></i> Nổi bật
                        </span>
                    @endif
                    <div class="text-center">
                        <!-- Brand Logo -->
                        @php $logo = $brand->logo_url ?? null; @endphp
                        @if($logo)
                            <img src="{{ $logo }}" 
                                 alt="{{ $brand->name }}" 
                                 class="w-20 h-20 mx-auto mb-4 object-contain group-hover:scale-110 transition-transform duration-300" loading="lazy" decoding="async">
                        @else
                            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-car text-white text-3xl"></i>
                            </div>
                        @endif
                        
                        <!-- Brand Name -->
                        <h3 class="font-bold text-lg text-gray-900 mb-2 group-hover:text-blue-600 transition-colors duration-300">
                            {{ $brand->name }}
                        </h3>
                        
                        <!-- Brand Info -->
                        <div class="text-sm text-gray-500 mb-4 flex items-center justify-center gap-2">
                            @php
                                // Count ALL models for brand (include models chưa có phiên bản)
                                $modelsCount = $brand->carModels()->count();
                            @endphp
                            <span class="inline-flex items-center gap-1"><i class="fas fa-layer-group text-gray-400"></i>{{ number_format($modelsCount) }} dòng xe</span>
                            @if(!empty($brand->country))
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 text-xs">{{ $brand->country }}</span>
                            @endif
                        </div>
                        
                        <!-- Brand Description -->
                        @if($brand->description)
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                {{ Str::limit($brand->description, 80) }}
                            </p>
                        @endif
                        
                        <!-- Action Button -->
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('car-brands.show', $brand->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105">
                                <span>Khám phá</span>
                                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                            <a href="{{ route('products.index', ['type' => 'car', 'brand' => $brand->id]) }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:border-gray-300 hover:bg-gray-50 transition-all duration-200">
                                <i class="fas fa-warehouse mr-2"></i>
                                <span>Xe của hãng</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <!-- No Brands Found -->
        @if($groupedBrands->isEmpty())
        <div class="text-center py-20">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-search text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Không tìm thấy hãng xe</h3>
            <p class="text-gray-600">Vui lòng thử tìm kiếm với từ khóa khác</p>
        </div>
        @endif
    </div>
</section>

<!-- CTA Section (consistent with hero) -->
<section class="relative overflow-hidden py-20 bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <div class="absolute inset-0 opacity-10" aria-hidden="true" style="background-image: radial-gradient(circle at 25% 25%, white 2px, transparent 2px), radial-gradient(circle at 75% 75%, white 2px, transparent 2px); background-size: 48px 48px;"></div>
    <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-4">
            Chưa tìm thấy hãng phù hợp?
        </h2>
        <p class="text-lg sm:text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
            Chúng tôi sẵn sàng tư vấn và đề xuất các lựa chọn phù hợp nhu cầu của bạn.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('products.index', ['type' => 'car']) }}"
               class="group bg-white text-slate-900 px-8 py-4 rounded-full font-bold text-lg transition-all duration-300 hover:bg-purple-100 hover:scale-105 shadow-lg hover:shadow-2xl">
                <i class="fas fa-car mr-2 group-hover:rotate-12 transition-transform"></i>
                Khám phá tất cả xe
            </a>
            <a href="{{ route('contact') }}" 
               class="border-2 border-white/30 text-white px-8 py-4 rounded-full font-bold text-lg transition-all duration-300 hover:bg-white/10 hover:border-white/50 backdrop-blur-sm">
                <i class="fas fa-phone mr-2"></i>
                Liên hệ tư vấn
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Brand search functionality
const brandSearchInput = document.getElementById('brand-search');
if (brandSearchInput) {
    brandSearchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const brandCards = document.querySelectorAll('.brand-card');

        let firstMatch = null;

        brandCards.forEach(card => {
            const brandName = card.querySelector('h3').textContent.toLowerCase();
            const isMatch = brandName.includes(query);
            card.style.display = isMatch ? 'block' : 'none';
            if (!firstMatch && isMatch) firstMatch = card;
        });

        // Show/hide letter sections based on visible brands
        updateLetterSections();

        // Toggle no-results message
        const visibleCount = Array.from(brandCards).filter(c => c.style.display !== 'none').length;
        const noResults = document.getElementById('no-results');
        if (noResults) {
            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }

        // Keep viewport at search bar (no auto-scroll)
    });
}

// Scroll to specific letter section
function scrollToLetter(letter) {
    const element = document.getElementById(`letter-${letter}`);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Update letter sections visibility
function updateLetterSections() {
    const letterSections = document.querySelectorAll('[id^="letter-"]');
    
    letterSections.forEach(section => {
        const visibleBrands = section.querySelectorAll('.group[style="display: block"], .group:not([style*="display: none"])');
        if (visibleBrands.length === 0) {
            section.style.display = 'none';
        } else {
            section.style.display = 'block';
        }
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling for all internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Clear search
    const clearBtn = document.getElementById('clear-search');
    if (clearBtn && brandSearchInput) {
        clearBtn.addEventListener('click', function() {
            brandSearchInput.value = '';
            brandSearchInput.dispatchEvent(new Event('input'));
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth transitions - chỉ áp dụng cho các element cụ thể */
.transition-smooth {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* Hover effects */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

.group:hover .group-hover\:translate-x-1 {
    transform: translateX(0.25rem);
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
