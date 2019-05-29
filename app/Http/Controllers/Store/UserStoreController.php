<?php

namespace App\Http\Controllers\Store;

use App\Models\Store\UserStore;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserStoreController extends Controller
{
    /**
     * @var User
     */
    protected $user;

    protected $url;

    protected $store;


    /**
     * ApiAccountController constructor.
     * @param User $userModel
     * @param UserStore $userStore
     */
    public function __construct( User $userModel, UserStore $userStore )
    {
        //User model property
        $this->user = $userModel;

        //User Store model property
        $this->store = $userStore;

        //Authentication Middleware
        $this->middleware('auth:api');
        //Base url
        $this->url = url('/');
    }

    /**
     * Get user store dashboard data
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboard( $userId )
    {
        // Gather data
        $data = [
            'hasActiveStore' => StoreHelperController::hasActiveStore($userId),
            'orders_amount' => StoreHelperController::OrdersTotalAmount($userId),
            'delivered_orders' => StoreHelperController::OrdersDeliveredAmount($userId),
            'total_commission' => StoreHelperController::totalCommission($userId),
            'recent_orders' => StoreHelperController::recentOrders($userId)
        ];
        // return json response
        return response()->json($data);
    }

    /**
     * check if user has active store
     * @param $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function hasStore( $user )
    {
        $check = StoreHelperController::hasActiveStore($user);
        return response()->json($check);
    }


    /**
     * Get Store Orders
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeOrders( $userId )
    {
        $query = ['store_id' => UserStore::storeId($userId)];
        $orders = StoreHelperController::getOrders($query);
        return response()->json($orders);
    }

}
