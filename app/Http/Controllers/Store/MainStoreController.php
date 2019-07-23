<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\HelperController;
use App\Models\Business\UserBusinessOrder;
use App\Models\Buyer;
use App\Models\Store\UserCart;
use App\Models\Store\UserInvoice;
use App\Models\Store\UserStore;
use App\Models\Store\UserVentureOrder;
use App\Models\Store\UserVentureProduct;
use App\Models\User;
use Carbon\Carbon;
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


    public function getCart()
    {
        if (auth()->check()) {
            $cartItems = StoreHelperController::getCartItems();
            return response()->json($cartItems);
        }

        return response()->json([], 401);
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
               ->where('store_identifier','=',$data['store_identifier'])->exists()) {

               //log new item
               UserCart::create($data);
               //pull all cart items for user
               $cartItems = StoreHelperController::getCartItems();

               //return response
               return response()->json($cartItems);
           }

           //return response
           return response()->json(['message' => "Item added to cart already"]);
       }

       //user not authenticated, throw 401 error
       return response()->json([],401);
    }


    public function removeFromCart( $item_id )
    {
        UserCart::find($item_id)->delete();
        //pull all cart items for user
        $cartItems = StoreHelperController::getCartItems();

        //return response
        return response()->json($cartItems);
    }


    public function placeOrder( Request $request )
    {
        $items = json_decode($request->get('items'));

        $data = $request->only(['transaction_ref','delivery_address','user_id']);
        $data['identifier'] = 'OD'. HelperController::generateIdentifier(14);
        $data['buyer_id'] = $data['user_id'];
        unset($data['user_id']);


        //invoice holder
        $total = 0;
        $subTotal = 0;
        $invoice = [];
        $invoice['items'] = [];
        $invoice['buyer_id'] = $data['buyer_id'];


        foreach ($items as $item) {

            $store = UserVentureProduct::find($item->product_id);
            $business = StoreHelperController::fetchBusinessId($store->venture_id);

            $data['product_id'] = $item->product_id;
            $data['business_id'] = $business;
            $data['store_id'] = $store->store_id;
            $data['quantity'] = $item->quantity;
            $data['amount'] = $item->amount;
            $data['product_sku'] = $item->product_sku;
            $data['status'] = 'processing';
            $data['forwarded'] = 1;

            //Forward orders to business
            $saved = UserBusinessOrder::create($data);

            //Log order to user venture Hub
            UserVentureOrder::create($data);

            //fetch original product
            $originalProduct = UserVentureProduct::find($item->product_id);

            //set invoice data
            $invoice['order_id'] = $saved->identifier;
            $invoice['order_date'] = Carbon::now();
            $invoice['order_status'] = 'paid';

            $subTotal += $item->amount;
            $total += $item->amount;

            $invoice['items'][] = [
                'product_name' => $originalProduct->product_name,
                'description' => $originalProduct->highlight,
                'quantity' => $item->quantity,
                'unit_cost' => $originalProduct->product_price,
                'total' => $item->amount,
            ];

            //remove item from cart
            UserCart::find($item->id)->delete();
        }

        //Attach total
        $invoice['sub_total'] = $subTotal;
        $invoice['total'] = $total;
        $invoice['remark'] = "Thank you for your purchase. <br/> Our Rep will be in touch with you shortly for your order delivery";

        //generate invoice
        UserInvoice::create($invoice);
        $cartItems = StoreHelperController::getCartItems();

        return response()->json([
            'invoice' => $invoice,
            'cartItems' => $cartItems,
            'message' => "Your order has been successfully place. Our Rep will be in touch for your delivery"
        ]);
    }
}
