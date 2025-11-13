<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
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
        // Disable Laravel's default email verification listener
        // We send email verification manually in RegisteredUserController
        // Registered::class => [
        //     SendEmailVerificationNotification::class,
        // ],
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
        
        // Disable Laravel's default SendEmailVerificationNotification listener
        // We handle email verification manually in RegisteredUserController
        Event::forget(Registered::class);
    }
}


