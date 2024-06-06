<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class service extends Model
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
}
