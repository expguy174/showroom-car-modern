<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ResetButton extends Component
{
    public string $formId;
    public string $callbackName;
    public string $text;
    public string $icon;
    public string $classes;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $formId,
        string $callbackName = 'handleFormReset',
        string $text = 'Đặt lại',
        string $icon = 'fas fa-undo',
        string $classes = 'inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors'
    ) {
        $this->formId = $formId;
        $this->callbackName = $callbackName;
        $this->text = $text;
        $this->icon = $icon;
        $this->classes = $classes;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.reset-button');
    }
}
