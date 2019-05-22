<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessVenture;
use App\Models\Partnership;
use App\Models\User;
use Illuminate\Http\Request;

class ApiVentureController extends Controller
{
    /**
     * @var User
     */
    protected $user;

    protected $url;


    /**
     * ApiAccountController constructor.
     * @param User $userModel
     */
    public function __construct( User $userModel )
    {
        $this->user = $userModel;
        $this->middleware('auth:api');
        $this->url = url('/');
    }

    public function index()
    {
        $all = BusinessVenture::with('business')
            ->with('business.user')
            ->orderBy('id','desc')
            ->get(['id','business_id','identifier','venture_name','created_at']);
        return response()->json(['ventures' => $all]);
    }

    public function allBusiness()
    {
        $all = Business::orderBy('id','asc')->get();
        return response()->json(['all' => $all]);
    }


    public function ventureByBusiness($id)
    {
        $ventures = $this->businessVentures($id);
        $partners = Partnership::where('business_id','=',$id)->with('venture')->with('user')->with('business')->get();
        return response()->json(['ventures' => $ventures->ventures, 'partners' => $partners]);
    }


    public function store( Request $request )
    {
        $data = $request->all();
        $business = Business::find($data['business_id']);
        $data['identifier'] = strtoupper(substr($business->name, 0 ,3)) . HelperController::generateIdentifier(5);

        BusinessVenture::create($data);

        $ventures = $this->businessVentures($data['business_id']);

        return response()->json($ventures);
    }


    public function update( Request $request, $id )
    {
        $data = $request->all();

        BusinessVenture::find($id)->update($data);
        $ventures = $this->businessVentures($data['business_id']);

        return response()->json($ventures);
    }


    public function businessVentures($businessID)
    {
        return Business::with('ventures')->with('ventures.partners')->find($businessID);
    }

    public function ventureBusiness( $id )
    {
        $ventures = BusinessVenture::with('business')->with('business.user')->where('business_id','=',$id)->get();
        return response()->json(['ventures' => $ventures]);
    }


    public function removeVenture($business_id, $id)
    {
        BusinessVenture::find($id)->delete();

        $ventures = $this->businessVentures($business_id);

        return response()->json(['ventures' => $ventures->ventures]);
    }


    public function singleVenture($identifier)
    {
        $venture = BusinessVenture::with('business')->with('business.user')->where('identifier','=', $identifier)->first();
        $venturePartners = Partnership::where('venture_id','=',$venture->id)->where('business_id','=',$venture->business_id)->get();
        $data = [
            'venturePartners' => $venturePartners,
            'venture' => $venture,
            'similar' => BusinessVenture::with('business')->with('business.user')->where('business_id','=',$venture->business_id)->get()
        ];

        if (auth()->check()){
            $data['userIsPartner'] = Partnership::where('user_id','=',auth()->user()->id)
                ->where('venture_id','=',$venture->id)->first();
        }

        return response()->json(['result' => $data]);
    }


    public function applyToPartner( $ventureId, $userId )
    {
        $data = [];
        $role = HelperController::fetchRoleData($userId);
        $venture = BusinessVenture::where('id','=',$ventureId)->first();

        $data['user_id'] = $userId;
        $data['role_data_id'] = $role['data']->id;
        $data['venture_id'] = $ventureId;
        $data['business_id'] = $venture->business_id;

        $id = Partnership::create($data);

        $update = Partnership::find($id);

        return response()->json($update[0]);
    }

    public function acceptPartnership( $partnershipId, $userId )
    {
        $user = $this->user->find($userId);
        $ref_link = HelperController::generateIdentifier(15);
        $record = Partnership::find($partnershipId);
        $record->update([
            'ref_link' => $this->url . '/api/referral/' .$ref_link . '-' .$user->slug,
            'status' => 'accepted'
        ]);

        $partners = Partnership::where('business_id','=',$record->business_id)->with('venture')->with('user')->get();

        return response()->json($partners);
    }
}
