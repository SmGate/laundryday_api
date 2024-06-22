<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

use App\Models\Service;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ServiceController extends Controller
{
    public function addService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_name' => 'required|string|unique:services,service_name',
            'service_name_arabic' => 'required|string|unique:services,service_name_arabic',
            'image' => 'nullable|string',
            'delivery_fee' => 'required|numeric',
            'operation_fee' => 'required|numeric',
            'user_id' => 'required|exists:users,id',


        ]);

    
    if ($validator->fails()) {
            return response()->json(
             [ "errors"=> $validator->errors()]
            , 403);
        }

        // $image = $request->file('image');
        // $path = '';
        // if ($image != null) {
        //     $imageName = time() . "." . $image->extension();
        //     $image->move(public_path('/storage/services'), $imageName);
        //     $path = 'services/' . $imageName;
        // }


   $base64Image = $request->input('image');
    $path = null;

    if ($base64Image) {
        
        $image = base64_decode($base64Image);

        
        $imageName = time() . ".png";

        $imagePath = public_path('/storage/services/' . $imageName);

        file_put_contents($imagePath, $image);

        $path = 'services/' . $imageName;

       $request['service_image'] = $path;

    }
        $service = Service::create($request->all());

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
    public function update(Request $request)
    {
        
         $validator = Validator::make($request->all(), [
            // 'service_name' => 'required|string|unique:services,service_name',
            // 'service_name_arabic' => 'required|string|unique:services,service_name_arabic',

            'delivery_fee' => 'required|numeric',
            'operation_fee' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'id' => 'required|exists:services,id',
            "service_description"=>'nullable',
            "service_description_arabic"=>'nullable',
            'image' => 'nullable|image',


        ]);


            if ($validator->fails()) {
            return response()->json(
             [ "errors"=> $validator->errors()]
            , 403);
        }
        



          


        

        $service = Service::findOrFail($request->id);


        

//         $base64Image = $request->input('image');
//     $path = '';

//     if ($base64Image) {
        
//    $destination = public_path('/storage/').$service->service_image;



//             if (File::exists($destination)) {

//                 unlink($destination);
//             }

//         $image = base64_decode($base64Image);

        
//         $imageName = time() . ".png";

//         $imagePath = public_path('/storage/services/' . $imageName);

//         file_put_contents($imagePath, $image);

//         $path = 'services/' . $imageName;

//             $service->service_image = $path;

//     }

        if ($request->hasFile('image')) {

   $destination = public_path('/storage/').$service->service_image;

            if (File::exists($destination)) {

                unlink($destination);
            }

            $image = $request->file('image');

            $imageName = time() . "." . $image->extension();

            $image->move(public_path('/storage/services'), $imageName);
             $path = 'services/' . $imageName;

            $service->service_image = $path;
        
        
        }
        

        $service->update($request->all());


        return response()->json([
            'message' => 'Service updated successfully!',
            'data' => $service,
        ], 200);
    }

    public function deleteService($id)
    {
        $service = Service::findOrFail($id);

        Storage::delete('public/' . $service->service_image);

        $service->delete();

        return response()->json([
            'message' => 'Service deleted successfully!',
        ], 200);
    }

   
}
