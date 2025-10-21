<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%] whitespace-nowrap">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%] whitespace-nowrap">Xe & Dịch vụ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[10%] whitespace-nowrap">Thời gian hẹn</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[10%] whitespace-nowrap">Showroom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[10%] whitespace-nowrap">Số tiền</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%] whitespace-nowrap">Trạng thái</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%] whitespace-nowrap">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($appointments ?? [] as $appointment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm">
                            <div class="font-medium text-gray-900">{{ $appointment->user->userProfile->name ?? 'N/A' }}</div>
                            <div class="text-gray-500 mt-1 text-xs">
                                <i class="fas fa-envelope text-xs mr-1"></i>{{ $appointment->user->email ?? '-' }}
                            </div>
                            @if($appointment->appointment_number)
                            <div class="text-gray-400 text-xs mt-1">
                                <i class="fas fa-hashtag text-xs"></i>{{ $appointment->appointment_number }}
                            </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm">
                            @if($appointment->vehicle_registration)
                            <div class="font-medium text-gray-900">
                                <i class="fas fa-car text-xs mr-1"></i>{{ $appointment->vehicle_registration }}
                            </div>
                            @endif
                            <div class="text-gray-700 mt-1 text-xs">{{ $appointment->service->name ?? 'N/A' }}</div>
                            @if($appointment->current_mileage)
                                <div class="text-gray-500 text-xs mt-1">
                                    <i class="fas fa-tachometer-alt text-xs mr-1"></i>{{ number_format($appointment->current_mileage) }} km
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="text-sm">{{ $appointment->appointment_date ? $appointment->appointment_date->format('d/m/Y') : '-' }}</div>
                        <div class="text-gray-500 text-xs">{{ $appointment->appointment_time ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $appointment->showroom->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $appointment->estimated_cost ? number_format($appointment->estimated_cost, 0, ',', '.') . 'đ' : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @switch($appointment->status)
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
                            @case('in_progress')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-cog mr-1"></i>
                                    Đang thực hiện
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
                                    {{ ucfirst($appointment->status) }}
                                </span>
                        @endswitch
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center justify-center gap-2">
                            {{-- View Details - Luôn hiện --}}
                            <a href="{{ route('admin.service-appointments.show', $appointment) }}" 
                               class="text-blue-600 hover:text-blue-900 transition-colors p-1 rounded hover:bg-blue-50" 
                               title="Xem chi tiết">
                                <i class="fas fa-eye w-4 h-4"></i>
                            </a>
                            
                            {{-- Confirm - Chỉ scheduled/rescheduled --}}
                            @if(in_array($appointment->status, ['scheduled', 'rescheduled']))
                                <button type="button"
                                        class="text-green-600 hover:text-green-900 transition-colors p-1 rounded hover:bg-green-50 confirm-btn"
                                        data-appointment-id="{{ $appointment->id }}"
                                        data-customer-name="{{ $appointment->user->userProfile->name ?? 'Khách hàng' }}"
                                        title="Xác nhận lịch hẹn">
                                    <i class="fas fa-check-circle w-4 h-4"></i>
                                </button>
                            @endif
                            
                            {{-- Start Service - Chỉ confirmed --}}
                            @if($appointment->status == 'confirmed')
                                <button type="button"
                                        class="text-purple-600 hover:text-purple-900 transition-colors p-1 rounded hover:bg-purple-50 start-service-btn"
                                        data-appointment-id="{{ $appointment->id }}"
                                        title="Bắt đầu thực hiện">
                                    <i class="fas fa-play-circle w-4 h-4"></i>
                                </button>
                            @endif
                            
                            {{-- Complete - Chỉ in_progress --}}
                            @if($appointment->status == 'in_progress')
                                <button type="button"
                                        class="text-green-600 hover:text-green-900 transition-colors p-1 rounded hover:bg-green-50 complete-service-btn"
                                        data-appointment-id="{{ $appointment->id }}"
                                        title="Hoàn thành">
                                    <i class="fas fa-check-double w-4 h-4"></i>
                                </button>
                            @endif
                            
                            {{-- Cancel - scheduled/confirmed --}}
                            @if(in_array($appointment->status, ['scheduled', 'confirmed']))
                                <button type="button"
                                        class="text-orange-600 hover:text-orange-900 transition-colors p-1 rounded hover:bg-orange-50 cancel-btn"
                                        data-appointment-id="{{ $appointment->id }}"
                                        data-customer-name="{{ $appointment->user->userProfile->name ?? 'Khách hàng' }}"
                                        data-service="{{ $appointment->service->name ?? 'N/A' }}"
                                        data-date="{{ $appointment->appointment_date ? $appointment->appointment_date->format('d/m/Y') : '' }}"
                                        title="Hủy lịch hẹn">
                                    <i class="fas fa-ban w-4 h-4"></i>
                                </button>
                            @endif
                            
                            {{-- Delete - Luôn hiện --}}
                            <button type="button"
                                    class="text-red-600 hover:text-red-900 transition-colors p-1 rounded hover:bg-red-50 delete-btn"
                                    data-appointment-id="{{ $appointment->id }}"
                                    data-customer-name="{{ $appointment->user->userProfile->name ?? 'Khách hàng' }}"
                                    data-service="{{ $appointment->service->name ?? 'N/A' }}"
                                    data-date="{{ $appointment->appointment_date ? $appointment->appointment_date->format('d/m/Y') : '' }}"
                                    title="Xóa lịch hẹn">
                                <i class="fas fa-trash w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-calendar-alt text-gray-300 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có lịch hẹn nào</h3>
                            <p class="text-gray-500 mb-4">Không tìm thấy lịch hẹn nào phù hợp với bộ lọc.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Admin Pagination Component --}}
    @if(isset($appointments) && $appointments->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        <x-admin.pagination :paginator="$appointments" />
    </div>
    @endif
</div>
