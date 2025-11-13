@extends('emails.layout')

@section('content')
@if($isLastInstallment)
    <h2>🎉 Chúc mừng! Hoàn thành trả góp</h2>
    
    <p>Xin chào {{ $installment->user->userProfile->name ?? 'Quý khách' }},</p>
    
    <p>Chúc mừng bạn đã hoàn thành <strong>tất cả {{ $installment->order->tenure_months }} kỳ</strong> trả góp!</p>
    
    <div class="info-box">
        <p><strong>Đơn hàng:</strong> #{{ $installment->order->order_number }}</p>
        <p><strong>Kỳ cuối:</strong> Kỳ {{ $installment->installment_number }}/{{ $installment->order->tenure_months }}</p>
        <p><strong>Số tiền:</strong> {{ number_format($installment->amount) }} VNĐ</p>
        <p><strong>Ngày thanh toán:</strong> {{ $installment->paid_at->format('d/m/Y H:i') }}</p>
    </div>
    
    <p>🎊 Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ trả góp của chúng tôi!</p>
    
@else
    <h2>Xác nhận thanh toán kỳ trả góp</h2>
    
    <p>Xin chào {{ $installment->user->userProfile->name ?? 'Quý khách' }},</p>
    
    <p>Chúng tôi đã nhận được thanh toán cho <strong>kỳ {{ $installment->installment_number }}</strong> của đơn hàng <strong>#{{ $installment->order->order_number }}</strong>.</p>
    
    <div class="info-box">
        <p><strong>Kỳ:</strong> {{ $installment->installment_number }}/{{ $installment->order->tenure_months }}</p>
        <p><strong>Số tiền:</strong> {{ number_format($installment->amount) }} VNĐ</p>
        <p><strong>Ngày thanh toán:</strong> {{ $installment->paid_at->format('d/m/Y H:i') }}</p>
        @php
            $remainingInstallments = $installment->order->installments()
                ->whereIn('status', ['pending', 'overdue'])
                ->count();
        @endphp
        <p><strong>Còn lại:</strong> {{ $remainingInstallments }} kỳ</p>
    </div>
    
    <p>✅ Cảm ơn bạn đã thanh toán đúng hạn!</p>
@endif

@php
    // Create signed URL that works without requiring login check
    $orderUrl = \Illuminate\Support\Facades\URL::signedRoute(
        'user.orders.show',
        ['order' => $installment->order_id],
        now()->addDays(30) // Valid for 30 days
    );
@endphp
<a href="{{ $orderUrl }}" class="button">Xem lịch trả góp</a>

<p>Trân trọng,<br>{{ config('app.name') }}</p>
@endsection
