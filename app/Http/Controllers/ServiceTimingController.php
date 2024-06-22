<?php

namespace App\Http\Controllers;

use App\Models\ServiceTiming;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ServiceTimingController extends Controller
{


    public function addServiceTiming(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'arabic_name' => 'required|string|max:255',
            'description' => 'required|string',
            'arabic_description' => 'required|string',
            'duration' => 'required|integer',
            'type' => 'required|in:min,second,hour,day',
            'arabic_type' => 'required|string|max:255',
            'image' => 'nullable|string',
            'service_id' => 'required|exists:services,id',
        ]);

           if ($validator->fails()) {
            return response()->json(
             [ "errors"=> $validator->errors()]
            , 403);
        }




    $base64Image = $request->input('image');
    $path = '';
    if ($base64Image) {
        
        $image = base64_decode($base64Image);
        $imageName = time() . ".png";
        $imagePath = public_path('/storage/service_timings/' . $imageName);
        file_put_contents($imagePath, $image);
        $path = 'service_timings/' . $imageName;
        $request['image'] = $path;

    }


        $serviceTiming = ServiceTiming::create($request->all());


        $message = 'Service Timing Added successfully!'; // Customize this message

        return response()->json([
            'message' => $message,
            'data' => $serviceTiming,
        ], 201); //


    }

    //////  get service timing 

    public function getAllServiceTiming()
    {
        // Fetch all categories
        $categories = ServiceTiming::with('service')->get();

        // Return a JSON response
        return response()->json([
            'message' => 'Retrieved successfully!',
            'data' => $categories
        ], 200);
    }


    //////// delete

    public function deleteServiceTiming($id)
    {

        $serviceTiming = ServiceTiming::findOrFail($id);

        Storage::delete('public/' .   $serviceTiming->image);
        

        $serviceTiming->delete();

        return response()->json([
            'message' => 'Deleted successfully!'
        ], 200);
    }



    //////////  UPDATE Service
    public function updateServiceTiming(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'arabic_name' => 'required|string|',
            'description' => 'required|string|',
            'arabic_description' => 'required|string|',
            'image' => 'nullable|string',
            'service_id' => 'required|integer|exists:services,id', 
             'id' => 'required|exists:servicetimings,id',
        ]);

           if ($validator->fails()) {
            return response()->json(
             [ "errors"=> $validator->errors()]
            , 403);
        }


        $serviceTiming = ServiceTiming::findOrFail($request->id);


  $base64Image = $request->input('image');
    $path =null;
    if ($base64Image) {
        
        
 Storage::delete('public/' .   $serviceTiming->image);
    
        
       
        $image = base64_decode($base64Image);
        $imageName = time() . ".png";
        $imagePath = public_path('/storage/service_timings/' . $imageName);
        file_put_contents($imagePath, $image);
        $path = 'service_timings/' . $imageName;
        $request['image'] = $path;

    }



        $serviceTiming->update([
            'name' => $request->get('name', $serviceTiming->name),
            'arabic_name' => $request->get('arabic_name', $serviceTiming->arabic_name),
            'description' => $request->get('description', $serviceTiming->description),
            'arabic_description' => $request->get('arabic_description', $serviceTiming->arabic_description),
            'type' => $request->get('type', $serviceTiming->type),
            'arabic_type' => $request->get('arabic_type', $serviceTiming->arabic_type),
           'duration' => $request->get('duration', $serviceTiming->duration),
            'image' => $path,
            'service_id' => $request->get('service_id', $serviceTiming->service_id),
        ]);

        return response()->json([
            'message' => 'ServiceTiming updated successfully!',
            'data' => $serviceTiming
        ], 200);
    }
}