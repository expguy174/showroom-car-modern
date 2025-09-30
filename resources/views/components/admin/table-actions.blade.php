@props([
    // New style props (preferred)
    'item' => null,
    'showRoute' => null,
    'editRoute' => null, 
    'deleteRoute' => null,
    'hasToggle' => false,
    
    // Legacy props (for backward compatibility)
    'itemId' => null,
    'itemName' => null, 
    'currentStatus' => null,
    'entityName' => 'item',
    'entityType' => 'variant',
    'toggleEndpoint' => null,
    'deleteData' => []
])

@php
    // Use new style if item is provided, otherwise use legacy props
    $id = $item ? $item->id : $itemId;
    $name = $item ? $item->name : $itemName;
    $status = $item ? $item->is_active : $currentStatus;
    $entity = $item ? class_basename($item) : $entityType;
@endphp

{{-- Table Actions Component --}}
<div class="flex items-center justify-center space-x-1">
    {{-- Status Toggle (New Style) --}}
    @if($hasToggle && $item)
        <button type="button" 
                class="text-{{ $status ? 'orange' : 'green' }}-600 hover:text-{{ $status ? 'orange' : 'green' }}-900 status-toggle w-4 h-4 flex items-center justify-center"
                data-brand-id="{{ $id }}"
                data-{{ strtolower($entity) }}-id="{{ $id }}"
                data-status="{{ $status ? 'false' : 'true' }}"
                title="{{ $status ? 'Tạm dừng' : 'Kích hoạt' }}">
            <i class="fas fa-{{ $status ? 'pause' : 'play' }} w-4 h-4"></i>
        </button>
    {{-- Status Toggle (Legacy Style) --}}
    @elseif($toggleEndpoint)
        <button type="button" 
                class="text-{{ $currentStatus ? 'orange' : 'green' }}-600 hover:text-{{ $currentStatus ? 'orange' : 'green' }}-900 status-toggle w-4 h-4 flex items-center justify-center"
                data-{{ $entityType }}-id="{{ $itemId }}"
                data-status="{{ $currentStatus ? 'false' : 'true' }}"
                data-toggle-endpoint="{{ $toggleEndpoint }}"
                title="{{ $currentStatus ? 'Tạm dừng' : 'Kích hoạt' }}">
            <i class="fas fa-{{ $currentStatus ? 'pause' : 'play' }} w-4 h-4"></i>
        </button>
    @endif
    
    {{-- Show Button --}}
    @if($showRoute)
        <a href="{{ route($showRoute, $id) }}" 
           class="text-gray-600 hover:text-gray-900 w-4 h-4 flex items-center justify-center" 
           title="Xem chi tiết">
            <i class="fas fa-eye w-4 h-4"></i>
        </a>
    @endif
    
    {{-- Edit Button --}}
    @if($editRoute)
        <a href="{{ route($editRoute, $id) }}" 
           class="text-blue-600 hover:text-blue-900 w-4 h-4 flex items-center justify-center" 
           title="Chỉnh sửa">
            <i class="fas fa-edit w-4 h-4"></i>
        </a>
    @endif
    
    {{-- Delete Button (New Style) --}}
    @if($deleteRoute && $item)
        <button 
            class="text-red-600 hover:text-red-900 delete-btn w-4 h-4 flex items-center justify-center" 
            title="Xóa"
            data-{{ strtolower($entity) }}-id="{{ $id }}"
            data-{{ strtolower($entity) }}-name="{{ $name }}"
            data-delete-url="{{ route($deleteRoute, $id) }}"
            @if($deleteData)
                @foreach($deleteData as $key => $value)
                    data-{{ $key }}="{{ $value }}"
                @endforeach
            @endif>
            <i class="fas fa-trash w-4 h-4"></i>
        </button>
    {{-- Delete Button (Legacy Style) --}}
    @else
        <button 
            class="text-red-600 hover:text-red-900 delete-btn w-4 h-4 flex items-center justify-center" 
            title="Xóa"
            data-{{ $entityType }}-id="{{ $itemId }}"
            data-{{ $entityType }}-name="{{ $itemName }}"
            @foreach($deleteData as $key => $value)
                data-{{ $key }}="{{ $value }}"
            @endforeach>
            <i class="fas fa-trash w-4 h-4"></i>
        </button>
    @endif
</div>
