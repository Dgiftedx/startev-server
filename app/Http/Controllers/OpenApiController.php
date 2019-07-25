<?php

namespace App\Http\Controllers;

use App\Models\Broadcast\BroadcastSchedule;
use App\Models\Feed;
use App\Models\Student;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class OpenApiController extends Controller
{
    public function landingStats()
    {
        $result = $this->gatherStat();
        return response()->json($result);
    }

    private function gatherStat()
    {
        $result = [];

        $result['mentors'] = count(HelperController::realMentorsId());
        $result['graduates'] = count(HelperController::graduatesId());
        $result['students'] = count(Student::get(['id']));
        $result['businesses'] = count(HelperController::businessIds());

        return $result;
    }

    public function getSingleFeed( $id )
    {
        $feed = [];

        $recent = Feed::orderBy('id','desc')->take(7)->get();
        foreach ($recent as $item) {
            $item->user = User::where('id','=',$item->user_id)->first(['id','slug','name','avatar']);
        }

        Feed::with('feedComments')
            ->with('feedComments.user')
            ->where('id', '=', $id)
            ->get()
            ->mapToGroups(function ($item) use (&$feed) {
                $feed = [
                    'id' => $item->id,
                    'postType' => $item->post_type,
                    'roleData' => HelperController::fetchRoleData($item->user_id),
                    'user' => User::where('id','=',$item->user_id)->first(['id','slug','name','avatar']),
                    'hasLiked' => $item->hasLiked,
                    'title' => $item->title,
                    'likers' => $item->likers()->get(),
                    'comments'=> $item->feedComments,
                    'image' => $item->image,
                    'images' => $item->images,
                    'video' => $item->image,
                    'link' => $item->link,
                    'content' => $item->body,
                    'time' => $item->time
                ];
                return [];
            });

        return response()->json(['feed' => $feed, 'recent' => $recent]);
    }

    public function downloadScheduleReport( $user_id )
    {
        $schedules = BroadcastSchedule::where('user_id','=', $user_id)->orderBy('id','desc')->get();

        foreach ($schedules as $schedule) {
            $schedule->users = User::whereIn('id',$schedule->participants)->get(['name','email']);
        }

        $pdf = PDF::loadView('pdf/broadcast-schedule-report', compact('schedules'));
        return $pdf->download('Schedule Report' . '.pdf');
    }
}
