<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailNotification;
use App\Models\Broadcast\BroadcastSchedule;
use App\Models\Trainee;
use App\Models\User;
use Illuminate\Http\Request;

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
            ->where('status', '=', 'pending')
            ->orderBy('id','desc')->get();
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
        $users = $this->user->whereIn('id', $schedule->participants)->get(['name','email','avatar']);
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
}
