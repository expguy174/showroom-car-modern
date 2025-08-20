@extends('admin.layouts.app')
@section('title', 'Phân tích khách hàng')
@section('content')
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Phân tích khách hàng</h1>
  <div class="row mb-4">
    <div class="col-md-4"><div class="card"><div class="card-body"><div class="text-muted">Tổng KH</div><div class="h5">{{ $customerDemographics->total_customers }}</div></div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body"><div class="text-muted">KH mới (tháng)</div><div class="h5">{{ $customerDemographics->new_customers_this_month }}</div></div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body"><div class="text-muted">Tỷ lệ giữ chân</div><div class="h5">{{ number_format($retentionRate, 1) }}%</div></div></div></div>
  </div>
  <div class="card mb-4">
    <div class="card-header">Giá trị vòng đời (Top 20)</div>
    <div class="card-body table-responsive">
      <table class="table table-sm">
        <thead><tr><th>KH</th><th>Đơn hàng</th><th>Tổng chi</th><th>Giá trị TB</th></tr></thead>
        <tbody>
          @foreach($customerLifetimeValue as $c)
          <tr><td>#{{ $c->user_id }}</td><td>{{ $c->total_orders }}</td><td>{{ number_format($c->total_spent) }}</td><td>{{ number_format($c->average_order_value) }}</td></tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <div class="card">
    <div class="card-header">Mức độ tương tác</div>
    <div class="card-body">
      <ul>
        <li>Lái thử: {{ $engagementMetrics['test_drives'] }}</li>
        <li>Lịch bảo dưỡng: {{ $engagementMetrics['service_appointments'] }}</li>
        <li>Đánh giá: {{ $engagementMetrics['reviews'] }}</li>
        
      </ul>
    </div>
  </div>
</div>
@endsection
