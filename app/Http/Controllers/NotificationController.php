<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Notification as NotificationModel;
use App\Models\User as UserModel;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        /** @var UserModel $user */
        $user = Auth::user();
        
        // Cache key for user notifications
        $cacheKey = "user_notifications_{$user->id}_page_" . ($request->get('page', 1));
        
        // Try to get from cache first (5 minutes)
        $notifications = Cache::remember($cacheKey, 300, function() use ($user) {
            return $user->notifications()
                ->select(['id', 'type', 'title', 'message', 'is_read', 'created_at'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $notifications,
            ]);
        }

        // Pass data to view to avoid extra AJAX call
        return view('user.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        /** @var UserModel $user */
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->update(['is_read' => true]);

        // Clear cache for this user
        $this->clearUserNotificationCache($user->id);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    public function markAllAsRead()
    {
        /** @var UserModel $user */
        $user = Auth::user();
        $user->notifications()->where('is_read', false)->update(['is_read' => true]);

        // Clear cache for this user
        $this->clearUserNotificationCache($user->id);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    public function unreadCount()
    {
        /** @var UserModel $user */
        $user = Auth::user();
        $count = $user->notifications()->where('is_read', false)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => $count
            ]
        ]);
    }

    public function delete($id)
    {
        /** @var UserModel $user */
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->delete();

        // Clear cache for this user
        $this->clearUserNotificationCache($user->id);

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted'
        ]);
    }

    /**
     * Bulk delete all notifications for the current user
     */
    public function deleteAll()
    {
        /** @var UserModel $user */
        $user = Auth::user();
        $user->notifications()->delete();

        // Clear cache for this user
        $this->clearUserNotificationCache($user->id);

        return response()->json([
            'success' => true,
            'message' => 'All notifications deleted'
        ]);
    }

    /**
     * Clear notification cache for a specific user
     */
    private function clearUserNotificationCache($userId)
    {
        // Clear all pages for this user
        for ($page = 1; $page <= 10; $page++) {
            Cache::forget("user_notifications_{$userId}_page_{$page}");
        }
    }
} 