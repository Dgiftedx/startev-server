<?php

namespace App\Http\Controllers;

use App\Models\HelpTip;
use Illuminate\Http\Request;

class ApiHelpTipsController extends Controller
{
    /**
     * @var HelpTip
     */
    protected $helpTip;

    protected $url;


    /**
     * ApiHelpTipsController constructor.
     * @param HelpTip $helpTipModel
     */
    public function __construct( HelpTip $helpTipModel )
    {
        $this->helpTip = $helpTipModel;
        $this->middleware('auth:api');
        $this->url = url('/');
    }


    public function index( $userId )
    {
        $roleData = HelperController::fetchRoleData($userId);
        $helpTips = $this->helpTip->where('target','=',$roleData['role'])->inRandomOrder()->get();

        return response()->json(['tips' => $helpTips]);
    }
}
