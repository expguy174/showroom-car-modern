@extends('layouts.app')
@section('title', 'Chi tiết lịch lái thử')
@section('content')
<div class="max-w-4xl mx-auto p-6">
  <h1 class="text-2xl font-bold mb-4">Chi tiết lịch lái thử</h1>
  <div class="bg-white shadow rounded-xl p-4">
    <div class="text-sm text-gray-600 mb-2">Mã: {{ $testDrive->test_drive_number ?? $testDrive->id }}</div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-gray-800">
      <div>Xe: {{ optional(optional($testDrive->carVariant)->carModel)->name }} {{ $testDrive->carVariant->name ?? '' }}</div>
      <div>Ngày: {{ optional($testDrive->preferred_date)->format('d/m/Y') }}</div>
      <div>Giờ: {{ is_string($testDrive->preferred_time) ? $testDrive->preferred_time : optional($testDrive->preferred_time)->format('H:i') }}</div>
      <div>Trạng thái: {{ $testDrive->status ?? '-' }}</div>
    </div>
  </div>
  <div class="mt-6">
    <a href="{{ route('test-drives.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Quay lại danh sách</a>
  </div>
</div>
@endsection
