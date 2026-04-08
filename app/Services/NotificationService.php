<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public static function send(int $userId, string $type, string $title, string $message, array $data = []): void
    {
        Notification::create([
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'data'    => $data ?: null,
        ]);
    }

    public static function sendToAdmins(string $type, string $title, string $message, array $data = []): void
    {
        User::where('role', 'admin')->pluck('id')->each(function ($id) use ($type, $title, $message, $data) {
            self::send($id, $type, $title, $message, $data);
        });
    }

    // Order placed — notify seller + admin
    public static function orderPlaced($order): void
    {
        // Notify customer
        self::send($order->user_id, 'success', 'Order Placed', "Your order #{$order->order_number} has been placed successfully.", ['url' => "/orders/{$order->id}"]);

        // Notify vendor
        if ($order->vendor_id) {
            self::send($order->vendor_id, 'info', 'New Order Received', "You have a new order #{$order->order_number}.", ['url' => "/vendor/orders"]);
        }

        // Notify admins
        self::sendToAdmins('info', 'New Order', "Order #{$order->order_number} placed by {$order->user->name}.", ['url' => "/admin/orders/{$order->id}"]);
    }

    // Order status changed — notify customer
    public static function orderStatusChanged($order): void
    {
        self::send($order->user_id, 'info', 'Order Status Updated', "Your order #{$order->order_number} is now " . ucfirst($order->status) . ".", ['url' => "/orders/{$order->id}"]);
    }

    // New ticket — notify admins
    public static function ticketCreated($ticket): void
    {
        self::sendToAdmins('warning', 'New Support Ticket', "Ticket #{$ticket->ticket_number}: {$ticket->subject}", ['url' => "/admin/tickets/{$ticket->id}"]);
    }

    // Ticket reply — notify ticket owner + admin
    public static function ticketReplied($ticket, $replierUser): void
    {
        $isAdminReply = in_array($replierUser->role, ['admin', 'employee']);

        if ($isAdminReply) {
            // Notify ticket owner
            self::send($ticket->user_id, 'info', 'Ticket Reply', "Admin replied to your ticket #{$ticket->ticket_number}.", ['url' => "/tickets/{$ticket->id}"]);
        } else {
            // Notify admins
            self::sendToAdmins('warning', 'Ticket Reply', "{$replierUser->name} replied to ticket #{$ticket->ticket_number}.", ['url' => "/admin/tickets/{$ticket->id}"]);
        }
    }

    // Payment confirmed
    public static function paymentConfirmed($order): void
    {
        self::send($order->user_id, 'success', 'Payment Confirmed', "Payment for order #{$order->order_number} has been confirmed.", ['url' => "/orders/{$order->id}"]);
    }

    // Advance payment approved/rejected
    public static function advancePaymentStatus($advancePayment, string $status): void
    {
        $type = $status === 'approved' ? 'success' : 'error';
        self::send($advancePayment->user_id, $type, 'Advance Payment ' . ucfirst($status), "Your advance payment request has been {$status}.", ['url' => "/my-orders"]);
    }

    // Withdrawal request
    public static function withdrawalStatus($withdrawal, string $status): void
    {
        $type = $status === 'approved' ? 'success' : 'error';
        self::send($withdrawal->vendor_id, $type, 'Withdrawal ' . ucfirst($status), "Your withdrawal request of ৳{$withdrawal->amount} has been {$status}.", ['url' => "/wallet"]);
    }
}
