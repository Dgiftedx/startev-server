<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Trainee;
use App\Models\User;
use Illuminate\Http\Request;

class ApiPublicationController extends Controller
{
    protected $user;

    protected $url;

    protected $publication;
    /**
     * ApiAccountController constructor.
     * @param User $userModel
     * @param Publication $publicationModel
     */
    public function __construct( User $userModel, Publication $publicationModel )
    {
        $this->middleware('auth:api');
        $this->user = $userModel;
        $this->publication = $publicationModel;
        $this->url = url('/');
    }


    public function index()
    {

        $publications = [];

        $this->publication
            ->with('user')
            ->with('user.business')
            ->with('user.mentor')
            ->orderBy('id','desc')
            ->get()
            ->mapToGroups(function ($item) use (&$publications) {
                $publications[] = [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'title' => $item->title,
                    'image' => $item->image,
                    'videoLink' => $item->videoLink,
                    'videoSource'=> $item->videoSource,
                    'likers' => $item->likers()->get(),
                    'user' => $item->user
                ];
                return [];
            });

        $userConnections = [];

        if (auth()->check()){
            $userConnections = Trainee::where('trainee_id','=',auth()->user()->id)->get();
        }

        return response()->json(['publications' => $publications, 'connections' => $userConnections]);
    }


    public function store( Request $request )
    {
        $data = $request->all();

        if ($data['image'] !== null){
            if(!is_null($request->file('image')) && $request->file('image')->isValid()){
                $path = $this->url. '/storage'. HelperController::processImageUpload($request->file('image'),$data['title'],'feeds',640,800);
                $data['image'] = $path;
            }
        }else{
            unset($data['image']);
        }

        //strip off unwanted html tags
        $data['content'] = strip_tags($data['content'], "<p><b><a><img><h1><h2><h3><h4><h5><h6>");

        $this->publication->create($data);

        return response()->json(['success' => true, 'message' => 'Publication Published to targeted audience, You can find this in Knowledge hub']);
    }


    public function toggleLike($userId, $publicationID)
    {

        $targetPublication = $this->publication->find($publicationID);

        $user = $this->user->find($userId);

        $user->toggleLike($targetPublication);

        if ($user->hasLiked($targetPublication)){
            $message = "You Liked {$targetPublication->title}.";
            $user->update(['liked_publication' => true]);
        }else{
            $user->update(['liked_publication' => false]);
            $message = "You've UnLiked ({$targetPublication->title}).";
        }

        $user->hasliked = $user->hasLiked($targetPublication);

        return response([
            'message' => $message,
            'hasLiked' => $user->hasLiked($targetPublication),
            'likers' => $targetPublication->likers()->get(),
            'targetPublication' => $targetPublication
        ]);
    }


    public function show( $publicationId )
    {
        $publication = $this->publication
            ->with('user')
            ->with('user.business')
            ->with('user.mentor')
            ->find($publicationId);
        $likers = $publication->likers()->get();


        $userConnections = [];

        if (auth()->check()){
            $userConnections = Trainee::where('trainee_id','=',auth()->user()->id)->get();
        }
        return response()->json(['publication' => $publication,'likers' => $likers, 'connections' => $userConnections]);
    }
}
