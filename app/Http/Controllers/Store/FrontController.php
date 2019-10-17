<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\HelperController;
use App\Jobs\SendEmailNotification;
use App\Models\Business\UserBusinessOrder;
use App\Models\Business\UserBusinessProduct;
use App\Models\Buyer;
use App\Models\Partnership;
use App\Models\Store\UserStore;
use App\Models\Store\UserVentureCommission;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FrontController extends Controller
{

    protected $productModel;

    public function __construct( UserBusinessProduct $products )
    {
        $this->productModel = $products;
    }

    public function all()
    {
        $products = $this->productModel->orderBy('created_at','desc')->paginate(10);
        return response()->json(['success' => true, 'products' => $products]);
    }

    public function newProducts()
    {
        $query = [
            'stock_status' => 'inStock'
        ];
        $products = $this->productModel->orderBy('created_at','desc')->byFilter($query)->take(8)->get();

        foreach($products as $product) {
            $product->image = "/store/" . $product->image;
        }
        return response()->json(['success' => true, 'products' => $products]);
    }

    public function newMainStorePlaceOrder( Request $request )
    {

        $userData = json_decode($request->get('userData'));
        $orders = json_decode($request->get('cart_items'));

        $referralCode = $request->get('refferal_code');

        if (isset($userData->notes)) {
            $notes = $userData->notes;
        }else{
            $notes = "N/A";
        }

        $transaction_ref = $request->get('transaction_ref');

        $update = [];


        $update['transaction_ref'] = $transaction_ref;
        $update['identifier'] = 'OD'. HelperController::generateIdentifier(14);

        if (Buyer::where('email_address','=',$userData->email_address)->where('phone','=', $userData->phone)->exists()) {
            $buyer = Buyer::where('email_address','=',$userData->email_address)->where('phone','=', $userData->phone)->first();
            $update['buyer_id'] = $buyer->id;
            $update['delivery_address'] = $buyer->address;
        }else{

            $userArray = (array) $userData;
            $buyer = Buyer::create($userArray);
            $update['buyer_id'] = $buyer->id;
            $update['delivery_address'] = $buyer->address;
        }

        $update['notes'] = $notes;

        foreach ($orders as $order) {
            $update['business_id'] = $order->business_id;
            $update['store_id'] = 0;
            $update['product_sku'] = $order->sku;
            $update['quantity'] = $order->temp_quantity;
            $update['amount'] = $order->temp_price;

//            UserBusinessOrder::create($update);
        }

        //calculate commission for referral;
        $this->calculateCommission($referralCode, $orders);

        //send email notification
        $this->sendUserOrderAlert($orders, $buyer);


        return response()->json(['success' => true]);
    }

    public function generateInvoiceData($items)
    {
        $deliveryFee = 1000;
        $products = [];

        $total = $deliveryFee;

        foreach ($items as $item) {
            $products[] = [
                'product_name' => $item->product_name,
                'quantity' => $item->temp_quantity,
                'amount' => $item->temp_price,
            ];

            $total += ($item->temp_quantity * $item->temp_price);
        }

        return [
            'products' => $products,
            'delivery_fee' => $deliveryFee,
            'total' => $total
        ];
    }

    public function sendUserOrderAlert($items, $user)
    {
        $invoiceData = $this->generateInvoiceData($items);
        $invoiceData['user'] = $user;
        //send invoice mail
    }


    public function sendBusinessOrderAlert($items)
    {

    }


    public function calculateCommission($ref_code, $items)
    {
        $commissions = 0;

        foreach ($items as $item) {
            if ($item->product_commission !== 0) {
                $commissions += $item->product_commission;
            }
        }

        if ($commissions !== 0) {
            $userStore = UserStore::where('ref_code','=',$ref_code)->first();
            UserVentureCommission::create(['user_id' => $userStore->user_id, 'commission' => $commissions]);

            $user = User::find($userStore->user_id);
            $message = "Dear $user->name,<br> You have a new commission of {$commissions} from product purchase. <br/>
            We will adhere to our settlement terms. <br/> Congratulations! <br/><br/> <b>Startev Team</b>";
            $mailContent = [
                'message' => $message,
                'to' => $user->email,
                'subject' => "New Commission Credit Alert"
            ];

            dispatch(new SendEmailNotification($mailContent));
        }
    }

    public function validateCode( Request $request )
    {
        $data = $request->all();

        if (UserStore::where('ref_code','=', $data['code'])->exists()) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => "Invalid Referral Code"]);
    }
}
