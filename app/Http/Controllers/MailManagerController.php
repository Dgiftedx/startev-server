<?php

namespace App\Http\Controllers;

use App\Jobs\sendMessageCampaign;
use App\Models\Admin\Admin;
use App\Models\Business;
use App\Models\Graduate;
use App\Models\Mentor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class MailManagerController extends Controller
{
    public function checkUsers( Request $request )
    {
        $data = $request->all();

        $users = User::whereBetween('created_at', [$data['from'], $data['to']])->count();

        if (is_null($users) || $users === 0) {
            return response()->json(['success' => true, 'hasUsers' => false ]);
        }

        return response()->json(['success' => true, 'hasUsers' => true ]);
    }


    public function sendMail( Request $request )
    {
        $data = $request->all();

        if (isset($data['from']) && isset($data['to'])) {
            $users = User::whereBetween('created_at', [$data['from'], $data['to']])->pluck('email')->toArray();
            $mailContent['message'] = $data['message'];
            $mailContent['subject'] = $data['subject'];
            $mailContent['recipients'] = $users;
            dispatch(new sendMessageCampaign($mailContent));

            return response()->json(['success' => true, "message" => "Message is being dispatched."]);
        }


        if (isset($data['user_group'])) {

            switch ($data['user_group']) {
                case 'graduates':
                    $ids = Graduate::pluck('id')->toArray();
                    $recipients = User::whereIn('id',$ids)->pluck('email')->toArray();
                    break;

                case 'students':
                    $ids = Student::pluck('id')->toArray();
                    $recipients = User::whereIn('id',$ids)->pluck('email')->toArray();
                    break;

                case 'mentors':
                    $ids = Mentor::pluck('id')->toArray();
                    $recipients = User::whereIn('id',$ids)->pluck('email')->toArray();
                    break;

                case 'businesses':
                    $ids = Business::pluck('id')->toArray();
                    $recipients = User::whereIn('id',$ids)->pluck('email')->toArray();
                    break;

                default:
                        $recipients = User::orderBy('id','desc')->pluck('email')->toArray();
                        break;
            }

            $mailContent['message'] = $data['message'];
            $mailContent['subject'] = $data['subject'];
            $mailContent['recipients'] = $recipients;
            dispatch(new sendMessageCampaign($mailContent));
            return response()->json(['success' => true, "message" => "Message is being dispatched."]);
        }


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
