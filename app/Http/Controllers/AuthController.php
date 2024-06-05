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
            'profile_image' => 'required|file',


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

        // Check for existing user with email
        $existingUserByEmail = User::where('email', $request->email)->first();

        // Check for existing user with mobile number (assuming mobile_number field exists)
        $existingUserByMobile = User::where('mobile_number', $request->mobile_number)->first();

        if ($existingUserByEmail) {
            return response()->json(['message' => 'Email Number Is Already Taken'], 409); // Conflict
        } elseif ($existingUserByMobile) {
            return response()->json(['message' => 'Mobile Number Is Already Taken'], 409); // Conflict
        }



        $user = User::create($request->all());

        return response()->json(['message' => 'User registered successfully!', 'data' => $user], 201); // Include created user data (optional)
    }

    /////  LOGIN
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user' => 'required|string', // Single field for email or phone
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 403);
        }

        $login = $request->input('user');


        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $login, 'password' => $request->password]; // Add password for email login
        } else {
            $credentials = ['mobile_number' => $login, 'password' => $request->password]; // Add password for phone login
        }



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
