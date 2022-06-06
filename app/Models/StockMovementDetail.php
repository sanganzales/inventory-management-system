<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovementDetail extends Model
{
    use HasFactory;

    protected $fillable=[
        'stockMovementId',
        'productId',
        'quantity'
    ];

    public function StockMovements()
    {
        return $this->belongsTo(StockMovement::class,'stockMovementId');
    }

    public function Product()
    {
        return $this->belongsTo(Product::class,'productId');
    }
}
