<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

     protected $table = 'countries'; // Specify the table name if different from the default
    protected $fillable = ['id']; 
}
