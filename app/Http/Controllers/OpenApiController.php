<?php

namespace App\Http\Controllers;

use App\Models\Broadcast\BroadcastSchedule;
use App\Models\Business;
use App\Models\BusinessVenture;
use App\Models\Feed;
use App\Models\Student;
use App\Models\User;
use App\Repositories\GuzzleCall;
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


    public function searchPlacesByAddress( Request $request )
    {
        //Get query text
        $query = $request->get('query');
        //Get raw result from api call
        $raw = $this->googleSearchPlaces($query);
        //process and format result
        $result = $this->processPlacesResult($raw);
        //return json response
        return response()->json($result);
    }

    public function calculateDelivery( Request $request )
    {
        $data = $request->all();

        $verified = [];
        $ids = $data['venture_ids'];
        //fetch venture business address
        $business = BusinessVenture::whereIn('id', $ids)->pluck('business_id')->toArray();
        $business = array_unique($business);

        //first get address of vendors from where product will be picked up
        $vendors = Business::whereIn('id',$business)->pluck('user_id')->toArray();
        $addresses = User::whereIn('id',$vendors)->get(['id','address','is_address_verified','lat','long']);

        //get coordinates of delivery
        $query = [
            ['type' => 'place_id', 'value' => $data['place_id']]
        ];

        foreach ($addresses as $address){
            if ($address->is_address_verified === 1) {
                $verified[] = $address->address;
                $query[] = ['type' => 'address', 'value' => $address->address];
            }
        }

        return response()->json(['success' => true, "result" => ['price' => 500]]);

        //before pushing destinations to query, check if at least there is one verified address
        // if (count($verified) < 1) {
        //     return response()->json(['error' => "We can't verify the address of the Vendor whose product(s) you are trying to checkout. You can't proceed with this checkout"]);
        // }

        // $result = $this->googleFindDistance($query);

        // return response()->json(['success' => true, "result" => $result]);
    }


    /**
     * Process result and remove unwanted parameters
     * @param $result
     * @return array
     */
    private function processPlacesResult($result)
    {
        $formatted = [];
        //perform process
        foreach ($result['predictions'] as $suggestion) {
            $formatted[] = [
                'id' => isset($suggestion['id'])?$suggestion['id']:'',
                'place_id' => isset($suggestion['place_id'])?$suggestion['place_id']:'',
                'name' => isset($suggestion['description'])?$suggestion['description']:'',
            ];
        }
        //return response
        return $formatted;
    }

    /**
     * Create full end point from available parameters
     * @param $partialEndPoint
     * @param string $initial
     * @return string
     */
    private function createFullEndPoint($partialEndPoint, $initial = 'input')
    {
        return $partialEndPoint . "key=" . env('GOOGLE_KEY') . "&{$initial}=";
    }

    /**
     * Make actual call to external API
     * @param $address
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     */
    private function googleSearchPlaces($address)
    {
        //fetch places endpoint
        $endpoint = env('GOOGLE_PLACE_ENDPOINT');
        //create full endpoint url with key and query
        $fullEndPoint = $this->createFullEndPoint($endpoint) . $address;
        //make api call and return json response
        return (new GuzzleCall('GET', $fullEndPoint))->run();
    }

    private function googleFindDistance($query)
    {
        //fetch places endpoint
        $endpoint = env('GOOGLE_MATRIX_ENDPOINT');

        $params = $this->createDistanceParams($query);
        //create full endpoint url with key and parameters
        $fullEndPoint = $this->createFullEndPoint($endpoint, 'origin') . $params;
        //make api call and return json response
        return (new GuzzleCall('GET', $fullEndPoint))->run();
    }


    private function createDistanceParams($array)
    {
        $string = '';
        //create query string
        foreach ($array as $arr){
            if ($arr['type'] === 'place_id'){
                $string .= "place_id:{$arr['value']}&destinations=";
            }

            if ($arr['type'] === 'address') {
                $string .= "{$arr['value']}|";
            }
        }

        dd($string);

        return $string;
    }
}
