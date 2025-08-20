<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarVariant;
use App\Application\Orders\UseCases\PlaceOrder;
use Illuminate\Support\Facades\Auth;

class UserOrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'fullName' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'variant' => 'required|exists:car_variants,id',
            'color' => 'required|exists:car_variant_colors,id',
            'additionalNotes' => 'nullable|string|max:1000',
        ]);

        $variant = CarVariant::findOrFail($request->variant);

        $useCase = app(PlaceOrder::class);
        $order = $useCase->handle([
            'user_id' => Auth::id(),
            'name' => $request->fullName,
            'phone' => $request->phone,
            'email' => $request->email,
            'note' => $request->additionalNotes,
            'address' => '',
            'items' => [[
                'item_type' => 'car_variant',
                'item_id' => $variant->id,
                'color_id' => $request->color,
                'quantity' => 1,
            ]],
        ]);

        return redirect()->back()->with('success', 'Đặt hàng thành công!');
    }
}