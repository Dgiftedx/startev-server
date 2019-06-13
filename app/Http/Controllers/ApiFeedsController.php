<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use App\Models\FeedComment;
use App\Models\User;
use App\Models\UserHiddenFeed;
use Carbon\Carbon;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
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
        $id = auth()->user()->id;

        $hiddenFeeds = UserHiddenFeed::where('user_id','=',$id)->pluck('feed_id')->toArray();

        $this->feed->with('feedComments')->with('feedComments.user')->whereNotIn('id', $hiddenFeeds)->orderBy('id','desc')
            ->get()
            ->mapToGroups(function ($item) use (&$feeds) {
                $feeds[] = [
                    'id' => $item->id,
                    'postType' => $item->post_type,
                    'roleData' => HelperController::fetchRoleData($item->user_id),
                    'user' => $this->user->where('id','=',$item->user_id)->first(['id','name','avatar']),
                    'hasLiked' => $item->hasLiked,
                    'title' => $item->title,
                    'likers' => $item->likers()->get(),
                    'comments'=> $item->feedComments,
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
            'user' => $this->user->where('id','=',$data['user_id'])->first(['id','name','avatar']),
            'title' => $data['title'],
            'body' => $data['body'],
            'time' => Carbon::now()
        ];

        $databaseUpdate = [
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'body' => $data['body'],
            'post_type' => $data['post_type'],
            'time' => Carbon::now()
        ];

        if (!is_null($request->file('image')) && $request->file('image')->isValid()){
            //upload image and add link to array
            $path = $this->url. '/storage'. HelperController::processImageUpload($request->file('image'),$data['title'],'feeds',640,800);
            $feedData['image'] = $path;
            $databaseUpdate['image'] = $path;


//            Cloudder::Upload($request->file('image'));
//            $image = Cloudder::getPublicId();
//
//            $databaseUpdate['image'] = $image;
//            $feedData['image'] = $image;

        }

        if ($request->has('video') && !is_null($data['video'])){
            //upload video is it's a file and add link to array.
            //if it's link add it to array without saving
        }

        if ($request->has('link') && !is_null($data['link'])){
            //Crawl link information and save inside link in array
        }

        $update = $this->feed->create($databaseUpdate);

        $feedData['id'] = $update->id;

        Pusher::trigger('my-channel', 'my-event', $feedData);

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

        $people = $this->user->inRandomOrder()->whereIn('id',$toFollow)->take(6)->get(['id','name','avatar']);

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

    public function toggleLike($userId, $feed)
    {

        $targetFeed = Feed::find($feed);

        $user = $this->user->find($userId);

        $user->toggleLike($targetFeed);

        if ($user->hasLiked($targetFeed)){
            $message = "You Liked {$targetFeed->title}.";
            $targetFeed->update(['hasLiked' => true]);
        }else{
            $targetFeed->update(['hasLiked' => false]);
            $message = "You've UnLiked ({$targetFeed->title}).";
        }

        $targetFeed->hasliked = $user->hasLiked($targetFeed);

        return response([
            'message' => $message,
            'hasLiked' => $user->hasLiked($targetFeed),
            'likers' => $targetFeed->likers()->get(),
            'targetFeed' => $targetFeed
        ]);
    }

    public function postComment( Request $request, $userId )
    {
        $comment = $request->all();
        $comment['user_id'] = $userId;
        $comment['feed_id'] = $comment['feedId'];
        $comment['comment'] = $comment['text'];

        unset($comment['feedId']);
        unset($comment['text']);

        $new = FeedComment::create($comment);

        $update = Feed::with('feedComments')->with('feedComments.user')->find($new->feed_id);
        return response()->json([
            'success' => true,
            'feed_id' => $new->feed_id,
            'comments' => $update->feedComments
        ]);

    }

    public function showSingle($feed_id)
    {
        $feed = Feed::with('user')->with('feedComments')->with('feedComments.user')->find($feed_id);
        $likers = $feed->likers()->get();
        return response()->json(['likers' => $likers, 'feed' => $feed]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function hideFeed( Request $request )
    {
        $data = $request->all();
        //hide feed for user
        UserHiddenFeed::create($data);
        return response()->json(['success' => true]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFeed( Request $request )
    {

        $data = $request->all();

        $feed = $this->feed->find($data['feed_id']);

        $hidden = UserHiddenFeed::where('user_id','=',$data['user_id'])->where('feed_id','=',$data['feed_id'])->first();

        if (!is_null($hidden)) {
            //remove feed from user hidden feeds
            UserHiddenFeed::find($hidden->id)->delete();
        }

        //remove image from storage
        if (!is_null($feed->image)) {
            HelperController::removeImage($feed->image);
        }


        //remove comments if they exists on feed thread
        $comments = FeedComment::where('feed_id','=', $data['feed_id'])->get();

        if (count($comments) > 0) {
            foreach ($comments as $comment) {
                FeedComment::find($comment->id)->delete();
            }
        }

        $feed->delete();

        return response()->json(['success' => true]);
    }

}
