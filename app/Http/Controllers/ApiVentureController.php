<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Mail\MailController;
use App\Http\Controllers\Store\StoreHelperController;
use App\Jobs\SendEmailNotification;
use App\Models\Business;
use App\Models\BusinessVenture;
use App\Models\Partnership;
use App\Models\User;
use Spatie\Searchable\Search;
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

    public function getPartners()
    {
        $id = auth()->user()->id;
        $partners = $this->user->with('partnerUserPivot')->find($id);
        return response()->json(['partners' => $partners->partnerUserPivot]);
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

        if (isset($ventures->ventures)) {
            return response()->json(['ventures' => $ventures->ventures, 'partners' => $partners]);
        }

        return response()->json(['ventures' => [], 'partners' => $partners]);
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
        //denial request if venture has been partnered with already

        if (Partnership::where('venture_id','=',$id)->exists()) {
            return response()->json(['success' => false, "message" => "Can't remove venture. People already partnered with this venture"]);
        }
        BusinessVenture::find($id)->delete();

        $ventures = $this->businessVentures($business_id);

        return response()->json(['success' => true, 'ventures' => $ventures->ventures]);
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


    public function applyToPartner(Request $request)
    {

        $input = $request->all();
        $data = [];
        $role = HelperController::fetchRoleData($input['user_id']);
        $venture = BusinessVenture::where('id','=',$input['id'])->first();
        $data['type'] = $role['role'];
        $data['user_id'] = $input['user_id'];
        $data['role_data_id'] = $role['data']->id;
        $data['venture_id'] = $input['id'];
        $data['business_id'] = $venture->business_id;

        //set application letter is it's set
        if(isset($input['letter'])) {
            $data['letter'] = $input['letter'];
        }

        $id = Partnership::create($data);
        $update = Partnership::find($id);

        $mailContent['message'] = "Your partnership request with <strong>{$venture->venture_name}</strong> has been submitted. You'll be notified upon approval.";
        $user = User::find($input['user_id']);
        $mailContent['to'] = $user->email;
        $mailContent['subject'] = "Partnership Update";

        dispatch(new SendEmailNotification($mailContent));

        return response()->json($update[0]);
    }

    public function acceptPartnership( Request $request )
    {
        $input = $request->all();

        $user = $this->user->find($input['user_id']);
        $ref_link = HelperController::generateIdentifier(15);
        $record = Partnership::find($input['partner_id']);

        // Handle user store as partnership comes with automatic store activation.
        //only and only if it's student
        // If this user doesn't has a store in place, create one.
        $messageAppend = "";
        if (($record->type === 'student') || ($record->type === 'graduate')) {
            StoreHelperController::CreateUserStore($input['user_id']);
            $messageAppend = "<br/>In case you don't have a store, a store has been created for you. Login now to activate by updating your store details";
        }

        //Update partnership data with referral link
        $record->update([
            'ref_link' => $this->url . '/api/referral/' .$ref_link . '-' .$user->slug,
            'status' => 'accepted'
        ]);

        $partners = Partnership::where('business_id','=',$record->business_id)->with('venture')->with('user')->get();

        $venture = BusinessVenture::where('id','=', $record->venture_id)->first();
        $mailContent['message'] = "Your partnership request with <strong>{$venture->venture_name}</strong> has been <b style=\"color: #1d643b\">Approved</b>. {$messageAppend}";
        $mailContent['to'] = $user->email;
        $mailContent['subject'] = "New Partnership Approval";

        //send mail notification
        dispatch(new SendEmailNotification($mailContent));

        return response()->json($partners);
    }


    public function searchVenture( Request $request )
    {
        $searchTerms = $request->get('query');
        $searchIds = [];

        $ventures = [];

        $searchResults = (new Search())
            ->registerModel(BusinessVenture::class, 'venture_name')
            ->perform($searchTerms);

        foreach ($searchResults as $result) {
            $searchIds[] = $result->searchable->id;
        }

        if (count($searchIds) > 0) {
            $ventures = BusinessVenture::with('business')->with('business.user')->whereIn('id', $searchIds)->get();
        }

        return response()->json($ventures); 
    }
}
