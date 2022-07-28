<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Notification;
use Spatie\WebhookServer\WebhookCall;


class NotificationController extends Controller
{
    
    public function getNotifications($apiKey, $userId){
        return response([
            'notifications'=>Notification::orderBy('created_at','desc')->where('owner_id', $userId)
            ->with('user:id,username,profile_picture,about,last_visit,token')->get()
        ], 200);
     }  

     public function newNotifications($apiKey, $userId){
        return response([
            'notifications'=>Notification::where('seen', 0)->where('owner_id', $userId)
            ->with('user:id,username,profile_picture,about,last_visit,token')
            ->get()
        ], 200);
     }  

     
     public function createDeleteNotification(Request $request, $apiKey){
        //delete notification if it already exist
        $noti = Notification::where('user_id', $request->user_id)
           ->where('owner_id', $request->owner_id)
                ->where('title', $request->title)->first();

        if($noti){
            $noti->delete();

            return response([
                'message'=>"Notification deleted"
            ], 200);

        }

        $notification = Notification::create([
            'body'=>$request->body,
            'user_id'=>$request->user_id,
            'owner_id'=>$request->owner_id,
            'post_id'=>$request->post_id,
            'title'=>$request->title,
            'token'=>$request->token,
            'seen'=>0
        ]);

        // //send notification
        // WebhookCall::create()
        // ->url('https://us-central1-feedmate.cloudfunctions.net/notifications')
        // ->payload(['notification'=>$notification])
        // ->useSecret('secretKey')
        // ->dispatch();

        return response([
            'message'=>$notification
        ], 200);
    }


    
  //update an item
  public function updateNotification($apiKey, $id){
    if($apiKey != config('services.key.api_key')){
        return response([
            'message'=>"invalid api key"
        ], 403);
    }

    $noti  = Notification::find($id);
    if(!$noti){
        return response([
            'message'=>"notification not found."
        ], 403);
    }

    
    $noti->update([
        'seen'=>1,
    ]);
    
    return response([
        'message'=>$noti,
    ], 200);
}

}
