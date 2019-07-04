<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        $chartData = HelperController::prepareChartData();

        $result = [
            'students' => HelperController::getStudentIds(),
            'graduates' => HelperController::getGraduatesIds(),
            'mentors' => HelperController::getMentorsIds(),
            'businesses' => HelperController::getBusinessesIds(),
            'chartData' => $chartData
        ];

        return response()->json(['success' => true, 'result' => $result]);
    }
}
