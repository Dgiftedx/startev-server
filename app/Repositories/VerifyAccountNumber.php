<?php

namespace App\Repositories;


use GuzzleHttp\Client;

class VerifyAccountNumber
{

    /**
     *
     * @var [type]
     */
    protected $client;


    /**
     * FetchBanks Constructor
     */
    public function __construct()
    {
        //Initialize new guzzle http client
        $this->client = (new Client);
    }

    /**
     * Make request to payStack endpoint to verify account number
     *
     * @param [type] $reference
     * @return mixed
     */
    public function verify($accountNumber, $bankCode)
    {
        $response = $this->makeRequest($accountNumber, $bankCode);
        return $this->filterResponse($response);
    }

    private function makeRequest($accountNumber, $bankCode)
    {
        $requestUrl = env('PAYSTACK_ENDPOINT') . "bank/resolve?account_number={$accountNumber}&bank_code={$bankCode}";
        try{
            $response = $this->client->request("GET", $requestUrl, ['headers' => ['Authorization' => 'Bearer '. env('PAYSTACK_SECRET_TEST')]]);
            $response = json_decode($response->getBody()->getContents(), true);
        }catch (\Exception $e){
            //just return the error message
            return $e->getMessage();
        }

        return $response;
    }

    /**
     * Filter response to get needed parameters.
     *
     * @param [type] $response
     * @return void
     */
    private function filterResponse($response)
    {
        if($response['status']) {
            return $response['data'];
        }
        return [];
    }


}
