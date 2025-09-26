<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StatusToggle extends Component
{
    public string $itemId;
    public bool $currentStatus;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $itemId,
        bool $currentStatus
    ) {
        $this->itemId = $itemId;
        $this->currentStatus = $currentStatus;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.status-toggle');
    }
}
