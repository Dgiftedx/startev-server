<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessVenture;
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
        return response()->json(['ventures' => $ventures->ventures]);
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
        return Business::with('ventures')->find($businessID);
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
        $data = [
            'venture' => $venture,
            'similar' => BusinessVenture::with('business')->with('business.user')->where('business_id','=',$venture->business_id)->get()
        ];

        return response()->json(['result' => $data]);
    }
}
