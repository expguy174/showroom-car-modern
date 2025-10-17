{{-- Stock Badge Component --}}
@props(['stock', 'type' => 'accessory', 'size' => 'md'])

@php
    $badge = \App\Helpers\StockHelper::getStockBadge((int)$stock, $type);
    
    // Size classes
    $sizeClasses = match($size) {
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2 py-1 text-xs',
        'lg' => 'px-3 py-1.5 text-sm',
        default => 'px-2 py-1 text-xs'
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1 {$sizeClasses} font-medium rounded {$badge['class']}"]) }}>
    <span>{{ $badge['icon'] }}</span>
    <span>{{ $badge['text'] }}</span>
</span>
