<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PendingSettlements;
use App\Models\Store\UserVentureOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Business\UserBusinessOrder;
use App\Models\BusinessSettlementBatch;
use App\Models\BusinessVenture;
use App\Models\StoreSettlementBatch;
use App\Models\Transaction\DeliveryCharge;
use Maatwebsite\Excel\Facades\Excel;

class PayoutsController extends Controller
{

    public function index( Request $request )
    {
        $data = $request->all();

        $result = [];

        switch($data['query']){
            case 'business':
            $result = BusinessSettlementBatch::with(['business','business.user'])->orderBy('id','desc')->get();
            break;

            case 'store':
            $result = StoreSettlementBatch::with(['store','store.user'])->orderBy('id','desc')->get();
            break;
/* $table->integer('active')->default(1); //At least one active settlement batch
            $table->string('status')->default('pending'); //processed if settled*/
            case 'all_pending':
                $result['biz'] = BusinessSettlementBatch::with(['business','business.user'])
                    ->byFilter(['active'=>1,'status'=>'pending'])
                    ->orderBy('id','desc')->get();
                $result['store'] = StoreSettlementBatch::with(['store','store.user'])
                    ->byFilter(['active'=>1,'status'=>0])
                    ->orderBy('id','desc')->get();

                break;

            case 'delivery':
            $result = [];
            break;
        }


        return response()->json(['success' => true, 'payouts' => $result]);
    }



    public function items( Request $request )
    {
        $data = $request->all();

        $result = [];

        switch($data['type']){
            case 'business':
            $settlement = UserBusinessOrder::with(['venture','settlement.order'])
                ->byFilter(['business_settlement_batch_id'=>$data['id']])->get();
            if(!is_null($settlement))
                    $result=$settlement;
                break;

            case 'store':
                $settlement = UserVentureOrder::with(['store','venture','settlement.order'])
                    ->byFilter(['store_settlement_batch_id'=>$data['id']])->get();
                if(!is_null($settlement))
                    $result=$settlement;
                break;
            case 'delivery':
            $result = [];
            break;
        }


        return response()->json(['success' => true, 'items' => $result ]);
    }

    public function exportPendingPayouts(){
        $result['biz'] = BusinessSettlementBatch::with(['business','business.user'])->orderBy('id','desc')->get();
        $result['store'] = StoreSettlementBatch::with(['store','store.user'])->orderBy('id','desc')->get();
        return  Excel::download(new PendingSettlements($result), 'startev_settlement_list.xlsx');
    }
}
