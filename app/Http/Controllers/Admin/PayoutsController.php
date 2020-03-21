<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PendingSettlements;
use App\Jobs\SendConfirmNotification;
use App\Models\Admin\Admin;
use App\Models\Store\UserVentureOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Business\UserBusinessOrder;
use App\Models\BusinessSettlementBatch;
use App\Models\BusinessVenture;
use App\Models\StoreSettlementBatch;
use App\Models\Transaction\DeliveryCharge;
use Maatwebsite\Excel\Facades\Excel;

class PayoutsController extends Controller
{

    public function index(Request $request)
    {
        $data = $request->all();

        $result = [];

        switch ($data['query']) {
            case 'business':
                $result = BusinessSettlementBatch::with(['business', 'business.user'])->orderBy('id', 'desc')->get();
                break;

            case 'store':
                $result = StoreSettlementBatch::with(['store', 'store.user'])->orderBy('id', 'desc')->get();
                break;
            /* $table->integer('active')->default(1); //At least one active settlement batch
                        $table->string('status')->default('pending'); //processed if settled*/
            case 'all_pending':
                $result['biz'] = BusinessSettlementBatch::with(['business', 'business.user'])
                    ->byFilter(['active' => 1, 'status' => 'pending'])
                    ->orderBy('id', 'desc')->get();
                $result['store'] = StoreSettlementBatch::with(['store', 'store.user'])
                    ->byFilter(['active' => 1, 'status' => 0])
                    ->orderBy('id', 'desc')->get();

                break;

            case 'delivery':
                $result = [];
                break;
        }


        return response()->json(['success' => true, 'payouts' => $result]);
    }


    public function items(Request $request)
    {
        $data = $request->all();

        $result = [];

        switch ($data['type']) {
            case 'business':
                $settlement = UserBusinessOrder::with(['venture', 'settlement.order'])
                    ->byFilter(['business_settlement_batch_id' => $data['id']])->get();
                if (!is_null($settlement))
                    $result = $settlement;
                break;

            case 'store':
                $settlement = UserVentureOrder::with(['store', 'venture', 'settlement.order'])
                    ->byFilter(['store_settlement_batch_id' => $data['id']])->get();
                if (!is_null($settlement))
                    $result = $settlement;
                break;
            case 'delivery':
                $result = [];
                break;
        }


        return response()->json(['success' => true, 'items' => $result]);
    }

    public function exportPendingPayouts()
    {
        $result['biz'] = BusinessSettlementBatch::with(['business', 'business.user'])->orderBy('id', 'desc')->get();
        $result['store'] = StoreSettlementBatch::with(['store', 'store.user'])->orderBy('id', 'desc')->get();
        return Excel::download(new PendingSettlements($result), 'startev_settlement_list.xlsx');
    }

