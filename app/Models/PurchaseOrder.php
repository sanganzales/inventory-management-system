<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchaseNumber',
        'createdBy',
        'amount',
        'purchaseStatusId',
        'warehouseId',
        'supplierId',

    ];

    public function Warehouse()
    {
        return $this->belongsTo(Warehouse::class,'warehouseId');

    }

    public function PurchaseItems()
    {
        return $this->hasMany(PurchaseOrderItem::class,'purchaseOrderId');
    }

    public function Status()
    {
        return $this->belongsTo(Status::class,'purchaseStatusId');
    }

    public function Payments()
    {
        return $this->hasMany(Payment::class,'orderId');
    }

    public function Supplier()
    {
        return $this->belongsTo(Supplier::class,'supplierId');
    }
}
