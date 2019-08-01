<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Mail\MailController;
use App\Jobs\SendEmailNotification;
use App\Models\Industry;
use App\Models\Publication;
use App\Models\PublicationCategory;
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


    public function myPublications($user_id)
    {
        $publications = Publication::where('user_id','=',$user_id)->with('category')->orderBy('id','desc')->get();
        return response()->json($publications);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( Request $request )
    {
        $data = $request->all();

        $category = PublicationCategory::where('category_slug', '=', $data['category'])->first();
        $data['category_id'] = $category->id;

        unset($data['category']);

        if ( $request->has('images') && count($request->file('images')) > 0){

            $data['images'] = [];

            foreach ($request->file('images') as $file) {
                //upload image and add link to array
//                $data['images'][] = $this->url. '/storage'. HelperController::processImageUpload($file, $data['title'],'publication',640,800);
                $data['images'][] = '/storage'. HelperController::processImageUpload($file, $data['title'],'publication',640,800);

            }

        }

        if ( $request->has('files') && count($request->file('files')) > 0){

            $data['files'] = [];
            foreach ($request->file('files') as $file) {
                //upload image and add link to array
                $data['files'][] = HelperController::processFileUpload($file, $data['title']);

            }

        }

        //strip off unwanted html tags
        $data['content'] = strip_tags($data['content'], "<p><b><a><img><h1><h2><h3><h4><h5><h6>");

        $pub = $this->publication->create($data);

        $user = User::find($pub->user_id);
        $mailContent['message'] = "You just published <strong>{$pub->title}</strong> to Startev Knowledge Hub. Should there be any mistake, feel free to edit. <br/> Thanks for impacting lives";
        $mailContent['to'] = $user->email;

        dispatch(new SendEmailNotification($mailContent));

        return response()->json(['success' => true, 'message' => 'Publication Published to targeted audience, You can find this in Knowledge hub']);
    }



    public function updatePublication( Request $request, $pub_id )
    {
        $data = $request->all();

        $category = PublicationCategory::where('category_slug', '=', $data['category'])->first();
        $data['category_id'] = $category->id;

        unset($data['category']);

        //strip off unwanted html tags
        $data['content'] = strip_tags($data['content'], "<p><b><a><img><h1><h2><h3><h4><h5><h6>");

        $this->publication->find($pub_id)->update($data);

        return response()->json(['success']);
    }


    /**
     * @param $userId
     * @param $publicationID
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
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


    /**
     * @param $publicationId
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @param $pub_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($pub_id)
    {
        $publication = $this->publication->find($pub_id);

//        if (!is_null($publication->images)) {
//            foreach ($publication->images as $image) {
//                HelperController::removeImage($image);
//            }
//        }

        $publication->delete();

        return response()->json('success');
    }


    public function getPubCategories()
    {
        $categories = PublicationCategory::orderBy('id','asc')->get(['id','category_name','category_slug']);
        $industries = Industry::orderBy('id','asc')->get(['id','alias', 'name']);
        return response()->json(['categories' => $categories, 'industries' => $industries]);
    }


    public function hubMaterials(Request $request)
    {
        $query = $request->get('q');

        $collection = [];

        switch ($query) {
            case 'all_career_fields':
                $this->publication->whereNotNull('industry_id')
                    ->with('industry')
                    ->with('user')
                    ->with('category')
                    ->orderBy('id','desc')->get()
                    ->mapToGroups( function($item) use (&$collection) {

                       $collection[$item->industry->name][] = $item;
                        return [];
                    });

                break;

            case 'career_development':
            case 'general_knowledge_areas':
            case 'entrepreneurship_business_management':
                $category = PublicationCategory::where('category_slug', '=', $query)->first();

                $collection = $this->publication->where('category_id', '=', $category->id)->with('category')->with('user')
                    ->orderBy('id','desc')->get();

                break;
        }


        if ($query !== 'all_career_fields' || count($collection) === 0) {
            return response()->json($collection);
        }

       //further processes
        $result = [];

        foreach ($collection as $industry => $item) {
            $result[] = ['industry' => $industry, 'materials' => count($item), 'items' => $item];
        }
        return response()->json($result);
    }
}
