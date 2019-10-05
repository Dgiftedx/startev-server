<?php

namespace App\Repositories;

use App\Models\Business\UserBusinessOrder;
use App\Models\BusinessSettlementBatch;
use App\Models\Setting;
use App\Models\StoreSettlementBatch;
use App\Models\Transaction\VendorSettlement;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class StoreIncrement
{
    public function __construct()
    {
        return $this->run();
    }

    public function run()
    {
        $this->incrementCounter();
        $totals = $this->calculateStoreTotals();
        $payoutIds = $this->updateSettlement($totals);

        return $payoutIds;
    }


    public function incrementCounter()
    {
        $batches = StoreSettlementBatch::where('active','=', 1)->get();
        if(count($batches) > 0) {

            //
            foreach($batches as $batch) {
                $counter = $batch->counter?$batch->counter:0;
                StoreSettlementBatch::find($batch->id)->update(['counter' => $counter + 1]);
            }
        }
    }

    public function takeReady()
    {
        $selected = [];

        $starDays = (new Setting)->value('STORE_PAY_DAYS');
        $batches = StoreSettlementBatch::where('active','=', 1)->get();

        foreach($batches as $batch) {
            //if pay day is today
            // if($batch->counter === $starDays){
            //     $selected[] = $batch->id;
            // }

            $selected[] = $batch->id; //for now pay immediately;
        }

        return $selected;
    }


    public function gatherStorePayout()
    {
        $data = [];

        $ids = $this->takeReady();
        VendorSettlement::whereIn('store_id', $ids)->whereNull('store_reference_id')->get()
            ->mapToGroups(function($item) use (&$data) {
                $data[$item->store_id][] = [
                    'order_id' => $item->order_id,
                    'vendor_settlement_id' => $item->id,
                    'payout' => $item->commission_payout
                ];
                return [];
            });

        return $data;
    }


    public function calculateStoreTotals()
    {

        $results = $this->gatherStorePayout();

        foreach($results as $store => $result) {
            $total = 0;
            foreach($result as $r){
                $total += $r['payout'];
            }

            $results[$store]['total'] = $total;
        }

        return $results;
    }


    public function updateSettlement($payload)
    {
        $toPay = [];
        foreach($payload as $store => $p) {
            $settlement = StoreSettlementBatch::where('store_id','=',$store)->first();
            StoreSettlementBatch::find($settlement->id)->update([
                'settlement_reference' => json_encode($p),
                'total' => $payload[$store]['total']
            ]);

            $toPay[] = $settlement->id;
        }

        return $toPay;
    }

}
