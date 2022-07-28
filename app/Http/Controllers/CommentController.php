<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;


class CommentController extends Controller
{
    //get all comments
    public function getComments($apiKey, $authId, $postId){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }
        $post = Post::find($postId);

        if(!$post){
            return response([
                'message'=>'Post not found.'
            ], 403);
        }
        $GLOBALS['authId'] = $authId;

        return response([
            'comments'=>$post->comments()->with('user:id,username,profile_picture,about,last_visit,token')->withCount('likes')
            ->with('likes', function($like){ 
                return $like->where('auth_id',  $GLOBALS['authId'])
                ->select('id', 'user_id', 'comment_id')->get();
            })
            ->get()
        ], 200);
    }


     //create  comment
     public function saveComment(Request $request, $apiKey, $userId, $postId){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }
        $post = Post::find($postId);
 
        if(!$post){
            return response([
                'message'=>'Post not found.'
            ], 403);
        }

        //validate fields
        $attrs =  $request->validate([
            'comment'=>'required|string',
        ]);

        Comment::create([
            'comment'=>$attrs['comment'],
            'visible'=>1,
            'post_id'=>$postId,
            'user_id'=>$userId
        ]);


        return response([
            'message'=>'Comment created.'
        ], 200);
    }

    //update a comment
    public function update(Request $request,$apiKey, $id){
        $comment = Comment::find($id);
 
        if(!$comment){
            return response([
                'message'=>'Comment not found.'
            ], 403);
        }

        if($comment->user_id != auth()->id){
            return response([
                'message'=>'Permission denied.'
            ], 403);
        }

        //validate fields
        $attrs =  $request->validate([
            'comment'=>'required|string'
        ]);

        $comment->update([
            'comment'=>$attrs['comment'],
           
        ]); 

        return response([
            'message'=>'Comment updated'
        ], 200);
    }


    //delete comments
    public function destroy($apiKey, $commentId){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }

        $comment = Comment::find($commentId);
 
        if(!$comment){
            return response([
                'message'=>'Comment not found.'
            ], 403);
        } 
        
       $comment->delete();
       
        return response([
            'message'=>"Comment deleted",
        ], 200);
    }

}
