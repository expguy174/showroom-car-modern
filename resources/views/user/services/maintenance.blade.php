@extends('layouts.app')
@section('title', 'Bảo dưỡng định kỳ - AutoLux')
@section('content')
<section class="py-16 bg-gray-50">
  <div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-8">Gói bảo dưỡng</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      @foreach($maintenancePackages as $key => $pkg)
      <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-semibold mb-1">{{ $pkg['name'] }}</h2>
        <div class="text-gray-500 mb-3">{{ $pkg['description'] }}</div>
        <div class="text-2xl font-bold text-blue-600 mb-4">{{ $pkg['price'] }} VNĐ</div>
        <ul class="space-y-1 text-gray-700 mb-4 list-disc list-inside">
          @foreach($pkg['services'] as $s)
            <li>{{ $s }}</li>
          @endforeach
        </ul>
        <div class="text-sm text-gray-500">Phù hợp: {{ $pkg['suitable_for'] }} • Thời gian: {{ $pkg['duration'] }}</div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endsection
