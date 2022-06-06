<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'categoryId',
        'createdBy'
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Children()
    {
        return $this->hasMany(Category::class,'categoryId');
    }

    public function Parent()
    {
        return $this->belongsTo(Category::class,'categoryId');
    }

    public function Products()
    {
        return $this->hasMany(Product::class);
    }
}
