<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CarVariant;
use App\Models\Accessory;
use App\Models\CarBrand;
use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = trim($request->get('q', ''));
        $category = $request->get('category', 'all');
        $sortBy = $request->get('sort', 'relevance');
        $perPage = $request->get('per_page', 12);
        
        if (empty($query)) {
            return redirect()->route('home');
        }
        
        $results = [];
        $totalResults = 0;
        
        // Search car variants
        if ($category === 'all' || $category === 'cars') {
            $carVariants = $this->searchCarVariants($query, $sortBy, $perPage);
            $results['cars'] = $carVariants;
            $totalResults += $carVariants->total();
        }
        
        // Search accessories
        if ($category === 'all' || $category === 'accessories') {
            $accessories = $this->searchAccessories($query, $sortBy, $perPage);
            $results['accessories'] = $accessories;
            $totalResults += $accessories->total();
        }
        
        // Get search suggestions
        $suggestions = $this->getSearchSuggestions($query);
        
        return view('user.search.results', compact('results', 'query', 'category', 'sortBy', 'totalResults', 'suggestions'));
    }
    
    private function searchCarVariants($query, $sortBy, $perPage)
    {
        $searchQuery = CarVariant::with(['carModel.carBrand', 'images', 'colors', 'specifications'])
            ->where('is_active', 1)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhereHas('specifications', function($sq) use ($query) {
                      $sq->where('spec_value', 'like', "%{$query}%")
                         ->orWhere('spec_name', 'like', "%{$query}%");
                  })
                  ->orWhereHas('carModel', function($subQ) use ($query) {
                      $subQ->where('name', 'like', "%{$query}%")
                           ->orWhere('description', 'like', "%{$query}%");
                  })
                  ->orWhereHas('carModel.carBrand', function($subQ) use ($query) {
                      $subQ->where('name', 'like', "%{$query}%")
                           ->orWhere('description', 'like', "%{$query}%");
                  });
            });
        
        // Apply sorting
        switch ($sortBy) {
            case 'price_low':
                $searchQuery->orderBy('current_price', 'asc');
                break;
            case 'price_high':
                $searchQuery->orderBy('current_price', 'desc');
                break;
            case 'newest':
                $searchQuery->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $searchQuery->orderBy('created_at', 'desc');
                break;
            case 'rating':
                $searchQuery->orderBy('created_at', 'desc');
                break;
            default: // relevance
                $searchQuery->orderByRaw("\n                    CASE \n                        WHEN name LIKE '{$query}' THEN 1\n                        WHEN name LIKE '{$query}%' THEN 2\n                        WHEN name LIKE '%{$query}%' THEN 3\n                        ELSE 4\n                    END\n                ")->orderBy('created_at', 'desc');
        }
        
        return $searchQuery->paginate($perPage);
    }
    
    private function searchAccessories($query, $sortBy, $perPage)
    {
        $searchQuery = Accessory::where('is_active', 1)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('category', 'like', "%{$query}%")
                  ->orWhere('subcategory', 'like', "%{$query}%");
            });
        
        // Apply sorting
        switch ($sortBy) {
            case 'price_low':
                $searchQuery->orderBy('current_price', 'asc');
                break;
            case 'price_high':
                $searchQuery->orderBy('current_price', 'desc');
                break;
            case 'newest':
                $searchQuery->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $searchQuery->orderBy('created_at', 'desc');
                break;
            case 'rating':
                $searchQuery->orderBy('created_at', 'desc');
                break;
            default: // relevance
                $searchQuery->orderByRaw("\n                    CASE \n                        WHEN name LIKE '{$query}' THEN 1\n                        WHEN name LIKE '{$query}%' THEN 2\n                        WHEN name LIKE '%{$query}%' THEN 3\n                        ELSE 4\n                    END\n                ")->orderBy('created_at', 'desc');
        }
        
        return $searchQuery->paginate($perPage);
    }
    
    private function getSearchSuggestions($query)
    {
        if (strlen($query) < 2) return [];
        
        $suggestions = [];
        
        // Get car brand suggestions
        $brands = CarBrand::where('name', 'like', "%{$query}%")
            ->limit(5)
            ->pluck('name')
            ->toArray();
        
        // Get car model suggestions
        $models = CarModel::where('name', 'like', "%{$query}%")
            ->limit(5)
            ->pluck('name')
            ->toArray();
        
        // Get car variant suggestions
        $variants = CarVariant::where('name', 'like', "%{$query}%")
            ->where('is_active', 1)
            ->limit(5)
            ->pluck('name')
            ->toArray();
            
        // Get accessory suggestions
        $accessories = Accessory::where('name', 'like', "%{$query}%")
            ->where('is_active', 1)
            ->limit(5)
            ->pluck('name')
            ->toArray();
        
        $suggestions = array_merge($brands, $models, $variants, $accessories);
        $suggestions = array_unique($suggestions);
        
        return array_slice($suggestions, 0, 10);
    }
    
    public function advancedSearch(Request $request)
    {
        $filters = $request->only([
            'brand', 'model', 'min_price', 'max_price', 'fuel_type', 
            'transmission', 'body_type', 'seats', 'year_min', 'year_max',
            'engine_type', 'drivetrain', 'color', 'features'
        ]);
        
        $sortBy = $request->get('sort', 'relevance');
        $perPage = $request->get('per_page', 12);
        
        $query = CarVariant::with(['carModel.carBrand', 'images', 'colors', 'specifications'])
            ->where('is_active', 1);
        
        // Apply filters
        if (!empty($filters['brand'])) {
            $query->whereHas('carModel.carBrand', function($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['brand']}%");
            });
        }
        
        if (!empty($filters['model'])) {
            $query->whereHas('carModel', function($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['model']}%");
            });
        }
        
        if (!empty($filters['min_price'])) {
            $query->where('current_price', '>=', $filters['min_price']);
        }
        
        if (!empty($filters['max_price'])) {
            $query->where('current_price', '<=', $filters['max_price']);
        }
        
        if (!empty($filters['fuel_type'])) {
            $query->whereHas('specifications', function($q) use ($filters) {
                $q->where('spec_name', 'fuel_type')->where('spec_value', $filters['fuel_type']);
            });
        }
        
        if (!empty($filters['transmission'])) {
            $query->whereHas('specifications', function($q) use ($filters) {
                $q->where('spec_name', 'transmission')->where('spec_value', $filters['transmission']);
            });
        }
        
        if (!empty($filters['seats'])) {
            $query->whereHas('specifications', function($q) use ($filters) {
                $q->where('spec_name', 'seating_capacity')->where('spec_value', $filters['seats']);
            });
        }
        
        if (!empty($filters['engine_type'])) {
            $query->whereHas('specifications', function($q) use ($filters) {
                $q->where('spec_name', 'engine_type')->where('spec_value', $filters['engine_type']);
            });
        }
        
        if (!empty($filters['drivetrain'])) {
            $query->whereHas('specifications', function($q) use ($filters) {
                $q->where('spec_name', 'drivetrain')->where('spec_value', $filters['drivetrain']);
            });
        }
        
        if (!empty($filters['color'])) {
            $query->whereHas('colors', function($q) use ($filters) {
                $q->where('color_name', 'like', "%{$filters['color']}%");
            });
        }
        
        if (!empty($filters['features'])) {
            $features = array_filter(array_map('trim', explode(',', $filters['features'])));
            foreach ($features as $feature) {
                $query->whereHas('featuresRelation', function($q) use ($feature) {
                    $q->where('feature_name', 'like', "%{$feature}%");
                });
            }
        }
        
        // Apply sorting
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('current_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('current_price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->orderBy('created_at', 'desc');
                break;
            case 'rating':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $carVariants = $query->paginate($perPage);
        
        // Get filter options for the form
        $brands = CarBrand::orderBy('name')->pluck('name', 'id');
        $models = CarModel::orderBy('name')->pluck('name', 'id');
        $fuelTypes = \App\Models\CarSpecification::where('spec_name', 'fuel_type')->distinct()->pluck('spec_value')->filter();
        $transmissions = \App\Models\CarSpecification::where('spec_name', 'transmission')->distinct()->pluck('spec_value')->filter();
        $drivetrains = \App\Models\CarSpecification::where('spec_name', 'drivetrain')->distinct()->pluck('spec_value')->filter();
        
        return view('user.search.advanced', compact('carVariants', 'filters', 'brands', 'models', 'fuelTypes', 'transmissions', 'drivetrains'));
    }
    
    public function autocomplete(Request $request)
    {
        $query = trim($request->get('q', ''));
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $results = [];
        
        // Search car brands
        $brands = CarBrand::where('name', 'like', "%{$query}%")
            ->limit(3)
            ->get(['id', 'name'])
            ->map(function($brand) {
                return [
                    'id' => $brand->id,
                    'text' => $brand->name,
                    'type' => 'brand',
                    'url' => route('search.results', ['q' => $brand->name, 'category' => 'cars'])
                ];
            });
        
        // Search car models
        $models = CarModel::where('name', 'like', "%{$query}%")
            ->with('carBrand')
            ->limit(3)
            ->get()
            ->map(function($model) {
                return [
                    'id' => $model->id,
                    'text' => $model->carBrand->name . ' ' . $model->name,
                    'type' => 'model',
                    'url' => route('search.results', ['q' => $model->name, 'category' => 'cars'])
                ];
            });
        
        // Search car variants
        $variants = CarVariant::where('name', 'like', "%{$query}%")
            ->where('is_active', 1)
            ->with(['carModel.carBrand'])
            ->limit(3)
            ->get()
            ->map(function($variant) {
                return [
                    'id' => $variant->id,
                    'text' => $variant->carModel->carBrand->name . ' ' . $variant->name,
                    'type' => 'variant',
                    'url' => route('car-variants.show', $variant->id)
                ];
            });
        
        // Search accessories
        $accessories = Accessory::where('name', 'like', "%{$query}%")
            ->where('is_active', 1)
            ->limit(3)
            ->get(['id', 'name'])
            ->map(function($accessory) {
                return [
                    'id' => $accessory->id,
                    'text' => $accessory->name,
                    'type' => 'accessory',
                    'url' => route('accessories.show', $accessory->id)
                ];
            });
        
        $results = $brands->concat($models)->concat($variants)->concat($accessories);
        
        return response()->json($results->take(10)->values());
    }
} 