@extends('layouts.app')
@section('title', 'Sửa chữa - AutoLux')
@section('content')
<section class="py-16 bg-gray-50">
  <div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-8">Dịch vụ sửa chữa</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      @foreach($repairServices as $key => $srv)
      <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold mb-1">{{ $srv['name'] }}</h2>
        <div class="text-gray-500 mb-3">{{ $srv['description'] }}</div>
        <ul class="space-y-1 text-gray-700 mb-4 list-disc list-inside">
          @foreach($srv['services'] as $s)
            <li>{{ $s }}</li>
          @endforeach
        </ul>
        <div class="text-sm text-gray-500">Khoảng giá: {{ $srv['price_range'] }}</div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endsection
