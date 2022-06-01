<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'c_password' => 'required|same:password',
            'role' => 'required',
            'phone'=>'min:6',
        ]);

        $user= User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
        ]);

        return response()->json([
            'status' => true,
            'data' => $user
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => $validator->errors()
            ]);
        }

   }

   public function login(Request $request)
   {
       $data = [
           'email' => $request->email,
           'password' => $request->password,
       ];
       
       if(auth('web')->attempt($data)){
           $token = auth('web')->user()->createToken('MyApp')->accessToken;
           return response()->json([
               'message' => 'Logged in successfully.',
               'token' => $token
           ], 200);
       }
       else{
           return response()->json([
            'status' => false,
            'message' => 'error'
        ],401 );
       }

    }

    public function details()
    {
        $user = Auth::user();
        return response()->json(['user' => $user], 200);
    }
}
