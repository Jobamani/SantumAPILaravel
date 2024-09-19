<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TextController extends Controller
{
    public function signup(Request $request){
        return "back";
        $validateUser = Validator::make(
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
                    'errors'=>$validateUser->error()->all()
                ],401);
            }
            //if validation sucess it will show user data, eliqoent method
            $user = User::create([
                'name'=> $request->name,
                'email'=> $request->email,
                'passowrd'=> $request->password,
            ]);

            return response()->json([
                'status' =>true,
                'message'=>'User Created Successfully',
                'user'=>$user
            ],200);
}
}