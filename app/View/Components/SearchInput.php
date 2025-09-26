<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SearchInput extends Component
{
    public string $name;
    public string $placeholder;
    public ?string $value;
    public string $callbackName;
    public int $debounceTime;
    public string $size;
    public bool $showIcon;
    public bool $showClearButton;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $name = 'search',
        string $placeholder = 'Tìm kiếm...',
        ?string $value = null,
        string $callbackName = 'handleSearch',
        int $debounceTime = 500,
        string $size = 'normal',
        bool $showIcon = true,
        bool $showClearButton = true
    ) {
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->value = $value ?? request($name);
        $this->callbackName = $callbackName;
        $this->debounceTime = $debounceTime;
        $this->size = $size;
        $this->showIcon = $showIcon;
        $this->showClearButton = $showClearButton;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.search-input');
    }

    /**
     * Get size classes
     */
    public function getSizeClasses()
    {
        $sizes = [
            'small' => [
                'input' => 'py-1 text-sm',
                'icon' => 'text-sm',
                'padding' => $this->showIcon ? 'pl-8 pr-3' : 'px-3'
            ],
            'normal' => [
                'input' => 'py-2 text-base',
                'icon' => 'text-base',
                'padding' => $this->showIcon ? 'pl-10 pr-3' : 'px-3'
            ],
            'large' => [
                'input' => 'py-3 text-lg',
                'icon' => 'text-lg',
                'padding' => $this->showIcon ? 'pl-12 pr-4' : 'px-4'
            ]
        ];

        return $sizes[$this->size] ?? $sizes['normal'];
    }
}
