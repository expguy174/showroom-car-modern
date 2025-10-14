<?php

namespace App\Traits;

use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusChanged;
use App\Mail\PaymentStatusChanged;
use App\Mail\OrderCancelled;

trait SendsOrderNotifications
{
    /**
     * Send notification to user about order status change
     */
    protected function notifyOrderStatusChanged($order, $oldStatus, $newStatus)
    {
        $statusNames = [
            'pending' => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'shipping' => 'Đang giao hàng',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy'
        ];

        $title = "Đơn hàng #{$order->order_number}";
        $message = "Trạng thái đơn hàng đã chuyển sang: {$statusNames[$newStatus]}";

        // Create in-app notification
        Notification::create([
            'user_id' => $order->user_id,
            'type' => 'order_status',
            'title' => $title,
            'message' => $message,
            'is_read' => false,
        ]);

        // Send email notification
        if ($order->user && $order->user->email) {
            Mail::to($order->user->email)->send(new OrderStatusChanged($order, $oldStatus, $newStatus));
        }
    }

    /**
     * Send notification to user about payment status change
     */
    protected function notifyPaymentStatusChanged($order, $oldStatus, $newStatus)
    {
        $statusNames = [
            'pending' => 'Chờ thanh toán',
            'completed' => 'Đã thanh toán',
            'partial' => 'Thanh toán một phần',
            'failed' => 'Thất bại',
            'refunded' => 'Đã hoàn tiền'
        ];

        $title = "Đơn hàng #{$order->order_number}";
        $message = "Thanh toán đã được cập nhật: {$statusNames[$newStatus]}";

        // Create in-app notification
        Notification::create([
            'user_id' => $order->user_id,
            'type' => 'payment_status',
            'title' => $title,
            'message' => $message,
            'is_read' => false,
        ]);

        // Send email notification
        if ($order->user && $order->user->email) {
            Mail::to($order->user->email)->send(new PaymentStatusChanged($order, $oldStatus, $newStatus));
        }
    }

    /**
     * Send notification to user about tracking update
     */
    protected function notifyTrackingUpdated($order, $trackingNumber, $carrier = null)
    {
        $title = "Đơn hàng #{$order->order_number}";
        $message = "Đơn hàng đã được giao cho đơn vị vận chuyển. Mã vận đơn: {$trackingNumber}";

        Notification::create([
            'user_id' => $order->user_id,
            'type' => 'tracking_update',
            'title' => $title,
            'message' => $message,
            'is_read' => false,
        ]);
    }

    /**
     * Send notification to user about installments created
     */
    protected function notifyInstallmentsCreated($order, $totalInstallments, $monthlyAmount)
    {
        $title = "Đơn hàng #{$order->order_number}";
        $message = "Lịch trả góp {$totalInstallments} kỳ đã được tạo. Mỗi kỳ: " . number_format($monthlyAmount) . " VNĐ";

        Notification::create([
            'user_id' => $order->user_id,
            'type' => 'installment',
            'title' => $title,
            'message' => $message,
            'is_read' => false,
        ]);
    }

    /**
     * Send notification to user about refund
     */
    protected function notifyRefund($order, $refundAmount, $refundType, $reason = null)
    {
        $typeText = $refundType === 'full' ? 'toàn bộ' : 'một phần';
        $title = "Đơn hàng #{$order->order_number}";
        $message = "Đã hoàn {$typeText} số tiền " . number_format($refundAmount) . " VNĐ";
        
        if ($reason) {
            $message .= ". Lý do: {$reason}";
        }

        Notification::create([
            'user_id' => $order->user_id,
            'type' => 'refund',
            'title' => $title,
            'message' => $message,
            'is_read' => false,
        ]);
    }

    /**
     * Send notification to user about order cancellation
     */
    protected function notifyOrderCancelled($order, $reason = null)
    {
        $title = "Đơn hàng #{$order->order_number}";
        $message = "Đơn hàng đã bị hủy";
        
        if ($reason) {
            $message .= ". Lý do: {$reason}";
        }

        // Create in-app notification
        Notification::create([
            'user_id' => $order->user_id,
            'type' => 'order_cancelled',
            'title' => $title,
            'message' => $message,
            'is_read' => false,
        ]);

        // Send email notification
        if ($order->user && $order->user->email) {
            Mail::to($order->user->email)->send(new OrderCancelled($order, $reason));
        }
    }
}
