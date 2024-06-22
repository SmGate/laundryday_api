<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{



    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_name',
        'service_name_arabic',
    
        'service_description',
        'service_description_arabic',
        'service_image',
        'delivery_fee',
        'operation_fee',
        'user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(category::class);
    }
      protected $casts = [
        "user_id" => 'integer',
        "delivery_fee" => 'double',

        "operation_fee" => 'double'
        
        // 'status' => 'integer',

    ];

    // app/Models/User.php


}
