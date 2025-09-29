@props(['item', 'showRoute' => null, 'editRoute' => null, 'deleteRoute' => null, 'hasToggle' => false, 'customActions' => []])

<div class="flex items-center justify-center space-x-1 sm:space-x-2">
    {{-- View Button --}}
    @if($showRoute)
        <a href="{{ route($showRoute, $item) }}" 
           class="text-green-600 hover:text-green-900 w-4 h-4 flex items-center justify-center" 
           title="Xem chi tiết">
            <i class="fas fa-eye w-4 h-4"></i>
        </a>
    @endif
    
    {{-- Edit Button --}}
    @if($editRoute)
        <a href="{{ route($editRoute, $item) }}" 
           class="text-blue-600 hover:text-blue-900 w-4 h-4 flex items-center justify-center" 
           title="Chỉnh sửa">
            <i class="fas fa-edit w-4 h-4"></i>
        </a>
    @endif
    
    {{-- Toggle Status Button --}}
    @if($hasToggle && isset($item->is_active))
        @if($item->is_active)
            <button class="text-orange-600 hover:text-orange-900 status-toggle w-4 h-4 flex items-center justify-center" 
                    title="Tạm dừng" 
                    data-variant-id="{{ $item->id }}" 
                    data-status="false">
                <i class="fas fa-pause w-4 h-4"></i>
            </button>
        @else
            <button class="text-green-600 hover:text-green-900 status-toggle w-4 h-4 flex items-center justify-center" 
                    title="Kích hoạt" 
                    data-variant-id="{{ $item->id }}" 
                    data-status="true">
                <i class="fas fa-play w-4 h-4"></i>
            </button>
        @endif
    @endif
    
    {{-- Custom Actions --}}
    @foreach($customActions as $action)
        {!! $action !!}
    @endforeach
    
    {{-- Delete Button --}}
    @if($deleteRoute)
        <button 
            class="text-red-600 hover:text-red-900 delete-btn w-4 h-4 flex items-center justify-center" 
            title="Xóa"
            data-variant-id="{{ $item->id }}"
            data-variant-name="{{ $item->name }}"
            @if(isset($item->carModel))
                data-model-name="{{ $item->carModel->carBrand->name }} {{ $item->carModel->name }}"
            @endif
            @if(method_exists($item, 'colors'))
                data-colors-count="{{ $item->colors()->count() }}"
            @endif
            @if(method_exists($item, 'images'))
                data-images-count="{{ $item->images()->count() }}"
            @endif>
            <i class="fas fa-trash w-4 h-4"></i>
        </button>
        
        {{-- Hidden Delete Form --}}
        <form id="delete-form-{{ $item->id }}" action="{{ route($deleteRoute, $item) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endif
</div>
