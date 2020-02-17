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
        return response()->json(['success'=>$this->user]);
        $user = User::find($this->user->id);
        $data = $request->all();
        if(!isset($data['token']))
            return response()->json(['error'=>'Invalid Data']);
        $user->update(['push_token'=>$data['token']]);
        return response()->json(['success'=>true]);
    }

}
