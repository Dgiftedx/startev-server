<?php

namespace App\Http\Controllers\Mail;

use App\Mail\MailNotify;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Mailgun\Mailgun;

class MailController extends Controller
{


    public static function sendNoticeMail($message, $to, $subject = null)
    {
        $mailContent = [];

        if (is_null($subject)) {
            $mailContent['subject'] = "Notification from Startev Africa";
        }else{
            $mailContent['subject'] = $subject;
        }

        $mailContent['message'] = $message;
        $mailContent['to'] = $to;

         try {
             Mail::to($mailContent['to'])->send(new MailNotify($mailContent));
         }catch (\Exception $e) {
             //do nothing
         }
    }
}
