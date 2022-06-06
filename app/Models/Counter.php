<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'warehouseId'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class,'warehouseId');
    }

    public function UserHasCounter()
    {
        return $this->hasMany(userHasCounter::class);
    }
}
