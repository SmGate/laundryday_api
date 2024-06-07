<?php

namespace App\Http\Controllers;

use App\Models\ServiceTiming;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

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


        $image = $request->file('service_image');
        $path = '';
        if ($image != null) {
            $imageName = time() . "." . $image->extension();
            $image->move(public_path('/storage/category'), $imageName);
            $path = 'category/' . $imageName;
        }
        $request['image'] = $path;

        $category = ServiceTiming::create($request->all());

        $message = 'Category created successfully!'; // Customize this message

        return response()->json([
            'message' => $message,
            'data' => $category,
        ], 201); //


    }
}
