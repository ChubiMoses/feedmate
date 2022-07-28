<?php

namespace App\Http\Controllers;
use App\Models\Chat;
use Illuminate\Http\Request;

class chatController extends Controller
{
    //get group chats
     public function getChats($apiKey, $chatId){
        return response([
            'chats'=>Chat::with('user:id,username,profile_picture,about,last_visit')
            ->where('chat_id', $chatId)
             ->get()
        ], 200);
     }  
     
     //count messages sent to me
     public function countMessage($apiKey, $chatId, $userId,){
        return response([
            'messages'=>Chat::where('chat_id', $chatId)
            ->where('receiver_id', $userId)
            ->get()->count()
        ], 200);
     }  

     //count messages sent to me
     public function messageCount($apiKey,  $userId){
        return response([
            'messages'=>Chat::where('receiver_id', $userId)
            ->get()->count()
        ], 200);
     }  


     //get group chats
     public function getlastChat($apiKey, $id){
        return response([
            'chats'=>Chat::orderBy('id', 'desc')->where('chat_id', $id)->first(),
        ], 200);
     }  

    
    //store message
     public function createChat(Request $request, $apiKey, $id){
        //validate fields
        $attrs = $request->validate([
            'message'=>'required|string',
            'user_id'=>'required|string',
            'receiver_id'=>'required|string',
        ]);
      
        $urls = $this->saveFiles($request->urls, $request->file_type, $request->file_type == 'pdf' ? 'documents': 'images');
    
        $message = Chat::create([
            'message'=>$attrs['message'],
            'user_id'=>$attrs['user_id'],
            'receiver_id'=>$attrs['receiver_id'],
            'chat_id'=>$id,
            'seen'=>0,
            'urls'=>$urls,
            'visible'=>1
        ]);

        return response([
            'message'=>"message created",
        ], 200);
    }

  //update seen
  public function updateSeen($apiKey, $chatId){
    // if($apiKey != config('services.key.api_key')){
    //     return response([
    //         'message'=>"invalid api key"
    //     ], 403);
    // }

    $chat  = Chat::find($chatId);

    if(!$chat){
        return response([
            'message'=>"not found."
        ], 403);
    }

    $chat->update([
        'seen'=>1,
    ]);

    return response([
        'chat'=>$chat
    ], 200);
}



  public function deleteChat($apiKey, $id){

    $chat  = Chat::find($id);
    if(!$chat){
        return response([
            'message'=>"chat not found."
        ], 403);
    }

    $chat->delete();

    return response([
        'message'=>"chat deleted",
    ], 200);
 }
}
