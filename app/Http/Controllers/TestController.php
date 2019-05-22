<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestController extends Controller
{


    protected $user;

    public function __construct(User $userModel)
    {
        $this->user = $userModel;
    }


    public function testCarbon()
    {
        $year = 2019;
        $month = 05;

        $datesArray = [];
        $datesArray['date'] = Carbon::createFromDate($year, $month);
        $datesArray['days'] = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $startRaw = Carbon::parse($datesArray['date'])->startOfMonth();
        $endRaw = Carbon::parse($datesArray['date'])->endOfMonth();
        $datesArray['dateLoop'] = [];

        $feeds = Feed::whereBetween('created_at',[$startRaw, $endRaw])->orderBy('id','asc')
            ->get();


        $startDay = (int) $startRaw->format('d');

        for ($i = $startDay; $i <= $datesArray['days']; $i++){
            $datesArray['dateLoop'][] = Carbon::create($year, $month, $i);
        }


        $collection = [];
        $absent = [];
        $total = 0;


            foreach($feeds as $item) {
                foreach ($datesArray['dateLoop'] as $date) {

                    if (Carbon::parse($item->created_at)->format('d') == (int) $date->format('d')){

                        if(Carbon::parse($item->created_at)->format('D') == $date->format('D') && $date->isWeekend()){
                            dump("it's weekend");
                        }else{

                               $total++;

                               if (!is_null($item->body)){

                                   //check is it's set
                                   if (!isset($collection[$item->id][$date->format('d')]['present'])){
                                       $collection[$item->id][$date->format('d')]['present'] = $collection[$item->id][$date->format('d')]['present'] = 1;
                                       $collection[$item->id][$date->format('d')]['absent'] = $collection[$item->id][$date->format('d')]['absent'] = 0;
                                   }

                               }else{
                                   //do nothing
                               }

                        }
                    }
                }
            }

        dump($collection, $total);
    }
}
