<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['modalId', 'title' => 'Xác nhận xóa', 'confirmText' => 'Xóa', 'cancelText' => 'Hủy', 'deleteCallbackName' => 'confirmDelete', 'entityType' => 'item']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['modalId', 'title' => 'Xác nhận xóa', 'confirmText' => 'Xóa', 'cancelText' => 'Hủy', 'deleteCallbackName' => 'confirmDelete', 'entityType' => 'item']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>


<div id="<?php echo e($modalId); ?>" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6 overflow-hidden">
        
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900"><?php echo e($title); ?></h3>
            </div>
        </div>
        
        
        <div class="mb-6">
            <p class="text-gray-600" id="<?php echo e($modalId); ?>_message">
                Bạn có chắc chắn muốn xóa <span id="<?php echo e($modalId); ?>_entityName" class="font-semibold"></span>?
            </p>
            <div id="<?php echo e($modalId); ?>_details" class="mt-3 text-sm text-gray-500"></div>
            <div id="<?php echo e($modalId); ?>_warnings" class="mt-3"></div>
        </div>
        
        
        <div class="flex space-x-3">
            <button type="button" 
                    id="<?php echo e($modalId); ?>_cancel" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                <?php echo e($cancelText); ?>

            </button>
            <button type="button" 
                    id="<?php echo e($modalId); ?>_confirm" 
                    class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-trash mr-2"></i>
                <?php echo e($confirmText); ?>

            </button>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
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
            this.confirmBtn.innerHTML = '<i class="fas fa-trash mr-2"></i><?php echo e($confirmText); ?>';
            this.confirmBtn.disabled = false;
        }
    }
    
    reset() {
        // Clear all content elements
        if (this.entityNameEl) this.entityNameEl.textContent = '';
        if (this.detailsEl) this.detailsEl.innerHTML = '';
        if (this.warningsEl) this.warningsEl.innerHTML = '';
        this.currentDeleteData = null;
    }
    
    setLoading(loading = true) {
        if (!this.confirmBtn) return;
        
        if (loading) {
            this.confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xóa...';
            this.confirmBtn.disabled = true;
        } else {
            this.confirmBtn.innerHTML = '<i class="fas fa-trash mr-2"></i><?php echo e($confirmText); ?>';
            this.confirmBtn.disabled = false;
        }
    }
}

// Initialize Delete Modal Manager for <?php echo e($modalId); ?>

document.addEventListener('DOMContentLoaded', function() {
    const modalManagerName = 'deleteModalManager_<?php echo e(str_replace(["-", "_"], "", $modalId)); ?>';
    window[modalManagerName] = new DeleteModalManager({
        modalId: '<?php echo e($modalId); ?>',
        deleteCallbackName: '<?php echo e($deleteCallbackName); ?>'
    });
    
    window[modalManagerName].init();
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/components/admin/delete-modal.blade.php ENDPATH**/ ?>