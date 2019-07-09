<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\SendEmailNotification;
use App\Models\CareerPath;
use App\Models\Graduate;
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

        $fields = CareerPath::orderBy('id','desc')->get();

        return response()->json(['success' => true, 'students' => $students, 'fields' => $fields]);
    }


    public function platformCreateStudent( Request $request )
    {
        $data = $request->all();

        $studentData = [];
        $studentData['careerPath'] = $data['careerPath'];
        $studentData['institution'] = $data['institution'];

        unset($data['institution']);
        unset($data['careerPath']);


        if (User::where('email', '=', $data['email'])->exists()) {
            return response()->json(['error' => "User with the same email already exists"]);
        }

        $mailContent['subject'] = "Account has been created for you.";
        $mailContent['message'] = "Dear {$data['name']}, <br/><br/> An account has been created for you on <b>Startev</b>. <br/> Your login: <br/> Email : <b>{$data['email']}</b> <br/> Password : <b>{$data['password']}</b>";
        $mailContent['to'] = $data['email'];

        $data['slug'] = uniqid(rand(), true);

        if (isset($data['avatar']) && $data['avatar'] !== 'undefined') {

            if ($request->file('avatar')->isValid()) {
                $avatar = $request->file('avatar');
                $path = url('/') . "/storage" . MainHelperController::processAvatarUpload($avatar, $data['name'], 'student');

                $data['avatar'] = $path;
            }

        }else{
            unset($data['avatar']);
        }

        $user = User::create($data);

        $studentData['user_id'] = $user->id;

        Student::create($studentData);

        dispatch(new SendEmailNotification($mailContent));

        return response()->json(['success' => true, 'message' => "Student account created successfully"]);
    }



    public function platformUpdateStudent( Request $request, $id )
    {
        $studentData = [];

        $data = $request->all();

        $user = User::find($id);

        if (isset($data['avatar']) && $data['avatar'] !== 'undefined') {

            if ($request->file('avatar')->isValid()) {

                if ($user->avatar) {
                    MainHelperController::removeImage($user->avatar);
                }

                $avatar = $request->file('avatar');
                $path = url('/') . "/storage" . MainHelperController::processAvatarUpload($avatar, $data['name'], 'student');

                $data['avatar'] = $path;
            }

        }else {
            unset($data['avatar']);
        }



        if (isset($data['careerPath']) && !is_null($data['careerPath'])) {
            $studentData['careerPath'] = $data['careerPath'];
            unset($data['careerPath']);
        }

        if (isset($data['institution']) && !is_null($data['institution'])) {
            $studentData['institution'] = $data['institution'];
            unset($data['institution']);
        }


        if (isset($data['password']) && $data['password'] === "undefined") {
            unset($data['password']);
        }

        $user->update($data);

        if (count($studentData) > 0) {
            $roleData = Student::where('user_id', '=', $id)->first();
            Student::find($roleData->id)->update($studentData);
        }

        return response()->json(['success' => true, 'message' => "{$user->name} data was updated successfully"]);
    }



    public function deleteStudentAccount($id)
    {
        $data['user_id'] = $id;
        AdminHelperController::handleDeletion($data);
        return response()->json(['success' => true, 'message' => "Account with all related information removed successfully. A notification has also been sent to said user."]);
    }



    public function platformGraduates()
    {
        $studentIds = MainHelperController::graduatesId();

        $students = User::orderBy('id', 'desc')->whereIn('id',$studentIds)->get();

        foreach ($students as $student) {
            $student->roleData = MainHelperController::fetchRoleData($student->id);
        }

        $fields = CareerPath::orderBy('id','desc')->get();

        return response()->json(['success' => true, 'graduates' => $students, 'fields' => $fields]);
    }


    public function platformCreateGraduate( Request $request )
    {
        $data = $request->all();

        $studentData = [];
        $studentData['careerPath'] = $data['careerPath'];
        $studentData['institution'] = $data['institution'];

        unset($data['institution']);
        unset($data['careerPath']);


        if (User::where('email', '=', $data['email'])->exists()) {
            return response()->json(['error' => "User with the same email already exists"]);
        }

        $mailContent['subject'] = "Account has been created for you.";
        $mailContent['message'] = "Dear {$data['name']}, <br/><br/> An account has been created for you on <b>Startev</b>. <br/> Your login: <br/> Email : <b>{$data['email']}</b> <br/> Password : <b>{$data['password']}</b>";
        $mailContent['to'] = $data['email'];

        $data['slug'] = uniqid(rand(), true);

        if (isset($data['avatar']) && $data['avatar'] !== 'undefined') {

            if ($request->file('avatar')->isValid()) {
                $avatar = $request->file('avatar');
                $path = url('/') . "/storage" . MainHelperController::processAvatarUpload($avatar, $data['name'], 'student');

                $data['avatar'] = $path;
            }

        }else{
            unset($data['avatar']);
        }

        $user = User::create($data);

        $studentData['user_id'] = $user->id;

        Graduate::create($studentData);

        dispatch(new SendEmailNotification($mailContent));

        return response()->json(['success' => true, 'message' => "Student account created successfully"]);
    }



    public function platformUpdateGraduate( Request $request, $id )
    {
        $studentData = [];

        $data = $request->all();

        $user = User::find($id);

        if (isset($data['avatar']) && $data['avatar'] !== 'undefined') {

            if ($request->file('avatar')->isValid()) {

                if ($user->avatar) {
                    MainHelperController::removeImage($user->avatar);
                }

                $avatar = $request->file('avatar');
                $path = url('/') . "/storage" . MainHelperController::processAvatarUpload($avatar, $data['name'], 'student');

                $data['avatar'] = $path;
            }

        }else {
            unset($data['avatar']);
        }



        if (isset($data['careerPath']) && !is_null($data['careerPath'])) {
            $studentData['careerPath'] = $data['careerPath'];
            unset($data['careerPath']);
        }

        if (isset($data['institution']) && !is_null($data['institution'])) {
            $studentData['institution'] = $data['institution'];
            unset($data['institution']);
        }


        if (isset($data['password']) && $data['password'] === "undefined") {
            unset($data['password']);
        }

        $user->update($data);

        if (count($studentData) > 0) {
            $roleData = Graduate::where('user_id', '=', $id)->first();
            Graduate::find($roleData->id)->update($studentData);
        }

        return response()->json(['success' => true, 'message' => "{$user->name} data was updated successfully"]);
    }
}
