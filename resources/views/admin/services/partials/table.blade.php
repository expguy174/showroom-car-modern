<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 20%;">Dịch vụ</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 20%;">Danh mục</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 15%;">Giá</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 15%;">Thời gian</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 15%;">Trạng thái</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 15%;">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($services ?? [] as $service)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div>
                        <div class="text-sm font-medium text-gray-900">{{ $service->name }}</div>
                        @if($service->code)
                        <div class="mt-1">
                            <span class="text-xs text-gray-500 font-mono bg-gray-100 px-2 py-0.5 rounded">{{ $service->code }}</span>
                        </div>
                        @endif
                    </div>
                </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @switch($service->category)
                            @case('maintenance')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-wrench mr-1"></i>Bảo dưỡng
                                </span>
                                @break
                            @case('repair')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-tools mr-1"></i>Sửa chữa
                                </span>
                                @break
                            @case('diagnostic')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-search mr-1"></i>Chẩn đoán
                                </span>
                                @break
                            @case('cosmetic')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                    <i class="fas fa-paint-brush mr-1"></i>Làm đẹp
                                </span>
                                @break
                            @case('emergency')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Khẩn cấp
                                </span>
                                @break
                            @default
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst($service->category) }}
                                </span>
                        @endswitch
                    </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="text-sm">
                        @if($service->price)
                            <div class="text-gray-900 font-medium">{{ number_format($service->price, 0, ',', '.') }}đ</div>
                        @else
                            <span class="text-gray-500">Liên hệ</span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="text-sm">
                        @if($service->duration_minutes)
                            <div class="text-gray-900">{{ $service->duration_minutes }} phút</div>
                        @else
                            <span class="text-gray-500">-</span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="flex flex-col items-center gap-1">
                        <x-admin.status-toggle 
                            :item-id="$service->id"
                            :current-status="$service->is_active"
                            entity-type="service" />
                        @if($service->is_featured)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-star mr-1"></i>Nổi bật
                        </span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <x-admin.table-actions 
                        :item="$service"
                        show-route="admin.services.show"
                        edit-route="admin.services.edit"
                        delete-route="admin.services.destroy"
                        :has-toggle="true" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fas fa-cogs text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-500 text-lg font-medium">Không có dịch vụ nào</p>
                        <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc để xem kết quả khác</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    {{-- Pagination --}}
    @if($services->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        <x-admin.pagination :paginator="$services" />
    </div>
    @endif
</div>
