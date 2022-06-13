<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        //'categoryId',
        'createdBy',
        'brandId',
        'barcode',
        'security_stock',
        'price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function Brand()
    {
        return $this->belongsTo(Brand::class,'brandId');
    }

    public function Categories()
    {
        return $this->belongsToMany(Category::class,'category_product', 'categoryId', 'productId');
    }

    public function Stock()
    {
        return $this->hasOne(Stock::class,'productId');
    }


    public function OrderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function PurchaseOrderItem()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
