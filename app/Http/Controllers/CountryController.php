<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    

public  function getCountry($id)  {


    $country = Country::find($id);

return response()->json([
            "success" => true,
            "data" => $country,
        ]);
    
}



}
