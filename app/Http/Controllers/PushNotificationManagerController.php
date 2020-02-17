<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PushNotificationManagerController extends Controller
{
    /**
     * @var User
     */
    protected $user;


    /**
     * ApiAccountController constructor.
     * @param User $userModel
     */
    public function __construct( User $userModel )
    {
        $this->user = $userModel;
        $this->middleware('auth:api');
    }


    public function saveToken( Request $request )
    {
        $data = $request->all();
        if(!isset($data['token']))
            return response()->json(['error'=>'Invalid Data']);
        $this->user->push_token = $data['token'];
        $this->user->save();
        return response()->json(['success'=>true]);
    }

}
