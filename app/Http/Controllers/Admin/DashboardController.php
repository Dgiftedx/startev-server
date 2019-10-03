<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction\VendorSettlement;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    public function getStats()
    {

        $chartData = HelperController::getChartData();

        //Get today sales
        $todayOrdersQuery = VendorSettlement::whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->addHour()])
        ->pluck('total')->toArray();
        $todaySales = array_sum($todayOrdersQuery);

        //Get unpaid Escrows
        $unpaidRaw = VendorSettlement::where('status', '=','unpaid')->get();
        $unpaidEscrowed = 0;
        $unpaidSettlements = 0; //adding escrowed and business payout and subtracting paystack charge


        foreach($unpaidRaw as $settlement){
            $unpaidEscrowed += $settlement->escrowed; //at checkout, paystack has been paid
        }

        foreach($unpaidRaw as $settlement)  {
            $unpaidSettlements += ($settlement->escrowed + $settlement->business_payout);
        }

        $totalPayoutRaw = $unpaidRaw = VendorSettlement::where('status', '=','paid')->pluck('total')->toArray();
        $totalPayout = array_sum($totalPayoutRaw);

        $result = [
            'students' => HelperController::getStudentIds(),
            'graduates' => HelperController::getGraduatesIds(),
            'mentors' => HelperController::getMentorsIds(),
            'businesses' => HelperController::getBusinessesIds(),
            'today_sales' => $todaySales,
            'unpaid_escrowed' => $unpaidEscrowed,
            'unpaid_settlements' => $unpaidSettlements,
            'total_payout' => $totalPayout,
            'chartData' => $chartData
        ];

        return response()->json(['success' => true, 'result' => $result]);
    }
}
