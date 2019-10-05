<?php
namespace App\Repositories;

use App\Models\BusinessSettlementBatch;
use GuzzleHttp\Client;

class ProcessBusinessPayoutData
{

    /**
     *
     * @var [type]
     */
    protected $client;

    public function __construct()
    {
        //Initialize new guzzle http client
        $this->client = (new Client);
    }


    public function payout($payload)
    {
        $batches = BusinessSettlementBatch::with('business')->whereIn('id', $payload)->get();

        foreach($batches as $batch) {

            //Only if user has account details added.
            if(!is_null($batch->business->payment_id)) {
                $payload = [
                    'source' => 'balance',
                    'amount' => ($batch->total * 100),
                    'recipient' => $batch->business->payment_id
                ];

                $pay = $this->makeRequest($payload);

                if($pay && isset($pay['status']) && $pay['status']) {

                    BusinessSettlementBatch::find($batch->id)->update(['status' => 'processed']);
                    return ['success' => true ];
                }

                //there is an error and could not be processed
                return $pay;

            }else{
                //remove batch.
                BusinessSettlementBatch::find($batch->id)->delete();
            }
        }

        return[];
    }

    private function makeRequest($singlePayout)
    {
        $requestUrl = env('PAYSTACK_ENDPOINT') . "transfer/";
        try{
            $response = $this->client->request("POST", $requestUrl, ['headers' => ['Authorization' => 'Bearer '. env('PAYSTACK_SECRET_TEST')], 'form_params' => $singlePayout]);
            $response = json_decode($response->getBody()->getContents(), true);
        }catch (\Exception $e){
            //just return the error message
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ];
        }

        return $response;

//        return $this->filterResponse($response);
    }



    private function filterResponse($response)
    {
        if($response['status']) {
            return $response['data'];
        }

        return [];
    }


}
