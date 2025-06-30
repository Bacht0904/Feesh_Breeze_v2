<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\productdetail;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'product_detail_id',
        'quantity',
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productdetail()
    {
        return $this->belongsTo(Product_details::class);
    }
}
