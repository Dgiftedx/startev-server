<?php

namespace App\Http\Controllers;

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

}
