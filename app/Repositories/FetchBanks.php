<?php

namespace App\Repositories;


use GuzzleHttp\Client;

class FetchBanks
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
     * Make request to payStack endpoint to list banks
     *
     * @param [type] $reference
     * @return mixed
     */
    public function all()
    {
        $response = $this->makeRequest();
        return $this->filterResponse($response);
    }

    private function makeRequest()
    {
        $requestUrl = env('PAYSTACK_ENDPOINT') . "bank?perPage=" . 500;
        try{
            $response = $this->client->request("GET", $requestUrl, ['headers' => ['Authorization' => 'Bearer '. env('PAYSTACK_SECRET_LIVE')]]);
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
