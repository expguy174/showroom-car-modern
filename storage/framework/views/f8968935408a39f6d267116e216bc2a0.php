<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'options', 'placeholder' => 'Chọn...', 'optionValue' => 'id', 'optionText' => 'name', 'optionSubtext' => null, 'selected' => null, 'onchange' => null, 'maxVisible' => 5, 'searchable' => true, 'width' => 'w-full']));

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

foreach (array_filter((['name', 'options', 'placeholder' => 'Chọn...', 'optionValue' => 'id', 'optionText' => 'name', 'optionSubtext' => null, 'selected' => null, 'onchange' => null, 'maxVisible' => 5, 'searchable' => true, 'width' => 'w-full']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $componentId = 'dropdown_' . str_replace(['[', ']', '.'], '_', $name) . '_' . uniqid();
    $containerHeight = min($maxVisible * 60, 240);
    
    // Function to get selected text
    $getSelectedText = function() use ($options, $selected, $placeholder, $optionValue, $optionText) {
        if (!$selected) {
            return $placeholder;
        }
        
        // Handle array/collection options
        if (is_array($options) || $options instanceof \Illuminate\Support\Collection) {
            foreach ($options as $option) {
                if (is_array($option) && isset($option[$optionValue]) && $option[$optionValue] == $selected) {
                    return (string) ($option[$optionText] ?? $placeholder);
                } elseif (is_object($option) && $option->{$optionValue} == $selected) {
                    return (string) ($option->{$optionText} ?? $placeholder);
                }
            }
        } else {
            // Handle simple key-value array
            return (string) ($options[$selected] ?? $placeholder);
        }
        
        return (string) $placeholder;
    };
?>

<div class="<?php echo e($width); ?> relative" data-component="custom-dropdown">
    
    <input type="hidden" name="<?php echo e($name); ?>" id="<?php echo e($componentId); ?>_input" value="<?php echo e($selected); ?>">
    
    
    <div class="custom-dropdown relative">
        <button type="button" 
                id="<?php echo e($componentId); ?>_btn"
                class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 pr-8 text-left shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400 transition-colors">
            <span id="<?php echo e($componentId); ?>_text" class="block truncate text-gray-900">
                <?php echo e($getSelectedText()); ?>

            </span>
            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                <i id="<?php echo e($componentId); ?>_icon" class="fas fa-chevron-down text-gray-400 transition-transform duration-200"></i>
            </span>
        </button>
        
        
        <div id="<?php echo e($componentId); ?>_menu" 
             class="absolute z-50 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg focus:outline-none hidden"
             style="min-width: 100%; width: max-content; max-width: 400px;">
            
            <?php if($searchable): ?>
            
            <div class="p-3 border-b border-gray-200">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 text-sm"></i>
                    </div>
                    <input type="text" 
                           id="<?php echo e($componentId); ?>_search"
                           class="block w-full pl-8 pr-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="Tìm kiếm...">
                </div>
            </div>
            <?php endif; ?>
            
            
            <div class="overflow-y-auto custom-scrollbar" 
                 id="<?php echo e($componentId); ?>_options"
                 style="max-height: <?php echo e($containerHeight); ?>px;">
                
                
                <div class="dropdown-option px-3 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100 <?php echo e(!$selected ? 'bg-blue-50' : ''); ?>" 
                     data-value="" 
                     data-text="<?php echo e($placeholder); ?>">
                    <div class="font-medium text-gray-900 whitespace-nowrap"><?php echo e($placeholder); ?></div>
                </div>
                
                
                <?php if(is_array($options) || $options instanceof \Illuminate\Support\Collection): ?>
                    <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            if (is_array($option)) {
                                $value = $option[$optionValue] ?? '';
                                $text = $option[$optionText] ?? '';
                                $subtext = $optionSubtext ? ($option[$optionSubtext] ?? '') : '';
                            } elseif (is_object($option)) {
                                $value = $option->{$optionValue} ?? '';
                                $text = $option->{$optionText} ?? '';
                                $subtext = $optionSubtext ? ($option->{$optionSubtext} ?? '') : '';
                            } else {
                                $value = $option;
                                $text = $option;
                                $subtext = '';
                            }
                            
                            // Ensure all values are strings
                            $value = (string) $value;
                            $text = (string) $text;
                            $subtext = (string) $subtext;
                            
                            $isSelected = $selected == $value;
                        ?>
                        
                        <div class="dropdown-option px-3 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100 <?php echo e($isSelected ? 'bg-blue-50' : ''); ?>" 
                             data-value="<?php echo e($value); ?>" 
                             data-text="<?php echo e($text); ?>">
                            <div class="font-medium text-gray-900 whitespace-nowrap"><?php echo e($text); ?></div>
                            <?php if($subtext): ?>
                                <div class="text-sm text-gray-500 whitespace-nowrap"><?php echo e($subtext); ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    
                    <div class="dropdown-option px-3 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100 <?php echo e(!$selected ? 'bg-blue-50' : ''); ?>" 
                         data-value="" 
                         data-text="<?php echo e($placeholder); ?>">
                        <div class="font-medium text-gray-900 whitespace-nowrap"><?php echo e($placeholder); ?></div>
                    </div>
                    
                    
                    <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $isSelected = $selected == $value;
                        ?>
                        
                        <div class="dropdown-option px-3 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100 <?php echo e($isSelected ? 'bg-blue-50' : ''); ?>" 
                             data-value="<?php echo e($value); ?>" 
                             data-text="<?php echo e($text); ?>">
                            <div class="font-medium text-gray-900 whitespace-nowrap"><?php echo e($text); ?></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const componentId = '<?php echo e($componentId); ?>';
    const onchangeCallback = '<?php echo e($onchange ?? ''); ?>';
    
    const button = document.getElementById(componentId + '_btn');
    const menu = document.getElementById(componentId + '_menu');
    const icon = document.getElementById(componentId + '_icon');
    const hiddenInput = document.getElementById(componentId + '_input');
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
    }
    
    // Close dropdown
    function closeDropdown() {
        isOpen = false;
        menu.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
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
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/components/admin/custom-dropdown.blade.php ENDPATH**/ ?>