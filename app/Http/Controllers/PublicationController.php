<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\User;
use Illuminate\Http\Request;

class PublicationController extends Controller
{

    protected $user;

    protected $url;
    /**
     * ApiAccountController constructor.
     * @param User $userModel
     */
    public function __construct( User $userModel )
    {
        $this->middleware('auth:api');
        $this->user = $userModel;
        $this->url = url('/');
    }



    public function store( Request $request )
    {
        $data = $request->all();

        if ($data['image'] !== null){
            if(!is_null($request->file('image')) && $request->file('image')->isValid()){
                $path = $this->url. '/storage'. HelperController::processImageUpload($request->file('image'),$data['title'],'feeds',640,800);
                $data['image'] = $path;
            }
        }

        //strip off unwanted html tags
        $data['content'] = strip_tags($data['content'], "<p><b><a><img><h1><h2><h3><h4><h5><h6>");

        Publication::create($data);

        return response()->json(['success' => true, 'message' => 'Publication Published to targeted audience, You can find this in Knowledge hub']);
    }
}
