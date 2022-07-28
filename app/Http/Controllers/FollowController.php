<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Follow;

class FollowController extends Controller
{

    //get followers
    public function followers($apiKey, $id){
        return response([
            'follows'=>Follow::orderBy('created_at', 'desc')->with('user:id,username,profile_picture,about')->
                where('user_id', $id)->get()
        ], 200);
    }
    
     //get following
     public function following($apiKey, $id){
        return response([
            'follows'=>Follow::orderBy('created_at', 'desc')->with('user:id,username,profile_picture,about')->
                where('follower_id', $id)->get()
        ], 200);
    }



    //follow a user
    public function followUnfollow(Request $request,$apiKey){
         //unfollow user if already following
         $follow = Follow::where('follower_id', $request->follower_id)
         ->where('user_id', $request->user_id)->first();

      if($follow){
          $follow->delete();

          return response([
              'message'=>"Unfollowed"
          ], 200);

      }

        Follow::create([
            'follower_id'=>$request->follower_id,
            'user_id'=>$request->user_id
        ]);

        return response([
            'message'=>'Followed'
        ], 200);

    }
}
