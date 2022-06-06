<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable=[
        'orderId',
        'transactionId',
        'paymentModeId',
        'amount',
        'createdBy',
    ];

    public function Order()
    {
        return $this->belongsTo(Order::class,'orderId');
    }

    public function PaymentModes()
    {
        return $this->belongsTo(PaymentMode::class,'paymentModeId');
    }

    public function Transaction()
    {
        return $this->belongsTo(Transaction::class,'transactionId');
    }




}
