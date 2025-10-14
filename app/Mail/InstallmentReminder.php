<?php

namespace App\Mail;

use App\Models\Installment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InstallmentReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $installment;
    public $daysUntilDue;

    public function __construct(Installment $installment, $daysUntilDue = 3)
    {
        $this->installment = $installment;
        $this->daysUntilDue = $daysUntilDue;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Nhắc nhở: Kỳ ' . $this->installment->installment_number . ' sắp đến hạn - Đơn hàng #' . $this->installment->order->order_number,
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.installment-reminder',
            with: [
                'installment' => $this->installment,
                'daysUntilDue' => $this->daysUntilDue,
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}
