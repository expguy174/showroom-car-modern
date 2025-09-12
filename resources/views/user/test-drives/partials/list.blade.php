<div id="bookings-list" class="space-y-2">
	@foreach($testDrives as $td)
		@php
			$variant = $td->carVariant;
			$images = optional($variant)->images ?? collect();
			$img = optional($images->first())->image_url;
			$thumb = $img ?: 'https://placehold.co/96x64/EEF2FF/3730A3?text=Car';
			$canCancel = in_array($td->status, ['pending','confirmed']);
		@endphp
		<div class="p-4 border rounded-xl flex items-start gap-4 hover:border-indigo-200 transition bg-white booking-card" data-id="{{ $td->id }}">
			<div class="w-20 h-14 rounded-lg bg-gray-100 overflow-hidden flex items-center justify-center">
				<img src="{{ $thumb }}" alt="thumb" class="w-full h-full object-cover" />
			</div>
			<div class="flex-1 min-w-0">
				<div class="flex items-center justify-between gap-3 min-w-0">
					<div class="min-w-0">
						<div class="flex items-center gap-2 min-w-0">
							<a href="{{ route('car-variants.show', $td->car_variant_id) }}" class="font-semibold text-gray-900 hover:text-indigo-700 truncate" title="{{ optional(optional($td->carVariant)->carModel)->name }} {{ $td->carVariant->name ?? '' }}">#{{ $td->test_drive_number ?? $td->id }} • {{ optional(optional($td->carVariant)->carModel)->name }} {{ $td->carVariant->name ?? '' }}</a>
						</div>
						<div class="text-xs sm:text-sm text-gray-600 mt-1 truncate">Ngày {{ optional($td->preferred_date)->format('d/m/Y') }} • Giờ {{ is_string($td->preferred_time) ? $td->preferred_time : optional($td->preferred_time)->format('H:i') }}@if($td->showroom) • {{ $td->showroom->name }}@endif</div>
						@if(!empty($td->notes))
							<div class="text-xs text-gray-500 mt-1 truncate">{{ $td->notes }}</div>
						@endif
					</div>
					<div class="flex items-center gap-2 shrink-0">
						<span class="px-2 py-0.5 rounded-full text-xs {{ $td->status_badge }} whitespace-nowrap inline-flex items-center" data-role="status-badge">{{ $td->status_text }}</span>
						<a href="{{ route('test-drives.show', $td) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs"><i class="fas fa-eye"></i> Chi tiết</a>
						{{-- Removed inline edit button per request --}}
						<button type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-500 text-white hover:bg-rose-600 disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed js-cancel" data-id="{{ $td->id }}" {{ $canCancel ? '' : 'disabled' }}><i class="fas fa-ban"></i> Hủy</button>
					</div>
				</div>
			</div>
		</div>
	@endforeach
</div>
<div class="mt-3">
    @include('components.pagination-modern', ['paginator' => $testDrives])
</div>
