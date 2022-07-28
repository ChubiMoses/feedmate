<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;


class ReportController extends Controller
{
   
    public function getReports($apiKey){
        return response([
            'reports'=>Report::get()
        ], 200);
     }  

     public function createReport(Request $request,$apiKey){
        $report = Report::create([
            'report'=>$request->report,
            'user_id'=>$request->user_id,
            'type'=>$request->type,
            'post_id'=>$request->post_id
        ]);

        return response([
            'message'=>"Report created",
        ], 200);
    }
}
