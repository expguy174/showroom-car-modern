<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Showroom;
use App\Models\Dealership;
use App\Models\CarVariant;

class ShowroomController extends Controller
{
    public function index(Request $request)
    {
        $query = Showroom::with(['dealership']);

        // Search by name, address, or city
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Filter by dealership
        if ($request->filled('dealership_id')) {
            $query->where('dealership_id', $request->dealership_id);
        }

        $showrooms = $query->orderBy('name')->paginate(12);

        // Get all cities for filter
        $cities = Showroom::select('city')
            ->distinct()
            ->whereNotNull('city')
            ->orderBy('city')
            ->pluck('city');

        // Get all dealerships for filter
        $dealerships = Dealership::orderBy('name')->get();

        return view('user.showrooms.index', compact('showrooms', 'cities', 'dealerships'));
    }

    public function show(Showroom $showroom)
    {
        $showroom->load(['dealership']);

        // Get available car variants at this showroom (if inventory system exists)
        // For now, we'll show all active variants
        $availableVariants = CarVariant::where('is_active', true)
            ->with(['carModel.carBrand', 'images', 'colors'])
            ->take(6)
            ->get();

        // Get nearby showrooms (same city)
        $nearbyShowrooms = Showroom::where('city', $showroom->city)
            ->where('id', '!=', $showroom->id)
            ->with(['dealership'])
            ->take(4)
            ->get();

        return view('user.showrooms.show', compact('showroom', 'availableVariants', 'nearbyShowrooms'));
    }

    public function map(Request $request)
    {
        $query = Showroom::with(['dealership']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('dealership_id')) {
            $query->where('dealership_id', $request->dealership_id);
        }

        $showrooms = $query->get();

        // Get all cities and dealerships for filters
        $cities = Showroom::select('city')
            ->distinct()
            ->whereNotNull('city')
            ->orderBy('city')
            ->pluck('city');

        $dealerships = Dealership::orderBy('name')->get();

        return view('user.showrooms.map', compact('showrooms', 'cities', 'dealerships'));
    }

    public function contact(Request $request, Showroom $showroom)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // Create contact message
        \App\Models\ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'showroom_id' => $showroom->id,
            'status' => 'new',
        ]);

        return back()->with('success', 'Tin nhắn của bạn đã được gửi thành công! Showroom sẽ liên hệ lại trong thời gian sớm nhất.');
    }

    public function directions(Showroom $showroom)
    {
        // Return JSON data for directions
        return response()->json([
            'name' => $showroom->name,
            'address' => $showroom->address,
            'city' => $showroom->city,
            'latitude' => $showroom->latitude,
            'longitude' => $showroom->longitude,
            'phone' => $showroom->phone,
            'email' => $showroom->email,
        ]);
    }
}
