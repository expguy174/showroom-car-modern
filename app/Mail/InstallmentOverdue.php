<?php

namespace App\Mail;

use App\Models\Installment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class InstallmentOverdue extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $installment;
    public $daysOverdue;

    public function __construct(Installment $installment, $daysOverdue)
    {
        $this->installment = $installment;
        $this->daysOverdue = $daysOverdue;
    }

    public function envelope()
    {
        return new Envelope(
            subject: '⚠️ Cảnh báo: Kỳ ' . $this->installment->installment_number . ' đã quá hạn - Đơn hàng #' . $this->installment->order->order_number,
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.installment-overdue',
            with: [
                'installment' => $this->installment,
                'daysOverdue' => $this->daysOverdue,
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}
