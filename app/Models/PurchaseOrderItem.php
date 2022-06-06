<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchaseOrderId',
        'productId',
        'price',
        'quantity',
        'total'

    ];

    public function PurchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class,'purchaseOrderId');
    }

    public function Product()
    {
        return $this->belongsTo(Product::class,'productId');
    }

}
