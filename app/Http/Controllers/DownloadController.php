<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadController extends Controller
{

    protected $url;

    public function __construct()
    {
        $this->url = url('/');
    }

    public function download( Request $request )
    {
        $query = $request->all();

        if (isset($query['file'])) {
            $path = public_path() . "/storage/uploads/" .  str_replace("-"," ", $query['file']);

            $headers = array();
            return response()->download( $path, $query['file'], $headers);
        }


        $path = str_replace($this->url, "", $query['image']);
        $headers = [];
        return response()->download( $path, $query['image'], $headers);
    }
}
