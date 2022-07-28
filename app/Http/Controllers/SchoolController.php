<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    //
    public function search($apiKey, $query){
        return response([
            'schools'=>School::where('school', 'LIKE', '%'.$query.'%')
            ->get()
        ], 200);
    }

     public function getSchools($apiKey){
        return response([
            'schools'=>School::get()
        ], 200);
     }  

     
     public function createSchool(Request $request, $apiKey){
        $school = School::create([
            'school'=>$request->school,
        ]);

        return response([
            'message'=>$school,
        ], 200);
    }
}
