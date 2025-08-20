<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderLogController extends Controller
{
    public function index(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $query = $order->logs()->with('user')->orderByDesc('created_at');

        if ($request->filled('action')) {
            $query->where('action', $request->get('action'));
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->get('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->get('to'));
        }

        if ($request->filled('user')) {
            $user = $request->get('user');
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user)
                  ->orWhereHas('user', function ($uq) use ($user) {
                      $uq->where('name', 'like', "%{$user}%");
                  });
            });
        }

        $logs = $query->paginate(20);

        return view('admin.orders.logs', compact('order', 'logs'));
    }

    public function export(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $query = $order->logs()->with('user')->orderByDesc('created_at');

        if ($request->filled('action')) {
            $query->where('action', $request->get('action'));
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->get('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->get('to'));
        }
        if ($request->filled('user')) {
            $user = $request->get('user');
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user)
                  ->orWhereHas('user', function ($uq) use ($user) {
                      $uq->where('name', 'like', "%{$user}%");
                  });
            });
        }

        $logs = $query->get();
        $filename = 'order_' . ($order->order_number ?? $order->id) . '_logs.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($logs) {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['time', 'action', 'user', 'details', 'ip']);
            foreach ($logs as $log) {
                fputcsv($output, [
                    $log->created_at,
                    $log->action,
                    optional($log->user)->name,
                    json_encode($log->details, JSON_UNESCAPED_UNICODE),
                    $log->ip_address,
                ]);
            }
            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }
}