@extends('layouts.admin')

@section('title', 'Quản lý lịch hẹn dịch vụ')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    {{-- Header --}}
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-calendar-alt text-blue-600 mr-3"></i>
                    Quản lý lịch hẹn dịch vụ
                </h1>
                <p class="text-sm text-gray-600 mt-1">Danh sách tất cả lịch hẹn dịch vụ</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.service-appointments.calendar') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="fas fa-calendar mr-2"></i>
                    Xem lịch
                </a>
                <a href="{{ route('admin.service-appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Thêm lịch hẹn
                </a>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="p-6 border-b border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-alt text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-blue-100 text-sm">Tổng lịch hẹn</p>
                        <p class="text-2xl font-semibold">{{ $totalAppointments ?? 0 }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-4 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-yellow-100 text-sm">Chờ xác nhận</p>
                        <p class="text-2xl font-semibold">{{ $pendingAppointments ?? 0 }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-green-100 text-sm">Đã xác nhận</p>
                        <p class="text-2xl font-semibold">{{ $confirmedAppointments ?? 0 }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-cog text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-purple-100 text-sm">Đang thực hiện</p>
                        <p class="text-2xl font-semibold">{{ $inProgressAppointments ?? 0 }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg p-4 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-flag-checkered text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-indigo-100 text-sm">Hoàn thành</p>
                        <p class="text-2xl font-semibold">{{ $completedAppointments ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Tìm kiếm theo tên, email, biển số xe..." 
                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Tất cả trạng thái</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Đang thực hiện</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
            </select>
            
            <select name="service_id" class="px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Tất cả dịch vụ</option>
                @foreach($services ?? [] as $service)
                    <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                        {{ $service->name }}
                    </option>
                @endforeach
            </select>
            
            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                   class="px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            
            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                   class="px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-search mr-2"></i>
                Tìm kiếm
            </button>
            
            <a href="{{ route('admin.service-appointments.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <i class="fas fa-redo mr-2"></i>
                Đặt lại
            </a>
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Xe & Dịch vụ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Showroom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chi phí</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($appointments ?? [] as $appointment)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        #{{ $appointment->id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                {{ strtoupper(substr($appointment->customer_name ?? 'N', 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $appointment->customer_name }}</div>
                                <div class="text-sm text-gray-500">{{ $appointment->customer_email }}</div>
                                <div class="text-sm text-gray-500">{{ $appointment->customer_phone }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            <div class="font-medium">{{ $appointment->vehicle_registration ?? 'N/A' }}</div>
                            <div class="text-gray-500">{{ $appointment->service->name ?? 'N/A' }}</div>
                            @if($appointment->current_mileage)
                                <div class="text-gray-500">{{ number_format($appointment->current_mileage) }} km</div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div>{{ $appointment->appointment_date ? $appointment->appointment_date->format('d/m/Y') : '-' }}</div>
                        <div class="text-gray-500">{{ $appointment->appointment_time ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $appointment->showroom->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $appointment->estimated_cost ? number_format($appointment->estimated_cost, 0, ',', '.') . 'đ' : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @switch($appointment->status)
                            @case('pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Chờ xác nhận
                                </span>
                                @break
                            @case('confirmed')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-check mr-1"></i>
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
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-flag-checkered mr-1"></i>
                                    Hoàn thành
                                </span>
                                @break
                            @case('cancelled')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1"></i>
                                    Đã hủy
                                </span>
                                @break
                            @default
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                        @endswitch
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.service-appointments.show', $appointment) }}" 
                               class="text-blue-600 hover:text-blue-900 transition-colors p-1 rounded hover:bg-blue-50" 
                               title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.service-appointments.edit', $appointment) }}" 
                               class="text-green-600 hover:text-green-900 transition-colors p-1 rounded hover:bg-green-50" 
                               title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($appointment->status == 'pending')
                                <form action="{{ route('admin.service-appointments.confirm', $appointment) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="text-blue-600 hover:text-blue-900 transition-colors p-1 rounded hover:bg-blue-50" 
                                            title="Xác nhận">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif
                            @if(in_array($appointment->status, ['pending', 'confirmed']))
                                <form action="{{ route('admin.service-appointments.cancel', $appointment) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Bạn có chắc muốn hủy lịch hẹn này?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 transition-colors p-1 rounded hover:bg-red-50" 
                                            title="Hủy">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-calendar-alt text-gray-300 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có lịch hẹn nào</h3>
                            <p class="text-gray-500 mb-4">Hệ thống chưa có lịch hẹn dịch vụ nào.</p>
                            <a href="{{ route('admin.service-appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Thêm lịch hẹn đầu tiên
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if(isset($appointments) && $appointments->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $appointments->links() }}
    </div>
    @endif
</div>
@endsection
