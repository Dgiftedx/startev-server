<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Business\UserBusinessOrder;
use App\Models\BusinessSettlementBatch;
use App\Models\BusinessVenture;
use App\Models\CareerPath;
use App\Models\City;
use App\Models\Country;
use App\Models\Industry;
use App\Models\State;
use App\Models\Store\UserStore;
use App\Models\StoreSettlementBatch;
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

    //=======================================================================
    // Store Settlements
    //=======================================================================
    public function storeSettlements($id)
    {
        $store = UserStore::where('user_id', '=', $id)->first();

        $result = StoreSettlementBatch::with(['store'])
        ->where('store_id','=', $store->id)->orderBy('id','desc')->get();
        return response()->json(['success' => true, 'result' => $result ]);
    }


    public function storeSettlementItems($id)
    {
        $result = [];

        $settlement = StoreSettlementBatch::find($id);
        $raw = json_decode($settlement->settlement_reference);
        foreach($raw as $key => $r){
            if($key !== 'total') {
                $result[] = [
                    'order' => UserBusinessOrder::find($r->order_id),
                    'payout' => $r->payout
                ];
            }
        }
        return response()->json(['success' => true, 'items' => $result ]);
    }

    //========================================================================
    // Business Settlements
    //========================================================================
    public function businessSettlements($id)
    {
        $business = Business::where('user_id', '=', $id)->first();

        $result = BusinessSettlementBatch::with(['business','business.user'])
        ->where('business_id','=', $business->id)->orderBy('id','desc')->get();
        return response()->json(['success' => true, 'result' => $result ]);
    }

    public function settlementItems($id)
    {
        $result = [];

        $settlement = BusinessSettlementBatch::find($id);
        $raw = json_decode($settlement->orders_pile);
        foreach($raw as $key => $r){

            if($key !== 'total') {
                $result[] = [
                    'venture' => BusinessVenture::find($r->venture_id),
                    'order' => UserBusinessOrder::find($r->order_id),
                    'payout' => $r->payout
                ];
            }
        }

        return response()->json(['success' => true, 'items' => $result ]);
    }
}
