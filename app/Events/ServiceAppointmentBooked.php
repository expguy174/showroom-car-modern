<?php

namespace App\Events;

use App\Models\ServiceAppointment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServiceAppointmentBooked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ServiceAppointment $appointment;

    public function __construct(ServiceAppointment $appointment)
    {
        $this->appointment = $appointment;
    }
}


