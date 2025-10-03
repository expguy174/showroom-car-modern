<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarVariant;
use App\Models\CarVariantColor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CarVariantColorController extends Controller
{
    public function index(CarVariant $carVariant)
    {
        $colors = $carVariant->colors()->orderBy('sort_order')->get();
        
        return view('admin.carvariants.colors.index', compact('carVariant', 'colors'));
    }

    public function create(CarVariant $carVariant)
    {
        return view('admin.carvariants.colors.create', compact('carVariant'));
    }

    public function store(Request $request, CarVariant $carVariant)
    {
        $validated = $request->validate([
            'color_name' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:50',
            'hex_code' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'rgb_code' => 'nullable|string|max:20',
            'color_type' => 'required|in:solid,metallic,pearlescent,matte,special',
            'availability' => 'required|in:standard,optional,limited,discontinued',
            'price_adjustment' => 'nullable|numeric',
            'is_free' => 'boolean',
            'description' => 'nullable|string',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Check for duplicate color name in this variant
        $existingColor = $carVariant->colors()
            ->where('color_name', $validated['color_name'])
            ->first();

        if ($existingColor) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Màu \"{$validated['color_name']}\" đã tồn tại trong phiên bản này.",
                    'errors' => ['color_name' => ["Màu \"{$validated['color_name']}\" đã tồn tại trong phiên bản này."]]
                ], 422);
            }
            return back()->withErrors(['color_name' => "Màu \"{$validated['color_name']}\" đã tồn tại trong phiên bản này."])->withInput();
        }

        $color = $carVariant->colors()->create([
            'color_name' => $validated['color_name'],
            'color_code' => $validated['color_code'],
            'hex_code' => $validated['hex_code'],
            'rgb_code' => $validated['rgb_code'],
            'color_type' => $validated['color_type'],
            'availability' => $validated['availability'],
            'price_adjustment' => $validated['price_adjustment'] ?? 0,
            'is_free' => $request->boolean('is_free', true),
            'description' => $validated['description'],
            'is_popular' => $request->boolean('is_popular', false),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        $successMessage = 'Đã thêm màu sắc thành công!';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect' => route('admin.carvariants.colors.index', $carVariant)
            ]);
        }

        return redirect()->route('admin.carvariants.colors.index', $carVariant)
            ->with('success', $successMessage);
    }

    public function edit(CarVariant $carVariant, CarVariantColor $color)
    {
        return view('admin.carvariants.colors.edit', compact('carVariant', 'color'));
    }

    public function update(Request $request, CarVariant $carVariant, CarVariantColor $color)
    {
        $validated = $request->validate([
            'color_name' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:50',
            'hex_code' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'rgb_code' => 'nullable|string|max:20',
            'color_type' => 'required|in:solid,metallic,pearlescent,matte,special',
            'availability' => 'required|in:standard,optional,limited,discontinued',
            'price_adjustment' => 'nullable|numeric',
            'is_free' => 'boolean',
            'description' => 'nullable|string',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Check for duplicate color name (excluding current color)
        $existingColor = $carVariant->colors()
            ->where('color_name', $validated['color_name'])
            ->where('id', '!=', $color->id)
            ->first();

        if ($existingColor) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Màu \"{$validated['color_name']}\" đã tồn tại trong phiên bản này.",
                    'errors' => ['color_name' => ["Màu \"{$validated['color_name']}\" đã tồn tại trong phiên bản này."]]
                ], 422);
            }
            return back()->withErrors(['color_name' => "Màu \"{$validated['color_name']}\" đã tồn tại trong phiên bản này."])->withInput();
        }

        $color->update([
            'color_name' => $validated['color_name'],
            'color_code' => $validated['color_code'],
            'hex_code' => $validated['hex_code'],
            'rgb_code' => $validated['rgb_code'],
            'color_type' => $validated['color_type'],
            'availability' => $validated['availability'],
            'price_adjustment' => $validated['price_adjustment'] ?? 0,
            'is_free' => $request->boolean('is_free', true),
            'description' => $validated['description'],
            'is_popular' => $request->boolean('is_popular', false),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        $successMessage = 'Đã cập nhật màu sắc thành công!';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect' => route('admin.carvariants.colors.index', $carVariant)
            ]);
        }

        return redirect()->route('admin.carvariants.colors.index', $carVariant)
            ->with('success', $successMessage);
    }

    public function destroy(CarVariant $carVariant, CarVariantColor $color)
    {
        $color->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa màu sắc thành công!'
        ]);
    }
}
