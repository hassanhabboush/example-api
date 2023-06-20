<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getUser(){
      $users =  User::get();
    //   return $users;
      return UserResource::collection($users);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|min:5|max:12',
        ], [
            'name.required' => 'The Name Is Required',
            'name.string' => 'The Name Is Must Be String',
            'name.required' => 'The Name Is Required',
            'email.required' => 'The Email Is Required',
            'email.email' => 'The Email Is Must Be Email',
            'password.required' => 'The Password Is Required',
            'password.min' => 'The Password Is Must Be At least 5',
            'password.max' => 'The Password Is Must Be At most 12 characters',
        ]);

        if ($validator->fails()) {
            return $validator->messages()->all();
        }

      $user =  User::create([
            'name' => $request-> name,
            'email' => $request-> email,
            'password' => Hash::make($request-> password)

        ]);
        return new UserResource($user);

    }

    public function update(Request $request,$id){
        $user =  User::find($request->id);
      $user->update([
              'name' => $request-> name,
              'email' => $request-> email,
              'password' => Hash::make($request-> password)

          ]);
          return new UserResource($user);

      }

      public function destroy(Request $request){
        $user = User::find($request->id);
        $user->delete();
        return new UserResource($user);

      }

      public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:5|max:12',
        ], [
            'email.required' => 'The Email Is Required',
            'email.email' => 'The Email Is Must Be Email',
            'password.required' => 'The Password Is Required',
            'password.min' => 'The Password Is Must Be At least 5',
            'password.max' => 'The Password Is Must Be At most 12 characters',
        ]);
        
        if ($validator->fails()) {
            return $this->errorResponse("Error",303,$validator->messages()->all());
        }

        $user = User::where('email',$request->email)->first();
        if(!$user){
            return  "User not found";
        }
        if(!Hash::check($request->password,$user->password)){
            return "No password is Not correct";
        }

        $token = $user->createToken('web')->plainTextToken;
        $response = collect([
            'name' => $user->name,
            'email'=>$user->email,
            'token' => $token
        ]);

        return $this->sendResponse($response,"User Is Login",300);

      }

     
    public  function logout(){
        Auth::guard('user-api')->user()->currentAccessToken()->delete();
        return $this->sendResponse([],"User Is Logout",200);
    }
}