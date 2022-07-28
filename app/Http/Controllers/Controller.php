<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;



class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function saveImage($image, $path = "public"){
        if(!$image){
            return null;
        }
        if($image == ''){
            return null;
        }

        $filename = time().'.png';
        //save image
        Storage::disk($path)->put($filename, base64_decode($image));

        //retutn path
        return URL::to("/")."/storage/".$path."/".$filename;
    }

    public function saveFiles($files, $fileType, $path = "public"){
        $urls = null;
        if(!$files){
            return null;
        }
        
        if($files != ''){
            $list = explode(",", $files);
            foreach($list as $image){
             $new_name = '';
                if($fileType == 'image'){
                    $new_name = rand().'.png';
                }else if($fileType == 'pdf'){
                    $new_name = rand().'.pdf';
                }else{
                     $new_name = rand().'.mp4';
                }
             Storage::disk($path)->put($new_name, base64_decode($image));
             $url =  URL::to("/")."/storage/".$path."/".$new_name;
             $urls=$urls.$url.",";
         }
        }
       return $urls;
    }
}
