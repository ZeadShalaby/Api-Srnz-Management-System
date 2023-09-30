<?php

namespace App\Models;

use App\Models\User;
use App\Models\Orders;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cards extends Model
{
    use HasFactory;


    protected $fillable = [
        'orders_id',
        'user_id',
        'department_id',
        'price',

    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'orders_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id');
    }

    
}
