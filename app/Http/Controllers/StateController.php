<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\State;

class StateController extends Controller
{
    public  function getStates($country_id)  {


    $states = State::where('country_id',$country_id)->get(['id','name','country_id']);

return response()->json([
            "success" => true,
            "regions" => $states,
        ]);
}
}