<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\HelperController;
use App\Models\Business\UserBusinessOrder;
use App\Models\Partnership;
use App\Models\Store\UserStore;
use App\Models\Store\UserVentureOrder;
use App\Models\Store\UserVentureProduct;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

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
     * Get user store
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeSettings( $userId )
    {
        $settings = StoreHelperController::userStore($userId);
        return response()->json($settings);
    }


    public function saveStoreSettings( Request $request, $user_id )
    {
        $data = $request->all();

        if (isset($data['auto_forward'])) {

            if ($data['auto_forward'] === 'true') {
                $data['auto_forward'] = 1;
            }else{
                $data['auto_forward'] = 0;
            }
        }

        $userStore = UserStore::find(UserStore::storeId($user_id));

        if ($data['store_logo'] !== 'null' && $request->file('store_logo')->isValid()) {
            //upload store logo
//            $path = $this->url. '/storage'. HelperController::processImageUpload($request->file('store_logo'),$data['store_name'],'store',150, 150);
            //remove old image
            HelperController::removeImage($userStore->store_logo);
            $path = '/storage'. HelperController::processImageUpload($request->file('store_logo'),$data['store_name'],'store',150, 150);
            $data['store_logo'] = $path;
        }else{
            unset($data['store_logo']);
        }

        $userStore->update($data);

        $update = StoreHelperController::userStore($user_id);
        return response()->json($update);
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
            'orders_amount_avg' => UserVentureOrder::where('store_id','=', UserStore::storeId($userId))->avg('amount'),
            'delivered_orders' => StoreHelperController::OrdersDeliveredAmount($userId),
            'delivered_orders_avg' => UserVentureOrder::where('store_id','=', UserStore::storeId($userId))->where('status','=','delivered')->avg('amount'),
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

    public function singleOrder( $orderId )
    {
        $orders = UserVentureOrder::with('buyer')
            ->with('product')
            ->where('identifier','=', $orderId)->get();

        $all = [
            'name' => $orders[0]->buyer->name,
            'delivery_address' => $orders[0]->delivery_address,
            'order_date' => Carbon::parse($orders[0]->created_at)->toDateTimeString(),
            'forwarded' => $orders[0]->forwarded,
            'orders' => $orders,
            'transaction_ref' => $orders[0]->transaction_ref
        ];

        return response()->json($all);
    }




    /**
     * Get user venture list/partnerships
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function ventureList( $userId )
    {
        // Grab ventures
        $partnerships = StoreHelperController::getVentureList($userId);


        foreach ($partnerships as $partnership) {
            // attach product counts
            $partnership->venture = StoreHelperController::attachSingleProductCount($partnership->venture);
        }

        return response()->json($partnerships);
    }


    /**
     * Get user store reviews
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function reviews( $userId )
    {
        $reviews = StoreHelperController::gatherReviews( $userId );
        return response()->json($reviews);
    }


    /**
     * Import Venture Products
     * @param $userId
     * @param $ventureId
     * @return \Illuminate\Http\JsonResponse
     */
    public function importVentureProducts( $userId, $ventureId )
    {
        // get business id;
        $partnership = Partnership::where('user_id','=',$userId)->where('venture_id','=',$ventureId)->first();

        $query = [
            'business_id' => $partnership->business_id,
            'venture_id' => $ventureId
        ];

        $products = StoreHelperController::getBusinessProducts($query);

        foreach ($products as $product){
            //Attach user store ID
            $product->store_id = UserStore::storeId($userId);

            //check if this product already exists
            if(UserVentureProduct::where('store_id','=', $product->store_id)->where('sku','=',$product->sku)->exists()){
                //only update
                //but i don't think this is necessary as this might revert any changes made
                // to user products before this import.
            }else{

                //create product copy into user store
                UserVentureProduct::create([
                    'store_id' => $product->store_id,
                    'venture_id' => $ventureId,
                    'sku' => $product->sku,
                    'slug' => uniqid(rand(), true),
                    'images' => $product->images,
                    'product_name' => $product->product_name,
                    'product_price' => $product->product_price,
                    'highlight' => $product->highlight,
                    'sizes' => $product->sizes,
                    'colors' => $product->colors,
                    'product_description' => $product->product_description,
                    'stock_status' => $product->stock_status,
                    'discount_price' => $product->discount_price
                ]);
            }
        }


        //then we need to set the imported_flag to avoid future import

        if (UserVentureProduct::where('store_id', '=', UserStore::storeId($userId))->where('venture_id', '=', $ventureId)->count() > 0) {
            Partnership::find($partnership->id)->update(['is_products_imported' => true ]);
            return response()->json(['success' => true, 'message' => "Product importation completed"]);
        }

        return response()->json(['success' => true, 'message' => "Action Done, But venture has no product to import."]);
    }


    /**
     * Synchronize Products with Source Venture
     * @param $userId
     * @param $ventureId
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncVentureProducts( $userId, $ventureId )
    {
        // get business id;
        $partnership = Partnership::where('user_id','=',$userId)->where('venture_id','=', $ventureId)->first();

        $query = [
            'business_id' => $partnership->business_id,
            'venture_id' => $partnership->venture_id
        ];

        $products = StoreHelperController::getBusinessProducts($query);

        foreach ($products as $product){
            //Attach user store ID
            $product->store_id = UserStore::storeId($userId);

            //check if this product already exists
            if(UserVentureProduct::where('store_id','=',UserStore::storeId($userId))->where('sku','=',$product->sku)->exists()){
                //only update
                //but i don't think this is necessary as this might revert any changes made
                // to user products before this import.
                // update only stock status of already imported products
                $imported = UserVentureProduct::where('store_id','=',UserStore::storeId($userId))->where('sku','=',$product->sku)->first();

                UserVentureProduct::find($imported->id)->update(['images' => null]);
                UserVentureProduct::find($imported->id)->update([
                    'stock_status' => $product->stock_status,
                    'images' => $product->images
                ]);
            }else{

                //create product copy into user store
                UserVentureProduct::create([
                    'store_id' => $product->store_id,
                    'venture_id' => $ventureId,
                    'sku' => $product->sku,
                    'slug' => uniqid(rand(), true),
                    'images' => $product->images,
                    'product_name' => $product->product_name,
                    'product_price' => $product->product_price,
                    'highlight' => $product->highlight,
                    'sizes' => $product->sizes,
                    'colors' => $product->colors,
                    'product_description' => $product->product_description,
                    'stock_status' => $product->stock_status,
                    'discount_price' => $product->discount_price
                ]);
            }
        }



        return response()->json(['success' => true, 'message' => "Product synchronization completed"]);
    }


    /**
     * Remove venture products from user store
     * @param $userId
     * @param $ventureId
     * @return \Illuminate\Http\JsonResponse
     */
    public function detachVentureProducts( $userId, $ventureId )
    {

        // get business id;
        $partnership = Partnership::where('user_id','=',$userId)->where('venture_id', '=', $ventureId)->first();

        $products = UserVentureProduct::where('store_id','=',UserStore::storeId($userId))->where('venture_id','=',$ventureId)->get();

        foreach ($products as $product) {
            $product->delete();
        }

        //then we need to disable the imported_flag to allow future import
        Partnership::find($partnership->id)->update(['is_products_imported' => false ]);

        return response()->json(['success' => true, 'message' => "Products detachment completed"]);
    }


    /**
     * Track order form business store
     * @param $orderId
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function trackOrder( $orderId , $userId )
    {

        if(UserVentureOrder::where('store_id','=',UserStore::storeId($userId))->where('identifier','=',$orderId)->exists()){
            $order = UserVentureOrder::with('buyer')->with('store')->with('product')->where('store_id','=',UserStore::storeId($userId))->where('identifier','=',$orderId)->get();
            $message = "Order Found, See Details Below";
            return response()->json(['success' => true, 'message' => $message, 'order' => $order]);
        }


        $message = "Order not found. Please check order ID to be sure it's valid.";

        return response()->json(['success' => false, 'message' => $message]);
    }


    public function forwardOrder( Request $request )
    {
        $data = $request->all();

        $products = UserVentureOrder::with('product')->where('store_id','=',$data['store_id'])->where('identifier','=',$data['identifier'])
            ->get();

        foreach ($products as $product) {
            $business = StoreHelperController::fetchBusinessId($product->product->venture_id);

            UserBusinessOrder::create([
                'store_id' => $product->store_id,
                'buyer_id' => $product->buyer_id,
                'amount' => $product->amount,
                'created_at' => $product->created_at,
                'delivery_address' => $product->delivery_address,
                'identifier' => $product->identifier,
                'product_sku' => $product->product_sku,
                'quantity' => $product->quantity,
                'business_id' => $business,
                'status' => 'processing',
                'transaction_ref' => $product->transaction_ref,
                'updated_at' => $product->updated_at
            ]);

            UserVentureOrder::find($product->id)->update(['forwarded' => true , 'status' => 'processing']);
        }

        return response()->json('success');
    }




    /**
     * Get store products
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeProducts( $userId )
    {
        $query = [
            'store_id' => UserStore::storeId($userId)
        ];

        $products = StoreHelperController::ventureProducts($query);

        return response()->json($products);
    }


    /**
     * Fetch single product
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function singleProduct( $id )
    {
        $result = UserVentureProduct::find($id);
        return response()->json($result);
    }


    /**
     * Edit product
     * @param Request $request
     * @param $productId
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function editProduct( Request $request, $productId, $userId )
    {

        //Grab all input
        $data = $request->all();

        // fetch single product
        $product = UserVentureProduct::find($productId);

        //check if user uploaded replacement images
        if (isset($data['images'])) {


            if (count($data['images']) > 0) {

                $product->update(['images' => null]);

                $images = $product->images;

                //Remove the old images
                if (!is_null($images)) {
                    foreach ($images as $image) {
                        $image = str_replace($this->url.'/storage', "", $image);

                        //Remove old image from storage if exists
                        if (Storage::disk('public')->exists($image)) {
                            //remove
                            Storage::disk('public')->delete($image);
                        }
                    }
                }

                //process image upload
                foreach ($data['images'] as $file) {
//                    $images[] = $this->url . '/storage/'. HelperController::processProductsImage($file,$data['product_name'],'storeManager');
                    $images[] = '/storage/'. HelperController::processProductsImage($file,$data['product_name'],'storeManager');

                }

                $data['images'] = $images;
            }

        }


        $data['highlight'] = strip_tags($data['highlight'], '<a><b><p><table><div><br><aside><h1><h2><h3><h4><h5><h6>');

        //Update record
        $product->update($data);

        return response()->json('success');
    }


    /**
     * Delete product
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProduct( $id )
    {
        $product = UserVentureProduct::find($id);
        //TODO: implement softDelete to prevent fatal error should in case order has been placed on product

        //remove images
        $images = $product->images;

        foreach ($images as $image) {
            $image = str_replace("/storage", "", $image);
            //Remove old image from storage if exists
            if(Storage::disk('public')->exists($image)){
                //remove
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return response()->json('success');

    }

}
