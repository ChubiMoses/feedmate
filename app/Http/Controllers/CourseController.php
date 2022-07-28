<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function search($apiKey, $query){
        return response([
            'courses'=>Course::where('course', 'LIKE', '%'.$query.'%')
            ->get()
        ], 200);
    }

     public function getCourses($apiKey){
        return response([
            'courses'=>Course::get()
        ], 200);
     }  

     
     public function createCourse(Request $request, $apiKey){
        $course = Course::create([
            'course'=>$request->course,
        ]);

        return response([
            'message'=>$course,
        ], 200);
    }
}
