<?php

namespace App\Http\Controllers\Admin;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController as MainHelperController;
use App\Http\Controllers\Admin\HelperController as AdminHelperController;

class PlatformUsersController extends Controller
{

    /**
     * PlatformUsersController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function platformStudents()
    {
        $studentIds = MainHelperController::getStudentIds();

        $students = User::orderBy('id', 'desc')->whereIn('id',$studentIds)->get();

        foreach ($students as $student) {
            $student->roleData = MainHelperController::fetchRoleData($student->id);
        }

        return response()->json(['success' => true, 'students' => $students]);
    }



    public function deleteStudentAccount($id)
    {
        $data['user_id'] = $id;
        AdminHelperController::handleDeletion($data);
        return response()->json(['success' => true, 'message' => "Account with all related information removed successfully. A notification has also been sent to said user."]);
    }
}
