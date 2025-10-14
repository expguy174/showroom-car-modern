<?php

namespace App\Mail;

use App\Models\Installment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InstallmentPaid extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $installment;
    public $isLastInstallment;

    public function __construct(Installment $installment, $isLastInstallment = false)
    {
        $this->installment = $installment;
        $this->isLastInstallment = $isLastInstallment;
    }

    public function envelope()
    {
        $subject = $this->isLastInstallment
            ? 'Hoàn thành trả góp - Đơn hàng #' . $this->installment->order->order_number
            : 'Xác nhận thanh toán kỳ ' . $this->installment->installment_number . ' - Đơn hàng #' . $this->installment->order->order_number;

        return new Envelope(
            subject: $subject,
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.installment-paid',
            with: [
                'installment' => $this->installment,
                'isLastInstallment' => $this->isLastInstallment,
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}
