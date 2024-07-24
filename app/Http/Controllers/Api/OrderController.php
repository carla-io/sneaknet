<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $orders = Order::select('orders.id', 'users.name as username', 'orders.product_name', 'orders.quantity', 'orders.total_price', 'orders.status')
            ->join('users', 'orders.user_id', '=', 'users.id');

        return DataTables::of($orders)
            ->make(true);
    }

    public function checkout(Request $request)
    {
        // $user = Auth::user();
        $user = Auth::user();
        $cart = $request->input('cart');

        \Log::info('Authenticated User:', ['user' => $user]);
        \Log::info('Cart Items:', ['cart' => $request->input('cart')]);


        if (!$cart || !is_array($cart)) {
            return response()->json(['error' => 'Invalid cart data'], 400);
        }

        foreach ($cart as $item) {
            if (!isset($item['product_name'], $item['quantity'], $item['price'])) {
                return response()->json(['error' => 'Incomplete item data'], 400);
            }

            // Calculate total price
            $totalPrice = $item['quantity'] * $item['price'];

            // Save order to database
            Order::create([
                'user_id' => $user->id,
                'product_name' => $item['product_name'],
                'quantity' => $item['quantity'],
                'total_price' => $totalPrice,
                'status' => 'Pending',
            ]);
        }

        return response()->json(['message' => 'Checkout successful'], 200);
    }

    public function store(Request $request)
    {
        $cartItems = Cart::where('user_id', Auth::id())->get();

        foreach ($cartItems as $item) {
            $product = Product::find($item->product_id);

            Order::create([
                'user_id' => Auth::id(),
                'product_name' => $product->name, // Assuming product has a 'name' attribute
                'quantity' => $item->quantity,
                'total_price' => $item->total,
                'status' => 'Pending'
            ]);
        }

        Cart::where('user_id', Auth::id())->delete();

        return response()->json(['success' => 'Order placed successfully!']);
    }

//     public function index(Request $request)
// {
//     $orders = Order::select('orders.id', 'users.name as username', 'orders.product_name', 'orders.quantity', 'orders.total_price', 'orders.status')
//         ->join('users', 'orders.user_id', '=', 'users.id');

//     return DataTables::of($orders)
//         ->make(true);
// }

// public function createOrder(Request $request)
// {
//     $validated = $request->validate([
//         'cart' => 'required|array',
//         'cart.*.product_id' => 'required|integer|exists:products,id',
//         'cart.*.quantity' => 'required|integer|min:1',
//         'cart.*.price' => 'required|numeric',
//     ]);

//     // Process the order here
//     foreach ($validated['cart'] as $cartItem) {
//         Order::create([
//             'user_id' => auth()->id(),
//             'product_id' => $cartItem['product_id'],
//             'quantity' => $cartItem['quantity'],
//             'total_price' => $cartItem['quantity'] * $cartItem['price'],
//             'status' => 'Pending',
//         ]);
//     }

//     return response()->json(['message' => 'Order created successfully']);
// }


//     public function store(Request $request)
// {
//     // Get the authenticated client's ID
//     $userId = Auth::id();

//     // Validate incoming request
//     $validatedData = $request->validate([
//         'category_id' => 'required|exists:categories,id',
//         'item_id' => 'required|exists:items,id',
//         'order_total_quantity' => 'required|integer',
//         'order_total' => 'required|numeric',
//         'order_status' => 'required|string|in:Pending,Completed,Cancelled', // Ensure this field is included in the request
//     ]);

//     // Create a new order
//     $order = new Order();
//     $order->user_id = $userId; // Assign the authenticated client's ID
//     $order->category_id = $validatedData['category_id'];
//     $order->item_id = $validatedData['item_id'];
//     $order->order_total_quantity = $validatedData['order_total_quantity'];
//     $order->order_total = $validatedData['order_total'];
//     $order->order_status = $validatedData['order_status'];
//     $order->order_date = now();

//     // Save the order
//     $order->save();

//     return response()->json(['message' => 'Order created successfully!'], 201);
// }



    // public function store(Request $request)
    // {
    //     // Validate incoming request
    //     $validator = Validator::make($request->all(), [
    //         'user_id' => 'required|integer|exists:users,id',
    //         'cart' => 'required|array',
    //         'cart.*.product_name' => 'required|string',
    //         'cart.*.quantity' => 'required|integer|min:1',
    //         'cart.*.price' => 'required|numeric|min:0',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()->first()], 400);
    //     }

    //     // Get the authenticated user
    //     $user = Auth::user();

    //     if (!$user) {
    //         return response()->json(['message' => 'Unauthenticated.'], 401);
    //     }

    //     // Begin a database transaction
    //     try {
    //         DB::beginTransaction();

    //         // Loop through the cart items and save each one
    //         foreach ($request->cart as $item) {
    //             $order = new Order();
    //             $order->user_id = $request->user_id; // Use user ID from request or Auth user
    //             $order->product_name = $item['product_name'];
    //             $order->quantity = $item['quantity'];
    //             $order->total_price = $item['quantity'] * $item['price']; // Calculate total price per item
    //             $order->status = 'Pending'; // Default status
    //             $order->save();
    //         }

    //         // Commit the transaction
    //         DB::commit();

    //         return response()->json(['message' => 'Orders created successfully!'], 201);
    //     } catch (\Exception $e) {
    //         // Rollback the transaction on failure
    //         DB::rollback();
    //         return response()->json(['error' => 'Failed to create orders. ' . $e->getMessage()], 500);
    //     }
    // }

    
}
