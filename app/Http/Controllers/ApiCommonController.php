<?php

namespace App\Http\Controllers;

use App\Models\Industry;
use Illuminate\Http\Request;

class ApiCommonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function industries()
    {
        $industries = $this->prepareFewIndustries();
        return response()->json(['industries' => $industries]);
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
}
