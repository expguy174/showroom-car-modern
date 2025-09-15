@php(
    $from = $paginator->firstItem() ?? 0
)
@php(
    $to = $paginator->lastItem() ?? 0
)
@php(
    $total = $paginator->total() ?? 0
)
<div class="flex items-center justify-between text-sm text-gray-600 mb-3">
    <div>Tổng: <span class="font-semibold">{{ number_format($total) }}</span> lịch</div>
    <div>Hiển thị: <span class="font-semibold">{{ $from }}</span>–<span class="font-semibold">{{ $to }}</span></div>
</div>

