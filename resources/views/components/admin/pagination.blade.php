@if ($paginator->hasPages())
<nav class="flex items-center justify-between" aria-label="Pagination">
    <div class="flex-1 flex justify-between sm:hidden">
        {{-- Mobile pagination --}}
        @if ($paginator->onFirstPage())
            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                Trước
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-blue-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                Trước
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-blue-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                Sau
            </a>
        @else
            <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                Sau
            </span>
        @endif
    </div>

    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-gray-700 leading-5">
                Hiển thị
                <span class="font-medium">{{ $paginator->firstItem() }}</span>
                đến
                <span class="font-medium">{{ $paginator->lastItem() }}</span>
                trong tổng số
                <span class="font-medium">{{ $paginator->total() }}</span>
                kết quả
            </p>
        </div>

        <div>
            <div class="inline-flex rounded-md shadow-sm" role="group">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-l-md">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-link relative inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50 focus:z-10 focus:outline-none transition-colors">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @php
                    $start = max(1, $paginator->currentPage() - 2);
                    $end = min($paginator->lastPage(), $paginator->currentPage() + 2);
                @endphp

                {{-- First Page --}}
                @if($start > 1)
                    <a href="{{ $paginator->url(1) }}" class="pagination-link relative inline-flex items-center justify-center w-10 h-10 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:z-10 focus:outline-none transition-colors">
                        1
                    </a>
                    @if($start > 2)
                        <span class="relative inline-flex items-center justify-center w-10 h-10 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default">
                            ...
                        </span>
                    @endif
                @endif

                {{-- Page Numbers --}}
                @for ($page = $start; $page <= $end; $page++)
                    @if ($page == $paginator->currentPage())
                        <span class="relative inline-flex items-center justify-center w-10 h-10 -ml-px text-sm font-medium text-white bg-blue-600 border border-blue-600 cursor-default z-10 focus:outline-none">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $paginator->url($page) }}" class="pagination-link relative inline-flex items-center justify-center w-10 h-10 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:z-10 focus:outline-none transition-colors">
                            {{ $page }}
                        </a>
                    @endif
                @endfor

                {{-- Last Page --}}
                @if($end < $paginator->lastPage())
                    @if($end < $paginator->lastPage() - 1)
                        <span class="relative inline-flex items-center justify-center w-10 h-10 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default">
                            ...
                        </span>
                    @endif
                    <a href="{{ $paginator->url($paginator->lastPage()) }}" class="pagination-link relative inline-flex items-center justify-center w-10 h-10 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:z-10 focus:outline-none transition-colors">
                        {{ $paginator->lastPage() }}
                    </a>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-link relative inline-flex items-center justify-center w-10 h-10 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50 focus:z-10 focus:outline-none transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span class="relative inline-flex items-center justify-center w-10 h-10 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-r-md">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        </div>
    </div>
</nav>
@endif
