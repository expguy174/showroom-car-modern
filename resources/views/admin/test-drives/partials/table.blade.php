<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%] whitespace-nowrap">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%] whitespace-nowrap">Xe lái thử</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%] whitespace-nowrap">Thời gian hẹn</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%] whitespace-nowrap">Showroom</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%] whitespace-nowrap">Trạng thái</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%] whitespace-nowrap">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($testDrives ?? [] as $testDrive)
                <tr class="hover:bg-gray-50" data-test-drive-id="{{ $testDrive->id }}">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            {{-- Avatar --}}
                            <div class="flex-shrink-0">
                                @if($testDrive->user && $testDrive->user->userProfile && $testDrive->user->userProfile->avatar_path)
                                    <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200" 
                                         src="{{ Storage::url($testDrive->user->userProfile->avatar_path) }}" 
                                         alt="{{ $testDrive->user->userProfile->name ?? $testDrive->user->email }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">
                                            {{ strtoupper(mb_substr($testDrive->user && $testDrive->user->userProfile ? $testDrive->user->userProfile->name : ($testDrive->user ? $testDrive->user->email : $testDrive->customer_name), 0, 2, 'UTF-8')) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            {{-- Customer Details --}}
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900 truncate">
                                    {{ $testDrive->user && $testDrive->user->userProfile ? $testDrive->user->userProfile->name : ($testDrive->user ? $testDrive->user->email : $testDrive->customer_name) }}
                                </div>
                                <div class="text-sm text-gray-500 truncate">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                    {{ $testDrive->user ? $testDrive->user->email : $testDrive->customer_email }}
                                </div>
                                @if($testDrive->user && $testDrive->user->userProfile && $testDrive->user->userProfile->phone)
                                    <div class="text-sm text-gray-500 truncate">
                                        <i class="fas fa-phone text-gray-400 mr-1"></i>
                                        {{ $testDrive->user->userProfile->phone }}
                                    </div>
                                @elseif($testDrive->customer_phone)
                                    <div class="text-sm text-gray-500 truncate">
                                        <i class="fas fa-phone text-gray-400 mr-1"></i>
                                        {{ $testDrive->customer_phone }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm">
                            <div class="font-medium text-gray-900">{{ $testDrive->carVariant->name ?? 'N/A' }}</div>
                            <div class="text-gray-700 mt-1 text-xs">
                                {{ optional($testDrive->carVariant->carModel->carBrand)->name }} 
                                {{ optional($testDrive->carVariant->carModel)->name }}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="text-sm">{{ $testDrive->preferred_date ? $testDrive->preferred_date->format('d/m/Y') : '-' }}</div>
                        <div class="text-gray-500 text-xs">{{ $testDrive->preferred_time ? substr($testDrive->preferred_time, 0, 5) : '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $testDrive->showroom->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center status-cell">
                        @switch($testDrive->status)
                            @case('scheduled')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Đã đặt lịch
                                </span>
                                @break
                            @case('confirmed')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Đã xác nhận
                                </span>
                                @break
                            @case('completed')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-flag-checkered mr-1"></i>
                                    Hoàn thành
                                </span>
                                @break
                            @case('cancelled')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Đã hủy
                                </span>
                                @break
                            @default
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-question mr-1"></i>
                                    {{ ucfirst($testDrive->status) }}
                                </span>
                        @endswitch
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium actions-cell">
                        <div class="flex items-center justify-center gap-2">
                            {{-- View Details - Luôn hiện --}}
                            <a href="{{ route('admin.test-drives.show', $testDrive) }}" 
                               class="text-blue-600 hover:text-blue-900 transition-colors p-1 rounded hover:bg-blue-50" 
                               title="Xem chi tiết">
                                <i class="fas fa-eye w-4 h-4"></i>
                            </a>
                            
                            {{-- Confirm - Chỉ scheduled --}}
                            @if($testDrive->status === 'scheduled')
                                <button type="button"
                                        class="text-green-600 hover:text-green-900 transition-colors p-1 rounded hover:bg-green-50 confirm-test-drive-btn"
                                        data-test-drive-id="{{ $testDrive->id }}"
                                        data-customer-name="{{ $testDrive->customer_name }}"
                                        title="Xác nhận">
                                    <i class="fas fa-check-circle w-4 h-4"></i>
                                </button>
                            @endif
                            
                            {{-- Complete - Chỉ confirmed --}}
                            @if($testDrive->status === 'confirmed')
                                <button type="button"
                                        class="text-purple-600 hover:text-purple-900 transition-colors p-1 rounded hover:bg-purple-50 complete-test-drive-btn"
                                        data-test-drive-id="{{ $testDrive->id }}"
                                        title="Hoàn thành">
                                    <i class="fas fa-check-double w-4 h-4"></i>
                                </button>
                            @endif
                            
                            {{-- Cancel - scheduled/confirmed --}}
                            @if(in_array($testDrive->status, ['scheduled', 'confirmed']))
                                <button type="button"
                                        class="text-orange-600 hover:text-orange-900 transition-colors p-1 rounded hover:bg-orange-50 cancel-test-drive-btn"
                                        data-test-drive-id="{{ $testDrive->id }}"
                                        data-customer-name="{{ $testDrive->customer_name }}"
                                        data-car="{{ $testDrive->car_full_name }}"
                                        data-date="{{ $testDrive->preferred_date ? $testDrive->preferred_date->format('d/m/Y') : '' }}"
                                        title="Hủy lịch hẹn">
                                    <i class="fas fa-ban w-4 h-4"></i>
                                </button>
                            @endif
                            
                            {{-- Delete - Luôn hiện --}}
                            <button type="button"
                                    class="text-red-600 hover:text-red-900 transition-colors p-1 rounded hover:bg-red-50 delete-test-drive-btn"
                                    data-test-drive-id="{{ $testDrive->id }}"
                                    data-customer-name="{{ $testDrive->customer_name }}"
                                    data-car="{{ $testDrive->car_full_name }}"
                                    data-date="{{ $testDrive->preferred_date ? $testDrive->preferred_date->format('d/m/Y') : '' }}"
                                    title="Xóa lịch lái thử">
                                <i class="fas fa-trash w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-car text-gray-300 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có lịch lái thử nào</h3>
                            <p class="text-gray-500 mb-4">Không tìm thấy lịch lái thử nào phù hợp với bộ lọc.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Admin Pagination Component --}}
    @if(isset($testDrives) && $testDrives->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        <x-admin.pagination :paginator="$testDrives" />
    </div>
    @endif
</div>
