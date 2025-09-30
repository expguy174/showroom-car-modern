@props(['itemId', 'currentStatus', 'entityType' => 'variant'])

{{-- Status Badge Display - consistent with other badges --}}
<span class="status-badge inline-flex items-center px-2 py-1 text-xs rounded-md font-medium whitespace-nowrap min-h-[20px] {{ $currentStatus ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
      data-{{ $entityType }}-id="{{ $itemId }}">
    <i class="fas {{ $currentStatus ? 'fa-check-circle' : 'fa-times-circle' }} mr-1.5 w-3 h-3 flex-shrink-0"></i>
    <span>{{ $currentStatus ? 'Hoạt động' : 'Tạm dừng' }}</span>
</span>

@push('scripts')
<script>
// Function to update status badge when toggle button is clicked
window.updateStatusBadge = function(itemId, newStatus, entityType = 'variant') {
    // Find all status badges for this item
    const badges = document.querySelectorAll(`[data-${entityType}-id="${itemId}"].status-badge`);
    
    badges.forEach(badge => {
        const statusIcon = badge.querySelector('i');
        const statusText = badge.querySelector('span:last-child');
        
        // Update classes
        badge.classList.remove(
            'bg-green-100', 'text-green-800',
            'bg-red-100', 'text-red-800'
        );
        
        if (newStatus) {
            badge.classList.add('bg-green-100', 'text-green-800');
            if (statusIcon) {
                statusIcon.className = 'fas fa-check-circle mr-1.5 w-3 h-3 flex-shrink-0';
            }
            if (statusText) {
                statusText.textContent = 'Hoạt động';
            }
        } else {
            badge.classList.add('bg-red-100', 'text-red-800');
            if (statusIcon) {
                statusIcon.className = 'fas fa-times-circle mr-1.5 w-3 h-3 flex-shrink-0';
            }
            if (statusText) {
                statusText.textContent = 'Tạm dừng';
            }
        }
    });
};
</script>
@endpush
