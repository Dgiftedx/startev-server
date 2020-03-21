<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\ApiVentureController;
use App\Http\Controllers\HelperController;
use App\Jobs\SendConfirmNotification;
use App\Jobs\SendOrderNotification;
use App\Models\Admin\Admin;
use App\Models\Business;
use App\Models\Business\UserBusinessOrder;
use App\Models\Business\UserBusinessSetting;
use App\Models\BusinessVenture;
use App\Models\Store\UserInvoice;
use App\Models\Store\UserVentureOrder;
use App\Models\User;
use App\Models\Business\UserBusinessProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BatchOrder;
use App\Models\Store\UserVentureProduct;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

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
     *  Get dashboard Data
     * @param $ventureId
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboard( $ventureId, $userId )
    {

        $filter = [
            'business_id' => Business::businessId($userId),
            'venture_id' => $ventureId
        ];

        // Gather data
        $data = [
            'orders_amount' => StoreHelperController::businessOrdersTotalAmount($userId, $ventureId),
            'orders_amount_avg' => UserBusinessOrder::byFilter($filter)->avg('amount'),
            'delivered_orders' => StoreHelperController::businessOrdersDeliveredAmount($userId, $ventureId),
            'delivered_orders_avg' => UserBusinessOrder::byFilter($filter)->where('status','=','delivered')->avg('amount'),
            'total_partners' => StoreHelperController::totalPartners($filter),
            'recent_orders' => StoreHelperController::recentBusinessOrders($userId, $ventureId)
        ];

        return response()->json($data);
    }
    public function venturedata( $ventureId, $userId )
    {

        // Gather data
        $data=BusinessVenture::find($ventureId);

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
     *
     * @param $ventureId
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeOrders( $ventureId, $userId )
    {
        $query = ['business_id' => Business::businessId($userId), 'venture_id' => $ventureId];
        $orders = StoreHelperController::businessGetOrders($query);
        return response()->json($orders);
    }


    public function singleOrder( $orderId )
    {
        $orders = UserBusinessOrder::
            with(['mainProduct','venture','store','buyer'])
            ->where('identifier','=', $orderId)->get();

        $all = [
            'name' => $orders[0]->buyer->name,
            'email' => $orders[0]->buyer->email,
            'phone' => $orders[0]->buyer->phone,
            'delivery_address' => $orders[0]->delivery_address,
            'order_date' => Carbon::parse($orders[0]->created_at)->toDateTimeString(),
            'orders' => $orders,
            'transaction_ref' => $orders[0]->transaction_ref
        ];

        return response()->json($all);
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


        $message = "Order not found. It might be that this order was made via partnered store.";

        return response()->json(['success' => false, 'message' => $message]);
    }


    /**
     * Get store products
     *
     * @param $ventureId
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeProducts( $ventureId, $userId )
    {
        $query = [
            'business_id' => Business::businessId($userId),
            'venture_id' => $ventureId
        ];

        $products = StoreHelperController::businessProducts($query);

        return response()->json($products);
    }


    /**
     * Store new product to store
     *
     * @param Request $request
     * @param $ventureId
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function addProduct( Request $request,$ventureId, $userId )
    {
        $data = $request->all();

        $images = [];

        $data['business_id'] = Business::businessId($userId);
        $data['venture_id'] = $ventureId;
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

        $toEffectVentures = UserVentureProduct::where('product_id','=',$product->id)->get();

        $changesImages = [];
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

        unset($data['product_commission']);
        foreach($toEffectVentures as $product) {
            UserVentureProduct::find($product->id)->update($data);
        }

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
        $result = StoreHelperController::attachProductCount($ventures, $userId);

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


    public function orderAction( $id )
    {
        $order = UserBusinessOrder::find($id);
        $order->update(['status' => 'confirmed']);

        $ventureOrder = UserVentureOrder::with(['store.user','buyer','venture'])->where('identifier','=',$order->identifier)->first();
        $ventureOrder->update(['status' => 'confirmed']);

        $batch = BatchOrder::where('batch_id','=', $order->batch_id)->first();
        $batch->update(['status' => 'confirmed']);


        $this->sendConfirmationMails($ventureOrder,$order->batch_id,$ventureOrder->identifier);
        $this->handleOfflineConfirmationNotification($ventureOrder,$order->batch_id,$ventureOrder->identifier);
        /**
         * Wherever we need to send the payload to for delivery,
         * it's done below and success response is returned to the server
         */

        //update product invoices
        // $invoices = UserInvoice::where('order_id','=',$data['order_id'])->get();
        // foreach ($invoices as $invoice) {
        //     UserInvoice::find($invoice->id)->update(['order_status' => $data['action']]);
        // }

        //update batch status
        return response()->json(['success' => true ]);
    }

    private function sendConfirmationMails($recipients, $batch_id, $order_id)
    {
        //First mail the buyer with items
        $mailContent = [
            'email' => $recipients->buyer->email,
            'name' =>  $recipients->buyer->name,
            'subject' => "Your order is Ready for Pickup!",
            'message'=>"Hi <strong>{$recipients->buyer->name}</strong>, <br> Your Order #<strong>{$order_id}</strong> has been confirmed for Pickup!",
            'role' => 'buyer',
            'base_url' => env('APP_BASE_URL','https://app.startev.africa'),
        ];



        //send to student
        dispatch( new SendConfirmNotification($mailContent));
        //Prepare store contents

        $msg="Hi {$recipients->store->user->name}. <br> The order  #<strong>{$order_id}</strong> from your store has been confirmed for Pickup!";
        $msg.="<h3>Order ID: {$order_id}</h3>";
        $msg.="<h3>Batch ID: {$batch_id}</h3>";
        $msg.="<h3>Store: {$recipients->store->store_name}</h3>";
        $msg.="<h3>Venture: {$recipients->venture->venture_name}</h3>";
        $msg.="<h3>Buyer: {$recipients->buyer->name}</h3>";
        $mailContent['email'] = $recipients->store->user->email;
        $mailContent['name'] = $recipients->store->store_name;
        $mailContent['buyer'] = $recipients->buyer->name;
        $mailContent['subject'] = "Order Pickup Confirmation Alert :: Startev Africa";
        $mailContent['role'] = 'store';
        $mailContent['message'] = $msg;
        $mailContent['base_url'] = env('APP_BASE_URL','https://app.startev.africa').'/venture-dashboard';
        //send to student
        dispatch( new SendConfirmNotification($mailContent));
        unset($mailContent['base_url']);

        //What of the Store administrators
        Admin::all()
            ->mapToGroups(function ($admin)use(&$mailContent,$recipients,$order_id,$batch_id){
                  $msg="Hi {$admin->name}. <br> An order #<strong>{$order_id}</strong> from  <strong>{$recipients->store->store_name}</strong> has been confirmed for Pickup by Venture! Kindly login and process Delivery";
                $msg.="<h3>Order ID: {$order_id}</h3>";
                $msg.="<h3>Batch ID: {$batch_id}</h3>";
                $msg.="<h3>Venture: {$recipients->venture->venture_name}</h3>";
                $msg.="<h3>Buyer: {$recipients->buyer->name}</h3>";
                $msg.="<h3>Store: {$recipients->store->store_name}</h3>";
                $mailContent['email']=$admin->email;
                $mailContent['name']=$admin->name;
                $mailContent['message']=$msg;
                $mailContent['server_url']= env('APP_SERVER_URL','https://startev.africa');

                dispatch( new SendConfirmNotification($mailContent));
                return [];
            });

        $startevMail = Setting::where('key','STARTEV_EMAIL')->first();
        $startevMailName = Setting::where('key', 'STARTEV_EMAIL_NAME')->first();
        $msg="Hi {$startevMailName->value}. <br> An order #<strong>{$order_id}</strong> from  <strong>{$recipients->store->store_name}</strong> has been confirmed for Pickup by Venture! Kindly login and process Delivery";
        $msg.="<h3>Order ID: {$order_id}</h3>";
        $msg.="<h3>Batch ID: {$batch_id}</h3>";
        $msg.="<h3>Venture: {$recipients->venture->venture_name}</h3>";
        $msg.="<h3>Buyer: {$recipients->buyer->name}</h3>";
        $msg.="<h3>Store: {$recipients->store->store_name}</h3>";

        $mailContent['email'] = $startevMail->value;
        $mailContent['name'] = $startevMailName->value;
        $mailContent['message']=$msg;
        $mailContent['server_url']= env('APP_SERVER_URL','https://startev.africa');

        dispatch( new SendConfirmNotification($mailContent));
        //All mail sent. Don't bother about the memory consumption. Job things!

    }


    //send push notification to target user if offline
    private  function handleOfflineConfirmationNotification($recipients, $batch_id, $order_id)
    {

        $pushData['content'] = [
            'data' => ['type'=>PushNotification::$OrderConfirmation],
            'title'=>'Order Pickup Confirmation Alert :: Startev Africa',
            'body'=>"The order  #<strong>{$order_id}</strong> from your store has been confirmed for Pickup!"
        ];

        $pushData['users'][] = $recipients->store->user->id;

        (new Notification)->sendPush($pushData);

    }


}
