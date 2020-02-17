<?php

namespace App\Http\Controllers;

use App\Models\User;
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
    public function __construct(User $userModel)
    {
        $this->user = $userModel;
        $this->middleware('auth:api');
    }


    public function saveToken(Request $request)
    {
        $data = $request->all();
        if (!isset($data['user_id']))
            return response()->json(['error' => 'Invalid Data']);
        $user = User::find($data['user_id']);
        $user->update(['push_token' => $data['token']]);
        return response()->json(['success' => true]);
    }

}
