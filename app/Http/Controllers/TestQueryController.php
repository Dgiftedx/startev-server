<?php

namespace App\Http\Controllers;

use App\Models\Query;
use Illuminate\Http\Request;

class TestQueryController extends Controller
{
    public function buildCountries(){
        $countries = Query::orderBy('id','asc')->get();

        $query = '';

        foreach ($countries as $country) {
            $query .= "Country::create([ <br/>";
            $query .= "'name' => '{$country->name}', <br/>";
            $query .= "'iso3' => '{$country->iso3}', <br/>";
            $query .= "'currency' => '{$country->currency}', <br/>";
            $query .= "]);<br/>";
        }

        echo $query;
    }

    public function buildStates(){
        $states = Query::orderBy('id','asc')->get();

        $query = '';

        foreach ($states as $state) {
            $query .= "State::create([ <br/>";
            $query .= "'country_id' => {$state->country_id}, <br/>";
            $query .= "'name' => '{$state->name}', <br/>";
            $query .= "'flag' => {$state->flag}, <br/>";
            $query .= "]);<br/>";
        }

        echo $query;
    }

    public function buildCities()
    {
        $cities = Query::orderBy('id','asc')->get();

        $query = '';

        foreach ($cities as $city) {
            $query .= "City::create([ <br/>";
            $query .= "'country_id' => {$city->country_id}, <br/>";
            $query .= "'state_id' => {$city->state_id}, <br/>";
            $query .= "'name' => '{$city->name}', <br/>";
            $query .= "'flag' => {$city->flag}, <br/>";
            $query .= "]);<br/>";
        }

        echo $query;
    }
}
