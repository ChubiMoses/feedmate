<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Like;
use App\Models\CommentLike;
use App\Models\Comment;

class LikeController extends Controller
{
    //like or unlike
    public function PostLikeOrUnlike($apiKey, $authId, $userId, $postId){
        $post  = Post::find($postId);

        if(!$post){
            return response([
                'message'=>"Post not found."
            ], 403);
        }

        $like = $post->likes()->where('user_id', $userId)->first();

        //if not liked then like

        if(!$like){
            Like::create([
                'post_id'=>$postId,
                'user_id'=>$userId,
                'auth_id'=>$authId
            ]);

            return response([
                'message'=>'Liked'
            ], 200);
        }else{
            //else dislike
            $like->delete();

            return response([
                'message'=>'Disliked'
            ],200);
        }
    }

     //like or unlike comment
     public function CommentLikeOrUnlike($apiKey, $authId, $userId, $commentId){
        $comment = Comment::find($commentId);
 
        if(!$comment){
            return response([
                'message'=>"comment not found."
            ], 403);
        }

        $like = $comment->likes()->where('user_id', $userId)->first();

        //if not liked then like

        if(!$like){
            CommentLike::create([
                'comment_id'=>$commentId,
                'user_id'=>$userId,
                'auth_id'=>$authId
            ]);

            return response([
                'message'=>'Liked'
            ], 200);
        }else{
            //else dislike
            $like->delete();
            return response([
                'message'=>'Disliked'
            ],200);
        }
    }
}
