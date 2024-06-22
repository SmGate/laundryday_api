<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;


    // Specify which attributes can be mass-assigned
    protected $fillable = ['name', 'arabic_name', 'image', 'service_id'];

    /**
     * Define the relationship with the Service model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->hasOne(Service::class,'id','service_id');
    }

      protected $casts = [
        "user_id" => 'integer',
        "service_id" => 'integer',
        
        // 'status' => 'integer',

    ];
}
