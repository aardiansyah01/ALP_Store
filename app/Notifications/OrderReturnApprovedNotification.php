<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderReturnApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public int $orderId = 0
    ) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => __('ui.order_refund_acepted'),

            'url' => route('orders.index', [
                'tab' => 'dikembalikan'
            ]),

            'order_id' => $this->orderId,
            'type' => 'return_approved'
        ];
    }
}
