<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $fillable = [ 'id','photo','price','discount_price','discount_end_data','visible','companyId'];
    use HasFactory;
    public function product_translation($lang)
    {
        return $this->hasMany(Product_translation::class,'productId')->where('lang','=',$lang)->select('title');
    }
    public function product_translation_all()
    {
        return $this->hasMany(Product_translation::class,'productId');
    }
    
    public function order_details()
    {
        return $this->hasMany(Order_details::class,'productId');
    }
}
