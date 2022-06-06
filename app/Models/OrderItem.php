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
        'itemId',
        'quantity'
    ];

    public function Order()
    {
        return $this->belongsTo(Order::class,'orderId');
    }

    public function Item()
    {
        return $this->belongsTo(Item::class,'itemId');
    }

    protected function SubTotal():Attribute
    {

        return Attribute::make(
            get:fn() => $this->item? $this->quantity * $this->item->price: 0
        );
    }
}
