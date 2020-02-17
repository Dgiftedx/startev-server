<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailNotification;
use App\Models\Broadcast\BroadcastGroupMessage;
use App\Models\Broadcast\BroadcastSchedule;
use App\Models\Broadcast\LiveSession;
use App\Models\Trainee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Pusher\Laravel\Facades\Pusher;

class BroadcastManagerController extends Controller
{
    /**
     * @var User
     */
    protected $user;


    /**
     * ApiAccountController constructor.
     * @param User $userModel
     */
    public function __construct( User $userModel )
    {
        $this->user = $userModel;
        $this->middleware('auth:api');
    }


    /**
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function schedules($user_id)
    {
        $schedules = BroadcastSchedule::where('user_id','=', $user_id)
            ->orderBy('status','desc')->get();
        return response()->json($schedules);
    }

    /**
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTrainees($user_id)
    {
        $ids = Trainee::where('trainer_id', '=', $user_id)->pluck('trainee_id')->toArray();
        $trainees = $this->user->whereIn('id', $ids)->get(['id','name','email']);

        return response()->json($trainees);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitSchedule( Request $request )
    {
        $data = $request->all();
        $newSchedule = [];
        $newSchedule['identifier'] = "BATCH-" . HelperController::generateIdentifier(13);
        $newSchedule['user_id'] = $data['user_id'];
        $newSchedule['type'] = 'video';
        $newSchedule['status'] = 'pending';
        $newSchedule['participants'] = json_decode($data['participants']);
        $newSchedule['date'] = $data['date'];
        $newSchedule['title'] = $data['title'];

        //send email notification to users

        if (isset($data['invitation_note'])) {
            $newSchedule['invitation_note'] = $data['invitation_note'];
        }

        BroadcastSchedule::create($newSchedule);

        return response()->json(['success' => true]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSchedule( Request $request )
    {
        $data = $request->all();

        $id = $data['schedule_id'];
        unset($data['schedule_id']);

        $data['participants'] = json_decode($data['participants']);

        BroadcastSchedule::find($id)->update($data);

        return response()->json(['success' => true]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendReminder( Request $request )
    {
        $data = $request->all();
        $batch = BroadcastSchedule::find($data['batch_id']);

        $users = $this->user->whereIn('id',$batch->participants)->get(['name','email']);

        $mailContent = [
            'message' => $data['message'],
            'subject' => 'Live Broadcast Session Reminder'
        ];

        foreach ($users as $user) {
            $mailContent['to'] = $user->email;

            dispatch(new SendEmailNotification($mailContent));
        }

        return response()->json(['success' => true]);
    }

    /**
     * @param $schedule_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getParticipants($schedule_id)
    {
        $schedule = BroadcastSchedule::find($schedule_id);
        $users = $this->user->whereIn('id', $schedule->participants)->get(['id','name','email','avatar']);
        return response()->json($users);
    }


    /**
     * @param $schedule_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSchedule($schedule_id)
    {
        BroadcastSchedule::find($schedule_id)->delete();

        //if schedule is pending, send cancellation notice to participants
        return response()->json(['success' => true]);
    }


    /**
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearSchedules( $user_id )
    {
        $schedules = BroadcastSchedule::where('user_id','=',$user_id)->pluck('id')->toArray();

        foreach ($schedules as $schedule) {
            BroadcastSchedule::find($schedule)->delete();
        }

        return response()->json(['success' => true]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logSession( Request $request )
    {

        $data = $request->all();

        //first update broadcast schedule
        BroadcastSchedule::find($data['schedule_id'])->update(['status' => 'in progress']);

        if (LiveSession::where('schedule_id','=',$data['schedule_id'])->exists()){
            $update = LiveSession::where('schedule_id','=',$data['schedule_id'])->first();
        }else{
            $update = LiveSession::create($data);
        }
        return response()->json($update);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeSession( Request $request )
    {
        $data = $request->all();
        LiveSession::find($data['id'])->delete();

        BroadcastSchedule::find($data['schedule_id'])->update(['status' => 'completed']);

        return response()->json(['success' => true]);
    }

    /**
     * @param $user
     * @return array
     */
    private function upComing($user)
    {
        $upcomingIds = [];

        $schedules = BroadcastSchedule::where('status','=','pending')->orderBy('id','desc')->get();
        foreach ($schedules as $schedule) {
            if (in_array($user, $schedule->participants)) {
                $upcomingIds[] = $schedule->id;
            }
        }

        return $upcomingIds;
    }

    /**
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUpcomingMeetings( $user_id )
    {
        $upComing = $this->upComing($user_id);
        $liveSessions = LiveSession::whereIn('schedule_id', $upComing)->pluck('schedule_id')->toArray();

        $realUpcoming = array_diff($upComing, $liveSessions);

        $schedules = BroadcastSchedule::whereIn('id', $realUpcoming)->orderBy('id','desc')->get();

        foreach ( $schedules as $schedule) {
            $mentor = User::find($schedule->user_id);
            $schedule->host = $mentor->name;
        }

        return response()->json($schedules);
    }

    /**
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLiveSessions( $user_id )
    {
        $liveIds = [];

        $userUpcomingSchedules  = [];

        $schedules = BroadcastSchedule::where('status','=','in progress')->orderBy('id','desc')->get();
        foreach ($schedules as $schedule) {
            if (in_array($user_id, $schedule->participants)) {
                $userUpcomingSchedules [] = $schedule->id;
            }
        }

        //get all live sessions
        $liveSessions = LiveSession::orderBy('id','desc')->get();

        foreach ($liveSessions as $liveSession) {
            //if there is any ongoing session in which i'm a participant, get the schedule id.
            if (in_array($liveSession->schedule_id, $userUpcomingSchedules)){
                $liveIds[] = $liveSession->schedule_id;
            }
        }

        //pull to collection
        $onGoings = LiveSession::whereIn('schedule_id', $liveIds)->get();
        //attach schedule
        foreach ($onGoings as $onGoing){
            $onGoing->schedule = BroadcastSchedule::find($onGoing->schedule_id);
        }
        //return result
        return response()->json($onGoings);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBroadcastMessages()
    {
        $messages = BroadcastGroupMessage::orderBy('created_at','asc')->get();
        return response()->json($messages);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitBroadcastMessage( Request $request )
    {
        $data = $request->all();
        $new = BroadcastGroupMessage::create($data);

        Pusher::trigger('broadcast-channel', 'chat-event', $new);

        return response()->json(['success' => true, 'message' => 'Post published successfully']);
    }


    public function removeBroadcastMessages( $schedule_id )
    {
        $messages = BroadcastGroupMessage::where('schedule_id','=', $schedule_id)->get();

        foreach ($messages as $message) {
            BroadcastGroupMessage::find($message->id)->delete();
        }

        return response()->json(['success' => true]);
    }
}
