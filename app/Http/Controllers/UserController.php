<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;


class UserController extends Controller
{
       
    //Register User
    public function register(Request $request, $apiKey, $authId){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }

        //validate fields
        $attr = $request->validate([
            'username'=>'required|string',
        ]);
         
        $image =  $this->saveImage($request->profile_picture, 'profiles');
            
        $user = User::create([
            'username' =>$attr['username'],
            'email' =>$request->email,
            'school_id'=>$request->school_id,
            'course_id'=>$request->course_id,
            'auth_id'=>$authId,
            'profile_picture'=>$image,
            'about'=>"",
            'blocked'=>0,
            'subscribed'=>0,
            'points'=>100,
            'rating'=>0,
            'token'=>"",
            'deactivated'=>0
        ]);

      //return user & token
        return response([
            'users'=> $user,
        ], 200);
    }

    //get user details
    public function getUserInfo($apiKey, $authId){ 
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }
        return response([
            'users'=>User::where('auth_id','=', $authId)
            ->join('schools', 'users.school_id', '=', 'schools.id')
            ->join('courses', 'users.course_id', '=', 'courses.id')
            ->select('users.*', 'schools.school AS school', 'courses.course AS course',  'users.id AS id')
            ->first()
        ], 200);
    }

     //get user details
     public function userInfo($apiKey, $userId){ 
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }
        return response([
            'users'=>User::join('schools', 'users.school_id', '=', 'schools.id')
            ->join('courses', 'users.course_id', '=', 'courses.id')
            ->select('users.*', 'schools.school AS school', 'courses.course AS course',  'users.id AS id')
            ->where('users.id', '=', $userId)
            ->first()
        ], 200);
    }
    

      public function search($apiKey, $query){ 
        if($apiKey != config('services.key.api_key')){
            return response([
                'message'=>"invalid api key"
            ], 403);
        }
        
        return response([
            'users'=>User::where('username', 'LIKE', '%'.$query.'%')->orderBy('created_at', 'desc')
            ->withCount('posts')->get()
        ], 200);
    }

    //get users
    public function users($apiKey){ 
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }
        
        return response([
            'users'=>User::join('schools', 'users.school_id', '=', 'schools.id')
            ->join('courses', 'users.course_id', '=', 'courses.id')
            ->select('users.*', 'schools.school AS school', 'courses.course AS course',  'users.id AS id')
            ->get()
        ], 200);
    }

    //update user
    public function  updateInfo(Request $request,  $apiKey, $userId){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }
         
        $user  = User::find($userId);

        if(!$user){
            return response([
                'message'=>"User not found."
            ], 403);
        }

      
         if($request->update_image == "Update"){
            $image =  $this->saveImage($request->profile_picture, 'profiles');
            
            $user->update([
                'username' =>$request->username,
                'profile_picture'=>$image,
                'about'=>$request->about,
            ]);    
         }else{
            $user->update([
                'username' =>$request->username,
                'about'=>$request->about,
            ]);
    
         }
         
        return response([
            'massage'=>'User updated',
        ], 200);
    }

      //update user last visit
      public function  lastVisited(Request $request,  $apiKey, $userId){
         $user  = User::find($userId);
         $user->update([
                'last_visit'=>$request->last_visit,
                'token'=>$request->token,
                'points'=>$request->points,
                'rating'=>$request->rating,
                'subscribed'=>$request->subscribed
            ]);    
         
          return response([
            'massage'=>$user,
       ], 200);
    }

}