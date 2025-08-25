<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Đơn hàng mới #' . $this->order->id)
            ->greeting('Xin chào Admin')
            ->line('Có đơn hàng mới từ khách hàng: ' . (optional($this->order->user)->name ?? 'Khách hàng'))
            ->line('Số điện thoại: ' . (optional($this->order->user)->phone ?? 'N/A'))
            ->line('Tổng tiền: ' . number_format($this->order->total_price) . ' VNĐ')
            ->action('Xem chi tiết đơn hàng', url('/admin/orders/' . $this->order->id))
            ->line('Vui lòng xử lý đơn hàng này sớm nhất có thể.');
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'customer_name' => optional($this->order->user)->name,
            'total_amount' => $this->order->total_price,
            'message' => 'Đơn hàng mới từ ' . (optional($this->order->user)->name ?? 'Khách hàng'),
            'type' => 'new_order'
        ];
    }
} 