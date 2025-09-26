@props(['itemId', 'currentStatus'])

{{-- Responsive Status Badge Display - matches table badge styling --}}
<span class="status-badge inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium {{ $currentStatus ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
      data-variant-id="{{ $itemId }}">
    
    {{-- Status Icon - responsive margin --}}
    <i class="fas {{ $currentStatus ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
    
    {{-- Responsive Status Text --}}
    <span class="status-text">
        {{-- Full text on desktop (lg and up) --}}
        <span class="hidden lg:inline">{{ $currentStatus ? 'Hoạt động' : 'Tạm dừng' }}</span>
        {{-- Short text on mobile and tablet --}}
        <span class="lg:hidden">{{ $currentStatus ? 'Bật' : 'Tắt' }}</span>
    </span>
</span>

@push('scripts')
<script>
// Function to update status badge when toggle button is clicked
window.updateStatusBadge = function(variantId, newStatus) {
    // Find all status badges for this variant
    const badges = document.querySelectorAll(`[data-variant-id="${variantId}"].status-badge`);
    
    badges.forEach(badge => {
        const statusIcon = badge.querySelector('i');
        const statusTextFull = badge.querySelector('.status-text .hidden');
        const statusTextShort = badge.querySelector('.status-text span:not(.hidden)');
        
        // Update classes
        badge.classList.remove(
            'bg-green-100', 'text-green-800',
            'bg-red-100', 'text-red-800'
        );
        
        if (newStatus) {
            badge.classList.add('bg-green-100', 'text-green-800');
            statusIcon.className = 'fas fa-check-circle mr-1';
            if (statusTextFull) statusTextFull.textContent = 'Hoạt động';
            if (statusTextShort) statusTextShort.textContent = 'Bật';
        } else {
            badge.classList.add('bg-red-100', 'text-red-800');
            statusIcon.className = 'fas fa-times-circle mr-1';
            if (statusTextFull) statusTextFull.textContent = 'Tạm dừng';
            if (statusTextShort) statusTextShort.textContent = 'Tắt';
        }
    });
};
</script>
@endpush
