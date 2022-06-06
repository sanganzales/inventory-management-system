<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'names',
        'phone',
        'address',
        'createdBy'
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function PurchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
