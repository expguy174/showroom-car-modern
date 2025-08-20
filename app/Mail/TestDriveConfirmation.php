<?php

namespace App\Mail;

use App\Models\TestDrive;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestDriveConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $testDrive;

    public function __construct(TestDrive $testDrive)
    {
        $this->testDrive = $testDrive;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Xác nhận đặt lịch lái thử xe',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.test-drive-confirmation',
            with: [
                'testDrive' => $this->testDrive,
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
} 