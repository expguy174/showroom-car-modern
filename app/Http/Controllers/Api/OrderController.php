<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $orders = Order::with(['items', 'paymentTransactions', 'installments'])
            ->when($user, function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->orderByDesc('created_at')
            ->paginate(15);

        return OrderResource::collection($orders);
    }

    public function show(Request $request, Order $order)
    {
        $user = $request->user();
        if ($user && $order->user_id !== $user->id) {
            abort(403);
        }
        $order->load(['items', 'paymentTransactions', 'installments', 'billingAddress', 'shippingAddress']);
        return new OrderResource($order);
    }
}


