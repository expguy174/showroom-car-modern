<div class="overflow-x-auto">
    <div class="inline-block min-w-full align-middle">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th style="width: 30%" class="px-2 sm:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <span class="lg:hidden">Sản phẩm</span>
                        <span class="hidden lg:inline">Phiên bản xe</span>
                    </th>
                    <th style="width: 20%" class="hidden md:table-cell px-2 sm:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dòng xe</th>
                    <th style="width: 20%" class="px-2 sm:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá bán</th>
                    <th style="width: 15%" class="hidden sm:table-cell px-2 sm:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Trạng thái</th>
                    <th style="width: 15%" class="px-2 sm:px-4 lg:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($carVariants as $variant)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-2 sm:px-4 lg:px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 sm:h-12 sm:w-12">
                            @if($variant->images->where('is_main', true)->first())
                                <img class="h-10 w-10 sm:h-12 sm:w-12 rounded-lg object-cover border border-gray-200" 
                                     src="{{ $variant->images->where('is_main', true)->first()->image_url }}" 
                                     alt="{{ $variant->name }}">
                            @else
                                <div class="h-10 w-10 sm:h-12 sm:w-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-semibold text-xs sm:text-sm">
                                    {{ strtoupper(substr($variant->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-3 sm:ml-4 flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $variant->name }}</div>
                            <div class="text-xs sm:text-sm text-gray-500 truncate">
                                {{-- Mobile: Show model info --}}
                                <div class="md:hidden">
                                    {{ $variant->carModel->carBrand->name }} {{ $variant->carModel->name }}
                                </div>
                                {{-- Desktop: Show SKU --}}
                                <div class="hidden md:block">
                                    @if($variant->sku)
                                        <span class="inline-flex items-center">
                                            <i class="fas fa-barcode text-gray-400 mr-1"></i>
                                            {{ $variant->sku }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            {{-- Mobile: Show status badges --}}
                            <div class="sm:hidden mt-1">
                                <div class="flex flex-wrap items-center gap-1">
                                    <x-admin.status-toggle 
                                        :item-id="$variant->id"
                                        :current-status="$variant->is_active" />
                                    <x-admin.status-badges 
                                        :item="$variant"
                                        size="small" />
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="hidden md:table-cell px-2 sm:px-4 lg:px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                            <img class="h-8 w-8 rounded object-contain bg-white p-1 border border-gray-200" 
                                 src="{{ $variant->carModel->carBrand->logo_url }}" 
                                 alt="{{ $variant->carModel->carBrand->name }}">
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $variant->carModel->name }}</div>
                            <div class="text-sm text-gray-500 truncate">{{ $variant->carModel->carBrand->name }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-2 sm:px-4 lg:px-6 py-4">
                    <div class="text-sm text-gray-900">
                        <div class="font-semibold text-base sm:text-lg text-blue-600">
                            {{ number_format($variant->current_price ?: $variant->base_price) }}đ
                        </div>
                        @if($variant->is_on_sale && $variant->current_price < $variant->base_price)
                            <div class="text-xs text-gray-500 line-through">
                                {{ number_format($variant->base_price) }}đ
                            </div>
                            <div class="text-xs text-orange-600 font-medium">
                                <i class="fas fa-tags mr-1"></i>
                                <span class="hidden sm:inline">Giảm </span>{{ number_format((($variant->base_price - $variant->current_price) / $variant->base_price) * 100, 1) }}%
                            </div>
                        @endif
                    </div>
                </td>
                <td class="hidden sm:table-cell px-2 sm:px-4 lg:px-6 py-4">
                    <div class="flex flex-col items-start gap-1 w-full">
                        <!-- Main Status - Using StatusToggle Component -->
                        <div class="w-full">
                            <x-admin.status-toggle 
                                :item-id="$variant->id"
                                :current-status="$variant->is_active" />
                        </div>
                        
                        <!-- Additional Badges - Always vertical -->
                        <div class="w-full">
                            <x-admin.status-badges :item="$variant" />
                        </div>
                    </div>
                </td>
                <td class="px-2 sm:px-4 lg:px-6 py-4 text-sm font-medium text-center">
                    <x-admin.table-actions 
                        :item="$variant"
                        show-route="admin.carvariants.show"
                        edit-route="admin.carvariants.edit"
                        delete-route="admin.carvariants.destroy"
                        :has-toggle="true" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-3 sm:px-6 py-12 text-center">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-cubes text-gray-400 text-3xl sm:text-4xl mb-4"></i>
                        <p class="text-gray-500 text-base sm:text-lg">Không tìm thấy phiên bản xe nào</p>
                        <p class="text-gray-400 text-xs sm:text-sm mt-1">Thử thay đổi bộ lọc hoặc tìm kiếm</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

{{-- Pagination --}}
@if($carVariants->hasPages())
<div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
    <x-admin.pagination :paginator="$carVariants->appends(request()->query())" />
</div>
@endif
