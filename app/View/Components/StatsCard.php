<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StatsCard extends Component
{
    public string $title;
    public string $value;
    public string $icon;
    public string $color;
    public ?string $clickAction;
    public ?string $description;
    public ?string $trend;
    public ?string $trendColor;
    public string $size;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $title,
        string $value,
        string $icon = 'fas fa-chart-bar',
        string $color = 'blue',
        ?string $clickAction = null,
        ?string $description = null,
        ?string $trend = null,
        ?string $trendColor = 'green',
        string $size = 'normal'
    ) {
        $this->title = $title;
        $this->value = $value;
        $this->icon = $icon;
        $this->color = $color;
        $this->clickAction = $clickAction;
        $this->description = $description;
        $this->trend = $trend;
        $this->trendColor = $trendColor;
        $this->size = $size;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.stats-card');
    }

    /**
     * Get color classes for the card
     */
    public function getColorClasses()
    {
        $colors = [
            'blue' => [
                'bg' => 'bg-blue-500',
                'text' => 'text-blue-600',
                'light' => 'bg-blue-50',
                'hover' => 'hover:bg-blue-600'
            ],
            'green' => [
                'bg' => 'bg-green-500',
                'text' => 'text-green-600',
                'light' => 'bg-green-50',
                'hover' => 'hover:bg-green-600'
            ],
            'red' => [
                'bg' => 'bg-red-500',
                'text' => 'text-red-600',
                'light' => 'bg-red-50',
                'hover' => 'hover:bg-red-600'
            ],
            'yellow' => [
                'bg' => 'bg-yellow-500',
                'text' => 'text-yellow-600',
                'light' => 'bg-yellow-50',
                'hover' => 'hover:bg-yellow-600'
            ],
            'purple' => [
                'bg' => 'bg-purple-500',
                'text' => 'text-purple-600',
                'light' => 'bg-purple-50',
                'hover' => 'hover:bg-purple-600'
            ],
            'gray' => [
                'bg' => 'bg-gray-500',
                'text' => 'text-gray-600',
                'light' => 'bg-gray-50',
                'hover' => 'hover:bg-gray-600'
            ]
        ];

        return $colors[$this->color] ?? $colors['blue'];
    }

    /**
     * Get trend color classes
     */
    public function getTrendColorClasses()
    {
        $colors = [
            'green' => 'text-green-600',
            'red' => 'text-red-600',
            'yellow' => 'text-yellow-600',
            'blue' => 'text-blue-600'
        ];

        return $colors[$this->trendColor] ?? $colors['green'];
    }
}
