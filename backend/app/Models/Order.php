<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'orders';

    protected $fillable = [
        'order_number',
        'user_id',
        'items',           // array of {product_id, name, price, qty, image}
        'subtotal',
        'discount',
        'shipping_fee',
        'total',
        'status',          // pending, confirmed, shipping, delivered, cancelled
        'payment_method',  // cash, aba, wing, bakong
        'payment_status',  // unpaid, paid
        'shipping_address',
        'note',
    ];

    protected $casts = [
        'items'            => 'array',
        'shipping_address' => 'array',
        'subtotal'         => 'float',
        'discount'         => 'float',
        'shipping_fee'     => 'float',
        'total'            => 'float',
    ];

    protected $attributes = [
        'status'         => 'pending',
        'payment_status' => 'unpaid',
        'discount'       => 0,
        'shipping_fee'   => 0,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $order->order_number = 'ORD-' . strtoupper(uniqid());
        });
    }
}
