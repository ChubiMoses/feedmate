<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupChat;


class groupMemberController extends Controller
{

    //get groups members
    public function getGroupMembers($apiKey, $id){
        $group  = Group::find($id);

        if(!$group){
            return response([
                'message'=>"group not found."
            ], 403);
        }

        return response([
            'group_members'=>GroupMember::with('user:id,username,profile_picture,about,last_visit')
            ->with('group:id,name,description,image')->where('group_id', $id)->get()
        ], 200);
     }  

      //get groups members
    public function getMyGroups($apiKey, $id){
        return response([
            'group_members'=>GroupMember::with('user:id,username,profile_picture,about,last_visit')
            ->with('group:id,name,description,image')->where('user_id', $id)->get()
        ], 200);
     } 

   //get groups members
    public function memberDetail(Request $request, $apiKey){
        return response([
            'group_members'=>GroupMember::with('user:id,username,profile_picture,about,last_visit')
            ->with('group:id,name,description,image')->where('user_id', $request->user_id)->where('group_id', $request->group_id)->get()
        ], 200);
     } 

 
    public function joinGroup($apiKey, $id, $userId){
        //check if user previously joined the group
        $member = GroupMember::where('user_id',$userId)->where('group_id',$id)->first();
        
        if($member){
            $member->update([
                'left_group'=> 0
            ]);

            return response([
                'message'=>"Joined group"
            ], 200);
         }

         $message = GroupMember::create([
            'user_id'=>$userId,
            'group_id'=>$id,
            'blocked'=>0,
            'left_group'=>0,
         ]);
        return response([
            'message'=>$message
        ], 200);
     }  
     
    public function blockUser($apiKey, $id){
        $groupMember  = GroupMember::find($id);

        if(!$groupMember){
            return response([
                'message'=>"member not found."
            ], 403);
        }
        $blocked = $groupMember['blocked'];

        $groupMember->update([
            'blocked'=> $blocked == 1 ? 0 : 1
        ]);

        return response([
            'message'=>"Blocked"
        ], 200);
     }  
    

    public function leaveGroup($apiKey, $id){
        $groupMember  = GroupMember::find($id);

        if(!$groupMember){
            return response([
                'message'=>"member  not found."
            ], 403);
        }
         
        $groupMember->update([
            'left_group'=> 1
        ]);

        // $groupMember->delete();

        return response([
            'message'=>"left group",
        ], 200);
     }  
}
