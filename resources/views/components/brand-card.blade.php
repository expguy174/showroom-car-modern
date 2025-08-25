@props(['brand'])

<a href="{{ route('car-brands.show', $brand->id) }}"
   class="group relative bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
    
    <!-- Featured Badge - Top Right -->
    @if($brand->is_featured)
        <span class="absolute top-3 right-3 inline-flex items-center gap-1 px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold">
            <i class="fas fa-star text-[10px]"></i> Nổi bật
        </span>
    @endif
    
    <div class="text-center">
        <!-- Brand Logo -->
        @php $logo = $brand->logo_url ?? null; @endphp
        @if($logo)
            <img src="{{ $logo }}" 
                 alt="{{ $brand->name }}" 
                 class="w-20 h-20 mx-auto mb-4 object-contain group-hover:scale-110 transition-transform duration-300" loading="lazy" decoding="async">
        @else
            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-car text-white text-3xl"></i>
            </div>
        @endif
        
        <!-- Brand Name -->
        <h3 class="font-bold text-lg text-gray-900 mb-2 group-hover:text-blue-600 transition-colors duration-300">
            {{ $brand->name }}
        </h3>

        <!-- Show number of models instead of country -->
        @php 
            $modelsCount = $brand->relationLoaded('carModels') ? $brand->carModels->count() : ($brand->carModels()->count());
        @endphp
        <div class="text-sm text-gray-600 mb-4 flex items-center justify-center gap-2">
            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-100 text-xs font-medium">
                <i class="fas fa-layer-group mr-1 text-blue-500"></i>
                {{ number_format($modelsCount) }} dòng xe
            </span>
        </div>
        

    </div>
</a>


