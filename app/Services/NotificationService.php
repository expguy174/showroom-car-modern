<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class NotificationService
{
    public function send(int $userId, string $type, string $title, string $message): ?Notification
    {
        try {
            $notification = Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'is_read' => false,
            ]);

            // Clear notification cache for this user
            $this->clearUserNotificationCache($userId);

            return $notification;
        } catch (\Exception $e) {
            Log::error('Failed to create notification', [
                'user_id' => $userId,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Clear notification cache for a specific user
     */
    private function clearUserNotificationCache(int $userId): void
    {
        // Clear all pages for this user
        for ($page = 1; $page <= 10; $page++) {
            Cache::forget("user_notifications_{$userId}_page_{$page}");
        }
    }
}
