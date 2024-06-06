<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{



    ///  REGISTER
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'identifier' => 'required|unique:users,',
            'email' => 'nullable|email|unique:users,email',
            'user_name' => 'nullable|unique:users,user_name',

             'identifier' => [
                'required',
                'string',
                'max:255',

                function ($attribute, $value, $fail) use ($request) {
                    $exists = User::where('identifier', $value)
                        ->where('role', $request->role)
                        ->exists();


                    if ($exists) {
                        $fail('The combination of identifier and role already exists.');
                    }
                },
            ],


        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 403);
        }

        $request['password'] = Hash::make($request->password);

        $image = $request->file('profile_image');
        $path = '';
        $image = $request->file('profile_image');

        if ($image != null) {
            $imageName = time() . "." . $image->extension();
            $image->move(public_path('/storage/user'), $imageName);
            $path = 'user/' . $imageName;
        }
        $request['image'] = $path;

    


        $user = User::create($request->all());

        return response()->json(['message' => 'User registered successfully!', 'data' => $user], 201); // Include created user data (optional)
    }

    /////  LOGIN
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string', // Single field for email or phone
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 403);
        }

        $login = $request->input('identifier');


       
            $credentials = ['identifier' => $login, 'password' => $request->password]; // Add password for email login
        



        if (Auth::attempt($credentials)) {

            $user = User::find(auth()->user()->id);
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful!',
                'data' => $user,
                'token' => $token,
            ]);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
}
