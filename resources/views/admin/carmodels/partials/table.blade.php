<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 20%;">Dòng xe</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 20%;">Hãng xe</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 20%;">Thông tin</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 15%;">Trạng thái</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 10%;">Phiên bản</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 15%;">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($carModels as $model)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-16 w-16">
                            <img class="h-16 w-16 rounded-lg object-cover border border-gray-200 bg-white p-1 shadow-sm" 
                                 src="{{ $model->image_url }}" alt="{{ $model->name }}">
                        </div>
                        <div class="ml-4 min-w-0 flex-1">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $model->name }}</div>
                            <div class="text-sm text-gray-500 truncate">{{ $model->slug }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                            <img class="h-8 w-8 rounded object-contain border border-gray-200 bg-white p-1" 
                                 src="{{ $model->carBrand->logo_url }}" alt="{{ $model->carBrand->name }}">
                        </div>
                        <div class="ml-3 min-w-0 flex-1">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $model->carBrand->name }}</div>
                            <div class="text-sm text-gray-500 truncate">
                                @php
                                    $countries = [
                                        'Japan' => 'Nhật Bản',
                                        'Germany' => 'Đức',
                                        'USA' => 'Mỹ',
                                        'South Korea' => 'Hàn Quốc',
                                        'France' => 'Pháp',
                                        'Italy' => 'Ý',
                                        'United Kingdom' => 'Anh',
                                        'Sweden' => 'Thụy Điển',
                                        'Czech Republic' => 'Séc',
                                        'Spain' => 'Tây Ban Nha',
                                        'China' => 'Trung Quốc',
                                        'India' => 'Ấn Độ',
                                        'Malaysia' => 'Malaysia',
                                        'Thailand' => 'Thái Lan',
                                        'Vietnam' => 'Việt Nam'
                                    ];
                                @endphp
                                {{ $countries[$model->carBrand->country] ?? $model->carBrand->country ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-900">
                        @if($model->body_type)
                            <div class="flex items-center mb-1">
                                <i class="fas fa-car text-gray-400 mr-2 flex-shrink-0"></i>
                                <span class="truncate">
                                    @php
                                        $bodyTypes = [
                                            'sedan' => 'Sedan',
                                            'suv' => 'SUV',
                                            'hatchback' => 'Hatchback',
                                            'wagon' => 'Wagon',
                                            'coupe' => 'Coupe',
                                            'convertible' => 'Convertible',
                                            'pickup' => 'Pickup',
                                            'van' => 'Van',
                                            'minivan' => 'Minivan'
                                        ];
                                    @endphp
                                    {{ $bodyTypes[$model->body_type] ?? ucfirst($model->body_type) }}
                                </span>
                            </div>
                        @endif
                        @if($model->segment)
                            <div class="flex items-center mb-1">
                                <i class="fas fa-layer-group text-gray-400 mr-2 flex-shrink-0"></i>
                                <span class="truncate">
                                    @php
                                        $segments = [
                                            'economy' => 'Xe tiết kiệm',
                                            'compact' => 'Xe nhỏ gọn',
                                            'mid-size' => 'Xe cỡ trung',
                                            'full-size' => 'Xe cỡ lớn',
                                            'luxury' => 'Xe sang trọng',
                                            'premium' => 'Xe cao cấp',
                                            'sports' => 'Xe thể thao',
                                            'exotic' => 'Xe siêu sang'
                                        ];
                                    @endphp
                                    {{ $segments[$model->segment] ?? ucfirst($model->segment) }}
                                </span>
                            </div>
                        @endif
                        @if($model->fuel_type)
                            <div class="flex items-center">
                                <i class="fas fa-gas-pump text-gray-400 mr-2 flex-shrink-0"></i>
                                <span class="truncate">
                                    @php
                                        $fuelTypes = [
                                            'gasoline' => 'Xăng',
                                            'diesel' => 'Dầu',
                                            'hybrid' => 'Hybrid',
                                            'electric' => 'Điện',
                                            'plug-in_hybrid' => 'Plug-in Hybrid',
                                            'hydrogen' => 'Hydrogen'
                                        ];
                                    @endphp
                                    {{ $fuelTypes[$model->fuel_type] ?? ucfirst($model->fuel_type) }}
                                </span>
                            </div>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex flex-col items-start gap-1 w-full">
                        <!-- Main Status - Using StatusToggle Component -->
                        <div class="w-full">
                            <x-admin.status-toggle 
                                :item-id="$model->id"
                                :current-status="$model->is_active"
                                entity-type="carmodel" />
                        </div>
                        
                        <!-- Additional Badges - Uniform height and spacing -->
                        @if($model->is_featured)
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-yellow-100 text-yellow-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-star mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Nổi bật</span>
                            </span>
                        @endif
                        
                        @if($model->is_discontinued)
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-red-100 text-red-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-ban mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Ngừng SX</span>
                            </span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                    <div class="flex flex-col items-center">
                        <div class="flex items-center">
                            <i class="fas fa-cogs text-gray-400 mr-2 flex-shrink-0"></i>
                            <span class="truncate">{{ $model->carVariants()->count() }} phiên bản</span>
                        </div>
                        @if($model->carVariants()->count() > 0)
                            <div class="text-xs text-gray-400 mt-1 truncate">
                                {{ $model->carVariants()->where('is_active', true)->count() }} hoạt động
                            </div>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                    <x-admin.table-actions 
                        :item="$model"
                        show-route="admin.carmodels.show"
                        edit-route="admin.carmodels.edit"
                        delete-route="admin.carmodels.destroy"
                        :has-toggle="true"
                        :delete-data="[
                            'brand-name' => $model->carBrand->name ?? '',
                            'variants-count' => $model->carVariants()->count()
                        ]" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-layer-group text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500 text-lg">Không tìm thấy dòng xe nào</p>
                        <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc hoặc tìm kiếm</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($carModels->hasPages())
<div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
    <x-admin.pagination :paginator="$carModels->appends(request()->query())" />
</div>
@endif
