@props(['formId', 'callbackName', 'text', 'icon', 'classes'])

<button type="button" id="resetBtn_{{ str_replace(['-', '_', '#'], '', $formId) }}" class="{{ $classes }}">
    <i class="{{ $icon }} mr-2"></i>
    {{ $text }}
</button>

@push('scripts')
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
        const customDropdowns = this.form.querySelectorAll('input[type="hidden"]');
        customDropdowns.forEach(input => {
            input.value = '';
        });
        
        // Update display text for custom dropdowns
        const dropdownTexts = this.form.querySelectorAll('[id$="_text"]');
        dropdownTexts.forEach(text => {
            const placeholder = text.closest('[data-component="custom-dropdown"]')?.querySelector('.dropdown-option')?.dataset.text || 'Tất cả';
            text.textContent = placeholder;
        });
        
        // Reset selected states
        const dropdownOptions = this.form.querySelectorAll('.dropdown-option');
        dropdownOptions.forEach(option => {
            option.classList.remove('bg-blue-50');
            if (option.dataset.value === '') {
                option.classList.add('bg-blue-50');
            }
        });
        
        // Call custom resetters
        this.customResetters.forEach(resetter => {
            if (typeof resetter === 'function') {
                resetter();
            }
        });
    }
}

// Initialize Form Reset Manager for {{ $formId }}
document.addEventListener('DOMContentLoaded', function() {
    const formResetManager_{{ str_replace(['-', '_', '#'], '', $formId) }} = new FormResetManager({
        form: document.querySelector('{{ $formId }}'),
        resetButton: document.getElementById('resetBtn_{{ str_replace(['-', '_', '#'], '', $formId) }}'),
        callbackName: '{{ $callbackName }}'
    });
    
    formResetManager_{{ str_replace(['-', '_', '#'], '', $formId) }}.init();
});
</script>
@endpush
