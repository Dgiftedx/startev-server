<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\HelperController;
use App\Models\Admin\Admin;
use App\Models\Business\UserBusinessOrder;
use App\Models\Business\UserBusinessProduct;
use App\Models\BusinessVenture;
use App\Models\Buyer;
use App\Models\Business;
use App\Models\Store\UserCart;
use App\Models\Store\UserInvoice;
use App\Models\Store\UserStore;
use App\Models\Store\UserVentureOrder;
use App\Models\Store\UserVentureProduct;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendOrderNotification;
use App\Models\BatchOrder;
use App\Models\Setting;
use App\Models\StoreSettlementBatch;
use App\Models\Transaction\VendorSettlement;
use App\Repositories\OrderTransaction;
use App\Repositories\PayStackVerifyTransaction;
use PhpParser\Node\Expr\New_;

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


    public function byFilter( Request $request, $identifier )
    {
        $data = $request->all();

        $store = StoreHelperController::getMainStoreInfo($identifier);

        $params = [
            'store_id' => $store->id,
        ];

        $products = StoreHelperController::getMainStoreProducts($params, $data['sort']);

        return response()->json($products);
    }


    public function getLocalProduct( Request $request )
    {
        $query = $request->all();
        $product = UserVentureProduct::find($query['product_id']);
        $store = UserStore::find($product->store_id);
        $store_identifier = $store->identifier;

        $result = ['product' => $product, 'store' => $store, 'store_identifier' => $store_identifier];
        return response()->json($result);
    }


    public function getCart()
    {
        $cartItems = StoreHelperController::getCartItems();
        return response()->json($cartItems);
    }


    public function addToCart( Request $request )
    {
        $data = $request->all();

        if(auth()->check()) {
            //user authenticated

            //check if store_identifier is set
            if (!isset($data['store_identifier'])) {
                $product = UserVentureProduct::find($data['product_id']);
                $store = UserStore::find($product->store_id);
                $data['store_identifier'] = $store->identifier;
            }

            //check if item already exist in cart
            if (!UserCart::where('user_id','=',$data['user_id'])
                ->where('product_id','=',$data['product_id'])
                ->where('original_product_id','=','original_product_id')
                ->where('store_identifier','=',$data['store_identifier'])->exists()) {

                //log new item
                UserCart::create($data);
                //pull all cart items for user
                $cartItems = StoreHelperController::getCartItems();

                //return response
                return response()->json(['items' => $cartItems, 'message' => "Product has been added to cart"]);
            }

            //return response
            return response()->json(['error' => "Item has been added to cart already"]);
        }

        return response()->json(['error' => "There is an error"]);
    }

    private function sessionSaveCart($item)
    {
        $cartItems = StoreHelperController::getCartItems();
        $cartItems[] = $item;
        $items = json_encode($cartItems);
        session(['cartItems' => $items]);
    }


    private function find_key_value($array, $key, $val)
    {
        foreach ($array as $item)
        {
            if (is_array($item) && $this->find_key_value($item, $key, $val)) return true;

            if (isset($item[$key]) && $item[$key] == $val) return true;
        }

        return false;
    }


    public function removeFromCart( $item_id )
    {
        UserCart::find($item_id)->delete();
        //pull all cart items for user
        $cartItems = StoreHelperController::getCartItems();

        //return response
        return response()->json($cartItems);
    }


    private function calculateStudentCommission($items)
    {

        $commission = 0;

        foreach($items as $item) {
            $mainProduct = UserBusinessProduct::find($item->original_product_id)->first();
            if($mainProduct->product_commission !== 0) {
                $commission += ($mainProduct->product_commission / 100) * $item->amount;
            }else{
                //use default commission percentage i.e 10%
                $commission += (0.3 / 100) * $item->amount;
            }
        }
        return $commission;
    }


    public function placeOrder( Request $request )
    {

        $recipients = [];

        // Get order items
        $items = json_decode($request->get('items'));

        //Get other information are regards this order
        $data = $request->all();


         $recipients['buyer'] = ['email' => $data['email'], 'name' => $data['name']];

        //set a batch id
        $data['batch_id'] = 'BA'. HelperController::generateIdentifier(14);

        //get buyer
        $data['buyer_id'] = $data['user_id'];

        $user=(New User)->where('email','=',$data['email'])->first();
        if(is_null($user)) {
            $usrData=['name'=>$data['name'],'slug'=>'all_users','email'=>$data['email'],'password'=>"password",'phone'=>$data['phone'],'address'=>$data['delivery_address']];
            $user = (new User)->create($usrData);
        }
        if(!is_null($user))
        {
            $data['buyer_id'] = $user->id;
            $data['name'] = $user->name?$user->name:null;
            $data['email'] = $user->email?$user->email:null;
            $data['location'] = $data['delivery_address'];
            $user->update(['phone' => $data['phone'], 'address' => $data['delivery_address']]);
        }
        unset($data['user_id']);


        //invoice holder
        $invoice = [];
        $invoice['items'] = [];
        $invoice['buyer_id'] = $data['buyer_id'];

        $batch = [];
        $batch['batch_id'] = $data['batch_id'];
        $batch['buyer_id'] = $data['buyer_id'];
        $batch['items_total'] = $data['items_total'];
        $batch['grant_total'] = $data['grand_total'];
        $batch['delivery_fee'] = $data['delivery_fee'];

        /**
         * Items are bought from same store,
         * so, we can get the store is using
         * the first order in the items list
         */
        $finder = $items[0]->store_identifier;
        $store = UserStore::where('identifier', '=', $finder)->first();
        if(is_null($store))
            return response()->json(['store not found',$store]);
        $batch['store_id'] = $store->id;

        //set store owner email on recipients to be sent notifications
        $storeOwner = (new User)->find($store->user_id);
        $recipients['store'] = ['email' => $storeOwner->email, 'store_name' => $store->store_name];

        $settlementBatch['store_id'] = $store->id;
        $settlementBatch['counter'] = 1;
        $settlementBatch['active'] = 1;
        //if there is no existing opening, create a new batch.
        if(!StoreSettlementBatch::where('store_id','=',$store->id)->where('active','=',1)->exists()){
            StoreSettlementBatch::create($settlementBatch);
        }


        //Calculate total student's commission and save alongside with batch details
        $batch['total_student_commission'] = $this->calculateStudentCommission($items);
        //first create batch entry
        $newBatch = (new BatchOrder)->create($batch);

        $recipients['ventures'] = [];

        foreach ($items as $index => $item) {
            $mainProduct = UserBusinessProduct::find($item->original_product_id);

            //Fetch the business who owns the product
            $business = StoreHelperController::fetchBusinessId($mainProduct->venture_id);
            $recipients['ventures'][] = $mainProduct->venture_id;

            $data['batch_id'] = $newBatch->batch_id;
            $data['identifier'] = 'OD'. HelperController::generateIdentifier(14); //unique order id
            $data['product_id'] = $item->product_id;
            $data['business_id'] = $business;
            $data['store_id'] = $store->id;
            $data['quantity'] = $item->quantity;
            $data['amount'] = $item->amount;
            $data['product_sku'] = $item->product_sku;
            $data['status'] = 'processing';
            $data['forwarded'] = 1;
            $data['commission'] = $mainProduct->product_commission;
            $data['venture_id'] = $mainProduct->venture_id;
            //insert original product id
            $data['original_product_id'] = $item->original_product_id;

            //Forward orders to business
            $businessOrder = UserBusinessOrder::create($data);

            //Forward orders to student from whose store this order is placed.
            UserVentureOrder::create($data);


            ////////////////////////////////////////////////////////////////////////////////////
            // Start Vendor Settlement Processes
            ////////////////////////////////////////////////////////////////////////////////////

            //use transaction reference to get payStack charge
            $payStackCharge = (new PayStackVerifyTransaction)->verify($data['transaction_ref']);

            //Pile request parameters for transaction calculations and settlements
            $params = [
                'delivery' => $data['delivery_fee'],
                'amountTotal' => $businessOrder->amount, //amount
                'starTev' => (new Setting)->value('STARTEV_PERCENTAGE_CHARGE'), //percentage
                'commission' => $businessOrder->commission, //percentage
                'payStack' => $payStackCharge //amount
            ];

            //Perform transaction breakdown
            $result = (new OrderTransaction($params))->calculate();
            $result['batch_id'] = $businessOrder->batch_id;
            $result['order_id'] = $businessOrder->id;
            $result['store_id'] = $businessOrder->store_id;

            //Log settlement split
            $settlement = VendorSettlement::create($result);

            //Log delivery charge
            $deliveryCharge['order_id'] = $businessOrder->id;
            $deliveryCharge['batch_id'] = $businessOrder->batch_id;
            $deliveryCharge['vendor_settlement_id'] = $settlement->id;
            $deliveryCharge['amount'] = $settlement->delivery;

            UserBusinessOrder::find($businessOrder->id)->update(['vendor_settlement_id' => $settlement->id]);

            //fetch original product
            $originalProduct = UserVentureProduct::find($item->product_id);

            //set invoice data
            $invoice['order_id'] = $businessOrder->identifier;
            $invoice['order_date'] = Carbon::now();
            $invoice['order_status'] = 'paid';

            $invoice['items'][] = [
                'product_name' => $originalProduct->product_name,
                'description' => $originalProduct->highlight,
                'quantity' => $item->quantity,
                'reference' => $data['transaction_ref'],
                'unit_cost' => $originalProduct->product_price,
                'total' => $item->amount,
            ];

            //remove item from cart
            if ($data['buyer_id'] > 0 && isset($item->id)){
                $search = UserCart::find($item->id);
                if(!is_null($search)) {
                    $search->delete();
                }
            }
        }

        //Attach total
        $invoice['sub_total'] = $data['items_total'];
        $invoice['total'] = $data['grand_total'];
        $invoice['remark'] = "Thank you for your purchase. <br/> Our Rep will be in touch with you shortly for your order delivery";

        //generate invoice
        UserInvoice::create($invoice);

        if ($data['buyer_id'] > 0) {
            $cartItems = StoreHelperController::getCartItems();
        }else{
            $cartItems = [];
        }


        /**
         * At this point, necessary recipients
         * ro receive order notifications are already collected
         * in recipients array with key referencing various entities.
         *
         * orders items is attached so we can send the buyer needed details
         * about the order.
         */
        $totals = ['items_total' => $data['grand_total'], 'grant_total' => $data['grand_total'], 'delivery_fee' => $data['delivery_fee']];
        $this->sendOrderMails($recipients,$totals, $items);

        //send venture notifications
        $this->sendVentureMail($recipients);


        // return success response with invoice
        return response()->json([
            'invoice' => $invoice,
            'cartItems' => $cartItems,
            'message' => "Your order has been successfully place. You will be notified on your order status."
        ]);
    }

    private function sendOrderMails($recipients, $totals = [], $items = [])
    {
        //First mail the buyer with items
        $mailContent = [
            'email' => $recipients['buyer']['email'],
            'name' =>  $recipients['buyer']['name'],
            'subject' => "Your order details!",
            'items' => $items,
            'total' => $totals,
            'role' => 'buyer'
        ];

        //send to student
        dispatch( new SendOrderNotification($mailContent));

        //Prepare store contents
        $mailContent['email'] = $recipients['store']['email'];
        $mailContent['name'] = $recipients['store']['store_name'];
        $mailContent['buyer'] = $recipients['buyer']['name'];
        $mailContent['subject'] = "New Order Alert :: Startev Africa";
        $mailContent['role'] = 'store';
        $mailContent['total'] = $totals;
        //send to student
        dispatch( new SendOrderNotification($mailContent));

        //What of the Store administrators
        Admin::all()
            ->mapToGroups(function ($admin)use(&$mailContent,$recipients){
                $mailContent['email']=$admin->email;
                $mailContent['name']=$admin->name;
                dispatch( new SendOrderNotification($mailContent));
                return [];
            });
        //All mail sent. Anyway don't bother about the memory consumption. Job things!

    }

    private function sendVentureMail($recipients)
    {
        //send notifications to venture businesses
        $mailContent['role'] = 'venture';
        $mailContent['store'] = $recipients['store'];
        // $mailContent['total'] = $totals;
        $mailContent['buyer'] = $recipients['buyer']['name'];
        $mailContent['subject'] = 'New Order Alert :: Startev Africa';
        $ventures = array_unique($recipients['ventures']);

        foreach($ventures as $ventureId) {
            $venture = BusinessVenture::find($ventureId);
            $business = \App\Models\Business::where('id', '=', $venture->business_id)->first();
            $user = User::find($business->user_id);
            $mailContent['email'] = $user->email;
            $mailContent['name'] = $user->name;
            $mailContent['venture'] = $venture->venture_name;

            dispatch( new SendOrderNotification($mailContent));
        }
    }
}
