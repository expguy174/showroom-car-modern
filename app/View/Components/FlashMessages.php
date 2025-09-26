<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FlashMessages extends Component
{
    public bool $showIcons;
    public bool $dismissible;
    public string $position;
    public int $autoHide;

    /**
     * Create a new component instance.
     */
    public function __construct(
        bool $showIcons = true,
        bool $dismissible = true,
        string $position = 'top-right',
        int $autoHide = 5000
    ) {
        $this->showIcons = $showIcons;
        $this->dismissible = $dismissible;
        $this->position = $position;
        $this->autoHide = $autoHide;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.flash-messages');
    }

    /**
     * Get position classes
     */
    public function getPositionClasses()
    {
        $positions = [
            'top-right' => 'top-4 right-4',
            'top-left' => 'top-4 left-4',
            'top-center' => 'top-4 left-1/2 transform -translate-x-1/2',
            'bottom-right' => 'bottom-4 right-4',
            'bottom-left' => 'bottom-4 left-4',
            'bottom-center' => 'bottom-4 left-1/2 transform -translate-x-1/2'
        ];

        return $positions[$this->position] ?? $positions['top-right'];
    }

    /**
     * Check if there are any flash messages
     */
    public function hasMessages()
    {
        return session()->has('success') || 
               session()->has('error') || 
               session()->has('warning') || 
               session()->has('info');
    }
}
