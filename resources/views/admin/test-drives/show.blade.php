@extends('layouts.admin')

@section('title', 'Chi tiết lịch lái thử')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-car text-blue-600 mr-3"></i>
                Chi tiết lịch lái thử #{{ $testDrive->id }}
            </h1>
            <p class="text-gray-600 mt-1">Thông tin chi tiết về lịch hẹn lái thử</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.test-drives.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại
            </a>
        </div>
    </div>

    {{-- Test Drive Details --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Thông tin lái thử</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Thông tin khách hàng</h3>
                    <div class="space-y-2">
                        <p><span class="font-medium">Họ tên:</span> {{ $testDrive->name }}</p>
                        <p><span class="font-medium">Email:</span> {{ $testDrive->email }}</p>
                        <p><span class="font-medium">Số điện thoại:</span> {{ $testDrive->phone }}</p>
                        @if($testDrive->user)
                            <p><span class="font-medium">Tài khoản:</span> 
                                <span class="text-blue-600">{{ $testDrive->user->email }}</span>
                            </p>
                        @endif
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Thông tin xe</h3>
                    <div class="space-y-2">
                        @if($testDrive->carVariant)
                            <p><span class="font-medium">Xe:</span> {{ $testDrive->carVariant->carModel->carBrand->name }} {{ $testDrive->carVariant->carModel->name }} {{ $testDrive->carVariant->name }}</p>
                            <p><span class="font-medium">Giá:</span> {{ number_format($testDrive->carVariant->price) }}đ</p>
                        @endif
                        <p><span class="font-medium">Ngày hẹn:</span> {{ $testDrive->preferred_date ? \Carbon\Carbon::parse($testDrive->preferred_date)->format('d/m/Y H:i') : 'Chưa xác định' }}</p>
                    </div>
                </div>
            </div>

            @if($testDrive->notes)
            <div class="mt-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Ghi chú</h3>
                <p class="text-gray-700">{{ $testDrive->notes }}</p>
            </div>
            @endif

            <div class="mt-6 flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-gray-500">Trạng thái:</span>
                    @php
                        $statusConfig = [
                            'pending' => ['label' => 'Chờ xử lý', 'color' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fas fa-clock'],
                            'confirmed' => ['label' => 'Đã xác nhận', 'color' => 'bg-blue-100 text-blue-800', 'icon' => 'fas fa-check'],
                            'completed' => ['label' => 'Hoàn thành', 'color' => 'bg-green-100 text-green-800', 'icon' => 'fas fa-check-circle'],
                            'cancelled' => ['label' => 'Đã hủy', 'color' => 'bg-red-100 text-red-800', 'icon' => 'fas fa-times-circle']
                        ];
                        $config = $statusConfig[$testDrive->status] ?? $statusConfig['pending'];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $config['color'] }} ml-2">
                        <i class="{{ $config['icon'] }} mr-1"></i>
                        {{ $config['label'] }}
                    </span>
                </div>
                <div class="text-sm text-gray-500">
                    Tạo lúc: {{ $testDrive->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Status Update Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Cập nhật trạng thái</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.test-drives.update_status', $testDrive) }}" method="POST" class="flex items-center space-x-4">
                @csrf
                @method('PUT')
                <select name="status" class="block px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="pending" {{ $testDrive->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="confirmed" {{ $testDrive->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="completed" {{ $testDrive->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="cancelled" {{ $testDrive->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Cập nhật
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
