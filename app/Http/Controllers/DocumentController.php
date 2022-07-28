<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentLike;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    
     public function search($apiKey, $authId, $query){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        $GLOBALS['authId'] = $authId;
        return response([
            'docs'=>Document::where('title', 'LIKE', '%'.$query.'%')
             ->join('schools', 'documents.school_id', '=', 'schools.id')
             ->join('courses', 'documents.course_id', '=', 'courses.id')
             ->orderBy('documents.id', 'DESC')
             ->with('likes',  function($like){
                return $like->where('auth_id', $GLOBALS['authId'])
                ->select('id', 'user_id')->get();
            })
             ->where('visible','=', 0)
             ->get()
        ], 200);
    }

     
    public function verify(Request $request, $apiKey){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }
        return response([
            'docs'=>Document::where('title', 'LIKE', '%'.$request->title.'%')
                ->orWhere('code', 'LIKE', '%'.$request->code.'%')
                ->orWhere('original_title', 'LIKE', '%'.$request->original_title.'%')
                ->join('schools', 'documents.school_id', '=', 'schools.id')
                ->join('courses', 'documents.course_id', '=', 'courses.id')
                ->get()
        ], 200);
    }
 

    public function getDocuments($apiKey, $authId){
        $GLOBALS['authId'] = $authId;
        return response([
            'docs'=>Document::join('schools', 'documents.school_id', '=', 'schools.id')
            ->join('courses', 'documents.course_id', '=', 'courses.id')
            ->join('groups', 'documents.id', '=', 'groups.doc_id', '')
            ->select('documents.*', 'groups.id AS group_id', 'groups.type AS group_type',  'groups.name AS group_name',  'groups.user_id AS group_user_id', 'schools.school', 'courses.course')
            ->with('likes',  function($like){
                return $like->where('auth_id', $GLOBALS['authId'])
                ->select('id', 'user_id')->get();
            })
            ->where('documents.visible', '=', 1)
            ->orderBy('documents.id', 'DESC')
            ->withCount('likes')->get()
        ], 200);
     }  


     public function getMyDocuments($apiKey, $authId,  $userId){
        $GLOBALS['authId'] = $authId;
        return response([
            'docs'=>Document::join('schools', 'documents.school_id', '=', 'schools.id')
            ->join('courses', 'documents.course_id', '=', 'courses.id')
            ->join('groups', 'documents.id', '=', 'groups.doc_id')
            ->select('documents.*', 'groups.id AS group_id', 'groups.type AS group_type',  'groups.name AS group_name',  'groups.user_id AS group_user_id', 'schools.school', 'courses.course')
            ->with('likes',  function($like){
                return $like->where('auth_id', $GLOBALS['authId'])
                ->select('id', 'user_id')->get();
            })->where('documents.user_id', '=', $userId)
              ->withCount('likes')->get()
        ], 200);
     } 

     public function getNewUploads($apiKey, $authId){
        $GLOBALS['authId'] = $authId;
        return response([
            'docs'=>Document::join('schools', 'documents.school_id', '=', 'schools.id')
            ->join('courses', 'documents.course_id', '=', 'courses.id')
            ->join('groups', 'documents.id', '=', 'groups.doc_id', '')
            ->select('documents.*', 'groups.id AS group_id', 'groups.type AS group_type',  'groups.name AS group_name',  'groups.user_id AS group_user_id', 'schools.school', 'courses.course')
            ->with('likes',  function($like){
                return $like->where('auth_id', $GLOBALS['authId'])
                ->select('id', 'user_id')->get();
            })
            ->where('documents.visible', 0)
            ->withCount('likes')->get()
        ], 200);
     }  


     
     public function saveDocument(Request $request, $apiKey, $userId){

        $url = $this->saveFiles($request->pdf, 'pdf', 'documents');

        $docs = Document::create([
            'title'=>$request->title,
            'code'=>$request->code,
            'user_id'=>$userId,
            'category'=>$request->category,
            'original_title'=>$request->original_title,
            'school_id'=>$request->school_id,
            'course_id'=>$request->course_id,
            'bytes'=>$request->bytes,
            'url'=>$url,
            'reads'=>0,
            'visible'=>0,
            'downloads'=>0
        ]);

        return response([
            'message'=>$docs
        ], 200);
    }

    
    public function update(Request $request, $apiKey, $docId){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }

        $doc  = Document::find($docId);

        if(!$doc){
            return response([
                'message'=>"not found."
            ], 403);
        }

        $doc->update([
            'reads'=>$request->reads,
            'downloads'=>$request->downloads,
            'title'=>$request->title,
            'visible'=>$request->visible,
            'code'=>$request->code,
        ]);

        return response([
            'doc'=>$doc
        ], 200);
    }

    
    public function delete(Request $request, $apiKey, $docId){
        // if($apiKey != config('services.key.api_key')){
        //     return response([
        //         'message'=>"invalid api key"
        //     ], 403);
        // }

        $doc  = Document::find($docId);

        if(!$doc){
            return response([
                'message'=>"not found."
            ], 403);
        }

        $doc->delete();

        return response([
            'message'=>"Deleted"
        ], 200);

    }
        
   //like or unlike
    public function DocLikeOrUnlike($apiKey, $authId, $userId, $docId){
        $doc  = Document::find($docId);

        if(!$doc){
            return response([
                'message'=>"not found."
            ], 403);
        }

        $like = $doc->likes()->where('user_id', $userId)->first();

        //if not liked then like

        if(!$like){
            DocumentLike::create([
                'document_id'=>$docId,
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
