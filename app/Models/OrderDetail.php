<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id', 'product_detail_id',
        'product_name', 'size', 'color',
        'price', 'quantity',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
