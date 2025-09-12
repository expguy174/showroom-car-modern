<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification as NotificationModel;
use App\Models\User as UserModel;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        /** @var UserModel $user */
        $user = Auth::user();
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->paginate(10);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $notifications,
            ]);
        }

        return view('user.notifications.index');
    }

    public function markAsRead($id)
    {
        /** @var UserModel $user */
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->update(['is_read' => true]);

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

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted'
        ]);
    }
} 