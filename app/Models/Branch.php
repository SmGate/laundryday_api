<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $table = 'branches';

      protected $fillable = [
                
        'country_id',
        'region_id', 
        'city_id', 
        'district_id',
        'postal_code', 
        'address',
        'area', 
        'google_map_address',
        'lat',
        'lng',
        'verification_status',
        'rating',
        'open_time',
        'close_time',
        'laundry_id'
    ];
    

  public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }


}
