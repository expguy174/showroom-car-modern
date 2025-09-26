@props(['name', 'placeholder', 'value', 'callbackName', 'debounceTime', 'size', 'showIcon', 'showClearButton'])

@php
    $sizeClasses = $getSizeClasses();
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
    
    {{-- Loading Indicator --}}
    <div id="{{ $inputId }}_loading" class="absolute inset-y-0 right-0 pr-3 flex items-center hidden">
        <i class="fas fa-spinner fa-spin text-gray-400 {{ $sizeClasses['icon'] }}"></i>
    </div>
</div>

@push('scripts')
<script>
// Search Input Management
class SearchInputManager {
    constructor(inputId, options = {}) {
        this.inputId = inputId;
        this.input = document.getElementById(inputId);
        this.clearButton = document.getElementById(inputId + '_clear');
        this.loadingIndicator = document.getElementById(inputId + '_loading');
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
        
        // Show loading indicator
        this.setLoadingState(true);
        
        // Set new timeout
        this.timeout = setTimeout(() => {
            this.search(value);
        }, this.debounceTime);
    }
    
    search(value) {
        // Hide loading indicator
        this.setLoadingState(false);
        
        // Call the callback function
        if (window[this.callbackName]) {
            window[this.callbackName](value, this.input);
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
    
    setLoadingState(loading) {
        if (!this.loadingIndicator) return;
        
        if (loading) {
            this.loadingIndicator.classList.remove('hidden');
            if (this.clearButton) {
                this.clearButton.classList.add('hidden');
            }
        } else {
            this.loadingIndicator.classList.add('hidden');
            this.toggleClearButton();
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
