<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\HelperController;
use App\Models\Business;
use App\Models\Business\UserBusinessProduct;
use App\Models\Business\UserBusinessOrder;
use App\Models\BusinessVenture;
use App\Models\Partnership;
use App\Models\Store\UserCart;
use App\Models\Store\UserStore;
use App\Models\Store\UserVentureCommission;
use App\Models\Store\UserVentureOrder;
use App\Models\Store\UserVentureProduct;
use App\Models\Store\UserVentureReview;
use App\Models\User;
use Carbon\Carbon;
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
        $orders = [];

        $products = [];
        UserVentureProduct::orderBy('id','asc')->get()->mapToGroups(function($item) use (&$products) {
            $products[$item->sku] = $item;
            return [];
        });

        $sortable = ['pending','processing'];

        UserVentureOrder::with('buyer')
            ->with('product')
            ->where('store_id', '=', UserStore::storeId($userId))
            ->whereIn('status',   $sortable)
            ->get()
            ->mapToGroups( function($item) use (&$orders, $products) {

                $orders[$item->identifier][] = [
                    'name' => $item->buyer->name,
                    'order_id' => $item->identifier,
                    'image' => self::checkIsset($products, $item->product_sku)?$products[$item->product_sku]->images[0]:'',
                    'product_name' => self::checkIsset($products, $item->product_sku)?$products[$item->product_sku]->product_name:'',
                    'amount' => $item->amount,
                    'quantity' => $item->quantity,
                    'date' => $item->created_at,
                    'status' => $item->status
                ];


                return [];
            });


        return $orders;
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
        $total = UserVentureOrder::where('store_id','=',$storeId)->pluck('amount')->toArray();
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
            ->where('status','=','delivered')->pluck('amount')->toArray();
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
            ->where('user_id','=', $userId)->pluck('commission')->toArray();
        return array_sum($total);
    }


    /**
     * query Orders Model and return result
     * @param $query
     * @return mixed
     */
    public static function getOrders($query)
    {
        $orders = [];

        $products = [];
        UserVentureProduct::orderBy('id','asc')->get()->mapToGroups(function($item) use (&$products) {
            $products[$item->sku] = $item;
            return [];
        });

        UserVentureOrder::with('buyer')
            ->with('product')
            ->byFilter($query)
            ->get()
            ->mapToGroups( function($item) use (&$orders, $products) {

                $orders[$item->identifier][] = [
                    'name' => $item->buyer->name,
                    'order_id' => $item->identifier,
                    'image' => self::checkIsset($products, $item->product_sku)?$products[$item->product_sku]->images[0]:'',
                    'product_name' => self::checkIsset($products, $item->product_sku)?$products[$item->product_sku]->product_name:'',
                    'amount' => $item->amount,
                    'quantity' => $item->quantity,
                    'date' => $item->created_at,
                    'status' => $item->status
                ];


                return [];
            });


        return $orders;
    }


    private static function checkIsset($items, $index)
    {
        return isset($items[$index])?true:false;
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
            ->where('status','=', 'accepted')
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
            'business_id' => Business::businessId($userId)
        ];

        $orders = [];
        $sortable = ['pending','processing'];

        $products = [];
        UserBusinessProduct::orderBy('id','asc')->get()->mapToGroups(function($item) use (&$products) {
            $products[$item->sku] = $item;
            return [];
        });


        UserBusinessOrder::with('buyer')
            ->byFilter($query)
            ->whereIn('status', $sortable)
            ->get()
            ->mapToGroups( function($item) use (&$orders, $products) {

                $orders[$item->identifier][] = [
                    'name' => $item->buyer->name,
                    'product_sku' => $item->product_sku,
                    'order_id' => $item->identifier,
                    'image' => self::checkIsset($products, $item->product_sku)?$products[$item->product_sku]->images[0]:'',
                    'product_name' => self::checkIsset($products, $item->product_sku)?$products[$item->product_sku]->product_name:'',
                    'amount' => $item->amount,
                    'quantity' => $item->quantity,
                    'date' => Carbon::parse($item->created_at)->toDateTimeString(),
                    'status' => $item->status
                ];

                return [];
            });

        return $orders;
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
        $total = UserBusinessOrder::where('business_id','=', Business::businessId($userId))->pluck('amount')->toArray();
        return array_sum($total);
    }

    /**
     * Get Business Orders
     * @param $query
     * @return mixed
     */
    public static function businessGetOrders( $query )
    {
        $orders = [];

        $products = [];
        UserBusinessProduct::orderBy('id','asc')->get()->mapToGroups(function($item) use (&$products) {

            $products[$item->sku] = $item;
            return [];
        });


        UserBusinessOrder::with('buyer')
            ->byFilter($query)
            ->get()
            ->mapToGroups( function($item) use (&$orders, $products) {

                $orders[$item->identifier][] = [
                    'name' => $item->buyer->name,
                    'product_sku' => $item->product_sku,
                    'order_id' => $item->identifier,
                    'image' => self::checkIsset($products, $item->product_sku)?$products[$item->product_sku]->images[0]:'',
                    'product_name' => self::checkIsset($products, $item->product_sku)?$products[$item->product_sku]->product_name:'',
                    'amount' => $item->amount,
                    'quantity' => $item->quantity,
                    'date' => Carbon::parse($item->created_at)->toDateTimeString(),
                    'status' => $item->status
                ];


                return [];
            });

        return $orders;
    }




    public static function singleBusinessProduct($product_sku)
    {
        return UserBusinessProduct::where('sku','=', $product_sku)->first();
    }

    /**
     * Get business amount of orders delivered so far.
     * @param $userId
     * @return float|int
     */
    private static function getBusinessOrdersDeliveredAmount($userId)
    {
        $deliveredAmount = UserBusinessOrder::where('business_id','=', Business::businessId($userId))
            ->where('status','=','delivered')->pluck('amount')->toArray();
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


    public static function ventureProducts($query)
    {
        $products = [];
        UserVentureProduct::orderBy('id','desc')
            ->where('store_id', '=', $query['store_id'])
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
     * @param $userId
     * @return mixed
     */
    public static function attachProductCount( $ventures , $userId)
    {
        foreach ( $ventures as $venture ) {
            if (UserBusinessProduct::where('venture_id','=',$venture->id)->exists()) {
                $venture->product_count = UserBusinessProduct::where('venture_id','=',$venture->id)
                    ->where('business_id', '=', Business::businessId($userId))->count();
            }else{
                $venture->product_count = 0;
            }

        }
        return $ventures;
    }

    /**
     * @param $venture
     * @param $userID
     * @return mixed
     */
    public static function attachSingleProductCount($venture, $userID)
    {
        return $venture->product_count = UserVentureProduct::where('venture_id','=',$venture->id)
            ->where('store_id', '=', UserStore::storeId($userID))->count();
    }

    /**
     * @param $query
     * @return mixed
     */
    public static function getBusinessProducts($query)
    {
        return UserBusinessProduct::orderBy('id','desc')
            ->where('venture_id', '=', $query['venture_id'])
            ->where('business_id', '=', $query['business_id'])->get();
    }



    public static function fetchBusinessId($venture_id)
    {
        $business = BusinessVenture::find($venture_id);
        return $business->business_id;
    }



    ///////////////////////////////////////////////////////////////////////////////////////////
    /// Main Store Helpers


    public static function getMainStoreInfo( $identifier )
    {
        return UserStore::where('identifier','=',$identifier)->first(['store_name','store_logo','user_id','id']);
    }


    public static function getMainStoreProducts($query, $sort = 'desc')
    {
        return UserVentureProduct::orderBy('id',"{$sort}")->byFilter($query)->get();
    }


    public static function getCartItems()
    {
        $user_id = auth()->user()->id;
        return UserCart::with('product')->with('store')->where('user_id','=',$user_id)->get();
    }
}
