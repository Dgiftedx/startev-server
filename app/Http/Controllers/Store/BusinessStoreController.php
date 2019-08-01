<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\ApiVentureController;
use App\Http\Controllers\HelperController;
use App\Models\Business;
use App\Models\Business\UserBusinessOrder;
use App\Models\Business\UserBusinessSetting;
use App\Models\BusinessVenture;
use App\Models\Store\UserInvoice;
use App\Models\Store\UserVentureOrder;
use App\Models\User;
use App\Models\Business\UserBusinessProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BusinessStoreController extends Controller
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


    /**
     * Get dashboard Data
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboard( $userId )
    {
        // Gather data
        $data = [
            'orders_amount' => StoreHelperController::businessOrdersTotalAmount($userId),
            'orders_amount_avg' => UserBusinessOrder::where('business_id','=', Business::businessId($userId))->avg('amount'),
            'delivered_orders' => StoreHelperController::businessOrdersDeliveredAmount($userId),
            'delivered_orders_avg' => UserBusinessOrder::where('business_id','=', Business::businessId($userId))->where('status','=','delivered')->avg('amount'),
            'total_partners' => StoreHelperController::totalPartners($userId),
            'recent_orders' => StoreHelperController::recentBusinessOrders($userId)
        ];

        return response()->json($data);
    }


    /**
     * Add new Venture with threshold check
     * @param Request $request
     * @param $businessId
     * @return \Illuminate\Http\JsonResponse
     */
    public function addVenture( Request $request, $businessId )
    {
        $businessVenture = (new ApiVentureController($this->user))->businessVentures($businessId);

        if (count($businessVenture->ventures) > 4) {
            return response()->json(['success' => false, 'message' => "You've reached your threshold for venture creation. Contact our support team for extension"]);
        }

        $business = Business::find($businessId);
        $data = $request->all();
        $data['business_id'] = $businessId;
        $data['identifier'] = strtoupper(substr($business->name, 0 ,3)) . HelperController::generateIdentifier(5);

        BusinessVenture::create($data);

        return response()->json(['success' => true, 'message' => "Venture Created successfully"]);
    }


    /**
     * Update venture
     * @param Request $request
     * @param $venture
     * @param $businessId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateVenture( Request $request, $venture, $businessId )
    {
        $data = $request->all();
        BusinessVenture::find($venture)->update($data);
        return response()->json(['success' => true, 'message' => "Venture updated successfully"]);

    }


    /**
     * Get business settings
     * @param $businessId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSettings( $businessId )
    {
        $settings = UserBusinessSetting::where('business_id','=', $businessId)->first();
        return response()->json($settings);
    }


    /**
     * Update Business Store Settings
     * @param Request $request
     * @param $businessId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSettings( Request $request, $businessId )
    {
        $data = $request->all();

        $data['working_days'] = json_decode($data['working_days']);


        // remove null fields
        foreach ($data as $key => $value ) {
            if (is_null($data[$key]) || $data[$key] === 'null') {
                unset($data[$key]);
            }
        }

        $data['business_id'] = $businessId;

        // update if already created
        if (UserBusinessSetting::where('business_id','=', $businessId)->exists()){
            $settings = UserBusinessSetting::where('business_id','=', $businessId)->first();

            UserBusinessSetting::find($settings->id)->update(['working_days' => null ]);

            UserBusinessSetting::find($settings->id)->update($data);

            return response()->json('success');
        }


        // create if not exists
        UserBusinessSetting::create($data);

        return response()->json('success');
    }


    /**
     * Get Store Orders
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeOrders( $userId )
    {
        $query = ['business_id' => Business::businessId($userId)];
        $orders = StoreHelperController::businessGetOrders($query);
        return response()->json($orders);
    }


    /**
     * Track order form business store
     * @param $orderId
     * @param $businessId
     * @return \Illuminate\Http\JsonResponse
     */
    public function trackOrder( $orderId , $businessId )
    {

        if(UserBusinessOrder::where('business_id','=',$businessId)->where('identifier','=',$orderId)->exists()){
            $orders = UserBusinessOrder::with('buyer')->with('store')->where('business_id','=',$businessId)->where('identifier','=',$orderId)->get();

            foreach ($orders as $order) {
                $order->product = StoreHelperController::singleBusinessProduct($order->product_sku);
            }

            $message = "Order Found, See Details Below";
            return response()->json(['success' => true, 'message' => $message, 'order' => $orders]);
        }


        $message = "Order not found. It might be that this order was made via partnered store. 
        In such case if they haven't forward the order, it won't be available for you to track.";

        return response()->json(['success' => false, 'message' => $message]);
    }


    /**
     * Get store products
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeProducts( $userId )
    {
        $query = [
            'business_id' => Business::businessId($userId)
        ];

        $products = StoreHelperController::businessProducts($query);

        return response()->json($products);
    }


    /**
     * Store new product to store
     * @param Request $request
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function addProduct( Request $request, $userId )
    {
        $data = $request->all();

        $images = [];

        $data['business_id'] = Business::businessId($userId);
        $data['slug'] = uniqid(rand(), true);

//        $data['sizes'] = json_decode($data['sizes']);
//        $data['colors'] = json_decode($data['colors']);

        if (count($data['images']) > 0) {
            //process image upload
            foreach ($data['images'] as $file) {
//                $images[] = $this->url . '/storage/'. HelperController::processProductsImage($file,$data['product_name'],'storeManager');
                $images[] =  '/storage/'. HelperController::processProductsImage($file,$data['product_name'],'storeManager');

            }

            $data['images'] = $images;
        }
        UserBusinessProduct::create($data);

        return response()->json('success');
    }


    /**
     * Fetch single product
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function singleProduct( $id )
    {
        $result = UserBusinessProduct::find($id);
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

//        $data['sizes'] = json_decode($data['sizes']);
//        $data['colors'] = json_decode($data['colors']);

        // fetch single product
        $product = UserBusinessProduct::find($productId);

        //check if user uploaded replacement images
        if (isset($data['images'])) {


            if (count($data['images']) > 0) {

                $product->update(['images' => null]);

                $images = $product->images;

                //Remove the old images
                if (!is_null($images)) {
                    foreach ($images as $image) {
                        $image = str_replace('/storage', "", $image);

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


        //Update record
        $product->update($data);

        return response()->json('success');
    }


    /**
     * Get ventures and their product count
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVentures( $userId )
    {
        $business = (new ApiVentureController($this->user))->businessVentures(Business::businessId($userId));
        $ventures = $business->ventures;
        //attach product counts
        $result = StoreHelperController::attachProductCount($ventures);

        return response()->json($result);
    }


    /**
     * Delete product
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProduct( $id )
    {
        $product = UserBusinessProduct::find($id);
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

    /**
     * Attach product to venture
     * @param Request $request
     * @param $ventureId
     * @return \Illuminate\Http\JsonResponse
     */
    public function attachProductVenture( Request $request, $ventureId )
    {
        $products = json_decode($request->get('idArray'));

        foreach ($products as $product) {
            $search = UserBusinessProduct::find($product);

            if (is_null($search->venture_id)) {
                $search->update(['venture_id' => $ventureId]);
            }
        }


        return response()->json('success');
    }


    /**
     * Detach product from venture
     * @param $venture
     * @return \Illuminate\Http\JsonResponse
     */
    public function detachProductVenture( $venture )
    {
        if (auth()->check()) {

            $products = UserBusinessProduct::where('venture_id','=',$venture)->get();

            foreach ($products as $product) {
                UserBusinessProduct::find($product->id)->update(['venture_id' => null]);
            }

            return response()->json(['success' => true, 'message' => "Products attached have been successfully detached"]);
        }

        return response()->json(['success' => false, 'message' => "You're not authorized to make the request"]);
    }


    public function orderAction( Request $request )
    {
        $data = $request->all();

        $orders = UserBusinessOrder::where('identifier','=', $data['order_id'])->get();

        foreach ($orders as $order){
            //perform action both on business order and originating store
            UserBusinessOrder::find($order->id)->update(['status' => $data['action']]);

            $origin = UserVentureOrder::where('store_id', '=', $order->store_id)
                ->where('product_sku','=',$order->product_sku)
                ->where('identifier', '=', $data['order_id'])->first();

            if ($origin) {
                UserVentureOrder::find($origin->id)->update(['status' => $data['action']]);
            }

            //run specific action for order if being shipped or delivered

        }


        //update product invoices
        $invoices = UserInvoice::where('order_id','=',$data['order_id'])->get();
        foreach ($invoices as $invoice) {
            UserInvoice::find($invoice->id)->update(['order_status' => $data['action']]);
        }


        return response()->json('success');
    }

}
