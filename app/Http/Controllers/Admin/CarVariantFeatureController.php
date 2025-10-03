<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarVariant;
use App\Models\CarVariantFeature;
use Illuminate\Http\Request;

class CarVariantFeatureController extends Controller
{
    public function index(CarVariant $carVariant)
    {
        $features = $carVariant->featuresRelation()
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');
        
        $categories = $this->getFeatureCategories();
        
        return view('admin.carvariants.features.index', compact('carVariant', 'features', 'categories'));
    }

    public function create(CarVariant $carVariant)
    {
        $categories = $this->getFeatureCategories();
        $availabilityOptions = $this->getAvailabilityOptions();
        $importanceOptions = $this->getImportanceOptions();
        
        return view('admin.carvariants.features.create', compact('carVariant', 'categories', 'availabilityOptions', 'importanceOptions'));
    }

    public function store(Request $request, CarVariant $carVariant)
    {
        $validated = $request->validate([
            'feature_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'feature_code' => 'nullable|string|max:100',
            'category' => 'required|in:safety,comfort,technology,performance,exterior,interior,entertainment,convenience,wheels,audio,navigation',
            'availability' => 'required|in:standard,optional',
            'importance' => 'required|in:essential,important,nice_to_have,luxury',
            'price' => 'nullable|numeric|min:0',
            'is_included' => 'boolean',
            'icon_path' => 'nullable|string|max:255',
            'image_path' => 'nullable|string|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_popular' => 'boolean',
            'is_recommended' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Check for duplicate feature name in this variant
        $existingFeature = $carVariant->featuresRelation()
            ->where('feature_name', $validated['feature_name'])
            ->first();

        if ($existingFeature) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Tính năng \"{$validated['feature_name']}\" đã tồn tại trong phiên bản này.",
                    'errors' => ['feature_name' => ["Tính năng \"{$validated['feature_name']}\" đã tồn tại trong phiên bản này."]]
                ], 422);
            }
            return back()->withErrors(['feature_name' => "Tính năng \"{$validated['feature_name']}\" đã tồn tại trong phiên bản này."])->withInput();
        }

        $feature = $carVariant->featuresRelation()->create([
            'feature_name' => $validated['feature_name'],
            'description' => $validated['description'],
            'feature_code' => $validated['feature_code'],
            'category' => $validated['category'],
            'availability' => $validated['availability'],
            'importance' => $validated['importance'],
            'price' => $validated['price'] ?? 0,
            'is_included' => $request->boolean('is_included', true),
            'icon_path' => $validated['icon_path'],
            'image_path' => $validated['image_path'],
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
            'is_popular' => $request->boolean('is_popular', false),
            'is_recommended' => $request->boolean('is_recommended', false),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        $successMessage = 'Đã thêm tính năng thành công!';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect' => route('admin.carvariants.features.index', $carVariant)
            ]);
        }

        return redirect()->route('admin.carvariants.features.index', $carVariant)
            ->with('success', $successMessage);
    }

    public function edit(CarVariant $carVariant, CarVariantFeature $feature)
    {
        $categories = $this->getFeatureCategories();
        $availabilityOptions = $this->getAvailabilityOptions();
        $importanceOptions = $this->getImportanceOptions();
        
        return view('admin.carvariants.features.edit', compact('carVariant', 'feature', 'categories', 'availabilityOptions', 'importanceOptions'));
    }

    public function update(Request $request, CarVariant $carVariant, CarVariantFeature $feature)
    {
        $validated = $request->validate([
            'feature_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'feature_code' => 'nullable|string|max:100',
            'category' => 'required|in:safety,comfort,technology,performance,exterior,interior,entertainment,convenience,wheels,audio,navigation',
            'availability' => 'required|in:standard,optional',
            'importance' => 'required|in:essential,important,nice_to_have,luxury',
            'price' => 'nullable|numeric|min:0',
            'is_included' => 'boolean',
            'icon_path' => 'nullable|string|max:255',
            'image_path' => 'nullable|string|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_popular' => 'boolean',
            'is_recommended' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Check for duplicate feature name (excluding current feature)
        $existingFeature = $carVariant->featuresRelation()
            ->where('feature_name', $validated['feature_name'])
            ->where('id', '!=', $feature->id)
            ->first();

        if ($existingFeature) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Tính năng \"{$validated['feature_name']}\" đã tồn tại trong phiên bản này.",
                    'errors' => ['feature_name' => ["Tính năng \"{$validated['feature_name']}\" đã tồn tại trong phiên bản này."]]
                ], 422);
            }
            return back()->withErrors(['feature_name' => "Tính năng \"{$validated['feature_name']}\" đã tồn tại trong phiên bản này."])->withInput();
        }

        $feature->update([
            'feature_name' => $validated['feature_name'],
            'description' => $validated['description'],
            'feature_code' => $validated['feature_code'],
            'category' => $validated['category'],
            'availability' => $validated['availability'],
            'importance' => $validated['importance'],
            'price' => $validated['price'] ?? 0,
            'is_included' => $request->boolean('is_included', true),
            'icon_path' => $validated['icon_path'],
            'image_path' => $validated['image_path'],
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
            'is_popular' => $request->boolean('is_popular', false),
            'is_recommended' => $request->boolean('is_recommended', false),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        $successMessage = 'Đã cập nhật tính năng thành công!';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect' => route('admin.carvariants.features.index', $carVariant)
            ]);
        }

        return redirect()->route('admin.carvariants.features.index', $carVariant)
            ->with('success', $successMessage);
    }

    public function destroy(CarVariant $carVariant, CarVariantFeature $feature)
    {
        $feature->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa tính năng thành công!'
        ]);
    }

    private function getFeatureCategories()
    {
        return [
            'safety' => 'An toàn',
            'comfort' => 'Tiện nghi',
            'technology' => 'Công nghệ',
            'performance' => 'Hiệu suất',
            'exterior' => 'Ngoại thất',
            'interior' => 'Nội thất',
            'entertainment' => 'Giải trí',
            'convenience' => 'Tiện ích',
            'wheels' => 'Bánh xe',
            'audio' => 'Âm thanh',
            'navigation' => 'Định vị'
        ];
    }

    private function getAvailabilityOptions()
    {
        return [
            'standard' => 'Tiêu chuẩn',
            'optional' => 'Tùy chọn'
        ];
    }

    private function getImportanceOptions()
    {
        return [
            'essential' => 'Thiết yếu',
            'important' => 'Quan trọng',
            'nice_to_have' => 'Tốt nếu có',
            'luxury' => 'Sang trọng'
        ];
    }
}
