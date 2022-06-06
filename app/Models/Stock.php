<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $fillable =[
        'productId',
        'quantity',
        'warehouseId',
        'createdBy',

    ];

    public function Product()
    {
        return $this->belongsTo(Product::class,'productId');
    }
}
