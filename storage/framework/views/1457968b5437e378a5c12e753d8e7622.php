<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['formId', 'callback' => null, 'buttonText' => 'Đặt lại']));

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

foreach (array_filter((['formId', 'callback' => null, 'buttonText' => 'Đặt lại']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $cleanId = str_replace(['-', '_', '#'], '', $formId);
?>


<button type="button" 
        id="resetBtn_<?php echo e($cleanId); ?>"
        class="inline-flex items-center px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors h-10">
    <i class="fas fa-undo mr-2"></i>
    <?php echo e($buttonText); ?>

</button>

<?php $__env->startPush('scripts'); ?>
<script>
// Form Reset Manager
class FormResetManager {
    constructor(options) {
        this.form = options.form;
        this.resetButton = options.resetButton;
        this.callbackName = options.callbackName || 'handleFormReset';
        this.customResetters = options.customResetters || [];
    }
    
    init() {
        if (!this.resetButton || !this.form) return;
        
        this.resetButton.addEventListener('click', () => {
            this.resetForm();
            this.resetCustomComponents();
            
            // Call global callback if exists
            if (window[this.callbackName]) {
                window[this.callbackName]();
            }
        });
    }
    
    resetForm() {
        if (this.form) {
            this.form.reset();
            
            // Clear search input specifically
            const searchInput = this.form.querySelector('input[type="text"], input[type="search"]');
            if (searchInput) {
                searchInput.value = '';
            }
            
            // Reset all select elements to first option
            const selects = this.form.querySelectorAll('select');
            selects.forEach(select => {
                select.selectedIndex = 0;
            });
        }
    }
    
    resetCustomComponents() {
        // Reset custom dropdowns (components)
        const customDropdowns = this.form.querySelectorAll('[data-component="custom-dropdown"]');
        customDropdowns.forEach(dropdown => {
            // Find hidden input and reset value
            const hiddenInput = dropdown.querySelector('input[type="hidden"]');
            if (hiddenInput) {
                hiddenInput.value = '';
            }
            
            // Find display text and reset to placeholder
            const displayText = dropdown.querySelector('[id$="_text"]');
            const firstOption = dropdown.querySelector('.dropdown-option[data-value=""]');
            if (displayText && firstOption) {
                displayText.textContent = firstOption.dataset.text;
            }
            
            // Reset selected states
            const options = dropdown.querySelectorAll('.dropdown-option');
            options.forEach(option => {
                option.classList.remove('bg-blue-50');
                if (option.dataset.value === '') {
                    option.classList.add('bg-blue-50');
                }
            });
        });
        
        // Call custom resetters
        this.customResetters.forEach(resetter => {
            if (typeof resetter === 'function') {
                resetter();
            }
        });
    }
}

// Initialize Form Reset Manager
document.addEventListener('DOMContentLoaded', function() {
    const formResetManager_<?php echo e($cleanId); ?> = new FormResetManager({
        form: document.querySelector('<?php echo e($formId); ?>'),
        resetButton: document.getElementById('resetBtn_<?php echo e($cleanId); ?>'),
        callbackName: '<?php echo e($callback); ?>'
    });
    
    formResetManager_<?php echo e($cleanId); ?>.init();
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/components/admin/reset-button.blade.php ENDPATH**/ ?>