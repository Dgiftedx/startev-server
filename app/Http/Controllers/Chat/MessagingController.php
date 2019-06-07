<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\HelperController;
use App\Models\Chat\Message;
use App\Models\Chat\MessageConversation;
use App\Models\User;
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
        $contacts = User::whereIn('id',$removeDuplicates)->get(['id','avatar','name','username','state','country','status']);
        foreach ($contacts as $contact) {
            $contact->status = $contact->isOnline();
            $contact->messages = $this->helperGetMessages($user_id, $contact->id);
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
            $messages[] = $item;
        }

        return $messages;

    }


    public function getContacts( $user_id )
    {
        $contactIds = HelperController::pullContacts($user_id);

        $contacts = User::whereIn('id',$contactIds)->get(['id','avatar','name','username','state','country','status']);
        foreach ($contacts as $contact) {
            $contact->status = $contact->isOnline();
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
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];

        Pusher::trigger('messaging', 'new-message', $pusher);

        $conversation = MessageConversation::create(['sender_id' => $data['sender_id'], 'receiver_id' => $data['receiver_id'], 'message' => $data['message']]);
//        $data['last_read'] = Carbon::now()->toDateTimeString();
        $data['conversation_id'] = $conversation->id;

        $inserted = Message::create($data);

        $new = Message::find($inserted->id);
        $pusher = [
            'id' => $new->id,
            'sender_id' => $new->sender_id,
            'receiver_id' => $new->receiver_id,
            'conversation_id' => $new->conversation_id,
            'messaging_group_id' => $new->messaging_group_id,
            'message' => $new->message,
            'file' => $new->file,
            'type' => $new->type,
            'las_read' => $new->last_read,
            'status' => $new->status,
            'created_at' => $new->created_at,
            'updated_at' => $new->updated_at
        ];

        return response()->json(['success' => true]);
    }

}
