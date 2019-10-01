<?php

namespace App\Http\Controllers\Admin;

use App\Models\Business\UserBusinessOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BatchOrder;
use App\Models\Store\UserVentureOrder;

class OrdersController extends Controller
{


    public function index( $type )
    {
        $query['status'] = '';

        /**
         * First, if query status is new,
         * then we fetch data from the batch to business orders
         * as needed, but if not, we fetch each item separately and then
         * group by batch_id with which we can use to get logged batch.
         */

        if($type === 'new') {
            $query['status'] = 'pending';
            //fetch result using the parameters
            $orders = BatchOrder::with(['store','buyer','ordersBusiness','ordersBusiness.venture','ordersBusiness.mainProduct'])->byFilter($query)->get();
            return response()->json(['success' => true, 'orders' => $orders]);
        }

        switch ($type) {
            case "confirmed":
                $query['status'] = "confirmed";
                break;

            case "delivered":
                $query['status'] = "delivered";
                break;

            case "cancelled":
                $query['status'] = "cancelled";
                break;
        }

        // $orders = [];

        // UserBusinessOrder::with(['store','mainProduct','buyer','venture'])->byFilter($query)->get()
        // ->mapToGroups(function( $item ) use (&$orders) {

        //     $orders[$item->batch_id][] = $item;
        //     return [];
        // });

        $orders =  UserBusinessOrder::with(['store','mainProduct','buyer','venture'])->byFilter($query)->get();

        return response()->json(['success' => true, 'orders' => $orders]);

    }



    public function confirmOrder( $id )
    {
        $order = UserBusinessOrder::find($id);
        $order->update(['status' => 'confirmed']);

        $ventureOrder = UserVentureOrder::where('identifier','=',$order->identifier)->first();
        UserVentureOrder::find($ventureOrder->id)->update(['status' => 'confirmed']);

        $batch = BatchOrder::where('batch_id','=', $order->batch_id)->first();
        BatchOrder::find($batch->id)->update(['status' => 'confirmed']);
        /**
         * Wherever we need to send the payload to for delivery,
         * it's done below and success response is returned to the server
         */


        return response()->json(['success' => true ]);
    }


    public function final($id)
    {
        /**
         * Here: necessary disbursement is done and all required settlements.
         * Paystack percentage is sent,
         * Student's commission is logged or have been logged,
         * Businesses accounts get credited,
         * Startev percentage is deducted and credited.
         */

         return response()->json(['success' => true ]);
    }
}
