<?php

namespace App\Http\Controllers;

use App\Repositories\ProcessBusinessPayoutData;
use App\Repositories\ProcessStorePayout;
use App\Repositories\RequestBusinessPayout;
use App\Repositories\StoreIncrement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CronController extends Controller
{
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

    }
}
