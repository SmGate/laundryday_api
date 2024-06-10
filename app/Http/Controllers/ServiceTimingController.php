<?php

namespace App\Http\Controllers;

use App\Models\ServiceTiming;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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
            'type' => 'required|in:min,second,hour',
            'arabic_type' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048', // Assuming it's an image file with maximum size of 2MB
            'service_id' => 'required|exists:services,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $image = $request->file('service_timing_image');
        $path = '';
        if ($image != null) {
            $imageName = time() . "." . $image->extension();
            $image->move(public_path('/storage/serviceTiming'), $imageName);
            $path = 'serviceTiming/' . $imageName;
        }
        $request['image'] = $path;


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

        $serviceTiming = ServiceTiming::find($id);


        if (!$serviceTiming) {
            return response()->json([
                'message' => 'Not found!'
            ], 404);
        }


        $file_path = public_path($serviceTiming->image);


        if (File::exists($file_path)) {

            File::delete($file_path);
        }


        $serviceTiming->delete();

        return response()->json([
            'message' => 'Deleted successfully!'
        ], 200);
    }



    //////////  UPDATE Service
    public function updateServiceTiming(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'arabic_name' => 'required|string|',
            'description' => 'required|string|',
            'arabic_description' => 'required|string|',
            'image' => 'nullable|file', // Optional image field
            'service_id' => 'required|integer|exists:services,id', // Ensures service_id exists
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $serviceTiming = ServiceTiming::find($id);

        if (!$serviceTiming) {
            return response()->json([
                'message' => 'Not found!'
            ], 404);
        }


        $image = $request->file('service_timing_image');
        $path = $serviceTiming->image;

        if ($image != null) {
            $imageName = time() . "." . $image->extension();
            $image->move(public_path('/storage/serviceTiming'), $imageName);
            $path = 'serviceTiming/' . $imageName;
        }


        $serviceTiming->update([
            'name' => $request->get('name', $serviceTiming->name),
            'arabic_name' => $request->get('arabic_name', $serviceTiming->arabic_name),


            'description' => $request->get('description', $serviceTiming->description),


            'arabic_description' => $request->get('arabic_description', $serviceTiming->arabic_description),
            'image' => $path,
            'service_id' => $request->get('service_id', $serviceTiming->service_id),
        ]);

        return response()->json([
            'message' => 'ServiceTiming updated successfully!',
            'data' => $serviceTiming
        ], 200);
    }
}