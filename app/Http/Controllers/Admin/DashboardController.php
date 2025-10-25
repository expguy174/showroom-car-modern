<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use App\Models\Order;
use App\Models\User;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\CarVariant;
use App\Models\Accessory;
use App\Models\Blog;
use App\Models\TestDrive;
use App\Models\ServiceAppointment;
use App\Models\Promotion;

class DashboardController extends Controller
{
    public function index()
    {
        // Cache dashboard stats for 5 minutes to improve performance
        $stats = Cache::remember('admin.dashboard.stats', 300, function () {
            return [
                // User breakdown
                'totalCustomers' => User::where('role', 'user')->count(), // 'user' role = customers
                'totalStaff' => User::whereIn('role', ['admin', 'manager', 'sales_person', 'technician'])->count(),
                'totalUsers' => User::count(),
                
                // Orders
                'totalOrders' => Order::count(),
                'pendingOrders' => Order::where('status', 'pending')->count(),
                'completedOrders' => Order::where('status', 'delivered')->count(),
                
                // Products breakdown
                'totalCarVariants' => CarVariant::count(),
                'activeCarVariants' => CarVariant::where('is_active', true)->count(),
                'totalAccessories' => Accessory::count() ?? 0,
                'activeAccessories' => Accessory::where('is_active', true)->count() ?? 0,
                
                // Business metrics
                'totalTestDrives' => TestDrive::count(),
                'pendingTestDrives' => TestDrive::where('status', 'scheduled')->count(),
                'totalServiceAppointments' => ServiceAppointment::count(),
                'pendingServiceAppointments' => ServiceAppointment::where('status', 'scheduled')->count(),
                'totalPromotions' => Promotion::where('is_active', true)->count(),
            ];
        });

        // Cache revenue data for 10 minutes
        $revenueData = Cache::remember('admin.dashboard.revenue', 600, function () {
            return [
                'monthlyRevenue' => Order::where('status', 'delivered')
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('grand_total') ?? 0,
                'totalRevenue' => Order::where('status', 'delivered')
                    ->sum('grand_total') ?? 0,
            ];
        });

        // Recent orders - cache for 2 minutes since this changes frequently
        $recentOrders = Cache::remember('admin.dashboard.recent_orders', 120, function () {
            return Order::with(['user.userProfile', 'items', 'billingAddress'])
                ->select(['id', 'order_number', 'user_id', 'status', 'payment_status', 'grand_total', 'total_price', 'billing_address_id', 'created_at'])
                ->latest()
                ->limit(5)
                ->get();
        });

        // Merge all data
        $data = array_merge($stats, $revenueData, [
            'recentOrders' => $recentOrders
        ]);

        return view('admin.dashboard', $data);
    }

    /**
     * Get dashboard stats via AJAX for real-time updates
     */
    public function getStats()
    {
        $stats = [
            'orders' => [
                'total' => Order::count(),
                'pending' => Order::where('status', 'pending')->count(),
                'delivered' => Order::where('status', 'delivered')->count(),
                'today' => Order::whereDate('created_at', today())->count(),
            ],
            'users' => [
                'total' => User::count(),
                'customers' => User::where('role', 'user')->count(), // 'user' role = customers
                'staff' => User::whereIn('role', ['admin', 'manager', 'sales_person', 'technician'])->count(),
                'new_this_week' => User::where('created_at', '>=', now()->subDays(7))->count(),
            ],
            'revenue' => [
                'today' => Order::whereDate('created_at', today())->where('status', 'delivered')->sum('grand_total') ?? 0,
                'this_week' => Order::where('created_at', '>=', now()->subDays(7))->where('status', 'delivered')->sum('grand_total') ?? 0,
                'this_month' => Order::whereMonth('created_at', now()->month)->where('status', 'delivered')->sum('grand_total') ?? 0,
            ],
            'products' => [
                'car_variants' => CarVariant::count(),
                'active_variants' => CarVariant::where('is_active', true)->count(),
                'accessories' => Accessory::count() ?? 0,
            ]
        ];

        return response()->json($stats);
    }

    /**
     * Clear dashboard cache
     */
    public function clearCache()
    {
        Cache::forget('admin.dashboard.stats');
        Cache::forget('admin.dashboard.revenue');
        Cache::forget('admin.dashboard.recent_orders');
        
        return response()->json(['message' => 'Cache cleared successfully']);
    }
}
