<?php

namespace App\Http\Controllers;

use App\Jobs\sendMessageCampaign;
use App\Models\Feed;
use App\Models\User;
use App\Repositories\OrderTransaction;
use App\Repositories\PayStackVerifyTransaction;
use App\Repositories\ProcessBusinessPayout;
use App\Repositories\ProcessBusinessPayoutData;
use App\Repositories\ProcessStorePayout;
use App\Repositories\RequestBusinessPayout;
use App\Repositories\StoreIncrement;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request;

class TestController extends Controller
{


    protected $base_url;

    public function __construct()
    {
        $this->base_url = url('/');
    }

    public function sendMail()
    {

        $mailContent['message'] = "This is a testing mail";
        $mailContent['subject'] = "Testing";
        $mailContent['recipients'] = ['olubunmivictor6@gmail.com'];
        dispatch(new sendMessageCampaign($mailContent));

        return "success";
    }

    public function payBusiness()
    {
        /**
         * Gathers all business orders that has been
         * delivered  but yet paid, with reference
         * to it's settlement split.
         * group and save batch for bulk payout
         */
          $process = (new RequestBusinessPayout)->run();

         /**
          * Process the bulk payout taking values from business
          * settlement batches.
          * Only process if there are settlements
          */

          if (is_array($process) && count($process) > 0) {
              $success = (new ProcessBusinessPayoutData)->payout($process);
          }else{
              $success = ['message' => "No settlement found for payout"];
          }

          Log::info($success);

//          return response()->json($success);
    }


    public function payStores()
    {
        /**
         * Increment days count on all active settlements.
         * Gathers all store that has been
         * due for payout  but yet paid, with reference
         * to it's settlement split.
         * group and save batch for bulk payout
         */
        $payoutIds = (new StoreIncrement)->run();


        /**
         * Process the bulk payout taking values from store
         * settlement batches.
         * Only process if there are settlements
         */
        if (is_array($payoutIds) && count($payoutIds) > 0) {
            $success = (new ProcessStorePayout)->payout($payoutIds);
        }else{
            $success = ['message' => "No settlement found for payout"];
        }

        Log::info($success);

//        return response()->json($success);
    }

    public function verifyTransaction($ref)
    {
        $payStackChargeVerify = (new PayStackVerifyTransaction)->verify($ref,1);
        dd($payStackChargeVerify);
    }



}
