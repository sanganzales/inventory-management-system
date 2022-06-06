<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'summary',
        'content',
        'categoryId',
        'subCategoryId',
        'createdBy'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function Category()
    {
        return $this->belongsTo(Category::class,'categoryId');
    }

    public function subCategory()
    {
        return $this->belongsTo(Category::class,'subCategoryId');
    }

    public function Stock()
    {
        return $this->hasOne(Stock::class,'productId');
    }

    public function Items()
    {
        return $this->hasMany(Item::class);
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
