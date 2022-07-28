<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\Question;


class AnswerController extends Controller
{
     //get all answers
     public function getAnswers($apiKey, $authId, $questionId){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }
        $question = Question::find($questionId);

        if(!$question){
            return response([
                'message'=>'Question not found.'
            ], 403);
        }
        $GLOBALS['authId'] = $authId;

        return response([
            'answers'=>$question->answers()->with('user:id,username,profile_picture,about,points,last_visit,token')->withCount('likes')
            ->with('likes', function($like){ 
                return $like->where('auth_id',  $GLOBALS['authId'])
                ->select('id', 'user_id', 'answer_id')->get();
            })
            ->get()
        ], 200);
    }


     //create  answer
     public function saveAnswer(Request $request, $apiKey, $userId, $questionId){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }
        $question = Question::find($questionId);
 
        if(!$question){
            return response([
                'message'=>'Question not found.'
            ], 403);
        }

        //validate fields
        $attrs =  $request->validate([
            'answer'=>'required|string',
        ]);
        $urls = $this->saveFiles($request->urls, 'image', 'images');
        Answer::create([
            'answer'=>$attrs['answer'],
            'visible'=>1,
            'urls'=>$urls,
            'question_id'=>$questionId,
            'user_id'=>$userId
        ]);


        return response([
            'message'=>'Answer created.'
        ], 200);
    }

    //update a answer
    public function update(Request $request,$apiKey, $id){
        $answer = Answer::find($id);
 
        if(!$answer){
            return response([
                'message'=>'Answer not found.'
            ], 403);
        }


        //validate fields
        $attrs =  $request->validate([
            'answer'=>'required|string'
        ]);

        $answer->update([
            'answer'=>$attrs['answer'],
        ]); 

        return response([
            'message'=>'Answer updated'
        ], 200);
    }


    //delete answers
    public function destroy($apiKey, $answerId){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }

        $answer = Answer::find($answerId);
 
        if(!$answer){
            return response([
                'message'=>'Answer not found.'
            ], 403);
        } 
        
       $answer->delete();
       
        return response([
            'message'=>"Answer deleted",
        ], 200);
    }

}
