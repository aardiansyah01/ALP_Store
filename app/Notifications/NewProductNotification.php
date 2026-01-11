<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewProductNotification extends Notification
{
    use Queueable;

    public function __construct(public $product) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => __('ui.new_product'),
            'url'     => route('products.show', $this->product->id),
        ];
    }
}

