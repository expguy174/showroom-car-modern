<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentStatusChanged extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;
    public $oldStatus;
    public $newStatus;

    public function __construct(Order $order, $oldStatus, $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function envelope()
    {
        $statusLabels = [
            'pending' => 'Chờ thanh toán',
            'processing' => 'Đang xử lý',
            'partial' => 'Thanh toán một phần',
            'completed' => 'Đã thanh toán',
            'failed' => 'Thanh toán thất bại',
            'refunded' => 'Đã hoàn tiền',
            'cancelled' => 'Đã hủy',
        ];

        return new Envelope(
            subject: 'Cập nhật thanh toán - Đơn hàng #' . $this->order->order_number . ' - ' . ($statusLabels[$this->newStatus] ?? $this->newStatus),
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.payment-status-changed',
            with: [
                'order' => $this->order,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}
