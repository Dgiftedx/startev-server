<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Searchable\Search;

class ApiSearchController extends Controller
{
    /**
     * SearchController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function search( Request $request )
    {
        $searchTerms = $request->get('query');

        $searchResults = (new Search())
            ->registerModel(Business::class, 'name')
            ->registerModel(User::class, 'name')
            ->registerModel(Feed::class, 'title')
            ->perform($searchTerms);

        return response()->json($searchResults);
    }
}
