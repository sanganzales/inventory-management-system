<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable =[
        'orderId',
        'productId',
        'quantity'
    ];

    public function Order()
    {
        return $this->belongsTo(Order::class,'orderId');
    }

    public function Product()
    {
        return $this->belongsTo(Product::class,'productId');
    }

    protected function SubTotal():Attribute
    {

        return Attribute::make(
            get:fn() => $this->product? $this->quantity * $this->product->price: 0
        );
    }
}
