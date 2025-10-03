<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AccessoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Accessory::query();

        if ($request->has('search') && $request->search !== '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $accessories = $query->orderByDesc('created_at')->paginate(10);

        return view('admin.accessories.index', [
            'accessories' => $accessories,
            'search' => $request->search,
        ]);
    }

    public function create()
    {
        return view('admin.accessories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_path' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        Accessory::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? '',
            'price' => $validated['price'],
            'image_path' => $validated['image_path'] ?? null,
            'is_active' => $validated['is_active'],
        ]);

        return redirect()->route('admin.accessories.index')->with('success', 'Thêm phụ kiện thành công!');
    }

    public function edit($id)
    {
        $accessory = Accessory::findOrFail($id);
        return view('admin.accessories.edit', compact('accessory'));
    }

    public function update(Request $request, $id)
    {
        $accessory = Accessory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_path' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $accessory->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? '',
            'price' => $validated['price'],
            'image_path' => $validated['image_path'] ?? null,
            'is_active' => $validated['is_active'],
        ]);

        return redirect()->route('admin.accessories.index')->with('success', 'Cập nhật phụ kiện thành công!');
    }

    public function destroy($id)
    {
        $accessory = Accessory::findOrFail($id);
        
        // Check for orders (if orders table exists and has accessory_id)
        $ordersCount = 0;
        try {
            if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'accessory_id')) {
                $ordersCount = DB::table('orders')
                    ->where('accessory_id', $accessory->id)
                    ->count();
            }
        } catch (\Exception $e) {
            $ordersCount = 0;
        }

        // Business logic validation - NEVER delete accessories with orders
        if ($ordersCount > 0) {
            return redirect()->route('admin.accessories.index')->with('error', 
                "⚠️ KHÔNG THỂ XÓA phụ kiện \"{$accessory->name}\" vì đã có {$ordersCount} đơn hàng. " .
                "Đây là dữ liệu giao dịch quan trọng! Bạn chỉ có thể 'Ngừng bán' thay vì xóa."
            );
        }

        // Delete image if exists
        if ($accessory->image_path && Storage::disk('public')->exists($accessory->image_path)) {
            Storage::disk('public')->delete($accessory->image_path);
        }

        // Safe to delete - no orders
        $accessory->delete();
        
        return redirect()->route('admin.accessories.index')->with('success', 
            "Đã xóa phụ kiện \"{$accessory->name}\" thành công!"
        );
    }

    public function toggleStatus(Request $request, $id)
    {
        $accessory = Accessory::findOrFail($id);
        
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $newStatus = $request->is_active;
        $accessory->update(['is_active' => $newStatus]);

        $statusText = $newStatus ? 'kích hoạt' : 'ngừng bán';
        
        return response()->json([
            'success' => true,
            'message' => "Đã {$statusText} phụ kiện \"{$accessory->name}\" thành công!",
            'is_active' => $newStatus
        ]);
    }
}