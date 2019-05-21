<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Pusher\Laravel\Facades\Pusher;

class ApiFeedsController extends Controller
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


    public function index()
    {
        $feeds = [];

        $this->feed->orderBy('id','desc')
            ->get()
            ->mapToGroups(function ($item) use (&$feeds) {
                $feeds[] = [
                    'id' => $item->id,
                    'postType' => $item->post_type,
                    'roleData' => HelperController::fetchRoleData($item->user_id),
                    'user' => $this->user->where('id','=',$item->user_id)->get(['id','name','avatar']),
                    'title' => $item->title,
                    'image' => $item->image,
                    'video' => $item->image,
                    'link' => $item->link,
                    'content' => $item->body,
                    'createdAt' => $item->time
                ];
                return [];
        });


        return response()->json($feeds);
    }

    public function post( Request $request )
    {
        $data = $request->all();

        $feedData = [
            'postType' => $data['post_type'],
            'roleData' => HelperController::fetchRoleData($data['user_id']),
            'user' => $this->user->where('id','=',$data['user_id'])->get(['id','name','avatar']),
            'title' => $data['title'],
            'body' => $data['body'],
            'time' => Carbon::now()
        ];

        if (!is_null($request->file('image')) && $request->file('image')->isValid()){
            //upload image and add link to array
            $path = $this->url. '/storage'. HelperController::processImageUpload($request->file('image'),$data['title'],'feeds',640,800);
            $feedData['image'] = $path;
        }

        if ($request->has('video') && !is_null($data['video'])){
            //upload video is it's a file and add link to array.
            //if it's link add it to array without saving
        }

        if ($request->has('link') && !is_null($data['link'])){
            //Crawl link information and save inside link in array
        }

        Pusher::trigger('my-channel', 'my-event', $feedData);

        $feedData['user_id'] = $data['user_id'];
        unset($feedData['user']);
        unset($feedData['roleData']);
        $feedData['post_type'] = $feedData['postType'];

        unset($feedData['postType']);

        $this->feed->create($feedData);

        return response()->json(['success' => true, 'message' => 'Post published successfully']);
    }

    /**
     * Get people to follow
     * @return \Illuminate\Http\JsonResponse
     */
    public function people()
    {
        //get authenticated user
        $userId = auth()->user()->id;
        $people = $this->gatherPeopleToFollow($userId);
        return response()->json($people);
    }

    public function gatherPeopleToFollow($userId)
    {
        //get mentors id array
        $mentors = HelperController::realMentorsId();
        //get business/companies id array
        $business = HelperController::realBusinessId();

        //get already followed ids
        $followings = $this->followingIds($userId);
        //combine mentors and business ids
        $combined = array_merge($mentors, $business);
        //exclude already followed people, also the current user id from combined ids
        $followings[] = $userId;
        $toFollow = array_diff($combined, $followings);

        $people = $this->user->whereIn('id',$toFollow)->take(10)->get(['id','name','avatar']);

        foreach ($people as $person) {
            $person->roleData = HelperController::fetchRoleData($person->id);
        }

        return $people;
    }

    public function followingIds( $user_id )
    {
        $user = $this->user->find($user_id);
        return $user->followings()->pluck('id')->toArray();
    }


    public function followings( $user_id )
    {
        $user = $this->user->find($user_id);
        return $user->followings()->get();
    }

}
