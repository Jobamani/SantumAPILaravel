<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function signup(Request $request){
        
        $validateUser = \Validator::make(
            $request->all(),
            [
                'name' =>'required',
                'email'=>'required|email|unique:users,email',
                'password'=> 'required',
            ]
            
            );
            //if validation fails
            if($validateUser->fails()){
                return response()->json([
                    'status' =>false,
                    'message'=>'validation Error',
                    'errors'=>$validateUser->errors()
                ],401);
            }
            //if validation sucess it will show user data, eliqoent method
            $user = User::create([
                'name'=> $request->name,
                'email'=> $request->email,
                'password'=> $request->password,
            ]);

            return response()->json([
                'status' =>true,
                'message'=>'User Created Successfully',
                'user'=>$user
            ],200);
    }

    public function login(Request $request){
        $validateUser = Validator::make(
            $request->all(),
            [
               
                'email'=>'required|email|',
                'password'=> 'required',
            ]  
            );
            
            if($validateUser->fails()){
                return response()->json([
                    'status' =>false,
                    'message'=>'Authentication Fails',
                    'errors'=>$validateUser->error()->all()
                ],404);        
            }

            if(Auth::attempt(['email'=> $request->email,'password'=>$request->password])){
                $authUser = Auth::user();
                return response()->json([
                    'status' =>true,
                    'message'=>'User Logged in Successfully',
                    'token'=> $u=authuser->createToken("API Token")->plainTextToken,
                    'token_type'=>'bearer'
                ],200); 
            }else{
                return response()->json([
                    'status' =>false,
                    'message'=>'Email & password does not matched',
                    'errors'=>$validateUser->error()->all()
                ],401); 
            }
}

    public function logout(Request $request){
        $user = $request->User();
        $user->tokens()->delete();

        return response()->json([
            'status' =>true,
            'user'=>$user,
            'message'=>'Logged out sucessfully',
          
        ],200); 

    }
}
