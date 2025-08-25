<?php

namespace App\Services;

use App\Models\Order;
use App\Models\CarVariant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ChartService
{
    public static function getSalesChartData($period = 'month', $limit = 12)
    {
        $query = Order::where('payment_status', 'completed');
        
        switch ($period) {
            case 'week':
                $data = $query->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total_price) as revenue')
                    ->where('created_at', '>=', Carbon::now()->subWeeks($limit))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                break;
                
            case 'month':
                $data = $query->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total_price) as revenue')
                    ->where('created_at', '>=', Carbon::now()->subMonths($limit))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                break;
                
            case 'year':
                $data = $query->selectRaw('MONTH(created_at) as month, COUNT(*) as orders, SUM(total_price) as revenue')
                    ->where('created_at', '>=', Carbon::now()->subYears($limit))
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();
                break;
                
            default:
                $data = collect();
        }
        
        return [
            'labels' => $data->pluck($period === 'year' ? 'month' : 'date'),
            'datasets' => [
                [
                    'label' => 'Đơn hàng',
                    'data' => $data->pluck('orders'),
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'tension' => 0.1
                ],
                [
                    'label' => 'Doanh thu (VNĐ)',
                    'data' => $data->pluck('revenue'),
                    'borderColor' => 'rgb(255, 99, 132)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'tension' => 0.1
                ]
            ]
        ];
    }

    public static function getTopSellingCarsChart($limit = 10)
    {
        $data = DB::table('order_items')
            ->join('car_variants', function($join) {
                $join->on('order_items.item_id', '=', 'car_variants.id')
                     ->where('order_items.item_type', '=', \App\Models\CarVariant::class);
            })
            ->join('car_models', 'car_variants.car_model_id', '=', 'car_models.id')
            ->join('car_brands', 'car_models.car_brand_id', '=', 'car_brands.id')
            ->selectRaw('car_brands.name as brand, car_models.name as model, SUM(order_items.quantity) as total_sold')
            ->groupBy('car_brands.name', 'car_models.name')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get();
        
        return [
            'labels' => $data->map(fn($item) => $item->brand . ' ' . $item->model),
            'datasets' => [
                [
                    'label' => 'Số lượng bán',
                    'data' => $data->pluck('total_sold'),
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(255, 159, 64, 0.8)',
                        'rgba(199, 199, 199, 0.8)',
                        'rgba(83, 102, 255, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)'
                    ]
                ]
            ]
        ];
    }

    public static function getCustomerGrowthChart($period = 'month', $limit = 12)
    {
        $query = User::where('role', 'user');
        
        switch ($period) {
            case 'week':
                $data = $query->selectRaw('DATE(created_at) as date, COUNT(*) as new_customers')
                    ->where('created_at', '>=', Carbon::now()->subWeeks($limit))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                break;
                
            case 'month':
                $data = $query->selectRaw('DATE(created_at) as date, COUNT(*) as new_customers')
                    ->where('created_at', '>=', Carbon::now()->subMonths($limit))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                break;
                
            case 'year':
                $data = $query->selectRaw('MONTH(created_at) as month, COUNT(*) as new_customers')
                    ->where('created_at', '>=', Carbon::now()->subYears($limit))
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();
                break;
                
            default:
                $data = collect();
        }
        
        return [
            'labels' => $data->pluck($period === 'year' ? 'month' : 'date'),
            'datasets' => [
                [
                    'label' => 'Khách hàng mới',
                    'data' => $data->pluck('new_customers'),
                    'borderColor' => 'rgb(54, 162, 235)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'tension' => 0.1
                ]
            ]
        ];
    }

    public static function getPaymentMethodChart()
    {
        $data = Order::selectRaw('payment_method_id, COUNT(*) as count')
            ->groupBy('payment_method_id')
            ->get();

        $labels = $data->map(function ($item) {
            $method = optional(\App\Models\PaymentMethod::find($item->payment_method_id))->name ?? 'Khác';
            return $method;
        });

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Số lượng đơn hàng',
                    'data' => $data->pluck('count'),
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)'
                    ]
                ]
            ]
        ];
    }

    public static function getInventoryChart()
    {
        $data = CarVariant::selectRaw('car_brands.name as brand, COUNT(*) as total_cars')
            ->join('car_models', 'car_variants.car_model_id', '=', 'car_models.id')
            ->join('car_brands', 'car_models.car_brand_id', '=', 'car_brands.id')
            ->where('car_variants.is_active', 1)
            ->groupBy('car_brands.name')
            ->orderBy('total_cars', 'desc')
            ->get();
        
        return [
            'labels' => $data->pluck('brand'),
            'datasets' => [
                [
                    'label' => 'Số lượng xe',
                    'data' => $data->pluck('total_cars'),
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)'
                    ]
                ]
            ]
        ];
    }

    public static function getDashboardStats()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        
        return [
            'total_revenue' => Order::where('payment_status', 'completed')->sum('total_price'),
            'total_orders' => Order::count(),
            'total_customers' => User::where('role', 'user')->count(),
            'total_cars' => CarVariant::where('is_active', 1)->count(),
            
            'today_revenue' => Order::whereDate('created_at', $today)
                ->where('payment_status', 'completed')
                ->sum('total_price'),
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            
            'month_revenue' => Order::where('created_at', '>=', $thisMonth)
                ->where('payment_status', 'completed')
                ->sum('total_price'),
            'month_orders' => Order::where('created_at', '>=', $thisMonth)->count(),
            
            'growth_rate' => self::calculateGrowthRate(),
            'conversion_rate' => self::calculateConversionRate()
        ];
    }

    private static function calculateGrowthRate()
    {
        $thisMonth = Order::where('created_at', '>=', Carbon::now()->startOfMonth())
            ->where('payment_status', 'completed')
            ->sum('total_price');
        
        $lastMonth = Order::where('created_at', '>=', Carbon::now()->subMonth()->startOfMonth())
            ->where('created_at', '<', Carbon::now()->startOfMonth())
            ->where('payment_status', 'completed')
            ->sum('total_price');
        
        if ($lastMonth == 0) return 0;
        
        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 2);
    }

    private static function calculateConversionRate()
    {
        $totalVisitors = 1000; // This should come from analytics
        $totalOrders = Order::count();
        
        if ($totalVisitors == 0) return 0;
        
        return round(($totalOrders / $totalVisitors) * 100, 2);
    }
} 