<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laundry extends Model
{
    use HasFactory;

 protected   $primaryKey='id';
     protected $fillable = [
                "logo",

        'name', 'arabic_name', 'type', 'user_id', 'branches', 'tax_number',

        'commercial_registration_no', 'commercial_registration_image',
          'is_central_laundry', 
        'subscription_status', 'verification_status', 
        'total_orders', 
      
    ];
public function services()
{
    return $this->belongsToMany(Service::class, 'laundry_services');
}


      public function laundry_branches()
{
    return $this->hasMany(branch::class, 'laundry_id',);
}

 
      public function business_owner()
{
    return $this->belongsTo(user::class, 'user_id',);
}



    protected $casts = [
        "user_id" => 'integer',
        "is_central_laundry"=>'integer',
        "country_id"=> "integer",
        "region_id"=> "integer",
        "city_id"=> "integer",
        "district_id"=>"integer",
        "branches"=> "integer",
        "lat"=>"double",
        "lng"=>"double",
        "rating"=>"double"


    ];


}
