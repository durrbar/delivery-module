<?php

namespace Modules\Delivery\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DeliveryCompleteNotification extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Delivery Completed')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your order #' . $this->order->id . ' has been successfully delivered.')
            ->line('We hope you enjoy your purchase!')
            ->action('View Order', url('/orders/' . $this->order->id))
            ->line('If you have any questions, feel free to contact us.');
    }
}
