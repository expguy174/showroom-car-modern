<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        // Basic counts
        $totalUsers = User::count();
        $totalOrders = Order::count();
        $totalCarBrands = CarBrand::count();
        $totalCarModels = CarModel::count();
        $totalCarVariants = CarVariant::count();
        $totalAccessories = Accessory::count();
        $totalBlogs = Blog::count();
        
        // Business metrics
        $totalTestDrives = TestDrive::count();
        $totalServiceAppointments = ServiceAppointment::count();
        $totalPromotions = Promotion::where('is_active', true)->count();
        
        // Recent activity
        $recentOrders = Order::with(['user.userProfile'])
            ->latest()
            ->limit(5)
            ->get();
            
        $recentUsers = User::with('userProfile')
            ->latest()
            ->limit(5)
            ->get();
            
        // Revenue calculation
        $monthlyRevenue = Order::where('status', 'delivered')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('grand_total');
            
        $totalRevenue = Order::where('status', 'delivered')
            ->sum('grand_total');

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalOrders',
            'totalCarBrands',
            'totalCarModels', 
            'totalCarVariants',
            'totalAccessories',
            'totalBlogs',
            'totalTestDrives',
            'totalServiceAppointments',
            'totalPromotions',
            'recentOrders',
            'recentUsers',
            'monthlyRevenue',
            'totalRevenue'
        ));
    }
}
