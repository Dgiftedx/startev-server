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
//        $todayOrdersQuery = VendorSettlement::all()->pluck('total')->toArray();
        $todayOrdersQuery = VendorSettlement::whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->addHour()])->pluck('total')->toArray();
//        dd($todayOrdersQuery);
        $todaySales = array_sum($todayOrdersQuery);

        //Get unpaid Escrows
        $unpaidRaw = VendorSettlement::where('status', '=','unpaid')->get();
        $paidRaw = VendorSettlement::all();
        $unpaidEscrowed = 0;
        $unpaidSettlements = 0; //adding escrowed and business payout and subtracting paystack charge
        $startevCommission = 0;
        $delivery = 0;
        $paystack_charge = 0;
        $commission_payout = 0;
        $total_transaction = 0;

        foreach($unpaidRaw as $settlement){
            $unpaidEscrowed = $unpaidEscrowed + $settlement->escrowed; //at checkout, paystack has been paid
        }

        foreach($unpaidRaw as $settlement)  {
            $unpaidSettlements += ($settlement->escrowed + $settlement->business_payout);
        }

        foreach($paidRaw as $settlement)  {
            $startevCommission += $settlement->startev_charge;
        }

        foreach($paidRaw as $settlement)  {
            $delivery+= $settlement->delivery;
        }

        foreach($paidRaw as $settlement)  {
            $paystack_charge+= $settlement->paystack_charge;
        }

        foreach($paidRaw as $settlement)  {
            $commission_payout+= $settlement->commission_payout;
        }

        foreach($paidRaw as $settlement)  {
            $total_transaction+= $settlement->total;
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
            'chartData' => $chartData,
            'startevCommission' => $startevCommission,
            'delivery' => $delivery,
            'paystack_charge' => $paystack_charge,
            'commission_payout' => $commission_payout,
            'total_transaction' => $total_transaction,
        ];

        return response()->json(['success' => true, 'result' => $result]);
    }
}
