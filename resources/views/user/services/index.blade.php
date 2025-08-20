@extends('layouts.app')
@section('title', 'Dịch vụ - AutoLux')
@section('content')
<section class="py-16 bg-gray-50">
  <div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-8">Dịch vụ của chúng tôi</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($services as $key => $service)
      <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center"><i class="{{ $service['icon'] }} text-gray-700"></i></div>
          <h2 class="text-xl font-semibold">{{ $service['title'] }}</h2>
        </div>
        <p class="text-gray-600 mb-4">{{ $service['description'] }}</p>
        <ul class="space-y-1 text-gray-700 mb-4 list-disc list-inside">
          @foreach($service['features'] as $f)
            <li>{{ $f }}</li>
          @endforeach
        </ul>
        <div class="text-sm text-gray-500">Khoảng giá: <span class="font-medium">{{ $service['price_range'] }}</span> • Thời gian: <span class="font-medium">{{ $service['duration'] }}</span></div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endsection
