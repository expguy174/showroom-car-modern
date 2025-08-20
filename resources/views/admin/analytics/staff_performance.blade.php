@extends('admin.layouts.app')
@section('title', 'Hiệu suất nhân viên')
@section('content')
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Hiệu suất nhân viên</h1>
  <div class="card mb-4">
    <div class="card-header">Kinh doanh</div>
    <div class="card-body table-responsive">
      <table class="table table-sm">
        <thead><tr><th>Nhân viên</th><th>Đơn phụ trách</th><th>Doanh số</th><th>TB đơn</th></tr></thead>
        <tbody>
          @foreach($salesStaffPerformance as $row)
          <tr><td>#{{ $row->sales_staff_id }}</td><td>{{ $row->orders_handled }}</td><td>{{ number_format($row->total_sales) }}</td><td>{{ number_format($row->average_sale) }}</td></tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <div class="card">
    <div class="card-header">Lái thử</div>
    <div class="card-body table-responsive">
      <table class="table table-sm">
        <thead><tr><th>Nhân viên</th><th>Số lái thử</th><th>Số chuyển đổi</th><th>Tỷ lệ</th></tr></thead>
        <tbody>
          @foreach($testDrivePerformance as $row)
          <tr><td>#{{ $row->staff_id }}</td><td>{{ $row->test_drives_handled }}</td><td>{{ $row->conversions }}</td><td>{{ number_format($row->conversion_rate,1) }}%</td></tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
