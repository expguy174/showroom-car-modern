<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 25%;">Hãng xe</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 30%;">Thông tin cơ bản</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 20%;">Trạng thái</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 15%;">Dòng xe</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 10%;">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($carbrands as $carbrand)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12">
                            <img class="h-12 w-12 rounded-lg object-contain border border-gray-200 bg-white p-1" 
                                 src="{{ $carbrand->logo_url }}" alt="{{ $carbrand->name }}">
                        </div>
                        <div class="ml-4 min-w-0 flex-1">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $carbrand->name }}</div>
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
                                {{ $countries[$carbrand->country] ?? $carbrand->country ?? 'Chưa cập nhật' }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-900">
                        @if($carbrand->founded_year)
                            <div class="flex items-center mb-1">
                                <i class="fas fa-calendar-alt text-gray-400 mr-2 flex-shrink-0"></i>
                                <span class="truncate">Thành lập: {{ $carbrand->founded_year }}</span>
                            </div>
                        @endif
                        @if($carbrand->website)
                            <div class="flex items-center mb-1">
                                <i class="fas fa-globe text-gray-400 mr-2 flex-shrink-0"></i>
                                <a href="{{ $carbrand->website }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline truncate">
                                    {{ parse_url($carbrand->website, PHP_URL_HOST) }}
                                </a>
                            </div>
                        @endif
                        @if($carbrand->phone || $carbrand->email || $carbrand->address)
                            <div class="text-xs text-gray-500 mt-1">
                                @if($carbrand->phone)
                                    <div class="truncate"><i class="fas fa-phone mr-1"></i>{{ $carbrand->phone }}</div>
                                @endif
                                @if($carbrand->email)
                                    <div class="truncate"><i class="fas fa-envelope mr-1"></i>{{ $carbrand->email }}</div>
                                @endif
                            </div>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex flex-col gap-1">
                        <!-- Main Status -->
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium {{ $carbrand->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas {{ $carbrand->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                            {{ $carbrand->is_active ? 'Hoạt động' : 'Tạm dừng' }}
                        </span>
                        
                        <!-- Additional Badges -->
                        @if($carbrand->is_featured)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-star mr-1"></i>
                                Nổi bật
                            </span>
                        @endif
                        @if($carbrand->sort_order > 0)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                <i class="fas fa-sort-numeric-down mr-1"></i>
                                #{{ $carbrand->sort_order }}
                            </span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="flex items-center">
                        <i class="fas fa-layer-group text-gray-400 mr-2 flex-shrink-0"></i>
                        <span class="truncate">{{ $carbrand->carModels()->count() }} dòng xe</span>
                    </div>
                    @if($carbrand->carModels()->count() > 0)
                        <div class="text-xs text-gray-400 mt-1 truncate">
                            {{ $carbrand->carModels()->where('is_active', true)->count() }} hoạt động
                        </div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.carbrands.show', $carbrand) }}" class="text-green-600 hover:text-green-900 w-4 h-4 flex items-center justify-center" title="Xem chi tiết">
                            <i class="fas fa-eye w-4 h-4"></i>
                        </a>
                        <a href="{{ route('admin.carbrands.edit', $carbrand) }}" class="text-indigo-600 hover:text-indigo-900 w-4 h-4 flex items-center justify-center" title="Chỉnh sửa">
                            <i class="fas fa-edit w-4 h-4"></i>
                        </a>
                        
                        <!-- Quick Status Toggle -->
                        @if($carbrand->is_active)
                            <button class="text-orange-600 hover:text-orange-900 status-toggle w-4 h-4 flex items-center justify-center" title="Tạm dừng" data-carbrand-id="{{ $carbrand->id }}" data-status="false">
                                <i class="fas fa-pause w-4 h-4"></i>
                            </button>
                        @else
                            <button class="text-green-600 hover:text-green-900 status-toggle w-4 h-4 flex items-center justify-center" title="Kích hoạt" data-carbrand-id="{{ $carbrand->id }}" data-status="true">
                                <i class="fas fa-play w-4 h-4"></i>
                            </button>
                        @endif
                        <button 
                            class="text-red-600 hover:text-red-900 delete-btn" 
                            title="Xóa"
                            data-carbrand-id="{{ $carbrand->id }}"
                            data-carbrand-name="{{ $carbrand->name }}"
                            data-models-count="{{ $carbrand->carModels()->count() }}"
                            data-variants-count="{{ $carbrand->carModels()->withCount('carVariants')->get()->sum('car_variants_count') }}">
                            <i class="fas fa-trash"></i>
                        </button>
                        <form id="delete-form-{{ $carbrand->id }}" action="{{ route('admin.carbrands.destroy', $carbrand) }}" method="POST" class="hidden">
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
@if($carbrands->hasPages())
<div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
    <x-admin.pagination :paginator="$carbrands->appends(request()->query())" />
</div>
@endif
