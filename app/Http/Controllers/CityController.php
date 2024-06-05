<?php

namespace App\Http\Controllers;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    
public  function getCities($state_id)  {


     $cities = City::where('state_id',$state_id)->get(['id','name','state_id','latitude','longitude']);

return response()->json([
            "success" => true,
            "cities" => $cities,
        ]);}

}
