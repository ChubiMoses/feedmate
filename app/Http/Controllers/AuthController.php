<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class authController extends Controller
{ 
   
       //Register User
       public function register(Request $request, $apiKey, $authId){
        if($apiKey != config('services.key.api_key')){
            return response([
                'message'=>"invalid api key"
            ], 403);
        }

        //validate fields
        $attr = $request->validate([
            'full_name'=>'required|string',
            'username'=>'required|string',
            'email'=>'required|email|unique:users,email',
            'call_up_id'=>'required|call_up_id|unique:users,call_up_id',
            'auth_id'=>'required|string',
            'ppa'=>'required|string',
            'profile_image'=>'required|string',
            'phone_number'=>'required|string',
            'service_year'=>'required|string',
        ]);


        $user = User::create([
            'full_name' =>$attr['full_name'],
            'username' =>$attr['username'],
            'email' =>$attr['email'],
            'call_up_id' =>$attr['call_up_id'],
            'auth_id' =>$attr['auth_id'],
            'ppa' =>$attr['ppa'],
            'profile_image' =>$attr['profile_image'],
            'phone_number' =>$attr['phone_number'],
            'service_year' =>$attr['service_year'],
            'Blocked'=>0,
            'visible' =>1
        ]);
        return response([
            'user'=> $user,
        ], 200);
    }

    //get user details
    public function userInfo($apiKey, $authId){

        if($apiKey != config('services.key.api_key')){
            return response([
                'message'=>"invalid api key"
            ], 403);
        }
        return response([
            'users'=>User::where('authId', $authId)->withCount('posts')->first()
        ], 200);
    }


    //verify corper id
    public function verifyCallUpId($apiKey, $callUpID){ 

        if($apiKey != config('services.key.api_key')){
            return response([
                'message'=>"invalid api key"
            ], 403);
        }

        return response([
            'users'=>User::where('call_up_id', $callUpID)->first()
        ], 200);
    }
    
    //get users 
    public function users($apiKey){
        if($apiKey != config('services.key.api_key')){
            return response([
                'message'=>"invalid api key"
            ], 403);
        }

        return response([
            'users'=>User::orderBy('created_at', 'desc')->withCount('posts')->get()
        ], 200);
    }

    //update user
    public function  update(Request $request, $apiKey, $userId ){
        $user  = User::find($userId);
       
        if($apiKey != config('services.key.api_key')){
            return response([
                'message'=>"invalid api key"
            ], 403);
        }
        
        $attr = $request->validate([
            'name'=> 'required|string'
        ]);

        $image =  $this->saveImage($request->image, 'profiles');

        $user->update([
            'name'=>$attr['name'],
            'image'=>$image
        ]);

        return response([
            'massage'=>'User updated',
        ], 200);
    }
}
