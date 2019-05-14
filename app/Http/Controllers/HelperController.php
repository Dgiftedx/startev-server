<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
}
