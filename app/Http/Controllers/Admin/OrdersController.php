<?php

namespace App\Http\Controllers\Admin;

use App\Models\Business\UserBusinessOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BatchOrder;

class OrdersController extends Controller
{


    public function index( $type )
    {
        $query['status'] = '';

        switch ($type) {
            case "new":
                $query['status'] = 'pending';
                break;

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


        //fetch result using the parameters
        $orders = BatchOrder::with(['store','buyer','ordersBusiness','ordersBusiness.venture'])->byFilter($query)->get();
        return response()->json(['success' => true, 'orders' => $orders]);

    }
}
