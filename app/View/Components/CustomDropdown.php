<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Collection;

class CustomDropdown extends Component
{
    public string $name;
    public Collection $options;
    public string $placeholder;
    public string $optionValue;
    public string $optionText;
    public ?string $optionSubtext;
    public mixed $selected;
    public ?string $onchange;
    public int $maxVisible;
    public bool $searchable;
    public string $width;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $name,
        $options = [],
        string $placeholder = 'Chọn...',
        string $optionValue = 'id',
        string $optionText = 'name',
        ?string $optionSubtext = null,
        mixed $selected = null,
        ?string $onchange = null,
        int $maxVisible = 6,
        bool $searchable = false,
        string $width = 'w-full md:w-48'
    ) {
        $this->name = $name;
        $this->options = collect($options);
        $this->placeholder = $placeholder;
        $this->optionValue = $optionValue;
        $this->optionText = $optionText;
        $this->optionSubtext = $optionSubtext;
        $this->selected = $selected;
        $this->onchange = $onchange;
        $this->maxVisible = $maxVisible;
        $this->searchable = $searchable;
        $this->width = $width;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.custom-dropdown');
    }

    /**
     * Get selected option text
     */
    public function getSelectedText()
    {
        if (!$this->selected) {
            return $this->placeholder;
        }

        $selectedOption = $this->options->where($this->optionValue, $this->selected)->first();
        
        if (!$selectedOption) {
            return $this->placeholder;
        }

        $text = data_get($selectedOption, $this->optionText);
        
        if ($this->optionSubtext) {
            $subtext = data_get($selectedOption, $this->optionSubtext);
            return $subtext ? "$subtext - $text" : $text;
        }

        return $text;
    }

    /**
     * Calculate container height
     */
    public function getContainerHeight()
    {
        return $this->maxVisible * 60; // 60px per option
    }
}
