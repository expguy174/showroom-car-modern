@props(['title', 'value', 'icon', 'color' => 'blue', 'clickAction' => null, 'description' => null, 'trend' => null, 'trendColor' => 'green', 'size' => 'normal', 'dataStat' => null])

@php
    // Define color classes function
    $getColorClasses = function() use ($color) {
        switch($color) {
            case 'blue':
                return ['light' => 'bg-blue-100', 'text' => 'text-blue-600'];
            case 'green':
                return ['light' => 'bg-green-100', 'text' => 'text-green-600'];
            case 'red':
                return ['light' => 'bg-red-100', 'text' => 'text-red-600'];
            case 'yellow':
                return ['light' => 'bg-yellow-100', 'text' => 'text-yellow-600'];
            case 'orange':
                return ['light' => 'bg-orange-100', 'text' => 'text-orange-600'];
            case 'purple':
                return ['light' => 'bg-purple-100', 'text' => 'text-purple-600'];
            case 'pink':
                return ['light' => 'bg-pink-100', 'text' => 'text-pink-600'];
            case 'teal':
                return ['light' => 'bg-teal-100', 'text' => 'text-teal-600'];
            case 'indigo':
                return ['light' => 'bg-indigo-100', 'text' => 'text-indigo-600'];
            case 'gray':
                return ['light' => 'bg-gray-100', 'text' => 'text-gray-600'];
            default:
                return ['light' => 'bg-blue-100', 'text' => 'text-blue-600'];
        }
    };

    // Define trend color classes function
    $getTrendColorClasses = function() use ($trendColor) {
        switch($trendColor) {
            case 'green':
                return 'text-green-600';
            case 'red':
                return 'text-red-600';
            case 'yellow':
                return 'text-yellow-600';
            default:
                return 'text-green-600';
        }
    };

    $colorClasses = $getColorClasses();
    $trendColorClass = $getTrendColorClasses();
    $isClickable = false; // Disabled click functionality
    $cardClasses = '';
    $sizeClasses = $size === 'large' ? 'p-8' : 'p-6';
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-200 {{ $cardClasses }} {{ $sizeClasses }}">
    
    <div class="flex items-center">
        {{-- Icon --}}
        <div class="flex-shrink-0">
            <div class="w-12 h-12 {{ $colorClasses['light'] }} rounded-lg flex items-center justify-center">
                <i class="{{ $icon }} {{ $colorClasses['text'] }} text-xl"></i>
            </div>
        </div>
        
        {{-- Content --}}
        <div class="ml-4 flex-1 min-w-0">
            <div class="flex items-center justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-gray-600 whitespace-nowrap truncate" title="{{ $title }}">{{ $title }}</p>
                    <p class="text-2xl font-bold text-gray-900" data-stat="{{ $dataStat ?? strtolower(str_replace(' ', '-', $title)) }}">{{ $value }}</p>
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
            <p class="text-xs text-gray-500 mt-1 whitespace-nowrap truncate" title="{{ $description }}">{{ $description }}</p>
            @endif
        </div>
    </div>
    
</div>
