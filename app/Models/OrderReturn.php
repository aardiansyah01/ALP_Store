<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReturn extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'reason',
        'description',
        'receipt_image',
        'unboxing_video',
        'status',
    ];

    /* Relasi*/

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderReturnItem::class);
    }
}
