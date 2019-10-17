<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            $path = public_path() . "/storage/uploads/" . $query['file'];
            $headers = array();

            if (Storage::disk('public')->exists("/uploads/" . $query['file'])) {
                return response()->download( $path, $query['file'], $headers);
            }

            return view('errors.404');
        }

        $path = public_path() . "/storage/publication/header/" . $query['image'];

        if (Storage::disk('public')->exists("/publication/header/". $query['image'])) {
            return response()->download( $path );
        }
        return view('errors.404');
    }
}
