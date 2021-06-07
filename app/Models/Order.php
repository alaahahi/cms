<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Order extends Model
{
    protected $table = 'order';
    use HasFactory;
    public function order_details()
    {
        return $this->hasMany(Order_details::class,'orderId');
    }
    public function product()
    {
        return $this->belongsToMany(Product::class,'order_details','orderId','productId')->withPivot('quantity');
    }
    public function users()
    {
        return $this->belongsTo(Users::class,'userId');
    }
  

}
