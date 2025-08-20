<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\CarVariant;
use App\Models\User;
use App\Models\TestDrive;
use App\Models\ServiceAppointment;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display the main analytics dashboard
     */
    public function dashboard()
    {
        // Get current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $lastMonth = Carbon::now()->subMonth()->month;
        $lastYear = Carbon::now()->subMonth()->year;

        // Sales Analytics
        $salesData = $this->getSalesData($currentMonth, $currentYear, $lastMonth, $lastYear);
        
        // Inventory Analytics
        $inventoryData = $this->getInventoryData();
        
        // Customer Analytics
        $customerData = $this->getCustomerData($currentMonth, $currentYear);
        
        // Performance Analytics
        $performanceData = $this->getPerformanceData($currentMonth, $currentYear);

        return view('admin.analytics.dashboard', compact(
            'salesData', 
            'inventoryData', 
            'customerData', 
            'performanceData'
        ));
    }

    /**
     * Get sales data for analytics
     */
    private function getSalesData($currentMonth, $currentYear, $lastMonth, $lastYear)
    {
        // Current month sales
        $currentMonthSales = Order::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'completed')
            ->sum('total_amount');

        // Last month sales
        $lastMonthSales = Order::whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $lastYear)
            ->where('status', 'completed')
            ->sum('total_amount');

        // Sales growth percentage
        $salesGrowth = $lastMonthSales > 0 ? 
            (($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100 : 0;

        // Monthly sales trend (last 12 months)
        $monthlySales = Order::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total_amount) as total')
            ->where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month', 'year')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Top selling products
        $topProducts = DB::table('order_items')
            ->join('car_variants', 'order_items.item_id', '=', 'car_variants.id')
            ->join('car_models', 'car_variants.car_model_id', '=', 'car_models.id')
            ->join('car_brands', 'car_models.car_brand_id', '=', 'car_brands.id')
            ->where('order_items.item_type', 'car_variant')
            ->selectRaw('car_brands.name as brand, car_models.name as model, car_variants.name as variant, COUNT(*) as sales_count')
            ->groupBy('car_brands.id', 'car_models.id', 'car_variants.id')
            ->orderBy('sales_count', 'desc')
            ->limit(10)
            ->get();

        return [
            'current_month_sales' => $currentMonthSales,
            'last_month_sales' => $lastMonthSales,
            'sales_growth' => round($salesGrowth, 2),
            'monthly_trend' => $monthlySales,
            'top_products' => $topProducts
        ];
    }

    /**
     * Get inventory data for analytics
     */
    private function getInventoryData()
    {
        // Total inventory count
        $totalInventory = Inventory::sum('quantity');
        
        // Low stock items (quantity <= 2)
        $lowStockItems = Inventory::where('quantity', '<=', 2)->count();
        
        // Out of stock items
        $outOfStockItems = Inventory::where('quantity', 0)->count();
        
        // Pre-order items
        $preOrderItems = Inventory::where('quantity', 0)
            ->where('is_available_for_preorder', 1)
            ->count();

        // Inventory value
        $inventoryValue = DB::table('inventories')
            ->join('car_variants', 'inventories.car_variant_id', '=', 'car_variants.id')
            ->selectRaw('SUM(inventories.quantity * car_variants.price) as total_value')
            ->first()
            ->total_value ?? 0;

        // Brand distribution
        $brandDistribution = DB::table('inventories')
            ->join('car_variants', 'inventories.car_variant_id', '=', 'car_variants.id')
            ->join('car_models', 'car_variants.car_model_id', '=', 'car_models.id')
            ->join('car_brands', 'car_models.car_brand_id', '=', 'car_brands.id')
            ->selectRaw('car_brands.name, SUM(inventories.quantity) as total_quantity')
            ->groupBy('car_brands.id', 'car_brands.name')
            ->orderBy('total_quantity', 'desc')
            ->get();

        return [
            'total_inventory' => $totalInventory,
            'low_stock_items' => $lowStockItems,
            'out_of_stock_items' => $outOfStockItems,
            'pre_order_items' => $preOrderItems,
            'inventory_value' => $inventoryValue,
            'brand_distribution' => $brandDistribution
        ];
    }

    /**
     * Get customer data for analytics
     */
    private function getCustomerData($currentMonth, $currentYear)
    {
        // New customers this month
        $newCustomers = User::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // Total customers
        $totalCustomers = User::count();

        // Customer growth rate
        $lastMonthCustomers = User::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();
        
        $customerGrowth = $lastMonthCustomers > 0 ? 
            (($newCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100 : 0;

        // Customer engagement (test drives, service appointments)
        $testDrives = TestDrive::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $serviceAppointments = ServiceAppointment::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // Customer segments
        $customerSegments = [
            'new' => User::where('created_at', '>=', Carbon::now()->subMonths(3))->count(),
            'returning' => User::where('created_at', '<', Carbon::now()->subMonths(3))
                ->whereHas('orders', function($q) use ($currentMonth, $currentYear) {
                    $q->whereMonth('created_at', $currentMonth)
                      ->whereYear('created_at', $currentYear);
                })->count(),
            'inactive' => User::where('created_at', '<', Carbon::now()->subMonths(6))
                ->whereDoesntHave('orders', function($q) use ($currentMonth, $currentYear) {
                    $q->where('created_at', '>=', Carbon::now()->subMonths(3));
                })->count()
        ];

        return [
            'new_customers' => $newCustomers,
            'total_customers' => $totalCustomers,
            'customer_growth' => round($customerGrowth, 2),
            'test_drives' => $testDrives,
            'service_appointments' => $serviceAppointments,
            'customer_segments' => $customerSegments
        ];
    }

    /**
     * Get performance data for analytics
     */
    private function getPerformanceData($currentMonth, $currentYear)
    {
        // Test drive conversion rate
        $totalTestDrives = TestDrive::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $convertedTestDrives = TestDrive::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('result', 'purchased')
            ->count();

        $conversionRate = $totalTestDrives > 0 ? 
            ($convertedTestDrives / $totalTestDrives) * 100 : 0;

        // Average order value
        $averageOrderValue = Order::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'completed')
            ->avg('total_amount') ?? 0;

        // Customer satisfaction (based on reviews)
        $averageRating = DB::table('reviews')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->avg('rating') ?? 0;

        // Response time (test drive requests)
        $avgResponseTime = TestDrive::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->whereNotNull('response_time')
            ->avg('response_time') ?? 0;

        return [
            'conversion_rate' => round($conversionRate, 2),
            'average_order_value' => round($averageOrderValue, 2),
            'average_rating' => round($averageRating, 1),
            'avg_response_time' => round($avgResponseTime, 1)
        ];
    }

    /**
     * Display sales report
     */
    public function salesReport(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        // Sales summary
        $salesSummary = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->selectRaw('
                COUNT(*) as total_orders,
                SUM(total_amount) as total_revenue,
                AVG(total_amount) as average_order_value,
                COUNT(DISTINCT user_id) as unique_customers
            ')
            ->first();

        // Daily sales trend
        $dailySales = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as daily_revenue, COUNT(*) as daily_orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Product performance
        $productPerformance = DB::table('order_items')
            ->join('car_variants', 'order_items.item_id', '=', 'car_variants.id')
            ->join('car_models', 'car_variants.car_model_id', '=', 'car_models.id')
            ->join('car_brands', 'car_models.car_brand_id', '=', 'car_brands.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('order_items.item_type', 'car_variant')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', 'completed')
            ->selectRaw('
                car_brands.name as brand,
                car_models.name as model,
                car_variants.name as variant,
                COUNT(*) as units_sold,
                SUM(order_items.price * order_items.quantity) as revenue
            ')
            ->groupBy('car_brands.id', 'car_models.id', 'car_variants.id')
            ->orderBy('revenue', 'desc')
            ->get();

        return view('admin.analytics.sales_report', compact(
            'salesSummary',
            'dailySales',
            'productPerformance',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display inventory report
     */
    public function inventoryReport()
    {
        // Inventory overview
        $inventoryOverview = Inventory::with(['carVariant.carModel.carBrand'])
            ->get()
            ->groupBy('carVariant.carModel.carBrand.name');

        // Low stock alerts
        $lowStockAlerts = Inventory::with(['carVariant.carModel.carBrand'])
            ->where('quantity', '<=', 2)
            ->where('quantity', '>', 0)
            ->get();

        // Out of stock items
        $outOfStockItems = Inventory::with(['carVariant.carModel.carBrand'])
            ->where('quantity', 0)
            ->get();

        // Inventory value by brand
        $inventoryValueByBrand = DB::table('inventories')
            ->join('car_variants', 'inventories.car_variant_id', '=', 'car_variants.id')
            ->join('car_models', 'car_variants.car_model_id', '=', 'car_models.id')
            ->join('car_brands', 'car_models.car_brand_id', '=', 'car_brands.id')
            ->selectRaw('
                car_brands.name as brand,
                COUNT(DISTINCT car_variants.id) as variants_count,
                SUM(inventories.quantity) as total_quantity,
                SUM(inventories.quantity * car_variants.price) as total_value
            ')
            ->groupBy('car_brands.id', 'car_brands.name')
            ->orderBy('total_value', 'desc')
            ->get();

        return view('admin.analytics.inventory_report', compact(
            'inventoryOverview',
            'lowStockAlerts',
            'outOfStockItems',
            'inventoryValueByBrand'
        ));
    }

    /**
     * Display customer analytics
     */
    public function customerAnalytics()
    {
        // Customer demographics
        $customerDemographics = User::selectRaw('
            COUNT(*) as total_customers,
            COUNT(CASE WHEN created_at >= ? THEN 1 END) as new_customers_this_month,
            COUNT(CASE WHEN created_at >= ? THEN 1 END) as new_customers_this_year
        ', [Carbon::now()->startOfMonth(), Carbon::now()->startOfYear()])
        ->first();

        // Customer lifetime value
        $customerLifetimeValue = Order::where('status', 'completed')
            ->selectRaw('
                user_id,
                COUNT(*) as total_orders,
                SUM(total_amount) as total_spent,
                AVG(total_amount) as average_order_value
            ')
            ->groupBy('user_id')
            ->orderBy('total_spent', 'desc')
            ->limit(20)
            ->get();

        // Customer engagement metrics
        $engagementMetrics = [
            'test_drives' => TestDrive::count(),
            'service_appointments' => ServiceAppointment::count(),
            'reviews' => DB::table('reviews')->count(),

        ];

        // Customer retention rate
        $retentionRate = $this->calculateRetentionRate();

        return view('admin.analytics.customer_analytics', compact(
            'customerDemographics',
            'customerLifetimeValue',
            'engagementMetrics',
            'retentionRate'
        ));
    }

    /**
     * Calculate customer retention rate
     */
    private function calculateRetentionRate()
    {
        $totalCustomers = User::count();
        $returningCustomers = User::whereHas('orders', function($q) {
            $q->where('created_at', '>=', Carbon::now()->subMonths(6));
        })->count();

        return $totalCustomers > 0 ? ($returningCustomers / $totalCustomers) * 100 : 0;
    }

    /**
     * Display staff performance
     */
    public function staffPerformance()
    {
        // Sales staff performance
        $salesStaffPerformance = Order::where('status', 'completed')
            ->selectRaw('
                sales_staff_id,
                COUNT(*) as orders_handled,
                SUM(total_amount) as total_sales,
                AVG(total_amount) as average_sale
            ')
            ->groupBy('sales_staff_id')
            ->orderBy('total_sales', 'desc')
            ->get();

        // Test drive performance
        $testDrivePerformance = TestDrive::selectRaw('
            staff_id,
            COUNT(*) as test_drives_handled,
            COUNT(CASE WHEN result = "purchased" THEN 1 END) as conversions,
            (COUNT(CASE WHEN result = "purchased" THEN 1 END) / COUNT(*)) * 100 as conversion_rate
        ')
        ->groupBy('staff_id')
        ->orderBy('conversion_rate', 'desc')
        ->get();

        return view('admin.analytics.staff_performance', compact(
            'salesStaffPerformance',
            'testDrivePerformance'
        ));
    }

    /**
     * Export analytics report
     */
    public function exportReport(Request $request)
    {
        $reportType = $request->get('type', 'sales');
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        switch ($reportType) {
            case 'sales':
                return $this->exportSalesReport($startDate, $endDate);
            case 'inventory':
                return $this->exportInventoryReport();
            case 'customers':
                return $this->exportCustomerReport();
            default:
                return back()->with('error', 'Loại báo cáo không hợp lệ');
        }
    }

    /**
     * Export sales report
     */
    private function exportSalesReport($startDate, $endDate)
    {
        $data = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->with(['user', 'items.carVariant.carModel.carBrand'])
            ->get();

        $filename = "sales_report_{$startDate->format('Y-m-d')}_{$endDate->format('Y-m-d')}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, ['Order ID', 'Customer', 'Date', 'Amount', 'Status']);
            
            // CSV data
            foreach ($data as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->user->name ?? 'N/A',
                    $order->created_at->format('Y-m-d H:i:s'),
                    number_format($order->total_amount),
                    $order->status
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export inventory report
     */
    private function exportInventoryReport()
    {
        $data = Inventory::with(['carVariant.carModel.carBrand'])->get();

        $filename = "inventory_report_" . Carbon::now()->format('Y-m-d') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, ['Brand', 'Model', 'Variant', 'Quantity', 'Status']);
            
            // CSV data
            foreach ($data as $inventory) {
                $status = $inventory->quantity > 0 ? 'In Stock' : 'Out of Stock';
                fputcsv($file, [
                    $inventory->carVariant->carModel->carBrand->name,
                    $inventory->carVariant->carModel->name,
                    $inventory->carVariant->name,
                    $inventory->quantity,
                    $status
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export customer report
     */
    private function exportCustomerReport()
    {
        $data = User::with(['orders', 'testDrives'])->get();

        $filename = "customer_report_" . Carbon::now()->format('Y-m-d') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, ['Customer ID', 'Name', 'Email', 'Orders', 'Test Drives', 'Total Spent']);
            
            // CSV data
            foreach ($data as $customer) {
                $totalSpent = $customer->orders->where('status', 'completed')->sum('total_amount');
                fputcsv($file, [
                    $customer->id,
                    $customer->name,
                    $customer->email,
                    $customer->orders->count(),
                    $customer->testDrives->count(),
                    number_format($totalSpent)
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
