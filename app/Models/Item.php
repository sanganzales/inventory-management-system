<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable =[
        'productId',
        'price',
        'discount',
        'createdBy'
    ];

    public function Product()
    {
        return $this->belongsTo(Product::class,'productId');
    }


    public function OrderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
