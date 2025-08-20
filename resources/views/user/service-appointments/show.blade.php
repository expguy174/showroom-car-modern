@extends('layouts.app')

@section('title', 'Chi tiết lịch bảo dưỡng')

@section('content')
<div class="bg-gray-50 min-h-screen">
  <div class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Chi tiết lịch bảo dưỡng</h1>
        <p class="text-sm text-gray-600 mt-1">Mã lịch: {{ $appointment->appointment_number }}</p>
      </div>
      <a href="{{ route('user.service-appointments.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border hover:bg-gray-50 text-sm">
        <i class="fas fa-arrow-left"></i> Quay lại
      </a>
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
      <div class="bg-white rounded-xl shadow-sm border p-6">
        <h2 class="font-semibold text-gray-900 mb-4">Thông tin chung</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
          <div><span class="text-gray-600">Trạng thái:</span> <span class="font-semibold">{{ \App\Helpers\ServiceAppointmentHelper::statusLabel($appointment->status) }}</span></div>
          <div><span class="text-gray-600">Loại hẹn:</span> <span class="font-semibold">{{ \App\Helpers\ServiceAppointmentHelper::typeLabel($appointment->appointment_type) }}</span></div>
          <div><span class="text-gray-600">Ngày hẹn:</span> <span class="font-semibold">{{ \App\Helpers\ServiceAppointmentHelper::formatDate($appointment->appointment_date) }}</span></div>
          <div><span class="text-gray-600">Giờ hẹn:</span> <span class="font-semibold">{{ \App\Helpers\ServiceAppointmentHelper::formatTime($appointment->appointment_time) }}</span></div>
          <div><span class="text-gray-600">Showroom:</span> <span class="font-semibold">{{ $appointment->showroom->name }}</span></div>
          <div><span class="text-gray-600">Ưu tiên:</span> <span class="font-semibold">{{ \App\Helpers\ServiceAppointmentHelper::priorityLabel($appointment->priority) }}</span></div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border p-6">
        <h2 class="font-semibold text-gray-900 mb-4">Xe & khách hàng</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
          <div><span class="text-gray-600">Xe:</span> <span class="font-semibold">{{ $appointment->carVariant->carModel->carBrand->name }} {{ $appointment->carVariant->carModel->name }} {{ $appointment->carVariant->name }}</span></div>
          <div><span class="text-gray-600">Biển số:</span> <span class="font-semibold">{{ $appointment->vehicle_registration ?: '-' }}</span></div>
          <div><span class="text-gray-600">Khách hàng:</span> <span class="font-semibold">{{ $appointment->customer_name }}</span></div>
          <div><span class="text-gray-600">SĐT:</span> <span class="font-semibold">{{ $appointment->customer_phone }}</span></div>
          <div><span class="text-gray-600">Email:</span> <span class="font-semibold">{{ $appointment->customer_email }}</span></div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border p-6">
        <h2 class="font-semibold text-gray-900 mb-4">Nội dung yêu cầu</h2>
        <div class="prose max-w-none text-sm text-gray-800">
          <p><span class="text-gray-600">Dịch vụ yêu cầu:</span> {{ $appointment->requested_services }}</p>
          @if($appointment->service_description)
          <p><span class="text-gray-600">Mô tả:</span> {{ $appointment->service_description }}</p>
          @endif
          @if($appointment->customer_complaints)
          <p><span class="text-gray-600">Phàn nàn:</span> {{ $appointment->customer_complaints }}</p>
          @endif
          @if($appointment->special_instructions)
          <p><span class="text-gray-600">Chỉ dẫn đặc biệt:</span> {{ $appointment->special_instructions }}</p>
          @endif
        </div>
      </div>
    </div>

    <div class="space-y-6">
      <div class="bg-white rounded-xl shadow-sm border p-6">
        <h2 class="font-semibold text-gray-900 mb-4">Hành động</h2>
        @if($appointment->status === 'scheduled')
          <div class="grid grid-cols-1 gap-2">
            <a href="{{ route('user.service-appointments.edit', $appointment->id) }}" class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-yellow-600 text-white hover:bg-yellow-700"><i class="fas fa-edit"></i> Sửa</a>
            <form action="{{ route('user.service-appointments.cancel', $appointment->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn hủy lịch này?')">
              @csrf
              @method('PUT')
              <button class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700"><i class="fas fa-times"></i> Hủy lịch</button>
            </form>
          </div>
        @else
          <div class="text-sm text-gray-600">Lịch hẹn đã ở trạng thái {{ $appointment->status }}, không thể sửa/hủy.</div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection


