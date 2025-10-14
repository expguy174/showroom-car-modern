<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduled Tasks
Schedule::command('installments:check-overdue')
    ->dailyAt('08:00')
    ->timezone('Asia/Ho_Chi_Minh')
    ->withoutOverlapping()
    ->onSuccess(function () {
        \Log::info('Overdue installments check completed successfully');
    })
    ->onFailure(function () {
        \Log::error('Overdue installments check failed');
    });

Schedule::command('installments:remind-upcoming')
    ->dailyAt('09:00')
    ->timezone('Asia/Ho_Chi_Minh')
    ->withoutOverlapping()
    ->onSuccess(function () {
        \Log::info('Installment reminders sent successfully');
    })
    ->onFailure(function () {
        \Log::error('Installment reminders failed');
    });
