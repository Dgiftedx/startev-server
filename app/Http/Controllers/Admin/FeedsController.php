<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Feed;
use App\Models\FeedComment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FeedsController extends Controller
{
    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function getFeeds()
    {
        $feeds = Feed::where('status', '!=' , -1)->orderBy('id', 'DESC')->paginate(10);
        foreach ($feeds as $feed ) {
            $feed->postType = $feed->post_type;
            $feed->user = User::where('id','=',$feed->user_id)->first(['id','slug','name','avatar']);
            $feed->likers = $feed->likers()->get();
            $feed->comments = $feed->feedComments;
            $feed->content = $feed->body;
        }
        return view('pages.feed.feeds')->with(['feeds'=>$feeds, 'status'=>'', 'sort_by'=>'id', 'sort_type'=>'desc']);
    }

    function fetch_data(Request $request){
        if(request()->ajax()) {
            $sort_by = $request->get('sortby');
            $status = $request->get('status');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            if($status == null){
                $feeds = Feed::where([
                        ['status', '!=' , -1],
                        ['id', 'like', '%' . $query . '%']
                    ])
                    ->orWhere([
                        ['status', '!=', -1],
                        ['body', 'like', '%' . $query . '%']
                    ])
                    ->orderBy($sort_by, $sort_type)
                    ->paginate(10);
//            dd($feeds);
            }
            else {
                $feeds = Feed::where([
                    ['status', '=', $status],
                    ['id', 'like', '%' . $query . '%']
                ])
                    ->orWhere([
                        ['status', '=', $status],
                        ['body', 'like', '%' . $query . '%']
                    ])
                    ->orderBy($sort_by, $sort_type)
                    ->paginate(10);
            }

            foreach ($feeds as $feed) {
                $feed->postType = $feed->post_type;
                $feed->user = User::where('id', '=', $feed->user_id)->first(['id', 'slug', 'name', 'avatar']);
                $feed->likers = $feed->likers()->get();
                $feed->comments = $feed->feedComments;
                $feed->content = $feed->body;

            }

            return view('pages.feed.feeds_data', compact(['feeds', 'status', 'sort_by', 'sort_type']))->render();
        }
        $this->getFeeds();
    }

    function trash_data(Request $request){
        if(request()->ajax()) {
            $id = $request->get('id');
//            dd($id);
            $feed = Feed::find($id);
            $feed->status = -1;
            $feed->save();
        }
    }

    function publish_data(Request $request){
        if(request()->ajax()) {
            $id = $request->get('id');
//            dd($id);
            $feed = Feed::find($id);
            $feed->status = 1;
            $feed->save();
        }
    }

    function review_data(Request $request){
        if(request()->ajax()) {
            $id = $request->get('id');
//            dd($id);
            $feed = Feed::find($id);
            $feed->status = 0;
            $feed->save();
        }
    }

    public function view($id){
        $feed = Feed::find($id);
        $feed->postType = $feed->post_type;
        $feed->user = User::where('id','=',$feed->user_id)->first(['id','slug','name','avatar']);
        $feed->likers = $feed->likers()->get();
        $feed->comments = $feed->feedComments;
        $feed->content = $feed->body;

//         dd($feed);
       return view('pages.feed.feed')->with('feed', $feed);
    }

    public function feedsSettings(){
        $table = 'feeds';
        $database = env('DB_DATABASE');
        $results = DB::select('
            select column_default 
            from information_schema.columns 
            where 
                table_schema = ? 
                and table_name = ?
        ', [$database, $table]);

        // Flatten the results to get an array of the default values
        $default = $results[14]->column_default;

//        dd($results[14]->column_default);
        return view('pages.feed.settings')->with('value', $default);
    }

    public function updateFeedSetting(Request $request)
    {
        if(request()->ajax()) {
            $status = $request->get('default');
//            dd($status);

            DB::statement('ALTER TABLE `feeds` CHANGE `status` `status` INT(11) NOT NULL DEFAULT '.$status.';');
            $table = 'feeds';
            $database = env('DB_DATABASE');
            $results = DB::select('
            select column_default 
            from information_schema.columns 
            where 
                table_schema = ? 
                and table_name = ?
        ', [$database, $table]);

            // Flatten the results to get an array of the default values
            $value = $results[14]->column_default;
            return view('pages.feed.settings_data', compact('value'))->render();
        }
    }
}
