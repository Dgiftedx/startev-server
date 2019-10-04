<?php
namespace App\Repositories;

use App\Models\BusinessSettlementBatch;
use App\Models\StoreSettlementBatch;
use GuzzleHttp\Client;

class ProcessStorePayout
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
        $batches = StoreSettlementBatch::with('store')->whereIn('id', $payload)->get();

        foreach($batches as $batch) {

            //Only if user has account details added.
            if(!is_null($batch->store->payment_id)) {
                $payload = [
                    'source' => 'balance',
                    'amount' => ($batch->total * 100), //in kobo
                    'recipient' => $batch->store->payment_id
                ];

                $pay = $this->makeRequest($payload);

                if($pay && isset($pay['status'])) {
                    //payout successful
                    dd($pay);
                }

                //payout failed with response code 400
                dd($pay);

            }else{
                //remove batch if there is no active account number
                StoreSettlementBatch::find($batch->id)->delete();
            }
        }
    }

    private function makeRequest($singlePayout)
    {
        $requestUrl = env('PAYSTACK_ENDPOINT') . "transfer";
        try{
            $response = $this->client->request("POST", $requestUrl, ['headers' => ['Authorization' => 'Bearer '. env('PAYSTACK_SECRET_TEST')], 'form_params' => $singlePayout]);
            $response = json_decode($response->getBody()->getContents(), true);
        }catch (\Exception $e){
            //just return the error message
            return $e->getMessage();
        }

        return $response;

        return $this->filterResponse($response);
    }



    private function filterResponse($response)
    {
        if($response['status']) {
            return $response['data'];
        }

        return [];
    }


}
