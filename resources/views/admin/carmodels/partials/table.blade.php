<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dòng xe</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hãng xe</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thông tin</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phiên bản</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
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
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $model->name }}</div>
                            <div class="text-sm text-gray-500">{{ $model->slug }}</div>
                            @if($model->is_featured)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-star mr-1"></i>
                                    Nổi bật
                                </span>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                            <img class="h-8 w-8 rounded object-contain border border-gray-200 bg-white p-1" 
                                 src="{{ $model->carBrand->logo_url }}" alt="{{ $model->carBrand->name }}">
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">{{ $model->carBrand->name }}</div>
                            <div class="text-sm text-gray-500">{{ $model->carBrand->country ?? 'N/A' }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-900">
                        @if($model->production_start_year)
                            <div class="flex items-center mb-1">
                                <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                                {{ $model->production_start_year }}
                                @if($model->production_end_year && $model->production_end_year != $model->production_start_year)
                                    - {{ $model->production_end_year }}
                                @endif
                            </div>
                        @endif
                        @if($model->body_type)
                            <div class="flex items-center mb-1">
                                <i class="fas fa-car text-gray-400 mr-2"></i>
                                {{ ucfirst($model->body_type) }}
                            </div>
                        @endif
                        @if($model->fuel_type)
                            <div class="flex items-center text-gray-500">
                                <i class="fas fa-gas-pump text-gray-400 mr-2"></i>
                                {{ ucfirst($model->fuel_type) }}
                            </div>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex flex-col space-y-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $model->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas {{ $model->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                            {{ $model->is_active ? 'Hoạt động' : 'Ngừng hoạt động' }}
                        </span>
                        @if($model->is_featured)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-star mr-1"></i>
                                Nổi bật
                            </span>
                        @endif
                        @if($model->is_new)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-sparkles mr-1"></i>
                                Mới
                            </span>
                        @endif
                        @if($model->is_discontinued)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-ban mr-1"></i>
                                Ngừng sản xuất
                            </span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="flex items-center">
                        <i class="fas fa-cogs text-gray-400 mr-2"></i>
                        {{ $model->carVariants()->count() }} phiên bản
                    </div>
                    @if($model->carVariants()->count() > 0)
                        <div class="text-xs text-gray-400 mt-1">
                            {{ $model->carVariants()->where('is_active', true)->count() }} đang hoạt động
                        </div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.carmodels.show', $model) }}" class="text-blue-600 hover:text-blue-900" title="Xem chi tiết">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.carmodels.edit', $model) }}" class="text-indigo-600 hover:text-indigo-900" title="Chỉnh sửa">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <!-- Quick Status Toggle -->
                        @if($model->is_active)
                            <button class="text-orange-600 hover:text-orange-900 status-toggle" title="Ngừng hoạt động" data-model-id="{{ $model->id }}" data-status="false">
                                <i class="fas fa-pause"></i>
                            </button>
                        @else
                            <button class="text-green-600 hover:text-green-900 status-toggle" title="Kích hoạt" data-model-id="{{ $model->id }}" data-status="true">
                                <i class="fas fa-play"></i>
                            </button>
                        @endif
                        <button 
                            class="text-red-600 hover:text-red-900 delete-btn" 
                            title="Xóa"
                            data-model-id="{{ $model->id }}"
                            data-model-name="{{ $model->name }}"
                            data-brand-name="{{ $model->carBrand->name }}"
                            data-variants-count="{{ $model->carVariants()->count() }}">
                            <i class="fas fa-trash"></i>
                        </button>
                        <form id="delete-form-{{ $model->id }}" action="{{ route('admin.carmodels.destroy', $model) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
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
