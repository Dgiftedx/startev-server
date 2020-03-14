<?php

namespace App\Http\Controllers;

use App\BlockUser;
use App\Http\Controllers\Mail\MailController;
use App\Jobs\PostVideoConverter;
use App\Jobs\SendEmailNotification;
use App\Models\Feed;
use App\Models\FeedComment;
use App\Models\User;
use App\Models\UserHiddenFeed;
use App\Models\UserNotification;
use App\ReportUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ReportFeed;
use JD\Cloudder\Facades\Cloudder;
use Nahid\Linkify\Facades\Linkify;
use Pusher\Laravel\Facades\Pusher;
use App\Providers\PushNotification;
use App\Repositories\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


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
        $id = auth()->user()->id;

        $hiddenFeeds = UserHiddenFeed::where('user_id','=',$id)->pluck('feed_id')->toArray();

        $feeds = $this->feed->with('feedComments')
            ->with('feedComments.user')
            ->whereNotIn('id', $hiddenFeeds)
            ->where('status', 1)
            ->orderBy('id','desc')
            ->paginate(5);

        foreach ($feeds as $feed ) {
            $feed->postType = $feed->post_type;
            $feed->roleData = HelperController::fetchRoleData($feed->user_id);
            $feed->user = $this->user->where('id','=',$feed->user_id)->first(['id','slug','name','avatar']);
            $feed->likers = $feed->likers()->get();
            $feed->comments = $feed->feedComments;
            $feed->content = $feed->body;
        }


        return response()->json($feeds);
    }


    public function myFeeds($user_id)
    {
        $feeds = [];

        $hiddenFeeds = UserHiddenFeed::where('user_id','=',$user_id)->pluck('feed_id')->toArray();

        $this->feed->with('feedComments')
            ->with('feedComments.user')
            ->whereNotIn('id', $hiddenFeeds)
            ->where('user_id', '=', $user_id)
            ->where('status', 1)
            ->orderBy('id','desc')
            ->get()
            ->mapToGroups(function ($item) use (&$feeds) {
                $feeds[] = [
                    'id' => $item->id,
                    'postType' => $item->post_type,
                    'roleData' => HelperController::fetchRoleData($item->user_id),
                    'user' => $this->user->where('id','=',$item->user_id)->first(['id','slug','name','avatar']),
                    'hasLiked' => $this->user->where('id','=',$item->user_id)->first()->hasLiked($item), //$item->hasLiked,
//                    'title' => $item->title,
                    'likers' => $item->likers()->get(),
                    'comments'=> $item->feedComments,
                    'image' => $item->image,
                    'images' => $item->images,
                    'video' => $item->video,
                    'link' => $item->link,
                    'views' => $item->views,
                    'content' => $item->body,
                    'time' => $item->time
                ];
                return [];
            });


        return response()->json($feeds);
    }


    public function uploadVideo( Request $request )
    {
        //check for null field
        if (is_null($request->file('file'))){
            return response(['error' => 'File upload field is null. Please insert a video']);
        }

        //check for acceptable file type
        $fileExtension = $request->file('file')->getClientOriginalExtension();

        $allowed = ['mp4','avi','asf','mov','qt','mpg','mpeg','mpeg-4','wmv','3gp','webm','mkv'];

        if (!in_array($fileExtension, $allowed)) {
            return response()->json(['error' => "Invalid file type."], 404);
        }

        $data = $request->all();

        $videoTmp = time();

        $file = request('file');
        $fileName = $videoTmp.'.'.$fileExtension;
        $path = public_path().'/uploads/';
        $file->move($path, $fileName);

        $data['file'] = $fileName;

        // Send Video Conversion and Upload to Job
        dispatch(new PostVideoConverter($data));
        // Return response
        return response()->json(['success' => true, 'message' => 'Your video upload is processing. You\'ll receive notification update once it\'s done']);
    }


    public function updateFeed( Request $request )
    {
        $id = $request->get('id');
        Feed::find($id)->update(['body' => $request->get('body')]);
        return response()->json(['success' => true]);
    }

    public function post( Request $request )
    {
        $data = $request->all();
        $mailContent = [];

        $convertToLink = new \Nahid\Linkify\Linkify(array('attr' => array('class' => 'feed-link', 'target' => '_blank')));

        if (is_null($data['post_type'])) {
            $data['post_type'] = "post";
        }
        $feedData = [
            'postType' => $data['post_type'],
            'roleData' => HelperController::fetchRoleData($data['user_id']),
            'user' => $this->user->where('id','=',$data['user_id'])->first(['id','slug','name','avatar']),
//            'title' => $data['title'],
            'body' => $convertToLink->process($data['body']),
            'hasLiked' => 0,
            'time' => Carbon::now()
        ];

        $databaseUpdate = [
            'user_id' => $data['user_id'],
//            'title' => $data['title'],
            'body' => $convertToLink->process($data['body']),
            'post_type' => $data['post_type'],
            'hasLiked' => 0,
            'time' => Carbon::now()
        ];

        if (!is_null($request->file('images')) && count($request->file('images'))){


            foreach ($request->file('images') as $file) {
                //upload image and add link to array
                $path = '/storage'. HelperController::processImageUpload($file, "homeFeed",'feeds',640,800);
                $feedData['images'][] = $path;
                $databaseUpdate['images'][] = $path;
            }

        }

        $update = $this->feed->create($databaseUpdate);

        $feedData['id'] = $update->id;

        Pusher::trigger('my-channel', 'my-event', $feedData);
        $user = User::find($data['user_id']);

        UserNotification::create(['user_id' => $data['user_id'], 'target_id' => 0, 'title' => "New feed has been published", 'content' => "{$user->name} published to news feed."]);

        $mailContent['message'] = "Your Post, <strong>{$update->title}</strong>, has been published successfully. Login to see user reactions.";
        $mailContent['to'] = $user->email;

        //send notification mail
        dispatch(new SendEmailNotification($mailContent));
//            Log::info("user".$user);


        //send push notification to recipient if offline
        $this->handleOfflineFeedNotification($user);


        return response()->json(['success' => true, 'message' => 'Post published successfully']);
    }

    //Send push notification to offline followers on new feed
    private  function handleOfflineFeedNotification($sender)
    {
        $recipients = $sender->followers()->where('push_token', '!=', '')->pluck('id');
//        Log::info("Rrecipients ".$recipients);
        $pushData['content'] = [
            'data' => ['type'=>PushNotification::$NewsFeed],
            'title' => 'News Feed Update',
            'body' => "New Post from " . $sender->name
        ];
        if(count($recipients) > 0) {
            $pushData['users'][] = $recipients;

            (new Notification)->sendPush($pushData);
        }
        if (!$sender->isOnline()) {
            $pushData['content']['body'] =
                "Post Published";
            $pushData['content']['body'] =
                "Your Post Has Been Published.";
            $pushData['users'] = [$sender->id];
            (new Notification)->sendPush($pushData);
        }

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
        $followers = $this->followerIds($userId);
        //combine mentors and business ids
        $combined = array_merge($mentors, $business, $followers);
        //exclude already followed people, also the current user id from combined ids
        $followings[] = $userId;
        $toFollow = array_diff($combined, $followings);

        $people = $this->user->inRandomOrder()->whereIn('id',$toFollow)->whereNotNull('avatar')->take(10)->get(['id','slug','name','avatar']);

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

    public function followerIds( $user_id )
    {
        $user = $this->user->find($user_id);
        return $user->followers()->pluck('id')->toArray();
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

        $feed = Feed::find($comment['feed_id']);
        $commentUser = User::find($comment['user_id']);

        UserNotification::create(['user_id' => $comment['user_id'], 'target_id'=> $feed->user_id, 'title' => 'Commented on Your Post', 'content' => "{$commentUser->name} commented on your post ({$feed->title})- ."]);

        unset($comment['feedId']);
        unset($comment['text']);

        $new = FeedComment::create($comment);

        $update = Feed::with('feedComments')->with('feedComments.user')->find($new->feed_id);

//        dd($feed->user_id, $userId);

        //send push notification to feed user if offline
        $this->handleOfflineCommentNotification($comment['user_id'], $feed->user_id);

        return response()->json([
            'success' => true,
            'feed_id' => $new->feed_id,
            'comments' => $update->feedComments
        ]);

    }

    //Send push notification to offline User on new comment
    private  function handleOfflineCommentNotification($sender, $recipient)
    {
        if($sender != $recipient) {
            $sender = User::find($sender);

            $pushData['content'] = [
                'data' => ['type' => PushNotification::$NewsFeed],
                'title' => 'News Feed Update',
                'body' => $sender->name . " commented on your post."
            ];
            $pushData['users'][] = $recipient;

            (new Notification)->sendPush($pushData);
        }

//        dd($pushData);
    }


    public function showSingle($feed_id)
    {

        $feed = [];

        $this->feed->with('feedComments')
            ->with('feedComments.user')
            ->where('id', '=', $feed_id)
            ->get()
            ->mapToGroups(function ($item) use (&$feed) {
                $feed = [
                    'id' => $item->id,
                    'postType' => $item->post_type,
                    'roleData' => HelperController::fetchRoleData($item->user_id),
                    'user' => $this->user->where('id','=',$item->user_id)->first(['id','slug','name','avatar']),
                    'hasLiked' => $item->hasLiked,
//                    'title' => $item->title,
                    'likers' => $item->likers()->get(),
                    'comments'=> $item->feedComments,
                    'image' => $item->image,
                    'images' => $item->images,
                    'video' => $item->image,
                    'views' => $item->views,
                    'link' => $item->link,
                    'content' => $item->body,
                    'time' => $item->time
                ];
                return [];
            });

        return response()->json(['feed' => $feed]);
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


        if (!is_null($feed->images) && count($feed->images) > 0) {
            foreach ($feed->images as $image) {
                HelperController::removeImage($image);
            }
        }

        //remove comments if they exists on feed thread
        $comments = FeedComment::where('feed_id','=', $data['feed_id'])->get();

        if (count($comments) > 0) {
            foreach ($comments as $comment) {
                FeedComment::find($comment->id)->delete();
            }
        }


        $user = User::find($data['user_id']);
        $mailContent['message'] = "You have deleted <b>{$feed->title} </b> successfully. <br/> Note that users' comments on this post and any other related actions has been removed as well.";
        $mailContent['to'] = $user->email;

        dispatch(new SendEmailNotification($mailContent));

        $feed->delete();

        return response()->json(['success' => true]);
    }



    public function increaseFeedViews($id)
    {
        $feed = $this->feed->find($id);
        $feed->update(['views' => $feed->views + 1]);
        $update = $this->feed->find($id);

        return response()->json($update);
    }

    public function feed_reporting(Request $request) {
        $reports = $request->all();
        $reports['status'] = 0;
        reportFeed::create([
            'user_id' => $reports['user_id'],
            'post_id' => $reports['feed_id'],
            'reports' => $reports['reports'],
            'status' => $reports['status']]
        );
        return response()->json([
            'success' => 'Report submitted'
        ]);
    }
    public function report_users(Request $request) {
        $reports = $request->all();
        $reports['status'] = 0;
        ReportUser::create([
                'user_id' => $reports['user_id'],
                'reported_user_id' => $reports['reported_user'],
                'status' => $reports['status']]
        );
        return response()->json([
            'success' => 'Report submitted'
        ]);
    }
    public function blockUser(Request $request) {
        $reports = $request->all();
        $reports['status'] = 1;
        BlockUser::create([
                'user_id' => $reports['user_id'],
                'blocked_user_id' => $reports['blocked_user'],
                'status' => $reports['status']]
        );
        return response()->json([
            'success' => 'Blocked contact will no longer be able to send you messages.'
        ]);
    }
    public function Block_reportUser(Request $request) {
        $reports = $request->all();
        $reports['status'] = 1;
        BlockUser::create([
                'user_id' => $reports['user_id'],
                'blocked_user_id' => $reports['offend_id'],
                'status' => $reports['status']]
        );
        $reports['status'] = 0;
        ReportUser::create([
                'user_id' => $reports['user_id'],
                'reported_user_id' => $reports['offend_id'],
                'status' => $reports['status']]
        );
        return response()->json([
            'success' => 'Contact reported and blocked.'
        ]);
    }

}
