<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'receiver_name',
        'email',
        'phone',
        'address',
        'city',
        'village',
        'dusun',
        'rt',
        'rw',
        'shipping_service',
        'shipping_cost',
        'payment_method',
        'subtotal',
        'total',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}