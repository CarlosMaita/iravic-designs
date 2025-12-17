<?php

namespace App\Services;

use App\Models\Config;
use App\Models\Customer;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Payment;
use App\Mail\WelcomeCustomer;
use App\Mail\OrderCreatedNotification;
use App\Mail\PaymentConfirmedNotification;
use App\Mail\ShippingNotification;
use App\Mail\ReviewRequestNotification;
use App\Mail\AdminNewOrderNotification;
use App\Mail\AdminPaymentReceivedNotification;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Send welcome notification to customer
     */
    public function sendWelcomeNotification(Customer $customer)
    {
        // Create web notification
        Notification::create([
            'customer_id' => $customer->id,
            'type' => Notification::TYPE_WELCOME,
            'title' => '¡Bienvenido a Iravic Designs!',
            'message' => 'Gracias por registrarte. Explora nuestro catálogo y encuentra productos únicos.',
            'action_url' => route('ecommerce.home'),
        ]);

        // Send email
        Mail::to($customer->email)->send(new WelcomeCustomer($customer));
    }

    /**
     * Send order created notification to customer and admin
     */
    public function sendOrderCreatedNotification(Order $order)
    {
        $order->load(['customer', 'orderProducts']);

        // Create web notification for customer
        Notification::create([
            'customer_id' => $order->customer_id,
            'type' => Notification::TYPE_ORDER_CREATED,
            'title' => 'Orden creada exitosamente',
            'message' => "Tu orden #{$order->id} ha sido creada. Total: $" . number_format($order->total, 2),
            'action_url' => route('customer.orders.show', $order->id),
        ]);

        // Send email to customer
        Mail::to($order->customer->email)->send(new OrderCreatedNotification($order));

        // Send email to all admins with notify_new_order enabled
        $adminsToNotify = \App\Models\User::where('notify_new_order', true)->get();
        foreach ($adminsToNotify as $admin) {
            if (filter_var($admin->email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($admin->email)->send(new AdminNewOrderNotification($order));
            }
        }
    }

    /**
     * Send payment submitted notification to admin
     */
    public function sendPaymentSubmittedNotification(Payment $payment)
    {
        $payment->load(['customer', 'order']);

        // Create web notification for customer
        Notification::create([
            'customer_id' => $payment->customer_id,
            'type' => Notification::TYPE_PAYMENT_SUBMITTED,
            'title' => 'Pago registrado',
            'message' => "Tu pago de $" . number_format($payment->amount, 2) . " ha sido registrado y está pendiente de verificación.",
            'action_url' => route('customer.orders.show', $payment->order_id),
        ]);

        // Send email to all admins with notify_new_payment enabled
        $adminsToNotify = \App\Models\User::where('notify_new_payment', true)->get();
        foreach ($adminsToNotify as $admin) {
            if (filter_var($admin->email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($admin->email)->send(new AdminPaymentReceivedNotification($payment));
            }
        }
    }

    /**
     * Send payment confirmed notification to customer
     */
    public function sendPaymentConfirmedNotification(Payment $payment)
    {
        $payment->load(['customer', 'order']);

        // Create web notification for customer
        Notification::create([
            'customer_id' => $payment->customer_id,
            'type' => Notification::TYPE_PAYMENT_CONFIRMED,
            'title' => 'Pago confirmado',
            'message' => "Tu pago de $" . number_format($payment->amount, 2) . " ha sido verificado exitosamente.",
            'action_url' => route('customer.orders.show', $payment->order_id),
        ]);

        // Send email to customer
        Mail::to($payment->customer->email)->send(new PaymentConfirmedNotification($payment));
    }

    /**
     * Send shipping notification to customer
     */
    public function sendShippingNotification(Order $order)
    {
        $order->load(['customer']);

        // Create web notification for customer
        Notification::create([
            'customer_id' => $order->customer_id,
            'type' => Notification::TYPE_SHIPPED,
            'title' => 'Tu pedido ha sido enviado',
            'message' => "Tu orden #{$order->id} ha sido enviada" . 
                ($order->shipping_tracking_number ? " - Guía: {$order->shipping_tracking_number}" : ""),
            'action_url' => route('customer.orders.show', $order->id),
        ]);

        // Send email to customer
        Mail::to($order->customer->email)->send(new ShippingNotification($order));
    }

    /**
     * Send review request notification to customer
     */
    public function sendReviewRequestNotification(Order $order)
    {
        $order->load(['customer']);

        // Create web notification for customer
        Notification::create([
            'customer_id' => $order->customer_id,
            'type' => Notification::TYPE_REVIEW_REQUEST,
            'title' => '¿Cómo fue tu experiencia?',
            'message' => "Nos encantaría conocer tu opinión sobre tu pedido #{$order->id}",
            'action_url' => route('customer.orders.show', $order->id),
        ]);

        // Send email to customer
        Mail::to($order->customer->email)->send(new ReviewRequestNotification($order));
    }
}
