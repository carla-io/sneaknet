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
        $cart = $request->input('cart');
        
        foreach ($cart as $item) {
            Order::create([
                'username' => $item['username'],
                'email' => $item['email'],
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'total' => $item['price'] * $item['quantity']
            ]);
        }
        
        return response()->json(['message' => 'Checkout successful']);
    }
}
