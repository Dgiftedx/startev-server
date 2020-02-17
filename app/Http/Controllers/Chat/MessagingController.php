<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\HelperController;
use App\Jobs\SendEmailNotification;
use App\Models\Chat\Message;
use App\Models\Chat\MessageConversation;
use App\Models\User;
use App\Models\UserContact;
use App\Providers\PushNotification;
use App\Repositories\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Pusher\Laravel\Facades\Pusher;

class MessagingController extends Controller
{
    /**
     * @var User
     */
    protected $user;

    protected $url;


    /**
     * ApiAccountController constructor.
     * @param User $userModel
     */
    public function __construct( User $userModel )
    {
        $this->user = $userModel;
        $this->middleware('auth:api');
        $this->url = url('/');
    }


    public function getMessages( $user_id )
    {
        //grab user ids i've sent messages to
        $messagingReceiverIds = Message::where('sender_id','=', $user_id)->pluck('receiver_id')->toArray();
        //grab user ids who sent messages to me
        $toMe = Message::where('receiver_id','=', $user_id)->pluck('sender_id')->toArray();
        //remove duplicate ids

        $merge = array_merge($messagingReceiverIds, $toMe);

        $removeDuplicates = array_unique($merge);
        // get this users and their messages
        $contacts = User::whereIn('id',$removeDuplicates)->get(['id','avatar','bio','name','username','state','country','status']);
        foreach ($contacts as $contact) {
            $contact->status = $contact->isOnline();
            $contact->messages = $this->helperGetMessages($user_id, $contact->id);
            $contact->role = HelperController::fetchMinimalRole($contact->id);
        }

        return response()->json($contacts);
    }



    public function helperGetMessages($user, $receiver)
    {

        $messages = [];

        $sent = Message::with('receiver')
            ->where('sender_id','=',$user)
            ->where('receiver_id', '=', $receiver)
            ->orderBy('created_at','desc')->get();

        foreach ($sent as $s) {
            $messages[] = $s;
        }

        $received =  Message::with('receiver')
            ->where('receiver_id','=',$user)
            ->where('sender_id', '=', $receiver)
            ->orderBy('created_at','desc')->get();

        foreach ($received as $item) {

            $item->created_at = Carbon::parse($item->created_at)->toDateTimeString();
            $messages[] = $item;
        }

        return $messages;

    }

    private function grabContactList($id)
    {
        return UserContact::where('user_id', '=', $id)->first();
    }

    public function getContacts( $user_id )
    {
        $contacts = [];

        $contactBook = $this->grabContactList($user_id);
        if (!is_null($contactBook)) {
            $contacts = User::whereIn('id', $contactBook->contacts_id)->get(['id','slug','avatar','name','username','status','email']);
            foreach ($contacts as $contact) {
                $contact->status = $contact->isOnline();
                $contact->role = HelperController::fetchMinimalRole($contact->id);
            }
        }
        return response()->json($contacts);
    }


    public function typingEvent( Request $request )
    {
        $data = $request->all();

        Pusher::trigger('single-chat', 'typing', $data);

        return response()->json('success');
    }


    public function sendMessage( Request $request )
    {
        $data = $request->all();

        $pusher = [
            'sender_id' => $data['sender_id'],
            'receiver_id' => $data['receiver_id'],
            'message' => $data['message'],
            'type' => $data['type'],
            'status' => 'unread',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()
        ];

        Pusher::trigger('messaging', 'new-message', $pusher);

        $conversation = MessageConversation::create(['sender_id' => $data['sender_id'], 'receiver_id' => $data['receiver_id'], 'message' => $data['message']]);

        $data['conversation_id'] = $conversation->id;

        Message::create($data);
        //send email notification to recipient if offline
        $this->handleOfflineMessageNotification($data['receiver_id'], $data['message'],$data['sender_id']);

        return response()->json(['success' => true]);
    }


    private  function handleOfflineMessageNotification($recipient, $message,$sender)
    {
        $user = User::find($recipient);
        $senderuser = User::find($sender);

        if (!$user->isOnline()) {
            $mailContent = [];
            $mailContent['message'] = $message;
            $mailContent['to'] = $user->email;
            $mailContent['subject'] = "You have a new Message";
//            dispatch(new SendEmailNotification($mailContent));

        }
        $pushData['content'] = [
            'data' => ['type'=>PushNotification::$Messages],
            'title'=>'You have a new Message ',
            'body'=>"New Message From ".$senderuser->name
        ];
        $pushData['users'][] = $recipient;

        (new Notification())->sendPush($pushData);
    }


    public function markAllAsRead( Request $request )
    {
        $data = $request->all();
        $messages = Message::byFilter($data)->where('status', '=', 'unread')->get();
        foreach ($messages as $message) {
            Message::find($message->id)->update(['status' => 'read']);
        }

        $data = ['sender_id' => $data['receiver_id'], 'receiver_id' => $data['sender_id']];
        $messages2 = Message::byFilter($data)->where('status', '=', 'unread')->get();
        foreach ($messages2 as $message) {
            Message::find($message->id)->update(['status' => 'read']);
        }

        return response()->json(['success' => true]);
    }

}
