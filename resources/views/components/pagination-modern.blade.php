@if ($paginator->hasPages())
    @php
        $window = \Illuminate\Pagination\UrlWindow::make($paginator);
        $elements = array_filter([
            $window['first'] ?? [],
            $window['slider'] ?? [],
            $window['last'] ?? [],
        ], function ($segment) {
            return is_array($segment) && count($segment);
        });
    @endphp
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        {{-- Mobile: Prev / Next only --}}
        <div class="flex-1 flex items-center justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 rounded-full bg-gray-100 text-gray-400 text-sm inline-flex items-center gap-2">
                    <i class="fas fa-chevron-left"></i> Trước
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-4 py-2 rounded-full bg-white text-gray-700 text-sm inline-flex items-center gap-2 shadow-sm border hover:bg-gray-50">
                    <i class="fas fa-chevron-left"></i> Trước
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-4 py-2 rounded-full bg-gray-900 text-white text-sm inline-flex items-center gap-2 shadow-sm hover:bg-black">
                    Sau <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="px-4 py-2 rounded-full bg-gray-100 text-gray-400 text-sm inline-flex items-center gap-2">
                    Sau <i class="fas fa-chevron-right"></i>
                </span>
            @endif
        </div>

        {{-- Desktop: numbers + prev/next --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-center">
            <div class="inline-flex items-center gap-2">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="px-3 py-2 rounded-xl bg-gray-100 text-gray-400 text-sm inline-flex items-center"><i class="fas fa-chevron-left"></i></span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3 py-2 rounded-xl bg-white text-gray-700 text-sm inline-flex items-center shadow-sm border hover:bg-gray-50"><i class="fas fa-chevron-left"></i></a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $pages)
                    @foreach ($pages as $page => $url)
                        @php($u = is_string($url) ? $url : (string)$url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-semibold">{{ $page }}</span>
                        @else
                            <a href="{{ $u }}" class="px-4 py-2 rounded-xl bg-white text-gray-700 text-sm shadow-sm border hover:bg-gray-50">{{ $page }}</a>
                        @endif
                    @endforeach
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-3 py-2 rounded-xl bg-gray-900 text-white text-sm inline-flex items-center shadow-sm hover:bg-black"><i class="fas fa-chevron-right"></i></a>
                @else
                    <span class="px-3 py-2 rounded-xl bg-gray-100 text-gray-400 text-sm inline-flex items-center"><i class="fas fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
    </nav>
@endif


