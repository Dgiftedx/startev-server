<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ApiAccountController extends Controller
{
    protected $user;

    public function __construct( User $userModel )
    {
        $this->user = $userModel;
        $this->middleware('auth:api');
    }


    public function profile()
    {
        $userId = auth()->user()->id;
        $profile = $this->userData($userId);
        return response()->json(['data' => $profile]);
    }

    public function userData($id)
    {
        $user = $this->user->with('mentor')->with('student')->find($id);
        $result = [];

        if (!is_null($user->mentor)){
            $result['role'] = [
                'student' => false,
                'mentor' => true,
                'name' => 'Mentor'
            ];
            $result['profile'] = $user->mentor;
        }

        if (!is_null($user->student)){
            $result['role'] = [
                'student' => true,
                'mentor' => false,
                'name' => 'Student'
            ];
            $result['profile'] = $user->student;
        }

        return $result;
    }


}
