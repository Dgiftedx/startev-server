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
        //  $process = (new RequestBusinessPayout);
        $process = [1];

         /**
          * Process the bulk payout taking values from business
          * settlement batches.
          */

          $success = (new ProcessBusinessPayoutData)->payout($process);

          dd($success);

         return response()->json($process);
    }


    public function payStores()
    {

        // $increment = (new StoreIncrement)->run();
        $payoutIds = [1,2];

        $success = (new ProcessStorePayout)->payout($payoutIds);

        dd($success);

         return response()->json($success);
    }



}
