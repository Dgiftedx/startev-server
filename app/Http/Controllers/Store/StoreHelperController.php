<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\HelperController;
use App\Models\Business;
use App\Models\Business\UserBusinessProduct;
use App\Models\Business\UserBusinessOrder;
use App\Models\Partnership;
use App\Models\Store\UserStore;
use App\Models\Store\UserVentureCommission;
use App\Models\Store\UserVentureOrder;
use App\Models\Store\UserVentureReview;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class StoreHelperController
 * @package App\Http\Controllers\Store
 */
class StoreHelperController extends Controller
{

    /**
     * Store checker : check if user has an active
     * Store.
     * @param $userId
     * @return mixed
     */
    public static function hasActiveStore($userId)
    {
        return UserStore::hasStore($userId);
    }


    /**
     * Chec if user has commission
     * @param $userId
     * @return mixed
     */
    public static function hasCommission($userId)
    {
        return UserVentureCommission::hasCommission($userId);

    }


    /**
     * Check if user has an active store and made orders.
     * @param $userId
     * @return bool
     */
    public static function hasStoreAndOrders( $userId )
    {
        //Check if use has an active store and has orders
        return self::hasActiveStore($userId) &&
            UserVentureOrder::hasOrders(UserStore::storeId($userId))?true:false;
    }


    /**
     * Create user store with setup data
     * if it has not been created
     * @param $userId
     * @return void
     */
    public static function CreateUserStore($userId)
    {
        if (!self::hasActiveStore($userId)){
            $setupData = self::prepareStoreInitialData();
            $setupData['user_id'] = $userId;
            UserStore::create($setupData);
        }
    }

    /**
     * Get initial setup settings needed for new store
     * Some of this settings can be changes later,
     * While some are permanent, e.g slug and identifier
     * @return array
     */
    private static function prepareStoreInitialData()
    {
        return [
            'slug' => uniqid(rand(), true),
            'identifier' => HelperController::generateIdentifier(18),
        ];
    }



    /**
     * Get orders total amount
     * @param $userId
     * @return float|int
     */
    public static function OrdersTotalAmount( $userId )
    {
        // If user has an active store and has orders
        // return orders total amount.
        if (self::hasStoreAndOrders($userId)) {
            //return amount
            return self::getOrdersTotalAmount(UserStore::storeId($userId));
        }

        // if there is no active store, return 0,
        // of course you must have an active store before
        // calling this guy.
        return 0;

    }


    public static function OrdersDeliveredAmount( $userId )
    {
        // If user has an active store and has orders
        // return total orders delivered amount.
        if (self::hasStoreAndOrders($userId)) {
            //return amount
            return self::getOrdersDeliveredAmount(UserStore::storeId($userId));
        }

        // if there is no active store, or orders,
        // of course you must have an active store before
        // calling this guy.
        return 0;
    }

    /**
     * Get total commission sor far
     * @param $userId
     * @return float|int
     */
    public static function totalCommission($userId)
    {
        // If user has commission, get total commission so far
        if (self::hasCommission($userId)) {
            return self::getTotalCommission(UserStore::storeId($userId), $userId);
        }

        return 0;
    }


    /**
     * Get recent orders
     * @param $userId
     * @return mixed
     */
    public static function recentOrders($userId)
    {
        //Build query array
        $query = [
            'store_id' => UserStore::storeId($userId),
            'status' => 'pending'
        ];

        return self::getOrders($query);
    }




    public static function userStore( $userId )
    {
        return UserStore::with('user')
            ->where('user_id','=',$userId)->first();
    }


    /**
     * Get total amount of orders in store
     * @param $storeId
     * @return float|int
     */
    private static function getOrdersTotalAmount($storeId)
    {
        $total = UserVentureOrder::where('store_id','=',$storeId)->pluck(['amount'])->toArray();
        return array_sum($total);
    }


    /**
     * Get amount of orders deleivered so far.
     * @param $storeId
     * @return float|int
     */
    private static function getOrdersDeliveredAmount($storeId)
    {
        $deliveredAmount = UserVentureOrder::where('store_id','=',$storeId)
            ->where('status','=','delivered')->pluck(['amount'])->toArray();
        return array_sum($deliveredAmount);
    }

    /**
     * Get user total commissions
     * @param $storeId
     * @param $userId
     * @return float|int
     */
    private static function getTotalCommission($storeId, $userId)
    {
        $total = UserVentureCommission::where('store_id','=',$storeId)
            ->where('user_id','=', $userId)->pluck(['commission'])->toArray();
        return array_sum($total);
    }


