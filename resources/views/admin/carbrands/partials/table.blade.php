<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 25%;">Hãng xe</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 30%;">Thông tin cơ bản</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 15%;">Trạng thái</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 15%;">Dòng xe</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 15%;">Thao tác</th>
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
                                @if($carbrand->address)
                                    <div class="truncate"><i class="fas fa-map-marker-alt mr-1"></i>{{ $carbrand->address }}</div>
                                @endif
                            </div>
                        @endif
                </td>
                <td class="px-6 py-4">
                    <div class="flex flex-col items-start gap-1 w-full">
                        <!-- Main Status - Using StatusToggle Component -->
                        <div class="w-full">
                            <x-admin.status-toggle 
                                :itemId="$carbrand->id" 
                                :currentStatus="$carbrand->is_active" 
                                entityType="carbrand" />
                        </div>
                        
                        <!-- Additional Badges - Uniform height and spacing -->
                        @if($carbrand->is_featured)
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-yellow-100 text-yellow-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-star mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Nổi bật</span>
                            </span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                    <div class="flex flex-col items-center">
                        <div class="flex items-center">
                            <i class="fas fa-layer-group text-gray-400 mr-2 flex-shrink-0"></i>
                            <span class="truncate">{{ $carbrand->carModels()->count() }} dòng xe</span>
                        </div>
                        @if($carbrand->carModels()->count() > 0)
                            <div class="text-xs text-gray-400 mt-1 truncate">
                                {{ $carbrand->carModels()->where('is_active', true)->count() }} hoạt động
                            </div>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                    <x-admin.table-actions 
                        :item="$carbrand"
                        show-route="admin.carbrands.show"
                        edit-route="admin.carbrands.edit"
                        delete-route="admin.carbrands.destroy"
                        :hasToggle="true"
                        :deleteData="['models-count' => $carbrand->carModels()->count()]" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-3 sm:px-6 py-12 text-center">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-industry text-gray-400 text-3xl sm:text-4xl mb-4"></i>
                        <p class="text-gray-500 text-base sm:text-lg">Không tìm thấy hãng xe nào</p>
                        <p class="text-gray-400 text-xs sm:text-sm mt-1">Thử thay đổi bộ lọc hoặc tìm kiếm</p>
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
