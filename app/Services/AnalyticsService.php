<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CarVariant;
use App\Models\User;
use App\Models\TestDrive;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public static function getDashboardStats()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        return [
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('payment_status', 'completed')->sum('total_price'),
            'total_customers' => User::where('role', 'user')->count(),
            'total_cars' => CarVariant::where('is_active', 1)->count(),
            
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'today_revenue' => Order::whereDate('created_at', $today)
                ->where('payment_status', 'completed')
                ->sum('total_price'),
            
            'this_month_orders' => Order::where('created_at', '>=', $thisMonth)->count(),
            'this_month_revenue' => Order::where('created_at', '>=', $thisMonth)
                ->where('payment_status', 'completed')
                ->sum('total_price'),
            
            'last_month_orders' => Order::whereBetween('created_at', [$lastMonth, $thisMonth])->count(),
            'last_month_revenue' => Order::whereBetween('created_at', [$lastMonth, $thisMonth])
                ->where('payment_status', 'completed')
                ->sum('total_price'),
        ];
    }

    public static function getSalesChart($period = 'month')
    {
        $query = Order::where('payment_status', 'completed');

        switch ($period) {
            case 'week':
                $data = $query->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total_price) as revenue')
                    ->where('created_at', '>=', Carbon::now()->subWeek())
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                break;
            
            case 'month':
                $data = $query->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total_price) as revenue')
                    ->where('created_at', '>=', Carbon::now()->subMonth())
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                break;
            
            case 'year':
                $data = $query->selectRaw('MONTH(created_at) as month, COUNT(*) as orders, SUM(total_price) as revenue')
                    ->where('created_at', '>=', Carbon::now()->subYear())
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();
                break;
            
            default:
                $data = collect();
        }

        return $data;
    }

    public static function getTopSellingCars($limit = 10)
    {
        return OrderItem::with('product.carVariant.carModel.car')
            ->selectRaw('product_id, SUM(quantity) as total_sold, SUM(quantity * price) as total_revenue')
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getCustomerStats()
    {
        return [
            'new_customers_this_month' => User::where('role', 'user')
                ->where('created_at', '>=', Carbon::now()->startOfMonth())
                ->count(),
            
            'repeat_customers' => User::where('role', 'user')
                ->whereHas('orders', function ($query) {
                    $query->havingRaw('COUNT(*) > 1');
                })
                ->count(),
            
            'average_order_value' => Order::where('payment_status', 'completed')->avg('total_price'),
        ];
    }

    public static function getTestDriveStats()
    {
        return [
            'total_test_drives' => TestDrive::count(),
            'pending_test_drives' => TestDrive::where('status', 'scheduled')->count(),
            'completed_test_drives' => TestDrive::where('status', 'completed')->count(),
            'test_drives_this_month' => TestDrive::where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
        ];
    }

    public static function getInventoryStats()
    {
        return [
            'total_cars' => CarVariant::where('is_active', 1)->count(),
            'low_stock_cars' => CarVariant::where('is_active', 1)
                ->where('stock_quantity', '<=', 5)
                ->count(),
            'out_of_stock_cars' => CarVariant::where('is_active', 1)
                ->where('stock_quantity', 0)
                ->count(),
        ];
    }

    public static function getPaymentStats()
    {
        return [
            'total_payments' => Order::where('payment_status', 'completed')->count(),
            'pending_payments' => Order::where('payment_status', 'pending')->count(),
            'failed_payments' => Order::where('payment_status', 'failed')->count(),
            
            'payment_methods' => Order::selectRaw('payment_method_id, COUNT(*) as count')
                ->groupBy('payment_method_id')
                ->get(),
        ];
    }

    public static function generateReport($type, $params = [])
    {
        switch ($type) {
            case 'sales':
                return self::generateSalesReport($params);
            case 'inventory':
                return self::generateInventoryReport($params);
            case 'customers':
                return self::generateCustomerReport($params);
            default:
                return null;
        }
    }

    private static function generateSalesReport($params)
    {
        $startDate = $params['start_date'] ?? Carbon::now()->subMonth();
        $endDate = $params['end_date'] ?? Carbon::now();

        return [
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
            ],
            'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_revenue' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_status', 'completed')
                ->sum('total_price'),
            'average_order_value' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_status', 'completed')
                ->avg('total_price'),
            'top_products' => self::getTopSellingCars(5),
        ];
    }

    private static function generateInventoryReport($params)
    {
        return [
            'total_cars' => CarVariant::where('is_active', 1)->count(),
            'low_stock' => CarVariant::where('is_active', 1)
                ->where('stock_quantity', '<=', 5)
                ->get(),
            'out_of_stock' => CarVariant::where('is_active', 1)
                ->where('stock_quantity', 0)
                ->get(),
            'brand_distribution' => CarVariant::with('carModel.car')
                ->where('is_active', 1)
                ->get()
                ->groupBy('carModel.car.name')
                ->map(function ($group) {
                    return $group->count();
                }),
        ];
    }

    private static function generateCustomerReport($params)
    {
        return [
            'total_customers' => User::where('role', 'user')->count(),
            'new_customers' => User::where('role', 'user')
                ->where('created_at', '>=', Carbon::now()->subMonth())
                ->count(),
            'repeat_customers' => User::where('role', 'user')
                ->whereHas('orders', function ($query) {
                    $query->havingRaw('COUNT(*) > 1');
                })
                ->count(),
            'top_customers' => User::where('role', 'user')
                ->withSum('orders', 'total_price')
                ->orderBy('orders_sum_total_price', 'desc')
                ->limit(10)
                ->get(),
        ];
    }
} 