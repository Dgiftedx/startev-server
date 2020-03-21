<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Setting;
use App\Repositories\FetchBanks;
use Illuminate\Support\Facades\Input;

class SettingsController extends Controller
{


    protected $settings;


    public function __construct( Setting $setting )
    {
        $this->settings = $setting;
    }



    public function siteData()
    {
        $siteData = Setting::all();
        $banksCount = Bank::count();
        return response()->json(['success' => true, 'banksCount' => $banksCount, 'siteData' => $siteData]);
    }


    public function reloadBanks()
    {
        $banks = (new FetchBanks)->all();

        if(count($banks) > 0) {
            (new Bank)->query()->truncate();

            foreach($banks as $bank) {
                Bank::create([
                    'name' => $bank['name'],
                    'slug' => $bank['slug'],
                    'code' => $bank['code'],
                    'long_code' => $bank['longcode'],
                    'gateway' => $bank['gateway']??''
                ]);
            }
        }

        $banksCount = Bank::count();

        return response()->json(['success' => true, 'banksCount' => $banksCount]);
    }


    public function updatesideData($id, $value)
    {
//        $data = Input::all();
//        dd($id, $value);
        $setting = Setting::find($id);
        $setting->value = $value;

        if($setting->update()){
            return response()->json('success', true);
        }else{
            return response()->json('success', true);
        }
    }
}
