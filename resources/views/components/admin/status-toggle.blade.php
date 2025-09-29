@props(['itemId', 'currentStatus'])

{{-- Responsive Status Badge Display - matches table badge styling --}}
<span class="status-badge flex items-center px-1.5 py-0.5 rounded text-xs font-medium leading-none w-auto sm:w-full whitespace-nowrap {{ $currentStatus ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
      data-variant-id="{{ $itemId }}">
    
    {{-- Status Icon - responsive margin --}}
    <i class="fas {{ $currentStatus ? 'fa-check-circle' : 'fa-times-circle' }} mr-1 w-3 h-3 flex-shrink-0 leading-none" style="line-height: 1;"></i>
    
    {{-- Full Status Text for all screen sizes --}}
    <span class="status-text leading-none">{{ $currentStatus ? 'Hoạt động' : 'Tạm dừng' }}</span>
</span>

@push('scripts')
<script>
// Function to update status badge when toggle button is clicked
window.updateStatusBadge = function(variantId, newStatus) {
    // Find all status badges for this variant
    const badges = document.querySelectorAll(`[data-variant-id="${variantId}"].status-badge`);
    
    badges.forEach(badge => {
        const statusIcon = badge.querySelector('i');
        const statusText = badge.querySelector('.status-text');
        
        // Store old status before updating (only check first badge to avoid duplicates)
        const wasActive = badge.classList.contains('bg-green-100');
        const isFirstBadge = badge === badges[0];
        
        // Update classes
        badge.classList.remove(
            'bg-green-100', 'text-green-800',
            'bg-red-100', 'text-red-800'
        );
        
        if (newStatus) {
            badge.classList.add('bg-green-100', 'text-green-800');
            statusIcon.className = 'fas fa-check-circle mr-1 w-3 h-3 flex-shrink-0 leading-none';
            statusIcon.style.lineHeight = '1';
            if (statusText) statusText.textContent = 'Hoạt động';
        } else {
            badge.classList.add('bg-red-100', 'text-red-800');
            statusIcon.className = 'fas fa-times-circle mr-1 w-3 h-3 flex-shrink-0 leading-none';
            statusIcon.style.lineHeight = '1';
            if (statusText) statusText.textContent = 'Tạm dừng';
        }
        
        // Update stats cards only once (from first badge) to avoid double counting
        if (isFirstBadge && typeof window.updateStatsCards === 'function') {
            window.updateStatsCards(wasActive, newStatus);
        }
    });
};
</script>
@endpush
