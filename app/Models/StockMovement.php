<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable=[
        'createdBy',
        'sourceWarehouseId'
    ];


    public function details()
    {
        return $this->hasMany(StockMovementDetail::class,'stockMovementId');
    }

    public function User()
    {
        return $this->belongsTo(User::class,'createdBy');
    }

    public function Source()
    {
        return $this->belongsTo(Warehouse::class,'sourceWarehouseId');
    }

    public function Destination()
    {
        return $this->belongsTo(Warehouse::class,'destinationWarehouseId');
    }
}
