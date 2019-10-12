<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\SendConfirmNotification;
use App\Models\Admin\Admin;
use App\Models\Business\UserBusinessOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BatchOrder;
use App\Models\Store\UserVentureOrder;

class OrdersController extends Controller
{


    public function index( $type )
    {
        $query['status'] = '';

        /**
         * First, if query status is new,
         * then we fetch data from the batch to business orders
         * as needed, but if not, we fetch each item separately and then
         * group by batch_id with which we can use to get logged batch.
         */

        if($type === 'new') {
            //fetch result using the parameters
            $orders = BatchOrder::with(['store','buyer','ordersBusiness','ordersBusiness.venture','ordersBusiness.mainProduct'])->get();
            return response()->json(['success' => true, 'orders' => $orders]);
        }

        switch ($type) {
            case "confirmed":
                $query['status'] = "confirmed";
                break;
            case "shipped":
                $query['status'] = "shipped";
                break;

            case "delivered":
                $query['status'] = "delivered";
                break;

            case "cancelled":
                $query['status'] = "cancelled";
                break;
        }

        // $orders = [];

        // UserBusinessOrder::with(['store','mainProduct','buyer','venture'])->byFilter($query)->get()
        // ->mapToGroups(function( $item ) use (&$orders) {

        //     $orders[$item->batch_id][] = $item;
        //     return [];
        // });

        $orders =  UserBusinessOrder::with(['store','mainProduct','buyer','venture','settlement'])->byFilter($query)->get();

        return response()->json(['success' => true, 'orders' => $orders]);

    }



    public function confirmOrder( $id )
    {
        $order = UserBusinessOrder::find($id);
        $order->update(['status' => 'shipped']);

        $ventureOrder = UserVentureOrder::with(['store.user','buyer','venture'])->where('identifier','=',$order->identifier)->first();
        $ventureOrder->update(['status' => 'shipped']);

        $batch = BatchOrder::where('batch_id','=', $order->batch_id)->first();
        $batch->update(['status' => 'shipped']);
        /**
         * Wherever we need to send the payload to for delivery,
         * it's done below and success response is returned to the server
         */
        $this->sendShippingMails($ventureOrder,$ventureOrder->batch_id,$ventureOrder->identifier);


        return response()->json(['success' => true ]);
    }


    public function finalizeOrder($id)
    {
         //set status of all others to delivered;
        $businessOrder = UserBusinessOrder::find($id);
        $businessOrder->update(['status' => 'delivered']);

        $ventureOrder = UserVentureOrder::with(['store.user','buyer','venture'])->where('identifier', '=', $businessOrder->identifier)->first();
        $ventureOrder->update(['status' => 'delivered']);
        $this->sendDeliveryConfirmationMails($ventureOrder,$ventureOrder->batch_id,$ventureOrder->identifier);

        return response()->json(['success' => true ]);
    }

    private function sendShippingMails($recipients, $batch_id, $order_id)
    {
        //First mail the buyer with items
        $mailContent = [
            'email' => $recipients->buyer->email,
            'name' =>  $recipients->buyer->name,
            'subject' => "Your order has been dispatched!",
            'message'=>"Hi <strong>{$recipients->buyer->name}</strong>, <br> Your Order #<strong>{$order_id}</strong> has been DISPATCHED! It should arrive any moment.",
            'role' => 'buyer',
            'base_url' => env('APP_BASE_URL','https://app.startev.africa'),
        ];



        //send to student
        dispatch( new SendConfirmNotification($mailContent));
        //Prepare store contents

        $msg="Hi {$recipients->store->user->name}. <br> The order  #<strong>{$order_id}</strong> from your store has been DISPATCHED!";
        $msg.="<h3>Order ID: {$order_id}</h3>";
        $msg.="<h3>Batch ID: {$batch_id}</h3>";
        $msg.="<h3>Store: {$recipients->store->store_name}</h3>";
        $msg.="<h3>Venture: {$recipients->venture->venture_name}</h3>";
        $msg.="<h3>Buyer: {$recipients->buyer->name}</h3>";
        $mailContent['email'] = $recipients->store->user->email;
        $mailContent['name'] = $recipients->store->store_name;
        $mailContent['buyer'] = $recipients->buyer->name;
        $mailContent['subject'] = "Order Dispatch Alert :: Startev Africa";
        $mailContent['role'] = 'store';
        $mailContent['message'] = $msg;
        $mailContent['base_url'] = env('APP_BASE_URL','https://app.startev.africa').'/venture-dashboard';
        //send to student
        dispatch( new SendConfirmNotification($mailContent));
        unset($mailContent['base_url']);

        //What of the Store administrators
        Admin::all()
            ->mapToGroups(function ($admin)use(&$mailContent,$recipients,$order_id,$batch_id){
                $msg="Hi {$admin->name}. <br> An order #<strong>{$order_id}</strong> from  <strong>{$recipients->store->store_name}</strong> has been DISPATCHED! Kindly login and process Delivery";
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
        //All mail sent. Don't bother about the memory consumption. Job things!

    }
    private function sendDeliveryConfirmationMails($recipients, $batch_id, $order_id)
    {
        //First mail the buyer with items
        $mailContent = [
            'email' => $recipients->buyer->email,
            'name' =>  $recipients->buyer->name,
            'subject' => "Your order has been completed!",
            'message'=>"Hi <strong>{$recipients->buyer->name}</strong>, <br> Your Order #<strong>{$order_id}</strong> has been Delivered! <br>This Transaction has been marked as COMPLETED. <br>For complaints or enquiry on this transaction, kindly send a mail to <a href='mailto:info@startev.africa'>info@startev.africa</a>",
            'role' => 'buyer',
            'base_url' => env('APP_BASE_URL','https://app.startev.africa'),
        ];



        //send to student
        dispatch( new SendConfirmNotification($mailContent));
        //Prepare store contents

        $msg="Hi {$recipients->store->user->name}. <br> The order  #<strong>{$order_id}</strong> from your store has been COMPLETED!";
        $msg.="<h3>Order ID: {$order_id}</h3>";
        $msg.="<h3>Batch ID: {$batch_id}</h3>";
        $msg.="<h3>Store: {$recipients->store->store_name}</h3>";
        $msg.="<h3>Venture: {$recipients->venture->venture_name}</h3>";
        $msg.="<h3>Buyer: {$recipients->buyer->name}</h3>";
        $mailContent['email'] = $recipients->store->user->email;
        $mailContent['name'] = $recipients->store->store_name;
        $mailContent['buyer'] = $recipients->buyer->name;
        $mailContent['subject'] = "Order Delivery Alert :: Startev Africa";
        $mailContent['role'] = 'store';
        $mailContent['message'] = $msg;
        $mailContent['base_url'] = env('APP_BASE_URL','https://app.startev.africa').'/venture-dashboard';
        //send to student
        dispatch( new SendConfirmNotification($mailContent));
        unset($mailContent['base_url']);

        //What of the Store administrators
        Admin::all()
            ->mapToGroups(function ($admin)use(&$mailContent,$recipients,$order_id,$batch_id){
                $msg="Hi {$admin->name}. <br> An order #<strong>{$order_id}</strong> from  <strong>{$recipients->store->store_name}</strong> has been COMPLETED! Transaction Reconciliation has been initialized. <br><br><strong>CONGRATULATIONS!</strong>";
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
        //All mail sent. Don't bother about the memory consumption. Job things!

    }

}
