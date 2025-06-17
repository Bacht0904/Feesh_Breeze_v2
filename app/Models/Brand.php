<?php

<<<<<<< HEAD

namespace App\Models;

=======
namespace App\Models;
>>>>>>> c003e191de1f040138947f86640739e312b1938e

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'slug', 
        'image',
        'status',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }


}
