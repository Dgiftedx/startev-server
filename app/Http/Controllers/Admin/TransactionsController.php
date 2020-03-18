<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction\VendorSettlement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionsController extends Controller
{
    protected $settlement;

    public function __construct( VendorSettlement $settlement )
    {
        $this->settlement = $settlement;
    }



    public function index( Request $request )
    {
        $query = $request->all();

        if ($query['status'] === 'all'){
            $settlements  = $this->settlement->with(['order','order.buyer','order.venture','order.store','order.mainProduct'])->get();
        }else{
            $settlements  = $this->settlement->with(['order','order.buyer','order.venture','order.store','order.mainProduct'])->byFilter($query)->get();
        }

        return response()->json(['success' => true, 'settlements' => $settlements]);
    }
    public function orderList() {
        return view('pages.order.order-list');
    }
}
