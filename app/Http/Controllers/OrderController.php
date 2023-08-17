<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    //

    public function index(): \Illuminate\Http\JsonResponse
    {
        try{
            $user = Auth::user();
            $orders = Order::where('user_id', $user->id)->get();
            return response()->json([
                'data' => $orders
            ]);
        }catch (\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        try{
            $validator = Validator::make($request->all(), [
                'total' => 'required|numeric',
                'address' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'products' => 'required|array',
                'products.*.id' => 'required|numeric|exists:products,id',
                'products.*.amount' => 'required|numeric',
            ]);

            if($validator->fails()){
                return response()->json($validator->errors()->first(), 400);
            }

            DB::beginTransaction();

            $user = Auth::user();

            $order = Order::create([
                'user_id' => $user->id,
                'total' => $request->total,
                'address' => $request->address,
                'phone' => $request->phone,
            ]);

            foreach ($request->products as $product){
                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $product['id'],
                    'amount' => $product['amount'],
                ]);
            }
            DB::commit();
            return response()->json([
                'message' => 'Order created successfully',
            ]);
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
