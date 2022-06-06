<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'roleId'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function canAccessFilament(): bool
    {
        return str_ends_with($this->email, '@gmail.com');// && $this->hasVerifiedEmail();
        //return true;
    }


    public function Categories()
    {
        return $this->hasMany(Category::class);
    }

    public function Suppliers()
    {
        return $this->hasMany(Supplier::class,'createdBy');
    }


    public function Warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }

    public function Orders()
    {
        return $this->hasMany(Order::class);
    }

    public function UserHasCounter()
    {
        return $this->hasOne(UserHasCounter::class,'userId')->latestOfMany();
    }

    public function isSeller():Attribute
    {
            return Attribute::make(
                get: fn() => $this->roleId==3 ? true: false
            );
    }

    // public function Counter()
    // {
    //     return $this->hasOneThrough(
    //         Counter::class,
    //         UserHasCounter::class,
    //         'userId',
    //         'counterId'
    //     );
    // }

}
