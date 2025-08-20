@extends('admin.layouts.app')

@section('title', 'Nhật ký đơn hàng')

@section('content')
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">📝 Nhật ký đơn #{{ $order->order_number ?? $order->id }}</h6>
  </div>
  <div class="card-body">
    <form method="GET" class="form-inline mb-3">
      <div class="form-group mr-2">
        <label class="mr-2">Action</label>
        <select name="action" class="form-control form-control-sm">
          <option value="">-- Tất cả --</option>
          @foreach(['order_created','order_updated','status_changed','order_cancelled','payment_completed','payment_failed'] as $a)
            <option value="{{ $a }}" {{ request('action')===$a?'selected':'' }}>{{ $a }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group mr-2">
        <label class="mr-2">User</label>
        <input type="text" name="user" value="{{ request('user') }}" placeholder="ID hoặc tên" class="form-control form-control-sm" />
      </div>
      <div class="form-group mr-2">
        <label class="mr-2">Từ ngày</label>
        <input type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm" />
      </div>
      <div class="form-group mr-2">
        <label class="mr-2">Đến ngày</label>
        <input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm" />
      </div>
      <button class="btn btn-sm btn-secondary mr-2">Lọc</button>
      <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.orders.logs.export', $order->id) }}?{{ http_build_query(request()->all()) }}">Export CSV</a>
    </form>

    <div class="table-responsive">
      <table class="table table-bordered table-sm">
        <thead class="thead-light">
          <tr>
            <th>Thời gian</th>
            <th>Action</th>
            <th>User</th>
            <th>Details</th>
            <th>IP</th>
          </tr>
        </thead>
        <tbody>
          @foreach($logs as $log)
            <tr>
              <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
              <td>{{ $log->action }}</td>
              <td>{{ $log->user->name ?? '-' }}</td>
              <td class="text-monospace">{{ json_encode($log->details, JSON_UNESCAPED_UNICODE) }}</td>
              <td>{{ $log->ip_address }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{ $logs->withQueryString()->links() }}
  </div>
</div>
@endsection


