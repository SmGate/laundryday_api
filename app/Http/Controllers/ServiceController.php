<?php

namespace App\Http\Controllers;

use App\Models\service;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function addService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_name' => 'required|string',
            'service_name_arabic' => 'required|string',
            'image' => 'required|file',
            'delivery_fee' => 'required|numeric',
            'operation_fee' => 'required|numeric',
            'user_id' => 'required|numeric',


        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 403);
        }



        $image = $request->file('image');
        $path = '';
        if ($image != null) {
            $imageName = time() . "." . $image->extension();
            $image->move(public_path('/storage/services'), $imageName);
            $path = 'services/' . $imageName;
        }
        $request['service_image'] = $path;
        $service = service::create($request->all());

        return response()->json(['message' => 'Service Added successfully!', 'data' => $service], 201); // Include created user data (optional)
    }


    //// GET ALL SERVICE
    public function getAllServices()
    {
        $services = Service::all();

        return [
            'message' => 'Services retrieved successfully!',
            'data' => $services->toArray(), // Convert collection to array of service objects
        ];
    }


    ////////  UPDATE SERVICE
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id); // Find the service by ID

        $validatedData = $request->validate([
            'service_name' => 'required|string',
            'service_name_arabic' => 'nullable|string',
            'service_description' => 'required|string',
            'service_description_arabic' => 'nullable|string',
            'delivery_fee' => 'required|numeric',
            'operation_fee' => 'required|numeric',
            'service_image' => 'nullable|file',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $service->update($validatedData);

        return response()->json([
            'message' => 'Service updated successfully!',
            'data' => $service,
        ], 200);
    }

    //////////  DELETE SERVICE
    public function deleteService($id)
    {
        $service = Service::findOrFail($id);

        $service->delete();

        return response()->json([
            'message' => 'Service deleted successfully!',
        ], 200);
    }
}
