@extends('layouts.app')

@section('title', 'Thanh toán - Showroom Car')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Thanh toán</h1>
                    <p class="mt-2 text-gray-600">Quản lý giao dịch thanh toán và trả góp</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('user.payments.create') }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Tạo giao dịch mới
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
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Giao dịch thành công</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $transactions->where('status', 'completed')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Đang xử lý</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $transactions->where('status', 'pending')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-credit-card text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Trả góp đang chạy</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $installments->where('status', 'pending')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Tổng chi tiêu</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ number_format($transactions->where('status', 'completed')->sum('amount')) }} VNĐ
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-sm mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Giao dịch gần đây</h2>
                    <a href="{{ route('user.payments.transaction-history') }}" class="text-blue-600 hover:text-blue-700 text-sm">
                        Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            @if($transactions->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($transactions->take(5) as $transaction)
                    <div class="p-6 hover:bg-gray-50 transition duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-credit-card text-gray-600"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <h3 class="text-lg font-medium text-gray-900">
                                                {{ $transaction->transaction_number }}
                                            </h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                   ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($transaction->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                            <div>
                                                <span class="font-medium">Phương thức:</span> {{ $transaction->paymentMethod->name ?? 'N/A' }}
                                            </div>
                                            <div>
                                                <span class="font-medium">Loại thanh toán:</span> {{ ucfirst($transaction->payment_type) }}
                                            </div>
                                            <div>
                                                <span class="font-medium">Ngày:</span> {{ $transaction->created_at->format('d/m/Y H:i') }}
                                            </div>
                                        </div>

                                        @if($transaction->order)
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">Đơn hàng:</span> #{{ $transaction->order->id }}
                                            </p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="text-2xl font-bold text-gray-900">{{ number_format($transaction->amount) }} VNĐ</p>
                                <a href="{{ route('user.payments.show', $transaction->id) }}" 
                                   class="text-blue-600 hover:text-blue-700 text-sm">
                                    Chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có giao dịch</h3>
                    <p class="mt-1 text-sm text-gray-500">Bạn chưa có giao dịch thanh toán nào.</p>
                </div>
            @endif
        </div>

        <!-- Installment Payments -->
        <div class="bg-white rounded-lg shadow-sm mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Trả góp đang chạy</h2>
                    <a href="{{ route('user.payments.installment-history') }}" class="text-blue-600 hover:text-blue-700 text-sm">
                        Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            @if($installments->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($installments->take(5) as $installment)
                    <div class="p-6 hover:bg-gray-50 transition duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-calendar-alt text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <h3 class="text-lg font-medium text-gray-900">
                                                Kỳ trả góp #{{ $installment->installment_number }}
                                            </h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $installment->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                                   ($installment->status === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($installment->status) }}
                                            </span>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                            <div>
                                                <span class="font-medium">Ngày đến hạn:</span> {{ $installment->due_date->format('d/m/Y') }}
                                            </div>
                                            <div>
                                                <span class="font-medium">Số ngày còn lại:</span> 
                                                @if($installment->due_date->isFuture())
                                                    {{ $installment->due_date->diffInDays(now()) }} ngày
                                                @else
                                                    Quá hạn {{ $installment->due_date->diffInDays(now()) }} ngày
                                                @endif
                                            </div>
                                            <div>
                                                <span class="font-medium">Đơn hàng:</span> #{{ $installment->order->id ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="text-2xl font-bold text-gray-900">{{ number_format($installment->amount) }} VNĐ</p>
                                @if($installment->status === 'pending')
                                <button class="mt-2 bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 transition duration-200 text-sm">
                                    Thanh toán
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có trả góp</h3>
                    <p class="mt-1 text-sm text-gray-500">Bạn chưa có khoản trả góp nào.</p>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tính toán trả góp</h3>
                <p class="text-gray-600 mb-4">Tính toán khoản trả góp hàng tháng</p>
                <a href="{{ route('user.payments.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-calculator mr-2"></i>Tính toán
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Phương thức thanh toán</h3>
                <p class="text-gray-600 mb-4">Xem các phương thức thanh toán có sẵn</p>
                <a href="{{ route('user.payments.payment-methods') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-credit-card mr-2"></i>Xem phương thức
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Lịch sử giao dịch</h3>
                <p class="text-gray-600 mb-4">Xem lại tất cả giao dịch thanh toán</p>
                <a href="{{ route('user.payments.transaction-history') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-history mr-2"></i>Xem lịch sử
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
