@extends('layouts.admin')

@section('title', 'Chi tiết phụ kiện: ' . $accessory->name)

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16 mr-4">
                    @php
                        // Get first image from gallery for header
                        $galleryRaw = $accessory->gallery;
                        $gallery = is_array($galleryRaw) ? $galleryRaw : (json_decode($galleryRaw ?? '[]', true) ?: []);
                        $headerImage = !empty($gallery) ? $gallery[0] : null;
                    @endphp
                    
                    @if($headerImage)
                        @php
                            // Get image URL from array structure
                            $imageUrl = is_array($headerImage) 
                                ? ($headerImage['url'] ?? asset('storage/' . ($headerImage['file_path'] ?? '')))
                                : $headerImage;
                        @endphp
                        <img class="h-16 w-16 rounded-xl object-cover border border-gray-200 bg-white p-1" 
                             src="{{ $imageUrl }}" 
                             alt="{{ $accessory->name }}"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="h-16 w-16 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center" style="display: none;">
                            <i class="fas fa-cogs text-gray-400 text-xl"></i>
                        </div>
                    @else
                        <div class="h-16 w-16 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center">
                            <i class="fas fa-cogs text-gray-400 text-xl"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $accessory->name }}</h1>
                    <p class="text-gray-600 mt-1">SKU: {{ $accessory->sku ?? 'Chưa có' }}</p>
                    <div class="flex items-center mt-2 space-x-2">
                        @if($accessory->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Hoạt động
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>
                                Ngừng hoạt động
                            </span>
                        @endif

                        @if($accessory->is_featured)
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-yellow-100 text-yellow-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-star mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Nổi bật</span>
                            </span>
                        @endif

                        @if($accessory->is_on_sale)
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-orange-100 text-orange-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-percentage mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Khuyến mãi</span>
                            </span>
                        @endif

                        @if($accessory->is_new_arrival)
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-blue-100 text-blue-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-plus-circle mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Mới</span>
                            </span>
                        @endif

                        @if($accessory->is_bestseller)
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-purple-100 text-purple-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-fire mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Bán chạy</span>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.accessories.edit', $accessory) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Chỉnh sửa
                </a>
                <a href="{{ route('admin.accessories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Pricing Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-tag text-green-600 mr-3"></i>
                    Thông tin giá
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600 mb-1">Giá hiện tại</div>
                        <div class="text-2xl font-bold text-green-600">
                            {{ number_format($accessory->current_price) }}đ
                        </div>
                    </div>
                    
                    @if($accessory->is_on_sale && $accessory->current_price < $accessory->base_price)
                        <div class="bg-orange-50 rounded-lg p-4">
                            <div class="text-sm text-gray-600 mb-1">Giá niêm yết</div>
                            <div class="text-lg text-gray-500 line-through">
                                {{ number_format($accessory->base_price) }}đ
                            </div>
                            <div class="text-sm text-orange-600 font-medium mt-1">
                                <i class="fas fa-tags mr-1"></i>
                                Tiết kiệm {{ number_format($accessory->base_price - $accessory->current_price) }}đ
                                ({{ number_format((($accessory->base_price - $accessory->current_price) / $accessory->base_price) * 100, 1) }}%)
                            </div>
                        </div>
                    @endif
                    
                    {{-- Stock Information --}}
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="text-sm text-blue-600 mb-1">Tồn kho</div>
                        <div class="text-2xl font-bold {{ ($accessory->stock_quantity ?? 0) > 5 ? 'text-green-600' : (($accessory->stock_quantity ?? 0) > 0 ? 'text-orange-600' : 'text-red-600') }}">
                            {{ $accessory->stock_quantity ?? 0 }} sản phẩm
                        </div>
                    </div>
                </div>
            </div>


            {{-- Gallery Images --}}
            @php
                $galleryRaw = $accessory->gallery;
                $gallery = is_array($galleryRaw) ? $galleryRaw : (json_decode($galleryRaw ?? '[]', true) ?: []);
                
                // CRITICAL: Sort gallery exactly like edit page
                // Primary images first, then by sort_order
                usort($gallery, function($a, $b) {
                    // Primary images always come first
                    $isPrimaryA = isset($a['is_primary']) && $a['is_primary'] ? 1 : 0;
                    $isPrimaryB = isset($b['is_primary']) && $b['is_primary'] ? 1 : 0;
                    
                    if ($isPrimaryA !== $isPrimaryB) {
                        return $isPrimaryB - $isPrimaryA;
                    }
                    
                    // Then sort by sort_order (ascending)
                    $sortA = isset($a['sort_order']) ? (int)$a['sort_order'] : 999;
                    $sortB = isset($b['sort_order']) ? (int)$b['sort_order'] : 999;
                    return $sortA - $sortB;
                });
                
                // Convert gallery items to simple objects
                $galleryImages = collect($gallery)->map(function($imageData, $index) use ($accessory) {
                    // Handle both array (new format) and string (legacy) formats
                    if (is_array($imageData)) {
                        $imageUrl = $imageData['url'] ?? asset('storage/' . ($imageData['file_path'] ?? ''));
                        $title = $imageData['title'] ?? "Ảnh " . ($index + 1);
                        $altText = $imageData['alt_text'] ?? ($accessory->name . " - Ảnh " . ($index + 1));
                        $description = $imageData['description'] ?? null;
                        $imageType = $imageData['image_type'] ?? 'product';
                        $sortOrder = $imageData['sort_order'] ?? 0;
                        $isPrimary = $imageData['is_primary'] ?? false;
                    } else {
                        // Legacy string URL
                        $imageUrl = $imageData;
                        $title = "Ảnh " . ($index + 1);
                        $altText = $accessory->name . " - Ảnh " . ($index + 1);
                        $description = null;
                        $imageType = 'product';
                        $sortOrder = 0;
                        $isPrimary = false;
                    }
                    
                    return (object) [
                        'id' => $index + 1,
                        'image_url' => $imageUrl,
                        'title' => $title,
                        'alt_text' => $altText,
                        'description' => $description,
                        'image_type' => $imageType,
                        'sort_order' => $sortOrder,
                        'is_primary' => $isPrimary,
                        'is_main' => $isPrimary // Use is_primary from data
                    ];
                });
                
                // Pagination setup like CarVariant
                $perPage = 4; // Show 4 images per page like CarVariant
                $initialImages = $galleryImages->take($perPage);
            @endphp
            
            @if($galleryImages->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-images text-purple-600 mr-3"></i>
                    Thư viện ảnh ({{ $galleryImages->count() }} ảnh)
                </h2>
                
                {{-- Main Image Display --}}
                <div class="mb-4 max-w-2xl mx-auto">
                    <div class="relative group">
                        @php
                            $firstImage = $galleryImages->first();
                        @endphp
                        <img id="mainImage" 
                             src="{{ $firstImage->image_url }}" 
                             alt="{{ $firstImage->alt_text }}"
                             class="w-full h-64 md:h-80 object-cover rounded-lg border border-gray-200 shadow-sm cursor-pointer"
                             onclick="viewImage('{{ $firstImage->image_url }}', '{{ $firstImage->title }}')">
                        
                        {{-- Navigation arrows for multiple images --}}
                        @if($galleryImages->count() > 1)
                        <button id="prevBtn" onclick="previousImage()" 
                                class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-70 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button id="nextBtn" onclick="nextImage()" 
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-70 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        
                        {{-- Image counter --}}
                        <div class="absolute bottom-4 right-4 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm">
                            <span id="currentImageIndex">1</span> / {{ $galleryImages->count() }}
                        </div>
                        @endif
                    </div>
                </div>
                

                {{-- Card Gallery with Pagination --}}
                <div class="space-y-4">
                    <div id="imageGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($initialImages as $index => $image)
                            <div class="image-item relative bg-gray-50 rounded-lg overflow-hidden border border-gray-200 hover:border-blue-300 transition-colors thumbnail-item {{ $loop->first ? 'active' : '' }}" 
                                 data-index="{{ $index }}">
                                
                                {{-- Image --}}
                                <div class="relative cursor-pointer" onclick="changeMainImageByIndex({{ $index }})">
                                    <img src="{{ $image->image_url }}" alt="{{ $image->alt_text }}"
                                         class="w-full h-32 object-cover hover:opacity-90 transition-opacity">
                                </div>
                                
                                {{-- Top badges --}}
                                <div class="absolute top-2 left-2 flex flex-wrap gap-1">
                                    @if($image->is_main)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-star mr-1"></i>Chính
                                        </span>
                                    @endif
                                </div>
                                
                                {{-- Image info --}}
                                <div class="p-2">
                                    @if($image->title)
                                        <p class="text-xs font-medium text-gray-900 truncate mb-1">{{ $image->title }}</p>
                                    @endif
                                    @if($image->alt_text)
                                        <p class="text-xs text-gray-600 truncate">{{ $image->alt_text }}</p>
                                    @endif
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            @switch($image->image_type)
                                                @case('product') Sản phẩm @break
                                                @case('detail') Chi tiết @break
                                                @case('installation') Lắp đặt @break
                                                @case('usage') Sử dụng @break
                                                @default {{ ucfirst($image->image_type) }}
                                            @endswitch
                                        </span>
                                    </div>
                                    @if($image->description)
                                        <p class="text-xs text-gray-500 mt-1 truncate" title="{{ $image->description }}">{{ $image->description }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- Image Pagination --}}
                    @if($galleryImages->count() > $perPage)
                    <div id="imagePagination" class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div id="imageInfo" class="text-sm text-gray-600">
                            Hiển thị 1-{{ min($perPage, $galleryImages->count()) }} trong {{ $galleryImages->count() }} ảnh
                        </div>
                        <div id="paginationControls" class="flex items-center space-x-1">
                            {{-- Pagination buttons will be generated by JavaScript --}}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Stock Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-boxes text-indigo-600 mr-3"></i>
                    Thông tin kho hàng
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-gray-900">{{ $accessory->stock_quantity ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Số lượng tồn</div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <div class="text-lg font-semibold {{ $accessory->stock_status === 'in_stock' ? 'text-green-600' : ($accessory->stock_status === 'low_stock' ? 'text-yellow-600' : 'text-red-600') }}">
                            @switch($accessory->stock_status)
                                @case('in_stock')
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Còn hàng
                                    @break
                                @case('low_stock')
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Sắp hết
                                    @break
                                @case('out_of_stock')
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Hết hàng
                                    @break
                                @default
                                    <i class="fas fa-question-circle mr-1"></i>
                                    Không xác định
                            @endswitch
                        </div>
                        <div class="text-sm text-gray-600">Trạng thái</div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <div class="text-lg font-semibold text-gray-900">{{ $accessory->weight ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-600">Trọng lượng (kg)</div>
                    </div>
                </div>
            </div>

            {{-- Specifications --}}
            @php
                $specificationsRaw = $accessory->specifications;
                $specifications = is_array($specificationsRaw) ? $specificationsRaw : (json_decode($specificationsRaw ?? '[]', true) ?: []);
            @endphp
            
            @if(!empty($specifications))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-cog text-gray-600 mr-3"></i>
                    Thông số kỹ thuật
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($specifications as $spec)
                        @php
                            // Handle both array format {name: 'X', value: 'Y'} and key-value format
                            if (is_array($spec) && isset($spec['name'])) {
                                $specName = $spec['name'];
                                $specValue = $spec['value'] ?? 'N/A';
                            } else {
                                $specName = is_numeric($spec) ? 'Thông số' : (is_string($spec) ? $spec : 'N/A');
                                $specValue = is_array($spec) ? json_encode($spec) : $spec;
                            }
                        @endphp
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-600 mb-1 flex items-center">
                                <i class="fas fa-wrench text-gray-400 mr-2 text-xs"></i>
                                {{ $specName }}
                            </div>
                            <div class="font-medium text-gray-900">{{ $specValue }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Features --}}
            @php
                $featuresRaw = $accessory->features;
                $features = is_array($featuresRaw) ? $featuresRaw : (json_decode($featuresRaw ?? '[]', true) ?: []);
            @endphp
            
            @if(!empty($features))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-star text-yellow-600 mr-3"></i>
                    Tính năng nổi bật
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($features as $feature)
                        <div class="flex items-center bg-gray-50 rounded-lg p-3">
                            <i class="fas fa-check-circle text-green-600 mr-3"></i>
                            <span class="text-gray-900">{{ is_array($feature) ? ($feature['name'] ?? json_encode($feature)) : $feature }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Compatibility Information --}}
            @php
                $compatibleBrands = is_array($accessory->compatible_car_brands) ? $accessory->compatible_car_brands : (json_decode($accessory->compatible_car_brands ?? '[]', true) ?: []);
                $compatibleModels = is_array($accessory->compatible_car_models) ? $accessory->compatible_car_models : (json_decode($accessory->compatible_car_models ?? '[]', true) ?: []);
                $compatibleYears = is_array($accessory->compatible_car_years) ? $accessory->compatible_car_years : (json_decode($accessory->compatible_car_years ?? '[]', true) ?: []);
            @endphp
            
            @if(!empty($compatibleBrands) || !empty($compatibleModels) || !empty($compatibleYears))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-car text-blue-600 mr-3"></i>
                    Tương thích với xe
                </h2>
                
                <div class="space-y-4">
                    @if(!empty($compatibleBrands))
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Thương hiệu xe:</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($compatibleBrands as $brand)
                                <span class="inline-flex items-center px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800">
                                    {{ $brand }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    @if(!empty($compatibleModels))
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Dòng xe:</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($compatibleModels as $model)
                                <span class="inline-flex items-center px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">
                                    {{ $model }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    @if(!empty($compatibleYears))
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Năm sản xuất:</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($compatibleYears as $year)
                                <span class="inline-flex items-center px-3 py-1 text-sm rounded-full bg-purple-100 text-purple-800">
                                    {{ $year }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Installation & Warranty Information --}}
            @if($accessory->installation_instructions || $accessory->warranty_info || $accessory->installation_service_available || $accessory->installation_requirements || $accessory->warranty_terms)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-tools text-orange-600 mr-3"></i>
                    Lắp đặt & Bảo hành
                </h2>
                
                <div class="space-y-4">
                    @if($accessory->installation_service_available)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-wrench text-green-600 mr-2"></i>
                            <h3 class="font-medium text-green-900">Dịch vụ lắp đặt có sẵn</h3>
                        </div>
                        @if($accessory->installation_fee)
                            <p class="text-sm text-green-800">Phí lắp đặt: {{ number_format($accessory->installation_fee) }}đ</p>
                        @endif
                        @if($accessory->installation_time_minutes)
                            <p class="text-sm text-green-800">Thời gian lắp đặt: {{ $accessory->installation_time_minutes }} phút</p>
                        @endif
                    </div>
                    @endif
                    
                    @if($accessory->installation_requirements)
                    <div>
                        <h3 class="font-medium text-gray-900 mb-2 flex items-center">
                            <i class="fas fa-list-check text-orange-600 mr-2"></i>
                            Yêu cầu lắp đặt:
                        </h3>
                        <div class="text-gray-700 bg-orange-50 rounded-lg p-4">
                            {!! nl2br(e($accessory->installation_requirements)) !!}
                        </div>
                    </div>
                    @endif
                    
                    @if($accessory->installation_instructions)
                    <div>
                        <h3 class="font-medium text-gray-900 mb-2 flex items-center">
                            <i class="fas fa-book-open text-blue-600 mr-2"></i>
                            Hướng dẫn lắp đặt:
                        </h3>
                        <div class="text-gray-700 bg-gray-50 rounded-lg p-4">
                            {!! nl2br(e($accessory->installation_instructions)) !!}
                        </div>
                    </div>
                    @endif
                    
                    @if($accessory->warranty_info)
                    <div>
                        <h3 class="font-medium text-gray-900 mb-2 flex items-center">
                            <i class="fas fa-shield-alt text-blue-600 mr-2"></i>
                            Thông tin bảo hành:
                        </h3>
                        <div class="text-gray-700 bg-blue-50 rounded-lg p-4">
                            {!! nl2br(e($accessory->warranty_info)) !!}
                        </div>
                    </div>
                    @endif
                    
                    @if($accessory->warranty_terms)
                    <div>
                        <h3 class="font-medium text-gray-900 mb-2 flex items-center">
                            <i class="fas fa-file-contract text-indigo-600 mr-2"></i>
                            Điều khoản bảo hành:
                        </h3>
                        <div class="text-gray-700 bg-indigo-50 rounded-lg p-4">
                            {!! nl2br(e($accessory->warranty_terms)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Basic Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Thông tin cơ bản
                </h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Danh mục:</span>
                        <span class="font-medium text-gray-900">{{ $accessory->vietnamese_category }}</span>
                    </div>
                    
                    @if($accessory->subcategory)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Danh mục con:</span>
                        <span class="font-medium text-gray-900">{{ $accessory->vietnamese_subcategory ?? $accessory->subcategory }}</span>
                    </div>
                    @endif
                    
                    @if($accessory->dimensions)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kích thước:</span>
                        <span class="font-medium text-gray-900">{{ $accessory->dimensions }}</span>
                    </div>
                    @endif
                    
                    @if($accessory->material)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Chất liệu:</span>
                        <span class="font-medium text-gray-900">{{ $accessory->material }}</span>
                    </div>
                    @endif
                    
                    @if($accessory->warranty_months)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Bảo hành:</span>
                        <span class="font-medium text-gray-900">{{ $accessory->warranty_months }} tháng</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- SEO Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-search text-green-600 mr-2"></i>
                    Thông tin SEO
                </h3>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-600">Meta Title:</span>
                        <p class="text-sm text-gray-900 mt-1">{{ $accessory->meta_title ?? 'Chưa có' }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm text-gray-600">Meta Description:</span>
                        <p class="text-sm text-gray-900 mt-1">{{ $accessory->meta_description ?? 'Chưa có' }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm text-gray-600">Keywords:</span>
                        <p class="text-sm text-gray-900 mt-1">{{ $accessory->meta_keywords ?? 'Chưa có' }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm text-gray-600">Slug:</span>
                        <p class="text-sm text-gray-900 mt-1 font-mono bg-gray-100 px-2 py-1 rounded">{{ $accessory->slug }}</p>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-align-left text-blue-600 mr-2"></i>
                    Mô tả sản phẩm
                </h3>
                
                @if($accessory->short_description)
                <div class="mb-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Mô tả ngắn</h4>
                    <div class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">
                        {!! nl2br(e($accessory->short_description)) !!}
                    </div>
                </div>
                @endif
                
                @if($accessory->description)
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Mô tả chi tiết</h4>
                    <div class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3 max-h-32 overflow-y-auto">
                        {!! nl2br(e($accessory->description)) !!}
                    </div>
                </div>
                @endif
                
                @if(!$accessory->short_description && !$accessory->description)
                <p class="text-gray-500 italic text-sm">Chưa có mô tả</p>
                @endif
            </div>

            {{-- Color Options --}}
            @php
                $colorOptionsRaw = $accessory->color_options;
                $colorOptions = is_array($colorOptionsRaw) ? $colorOptionsRaw : (json_decode($colorOptionsRaw ?? '[]', true) ?: []);
            @endphp
            
            @if(!empty($colorOptions))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-palette text-pink-600 mr-2"></i>
                    Tùy chọn màu
                </h3>
                
                <div class="flex flex-wrap gap-2">
                    @foreach($colorOptions as $color)
                        <span class="inline-flex items-center px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-800 border">
                            @if(is_array($color) && isset($color['hex']))
                                <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $color['hex'] }};"></div>
                            @else
                                <div class="w-3 h-3 rounded-full mr-2 bg-gray-400"></div>
                            @endif
                            {{ is_array($color) ? ($color['name'] ?? 'Không rõ') : $color }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Sale Information --}}
            @if($accessory->is_on_sale && ($accessory->sale_start_date || $accessory->sale_end_date))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-percentage text-orange-600 mr-2"></i>
                    Thông tin khuyến mãi
                </h3>
                
                <div class="space-y-3">
                    @if($accessory->sale_start_date)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Bắt đầu:</span>
                        <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($accessory->sale_start_date)->format('d/m/Y') }}</span>
                    </div>
                    @endif
                    
                    @if($accessory->sale_end_date)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kết thúc:</span>
                        <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($accessory->sale_end_date)->format('d/m/Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Return Policy --}}
            @if($accessory->return_policy || $accessory->return_policy_days)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-undo text-blue-600 mr-2"></i>
                    Chính sách đổi trả
                </h3>
                
                <div class="space-y-3">
                    @if($accessory->return_policy_days)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Thời hạn đổi trả:</span>
                        <span class="font-medium text-gray-900">{{ $accessory->return_policy_days }} ngày</span>
                    </div>
                    @endif
                    
                    @if($accessory->return_policy)
                    <div>
                        <span class="text-gray-600">Chi tiết:</span>
                        <div class="text-gray-900 mt-2 bg-gray-50 rounded-lg p-3 text-sm">
                            {!! nl2br(e($accessory->return_policy)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif


            {{-- Timestamps --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-clock text-gray-600 mr-2"></i>
                    Thời gian
                </h3>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-600">Tạo lúc:</span>
                        <p class="text-sm text-gray-900 mt-1">{{ $accessory->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm text-gray-600">Cập nhật lúc:</span>
                        <p class="text-sm text-gray-900 mt-1">{{ $accessory->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Image Modal --}}
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-4xl max-h-full overflow-auto">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h3 id="imageTitle" class="text-lg font-semibold text-gray-900"></h3>
            <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-4">
            <img id="modalImage" src="" alt="" class="max-w-full h-auto">
        </div>
    </div>
</div>

@push('scripts')
<script>
// Gallery images data
const images = [
    @if($galleryImages->count() > 0)
        @foreach($galleryImages as $index => $image)
        {
            url: '{{ $image->image_url }}',
            alt: '{{ $image->alt_text }}',
            title: '{{ $image->title }}',
            index: {{ $index }},
            is_primary: {{ $image->is_primary ? 'true' : 'false' }},
            is_main: {{ $image->is_main ? 'true' : 'false' }}
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
    @endif
];

// Find index of main/primary image after sort
let currentImageIndex = images.findIndex(img => img.is_primary || img.is_main);
if (currentImageIndex === -1) currentImageIndex = 0; // Fallback to first if no primary

// Pagination variables
let currentPage = 1;
let perPage = 4; // Match with PHP $perPage
let totalImages = 0;
let totalPages = 0;

// Initialize gallery
document.addEventListener('DOMContentLoaded', function() {
    if (images.length > 0) {
        totalImages = images.length;
        totalPages = Math.ceil(totalImages / perPage);
        
        if (totalImages > perPage) {
            updatePagination();
        }
        
        // Set initial active state for primary/main image
        updateThumbnailActive();
    }
});

// Change main image by index
function changeMainImageByIndex(index) {
    if (index >= 0 && index < images.length) {
        currentImageIndex = index;
        const image = images[index];
        changeMainImage(image.url, image.alt, image.title);
        updateImageCounter();
        updateThumbnailActive();
    }
}

// Change main image when clicking thumbnail
function changeMainImage(url, alt, title) {
    const mainImage = document.getElementById('mainImage');
    if (mainImage) {
        mainImage.src = url;
        mainImage.alt = alt || 'Hình ảnh';
        // Update onclick for modal
        mainImage.onclick = function() {
            viewImage(url, title || alt || 'Hình ảnh');
        };
    }
}

// Navigate to previous image
function previousImage() {
    const prevIndex = currentImageIndex > 0 ? currentImageIndex - 1 : images.length - 1;
    changeMainImageByIndex(prevIndex);
    syncThumbnailPagination(prevIndex);
}

// Navigate to next image
function nextImage() {
    const nextIndex = currentImageIndex < images.length - 1 ? currentImageIndex + 1 : 0;
    changeMainImageByIndex(nextIndex);
    syncThumbnailPagination(nextIndex);
}

// Sync thumbnail pagination when using main image arrows
function syncThumbnailPagination(imageIndex) {
    const targetPage = Math.ceil((imageIndex + 1) / perPage);
    if (targetPage !== currentPage && totalPages > 1) {
        currentPage = targetPage;
        renderImages();
        updatePagination();
    }
    updateThumbnailActive();
}

// Update image counter
function updateImageCounter() {
    const counter = document.getElementById('currentImageIndex');
    if (counter) {
        counter.textContent = currentImageIndex + 1;
    }
}

// Update thumbnail active state
function updateThumbnailActive() {
    const thumbnails = document.querySelectorAll('.thumbnail-item');
    thumbnails.forEach((thumb) => {
        const thumbIndex = parseInt(thumb.getAttribute('data-index'));
        if (thumbIndex === currentImageIndex) {
            // Active state with shadow like CarVariant
            thumb.classList.add('active', 'shadow-lg');
            thumb.style.borderColor = '#3B82F6'; // Blue-500
            thumb.style.borderWidth = '2px';
        } else {
            // Inactive state
            thumb.classList.remove('active', 'shadow-lg');
            thumb.style.borderColor = '#E5E7EB'; // Gray-200
            thumb.style.borderWidth = '1px';
        }
    });
}


// Pagination functions
function updatePagination() {
    const imageInfo = document.getElementById('imageInfo');
    const paginationControls = document.getElementById('paginationControls');
    
    if (!imageInfo || !paginationControls) return;
    
    // Update info text
    const startIndex = (currentPage - 1) * perPage + 1;
    const endIndex = Math.min(currentPage * perPage, totalImages);
    imageInfo.textContent = `Hiển thị ${startIndex}-${endIndex} trong ${totalImages} ảnh`;
    
    // Generate pagination controls
    paginationControls.innerHTML = '';
    
    if (totalPages > 1) {
        // Previous button
        if (currentPage > 1) {
            const prevBtn = document.createElement('button');
            prevBtn.className = 'inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors';
            prevBtn.innerHTML = '<i class="fas fa-chevron-left mr-1"></i>Trước';
            prevBtn.onclick = () => goToPage(currentPage - 1);
            paginationControls.appendChild(prevBtn);
        }
        
        // Page numbers (show max 5 pages)
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, startPage + 4);
        
        for (let i = startPage; i <= endPage; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = i === currentPage 
                ? 'inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-300 rounded-lg'
                : 'inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors';
            pageBtn.textContent = i;
            if (i !== currentPage) {
                pageBtn.onclick = () => goToPage(i);
            }
            paginationControls.appendChild(pageBtn);
        }
        
        // Next button
        if (currentPage < totalPages) {
            const nextBtn = document.createElement('button');
            nextBtn.className = 'inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors';
            nextBtn.innerHTML = 'Sau<i class="fas fa-chevron-right ml-1"></i>';
            nextBtn.onclick = () => goToPage(currentPage + 1);
            paginationControls.appendChild(nextBtn);
        }
    }
}

function goToPage(page) {
    if (page >= 1 && page <= totalPages) {
        currentPage = page;
        renderImages();
        updatePagination();
    }
}

function renderImages() {
    const imageGrid = document.getElementById('imageGrid');
    if (!imageGrid) return;
    
    const startIndex = (currentPage - 1) * perPage;
    const endIndex = startIndex + perPage;
    const imagesToShow = images.slice(startIndex, endIndex);
    
    // Clear current images
    imageGrid.innerHTML = '';
    
    // Render new images
    imagesToShow.forEach((imageData, index) => {
        const actualIndex = startIndex + index;
        const card = createImageCard(imageData, actualIndex);
        imageGrid.appendChild(card);
    });
}

function createImageCard(imageData, index) {
    const card = document.createElement('div');
    card.className = 'image-item relative bg-gray-50 rounded-lg overflow-hidden border border-gray-200 hover:border-blue-300 transition-colors thumbnail-item';
    card.setAttribute('data-index', imageData.index);
    
    card.innerHTML = `
        <div class="relative cursor-pointer" onclick="changeMainImageByIndex(${imageData.index})">
            <img src="${imageData.url}" alt="${imageData.alt}"
                 class="w-full h-32 object-cover hover:opacity-90 transition-opacity">
        </div>
        
        ${imageData.index === 0 ? `
        <div class="absolute top-2 left-2 flex flex-wrap gap-1">
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                <i class="fas fa-star mr-1"></i>Chính
            </span>
        </div>
        ` : ''}
        
        <div class="p-2">
            <p class="text-xs font-medium text-gray-900 truncate mb-1">${imageData.title}</p>
            <p class="text-xs text-gray-600 truncate">${imageData.alt}</p>
        </div>
    `;
    
    return card;
}

// Image modal functionality
function viewImage(url, title) {
    document.getElementById('modalImage').src = url;
    document.getElementById('imageTitle').textContent = title || 'Hình ảnh';
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (!document.getElementById('imageModal').classList.contains('hidden')) {
        // Only work when modal is open
        if (e.key === 'Escape') {
            e.preventDefault();
            closeImageModal();
        } else if (e.key === 'ArrowLeft') {
            e.preventDefault();
            previousImage();
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            nextImage();
        }
    }
});
</script>
@endpush
@endsection
