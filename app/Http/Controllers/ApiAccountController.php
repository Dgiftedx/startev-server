<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ApiAccountController extends Controller
{
    protected $user;

    public function __construct( User $userModel )
    {
        $this->user = $userModel;
        $this->middleware('auth:api');
    }

    public function getCompletionProgress()
    {
        return response()->json(['progress' => $this->checkProfileProgress(auth()->user()->id)]);
    }

    public function checkProfileProgress( $id )
    {
        $user = $this->user->find($id);
        $roleData = HelperController::fetchRoleData($id);

        $totalFields = 0;
        $filled = 0;

        $userObj = (array) $user;
        $roleDataObj = (array) $roleData['data'];

        $userObj = $userObj["\x00*\x00attributes"];
        $roleDataObj = $roleDataObj["\x00*\x00attributes"];

        foreach ($userObj as $key => $item){
            $totalFields++;
            if (!is_null($userObj[$key])) {
                $filled++;
            }
        }

        foreach ($roleDataObj as $index => $value) {
            $totalFields++;

            if (!is_null($roleDataObj[$index])){
                $filled++;
            }
        }

        $progressCal = ( $filled / $totalFields ) * 100;
        $progressCal = round($progressCal);

        return $progressCal;
    }


}
