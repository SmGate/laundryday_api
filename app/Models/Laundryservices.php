<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laundryservices extends Model
{

        protected $fillable = [
        'laundry_id','service_id'
    ];
    use HasFactory;
}
