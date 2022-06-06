<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
    protected $fillable = [
        'names',
        'userId',
        'createdBy'
    ];

    public function User()
    {
        return $this->belongsTo(User::class,'userId');
    }

    public function Stocks()
    {
        return $this->hasMany(Stock::class,'warehouseId');
    }

    public function counters()
    {
        return $this->hasOne(Counter::class);
    }
}
