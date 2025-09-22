@extends('layouts.admin')
@section('title', 'Báo cáo kho xe')
@section('content')
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Báo cáo kho xe</h1>
  <div class="card mb-4">
    <div class="card-header">Giá trị kho theo hãng</div>
    <div class="card-body table-responsive">
      <table class="table table-sm">
        <thead><tr><th>Hãng</th><th>Phiên bản</th><th>Số lượng</th><th>Giá trị</th></tr></thead>
        <tbody>
          @foreach($inventoryValueByBrand as $row)
          <tr><td>{{ $row->brand }}</td><td>{{ $row->variants_count }}</td><td>{{ $row->total_quantity }}</td><td>{{ number_format($row->total_value) }} VNĐ</td></tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <div class="card mb-4">
    <div class="card-header">Hết hàng</div>
    <div class="card-body table-responsive">
      <table class="table table-sm">
        <thead><tr><th>Hãng</th><th>Dòng</th><th>Phiên bản</th></tr></thead>
        <tbody>
          @foreach($outOfStockItems as $inv)
          <tr><td>{{ $inv->carVariant->carModel->carBrand->name }}</td><td>{{ $inv->carVariant->carModel->name }}</td><td>{{ $inv->carVariant->name }}</td></tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <div class="card">
    <div class="card-header">Cảnh báo sắp hết</div>
    <div class="card-body table-responsive">
      <table class="table table-sm">
        <thead><tr><th>Hãng</th><th>Dòng</th><th>Phiên bản</th><th>Số lượng</th></tr></thead>
        <tbody>
          @foreach($lowStockAlerts as $inv)
          <tr><td>{{ $inv->carVariant->carModel->carBrand->name }}</td><td>{{ $inv->carVariant->carModel->name }}</td><td>{{ $inv->carVariant->name }}</td><td>{{ $inv->quantity }}</td></tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
