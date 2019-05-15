<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Industry;
use App\Models\State;
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


    public function profile()
    {
        $roleData = HelperController::fetchRoleData(auth()->user()->id);

        $profileData = [
            'roleData' =>  $roleData['data'],
            'role' => $roleData['role'],
            'user' => auth()->user(),
            'progress' => (new ApiAccountController($this->user))->checkProfileProgress(auth()->user()->id)
        ];

        return response()->json(['profileData' =>  $profileData]);
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
        return response()->json(['industry' => $industry]);
    }

    public function prepareFewIndustries()
    {
        return Industry::inRandomOrder()->with('mentors')->orderBy('name','asc')->get(['id','name','alias','slug'])->take(5);
    }

    public function prepareAllIndustries()
    {
        return Industry::with('mentors')->orderBy('name','asc')->get(['id','name','alias','slug']);
    }

    public function prepareSingleIndustry($slug)
    {
        return Industry::whereSlug($slug)->with('mentors')->first();
    }

    public function prepareStates($country_id)
    {
        return State::where('country_id','=',$country_id)->orderBy('id','asc')->get(['id','name']);
    }

    public function prepareCities($country_id)
    {
        return City::where('country_id','=',$country_id)->orderBy('id','asc')->get(['id','name']);
    }
}
