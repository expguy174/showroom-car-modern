<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use Illuminate\Http\Request;

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
        $accessory->delete();

        return redirect()->route('admin.accessories.index')->with('success', 'Xoá phụ kiện thành công!');
    }
}