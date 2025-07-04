<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderDetail;

class Order extends Model
{
    protected $fillable = [
        'id_user', 'id_payment', 'id_shipping', 'order_date',
        'suptotal', 'payment_method', 'payment_status',
        'name', 'phone', 'address', 'email', 'note',
        'coupon_code', 'coupon_discount', 'shipping_fee', 'total', 'status',
    ];

    public function details()
    {
        return $this->hasMany(OrderDetail::class, "order_id");
    }
}

