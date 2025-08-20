<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;
    public $status;

    public function __construct(Order $order, $status)
    {
        $this->order = $order;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $statusText = [
            'pending' => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'shipping' => 'Đang giao hàng',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy'
        ];

        return (new MailMessage)
            ->subject('Cập nhật trạng thái đơn hàng #' . $this->order->id)
            ->greeting('Xin chào ' . $notifiable->name)
            ->line('Đơn hàng #' . $this->order->id . ' của bạn đã được cập nhật trạng thái.')
            ->line('Trạng thái mới: ' . ($statusText[$this->status] ?? $this->status))
            ->action('Xem chi tiết đơn hàng', url('/orders/' . $this->order->id))
            ->line('Cảm ơn bạn đã mua hàng tại Showroom Car!');
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'status' => $this->status,
            'message' => 'Đơn hàng #' . $this->order->id . ' đã được cập nhật trạng thái thành ' . $this->status,
            'type' => 'order_status'
        ];
    }
} 