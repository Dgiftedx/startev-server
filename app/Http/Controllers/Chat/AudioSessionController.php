<?php

namespace App\Http\Controllers\Chat;

use App\Models\Chat\AudioSession;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AudioSessionController extends Controller
{
    /**
     * @var AudioSession
     */
    protected $audio;

    protected $url;


    /**
     * AudioSessionController constructor.
     * @param AudioSession $audioModel
     */
    public function __construct( AudioSession $audioModel )
    {
        $this->audio = $audioModel;
        $this->middleware('auth:api');
        $this->url = url('/');
    }


    public function logSession( Request $request )
    {
        $data = $request->all();

        //check if user is online
        $user = User::find($data['target_user_id']);
        if (!$user->isOnline()) {
            return response()->json(['error' => "This user is offline. Can't connect call at this time."]);
        }

        if ($this->audio->where('target_user_id','=',$data['target_user_id'])->where('host','!=',$data['host'])->exists()) {
            return response()->json(['error' => "This user is on another call at the moment"]);
        }

        if ($this->audio->where('target_user_id','=',$data['target_user_id'])->where('host','=',$data['host'])->exists()) {
            $call = $this->audio->where('target_user_id','=',$data['target_user_id'])->where('host','=',$data['host'])->first();
            return response()->json($call);
        }

        // Log audio session
        $update = $this->audio->create($data);
        return response()->json($update);
    }


    public function getSession($target)
    {
        $session = $this->audio->orderBy('id','desc')->where('target_user_id','=',$target)->where('status','=','incoming')->first();
        return response()->json($session);
    }

    public function getHostSession($host)
    {
        $session = $this->audio->orderBy('id','desc')->where('host','=',$host)->first();
        return response()->json($session);
    }


    public function endReceiverSession( Request $request )
    {
        $update = [];
        $data = $request->all();
        if (isset($data['audio_session'])) {
            $this->audio->find($data['audio_session'])->update(['status' => 'ended']);
            $update = $this->audio->find($data['audio_session']);
        }

        return response()->json($update);
    }


    public function pickSession($id)
    {
        $this->audio->find($id)->update(['status' => 'picked']);
        $update = $this->audio->find($id);
        return response()->json($update);
    }

    public function deleteSession($id)
    {
        $session = $this->audio->find($id);
        if ($session) {
            $session->delete();
        }
        return response()->json(['success' => true ]);
    }
}
