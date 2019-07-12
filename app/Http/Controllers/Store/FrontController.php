<?php

namespace App\Http\Controllers\Store;

use App\Models\Business\UserBusinessProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FrontController extends Controller
{

    protected $productModel;

    public function __construct( UserBusinessProduct $products )
    {
        $this->productModel = $products;
    }

    public function all()
    {
        $products = $this->productModel->orderBy('created_at','desc')->paginate(10);
        return response()->json(['success' => true, 'products' => $products]);
    }

    public function newProducts()
    {
        $query = [
            'stock_status' => 'inStock'
        ];
        $products = $this->productModel->orderBy('created_at','desc')->byFilter($query)->take(8)->get();
        return response()->json(['success' => true, 'products' => $products]);
    }
}
