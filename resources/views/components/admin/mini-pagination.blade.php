@props(['paginator', 'showInfo' => true, 'showLinks' => true, 'compact' => false])

@php
    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
    $total = $paginator->total();
    $perPage = $paginator->perPage();
    $from = ($currentPage - 1) * $perPage + 1;
    $to = min($currentPage * $perPage, $total);
@endphp

@if($paginator->hasPages() || $showInfo)
<div class="flex items-center justify-between {{ $compact ? 'text-xs' : 'text-sm' }} text-gray-700 bg-white px-4 py-3 border-t border-gray-200">
    {{-- Pagination Info --}}
    @if($showInfo)
    <div class="flex-1 flex justify-between sm:hidden">
        <span class="text-gray-500">
            Trang {{ $currentPage }} / {{ $lastPage }}
        </span>
        <span class="text-gray-500">
            {{ $total }} kết quả
        </span>
    </div>
    
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
            <p class="text-gray-700">
                Hiển thị
                <span class="font-medium">{{ $from }}</span>
                đến
                <span class="font-medium">{{ $to }}</span>
                trong tổng số
                <span class="font-medium">{{ $total }}</span>
                kết quả
            </p>
        </div>
    </div>
    @endif
    
    {{-- Pagination Links --}}
    @if($showLinks && $paginator->hasPages())
    <div class="flex-1 flex justify-center sm:justify-end">
        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            {{-- Previous Page Link --}}
            @if($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-gray-400 cursor-not-allowed">
                    <i class="fas fa-chevron-left {{ $compact ? 'text-xs' : 'text-sm' }}"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" 
                   class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors">
                    <i class="fas fa-chevron-left {{ $compact ? 'text-xs' : 'text-sm' }}"></i>
                </a>
            @endif

            {{-- Page Numbers --}}
            @php
                $start = max(1, $currentPage - 2);
                $end = min($lastPage, $currentPage + 2);
                
                // Adjust if we're near the beginning or end
                if ($currentPage <= 3) {
                    $end = min($lastPage, 5);
                }
                if ($currentPage > $lastPage - 3) {
                    $start = max(1, $lastPage - 4);
                }
            @endphp

            {{-- First page if not in range --}}
            @if($start > 1)
                <a href="{{ $paginator->url(1) }}" 
                   class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-colors {{ $compact ? 'text-xs' : 'text-sm' }}">
                    1
                </a>
                @if($start > 2)
                    <span class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-gray-500 {{ $compact ? 'text-xs' : 'text-sm' }}">
                        ...
                    </span>
                @endif
            @endif

            {{-- Page range --}}
            @for($page = $start; $page <= $end; $page++)
                @if($page == $currentPage)
                    <span class="relative inline-flex items-center px-3 py-2 border border-blue-500 bg-blue-50 text-blue-600 font-medium {{ $compact ? 'text-xs' : 'text-sm' }}">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $paginator->url($page) }}" 
                       class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-colors {{ $compact ? 'text-xs' : 'text-sm' }}">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            {{-- Last page if not in range --}}
            @if($end < $lastPage)
                @if($end < $lastPage - 1)
                    <span class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-gray-500 {{ $compact ? 'text-xs' : 'text-sm' }}">
                        ...
                    </span>
                @endif
                <a href="{{ $paginator->url($lastPage) }}" 
                   class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-colors {{ $compact ? 'text-xs' : 'text-sm' }}">
                    {{ $lastPage }}
                </a>
            @endif

            {{-- Next Page Link --}}
            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" 
                   class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors">
                    <i class="fas fa-chevron-right {{ $compact ? 'text-xs' : 'text-sm' }}"></i>
                </a>
            @else
                <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-gray-400 cursor-not-allowed">
                    <i class="fas fa-chevron-right {{ $compact ? 'text-xs' : 'text-sm' }}"></i>
                </span>
            @endif
        </nav>
    </div>
    @endif
</div>
@endif
