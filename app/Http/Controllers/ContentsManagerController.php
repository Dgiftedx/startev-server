<?php

namespace App\Http\Controllers;

use App\Models\HelpTip;
use Illuminate\Http\Request;

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
}
