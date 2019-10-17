<?php

namespace App\Http\Controllers;

use App\Exports\UserExports;
use App\Jobs\sendMessageCampaign;
use App\Mail\CampaignMail;
use App\Models\Admin\Admin;
use App\Models\Business;
use App\Models\Graduate;
use App\Models\Mentor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class MailManagerController extends Controller
{
    public function checkUsers( Request $request )
    {
        $data = $request->all();
        if (isset($data['from']) && isset($data['to'])) {
            $users = User::whereBetween('created_at', [$data['from'], $data['to']])->select('name', 'email')->get();
            if (is_null($users) || $users->count() === 0) {
                return response()->json(['success' => true, 'hasUsers' => false]);
            }

            return response()->json(['success' => true, 'hasUsers' => true, 'users' => $users]);
        }
        if (isset($data['user_group'])) {

            switch ($data['user_group']) {
                case 'graduates':
                    $ids = Graduate::pluck('user_id')->toArray();
                    $recipients = User::whereIn('id',$ids)->select('name', 'email')->get();
                    break;

                case 'students':
                    $ids = Student::pluck('user_id')->toArray();
                    $recipients = User::whereIn('id',$ids)->select('name', 'email')->get();
                    break;

                case 'mentors':
                    $ids = Mentor::pluck('user_id')->toArray();
                    $recipients = User::whereIn('id',$ids)->select('name', 'email')->get();
                    break;

                case 'businesses':
                    $ids = Business::pluck('user_id')->toArray();
                    $recipients = User::whereIn('id',$ids)->select('name', 'email')->get();
                    break;

                default:
                    $recipients = User::orderBy('id','desc')->select('name', 'email')->get();
                    break;
            }

            if (is_null($recipients) || $recipients->count() === 0) {
                return response()->json(['success' => true, 'hasUsers' => false]);
            }

            return response()->json(['success' => true, 'hasUsers' => true, 'users' => $recipients]);
        }
        elseif (isset($data['targetGroup'])&&$data['targetGroup']=='admins') {
            $users=Admin::all();
            if (is_null($users) || $users->count() === 0) {
                return response()->json(['success' => true, 'hasUsers' => false]);
            }
            return response()->json(['success' => true, 'hasUsers' => true, 'users' => $users]);
        }


    }
    public function checkUsersExportMail( Request $request )
    {
        $data = json_decode($_GET['params'],true);
        if (isset($data['from']) && isset($data['to'])) {
            $users = User::whereBetween('created_at', [$data['from'], $data['to']])->select('name', 'email')->get();
            if (is_null($users) || $users->count() === 0) {
                return response()->json(['success' => true, 'hasUsers' => false]);
            }

            return Excel::download(new UserExports($users), 'startev_users_list.xlsx');
        }
        if (isset($data['user_group'])) {

            switch ($data['user_group']) {
                case 'graduates':
                    $ids = Graduate::pluck('user_id')->toArray();
                    $recipients = User::whereIn('id',$ids)->select('name', 'email')->get();
                    break;

                case 'students':
                    $ids = Student::pluck('user_id')->toArray();
                    $recipients = User::whereIn('id',$ids)->select('name', 'email')->get();
                    break;

                case 'mentors':
                    $ids = Mentor::pluck('user_id')->toArray();
                    $recipients = User::whereIn('id',$ids)->select('name', 'email')->get();
                    break;

                case 'businesses':
                    $ids = Business::pluck('user_id')->toArray();
                    $recipients = User::whereIn('id',$ids)->select('name', 'email')->get();
                    break;

                default:
                    $recipients = User::orderBy('id','desc')->select('name', 'email')->get();
                    break;
            }

            if (is_null($recipients) || $recipients->count() === 0) {
                return response()->json(['success' => true, 'hasUsers' => false]);
            }

            return Excel::download(new UserExports($recipients), 'startev_'.$data['user_group'].'_list.xlsx');

        }
        elseif (isset($data['targetGroup'])&&$data['targetGroup']=='admins') {
            $users=Admin::all();
            if (is_null($users) || $users->count() === 0) {
                return response()->json(['success' => true, 'hasUsers' => false]);
            }
            return  Excel::download(new UserExports($users), 'startev_'.$data['targetGroup'].'_list.xlsx');
        }
    }


    public function sendMail( Request $request )
    {
        $data = $request->all();
        if (isset($data['recipients'])){
//            $users = User::whereBetween('created_at', [$data['from'], $data['to']])->pluck('email')->toArray();
            $mailContent['message'] = $data['message'];
            $mailContent['subject'] = $data['subject'];
            $mailContent['recipients'] = $data['recipients'];
            dispatch(new sendMessageCampaign($mailContent));
//            foreach ($mailContent['recipients'] as $recipient) {
//                Mail::to($recipient['email'])->send(new CampaignMail($mailContent));
//            }
//            return response()->json(['success' => true, "message" => "Message has been dispatched."]);
            return response()->json(['success' => true, "message" => "Message is being dispatched."]);
        }


//        if (isset($data['user_group'])) {
//
//            switch ($data['user_group']) {
//                case 'graduates':
//                    $ids = Graduate::pluck('user_id')->toArray();
//                    $recipients = User::whereIn('id',$ids)->pluck('email')->toArray();
//                    break;
//
//                case 'students':
//                    $ids = Student::pluck('user_id')->toArray();
//                    $recipients = User::whereIn('id',$ids)->pluck('email')->toArray();
//                    break;
//
//                case 'mentors':
//                    $ids = Mentor::pluck('user_id')->toArray();
//                    $recipients = User::whereIn('id',$ids)->pluck('email')->toArray();
//                    break;
//
//                case 'businesses':
//                    $ids = Business::pluck('user_id')->toArray();
//                    $recipients = User::whereIn('id',$ids)->pluck('email')->toArray();
//                    break;
//
//                default:
//                        $recipients = User::orderBy('id','desc')->pluck('email')->toArray();
//                        break;
//            }
//
//            $mailContent['message'] = $data['message'];
//            $mailContent['subject'] = $data['subject'];
//            $mailContent['recipients'] = $recipients;
//
//            dispatch(new sendMessageCampaign($mailContent));
//            return response()->json(['success' => true, "message" => "Message is being dispatched."]);
//        }


        if (isset($data['target_group']) && $data['target_group'] === 'admins') {
            $recipients = Admin::orderBy('id','desc')->pluck('email')->toArray();
            $mailContent['message'] = $data['message'];
            $mailContent['subject'] = $data['subject'];
            $mailContent['recipients'] = $recipients;
            dispatch(new sendMessageCampaign($mailContent));
        }

        return response()->json(['success' => true, "message" => "Message is being dispatched."]);
    }
}
