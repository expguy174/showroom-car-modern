@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $prevUrl = $paginator->previousPageUrl();
        $nextUrl = $paginator->nextPageUrl();
        $show = 2; // số trang kề hai bên hiện tại
        $start = max(1, $current - $show);
        $end = min($last, $current + $show);
    @endphp
    <nav class="flex items-center justify-between" role="navigation" aria-label="Phân trang">
        <div class="text-sm text-gray-600">
            Trang <span class="font-semibold">{{ $current }}</span>/<span class="font-semibold">{{ $last }}</span>
        </div>
        <ul class="flex items-center gap-1">
            <li>
                <a href="{{ $prevUrl ?: '#' }}" aria-label="Trang trước" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border text-gray-600 hover:bg-gray-50 {{ $prevUrl ? '' : 'pointer-events-none opacity-40' }}">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
            @if($start > 1)
                <li><a href="{{ $paginator->url(1) }}" class="inline-flex items-center justify-center min-w-[36px] h-9 px-2 rounded-lg border text-gray-700 hover:bg-gray-50">1</a></li>
                @if($start > 2)
                    <li class="text-gray-400 px-1">…</li>
                @endif
            @endif
            @for($i=$start; $i<=$end; $i++)
                <li>
                    @if($i === $current)
                        <span class="inline-flex items-center justify-center min-w-[36px] h-9 px-2 rounded-lg bg-indigo-600 text-white font-semibold">{{ $i }}</span>
                    @else
                        <a href="{{ $paginator->url($i) }}" class="inline-flex items-center justify-center min-w-[36px] h-9 px-2 rounded-lg border text-gray-700 hover:bg-gray-50">{{ $i }}</a>
                    @endif
                </li>
            @endfor
            @if($end < $last)
                @if($end < $last - 1)
                    <li class="text-gray-400 px-1">…</li>
                @endif
                <li><a href="{{ $paginator->url($last) }}" class="inline-flex items-center justify-center min-w-[36px] h-9 px-2 rounded-lg border text-gray-700 hover:bg-gray-50">{{ $last }}</a></li>
            @endif
            <li>
                <a href="{{ $nextUrl ?: '#' }}" aria-label="Trang sau" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border text-gray-600 hover:bg-gray-50 {{ $nextUrl ? '' : 'pointer-events-none opacity-40' }}">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        </ul>
    </nav>
@endif


