<?php

namespace App\Models;

use App\Models\Orders;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orders extends Model
{
    use HasFactory,SoftDeletes;


   
    protected $fillable = [
        'name_ar',
        'name_en',
        'user_id',
        'department_id',
        'gmail',
        'phone',
        'description',
        'price',
        'path',
        'view',
    ];
   

    protected $dates =['delete_at'];


    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
