<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\chatId;


class chatIdController extends Controller
{
     public function getChatsId($apiKey, $id){
        return response([
            'chat_ids'=>chatId::with('user:id,username,profile_picture,about,last_visit')->
             with('group:id,user_id,name,description,image,type')
             ->join('users', 'chat_ids.receiver_id', '=', 'users.id')
             ->select('chat_ids.*', 'users.id AS receiver_id', 
             'users.profile_picture AS receiver_profile_picture', 
             'users.about AS receiver_about', 'users.last_visit AS receiver_last_visit',
             'users.username AS receiver_username')
             ->where('chat_ids.receiver_id','=',  $id)->
              orWhere('chat_ids.user_id','=',  $id)->
              orderBy('chat_ids.updated_at','desc')->get()
        ], 200);
     }

     
     public function createChatId(Request $request,$apiKey){
        //validate fields
        $attrs = $request->validate([
            'receiver_id'=>'required|string',
            'type'=>'required|string',
        ]);
      

        $message = chatId::create([
            'receiver_id'=>$attrs['receiver_id'],
            'type'=>$attrs['type'],
            'group_id'=>$request->group_id,
            'user_id'=>$request->user_id,
            'visible'=>1
        ]);

        return response([
            'message'=>$message,
        ], 200);
    }


  public function deleteChatId(Request $request, $apiKey){
     
    $chatId = chatId::where('receiver_id', $request->user_id)->where('group_id', $request->group_id)->get();
    
    if($chatId){
        $chat  = chatId::find($chatId[0]['id']);
        $chat->delete();

        return response([
            'message'=>"chat id deleted."
        ], 200);
    }

        return response([
            'message'=>"chat id not found."
        ], 403);
 }

 
 public function updateChatId($apiKey, $chatId, $timestamp){
     
    $chatId = chatId::find($chatId);
    
    if($chatId){
        $chatId->update([
            'last_chat'=>$timestamp,
        ]);

        return response([
            'message'=>"updated."
        ], 200);
     }

    return response([
        'message'=>"not found."
    ], 403);
 }
}
