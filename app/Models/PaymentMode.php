<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMode extends Model
{
    use HasFactory;

    protected $fillable =[
        'name',
        'createdBy'
    ];

    public function Payments()
    {
        return $this->hasMany(Payment::class,'paymentModeId');
    }
}
