@if ($paginator->hasPages())
<div class="flex items-center justify-center space-x-1">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <span class="relative inline-flex items-center px-2 py-1 text-xs font-medium text-gray-400 bg-gray-100 border border-gray-200 cursor-default rounded">
            <i class="fas fa-chevron-left"></i>
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-2 py-1 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
            <i class="fas fa-chevron-left"></i>
        </a>
    @endif

    {{-- Current Page Info --}}
    <span class="relative inline-flex items-center px-3 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded">
        {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
    </span>

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-2 py-1 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
            <i class="fas fa-chevron-right"></i>
        </a>
    @else
        <span class="relative inline-flex items-center px-2 py-1 text-xs font-medium text-gray-400 bg-gray-100 border border-gray-200 cursor-default rounded">
            <i class="fas fa-chevron-right"></i>
        </span>
    @endif
</div>
@endif
