<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable=[
        'customerId',
        'createdBy',
        'statusId',
        'orderReferenceNumber',
        'counterId'
    ];

    public function User()
    {
        return $this->belongsTo(User::class,'createdBy');
    }

    public function OrderItems()
    {
        return $this->hasMany(OrderItem::class,'orderId');
    }

    public function Customer()
    {
        return $this->belongsTo(Customer::class,'customerId');
    }

    public function Status()
    {
        return $this->belongsTo(Status::class,'statusId');
    }

    public function Payments()
    {
        return $this->hasMany(Payment::class,'orderId');
    }

    public function Counter()
    {
        return $this->belongsTo(Counter::class,'counterId');
    }

    protected function total():Attribute
    {
        return Attribute::make(
            get:fn() => 123//$this->loadSum('order','price')

        );
    }
}
