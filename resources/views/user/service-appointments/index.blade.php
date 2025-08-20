@extends('layouts.app')

@section('title', 'Lịch bảo dưỡng - Showroom Car')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Lịch bảo dưỡng</h1>
                    <p class="mt-2 text-gray-600">Quản lý lịch bảo dưỡng và sửa chữa xe</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('user.service-appointments.create') }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Đặt lịch mới
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Chờ xác nhận</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $appointments->where('status', 'scheduled')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Đã xác nhận</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $appointments->where('status', 'confirmed')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tools text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Đang thực hiện</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $appointments->where('status', 'in_progress')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Hoàn thành</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $appointments->where('status', 'completed')->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointments List -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Danh sách lịch bảo dưỡng</h2>
            </div>

            @if($appointments->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($appointments as $appointment)
                    <div class="p-6 hover:bg-gray-50 transition duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-car text-gray-600"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <h3 class="text-lg font-medium text-gray-900">
                                                {{ $appointment->carVariant->carModel->carBrand->name }} {{ $appointment->carVariant->carModel->name }}
                                            </h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ \App\Helpers\ServiceAppointmentHelper::statusBadgeClass($appointment->status) }}">
                                                {{ \App\Helpers\ServiceAppointmentHelper::statusLabel($appointment->status) }}
                                            </span>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                            <div>
                                                <span class="font-medium">Mã lịch:</span> {{ $appointment->appointment_number }}
                                            </div>
                                            <div>
                                                <span class="font-medium">Loại dịch vụ:</span> {{ \App\Helpers\ServiceAppointmentHelper::typeLabel($appointment->appointment_type) }}
                                            </div>
                                            <div>
                                                <span class="font-medium">Showroom:</span> {{ $appointment->showroom->name }}
                                            </div>
                                            <div>
                                                <span class="font-medium">Ngày hẹn:</span> {{ \App\Helpers\ServiceAppointmentHelper::formatDate($appointment->appointment_date) }}
                                            </div>
                                            <div>
                                                <span class="font-medium">Giờ hẹn:</span> {{ $appointment->appointment_time }}
                                            </div>
                                            <div>
                                                <span class="font-medium">Mức độ ưu tiên:</span> {{ \App\Helpers\ServiceAppointmentHelper::priorityLabel($appointment->priority) }}
                                            </div>
                                        </div>

                                        @if($appointment->service_description)
                                        <div class="mt-3">
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">Mô tả:</span> {{ Str::limit($appointment->service_description, 100) }}
                                            </p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2">
                                <a href="{{ route('user.service-appointments.show', $appointment->id) }}" 
                                   class="bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700 transition duration-200 text-sm">
                                    <i class="fas fa-eye mr-1"></i>Chi tiết
                                </a>
                                
                                @if($appointment->status === 'scheduled')
                                <a href="{{ route('user.service-appointments.edit', $appointment->id) }}" 
                                   class="bg-yellow-600 text-white px-3 py-2 rounded-md hover:bg-yellow-700 transition duration-200 text-sm">
                                    <i class="fas fa-edit mr-1"></i>Sửa
                                </a>
                                
                                <form action="{{ route('user.service-appointments.cancel', $appointment->id) }}" 
                                      method="POST" class="inline" 
                                      onsubmit="return confirm('Bạn có chắc chắn muốn hủy lịch này?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" 
                                            class="bg-red-600 text-white px-3 py-2 rounded-md hover:bg-red-700 transition duration-200 text-sm">
                                        <i class="fas fa-times mr-1"></i>Hủy
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $appointments->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có lịch bảo dưỡng</h3>
                    <p class="mt-1 text-sm text-gray-500">Bạn chưa đặt lịch bảo dưỡng nào.</p>
                    <div class="mt-6">
                        <a href="{{ route('user.service-appointments.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>Đặt lịch đầu tiên
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Lịch sử bảo dưỡng</h3>
                <p class="text-gray-600 mb-4">Xem lại tất cả các lịch bảo dưỡng đã hoàn thành</p>
                <a href="{{ route('user.service-appointments.history') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-history mr-2"></i>Xem lịch sử
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Kiểm tra lịch trống</h3>
                <p class="text-gray-600 mb-4">Kiểm tra lịch trống tại các showroom</p>
                <a href="{{ route('user.service-appointments.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-calendar-check mr-2"></i>Kiểm tra lịch
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
