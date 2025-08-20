<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
    // GET /api/v1/inventory/count?variant_ids[]=1&variant_ids[]=2
    public function count(Request $request)
    {
        $ids = (array) $request->get('variant_ids', []);
        $ids = array_values(array_filter(array_map('intval', $ids)));
        if (empty($ids)) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $rows = Inventory::query()
            ->selectRaw('car_variant_id, SUM(CASE WHEN status = "available" THEN 1 ELSE 0 END) as available_count')
            ->whereIn('car_variant_id', $ids)
            ->groupBy('car_variant_id')
            ->get();

        $map = [];
        foreach ($rows as $r) {
            $map[(int) $r->car_variant_id] = (int) $r->available_count;
        }
        return response()->json(['success' => true, 'data' => $map]);
    }

    // GET /api/v1/inventory/by-variant?variant_id=1
    public function byVariant(Request $request)
    {
        $variantId = (int) $request->get('variant_id');
        if (!$variantId) {
            return response()->json(['success' => false, 'message' => 'variant_id is required'], 422);
        }

        $units = Inventory::query()
            ->with(['showroom', 'carVariant.carModel.carBrand'])
            ->where('car_variant_id', $variantId)
            ->where('status', 'available')
            ->orderByDesc('is_featured')
            ->orderBy('selling_price')
            ->limit(50)
            ->get()
            ->map(function ($u) {
                return [
                    'id' => $u->id,
                    'vin' => $u->vin,
                    'color' => $u->color,
                    'selling_price' => (float) $u->selling_price,
                    'showroom' => optional($u->showroom)->name,
                    'location' => optional($u->showroom)->address,
                    'status' => $u->status,
                    'status_display' => $u->status_display,
                ];
            });

        return response()->json(['success' => true, 'data' => $units]);
    }
}


