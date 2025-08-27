@extends('admin.layouts.app')

@section('title', 'Analytics Dashboard - Admin Panel')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Analytics Dashboard</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.analytics.sales-report') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-chart-line mr-1"></i> Sales Report
            </a>
            <a href="{{ route('admin.analytics.inventory-report') }}" class="btn btn-success btn-sm">
                <i class="fas fa-boxes mr-1"></i> Inventory Report
            </a>
            <a href="{{ route('admin.analytics.export-report') }}" class="btn btn-info btn-sm">
                <i class="fas fa-download mr-1"></i> Export
            </a>
        </div>
    </div>

    <!-- Sales Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Doanh thu tháng này
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($salesData['current_month_sales']) }} VNĐ
                            </div>
                            @if($salesData['sales_growth'] > 0)
                            <div class="text-success text-xs">
                                <i class="fas fa-arrow-up"></i> +{{ $salesData['sales_growth'] }}%
                            </div>
                            @else
                            <div class="text-danger text-xs">
                                <i class="fas fa-arrow-down"></i> {{ $salesData['sales_growth'] }}%
                            </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Khách hàng mới
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $customerData['new_customers'] }}
                            </div>
                            @if($customerData['customer_growth'] > 0)
                            <div class="text-success text-xs">
                                <i class="fas fa-arrow-up"></i> +{{ $customerData['customer_growth'] }}%
                            </div>
                            @else
                            <div class="text-danger text-xs">
                                <i class="fas fa-arrow-down"></i> {{ $customerData['customer_growth'] }}%
                            </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Tỷ lệ chuyển đổi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $performanceData['conversion_rate'] }}%
                            </div>
                            <div class="text-xs text-gray-600">
                                Lái thử → Mua xe
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-2 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Tổng kho xe
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $inventoryData['total_inventory'] }}
                            </div>
                            <div class="text-xs text-gray-600">
                                {{ $inventoryData['in_stock'] }} có sẵn
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-car fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Sales Trend Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Xu hướng doanh thu (12 tháng gần nhất)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="salesTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Status Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Trạng thái kho xe</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="inventoryStatusChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Có sẵn
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Đặt trước
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-warning"></i> Sắp về
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products and Brand Distribution -->
    <div class="row mb-4">
        <!-- Top Selling Products -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sản phẩm bán chạy nhất</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Hãng xe</th>
                                    <th>Dòng xe</th>
                                    <th>Phiên bản</th>
                                    <th>Số lượng bán</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salesData['top_products']->take(5) as $product)
                                <tr>
                                    <td>{{ $product->brand }}</td>
                                    <td>{{ $product->model }}</td>
                                    <td>{{ $product->variant }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-primary">{{ $product->sales_count }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Brand Distribution -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Phân bố theo hãng xe</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Hãng xe</th>
                                    <th>Số lượng</th>
                                    <th>Tỷ lệ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inventoryData['brand_distribution']->take(5) as $brand)
                                <tr>
                                    <td>{{ $brand->name }}</td>
                                    <td class="text-center">{{ $brand->total_quantity }}</td>
                                    <td>
                                        @php
                                        $percentage = $inventoryData['total_inventory'] > 0 ?
                                        round(($brand->total_quantity / $inventoryData['total_inventory']) * 100, 1) : 0;
                                        @endphp
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" data-width="{{ $percentage }}" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">{{ $percentage }}%</div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Segments and Performance Metrics -->
    <div class="row mb-4">
        <!-- Customer Segments -->
        <div class="col-xl-4 col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Phân khúc khách hàng</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Khách hàng mới</span>
                            <span class="font-weight-bold">{{ $customerData['customer_segments']['new'] }}</span>
                        </div>
                        <div class="progress mb-2" style="height: 8px;"><div class="progress-bar bg-success" data-width="{{ $customerData['customer_segments']['new'] > 0 ? ($customerData['customer_segments']['new'] / $customerData['total_customers']) * 100 : 0 }}"></div></div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Khách hàng quay lại</span>
                            <span class="font-weight-bold">{{ $customerData['customer_segments']['returning'] }}</span>
                        </div>
                        <div class="progress mb-2" style="height: 8px;"><div class="progress-bar bg-primary" data-width="{{ $customerData['customer_segments']['returning'] > 0 ? ($customerData['customer_segments']['returning'] / $customerData['total_customers']) * 100 : 0 }}"></div></div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Khách hàng không hoạt động</span>
                            <span class="font-weight-bold">{{ $customerData['customer_segments']['inactive'] }}</span>
                        </div>
                        <div class="progress mb-2" style="height: 8px;"><div class="progress-bar bg-warning" data-width="{{ $customerData['customer_segments']['inactive'] > 0 ? ($customerData['customer_segments']['inactive'] / $customerData['total_customers']) * 100 : 0 }}"></div></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="col-xl-4 col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Chỉ số hiệu suất</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Giá trị đơn hàng TB</span>
                            <span class="font-weight-bold">{{ number_format($performanceData['average_order_value']) }} VNĐ</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Đánh giá TB</span>
                            <span class="font-weight-bold">{{ isset($performanceData['average_rating']) ? $performanceData['average_rating'] : 'N/A' }}/5</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Thời gian phản hồi TB</span>
                            <span class="font-weight-bold">{{ $performanceData['avg_response_time'] }} giờ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-xl-4 col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thao tác nhanh</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.analytics.sales-report') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-chart-line mr-1"></i> Báo cáo bán hàng
                        </a>
                        <a href="{{ route('admin.analytics.inventory-report') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-boxes mr-1"></i> Báo cáo kho xe
                        </a>
                        <a href="{{ route('admin.analytics.customer-analytics') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-users mr-1"></i> Phân tích khách hàng
                        </a>
                        <a href="{{ route('admin.analytics.staff-performance') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-user-tie mr-1"></i> Hiệu suất nhân viên
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts and Notifications -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cảnh báo và thông báo</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($inventoryData['low_stock_items'] > 0)
                        <div class="col-md-6 mb-3">
                            <div class="alert alert-warning" role="alert">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Cảnh báo:</strong> {{ $inventoryData['low_stock_items'] }} xe có số lượng thấp (≤2)
                            </div>
                        </div>
                        @endif

                        @if($inventoryData['out_of_stock_items'] > 0)
                        <div class="col-md-6 mb-3">
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-times-circle mr-2"></i>
                                <strong>Hết hàng:</strong> {{ $inventoryData['out_of_stock_items'] }} xe đã hết hàng
                            </div>
                        </div>
                        @endif

                        @if($performanceData['conversion_rate'] < 20)
                            <div class="col-md-6 mb-3">
                            <div class="alert alert-info" role="alert">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Lưu ý:</strong> Tỷ lệ chuyển đổi lái thử thấp ({{ $performanceData['conversion_rate'] }}%)
                            </div>
                    </div>
                    @endif

                    @if($customerData['customer_growth'] < 0)
                        <div class="col-md-6 mb-3">
                        <div class="alert alert-secondary" role="alert">
                            <i class="fas fa-chart-line mr-2"></i>
                            <strong>Phân tích:</strong> Số lượng khách hàng mới giảm so với tháng trước
                        </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection

@push('scripts')
{{-- Charts JS moved to external asset to avoid inline parsing issues --}}
@endpush