<?php

namespace App\Http\Controllers;

use App\Models\CareerPath;
use App\Models\City;
use App\Models\Country;
use App\Models\Industry;
use App\Models\State;
use App\Models\Trainee;
use App\Models\User;
use Illuminate\Http\Request;

class ApiCommonController extends Controller
{


    protected  $user;

    public function __construct(User $userModel)
    {
        $this->user = $userModel;
        $this->middleware('auth:api');
    }

    public function industries()
    {
        $industries = $this->prepareFewIndustries();
        return response()->json(['industries' => $industries]);
    }

    public function countries()
    {
        return response()->json(['countries' => Country::orderBy('id','asc')->get(['id','name'])]);
    }

    public function states($id)
    {
        $states = $this->prepareStates($id);
        return response()->json(['states' => $states]);
    }

    public function cities($id)
    {
        $cities = $this->prepareCities($id);
        return response()->json(['cities' => $cities]);
    }


    public function allIndustries()
    {
        $industries = $this->prepareAllIndustries();
        return response()->json(['industries' => $industries]);
    }

    public function singleIndustry( $slug )
    {
        $industry = $this->prepareSingleIndustry($slug);
        $userConnections  = [];
        if (auth()->check()) {
            $userConnections = Trainee::where('trainee_id','=',auth()->user()->id)->get();
        }
        return response()->json(['industry' => $industry, 'connections' => $userConnections]);
    }

    public function careerPaths()
    {
        $careers = CareerPath::orderBy('id','asc')->get(['id','name']);
        return response()->json(['careers' => $careers]);
    }

    public function prepareFewIndustries()
    {
        return Industry::inRandomOrder()->with('mentors')->orderBy('name','asc')->get(['id','name','alias','slug'])->take(7);
    }

    public function prepareAllIndustries()
    {
        return Industry::with('mentors')->orderBy('name','asc')->get(['id','name','alias','slug']);
    }

    public function prepareSingleIndustry($slug)
    {
        return Industry::whereSlug($slug)->with('mentors')->with('mentors.mentor')->with('mentors.trainerPivot')->with('mentors.business')->first();
    }

    public function prepareStates($country_id)
    {
        return State::where('country_id','=',$country_id)->orderBy('id','asc')->get(['id','name']);
    }

    public function prepareCities($country_id)
    {
        return City::where('country_id','=',$country_id)->orderBy('id','asc')->get(['id','name']);
    }


    //Mentor
    public function singleMentor( $slug )
    {
        $user = $this->user->whereSlug($slug)->first();
        $roleData = HelperController::fetchRoleData($user->id);

        $profile = [
            'user' => $user,
            'roleData' => $roleData,
            'role' => $roleData['role'],
            'followers' => $user->followers()->get(),
            'following' => $user->followings()->get(),
            'mentors' => HelperController::mentors()
        ];
        
        return response()->json(['profile' => $profile]);
    }
}
