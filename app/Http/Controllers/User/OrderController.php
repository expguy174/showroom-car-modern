<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['items.item', 'shippingAddress', 'billingAddress', 'paymentMethod'])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('user.orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order)
    {
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }
        $order->load(['items.item', 'items.color', 'paymentMethod', 'billingAddress', 'shippingAddress']);
        return view('user.orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        // Placeholder: this keeps route compatibility; real checkout logic lives in CartController
        abort(404);
    }
}


