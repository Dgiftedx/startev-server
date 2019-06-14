<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class ApiUserNotificationController extends Controller
{
    protected $user;

    protected $notification;

    protected $url;


    /**
     * ApiUserNotificationController constructor.
     * @param User $userModel
     * @param UserNotification $notificationModel
     */
    public function __construct( User $userModel, UserNotification $notificationModel )
    {
        $this->middleware('auth:api');
        $this->user = $userModel;
        $this->url = url('/');
        $this->notification = $notificationModel;
    }


    public function index()
    {
        $notifications = $this->notification->where('user_id', '=', auth()->user()->id)->with('user')->get();
        return response()->json($notifications);
    }


    public function widgetNotifications($user_id)
    {
        $notifications = $this->unreadNoty($user_id);
        return response()->json($notifications);
    }

    public function unreadNoty($user_id)
    {
        return $this->notification->with('user')->where('user_id','=',$user_id)->where('status', '=','unread')->take(6)->get();
    }

}
