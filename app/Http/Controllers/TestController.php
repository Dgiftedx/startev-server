<?php

namespace App\Http\Controllers;

use App\Jobs\sendMessageCampaign;
use App\Models\Feed;
use App\Models\User;
use App\Repositories\OrderTransaction;
use App\Repositories\PayStackVerifyTransaction;
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

    public function runTransactions( Request $request )
    {

        $reference = '45faf737-7a71-a107-7a89-fffccedc09b3';
        //use transaction reference to get payStack charge and percentage
        $payStackCharge = (new PayStackVerifyTransaction)->verify($reference);

        //Pile request parameters
        $params = [
            'amountTotal' => 100000,
            'starTev' => 5,
            'commission' => 4,
            'delivery' => 500,
            'payStack' => $payStackCharge //percent
        ];

        //Perform transaction breakdown
        $result = (new OrderTransaction($params))->calculate();

        //Pile response parameters
        $response = [
            'success' => true,
            'message' => 'reached',
            'result' => $result
        ];

        //return response
        return response()->json($response);
    }



}
