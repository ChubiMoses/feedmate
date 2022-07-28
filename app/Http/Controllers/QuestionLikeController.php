<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Question;
use App\Models\QuestionLike;
use App\Models\AnswerLike;
use App\Models\Answer;

class QuestionLikeController extends Controller
{
    //like or unlike
    public function questionLikeOrUnlike($apiKey, $authId, $userId, $questionId){
        $question  = Question::find($questionId);

        if(!$question){
            return response([
                'message'=>"Question not found."
            ], 403);
        }

        $like = $question->likes()->where('user_id', $userId)->first();

        //if not liked then like

        if(!$like){
            QuestionLike::create([
                'question_id'=>$questionId,
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

     //like or unlike answer
     public function answerLikeOrUnlike($apiKey, $authId, $userId, $answerId){
        $answer = Answer::find($answerId);
 
        if(!$answer){
            return response([
                'message'=>"answer not found."
            ], 403);
        }

        $like = $answer->likes()->where('user_id', $userId)->first();

        //if not liked then like

        if(!$like){
            AnswerLike::create([
                'answer_id'=>$answerId,
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
