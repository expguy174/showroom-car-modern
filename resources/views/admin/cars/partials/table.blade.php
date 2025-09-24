<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hãng xe</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thông tin cơ bản</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dòng xe</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($cars as $car)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12">
                            <img class="h-12 w-12 rounded-lg object-contain border border-gray-200 bg-white p-1" 
                                 src="{{ $car->logo_url }}" alt="{{ $car->name }}">
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $car->name }}</div>
                            <div class="text-sm text-gray-500">{{ $car->country ?? 'Chưa cập nhật' }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-900">
                        @if($car->founded_year)
                            <div class="flex items-center mb-1">
                                <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                                Thành lập: {{ $car->founded_year }}
                            </div>
                        @endif
                        @if($car->website)
                            <div class="flex items-center mb-1">
                                <i class="fas fa-globe text-gray-400 mr-2"></i>
                                <a href="{{ $car->website }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs">
                                    {{ Str::limit($car->website, 30) }}
                                </a>
                            </div>
                        @endif
                        @if($car->phone || $car->email || $car->address)
                            <div class="text-xs text-gray-500 mt-1">
                                @if($car->phone)
                                    <div><i class="fas fa-phone mr-1"></i>{{ $car->phone }}</div>
                                @endif
                                @if($car->email)
                                    <div><i class="fas fa-envelope mr-1"></i>{{ $car->email }}</div>
                                @endif
                            </div>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex flex-col space-y-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $car->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas {{ $car->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                            {{ $car->is_active ? 'Hoạt động' : 'Ngừng hoạt động' }}
                        </span>
                        @if($car->is_featured)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-star mr-1"></i>
                                Nổi bật
                            </span>
                        @endif
                        @if($car->sort_order > 0)
                            <span class="text-xs text-gray-500">
                                <i class="fas fa-sort-numeric-down mr-1"></i>
                                Thứ tự: {{ $car->sort_order }}
                            </span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="flex items-center">
                        <i class="fas fa-layer-group text-gray-400 mr-2"></i>
                        {{ $car->carModels()->count() }} dòng xe
                    </div>
                    @if($car->carModels()->count() > 0)
                        <div class="text-xs text-gray-400 mt-1">
                            {{ $car->carModels()->where('is_active', true)->count() }} đang hoạt động
                        </div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.cars.show', $car) }}" class="text-blue-600 hover:text-blue-900" title="Xem chi tiết">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.cars.edit', $car) }}" class="text-indigo-600 hover:text-indigo-900" title="Chỉnh sửa">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <!-- Quick Status Toggle -->
                        @if($car->is_active)
                            <button class="text-orange-600 hover:text-orange-900 status-toggle" title="Ngừng hoạt động" data-car-id="{{ $car->id }}" data-status="false">
                                <i class="fas fa-pause"></i>
                            </button>
                        @else
                            <button class="text-green-600 hover:text-green-900 status-toggle" title="Kích hoạt" data-car-id="{{ $car->id }}" data-status="true">
                                <i class="fas fa-play"></i>
                            </button>
                        @endif
                        <button 
                            class="text-red-600 hover:text-red-900 delete-btn" 
                            title="Xóa"
                            data-car-id="{{ $car->id }}"
                            data-car-name="{{ $car->name }}"
                            data-models-count="{{ $car->carModels()->count() }}"
                            data-variants-count="{{ $car->carModels()->withCount('carVariants')->get()->sum('car_variants_count') }}">
                            <i class="fas fa-trash"></i>
                        </button>
                        <form id="delete-form-{{ $car->id }}" action="{{ route('admin.cars.destroy', $car) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-industry text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500 text-lg">Không tìm thấy hãng xe nào</p>
                        <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc hoặc tìm kiếm</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($cars->hasPages())
<div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
    <x-admin.pagination :paginator="$cars->appends(request()->query())" />
</div>
@endif
