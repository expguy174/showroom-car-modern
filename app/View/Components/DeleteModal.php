<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DeleteModal extends Component
{
    public string $modalId;
    public string $title;
    public string $entityName;
    public string $warningText;
    public string $confirmText;
    public string $cancelText;
    public string $deleteCallbackName;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $modalId = 'deleteModal',
        string $title = 'Xác nhận xóa',
        string $entityName = 'mục này',
        string $warningText = 'Bạn có chắc chắn muốn xóa',
        string $confirmText = 'Xóa',
        string $cancelText = 'Hủy',
        string $deleteCallbackName = 'confirmDelete'
    ) {
        $this->modalId = $modalId;
        $this->title = $title;
        $this->entityName = $entityName;
        $this->warningText = $warningText;
        $this->confirmText = $confirmText;
        $this->cancelText = $cancelText;
        $this->deleteCallbackName = $deleteCallbackName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.delete-modal');
    }
}