    public function markPayoutAsSettled(Request $request)
    {
        $data = $request->all();
        $result = null;
        switch ($data['type']) {
            case 'business':
                $result = BusinessSettlementBatch::with(['business.user'])->orderBy('id', 'desc')->find($data['id']);
                if (is_null($result))
                    return response()->json(['error' => "Business Settlement Entry not found"]);
                $result->userBusinessOrders->mapToGroups(function ($order) {
                    $settlement = $order->settlement;
                    $settlement->update(['business_settled' => 1, 'status' => 'paid']);
                    return [];
                });

                $result->update(['status' => 1, 'active' => 0]);

                $msg = "Hi {$result->business->user->name}. <br> You have been settled for the Batch  #<strong>{$result->batch_ref_id}</strong> for {$result->userBusinessOrders->count()} COMPLETED orders!<br><br><strong>CONGRATULATIONS!</strong>";
                $msg .= "<h3>Settlement ID: {$result->batch_ref_id}</h3>";
                $msg .= "<h3>Store: {$result->business->name}</h3>";
                $mailContent['email'] = $result->business->user->email;
                $mailContent['name'] = $result->business->user->name;
                $mailContent['subject'] = "Settlement Alert :: Startev Africa";
                $mailContent['role'] = 'store';
                $mailContent['message'] = $msg;
                $mailContent['base_url'] = env('APP_BASE_URL', 'https://app.startev.africa') . '/store-manager';
                //send to business venture
                dispatch(new SendConfirmNotification($mailContent));
                unset($mailContent['base_url']);
                //send to all admins
                Admin::all()
                    ->mapToGroups(function ($admin) use (&$mailContent, $result) {
                        $msg = "Hi {$admin->name}. <br> <strong>{$result->business->user->name}</strong> has just been settled for Accumulated Settlement ID  <strong>{$result->batch_ref_id}</strong> with {$result->userBusinessOrders->count()} COMPLETED orders! <br><br><strong>CONGRATULATIONS!</strong>";
                        $msg .= "<h3>Settlement ID: {$result->batch_ref_id}</h3>";
                        $msg .= "<h3>Store: {$result->business->name}</h3>";
                        $mailContent['email'] = $admin->email;
                        $mailContent['name'] = $admin->name;
                        $mailContent['message'] = $msg;
                        $mailContent['subject'] = "Order Settlement Alert :: Startev Africa";
                        $mailContent['server_url'] = env('APP_SERVER_URL', 'https://startev.africa');

                        dispatch(new SendConfirmNotification($mailContent));
                        return [];
                    });

                break;

            case 'store':
                $result = StoreSettlementBatch::with(['store', 'store.user'])->orderBy('id', 'desc')->find($data['id']);
                if (is_null($result))
                    return response()->json(['error' => "Store Settlement Entry not found"]);
                $result->userVentureOrders->mapToGroups(function ($order) {
                    $settlement = $order->settlement;
                    $settlement->update(['store_reference_id' => $order->identifier]);
                    $order->userVentureCommission->update(['status'=>'paid']);
                    return [];
                });
                $result->update(['status' => 1, 'active' => 0]);
                $msg = "Hi {$result->store->user->name}. <br> Your Accumulated Commissions have been paid successfully for the Settlement Batch  #<strong>{$result->reference_id}</strong> for {$result->userVentureOrders->count()} COMPLETED orders!";
                $msg .= "<h3>SETTLEMENT ID: {$result->reference_id}</h3>";
                $msg .= "<h3>Store: {$result->store->store_name}</h3>";
                $mailContent['email'] = $result->store->user->email;
                $mailContent['name'] = $result->store->store_name;
                $mailContent['subject'] = "Commission Settlement Alert :: Startev Africa";
                $mailContent['role'] = 'store';
                $mailContent['message'] = $msg;
                $mailContent['base_url'] = env('APP_BASE_URL', 'https://app.startev.africa') . '/venture-dashboard';
                //send to student
                dispatch(new SendConfirmNotification($mailContent));

                $this->handleOfflineSettlementNotification($result);

                unset($mailContent['base_url']);
                //send to all admins
                Admin::all()
                    ->mapToGroups(function ($admin) use (&$mailContent, $result) {
                        $msg = "Hi {$admin->name}. <br> <strong>{$result->store->user->name}</strong> has just been settled for Accumulated Settlement ID  <strong>{$result->reference_id}</strong> with {$result->userVentureOrders->count()} COMPLETED orders! <br><br><strong>CONGRATULATIONS!</strong>";
                        $msg .= "<h3>Settlement ID: {$result->batch_ref_id}</h3>";
                        $msg .= "<h3>Store: {$result->store->store_name}</h3>";
                        $mailContent['email'] = $admin->email;
                        $mailContent['name'] = $admin->name;
                        $mailContent['message'] = $msg;
                        $mailContent['subject'] = "Order Settlement Alert :: Startev Africa";
                        $mailContent['server_url'] = env('APP_SERVER_URL', 'https://startev.africa');

                        dispatch(new SendConfirmNotification($mailContent));
                        return [];
                    });

                break;
            case 'delivery':
                $result = [];
                break;
        }

        return response()->json(['success' => true]);

    }


    //send push notification to target user if offline
    private  function handleOfflineSettlementNotification($result)
    {

        $pushData['content'] = [
            'data' => ['type'=>PushNotification::$Settlement],
            'title'=>'Commission Settlement Alert :: Startev Africa',
            'body'=>"Your Accumulated Commissions have been paid successfully for the Settlement Batch  #<strong>{$result->reference_id}</strong> for {$result->userVentureOrders->count()} COMPLETED orders!"
        ];

        //send to student
        $pushData['users'][] = $result->store->user->id;

        (new Notification)->sendPush($pushData);

        //send to business
        $pushData['users'][] = $result->venture->business->user->id;

        (new Notification)->sendPush($pushData);

    }
}