    /**
     * query Orders Model and return result
     * @param $query
     * @return mixed
     */
    public static function getOrders($query)
    {
        return UserVentureOrder::with('buyer')->with('product')->byFilter($query)->get();
    }


    /**
     * Get user venture list/partnership list
     * Also attach venture and business entity.
     * @param $userId
     * @return mixed
     */
    public static function getVentureList($userId)
    {
        return Partnership::where('user_id','=',$userId)
            ->with('venture')
            ->with('business')
            ->with('business.user')
            ->orderBy('id','asc')
            ->get();
    }

    /**
     * Get user store reviews
     * @param array $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function gatherReviews( $userId, $query = [] )
    {
        $storeId = UserStore::storeId($userId);
        // Get all reviews
        $reviews = UserVentureReview::where('store_id','=', $storeId)
            ->with('buyer')->orderBy('id','desc')->get();

        // If there are query parameters, use them
        if (count($query) > 0) {
            $reviews = UserVentureReview::where('store_id','=', $storeId)
                ->with('buyer')->orderBy('id','desc')->byFilter($query)->get();
        }

        // Return query result
        return $reviews;
    }








    ////////////////////////////////////////////////////////////////////////////////////////////
    /// Business Store Helpers


    public static function businessHasOrders($userId)
    {
        return UserBusinessOrder::hasOrders(Business::businessId($userId));
    }

    /**
     * Get recent orders
     * @param $userId
     * @return mixed
     */
    public static function recentBusinessOrders($userId)
    {
        //Build query array
        $query = [
            'business_id' => Business::businessId($userId),
            'status' => 'pending'
        ];

        return self::businessGetOrders($query);
    }



    /**
     * Get business orders total amount
     * @param $userId
     * @return float|int
     */
    public static function businessOrdersTotalAmount( $userId )
    {
        // If business store has orders
        if (self::businessHasOrders($userId)) {
            //return amount
            return self::getBusinessOrdersTotalAmount($userId);
        }

        // if there are no orders, return 0
        return 0;

    }


    /**
     * Get delivered business orders
     * @param $userId
     * @return float|int
     */
    public static function businessOrdersDeliveredAmount( $userId )
    {
        // If business store has orders
        if (self::businessHasOrders($userId)) {
            //return amount
            return self::getBusinessOrdersDeliveredAmount($userId);
        }

        // if there are no orders, return 0
        return 0;
    }

    /**
     * Get business total amount of orders in store
     * @param $userId
     * @return float|int
     */
    private static function getBusinessOrdersTotalAmount($userId)
    {
        $total = UserBusinessOrder::where('business_id','=', Business::businessId($userId))->pluck(['amount'])->toArray();
        return array_sum($total);
    }

    /**
     * Get Business Orders
     * @param $query
     * @return mixed
     */
    public static function businessGetOrders( $query )
    {
        return UserBusinessOrder::with('buyer')->byFilter($query)->get();
    }

    /**
     * Get business amount of orders deleivered so far.
     * @param $userId
     * @return float|int
     */
    private static function getBusinessOrdersDeliveredAmount($userId)
    {
        $deliveredAmount = UserVentureOrder::where('business_id','=', Business::businessId($userId))
            ->where('status','=','delivered')->pluck(['amount'])->toArray();
        return array_sum($deliveredAmount);
    }


    /**
     * Get total partners
     * @param $userId
     * @return mixed
     */
    public static function totalPartners( $userId )
    {
        return Partnership::where('business_id','=', Business::businessId($userId))->count();
    }

    public static function businessProducts($query)
    {
        $products = [];
        UserBusinessProduct::orderBy('id','desc')
            ->byFilter($query)
            ->get()
            ->mapToGroups(function($item) use (&$products) {
                $products[] = [
                    'image' => $item->images? $item->images[0]: null,
                    'name' => $item->product_name,
                    'sku' => $item->sku,
                    'amount' => $item->product_price,
                    'status' => $item->stock_status,
                    'id' => $item->id,
                ];

                return [];
            });

        return $products;
    }


    /**
     * Strip unwanted html tags from text
     * @param $value
     * @return string
     */
    public static function stripTextTags( $value )
    {
        return strip_tags($value, "<p><b><a><img><h1><h2><h3><h4><h5><h6>");
    }


    /**
     * Attach product count to ventures model
     * @param $ventures
     * @return mixed
     */
    public static function attachProductCount( $ventures )
    {
        foreach ( $ventures as $venture ) {
            if (UserBusinessProduct::where('venture_id','=',$venture->id)->exists()) {
                $venture->product_count = UserBusinessProduct::where('venture_id','=',$venture->id)->count();
            }else{
                $venture->product_count = 0;
            }

        }


        return $ventures;
    }

}
