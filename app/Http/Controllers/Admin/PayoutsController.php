<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Business\UserBusinessOrder;
use App\Models\BusinessSettlementBatch;
use App\Models\BusinessVenture;
use App\Models\StoreSettlementBatch;
use App\Models\Transaction\DeliveryCharge;

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
            $settlement = BusinessSettlementBatch::find($data['id']);
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
            break;

            case 'store':
            $settlement = StoreSettlementBatch::find($data['id']);
            $raw = isset($settlement->settlement_reference)?json_decode($settlement->settlement_reference):[];
            foreach($raw as $key => $r){

                if($key !== 'total') {
                    $result[] = [
                        'order' => UserBusinessOrder::find($r->order_id),
                        'payout' => $r->payout
                    ];
                }
            }
            break;

            case 'delivery':
            $result = [];
            break;
        }


        return response()->json(['success' => true, 'items' => $result ]);
    }
}
