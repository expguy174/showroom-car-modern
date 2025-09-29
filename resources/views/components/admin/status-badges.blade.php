@props(['item', 'badges' => ['featured', 'on_sale', 'new_arrival', 'bestseller'], 'size' => 'normal'])

@php
    $sizeClasses = $size === 'small' ? 'px-1 py-0.5 text-xs' : 'px-1.5 py-0.5 text-xs';
    $iconSize = $size === 'small' ? 'w-3 h-3' : 'w-3 h-3';
@endphp

<div class="contents sm:flex sm:flex-col sm:items-start sm:gap-1 sm:w-full">
    @if(in_array('featured', $badges) && $item->is_featured)
        <span class="flex items-center {{ $sizeClasses }} rounded font-medium bg-yellow-100 text-yellow-800 leading-none w-auto sm:w-full whitespace-nowrap">
            <i class="fas fa-star mr-1 {{ $iconSize }} flex-shrink-0"></i>
            <span class="leading-none">Nổi bật</span>
        </span>
    @endif
    
    @if(in_array('on_sale', $badges) && $item->is_on_sale)
        <span class="flex items-center {{ $sizeClasses }} rounded font-medium bg-orange-100 text-orange-800 leading-none w-auto sm:w-full whitespace-nowrap">
            <i class="fas fa-tag mr-1 {{ $iconSize }} flex-shrink-0"></i>
            <span class="leading-none">Khuyến mãi</span>
        </span>
    @endif
    
    @if(in_array('new_arrival', $badges) && $item->is_new_arrival)
        <span class="flex items-center {{ $sizeClasses }} rounded font-medium bg-purple-100 text-purple-800 leading-none w-auto sm:w-full whitespace-nowrap">
            <i class="fas fa-plus mr-1 {{ $iconSize }} flex-shrink-0"></i>
            <span class="leading-none">Mới</span>
        </span>
    @endif
    
    @if(in_array('bestseller', $badges) && $item->is_bestseller)
        <span class="flex items-center {{ $sizeClasses }} rounded font-medium bg-pink-100 text-pink-800 leading-none w-auto sm:w-full whitespace-nowrap">
            <i class="fas fa-crown mr-1 {{ $iconSize }} flex-shrink-0"></i>
            <span class="leading-none">Bán chạy</span>
        </span>
    @endif
</div>
