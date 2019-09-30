<?php

namespace App\Http\Controllers;

use App\Models\Vocal;
use App\Models\VocalReferral;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VocalsController extends Controller
{

    protected $vocal;

    public function __construct( Vocal $vocalModel )
    {
        $this->vocal = $vocalModel;
    }

    private function todayRef($vocalId)
    {
        $todayStart = Carbon::now()->startOfDay()->toDateTimeString();
        $todayEnd = Carbon::now()->toDateTimeString();

        $refs = VocalReferral::where('vocal_id', '=', $vocalId)->where('status','=','pending')->whereBetween('registered_on', [$todayStart, $todayEnd])->count();

       return $refs;
    }

    public function allVocals()
    {
        $vocals = $this->vocal->with(['registeredUsers' => function($query) {
            $query->where('status','=', 'pending');
        }])->orderBy('id','desc')->get();

        foreach ($vocals as $vocal) {
            $vocal->refToday = $this->todayRef($vocal->id);
        }

        return response()->json(['success' => true, 'vocals' => $vocals]);
    }


    public function store( Request $request )
    {
        $data = $request->all();

        if ($this->vocal->where('email','=',$data['email'])->exists()) {
            return response()->json(['error' => "User with the same email address already exist."], 401);
        }

        $data['ref_code'] = HelperController::generateRefCode(17);

        $this->vocal->create($data);

        return response()->json(['success' => true ]);
    }

    public function resetVocal($vocalId)
    {
        $referrals = VocalReferral::where('vocal_id', '=', $vocalId)->where('status','=','pending')->get();
        if(count($referrals) < 1) return response()->json(['success' => true, 'message' => "no data to refresh"]);

        //continue
        foreach($referrals as $referral) {
            VocalReferral::find($referral->id)->update(['status' => 'settled']);
        }

        return response()->json(['success' => true, 'message' => "registration count refreshed"]);
    }


    public function deleteVocal($id)
    {
        $vocal = $this->vocal->find($id);
        DB::table('vocal_referrals')->where('vocal_id','=', $id)->delete();
        $vocal->delete();
        return response()->json(['success' => "Vocal removed from system"]);
    }
}
