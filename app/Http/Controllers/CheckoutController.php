<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Cart;
// use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function proceedToCheckout(Request $request)
    {
        $cartItems = Cart::where('user_id', Auth::id())->get();

        foreach ($cartItems as $item) {
            Order::create([
                'user_id' => Auth::id(),
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'total_price' => $item->total,
                'status' => 'pending'
            ]);
        }

        Cart::where('user_id', Auth::id())->delete();

        return response()->json(['success' => 'Order placed successfully!']);
    }
}
