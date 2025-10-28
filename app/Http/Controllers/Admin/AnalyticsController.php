<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\CarVariant;
use App\Models\User;
use App\Models\TestDrive;
use App\Models\ServiceAppointment;
// use App\Models\Inventory; // module inventory đã gỡ bỏ
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
        
        // Inventory Analytics removed
        $inventoryData = [
            'total_inventory' => 0,
            'low_stock_items' => 0,
            'out_of_stock_items' => 0,
            'pre_order_items' => 0,
            'inventory_value' => 0,
            'brand_distribution' => collect(),
        ];
        
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
            ->where('status', 'delivered')
            ->sum('grand_total');

        // Last month sales
        $lastMonthSales = Order::whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $lastYear)
            ->where('status', 'delivered')
            ->sum('grand_total');

        // Sales growth percentage
        $salesGrowth = $lastMonthSales > 0 ? 
            (($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100 : 0;

        // Monthly sales trend (last 12 months)
        $monthlySales = Order::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(grand_total) as total')
            ->where('status', 'delivered')
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
            ->groupBy('car_brands.id', 'car_models.id', 'car_variants.id', 'car_brands.name', 'car_models.name', 'car_variants.name')
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
    // getInventoryData removed with inventory module

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
            ->where('status', 'completed')
            ->count();

        $conversionRate = $totalTestDrives > 0 ? 
            ($convertedTestDrives / $totalTestDrives) * 100 : 0;

        // Average order value
        $averageOrderValue = Order::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'delivered')
            ->avg('grand_total') ?? 0;

        // Customer satisfaction (based on reviews)
        $averageRating = DB::table('reviews')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('is_approved', true)
            ->avg('rating') ?? 0;

        // Response time (test drive requests) - calculated from created_at to confirmed_at
        $avgResponseTime = TestDrive::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->whereNotNull('confirmed_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, confirmed_at)) as avg_hours')
            ->value('avg_hours') ?? 0;

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
            ->where('status', 'delivered')
            ->selectRaw('
                COUNT(*) as total_orders,
                SUM(grand_total) as total_revenue,
                AVG(grand_total) as average_order_value,
                COUNT(DISTINCT user_id) as unique_customers
            ')
            ->first();

        // Daily sales trend
        $dailySales = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'delivered')
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as daily_revenue, COUNT(*) as daily_orders')
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
            ->where('orders.status', 'delivered')
            ->selectRaw('
                car_brands.name as brand,
                car_models.name as model,
                car_variants.name as variant,
                COUNT(*) as units_sold,
                SUM(order_items.price * order_items.quantity) as revenue
            ')
            ->groupBy('car_brands.id', 'car_models.id', 'car_variants.id', 'car_brands.name', 'car_models.name', 'car_variants.name')
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
    // inventoryReport removed with inventory module

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
        $customerLifetimeValue = Order::where('status', 'delivered')
            ->with('user.userProfile')
            ->selectRaw('
                user_id,
                COUNT(*) as total_orders,
                SUM(grand_total) as total_spent,
                AVG(grand_total) as average_order_value
            ')
            ->groupBy('user_id')
            ->orderBy('total_spent', 'desc')
            ->limit(20)
            ->get();

        // Customer engagement metrics
        $engagementMetrics = [
            'test_drives' => TestDrive::count(),
            'service_appointments' => ServiceAppointment::count(),
            'reviews' => DB::table('reviews')->where('is_approved', true)->count(),
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
        // Get all staff (exclude customers with role = 'user')
        $allStaff = User::whereIn('role', ['admin', 'sales_person', 'technician', 'manager'])
            ->where('is_active', true)
            ->with('userProfile')
            ->get();

        // Calculate performance metrics from order_logs
        $staffPerformance = $allStaff->map(function($staff) {
            // Get distinct order IDs that this staff worked on
            $orderIds = DB::table('order_logs')
                ->where('user_id', $staff->id)
                ->distinct()
                ->pluck('order_id');

            $ordersHandled = $orderIds->count();

            // Total revenue from orders this staff handled (delivered only)
            $totalRevenue = Order::whereIn('id', $orderIds)
                ->where('status', 'delivered')
                ->sum('grand_total');

            // Completed orders count
            $completedOrders = Order::whereIn('id', $orderIds)
                ->where('status', 'delivered')
                ->count();

            // Activity breakdown
            $activities = DB::table('order_logs')
                ->where('user_id', $staff->id)
                ->select('action', DB::raw('COUNT(*) as count'))
                ->groupBy('action')
                ->get()
                ->pluck('count', 'action')
                ->toArray();

            return (object)[
                'id' => $staff->id,
                'employee_id' => $staff->employee_id ?? 'N/A',
                'name' => $staff->userProfile->name ?? $staff->email,
                'email' => $staff->email,
                'role' => $staff->role,
                'role_display' => $this->getRoleName($staff->role),
                'department' => $staff->department ?? $this->getDefaultDepartment($staff->role),
                'position' => $staff->position ?? $this->getDefaultPosition($staff->role),
                'orders_handled' => $ordersHandled,
                'total_revenue' => $totalRevenue,
                'completed_orders' => $completedOrders,
                'completion_rate' => $ordersHandled > 0 ? round(($completedOrders / $ordersHandled) * 100, 1) : 0,
                'activities' => $activities,
                'last_login_at' => $staff->last_login_at,
                'is_active' => $staff->is_active,
            ];
        })->sortByDesc('orders_handled');

        // Group by role
        $staffByRole = $staffPerformance->groupBy('role')->map(function($staffGroup, $role) {
            return [
                'role_name' => $this->getRoleName($role),
                'count' => $staffGroup->count(),
                'staff' => $staffGroup->values(),
                'total_orders' => $staffGroup->sum('orders_handled'),
                'total_revenue' => $staffGroup->sum('total_revenue'),
            ];
        });

        // Staff statistics
        $staffStats = [
            'total' => $allStaff->count(),
            'total_orders_handled' => $staffPerformance->sum('orders_handled'),
            'total_revenue' => $staffPerformance->sum('total_revenue'),
            'by_role' => [
                'admin' => $allStaff->where('role', 'admin')->count(),
                'sales_person' => $allStaff->where('role', 'sales_person')->count(),
                'technician' => $allStaff->where('role', 'technician')->count(),
                'manager' => $allStaff->where('role', 'manager')->count(),
            ],
        ];

        return view('admin.analytics.staff_performance', compact(
            'staffByRole',
            'staffStats',
            'allStaff'
        ));
    }

    /**
     * Get role display name
     */
    private function getRoleName($role)
    {
        return match($role) {
            'admin' => 'Quản trị viên',
            'sales_person' => 'Nhân viên kinh doanh',
            'technician' => 'Kỹ thuật viên',
            'manager' => 'Quản lý',
            default => ucfirst($role),
        };
    }

    /**
     * Get default department for role
     */
    private function getDefaultDepartment($role)
    {
        return match($role) {
            'admin' => 'Quản trị',
            'sales_person' => 'Kinh doanh',
            'technician' => 'Kỹ thuật',
            'manager' => 'Quản lý',
            default => 'Khác',
        };
    }

    /**
     * Get default position for role
     */
    private function getDefaultPosition($role)
    {
        return match($role) {
            'admin' => 'Quản trị viên hệ thống',
            'sales_person' => 'Nhân viên bán hàng',
            'technician' => 'Kỹ thuật viên',
            'manager' => 'Trưởng phòng',
            default => 'Nhân viên',
        };
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
                return back()->with('error', 'Inventory module has been removed');
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
            ->where('status', 'delivered')
            ->with(['user'])
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
                    number_format($order->grand_total),
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
    // exportInventoryReport removed with inventory module

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
                $totalSpent = $customer->orders->where('status', 'delivered')->sum('grand_total');
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
