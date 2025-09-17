@extends('layouts.app')
@section('title', 'Dịch vụ bảo dưỡng - AutoLux')
@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Dịch vụ bảo dưỡng</h1>
            <p class="text-gray-600">Chăm sóc xe của bạn với các dịch vụ bảo dưỡng chuyên nghiệp</p>
        </div>

        <!-- Recent Appointments (if user logged in) -->
        @auth
        @if($recentAppointments->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Lịch bảo dưỡng gần đây</h2>
            <div class="space-y-3">
                @foreach($recentAppointments as $appointment)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">{{ $appointment->service->name }}</div>
                        <div class="text-sm text-gray-500">
                            {{ $appointment->showroom->name }} • {{ $appointment->appointment_date->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            @if($appointment->status === 'completed') bg-green-100 text-green-800
                            @elseif($appointment->status === 'scheduled') bg-blue-100 text-blue-800
                            @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4">
                <a href="{{ route('user.service-appointments.index') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                    Xem tất cả lịch hẹn →
                </a>
            </div>
        </div>
        @endif
        @endauth

        <!-- Available Maintenance Services -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Dịch vụ bảo dưỡng có sẵn</h2>
            
            @if($maintenanceServices->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($maintenanceServices as $service)
                <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $service->name }}</h3>
                            <p class="text-gray-600 text-sm">{{ $service->description }}</p>
                        </div>
                        @if($service->is_featured)
                        <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs font-medium rounded-full">
                            Nổi bật
                        </span>
                        @endif
                    </div>

                    @if($service->requirements)
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Bao gồm:</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            @foreach(explode(', ', $service->requirements) as $requirement)
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-green-500 text-xs"></i>
                                {{ $requirement }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="flex items-center justify-between mb-4">
                        <div class="text-2xl font-bold text-indigo-600">
                            @if($service->price > 0)
                                {{ number_format($service->price, 0, ',', '.') }} đ
                            @else
                                Miễn phí
                            @endif
                        </div>
                        @if($service->duration_minutes)
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-clock mr-1"></i>
                            @if($service->duration_minutes < 60)
                                {{ $service->duration_minutes }} phút
                            @elseif($service->duration_minutes < 1440)
                                {{ round($service->duration_minutes / 60) }} giờ
                            @else
                                {{ round($service->duration_minutes / 1440) }} ngày
                            @endif
                        </div>
                        @endif
                    </div>

                    @if($service->notes)
                    <div class="text-xs text-gray-500 mb-4 p-2 bg-gray-50 rounded">
                        <i class="fas fa-info-circle mr-1"></i>
                        {{ $service->notes }}
                    </div>
                    @endif

                    <div class="flex gap-2">
                        <a href="{{ route('user.service-appointments.create', ['service_id' => $service->id]) }}" 
                           class="flex-1 bg-indigo-600 text-white text-center py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                            Đặt lịch ngay
                        </a>
                        <button class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                                onclick="showServiceDetails('{{ $service->id }}')">
                            <i class="fas fa-info"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-tools text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có dịch vụ bảo dưỡng</h3>
                <p class="text-gray-500">Hiện tại chưa có dịch vụ bảo dưỡng nào được cung cấp.</p>
            </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-plus text-blue-600 text-xl"></i>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Đặt lịch mới</h3>
                <p class="text-sm text-gray-600 mb-4">Đặt lịch bảo dưỡng cho xe của bạn</p>
                <a href="{{ route('user.service-appointments.create') }}" 
                   class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium text-sm">
                    Đặt lịch <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-history text-green-600 text-xl"></i>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Lịch sử bảo dưỡng</h3>
                <p class="text-sm text-gray-600 mb-4">Xem lại các lần bảo dưỡng trước đây</p>
                <a href="{{ route('user.service-appointments.index') }}" 
                   class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 font-medium text-sm">
                    Xem lịch sử <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-4 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-phone text-purple-600 text-xl"></i>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Hỗ trợ 24/7</h3>
                <p class="text-sm text-gray-600 mb-4">Liên hệ với chúng tôi mọi lúc</p>
                <a href="tel:1900123456" 
                   class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-700 font-medium text-sm">
                    1900 123 456 <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function showServiceDetails(serviceId) {
    // You can implement a modal or redirect to service details page
    alert('Chi tiết dịch vụ ID: ' + serviceId);
}
</script>
@endsection
