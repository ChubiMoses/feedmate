<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Post;


class PostController extends Controller
{   

    //get all posts
    public function search($apiKey, $authId, $query){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }
        $GLOBALS['authId'] = $authId;
        return response([
            'posts'=>Post::orderBy('created_at', 'desc')->with('user:id,username,profile_picture,about,last_visit,token')->withCount('comments', 'likes')
            ->with('likes',  function($like){
                return $like->where('auth_id', $GLOBALS['authId'])
                ->select('id', 'user_id', 'post_id')->get();
            })->where('body', 'LIKE', '%'.$query.'%')->where('visible', 1)
            ->get()
        ], 200);
    }
 

    
    //get all posts
    public function getPosts($apiKey, $authId){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }

        $GLOBALS['authId'] = $authId;

        return response([
            'posts'=>Post::orderBy('created_at', 'desc')->with('user:id,username,profile_picture,about,last_visit,token')->withCount('comments', 'likes')
            ->with('likes',  function($like){
                return $like->where('auth_id', $GLOBALS['authId'])
                ->select('id', 'user_id', 'post_id')->get();
            })->where('visible', 1)
            ->get()
        ], 200);
    }
 
    //get user posts
    public function myPosts($apiKey, $authId, $userId){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }

        $GLOBALS['authId'] = $authId;
            return response([
                'posts'=>Post::orderBy('created_at', 'desc')->with('user:id,username,profile_picture,about,last_visit,token')->withCount('comments', 'likes')
                    ->with('likes', function($like){
                        return $like->where('auth_id', $GLOBALS['authId'])
                        ->select('id', 'user_id', 'post_id')->get();
                    })->where('user_id', $userId)->where('visible', 1)->get()
            ], 200);
    }
     
     //get user posts
     public function singlePost($apiKey, $authId, $postId){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }

        $GLOBALS['authId'] = $authId;

        return response([
            'posts'=>Post::with('user:id,username,profile_picture,about,last_visit,token')->where('id', $postId)->
            withCount('comments', 'likes')->with('likes', function($like){
                return $like->where('auth_id', $GLOBALS['authId'])
                ->select('id', 'user_id', 'post_id')->get();
            })->get()
        ], 200);
}  
   
    
    //create a post
    public function createPost(Request $request, $apiKey, $userId){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }
        //validate fields
        $attrs =  $request->validate([
            'body'=>'required|string',        
        ]);
         
     
       $urls = $this->saveFiles($request->urls, $request->file_type, 'profiles');
      
        $post = Post::create([
            'body'=>$attrs['body'],
            'visible'=>1,
            'user_id'=>$userId,
            'shared_type'=>$request->shared_type,
            'shared_id'=>$request->shared_id,
            'urls'=>$urls
        ]);

        return response([
            'post'=>$post
        ], 200);
    }

 
    //update a post
    public function update(Request $request, $apiKey, $postId){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }

        $post  = Post::find($postId);

        if(!$post){
            return response([
                'message'=>"Post not found."
            ], 403);
        }

        //validate fields
        $attrs =  $request->validate([
            'body'=>'nullable|string',
        ]);
        
        $post->update([
            'body'=>$attrs['body'],
        ]);

        return response([
            'post'=>$post
        ], 200);
    }

    //delete post
     public function destroy($apiKey, $postId){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }
        $post  = Post::find($postId);

        if(!$post){
            return response([
                'message'=>"Post not found."
            ], 403);
        }

        $post->delete();
        $post->update([
            'visible'=>0,
        ]);

        return response([
            'message'=>"Post deleted."
        ], 200);

}

}