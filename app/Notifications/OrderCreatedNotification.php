<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
{
    public function __construct(
        public bool $isAdmin = false,
        public int $orderId = 0
    ) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->isAdmin
                ? __('ui.order_create1')
                : __('ui.order_create2'),

            'url' => $this->isAdmin
                ? route('admin.orders.index')
                : route('orders.index', ['tab' => 'proses']),

            'order_id' => $this->orderId,
            'type' => 'order_created'
        ];
    }
}


