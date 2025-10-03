<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarVariant;
use App\Models\CarVariantSpecification;
use Illuminate\Http\Request;

class CarVariantSpecificationController extends Controller
{
    public function index(CarVariant $carVariant)
    {
        $specifications = $carVariant->specifications()
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');
        
        $categories = CarVariantSpecification::getCategories();
        
        return view('admin.carvariants.specifications.index', compact('carVariant', 'specifications', 'categories'));
    }

    public function create(CarVariant $carVariant)
    {
        $categories = CarVariantSpecification::getCategories();
        
        return view('admin.carvariants.specifications.create', compact('carVariant', 'categories'));
    }

    public function store(Request $request, CarVariant $carVariant)
    {
        $validated = $request->validate([
            'category' => 'nullable|string|max:255',
            'spec_name' => 'required|string|max:255',
            'spec_value' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'spec_code' => 'nullable|string|max:100',
            'is_important' => 'boolean',
            'is_highlighted' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Check for duplicate spec name in this variant
        $existingSpec = $carVariant->specifications()
            ->where('spec_name', $validated['spec_name'])
            ->first();

        if ($existingSpec) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Thông số \"{$validated['spec_name']}\" đã tồn tại trong phiên bản này.",
                    'errors' => ['spec_name' => ["Thông số \"{$validated['spec_name']}\" đã tồn tại trong phiên bản này."]]
                ], 422);
            }
            return back()->withErrors(['spec_name' => "Thông số \"{$validated['spec_name']}\" đã tồn tại trong phiên bản này."])->withInput();
        }

        $specification = $carVariant->specifications()->create([
            'category' => $validated['category'],
            'spec_name' => $validated['spec_name'],
            'spec_value' => $validated['spec_value'],
            'unit' => $validated['unit'],
            'description' => $validated['description'],
            'spec_code' => $validated['spec_code'],
            'is_important' => $request->boolean('is_important', false),
            'is_highlighted' => $request->boolean('is_highlighted', false),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        $successMessage = 'Đã thêm thông số kỹ thuật thành công!';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect' => route('admin.carvariants.specifications.index', $carVariant)
            ]);
        }

        return redirect()->route('admin.carvariants.specifications.index', $carVariant)
            ->with('success', $successMessage);
    }

    public function edit(CarVariant $carVariant, CarVariantSpecification $specification)
    {
        $categories = CarVariantSpecification::getCategories();
        
        return view('admin.carvariants.specifications.edit', compact('carVariant', 'specification', 'categories'));
    }

    public function update(Request $request, CarVariant $carVariant, CarVariantSpecification $specification)
    {
        $validated = $request->validate([
            'category' => 'nullable|string|max:255',
            'spec_name' => 'required|string|max:255',
            'spec_value' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'spec_code' => 'nullable|string|max:100',
            'is_important' => 'boolean',
            'is_highlighted' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Check for duplicate spec name (excluding current spec)
        $existingSpec = $carVariant->specifications()
            ->where('spec_name', $validated['spec_name'])
            ->where('id', '!=', $specification->id)
            ->first();

        if ($existingSpec) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Thông số \"{$validated['spec_name']}\" đã tồn tại trong phiên bản này.",
                    'errors' => ['spec_name' => ["Thông số \"{$validated['spec_name']}\" đã tồn tại trong phiên bản này."]]
                ], 422);
            }
            return back()->withErrors(['spec_name' => "Thông số \"{$validated['spec_name']}\" đã tồn tại trong phiên bản này."])->withInput();
        }

        $specification->update([
            'category' => $validated['category'],
            'spec_name' => $validated['spec_name'],
            'spec_value' => $validated['spec_value'],
            'unit' => $validated['unit'],
            'description' => $validated['description'],
            'spec_code' => $validated['spec_code'],
            'is_important' => $request->boolean('is_important', false),
            'is_highlighted' => $request->boolean('is_highlighted', false),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        $successMessage = 'Đã cập nhật thông số kỹ thuật thành công!';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect' => route('admin.carvariants.specifications.index', $carVariant)
            ]);
        }

        return redirect()->route('admin.carvariants.specifications.index', $carVariant)
            ->with('success', $successMessage);
    }

    public function destroy(CarVariant $carVariant, CarVariantSpecification $specification)
    {
        $specification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa thông số kỹ thuật thành công!'
        ]);
    }
}
