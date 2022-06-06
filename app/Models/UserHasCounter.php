<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHasCounter extends Model
{
    use HasFactory;

    protected $fillable=[
        'userId',
        'counterId'
    ];

    public function User()
    {
        return $this->belongsTo(User::class,'userId');
    }

    public function Counter()
    {
        return $this->belongsTo(Counter::class,'counterId');
    }
}
