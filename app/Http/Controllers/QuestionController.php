<?php

namespace App\Http\Controllers;
use App\Models\Question;

use Illuminate\Http\Request;

class QuestionController extends Controller
{
    //
    
    //get all questions
    public function search($apiKey, $authId, $query){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // } 
        $GLOBALS['authId'] = $authId;
        return response([
            'questions'=>Question::orderBy('created_at', 'desc')->with('user:id,username,points,profile_picture,about,last_visit,token')->withCount('answers', 'likes')
            ->with('likes',  function($like){
                return $like->where('auth_id', $GLOBALS['authId'])
                ->select('id', 'user_id', 'question_id')->get();
            })->where('body', 'LIKE', '%'.$query.'%')->where('visible', 1)
            ->get()
        ], 200);
    }
 

    
    //get all questions
    public function getQuestions($apiKey, $authId){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }

        $GLOBALS['authId'] = $authId;

        return response([
            'questions'=>Question::orderBy('created_at', 'desc')->
            with('user:id,username,profile_picture,points,about,last_visit,token')
            ->withCount('answers','likes')
            ->with('likes',  function($like){
                return $like->where('auth_id', $GLOBALS['authId'])
                ->select('id', 'user_id', 'question_id')->get();
            })->where('visible', 1)
            ->get()
        ], 200);
    }
 
    //get user questions
    public function myQuestion($apiKey, $authId, $userId){
        if($apiKey != config('services.key.api_key')){
            return response([
                'message'=>"invalid api key"
            ], 403);
        }

        $GLOBALS['authId'] = $authId;
            return response([
                'questions'=>Question::orderBy('created_at', 'desc')->with('user:id,username,profile_picture,about,last_visit,token')->withCount('answers', 'likes')
                    ->with('likes', function($like){
                        return $like->where('auth_id', $GLOBALS['authId'])
                        ->select('id', 'user_id', 'question_id')->get();
                    })->where('user_id', $userId)->where('visible', 1)->get()
            ], 200);
    }
     
     //get user questions
     public function singleQuestion($apiKey, $authId, $questionId){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }

        $GLOBALS['authId'] = $authId;

        return response([
            'questions'=>Question::with('user:id,username,profile_picture,about,points,last_visit,token')->where('id', $questionId)->
            withCount('comments', 'likes')->with('likes', function($like){
                return $like->where('auth_id', $GLOBALS['authId'])
                ->select('id', 'user_id', 'question_id')->get();
            })->get()
        ], 200);
}  
   
    
    //create a question
    public function createQuestion(Request $request, $apiKey, $userId){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }
        //validate fields
        $attrs =  $request->validate([
            'question'=>'required|string',
            'title'=>'required|string',
            'category'=>'required|string'        
        ]);
         
     
       $urls = $this->saveFiles($request->urls, 'image', 'images');
      
        $question = Question::create([
            'question'=>$attrs['question'],
            'title'=>$attrs['title'],
            'category'=>$attrs['category'],
            'visible'=>1,
            'user_id'=>$userId,
            'urls'=>$urls
        ]);

        return response([
            'question'=>$question
        ], 200);
    }

 
    //update a question
    public function update(Request $request, $apiKey, $questionId){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }

        $question  = Question::find($questionId);

        if(!$question){
            return response([
                'message'=>"Question not found."
            ], 403);
        }

        //validate fields
        $attrs =  $request->validate([
            'question'=>'nullable|string',
        ]);
        
        $question->update([
            'question'=>$attrs['question'],
        ]);

        return response([
            'question'=>$question
        ], 200);
    }

    //delete question
     public function destroy($apiKey, $questionId){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }
        $question  = Question::find($questionId);

        if(!$question){
            return response([
                'message'=>"Question not found."
            ], 403);
        }

        $question->delete();
        $question->update([
            'visible'=>0,
        ]);

        return response([
            'message'=>"Question deleted."
        ], 200);

}
}
