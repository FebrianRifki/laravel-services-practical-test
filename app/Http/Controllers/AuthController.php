<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{
    public function register(Request $request){
       try {
            $validator = Validator::make($request->all(), 
            [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users,email',
                'address' => 'required|string',
                'role' => 'required|integer',
                'password' => 'required|string|min:6'
            ]);
            
            if($validator->fails()){
                return response()->json(
                    [
                        'message' => $validator->errors()->first('email') ? 'Email already registered' : 'Please check your input',
                        'success' => false,
                        'code' => 400,
                    ], 400
                );
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'role' => $request->role,
                'password' => Hash::make($request->password),
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'User successfully registered',
                'success' => true,
                'code' => 201,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], 201);

       } catch (\Throwable $th) {
        \Log::error('Login error: ' . $th->getMessage());
        return response()->json([
            'message' => 'internal server error',
            'success' => false,
            'code' => 500,
        ], 500);
       }
    }

    public function login(Request $request){
        
        $credentials = $request->only('email', 'password');
       
        try {
            if(!$token = JWTAuth::attempt($credentials)){
                return response()->json(['error' => 'invalid credentials' , 490]);
            }
            
            $user = auth()->user();

            $token = JWTAuth::claims(['user' => $user])->fromUser($user);

           return response()->json([
                'message' => 'User successfully logged in',
                'success' => true,
                'code' => 200,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'canot create token '. $th ,
                'success' => false,
                'code' => 500,
                'access_token' => "",
                'token_type' => ""
            ], 500);
        }
    }

    public function logout(){
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'message' => 'Successfully logged out',
                'success' => true,
                'code' => 200
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'internal server error',
                'success' => false,
                'code' => 500,
              ], 500);
        }
    }
}
