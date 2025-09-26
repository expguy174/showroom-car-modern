@props(['name', 'options', 'placeholder', 'optionValue', 'optionText', 'optionSubtext', 'selected', 'onchange', 'maxVisible', 'searchable', 'width'])

@php
    $componentId = 'dropdown_' . str_replace(['[', ']', '.'], '_', $name) . '_' . uniqid();
    $containerHeight = $maxVisible * 60;
@endphp

<div class="{{ $width }} relative" data-component="custom-dropdown">
    {{-- Hidden input to store selected value --}}
    <input type="hidden" name="{{ $name }}" id="{{ $name }}" value="{{ $selected }}">
    
    {{-- Custom Dropdown --}}
    <div class="custom-dropdown relative">
        <button type="button" 
                id="{{ $componentId }}_btn" 
                class="w-full px-3 py-2 text-left bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 flex items-center justify-between">
            <span id="{{ $componentId }}_text" class="text-gray-900">
                {{ $getSelectedText() }}
            </span>
            <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200" 
               id="{{ $componentId }}_icon"></i>
        </button>
        
        {{-- Dropdown Menu --}}
        <div id="{{ $componentId }}_menu" 
             class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden">
            
            @if($searchable)
            {{-- Search Input --}}
            <div class="p-3 border-b border-gray-200">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 text-sm"></i>
                    </div>
                    <input type="text" 
                           id="{{ $componentId }}_search"
                           class="block w-full pl-8 pr-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="Tìm kiếm...">
                </div>
            </div>
            @endif
            
            {{-- Options List --}}
            <div class="overflow-y-auto custom-scrollbar" 
                 id="{{ $componentId }}_options"
                 style="max-height: {{ $containerHeight }}px;">
                
                {{-- Default option --}}
                <div class="dropdown-option px-3 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100 {{ !$selected ? 'bg-blue-50' : '' }}" 
                     data-value="" 
                     data-text="{{ $placeholder }}">
                    <div class="font-medium text-gray-900">{{ $placeholder }}</div>
                </div>
                
                {{-- Dynamic options --}}
                @foreach($options as $option)
                    @php
                        $value = data_get($option, $optionValue);
                        $text = data_get($option, $optionText);
                        $subtext = $optionSubtext ? data_get($option, $optionSubtext) : null;
                        $displayText = $subtext ? "$subtext - $text" : $text;
                        $isSelected = $selected == $value;
                    @endphp
                    
                    <div class="dropdown-option px-3 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100 {{ $isSelected ? 'bg-blue-50' : '' }}" 
                         data-value="{{ $value }}" 
                         data-text="{{ $displayText }}"
                         data-search="{{ strtolower($text . ' ' . ($subtext ?? '')) }}">
                        
                        @if($optionSubtext)
                            <div class="font-medium text-gray-900">{{ $text }}</div>
                            <div class="text-sm text-gray-500">{{ $subtext }}</div>
                        @else
                            <div class="font-medium text-gray-900">{{ $text }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Custom scrollbar for dropdown */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Firefox scrollbar */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: #c1c1c1 #f1f1f1;
}

/* Dropdown options height calculation */
.dropdown-option {
    min-height: 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const componentId = '{{ $componentId }}';
    const onchangeCallback = '{{ $onchange ?? '' }}';
    
    const button = document.getElementById(componentId + '_btn');
    const menu = document.getElementById(componentId + '_menu');
    const icon = document.getElementById(componentId + '_icon');
    const hiddenInput = document.getElementById('{{ $name }}');
    const displayText = document.getElementById(componentId + '_text');
    
    if (!button || !menu) return;
    
    let isOpen = false;
    
    // Toggle dropdown
    button.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (isOpen) {
            closeDropdown();
        } else {
            openDropdown();
        }
    });
    
    // Open dropdown
    function openDropdown() {
        isOpen = true;
        menu.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
        positionDropdown();
    }
    
    // Close dropdown
    function closeDropdown() {
        isOpen = false;
        menu.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
    
    // Position dropdown
    function positionDropdown() {
        const rect = button.getBoundingClientRect();
        const spaceBelow = window.innerHeight - rect.bottom;
        const dropdownHeight = {{ $containerHeight }};
        
        if (spaceBelow < dropdownHeight && rect.top > dropdownHeight) {
            // Open upward
            menu.style.bottom = '100%';
            menu.style.top = 'auto';
            menu.style.marginBottom = '4px';
            menu.style.marginTop = '0';
        } else {
            // Open downward (default)
            menu.style.top = '100%';
            menu.style.bottom = 'auto';
            menu.style.marginTop = '4px';
            menu.style.marginBottom = '0';
        }
    }
    
    // Handle option clicks
    const options = menu.querySelectorAll('.dropdown-option');
    options.forEach(option => {
        option.addEventListener('click', function() {
            const value = this.dataset.value;
            const text = this.dataset.text;
            
            // Update hidden input
            hiddenInput.value = value;
            
            // Update display text
            displayText.textContent = text;
            
            // Update selected state
            options.forEach(opt => opt.classList.remove('bg-blue-50'));
            this.classList.add('bg-blue-50');
            
            // Close dropdown
            closeDropdown();
            
            // Trigger callback
            if (onchangeCallback && window[onchangeCallback]) {
                window[onchangeCallback]();
            }
        });
    });
    
    // Close when clicking outside
    document.addEventListener('click', function(e) {
        if (!button.contains(e.target) && !menu.contains(e.target)) {
            closeDropdown();
        }
    });
});
</script>
@endpush
