<?php

namespace App\Repositories;

use App\Models\Business\UserBusinessOrder;
use App\Models\BusinessSettlementBatch;
use App\Models\Transaction\VendorSettlement;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class RequestBusinessPayout
{

    public function run()
    {
        return $this->saveBusinessBatch();
    }


    public function getBusinessOrders()
    {
        $result = [];

        UserBusinessOrder::where('status','=','delivered')
        ->get()
        ->mapToGroups(function($item) use (&$result) {
            $result[$item->business_id][] = $item;
            return [];
        });

        return $result;
    }

    public function filterUnsettledSplits()
    {
        $collection = [];

        foreach($this->getBusinessOrders() as $id => $orders) {

            $collection[$id] = [];
            $total = 0;
            foreach($orders as $order) {
                $settlement = VendorSettlement::where('order_id', '=', $order->id)->first();

                if(!$settlement->business_settled) {
                    $collection[$id][] = [
                        'settlement_id' => $settlement->id,
                        'business_id' => $order->business_id,
                        'venture_id' => $order->venture_id,
                        'order_id' => $order->id,
                        'payout' => $settlement->business_payout,
                    ];

                    $total += $settlement->business_payout;
                }
            }

            $collection[$id]['total'] = $total;
        }

        return $collection;
    }


    /**
     * @return array
     */
    public function saveBusinessBatch()
    {
        $batchIds = [];

        $businessSplit = $this->filterUnsettledSplits();

        foreach($businessSplit as $id => $split) {
            $total = $businessSplit[$id]['total'];

            if (BusinessSettlementBatch::where('business_id','=',$id)->where('status','=','pending')->exists()){
                $batch = BusinessSettlementBatch::where('business_id','=',$id)->where('status','=','pending')->first();
            }else{
                $batch = BusinessSettlementBatch::create([
                    'business_id' => $id,
                    'orders_pile' => json_encode($split),
                    'total' => $total
                ]);
            }

            $batchIds[] = $batch->id;
        }

        return $batchIds;
    }


}
