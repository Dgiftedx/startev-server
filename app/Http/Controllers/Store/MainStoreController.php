<?php

namespace App\Http\Controllers\Store;

use App\Models\Store\UserVentureProduct;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MainStoreController extends Controller
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
        //User model property
        $this->user = $userModel;

        //Authentication Middleware
        $this->middleware('auth:api');
        //Base url
        $this->url = url('/');
    }


    public function index( $identifier )
    {
        $store = StoreHelperController::getMainStoreInfo($identifier);

        $params = [
            'store_id' => $store->id,
        ];

        $products = StoreHelperController::getMainStoreProducts($params);

        return response()->json(['products' => $products, 'storeDetails' => $store]);
    }


    public function singleProduct( $product )
    {
        $single = UserVentureProduct::find($product);
        return response()->json($single);
    }
}
