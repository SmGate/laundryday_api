<?php

namespace App\Http\Controllers;
use App\Models\Laundry;
use App\Models\Laundryservices;
use App\Models\User;
use App\Models\Service;
use App\Models\Branch;
use App\Models\Region;

use App\Rules\Base64ImageOrSvg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LaundryController extends Controller
{


public function register(Request $request)  {



    $validator = Validator::make($request->all(), [

            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile_number' => 'required|unique:users,',
            'email' => 'required|email|unique:users,email,',
             'mobile_number' => [
                'required',
                'string',
                'max:255',

                function ($attribute, $value, $fail) use ($request) {
                    $exists = User::where('identifier', $value)
                        ->where('role', 'business_owner')
                        ->exists();


                    if ($exists) {
                        $fail('mobile number has already been taken.');
                    }
                },
            
            
            
            ],
                        'password' => 'required|string|max:255',


            'name' => 'required|string|max:255',
            'arabic_name' => 'required|string|max:255',
            'type' => 'required|in:laundry,central laundry',
            'branches' => 'required|integer|min:0',
            'tax_number' => 'required|string|max:255',
            'commercial_registration_no' => 'required|string',
            'commercial_registration_image' => 'required|string', 
            'logo' => 'nullable|string',
            'is_central_laundry' => 'required|boolean',
            'service_ids' => 'required|array',
            'service_ids.*' => 'exists:services,id',
            'branches_list'=>'required|array',
            'branches_list.*.country_id' => 'required|exists:countries,id',
            'branches_list.*.region_id' => 'required|exists:regions,id',
            'branches_list.*.city_id' => 'required|exists:cities,id',
            'branches_list.*.district_id' => 'nullable|exists:districts,id',
            'branches_list.*.postal_code' => 'nullable|string|max:255',
            'branches_list.*.area' => 'nullable|string|max:255',
            'branches_list.*.google_map_address' => 'required|string|max:255',
            'branches_list.*.lat' => 'required|numeric',
            'branches_list.*.lng' => 'required|numeric',
            'branches_list.*.address' => 'nullable|string|max:255',
           

        
            
        
        ]);






if ($validator->fails()) {
            return response()->json(
             [ "errors"=> $validator->errors()]
            , 403);
        }


        $branchesList = $request->input('branches_list');

                   $base64Image = $request->input('commercial_registration_image');
    $path = null;

    if ($base64Image) {
        


        $image = base64_decode($base64Image);
   
        $imageName = time() . ".png";

        $imagePath = public_path('/storage/laundries/' . $imageName);

        file_put_contents($imagePath, $image);

        $path = 'laundries/' . $imageName;

       $request['commercial_registration_image'] = $path;

    }


    


    

         $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'identifier' => $request->mobile_number,
            "provider"=>'mobile_number',
            'email'=>$request->email,
            "role"=>'business_owner',
            'password' => Hash::make($request->password),
        ]);
        $request['user_id']=$user->id;

        $laundry = Laundry::create($request->all());
        


           foreach ($branchesList as $branchData) {
            Branch::create([
                'laundry_id' => $laundry->id,
                'country_id' => $branchData['country_id'],
                'region_id' => $branchData['region_id'],
                'city_id' => $branchData['city_id'],
                'district_id' => $branchData['district_id'],
                'postal_code' => $branchData['postal_code'],
                'area' => $branchData['area'],
                'google_map_address' => $branchData['google_map_address'],
                'lat' => $branchData['lat'],
                'lng' => $branchData['lng'],
                'address' => $branchData['address'],
            ]);
        }

    
    $laundry->services()->sync($request->service_ids);

    return response()->json(['message' => 'Laundry registered successfully', 'data' => $laundry], 201);
    
}



public function  verify(Request $request)  {



    $validator = Validator::make($request->all(), [
        
            'id' => 'required|exists:laundries,id',
            'verification_status' => 'required|in:verified,unverified',

           
        ]);

if ($validator->fails()) {
            return response()->json(
             [ "errors"=> $validator->errors()]
            , 403);
        }


       
 $laundry = Laundry::findOrFail($request->id);

 $laundry->verification_status=$request->verification_status;
 $laundry->update(
$request->all()
    );


 return response()->json(['message' => 'Laundry verified successfully', 'data' => $laundry], 201);
    
}


  public function delete($id)
    {



        
        $laundry = Laundry::findOrFail($id);
        Storage::delete('public/' . $laundry->commercial_registration_no);
                    $laundry->delete();



        return response()->json([
            'message' => 'Laundry deleted successfully!'
        ], 200);
    }




       public function all()
    {
        $laundries = Laundry::with(['services','business_owner', 'laundry_branches'  => function ($query) {
        $query->with('country', 'city','region','district');
    },
    ])->get();

        return [
            'message' => 'Laundrie retrieved successfully!',
            'data' =>  $laundries,
        ];
    }




    public function update(Request $request)  {



    $validator = Validator::make($request->all(), [

            'name' => 'required|string|max:255',
            'arabic_name' => 'required|string|max:255',
            'type' => 'required|in:laundry,central laundry',
            'branches' => 'required|integer|min:0',
            'tax_number' => 'required|string|max:255',
            'commercial_registration_no' => 'required|string',
            'commercial_registration_image' => 'required|string', 
            'is_central_laundry' => 'required|boolean', 
            'service_ids' => 'required|array',
            'service_ids.*' => 'exists:services,id',
            'id' => 'required|exists:laundries,id',
        
        ]);

if ($validator->fails()) {
            return response()->json(
             [ "errors"=> $validator->errors()]
            , 403);
        }


    

$laundry = Laundry::findOrFail($request->id);



 $base64Image = $request->input('commercial_registration_image');
    $path = null;

    if ($base64Image) {
        


        $image = base64_decode($base64Image);
        $imageName = time() . ".png";
        $imagePath = public_path('/storage/laundries/' . $imageName);
        file_put_contents($imagePath, $image);
        $path = 'laundries/' . $imageName;
        $request['commercial_registration_image'] = $path;

    }
            $laundry->update(
$request->all()
        );


       
        return response()->json(['message' => 'Laundry update successfully', 'data' => $laundry], 201);
    
}





}
