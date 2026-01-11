<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderCancelledNotification extends Notification
{
    use Queueable;

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
            'type'     => 'order_cancelled',
            'order_id' => $this->orderId,
            'message'  => $this->isAdmin
                ? __('ui.order_cancelled2')
                : __('ui.order_cancelled1'),
            'url' => $this->isAdmin
                ? route('admin.orders.index', ['tab' => 'dibatalkan'])
                : route('orders.index', ['tab' => 'dibatalkan']),
        ];
    }
}

