<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 25%;">Đại lý</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 22%;">Địa điểm</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 23%;">Liên hệ</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 15%;">Trạng thái</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 15%;">Thao tác</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($dealerships as $dealership)
            <tr class="hover:bg-gray-50" data-dealership-id="{{ $dealership->id }}">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900 truncate">{{ $dealership->name }}</div>
                    <div class="text-xs text-gray-500 truncate">{{ $dealership->code }}</div>
                    @if($dealership->description)
                    <div class="text-xs text-gray-400 truncate">{{ Str::limit($dealership->description, 50) }}</div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900 truncate"><i class="fas fa-map-marker-alt text-blue-600 mr-1"></i>{{ $dealership->city }}</div>
                    <div class="text-xs text-gray-500 truncate">{{ Str::limit($dealership->address, 35) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900 truncate"><i class="fas fa-phone text-green-600 mr-1"></i>{{ $dealership->phone }}</div>
                    @if($dealership->email)
                    <div class="text-xs text-gray-500 truncate"><i class="fas fa-envelope text-gray-400 mr-1"></i>{{ $dealership->email }}</div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    @if($dealership->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Hoạt động
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <i class="fas fa-pause-circle mr-1"></i>Tạm dừng
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                    <x-admin.table-actions
                        :item="$dealership"
                        editRoute="admin.dealerships.edit"
                        deleteRoute="admin.dealerships.destroy"
                        :hasToggle="true" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fas fa-handshake text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500 text-lg font-medium">Không tìm thấy đại lý nào</p>
                        <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc hoặc tìm kiếm khác</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    {{-- Pagination --}}
    @if($dealerships->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        <x-admin.pagination :paginator="$dealerships" />
    </div>
    @endif
</div>
