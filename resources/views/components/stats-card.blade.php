@props(['title', 'value', 'icon', 'color', 'clickAction', 'description', 'trend', 'trendColor', 'size'])

@php
    $colorClasses = $getColorClasses();
    $trendColorClass = $getTrendColorClasses();
    $isClickable = !empty($clickAction);
    $cardClasses = $isClickable ? 'cursor-pointer transition-all duration-200 hover:shadow-lg hover:scale-105' : '';
    $sizeClasses = $size === 'large' ? 'p-8' : 'p-6';
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-200 {{ $cardClasses }} {{ $sizeClasses }}"
     @if($isClickable) onclick="{{ $clickAction }}()" @endif
     data-stat="{{ strtolower(str_replace(' ', '-', $title)) }}">
    
    <div class="flex items-center">
        {{-- Icon --}}
        <div class="flex-shrink-0">
            <div class="w-12 h-12 {{ $colorClasses['light'] }} rounded-lg flex items-center justify-center">
                <i class="{{ $icon }} {{ $colorClasses['text'] }} text-xl"></i>
            </div>
        </div>
        
        {{-- Content --}}
        <div class="ml-4 flex-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">{{ $title }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $value }}</p>
                </div>
                
                {{-- Trend Indicator --}}
                @if($trend)
                <div class="text-right">
                    <div class="flex items-center {{ $trendColorClass }}">
                        @if(str_contains($trend, '+') || str_contains($trend, 'tăng'))
                            <i class="fas fa-arrow-up text-sm mr-1"></i>
                        @elseif(str_contains($trend, '-') || str_contains($trend, 'giảm'))
                            <i class="fas fa-arrow-down text-sm mr-1"></i>
                        @else
                            <i class="fas fa-minus text-sm mr-1"></i>
                        @endif
                        <span class="text-sm font-medium">{{ $trend }}</span>
                    </div>
                </div>
                @endif
            </div>
            
            {{-- Description --}}
            @if($description)
            <p class="text-xs text-gray-500 mt-1">{{ $description }}</p>
            @endif
        </div>
    </div>
    
    {{-- Click indicator --}}
    @if($isClickable)
    <div class="mt-3 flex items-center text-xs text-gray-400">
        <i class="fas fa-mouse-pointer mr-1"></i>
        <span>Click để xem chi tiết</span>
    </div>
    @endif
</div>

@if($isClickable)
@push('scripts')
<script>
// Stats card click handlers
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects for clickable cards
    const clickableCards = document.querySelectorAll('[data-stat][onclick]');
    clickableCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endpush
@endif
