<?php

namespace App\Http\Controllers;

use App\Models\Student;
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
        $result['professionals'] = count(array_unique(array_merge(HelperController::realMentorsId(), HelperController::realBusinessId())));
        $result['students'] = count(Student::get(['id']));
        $result['businesses'] = count(HelperController::businessIds());

        return $result;
    }

}
