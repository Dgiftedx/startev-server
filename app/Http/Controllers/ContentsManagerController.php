<?php

namespace App\Http\Controllers;

use App\Models\Advert;
use App\Models\HelpTip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentsManagerController extends Controller
{

    protected $helpTip;

    public function __construct( HelpTip $helpTipModel )
    {
        $this->helpTip = $helpTipModel;
    }

    public function getHelpTips()
    {
        $tips = $this->helpTip->orderBy('id','desc')->get();
        return response()->json(['success' => true, 'helpTips' => $tips]);
    }

    public function storeHelpTip(Request $request)
    {
        $data = $request->all();
        $this->helpTip->create($data);
        return response()->json(['success' => true, "message" => "Help Tip created successfully"]);
    }


    public function updateHelpTip( Request $request, $id )
    {
        $data = $request->all();
        $this->helpTip->find($id)->update($data);
        return response()->json(['success' => true, "message" => "Help Tip updated."]);
    }


    public function deleteHelpTip($id)
    {
        $this->helpTip->find($id)->delete();
        return response()->json(['success' => true, 'message' => "Help tip removed from system successfully"]);
    }


    public function advertBanners()
    {
        $adverts = Advert::orderBy('id','desc')->get();
        return response()->json(['success' => true, 'adverts' => $adverts]);
    }

    public function uploadBanner( Request $request )
    {
        $data = [
            'link' => $request->get('link'),
        ];


        if($request->file('banner')->isValid()) {
            //upload advert banner image
            $path = HelperController::processFileUpload($request->file('banner'), 'adverts');
            $data['path'] = $path;
        }

        Advert::create($data);

        return response()->json(['success' => true, 'message' => "Advert image uploaded"]);
    }


    public function updateAdvert($id, $status) {
        $actives = Advert::where('status','=','active')->count();

        if($status === 'active') {
            if($actives > 2) {
                return response()->json(['success' => true, 'message' => 'Maximum of 2 active banners allowed']);
            }
        }
        Advert::find($id)->update(['status' => $status]);
        return response()->json(['success' => true, 'message' => "updated"]);
    }


    public function removeAdvert($id)
    {
        $advert = Advert::find($id);
        if(Storage::disk('public')->exists($advert->path)){
            //remove
            Storage::disk('public')->delete($advert->path);
        }

        $advert->delete();

        return response()->json(['success' => true]);
    }
}
