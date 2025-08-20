<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\OrderCreated;
use App\Listeners\SendOrderCreatedNotifications;
use App\Listeners\LogOrderCreated;
use App\Events\TestDriveBooked;
use App\Listeners\SendTestDriveBookedNotifications;
use App\Events\PaymentProcessed;
use App\Listeners\HandlePaymentProcessed;
use App\Events\ServiceAppointmentBooked;
use App\Listeners\SendServiceAppointmentNotifications;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderCreated::class => [
            SendOrderCreatedNotifications::class,
            LogOrderCreated::class,
        ],
        TestDriveBooked::class => [
            SendTestDriveBookedNotifications::class,
        ],
        PaymentProcessed::class => [
            HandlePaymentProcessed::class,
        ],
        ServiceAppointmentBooked::class => [
            SendServiceAppointmentNotifications::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}


