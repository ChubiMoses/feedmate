<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\chatId;
use App\Models\GroupMember;

class groupController extends Controller
{     
    public function search($apiKey, $query){ 
        if($apiKey != config('services.key.api_key')){
            return response([
                'message'=>"invalid api key"
            ], 403);
        }
        
        return response([
            'groups'=>Group::where('name', 'LIKE', '%'.$query.'%')
            ->with('user:id,username,profile_picture,about,last_visit')
            ->orderBy('created_at', 'desc')->withCount('members')->get()
        ], 200);
    }
    
     //get groups
     public function getGroups($apiKey){
        return response([
            'groups'=>Group::with('user:id,username,profile_picture,about,last_visit')->orderBy('created_at', 'desc')->withCount('members')->get()
        ], 200);
     }  
    
      //get group chats
      public function groupInfo($apiKey, $id){
        return response([
            'groups'=>Group::with('user:id,username,profile_picture,about,last_visit')->withCount('members')->where('id', $id)->get(),
        ], 200);
     }

     
    //store message
     public function createGroup(Request $request, $apiKey){
        //validate fields
        $attrs = $request->validate([
            'name'=>'required|string',
            'description'=>'required|string',
            'user_id'=>'required|string',
            'type'=>'required|string'
        ]);
      

        $image =  $this->saveImage($request->image, 'images');
    

        $group = Group::create([
            'name'=>$attrs['name'],
            'description'=>$attrs['description'],
            'user_id'=>$attrs['user_id'],
            'type'=>$attrs['type'],
            'doc_id'=>$request->doc_id,
            'image'=>$image,
            'visible'=>1,
            'private'=>0
        ]);

        return response([
            'message'=>$group
        ], 200);
    }

   //update a group
   public function editGroup(Request $request, $apiKey, $id){
    $group  = Group::find($id);

    if(!$group){
        return response([
            'message'=>"group not found."
        ], 403);
    }
     //validate fields
     $attrs = $request->validate([
        'name'=>'required|string',
        'description'=>'required|string',
    ]);

    $image = '';
    if($request->update_image == "Update"){
        $image =  $this->saveImage($request->image, 'profiles');
    }else{
        $image = $request->image;
    }
    
    $group->update([
        'name'=>$attrs['name'],
        'description'=>$attrs['description'],
        'image'=>$image
    ]);

    return response([
        'group'=>$group
    ], 200);
    
}

 //make a group private or public
public function lockUnlockGroup($apiKey, $groupId){
    $group  = Group::find($groupId);

    $private = $group['private'];

    $group->update([
        'private'=> $private == 1 ? 0 : 1
    ]);

    return response([
        'group'=>$group
    ], 200);
 }  


  public function deleteGroup($apiKey, $groupId, $userId){

    //check if group exist
    $group  = Group::find($groupId);
    if(!$group){
        return response([
            'message'=>"group not found."
        ], 403);
    }

    $chats = chatId::where('group_id', $groupId)->where('receiver_id', $userId)->get();

    $members = GroupMember::where('group_id', $groupId)->where('user_id', $userId)->get();
    
    //delete group members
    foreach($members as $member){
        $member->delete();
    }
    //delete group chat id
    foreach($chats as $chat){
        $chat->delete();
    }
    //delete
    $group->delete();

    return response([
        'message'=>'Group deleted'
    ], 200);
 }
}
