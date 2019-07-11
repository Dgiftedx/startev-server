<?php

namespace App\Http\Controllers\Admin;

use App\Models\Business;
use App\Models\Mentor;
use App\Models\VerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController as MainHelperController;
use Illuminate\Support\Facades\Storage;

class VerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function allRequests()
    {
        $results = VerificationRequest::orderBy('id','desc')->with('user')->get();

        foreach ($results as $result) {
            $result->roleData = MainHelperController::fetchRoleData($result->user->id);
        }

        return response()->json(['success' => true, 'results' => $results]);
    }

    public function downloadDocument($file)
    {
        $path = public_path() . "/storage/uploads/" . $file;
        $headers = array();
        return response()->download($path, "document", $headers);
    }


    public function verify($id)
    {
        $var = VerificationRequest::find($id);
        $user_id = $var->user_id;
        $var->update(['status' => 'accepted']);

        $roleData = MainHelperController::fetchRoleData($user_id);
        $id = $roleData['data']['id'];

        if ($roleData['role'] == 'business') {
            Business::find($id)->update(['verification_status' => 'accepted']);
        }else{
            Mentor::find($id)->update(['verification_status' => 'accepted']);
        }

        return response()->json(['success' => true]);
    }

    public function reject($id)
    {
        $var = VerificationRequest::find($id);
        $user_id = $var->user_id;
        $var->update(['status' => 'rejected']);

        $roleData = MainHelperController::fetchRoleData($user_id);
        $id = $roleData['data']['id'];

        if ($roleData['role'] == 'business') {
            Business::find($id)->update(['verification_status' => 'rejected']);
        }else{
            Mentor::find($id)->update(['verification_status' => 'rejected']);
        }

        return response()->json(['success' => true]);
    }
}
