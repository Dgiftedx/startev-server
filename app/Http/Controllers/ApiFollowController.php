<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Mail\MailController;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Http\Request;

class ApiFollowController extends Controller
{
    protected $user;

    protected $feed;

    protected $url;


    /**
     * ApiAccountController constructor.
     * @param User $userModel
     * @param Feed $feedModel
     */
    public function __construct( User $userModel, Feed $feedModel )
    {
        $this->middleware('auth:api');
        $this->user = $userModel;
        $this->feed = $feedModel;
        $this->url = url('/');
    }

    public function follow($userId, $target)
    {
        $user = $this->user->find($userId);
        $user->follow($target);
        $people = (new ApiFeedsController($this->user, $this->feed))->gatherPeopleToFollow($userId);
        return response()->json(['success' => true, 'people' => $people]);
    }

    public function toggleFollow($userId, $target)
    {

        $user = $this->user->find($userId);
        $user->toggleFollow($target);

        $targetUser = $this->user->find($target);

        if ($user->isFollowing($target)){
            $message = "You are now following {$targetUser->name}. You'll now receive updates from this user";
            MailController::sendNoticeMail($message, $user->email, "New Follow Activity");
        }else{
            $message = "You've stopped following {$targetUser->name}. You'll no longer receive updates from this user";
        }
        return response([
            'message' => $message,
            'people' => (new ApiFeedsController($this->user, $this->feed))->gatherPeopleToFollow($userId),
            'following' => $user->followings()->get(),
            'followers' => $user->followers()->get()
        ]);
    }

}
