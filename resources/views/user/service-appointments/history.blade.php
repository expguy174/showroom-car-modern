@extends('layouts.app')

@section('title', 'Lịch sử bảo dưỡng')

@section('content')
<div class="bg-gray-50 min-h-screen">
  <div class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold text-gray-900">Lịch sử bảo dưỡng</h1>
      <a href="{{ route('user.service-appointments.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border hover:bg-gray-50 text-sm"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if($appointments->count())
      <div class="bg-white rounded-xl border shadow-sm divide-y">
        @foreach($appointments as $a)
          <div class="p-6">
            <div class="flex items-center justify-between">
              <div>
                <div class="text-sm text-gray-600">Mã lịch: {{ $a->appointment_number }}</div>
                <div class="font-semibold text-gray-900">{{ $a->carVariant->carModel->carBrand->name }} {{ $a->carVariant->carModel->name }} {{ $a->carVariant->name }}</div>
                <div class="text-sm text-gray-600">Ngày: {{ optional($a->appointment_date)->format('d/m/Y') }} • Giờ: {{ $a->appointment_time }} • Showroom: {{ $a->showroom->name }}</div>
              </div>
              <a href="{{ route('user.service-appointments.show', $a->id) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm"><i class="fas fa-eye"></i> Xem</a>
            </div>
            @if($a->service_description)
              <div class="mt-2 text-sm text-gray-700">{{ Str::limit($a->service_description, 160) }}</div>
            @endif
          </div>
        @endforeach
      </div>
      <div class="mt-6">{{ $appointments->links() }}</div>
    @else
      <div class="text-center py-16">
        <i class="fas fa-history text-3xl text-gray-400"></i>
        <div class="mt-2 text-gray-700">Chưa có lịch bảo dưỡng hoàn thành</div>
      </div>
    @endif
  </div>
</div>
@endsection


