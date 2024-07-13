<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Spatie\Searchable\Search;
use App\Http\Controllers\Controller;
use Spatie\Searchable\SearchResult;
// use App\Http\Controllers\Api\Product;
use App\Models\Product;

class SearchController extends Controller
{
    // public function search(Request $request)
    // {
    //     // Assuming you want to perform a search based on a query parameter
    //     $query = $request->input('query');

    //     // Perform the search
    //     $searchResults = (new Search())
    //         ->search($query);

    //     // Return the search results
    //     return response()->json([
    //         'results' => $searchResults->map(function (SearchResult $result) {
    //             // Use the methods provided by SearchResult
    //             return [
    //                 'model' => $result->model, // Get the model
    //                 'title' => $result->title, // Get the title or other relevant attribute
    //             ];
    //         })
    //     ]);
    // }

    public function search(Request $request)
{
    $query = $request->input('query');
    $products = Product::where('product_name', 'like', "%{$query}%")->get(); // Search query

    return response()->json([
        'data' => $products
    ]);
}

}
