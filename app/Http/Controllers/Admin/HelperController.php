<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\SendEmailNotification;
use App\Models\Business;
use App\Models\Feed;
use App\Models\Graduate;
use App\Models\Mentor;
use App\Models\Store\UserStore;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController as MainHelperController;
use Illuminate\Support\Facades\DB;

class HelperController extends Controller
{


    public static function getStudentIds()
    {
        return Student::orderBy('id','desc')->count();
    }

    public static function getGraduatesIds()
    {
        return Graduate::orderBy('id','desc')->count();
    }

    public static function getMentorsIds()
    {
        return Mentor::orderBy('id','desc')->count();
    }


    public static function getBusinessesIds()
    {
        return Business::orderBy('id','asc')->count();
    }


    public static function handleDeletion( $data )
    {
        $userID = $data['user_id'];

        $roleData = MainHelperController::fetchRoleData($userID);

        $feeds = Feed::where('user_id', '=', $userID)->get();

        foreach ($feeds as $feed) {
            if (!is_null($feed->image)) {
                MainHelperController::removeImage($feed->image);
            }


            $feed->delete();
        }

        DB::table('feed_comments')->where('user_id', '=', $userID)->get();

        DB::table('messages')->where('sender_id','=', $userID)->delete();

        DB::table('message_conversations')->where('sender_id','=', $userID)->delete();

        DB::table('partnerships')->where('user_id','=', $userID)->delete();

        DB::table('trainees')->where('trainer_id', '=', $userID)->delete();

        DB::table('user_hidden_feeds')->where('user_id','=',$userID)->delete();

        DB::table('user_industry')->where('user_id','=', $userID)->delete();

        if ($roleData['role'] === 'business') {
            $business_id = Business::businessId($userID);

            DB::table('user_business_products')->where('business_id','=', $business_id)->delete();

            DB::table('user_business_settings')->where('business_id', '=', $business_id)->delete();

            DB::table('business_ventures')->where('business_id','=', $business_id)->delete();

            DB::table('businesses')->where('user_id','=', $userID)->delete();
        }


        if ($roleData['role'] === 'mentor') {
            DB::table('mentors')->where('user_id','=', $userID)->delete();
        }


        if ($roleData['role'] === 'students') {
            DB::table('students')->where('user_id', '=', $userID)->delete();

            $store_id = UserStore::storeId($userID);

            DB::table('user_venture_products')->where('store_id','=', $store_id)->delete();

            DB::table('user_venture_reviews')->where('store_id', '=', $store_id)->delete();
        }

        DB::table('user_stores')->where('user_id','=', $userID)->delete();


        DB::table('user_notifications')->where('user_id','=', $userID)->delete();


        $user = User::find($userID);

        if (!is_null($user->avatar)) {
            MainHelperController::removeImage($user->avatar);
        }

        if (!is_null($user->bg_image)) {
            MainHelperController::removeImage($user->bg_image);
        }

        $mailContent['message'] = "Dear {$user->name}, <br/><br/>Sorry to see you go. <br> Should Startev objective and aim cross your mind again, you are always welcome.";
        $mailContent['to'] = $user->email;
        $mailContent['subject'] = "Account Deletion Successful";

        dispatch(new SendEmailNotification($mailContent));

        $user->delete();
    }


    public static function getChartData()
    {
        $chartData = self::prepareChartData();

        $indexes = [];
        $students = [];
        $graduates = [];
        $mentors = [];
        $businesses = [];


        foreach ($chartData as $date => $data) {

            $indexes[] = $date;

            foreach ($chartData[$date] as $key => $item) {
                if ($key === 'students') {
                    $students[] = $item;
                }

                if ($key === 'graduates') {
                    $graduates[] = $item;
                }

                if ($key === 'mentors') {
                    $mentors[] = $item;
                }

                if ($key === 'businesses') {
                    $businesses[] = $item;
                }
            }


        }

        $result["index"] = $indexes;
        $result["data"] = [
            [
                'name' => 'students',
                'data' => $students
            ],
            [
                'name' => 'graduates',
                'data' => $graduates
            ],
            [
                'name' => 'mentor',
                'data' => $mentors
            ],
            [
                'name' => 'businesses',
                'data' => $businesses
            ]

        ];

        return $result;
    }

    public static function prepareChartData()
    {
        $bar_chart_data = [];

        User::orderBy('id','asc')->get()
            ->mapToGroups( function ($item) use (&$bar_chart_data) {
                $bar_chart_data[Carbon::parse($item->created_at)->format('M') . " " . Carbon::parse($item->created_at)->format('Y') ][] = $item->id;
                return [];
            });

        foreach ($bar_chart_data as $date => $chart_datum) {
            $bar_chart_data[$date]['students'] = self::sortRoles($chart_datum, 'student');
            $bar_chart_data[$date]['graduates'] = self::sortRoles($chart_datum, 'graduate');
            $bar_chart_data[$date]['mentors'] = self::sortRoles($chart_datum, 'mentor');
            $bar_chart_data[$date]['businesses'] = self::sortRoles($chart_datum, 'business');
        }


        foreach ($bar_chart_data as $date => $bar_chart_datum) {
            //remove every index that are not a string.
            $bar_chart_data[$date] = array_filter($bar_chart_data[$date], 'is_string', ARRAY_FILTER_USE_KEY);
        }

        return $bar_chart_data;
    }

    private static function sortRoles($collection, $role)
    {
        $sorted = [];

      switch ($role) {
          case 'student':
              $studentIds = Student::orderBy('id','asc')->pluck('user_id')->toArray();

//              dd($collection, $studentIds);
              foreach ($collection as $item) {

//                  dd($item, $studentIds);

                  if (in_array($item,$studentIds)) {
                      $sorted[] = $item;
                  }
              }
              break;


          case 'graduate':
              $graduateIds = Graduate::orderBy('id','asc')->pluck('user_id')->toArray();

              foreach ($collection as $item) {
                  if (in_array($item,$graduateIds)) {
                      $sorted[] = $item;
                  }
              }
              break;


          case 'mentor':
              $mentorIds = Mentor::orderBy('id','asc')->pluck('user_id')->toArray();

              foreach ($collection as $item) {
                  if (in_array($item,$mentorIds)) {
                      $sorted[] = $item;
                  }
              }
              break;


          case 'business':
              $businessIds = Business::orderBy('id','asc')->pluck('user_id')->toArray();

              foreach ($collection as $item) {
                  if (in_array($item,$businessIds)) {
                      $sorted[] = $item;
                  }
              }
              break;
      }

      return count($sorted);
    }
}
