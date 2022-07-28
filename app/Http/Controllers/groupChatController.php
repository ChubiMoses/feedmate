<?php

namespace App\Http\Controllers;
use App\Models\GroupChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

class groupChatController extends Controller
{
     //get group chats
     public function getChats($apiKey, $id){
        return response([
            'group_chats'=>GroupChat::with('user:id,username,profile_picture,about,last_visit,points')
            ->where('group_id', $id)->get()
        ], 200);
     }  
   
    //get last message
     public function getlastMessage($apiKey, $id){
        return response([
            'group_chats'=>GroupChat::orderBy('id', 'desc')->where('group_id', $id)->first(),
        ], 200);
     }  
    
   
    //get group messages not sent by me
      public function countMessage($apiKey, $groupId, $userId,){
        return response([
            'messages'=>GroupChat::where('group_id', $groupId)
            ->where('user_id', '!=', $userId)->orWhereNull('user_id')
            ->get()->count()
        ], 200);
     }  

     //get  all group messages not sent by me
     public function messageCount($apiKey, $userId,){
        return response([
            'messages'=>GroupChat::where('user_id', '!=', $userId)->orWhereNull('user_id')
            ->get()->count()
        ], 200);
     }  

    //store message
     public function createChat(Request $request,$apiKey, $id){
        //validate fields
        $attrs = $request->validate([
            'message'=>'required|string',
            'user_id'=>'required|string',
        ]);
      

        $urls = $this->saveFiles($request->urls, $request->file_type, $request->file_type == 'pdf' ? 'documents': 'images');


        $message = GroupChat::create([
            'message'=>$attrs['message'],
            'user_id'=>$attrs['user_id'],
            'group_id'=>$id,
            'urls'=>$urls,
            'visible'=>1
        ]);

        return response([
            'message'=>"message created",
            'message'=>$message
        ], 200);
    }


  public function deleteChat($apiKey, $id){
    $groupChat  = GroupChat::find($id);
    if(!$groupChat){
        return response([
            'message'=>"chat not found."
        ], 403);
    }

    $groupChat->delete();

    return response([
        'message'=>"chat deleted",
    ], 200);
 }
}
