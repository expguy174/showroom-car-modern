<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Showroom;
use App\Models\CarVariant;
use App\Models\CarBrand;
use App\Models\User;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::with(['carVariant.carModel.carBrand', 'showroom', 'assignedSalesPerson']);

        // Filter by showroom
        if ($request->has('showroom_id') && $request->showroom_id) {
            $query->where('showroom_id', $request->showroom_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by condition
        if ($request->has('condition') && $request->condition) {
            $query->where('condition', $request->condition);
        }

        // Filter by car brand
        if ($request->has('car_brand') && $request->car_brand) {
            $query->whereHas('carVariant.carModel.carBrand', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->car_brand . '%');
            });
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('vin', 'like', '%' . $search . '%')
                  ->orWhere('license_plate', 'like', '%' . $search . '%')
                  ->orWhereHas('carVariant.carModel.carBrand', function($carQuery) use ($search) {
                      $carQuery->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('carVariant.carModel', function($modelQuery) use ($search) {
                      $modelQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        $inventories = $query->orderBy('created_at', 'desc')->paginate(15);
        $showrooms = Showroom::all();
        $carBrands = CarBrand::distinct()->pluck('name');
        $statuses = ['available', 'reserved', 'sold', 'in_transit', 'maintenance', 'test_drive'];
        $conditions = ['excellent', 'good', 'fair', 'poor'];

        return view('admin.inventory.index', compact('inventories', 'showrooms', 'carBrands', 'statuses', 'conditions'));
    }

    public function create()
    {
        $showrooms = Showroom::all();
        $carVariants = CarVariant::with('carModel.carBrand')->get();
        $salesPersons = User::where('role', 'sales')->get();

        return view('admin.inventory.create', compact('showrooms', 'carVariants', 'salesPersons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'showroom_id' => 'required|exists:showrooms,id',
            'car_variant_id' => 'required|exists:car_variants,id',
            'vin' => 'required|unique:inventories,vin',
            'license_plate' => 'nullable|string|max:20',
            'color' => 'required|string|max:50',
            'mileage' => 'required|integer|min:0',
            'condition' => 'required|in:excellent,good,fair,poor',
            'status' => 'required|in:available,reserved,sold,in_transit,maintenance,test_drive',
            'selling_price' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'assigned_sales_person_id' => 'nullable|exists:users,id',
            'location_details' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $inventory = Inventory::create($request->all());

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Xe đã được thêm vào kho thành công!');
    }

    public function show(Inventory $inventory)
    {
        $inventory->load(['carVariant.carModel.carBrand', 'showroom', 'assignedSalesPerson']);
        
        return view('admin.inventory.show', compact('inventory'));
    }

    public function edit(Inventory $inventory)
    {
        $showrooms = Showroom::all();
        $carVariants = CarVariant::with('carModel.carBrand')->get();
        $salesPersons = User::where('role', 'sales')->get();

        return view('admin.inventory.edit', compact('inventory', 'showrooms', 'carVariants', 'salesPersons'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'showroom_id' => 'required|exists:showrooms,id',
            'car_variant_id' => 'required|exists:car_variants,id',
            'vin' => 'required|unique:inventories,vin,' . $inventory->id,
            'license_plate' => 'nullable|string|max:20',
            'color' => 'required|string|max:50',
            'mileage' => 'required|integer|min:0',
            'condition' => 'required|in:excellent,good,fair,poor',
            'status' => 'required|in:available,reserved,sold,in_transit,maintenance,test_drive',
            'selling_price' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'assigned_sales_person_id' => 'nullable|exists:users,id',
            'location_details' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $inventory->update($request->all());

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Thông tin xe đã được cập nhật thành công!');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Xe đã được xóa khỏi kho thành công!');
    }

    public function updateStatus(Request $request, Inventory $inventory)
    {
        $request->validate([
            'status' => 'required|in:available,reserved,sold,in_transit,maintenance,test_drive'
        ]);

        $inventory->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Trạng thái xe đã được cập nhật thành công!'
        ]);
    }

    public function export(Request $request)
    {
        $query = Inventory::with(['carVariant.carModel.carBrand', 'showroom']);

        // Apply filters
        if ($request->has('showroom_id') && $request->showroom_id) {
            $query->where('showroom_id', $request->showroom_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $inventories = $query->get();

        $filename = 'inventory_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($inventories) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'VIN', 'Biển số', 'Hãng xe', 'Dòng xe', 'Phiên bản', 'Màu sắc', 
                'Số km', 'Tình trạng', 'Trạng thái', 'Giá bán', 'Showroom', 
                'Ngày nhập kho', 'Số ngày trong kho'
            ]);

            foreach ($inventories as $inventory) {
                fputcsv($file, [
                    $inventory->vin,
                    $inventory->license_plate ?? 'N/A',
                    $inventory->carVariant->carModel->carBrand->name,
                    $inventory->carVariant->carModel->name,
                    $inventory->carVariant->name,
                    $inventory->color,
                    $inventory->mileage,
                    $inventory->condition,
                    $inventory->status,
                    $inventory->selling_price,
                    $inventory->showroom->name,
                    $inventory->created_at->format('d/m/Y'),
                    $inventory->days_in_inventory
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
