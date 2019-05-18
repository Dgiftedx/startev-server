<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class HelperController extends Controller
{
    public static function fetchRoleData( $id )
    {
        $user = User::with('mentor')->with('student')->with('business')->find($id);

        if (count($user->student) > 0){

            return ['data' => $user->student, 'role' => 'student'];
        }else if(count($user->mentor) > 0) {

            return ['data' => $user->mentor, 'role' => 'mentor'];
        }else{

            return ['data' => $user->business, 'role' => 'business'];
        }
    }


    public static function generateIdentifier($length){
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
//        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max-1)];
        }
        return $token;
    }

    public static function processFileUpload( $file , $name)
    {
        //Process new file
        $fileName = '/uploads/' . $name . '.' . $file->getClientOriginalExtension();

        if(Storage::disk('public')->exists($fileName)){
            //remove
            Storage::disk('public')->delete($fileName);
        }
        Storage::disk('public')->putFileAs('uploads', $file, $name . '.' . $file->getClientOriginalExtension());

        return $fileName;
    }

    public static function processImageUpload( $image, $name = 'user', $dir = 'user', $height = 350, $width = 1200 )
    {
        if (!file_exists(storage_path('app/public/'.$dir))) {
            mkdir(storage_path('app/public/'.$dir), 0777, true);
        }
        //Process new image
        $imageName = preg_replace('/\s+/', '', $name);
        $user_image = '/'.$dir.'/header/' . uniqid(rand()) . $imageName . '.' . $image->getClientOriginalExtension();

        $imageR = Image::make($image);
        $imageR = $imageR->resize($width, $height); //width height

        Storage::disk('public')->put($user_image, (string)$imageR->encode());

        return $user_image;
    }


    public static function processFeedImage( $image, $name = 'post', $dir = 'feeds')
    {
        if (!file_exists(storage_path('app/public/'.$dir))) {
            mkdir(storage_path('app/public/'.$dir), 0777, true);
        }
        //Process new image
        $imageName = preg_replace('/\s+/', '', $name);
        $feed_image = '/'.$dir. uniqid(rand()) . $imageName . '.' . $image->getClientOriginalExtension();

        Storage::disk('public')->put($feed_image, $image);

        return $feed_image;
    }


    public static function processSimpleUpload($file, $user_id = null)
    {
        if (!is_null($user_id)){
            $user = User::find($user_id);
            //Remove old image from storage if exists
            if(Storage::disk('public')->exists($user->avatar)){
                //remove
                Storage::disk('public')->delete($user->avatar);
            }
        }

        $path = '/users/avatar/'.self::generateIdentifier(10).'.png';
        Storage::disk('public')->put($path, $file);
        return $path;
    }


}
