@extends('layouts.admin')
@section('title', 'Báo cáo bán hàng')
@section('content')
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Báo cáo bán hàng</h1>
  <div class="row mb-3">
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Đơn hàng</div><div class="h5">{{ $salesSummary->total_orders }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Doanh thu</div><div class="h5">{{ number_format($salesSummary->total_revenue) }} VNĐ</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Giá trị TB</div><div class="h5">{{ number_format($salesSummary->average_order_value) }} VNĐ</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Khách hàng</div><div class="h5">{{ $salesSummary->unique_customers }}</div></div></div></div>
  </div>
  <div class="card mb-4">
    <div class="card-header">Xu hướng theo ngày</div>
    <div class="card-body table-responsive">
      <table class="table table-sm">
        <thead><tr><th>Ngày</th><th>Doanh thu</th><th>Đơn hàng</th></tr></thead>
        <tbody>
          @foreach($dailySales as $d)
          <tr><td>{{ $d->date }}</td><td>{{ number_format($d->daily_revenue) }}</td><td>{{ $d->daily_orders }}</td></tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <div class="card">
    <div class="card-header">Hiệu quả sản phẩm</div>
    <div class="card-body table-responsive">
      <table class="table table-sm">
        <thead><tr><th>Hãng</th><th>Dòng</th><th>Phiên bản</th><th>SL bán</th><th>Doanh thu</th></tr></thead>
        <tbody>
          @foreach($productPerformance as $p)
          <tr><td>{{ $p->brand }}</td><td>{{ $p->model }}</td><td>{{ $p->variant }}</td><td>{{ $p->units_sold }}</td><td>{{ number_format($p->revenue) }}</td></tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
