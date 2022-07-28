<?php

namespace App\Http\Controllers;

use App\Models\GroupMessageOpened;
use App\Models\MessageOpened;

class MessageOpenedController extends Controller
{
    //get all chat messages read by user
    public function myOpenedMessages($apiKey, $userId,){
        return response([
           'messages'=>MessageOpened::where('user_id',  $userId)
            ->get()->count()
        ], 200);
     }  

      //get opened chat  messages
      public function openedMessages($apiKey, $chatId, $userId,){
        return response([
            'messages'=>MessageOpened::where('chat_id', $chatId)
            ->where('user_id',  $userId)
            ->get()->count()
        ], 200);
     }  

    //opened message history
     public function messageOpened($apiKey, $chatId, $messageId, $userId){
        $opened = MessageOpened::where('chat_id', $chatId)
        ->where('message_id', $messageId)->where('user_id', $userId)->first();
        if(!$opened){
            MessageOpened::create([
                'message_id'=>$messageId,
                'user_id'=>$userId,
                'chat_id'=>$chatId,
            ]);
    
            return response([
                'message'=>'Message Opened'
            ], 200);
        }
       
     }
    
     //get opened group messages by user
     public function myOpenedgroupMessages($apiKey, $userId){
        return response([
            'messages'=>GroupMessageOpened::where('user_id', $userId)
             ->get()->count()
        ], 200);
     }  

    //get opened group messages
      public function openedgroupMessages($apiKey, $groupId, $userId){
        return response([
            'messages'=>GroupMessageOpened::where('group_id', $groupId)
             ->where('user_id', $userId)
             ->get()->count()
        ], 200);
     }  
     
     //create opened message history
     public function groupMessageOpened($apiKey, $groupId,  $messageId, $userId){
        $opened = GroupMessageOpened::where('group_id', $groupId)
        ->where('message_id', $messageId)->where('user_id', $userId)->first();
        if(!$opened){
            GroupMessageOpened::create([
                'message_id'=>$messageId,
                'user_id'=>$userId,
                'group_id'=>$groupId
            ]);
            return response([
                'message'=>'Message Opened'
            ], 200);
       
        }
       
  }
}
