@extends('layouts.app')
@section('title', 'Bảo hiểm - AutoLux')
@section('content')
<section class="py-16 bg-gray-50">
  <div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-8">Gói bảo hiểm</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      @foreach($insurancePackages as $key => $pkg)
      <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-semibold mb-1">{{ $pkg['name'] }}</h2>
        <div class="text-gray-500 mb-3">{{ $pkg['description'] }}</div>
        <div class="text-2xl font-bold text-blue-600 mb-4">{{ $pkg['price'] }} VNĐ</div>
        <div class="mb-2 font-medium">Phạm vi bảo hiểm</div>
        <ul class="mb-4 space-y-1 text-gray-700 list-disc list-inside">
          @foreach($pkg['coverage'] as $c)
            <li>{{ $c }}</li>
          @endforeach
        </ul>
        <div class="mb-2 font-medium">Quyền lợi</div>
        <ul class="space-y-1 text-gray-700 list-disc list-inside">
          @foreach($pkg['benefits'] as $b)
            <li>{{ $b }}</li>
          @endforeach
        </ul>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endsection
