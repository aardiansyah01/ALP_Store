<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Category;

class Product extends Model
{
    protected $casts = [
        'sizes' => 'array',
    ];

    protected $fillable = [
        'name',
        'price',
        'description',
        'color',
        'category_id',
        'image',
        'stock',
        'location',
        'sizes',
    ];

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

}


