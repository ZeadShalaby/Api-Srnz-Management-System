<?php

namespace App\Models;

use App\Models\User;
use App\Models\Categories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favourite extends Model
{
    use HasFactory;


    protected $fillable = [
        'orders_id',
        'user_id',
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'orders_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
