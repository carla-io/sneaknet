<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

class CartController extends Controller
{

    public function index()
    {
        $cartItems = [];
        return view('cart');
    }

    public function checkout(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'cart' => 'required|array',
            'cart.*.product_name' => 'required|string',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Proceed with creating orders and order items
        try {
            foreach ($request->cart as $item) {
                Order::create([
                    'user_id' => $user->id,
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'total_price' => $item['quantity'] * $item['price'], // Calculate total price per item
                    'status' => 'Pending', // Default status
                ]);
            }

            return response()->json(['message' => 'Checkout successful!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to checkout. ' . $e->getMessage()], 500);
        }
    }
}
