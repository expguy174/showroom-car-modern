@props(['title', 'description' => null, 'icon' => null])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
        <div class="min-w-0 flex-1">
            <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 flex items-center">
                @if($icon)
                    <i class="{{ $icon }} text-blue-600 mr-2 sm:mr-3 text-base sm:text-lg lg:text-xl"></i>
                @endif
                <span class="truncate">{{ $title }}</span>
            </h1>
            @if($description)
                <p class="text-gray-600 mt-1 text-xs sm:text-sm lg:text-base">{{ $description }}</p>
            @endif
        </div>
        <div class="flex-shrink-0">
            {{ $slot }}
        </div>
    </div>
</div>
