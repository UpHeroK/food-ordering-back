<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    //

    public function index(): \Illuminate\Http\JsonResponse
    {
        try{
            $products = Product::all();
            return response()->json([
                'data' => $products
            ]);
        }catch (\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
