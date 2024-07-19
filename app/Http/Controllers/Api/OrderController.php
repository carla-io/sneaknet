<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
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
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function handle($request, Closure $next)
{
    if (!$request->user()) {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }

    return $next($request);
}


    public function store(Request $request)
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
            DB::beginTransaction();

            foreach ($request->cart as $item) {
                $order = new Order();
                $order->user_id = $user->id;
                $order->product_name = $item['product_name'];
                $order->quantity = $item['quantity'];
                $order->total_price = $item['quantity'] * $item['price']; // Calculate total price per item
                $order->status = 'Pending'; // Default status
                $order->save();

                // Optionally, you can store order items separately if needed
                // $orderItem = new OrderItem();
                // $orderItem->order_id = $order->id;
                // $orderItem->product_id = $item['product_id'];
                // $orderItem->quantity = $item['quantity'];
                // $orderItem->price = $item['price'];
                // $orderItem->save();
            }

            DB::commit();

            return response()->json(['message' => 'Orders created successfully!'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to create orders. ' . $e->getMessage()], 500);
        }
    }
}
