<?php

namespace App\Services;

use App\Mail\OrderConfirmation;
use App\Mail\TestDriveConfirmation;
use App\Mail\ServiceAppointmentConfirmation;
use App\Mail\QuoteRequestConfirmation;
use App\Models\Order;
use App\Models\TestDrive;
use App\Models\Lead;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    public function sendOrderConfirmation(Order $order)
    {
        try {
            if ($order->email) {
                Mail::to($order->email)->send(new OrderConfirmation($order));
                Log::info('Order confirmation email sent', ['order_id' => $order->id, 'email' => $order->email]);
                return true;
            }
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email', [
                'order_id' => $order->id,
                'email' => $order->email,
                'error' => $e->getMessage()
            ]);
        }
        return false;
    }

    public function sendTestDriveConfirmation(TestDrive $testDrive)
    {
        try {
            if ($testDrive->email) {
                Mail::to($testDrive->email)->send(new TestDriveConfirmation($testDrive));
                Log::info('Test drive confirmation email sent', ['test_drive_id' => $testDrive->id, 'email' => $testDrive->email]);
                return true;
            }
        } catch (\Exception $e) {
            Log::error('Failed to send test drive confirmation email', [
                'test_drive_id' => $testDrive->id,
                'email' => $testDrive->email,
                'error' => $e->getMessage()
            ]);
        }
        return false;
    }

    public function sendQuoteRequestConfirmation(Lead $lead)
    {
        try {
            if ($lead->email) {
                Mail::to($lead->email)->send(new QuoteRequestConfirmation($lead));
                Log::info('Quote request confirmation email sent', ['lead_id' => $lead->id, 'email' => $lead->email]);
                return true;
            }
        } catch (\Exception $e) {
            Log::error('Failed to send quote request confirmation email', [
                'lead_id' => $lead->id,
                'email' => $lead->email,
                'error' => $e->getMessage()
            ]);
        }
        return false;
    }

    public function sendServiceAppointmentConfirmation(\App\Models\ServiceAppointment $appointment)
    {
        try {
            if ($appointment->customer_email) {
                Mail::to($appointment->customer_email)->send(new ServiceAppointmentConfirmation($appointment));
                Log::info('Service appointment confirmation email sent', [
                    'appointment_id' => $appointment->id,
                    'email' => $appointment->customer_email
                ]);
                return true;
            }
        } catch (\Exception $e) {
            Log::error('Failed to send service appointment confirmation email', [
                'appointment_id' => $appointment->id,
                'email' => $appointment->customer_email,
                'error' => $e->getMessage()
            ]);
        }
        return false;
    }

    public function sendAdminNotification($subject, $message, $data = [])
    {
        try {
            $adminEmail = config('mail.admin_email', 'admin@showroomcar.com');
            
            Mail::raw($message, function ($message) use ($subject, $adminEmail) {
                $message->to($adminEmail)
                        ->subject($subject);
            });
            
            Log::info('Admin notification sent', ['subject' => $subject, 'email' => $adminEmail]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send admin notification', [
                'subject' => $subject,
                'error' => $e->getMessage()
            ]);
        }
        return false;
    }
} 