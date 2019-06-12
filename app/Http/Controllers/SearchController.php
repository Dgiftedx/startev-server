<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Searchable\Search;

class SearchController extends Controller
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
        $searchTerms = $request->input('query');

        $searchResults = (new Search())
            ->registerModel(Business::class, 'name')
            ->registerModel(User::class, 'name')
            ->registerModel(Feed::class, 'title')
            ->perform($searchTerms);

        return response()->json($searchResults);
    }
}
