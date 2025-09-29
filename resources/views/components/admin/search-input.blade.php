@props([
    'name' => 'search', 
    'placeholder' => 'Tìm kiếm...', 
    'value' => '', 
    'callbackName' => 'loadData', 
    'debounceTime' => 300, 
    'size' => 'normal', 
    'showIcon' => true, 
    'showClearButton' => true
])

@php
    $sizeOptions = [
        'small' => [
            'padding' => 'pl-9 pr-3 py-2 h-10',
            'input' => 'text-sm',
            'icon' => 'text-sm'
        ],
        'normal' => [
            'padding' => 'pl-10 pr-3 py-2 h-10',
            'input' => 'text-base',
            'icon' => 'text-base'
        ],
        'large' => [
            'padding' => 'pl-12 pr-4 py-2 h-10',
            'input' => 'text-lg',
            'icon' => 'text-lg'
        ]
    ];
    
    $sizeClasses = $sizeOptions[$size] ?? $sizeOptions['normal'];
    $inputId = 'search_input_' . str_replace(['[', ']', '.'], '_', $name);
@endphp

<div class="relative">
    {{-- Search Icon --}}
    @if($showIcon)
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <i class="fas fa-search text-gray-400 {{ $sizeClasses['icon'] }}"></i>
    </div>
    @endif
    
    {{-- Search Input --}}
    <input type="text" 
           name="{{ $name }}" 
           id="{{ $inputId }}"
           class="block w-full {{ $sizeClasses['padding'] }} {{ $sizeClasses['input'] }} border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
           placeholder="{{ $placeholder }}"
           value="{{ $value }}"
           autocomplete="off">
    
    {{-- Clear Button --}}
    @if($showClearButton)
    <button type="button" 
            id="{{ $inputId }}_clear"
            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors {{ empty($value) ? 'hidden' : '' }}"
            onclick="clearSearch('{{ $inputId }}')">
        <i class="fas fa-times {{ $sizeClasses['icon'] }}"></i>
    </button>
    @endif
    
</div>

@push('scripts')
<script>
// Search Input Management
class SearchInputManager {
    constructor(inputId, options = {}) {
        this.inputId = inputId;
        this.input = document.getElementById(inputId);
        this.clearButton = document.getElementById(inputId + '_clear');
        this.callbackName = options.callbackName || '{{ $callbackName }}';
        this.debounceTime = options.debounceTime || {{ $debounceTime }};
        this.timeout = null;
        
        this.init();
    }
    
    init() {
        if (!this.input) return;
        
        // Input event listener
        this.input.addEventListener('input', (e) => {
            this.handleInput(e.target.value);
        });
        
        // Clear button event listener
        if (this.clearButton) {
            this.clearButton.addEventListener('click', () => {
                this.clear();
            });
        }
        
        // Show/hide clear button based on input value
        this.input.addEventListener('input', () => {
            this.toggleClearButton();
        });
        
        // Enter key handling
        this.input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.search(this.input.value);
            }
        });
    }
    
    handleInput(value) {
        // Clear existing timeout
        if (this.timeout) {
            clearTimeout(this.timeout);
        }
        
        // Set new timeout
        this.timeout = setTimeout(() => {
            this.search(value);
        }, this.debounceTime);
    }
    
    search(value) {
        // Update the input value in the form
        this.input.value = value;
        
        // Trigger form submission via the callback
        if (window[this.callbackName]) {
            // For AjaxTable integration, we need to trigger form-based search
            const form = this.input.closest('form');
            if (form) {
                // Create URL with form data
                const formData = new FormData(form);
                const baseUrl = form.dataset.baseUrl || window.location.pathname;
                const url = baseUrl + '?' + new URLSearchParams(formData).toString();
                window[this.callbackName](url);
            } else {
                // Fallback: call with value directly
                window[this.callbackName](value, this.input);
            }
        } else {
            console.warn(`Search callback function '${this.callbackName}' not found`);
        }
    }
    
    clear() {
        this.input.value = '';
        this.input.focus();
        this.toggleClearButton();
        this.search('');
    }
    
    toggleClearButton() {
        if (!this.clearButton) return;
        
        if (this.input.value.length > 0) {
            this.clearButton.classList.remove('hidden');
        } else {
            this.clearButton.classList.add('hidden');
        }
    }
    
    setValue(value) {
        this.input.value = value;
        this.toggleClearButton();
    }
    
    getValue() {
        return this.input.value;
    }
}

// Global clear function
window.clearSearch = function(inputId) {
    const manager = window.searchManagers?.[inputId];
    if (manager) {
        manager.clear();
    }
};

// Initialize search input for {{ $inputId }}
document.addEventListener('DOMContentLoaded', function() {
    // Initialize search managers registry
    if (!window.searchManagers) {
        window.searchManagers = {};
    }
    
    // Create manager for this input
    window.searchManagers['{{ $inputId }}'] = new SearchInputManager('{{ $inputId }}', {
        callbackName: '{{ $callbackName }}',
        debounceTime: {{ $debounceTime }}
    });
});
</script>
@endpush
