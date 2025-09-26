<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AjaxTable extends Component
{
    public string $tableId;
    public string $loadingId;
    public string $formId;
    public string $baseUrl;
    public string $callbackName;
    public ?string $emptyMessage;
    public ?string $emptyIcon;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $tableId,
        string $loadingId,
        string $formId,
        string $baseUrl,
        string $callbackName = 'loadTableData',
        ?string $emptyMessage = 'Không tìm thấy dữ liệu',
        ?string $emptyIcon = 'fas fa-inbox'
    ) {
        $this->tableId = $tableId;
        $this->loadingId = $loadingId;
        $this->formId = $formId;
        $this->baseUrl = $baseUrl;
        $this->callbackName = $callbackName;
        $this->emptyMessage = $emptyMessage;
        $this->emptyIcon = $emptyIcon;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.ajax-table');
    }
}
