@if(($testDrives->count() ?? 0) === 0)
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 sm:p-14 text-center">
	<div class="w-16 h-16 mx-auto mb-5 bg-gray-100 rounded-full flex items-center justify-center">
		<i class="fas fa-car-side text-2xl text-gray-400"></i>
	</div>
	<div class="text-lg sm:text-xl font-semibold text-gray-700 mb-2">Chưa có lịch lái thử</div>
	<p class="text-sm sm:text-base text-gray-500 max-w-xl mx-auto">Bạn chưa có lịch lái thử nào. Hãy đặt lịch để trải nghiệm mẫu xe bạn quan tâm.</p>
</div>
@else
<div id="bookings-list" class="space-y-3 sm:space-y-4">
	@foreach($testDrives as $td)
		@php
			$canCancel = in_array($td->status, ['pending','confirmed']);
			$carModelName = optional(optional($td->carVariant)->carModel)->name;
			$variantName = $td->carVariant->name ?? '';
			$timeDisplay = is_string($td->preferred_time) ? $td->preferred_time : optional($td->preferred_time)->format('H:i');
		@endphp
		<div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow booking-card" data-id="{{ $td->id }}">
			<div class="p-3 sm:p-5 flex flex-col h-full">
				<div class="flex-1 flex flex-col gap-3 sm:gap-4">
					<div class="flex items-start sm:items-center justify-between gap-2">
						<div class="min-w-0">
							<div class="flex items-center gap-2 flex-wrap">
								<span class="text-gray-800 font-semibold truncate">#{{ $td->test_drive_number ?? $td->id }}</span>
								<span class="hidden xs:inline text-gray-400">•</span>
								<span class="text-gray-500 text-xs sm:text-sm">{{ optional($td->preferred_date)->format('d/m/Y') }} {{ $timeDisplay }}</span>
							</div>
							<div class="text-xs sm:text-sm text-gray-500 mt-1 truncate">{{ $carModelName }} {{ $variantName }}@if($td->showroom) • {{ $td->showroom->name }}@endif</div>
						</div>
						<div class="flex items-center gap-2 shrink-0">
							<span class="px-2 py-0.5 rounded-full text-xs whitespace-nowrap inline-flex items-center {{ $td->status_badge }}" data-role="status-badge">{{ $td->status_text }}</span>
						</div>
					</div>
				</div>
				<div class="flex items-center justify-between">
					<div class="flex flex-wrap items-center gap-2 sm:gap-3 text-[11px] sm:text-xs text-gray-500">
						<span class="truncate max-w-[60ch]" title="{{ $td->notes ?: 'Không có' }}">Ghi chú: <span class="font-medium text-gray-700">{{ !empty($td->notes) ? Str::limit($td->notes, 80) : 'Chưa có ghi chú' }}</span></span>
					</div>
					<div class="flex items-center gap-2">
						<a href="{{ route('test-drives.show', $td) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs"><i class="fas fa-eye"></i> Chi tiết</a>
						<button type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-500 text-white hover:bg-rose-600 disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed js-cancel" data-id="{{ $td->id }}" {{ $canCancel ? '' : 'disabled' }}><i class="fas fa-ban"></i> Hủy lịch</button>
					</div>
				</div>
			</div>
		</div>
	@endforeach
</div>
<div class="mt-3">
    @include('components.pagination-modern', ['paginator' => $testDrives])
</div>
@endif
