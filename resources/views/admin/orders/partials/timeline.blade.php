@php
    $actionLabels = [
        'order_created' => 'Tạo đơn hàng',
        'order_updated' => 'Cập nhật đơn hàng',
        'status_changed' => 'Chuyển trạng thái',
        'order_cancelled' => 'Huỷ đơn hàng',
        'payment_completed' => 'Thanh toán thành công',
        'payment_failed' => 'Thanh toán thất bại',
    ];
@endphp

<div class="card mt-4">
  <div class="card-header py-2">
    <h6 class="m-0 font-weight-bold text-secondary">🕒 Lịch sử đơn hàng</h6>
  </div>
  <div class="card-body">
    @if($logs->isEmpty())
      <p class="text-muted">Chưa có nhật ký.</p>
    @else
      <ul class="list-unstyled timeline">
        @foreach($logs as $log)
          @php
            $badges = [
              'order_created' => 'badge-success',
              'order_updated' => 'badge-info',
              'status_changed' => 'badge-primary',
              'order_cancelled' => 'badge-danger',
              'payment_completed' => 'badge-success',
              'payment_failed' => 'badge-warning',
            ];
          @endphp
          <li class="mb-3">
            <div>
              <span class="badge {{ $badges[$log->action] ?? 'badge-secondary' }}">{{ $actionLabels[$log->action] ?? $log->action }}</span>
              <span class="text-muted">— {{ $log->created_at->format('d/m/Y H:i') }}</span>
            </div>
            @if($log->details)
              <div class="small text-monospace bg-light p-2 rounded">{{ json_encode($log->details, JSON_UNESCAPED_UNICODE) }}</div>
            @endif
            @if($log->message)
              <div class="small">{{ $log->message }}</div>
            @endif
            @if($log->user)
              <div class="small text-muted">Bởi: {{ $log->user->name }}</div>
            @endif
          </li>
        @endforeach
      </ul>
    @endif
  </div>
</div>


