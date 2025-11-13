<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    /**
     * Get admin notifications (user_id = null)
     * Returns all notifications (both read and unread), sorted by: unread first, then by created_at desc
     */
    public function index(Request $request)
    {
        // Get all notifications, sorted: unread first, then by created_at desc
        $notifications = Notification::whereNull('user_id')
            ->orderBy('is_read', 'asc') // false (unread) comes before true (read)
            ->orderBy('created_at', 'desc')
            ->limit(100) // Increased limit to show more notifications
            ->get();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'notifications' => $notifications->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'type' => $notification->type,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'is_read' => $notification->is_read,
                        'created_at' => $notification->created_at->diffForHumans(),
                        'icon' => $notification->icon,
                        'color' => $notification->color,
                    ];
                }),
                'unread_count' => Notification::whereNull('user_id')->where('is_read', false)->count(),
                'total_count' => Notification::whereNull('user_id')->count(),
            ]);
        }

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Get unread count
     */
    public function unreadCount()
    {
        $count = Cache::remember('admin_notifications_unread_count', 60, function () {
            return Notification::whereNull('user_id')
                ->where('is_read', false)
                ->count();
        });

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::whereNull('user_id')->findOrFail($id);
        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        // Clear cache
        Cache::forget('admin_notifications_unread_count');

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
        ]);
    }

    /**
     * Mark all as read
     */
    public function markAllAsRead()
    {
        Notification::whereNull('user_id')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        // Clear cache
        Cache::forget('admin_notifications_unread_count');

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
        ]);
    }

    /**
     * Delete a single notification
     */
    public function destroy($id)
    {
        $notification = Notification::whereNull('user_id')->findOrFail($id);
        $notification->delete();

        // Clear cache
        Cache::forget('admin_notifications_unread_count');

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted',
        ]);
    }

    /**
     * Delete all notifications
     */
    public function deleteAll()
    {
        Notification::whereNull('user_id')->delete();

        // Clear cache
        Cache::forget('admin_notifications_unread_count');

        return response()->json([
            'success' => true,
            'message' => 'All notifications deleted',
        ]);
    }

    /**
     * Delete all read notifications
     */
    public function deleteRead()
    {
        Notification::whereNull('user_id')
            ->where('is_read', true)
            ->delete();

        // Clear cache
        Cache::forget('admin_notifications_unread_count');

        return response()->json([
            'success' => true,
            'message' => 'All read notifications deleted',
        ]);
    }
}

