<?php

namespace App\Mail;

use App\Models\ServiceAppointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ServiceAppointmentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public ServiceAppointment $appointment;

    public function __construct(ServiceAppointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function build()
    {
        return $this->subject('Xác nhận lịch hẹn dịch vụ #' . $this->appointment->appointment_number)
            ->view('emails.service-appointment-confirmation')
            ->with([
                'appointment' => $this->appointment,
            ]);
    }
}


