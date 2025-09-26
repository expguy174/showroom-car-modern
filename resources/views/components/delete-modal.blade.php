@props(['modalId', 'title', 'entityName', 'warningText', 'confirmText', 'cancelText', 'deleteCallbackName'])

{{-- Delete Modal --}}
<div id="{{ $modalId }}" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6">
        {{-- Header --}}
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 mx-auto bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
            </div>
        </div>
        
        {{-- Content --}}
        <div class="mb-6">
            <p class="text-gray-600" id="{{ $modalId }}_message">
                {{ $warningText }} <span id="{{ $modalId }}_entityName" class="font-semibold">{{ $entityName }}</span>?
            </p>
            <div id="{{ $modalId }}_details" class="mt-3 text-sm text-gray-500"></div>
            <div id="{{ $modalId }}_warnings" class="mt-3"></div>
        </div>
        
        {{-- Actions --}}
        <div class="flex space-x-3">
            <button type="button" 
                    id="{{ $modalId }}_confirm" 
                    class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-trash mr-2"></i>
                {{ $confirmText }}
            </button>
            <button type="button" 
                    id="{{ $modalId }}_cancel" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                {{ $cancelText }}
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Delete Modal Manager
class DeleteModalManager {
    constructor(options) {
        this.modalId = options.modalId;
        this.deleteCallbackName = options.deleteCallbackName;
        this.modal = document.getElementById(this.modalId);
        this.confirmBtn = document.getElementById(this.modalId + '_confirm');
        this.cancelBtn = document.getElementById(this.modalId + '_cancel');
        this.messageEl = document.getElementById(this.modalId + '_message');
        this.entityNameEl = document.getElementById(this.modalId + '_entityName');
        this.detailsEl = document.getElementById(this.modalId + '_details');
        this.warningsEl = document.getElementById(this.modalId + '_warnings');
        
        this.currentDeleteData = null;
    }
    
    init() {
        if (!this.modal) return;
        
        // Cancel button
        this.cancelBtn?.addEventListener('click', () => {
            this.hide();
        });
        
        // Confirm button
        this.confirmBtn?.addEventListener('click', () => {
            if (window[this.deleteCallbackName]) {
                window[this.deleteCallbackName](this.currentDeleteData);
            }
        });
        
        // Close on outside click
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.hide();
            }
        });
        
        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !this.modal.classList.contains('hidden')) {
                this.hide();
            }
        });
        
        // Make show function globally available
        window[`show${this.modalId.charAt(0).toUpperCase() + this.modalId.slice(1)}`] = (data) => {
            this.show(data);
        };
    }
    
    show(data = {}) {
        this.currentDeleteData = data;
        
        // Update content
        if (data.entityName && this.entityNameEl) {
            this.entityNameEl.textContent = data.entityName;
        }
        
        if (data.message && this.messageEl) {
            this.messageEl.innerHTML = data.message;
        }
        
        if (data.details && this.detailsEl) {
            this.detailsEl.innerHTML = data.details;
        }
        
        if (data.warnings && this.warningsEl) {
            this.warningsEl.innerHTML = data.warnings;
        }
        
        // Show modal
        this.modal.classList.remove('hidden');
        
        // Focus confirm button for accessibility
        setTimeout(() => {
            this.confirmBtn?.focus();
        }, 100);
    }
    
    hide() {
        this.modal.classList.add('hidden');
        this.currentDeleteData = null;
        
        // Reset button state
        if (this.confirmBtn) {
            this.confirmBtn.innerHTML = '<i class="fas fa-trash mr-2"></i>{{ $confirmText }}';
            this.confirmBtn.disabled = false;
        }
    }
    
    setLoading(loading = true) {
        if (!this.confirmBtn) return;
        
        if (loading) {
            this.confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xóa...';
            this.confirmBtn.disabled = true;
        } else {
            this.confirmBtn.innerHTML = '<i class="fas fa-trash mr-2"></i>{{ $confirmText }}';
            this.confirmBtn.disabled = false;
        }
    }
}

// Initialize Delete Modal Manager for {{ $modalId }}
document.addEventListener('DOMContentLoaded', function() {
    const deleteModal_{{ str_replace(['-', '_'], '', $modalId) }} = new DeleteModalManager({
        modalId: '{{ $modalId }}',
        deleteCallbackName: '{{ $deleteCallbackName }}'
    });
    
    deleteModal_{{ str_replace(['-', '_'], '', $modalId) }}.init();
});
</script>
@endpush
